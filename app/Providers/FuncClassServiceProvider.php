<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;

class FuncClassServiceProvider extends ServiceProvider
{
    public function register ()
    {
        App::bind('FuncClass', function () {
            return new \App\Classes\FuncClass;
        });
    }
}
