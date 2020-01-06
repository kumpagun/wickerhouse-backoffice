<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;

class TeacherClassServiceProvider extends ServiceProvider
{
    public function register ()
    {
        App::bind('TeacherClass', function () {
            return new \App\Classes\TeacherClass;
        });
    }
}