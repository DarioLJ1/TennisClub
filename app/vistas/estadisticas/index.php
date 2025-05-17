<?php require APPROOT . '/vistas/inc/header.php'; ?>

<h1 class="mb-4">Estadísticas de Uso de Pistas</h1>

<form action="<?php echo URLROOT; ?>/estadisticas/verEstadisticas" method="post">

    <div class="form-group">

        <label for="id_pista">Seleccionar Pista:</label>

        <select name="id_pista" class="form-control">
            <option value="todas">Todas las Pistas</option>
            <?php foreach($datos['pistas'] as $pista) : ?>
                <option value="<?php echo $pista->id; ?>"><?php echo $pista->nombre; ?></option>
            <?php endforeach; ?>
        </select>

    </div>

    <div class="form-group">
        <label for="fecha_inicio">Fecha de Inicio:</label>
        <input type="date" name="fecha_inicio" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="fecha_fin">Fecha de Fin:</label>
        <input type="date" name="fecha_fin" class="form-control" required>
    </div>

    <input type="submit" class="btn btn-primary" value="Ver Estadísticas">
    
</form>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>