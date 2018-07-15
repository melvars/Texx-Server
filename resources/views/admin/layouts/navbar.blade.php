<header class="main-header">

    <a href="/" class="logo">
        <span class="logo-mini"><b>BEAM</b></span>
        <span class="logo-lg"><b>BEAM</b>-Messenger</span>
    </a>

    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="/avatar/{{ $user->id }}" class="user-image" alt="User Image">
              <span class="hidden-xs">{{ $user->name }}</span>
            </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="/avatar/{{ $user->id }}" class="img-circle" alt="User Image">
                            <p>
                                {{ $user->name }} - {{ $user->email }}
                                <small>Member since {{ $user->created_at !== null ? $user->created_at : "ever." }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="/profile" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="/logout" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>

    </nav>
</header>