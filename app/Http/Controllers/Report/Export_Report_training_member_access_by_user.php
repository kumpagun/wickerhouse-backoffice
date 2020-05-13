<?php
namespace App\Http\Controllers\Report;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class Export_Report_training_member_access_by_user implements FromView
{

  public function __construct($datas)
  {
    $this->datas = $datas;
  }
  
  public function view(): View
  {
    return view('excel.training_member_access_content_by_user', [
      'datas' => $this->datas,
    ]);
  }
}
