@extends('layouts.dashboard')
@section('page_heading','Conformation')
@section('section')
    <div class="row">
        <div class="col-sm-8">
            <form class="form-horizontal" action='{{ isset($submit)? $submit : "submit" }}' id="myform"
                  method='{{ isset($method)?"POST":"GET" }}'>
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-4 control-label"></label>
                        <h1 class="col-md-8">
                            <label>Are You Sure ?</label>
                            <br>
                            <small>
                                {!! (isset($msg)?$msg:"") !!}
                            </small>
                        </h1>
                    </div>

                    <input type="hidden" name="answer" value="0" id="answer">

                    <input type="hidden" name="id" value="{{ $id }}">
                    @if(isset($no))
                        <input type="hidden" name="no" value="{{ $no }}">
                    @endif
                    @if(isset($method))
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                @endif

                <!-- Button -->
                    <div class="form-group">
                        <div class="col-md-4">

                        </div>
                        <div class="col-md-4 btn-group-sm " role="group">
                            <button type="button" class="btn btn-lg btn-success" onclick="return ans_set(true);">Yes
                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-danger btn-lg" onclick="return ans_set(false);">No
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                            </button>
                        </div>


                    </div>

                </fieldset>
            </form>
        </div>
    </div>
    <script>
        function ans_set(ans) {
            $("#answer").val(ans);
            $("#myform").submit();
            return true;
        }
    </script>
@stop