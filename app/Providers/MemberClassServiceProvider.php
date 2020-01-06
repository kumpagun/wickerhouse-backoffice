<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;

class MemberClassServiceProvider extends ServiceProvider
{
    public function register ()
    {
        App::bind('MemberClass', function () {
            return new \App\Classes\MemberClass;
        });
    }
}