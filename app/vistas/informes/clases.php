<?php require APPROOT . '/vistas/inc/header.php'; ?>

<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Informe de Clases Particulares</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo URLROOT; ?>/informes" class="btn btn-secondary">Volver a Informes</a>
        </div>
    </div>
    
    <?php flash('informe_mensaje'); ?>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Generar Informe de Clases Particulares</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo URLROOT; ?>/informes/clases" method="post">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="fecha_inicio">Fecha de Inicio:</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?php echo $datos['fecha_inicio']; ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="fecha_fin">Fecha de Fin:</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?php echo $datos['fecha_fin']; ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="id_profesor">Profesor:</label>
                        <select name="id_profesor" id="id_profesor" class="form-control">
                            <option value="">Todos</option>
                            <?php foreach($datos['profesores'] as $profesor): ?>
                                <option value="<?php echo $profesor->id; ?>"><?php echo $profesor->nombre; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="estado">Estado:</label>
                        <select name="estado" id="estado" class="form-control">
                            <option value="">Todos</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="confirmada">Confirmada</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="formato">Formato:</label>
                        <select name="formato" id="formato" class="form-control">
                            <option value="pdf">PDF</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Generar Informe</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>