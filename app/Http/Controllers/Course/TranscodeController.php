<?php

namespace App\Http\Controllers\Course;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MongoDB\BSON\ObjectId as ObjectId;
// Model
use App\Models\Episode;
use App\Models\TranscodeCallback;

class TranscodeController extends Controller
{
  public function transcode_callback(Request $request){
    $new_callback = new TranscodeCallback;
    $new_callback->callback = $request->all();
    $new_callback->status = $request->status;
    $new_callback->save();

    $task = intval($request->input('task'));
    $status = $request->input('status');
    $thumbnail = $request->input('thumbnail');
    $screenshots = $request->input('screenshots');
    $presets = $request->input('presets');
    $playlists = $request->input('playlists');
    $wait_time = $request->input('wait_time');
    $job_time = $request->input('job_time');

    $episode = Episode::where('task_id', $task)->first();
    if (!empty($episode)) {
      if (!empty($thumbnail)) {
        $episode->thumbnail = $thumbnail;
      }
      if (!empty($screenshots)) {
        $episode->screenshots = $screenshots;
      }
      if (!empty($presets)) {
        $episode->presets = $presets;
      }
      if (!empty($playlists)) {
        $episode->playlists = $playlists;
      }
      if (isset($wait_time)) {
        $episode->wait_time = intval($wait_time);
      }
      if (isset($job_time)) {
        $episode->job_time = intval($job_time);
      }
      $episode->transcode_status = $status;
      $episode->save();
    }

    return response()->json([
      'status'    => 200
    ]);
  }
}