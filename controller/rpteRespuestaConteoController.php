<?php

/**
 * rpteRespuestaConteoGEController.php Controller
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.24
 * @access public
 */

class rpteRespuestaConteoController Extends baseController {

    function index() {

        $unidad = new unidad();
        $this->registry->template->uni_id = $unidad->obtenerSelect();
        $this->registry->template->PATH_WEB = PATH_WEB;
        $this->registry->template->PATH_DOMAIN = PATH_DOMAIN;
        $this->registry->template->PATH_EVENT = "verRpte";
        $this->registry->template->GRID_SW = "true";
        $this->registry->template->PATH_J = "jquery-1.4.1";
        $this->menu = new menu();
        $liMenu = $this->menu->imprimirMenu("rpteRespuestaConteo", $_SESSION['USU_ID']);
        $this->registry->template->men_titulo = $liMenu;
        $this->registry->template->show('headerF');
        $this->registry->template->show('rpteRespuestaConteo.tpl');
        $this->registry->template->show('footer');
    }

    function verRpte() {
        $where = "";
        $uni_id = $_POST['uni_id'];
        $tiporeporte = $_POST['tiporeporte'];
        if ($uni_id){
            if ($uni_id!='Todos'){
                $where .= " WHERE usuario.uid = $uni_id";
            }
        }
        
        $usuario = new Tab_usuario();
        if ($tiporeporte == 1) {
            require_once ('tcpdf/config/lang/eng.php');
            require_once ('tcpdf/tcpdf.php');
            $this->usuario = new usuario ();
            // create new PDF document
            $pdf = new TCPDF('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->setFontSubsetting(FALSE);
            $pdf->SetAuthor($this->usuario->obtenerNombre($_SESSION['USU_ID']));
            $pdf->SetTitle('Reporte de Inventario');
            $pdf->SetSubject('Reporte de Inventario');
            $pdf->SetKeywords('DGGE, Sistema de Accidentes');
            // set default header data
            $pdf->SetHeaderData('logo.png', 20, 'MINISTERIO DE PLANIFICACION DEL DESARROLLO', 'DGGE');        
            // set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(5, 30, 10);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->setPrintFooter(false);
            //set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, 15);
            //set some language-dependent strings
            $pdf->setLanguageArray($l);
            $pdf->SetFont('helvetica', '', 9);
            // add a page
            $pdf->AddPage();
        } else if ($tiporeporte == 2) {
            header("Content-type: application/vnd.ms-excel; name='excel'");
            header("Content-Disposition: filename=conteo_encuesta.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
        } else if ($tiporeporte == 3) {
            // Other
        }


        
        $cadena .= '<table width="100%" border="0" >';
        $cadena .= '<tr><td colspan= "3" align="center">';
        $cadena .= '<span style="font-size: 30px;font-weight: bold;">';
        $cadena .= 'CONTEO ENCUESTA - GOBIERNO ELECTRONICO';
        $cadena .= '</span>';
        $cadena .= '</td></tr>';
        $cadena .= '<tr><td>Sobre un total de 27 preguntas en la encuesta</td></tr>';
        
        // Body - Header
        $cadena .= '<tr bgcolor="#CCCCCC">';
        $cadena .= '<td width="60%" align="center"><span style="font-family: helvetica; font-size: 11px;font-weight: bold;">Entidad/Usuario</span></td>';
        $cadena .= '<td width="20%" align="left"><span style="font-family: helvetica; font-size: 11px;font-weight: bold;">Nro. de respuestas</span></td>';
        $cadena .= '<td width="20%" align="left"><span style="font-family: helvetica; font-size: 11px;font-weight: bold;">% de avance</span></td>';
        $cadena .= '</tr>';        
        
        $sql = "SELECT
                usuario.uid,
                usuario.username
                FROM
                usuario
                $where
                ORDER BY  usuario.username ";        
        $answers = new answers();
        $rows = $answers->dbSelectBySQL($sql); //print $sql;       
        foreach ($rows as $row) {            
            $sql = "SELECT count(DISTINCT questioning.qid) as contador
                    FROM
                    groups
                    INNER JOIN questioning ON groups.gid = questioning.gid
                    INNER JOIN answers ON questioning.qid = answers.qid
                    INNER JOIN usuario ON usuario.uid = answers.uid
                    WHERE usuario.uid = '$row->uid'
                    AND questioning.estado = 1
                    ORDER BY  username, groups.gnum, 
                    questioning.qnum ";
            $result = $answers->dbSelectBySQL($sql); //print $sql;        
            $this->usuario = new usuario ();        

            $usernamea = "";
            $cont = 0;
            foreach ($result as $fila) {
                // Data
                $cadena .= '<tr bgcolor="FFFFFF">';
                $cadena .= '<td width="60%" align="center"><span style="font-family: helvetica; font-size: 11px;">' . $row->username . '</span></td>';
                $cadena .= '<td width="20%" align="left"><span style="font-family: helvetica; font-size: 11px;">' . $fila->contador . '</span></td>';
                $cadena .= '<td width="20%" align="left"><span style="font-family: helvetica; font-size: 11px;">' . ($fila->contador/27)*100 .  '</span></td>';
                $cadena .= '</tr>';
            }            
            
        }   
            
        $cadena .= '</table>';
        if ($tiporeporte == 1) {
            $pdf->writeHTML($cadena, true, false, false, false, '');
            //Close and output PDF document
            $pdf->Output('conteo_encuesta.pdf', 'I');
        } else if ($tiporeporte == 2) {
            echo $cadena;
        } else if ($tiporeporte == 3) {
            echo $cadena;
        }
        
    }        
}

?>
