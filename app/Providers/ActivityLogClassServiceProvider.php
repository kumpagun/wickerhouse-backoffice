<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;

class ActivityLogClassServiceProvider extends ServiceProvider
{
    public function register ()
    {
        App::bind('ActivityLogClass', function () {
            return new \App\Classes\ActivityLogClass;
        });
    }
}