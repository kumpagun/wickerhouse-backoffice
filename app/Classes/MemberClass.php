<?php

namespace App\Classes;

use Request;
use Auth;
use MongoDB\BSON\ObjectId as ObjectId;
use Maklad\Permission\Models\Role;

// Model
use App\User;
use App\Models\Member_jasmine;
use App\Models\Employee;
use App\Models\Giftcode_usage;
use App\Models\Member;
use App\Models\Delegate;

class MemberClass
{
    public function get_name_member_jasmine($employee_id){
        $name = '-';
        $employee_data = Member_jasmine::where('status',1)->where('employee_id',$employee_id)->first(); 
        $employee = Employee::where('employee_id',$employee_id)->first(); 
        if($employee_data) {
          $name = $employee_data->firstname." ".$employee_data->lastname; 
        } 
        if($employee) {
          $name = $employee->tf_name." ".$employee->tl_name; 
        } 
        
        return $name;
    }
    public function get_name_member_jasmine_by_id($user_id){
      $name = '-';
      $employee_data = Member::find($user_id);
      if ( $employee_data) {
        $name = $employee_data->fullname; 
      } 
      return $name;
    }
    public function get_employeeId_member_jasmine_by_id($user_id){
      $name = '-';
      $employee_data = Member::find($user_id);
      if ( $employee_data) {
        $name = $employee_data->employee_id; 
      } 
      return $name;
    }
    public function get_name_role($user_id = ''){
        $user = User::find($user_id);
        if(!empty($user)){
            $role_id = ($user->role_ids)[0];
            $role = Role::find($role_id);
            // dd($role_id);
            return $role->display_name;
        }else{
            return '-';
        }

    }
    public function get_id_role($user_id = ''){
        if($user_id ){
            $user =  User::find($user_id);
            $data = ($user->role_ids)[0];
            return $data;
        }else{
            return dd('ไม่มี Role');
        }
    } 
    public function get_member_by_user_id($user_id = ''){
        
        $datas = Member::query()->where('user_id',new ObjectId($user_id))->first();

        return $datas;
    }
    public function get_roleId($role_name = ''){
        $roles = [];
        $query = Role::query()->where('status',1)->where('collection','members');
        if(!empty($role_name)) {
            $query->where('name', $role_name);
        }
        $datas = $query->get();
        foreach($datas as $key){
            array_push($roles, $key->id);
        }
        return $roles;
    }
    public function get_roleId_array_in($role_name=[]){
        $roles = [];
        $query = Role::query()->where('status',1)->where('collection','members');
        if(!empty($role_name)) {
            $query->whereIn('name', $role_name);
        }
        $datas = $query->get();
        foreach($datas as $key){
            array_push($roles, $key->id);
        }
        return $roles;
    }
    public function get_roleId_not_approve($role_name=''){
        $roles = [];
        $query = Role::query()->where('status',1)->where('collection','members');
        if(!empty($role_name)) {
            $query->where('name', $role_name);
        }
        $datas = $query->get();
        foreach($datas as $key){
            array_push($roles, $key->id);
        }
        return $roles;
    }

    public function get_name_user($id = ''){
        $name = '-';
        $user = User::find($id);
        if(!empty($user)){
            $name = $user->name;
        }
        return $name;
    }

    
    public function getUserRequestAccessCount()
    {
        $roles = $this->get_roleId('guest');
        $request_datas = User::whereIn('role_ids',$roles)->count();
        return $request_datas;
    }


  public function getUserFromGiftcode($giftcode_id) {
    $giftcode_usage = Giftcode_usage::where('giftcode_id',new ObjectId($giftcode_id))->where('status',1)->first();

    if(!empty($giftcode_usage)) {
      return $this->get_name_member_jasmine_by_id($giftcode_usage->user_id);
    } else {
      return '-';
    }
  }
  
  public function getUserEmailFromEmployeeId($employee_id) {
    $data = Member::where('employee_id', $employee_id)->first();
    return $data->email;
  }

  public function get_employee_id_from_head() {
    $employee_id = Auth::user()->username;
    $headofhead = ['SVP - Acting Head of Regional Operation Group','ผู้อำนวยการภาค'];
    $director = Employee::where('employee_id',$employee_id)->whereIn('title_name', $headofhead)->first();
    $delegate = Delegate::where('employee_delegate_id',$employee_id)->orderBy('created_at','desc')->first();
    $arr_employee_id = [];
    
    if(!empty($director)) { // หัวหน้าภาค
      $employees = Employee::where('region', $director->region)->get();
    }
    else if(!empty($delegate)) { // ตัวแทนหัวหน้าภาค
      $head = Employee::where('employee_id',$delegate->employee_executive_id)->first();
      $employees = Employee::where('region', $head->region)->get();
    } 
    else { // // หัวหน้า
      array_push($arr_employee_id, $employee_id);
      $employees = Employee::whereIn('heads', $arr_employee_id)->get();
    }
   
    $data_back = [];
    if(!empty($employees)) {
      foreach($employees as $employee) {
        array_push($data_back, $employee->employee_id);
      }
    } 

    return $data_back;
  }

  public function get_name_from_employee_id($employee_id) {
    $member = Employee::where('employee_id', $employee_id)->first();
    if(!empty($member)) {
      return $member->tf_name.' '.$member->tl_name;
    } else {
      return '';
    }
  }

  public function get_employee_from_employee_id($employee_id) {
    $member = Employee::where('employee_id', $employee_id)->first();
    if(!empty($member)) {
      return $member;
    } else {
      return '';
    }
  }
}
