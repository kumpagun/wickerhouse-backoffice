<?php
namespace App\Http\Controllers\Report;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class Export_Review_Training implements FromView
{

  public function __construct($training,$review_group,$reviews,$data_question,$data_choice,$datas_report,$count_report,$data_total)
  {
    ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 1800);
    $this->training = $training;
    $this->review_group = $review_group;
    $this->reviews = $reviews;
    $this->data_question = $data_question;
    $this->data_choice = $data_choice;
    $this->datas_report = $datas_report;
    $this->count_report = $count_report;
    $this->data_total = $data_total;
  }
  
  public function view(): View
  {
    return view('excel.review', [
      'training' => $this->training,
      'review_group' => $this->review_group,
      'reviews' => $this->reviews,
      'data_question' => $this->data_question,
      'data_choice' => $this->data_choice,
      'datas_report' => $this->datas_report,
      'count_report' => $this->count_report,
      'data_total' => $this->data_total
    ]);
  }
}
