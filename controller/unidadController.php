<?php

/**
 * unidadController.php Controller
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.24
 * @access public
 */
class unidadController extends baseController {

    var $unidad;

    function index() {
        $this->menu = new menu ();
        $liMenu = $this->menu->imprimirMenu(VAR1, $_SESSION ['USU_ID']);
        $this->registry->template->men_titulo = $liMenu;
        $this->registry->template->PATH_WEB = PATH_WEB;
        $this->registry->template->PATH_DOMAIN = PATH_DOMAIN;
        $this->registry->template->PATH_EVENT = "add";
        $this->registry->template->GRID_SW = "false";
        $this->registry->template->PATH_J = "jquery";
        $this->registry->template->show('header');
        $this->registry->template->show('unidad/tab_unidadg.tpl');
        $this->registry->template->show('footer');
    }

    function load() {
        $this->unidad = new tab_unidad ();
        $this->unidad->setRequest2Object($_REQUEST);
        $page = $_REQUEST['page'];
        $rp = $_REQUEST['rp'];
        $sortname = $_REQUEST['sortname'];
        $sortorder = $_REQUEST['sortorder'];
        if (!$sortname){
            $sortname = ' tab_fondo.fon_cod,
                tem.uni_cod';
        }
        if (!$sortorder)
            $sortorder = 'desc';
        $sort = "ORDER BY $sortname $sortorder";
        if (!$page)
            $page = 1;
        if (!$rp)
            $rp = 15;
        $start = (($page - 1) * $rp);
        $limit = "LIMIT $rp OFFSET $start ";
        $query = strtoupper(trim($_REQUEST['query']));
        $qtype = $_REQUEST['qtype'];
        $where = "";
        if ($query) {
            if ($query == 'Niveles') {
            } elseif ($qtype == 'uni_id') {
                $where = " AND tem.uni_id = '$query' ";
            } elseif ($qtype == 'ubi_id_cod') {
                $where = " AND tem.ubi_id IN (SELECT ubi_id from tab_ubicacion WHERE ubi_codigo like '%$query%') ";
            } elseif ($qtype == 'uni_piso_cod') {
                $where = " AND tem.uni_piso IN (SELECT ubi_id from tab_ubicacion WHERE ubi_codigo like '%$query%') ";
            } elseif ($qtype == 'uni_par_cod') {
                $where = " AND tem.uni_par IN (SELECT uni_id from tab_unidad WHERE uni_codigo like '%$query%') ";
            } elseif ($qtype == 'fondo') {
                $where = " AND tem.unif_id IN (SELECT uni_id from tab_unidad WHERE uni_descripcion like '%$query%') ";
            } elseif ($qtype == 'fon_cod') {
                $where = " AND tem.fon_id IN (SELECT fon_id from tab_fondo WHERE fon_cod like '%$query%') ";
            } else {
                $where = " AND $qtype like '%$query%' ";
            }
        }
        
        $sql = "SELECT
                tem.uni_id,
                tab_fondo.fon_descripcion,
                tab_fondo.fon_cod,
                tem.uni_par,
                tem.uni_codigo,
                tem.uni_cod,                
                tem.uni_descripcion,
                (SELECT ubi_codigo from tab_ubicacion WHERE ubi_id=tem.ubi_id) AS ubi_id_cod,
                (SELECT ubi_codigo from tab_ubicacion WHERE ubi_id=tem.uni_piso) AS uni_piso_cod,
                (SELECT uni_descripcion from tab_unidad WHERE uni_id=tem.uni_par) AS uni_par_cod,
                (SELECT fon_cod from tab_fondo WHERE fon_id=tem.fon_id) AS fon_cod,
                tem.uni_contador 
                FROM tab_unidad AS tem
                INNER JOIN tab_fondo ON tab_fondo.fon_id = tem.fon_id
                WHERE
                tem.uni_estado = '1' 
                $where
                $sort 
                $limit";
        $result = $this->unidad->dbselectBySQL($sql);
        $total = $this->unidad->countBySQL("SELECT COUNT(tem.uni_id) 
                                            FROM tab_unidad AS tem
                                            INNER JOIN tab_fondo ON tab_fondo.fon_id = tem.fon_id
                                            WHERE
                                            tem.uni_estado = '1' 
                                            $where");
        
        header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
        header ( "Cache-Control: no-cache, must-revalidate" );
        header ( "Pragma: no-cache" );
        header("Content-type: text/x-json");
        $json = "";
        $json .= "{\n";
        $json .= "page: $page,\n";
        $json .= "total: $total,\n";
        $json .= "rows: [";
        $rc = false;
        $i = 0;
        
        $fon_descripciona = "";
        $fon_descripcionn = "";
        
        foreach ($result as $un) {
            if ($rc)
                $json .= ",";
            
            // New
            $fon_descripcionn = $un->fon_descripcion;
            if ($fon_descripcionn != $fon_descripciona) {
                $json .= "\n{";
                $json .= "id:'" . $un->uni_id . "',";
                $json .= "cell:[''";               
                $json .= ",'<font color=#02a602>" . addslashes($un->fon_cod) . "'";                
                $json .= ",'<font color=#02a602>" . addslashes(utf8_encode($un->fon_descripcion)) . "'";
                $json .= ",'" . addslashes("") . "'";
                $json .= ",'" . addslashes(utf8_encode("")) . "'";
                $json .= ",'" . addslashes("") . "'";                
                $json .= "]}"; 
                
                // List seccion
                $rc = true;
                if ($rc)
                $json .= ",";
                $json .= "\n{";
                $json .= "id:'" . $un->uni_id . "',";
                $json .= "cell:['" . $un->uni_id . "'";               
                $json .= ",'" . addslashes($un->fon_cod . DELIMITER . $un->uni_cod) . "'";
                
                if ($un->uni_par=='-1'){
                    $json .= ",'" . addslashes(utf8_encode($un->uni_descripcion)) . "'";
                }else{
                    $json .= ",'" . addslashes("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . utf8_encode($un->uni_descripcion)) . "'";
                    //$json .= ",'" . addslashes("----- " . utf8_encode($un->uni_descripcion)) . "'";
                }                        
                $json .= ",'" . addslashes($un->uni_par_cod) . "'";
                $json .= ",'" . addslashes(utf8_encode($un->fon_descripcion)) . "'";
                $json .= ",'" . addslashes($un->uni_contador) . "'";
                
                $json .= "]}";                 
                
            }else{
                
                $json .= "\n{";
                $json .= "id:'" . $un->uni_id . "',";
                $json .= "cell:['" . $un->uni_id . "'";               
                $json .= ",'" . addslashes($un->fon_cod . DELIMITER . $un->uni_cod) . "'";

                if ($un->uni_par=='-1'){
                    $json .= ",'" . addslashes(utf8_encode($un->uni_descripcion)) . "'";
                }else{
                    $json .= ",'" . addslashes("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . utf8_encode($un->uni_descripcion)) . "'";
                    //$json .= ",'" . addslashes("----- " . utf8_encode($un->uni_descripcion)) . "'";
                }                        
                $json .= ",'" . addslashes($un->uni_par_cod) . "'";
                $json .= ",'" . addslashes(utf8_encode($un->fon_descripcion)) . "'";
                $json .= ",'" . addslashes($un->uni_contador) . "'";
                
                $json .= "]}";                
            }    
            
            $fon_descripciona = $un->fon_descripcion;
            $rc = true;
            $i++;
        }
        $json .= "]\n";
        $json .= "}";
        echo $json;
    }


    function add() {
        $this->menu = new menu ();
        $liMenu = $this->menu->imprimirMenu(VAR1, $_SESSION ['USU_ID']);
        $this->registry->template->men_titulo = $liMenu;

        $ubi = new ubicacion();
        $unidad = new unidad();
        $fondo = new fondo();

        $tipoarch = new tipoarch();

        $this->registry->template->uni_post = "";
        $this->registry->template->titulo = "NUEVA SECCI&Oacute;N";
        $this->registry->template->uni_id = "";
        $this->registry->template->if_upd = 0;
        $this->registry->template->ubi_id = $ubi->obtenerSelect(0);
        $this->registry->template->uni_piso = "";
        $this->registry->template->uni_par = "";
        $this->registry->template->fon_id = $fondo->obtenerSelectFondos();
        $this->registry->template->uni_codigo = "";
        $this->registry->template->uni_cod = "";
        $this->registry->template->uni_descripcion = "";
        $this->registry->template->uni_ml = "";
        $this->registry->template->uni_tel = "";
        $this->registry->template->tar_id = $tipoarch->obtenerSelect();

        $this->registry->template->PATH_WEB = PATH_WEB;
        $this->registry->template->PATH_DOMAIN = PATH_DOMAIN;
        $this->registry->template->PATH_EVENT = "save";
        $this->registry->template->GRID_SW = "false";
        $this->registry->template->PATH_J = "jquery-1.4.1";
        $this->registry->template->show('header');
        $this->registry->template->show('unidad/tab_unidad.tpl');
        $this->registry->template->show('footer');
    }

    function save() {
        $this->unidad = new tab_unidad ();
        $this->oficina = new tab_oficina ();
        
        if ($_REQUEST['uni_par']){            
            $this->unidad->setUni_id($_REQUEST['uni_id']);
            $this->unidad->setUbi_id($_REQUEST['ubi_id']);
            $this->unidad->setUni_piso($_REQUEST['uni_piso']);            
            $this->unidad->setUni_par($_REQUEST['uni_par']);
            $this->unidad->setUni_descripcion($_REQUEST['uni_descripcion']);
            $this->unidad->setFon_id($_REQUEST['fon_id']);           
            $this->unidad->setUni_estado(1);
            $this->unidad->setUni_codigo($_REQUEST['uni_codigo']);            
            // Genera codigo
            $codigo = $this->generaCodigo($_REQUEST['uni_par']);
            $this->unidad->setUni_cod($codigo);
            $this->unidad->setTar_id($_REQUEST['tar_id']);
            $this->unidad->setUnif_id(1);
            $this->unidad->setUni_parcont('0');            
            $this->unidad->setUni_ml('0');
            $this->unidad->setNiv_id(1);             
            $this->unidad->setUni_contador('0');
            $this->unidad->setUni_tel($_REQUEST['uni_tel']);
            $uni_id = $this->unidad->insert2();

            // Actualizar Siguiente Hijo del padre
            $row2 = $this->unidad->dbselectByField("uni_id", $_REQUEST['uni_par']);
            $row2 = $row2[0];            
            $this->unidad->setUni_id($row2->uni_id);
            $this->unidad->setUbi_id($row2->ubi_id);
            $this->unidad->setUni_piso($row2->uni_piso);
            $this->unidad->setUni_codigo($row2->uni_codigo);
            $this->unidad->setUni_cod($row2->uni_cod);
            $this->unidad->setUni_par($row2->uni_par);
            $this->unidad->setUnif_id($row2->unif_id);
            $this->unidad->setUni_parcont($row2->uni_parcont);  
            $this->unidad->setUni_descripcion($row2->uni_descripcion);
            $this->unidad->setFon_id($row2->fon_id);
            $this->unidad->setTar_id($row2->tar_id);
            $uni_contador = $row2->uni_contador+1;
            $this->unidad->setUni_contador($uni_contador);                 
            $this->unidad->setUni_ml(0);
            $this->unidad->setNiv_id(1);             
            $this->unidad->setUni_tel($row2->uni_tel);
            $this->unidad->update();          
        }
        else{
            // No tiene padre
            $this->unidad->setUni_id($_REQUEST['uni_id']);
            $this->unidad->setUni_par('-1');
            $this->unidad->setUni_codigo($_REQUEST['uni_codigo']);
            // Genera codigo
            $codigo = $this->getCodigoPadre($_REQUEST['fon_id']);            
            $this->unidad->setUni_cod($codigo . DELIMITER . "0");            
            $this->unidad->setUni_descripcion($_REQUEST['uni_descripcion']);
            $this->unidad->setFon_id($_REQUEST['fon_id']);
            $this->unidad->setTar_id($_REQUEST['tar_id']);            
            $this->unidad->setUni_estado(1);
            $this->unidad->setUni_contador('0');
            $this->unidad->setUnif_id(1);
            $this->unidad->setUni_parcont('0');            
            $this->unidad->setUni_ml('0');
            $this->unidad->setNiv_id(1); 
            $this->unidad->setUbi_id($_REQUEST['ubi_id']);
            $this->unidad->setUni_piso($_REQUEST['uni_piso']); 
            $this->unidad->setUni_tel($_REQUEST['uni_tel']); 
            $uni_id = $this->unidad->insert2();
        }
        Header("Location: " . PATH_DOMAIN . "/unidad/");
    }


    function edit() {
        $this->unidad = new tab_unidad ();
        $this->unidad->setRequest2Object($_REQUEST);
        header("Location: " . PATH_DOMAIN . "/unidad/view/" . $_REQUEST["uni_id"] . "/");
    }

    function view() {
        if (!VAR3) {
            die("Error del sistema 404");
        }
        
        $fondo = new fondo();
        $tipoarch = new tipoarch();
        $this->unidad = new tab_unidad ();
        if (VAR3 == null) {
            header("Location: " . PATH_DOMAIN . "/unidad/");
        }
        $row = $this->unidad->dbselectByField("uni_id", VAR3);
        if (count($row) == 0) {
            header("Location: " . PATH_DOMAIN . "/unidad/");
        }
        $row = $row [0];
        $ubicacion = new ubicacion();
        $unidad = new unidad();
        $this->registry->template->uni_post = " + '&uni_id=$row->uni_id'";
        $this->registry->template->titulo = "EDITAR SECCI&Oacute;N: $row->uni_descripcion";
        $this->registry->template->uni_id = $row->uni_id;
        $this->registry->template->fon_id = $fondo->obtenerSelectFondos($row->fon_id);
        $this->registry->template->tar_id = $tipoarch->obtenerSelect($row->tar_id);
        
        $this->registry->template->ubi_id = $ubicacion->obtenerSelect(0, $row->ubi_id);
        $this->registry->template->uni_piso = $ubicacion->obtenerSelect($row->ubi_id, $row->uni_piso);
        if ($row->uni_par > 0) {
            $uni_par = $unidad->obtenerSelectUnidades($row->uni_par);
            $this->registry->template->uni_par = $uni_par;
        } else {
            $this->registry->template->uni_par = $unidad->obtenerSelectPadres();
        }        
        
        $this->registry->template->uni_codigo = $row->uni_codigo;
        $this->registry->template->uni_cod = $row->uni_cod;
        $this->registry->template->uni_descripcion = utf8_encode($row->uni_descripcion);
        $this->registry->template->uni_ml = $row->uni_ml;
        $this->registry->template->uni_tel = $row->uni_tel;
        
        $this->menu = new menu ();
        $liMenu = $this->menu->imprimirMenu(VAR1, $_SESSION ['USU_ID']);
        $this->registry->template->men_titulo = $liMenu;        
        $this->registry->template->PATH_WEB = PATH_WEB;
        $this->registry->template->PATH_DOMAIN = PATH_DOMAIN;
        $this->registry->template->PATH_WEB = PATH_WEB;
        $this->registry->template->PATH_DOMAIN = PATH_DOMAIN;
        $this->registry->template->PATH_EVENT = "update";
        $this->registry->template->GRID_SW = "true";
        $this->registry->template->PATH_J = "jquery-1.4.1";
        $this->registry->template->show('header');
        $this->registry->template->show('unidad/tab_unidad.tpl');
        $this->registry->template->show('footer');
    }
    
    
    function update() {
        $this->unidad = new tab_unidad ();
        $this->unidad->setRequest2Object($_REQUEST);
        $row = $this->unidad->dbselectByField("uni_id", $_REQUEST['uni_id']);
        $this->unidad->setUni_id($_REQUEST['uni_id']);
        
        // C&oacute;digo antiguo
        $this->unidad->setUbi_id($_REQUEST['ubi_id']);
        $this->unidad->setUni_piso($_REQUEST['uni_piso']);
        if ($_REQUEST['uni_par']){
            $this->unidad->setUni_par($_REQUEST['uni_par']);
            // Genera codigo
            $codigo = $this->generaCodigo($_REQUEST['uni_par']);
            $this->unidad->setUni_cod($_REQUEST['uni_cod']);            
        }else{
            $this->unidad->setUni_par('-1');
            $this->unidad->setUni_cod($_REQUEST['uni_cod'] . DELIMITER . "0");             
        }
        $this->unidad->setUni_codigo($_REQUEST['uni_codigo']);
        $this->unidad->setUni_descripcion($_REQUEST['uni_descripcion']);
        $this->unidad->setFon_id($_REQUEST['fon_id']);
        $this->unidad->setTar_id($_REQUEST['tar_id']);
        $this->unidad->setUni_tel($_REQUEST['uni_tel']);
        $this->unidad->update();

        Header("Location: " . PATH_DOMAIN . "/unidad/");
        
        
    }    
    
    // Nueva generaci&oacute;n de codigo 
    function getCodigoPadre($fon_id){
        $contador = "";
        $unidad = new tab_unidad();
        $sql = "SELECT count(uni_id) as contador
                FROM tab_unidad
                WHERE uni_par = -1
                AND fon_id = '$fon_id'
                ORDER BY 1 DESC ";
        $result = $unidad->dbSelectBySQL($sql);
        if ($result != null) {
            foreach ($result as $row) {
                //$res = sprintf("%01d", $row->ofi_contador + 1);
                $res = $row->contador;
            }
            $contador = $res+1;
        }        
        return $contador;
    }
    
    
    // Nueva generaci&oacute;n de codigo 
    function generaCodigo($uni_par) {
        $new_cod = "";
        $unidad = new tab_unidad();
        $sql = "SELECT 
                uni_cod, 
                uni_contador
                FROM tab_unidad 
                WHERE uni_id = $uni_par
                ORDER BY 1 DESC 
                LIMIT 1 OFFSET 0";
        $result = $unidad->dbSelectBySQL($sql);
        if ($result != null) {
            foreach ($result as $row) {
                //$res1 = explode(".", $row->uni_codigo);
                $res = $row->uni_cod. "." . sprintf("%01d", $row->uni_contador + 1);
            }
            $new_cod = $res;
        }        
        return $new_cod;
    }



    function delete() {
        $tunidad = new tab_unidad ();
        $tunidad->setRequest2Object($_REQUEST);
        $res = array();
        $uni_id = $_REQUEST['uni_id'];
        $hijos = $tunidad->dbSelectBySQL("SELECT * FROM tab_unidad WHERE (uni_estado = '1' or uni_estado = '10') AND uni_par='" . $uni_id . "' ");
        if (count($hijos) > 0) {
            foreach ($hijos as $hijo) {
                $res[$hijo->uni_id] = $hijo->uni_codigo;
            }
        } else {
            $sql = "SELECT
                    Count(tu.usu_id)
                    FROM
                    tab_unidad AS tun
                    Inner Join tab_usuario AS tu ON tu.uni_id = tun.uni_id
                    WHERE
                    tun.uni_id =  '$uni_id' ";
            $num = $tunidad->countBySQL($sql);
            if ($num > 0) {
                $tunidad->setUni_id($uni_id);
                $tunidad->setUni_estado(3);
                $tunidad->update();
            } else {
                $tunidad->setUni_id($uni_id);
                $tunidad->setUni_estado(2);
                $tunidad->update();
            }
        }
        echo json_encode($res);
    }


    function loadAjax() { //despliega las unidades padre de un nivel dado (unidad hija)
        $res = array();
        $niv = $_POST["niv_id"];
        $nivel = new tab_nivel();
        $nivel = $nivel->dbselectById($niv);
        if ($nivel->niv_codigo != '0') {
            $add = "";
            if (isset($_POST["uni_id"]))
                $add = " AND tun.uni_id<>'" . $_POST["uni_id"] . "'";
            $add.= " AND tn.niv_id = '" . $nivel->niv_codigo . "' ";
            $sql = "SELECT DISTINCT
			tun.uni_id,
			tun.uni_codigo, tun.uni_descripcion
			FROM
			tab_unidad tun INNER JOIN tab_nivel tn ON tn.niv_id=tun.niv_id
			WHERE
			(uni_estado = '1' || uni_estado ='10') $add
			ORDER BY uni_descripcion ASC ";
            //print $sql;die;
            $unidad = new tab_unidad();
            $result = $unidad->dbSelectBySQL($sql);

            foreach ($result as $row) {
                $res[$row->uni_id] = $row->uni_descripcion;
            }
        }
        echo json_encode($res);
    }

    function verifCodigo() {
        $unidad = new unidad();
        $unidad->setRequest2Object($_REQUEST);
        $uni_codigo = trim($_REQUEST['uni_codigo']);
        if ($unidad->existeCodigo($uni_codigo)) {
            echo 'El c&oacute;digo ya existe, escriba otro.';
        }
        echo '';
    }

    function verifyFields() {
        $unidad = new unidad();
        $uni_codigo = trim($_POST['uni_codigo']);
        $Path_event = $_POST['path_event'];
        if ($Path_event != 'update') {
            if ($unidad->existeCodigo($uni_codigo)) {
                echo 'El c&oacute;digo ya existe, escriba otro.';
            }
            if (strlen($uni_codigo) < 2 || strlen($uni_codigo) > 2) {
                echo 'El tama&ntilde;o debe de ser igual a 2.';
            } else {
                echo '';
            }
        } else {
            echo '';
        }
    }

    //despliega las unidades padre de un nivel dado (unidad hija)
    function loadAjaxFondo() {
        $fondo = "";
        $uni_id = $_POST["Uni_id"];
        $sql = "SELECT
                tab_fondo.fon_id,
                tab_fondo.fon_cod,
                tab_fondo.fon_descripcion
                FROM
                tab_unidad
                INNER JOIN tab_fondo ON tab_fondo.fon_id = tab_unidad.fon_id
                WHERE
                tab_unidad.uni_estado = '1'
                AND tab_unidad.uni_id = '$uni_id'
                ORDER BY uni_descripcion ASC ";
        $unidad = new tab_unidad();
        $result = $unidad->dbSelectBySQL($sql);

        foreach ($result as $row) {
            $fondo = $row->fon_descripcion;
        }
        echo $fondo;
    }

    function getCodigo() {
        $res = array();
        //$dep_id = $_POST["Dep_id"];
        $uni_id = $_POST["Uni_id"];        
        $departamento = new tab_departamento();
        
        $res['fon_cod'] = '';
        $res['uni_cod'] = '';
        $res['uni_contador'] = '';        
        if ($uni_id != "0") {
            $sql = "SELECT
                    tab_fondo.fon_cod,
                    tab_unidad.uni_cod,
                    tab_unidad.uni_contador
                    FROM
                    tab_fondo
                    INNER JOIN tab_unidad ON tab_fondo.fon_id = tab_unidad.fon_id
                    WHERE (tab_unidad.uni_estado = '1' AND tab_unidad.uni_id='$uni_id')";
            $result = $departamento->dbSelectBySQL($sql);
            foreach ($result as $row) {
                $res['fon_cod'] = $row->fon_cod;
                $res['uni_cod'] = $row->uni_cod;
                $res['uni_contador'] = sprintf("%01d", ($row->uni_contador)+1);
            }
        }
        echo json_encode($res);
    }

    
    function getCodigoTipocorr() {
        $res = array();
        $tco_id = $_POST["Tco_id"];        
        $unidad = new tab_unidad();        
        $res['tco_codigo'] = '';
        if ($tco_id != "0") {
            $sql = "SELECT
                    tab_tipocorr.tco_codigo
                    FROM
                    tab_tipocorr
                    WHERE (tab_tipocorr.tco_estado = '1' AND tab_tipocorr.tco_id='$tco_id')";
            $result = $unidad->dbSelectBySQL($sql);
            foreach ($result as $row) {
                $res['tco_codigo'] = $row->tco_codigo;
            }
        }
        echo json_encode($res);
    }
    
    
    
    function getCodigoOfi() {
        $res = array();
        $ofi_id = $_POST["_ofi_id"];

        $oficina = new tab_oficina();
        $sql = "SELECT
                ofi_id,
                ofi_codigo
                FROM
                tab_oficina
                WHERE (ofi_estado = '1' AND ofi_id='$ofi_id')";
        $result = $oficina->dbSelectBySQL($sql);
        foreach ($result as $row) {
            $res['ofi_codigo'] = $row->ofi_codigo;
        }

        echo json_encode($res);
    }

    function loadAjaxUnidades() {
        $fon_id = $_POST["Fon_id"];
        
        $sql = "SELECT
                tab_fondo.fon_cod,
                tab_fondo.fon_descripcion,
                tab_unidad.uni_id,
                tab_unidad.uni_cod,
                tab_unidad.uni_codigo,
                tab_unidad.uni_descripcion,
                tab_unidad.uni_par
                FROM
                tab_unidad
                INNER JOIN tab_fondo ON tab_fondo.fon_id = tab_unidad.fon_id
                WHERE
                tab_unidad.uni_estado = 1
                AND tab_unidad.fon_id =  '$fon_id'
                ORDER BY tab_fondo.fon_cod,
                tab_unidad.uni_cod ";        
        
        $unidad = new tab_unidad();
        $result = $unidad->dbSelectBySQL($sql);
        $res = array();
        foreach ($result as $row) {
            if ($row->uni_par=='-1'){
                $res[$row->uni_id] = $row->uni_descripcion;
            }else{
                $res[$row->uni_id] = "----- " . $row->uni_descripcion;
            }
        }
        echo json_encode($res);
    }    
    
    
    function impresion() {

        $blanco = '#FFFFFF';
        $gris = '#DDDDDD';
        require_once ('tcpdf/config/lang/eng.php');
        require_once ('tcpdf/tcpdf.php');
        //ob_end_clean();
        // create new PDF document
        $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Arsenio Castellon');
        $pdf->SetTitle('Reporte encuesta');
        $pdf->SetSubject('Reporte encuesta');
        $pdf->SetKeywords('DGGE, DGGE, DGGE SRL');
        $pdf->SetHeaderData('logo2.png', 20, 'ABC', 'ADMINISTRADORA BOLIVIANA DE CARRETERAS');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        //set margins
        //define ('PDF_MARGIN_LEFT', 35);
        $pdf->SetMargins(15, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        //set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        //set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //set some language-dependent strings
        $pdf->setLanguageArray($l);
        // set font
        $pdf->AddPage();

        $cadena = "";
        $cadena .= '<table border="1">';
        $cadena .= '<tr align="center">
                        <td width="30"><b>Nro</b></td>
                        <td width="150"><b>C&oacute;digo</b></td>
                        <td width="500"><b>Secci&oacute;n/Subsecci&oacute;n</b></td>
                    </tr>';


        $sql = "SELECT
                tab_fondo.fon_cod,
                tab_fondo.fon_descripcion,
                tab_unidad.uni_cod,
                tab_unidad.uni_id,
                tab_unidad.uni_descripcion
                FROM
                tab_unidad
                INNER JOIN tab_fondo ON tab_fondo.fon_id = tab_unidad.fon_id
                WHERE
                tab_unidad.uni_estado = 1
                ORDER BY tab_fondo.fon_cod,
                tab_unidad.uni_cod ";
        $this->tab_unidad = new Tab_unidad();
        $row = $this->tab_unidad->dbSelectBySQL($sql);

        $i = 1;
        $fon_descripciona = "";
        $fon_descripcionn = "";
        foreach ($row as $value) {
            $fon_descripcionn = $value->fon_descripcion;
            if ($fon_descripcionn != $fon_descripciona) {
                $cadena .= '<tr bgcolor="' . $gris . '">
                                <td width="680" colspan="3">' . $value->fon_cod . " " . $value->fon_descripcion . '</td>
                            </tr>';
            }
            $cadena .= '<tr bgcolor="' . $blanco . '">
                            <td width="30">' . $i . '</td>
                            <td width="150">' . $value->fon_cod . DELIMITER . $value->uni_cod . '</td>
                            <td width="500">' . $value->uni_descripcion . '</td>
                        </tr>';
            $i++;
            $fon_descripciona = $value->fon_descripcion;
        }


        $cadena .= "</table>";

        // print a line using Cell()
        $pdf->SetFont('times', 'B', 10);
        $pdf->Cell(0, 10, 'LISTADO DE SECCIONES Y SUBSECCIONES', 0, 1, 'L', 0, '', 0);

        //$pdf->Ln();
        $pdf->SetFont('times', 'B', 10);
        $pdf->writeHTML($cadena, true, 0, true, 0);

        $pdf->Output('listado_secciones.pdf', 'I');
    }

    function impresionExcel() {
        //
        $sql = "SELECT
                tab_fondo.fon_cod,
                tab_fondo.fon_descripcion,
                tab_unidad.uni_cod,
                tab_unidad.uni_id,
                tab_unidad.uni_descripcion
                FROM
                tab_unidad
                INNER JOIN tab_fondo ON tab_fondo.fon_id = tab_unidad.fon_id
                WHERE
                tab_unidad.uni_estado = 1
                ORDER BY tab_fondo.fon_cod,
                tab_unidad.uni_cod ";
        $this->tab_unidad = new tab_encuesta();
        $row = $this->tab_unidad->dbSelectBySQL($sql);
        $i = 1;
        $fon_descripciona = "";
        $fon_descripcionn = "";

        // Excel
        $blanco = '#FFFFFF';
        $gris = '#DDDDDD';
        header("Content-type: application/vnd.ms-excel; name='excel'");
        header("Content-Disposition: filename=listado_secciones_excel.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        // Chain
        $cadena = "";
        $cadena .= '<table border="1">';
        $cadena .= '<tr></tr>';        
        $cadena .= '<tr bgcolor="' . $blanco . '">
                        <td width="30">ABC</td>
                        <td width="650" colspan="2">LISTADO DE SECCIONES Y SUBSECCIONES</td>
                    </tr>';
        $cadena .= '<tr align="center">
                        <td width="30"><b>Nro</b></td>
                        <td width="150"><b>C&oacute;digo</b></td>
                        <td width="500"><b>Serie</b></td>
                    </tr>';

        foreach ($row as $value) {
            $fon_descripcionn = $value->fon_descripcion;
            if ($fon_descripcionn != $fon_descripciona) {
                $cadena .= '<tr bgcolor="' . $gris . '">
                                <td width="680" colspan="3">' . $value->fon_cod . " " . $value->fon_descripcion . '</td>
                            </tr>';
            }

            $cadena .= '<tr bgcolor="' . $blanco . '">
                            <td width="30">' . $i . '</td>
                            <td width="150">' . $value->fon_cod . DELIMITER . $value->uni_cod . '</td>
                            <td width="500">' . $value->uni_descripcion . '</td>
                        </tr>';
            $i++;
            $fon_descripciona = $value->fon_descripcion;
        }
        $cadena .= "</table>";
        echo $cadena;
    }
    
    
    function rpteUnidad() {
        $fecha_actual = date("d/m/Y");
        $sql = "SELECT
                tem.uni_id,
                (SELECT ubi_codigo from tab_ubicacion WHERE ubi_id=tem.ubi_id) as ubi_id_cod,
                (SELECT ubi_codigo from tab_ubicacion WHERE ubi_id=tem.uni_piso) as uni_piso_cod,
                (SELECT uni_descripcion from tab_unidad WHERE uni_id=tem.uni_par) as uni_par_cod,
                (SELECT uni_descripcion from tab_unidad WHERE uni_id=tem.unif_id) as fondo,
                (SELECT fon_cod from tab_fondo WHERE fon_id=tem.fon_id) as fon_cod,
                tem.uni_codigo,
                tem.uni_ml,
                tem.uni_descripcion
                FROM
                tab_unidad AS tem
                WHERE uni_estado = '1'";

        $this->unidad = new Tab_unidad();
        $result = $this->unidad->dbselectBySQL($sql);
        require_once ('tcpdf/config/lang/eng.php');
        require_once ('tcpdf/tcpdf.php');
        $this->usuario = new usuario ();
        // create new PDF document
        $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($this->usuario->obtenerNombre($_SESSION['USU_ID']));
        $pdf->SetTitle('Reporte de Unidades');
        $pdf->SetSubject('Reporte de Unidades');
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetKeywords('DGGE, Sistema de Accidentes');
        $pdf->SetHeaderData('logo2.png', 20, 'ABC', 'Administradora Boliviana de Carreteras');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetMargins(10, 30, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(TRUE, 15);
        $pdf->SetFont('helvetica', '', 8);

        // Page
        $pdf->AddPage();
        $cadena .= '<table width="540" border="0" >';
        $cadena .= '<tr><td align="center">';
        $cadena .= '<span style="font-size: 30px;font-weight: bold;">';
        $cadena .= 'REPORTE DE SECCIONES';
        $cadena .= '</span>';
        $cadena .= '</td></tr>';
        $cadena .= '<tr><td align="left">Fecha de Elaboracion: ' . $fecha_actual . '</td></tr>';
        $cadena .= '</table>';

        $cadena .= '<table width="540" border="1">';
        $cadena .= '<tr>';
        $cadena .= '<td width="20"><div align="center"><strong>Nro.</strong></div></td>';
        $cadena .= '<td width="40"><div align="center"><strong>C&oacute;digo</strong></div></td>';
        $cadena .= '<td width="40"><div align="center"><strong>Nivel</strong></div></td>';
        $cadena .= '<td width="115"><div align="center"><strong>Descripci&oacute;n</strong></div></td>';
        $cadena .= '<td width="90"><div align="center"><strong>Ubicaci&oacute;n</strong></div></td>';
        $cadena .= '<td width="40"><div align="center"><strong>Piso</strong></div></td>';
        $cadena .= '<td width="80"><div align="center"><strong>Superior</strong></div></td>';
        $cadena .= '<td width="40"><div align="center"><strong>Fondo</strong></div></td>';
        $cadena .= '<td width="40"><div align="center"><strong>Cod. Fondo</strong></div></td>';
        $cadena .= '<td width="40"><div align="center"><strong>ML</strong></div></td>';
        $cadena .= '</tr>';
        $numero = 1;
        foreach ($result as $fila) {
            $cadena .= '<tr>';
            $cadena .= '<td width="20"><div align="center">' . $numero . '</div></td>';
            $cadena .= '<td width="40"><div align="center">' . $fila->uni_codigo . '</div></td>';
            $cadena .= '<td width="115">' . $fila->uni_descripcion . '</td>';
            $cadena .= '<td width="90">' . $fila->ubi_id_cod . '</td>';
            $cadena .= '<td width="40"><div align="center">' . $fila->uni_piso_cod . '</div></td>';
            $cadena .= '<td width="80">' . $fila->uni_par_cod . '</td>';
            $cadena .= '<td width="40">' . $fila->fondo . '</td>';
            $cadena .= '<td width="40"><div align="center">' . $fila->fon_cod . '</div></td>';
            $cadena .= '<td width="40">' . $fila->uni_ml . '</td>';
            $cadena .= '</tr>';
            $numero++;
        }
        $cadena .= '</table>';

        $pdf->writeHTML($cadena, true, false, false, false, '');

        // -----------------------------------------------------------------------------
        //Close and output PDF document
        $pdf->Output('reporte_unidad.pdf', 'I');
    }
    
}

?>
