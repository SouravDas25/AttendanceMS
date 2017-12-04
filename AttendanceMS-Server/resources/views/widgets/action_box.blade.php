<div class="btn-group btn-group-xs " role="group" style="min-width:60px;">
    @if( isset($ab_view) )
    <a type="button" class="btn btn-warning tool-btn btn-xs" data-toggle="tooltip" title="View"  >
        <i class="fa fa-info fa-fw"></i>
    </a>
    @endif
    <a type="button" class="btn btn-primary tool-btn btn-xs" href="/{{$ab_edit}}" data-toggle="tooltip" title="Edit" >
        <i class="fa fa-cog fa-fw"></i>
    </a>
    <a type="button" class="btn btn-danger tool-btn btn-xs" href="/{{$ab_delete}}" data-toggle="tooltip" title="Delete" >
        <i class="fa fa-minus fa-fw"></i>
    </a>
</div>