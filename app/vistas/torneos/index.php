<?php require APPROOT . '/vistas/inc/header.php'; ?>

<h1 class="mb-4">Torneos Disponibles</h1>

<?php flash('torneo_mensaje'); ?>

<div class="row">

    <?php foreach($datos['torneos'] as $torneo) : ?>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $torneo->nombre; ?></h5>
                    <p class="card-text">Fecha: <?php echo $torneo->fecha_inicio . ' - ' . $torneo->fecha_fin; ?></p>
                    <p class="card-text">Estado: <?php echo ucfirst($torneo->estado); ?></p>
                    <a href="<?php echo URLROOT; ?>/torneos/detalle/<?php echo $torneo->id; ?>" class="btn btn-primary">Ver Detalles</a>
                </div>
            </div>
        </div>

    <?php endforeach; ?>
    
</div>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>

