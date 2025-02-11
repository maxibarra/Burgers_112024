@extends("web.plantilla")
@section("contenido")

<!-- about section -->

<section class="about_section layout_padding">
  <div class="container">
    <div class="row">
      <div class="col-md-6 ">
        <div class="img-box">
          <img src="web/images/about-img.png" alt="">
        </div>
      </div>
      <div class="col-md-6">
        <div class="detail-box">
          <div class="heading_container">
            <h2>
              Somos Burgers SRL
            </h2>
          </div>
          <p>
            Desde 2010, nos dedicamos a reinventar el arte de las burgers con pasión y calidad. Usamos ingredientes frescos, seleccionados localmente, y carnes 100% premium para crear combinaciones que despiertan los sentidos.

            Nuestro secreto: un equipo apasionado por la gastronomía, la innovación constante y el compromiso con nuestra comunidad. Cada hamburguesa que servimos no solo lleva nuestro sello de autenticidad, sino también la promesa de una experiencia única.

            Creemos en disfrutar sin límites, cuidar el medio ambiente (¡nuestros packaging son eco-friendly!) y conectar con quienes comparten nuestra obsesión por el buen comer.

            ¡Bienvenidos a la familia Burger SRL, donde cada bocado cuenta una historia!
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- end about section -->

<!-- client section -->

<section class="client_section layout_padding-bottom mt-4">
  <div class="container">
    <div class="heading_container heading_center psudo_white_primary mb_45">
      <h2>
        Que dicen nuestros clientes?
      </h2>
    </div>
    <div class="carousel-wrap row ">
      <div class="owl-carousel client_owl-carousel">
        <div class="item">
          <div class="box">
            <div class="detail-box">
              <p>
                Muy rico todo, y la atención un 10!
              </p>
              <h6>
                Moana Michell
              </h6>
              <p>
                magna aliqua
              </p>
            </div>
            <div class="img-box">
              <img src="web/images/client1.jpg" alt="" class="box-img">
            </div>
          </div>
        </div>
        <div class="item">
          <div class="box">
            <div class="detail-box">
              <p>
                Muy ricas hamburguesas!, sin dudas voy a volver
              </p>
              <h6>
                Mike Hamell
              </h6>
              <p>
                magna aliqua
              </p>
            </div>
            <div class="img-box">
              <img src="web/images/client2.jpg" alt="" class="box-img">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- end client section -->

<!-- postulacion section -->
<section class="book_section m-3">
  <div class="container">
    <div class=" card card-header">
      <h2 class="text-center">
        Queres trabajar con nosotros? Postulate aqui!
      </h2>
    </div>
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="form_container contact-form">
          <form action="" method="POST" class="mb-4" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
            <div class="form-group mt-3">
              <input type="text" id="txtNombre" name="txtNombre" class="form-control" placeholder="Nombre" required />
            </div>
            <div class="form-group mt-3">
              <input type="text" id="txtApellido" name="txtApellido" class="form-control" placeholder="Apellido" required />
            </div>
            <div class="form-group">
              <input type="email" id="txtCorreo" name="txtCorreo" class="form-control" placeholder="Correo" required />
            </div>
            <div class="form-group ">
              <input type="text" id="txtWhatsapp" name="txtWhatsapp" class="form-control" placeholder="whatsapp" required />
            </div>
            <div class="form-group ">
              <label>Cargar Cv: </label> <br>
              <input type="file" id="archivo" name="archivo" accept=".doc, .docx, .pdf">
              <small class="d-block">Archivos admitidos:.doc, .docx, .pdf</small>
            </div>
            <div class="btn_box text-center">
              <button type="submit">
                Enviar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- end postulacion section -->
@endsection