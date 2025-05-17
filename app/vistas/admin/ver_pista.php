<?php require APPROOT . '/vistas/inc/header.php'; ?>

<h1 class="mb-4">Detalles de la Pista: <?php echo $datos['pista']->nombre; ?></h1>

<?php flash('mantenimiento_mensaje'); ?>

<div class="card mb-4">

    <div class="card-body">
        <h5 class="card-title">Información de la Pista</h5>
        <p><strong>Tipo:</strong> <?php echo $datos['pista']->tipo; ?></p>
        <p><strong>Estado:</strong> <?php echo $datos['pista']->estado; ?></p>
    </div>

</div>

<h2 class="mb-4">Historial de Mantenimiento</h2>

<a href="<?php echo URLROOT; ?>/admin/agregarMantenimiento/<?php echo $datos['pista']->id; ?>" class="btn btn-primary mb-3">Agregar Mantenimiento</a>

<table class="table table-striped">

    <thead>
        <tr>
            <th>Fecha de Inicio</th>
            <th>Fecha de Fin</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>

        <?php foreach($datos['mantenimientos'] as $mantenimiento) : ?>
            <tr>
                <td><?php echo $mantenimiento->fecha_inicio; ?></td>
                <td><?php echo $mantenimiento->fecha_fin ? $mantenimiento->fecha_fin : 'N/A'; ?></td>
                <td><?php echo $mantenimiento->descripcion; ?></td>
                <td><?php echo $mantenimiento->estado; ?></td>
                <td>
                    <a href="<?php echo URLROOT; ?>/admin/editarMantenimiento/<?php echo $mantenimiento->id; ?>" class="btn btn-sm btn-warning">Editar</a>
                    <form class="d-inline" action="<?php echo URLROOT; ?>/admin/eliminarMantenimiento/<?php echo $mantenimiento->id; ?>" method="post">
                        <input type="submit" value="Eliminar" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar este mantenimiento?');">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>

    </tbody>
</table>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>