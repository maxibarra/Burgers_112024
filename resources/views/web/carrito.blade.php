@extends("web.plantilla")
@section("contenido")
<section class=" food_section layout_padding">
      <div class="container">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
            @if(isset($msg) && ($msg))
            <div class=" mt-4 alert alert-{{$msg["ESTADO"]}} text-center" role="alert">
                  {{ $msg["MSG"] }}
            </div>
            @endif
            <div class="heading_container">
                  <h2>
                        Mi carrito
                  </h2>
            </div>


            @if($aCarritos)
            <div class="row">
                  <div class="col-9">
                        <div class="row mt-2 p-2">

                              <div class="col-md-12">
                                    <div class="table table-hover">
                                          <table>
                                                <thead>
                                                      <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th>Precio</th>
                                                            <th style="width:15px;">Cantidad</th>
                                                            <th>SubTotal</th>
                                                      </tr>
                                                </thead>
                                                <tbody
                                                      <?php
                                                      $total = 0;
                                                      ?>
                                                      @foreach($aCarritos as $carrito)
                                                      <?php
                                                      $subtotal = $carrito->precio * $carrito->cantidad;
                                                      $total += $subtotal;
                                                      ?>
                                                      <tr>
                                                      <form action="" method="POST">
                                                            <td style="width: 0px;">
                                                                  <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
                                                                  <input type="hidden" id="txtCarrito" name="txtCarrito" class="form-control" value="{{$carrito->idcarrito}}" required>
                                                            </td>
                                                            <td style="width:100px;">
                                                                  <img src="files/{{$carrito->imagen}}" class="img-thumbnail">
                                                            </td>
                                                            <td>
                                                                  ${{$carrito->producto}}
                                                            </td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>
                                                                  ${{$carrito->precio}}
                                                            </td>
                                                            <td style="width:15px;">
                                                                  <input id="txtProducto" name="txtProducto" type="hidden" class="form-control" value="{{$carrito->fk_idproducto}}" required>
                                                                  <input id="txtCantidad" name="txtCantidad" type="number" class="form-control" min="1" value="{{$carrito->cantidad}}" required>
                                                            </td>
                                                            <td>
                                                                  ${{number_format($subtotal,2,".",",") }}
                                                            </td>
                                                            <td>
                                                                  <div class="btn-group">
                                                                        <button type="submit" class="btn btn-info" id="btnActualizar" name="btnActualizar">
                                                                              <i class="fa-solid fa-rotate-right"></i>
                                                                        </button>
                                                                        <button type="submit" class="btn btn-danger" id="btnBorrar" name="btnBorrar">
                                                                              <i class="fa-solid fa-trash"></i>
                                                                        </button>
                                                                  </div>
                                                            </td>
                                                      </form>
                                                      </tr>
                                                      @endforeach
                                                      <tr>
                                                            <td colspan="4" style="text-align: right;">¿te falto algo?</td>
                                                            <td colspan="2" style="text-align: right;"><a class="btn btn-primary" href="/takeaway">Continuar pedido</a></td>
                                                      </tr>
                                                </tbody>
                                          </table>
                                    </div>
                              </div>
                        </div>
                  </div>

                  <div class="col-3">
                        <div class="row mt-2 p-2">
                              <div class="col-md-12">
                                    <div class="table">
                                          <table>
                                                <thead>
                                                      <tr>
                                                            <th>Total:${{number_format($total,2,".",".")}} </th>

                                                      </tr>
                                                </thead>
                                                <tbody>
                                                      <form action="" method="POST">
                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
                                                <tbody>
                                                      <tr>
                                                            <td>
                                                                  <label>Sucursal:</label><br>
                                                                  <select id="lstSucursal" name="lstSucursal" class="form-select" required>
                                                                        <option value="" disabled selected>Seleccionar</option>
                                                                        @foreach($aSucursales as $sucursal)
                                                                        <option value="{{$sucursal->idsucursal}}">{{$sucursal->nombre}}</option>
                                                                        @endforeach
                                                                  </select>
                                                            </td>

                                                      </tr>
                                                      <tr>
                                                            <td>
                                                                  <label>Metodo de pago:</label><br>
                                                                  <select id="lstPago" name="lstPago" class="form-select" required>
                                                                        <option value="" disabled selected>Seleccionar</option>
                                                                        <option value="Mercadopago">Mercado Pago</option>
                                                                        <option value="Efectivo">Efectivo</option>
                                                                  </select>
                                                            </td>
                                                      </tr>
                                                      <tr>
                                                            <td><button type="submit" class="btn btn-success" id="btnFinalizar" name="btnFinalizar">Finalizar Compra</button></td>
                                                      </tr>
                                                </tbody>
                                                </form>
                                                </tbody>
                                          </table>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
            @else

            <div class="heading_container text-center">
                  <h2>
                        No hay productos en el carrito
                  </h2>
            </div>
            @endif
      </div>
</section>

@endsection