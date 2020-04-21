<?php
namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class Export_Email_log implements FromView
{

  public function __construct($employee)
  {
    $this->employee = $employee;
  }
  
  public function view(): View
  {
    return view('excel.email_log', [
      'employee' => $this->employee,
    ]);
  }
}
