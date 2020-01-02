<?php

namespace App\ImportExcels;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\ToModel;

class MembersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {   
        return new Member([
            'employee_id' => $row[1],
            'tinitial' => $row[2],
            'tf_name'     => $row[3],
            'tl_name' => $row[4],
            'emptype' => $row[5],
            'joined_date' => $row[6],
            'workplace'     => $row[7],
            'email' => $row[8],
            'sex' => $row[9],
            'birth_date' => $row[10],
            'health_plan_id'     => $row[11],
            'title_name' => $row[12],
            'division_name'     => $row[13],
            'section_name' => $row[14],
            'dept_name' => $row[15],
            'company_short_name' => $row[16],
            'staff_grade'     => $row[17],
            'job_family' => $row[18],

        ]);
       
    }

}
