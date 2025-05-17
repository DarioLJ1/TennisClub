<?php require APPROOT . '/vistas/inc/header.php'; ?>

<h1 class= "mb-4"><?php echo $datos['profesor']->nombre . ' ' . $datos['profesor']->apellido; ?></h1>

<div class = "card">
    <div class = "card-body">
        <h5 class= "card-title">Detalles del profesor</h5>
        <p class = "card-text"><strong>Especialidad:</strong><?php echo $datos['profesor']->especialidad; ?></p>
        <p class="card-text"><strong>Email:</strong> <?php echo $datos['profesor']->email; ?></p>
        <p class="card-text"><strong>Teléfono:</strong> <?php echo $datos['profesor']->telefono; ?></p>
        <a href="<?php echo URLROOT; ?>/clases/reservar/<?php echo $datos['profesor']->id; ?>" class="btn btn-primary">Reservar Clase</a>
    </div>
</div>

<?php require APPROOT . '/vistas/inc/footer.php';?>