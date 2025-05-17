<?php require APPROOT . '/vistas/inc/header.php'; ?>

<h1 class="mb-4">Pistas de Tenis</h1>

<?php flash('reserva_mensaje'); ?>

<div class="row">
    <?php foreach($datos['pistas'] as $pista) : ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $pista->nombre; ?></h5>
                    <p class="card-text">Tipo: <?php echo $pista->tipo; ?></p>
                    <p class="card-text">Estado: <?php echo $pista->estado; ?></p>
                    <a href="<?php echo URLROOT; ?>/pistas/reservar/<?php echo $pista->id; ?>" class="btn btn-primary">Reservar</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>








