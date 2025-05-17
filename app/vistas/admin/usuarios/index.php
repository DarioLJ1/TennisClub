<?php require APPROOT . '/vistas/inc/header.php'; ?>

<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Gestión de Usuarios</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo URLROOT; ?>/admin" class="btn btn-secondary">Volver al Panel</a>
        </div>
    </div>

    <?php flash('usuario_mensaje'); ?>

    <div class="card">
        <div class="card-header">
            <h3>Listado de Usuarios</h3>
        </div>
        <div class="card-body">
            <?php if(empty($datos['usuarios'])) : ?>
                <p>No hay usuarios registrados.</p>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Fecha de Registro</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($datos['usuarios'] as $usuario) : ?>
                                <tr>
                                    <td><?php echo $usuario->id; ?></td>
                                    <td><?php echo $usuario->nombre; ?></td>
                                    <td><?php echo $usuario->email; ?></td>
                                    <td>
                                        <?php 
                                        if(isset($usuario->fecha_registro)) {
                                            echo date('d/m/Y', strtotime($usuario->fecha_registro));
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php if(isset($usuario->is_admin) && $usuario->is_admin) : ?>
                                            <span class="badge badge-primary">Administrador</span>
                                        <?php elseif(isset($usuario->role) && $usuario->role == 'admin') : ?>
                                            <span class="badge badge-primary">Administrador</span>
                                        <?php else : ?>
                                            <span class="badge badge-secondary">Usuario</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($usuario->id != $_SESSION['user_id']) : ?>
                                            <?php if((isset($usuario->is_admin) && $usuario->is_admin) || 
                                                    (isset($usuario->role) && $usuario->role == 'admin')) : ?>
                                                <form class="d-inline" action="<?php echo URLROOT; ?>/admin/cambiarRolUsuario/<?php echo $usuario->id; ?>" method="post">
                                                    <input type="hidden" name="rol" value="0">
                                                    <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('¿Está seguro de que desea quitar los permisos de administrador a este usuario?');">Quitar Admin</button>
                                                </form>
                                            <?php else : ?>
                                                <form class="d-inline" action="<?php echo URLROOT; ?>/admin/cambiarRolUsuario/<?php echo $usuario->id; ?>" method="post">
                                                    <input type="hidden" name="rol" value="1">
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('¿Está seguro de que desea hacer administrador a este usuario?');">Hacer Admin</button>
                                                </form>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <span class="text-muted">Usuario actual</span>
                                        <?php endif; ?>
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