<?php require APPROOT . '/vistas/inc/header.php'; ?>

<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Agregar Torneo</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo URLROOT; ?>/admin/torneos" class="btn btn-secondary">Volver a Torneos</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="<?php echo URLROOT; ?>/admin/agregarTorneo" method="post">
                <div class="form-group">
                    <label for="nombre">Nombre del Torneo:</label>
                    <input type="text" name="nombre" class="form-control <?php echo (!empty($datos['nombre_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $datos['nombre']; ?>">
                    <span class="invalid-feedback"><?php echo $datos['nombre_err']; ?></span>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="fecha_inicio">Fecha de Inicio:</label>
                        <input type="date" name="fecha_inicio" class="form-control <?php echo (!empty($datos['fecha_inicio_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $datos['fecha_inicio']; ?>">
                        <span class="invalid-feedback"><?php echo $datos['fecha_inicio_err']; ?></span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="fecha_fin">Fecha de Fin:</label>
                        <input type="date" name="fecha_fin" class="form-control <?php echo (!empty($datos['fecha_fin_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $datos['fecha_fin']; ?>">
                        <span class="invalid-feedback"><?php echo $datos['fecha_fin_err']; ?></span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea name="descripcion" class="form-control" rows="3"><?php echo $datos['descripcion']; ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="capacidad">Capacidad (número de participantes):</label>
                        <input type="number" name="capacidad" class="form-control <?php echo (!empty($datos['capacidad_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $datos['capacidad']; ?>">
                        <span class="invalid-feedback"><?php echo $datos['capacidad_err']; ?></span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="estado">Estado:</label>
                        <select name="estado" class="form-control">
                            <option value="programado" <?php echo ($datos['estado'] == 'programado') ? 'selected' : ''; ?>>Programado</option>
                            <option value="abierto" <?php echo ($datos['estado'] == 'abierto') ? 'selected' : ''; ?>>Abierto</option>
                            <option value="en_progreso" <?php echo ($datos['estado'] == 'en_progreso') ? 'selected' : ''; ?>>En Progreso</option>
                            <option value="finalizado" <?php echo ($datos['estado'] == 'finalizado') ? 'selected' : ''; ?>>Finalizado</option>
                            <option value="cerrado" <?php echo ($datos['estado'] == 'cerrado') ? 'selected' : ''; ?>>Cerrado</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="tipo">Tipo:</label>
                        <select name="tipo" class="form-control">
                            <option value="Individual" <?php echo ($datos['tipo'] == 'Individual') ? 'selected' : ''; ?>>Individual</option>
                            <option value="Dobles" <?php echo ($datos['tipo'] == 'Dobles') ? 'selected' : ''; ?>>Dobles</option>
                            <option value="Mixto" <?php echo ($datos['tipo'] == 'Mixto') ? 'selected' : ''; ?>>Mixto</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="nivel">Nivel:</label>
                        <select name="nivel" class="form-control">
                            <option value="Todos" <?php echo ($datos['nivel'] == 'Todos') ? 'selected' : ''; ?>>Todos</option>
                            <option value="Principiante" <?php echo ($datos['nivel'] == 'Principiante') ? 'selected' : ''; ?>>Principiante</option>
                            <option value="Intermedio" <?php echo ($datos['nivel'] == 'Intermedio') ? 'selected' : ''; ?>>Intermedio</option>
                            <option value="Avanzado" <?php echo ($datos['nivel'] == 'Avanzado') ? 'selected' : ''; ?>>Avanzado</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="precio_inscripcion">Precio de Inscripción (€):</label>
                        <input type="number" step="0.01" name="precio_inscripcion" class="form-control" value="<?php echo $datos['precio_inscripcion']; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Crear Torneo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>