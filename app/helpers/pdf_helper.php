<?php
/**
 * Helper para la generación de documentos PDF
 * 
 * Este helper utiliza la biblioteca TCPDF para generar informes en formato PDF
 */

// Cargar TCPDF mediante Composer
if (file_exists(APPROOT . '/../vendor/autoload.php')) {
    require_once APPROOT . '/../vendor/autoload.php';
} else {
    die('Error: TCPDF no encontrado. Por favor, instala Composer y ejecuta "composer require tecnickcom/tcpdf".');
}

/**
 * Clase personalizada que extiende TCPDF para personalizar encabezados y pies de página
 */
class MYPDF extends TCPDF {
    protected $club_name = 'HomeTennis';
    protected $club_logo = null;
    protected $report_title = '';

    public function setReportTitle($title) {
        $this->report_title = $title;
    }

    public function Header() {
        if ($this->club_logo) {
            $this->Image($this->club_logo, 10, 10, 25, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        }
        $this->SetFont('helvetica', 'B', 16);
        $this->Cell(0, 15, $this->club_name, 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Line(10, 20, $this->getPageWidth() - 10, 20);
        $this->SetFont('helvetica', 'B', 12);
        $this->SetY(25);
        $this->Cell(0, 10, $this->report_title, 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Generado el: ' . date('d/m/Y H:i:s'), 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}

/**
 * Función para generar un informe de reservas
 */
function generarInformeReservas($reservas, $titulo = 'Informe de Reservas', $filename = 'informe_reservas.pdf', $download = false) {
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->setReportTitle($titulo);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('HomeTennis');
    $pdf->SetTitle($titulo);
    $pdf->SetSubject('Informe de Reservas');
    $pdf->SetKeywords('HomeTennis, Reservas, Informe, PDF');
    $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);

    $html = '<table border="1" cellpadding="5">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th width="5%"><b>ID</b></th>
                <th width="20%"><b>Usuario</b></th>
                <th width="20%"><b>Pista</b></th>
                <th width="15%"><b>Fecha</b></th>
                <th width="15%"><b>Hora</b></th>
                <th width="10%"><b>Estado</b></th>
                <th width="15%"><b>Precio</b></th>
            </tr>
        </thead>
        <tbody>';
    
    $total = 0;
    foreach ($reservas as $reserva) {
        $estado_color = '';
        switch ($reserva->estado) {
            case 'confirmada': $estado_color = 'color: green;'; break;
            case 'pendiente': $estado_color = 'color: orange;'; break;
            case 'cancelada': $estado_color = 'color: red;'; break;
            default: $estado_color = '';
        }
        $html .= '<tr>
            <td>' . $reserva->id . '</td>
            <td>' . $reserva->nombre_usuario . '</td>
            <td>' . $reserva->nombre_pista . '</td>
            <td>' . date('d/m/Y', strtotime($reserva->fecha)) . '</td>
            <td>' . substr($reserva->hora_inicio, 0, 5) . ' - ' . substr($reserva->hora_fin, 0, 5) . '</td>
            <td style="' . $estado_color . '">' . ucfirst($reserva->estado) . '</td>
            <td>' . number_format($reserva->precio, 2) . ' €</td>
        </tr>';
        if ($reserva->estado != 'cancelada') {
            $total += $reserva->precio;
        }
    }
    $html .= '</tbody>
        <tfoot>
            <tr style="background-color: #f2f2f2;">
                <td colspan="6" align="right"><b>Total:</b></td>
                <td><b>' . number_format($total, 2) . ' €</b></td>
            </tr>
        </tfoot>
    </table>';
    $html .= '<br><br>
    <table border="0" cellpadding="5">
        <tr>
            <td width="50%"><b>Resumen:</b></td>
            <td width="50%"></td>
        </tr>
        <tr>
            <td>Total de reservas: ' . count($reservas) . '</td>
            <td>Total facturado: ' . number_format($total, 2) . ' €</td>
        </tr>
    </table>';
    
    $pdf->writeHTML($html, true, false, true, false, '');
    if ($download) {
        $pdf->Output($filename, 'D');
    } else {
        $pdf->Output($filename, 'I');
    }
}

/**
 * Función para generar un informe de clases particulares
 */
function generarInformeClases($clases, $titulo = 'Informe de Clases Particulares', $filename = 'informe_clases.pdf', $download = false) {
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->setReportTitle($titulo);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('HomeTennis');
    $pdf->SetTitle($titulo);
    $pdf->SetSubject('Informe de Clases Particulares');
    $pdf->SetKeywords('HomeTennis, Clases, Informe, PDF');
    $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);

    $html = '<table border="1" cellpadding="5">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th width="5%"><b>ID</b></th>
                <th width="15%"><b>Usuario</b></th>
                <th width="15%"><b>Profesor</b></th>
                <th width="15%"><b>Pista</b></th>
                <th width="15%"><b>Fecha</b></th>
                <th width="15%"><b>Hora</b></th>
                <th width="10%"><b>Estado</b></th>
                <th width="10%"><b>Precio</b></th>
            </tr>
        </thead>
        <tbody>';
    
    $total = 0;
    foreach ($clases as $clase) {
        $estado_color = '';
        switch ($clase->estado) {
            case 'confirmada': $estado_color = 'color: green;'; break;
            case 'pendiente': $estado_color = 'color: orange;'; break;
            case 'cancelada': $estado_color = 'color: red;'; break;
            default: $estado_color = '';
        }
        $html .= '<tr>
            <td>' . $clase->id . '</td>
            <td>' . $clase->nombre_usuario . '</td>
            <td>' . $clase->nombre_profesor . '</td>
            <td>' . (isset($clase->nombre_pista) ? $clase->nombre_pista : 'N/A') . '</td>
            <td>' . date('d/m/Y', strtotime($clase->fecha)) . '</td>
            <td>' . substr($clase->hora_inicio, 0, 5) . ' - ' . substr($clase->hora_fin, 0, 5) . '</td>
            <td style="' . $estado_color . '">' . ucfirst($clase->estado) . '</td>
            <td>' . number_format($clase->precio, 2) . ' €</td>
        </tr>';
        if ($clase->estado != 'cancelada') {
            $total += $clase->precio;
        }
    }
    $html .= '</tbody>
        <tfoot>
            <tr style="background-color: #f2f2f2;">
                <td colspan="7" align="right"><b>Total:</b></td>
                <td><b>' . number_format($total, 2) . ' €</b></td>
            </tr>
        </tfoot>
    </table>';
    $html .= '<br><br>
    <table border="0" cellpadding="5">
        <tr>
            <td width="50%"><b>Resumen:</b></td>
            <td width="50%"></td>
        </tr>
        <tr>
            <td>Total de clases: ' . count($clases) . '</td>
            <td>Total facturado: ' . number_format($total, 2) . ' €</td>
        </tr>
    </table>';
    
    $pdf->writeHTML($html, true, false, true, false, '');
    if ($download) {
        $pdf->Output($filename, 'D');
    } else {
        $pdf->Output($filename, 'I');
    }
}

/**
 * Función para generar un informe de torneos
 */
function generarInformeTorneos($torneos, $inscripciones = [], $titulo = 'Informe de Torneos', $filename = 'informe_torneos.pdf', $download = false, $fecha_inicio = '', $fecha_fin = '', $estado = '') {
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    $titulo_completo = $titulo;
    if ($fecha_inicio && $fecha_fin) {
        $titulo_completo .= " (del " . date('d/m/Y', strtotime($fecha_inicio)) . " al " . date('d/m/Y', strtotime($fecha_fin)) . ")";
    }
    if ($estado) {
        $titulo_completo .= " - Estado: " . ucfirst($estado);
    }
    $pdf->setReportTitle($titulo_completo);
    
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('HomeTennis');
    $pdf->SetTitle($titulo);
    $pdf->SetSubject('Informe de Torneos');
    $pdf->SetKeywords('HomeTennis, Torneos, Informe, PDF');
    $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);

    $html = '<h3>Filtros aplicados:</h3>';
    $html .= '<p>Rango de fechas: ' . ($fecha_inicio ? date('d/m/Y', strtotime($fecha_inicio)) : 'N/A') . ' - ' . ($fecha_fin ? date('d/m/Y', strtotime($fecha_fin)) : 'N/A') . '</p>';
    if ($estado) {
        $html .= '<p>Estado: ' . ucfirst($estado) . '</p>';
    }
    $html .= '<br><table border="1" cellpadding="5">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th width="5%"><b>ID</b></th>
                <th width="25%"><b>Nombre</b></th>
                <th width="15%"><b>Fechas</b></th>
                <th width="10%"><b>Capacidad</b></th>
                <th width="10%"><b>Inscritos</b></th>
                <th width="10%"><b>Estado</b></th>
                <th width="10%"><b>Tipo</b></th>
                <th width="15%"><b>Ingresos</b></th>
            </tr>
        </thead>
        <tbody>';
    
    $total_ingresos = 0;
    $total_inscritos = 0;
    foreach ($torneos as $torneo) {
        $estado_color = '';
        switch ($torneo->estado) {
            case 'abierto': $estado_color = 'color: green;'; break;
            case 'programado': $estado_color = 'color: blue;'; break;
            case 'finalizado': $estado_color = 'color: gray;'; break;
            default: $estado_color = '';
        }
        $inscritos_confirmados = 0;
        $ingresos_torneo = 0;
        if (isset($inscripciones[$torneo->id])) {
            foreach ($inscripciones[$torneo->id] as $inscripcion) {
                if ($inscripcion->estado == 'confirmada') {
                    $inscritos_confirmados++;
                    $ingresos_torneo += $torneo->precio_inscripcion;
                }
            }
        }
        $total_inscritos += $inscritos_confirmados;
        $total_ingresos += $ingresos_torneo;
        
        $html .= '<tr>
            <td>' . $torneo->id . '</td>
            <td>' . $torneo->nombre . '</td>
            <td>' . date('d/m/Y', strtotime($torneo->fecha_inicio)) . ' - ' . date('d/m/Y', strtotime($torneo->fecha_fin)) . '</td>
            <td>' . $torneo->capacidad . '</td>
            <td>' . $inscritos_confirmados . '</td>
            <td style="' . $estado_color . '">' . ucfirst($torneo->estado) . '</td>
            <td>' . $torneo->tipo . '</td>
            <td>' . number_format($ingresos_torneo, 2) . ' €</td>
        </tr>';
    }
    $html .= '</tbody>
        <tfoot>
            <tr style="background-color: #f2f2f2;">
                <td colspan="4" align="right"><b>Totales:</b></td>
                <td><b>' . $total_inscritos . '</b></td>
                <td colspan="2"></td>
                <td><b>' . number_format($total_ingresos, 2) . ' €</b></td>
            </tr>
        </tfoot>
    </table>';
    $html .= '<br><br>
    <table border="0" cellpadding="5">
        <tr>
            <td width="50%"><b>Resumen:</b></td>
            <td width="50%"></td>
        </tr>
        <tr>
            <td>Total de torneos: ' . count($torneos) . '</td>
            <td>Total de inscritos: ' . $total_inscritos . '</td>
        </tr>
        <tr>
            <td>Total de ingresos: ' . number_format($total_ingresos, 2) . ' €</td>
            <td></td>
        </tr>
    </table>';
    
    $pdf->writeHTML($html, true, false, true, false, '');
    if ($download) {
        $pdf->Output($filename, 'D');
    } else {
        $pdf->Output($filename, 'I');
    }
}

/**
 * Función para generar un informe de usuarios
 */
function generarInformeUsuarios($usuarios, $titulo = 'Informe de Usuarios', $filename = 'informe_usuarios.pdf', $download = false, $rol = '') {
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    $titulo_completo = $titulo;
    if ($rol) {
        $titulo_completo .= " - Rol: " . ucfirst($rol);
    }
    $pdf->setReportTitle($titulo_completo);
    
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('HomeTennis');
    $pdf->SetTitle($titulo);
    $pdf->SetSubject('Informe de Usuarios');
    $pdf->SetKeywords('HomeTennis, Usuarios, Informe, PDF');
    $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);

    $html = '';
    if ($rol) {
        $html .= '<h3>Filtro aplicado:</h3>';
        $html .= '<p>Rol: ' . ucfirst($rol) . '</p><br>';
    }
    $html .= '<table border="1" cellpadding="5">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th width="5%"><b>ID</b></th>
                <th width="20%"><b>Nombre</b></th>
                <th width="25%"><b>Email</b></th>
                <th width="15%"><b>Fecha Registro</b></th>
                <th width="10%"><b>Rol</b></th>
                <th width="25%"><b>Actividad</b></th>
            </tr>
        </thead>
        <tbody>';
    
    foreach ($usuarios as $usuario) {
        $rol_usuario = isset($usuario->rol) ? $usuario->rol : (isset($usuario->is_admin) && $usuario->is_admin ? 'Administrador' : 'Usuario');
        $fecha_registro = isset($usuario->fecha_registro) && $usuario->fecha_registro ? date('d/m/Y', strtotime($usuario->fecha_registro)) : 'N/A';
        $ultima_actividad = isset($usuario->ultima_actividad) && $usuario->ultima_actividad ? date('d/m/Y H:i', strtotime($usuario->ultima_actividad)) : 'N/A';
        
        $html .= '<tr>
            <td>' . $usuario->id . '</td>
            <td>' . $usuario->nombre . '</td>
            <td>' . $usuario->email . '</td>
            <td>' . $fecha_registro . '</td>
            <td>' . ucfirst($rol_usuario) . '</td>
            <td>' . $ultima_actividad . '</td>
        </tr>';
    }
    $html .= '</tbody>
    </table>';
    $html .= '<br><br>
    <table border="0" cellpadding="5">
        <tr>
            <td width="50%"><b>Resumen:</b></td>
            <td width="50%"></td>
        </tr>
        <tr>
            <td>Total de usuarios: ' . count($usuarios) . '</td>
            <td></td>
        </tr>
    </table>';
    
    $pdf->writeHTML($html, true, false, true, false, '');
    if ($download) {
        $pdf->Output($filename, 'D');
    } else {
        $pdf->Output($filename, 'I');
    }
}