<table>
  <thead>
    <tr class="">
    <th class="text-center align-middle">#</th>
    <th class="text-center align-middle">EmployeeId</th>
    <th class="text-center align-middle">Tinitial</th>
    <th class="text-center align-middle">TFName</th>
    <th class="text-center align-middle">TLName</th>
    <th class="text-center align-middle">Workplace</th>
    <th class="text-center align-middle">TitleName</th>
    <th class="text-center align-middle">DivisionName</th>
    <th class="text-center align-middle">SectionName</th>
    <th class="text-center align-middle">DeptName</th>
    <th class="text-center align-middle">BranchName</th>
    <th class="text-center align-middle">Region</th>
    <th class="text-center align-middle">StaffGrade</th>
    <th class="text-center align-middle">JobFamily</th>
    </tr>
  </thead>
  <tbody>
    @if(count($employee))
      @foreach($employee as $data)
      <tr>
        <td class="text-center">{{ $loop->iteration }}</td>

        @if(!empty($data->employee_id)) 
          <td class="text-center">{{ $data->employee_id }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->tinitial)) 
          <td class="text-center">{{ $data->tinitial }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->tf_name)) 
          <td class="text-center">{{ $data->tf_name }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->tl_name)) 
          <td class="text-center">{{ $data->tl_name }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->workplace)) 
          <td class="text-center">{{ $data->workplace }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->title_name)) 
          <td class="text-center">{{ $data->title_name }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->division_name)) 
          <td class="text-center">{{ $data->division_name }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->section_name)) 
          <td class="text-center">{{ $data->section_name }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->dept_name)) 
          <td class="text-center">{{ $data->dept_name }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->branch_name)) 
          <td class="text-center">{{ $data->branch_name }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->region)) 
          <td class="text-center">{{ $data->region }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->staff_grade)) 
          <td class="text-center">{{ $data->staff_grade }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->job_family)) 
          <td class="text-center">{{ $data->job_family }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

      </tr>
      @endforeach
    @else
      <tr>
        <td colspan=99 class="text-center">ไม่มีข้อมูล</td>
      </tr>
    @endif
  </tbody>
</table>