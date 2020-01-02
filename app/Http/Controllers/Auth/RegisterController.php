<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Auth;

class RegisterController extends Controller
{
  protected function view() {
    $data = new \stdClass();
    $data->id = '';
    $data->name = '';
    $data->username = '';
    $data->email = '';
    $data->status = 1;
    $data->role_ids = [];
    $withData = [
      'data' => $data
    ];
    return view('auth.register', $withData);
  }

  protected function detail($id) {
    $data = User::find($id); 
    $withData = [
      'data' => $data
    ];
    return view('auth.register', $withData);
  }

  protected function store(Request $request) 
  {
    if(empty($request->input('id'))) {
      $validator = $this->validator($request->all());
      if ($validator->fails()) {
        return redirect()->back()->withInput()->withErrors($validator,'register');
      }
      $this->create($request->all());
    } else {
      $validator = $this->update_validator($request->all());
      if ($validator->fails()) {
        return redirect()->back()->withInput()->withErrors($validator,'register');
      }
      $this->update($request->all());
    }
    
    return redirect()->route('users_index');
  }

  protected function validator(array $data)
  {
    return Validator::make($data, [
      'name' => ['required', 'string', 'max:255'],
      'username' => ['required', 'string', 'max:255', 'unique:users'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
      'password' => ['required', 'string', 'min:8', 'confirmed'],
      'role' => ['required'],
      'permission' => ['required']
    ]);
  }

  protected function create(array $data)
  {
    $user = User::create([
      'name' => $data['name'],
      'username' => $data['username'],
      'email' => $data['email'],
      'password' => Hash::make($data['password']),
      'status' => (int)$data['status']
    ]);
    $user->syncPermissions([$data['permission']]);
    $user->syncRoles($data['role']);
  }

  protected function update_validator(array $data)
  {
    return Validator::make($data, [
      'role' => ['required']
    ]);
  }

  protected function update(array $data)
  {
    $user = User::find($data['id']);
    $user->name = $data['name'];
    $user->status = $data['status'];
    $user->save();
    $user->syncPermissions([$data['permission']]);
    $user->syncRoles([$data['role']]);
  }

  protected function resetpassword(Request $request) 
  {
    $validator = $this->validator_resetpassword($request->all());
    // $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return redirect()->back()->withInput()->withErrors($validator,'resetpassword');
    }
    $this->update_password($request->all());
    return redirect()->back()->with('success','success');
  }

  protected function validator_resetpassword(array $data)
  {
    return Validator::make($data, [
      'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);
  }

  protected function update_password(array $data)
  {
    $user = User::find($data['id']);
    $user->password = Hash::make($data['password']);
    $user->save();
  }
}
