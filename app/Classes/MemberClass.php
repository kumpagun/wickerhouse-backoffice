<?php

namespace App\Classes;
use Request;
use MongoDB\BSON\ObjectId as ObjectId;
use App\Models\Member;
use App\User;
use Maklad\Permission\Models\Role;
use App\Models\Member_jasmine;
class MemberClass
{
    public function get_name_member_jasmine($employee_id){
        $name = '-';
        $employee_data = Member_jasmine::where('status',1)->where('employee_id',$employee_id)->first();
        if ( $employee_data) {
            $name = $employee_data->tf_name." ".$employee_data->tl_name; 
        } 
        return $name;
    }
    public function get_name_member_jasmine_by_id($user_id){
        $name = '-';
        $employee_data = Member_jasmine::find($user_id);
        if ( $employee_data) {
            $name = $employee_data->tf_name." ".$employee_data->tl_name; 
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
}
