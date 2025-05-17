<?php require APPROOT . '/vistas/inc/header.php'; ?>

<div class="container">
    <h1 class="mb-4">Reservar Clase con <?php echo $datos['profesor']->nombre . ' ' . $datos['profesor']->apellido; ?></h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Información del Profesor</h5>
                    <p><strong>Especialidad:</strong> <?php echo $datos['profesor']->especialidad; ?></p>
                    <p><strong>Nivel:</strong> <?php echo $datos['profesor']->nivel; ?></p>
                    <p><strong>Precio por hora:</strong> <?php echo number_format($datos['profesor']->precio_hora, 2); ?>€</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Horarios Disponibles</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Día</th>
                                    <th>Horario</th>
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
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <form action="<?php echo URLROOT; ?>/clases/reservar/<?php echo $datos['id_profesor']; ?>" method="post">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Detalles de la Reserva</h5>
                    </div>
                    <div class="card-body">
                        <?php if(!empty($datos['disponibilidad_err'])) : ?>
                            <div class="alert alert-danger"><?php echo $datos['disponibilidad_err']; ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="fecha">Fecha: <sup>*</sup></label>
                            <input type="date" name="fecha" class="form-control <?php echo (!empty($datos['fecha_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $datos['fecha']; ?>" min="<?php echo date('Y-m-d'); ?>">
                            <span class="invalid-feedback"><?php echo $datos['fecha_err']; ?></span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hora_inicio">Hora de inicio: <sup>*</sup></label>
                                    <input type="time" name="hora_inicio" class="form-control <?php echo (!empty($datos['hora_inicio_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $datos['hora_inicio']; ?>">
                                    <span class="invalid-feedback"><?php echo $datos['hora_inicio_err']; ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hora_fin">Hora de fin: <sup>*</sup></label>
                                    <input type="time" name="hora_fin" class="form-control <?php echo (!empty($datos['hora_fin_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $datos['hora_fin']; ?>">
                                    <span class="invalid-feedback"><?php echo $datos['hora_fin_err']; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo_clase">Tipo de clase:</label>
                                    <select name="tipo_clase" id="tipo_clase" class="form-control">
                                        <option value="Individual" <?php echo ($datos['tipo_clase'] == 'Individual') ? 'selected' : ''; ?>>Individual</option>
                                        <option value="Grupo" <?php echo ($datos['tipo_clase'] == 'Grupo') ? 'selected' : ''; ?>>Grupo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="num_alumnos">Número de alumnos:</label>
                                    <input type="number" name="num_alumnos" id="num_alumnos" class="form-control" value="<?php echo $datos['num_alumnos']; ?>" min="1" max="4">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notas">Notas adicionales:</label>
                            <textarea name="notas" class="form-control" rows="3"><?php echo $datos['notas']; ?></textarea>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Reservar Clase">
                        <a href="<?php echo URLROOT; ?>/profesores" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Información Importante</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">✓ Las clases deben reservarse con al menos 24 horas de antelación</li>
                        <li class="mb-2">✓ La duración mínima de una clase es de 1 hora</li>
                        <li class="mb-2">✓ Las clases en grupo tienen un máximo de 4 alumnos</li>
                        <li class="mb-2">✓ El pago se realiza al momento de la reserva</li>
                        <li class="mb-2">✓ Cancelación gratuita hasta 24 horas antes</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoClaseSelect = document.getElementById('tipo_clase');
    const numAlumnosInput = document.getElementById('num_alumnos');

    function actualizarNumAlumnos() {
        if (tipoClaseSelect.value === 'Individual') {
            numAlumnosInput.value = '1';
            numAlumnosInput.disabled = true;
        } else {
            numAlumnosInput.disabled = false;
        }
    }

    tipoClaseSelect.addEventListener('change', actualizarNumAlumnos);
    actualizarNumAlumnos(); // Establecer estado inicial
});
</script>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>