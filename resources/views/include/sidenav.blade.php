<!-- ////////////////////////////////////////////////////////////////////////////-->
<div class="main-menu menu-fixed menu-light menu-accordion" data-scroll-to-active="true">
  <div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
      @hasrole('admin|course')
      <li class="navigation-header">
        <span>Teacher</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right" data-original-title="Teacher"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('teacher_index') }}"><i class="ft-user"></i><span class="menu-title" data-i18n="">List Teacher</span></a>
      </li>
      @endhasrole

      @hasrole('admin|course')
      <li class=" navigation-header">
        <span>Course</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right" data-original-title="Course"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('course_index') }}"><i class="ft-tv"></i><span class="menu-title" data-i18n="">List Course</span></a>
      </li>
      <li class="nav-item">
        <a href="{{ route('category_index') }}"><i class="ft-list"></i><span class="menu-title" data-i18n="">Category</span></a>
      </li>
      @endhasrole
      
      @hasrole('admin|course')
      <li class=" navigation-header">
        <span>Homework</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right" data-original-title="Course"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('homework_index') }}"><i class="ft-edit"></i><span class="menu-title" data-i18n="">ตรวจการบ้าน</span></a>
      </li>
      @endhasrole

      @hasrole('admin|course')
      <li class=" navigation-header">
        <span>Training</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right" data-original-title="Course"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('training_index') }}"><i class="ft-tv"></i><span class="menu-title" data-i18n="">List Training</span></a>
      </li>
      <li class="nav-item">
        <a href="{{route('company_index')}}"><i class="ft-users"></i><span class="menu-title" data-i18n="">Company</span></a>
      </li>
      <li class="nav-item">
        <a href="{{route('department_index')}}"><i class="ft-users"></i><span class="menu-title" data-i18n="">Department</span></a>
      </li>
      @endhasrole

      @hasrole('admin')
      <li class=" navigation-header">
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