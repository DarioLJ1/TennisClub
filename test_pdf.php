<?php
// Definir constantes necesarias
define('APPROOT', __DIR__ . '/app');

// Incluir TCPDF
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} else {
    require_once __DIR__ . '/app/librerias/TCPDF/tcpdf.php';
}

// Crear un PDF simple
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('HomeTennis');
$pdf->SetTitle('Test PDF');
$pdf->SetSubject('TCPDF Test');
$pdf->SetKeywords('TCPDF, PDF, test');
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Si puedes ver este mensaje, TCPDF está funcionando correctamente.', 0, 1);
$pdf->Output('test.pdf', 'I');