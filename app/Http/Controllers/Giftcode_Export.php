<?php
namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class Giftcode_Export implements FromView
{
  public function __construct($datas)
  {
    $this->datas = $datas;
  }
  
  public function view(): View
  {
    return view('excel.giftcode_usage', [
      'datas' => $this->datas
    ]);
  }
}
