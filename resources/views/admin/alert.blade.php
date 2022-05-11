<div style="position: fixed;right: 0px;top: 100px;width: auto;height: 200px;margin-right: 5px;">
    @if(Session::has('error'))
    <div class="alert alert-danger">
        <button type="button" class="close pl-1" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Error!</strong>   {{Session::get('error')}}
    </div>
    @endif
    
    @if(Session::has('success'))
    <div class="alert alert-success">
        <button type="button" class="close pl-1" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Success!</strong>   {{Session::get('success')}}
    </div>
    @endif
</div>