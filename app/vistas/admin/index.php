<?php require APPROOT . '/vistas/inc/header.php'; ?>

<h1 class="mb-4">Panel de Administración</h1>

<div class="row mb-4">

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Gestión de Pistas</h5>
                <p class="card-text">Administrar pistas de tenis y su mantenimiento</p>
                <a href="<?php echo URLROOT; ?>/admin/agregarPista" class="btn btn-primary">Agregar Nueva Pista</a>
                <a href="<?php echo URLROOT; ?>/estadisticas" class="btn btn-info">Ver Estadísticas</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Gestión de Profesores</h5>
                <p class="card-text">Administrar profesores y sus horarios</p>
                <a href="<?php echo URLROOT; ?>/admin/profesores" class="btn btn-primary">Gestionar Profesores</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Gestión de Clases</h5>
                <p class="card-text">Administrar clases particulares y sus estados</p>
                <a href="<?php echo URLROOT; ?>/admin/clases" class="btn btn-primary">Gestionar Clases</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Gestión de Torneos</h5>
                <p class="card-text">Administrar torneos y sus inscripciones</p>
                <a href="<?php echo URLROOT; ?>/admin/torneos" class="btn btn-primary">Gestionar Torneos</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Gestión de Usuarios</h5>
                <p class="card-text">Administrar usuarios y sus permisos</p>
                <a href="<?php echo URLROOT; ?>/admin/usuarios" class="btn btn-primary">Gestionar Usuarios</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Informes</h5>
                <p class="card-text">Generar informes de reservas, clases, torneos y usuarios</p>
                <a href="<?php echo URLROOT; ?>/informes" class="btn btn-primary">Gestionar Informes</a>
            </div>
        </div>
    </div>

</div>

<?php flash('pista_mensaje'); ?>

<h2>Listado de Pistas</h2>

<table class="table table-striped">

    <thead>
        <tr>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($datos['pistas'] as $pista) : ?>
            <tr>
                <td><?php echo $pista->nombre; ?></td>
                <td><?php echo $pista->tipo; ?></td>
                <td><?php echo $pista->estado; ?></td>
                <td>
                    <a href="<?php echo URLROOT; ?>/admin/verPista/<?php echo $pista->id; ?>" class="btn btn-sm btn-info">Ver Detalles</a>
                    <a href="<?php echo URLROOT; ?>/admin/editarPista/<?php echo $pista->id; ?>" class="btn btn-sm btn-warning">Editar</a>
                    <form class="d-inline" action="<?php echo URLROOT; ?>/admin/eliminarPista/<?php echo $pista->id; ?>" method="post">
                        <input type="submit" value="Eliminar" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar esta pista?');">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    
</table>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>