@extends("plantilla")
@section('titulo', $titulo)
@section('scripts')
<script>
      globalId = '<?php echo isset($pedido->idpedido) && $pedido->idpedido > 0 ? $pedido->idpedido : 0; ?>';
      <?php $globalId = isset($pedido->idpedido) ? $pedido->idpedido : "0"; ?>
</script>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/admin/home">Inicio</a></li>
      <li class="breadcrumb-item"><a href="/admin/pedidos">Pedidos</a></li>
      <li class="breadcrumb-item active">Modificar</li>
</ol>
<ol class="toolbar">
      <li class="btn-item"><a title="Nuevo" href="/admin/pedido/nuevo" class="fa fa-plus-circle" aria-hidden="true"><span>Nuevo</span></a></li>
      <li class="btn-item"><a title="Guardar" href="#" class="fa fa-floppy-o" aria-hidden="true" onclick="javascript: $('#modalGuardar').modal('toggle');"><span>Guardar</span></a>
      </li>
      @if($globalId > 0)
      <li class="btn-item"><a title="Guardar" href="#" class="fa fa-trash-o" aria-hidden="true" onclick="javascript: $('#mdlEliminar').modal('toggle');"><span>Eliminar</span></a></li>
      @endif
      <li class="btn-item"><a title="Salir" href="#" class="fa fa-arrow-circle-o-left" aria-hidden="true" onclick="javascript: $('#modalSalir').modal('toggle');"><span>Salir</span></a></li>
</ol>
<script>
      function fsalir() {
            location.href = "/admin/sistema/menu";
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
                        <label>Cliente: *</label>
                        <select id="lstCliente" name="lstCliente" class="form-control"  required>
                              <option value="" disabled selected>Seleccionar</option>
                              @foreach($aClientes as $cliente)
                                    <option value="{{ $cliente->idcliente }}" 
                                          {{ isset($pedido->fk_idcliente) && $pedido->fk_idcliente == $cliente->idcliente ? 'selected' : '' }}>
                                          {{ $cliente->nombre }}
                                    </option>
                              @endforeach
                        </select>
                  </div>
                  <div class="form-group col-6">
                        <label>Sucursal: *</label>
                        <select id="lstSucursal" name="lstSucursal" class="form-control" required>
                              <option value="" disabled selected>Seleccionar</option>
                              @foreach($aSucursales as $sucursal)
                                    <option value="{{ $sucursal->idsucursal }}" 
                                          {{ isset($pedido->fk_idsucursal) && $pedido->fk_idsucursal == $sucursal->idsucursal ? 'selected' : '' }}>
                                          {{ $sucursal->nombre }}
                                    </option>
                              @endforeach
                        </select>
                  </div>
                  <div class="form-group col-6">
                        <label>Estado Pedido: *</label>
                        <select id="lstEstadoPedido" name="lstEstadoPedido" class="form-control" required>
                              <option value="" disabled selected>Seleccionar</option>
                              @foreach($aEstadoPedidos as $estadoPedido)
                                    <option value="{{ $estadoPedido->idestadopedido }}" 
                                          {{ isset($pedido->fk_idestadopedido) && $pedido->fk_idestadopedido == $estadoPedido->idestadopedido ? 'selected' : '' }}>
                                          {{ $estadoPedido->nombre }}
                                    </option>
                              @endforeach
                        </select>
                  </div>
                
                  <div class="form-group col-6">
                        <label>Fecha: *</label>
                        <input type="date" id="txtFecha" name="txtFecha" class="form-control" value="{{$pedido->fecha}}" required>
                  </div>
                  <div class="form-group col-6">
                        <label>Total: *</label>
                        <input type="text" id="txtTotal" name="txtTotal" class="form-control" value="{{$pedido->total}}" required>
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
            url: "{{ asset('/admin/pedido/eliminar') }}",
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