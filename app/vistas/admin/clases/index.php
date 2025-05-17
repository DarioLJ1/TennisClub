<?php require APPROOT . '/vistas/inc/header.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestión de Clases</h1>
    </div>

    <?php flash('clase_mensaje'); ?>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Profesor</th>
                    <th>Alumno</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($datos['clases'] as $clase) : ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($clase->fecha)); ?></td>
                        <td><?php echo substr($clase->hora_inicio, 0, 5) . ' - ' . substr($clase->hora_fin, 0, 5); ?></td>
                        <td><?php echo $clase->nombre_profesor . ' ' . $clase->apellido_profesor; ?></td>
                        <td><?php echo $clase->nombre_usuario; ?></td>
                        <td><?php echo $clase->tipo_clase ?? 'Individual'; ?></td>
                        <td>
                            <span class="badge badge-<?php 
                                echo (!empty($clase->estado) ? 
                                    ($clase->estado == 'Confirmada' ? 'success' : 
                                    ($clase->estado == 'Pendiente' ? 'warning' : 
                                    ($clase->estado == 'Cancelada' ? 'danger' : 'info'))) 
                                    : 'warning'); 
                            ?>">
                                <?php echo $clase->estado ?? 'Pendiente'; ?>
                            </span>
                        </td>
                        <td><?php echo number_format($clase->precio ?? 0, 2); ?>€</td>
                        <td>
                            <?php if(empty($clase->estado) || ($clase->estado != 'Cancelada' && $clase->estado != 'Completada')) : ?>
                                <form class="d-inline" action="<?php echo URLROOT; ?>/admin/actualizarEstadoClase/<?php echo $clase->id; ?>" method="post">
                                    <select name="estado" class="form-control form-control-sm d-inline-block w-auto mr-2">
                                        <option value="Pendiente" <?php echo ($clase->estado ?? 'Pendiente') == 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                        <option value="Confirmada" <?php echo ($clase->estado ?? '') == 'Confirmada' ? 'selected' : ''; ?>>Confirmada</option>
                                        <option value="Completada" <?php echo ($clase->estado ?? '') == 'Completada' ? 'selected' : ''; ?>>Completada</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Actualizar</button>
                                </form>
                                
                                <form class="d-inline" action="<?php echo URLROOT; ?>/admin/cancelarClase/<?php echo $clase->id; ?>" method="post">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de que desea cancelar esta clase?');">Cancelar</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>