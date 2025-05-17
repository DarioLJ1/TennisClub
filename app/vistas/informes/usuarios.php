<?php require APPROOT . '/vistas/inc/header.php'; ?>

<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Informe de Usuarios</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo URLROOT; ?>/informes" class="btn btn-secondary">Volver a Informes</a>
        </div>
    </div>
    
    <?php flash('informe_mensaje'); ?>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Generar Informe de Usuarios</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo URLROOT; ?>/informes/usuarios" method="post">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="rol">Rol:</label>
                        <select name="rol" id="rol" class="form-control">
                            <option value="">Todos</option>
                            <option value="admin">Administrador</option>
                            <option value="usuario">Usuario</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
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