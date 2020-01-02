<!-- ////////////////////////////////////////////////////////////////////////////-->
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
  <div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
      {{-- <li class=" nav-item">
        <a href="index.html"><i class="ft-home"></i><span class="menu-title" data-i18n="">Dashboard</span><span class="badge badge badge-primary badge-pill float-right mr-2">3</span></a>
        <ul class="menu-content">
          <li><a class="menu-item" href="dashboard-ecommerce.html">eCommerce</a>
          </li>
          <li><a class="menu-item" href="dashboard-analytics.html">Analytics</a>
          </li>
          <li><a class="menu-item" href="dashboard-fitness.html">Fitness</a>
          </li>
        </ul>
      </li> --}}
      <li class="navigation-header">
        <span>Teacher</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right" data-original-title="Teacher"></i>
      </li>
      <li class="nav-item {{ Page::activeMenu('555') }}">
        <a href="{{ route('teacher_index') }}"><i class="ft-user"></i><span class="menu-title" data-i18n="">List Teacher</span></a>
      </li>

      @hasrole('admin|course')
      <li class=" navigation-header">
        <span>Course</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right" data-original-title="Course"></i>
      </li>
      <li class="nav-item {{ Page::activeMenu(route('course_index')) }}">
        <a href="{{ route('course_index') }}"><i class="ft-tv"></i><span class="menu-title" data-i18n="">List Course</span></a>
      </li>
      <li class="nav-item {{ Page::activeMenu(route('category_index')) }}">
        <a href="{{ route('category_index') }}"><i class="ft-list"></i><span class="menu-title" data-i18n="">Category</span></a>
      </li>
      {{-- <li class="nav-item disabled {{ Page::activeMenu('555') }}">
        <a href="#"><i class="ft-clipboard"></i><span class="menu-title" data-i18n="">Examination</span></a>
      </li>
      <li class="nav-item {{ Page::activeMenu(route('homework_index')) }}">
        <a href="{{ route('homework_index') }}"><i class="ft-edit"></i><span class="menu-title" data-i18n="">Homework</span></a>
      </li> --}}

      <li class=" navigation-header">
        <span>Training</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right" data-original-title="Course"></i>
      </li>
      <li class="nav-item {{ Page::activeMenu(route('training_index')) }}">
        <a href="{{ route('training_index') }}"><i class="ft-tv"></i><span class="menu-title" data-i18n="">List Training</span></a>
      </li>
      <li class="nav-item {{ Page::activeMenu(route('company_index')) }}">
        <a href="{{route('company_index')}}"><i class="ft-filter"></i><span class="menu-title" data-i18n="">Company</span></a>
      </li>
      <li class="nav-item {{ Page::activeMenu(route('department_index')) }}">
        <a href="{{route('department_index')}}"><i class="ft-filter"></i><span class="menu-title" data-i18n="">Department</span></a>
      </li>
      @endhasrole

      @hasrole('admin')
      <li class=" navigation-header">
        <span>Admin</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right" data-original-title="Admin"></i>
      </li>
      <li class="nav-item {{ Page::activeMenu(route('users_index')) }}">
        <a href="{{ route('users_index') }}"><i class="ft-user"></i><span class="menu-title" data-i18n="">List user</span></a>
      </li>
      <li class="nav-item {{ Page::activeMenu(route('roles_index')) }}">
        <a href="{{ route('roles_index') }}"><i class="ft-unlock"></i><span class="menu-title" data-i18n="">Roles</span></a>
      </li>
      <li class="nav-item {{ Page::activeMenu(route('permissions_index')) }}">
        <a href="{{ route('permissions_index') }}"><i class="ft-sliders"></i><span class="menu-title" data-i18n="">Permissions</span></a>
      </li>
      @endhasrole

    </ul>
  </div>
</div>