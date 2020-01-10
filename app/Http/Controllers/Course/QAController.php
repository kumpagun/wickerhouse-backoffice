<?php

namespace App\Http\Controllers\Course;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MongoDB\BSON\ObjectId as ObjectId;
use IIlluminate\Http\UploadedFile;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use ActivityLogClass;
use FuncClass;
use File;
use Image;
use URL;
// Controller
use App\Http\Controllers\Course\CourseController;
// Model
use App\Models\Course;
use App\Models\Homework;
use App\Models\HomeworkAnswer;
use App\Models\Category;
use App\Models\Teacher;
use App\Models\Training;


class QAController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function homework_index(){
    $datas = Training::where('status',1)->get();
    return view('homework.homework_index',['datas' => $datas]);
  }
}
