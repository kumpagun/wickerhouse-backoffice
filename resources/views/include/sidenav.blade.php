<!-- ////////////////////////////////////////////////////////////////////////////-->
<div class="main-menu menu-fixed menu-light menu-accordion" data-scroll-to-active="true">
  <div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
      
      <li class="nav-item">
        <a href="{{ route('report_member_access_content_by_RO') }}"><i class="ft-bar-chart-2"></i><span class="menu-title" data-i18n="">Dashboard</span></a>
      </li>

      @hasrole('admin|course')
      <li class="navigation-header">
        <span>วิทยากร</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right" data-original-title="Teacher"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('teacher_index') }}"><i class="ft-user"></i><span class="menu-title" data-i18n="">วิทยากรทั้งหมด</span></a>
      </li>
      @endhasrole

      @hasrole('admin|course')
      <li class="navigation-header">
        <span>หลักสูตรการเรียน</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right" data-original-title="Course"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('category_index') }}"><i class="ft-list"></i><span class="menu-title" data-i18n="">ประเภทของหลักสูตร</span></a>
      </li>
      <li class="nav-item">
        <a href="{{ route('course_index') }}"><i class="ft-tv"></i><span class="menu-title" data-i18n="">หลักสูตรทั้งหมด</span></a>
      </li>
      @endhasrole
      
      @hasrole('admin|homework')
      <li class="navigation-header">
        <span>แบบฝึกหัด</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right" data-original-title="Course"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('homework_index') }}"><i class="ft-edit"></i><span class="menu-title" data-i18n="">ตรวจแบบฝึกหัดหลังเรียน</span></a>
      </li>
      @endhasrole
      
      @hasrole('admin|question')
      <li class="navigation-header">
        <span>คำถาม</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right" data-original-title="Course"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('question_index') }}"><i class="ft-edit"></i><span class="menu-title" data-i18n="">ถาม-ตอบ</span></a>
      </li>
      @endhasrole

      @hasrole('admin|course')
      <li class="navigation-header">
        <span>รอบการอบรม</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right" data-original-title="Course"></i>
      </li>
      <li class="nav-item">
        <a href="{{route('company_index')}}"><i class="ft-users"></i><span class="menu-title" data-i18n="">บริษัท</span></a>
      </li>
      <li class="nav-item">
        <a href="{{route('department_index')}}"><i class="ft-users"></i><span class="menu-title" data-i18n="">แผนก</span></a>
      </li>
      <li class="nav-item">
        <a href="{{ route('training_index') }}"><i class="ft-tv"></i><span class="menu-title" data-i18n="">รอบอบรมทั้งหมด</span></a>
      </li>
      @endhasrole

      @hasrole('admin')
      <li class="navigation-header">
        <span>Admin</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right" data-original-title="Admin"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('users_index') }}"><i class="ft-user"></i><span class="menu-title" data-i18n="">List user</span></a>
      </li>
      <li class="nav-item">
        <a href="{{ route('roles_index') }}"><i class="ft-unlock"></i><span class="menu-title" data-i18n="">Roles</span></a>
      </li>
      <li class="nav-item">
        <a href="{{ route('permissions_index') }}"><i class="ft-sliders"></i><span class="menu-title" data-i18n="">Permissions</span></a>
      </li>
      @endhasrole

    </ul>
  </div>
</div>