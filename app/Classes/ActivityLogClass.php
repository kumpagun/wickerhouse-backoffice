<?php

namespace App\Classes;

use MongoDB\BSON\ObjectId as ObjectId;

use Auth;

use App\Models\ActivityLog;
use App\Models\FeedLog;
use App\Models\TrainingUserImportLog;
use App\User;
class ActivityLogClass
{

   
    public function log ($action, $action_id, $collection, $data,$name='')
    {   
        /* ACTION - 'ACTION_{FIELD}?_TABLE'*/
        $new_log = new ActivityLog;
        $new_log->action_type = $action;
        $new_log->user_action = new ObjectId($action_id);
        $new_log->collection = $collection;
        $new_log->data = $data;
        $new_log->user = $name;
        $new_log->save();
    }
    public function get_log($action,$crontab){
        if($action == 'start'){
            $data = FeedLog::query()->where('crontab',$crontab)->first();
            if(empty($data)){
                $new_log = new FeedLog;
                $new_log->action = $action;
                $new_log->crontab = $crontab;
                $new_log->status = 0;
                $new_log->save();
            }else{
                $data->status = 0; 
                $data->save();
            }
           
        }else{
            $data = FeedLog::query()->where('crontab',$crontab)->first();
            if(empty($data)){
                $new_log = new FeedLog;
                $new_log->action = $action;
                $new_log->crontab = $crontab;
                $new_log->status = 1;
                $new_log->save();
            }else{
                $data->status = 1; 
                $data->save();
            }
        }
    }
    public function get_error_log($action,$status_code,$crontab){
        $new_log = new FeedLog;
        $new_log->action = $action;
        $new_log->crontab = $crontab;
        $new_log->status_code = $status_code;
        $new_log->status = 1;
        $new_log->save();
    }
    public function log_start_import_excel($file_path,$training_id,$user_action,$original_filename,$total_record,$course_id){
        $new_log = new TrainingUserImportLog;
        $new_log->training_id = new ObjectId($training_id);
        $new_log->user_action = new ObjectId($user_action);
        $new_log->original_filename = $original_filename;
        $new_log->file_path = $file_path;
        $new_log->total_record = intval($total_record);
        $new_log->total_error = 0;
        $new_log->total_import = 0;
        $new_log->total_duplicate = 0;
        $new_log->course_id = new ObjectId($course_id);
        $new_log->status = 1;
        $new_log->save();
        $id = $new_log->_id;
        return $id;
    }
    public function log_end_import_excel($id,$total_import,$total_error,$total_duplicate){
    
            $update_log =  TrainingUserImportLog::find($id);
            $update_log->total_error = intval($total_error);
            $update_log->total_import = intval($total_import);
            $update_log->total_duplicate = intval($total_duplicate);
            $update_log->status = 1;
            $update_log->save();
    }


}