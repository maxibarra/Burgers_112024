@extends("web.plantilla")
@section("contenido")
<section class="book_section layout_padding">
      <div class="container">
            <div>
                  <h2 class="text-center mb-5">
                        Cambiar Clave
                  </h2>
            </div>
            <div class="row justify-content-center">
                  <div class="col-md-6">
                        @if(isset($msg) && ($msg))
                        <div class="alert alert-{{$msg["ESTADO"]}}" role="alert">
                              {{ $msg["MSG"] }}
                        </div>
                        @endif
                        <div class="form_container">
                              <form action="" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
                                    <div class="form-group">
                                          <input type="password" id="txtClave1" name="txtClave1" class="form-control" placeholder="Clave nueva" />
                                    </div>
                                    <div class="form-group">
                                          <input type="password" id="txtClave2" name="txtClave2" class="form-control" placeholder="Repetir Clave nueva" />
                                    </div>
                                    <div class="btn_box text-center">
                                          <button>
                                                Aceptar Cambio
                                          </button>
                                    </div>
                              </form>
                        </div>
                  </div>
            </div>
      </div>
</section>
@endsection