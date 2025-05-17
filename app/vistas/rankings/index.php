<?php require APPROOT . '/vistas/inc/header.php'; ?>
<div class="container mt-5">
    <h1><?php echo $datos['titulo']; ?></h1>
    <?php flash('rankings_success'); ?>
    <?php flash('rankings_error'); ?>

    <?php if (!$datos['estaInscrito'] && isset($_SESSION['user_id'])) : ?>
        <a href="<?php echo URLROOT; ?>/rankings/inscribir" class="btn btn-primary mb-3">Inscríbete al Ranking</a>
    <?php endif; ?>

    <?php if (empty($datos['ranking'])) : ?>
        <p>No hay jugadores inscritos en el ranking.</p>
    <?php else : ?>
        <table class="table table-bordered table-striped table-hover table-light text-center">
            <thead>
                <tr>
                    <th>Posición</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Puntuación</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($datos['ranking'] as $jugador) : ?>
                    <tr>
                        <td><?php echo $jugador->posicion; ?></td>
                        <td><?php echo htmlspecialchars($jugador->nombre); ?></td>
                        <td><?php echo htmlspecialchars($jugador->apellidos); ?></td>
                        <td><?php echo $jugador->puntuacion; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require APPROOT . '/vistas/inc/footer.php'; ?>