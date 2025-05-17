<?php require APPROOT . '/vistas/inc/header.php'; ?>

<div class="container mt-4">
    <h1>Generación de Informes</h1>
    
    <?php flash('informe_mensaje'); ?>
    
    <div class="row mt-4">
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informe de Reservas</h5>
                    <p class="card-text">Generar informes de reservas de pistas por período.</p>
                    <a href="<?php echo URLROOT; ?>/informes/reservas" class="btn btn-primary">Generar</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informe de Clases</h5>
                    <p class="card-text">Generar informes de clases particulares por período o profesor.</p>
                    <a href="<?php echo URLROOT; ?>/informes/clases" class="btn btn-primary">Generar</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informe de Torneos</h5>
                    <p class="card-text">Generar informes de torneos e inscripciones.</p>
                    <a href="<?php echo URLROOT; ?>/informes/torneos" class="btn btn-primary">Generar</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informe de Usuarios</h5>
                    <p class="card-text">Generar informes de usuarios registrados.</p>
                    <a href="<?php echo URLROOT; ?>/informes/usuarios" class="btn btn-primary">Generar</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="<?php echo URLROOT; ?>/admin" class="btn btn-secondary">Volver al Panel de Administración</a>
    </div>
</div>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>