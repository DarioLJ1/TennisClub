<?php require APPROOT . '/vistas/inc/header.php'; ?>

<div class="container">
    <h1 class="mb-4">Historial de Reservas</h1>

    <?php if (empty($datos['reservas'])) : ?>
        <div class="alert alert-info">
            No tienes reservas en tu historial.
        </div>
    <?php else : ?>
        <p>Mostrando <?php echo count($datos['reservas']); ?> de <?php echo $datos['totalReservas']; ?> reservas.</p>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Pista</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos['reservas'] as $reserva) : ?>
                        <tr>

                            <td><?php echo $reserva->nombre_pista; ?></td>
                            <td><?php echo $reserva->tipo_pista; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($reserva->fecha)); ?></td>
                            <td><?php echo date('H:i', strtotime($reserva->hora_inicio)) . ' - ' . date('H:i', strtotime($reserva->hora_fin)); ?></td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Controles de paginación -->
        <?php if ($datos['totalPaginas'] > 1) : ?>
            <nav aria-label="Navegación de páginas">
                <ul class="pagination justify-content-center">
                    <!-- Botón Anterior -->
                    <li class="page-item <?php echo ($datos['paginaActual'] <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo URLROOT; ?>/reservas/historial?pagina=<?php echo $datos['paginaActual'] - 1; ?>" aria-label="Anterior">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    
                    <!-- Números de página -->
                    <?php for ($i = 1; $i <= $datos['totalPaginas']; $i++) : ?>
                        <li class="page-item <?php echo ($i == $datos['paginaActual']) ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo URLROOT; ?>/reservas/historial?pagina=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <!-- Botón Siguiente -->
                    <li class="page-item <?php echo ($datos['paginaActual'] >= $datos['totalPaginas']) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo URLROOT; ?>/reservas/historial?pagina=<?php echo $datos['paginaActual'] + 1; ?>" aria-label="Siguiente">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require APPROOT . '/vistas/inc/footer.php'; ?>