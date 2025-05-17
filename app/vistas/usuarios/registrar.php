<?php require APPROOT . '/vistas/inc/header.php'; ?>

  <div class="row">
    <div class="col-md-6 mx-auto">
      <div class="card card-body bg-light mt-5">

        <h2>Crear una cuenta</h2>
        <p>Por favor llene este formulario para registrarse con nosotros</p>

        <form action="<?php echo URLROOT; ?>/usuarios/registrar" method="post">

          <div class="form-group">
            <label for="nombre">Nombre: <sup>*</sup></label>
            <input type="text" name="nombre" class="form-control form-control-lg <?php echo (!empty($datos['nombre_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $datos['nombre']; ?>">
            <span class="invalid-feedback"><?php echo $datos['nombre_err']; ?></span>
          </div>

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

          <div class="form-group">
            <label for="confirmar_password">Confirmar Contraseña: <sup>*</sup></label>
            <input type="password" name="confirmar_password" class="form-control form-control-lg <?php echo (!empty($datos['confirmar_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $datos['confirmar_password']; ?>">
            <span class="invalid-feedback"><?php echo $datos['confirmar_password_err']; ?></span>
          </div>

          <div class="row">

            <div class="col">
              <input type="submit" value="Registrar" class="btn btn-success btn-block">
            </div>
            
            <div class="col">
              <a href="<?php echo URLROOT; ?>/usuarios/login" class="btn btn-light btn-block">¿Tiene una cuenta? Login</a>
            </div>

          </div>
        </form>
      </div>
    </div>
  </div>
<?php require APPROOT . '/vistas/inc/footer.php'; ?>