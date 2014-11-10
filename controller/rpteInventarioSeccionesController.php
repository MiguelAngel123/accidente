<?php

/**
 * rpteInventarioSeccionesController.php Controller
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.24
 * @access public
 */

class rpteInventarioSeccionesController Extends baseController {

    function index() {

        $encuesta = new encuesta();
        $this->registry->template->optSerie = $encuesta->obtenerSelectTodas();
        $encuesta = new encuesta();
        $this->registry->template->seccion = $encuesta->obtenerSeccionInventario();
        $this->registry->template->PATH_WEB = PATH_WEB;
        $this->registry->template->PATH_DOMAIN = PATH_DOMAIN;
        $this->registry->template->PATH_EVENT = "verRpte";
        $this->registry->template->GRID_SW = "true";
        $this->registry->template->PATH_J = "jquery-1.4.1";
        $this->menu = new menu();
        $liMenu = $this->menu->imprimirMenu("rpteencuestas", $_SESSION['USU_ID']);
        $this->registry->template->men_titulo = $liMenu;
        $this->registry->template->show('headerF');
        $this->registry->template->show('tab_rpteInventarioSeccion.tpl');
        $this->registry->template->show('footer');
    }

    function verRpte() {
        $where = "";
        $filtro_seccion = $_POST['filtro_seccion'];
        $tiporeporte = $_POST['tiporeporte'];
        
        
        $nrocajas = $_POST['nro_cajas'];
        $usuario = new Tab_usuario();
        $where .= " AND tab_unidad.uni_id = $filtro_seccion";

        if ($tiporeporte == 1) {
            require_once ('tcpdf/config/lang/eng.php');
            require_once ('tcpdf/tcpdf.php');
            $this->usuario = new usuario ();
            // create new PDF document
            $pdf = new TCPDF('L', PDF_UNIT, 'LETTER', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->setFontSubsetting(FALSE);
            $pdf->SetAuthor($this->usuario->obtenerNombre($_SESSION['USU_ID']));
            $pdf->SetTitle('Reporte de Inventario');
            $pdf->SetSubject('Reporte de Inventario');
            $pdf->SetKeywords('DGGE, SAD');
            // set default header data
            $pdf->SetHeaderData('logo2.png', 20, 'ABC', "");
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

            $pdf->Image(PATH_ROOT . '/web/img/iso.png', '255', '8', 15, 15, 'PNG', '', 'T', false, 300, '', false, false, 1, false, false, false);
        } else if ($tiporeporte == 2) {
            header("Content-type: application/vnd.ms-excel; name='excel'");
            header("Content-Disposition: filename=inventario_sec.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
        } else if ($tiporeporte == 3) {
            
        }


        
        // Count data
        $sql = "SELECT
                e.exp_id,
                (SELECT 
                count(tab_archivo.fil_nroejem) as cont
                FROM
                tab_respuesta
                INNER JOIN tab_exparchivo ON tab_respuesta.exp_id = tab_exparchivo.exp_id
                INNER JOIN tab_archivo ON tab_archivo.fil_id = tab_exparchivo.fil_id
                WHERE tab_respuesta.exp_id = e.exp_id) AS contador,
                tab_fondo.fon_cod,
                tab_unidad.uni_descripcion,
                tab_unidad.uni_cod,
                tab_unidad.uni_id,
                tab_tipocorr.tco_codigo,
                tab_encuesta.ser_id,
                tab_encuesta.ser_par,
                tab_encuesta.ser_codigo,
                e.exp_codigo,
                tab_expisadg.exp_titulo,
                tab_expisadg.exp_anioi,
                tab_expisadg.exp_aniof,
                tab_expisadg.exp_fecha_exi,
                tab_expisadg.exp_id,
                tab_encuesta.ser_id,
                tab_unidad.uni_par,
                tab_unidad.uni_id,
                tab_fondo.fon_descripcion,
                tab_encuesta.ser_categoria
                FROM
                tab_respuesta e
                INNER JOIN tab_encuesta ON e.ser_id = tab_encuesta.ser_id
                INNER JOIN tab_expisadg ON e.exp_id = tab_expisadg.exp_id
                INNER JOIN tab_unidad ON tab_unidad.uni_id = tab_encuesta.uni_id
                INNER JOIN tab_fondo ON tab_fondo.fon_id = tab_unidad.fon_id
                INNER JOIN tab_tipocorr ON tab_tipocorr.tco_id = tab_encuesta.tco_id
                WHERE
                tab_fondo.fon_estado = 1 AND
                tab_unidad.uni_estado = 1 AND
                tab_encuesta.ser_estado = 1 AND
                tab_tipocorr.tco_estado = 1 AND
                e.exp_estado = 1 AND
                tab_expisadg.exp_estado = 1 AND
                tab_unidad.uni_id = '$filtro_seccion'
                ORDER BY
                e.exp_id::int ASC";

        $encuesta = new tab_respuesta();
        $result = $encuesta->dbselectBySQL($sql);

        $piezas = 0;
        if (count($result) > 0) {
            foreach ($result as $row) {
                if ($row->contador == 0) {
                    $piezas = $piezas + 1;
                } else {
                    $piezas = $piezas + $row->contador;
                }
            }
        }



        
        

        // Header data
        $sqlh = "SELECT
                e.exp_id,
                (SELECT fon_descripcion from tab_fondo WHERE fon_id=f.fon_par) as fon_par,
                f.fon_descripcion,                
                f.fon_cod,
                (SELECT uni_descripcion from tab_unidad WHERE uni_id=u.uni_par) as uni_padre,
                u.uni_descripcion,
                u.uni_cod,
                u.uni_id,
                u.uni_par,
                tab_tipocorr.tco_codigo,
                tab_encuesta.ser_id,
                tab_encuesta.ser_par,
                tab_encuesta.ser_codigo,
                e.exp_codigo,
                tab_expisadg.exp_titulo,
                tab_expisadg.exp_anioi,
                tab_expisadg.exp_aniof,
                tab_expisadg.exp_fecha_exi,
                tab_expisadg.exp_id,
                tab_encuesta.ser_id,
                u.uni_par,
                u.uni_id,
                tab_encuesta.ser_categoria
                FROM
                tab_respuesta e
                INNER JOIN tab_encuesta ON e.ser_id = tab_encuesta.ser_id
                INNER JOIN tab_expisadg ON e.exp_id = tab_expisadg.exp_id
                INNER JOIN tab_unidad u ON u.uni_id = tab_encuesta.uni_id
                INNER JOIN tab_fondo f ON f.fon_id = u.fon_id
                INNER JOIN tab_tipocorr ON tab_tipocorr.tco_id = tab_encuesta.tco_id
                WHERE
                f.fon_estado = 1 AND
                u.uni_estado = 1 AND
                tab_encuesta.ser_estado = 1 AND
                tab_tipocorr.tco_estado = 1 AND
                e.exp_estado = 1 AND
                tab_expisadg.exp_estado = 1 AND
                u.uni_id = '$filtro_seccion'
                ORDER BY
                e.exp_id ASC";

        $exp_titulo = "";
        $encuestah = new tab_respuesta();
        $resulth = $encuestah->dbselectBySQL($sqlh);
        $cadena = "";
        if (count($resulth) > 0) {
            
            // Header
            $cadena .= '<table width="100%" cellpadding="2" border="1" style="border-collapse: collapse; "  >';
            $cadena .= '<tr><td align="center" colspan="12" >';
            $cadena .= '<span style="font-family: helvetica; font-size: 20px;font-weight: bold;text-decoration: underline;">';
            $cadena .= 'FORMULARIO DE INVENTARIO POR SECCIONES';
            $cadena .= '</span>';
            $cadena .= '</td></tr>';
            
            $cadena .= '<tr>';
            $cadena .= '<td width="5%" colspan="2" bgcolor="#CCCCCC"><span style="font-family: helvetica; font-size: 16px;font-weight: bold; text-align:justify;"> FONDO:</span></td>';
            $cadena .= '<td width="50%" colspan="4"><span style="font-family: helvetica; font-size: 16px; text-align:justify;">' . $resulth[0]->fon_par . '</span></td>';
            $cadena .= '<td width="5%" colspan="3" bgcolor="#CCCCCC"><span style="font-family: helvetica; font-size: 16px;font-weight: bold; text-align:justify;">INSTRUMENTO DE CONSULTA:</span></td>';
            $cadena .= '<td width="40%" colspan="3"><span style="font-family: helvetica; font-size: 16px; text-align:justify;">INVENTARIO DE DOCUMENTOS POR SECCIONES</span></td>';
            $cadena .= '</tr>';

            $cadena .= '<tr>';
            $cadena .= '<td width="5%" colspan="2" bgcolor="#CCCCCC"><span style="font-family: helvetica; font-size: 16px;font-weight: bold; text-align:justify;">SUB-FONDO:</span></td>';
            $cadena .= '<td width="50%" colspan="4"><span style="font-family: helvetica; font-size: 16px; text-align:justify;">' . $resulth[0]->fon_descripcion . '</span></td>';
            $cadena .= '<td width="5%" colspan="3" bgcolor="#CCCCCC"><span style="font-family: helvetica; font-size: 16px;font-weight: bold; text-align:justify;">TOTAL DE CAJAS:</span></td>';
            $cadena .= '<td width="40%" colspan="3"><span style="font-family: helvetica; font-size: 16px; text-align:justify;">' . $nrocajas . '</span></td>';
            $cadena .= '</tr>';

            $unidad = new unidad();
            $cadena .= '<tr>';
            $cadena .= '<td width="5%" colspan="2" bgcolor="#CCCCCC"><span style="font-family: helvetica; font-size: 16px;font-weight: bold; text-align:justify;">SECCI&Oacute;N:</span></td>';
            $cadena .= '<td width="50%" colspan="4"><span style="font-family: helvetica; font-size:16px;">' . $unidad->obtenerPadre($resulth[0]->uni_par, $resulth[0]->uni_descripcion) . '</span></td>';
            $cadena .= '<td width="5%" colspan="3" bgcolor="#CCCCCC"><span style="font-size: 16px;font-weight: bold; text-align:justify;">TOTAL DE PIEZAS:</span></td>';
            $cadena .= '<td width="40%" colspan="3"><span style="font-size: 16px;">' . $piezas . '</span></td>';
            $cadena .= '</tr>';

            $cadena .= '<tr>';
            $cadena .= '<td width="5%" colspan="2" bgcolor="#CCCCCC"><span style="font-size: 16px;font-weight: bold; text-align:justify;">SUB SECCI&Oacute;N:</span></td>';
            $cadena .= '<td width="50%" colspan="4"><span style="font-size: 16px;">' . $unidad->obtenerPadres($resulth[0]->uni_par, $resulth[0]->uni_descripcion) .  '</span></td>';
            $cadena .= '<td width="5%" colspan="3" bgcolor="#CCCCCC"><span style="font-size: 16px;font-weight: bold; text-align:justify;"></span></td>';
            $cadena .= '<td width="40%" colspan="3"><span style="font-size: 16px;"></span></td>';
            $cadena .= '</tr>';

            
            
            
            
            // Body - Header
            $cadena .= '<tr bgcolor="#CCCCCC">';
            $cadena .= '<td width="95%" align="center" colspan="11"><span style="font-family: helvetica; font-size: 11px;font-weight: bold;">&Aacute;REA DE IDENTIFICACI&Oacute;N</span></td>';
            $cadena .= '<td width="5%" align="left"><span style="font-family: helvetica; font-size: 11px;font-weight: bold;">&Aacute;REA DE NOTAS</span></td>';
            $cadena .= '</tr>';

            $cadena .= '<tr bgcolor="#CCCCCC">';
            $cadena .= '<td width="5%"  align="left"><span style="font-family: helvetica; font-size: 11px ;font-weight: bold;">Archivo</span></td>';
            $cadena .= '<td width="5%"  align="left"><span style="font-family: helvetica; font-size: 11px ;font-weight: bold;">C&oacute;digo</span></td>';
            $cadena .= '<td width="60%" align="center"><span style="font-family: helvetica; font-size: 11px ;font-weight: bold;">Titulo/Subtitulo</span></td>';
            $cadena .= '<td width="5%"  align="center"><span style="font-family: helvetica; font-size: 11px ;font-weight: bold;">T/V</span></td>';
            $cadena .= '<td width="5%"  align="center"><span style="font-family: helvetica; font-size: 11px ;font-weight: bold;">Fechas Extremas</span></td>';
            $cadena .= '<td width="3%"  align="center"><span style="font-family: helvetica; font-size: 11px ;font-weight: bold;">Caja</span></td>';
            $cadena .= '<td width="3%"  align="center"><span style="font-family: helvetica; font-size: 11px ;font-weight: bold;">Sala</span></td>';
            $cadena .= '<td width="3%"  align="center"><span style="font-family: helvetica; font-size: 9px ;font-weight: bold;">Estante</span></td>';
            $cadena .= '<td width="3%"  align="center"><span style="font-family: helvetica; font-size: 9px ;font-weight: bold;">Cuerpo</span></td>';
            $cadena .= '<td width="3%"  align="center"><span style="font-family: helvetica; font-size: 9px ;font-weight: bold;">Balda</span></td>';
            $cadena .= '<td width="5%"  align="center"><span style="font-family: helvetica; font-size: 9px ;font-weight: bold;">Soporte Fisico</span></td>';
            $cadena .= '<td width="5%" align="left"><span style="font-family: helvetica; font-size: 9px ;font-weight: bold; height: auto; ">Observaciones</span></td>';
            $cadena .= '</tr>';

            
            
            
            // Body - Data
            $sql = "SELECT
            tab_unidad.uni_id,
            tab_unidad.uni_descripcion,
            tab_encuesta.ser_id,
            tab_encuesta.ser_categoria
            FROM
            tab_unidad
            INNER JOIN tab_encuesta ON tab_unidad.uni_id = tab_encuesta.uni_id
            WHERE tab_unidad.uni_id = $filtro_seccion
            ORDER BY
            tab_encuesta.ser_orden, ser_codigo ";

            $encuestah = new tab_respuesta();
            $result = $encuestah->dbselectBySQL($sql);
            foreach ($result as $res) {
                // Serie
                // encuestas sin documentos
                // Search exps
                $select = "SELECT
                    tab_respuesta.exp_id,
                    (SELECT fon_codigo from tab_fondo WHERE fon_id=f.fon_par) AS fon_codigo,
                    tab_unidad.uni_descripcion,
                    tab_encuesta.ser_categoria,
                    tab_expisadg.exp_titulo,
                    tab_expisadg.exp_mesi,
                    tab_expisadg.exp_anioi,
                    tab_expisadg.exp_mesf,
                    tab_expisadg.exp_aniof,
                    f.fon_cod,
                    tab_unidad.uni_cod,
                    tab_tipocorr.tco_codigo,
                    tab_encuesta.ser_codigo,
                    tab_respuesta.exp_codigo,
                    tab_respuesta.exp_tomovol,
                    tab_respuesta.sof_id,
                    tab_respuesta.exp_nrocaj,
                    tab_respuesta.exp_sala,
                    tab_respuesta.exp_estante,
                    tab_respuesta.exp_cuerpo,
                    tab_respuesta.exp_balda,
                    tab_respuesta.exp_obs
                    FROM
                    tab_fondo as f
                    INNER JOIN tab_unidad ON f.fon_id = tab_unidad.fon_id
                    INNER JOIN tab_encuesta ON tab_unidad.uni_id = tab_encuesta.uni_id
                    INNER JOIN tab_tipocorr ON tab_tipocorr.tco_id = tab_encuesta.tco_id
                    INNER JOIN tab_respuesta ON tab_encuesta.ser_id = tab_respuesta.ser_id
                    INNER JOIN tab_expisadg ON tab_respuesta.exp_id = tab_expisadg.exp_id
                    INNER JOIN tab_encusuario ON tab_respuesta.exp_id = tab_encusuario.exp_id
                    WHERE
                    f.fon_estado = 1 AND
                    tab_unidad.uni_estado = 1 AND
                    tab_tipocorr.tco_estado = 1 AND
                    tab_encuesta.ser_estado = 1 AND
                    tab_respuesta.exp_estado = 1 AND
                    tab_expisadg.exp_estado = 1 AND 
                    tab_encusuario.eus_estado = 1 AND 
                    tab_encuesta.ser_id = '$res->ser_id'
                    ORDER BY tab_unidad.uni_cod, 
                    tab_encuesta.ser_id, 
                    tab_respuesta.exp_codigo::int ";

                $ser_categoria = "";
                $exp_titulo = "";
                $sopfisico = new sopfisico ();
                $rows2 = $encuestah->dbSelectBySQL($select);
                if (count($rows2) > 0) {

                    // encuestas                    
                    // Data body
                    foreach ($rows2 as $row) {
                        if ($row->ser_categoria != $ser_categoria) {

                            // Validate 
                            // Aqui
                            // encuestas
                            // Search docs
                            $select = "SELECT
                                     tab_respuesta.exp_id,
                                     (SELECT fon_codigo from tab_fondo WHERE fon_id=f.fon_par) AS fon_codigo,
                                     tab_unidad.uni_descripcion,
                                     tab_encuesta.ser_categoria,
                                     tab_expisadg.exp_titulo,
                                     tab_expisadg.exp_mesi,
                                     tab_expisadg.exp_anioi,
                                     tab_expisadg.exp_mesf,
                                     tab_expisadg.exp_aniof,
                                     tab_expisadg.exp_aniof,                    
                                     f.fon_cod,
                                     tab_unidad.uni_cod,
                                     tab_tipocorr.tco_codigo,
                                     tab_encuesta.ser_codigo,
                                     tab_respuesta.exp_codigo,
                                     tab_respuesta.exp_tomovol,
                                     tab_respuesta.sof_id,
                                     tab_respuesta.exp_nrocaj,
                                     tab_respuesta.exp_sala,
                                     tab_respuesta.exp_estante,
                                     tab_respuesta.exp_cuerpo,
                                     tab_respuesta.exp_balda
                                     FROM
                                     tab_fondo as f
                                     INNER JOIN tab_unidad ON f.fon_id = tab_unidad.fon_id
                                     INNER JOIN tab_encuesta ON tab_unidad.uni_id = tab_encuesta.uni_id
                                     INNER JOIN tab_tipocorr ON tab_tipocorr.tco_id = tab_encuesta.tco_id
                                     INNER JOIN tab_respuesta ON tab_encuesta.ser_id = tab_respuesta.ser_id
                                     INNER JOIN tab_expisadg ON tab_respuesta.exp_id = tab_expisadg.exp_id
                                     INNER JOIN tab_encusuario ON tab_respuesta.exp_id = tab_encusuario.exp_id
                                     WHERE
                                     f.fon_estado = 1 AND
                                     tab_unidad.uni_estado = 1 AND
                                     tab_tipocorr.tco_estado = 1 AND
                                     tab_encuesta.ser_estado = 1 AND
                                     tab_respuesta.exp_estado = 1 AND
                                     tab_expisadg.exp_estado = 1 AND 
                                     tab_encusuario.eus_estado = 1 AND 
                                     tab_respuesta.exp_id = '$row->exp_id'
                                     ORDER BY tab_unidad.uni_id, 
                                     tab_encuesta.ser_id, 
                                     tab_respuesta.exp_codigo::int ";


                            $sopfisico = new sopfisico ();
                            $rows4 = $encuestah->dbSelectBySQL($select);
                            if (count($rows4) > 0) {
                                // SERIE
                                $cadena .= '<tr bgcolor="#969696">';
                                $cadena .= '<td width="5%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;"></span></td>';
                                $cadena .= '<td width="5%" align="left"><span style="font-family: helvetica; font-size: 11px ;font-weight: bold; text-align:left;">' . $row->fon_cod . DELIMITER . $row->uni_cod . DELIMITER . $row->tco_codigo . DELIMITER . $row->ser_codigo . DELIMITER . '</span></td>';
                                $cadena .= '<td width="60%"><span style="font-family: helvetica; font-size: 11px; text-align:left;">' . $row->ser_categoria . '</span></td>';
                                $cadena .= '<td width="5%"><span style="font-family: helvetica; font-size: 11px; text-align:center;"></span></td>';
                                $cadena .= '<td width="5%"><span style="font-family: helvetica; font-size: 11px; text-align:center;"></span></td>';
                                $cadena .= '<td width="3%"><span style="font-family: helvetica; font-size: 11px; text-align:center;"></span></td>';
                                $cadena .= '<td width="3%"><span style="font-family: helvetica; font-size: 11px; text-align:center;"></span></td>';
                                $cadena .= '<td width="3%"><span style="font-family: helvetica; font-size: 11px; text-align:center;"></span></td>';
                                $cadena .= '<td width="3%"><span style="font-family: helvetica; font-size: 11px; text-align:center;"></span></td>';
                                $cadena .= '<td width="3%"><span style="font-family: helvetica; font-size: 11px; text-align:center;"></span></td>';
                                $cadena .= '<td width="5%"><span style="font-family: helvetica; font-size: 11px; text-align:center;"></span></td>';
                                $cadena .= '<td width="5%" height="auto"><span style="font-family: helvetica; font-size: 7px; text-align:left; "></span></td>';
                                $cadena .= '</tr>';
                                $ser_categoria = $row->ser_categoria;
                            }
                        }


                        // Aqui
                        // Documentos
                        // Search docs
                        $select = "SELECT
                                 tab_respuesta.exp_id,
                                 (SELECT fon_codigo from tab_fondo WHERE fon_id=f.fon_par) AS fon_codigo,
                                 tab_unidad.uni_descripcion,
                                 tab_encuesta.ser_categoria,
                                 tab_expisadg.exp_titulo,
                                 tab_expisadg.exp_mesi,
                                 tab_expisadg.exp_anioi,
                                 tab_expisadg.exp_mesf,
                                 tab_expisadg.exp_aniof,
                                 tab_expisadg.exp_aniof,                    
                                 f.fon_cod,
                                 tab_unidad.uni_cod,
                                 tab_tipocorr.tco_codigo,
                                 tab_encuesta.ser_codigo,
                                 tab_respuesta.exp_codigo,
                                 tab_respuesta.exp_tomovol,
                                 tab_respuesta.sof_id,
                                 tab_respuesta.exp_nrocaj,
                                 tab_respuesta.exp_sala,
                                 tab_respuesta.exp_estante,
                                 tab_respuesta.exp_cuerpo,
                                 tab_respuesta.exp_balda,
                                 tab_encusuario.usu_id
                                 FROM
                                 tab_fondo as f
                                 INNER JOIN tab_unidad ON f.fon_id = tab_unidad.fon_id
                                 INNER JOIN tab_encuesta ON tab_unidad.uni_id = tab_encuesta.uni_id
                                 INNER JOIN tab_tipocorr ON tab_tipocorr.tco_id = tab_encuesta.tco_id
                                 INNER JOIN tab_respuesta ON tab_encuesta.ser_id = tab_respuesta.ser_id
                                 INNER JOIN tab_expisadg ON tab_respuesta.exp_id = tab_expisadg.exp_id
                                 INNER JOIN tab_encusuario ON tab_respuesta.exp_id = tab_encusuario.exp_id
                                 WHERE
                                 f.fon_estado = 1 AND
                                 tab_unidad.uni_estado = 1 AND
                                 tab_tipocorr.tco_estado = 1 AND
                                 tab_encuesta.ser_estado = 1 AND
                                 tab_respuesta.exp_estado = 1 AND
                                 tab_expisadg.exp_estado = 1 AND 
                                 tab_encusuario.eus_estado = 1 AND 
                                 tab_respuesta.exp_id = '$row->exp_id'
                                 ORDER BY tab_unidad.uni_id, 
                                 tab_encuesta.ser_id, 
                                 tab_respuesta.exp_codigo::int ";


                        $exp_titulo = "";
                        $sopfisico = new sopfisico ();
                        $usuario = new usuario ();
                        $rows3 = $encuestah->dbSelectBySQL($select);
                        if (count($rows3) > 0) {
                            // Data body
                            foreach ($rows3 as $row) {
                                // encuesta
                                if ($row->exp_titulo != $exp_titulo) {
                                    $cadena .= '<tr bgcolor="#DDDDDD">';
                                    $cadena .= '<td width="5%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $usuario->getRol($row->usu_id) . '</span></td>';
                                    $cadena .= '<td width="5%" align="left"><span style="font-family: helvetica; font-size: 11px ;font-weight: bold; text-align: left;">' . $row->fon_cod . DELIMITER . $row->uni_cod . DELIMITER . $row->tco_codigo . DELIMITER . $row->ser_codigo . DELIMITER . $row->exp_codigo . '</span></td>';
                                    $cadena .= '<td width="60%"><span style="font-family: helvetica; font-size: 11px; text-align: left;">' . $row->exp_titulo . '</span></td>';
                                    $cadena .= '<td width="5%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row->exp_tomovol . '</span></td>';
                                    if ($row->exp_aniof) {
                                        if ($row->exp_aniof!=" "){
                                            $cadena .= '<td width="5%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row->exp_anioi . ' - ' . $row->exp_aniof . '</span></td>';
                                        }else{
                                            $cadena .= '<td width="5%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row->exp_anioi . '</span></td>';
                                        }
                                    }else{
                                        $cadena .= '<td width="5%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row->exp_anioi . '</span></td>';
                                    }
                                    $cadena .= '<td width="3%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row->exp_nrocaj . '</span></td>';
                                    $cadena .= '<td width="3%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row->exp_sala . '</span></td>';
                                    $cadena .= '<td width="3%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row->exp_estante . '</span></td>';
                                    $cadena .= '<td width="3%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row->exp_cuerpo . '</span></td>';
                                    $cadena .= '<td width="3%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row->exp_balda . '</span></td>';
                                    $cadena .= '<td width="5%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $sopfisico->obtenerNombre($row->sof_id) . '</span></td>';
                                    $cadena .= '<td width="5%" height="auto" align="left" valign="top"><span style="font-family: helvetica; font-size: 7px; text-align: left; height: auto; ">' . $row->exp_obs . '</span></td>';
                                    $cadena .= '</tr>';
                                    $exp_titulo = $row->exp_titulo;

                                    // Documento                                
                                    // Aqui
                                    // Documentos
                                    // Search docs
                                    $select = "SELECT
                                             tab_respuesta.exp_id,
                                             tab_archivo.fil_id,
                                             (SELECT fon_codigo from tab_fondo WHERE fon_id=f.fon_par) AS fon_codigo,
                                             tab_unidad.uni_descripcion,
                                             tab_encuesta.ser_categoria,
                                             tab_expisadg.exp_titulo,
                                             tab_expisadg.exp_mesi,
                                             tab_expisadg.exp_anioi,
                                             tab_expisadg.exp_mesf,
                                             tab_expisadg.exp_aniof,
                                             tab_expisadg.exp_aniof,                    
                                             f.fon_cod,
                                             tab_unidad.uni_cod,
                                             tab_tipocorr.tco_codigo,
                                             tab_encuesta.ser_codigo,
                                             tab_respuesta.exp_codigo,
                                             tab_respuesta.exp_tomovol,
                                             tab_respuesta.exp_nrocaj,
                                             tab_respuesta.exp_sala,
                                             tab_respuesta.exp_estante,
                                             tab_respuesta.exp_cuerpo,
                                             tab_respuesta.exp_balda,
                                             tab_encusuario.usu_id,
                                             tab_cuerpos.cue_codigo,
                                             tab_archivo.fil_codigo,
                                             tab_archivo.fil_nro,
                                             tab_cuerpos.cue_descripcion,
                                             tab_archivo.fil_titulo,
                                             tab_archivo.fil_subtitulo,
                                             tab_archivo.fil_proc,
                                             tab_archivo.fil_firma,
                                             tab_archivo.fil_cargo,
                                             tab_archivo.fil_nrofoj,
                                             tab_archivo.fil_tomovol,
                                             tab_archivo.fil_nroejem,
                                             tab_archivo.fil_nrocaj,
                                             tab_archivo.fil_sala,
                                             tab_archivo.fil_estante,
                                             tab_archivo.fil_cuerpo,
                                             tab_archivo.fil_balda,
                                             tab_archivo.fil_tipoarch,
                                             tab_archivo.fil_mrb,
                                             tab_archivo.fil_ori,
                                             tab_archivo.fil_cop,
                                             tab_archivo.fil_fot,
                                             tab_archivo.sof_id,
                                             tab_archivo.sof_id,
                                             tab_archivo.fil_anio,
                                             tab_archivo.fil_aniof,
                                             (CASE tab_exparchivo.exa_condicion 
                                                                 WHEN '1' THEN 'DISPONIBLE' 
                                                                 WHEN '2' THEN 'PRESTADO' END) AS disponibilidad,
                                             (SELECT fil_nomoriginal FROM tab_archivo_digital WHERE tab_archivo_digital.fil_id=tab_archivo.fil_id AND tab_archivo_digital.fil_estado = '1' ) AS fil_nomoriginal,
                                             (SELECT fil_extension FROM tab_archivo_digital WHERE tab_archivo_digital.fil_id=tab_archivo.fil_id AND tab_archivo_digital.fil_estado = '1' ) AS fil_extension,
                                             (SELECT fil_tamano/1048576 FROM tab_archivo_digital WHERE tab_archivo_digital.fil_id=tab_archivo.fil_id AND tab_archivo_digital.fil_estado = '1' ) AS fil_tamano,
                                             (SELECT fil_nur FROM tab_doccorr WHERE tab_doccorr.fil_id=tab_archivo.fil_id AND tab_doccorr.dco_estado = '1' ) AS fil_nur,                
                                             (SELECT fil_asunto FROM tab_doccorr WHERE tab_doccorr.fil_id=tab_archivo.fil_id AND tab_doccorr.dco_estado = '1' ) AS fil_asunto,                
                                             tab_archivo.fil_obs
                                             FROM
                                             tab_fondo as f
                                             INNER JOIN tab_unidad ON f.fon_id = tab_unidad.fon_id
                                             INNER JOIN tab_encuesta ON tab_unidad.uni_id = tab_encuesta.uni_id
                                             INNER JOIN tab_tipocorr ON tab_tipocorr.tco_id = tab_encuesta.tco_id
                                             INNER JOIN tab_respuesta ON tab_encuesta.ser_id = tab_respuesta.ser_id
                                             INNER JOIN tab_exparchivo ON tab_respuesta.exp_id = tab_exparchivo.exp_id
                                             INNER JOIN tab_archivo ON tab_archivo.fil_id = tab_exparchivo.fil_id
                                             INNER JOIN tab_expisadg ON tab_respuesta.exp_id = tab_expisadg.exp_id
                                             INNER JOIN tab_encusuario ON tab_respuesta.exp_id = tab_encusuario.exp_id
                                             INNER JOIN tab_cuerpos ON tab_cuerpos.cue_id = tab_exparchivo.cue_id
                                             INNER JOIN tab_tramitecuerpos ON tab_cuerpos.cue_id = tab_tramitecuerpos.cue_id
                                             INNER JOIN tab_tramite ON tab_tramite.tra_id = tab_tramitecuerpos.tra_id
                                             WHERE
                                             f.fon_estado = 1 AND
                                             tab_unidad.uni_estado = 1 AND
                                             tab_tipocorr.tco_estado = 1 AND
                                             tab_encuesta.ser_estado = 1 AND
                                             tab_respuesta.exp_estado = 1 AND
                                             tab_expisadg.exp_estado = 1 AND 
                                             tab_archivo.fil_estado = 1 AND
                                             tab_exparchivo.exa_estado = 1 AND
                                             tab_respuesta.exp_id = '$row->exp_id'
                                             ORDER BY tab_unidad.uni_id, 
                                             tab_encuesta.ser_id, 
                                             tab_respuesta.exp_codigo::int,
                                             tab_archivo.fil_nro::int ";

                                    $sopfisico = new sopfisico ();
                                    $rows6 = $encuestah->dbSelectBySQL($select);
                                    if (count($rows6) > 0) {
                                        foreach ($rows6 as $row6d) {
                                            // Documentos
                                            $cadena .= '<tr>';
                                            $cadena .= '<td width="5%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $usuario->getRol($row6d->usu_id) . '</span></td>';
                                            $cadena .= '<td width="5%"  align="left"><span style="font-family: helvetica; font-size: 11px ;font-weight: bold; text-align: left;">' . $row6d->fon_cod . DELIMITER . $row6d->uni_cod . DELIMITER . $row6d->tco_codigo . DELIMITER . $row6d->ser_codigo . DELIMITER . $row6d->exp_codigo . DELIMITER . $row6d->fil_nro . '</span></td>';
                                            if ($row6d->fil_subtitulo) {
                                                $cadena .= '<td width="60%"><span style="font-family: helvetica; font-size: 11px; text-align: left;">' . $row6d->fil_titulo . '-' . $row6d->fil_subtitulo . '</span></td>';
                                            } else {
                                                $cadena .= '<td width="60%"><span style="font-family: helvetica; font-size: 11px; text-align: left;">' . $row6d->fil_titulo . '</span></td>';
                                            }
                                            $cadena .= '<td width="5%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row6d->fil_tomovol . '</span></td>';
                                            if ($row6d->fil_aniof){
                                                $cadena .= '<td width="5%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;;">' . $row6d->fil_anio . ' - ' . $row6d->fil_aniof . '</span></td>';
                                            }else{
                                                $cadena .= '<td width="5%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;;">' . $row6d->fil_anio . '</span></td>';
                                            }
                                            $cadena .= '<td width="3%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row6d->fil_nrocaj . '</span></td>';
                                            $cadena .= '<td width="3%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row6d->fil_sala . '</span></td>';
                                            $cadena .= '<td width="3%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row6d->fil_estante . '</span></td>';
                                            $cadena .= '<td width="3%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row6d->fil_cuerpo . '</span></td>';
                                            $cadena .= '<td width="3%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row6d->fil_balda . '</span></td>';
                                            $cadena .= '<td width="5%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $sopfisico->obtenerNombre($row6d->sof_id) . '</span></td>';
                                            $cadena .= '<td width="5%" height="auto" align="left" valign="top"><span style="font-family: helvetica; font-size: 7px; text-align: left; height: auto; ">' . $row6d->fil_obs . '</span></td>';
                                            $cadena .= '</tr>';
                                        }
                                    }
                                    $exp_titulo = $row->exp_titulo;
                                    
                                } else {

                                    // Documento                                
                                    // Aqui
                                    // Documentos
                                    // Search docs
                                    $select = "SELECT
                                             tab_respuesta.exp_id,
                                             tab_archivo.fil_id,
                                             (SELECT fon_codigo from tab_fondo WHERE fon_id=f.fon_par) AS fon_codigo,
                                             tab_unidad.uni_descripcion,
                                             tab_encuesta.ser_categoria,
                                             tab_expisadg.exp_titulo,
                                             tab_expisadg.exp_mesi,
                                             tab_expisadg.exp_anioi,
                                             tab_expisadg.exp_mesf,
                                             tab_expisadg.exp_aniof,
                                             tab_expisadg.exp_aniof,                    
                                             f.fon_cod,
                                             tab_unidad.uni_cod,
                                             tab_tipocorr.tco_codigo,
                                             tab_encuesta.ser_codigo,
                                             tab_respuesta.exp_codigo,
                                             tab_respuesta.exp_tomovol,
                                             tab_respuesta.exp_nrocaj,
                                             tab_respuesta.exp_sala,
                                             tab_respuesta.exp_estante,
                                             tab_respuesta.exp_cuerpo,
                                             tab_respuesta.exp_balda,
                                             tab_encusuario.usu_id,
                                             tab_cuerpos.cue_codigo,
                                             tab_archivo.fil_codigo,
                                             tab_archivo.fil_nro,
                                             tab_cuerpos.cue_descripcion,
                                             tab_archivo.fil_titulo,
                                             tab_archivo.fil_subtitulo,
                                             tab_archivo.fil_proc,
                                             tab_archivo.fil_firma,
                                             tab_archivo.fil_cargo,
                                             tab_archivo.fil_nrofoj,
                                             tab_archivo.fil_tomovol,
                                             tab_archivo.fil_nroejem,
                                             tab_archivo.fil_nrocaj,
                                             tab_archivo.fil_sala,
                                             tab_archivo.fil_estante,
                                             tab_archivo.fil_cuerpo,
                                             tab_archivo.fil_balda,
                                             tab_archivo.fil_tipoarch,
                                             tab_archivo.fil_mrb,
                                             tab_archivo.fil_ori,
                                             tab_archivo.fil_cop,
                                             tab_archivo.fil_fot,
                                             tab_archivo.sof_id,
                                             tab_archivo.fil_anio,
                                             tab_archivo.fil_aniof,
                                             (CASE tab_exparchivo.exa_condicion 
                                                                 WHEN '1' THEN 'DISPONIBLE' 
                                                                 WHEN '2' THEN 'PRESTADO' END) AS disponibilidad,
                                             (SELECT fil_nomoriginal FROM tab_archivo_digital WHERE tab_archivo_digital.fil_id=tab_archivo.fil_id AND tab_archivo_digital.fil_estado = '1' ) AS fil_nomoriginal,
                                             (SELECT fil_extension FROM tab_archivo_digital WHERE tab_archivo_digital.fil_id=tab_archivo.fil_id AND tab_archivo_digital.fil_estado = '1' ) AS fil_extension,
                                             (SELECT fil_tamano/1048576 FROM tab_archivo_digital WHERE tab_archivo_digital.fil_id=tab_archivo.fil_id AND tab_archivo_digital.fil_estado = '1' ) AS fil_tamano,
                                             (SELECT fil_nur FROM tab_doccorr WHERE tab_doccorr.fil_id=tab_archivo.fil_id AND tab_doccorr.dco_estado = '1' ) AS fil_nur,                
                                             (SELECT fil_asunto FROM tab_doccorr WHERE tab_doccorr.fil_id=tab_archivo.fil_id AND tab_doccorr.dco_estado = '1' ) AS fil_asunto,                
                                             tab_archivo.fil_obs
                                             FROM
                                             tab_fondo as f
                                             INNER JOIN tab_unidad ON f.fon_id = tab_unidad.fon_id
                                             INNER JOIN tab_encuesta ON tab_unidad.uni_id = tab_encuesta.uni_id
                                             INNER JOIN tab_tipocorr ON tab_tipocorr.tco_id = tab_encuesta.tco_id
                                             INNER JOIN tab_respuesta ON tab_encuesta.ser_id = tab_respuesta.ser_id
                                             INNER JOIN tab_exparchivo ON tab_respuesta.exp_id = tab_exparchivo.exp_id
                                             INNER JOIN tab_archivo ON tab_archivo.fil_id = tab_exparchivo.fil_id
                                             INNER JOIN tab_expisadg ON tab_respuesta.exp_id = tab_expisadg.exp_id
                                             INNER JOIN tab_encusuario ON tab_respuesta.exp_id = tab_encusuario.exp_id
                                             INNER JOIN tab_cuerpos ON tab_cuerpos.cue_id = tab_exparchivo.cue_id
                                             INNER JOIN tab_tramitecuerpos ON tab_cuerpos.cue_id = tab_tramitecuerpos.cue_id
                                             INNER JOIN tab_tramite ON tab_tramite.tra_id = tab_tramitecuerpos.tra_id
                                             WHERE
                                             f.fon_estado = 1 AND
                                             tab_unidad.uni_estado = 1 AND
                                             tab_tipocorr.tco_estado = 1 AND
                                             tab_encuesta.ser_estado = 1 AND
                                             tab_respuesta.exp_estado = 1 AND
                                             tab_expisadg.exp_estado = 1 AND 
                                             tab_archivo.fil_estado = 1 AND
                                             tab_exparchivo.exa_estado = 1 AND
                                             tab_respuesta.exp_id = '$row->exp_id'
                                             ORDER BY tab_unidad.uni_id, 
                                             tab_encuesta.ser_id, 
                                             tab_respuesta.exp_codigo::int, 
                                             tab_archivo.fil_nro::int ";

                                    $sopfisico = new sopfisico ();
                                    $rows6 = $encuestah->dbSelectBySQL($select);
                                    if (count($rows6) > 0) {
                                        foreach ($rows6 as $row6d) {
                                            // Documentos
                                            $cadena .= '<tr>';
                                            $cadena .= '<td width="5%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $usuario->getRol($row6d->usu_id) . '</span></td>';
                                            $cadena .= '<td width="5%"  align="left"><span style="font-family: helvetica; font-size: 11px ;font-weight: bold; text-align: left;">' . $row6d->fon_cod . DELIMITER . $row6d->uni_cod . DELIMITER . $row6d->tco_codigo . DELIMITER . $row6d->ser_codigo . DELIMITER . $row6d->exp_codigo . DELIMITER . $row6d->fil_nro . '</span></td>';
                                            if ($row6d->fil_subtitulo) {
                                                $cadena .= '<td width="60%"><span style="font-family: helvetica; font-size: 11px; text-align: left;">' . $row6d->fil_titulo . '-' . $row6d->fil_subtitulo . '</span></td>';
                                            } else {
                                                $cadena .= '<td width="60%"><span style="font-family: helvetica; font-size: 11px; text-align: left;">' . $row6d->fil_titulo . '</span></td>';
                                            }
                                            $cadena .= '<td width="5%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row6d->fil_tomovol . '</span></td>';
                                            if ($row6d->fil_aniof){
                                                $cadena .= '<td width="5%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row6d->fil_anio . ' - ' . $row6d->fil_aniof . '</span></td>';
                                            }else{
                                                $cadena .= '<td width="5%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row6d->fil_anio . '</span></td>';
                                            }
                                            $cadena .= '<td width="3%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row6d->fil_nrocaj . '</span></td>';
                                            $cadena .= '<td width="3%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row6d->fil_sala . '</span></td>';
                                            $cadena .= '<td width="3%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row6d->fil_estante . '</span></td>';
                                            $cadena .= '<td width="3%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row6d->fil_cuerpo . '</span></td>';
                                            $cadena .= '<td width="3%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $row6d->fil_balda . '</span></td>';
                                            $cadena .= '<td width="5%" align="center" valign="top"><span style="font-family: helvetica; font-size: 11px; text-align: center;">' . $sopfisico->obtenerNombre($row6d->sof_id) . '</span></td>';
                                            $cadena .= '<td width="5%" height="auto" align="left" valign="top"><span style="font-family: helvetica; font-size: 7px; text-align: left; height: auto; ">' . $row6d->fil_obs . '</span></td>';
                                            $cadena .= '</tr>';
                                        }
                                    }

                                    $exp_titulo = $row->exp_titulo;
                                }
                            } // end for
                        }
                    } // end for                    
                }
            } // End foreach

            $cadenaLogo = '';
            $cadena .= '</table>';

            if ($tiporeporte == 1) {
                $pdf->writeHTML($cadena, true, false, false, false, '');
                //Close and output PDF document
                $pdf->Output('reporte_inventario.pdf', 'I');
            } else if ($tiporeporte == 2) {
                echo $cadena;
            } else if ($tiporeporte == 3) {

                $cadenaLogo .= '<tr';
                $cadenaLogo .= '<td width="760"  align="left"><img src="' . PATH_DOMAIN . '/web/img/logo2.png" width="80" height="100"></td>';
                $cadenaLogo .= '</tr>';
                $cadena = $cadenaLogo . $cadena;
                echo $cadena;
            }
        }
    }

}

?>
