@extends("web.plantilla")
@section("contenido")
<section class="book_section layout_padding">
      <div class="container">
            <div>
                  <h2 class="text-center mb-5">
                        Recuperar Clave
                  </h2>
            </div>
            <div class="row justify-content-center">
                  <div class="col-md-6">
                        <div class="form_container">
                              <form action="" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
                                    @if(isset($mensaje))
                                          <div class="alert alert-secondary text-center" role="alert">
                                                {{ $mensaje }}
                                          </div>
                                    @else      
                                    <div class="form-group">
                                          <input type="mail" id="txtCorreo" name="txtCorreo" class="form-control" placeholder="Correo" />
                                    </div>
                                    <div class="btn_box text-center">
                                          <button>
                                                Recuperar Clave
                                          </button>
                                    </div>
                              @endif
                              </form>
                        </div>
                  </div>
            </div>
      </div>
</section>
@endsection