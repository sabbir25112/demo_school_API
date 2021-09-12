<?php

if (!function_exists('generateOTP')) {

    function generateOTP()
    {
        $otp_length = config('auth.otp_length');
        $max_otp = 10 ** ($otp_length - 1);
        $min_otp = (10 ** $otp_length) - 1;
        return rand($min_otp, $max_otp);
    }
}
