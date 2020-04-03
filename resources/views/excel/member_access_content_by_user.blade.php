<table>
  <thead>
    @if(Auth::user()->type=='jasmine')
    <tr>
      <th colspan=17>Name <span class="text-primary">{{ Auth::user()->user_info['thai_fullname'] }}</span></th>
    </tr>
    <tr>
      <th colspan=17>DivisionName <span class="text-primary">{{ Auth::user()->user_info['division'] }}</span></th>
    </tr>
    <tr>
      <th colspan=17>SectionName <span class="text-primary">{{ Auth::user()->user_info['section'] }}</span></th>
    </tr>
    <tr>
      <th colspan=17>DeptName <span class="text-primary">{{ Auth::user()->user_info['department'] }}</span></th>
    </tr>
    <tr>
      <th></th>
    </tr>
    @endif
    <tr>
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
      <th class="text-center align-middle">StaffGrade</th>
      <th class="text-center align-middle">JobFamily</th>
      <th class="text-center align-middle">Status</th>
      <th class="text-center align-middle">Pretest</th>
      <th class="text-center align-middle">Posttest</th>
      <th class="text-center align-middle">Course Complete</th>
    </tr>
  </thead>
  <tbody>
    @if(count($datas))
      @foreach($datas as $data)
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

        @if(!empty($data->firstname)) 
          <td class="text-center">{{ $data->firstname }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->lastname)) 
          <td class="text-center">{{ $data->lastname }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->workplace)) 
          <td class="text-center">{{ $data->workplace }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->title)) 
          <td class="text-center">{{ $data->title }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->division)) 
          <td class="text-center">{{ $data->division }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->section)) 
          <td class="text-center">{{ $data->section }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->department)) 
          <td class="text-center">{{ $data->department }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->branch)) 
          <td class="text-center">{{ $data->branch }}</td>
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

        @if(!empty($data->play_course)) 
          <td class="text-left text-success">เข้าเรียนแล้ว</td>
        @else 
          <td class="text-left text-danger">ยังไม่เข้าเรียน</td>
        @endif

        @if(!empty($data->pretest)) 
        <td class="text-center">{{ $data->pretest }}</td>
        @else 
        <td class="text-center">-</td>
        @endif

        @if(!empty($data->posttest)) 
          <td class="text-center">{{ $data->posttest }}</td>
        @else 
          <td class="text-center">-</td>
        @endif

        @if(!empty($data->play_course_end)) 
          <td class="text-center">{{ $data->play_course_end }}</td>
        @else 
          <td class="text-center">0</td>
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