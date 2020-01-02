<?php

namespace App\Classes;
use Request;
use MongoDB\BSON\ObjectId as ObjectId;
use App\User;
use Maklad\Permission\Models\Role;
use Maklad\Permission\Models\Permission;
use Auth;

class UserClass
{
  public function get_roles() {
    $user = Auth::user();
    $query = Role::where('status',1)->get();
    return $query;
  }
  public function get_permissions() {
    $user = Auth::user();
    $query = Permission::where('status',1)->get();
    
    return $query;
  }

  public function getLogoutSession(){
    $current_user = Auth::user();
    if(!empty($current_user)){
      $check  = User::find($current_user->_id);
      if($check){
        return redirect()->route('admin_signout');
      }   
    }  
  }

  public function get_name_role($user_id = ''){
    $user = User::find($user_id);
    if(!empty($user)){
      $roles = $user->getRoleNames();
      $roles = $roles->toArray();
      $data = implode(', ', $roles);
      return $data;
    }else{
      return '-';
    }
  }

  public function get_name_permission($user_id = ''){
    $user = User::find($user_id);
    if(!empty($user)){
      $permissions = $user->getPermissionNames();
      $permissions = $permissions->toArray();
      $data = implode(', ', $permissions);
      return $data;
    }else{
      return '-';
    }
  }

  public function get_roleId($role_name = ''){
    $roles = [];
    $query = Role::query()->where('status',1)->where('collection','users');
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
    $query = Role::query()->where('status',1)->where('collection','users');
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
    $query = Role::query()->where('status',1)->where('collection','users');
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
