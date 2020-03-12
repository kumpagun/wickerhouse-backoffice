<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Episode extends Eloquent  
{
  protected $collection = 'episodes';
  protected $fillable = [
    'course_id', // ObjectId
    'episode_group_id', // ObjectId
    'code',
    'title',
    'description',
    'total_view',
    'total_episode',
    'last_episode',
    'position',
    'content_id',
    'filename',
    'transcode_status',
    'task_id',
    'streams', // ObjectId
    'meta',  // ObjectId
    'duration',
    'thumbnail',
    'presets',
    'playlists',
    'screenshots', // [String]
    'status',
    'require_episode',
    'have_quiz',
    'total_quiz',
    'passing_point'
  ];
}