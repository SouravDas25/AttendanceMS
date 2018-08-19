@extends('layouts.open')

@section('page_content')
    <div class="container white">
        <div class="section">
            <div class="row">
                @component('widgets.card', ['card_class'=>'bg-warning animated bounceInRight','card_hclass'=>" cyan accent-4"])
                    @slot("card_heading")
                        <h5 class="panel-heading">Operation Interrupted !</h5>
                    @endslot
                    <div class="jumbotron">
                        <div class="container">
                            <h1>Error!</h1>
                            <p>
                                {{ isset($msg)? $msg : "" }}
                            </p>
                            <p>
                                @if(isset($back))
                                    <a class="btn btn-primary btn-lg" href="{{ $back }}">Back</a>
                                @else
                                    <a class="btn btn-primary btn-lg" onclick='goBack()' role="button">Back</a>
                                @endif
                                <a class="btn btn-primary btn-lg" href="{{ route('home') }}" role="button">Return To Home</a>
                            </p>
                        </div>
                    </div>
                @endcomponent
            </div>
        </div>
    </div>
@endsection

@section('page_script')
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
@endsection