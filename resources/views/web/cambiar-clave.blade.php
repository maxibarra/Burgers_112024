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
                        <div class="form_container">
                              <form action="" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
                                    <div class="form-group">
                                          <input type="password" id="txtClaveAnterior" name="txtClaveAnterior" class="form-control" placeholder="Clave nueva" />
                                    </div>
                                    <div class="form-group">
                                          <input type="password" id="txtClaveNueva" name="txtClaveNueva" class="form-control" placeholder="Repetir Clave nueva" />
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