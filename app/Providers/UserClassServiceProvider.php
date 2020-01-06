<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;

class UserClassServiceProvider extends ServiceProvider
{
    public function register ()
    {
        App::bind('UserClass', function () {
            return new \App\Classes\UserClass;
        });
    }
}