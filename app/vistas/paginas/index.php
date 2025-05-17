<?php require APPROOT . '/vistas/inc/header.php'; ?>

<div class="jumbotron jumbotron-flud text-center">
    
  <div class="container">
      <h1 class="display-3"><?php echo $datos['titulo']; ?></h1>
      <p class="lead"><?php echo $datos['descripcion']; ?></p>
      <?php if(isset($_SESSION['user_id'])) : ?>
          <p>Bienvenido, <?php echo $_SESSION['user_name']; ?>!</p>
      <?php endif; ?>
  </div>

</div>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>



