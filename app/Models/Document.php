<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Document extends Eloquent  
{
  protected $collection = 'documents';
  protected $fillable = [
    'title',
    'document_path',
    'document_paths'
  ];
  
}