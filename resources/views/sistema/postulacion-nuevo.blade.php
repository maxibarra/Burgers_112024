@extends("plantilla")
@section('titulo', $titulo)
@section('scripts')
<script>
    globalId = '<?php echo isset($postulacion->idpostulacion) && $postulacion->idpostulacion > 0 ? $postulacion->idpostulacion : 0; ?>';
    <?php $globalId = isset($postulacion->idpostulacion) ? $postulacion->idpostulacion : "0";?>
</script>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin/home">Inicio</a></li>
    <li class="breadcrumb-item"><a href="/admin/postulaciones">Postulaciones</a></li>
    <li class="breadcrumb-item active">Modificar</li>
</ol>
<ol class="toolbar">
    <li class="btn-item"><a title="Nuevo" href="/admin/postulacion/nuevo" class="fa fa-plus-circle" aria-hidden="true"><span>Nuevo</span></a></li>
    <li class="btn-item"><a title="Guardar" href="#" class="fa fa-floppy-o" aria-hidden="true" onclick="javascript: $('#modalGuardar').modal('toggle');"><span>Guardar</span></a>
    </li>
    @if($globalId > 0)
    <li class="btn-item"><a title="Guardar" href="#" class="fa fa-trash-o" aria-hidden="true" onclick="javascript: $('#mdlEliminar').modal('toggle');"><span>Eliminar</span></a></li>
    @endif
    <li class="btn-item"><a title="Salir" href="#" class="fa fa-arrow-circle-o-left" aria-hidden="true" onclick="javascript: $('#modalSalir').modal('toggle');"><span>Salir</span></a></li>
</ol>
<script>
function fsalir(){
    location.href ="/admin/sistema/menu";
}
</script>
@endsection
@section('contenido')
<?php
if (isset($msg)) {
    echo '<script>msgShow("' . $msg["MSG"] . '", "' . $msg["ESTADO"] . '")</script>';
}
?>
<div id = "msg"></div>
<div class="panel-body">
        <form id="form1" method="POST">
            <div class="row">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
                <input type="hidden" id="id" name="id" class="form-control" value="{{$globalId}}" required>
                <div class="form-group col-6">
                    <label>Nombre: *</label>
                    <input type="text" id="txtNombre" name="txtNombre" class="form-control" value="{{$postulacion->nombre}}" required>
                </div>
                <div class="form-group col-6">
                    <label>Apellido: *</label>
                    <input type="text" id="txtApellido" name="txtApellido" class="form-control" value="{{$postulacion->apellido}}" required>
                </div>
                <div class="form-group col-6">
                    <label>Whatsapp: *</label>
                    <input type="text" id="txtWhatsapp" name="txtWhatsapp" class="form-control" value="{{$postulacion->whatsapp}}" required>
                </div> 
                <div class="form-group col-6">
                    <label>Correo: *</label>
                    <input type="email" id="txtCorreo" name="txtCorreo" class="form-control" value="{{$postulacion->correo}}" required>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
                <input type="hidden" id="id" name="id" class="form-control" value="{{$globalId}}">
                <div class="form-group col-6">
                    <label>CV: </label> <br>
                    <input type="file" id="txtCV" name="txtCV" >
                </div>
            </div>
        </form>
</div>

<script>

    $("#form1").validate();

    function guardar() {
        if ($("#form1").valid()) {
            modificado = false;
            form1.submit();
        } else {
            $("#modalGuardar").modal('toggle');
            msgShow("Corrija los errores e intente nuevamente.", "danger");
            return false;
        }
    }

    function eliminar() {
        $.ajax({
            type: "GET",
            url: "{{ asset('/admin/postulacion/eliminar') }}",
            data: { id:globalId },
            async: true,
            dataType: "json",
            success: function (data) {
                if (data.mensaje != "0") {
                    msgShow(data.mensaje , "success");
                } else {
                    msgShow(data.mensaje, "danger");
                }
                $('#mdlEliminar').modal('toggle');
            }
        });
    }
    </script>
@endsection