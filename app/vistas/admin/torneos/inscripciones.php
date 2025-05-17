<?php require APPROOT . '/vistas/inc/header.php'; ?>

<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Inscripciones - <?php echo $datos['torneo']->nombre; ?></h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo URLROOT; ?>/admin/torneos" class="btn btn-secondary">Volver a Torneos</a>
        </div>
    </div>

    <?php flash('inscripcion_mensaje'); ?>

    <div class="card mb-4">
        <div class="card-header">
            <h3>Detalles del Torneo</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Fechas:</strong> <?php echo date('d/m/Y', strtotime($datos['torneo']->fecha_inicio)) . ' - ' . date('d/m/Y', strtotime($datos['torneo']->fecha_fin)); ?></p>
                    <p><strong>Capacidad:</strong> <?php echo $datos['torneo']->capacidad; ?> participantes</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Estado:</strong> 
                        <span class="badge badge-<?php 
                            echo $datos['torneo']->estado == 'abierto' ? 'success' : 
                                ($datos['torneo']->estado == 'finalizado' ? 'secondary' : 'info'); 
                        ?>">
                            <?php echo ucfirst($datos['torneo']->estado); ?>
                        </span>
                    </p>
                    <p><strong>Descripción:</strong> <?php echo $datos['torneo']->descripcion; ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Listado de Inscripciones</h3>
        </div>
        <div class="card-body">
            <?php if(empty($datos['inscripciones'])) : ?>
                <p>No hay inscripciones para este torneo.</p>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Participante</th>
                                <th>Fecha de Inscripción</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($datos['inscripciones'] as $inscripcion) : ?>
                                <tr>
                                    <td><?php echo $inscripcion->nombre; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($inscripcion->fecha_inscripcion)); ?></td>
                                    <td>
                                        <span class="badge badge-<?php 
                                            echo $inscripcion->estado == 'confirmada' ? 'success' : 
                                                ($inscripcion->estado == 'rechazada' ? 'danger' : 'warning'); 
                                        ?>">
                                            <?php echo ucfirst($inscripcion->estado); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form action="<?php echo URLROOT; ?>/admin/actualizarEstadoInscripcion/<?php echo $inscripcion->id; ?>" method="post" class="form-inline">
                                            <input type="hidden" name="id_torneo" value="<?php echo $datos['torneo']->id; ?>">
                                            <select name="estado" class="form-control form-control-sm mr-2">
                                                <option value="pendiente" <?php echo ($inscripcion->estado == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                                                <option value="confirmada" <?php echo ($inscripcion->estado == 'confirmada') ? 'selected' : ''; ?>>Confirmada</option>
                                                <option value="rechazada" <?php echo ($inscripcion->estado == 'rechazada') ? 'selected' : ''; ?>>Rechazada</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-primary">Actualizar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>