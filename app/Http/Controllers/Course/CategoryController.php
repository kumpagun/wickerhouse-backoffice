<?php

namespace App\Http\Controllers\Course;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Models\Category;

use MongoDB\BSON\ObjectId;
use Intervention\Image\ImageManagerStatic as Image;
use IIlluminate\Http\UploadedFile;
use MongoDB\BSON\UTCDateTime as UTCDateTime;
use Carbon\Carbon;


use Auth;


use ActivityLogClass;

class CategoryController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function category_index(){

        $datas = Category::query()->where('status',1)->get();

        return view('category.category_index',['datas' => $datas]);
    }
    public function category_create($id=''){
        if(empty($id)) {
            $data = new \stdClass();
            $data->id = '';
            $data->title = '';
            $data->description = '';
            $data->code = '';
            $data->slug = '';
            $data->status = 1;
          } else {
            $data = Category::find($id);
          }
          $withData = [
            'data' => $data
          ]; 
          return view('category.category_detail',$withData);
    }
    public function category_store(Request $request){
        $current_user   = Auth::user();
        $id = $request->input('id');
        $title = $request->input('title');
        $description = $request->input('description');
        $code = $request->input('code');
        $slug = $request->input('slug');
        $seq = 0;
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'code' => 'required',
            'slug' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'category')->withInput();
        }
        if ($id) {
            $update = Category::find($id);
            $seq = $update->seq;
        }
        $datas = [
            'title' => $title,
            'description' => $description,
            'code' => $code,
            'slug' => strtolower($slug),
            'seq'  => $seq,
            'status' => 1
        ];
        $find = [
            '_id' => $id
        ];
        $store = Category::UpdateOrCreate($find, $datas);
        ActivityLogClass::log('เพิ่มหรือแก้ไข Category', new ObjectId($current_user->_id), $store->getTable(), $store->getAttributes(),$current_user->username);

        return redirect()->route('category_index');
    }
    public function category_delete($id=''){
        $delete = Category::find($id);
        $delete->status = 0;
        $delete->save();
        ActivityLogClass::log('ลบข้อมูล Category', new ObjectId($current_user->_id), $store->getTable(), $store->getAttributes(),$current_user->username);
        return redirect()->route('category_index');
    }

}
