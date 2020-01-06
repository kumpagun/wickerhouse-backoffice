<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class TrainingUserImportLog extends Eloquent  
{
  protected $collection = 'training_user_import_logs';
  protected $fillable = ['status','training_id','user_action','original_filename','file_path','total_record','total_import','total_duplicate','total_error','course_id'];
}