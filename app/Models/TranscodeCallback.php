<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class TranscodeCallback extends Eloquent  
{
  protected $collection = 'transcode_callbacks';
}