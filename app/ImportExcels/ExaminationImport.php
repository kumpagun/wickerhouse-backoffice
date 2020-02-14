<?php

namespace App\ImportExcels;

use App\Models\Examination;
use Maatwebsite\Excel\Concerns\ToModel;

class ExaminationImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {   
      return new Examination([
        'exam_group_id' => $row[1],
        'course_id' => $row[2],
        'question' => $row[3],
        'choice' => $row[4],
        'answer_value' => $row[5],
        'answer_key' => $row[6],
        'status' => $row[7]
      ]);
    }
}
