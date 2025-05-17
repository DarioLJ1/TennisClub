<?php require APPROOT . '/vistas/inc/header.php'; ?>

<div class="row">
    <div class="col-md-6 mx-auto">

        <div class="card card-body bg-light mt-5">
            <h2>Procesar Pago</h2>
            <p>Por favor, complete los detalles del pago para la reserva.</p>
            <form action="<?php echo URLROOT; ?>/pagos/procesar/<?php echo $datos['id_reserva']; ?>" method="post">
                <div class="form-group">
                    <label for="monto">Monto a pagar:</label>
                    <input type="text" name="monto" class="form-control" value="<?php echo $datos['monto']; ?>€" readonly>
                </div>

                <div class="form-group">
                    <label for="metodo_pago">Método de Pago:</label>
                    <select name="metodo_pago" id="metodo_pago" class="form-control" required>
                        <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                        <option value="metalico">Metálico</option>
                    </select>
                </div>

                <div id="detalles_tarjeta">
                    <div class="form-group">
                        <label for="numero_tarjeta">Número de Tarjeta:</label>
                        <input type="text" name="numero_tarjeta" id="numero_tarjeta" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="fecha_expiracion">Fecha de Expiración:</label>
                        <input type="text" name="fecha_expiracion" id="fecha_expiracion" class="form-control" placeholder="MM/YY">
                    </div>

                    <div class="form-group">
                        <label for="cvv">CVV:</label>
                        <input type="text" name="cvv" id="cvv" class="form-control">
                    </div>

                </div>
                
                <input type="submit" class="btn btn-primary" value="Procesar Pago">

            </form>
        </div>
    </div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function() {
    const metodoPago = document.getElementById('metodo_pago');
    const detallesTarjeta = document.getElementById('detalles_tarjeta');
    const camposTarjeta = detallesTarjeta.querySelectorAll('input');

    function toggleDetallesTarjeta() {
        if (metodoPago.value === 'metalico') {
            detallesTarjeta.style.display = 'none';
            camposTarjeta.forEach(campo => campo.disabled = true);
        } else {
            detallesTarjeta.style.display = 'block';
            camposTarjeta.forEach(campo => campo.disabled = false);
        }
    }

    metodoPago.addEventListener('change', toggleDetallesTarjeta);
    toggleDetallesTarjeta();

});
</script>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>



