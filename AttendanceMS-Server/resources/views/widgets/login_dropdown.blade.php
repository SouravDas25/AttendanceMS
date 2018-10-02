<ul class="nav navbar-nav navbar-right {{ isset($ld_class)? $ld_class : "" }} ">
    <li class="dropdown ">
        <a class="dropdown-toggle waves-effect waves-dark " data-toggle="dropdown" 
        href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-user fa-fw " ></i> 
            {{ Utility::is_loged_in() ? Utility::get_user_name() : "User Profile" }}
            <span class="caret"></span >
        </a>
        <ul class="dropdown-menu dropdown-user grey lighten-4">
            <li >
            <a href="{{ $login_email_link }}" >
            <span class="glyphicon  {{ ($login_email_link == '/')? 'glyphicon-th-large' : 'glyphicon-home' }} glyphicon-th-large text-warning" 
            aria-hidden="true">
            </span>
            {{ Utility::is_loged_in() ? Utility::get_user_email() : "Laravel 5" }}
            </a>
            </li>
            <li >
                <a href="{{route('home.settings')}}">
                <span class="glyphicon glyphicon-cog text-info" aria-hidden="true" ></span> 
                <span >Account Settings</span>
                </a>
            </li>
            <li class="divider"></li>
            <li class="text-danger ">
                <a href="{{ url ('logout') }}" >
                    <span class="glyphicon glyphicon-log-out text-danger " aria-hidden="true">
                    </span> 
                    Logout
                </a>
            </li>
        </ul>
    </li>
</ul>