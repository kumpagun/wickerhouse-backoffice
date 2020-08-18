<?php
namespace App\Http\Controllers\Report;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class Export_Review_Byuser implements FromView
{

  public function __construct($course,$review_group,$review_group_arr,$total_reviews_arr,$reviews,$reviews_arr,$employees,$datas_report,$datas_report_createdAt)
  {
    ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 1800);
    $this->course = $course;
    $this->review_group = $review_group;
    $this->review_group_arr = $review_group_arr;
    $this->total_reviews_arr = $total_reviews_arr;
    $this->reviews = $reviews;
    $this->reviews_arr = $reviews_arr;
    $this->employees = $employees;
    $this->datas_report = $datas_report;
    $this->datas_report_createdAt = $datas_report_createdAt;
  }
  
  public function view(): View
  {
    return view('excel.review_training_byuser', [
      'course' => $this->course,
      'review_group' => $this->review_group,
      'review_group_arr' => $this->review_group_arr,
      'total_reviews_arr' => $this->total_reviews_arr,
      'reviews' => $this->reviews,
      'reviews_arr' => $this->reviews_arr,
      'employees' => $this->employees,
      'datas_report' => $this->datas_report,
      'datas_report_createdAt' => $this->datas_report_createdAt
    ]);
  }
}
