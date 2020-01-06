<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;

class CourseClassServiceProvider extends ServiceProvider
{
    public function register ()
    {
        App::bind('CourseClass', function () {
            return new \App\Classes\CourseClass;
        });
    }
}