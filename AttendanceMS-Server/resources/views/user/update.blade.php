
@extends('layouts.dashboard')
@section('page_heading','New User')
@section('section')
<div class="row">
    <div class="col-sm-8">
        @section ('pane2_panel_title', 'Update User')
        @section ('pane2_panel_body')
        <form class="form-horizontal" action="submit" method="get">
            <fieldset>

            <!-- Form Name -->

            <!-- Text input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="user_type">User Type</label>
              <div class="col-md-4">
                <select id="user_type" name="user_type" class="form-control">
                  @foreach($all_types as $type)
                      <option value="{{ $type }}" {{{ ($all->user_type == $type )? "Selected":"" }}} ><samll>{{ $type }}</samll></option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label" for="dept">Department</label>
              <div class="col-md-4">
                <select id="dept" name="dept" class="form-control">
                  <option value="null" disabled selected value><samll>-- Choose Department --</samll></option>
                  @foreach($arr as $dept)
                      <option value="{{{ $dept->id }}}" {{{ ($all->dept_id == $dept->id )? "Selected":"" }}}>
                        {{ $dept->name  }}
                      </option>
                  @endforeach
                </select>
              </div>
            </div>

            <!-- Button -->
            <input type="hidden" name="user_id" value="{{ $id }}">
            <div class="form-group">
              <label class="col-md-4 control-label" for=""></label>
              <div class="col-md-4">
                <button id="" name="" class="btn btn-primary">Submit</button>
              </div>
            </div>

            </fieldset>
        </form>
        @endsection
        @include('widgets.panel', array('header'=>true, 'as'=>'pane2','class'=>'primary'))
    </div>
</div>
@stop