<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maklad\Permission\Models\Permission;
// Model
use App\User;

class PermissionsController extends Controller
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
    $datas = Permission::all();
    $withData = ['datas' => $datas];
    return view('permissions.index',$withData);
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
      $data = Permission::find($id);
    }
    $withData = [
      'data' => $data
    ];
    return view('permissions.detail',$withData);
  }

  public function store(Request $request) 
  {
    $validator = $this->validator($request->all());
    if ($validator->fails()) {
      return redirect()->back()->withInput()->withErrors($validator,'register');
    }
    $this->update($request->all());
    return redirect()->route('permissions_index');
  }

  protected function validator(array $data)
  {
    if(!empty($data['id'])) {
      return Validator::make($data, []);
    } else {
      return Validator::make($data, [
        'name' => ['name' => 'required', 'alpha_dash', 'max:255', 'unique:permissions'],
        'display_name' => ['name' => 'required', 'max:255']
      ]);
    }
  }

  protected function update(array $data)
  {
    if(!empty($data['id'])) {
      $role = Permission::find($data['id']);
    } else {
      $role = new Permission();
      $role->name = strtolower($data['name']);
    }
    $role->display_name = $data['display_name'];
    $role->description = $data['description'];
    $role->status = 1;
    $role->save();
  }
}
