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
use App\Classes\UploadHandler;
use Hashids\Hashids;

// Controller
use  App\Http\Controllers\Course\HomeworkController;
// Model
use App\Models\Course;
use App\Models\Episode_group;
use App\Models\Episode;

class EpisodeController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function get_episode_group($course_id)
  {
    $data = Episode_group::where('course_id',new ObjectId($course_id))->where('status',1)->orderBy('position')->get();
    return $data;
  }
  public function get_episode($course_id)
  {
    $data = Episode::where('course_id',new ObjectId($course_id))->where('status',1)->get();
    return $data;
  }
  public function episode_group_sortgroup(Request $request) 
  {
    $course_id = $request->input('course_id');
    $episode_group = $request->input('episode_group');

    $count = 0;
    foreach($episode_group as $row) {
      $update = Episode_group::find($row);
      $update->position = $count;
      $update->save();
      $count++;
    }

    return response()->json([
      'status' => 200,
      'message' => 'Success.'
    ]); 
  }

  public function episode_group_create($course_id, $id)
  {
    $episode_group = $this->get_episode_group($course_id);
    $episode_list_active = Episode::where('course_id',new ObjectId($course_id))->whereNull('episode_group_id')->where('status',1)->get();
    $episode_list_selectes = Episode::where('course_id',new ObjectId($course_id))->whereNotNull('episode_group_id')->where('status',1)->get();

    $episode_list_selected = [];
    foreach($episode_list_selectes as $row) {
      array_push($episode_list_selected[$row->episode_group_id], $row->_id);
    }
    
    $data = Episode_group::find($id);
    $datas = [
      'id' => $id,
      'course_id' => $course_id,
      'episode_group' => $episode_group,
      'episode_list_active' => $episode_list_active,
      'episode_list_selected' => $episode_list_selected,
      'data' => $data
    ];
    return view('episode.episode_group_detail', $datas);
  }

  public function episode_update_group_id(Request $request) 
  {
    $episode_group_id = $request->input('episode_group_id');
    $episode = $request->input('episode');

    // $count = 0;
    // foreach($episode_group as $row) {
    //   $update = Episode_group::find($row);
    //   $update->position = $count;
    //   $update->save();
    //   $count++;
    // }

    // return response()->json([
    //   'status' => 200,
    //   'message' => 'Success.'
    // ]); 
  }

  public function episode_group_store(Request $request)
  {
    $course_id = $request->input('course_id');
    $title = $request->input('title');

    $position = Episode_group::where('course_id',new ObjectId($course_id))->where('status',1)->count();

    if(!empty($data['id'])) {
      $episode_group = Episode_group::find($id);
    } else {
      $episode_group = new Episode_group();
    }
    $episode_group->title = $title;
    $episode_group->course_id = new ObjectId($course_id);
    $episode_group->status = 1;
    $episode_group->position = $position;
    $episode_group->save();

    $current_user = Auth::user();
    ActivityLogClass::log('เพิ่มหรือแก้ไข episode_group', new ObjectId($current_user->_id), $episode_group->getTable(), $episode_group->getAttributes(),$current_user->username);
  
    return redirect()->route('course_create', ['id' => $course_id, '#episodegroup']);
  }

  public function episode_create($course_id, $id='')
  {
    $episode_list = $this->get_episode($course_id);
    if(empty($id)) {
      $data = new \stdClass();
      $data->_id = '';
      $data->title = '';
      $data->description = '';
      $data->code = '';
      $data->require_episodes = '';
      $data->status = 1;
    } else {
      $data = Episode::find($id);
    }
    $datas = [
      'id' => $id,
      'course_id' => $course_id,
      'episode_list' => $episode_list,
      'data' => $data
    ];
    return view('episode.episode_detail', $datas);
  }

  public function episode_store(Request $request)
  {
    $course_id = $request->input('course_id');
    $title = $request->input('title');
    $description = $request->input('description');
    $require_episode = $request->input('require_episode');

    $arr_require = [];
    array_push($arr_require, new ObjectId($require_episode));

    $hashids = new Hashids();
    

    $episode = new Episode();
    $episode->title = $title;
    $episode->description = $description;
    $episode->course_id = new ObjectId($course_id);
    $episode->require_episode = $arr_require;
    $episode->code = $hashids->encode(Carbon::now()->timestamp);
    $episode->content_id = 'jasmine:'.Carbon::now()->timestamp;
    $episode->status = 1;
    $episode->save();

    // Transcode
    // $path = 'videos/temp/'.$file;
    // dispatch(new UploadClip($episode, $path));

    return redirect()->route('course_create', ['id' => $course_id, '#episodelist']);
  }
  
  public function episode_upload_file(Request $request)
  {
    $timestamp = $request->timestamp;
    error_reporting(E_ALL | E_STRICT);
    $upload_handler = new UploadHandler($timestamp);
  }
  public function episode_video_delete_file(Request $request) {
    $file = $request->input('file');
    $path = 'videos/temp/'.$file;
    $exists = Storage::disk('local')->exists($path);
    $status = 200;
    $response = new \stdClass();
    if ($exists) {
      $result = Storage::disk('local')->delete($path);
      // Storage::allFiles
      if ($result) {
        $response->status = 'DELETE_SUCCESS';
      } else {
        $response->status = 'DELETE_FAIL';
      }
    } else {
      $response->status = 'NOT_EXIST';
      $response->status = $path;
    }
    return response()->json($response);
  }

}