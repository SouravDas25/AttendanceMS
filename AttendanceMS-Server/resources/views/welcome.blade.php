@extends('layouts.open')

@section('page_content')

    <div id="index-banner" class="parallax-container ">
        <div class="section no-pad-bot">
            <div class="container">
                <div class="row center">
                    <img src="{{ asset("/img/Tict_logo.png") }}" alt="img-responsive"
                         class="img-responsive header center animated bounceInDown">
                </div>
                <h1 class="header center teal-text text-lighten-4 res-font animated bounceInRight">Techno India College
                    Of Technology</h1>
                <div class="row center">
                    <h5 class="header col s12 light animated bounceInLeft">Online Attendance System</h5>
                </div>
                <div class="row center animated bounceInUp">
                    @if( Utility::is_loged_in() )
                        <a href="{{route('home')}}" class="btn-large waves-effect waves-light teal lighten-1">
                            Dashboard
                        </a>
                    @else
                        <a onclick="$('#modal1').modal('open');"
                           class="btn-large waves-effect waves-light teal lighten-1">
                            Sign In
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="parallax  brown   darken-4">
            <img src="{{ asset("img/bg5.jpg") }}" id="clg-img" alt="Unsplashed background">
        </div>
    </div>

    <div class="container   grey lighten-4">
        <div class="section ">

            <div class="row">
                <div class="col s12 center">
                    <h3>
                        <i class="mdi-content-send brown-text"></i>
                    </h3>
                    <h4>Your Attendance</h4>
                    <p class="left-align light" id="cya">

                    <div id='indet' class="progress">
                        <div class="indeterminate"></div>
                    </div>

                    <form class="" action="{{ url('/') }}" id="your_attn_form">

                        <div class="input-field col s12">
                            <select id="dept" name="dept" class="validate" required onchange="show_year()">
                                <option disabled selected value> -- Department --</option>
                                @foreach($depts as $dept )
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Select Basic -->
                        <div class="input-field col s12">
                            <select id="cya_year" name="year" class="validate" required>
                            </select>
                        </div>

                        <!-- Search input-->
                        <div class="input-field col s12">
                            <input id="roll" name="roll" type="number" class="validate" required="">
                            <label>Roll No</label>
                        </div>

                        <div class="input-field col s12">
                            <input type="button" value="Check" class="btn btn-success btn-block " disabled
                                   id="check-btn"
                                   onclick="submit_your_attendance()">
                        </div>
                    </form>
                    </p>
                </div>
            </div>
            <div class="row" id="cya_view">
                @if( isset($err_msg) )
                    <h2 style="color:red;text-align:center">** {{ $err_msg }} **</h2>
                @endif
                @if( isset($result) || isset($sem_result) )
                    <div class="animated bounceInRight">
                        @component('widgets.card', ['card_class'=>'bg-warning'])
                            @slot("card_heading")
                                <div class="white-text">
                                    <h3>{{ $student->student_name }}
                                        <br>
                                    </h3>
                                    <i style="font-size:15px;">
                                        {{ $batch->dept_name }} {{ Utility::ordinal_suffix($batch->current_year) }}
                                        Year {{ Utility::ordinal_suffix($batch->sem_no) }} Sem
                                        <br> Roll No - {{ $student->student_roll }}
                                    </i>
                                </div>
                            @endslot
                            @if( isset($result) )
                                <div class="row">
                                    <div class="col-md-6">
                                        <div id="chartContainer" style="height: 340px; width: 100%;"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-condensed table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Subject</th>
                                                    <th>Percentage</th>
                                                    <th>Your</th>
                                                    <th>Total</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if (count($result) > 0)
                                                    @foreach( $result as $arr )
                                                        <tr>
                                                            <td>
                                                                <b>{{ $arr['name'] }} </b>
                                                                <br> ( {{ $arr['code'] }} )
                                                            </td>
                                                            <td>{{ Utility::percentage($arr['attn'],$arr['total']) }}%
                                                            </td>
                                                            <td>{{ $arr['attn'] }}</td>
                                                            <td>{{ $arr['total'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (count($sem_result) > 0)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div id="chartContainer2" style="height: 340px; width: 100%;"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-condensed table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Semester</th>
                                                    <th>Percentage</th>
                                                    <th>Your</th>
                                                    <th>Total</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach( $sem_result as $sem )
                                                    <tr>
                                                        <td>{{ Utility::ordinal_suffix($sem->sem_no) }}</td>
                                                        <td>{{ Utility::percentage($sem->attendance,$sem->total) }}%
                                                        </td>
                                                        <td>{{ $sem->attendance }}</td>
                                                        <td>{{ $sem->total }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endcomponent
                    </div>
                @endif
            </div>
        </div>
    </div>



    <div class="parallax-container valign-wrapper">
        <div class="section no-pad-bot">
            <div class="container">
                <div class="row center">
                    <h2 class="header col s12">Attendance Matters</h2>
                    <h5 class="light"> every student, every day</h5>
                </div>
            </div>
        </div>
        <div class="parallax brown   darken-4">
            <img src="{{ asset("img/bg10.jpg") }}" alt="Unsplashed background img 2">
        </div>
    </div>

    <div class="container  grey lighten-4">
        <div class="section ">

            <!--   Icon Section   -->
            <div class="row">
                <div class="col s12 m4">
                    <div class="icon-block">
                        <h2 class="center brown-text">
                            <i class="material-icons">timer</i>
                        </h2>
                        <h5 class="center">Speeds up Attendance</h5>

                        <p class="light">We did most of the heavy lifting for you to provide a default settings that
                            incorporate our custom components. Additionally,
                            we refined the Ui and added all data to provide a smoother experience for users.</p>
                    </div>
                </div>

                <div class="col s12 m4">
                    <div class="icon-block">
                        <h2 class="center brown-text">
                            <i class="material-icons">gesture</i>
                        </h2>
                        <h5 class="center">User Experience Focused</h5>

                        <p class="light">By utilizing experized skill from our teacher and huge pool of example and test
                            cases, we were able to create a framework
                            that incorporates components and automation that provide more easy of use and centralized
                            control. Additionally, a
                            single underlying responsive system across all platforms allow for a more unified user
                            experience.</p>
                    </div>
                </div>

                <div class="col s12 m4">
                    <div class="icon-block">
                        <h2 class="center brown-text">
                            <i class="material-icons">settings</i>
                        </h2>
                        <h5 class="center">Easy to work with</h5>

                        <p class="light">We have provided detailed documentation as well as live examples to help new
                            users get started. We are also always open
                            to feedback and can answer any questions a user may have about Attendance MS.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection





@section('page_script')
    <script src="{{ asset("js/jquery.canvasjs.min.js") }}" type="text/javascript"></script>
    <script>
        var navigationFn;
        navigationFn = {
            goToSection: function (id, time) {
                $('html, body').animate({
                    scrollTop: $(id).offset().top
                }, time);
            }
        };

        $(document).ready(function () {
            // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
            $('#cya_year').parent().hide();
            $('#roll').parent().hide();
            $("#check-btn").hide();
            $("#indet").hide(0);
            @if( !isset($result) && !isset($err_msg) && !isset($sem_result) )
            setTimeout(function () {
                navigationFn.goToSection('#index-banner', 25);
            }, 500);
            @endif
        });

        function submit_your_attendance() {
            MY_click_load_wait();
            $("#your_attn_form").submit();
        }

        $(window).bind("load", function () {
            {!! ( isset($result) || isset($sem_result) )? "navigationFn.goToSection('#cya',2000);" : "" !!}
            {!! ( isset($err_msg) )? "navigationFn.goToSection('#cya',2000);" : "" !!}
            myonload();
        });


        function show_year() {
            $('#dept').parent().hide("fast");
            $('#cya_year').parent().hide("fast");
            $('#roll').parent().hide("fast");
            $("#check-btn").hide("fast");
            $("#indet").show("fast");
            $.get("{{ Utility::rootUrl('/get_year') }}", {'dept': $('#dept').val()},
                function (data) {
                    var obj = JSON.parse(data);
                    $('#cya_year').empty().html(' ');
                    $('#cya_year').append($('<option disabled selected value> -- Session -- </option>'));
                    for (i = 0; i < obj.length; i++) {
                        var end_date = parseInt(obj[i]['start_date']) + parseInt(obj[i]['course_years']);
                        $('#cya_year').append($('<option>',
                            {
                                value: obj[i]['batch_no'],
                                text: obj[i]['start_date'] + " - " + end_date
                            }));
                    }

                    $("#cya_year").material_select();
                    $('#cya').show("fast");
                    $('#dept').parent().show("fast");
                    $('#cya_year').parent().show("fast");
                    $('#roll').parent().show("fast");
                    $("#check-btn").removeAttr("disabled");
                    $("#check-btn").show("fast");
                    $("#indet").hide("fast");
                }
            ).fail(function () {
                $("#indet").hide("fast");
                $('#cya').html(" No Internet Connection.");
            });
        }


        function myonload() {
                    @if( isset($result) )
            var chart = new CanvasJS.Chart("chartContainer", {
                    theme: "light1", // "light2", "dark1", "dark2"
                    animationEnabled: true, // change to true
                    title: {
                        text: "Attendance Percentage Of Current Semester",
                    },
                    axisY: {
                        title: "Percentage",
                        suffix: "%",
                    },
                    axisX: {
                        title: "Months"
                    },
                    data: [
                        {
                            type: "splineArea",
                            legendText: "Attendance Percentage",
                            yValueFormatString: "#,##0.##\"%\"",
                            dataPoints: [
                                    @foreach($month as $mon )
                                {
                                    label: "{{ $mon['mon'] }}", y: {{ Utility::percentage($mon['attn'],$mon['total']) }} },
                                @endforeach
                            ]
                        }
                    ]
                });

            chart.render();
            $('#chartContainer').resize(function () {
                chart.render();
            });
                    @endif


                    @if ( isset($sem_result) && count($sem_result) > 0)
            var chart2 = new CanvasJS.Chart("chartContainer2", {
                    theme: "dark2", //"light2", "dark1", "dark2"
                    animationEnabled: true,
                    title: {
                        text: "Attendance Percentage Of All Previous Semester",
                    },
                    axisY: {
                        title: "Percentage",
                        suffix: "%",
                    },
                    axisX: {
                        title: "Semester"
                    },
                    data: [
                        {
                            type: "splineArea",
                            legendText: "Attendance Percentage",
                            yValueFormatString: "#,##0.##\"%\"",
                            dataPoints: [
                                    @foreach($sem_result as $sem )
                                {
                                    label: "{{ Utility::ordinal_suffix($sem->sem_no) }}",
                                    y: {{ Utility::percentage($sem->attendance,$sem->total) }} },
                                @endforeach
                            ]
                        }
                    ]
                });
            chart2.render();
            $('#chartContainer2').resize(function () {
                chart2.render();
            });
            @endif
        }
    </script>
    @yield('page_script')

@endsection