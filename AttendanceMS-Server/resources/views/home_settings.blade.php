@extends('layouts.dashboard')
@section('page_heading','Settings')
@section('section')

    <div class="row">
        <div class="col-md-4 animated bounceInUp">
            @include('widgets.panel_button',
                [   'pb_link'=> "/home/dept",
                    'pb_icon'=>"fa fa-code-fork fa-5x",
                    'pb_icon_ani'=>"animated shake",
                    'pb_label'=>"Department Settings",
                    'pb_class'=>"info"       ])
        </div>
        <div class="col-md-4 animated bounceInDown">
            @include('widgets.panel_button',
                [   'pb_link'=> "/home/subject",
                    'pb_icon'=>"fa fa-align-justify fa-5x",
                    'pb_icon_ani'=>"animated shake",
                    'pb_label'=>"Subject Settings",
                    'pb_class'=>"warning"       ])
        </div>
        <div class="col-md-4 animated bounceInUp">
            @include('widgets.panel_button',
                [   'pb_link'=> "/home/batch/setting/index",
                    'pb_icon'=>"fa fa-users fa-5x",
                    'pb_icon_ani'=>"animated swing",
                    'pb_label'=>"Batch Settings",
                    'pb_class'=>"danger"       ])
        </div>

    </div>
    <div class="row">
        <div class="col-md-4 animated bounceInDown">
            @include('widgets.panel_button',
                [   'pb_link'=> "/home/student",
                    'pb_icon'=>"fa fa-user fa-5x",
                    'pb_icon_ani'=>"animated shake",
                    'pb_label'=>"Student Settings",
                    'pb_class'=>"primary"       ])
        </div>
        <div class="col-md-4 animated bounceInDown">
            @include('widgets.panel_button',
                [   'pb_link'=> "/home/faculty",
                    'pb_icon'=>"fa fa-user-secret fa-5x",
                    'pb_icon_ani'=>"animated swing",
                    'pb_label'=>"Faculty Settings",
                    'pb_class'=>"success"       ])
        </div>
        <div class="col-md-4 animated bounceInDown">
            @include('widgets.panel_button',
                [   'pb_link'=> "/home/misc",
                    'pb_icon'=>"fa fa-exclamation fa-5x",
                    'pb_icon_ani'=>"animated shake",
                    'pb_label'=>"Miscellaneous Settings",
                    'pb_class'=>""       ])
        </div>
    </div>
@stop
