<nav class=" grey lighten-4" role="navigation">
    <div class="nav-wrapper container">
        <a id="logo-container" href="{{ Utility::is_loged_in() ? url('/home') : url('/') }}"
           class="brand-logo" style="font-size:1.2em;">Attendance MS</a>
        <ul class="right hide-on-med-and-down">
            <li>
                @if( Utility::is_loged_in() )
                    <a href="{{ url('/home') }}">
                        Dashboard
                    </a>
                @else
                    <a onclick="$('#modal1').modal('open');">
                        Sign In
                    </a>
                @endif
            </li>
        </ul>

        <ul id="nav-mobile" class="side-nav">
            <li>
                @if( Utility::is_loged_in() )
                    <a href="{{ url('/home') }}">
                        Dashboard
                    </a>
                @else
                    <a onclick="$('#modal1').modal('open');">
                        Sign In
                    </a>
                @endif
            </li>
            <li>
                <a href="#cya">
                    Check Attendance
                </a>
            </li>
        </ul>
        <a href="#" data-activates="nav-mobile" class="button-collapse">
            <i class="material-icons">menu</i>
        </a>
    </div>
</nav>
