<?php require APPROOT . '/vistas/inc/header.php'; ?>

<h1 class = "mb-4">Nuestros Profesores</h1>

<div class= "row">
    <?php foreach($datos['profesores']as $profesor) : ?>
        <div class = "col-md-4 mb-4">
            <div class = "card">
                <div class = "card-body">
                    <h5 class = "card-title"><?php echo $profesor->nombre . ' ' . $profesor->apellido; ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $profesor->especialidad; ?></h6>
                    <p class="card-text">Email: <?php echo $profesor->email; ?></p>
                    <a href="<?php echo URLROOT; ?>/profesores/detalle/<?php echo $profesor->id; ?>" class="btn btn-primary">Ver Detalles</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require APPROOT . '/vistas/inc/footer.php';?>


