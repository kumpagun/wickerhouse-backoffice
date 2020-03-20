<table class="table table-striped table-bordered zero-configuration">
  <thead>
    <tr class="">
    <th class="text-center align-middle">#</th>
    <th class="text-center align-middle">Giftcode</th>
    <th class="text-center align-middle">EmployeeId</th>
    <th class="text-center align-middle">Tinitial</th>
    <th class="text-center align-middle">TFName</th>
    <th class="text-center align-middle">TLName</th>
    <th class="text-center align-middle">Email</th>
    <th class="text-center align-middle">Workplace</th>
    <th class="text-center align-middle">TitleName</th>
    <th class="text-center align-middle">DivisionName</th>
    <th class="text-center align-middle">SectionName</th>
    <th class="text-center align-middle">DeptName</th>
    <th class="text-center align-middle">BranchName</th>
    </tr>
  </thead>
  <tbody>
    @if(count($datas))
      @foreach($datas as $data)
      <tr>
        <td class="text-center">{{ $loop->iteration }}</td>

        <td class="text-center">{{ $data->code }}</td>
        @if(!empty($data->employees->employee_id)) 
          <td class="text-center">{{ $data->employees->employee_id }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->employees->tinitial)) 
          <td class="text-center">{{ $data->employees->tinitial }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->employees->tf_name)) 
          <td class="text-center">{{ $data->employees->tf_name }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->employees->tl_name)) 
          <td class="text-center">{{ $data->employees->tl_name }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        <td class="text-center">{{ Member::getUserEmailFromEmployeeId($data->employees->employee_id) }}</td>

        @if(!empty($data->employees->workplace)) 
          <td class="text-center">{{ $data->employees->workplace }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->employees->title_name)) 
          <td class="text-center">{{ $data->employees->title_name }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->employees->division_name)) 
          <td class="text-center">{{ $data->employees->division_name }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->employees->section_name)) 
          <td class="text-center">{{ $data->employees->section_name }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->employees->dept_name)) 
          <td class="text-center">{{ $data->employees->dept_name }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->employees->branch_name)) 
          <td class="text-center">{{ $data->employees->branch_name }}</td>
        @else 
          <td class="text-center">-</td>
        @endif
      </tr>
      @endforeach
    @else
      <tr>
        <td colspan="9" class="text-center">ไม่มีข้อมูล</td>
      </tr>
    @endif
  </tbody>
</table>