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
  Route::get('/signout', 'Auth\LoginController@signout')->name('auth_signout');
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

// Banner
Route::group(['prefix' => 'banner', 'middleware' => ['auth', 'role:admin|banner']], function () {
  Route::get('/', 'BannerController@banner_index')->name('banner_index');
  Route::get('/create/{id?}', 'BannerController@banner_create')->name('banner_create');
  Route::post('/store', 'BannerController@banner_store')->name('banner_store');
  Route::post('/sort', 'BannerController@banner_sort')->name('banner_sort');
  Route::get('/banner/{id?}', 'BannerController@banner_delete')->name('banner_delete');
});

// product
Route::group(['prefix' => 'product', 'middleware' => ['auth', 'role:admin|product']], function () {
  Route::match(['get','post'],'/', 'ProductController@product_index')->name('product_index');
  Route::match(['get','post'],'/create/{id?}', 'ProductController@product_create')->name('product_create');
  Route::post('/store/{id?}', 'ProductController@product_store')->name('product_store');
});

// Category
Route::group(['prefix' => 'category', 'middleware' => ['auth', 'role:admin|course']], function () {
  Route::match(['get','post'], '/', 'Course\CategoryController@category_index')->name('category_index');
  Route::match(['get','post'], '/create/{id?}', 'Course\CategoryController@category_create')->name('category_create');
  Route::match(['get','post'], '/store', 'Course\CategoryController@category_store')->name('category_store');
  Route::get('/delete', 'Course\CategoryController@category_delete')->name('category_delete');
});