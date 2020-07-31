<?php
namespace App\Http\Controllers\Report;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class Export_Report_member_access_by_director implements FromView
{

  public function __construct($group_name,$datas)
  {
    ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 1800);
    $this->datas = $datas;
    $this->group_name = $group_name;
  }
  
  public function view(): View
  {
    return view('excel.member_access_content_by_director', [
      'datas' => $this->datas,
      'group_name' => $this->group_name,
    ]);
  }
}
