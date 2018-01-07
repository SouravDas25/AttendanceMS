@extends('layouts.dashboard')
@section('page_heading','Operation Interupted!')
@section('section')
    <div class="jumbotron">
        <div class="container">
            <h2>Error!</h2>
            <h4>
                {{{ isset($msg)? $msg : "" }}}
            </h4>
            <p>
                <button class="waves-effect waves-light btn-primary btn-large" onclick="goBack()">Back</button>
                <a class="waves-effect waves-light btn-large" href="{{ route('home') }}" role="button">Return To Home</a>
            </p>
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
@stop