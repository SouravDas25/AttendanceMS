@extends('layouts.dashboard')



@section('page_heading'," $batch->dept_name  " . Utility::ordinal_suffix($batch->current_year) ." Year" )


@section('section')
		
<div class="col-sm-12">
    <div class ="row">

@if (isset($students) )

    <div class="row">
        <div class="col-md-4 col-md-push-8">
            <div class="row">
                <div class="col-xs-12">
                    @include('widgets.card_button_3d',
                        [   'pb_link'=> "javascript:View_detail_function();",
                            'pb_icon'=>"fa fa-bar-chart-o fa-5x",
                            'pb_icon_ani'=>"animated swing",
                            'pb_label'=>"View in Detail",
                            'pb_heading'=>$total_classes. " Total Classes",
                            'pb_fclass'=>"orange" ,
                            'pb_class'=>"bg-warning"       ])
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    @component('widgets.card', ['card_class'=>'bg-success','card_hclass' => 'green'])
                    @slot("card_heading","Select A Subject")
                        <div class="list-group">
                            @if (isset($subjects) )
                            @foreach ($subjects as $subject)
                            <a href="{{ route("home.batch.student.subject",["id"=>$batch->batch_no,"code"=>$subject->subject_code]) }}" class="list-group-item {{ (Request::is('*/'.$subject->subject_code) ? 'active' : '') }}">
                                <h5 class="list-group-item-heading  ">{{ $subject->subject_code }}</h5>
                                <small>{{ $subject->subject_name }}</small>
                            </a>
                            @endforeach
                            @endif
                            <a href="{{ url()->full() }}/total" class="list-group-item {{ (Request::is('*/total') ? 'active' : '') }} ">
                                <h5 class="list-group-item-heading">Total</h5>
                                <small>Average Attendance of all classes</small>
                            </a>
                        </div>
                    @endcomponent
                </div>
            </div>
        </div>
        <div class="col-md-8 col-md-pull-4">
            @component('widgets.advance_table', array('table_class' => ' animated bounceInLeft'))
            @slot("table_heading",isset($total)?"Total Attendance":$subject_code)
                <thead>
                    <tr>
                        <th>Roll No</th>
                        <th>Name</th>
                        <th>Percentage</th>
                        <th>Attendance</th>
                        @if( Utility::is_user_admin() )
                            <th class="hidden-sm hidden-xs" >Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if (count($students) > 0)
                    
                    @foreach ($students as $student)
                    <tr>
                        <td>{{ $student->student_roll }}</td>
                        <td>{{ $student->student_name }}</td>
                        @if($total_classes)
                        <td>{{ round($student->attn/$total_classes*100,2) }} %</td>
                        @else
                        <td> 0 % </td>
                        @endif
                        <td>{{ $student->attn }}</td>
                        @if( Utility::is_user_admin() )
                            <td class="text-center hidden-sm hidden-xs">
                            @include('widgets.action_box',
                                ['ab_edit'=>"home/student/update/".$student->student_id,
                                'ab_delete'=>"home/student/delete/".$student->student_id])
                            </td>
                        @endif
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

function View_detail_function()
{
    if( {{ !isset($total) ? "true" : "false"}} )
    {
        window.location = "{{ url()->current() . '/longlist' }}";
    }
    else
    {
        Disable_my_click_load_wait();
        Materialize.toast('Select A Subject', 2000);
    }
}

</script>
@endsection
