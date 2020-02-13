<!-- fixed-top-->
<nav class="header-navbar navbar-expand-md navbar navbar-with-menu fixed-top navbar-light navbar-border">
  <div class="navbar-wrapper">
    <div class="navbar-header">
      <ul class="nav navbar-nav flex-row">
        <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
        <li class="nav-item">
          <a class="navbar-brand" href="/">
            {{-- <img class="brand-logo" alt="stack admin logo" src="{{ asset('stack-admin/app-assets/images/logo/stack-logo-light.png') }}"> --}}
            <h2 class="brand-text"><span class="color-1">JAS</span> <span class="color-2">Online</span></h2>
          </a>
        </li>
        <li class="nav-item d-md-none">
          <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="fa fa-ellipsis-v"></i></a>
        </li>
      </ul>
    </div>
    <div class="navbar-container content">
      <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="nav navbar-nav mr-auto float-left">
          <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu"></i></a></li>
        </ul>
        <ul class="nav navbar-nav float-right">
          <li class="dropdown dropdown-user nav-item">
            <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
              <span class="avatar avatar-online">
                <img src="{{ asset('stack-admin/app-assets/images/portrait/small/avatar-s-1.png') }}" alt="avatar"><i></i></span>
              <span class="user-name">{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              {{-- <a class="dropdown-item" href="user-profile.html"><i class="ft-user"></i> Edit Profile</a>
              <a class="dropdown-item" href="email-application.html"><i class="ft-mail"></i> My Inbox</a>
              <a class="dropdown-item" href="user-cards.html"><i class="ft-check-square"></i> Task</a>
              <a class="dropdown-item" href="chat-application.html"><i class="ft-message-square"></i> Chats</a>
              <div class="dropdown-divider"></div> --}}
              <a class="dropdown-item" href="{{ route('auth_signout') }}"><i class="ft-power"></i> Logout</a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>