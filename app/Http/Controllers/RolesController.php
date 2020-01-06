<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maklad\Permission\Models\Role;
// Model
use App\User;

class RolesController extends Controller
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
    $datas = Role::all();
    $withData = ['datas' => $datas];
    return view('roles.index',$withData);
  }

  public function create($id='') 
  {
    if(empty($id)) {
      $data = new \stdClass();
      $data->id = '';
      $data->name = '';
      $data->display_name = '';
      $data->description = '';
      $data->status = 1;
    } else {
      $data = Role::find($id);
    }
    $withData = [
      'data' => $data
    ];
    return view('roles.detail',$withData);
  }

  public function store(Request $request) 
  {
    $validator = $this->validator($request->all());
    if ($validator->fails()) {
      return redirect()->back()->withInput()->withErrors($validator,'register');
    }
    $this->update($request->all());
    return redirect()->route('roles_index');
  }

  protected function validator(array $data)
  {
    if(!empty($data['id'])) {
      return Validator::make($data, []);
    } else {
      return Validator::make($data, [
        'name' => ['name' => 'required', 'alpha_dash', 'max:255', 'unique:roles'],
        'display_name' => ['name' => 'required', 'max:255']
      ]);
    }
  }

  protected function update(array $data)
  {
    if(!empty($data['id'])) {
      $role = Role::find($data['id']);
    } else {
      $role = new Role;
      $role->name = strtolower($data['name']);
    }
    $role->display_name = $data['display_name'];
    $role->description = $data['description'];
    $role->status = 1;
    $role->save();
  }
}
