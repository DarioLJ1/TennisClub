<?php require APPROOT . '/vistas/inc/header.php'; ?>

<div class="container">
    <h1 class="mb-4">Mis Clases</h1>

    <?php flash('clase_mensaje'); ?>

    <?php if(empty($datos['clases'])) : ?>
        <div class="alert alert-info">
            <p class="mb-0">No tienes clases reservadas. ¿Por qué no reservas una clase con uno de nuestros profesores?</p>
            <a href="<?php echo URLROOT; ?>/profesores" class="btn btn-primary mt-3">Ver Profesores</a>
        </div>
    <?php else : ?>
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs mb-4" id="clasesTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="proximas-tab" data-toggle="tab" href="#proximas" role="tab">
                            Próximas Clases
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pasadas-tab" data-toggle="tab" href="#pasadas" role="tab">
                            Clases Pasadas
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="clasesTabContent">
                    <div class="tab-pane fade show active" id="proximas" role="tabpanel">
                        <?php 
                        $hayProximas = false;
                        foreach($datos['clases'] as $clase) : 
                            if(strtotime($clase->fecha) >= strtotime(date('Y-m-d'))) :
                                $hayProximas = true;
                        ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        Clase con <?php echo $clase->nombre_profesor . ' ' . $clase->apellido_profesor; ?>
                                    </h5>
                                    <p class="card-text">
                                        <strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($clase->fecha)); ?><br>
                                        <strong>Hora:</strong> <?php echo substr($clase->hora_inicio, 0, 5) . ' - ' . substr($clase->hora_fin, 0, 5); ?><br>
                                        <strong>Tipo:</strong> <?php echo $clase->tipo_clase; ?><br>
                                        <strong>Estado:</strong> 
                                        <span class="badge badge-<?php 
                                            echo $clase->estado == 'Confirmada' ? 'success' : 
                                                ($clase->estado == 'Pendiente' ? 'warning' : 
                                                ($clase->estado == 'Cancelada' ? 'danger' : 'info')); 
                                        ?>">
                                            <?php echo $clase->estado; ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        <?php 
                            endif;
                        endforeach; 
                        
                        if (!$hayProximas) :
                        ?>
                            <div class="alert alert-info">No tienes clases próximas programadas.</div>
                        <?php endif; ?>
                    </div>

                    <div class="tab-pane fade" id="pasadas" role="tabpanel">
                        <?php 
                        $hayPasadas = false;
                        foreach($datos['clases'] as $clase) : 
                            if(strtotime($clase->fecha) < strtotime(date('Y-m-d'))) :
                                $hayPasadas = true;
                        ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        Clase con <?php echo $clase->nombre_profesor . ' ' . $clase->apellido_profesor; ?>
                                    </h5>
                                    <p class="card-text">
                                        <strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($clase->fecha)); ?><br>
                                        <strong>Hora:</strong> <?php echo substr($clase->hora_inicio, 0, 5) . ' - ' . substr($clase->hora_fin, 0, 5); ?><br>
                                        <strong>Tipo:</strong> <?php echo $clase->tipo_clase; ?><br>
                                        <strong>Estado:</strong> 
                                        <span class="badge badge-<?php 
                                            echo $clase->estado == 'Completada' ? 'success' : 
                                                ($clase->estado == 'Cancelada' ? 'danger' : 'info'); 
                                        ?>">
                                            <?php echo $clase->estado; ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        <?php 
                            endif;
                        endforeach; 
                        
                        if (!$hayPasadas) :
                        ?>
                            <div class="alert alert-info">No tienes clases pasadas.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>