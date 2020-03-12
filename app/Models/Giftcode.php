<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Giftcode extends Eloquent  
{
  protected $collection = 'giftcodes';
  protected $fillable = [
    'group_id',
    'title',
    'code',
    'active',
    'status'
  ];
}