<?php
namespace App\Http\Controllers\Report;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class Export_Report_training_member_access_by_user implements FromView
{

  public function __construct($group_name, $datas)
  {
    ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 1800);
    $this->datas = $datas;
    $this->group_name = $group_name;
  }
  
  public function view(): View
  {
    return view('excel.training_member_access_content_by_user', [
      'datas' => $this->datas,
      'group_name' => $this->group_name,
    ]);
  }
}
