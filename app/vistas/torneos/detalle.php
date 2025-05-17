<?php require APPROOT . '/vistas/inc/header.php'; ?>

<h1 class="mb-4"><?php echo $datos['torneo']->nombre; ?></h1>

<?php flash('torneo_mensaje'); ?>

<div class="card mb-4">

    <div class="card-body">
        <h5 class="card-title">Detalles del Torneo</h5>
        <p class="card-text"><strong>Fecha:</strong> <?php echo $datos['torneo']->fecha_inicio . ' - ' . $datos['torneo']->fecha_fin; ?></p>
        <p class="card-text"><strong>Descripción:</strong> <?php echo $datos['torneo']->descripcion; ?></p>
        <p class="card-text"><strong>Capacidad:</strong> <?php echo $datos['torneo']->capacidad; ?></p>
        <p class="card-text"><strong>Estado:</strong> <?php echo ucfirst($datos['torneo']->estado); ?></p>
        
        <?php if($datos['torneo']->estado == 'abierto') : ?>
            <form action="<?php echo URLROOT; ?>/torneos/inscribir/<?php echo $datos['torneo']->id; ?>" method="post">
                <input type="submit" class="btn btn-success" value="Inscribirse">
            </form>
        <?php endif; ?>

    </div>
    
</div>

<h2 class="mb-4">Participantes Inscritos</h2>

<ul class="list-group">
    <?php foreach($datos['inscripciones'] as $inscripcion) : ?>
        <li class="list-group-item">
            <?php echo $inscripcion->nombre; ?> 
            <span class="badge badge-primary"><?php echo ucfirst($inscripcion->estado); ?></span>
        </li>
    <?php endforeach; ?>
</ul>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>



