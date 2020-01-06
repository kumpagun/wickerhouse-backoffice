<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MemberClass extends Facade
{
    protected static function getFacadeAccessor () { return 'MemberClass'; }
}