<?php

namespace App\Classes;
use Request;
use MongoDB\BSON\ObjectId as ObjectId;
use App\User;
use Maklad\Permission\Models\Role;

use MongoDB\BSON\UTCDateTime as UTCDateTime;
use Carbon\Carbon;

use App\Models\Company;
use App\Models\Department;
use App\Models\TrainingUser;
use App\Models\Training;
use App\Models\Category;
class FuncClass
{   
    public function update_seq_cate($cate_id=''){
        $update_seq = Category::find($cate_id);
        if ($update_seq) {
            $seq = $update_seq->seq+1;
            $update_seq->seq  = $seq;
            $update_seq->save();
        } 
    }
    public function count_user_in_traingin($training_id = ''){
        $count = 0;
        if ($training_id) {
            $count = TrainingUser::query()->where('status',1)->where('training_id',new ObjectId($training_id))->count();
        } 
        return $count;
    }
    public function utc_to_carbon_with_format ($time='', $format='')
    {
        if ($time && $format) {
            $timestamp  = $time->toDateTime()->getTimestamp();
            $carbon = Carbon::createFromTimestamp($timestamp);
            return $carbon->format($format);
        }
        return null;
    }
    public function utc_to_carbon_format_time_zone_bkk ($time='')
    {
        if ($time) {
            $timestamp  = $time->toDateTime()->getTimestamp();
            $carbon = Carbon::createFromTimestamp($timestamp);
            return $carbon->timezone('Asia/Bangkok')->format('d-m-Y H:i:s');
        }
        return '-';
        // ->timezone('Asia/Bangkok')->toDateTimeString()
    }
    public function checkCurrentDate ($date = '')
    {
        $current  = Carbon::now()->getTimestamp();
        if ($date) {
          $date = $date->toDateTime()->getTimestamp();
          if ($current < $date) {
            return false;
          }else{
            return true;
          }
        }else{
            return true;
        }
    }
    public function utc_to_carbon_format_time_zone_bkk_in_format ($time='',$format='d-m-Y')
    {
        if ($time) {
            $timestamp  = $time->toDateTime()->getTimestamp();
            $carbon = Carbon::createFromTimestamp($timestamp);
            return $carbon->timezone('Asia/Bangkok')->format($format);
        }
        return '-';
        // ->timezone('Asia/Bangkok')->toDateTimeString()
    }
    public function utc_to_carbon_format_no_second ($time='')
    {
        if ($time) {
            $timestamp  = $time->toDateTime()->getTimestamp();
            $carbon = Carbon::createFromTimestamp($timestamp);
            return $carbon->format('d-m-Y H:i');
        }
        return '-';
    }
    public function get_name_company($id){
        $name = '-';
        $datas = Company::find($id);
        if(!empty($datas)){
            $name = $datas->title; 
        }
        return $name;
    }
    public function get_name_department($id){
        $name = '';
        $datas = Department::find($id);
        if(!empty($datas)){
            $name = $datas->title; 
        }
        return $name;   
    }

}
