<?php require APPROOT . '/vistas/inc/header.php'; ?>

<h1 class="mb-4">Mis Inscripciones a Torneos</h1>

<?php flash('torneo_mensaje'); ?>

<div class="row">
    
    <?php foreach($datos['inscripciones'] as $inscripcion) : ?>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $inscripcion->nombre_torneo; ?></h5>
                    <p class="card-text">Fecha: <?php echo $inscripcion->fecha_inicio . ' - ' . $inscripcion->fecha_fin; ?></p>
                    <p class="card-text">Estado de inscripción: <?php echo ucfirst($inscripcion->estado); ?></p>
                    <a href="<?php echo URLROOT; ?>/torneos/detalle/<?php echo $inscripcion->id_torneo; ?>" class="btn btn-primary">Ver Detalles del Torneo</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

</div>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>

