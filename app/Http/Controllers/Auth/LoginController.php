<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Validator;
use ActivityLogClass;
use MongoDB\BSON\ObjectId;

// Model
use App\User;

class LoginController extends Controller
{
  public function view() {
    return view('auth.login');
  }

  public function signin(Request $request) {
    $rules = [
      'username' => 'required',
      'password' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return redirect()->back()->withInput()->withErrors($validator,'signin');
    }
    $user = [
      'username' => $request->input('username'),
      'password' => $request->input('password'),
      'status'   => 1
    ];
    if(Auth::attempt($user)){
      $user = User::find(Auth::user()->_id);
      if($user){
        auth()->login($user, true);
        $current_user = Auth::user();
        ActivityLogClass::log('User เข้าใช้งาน', new ObjectId($current_user->_id), $current_user->getTable(), $current_user->getAttributes(),$current_user->username);
        return redirect()->route('index');
      }else{
        return redirect()->route('auth_view');
      }
    }else{
      $rules = [
        'username' => 'required',
        'password' => 'required'
      ];
      $validator = Validator::make($request->all(), $rules);
      $validator->getMessageBag()->add('signin', 'Username or Password wrong');
      return redirect()->route('auth_view')->withErrors($validator, 'signin')->withInput();
    }
  }

  public function signout() {
    Auth::logout();
    return redirect()->route('auth_view');
  }
}
