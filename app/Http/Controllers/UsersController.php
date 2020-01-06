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
    $datas = User::all();
    $withData = ['datas' => $datas];
    return view('users.index',$withData);
  }
}
