@extends('layouts.plane3')


@section('style')
    @yield('page_style')
@endsection



@section('body')

    @include('nav.root')

    @yield('page_content')

    @include('footer.root')

    @include('modal.login-form')


@endsection


@section('script')
    <script>
        $(document).ready(function () {
            $('.modal').modal();
            @if(Session::has('login_perror'))
            $('#login-modal').modal('open');
            @endif
        });

        function login_submit() {
            MY_click_load_wait();
            $('#login-form').submit();
        }
    </script>
    @yield('page_script')
@endsection
