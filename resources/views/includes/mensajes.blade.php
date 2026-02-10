@if (session("status"))
    <div class="alert alert-success alert-dismissable mt-1 mb-1" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Mensaje del sistema</h5>
        {{ session("status") }}
    </div>
@endif