<?php require APPROOT . '/vistas/inc/header.php'; ?>

<h1 class="mb-4">Agregar Mantenimiento</h1>

<form action="<?php echo URLROOT; ?>/admin/agregarMantenimiento/<?php echo $datos['id_pista']; ?>" method="post">
    
    <div class="form-group">
        <label for="fecha_inicio">Fecha de Inicio:</label>
        <input type="date" name="fecha_inicio" class="form-control <?php echo (!empty($datos['fecha_inicio_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $datos['fecha_inicio']; ?>">
        <span class="invalid-feedback"><?php echo $datos['fecha_inicio_err']; ?></span>
    </div>

    <div class="form-group">
        <label for="fecha_fin">Fecha de Fin (opcional):</label>
        <input type="date" name="fecha_fin" class="form-control <?php echo (!empty($datos['fecha_fin_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $datos['fecha_fin']; ?>">
        <span class="invalid-feedback"><?php echo $datos['fecha_fin_err']; ?></span>
    </div>

    <div class="form-group">
        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" class="form-control <?php echo (!empty($datos['descripcion_err'])) ? 'is-invalid' : ''; ?>"><?php echo $datos['descripcion']; ?></textarea>
        <span class="invalid-feedback"><?php echo $datos['descripcion_err']; ?></span>
    </div>

    <div class="form-group">
        <label for="estado">Estado:</label>
        <select name="estado" class="form-control">
            <option value="Programado" <?php echo ($datos['estado'] == 'Programado') ? 'selected' : ''; ?>>Programado</option>
            <option value="En progreso" <?php echo ($datos['estado'] == 'En progreso') ? 'selected' : ''; ?>>En progreso</option>
            <option value="Completado" <?php echo ($datos['estado'] == 'Completado') ? 'selected' : ''; ?>>Completado</option>
        </select>
    </div>

    <input type="submit" class="btn btn-primary" value="Agregar Mantenimiento">

</form>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>