<?php

namespace App\Http\Controllers;

use App\Imports\StudentImport;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function __construct()
    {
        set_time_limit(0);
    }

    public function bulkUpload(Request $request)
    {
        $this->validate($request, [
            'students' => 'required|file|mimes:csv,txt'
        ]);

        try {
             Excel::import(new StudentImport, $request->file('students'));

            return response([
                'message' => 'Student Created'
            ], 201);
        } catch (\Exception $exception) {
            Log::error($exception);

            return response([
                'message' => 'Something went wrong'
            ], 500);
        }
    }

    public function index()
    {
        $students = Student::when(request()->has('firstname'), function($query) {
            $firstname = request()->get('firstname');
            return $query->where('firstname', 'LIKE', "%$firstname%");
        })->when(request()->has('lastname'), function($query) {
            $lastname = request()->get('lastname');
            return $query->where('lastname', 'LIKE', "%$lastname%");
        })->paginate(10);

        return response($students, 200);
    }
}
