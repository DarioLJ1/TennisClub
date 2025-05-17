<?php require APPROOT . '/vistas/inc/header.php'; ?>

<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Gestión de Torneos</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo URLROOT; ?>/admin/agregarTorneo" class="btn btn-primary">Agregar Torneo</a>
            <a href="<?php echo URLROOT; ?>/admin" class="btn btn-secondary">Volver al Panel</a>
        </div>
    </div>

    <?php flash('torneo_mensaje'); ?>

    <div class="card">
        <div class="card-header">
            <h3>Listado de Torneos</h3>
        </div>
        <div class="card-body">
            <?php if(empty($datos['torneos'])) : ?>
                <p>No hay torneos registrados.</p>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Fechas</th>
                                <th>Capacidad</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($datos['torneos'] as $torneo) : ?>
                                <tr>
                                    <td><?php echo $torneo->nombre; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($torneo->fecha_inicio)) . ' - ' . date('d/m/Y', strtotime($torneo->fecha_fin)); ?></td>
                                    <td><?php echo $torneo->capacidad; ?></td>
                                    <td>
                                        <span class="badge badge-<?php 
                                            echo $torneo->estado == 'abierto' ? 'success' : 
                                                ($torneo->estado == 'finalizado' ? 'secondary' : 'info'); 
                                        ?>">
                                            <?php echo ucfirst($torneo->estado); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo URLROOT; ?>/admin/verInscripciones/<?php echo $torneo->id; ?>" class="btn btn-sm btn-info">Ver Inscripciones</a>
                                        <a href="<?php echo URLROOT; ?>/admin/editarTorneo/<?php echo $torneo->id; ?>" class="btn btn-sm btn-warning">Editar</a>
                                        <form class="d-inline" action="<?php echo URLROOT; ?>/admin/eliminarTorneo/<?php echo $torneo->id; ?>" method="post">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar este torneo?');">Eliminar</button>
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