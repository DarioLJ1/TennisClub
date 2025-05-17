<?php require APPROOT . '/vistas/inc/header.php'; ?>

  <div class="row">
    <div class="col-md-6 mx-auto">
      <div class="card card-body bg-light mt-5">
        <?php flash('register_success'); ?>
        <h2>Iniciar sesión</h2>
        <p>Por favor, ingrese sus credenciales para iniciar sesión</p>
        <form action="<?php echo URLROOT; ?>/usuarios/login" method="post">

          <div class="form-group">
            <label for="email">Email: <sup>*</sup></label>
            <input type="email" name="email" class="form-control form-control-lg <?php echo (!empty($datos['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $datos['email']; ?>">
            <span class="invalid-feedback"><?php echo $datos['email_err']; ?></span>
          </div>

          <div class="form-group">
            <label for="password">Contraseña: <sup>*</sup></label>
            <input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($datos['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $datos['password']; ?>">
            <span class="invalid-feedback"><?php echo $datos['password_err']; ?></span>
          </div>

          <div class="row">
            <div class="col">
              <input type="submit" value="Iniciar Sesión" class="btn btn-success btn-block">
            </div>

            <div class="col">
              <a href="<?php echo URLROOT; ?>/usuarios/registrar" class="btn btn-light btn-block">¿No tiene una cuenta? Registrarse</a>
            </div>

          </div>
        </form>
      </div>
    </div>
  </div>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>

