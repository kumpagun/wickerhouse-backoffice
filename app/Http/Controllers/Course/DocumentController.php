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
use Storage;
use Hashids\Hashids;
// Controller
use App\Http\Controllers\Course\CourseController;
// Model
use App\Models\Course;
use App\Models\Document;

class DocumentController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function get_document($course_id) {
    $data = Document::where('course_id',new ObjectId($course_id))->where('status',1)->first();
    return $data;
  }
  public function update_course($course_id) {
    $course = Course::find($course_id);
    $document = Document::where('course_id',new ObjectId($course_id))->where('status',1)->first();
    if(!empty($document)) {
      $course->have_document = true;
    } else {
      $course->have_document = false;
    }
    $course->save();
  }
  public function document_index(){
    $datas = Document::query()->where('status','!=',0)->get();
    return view('document.document_index',['datas' => $datas]);
  }
  public function document_store(Request $request){
    $id = $request->input('id');
    $course_id = $request->input('course_id');
    $title = $request->input('title');
    $file_zip = $request->file('file_zip');
    $file_pdf = $request->file('file_pdf');
    $file_type = $request->input('file_type');

    if($file_type=='zip') {
      $rules = [
        'title' => 'required',
        'file_type' => 'required',
        'file_zip' => 'required'
      ];
      $file = $file_zip;
    } else if($file_type=='pdf') {
      $rules = [
        'title' => 'required',
        'file_type' => 'required',
        'file_pdf' => 'required'
      ];
      $file = $file_pdf;
    }
    
    $validator = Validator::make($request->all(), $rules);
    if($validator->fails()) {
      return redirect()->back()->withErrors($validator, 'document')->withInput();
    }

    $chk_doc = Document::where('course_id',new ObjectId($course_id))->first();

    $document_paths = [];
    if(!empty($chk_doc)) {
      $document = Document::find($chk_doc->_id);
      if(!empty($document->document_paths)) {
        $document_paths = $document->document_paths;
      }
    } else {
      $document = new Document();
    }
    // UPLOAD FILE STORAGE
    // Storage::disk('public')->put($file, 'Contents');
    $path_file = "public/document/$course_id";
    $path = Storage::putFile($path_file, $file);

    if($file_type=='zip') {
      $document->title = $title;
      $document->document_path = $path;
    } else if($file_type=='pdf') {
      $hashids = new Hashids();
      $data = new \stdClass();
      $data->title = $title;
      $data->path = $path;
      $data->code = $hashids->encode(Carbon::now()->timestamp);
      array_push($document_paths, $data);
      $document->document_paths = $document_paths;
    }

    $document->course_id = new ObjectId($course_id);
    $document->status = 1;
    $document->save();

    // UPDATE COURSE HAVE_DOCUMENT
    $this->update_course($course_id);

    $current_user = Auth::user();
    ActivityLogClass::log('เพิ่มหรือแก้ไข document', new ObjectId($current_user->_id), $document->getTable(), $document->getAttributes(),$current_user->username);
  
    return redirect()->route('course_create', ['id' => $course_id, '#document']);
  }

  public function document_zip_delete($course_id){
    $clear_ep = Document::where('course_id',new ObjectId($course_id))->unset('title')->unset('document_path');
    $document = Document::where('course_id',new ObjectId($course_id))->first();

    $this->update_course($course_id);

    $current_user = Auth::user();
    ActivityLogClass::log('ลบ document zip', new ObjectId($current_user->_id), $document->getTable(), $document->getAttributes(),$current_user->username);

    return redirect()->route('course_create', ['id' => $course_id, '#document']);
  }

  public function document_pdf_delete($course_id,$code){
    $document = Document::where('course_id',new ObjectId($course_id))->first();

    $index_del = 0;
    $document_paths = $document->document_paths;
    foreach($document->document_paths as $index => $value) {
      if($code==$value['code']) {
        $index_del = $index;
      }
    }
    unset($document_paths[$index_del]);

    $document = Document::find($document->_id);
    $document->document_paths = $document_paths;
    $document->save();

    $this->update_course($course_id);

    $current_user = Auth::user();
    ActivityLogClass::log('ลบ document pdf', new ObjectId($current_user->_id), $document->getTable(), $document->getAttributes(),$current_user->username);

    return redirect()->route('course_create', ['id' => $course_id, '#document']);
  }
}
