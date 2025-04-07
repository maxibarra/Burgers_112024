@extends("web.plantilla")
@section("contenido")
<!-- food section -->

<section class="food_section layout_padding">
  <div class="container">
    <div class="heading_container heading_center">
      <h2>
        Nuestro menú
      </h2>
    </div>

    <ul class="filters_menu">
      <li class="active" data-filter="*">Todos</li>
      @foreach($aCategorias as $categoria)
      <li data-filter=".{{$categoria->nombre}}">{{$categoria->nombre}}</li>
      @endforeach
    </ul>
    @if(isset($msg) && ($msg))
    <div class="alert alert-{{$msg["ESTADO"]}} text-center" role="alert">
      {{ $msg["MSG"] }}
    </div>
    @endif

    <div class="filters-content">
      <div class="row grid">
        @foreach($aProductos as $producto)
        <div class="col-sm-6 col-lg-4 all {{$producto->tipoProducto}}">
          <div class="box">
            <div>
              <div class="img-box">
                <img src="{{'files/'.$producto->imagen}}" alt="">
              </div>
              <div class="detail-box text-center">
                <h5>
                  {{$producto->nombre}}
                </h5>
                <p>
                  {{$producto->descripcion}}
                </p>
                <div class="options">
                  <h6>
                    $ {{ number_format($producto->precio , 2, ',', '.') }}
                  </h6>
                  <form action="" method="POST" class="form-group">
                    <!-- cantidad de productos que se desea agregar al carrito -->
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
                    <input type="hidden" id="txtProducto" name="txtProducto" class="form-control" style="width:60px;" value="{{$producto->idproducto}}" required>
                    <input type="number" id="txtCantidad" name="txtCantidad" class="form-control" style="width:60px;" value="0" min="0" required>
                    <button type="submit" class="btn">
                      <i class="fa-solid fa-cart-shopping"></i>
                    </button>
                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
</section>

<!-- end food section -->
@endsection