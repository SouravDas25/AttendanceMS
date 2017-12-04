<a href="{{ isset($pb_link)? $pb_link : ''}}" >
    <div class="panel {{ isset($pb_class)? $pb_class : 'default'}} " 
        style="box-shadow: 1px 1px 7px #888888;box-sizing: border-box;">
        <div class="panel-heading ">
            <div class="row">
                @if( isset($pb_heading) )
                    <div class="col-xs-3 text-center">
                        <i class="{{ isset($pb_icon)? $pb_icon : ''}} {{ isset($pb_icon_ani)? $pb_icon_ani : ''}}"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <h1 class="huge"> {{ $pb_heading }} </h1>
                        <div> {{ isset($pb_sub_heading)? $pb_sub_heading : "" }}</div>
                    </div>
                @else
                    <div class="col-sm-12 text-center ">
                        <i class="{{ isset($pb_icon)? $pb_icon : ''}} {{ isset($pb_icon_ani)? $pb_icon_ani : ''}}"></i>
                    </div>
                @endif
            </div>
        </div>
        <div class="panel-footer text-center {{ isset($pb_fclass)? $pb_fclass : 'teal'}}">
            <span class="white-text ">{{ isset($pb_label)? $pb_label : ''}}</span>
            <div class="clearfix"></div>
        </div>
    </div>
</a>