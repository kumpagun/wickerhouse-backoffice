<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Validator;
use ActivityLogClass;
use MongoDB\BSON\ObjectId;
use GuzzleHttp\Client;

// Model
use App\User;

class JasmineController extends Controller
{
  public function signin() {
    $params = [
      'response_type' => env('JAS_RESPONE_TYPE'),
      'client_id' => env('JAS_CLIENT_ID'),
      'redirect_uri' => env('JAS_CALLBACK')
    ];

    $postdata = http_build_query($params);
    $oauth_login_url = env('JAS_LOGIN_URL')."?".$postdata;

    return redirect()->to($oauth_login_url);
  }

  public function callback(Request $request) {
    $code = $request->input('code');
    $params = [
      'grant_type' => env('JAS_GRANT_TYPE'),
      'client_id' => env('JAS_CLIENT_ID'),
      'redirect_uri' => env('JAS_CALLBACK'),
      'code' => $code
    ];
    $OAUTH_STR = http_build_query($params);
    $client = new Client();
    $response = $client->request('POST', env('JAS_TOKEN'), [
      'auth' => [
        env('JAS_CLIENT_ID'), 
        env('JAS_CLIENT_SECRET')
      ],
      'form_params' => $params
    ]);
    $data = json_decode($response->getBody(), true);

    $token = $data['access_token'];
    $tokenType = $data['token_type'];
    $refreshToken = $data['refresh_token'];

    // $token = "d0dbb90a-1917-4b85-9c7b-f70d006f5938";
    // $tokenType = "bearer";
    // $refreshToken = "16cf9ed4-cc5a-460b-b82e-7b35740179be";

    $this->handle_callback($token);

    return redirect()->route('index');
  }

  public function handle_callback($token) {
    $client = new Client();
    $headers = [
      'Authorization' => 'Bearer ' . $token,        
      'Accept'        => 'application/json',
    ];
    $response = $client->request('GET', env('JAS_PROFILE'), [
      'headers' => $headers
    ]);
    $data = json_decode($response->getBody());
    $profile = $data[0];

    $user = User::where('username',$profile->employee_id)->where('type','jasmine')->where('status',1)->first();
    
    if(empty($user)) {
      $user = User::create([
        'name' => $profile->thai_fullname,
        'username' => $profile->employee_id,
        'email' => $profile->email,
        'password' => Hash::make($profile->employee_id),
        'status' => 1,
        'type' => 'jasmine',
        'user_info' => $profile
      ]);
      $user->syncPermissions(['viewer']);
      $user->syncRoles(['guest']);
    }
    
    $auth_params = [
      'username' => $profile->employee_id,
      'password' => $profile->employee_id,
      'status'   => 1
    ];

    if(Auth::attempt($auth_params)){
      $user = User::find(Auth::user()->_id);
      auth()->login($user, true);
      $current_user = Auth::user();
      ActivityLogClass::log('User Jasmine เข้าใช้งาน', new ObjectId($current_user->_id), $current_user->getTable(), $current_user->getAttributes(),$current_user->username);
    }
  }
}
