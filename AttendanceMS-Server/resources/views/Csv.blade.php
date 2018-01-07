@extends('layouts.dashboard')
@section('page_heading',$file_name)
@section('section')

    @if (isset($arr))
        @if (count($arr) > 0)
            <div class="row">
                <div class="col-lg-8">

                    @section ('pane2_panel_title', $file_name)
                    @section ('pane2_panel_body')
                        <div class="table-responsive ">
                            <table class="table table-striped table-bordered table-condensed table-hover ">
                                <thead>
                                <tr>
                                    @foreach (array_keys($arr[0]) as $key)
                                        <th>{{ $key }}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @for ($i = 0; $i < count($arr); $i++)
                                    <tr>
                                        @foreach(array_keys($arr[$i]) as $key)
                                            <td>{{ $arr[$i][$key] }}</td>
                                        @endforeach
                                    </tr>
                                @endfor
                                </tbody>
                            </table>
                        </div>
                    @endsection
                    @include('widgets.panel', array('header'=>true, 'as'=>'pane2'))
                </div>
            </div>
            @endif
            @endif
            </div>



@stop