<?php require APPROOT . '/vistas/inc/header.php'; ?>

<div class = "d-flex justify-content-between align-items-center mb-4">
    <h1>Gestion de Profesores</h1>
    <a href="<?php echo URLROOT; ?>/admin/agregarProfesor" class="btn btn-primary">Agregar Profesor</a>
</div>

<?php flash('profesor_mensaje');?>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Telefono</th>
                <th>Especialidad</th>
                <th>Nivel</th>
                <th>Precio/Hora</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($datos['profesores'] as $profesor) : ?>
            <tr>
                <td><?php echo $profesor->nombre . ' '.$profesor->apellido; ?></td>
                <td><?php echo $profesor->email ; ?></td>
                <td><?php echo $profesor->telefono ; ?></td>
                <td><?php echo $profesor->especialidad; ?></td>
                <td><?php echo $profesor->nivel; ?></td>
                <td><?php echo number_format($profesor->precio_hora, 2); ?>€</td>
                <td>
                <span class="badge badge-<?php echo $profesor->disponible ? 'success' : 'danger'; ?>">
                            <?php echo $profesor->disponible ? 'Disponible' : 'No disponible'; ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?php echo
                        URLROOT; ?>/admin/horarioProfesor/<?php echo
                        $profesor->id; ?>"
                        class="btn btn-sm btn-info">Horario</a>
                        <a href="<?php echo URLROOT; ?>/admin/editarProfesor/<?php echo
                        $profesor->id; ?>"
                        class="btn btn-sm btn-warning">Editar</a>
                        <form class="d-inline" action="<?php echo URLROOT; ?>/admin/eliminarProfesor/<?php echo
                        $profesor->id; ?>" method="post">
                        <input type="submit" value="Eliminar" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar este profesor?');">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>