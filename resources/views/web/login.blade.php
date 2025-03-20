@extends("web.plantilla")
@section("contenido")
<section class="book_section layout_padding">
      <div class="container">
            <div class=" card card-header">
                  <h2 class="text-center">
                        Ingresar
                  </h2>
            </div>
            <div class="row justify-content-center">
                  <div class="col-md-4">
                        <div class="form_container contact-form">

                              <form action="" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
                                    @if(isset($msg) && ($msg))
                                    <div class=" mt-4 alert alert-{{$msg["ESTADO"]}} text-center" role="alert">
                                          {{ $msg["MSG"] }}
                                    </div>
                                    @endif
                                    <div class="form-group mt-2">
                                          <input type="text" id="txtCorreo" name="txtCorreo" class="form-control" placeholder="Ingresar correo" />
                                    </div>
                                    <div class="form-group">
                                          <input type="password" id="txtClave" name="txtClave" class="form-control" placeholder="Clave" />
                                    </div>
                                    <div class="btn_box text-center">
                                          <button type="submit" name="btnIngresar">
                                                Entrar
                                          </button>
                                    </div>
                              </form>
                        </div>
                        <div class="text-center pt-4">
                              <a class="d-block small" href="/registrarse">No tenes cuenta? Registrate Acá</a>
                        </div>
                        <div class="text-center pt-3">
                              <a class="d-block small" href="/recuperar-clave">Recuperar clave</a>
                        </div>
                  </div>
            </div>
      </div>
</section>
@endsection