<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class UserClass extends Facade
{
    protected static function getFacadeAccessor () { return 'UserClass'; }
}