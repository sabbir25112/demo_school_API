<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Student([
            'firstname' => $row[0],
            'lastname'  => $row[1],
            'age'       => $row[2],
            'gender'    => $row[3],
        ]);
    }
}
