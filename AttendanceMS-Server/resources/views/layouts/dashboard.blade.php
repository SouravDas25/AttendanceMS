@extends('layouts.plane2')



@section('style')
    <style>
        label {
            font-size: 1.05em;
        }

        a:hover {
            text-decoration: none;
        }

        @media (max-width: 426px) {

            /*.navbar{
                    height:92px;
            }*/
            .navbar-side {
                top: 60px;
            }
        }

    </style>
    @yield('page_style')
@endsection



@section('body')
    <div id="wrapper">
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="navbar-header ">
                <button type="button" class="navbar-toggle waves-effect waves-dark"
                        data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand waves-effect waves-dark blue" href="/">
                    <i class="large material-icons">insert_chart</i>
                    <strong>Attendance MS</strong>
                </a>

                <div id="sideNav" href="">
                    <i class="material-icons dp48">toc</i>
                </div>
            </div>

            @include('widgets.login_dropdown',['login_email_link' => route('root'),'ld_class'=>'hidden-xs'])
        </nav>
        <!--/. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse ">
                <ul class="nav" id="main-menu">
                    <li>
                        <a class="waves-effect waves-dark" title="User">
                            <i class="glyphicon glyphicon-user"></i>
                            {{ Utility::is_loged_in() ? Utility::get_user_name() : "User Profile" }}
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="{{ url('/') }}" class="waves-effect waves-dark">
                                    <small><i class="fa fa-envelope"></i>
                                        {{ Utility::is_loged_in() ? Utility::get_user_email() : "Laravel 5" }}</small>
                                </a>
                            </li>
                            <li>
                                <a href="{{route('home.settings')}}">
                                    <small>
                                        <i class="fa fa-gear fa-fw"></i> Account Settings
                                    </small>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url ('logout') }}">
                                    <small>
                                        <i class="fa fa-sign-out fa-fw"></i> Logout
                                    </small>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li {!! (Request::is('*home') ? 'class="active-menu"' : '') !!}>
                        <a href="{{ url ('home') }}" class="waves-effect waves-dark" title="Home Page">
                            <i class="fa fa-dashboard"></i> Dashboard
                        </a>
                    </li>
                    <li {!! (Request::is('*home/myclass*') ? 'class="active-menu"' : '') !!} >
                        <a href="{{ url('home/myclass') }}" class="waves-effect waves-dark"
                           title="View Classes Taken By You">
                            <i class="fa fa-lastfm"></i>My Classes </a>
                    </li>
                    <li {!! (Request::is('*home/class*') ? 'class="active-menu"' : '') !!}>
                        <a href="{{ url('home/class') }}" class="waves-effect waves-dark"
                           title="Take A Class's Attendance">
                            <i class="fa fa-mortar-board"></i> Take Attendance</a>
                    </li>
                    <li>
                        <a href="#" class="waves-effect waves-dark">
                            <i class="fa fa-tripadvisor"></i> View Attendance
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level" aria-expanded="true">
                            <li {!! (Request::is('*home/batch*') ? 'class="active-menu"' : '') !!} >
                                <a href="{{ url('home/batch') }}" class="waves-effect waves-dark"
                                   title="View Attendance Of Current Semester.">
                                    <i class="fa fa-bar-chart-o"></i>Current Semester</a>
                            </li>
                            <li {!! (Request::is('*home/long.term*') ? 'class="active-menu"' : '') !!} >
                                <a href="{{ url('home/long.term') }}" class="waves-effect waves-dark"
                                   title="View Attendance Of Previous Semester.">
                                    <i class="fa fa-table"></i>Previous Semester</a>
                            </li>
                        </ul>
                    </li>
                    @if( Utility::is_user_admin() )
                        <li class="{{ Request::is('*home/dept*') ? 'active' : '' }}
                        {{ Request::is('*home/subject*') ? 'active' : '' }}
                        {{ Request::is('*home/batch/setting*') ? 'active' : '' }}
                        {{ Request::is('*home/student*') ? 'active' : '' }}
                        {{ Request::is('*home/faculty*') ? 'active' : '' }}">
                            <a href="#" class="waves-effect waves-dark">
                                <i class="fa fa-sitemap"></i> Admin Settings
                                <span class="fa arrow"></span>
                            </a>
                            <ul class="nav nav-second-level" aria-expanded="true">
                                <li {!! (Request::is('*home/dept*') ? 'class="active-menu"' : '') !!}>
                                    <a href="{{ url('/home/dept')  }}">
                                        <i class="fa fa-code-fork"></i>
                                        Departments
                                    </a>
                                </li>
                                <li {!! (Request::is('*home/subject*') ? 'class="active-menu"' : '') !!}>
                                    <a href="{{ url('/home/subject') }}">
                                        <i class="fa fa-align-justify"></i>
                                        Subjects
                                    </a>
                                </li>
                                <li {!! (Request::is('*home/batch/setting*') ? 'class="active-menu"' : '') !!}>
                                    <a href="{{ url('/home/batch/setting/index') }}">
                                        <i class="fa fa-users"></i>
                                        Batches
                                    </a>
                                </li>
                                <li {!! (Request::is('*home/student*') ? 'class="active-menu"' : '') !!}>
                                    <a href="{{ url('/home/student') }}">
                                        <i class="fa fa-user"></i>
                                        Students
                                    </a>
                                </li>
                                <li {!! (Request::is('*home/faculty*') ? 'class="active-menu"' : '') !!}>
                                    <a href="{{ url('/home/faculty') }}">
                                        <i class="fa fa-user-secret"></i>
                                        Faculties
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    <li {!! (Request::is('*/home/misc*') ? 'class="active-menu"' : '') !!} >
                        <a href="{{ url('/home/misc') }}" class="waves-effect waves-dark">
                            <i class="fa fa-exclamation"></i>Miscellaneous Settings
                        </a>
                    </li>
                </ul>

            </div>

        </nav>
        <!-- /. NAV SIDE  -->

        <div id="page-wrapper">
            <div class="header">
                <h1 class="page-header">
                    @yield('page_heading')
                </h1>
                <ol class="breadcrumb">
                    <?php $prev = Request::root() ?>
                    @foreach( explode("/",Request::path()) as $part )
                        <?php  $prev = $prev . "/" . $part ?>
                        <li>
                            <a href="{{$prev}}">{{ strtoupper($part) }}</a>
                        </li>
                    @endforeach
                    <li>
                        @yield('page_heading')
                    </li>
                </ol>
            </div>
            <div id="page-inner">
                @yield('section')
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('.datepicker').pickadate({
                selectMonths: true, // Creates a dropdown to control month
                today: 'Today',
                clear: 'Clear',
                close: 'Ok',
                format: 'yyyy-mm-dd',
                closeOnSelect: false // Close upon selecting a date,
            });
            if (screen.width < 426)
                $(".sidebar-collapse ").toggleClass("collapse");
        });
    </script>
    @yield('page_script')
@endsection