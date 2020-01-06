<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('index');

// UPLOAD IMAGE
Route::group(['prefix' => 'upload'], function () {
  Route::post('/upload', 'UploadImagesController@upload')->name('upload_images');
  Route::post('/cropimage', 'UploadImagesController@cropImage')->name('upload_cropimages');
});
// Auth
Route::group(['prefix' => 'auth'], function () {
  Route::get('/', 'Auth\LoginController@view')->name('auth_view');
  Route::post('/signin', 'Auth\LoginController@signin')->name('auth_signin');
  Route::group(['middleware' => ['auth', 'role:admin']], function() {
    Route::get('/signout', 'Auth\LoginController@signout')->name('auth_signout');
  });
});
// Users
Route::group(['prefix' => 'users', 'middleware' => ['auth', 'role:admin']], function () {
  Route::get('/', 'UsersController@index')->name('users_index');
  Route::get('/detail/{id}', 'Auth\RegisterController@detail')->name('users_detail');
  Route::get('/register', 'Auth\RegisterController@view')->name('users_register');
  Route::post('/register/store', 'Auth\RegisterController@store')->name('users_store');
  Route::post('/resetpassword', 'Auth\RegisterController@resetpassword')->name('users_resetpassword');
});
// Roles
Route::group(['prefix' => 'roles', 'middleware' => ['auth', 'role:admin']], function () {
  Route::get('/', 'RolesController@index')->name('roles_index');
  Route::get('/create/{id?}', 'RolesController@create')->name('roles_create');
  Route::post('/store', 'RolesController@store')->name('roles_store');
});
// Permissions
Route::group(['prefix' => 'permissions', 'middleware' => ['auth', 'role:admin']], function () {
  Route::get('/', 'PermissionsController@index')->name('permissions_index');
  Route::get('/create/{id?}', 'PermissionsController@create')->name('permissions_create');
  Route::post('/store', 'PermissionsController@store')->name('permissions_store');
});

// Course
Route::group(['prefix' => 'course', 'middleware' => ['auth', 'role:admin|course']], function () {
  Route::match(['get','post'],'/', 'Course\CourseController@course_index')->name('course_index');
  Route::match(['get','post'],'/create/{id?}', 'Course\CourseController@course_create')->name('course_create');
  Route::post('/store/{id?}', 'Course\CourseController@course_store')->name('course_store');
});

// Episode
Route::group(['prefix' => 'episode', 'middleware' => ['auth', 'role:admin|course']], function () {
  // GROUP
  Route::post('/group/store', 'Course\EpisodeController@episode_group_store')->name('episode_group_store');
  Route::get('/group/create/{course_id}/{id?}', 'Course\EpisodeController@episode_group_create')->name('episode_group_create');
  // EP
  Route::post('/group/updatelist', 'Course\EpisodeController@episode_group_updatelist')->name('episode_group_updatelist');
  Route::get('/create/{course_id}/{id?}', 'Course\EpisodeController@episode_create')->name('episode_create');
  Route::post('/store', 'Course\EpisodeController@episode_store')->name('episode_store');
  // Upload Video
  Route::match(['get','post'],'/upload_file', 'Course\EpisodeController@episode_upload_file')->name('episode_upload_file');
  Route::match(['get','post'],'/video_delete_file', 'Course\EpisodeController@episode_video_delete_file')->name('episode_video_delete_file');
});

// Homework
Route::group(['prefix' => 'homework', 'middleware' => ['auth', 'role:admin|course']], function () {
  Route::match(['get','post'], '/', 'Course\HomeworkController@homework_index')->name('homework_index');
  Route::get('/course/create/{id?}', 'Course\HomeworkController@homework_by_course_create')->name('homework_by_course_create');
  Route::get('/create/{id?}', 'Course\HomeworkController@homework_create')->name('homework_create');
  Route::post('/store/{id?}', 'Course\HomeworkController@homework_store')->name('homework_store');
});

