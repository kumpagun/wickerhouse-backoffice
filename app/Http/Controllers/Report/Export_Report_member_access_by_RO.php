<?php
namespace App\Http\Controllers\Report;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class Export_Report_member_access_by_RO implements FromView
{

  public function __construct($training_title,$datas,$data_total)
  {
    ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 1800);
    $this->training_title = $training_title;
    $this->datas = $datas;
    $this->data_total = $data_total;
  }
  
  public function view(): View
  {
    return view('excel.member_access_content_by_ro', [
      'training_title' => $this->training_title,
      'datas' => $this->datas,
      'data_total' => $this->data_total
    ]);
  }
}
