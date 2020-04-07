<!-- ////////////////////////////////////////////////////////////////////////////-->
<div class="main-menu menu-fixed menu-light menu-accordion" data-scroll-to-active="true">
  <div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
      
      <li class="nav-item">
        <a href="{{ route('report_member_access_content_by_RO') }}"><i class="feather icon-bar-chart-2"></i><span class="menu-title" data-i18n="">Dashboard</span></a>
      </li>
      @if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin'))
      <li class="nav-item">
        <a href="{{ route('report_access_content_by_user') }}"><i class="feather icon-user"></i><span class="menu-title" data-i18n="">รายงานการใช้งานรายบุคคล</span></a>
      </li>
      @endif
      @hasrole('admin|banner')
      <li class="nav-item">
        <a href="{{ route('banner_index') }}"><i class="feather icon-square"></i><span class="menu-title" data-i18n="">แบนเนอร์</span></a>
      </li>
      @endhasrole

      @hasrole('admin|course')
      <li class="navigation-header">
        <span>วิทยากร</span><i class=" feather icon-minus" data-toggle="tooltip" data-placement="right" data-original-title="วิทยากร"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('teacher_index') }}"><i class="feather icon-user"></i><span class="menu-title" data-i18n="">วิทยากรทั้งหมด</span></a>
      </li>
      @endhasrole

      @hasrole('admin|course')
      <li class="navigation-header">
        <span>หลักสูตรการเรียน</span><i class=" feather icon-minus" data-toggle="tooltip" data-placement="right" data-original-title="หลักสูตรการเรียน"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('category_index') }}"><i class="feather icon-list"></i><span class="menu-title" data-i18n="">ประเภทของหลักสูตร</span></a>
      </li>
      <li class="nav-item">
        <a href="{{ route('course_index') }}"><i class="feather icon-tv"></i><span class="menu-title" data-i18n="">หลักสูตรทั้งหมด</span></a>
      </li>
      @endhasrole
      
      @hasrole('admin|homework')
      <li class="navigation-header">
        <span>แบบฝึกหัด</span><i class=" feather icon-minus" data-toggle="tooltip" data-placement="right" data-original-title="แบบฝึกหัด"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('homework_index') }}"><i class="feather icon-edit"></i><span class="menu-title" data-i18n="">ตรวจแบบฝึกหัดหลังเรียน</span></a>
      </li>
      @endhasrole
      
      @hasrole('admin|question')
      <li class="navigation-header">
        <span>คำถาม</span><i class=" feather icon-minus" data-toggle="tooltip" data-placement="right" data-original-title="คำถาม"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('question_index') }}"><i class="feather icon-edit"></i><span class="menu-title" data-i18n="">ถาม-ตอบ</span></a>
      </li>
      @endhasrole

      @hasrole('admin|course')
      <li class="navigation-header">
        <span>รอบการอบรม</span><i class=" feather icon-minus" data-toggle="tooltip" data-placement="right" data-original-title="รอบการอบรม"></i>
      </li>
      <li class="nav-item">
        <a href="{{route('department_index')}}"><i class="feather icon-users"></i><span class="menu-title" data-i18n="">บริษัท / แผนก</span></a>
      </li>
      <li class="nav-item">
        <a href="{{ route('training_index') }}"><i class="feather icon-tv"></i><span class="menu-title" data-i18n="">รอบอบรมทั้งหมด</span></a>
      </li>
      <li class="nav-item">
        <a href="{{ route('employee_vip_index') }}"><i class="feather icon-user"></i><span class="menu-title" data-i18n=""> สมาชิก VIP</span></a>
      </li>
      @hasrole('admin|certificate')
      <li class="nav-item">
        <a href="{{ route('certificate_index') }}"><i class="feather icon-thumbs-up"></i><span class="menu-title" data-i18n=""> Certificate</span></a>
      </li>
      @endhasrole
      @endhasrole

      @hasrole('admin|giftcode')
      <li class="navigation-header">
        <span>ของรางวัล</span><i class=" feather icon-minus" data-toggle="tooltip" data-placement="right" data-original-title="ของรางวัล"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('giftcode_group_index') }}"><i class="feather icon-star"></i><span class="menu-title" data-i18n=""> กิจกรรมแจกของรางวัล</span></a>
      </li>
      <li class="nav-item">
        <a href="{{ route('giftcode_usage') }}"><i class="feather icon-user"></i><span class="menu-title" data-i18n=""> รายงานผู้โชคดีที่ได้รับรางวัล</span></a>
      </li>
      @endhasrole

      @hasrole('admin|report')
      <li class="navigation-header">
        <span>รายงาน</span><i class="feather icon-minus" data-toggle="tooltip" data-placement="right" data-original-title="รายงาน"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('report_access_content_by_user') }}"><i class="feather icon-user"></i><span class="menu-title" data-i18n="">รายงานการใช้งานรายบุคคล</span></a>
      </li>
      <li class="nav-item">
        <a href="{{ route('report_review_index') }}"><i class="feather icon-message-circle"></i><span class="menu-title" data-i18n="">ประเมินหลักสูตรหลังเรียน</span></a>
      </li>
      @endhasrole

      @hasrole('admin')
      <li class="navigation-header">
        <span>Admin</span><i class="feather icon-minus" data-toggle="tooltip" data-placement="right" data-original-title="Admin"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('users_index') }}"><i class="feather icon-user"></i><span class="menu-title" data-i18n="">List user</span></a>
      </li>
      {{-- <li class="nav-item">
        <a href="{{ route('roles_index') }}"><i class="feather icon-unlock"></i><span class="menu-title" data-i18n="">Roles</span></a>
      </li>
      <li class="nav-item">
        <a href="{{ route('permissions_index') }}"><i class="feather icon-sliders"></i><span class="menu-title" data-i18n="">Permissions</span></a>
      </li> --}}
      @endhasrole

    </ul>
  </div>
</div>