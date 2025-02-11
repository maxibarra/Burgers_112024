@extends("web.plantilla")
@section("contenido")
<section class="book_section layout_padding">
      <div class="container">
            <div>
                  <h2 class="text-center mb-3">
                        Registrate
                  </h2>
            </div>
            @if(isset($msg) && ($msg))
            <div class="alert alert-{{$msg["ESTADO"]}} text-center" role="alert">
                  {{ $msg["MSG"] }}
            </div>
            @endif
            <div class="row">
                  <div class="col-md-12">
                        <div class="form_container contact-form">
                              <form action="" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
                                    <div class="row">
                                          <div class="form-group col-6">
                                                <input type="text" id="txtNombre" name="txtNombre" class="form-control" placeholder="Nombre" />
                                          </div>
                                          <div class="form-group col-6">
                                                <input type="text" id="txtApellido" name="txtApellido" class="form-control" placeholder="Apellido" />
                                          </div>
                                          <div class="form-group col-6">
                                                <input type="email" id="txtCorreo" name="txtCorreo" class="form-control" placeholder="Correo" />
                                          </div>
                                          <div class="form-group col-6">
                                                <input type="text" id="txtDni" name="txtDni" class="form-control" placeholder="Documento" />
                                          </div>
                                          <div class="form-group col-6">
                                                <input type="text" id="txtCelular" name="txtCelular" class="form-control" placeholder="celular" />
                                          </div>
                                          <div class="form-group col-6">
                                                <input type="text" id="txtDireccion" name="txtDireccion" class="form-control" placeholder="Direccion" />
                                          </div>
                                          <div class="form-group col-6">
                                                <input type="password" id="txtClave" name="txtClave" class="form-control" placeholder="Clave" />
                                          </div>
                                    </div>
                                    <div class="btn_box text-center">
                                          <button type="submit" class="btn btn-primary btn-block col-6">
                                                Registrarse
                                          </button>
                                    </div>

                              </form>
                        </div>
                  </div>
            </div>
      </div>
</section>
@endsection