@extends('layouts.dashboard')



@section('page_heading','Student Overview')


@section('section')
		
<div class="col-sm-12">
    <div class ="row">

@if (isset($students) )

    <div class="row">
        <div class="col-md-4 col-md-push-8">
            @include('widgets.panel_button',
                [ 'pb_icon'=>"fa fa-user fa-5x",
                    'pb_icon_ani'=>"animated shake",
                    'pb_label'=>"Student Count",
                    'pb_heading' => count($students),
                    'pb_class'=>"panel-info"       ])
        </div>
        <div class="col-md-8 col-md-pull-4">
            @component('widgets.advance_table', array('table_class' => ' animated bounceInLeft'))
            @slot("table_heading")
                <h3 class="panel-title " style="padding-bottom: 7.5px;font-size:25px">
                    {{ $batch->start_date }} - 
                    {{ $batch->start_date + $batch->course_years }} Batch
                    <small><br>Attendance Records of Last Semisters</small>
                </h3>
            @endslot
                <thead>
                    <tr>
                        <th>Actions</th>
                        <th>Roll No</th>
                        <th>Name</th>
                        <th>Percentage</th>
                        <th>Attendance</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($students) > 0)
                    @foreach ($students as $student)
                    
                        <tr>
                            <td><a href="{{ route('home.long.term.view.details',[$student->student_id]) }}"
                            class="btn btn-warning btn-block btn-xs" >View</a>
                            </td>
                            <td>{{ $student->student_roll }}</td>
                            <td>{{ $student->student_name }}</td>
                            <td>{{ Utility::percentage($student->attn,$student->total) }} % </td>
                            <td>{{ $student->attn }}</td>
                        </tr>
                    
                    @endforeach
                    @endif
                </tbody>
            @endcomponent
        </div>
    </div>
    @endif
    </div>
</div>
@endsection


@section('page_script')
<script>
$(document).ready(function () {
    $('#dataTables-example').dataTable();
});
 </script>
@endsection
