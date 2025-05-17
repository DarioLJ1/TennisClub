<?php require APPROOT . '/vistas/inc/header.php'; ?>

<h1 class="mb-4">Editar Pista</h1>

<form action="<?php echo URLROOT; ?>/admin/editarPista/<?php echo $datos['id']; ?>" method="post">

    <div class="form-group">
        <label for="nombre">Nombre de la Pista:</label>
        <input type="text" name="nombre" class="form-control <?php echo (!empty($datos['nombre_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $datos['nombre']; ?>">
        <span class="invalid-feedback"><?php echo $datos['nombre_err']; ?></span>
    </div>

    <div class="form-group">
        <label for="tipo">Tipo de Pista:</label>
        <select name="tipo" class="form-control <?php echo (!empty($datos['tipo_err'])) ? 'is-invalid' : ''; ?>">
            <option value="Tierra batida" <?php echo ($datos['tipo'] == 'Tierra batida') ? 'selected' : ''; ?>>Tierra batida</option>
            <option value="Césped" <?php echo ($datos['tipo'] == 'Césped') ? 'selected' : ''; ?>>Césped</option>
            <option value="Dura" <?php echo ($datos['tipo'] == 'Dura') ? 'selected' : ''; ?>>Dura</option>
        </select>
        <span class="invalid-feedback"><?php echo $datos['tipo_err']; ?></span>
    </div>

    <div class="form-group">
        <label for="estado">Estado de la Pista:</label>
        <select name="estado" class="form-control <?php echo (!empty($datos['estado_err'])) ? 'is-invalid' : ''; ?>">
            <option value="Disponible" <?php echo ($datos['estado'] == 'Disponible') ? 'selected' : ''; ?>>Disponible</option>
            <option value="En mantenimiento" <?php echo ($datos['estado'] == 'En mantenimiento') ? 'selected' : ''; ?>>En mantenimiento</option>
            <option value="Fuera de servicio" <?php echo ($datos['estado'] == 'Fuera de servicio') ? 'selected' : ''; ?>>Fuera de servicio</option>
        </select>
        <span class="invalid-feedback"><?php echo $datos['estado_err']; ?></span>
    </div>

    <input type="submit" class="btn btn-primary" value="Actualizar Pista">
    
</form>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>