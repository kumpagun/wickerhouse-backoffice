<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Model
use App\User;

class UsersController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $datas = User::where('status',1)->get();
    $withData = ['datas' => $datas];
    return view('users.index',$withData);
  }
}
