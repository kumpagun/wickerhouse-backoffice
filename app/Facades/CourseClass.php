<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CourseClass extends Facade
{
    protected static function getFacadeAccessor () { return 'CourseClass'; }
}
