<?php require APPROOT . '/vistas/inc/header.php'; ?>

<h1 class="mb-4">Estadísticas de Uso</h1>

<p>Período: <?php echo $datos['fecha_inicio']; ?> - <?php echo $datos['fecha_fin']; ?></p>

<?php if ($datos['id_pista'] == 'todas') : ?>

    <table class="table table-striped">

        <thead>
            <tr>
                <th>Pista</th>
                <th>Total Horas de Uso</th>
                <th>Total Reservas</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($datos['estadisticas'] as $estadistica) : ?>
                <tr>
                    <td><?php echo $estadistica->nombre; ?></td>
                    <td><?php echo $estadistica->total_horas; ?></td>
                    <td><?php echo $estadistica->total_reservas; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>

<?php else : ?>
    
    <h2><?php echo $datos['estadisticas']->nombre; ?></h2>
    <p>Total Horas de Uso: <?php echo $datos['estadisticas']->total_horas; ?></p>
    <p>Total Reservas: <?php echo $datos['estadisticas']->total_reservas; ?></p>
<?php endif; ?>

<a href="<?php echo URLROOT; ?>/estadisticas" class="btn btn-secondary">Volver</a>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>