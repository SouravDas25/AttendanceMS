@extends('layouts.dashboard')
@section('page_heading','Dashboard')

@section('section')
    <div class="row">
        <div class="col-lg-3 col-md-6 animated bounceInUp">
            @include('widgets.panel_button',
                [   'pb_link'=> url('/home/class'),
                    'pb_icon'=>"fa fa-mortar-board fa-5x",
                    'pb_icon_ani'=>"animated shake",
                    'pb_label'=>"Take Attendance",
                    'pb_fclass'=>"red" ,
                    'pb_class'=>"panel-danger"      ])
        </div>
        <div class="col-lg-3 col-md-6 animated bounceInDown">
            @include('widgets.panel_button',
                [   'pb_link'=> url('/home/batch'),
                    'pb_icon'=>"	fa fa-area-chart fa-5x",
                    'pb_icon_ani'=>"animated swing",
                    'pb_label'=>"View Attendance",
                    'pb_fclass'=>"blue" ,
                    'pb_class'=>"panel-info"       ])
        </div>
        <div class="col-lg-3 col-md-6 animated bounceInUp">
            @include('widgets.panel_button',
                [   'pb_link'=> url('/home/myclass'),
                    'pb_icon'=>"fa fa-lastfm fa-5x",
                    'pb_icon_ani'=>"animated shake",
                    'pb_label'=>"My Classes",
                    'pb_fclass'=>"orange" ,
                    'pb_class'=>"panel-warning"       ])
        </div>
        <div class="col-lg-3 col-md-6 animated bounceInDown">
            @include('widgets.panel_button',
                [   'pb_link'=> url('/home/long.term'),
                    'pb_icon'=>"fa fa-database fa-5x",
                    'pb_icon_ani'=>"animated swing",
                    'pb_label'=>"Attendance Storage",
                    'pb_fclass'=>"green" ,
                    'pb_class'=>"panel-success"       ])
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            @component('widgets.advance_table', array('table_class' => ' animated bounceInLeft'))
                @slot("table_heading","Classes Taken by You this Semester")
                <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Number Of Classes</th>
                </tr>
                </thead>
                <tbody>
                @if (count($data) > 0)

                    @foreach ($data as $sub)
                        <tr>
                            <td>{{ $sub->subject_code }}</td>
                            <td>{{ $sub->subject_name  }}</td>
                            <td>{{ $sub->subcnt }}</td>
                        </tr>
                    @endforeach

                @endif
                </tbody>
            @endcomponent
        </div>
    </div>

    @if(count($al) > 3)
        <div class="row">
            <div class="col-md-12">
                <h2>Top Attendance</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 animated bounceInUp">
                @include('widgets.card_button_3d',
                    [   'pb_link'=> url("/home/long.term/view/details/".$al[0]->student_id),
                        'pb_icon'=>"	glyphicon glyphicon-user fa-5x",
                        'pb_icon_ani'=>"animated shake",
                        'pb_label'=>$al[0]->name,
                        'pb_heading'=>$al[0]->attn . " Classes",
                        'pb_fclass'=>"red" ,
                        'pb_class'=>"bg-danger"      ])
            </div>
            <div class="col-lg-3 col-md-6 animated bounceInDown">
                @include('widgets.card_button_3d',
                    [   'pb_link'=> "/home/long.term/view/details/".$al[1]->student_id,
                        'pb_icon'=>"	glyphicon glyphicon-user fa-5x",
                        'pb_icon_ani'=>"animated swing",
                        'pb_label'=>$al[1]->name,
                        'pb_heading'=>$al[1]->attn. " Classes",
                        'pb_fclass'=>"blue" ,
                        'pb_class'=>"bg-info"       ])
            </div>
            <div class="col-lg-3 col-md-6 animated bounceInUp">
                @include('widgets.card_button_3d',
                    [   'pb_link'=> "/home/long.term/view/details/".$al[2]->student_id,
                        'pb_icon'=>"	glyphicon glyphicon-user fa-5x",
                        'pb_icon_ani'=>"animated shake",
                        'pb_label'=>$al[2]->name,
                        'pb_heading'=>$al[2]->attn. " Classes",
                        'pb_fclass'=>"orange" ,
                        'pb_class'=>"bg-warning"       ])
            </div>
            <div class="col-lg-3 col-md-6 animated bounceInDown">
                @include('widgets.card_button_3d',
                    [   'pb_link'=> "/home/long.term/view/details/".$al[3]->student_id,
                        'pb_icon'=>"	glyphicon glyphicon-user fa-5x",
                        'pb_icon_ani'=>"animated swing",
                        'pb_label'=>$al[3]->name,
                        'pb_heading'=>$al[3]->attn. " Classes",
                        'pb_fclass'=>"green" ,
                        'pb_class'=>"bg-success"       ])
            </div>
        </div>
    @endif

@endsection


@section('page_script')
    <script>
        $(document).ready(function () {
            $('#dataTables-example').dataTable({
                responsive: true
            });
        });
    </script>
@endsection