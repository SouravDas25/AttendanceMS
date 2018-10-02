@extends('layouts.dashboard')
@section('page_heading','New Batch' )



@section('section')
    <div class="row">
        <div class="col-sm-8">
            @section ('pane2_panel_title', 'Create Batch')
            @section ('pane2_panel_body')
                <form class="form-horizontal" action='{{ Utility::rootUrl("/" . Request::path() . "/submit" ) }}'
                      method="POST" enctype="multipart/form-data">
                    <fieldset>

                        <!-- Text input-->

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="bch_date">Batch Starting Year</label>
                            <div class="col-md-4">
                                <input id="bch_date" name="bch_date" type="number" min="2005" max="2050"
                                       placeholder="YYYY" class="validate" required value="{{date('Y')}}">
                            </div>

                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="bch_dept">Department</label>
                            <div class="col-md-4">
                                <select id="bch_dept" name="bch_dept" class="form-control" required="">
                                    <option disabled selected value> -- select department --</option>
                                    @foreach($arr as $dept)
                                        <option value="{{{ $dept->id }}}"> {{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- File Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="csv_file">Student Csv File</label>
                            <div class="col-md-4">
                                <input id="csv_file" name="csv_file" class=" input-file" type="file" required>
                            </div>
                        </div>

                        <div id="csv_cols" class="form-groups">
                            <div class="col-md-8 col-md-offset-4">
                                <small>
                                    <div class="row">
                                        <label class="col-md-4">Student Name Aliase</label>
                                        <div class="col-md-4">
                                            <select id="name_col" name="name_col" class="form-control" required
                                                    disabled>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <label class="col-md-4">Roll No Aliase</label>
                                        <div class="col-md-4">
                                            <select id="roll_col" name="roll_col" class="form-control" required
                                                    disabled>
                                            </select>
                                        </div>

                                    </div>
                                </small>
                            </div>
                        </div>

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <span class="col-md-4"><a href="{{ url("/csv/sample_batch.csv") }}">See Sample File</a></span>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for=""></label>
                            <div class="col-md-4">
                                <input class="btn btn-primary" type="submit" value="Submit">
                            </div>
                        </div>

                    </fieldset>
                </form>
            @endsection
            @include('widgets.panel', array('header'=>true, 'as'=>'pane2','class'=>'primary'))
        </div>
    </div>

@endsection







@section('page_script')
    <script src="{{ asset("/js/jquery.csv.js") }}" type="text/javascript"></script>
    <script>


        function empty_csv_file() {
            $("#roll_col").find('option').remove().attr("disabled");
            $("#name_col").find('option').remove().attr("disabled");
            $("#csv_file").val(undefined);
        }

        $(document).ready(function () {
            empty_csv_file();
        })

        function readSingleFile(e) {
            var file = e.target.files[0];
            if (!file) {
                empty_csv_file();
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                empty_csv_file();
                alert("File Size Should Be Less Than 2 MB.");
                return;
            }
            var reader = new FileReader();
            reader.readAsText(file);
            reader.onload = function (e) {
                var contents = e.target.result;
                displayContents(contents);
            };
            reader.onerror = function () {
                alert('Unable to read ' + file.fileName);
                empty_csv_file();
            };
        }

        function displayContents(contents) {
            try {
                var items = $.csv.toObjects(contents);
            }
            catch (e) {
                alert(e.message);
                empty_csv_file();
                return;
            }
            var keys = Object.keys(items[0]);
            $("#roll_col").find('option').remove();
            $("#name_col").find('option').remove();
            for (i = 0; i < keys.length; i++) {
                $("#roll_col").append($('<option></option>').val(keys[i]).html(keys[i]));
                $("#name_col").append($('<option></option>').val(keys[i]).html(keys[i]));
            }
            $("#roll_col").removeAttr("disabled");
            $("#name_col").removeAttr("disabled");
        }

        document.getElementById('csv_file').addEventListener('change', readSingleFile, false);
    </script>

@endsection
