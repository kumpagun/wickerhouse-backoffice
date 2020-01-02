<?php

namespace App\Classes;
use Request;
use MongoDB\BSON\ObjectId as ObjectId;
use App\User;
use Maklad\Permission\Models\Role;

use MongoDB\BSON\UTCDateTime as UTCDateTime;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\Teacher;

class TeacherClass
{   
    public function get_name_teacher($teacher_id = ''){
        $name = '-';
        $data = Teacher::find($teacher_id);
        if ($data) {
            $name = $data->name;
        }
        return $name;
    }
}
