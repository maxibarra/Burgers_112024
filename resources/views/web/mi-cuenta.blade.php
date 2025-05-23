@extends("web.plantilla")
@section("contenido")
<section class="book_section layout_padding">
      <div class="container">
            <div>
                  <h2 class="text-center mb-3 card-header">
                        Datos del Usuario
                  </h2>
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="form_container contact-form">
                              <form action="" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
                                    <div class="row">
                                          <div class="form-group col-6">
                                                <input type="text" id="txtNombre" name="txtNombre" class="form-control" placeholder="Nombre" value="{{$cliente->nombre}}" required />
                                          </div>
                                          <div class="form-group col-6">
                                                <input type="text" id="txtApellido" name="txtApellido" class="form-control" placeholder="Apellido" value="{{$cliente->apellido}}" />
                                          </div>
                                          <div class="form-group col-6">
                                                <input type="email" id="txtCorreo" name="txtCorreo" class="form-control" placeholder="Correo" value="{{$cliente->correo}}" required />
                                          </div>
                                          <div class="form-group col-6">
                                                <input type="text" id="txtDni" name="txtDni" class="form-control" placeholder="Documento" value="{{$cliente->dni}}" required />
                                          </div>
                                          <div class="form-group col-6">
                                                <input type="text" id="txtCelular" name="txtCelular" class="form-control" placeholder="Celular" value="{{$cliente->celular}}" required />
                                          </div>
                                          <div class="form-group col-6">
                                                <input type="text" id="txtDireccion" name="txtDireccion" class="form-control" placeholder="Direccion" value="{{$cliente->direccion}}" required />
                                          </div>
                                    </div>
                                    <div class="btn_box text-center">
                                          <button type="submit" class="btn btn-primary btn-block col-3">
                                                Guardar
                                          </button>
                                    </div>
                              </form>
                        </div>
                  </div>
            </div>
            <div>
                  <a href="/cambiar-clave">
                        Cambiar Clave
                  </a>
            </div>
            <div>
                  <h2 class="text-center mt-3 card-header">
                        Pedidos Activos
                  </h2>
            </div>
            <div class="row">
                  <div class="col-12">
                        <table class="table table-striped table-bordered table-hover">
                              <thead>
                                    <tr>
                                          <th>Fecha</th>
                                          <th>Estado</th>
                                          <th>Sucursal</th>
                                          <th>Total</th>
                                    </tr>
                              </thead>

                              <tbody>
                                    @forelse($aPedidos as $pedido)

                                    <tr>
                                          <td>{{$pedido->fecha}}</td>

                                          <td>{{$pedido->estadopedido}}</td>
                                          <td>{{$pedido->sucursal}}</td>
                                          <td>$ {{$pedido->total}}</td>
                                    </tr>

                                    @empty

                                    <tr>
                                          <td colspan="5" class="text-center">No hay pedidos registrados.</td>
                                    </tr>
                                    @endforelse
                              </tbody>

                        </table>
                  </div>
            </div>
      </div>
</section>
@endsection