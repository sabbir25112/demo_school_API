<?php

namespace App\Http\Controllers;

use App\Mail\SendOTP;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use JWTAuth;

class AuthController extends Controller
{
    public function sendOTP(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        try  {
            $user = User::getUserByEmail($request->email);
            if (!$user->otp_valid_till || $user->otp_valid_till->isPast()) {
                $otp = generateOTP();
                $user->update([
                    'otp' => $otp,
                    'otp_valid_till' => Carbon::now()->addMinutes(5),
                ]);

                Mail::to($user->email)->send(new SendOTP($otp));
            }
            return response([
                'status' => 200,
                'message' => 'OTP Sent',
            ], 200);
        } catch (\Exception $exception) {
            Log::error($exception);

            return response([
                'status' => 500,
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'otp'   => 'required',
        ]);

        $user = User::getUserByEmail($request->email);

        if (
            $user->otp == $request->otp &&
            $user->otp_valid_till &&
            $user->otp_valid_till->isFuture()
        ) {
            return response([
                'status' => 200,
                'access_token' => JWTAuth::fromUser($user),
                'token_type' => 'bearer',
                'expires_in' => $this->guard()->factory()->getTTL() * 60
            ], 200);
        } else {
            return response([
                'status' => 401,
                'message' => 'OTP Invalid Or Expired',
            ], 401);
        }
    }

    public function guard()
    {
        return Auth::guard();
    }
}
