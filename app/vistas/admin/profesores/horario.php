<?php require APPROOT . '/vistas/inc/header.php'; ?>

<h1 class="mb-4">Horario del Profesor: <?php echo $datos['profesor']->nombre . ' ' . $datos['profesor']->apellido; ?></h1>

<?php flash('horario_mensaje'); ?>

<div class="row mb-4">

    <div class="col-md-6">

        <div class="card">

            <div class="card-header">

                <h5 class="card-title mb-0">Agregar Horario</h5>

            </div>

            <div class="card-body">

                <form action="<?php echo URLROOT; ?>/admin/horarioProfesor/<?php echo $datos['profesor']->id; ?>" method="post">

                    <div class="form-group">

                        <label for="dia_semana">Día de la semana:</label>

                        <select name="dia_semana" class="form-control" required>
                            <option value="1">Lunes</option>
                            <option value="2">Martes</option>
                            <option value="3">Miércoles</option>
                            <option value="4">Jueves</option>
                            <option value="5">Viernes</option>
                            <option value="6">Sábado</option>
                            <option value="7">Domingo</option>
                        </select>

                    </div>

                    <div class="form-group">
                        <label for="hora_inicio">Hora de inicio:</label>
                        <input type="time" name="hora_inicio" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="hora_fin">Hora de fin:</label>
                        <input type="time" name="hora_fin" class="form-control" required>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Agregar Horario">

                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">

        <div class="card">

            <div class="card-header">

                <h5 class="card-title mb-0">Horarios Actuales</h5>

            </div>

            <div class="card-body">

                <?php if(empty($datos['horarios'])) : ?>

                    <p class="text-muted">No hay horarios definidos</p>

                <?php else : ?>

                    <table class="table">

                        <thead>

                            <tr>
                                <th>Día</th>
                                <th>Horario</th>
                                <th>Acciones</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach($datos['horarios'] as $horario) : ?>

                                <tr>
                                    <td>

                                        <?php 
                                            $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                                            echo $dias[$horario->dia_semana - 1];
                                        ?>

                                    </td>

                                    <td><?php echo substr($horario->hora_inicio, 0, 5) . ' - ' . substr($horario->hora_fin, 0, 5); ?></td>
                                    <td>

                                        <form class="d-inline" action="<?php echo URLROOT; ?>/admin/eliminarHorario/<?php echo $horario->id; ?>" method="post">
                                            <input type="submit" value="Eliminar" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar este horario?');">
                                        </form>
                                        
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<a href="<?php echo URLROOT; ?>/admin/profesores" class="btn btn-secondary">Volver</a>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>