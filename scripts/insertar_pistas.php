<?php
define('BASEPATH', realpath(dirname(__FILE__) . '/../'));

require_once BASEPATH . '/app/config/config.php';
require_once BASEPATH . '/app/config/base_datos.php';

$db = new Database;

$pistas = [
    ['nombre' => 'Pista Central', 'tipo' => 'Tierra batida', 'estado' => 'Disponible', 'imagen' => 'pista-tenis.png'],
    ['nombre' => 'Pista 2', 'tipo' => 'Césped', 'estado' => 'Disponible', 'imagen' => 'pista-tenis.png'],
    ['nombre' => 'Pista 3', 'tipo' => 'Dura', 'estado' => 'En mantenimiento', 'imagen' => 'pista-tenis.png']
];

foreach ($pistas as $pista) {
    try {
        $db->query('INSERT INTO pistas (nombre, tipo, estado, imagen) VALUES(:nombre, :tipo, :estado, :imagen)');
        $db->bind(':nombre', substr($pista['nombre'], 0, 100));  
        $db->bind(':tipo', substr($pista['tipo'], 0, 50));       
        $db->bind(':estado', substr($pista['estado'], 0, 50));   
        $db->bind(':imagen', substr($pista['imagen'], 0, 255));  
        $db->execute();
        echo "Pista insertada: " . $pista['nombre'] . "\n";
    } catch (PDOException $e) {
        echo "Error al insertar pista " . $pista['nombre'] . ": " . $e->getMessage() . "\n";
    }
}

echo "Proceso de inserción de pistas completado.";