<?php require APPROOT . '/vistas/inc/header.php'; ?>

<h1 class="mb-4">Reservar Pista: <?php echo $datos['pista']->nombre; ?></h1>

<div class="row">
    <div class="col-md-6">
        <h3>Detalles de la Pista</h3>
        <p>Tipo: <?php echo $datos['pista']->tipo; ?></p>
        <p>Estado: <?php echo $datos['pista']->estado; ?></p>
    </div>
    
    <div class="col-md-6">
        <form action="<?php echo URLROOT; ?>/pistas/reservar/<?php echo $datos['pista']->id; ?>" method="post">

            <div class="form-group">
                <label for="fecha">Fecha:</label>
                <input type="date" name="fecha" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="hora_inicio">Hora de inicio:</label>
                <input type="time" name="hora_inicio" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="hora_fin">Hora de fin:</label>
                <input type="time" name="hora_fin" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="tipo_reserva">Tipo de Reserva:</label>
                <select name="tipo_reserva" class="form-control" required>
                    <option value="individual">Individual (10€)</option>
                    <option value="doble">Doble (20€)</option>
                </select>
            </div>

            <input type="submit" class="btn btn-primary" value="Reservar">

        </form>
    </div>
</div>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>




