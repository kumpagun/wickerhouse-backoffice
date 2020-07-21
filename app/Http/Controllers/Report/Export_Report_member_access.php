<?php
namespace App\Http\Controllers\Report;

use App\Models\Report_member_access;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Export_Report_member_access implements FromQuery, WithMapping, WithHeadings
{
  use Exportable;
  public $training_id;
  public $course_id;
  public $count = 0;

  public function __construct($training_id,$course_id)
  {
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 1800);
    
    $this->training_id = $training_id;
    $this->course_id = $course_id;
    $this->count = 1;
  }

  public function headings(): array
  {
    return [
      '#',
      'EmployeeId',
      'Tinitial',
      'TFName',
      'TLName',
      'Workplace',
      'TitleName',
      'DivisionName',
      'SectionName',
      'DeptName',
      'StaffGrade',
      'JobFamily',
      'Status',
      'Pretest',
      'Posttest',
      'Course Complete',
    ];
  }

  public function map($report): array
  {
    if($report->play_course!=0) {
      $status = 'เข้าเรียนแล้ว';
    } else { 
      $status = 'ยังไม่เข้าเรียน'; 
    }
    $count = $this->count++;
    return [
      $count,
      $report->employee_id,
      $report->tinitial,
      $report->firstname,
      $report->lastname,
      $report->workplace,
      $report->title,
      $report->division,
      $report->section,
      $report->department,
      $report->staff_grade,
      $report->job_family,
      $status,
      $report->pretest,
      $report->posttest,
      $report->play_course_end
    ];
  }

  public function query()
  {
    $training_id = $this->training_id;
    $course_id = $this->course_id;
    return Report_member_access::query()->where('training_id',$training_id)->where('course_id',$course_id)->where('status',1);
  }
}