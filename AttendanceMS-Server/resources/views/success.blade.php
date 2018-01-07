@extends('layouts.dashboard')
@section('page_heading','Operation Completed!')
@section('section')
    <div class="jumbotron">
        <div class="container">
            <h2>Success!</h2>
            <h4>
                {{{ isset($msg)? $msg : "" }}}
            </h4>
            <p>
                <a class="waves-effect waves-light btn-primary btn-large"
                        {!! (isset($back))? "href='".$back."'" : "onclick='goBack()' " !!}>Back</a>
                <a class="waves-effect waves-light btn-large" href="{{ route('home') }}" role="button">Return To Home</a>
            </p>
            @if(isset($data))
                @foreach($data as $item)
                    <h2>{{ $item }}</h2>
                @endforeach
            @endif

        </div>
    </div>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
@stop