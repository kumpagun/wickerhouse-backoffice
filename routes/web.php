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

  Route::group(['prefix' => 'jas'], function () {
    Route::get('/signin', 'Auth\JasmineController@signin')->name('jasmine_signin');
    Route::get('/callback', 'Auth\JasmineController@callback')->name('jasmine_callback');
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

// Banner
Route::group(['prefix' => 'banner', 'middleware' => ['auth', 'role:admin|banner']], function () {
  Route::get('/', 'BannerController@banner_index')->name('banner_index');
  Route::get('/create/{id?}', 'BannerController@banner_create')->name('banner_create');
  Route::post('/store', 'BannerController@banner_store')->name('banner_store');
  Route::post('/sort', 'BannerController@banner_sort')->name('banner_sort');
  Route::get('/banner/{id?}', 'BannerController@banner_delete')->name('banner_delete');
});

// Course
Route::group(['prefix' => 'course', 'middleware' => ['auth', 'role:admin|course']], function () {
  Route::match(['get','post'],'/', 'Course\CourseController@course_index')->name('course_index');
  Route::match(['get','post'],'/create/{id?}', 'Course\CourseController@course_create')->name('course_create');
  Route::post('/store/{id?}', 'Course\CourseController@course_store')->name('course_store');

  // Document
  Route::group(['prefix' => 'document', 'middleware' => ['auth', 'role:admin|course']], function () {
    Route::get('/', 'Course\DocumentController@document_index')->name('document_index');
    Route::get('/create/{id?}', 'Course\DocumentController@document_create')->name('document_create');
    Route::post('/store/{id?}', 'Course\DocumentController@document_store')->name('document_store');
    Route::get('/zip_delete/{course_id?}', 'Course\DocumentController@document_zip_delete')->name('document_zip_delete');
    Route::get('/pdf_delete/{course_id?}/{code?}', 'Course\DocumentController@document_pdf_delete')->name('document_pdf_delete');
  });

  // Episode
  Route::group(['prefix' => 'episode', 'middleware' => ['auth', 'role:admin|course']], function () {
    // GROUP
    Route::post('/group/store', 'Course\EpisodeController@episode_group_store')->name('episode_group_store');
    Route::get('/group/create/{course_id}', 'Course\EpisodeController@episode_group_create')->name('episode_group_create');
    Route::get('/group/delete/{episode_group_id?}', 'Course\EpisodeController@episode_group_delete')->name('episode_group_delete');
    
    // EP
    Route::post('/group/sortgroup', 'Course\EpisodeController@episode_group_sortgroup')->name('episode_group_sortgroup');
    Route::get('/create/{course_id}/{id?}', 'Course\EpisodeController@episode_create')->name('episode_create');
    Route::post('/store', 'Course\EpisodeController@episode_store')->name('episode_store');
    Route::get('/delete/{id?}', 'Course\EpisodeController@episode_delete')->name('episode_delete');
    Route::post('/update_group_id', 'Course\EpisodeController@episode_update_group_id')->name('episode_update_group_id');
    
    // Upload Video
    Route::match(['get','post'],'/upload_file', 'Course\EpisodeController@episode_upload_file')->name('episode_upload_file');
    Route::match(['get','post'],'/video_delete_file', 'Course\EpisodeController@episode_video_delete_file')->name('episode_video_delete_file');
  });

  // Homework
  Route::group(['prefix' => 'homework', 'middleware' => ['auth', 'role:admin|course']], function () {
    Route::get('/create/{course_id}/{id?}', 'Course\HomeworkController@homework_create')->name('homework_create');
    Route::post('/store', 'Course\HomeworkController@homework_store')->name('homework_store');
    Route::get('/delete/{id?}', 'Course\HomeworkController@homework_delete')->name('homework_delete');
  });

  // Examination
  Route::group(['prefix' => 'examination', 'middleware' => ['auth', 'role:admin|course']], function () {
    Route::post('/group_store/{id?}', 'Course\ExaminationController@examination_group_store')->name('examination_group_store');
    Route::get('/group_delete/{id?}', 'Course\ExaminationController@examination_group_delete')->name('examination_group_delete');

    Route::post('/posttest_update/{id?}', 'Course\ExaminationController@examination_posttest_update')->name('examination_posttest_update');

    Route::get('/index/{id?}', 'Course\ExaminationController@examination_index')->name('examination_index');
    Route::get('/create/{examination_group_id}/{id?}', 'Course\ExaminationController@examination_create')->name('examination_create');
    Route::post('/store', 'Course\ExaminationController@examination_store')->name('examination_store');
    Route::get('/delete/{id?}', 'Course\ExaminationController@examination_delete')->name('examination_delete');

    Route::post('/import-excel', 'Course\ExaminationController@examination_import_excel')->name('examination_import_excel');
  });

  // Quiz
  Route::group(['prefix' => 'quiz', 'middleware' => ['auth', 'role:admin|course']], function () {
    Route::post('/group_store/{id?}', 'Course\QuizController@quiz_group_store')->name('quiz_group_store');
    Route::get('/group_delete/{id?}', 'Course\QuizController@quiz_group_delete')->name('quiz_group_delete');
    
    Route::post('/detail/store', 'Course\QuizController@quiz_detail_store')->name('quiz_detail_store');

    Route::get('/index/{id?}', 'Course\QuizController@quiz_index')->name('quiz_index');
    Route::get('/create/{quiz_group_id}/{id?}', 'Course\QuizController@quiz_create')->name('quiz_create');
    Route::post('/store', 'Course\QuizController@quiz_store')->name('quiz_store');
    Route::get('/delete/{id?}', 'Course\QuizController@quiz_delete')->name('quiz_delete');

    Route::post('/import-excel', 'Course\QuizController@quiz_import_excel')->name('quiz_import_excel');
  });
});

// Homework
Route::group(['prefix' => 'homework', 'middleware' => ['auth', 'role:admin|homework']], function () {
  Route::get('/', 'Course\HomeworkController@homework_index')->name('homework_index');
  Route::get('/answer/{homework_id}/{type?}', 'Course\HomeworkController@homework_answer_index')->name('homework_answer_index');
  Route::post('/answer_store', 'Course\HomeworkController@homework_answer_store')->name('homework_answer_store');
});

// Question
Route::group(['prefix' => 'question', 'middleware' => ['auth', 'role:admin|question']], function () {
  Route::get('/', 'Course\QuestionController@question_index')->name('question_index');
  Route::get('/answer/{question_id}/{type?}', 'Course\QuestionController@question_answer_index')->name('question_answer_index');
  Route::post('/answer_store', 'Course\QuestionController@question_answer_store')->name('question_answer_store');
});

// Category
Route::group(['prefix' => 'category', 'middleware' => ['auth', 'role:admin|course']], function () {
  Route::match(['get','post'], '/', 'Course\CategoryController@category_index')->name('category_index');
  Route::match(['get','post'], '/create/{id?}', 'Course\CategoryController@category_create')->name('category_create');
  Route::match(['get','post'], '/store', 'Course\CategoryController@category_store')->name('category_store');
  Route::get('/delete', 'Course\CategoryController@category_delete')->name('category_delete');
});

// Training
Route::group(['prefix' => 'training', 'middleware' => ['auth', 'role:admin|course']], function () {
  Route::match(['get','post'],'/', 'Training\TrainingController@training_index')->name('training_index');
  Route::match(['get','post'],'/user/{id?}', 'Training\TrainingController@traingin_user_list')->name('traingin_user_list');
  Route::match(['get','post'],'/store', 'Training\TrainingController@training_store')->name('training_store');
  Route::match(['get','post'],'/create/{id?}', 'Training\TrainingController@training_create')->name('training_create');
  Route::match(['get','post'],'/import/excel', 'Training\TrainingController@import_excel')->name('import_excel');
  Route::match(['get','post'],'/delete/{id?}', 'Course\CategoryController@training_delete')->name('training_delete');
  Route::post('/user_del','Training\TrainingController@traingin_user_delete')->name('training_user_delete');

  Route::match(['get','post'],'/employee-filter', 'Training\TrainingController@training_employee_filter')->name('training_employee_filter');
  Route::post('/import_employees', 'Training\TrainingController@training_import_employees')->name('training_import_employees');
  
  
});

// Assessment
Route::group(['prefix' => 'review', 'middleware' => ['auth', 'role:admin|course']], function () {
  Route::group(['prefix' => 'group'], function () {
    Route::post('/store', 'ReviewController@review_group_store')->name('review_group_store');
    Route::get('/delete/{id?}', 'ReviewController@review_group_delete')->name('review_group_delete');
  });
  Route::get('/{review_group_id}', 'ReviewController@review_index')->name('review_index');
  Route::get('/create/{type}/{review_group_id}/{id?}', 'ReviewController@review_create')->name('review_create');
  Route::post('/store', 'ReviewController@review_store')->name('review_store');
  Route::match(['get','post'],'/delete/{id?}', 'ReviewController@review_delete')->name('review_delete');
  // Choice
  Route::post('/choice/store', 'ReviewController@review_choice_store')->name('review_choice_store');
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

// EMPLOYEE VIP
Route::group(['prefix' => 'employee', 'middleware' => ['auth', 'role:admin|course']], function () {
  Route::get('/vip', 'EmployeeVIPController@employee_vip_index')->name('employee_vip_index');
  Route::get('/vip/create/{id?}', 'EmployeeVIPController@employee_vip_create')->name('employee_vip_create');
  Route::post('/vip/store/{id?}', 'EmployeeVIPController@employee_vip_store')->name('employee_vip_store');
  Route::match(['get','post'],'/vip/delete/{id?}', 'EmployeeVIPController@employee_vip_delete')->name('employee_vip_delete');
});

// REPORT
Route::match(['get','post'],'/dashboard', 'Report\MemberAccessContentController@member_access_content_by_RO')->name('report_member_access_content_by_RO');
Route::group(['prefix' => 'report', 'middleware' => ['auth']], function () {
  Route::match(['get','post'],'/access-content-by-user', 'Report\MemberAccessByUserController@access_content_by_user')->name('report_access_content_by_user');
  Route::get('/excel-users', 'Report\MemberAccessByUserController@access_content_by_user_excel')->name('report_access_content_by_user_excel');
  // REVIEW
  Route::group(['prefix' => 'review'], function () {
    Route::get('/', 'Report\ReviewController@review_index')->name('report_review_index');
    Route::get('/create/{training_id}', 'Report\ReviewController@review_create')->name('report_review_create');
    Route::get('/create_answer/{review_id}', 'Report\ReviewController@review_create_answer_text')->name('report_review_create_answer_text');
  });
});

// CRONTAB
Route::group(['prefix' => 'crontab'], function () {
  Route::group(['prefix' => 'report'], function () {
    Route::get('/access-content-by-user', 'Crontab\ReportController@access_content_by_user')->name('crontab_report_access_content_by_user');
    Route::get('/access-content-excel', 'Report\MemberAccessByUserController@crontab_access_content_excel')->name('crontab_report_crontab_access_content_excel');

    Route::get('/update_branch', 'Crontab\ReportController@update_branch')->name('crontab_report_update_branch');
  });
});