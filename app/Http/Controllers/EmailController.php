<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use MongoDB\BSON\ObjectId;
use Intervention\Image\ImageManagerStatic as Image;
use IIlluminate\Http\UploadedFile;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use Carbon\Carbon;
use ActivityLogClass;
use Auth;
use Mail;

// Models
use App\Models\Member;
use App\Models\Employee;
use App\Models\Employee_vip;

class EmailController extends Controller
{
  public function test() {
    $to_name = 'sorachai to';
    $to_email = 'sorachai.b@mono.co.th';
    $from_name = 'sorachai from';
    $from_email = 'sorachai.b@mono.co.th';
    $data = [
      'name' => 'GUNGUN is HAPPY',
      'body' => 'This is a mail'
    ];
    Mail::send('emails.test', $data, function($message) use ($to_name, $to_email, $from_email) {
      $message->to($to_email, $to_name)->subject('ทดสอบส่งเมล์ SMTP jasmine');
      $message->from($from_email,'Test Mail');
    });
  }
}