// Examination
Route::group(['prefix' => 'examination', 'middleware' => ['auth', 'role:admin|course']], function () {
  Route::post('/group_store/{id?}', 'Course\ExaminationController@examination_group_store')->name('examination_group_store');
  Route::get('/group_delete/{id?}', 'Course\ExaminationController@examination_group_delete')->name('examination_group_delete');

  Route::get('/index/{id?}', 'Course\ExaminationController@examination_index')->name('examination_index');
  Route::get('/create/{examination_group_id}/{id?}', 'Course\ExaminationController@examination_create')->name('examination_create');
  Route::post('/store', 'Course\ExaminationController@examination_store')->name('examination_store');
  Route::get('/delete/{id?}', 'Course\ExaminationController@examination_delete')->name('examination_delete');
});

// Category
Route::group(['prefix' => 'category', 'middleware' => ['auth', 'role:admin|course']], function () {
  Route::match(['get','post'], '/', 'Course\CategoryController@category_index')->name('category_index');
  Route::match(['get','post'], '/create/{id?}', 'Course\CategoryController@category_create')->name('category_create');
  Route::match(['get','post'], '/store', 'Course\CategoryController@category_store')->name('category_store');
  Route::match(['get','post'],'/delete/{id?}', 'Course\CategoryController@category_delete')->name('category_delete');
});

// Class Room
Route::group(['prefix' => 'training', 'middleware' => ['auth', 'role:admin|course']], function () {
  Route::match(['get','post'],'/', 'Training\TrainingController@training_index')->name('training_index');
  Route::match(['get','post'],'/user/{id?}', 'Training\TrainingController@traingin_user_list')->name('traingin_user_list');
  Route::match(['get','post'],'/store', 'Training\TrainingController@training_store')->name('training_store');
  Route::match(['get','post'],'/create/{id?}', 'Training\TrainingController@training_create')->name('training_create');
  Route::match(['get','post'],'/import/excel', 'Training\TrainingController@import_excel')->name('import_excel');
  Route::match(['get','post'],'/delete/{id?}', 'Course\CategoryController@training_delete')->name('training_delete');
  Route::post('/user_del','Training\TrainingController@traingin_user_delete')->name('training_user_delete');
});

// Company
Route::group(['prefix' => 'company', 'middleware' => ['auth', 'role:admin|course']], function () {
  Route::match(['get','post'],'/', 'Training\CompanyController@company_index')->name('company_index');
  Route::match(['get','post'],'/store', 'Training\CompanyController@company_store')->name('company_store');
  Route::match(['get','post'],'/create/{id?}', 'Training\CompanyController@company_create')->name('company_create');
  Route::match(['get','post'],'/delete/{id?}', 'Training\CompanyController@company_delete')->name('company_delete');
});

// Department
Route::group(['prefix' => 'department', 'middleware' => ['auth', 'role:admin|course']], function () {
  Route::match(['get','post'],'/', 'Training\DepartmentController@department_index')->name('department_index');
  Route::match(['get','post'],'/store', 'Training\DepartmentController@store_department')->name('store_department');
  Route::match(['get','post'],'/create/{id?}', 'Training\DepartmentController@create_department')->name('create_department');
  Route::match(['get','post'],'/delete/{id?}', 'Training\DepartmentController@delete_department')->name('delete_department');
  // Ajax get_department_by_company
  Route::match(['get','post'],'/by/company/{company_id?}', 'Training\TrainingController@get_department_by_company')->name('get_department_by_company');
});

// TEACHER
Route::group(['prefix' => 'teacher', 'middleware' => ['auth', 'role:admin|course']], function () {
  Route::match(['get','post'],'/', 'Teacher\TeacherController@teacher_index')->name('teacher_index');
  Route::match(['get','post'],'/create/{id?}', 'Teacher\TeacherController@teacher_create')->name('teacher_create');
  Route::post('/store/{id?}', 'Teacher\TeacherController@teacher_store')->name('teacher_store');
});