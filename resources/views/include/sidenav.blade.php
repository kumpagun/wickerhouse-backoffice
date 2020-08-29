<!-- ////////////////////////////////////////////////////////////////////////////-->
<div class="main-menu menu-fixed menu-light menu-accordion" data-scroll-to-active="true">
  <div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

      @hasrole('admin|product')
      <li class="navigation-header">
        <span>Menu</span><i class=" feather icon-minus" data-toggle="tooltip" data-placement="right" data-original-title="หลักสูตรการเรียน"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('banner_index') }}"><i class="feather icon-image"></i><span class="menu-title" data-i18n="">Banner</span></a>
      </li>
      <li class="nav-item">
        <a href="{{ route('category_index') }}"><i class="feather icon-grid"></i><span class="menu-title" data-i18n="">Category</span></a>
      </li>
      <li class="nav-item">
        <a href="{{ route('product_index') }}"><i class="feather icon-tag"></i><span class="menu-title" data-i18n="">All Product</span></a>
      </li>
      <li class="nav-item">
        <a href="#"><i class="feather icon-shopping-cart"></i><span class="menu-title" data-i18n="">Shopping</span></a>
      </li>
      @endhasrole

      @hasrole('admin|product')
      <li class="navigation-header">
        <span>Report</span><i class=" feather icon-minus" data-toggle="tooltip" data-placement="right" data-original-title="หลักสูตรการเรียน"></i>
      </li>
      <li class="nav-item">
        <a href="#"><i class="feather icon-trending-up"></i><span class="menu-title" data-i18n="">Page view</span></a>
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