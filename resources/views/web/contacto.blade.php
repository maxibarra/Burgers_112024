@extends("web.plantilla")
@section("contenido")
<!-- book section -->
<section class="book_section layout_padding">
  <div class="container">
    <div class=" card card-header">
      <h2 class="text-center">
        Contactanos!
      </h2>
    </div>
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="form_container contact-form">
          <form action="" method="POST">
            <div class="form-group mt-3">
              <input type="text" id="txtUsuario" name="txtUsuario" class="form-control" placeholder="Nombre" />
            </div>
            <div class="form-group">
              <input type="email" id="txtCorreo" name="txtCorreo" class="form-control" placeholder="Correo" />
            </div>
            <div class="form-group ">
              <input type="text" id="txtAsunto" name="txtAsunto" class="form-control" placeholder="Asunto" />
            </div>
            <div class="form-group ">
              <textarea type="" id="txtMensaje" name="txtMensaje" class="form-control w-100  h-25" placeholder="Mensaje"></textarea>
            </div>
            <div class="btn_box text-center">
              <button type="submit">
                Enviar Mensaje
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- end book section -->
@endsection