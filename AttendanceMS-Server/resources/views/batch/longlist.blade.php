@extends('layouts.dashboard')


@section('page_heading','Attendance List')


@section('section')

    <div class="col-sm-12">
        <div class="row">

            @if (isset($students) )
                @component('widgets.advance_table', array('table_class' => ' animated bounceInLeft'))
                    @slot("table_heading")
                        {{$subject->code}}
                        <h1>{{ $subject->name }}</h1>
                        <h4> Taken By : {{ $db_classes[0]->teacher_name }} </h4>
                    @endslot
                    <thead>
                    <tr>
                        <th>Roll No</th>
                        <th>Name</th>
                        @foreach ($db_classes as $db_class)
                            <th>{{ date_format(date_create($db_class->class_date),"d M") }}</th>
                        @endforeach
                        <th>Attended / Total = %</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($students) > 0)

                        @foreach ($students as $student)
                            <tr>
                                <td>{{ $student->student_roll }}</td>
                                <td>{{ $student->student_name }}</td>
                                <?php $attn_cnt = $total_cnt = 0; ?>
                                @foreach ($db_classes as $db_class)
                                    @if($attendances[$student->student_id][$db_class->active_day_id] == true)
                                        <td>p<sup>{{$db_class->mark_count}}</sup></td>
                                        <?php $attn_cnt += $db_class->mark_count; ?>
                                    @else
                                        <td></td>
                                    @endif
                                    <?php $total_cnt += $db_class->mark_count; ?>
                                @endforeach
                                <td> {{$attn_cnt}} / {{$total_cnt}} = {{ Utility::percentage($attn_cnt,$total_cnt) }}
                                    %
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                @endcomponent
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
