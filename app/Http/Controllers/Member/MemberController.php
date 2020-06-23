<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use MongoDB\BSON\ObjectId;
use Intervention\Image\ImageManagerStatic as Image;
use IIlluminate\Http\UploadedFile;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use Carbon\Carbon;

// Model
use App\Models\Category;
use App\Models\Member;


class MemberController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index_member(){
    $datas = Member::query()->where('status',1)->get();
  }

}
