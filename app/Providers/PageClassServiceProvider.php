<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;

class PageClassServiceProvider extends ServiceProvider
{
  public function register ()
  {
    App::bind('PageClass', function () {
      return new \App\Classes\PageClass;
    });
  }
}