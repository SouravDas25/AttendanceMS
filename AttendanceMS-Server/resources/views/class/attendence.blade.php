@extends('layouts.dashboard')
@section('page_heading', $sub_code )


@section('page_style')
<style>
label {
    margin-top:5px;
}

</style>
@endsection




@section('section')
<div class ="row">
	<div class ="col-sm-12">
        <div class="panel ">
            <div class="panel-heading red lighten-1 ">
                <div class ="row">
                    <div class ="col-sm-8" >
                        <h3 style="margin:5px;" ><b >{{$sub_name}}</b></h3>
                    </div>
                    <br>
                    <div class ="col-sm-4">
                        <div class="input-field">
                            <label for="attn_date" class="white-text">Class Date ( YYYY-MM-DD )</label>
                            <input id="attn_date" name="attn_date" type="text" class="datepicker" value="{{{ date('Y-m-d') }}}">
                        </div>
                    </div>
                </div>
                <div class ="row">
                    <div class ="col-md-8">
                        <div class="input-field">
                            <input type="text" class="validate" id="attn_topic" name="attn_topic"  >
                            <label for="attn_topic" class="white-text">Topic</label>
                        </div>
                    </div>
                    <div class ="col-md-4"> 
                        <i>Head Count :</i> <b id = "hc">0</b>
                    </div>
                </div>
            </div>
            <div class="panel-body bg-warning"  >
                <div class="table-responsive" >
                    <table class="table table-striped table-bordered table-condensed table-hover " id="atbl">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Roll No</th>
                                <th width="20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($students) > 0)
                            
                            @foreach ($students as $student)
                            <tr>
                                <td>{{ $student->student_name }}</td>
                                <td>{{ $student->student_roll }}</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-block" 
                                        onclick="toggle_present({{ $student->student_id }})"  title="Mark For Attedance"
                                        id ="stud-{{ $student->student_id }}" mydata="false"
                                        data-toggle="button" aria-pressed="false" autocomplete="off">
                                        Absent
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer ">
                <div class ="row ">
                    <div class ="col-sm-8">
                        <div class ="row">
                            <div class ="col-sm-3" >
                                <h1 class="panel-title pull-left" style="padding-top: 2%;padding-bottom: 2%"> Remarks</h1> 
                            </div>
                            <div class ="col-sm-9">
                                <div class="form-group">
                                    <textarea class="form-control" rows="3" id="attn_remarks" name="attn_remarks"  
                                    style="padding-top: 2%;padding-bottom: 2%"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class ="row">
                            <div class ="col-sm-3" >
                            </div>
                            <div class ="col-sm-9">
                                <div class="input-field">
                                    <label for="mark_count">Attendance Per Mark</label>  
                                    <input type="number" class="validate" id="mark_count" name="mark_count" value="1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class ="col-sm-4" >
                        <form class="form-horizontal" onSubmit="myonsubmit_count();" action="/home/class/submit" enctype="multipart/form-data"
                        method="POST">
                            <fieldset>
                                <input type="hidden" value="" name="attn_data" id="attn_data" />
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <div class="form-group">
                                    <div class="col-md-12">
                                    <input type="submit" class="btn btn-primary btn-block" value="Confirm" >
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>

@endsection

@section('page_script')
<script>
var dtbl = null;
var ids = {
          @foreach ($students as $student)
          {{ $student->student_id }} : false,
          @endforeach
    };
$(document).ready(function () {
    dtbl = $('#atbl').dataTable();
});
var head_count = 0;
function toggle_present(id) {
    var btn = "#stud-"+id;
    //alert($(btn).attr("mydata"));
    if( ids[id] == true ) {
        $(btn).html("Absent");
        $(btn).button("toggle");
        ids[id] = false;
        $(btn).toggleClass("btn-success");
        $(btn).toggleClass("btn-danger");
        head_count--;
        $("#hc").html(head_count);
    } else {
        //alert("lol");
        $(btn).button("toggle");
        $(btn).html("Present");
        ids[id] = true;
        $(btn).toggleClass("btn-success");
        $(btn).toggleClass("btn-danger");
        head_count++;
        $("#hc").html(head_count);
    }
    //alert($(btn).attr("mydata"));
}

function myonsubmit_count() {
    var b = confirm("Are You Sure ?");
    //alert("lol");
    if(!b)
    {
    event.preventDefault();
    return false;
    }
    var i ;

    var jsn = [];
    for(var i in ids) {
        if( ids[i] == true )jsn.push([i,ids[i]]);
    }
    var data = {
        "date" : $("#attn_date").val() ,
        "attn": jsn ,
        "code" : "{{$sub_code}}",
        "topic" : $("#attn_topic").val(),
        "mark_count" : $("#mark_count").val(),
        "remarks" : $("#attn_remarks").val(),
        "batch_no":"{{$batch_no}}"
    } 
    if(data["topic"].length < 1 )
    {
        event.preventDefault();
        alert("Class Topic Not Given.");
        return false;
    }

    $("#attn_data").val( JSON.stringify(data ));
    MY_click_load_wait();
    return true;
}
</script>
@endsection