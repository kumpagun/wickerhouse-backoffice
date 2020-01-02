<?php

namespace App\Classes;
use Request;
use User as UserClass;
use App\User;

class PageClass
{
  public function openMenu ($uri='')
  {
    $active = '';
    if (is_array($uri)) {
      foreach ($uri as $each_uri) {
        if (Request::is(Request::segment(0). $each_uri . '/*') || Request::is(Request::segment(0). $each_uri) || Request::is($each_uri)) {
          $active = 'open';
        }
      }
    } else if (is_string($uri)) {
      if (Request::is(Request::segment(0). $uri . '/*') || Request::is(Request::segment(0). $uri) || Request::is($uri)) {
        $active = 'open';
      }
    }
    return $active;
  }

  public function activeMenu ($uri='')
  {
    $active = '';
    if (Request::is(Request::segment(0). $uri . '/*') || Request::is(Request::segment(0). $uri) || Request::is($uri)) {
      $active = 'active';
    }
    
    return $active;
  }
  public function active_bullet_menu()
  {
    $roles = UserClass::get_roleId_not_approve('guest');
    $active = User::where('status',0)->where('collection','users')->count();
    return $active;
  }

  public function activeMenuExact ($uri='')
  {
    $active = '';
    if (Request::is(Request::segment(0). $uri) || Request::is($uri)) {
      $active = 'active';
    }
    return $active;
  }
}
