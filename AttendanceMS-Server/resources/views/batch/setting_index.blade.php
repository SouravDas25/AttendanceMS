@extends('layouts.dashboard')
@section('page_heading','Batches')


@section('section')

@if (isset($batches) )

    <div class="row">
        <div class="col-md-8">
            @component('widgets.advance_table', array('table_class' => ' animated bounceInLeft'))
            @slot("table_heading","Students")
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Year</th>
                        <th>Semistar</th>
                        <th>Course Years</th>
                        <th width="17%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($batches) > 0)
                    
                    @foreach ($batches as $batch)
                    <tr>
                        <td>{{ $batch->dept_name }}</td>
                        <td>
                        {{ ($batch->current_year<=$batch->course_years)? Utility::ordinal_suffix($batch->current_year):"Pass Out" }}
                        </td>
                        <td>{{ ($batch->current_year<=$batch->course_years)? Utility::ordinal_suffix($batch->sem_no):"Pass Out"  }}</td>
                        <td>{{ $batch->start_date }} - {{ $batch->start_date + $batch->course_years }}</td>
                        <td class="text-center" >
                        @include('widgets.action_box',
                                ['ab_edit'=>"home/batch/update/".$batch->batch_no,
                                'ab_delete'=>"home/batch/delete/".$batch->batch_no])
                        </td>
                    </tr>
                    @endforeach
                    
                    @endif
                </tbody>
            @endcomponent
        </div>
        <div class="col-md-4">
            @include('widgets.panel_button',
            [   'pb_link'=> "/home/batch/create",
                'pb_icon'=>"fa fa-sitemap fa-5x",
                'pb_icon_ani'=>"animated swing",
                'pb_label'=>"Add a Batch",
                'pb_fclass'=>"green",
                'pb_class'=>"panel-success"       ])
        </div>
    </div>
    @endif
@endsection


@section('page_script')
<script>
$(document).ready(function () {
    $('#dataTables-example').dataTable();
});
 </script>
@endsection