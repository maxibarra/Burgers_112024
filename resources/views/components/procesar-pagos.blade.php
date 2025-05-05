<div class="modal fade" id="procesarPagos" tabindex="-1" aria-labelledby="procesarPagos" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="procesarPagos">Terminal de Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">

                    <!-- Importe -->
                    <div class="mb-3">
                        <label for="monto" class="form-label">Importe</label>
                        <input type="number" class="form-control" id="monto" name="monto" value="{{ $total }}"
                            {{ $total ? 'readonly' : '' }} required>
                    </div>
                    <!-- Tipo de tarjeta -->
                    <div class="mb-3">
                        <label for="tipo_tarjeta" class="form-label">Tipo de tarjeta</label>
                        <select class="form-select" id="tipo_tarjeta" name="tipo_tarjeta" required>
                            <option value="">Seleccione</option>
                            <option value="VISA">VISA</option>
                            <option value="Mastercard">Mastercard</option>
                            <option value="AMEX">AMEX</option>
                        </select>
                    </div>

                    <!-- Número de tarjeta -->
                    <div class="mb-3">
                        <label for="numero_tarjeta" class="form-label">Número de tarjeta</label>
                        <input type="text" class="form-control" id="numero_tarjeta" name="numero_tarjeta" maxlength="16" required>
                    </div>


                    <!-- Vencimiento -->
                    <div class="row mb-3">
                        <div class="col">
                            <label for="vencimiento_mes" class="form-label">Mes (MM)</label>
                            <input type="text" class="form-control" id="vencimiento_mes" name="vencimiento_mes" maxlength="2" required>
                        </div>
                        <div class="col">
                            <label for="vencimiento_anio" class="form-label">Año (AAAA)</label>
                            <input type="text" class="form-control" id="vencimiento_anio" name="vencimiento_anio" maxlength="4" required>
                        </div>
                    </div>

                    <!-- CVV -->
                    <div class="mb-3">
                        <label for="cvv" class="form-label">CVV</label>
                        <input type="text" class="form-control" id="cvv" name="cvv" maxlength="4" required>
                    </div>

                    <!-- Nombre del titular -->
                    <div class="mb-3">
                        <label for="nombre_titular" class="form-label">Nombre del titular</label>
                        <input type="text" class="form-control" id="nombre_titular" name="nombre_titular" required>
                    </div>

                    <!-- Tipo de documento -->
                    <div class="mb-3">
                        <label for="tipo_documento" class="form-label">Tipo de documento</label>
                        <select class="form-select" id="tipo_documento" name="tipo_documento" required>
                            <option value="">Seleccione</option>
                            <option value="DNI">DNI</option>
                            <option value="CUIT">CUIT</option>
                            <option value="Pasaporte">Pasaporte</option>
                        </select>
                    </div>

                    <!-- Documento del titular -->
                    <div class="mb-3">
                        <label for="documento_titular" class="form-label">Documento del titular</label>
                        <input type="text" class="form-control" id="documento_titular" name="documento_titular" required>
                    </div>

                    <!-- Cuotas -->
                    <div class="mb-3">
                        <label for="cuotas" class="form-label">Cuotas</label>
                        <select class="form-select" id="cuotas" name="cuotas" required>
                            <option value="">Seleccione</option>
                            <option value="1">1 cuotas </option>
                            <option value="3">3 cuotas </option>
                            <option value="6">6 cuotas </option>
                            <option value="12">12 cuotas </option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Procesar Pago</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        function actualizarOpcionesCuotas() {
            var monto = parseFloat($('#monto').val());
            var $cuotas = $('#cuotas');

            $cuotas.find('option').each(function() {
                var valor = parseInt($(this).val());

                // Evitamos la opción "Seleccione"
                if (!isNaN(valor) && !isNaN(monto) && monto > 0) {
                    var cuota = (monto / valor).toFixed(2);
                    $(this).text(`${valor} cuota${valor > 1 ? 's' : '' } (en $${cuota} sin interés)`);
                    $(this).text(valor === 1 ? `${valor} cuota de $${cuota}` :`${valor} cuotas de $${cuota} sin interés`
                    );
                }
            });
        }

        // Eventos para actualizar dinámicamente
        $('#monto').on('input', actualizarOpcionesCuotas);

        // Ejecutar al cargar si el monto ya viene cargado
        actualizarOpcionesCuotas();
    });
</script>