@extends('layouts.dashboard')
@section('page_heading','Department - Streams')
@section('section')

    @if (isset($data) )
        @component('widgets.card', ['card_class'=>'bg-warning animated bounceInRight'])
            @slot("card_heading","Streams")
            <ul class="collapsible popout" data-collapsible="accordion">
                @foreach($data as $dept)
                    <li>
                        <div class="collapsible-header red">
                            <strong>{{ $dept['dept_data']->name}}</strong> - {{ $dept['dept_data']->full_name }}
                        </div>
                        <div class="collapsible-body bg-danger" style="padding:5px">
                            <div class="list-group">
                                @foreach($dept['batches'] as $batch)
                                    <a href="{{ route('home.batch.student.subject',[ $batch->batch_no,'total']) }}"
                                       class="list-group-item">
                                        <h4>
                                            {{ Utility::ordinal_suffix( intdiv($batch->sem_no +1,$dept['dept_data']->course_years) +1 ) }}
                                            Year ,
                                            {{ Utility::ordinal_suffix($batch->sem_no)  }} Sem
                                        </h4>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endcomponent

    @endif




@stop