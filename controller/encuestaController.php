<?php

/**
 * encuestaController.php Controller
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.14
 * @access public
 */
class encuestaController Extends baseController {

    function index() {
        
        
        
        $this->registry->template->enc_id = "";
        $this->registry->template->enc_categoria = "";
        $this->registry->template->PATH_WEB = PATH_WEB;
        $this->registry->template->PATH_DOMAIN = PATH_DOMAIN;
        $this->registry->template->PATH_EVENT = "add";
        $this->registry->template->GRID_SW = "false";
        $this->registry->template->PATH_J = "jquery";
        $this->menu = new menu();
        $this->liMenu = $this->menu->imprimirMenu(VAR1, $_SESSION['USU_ID']);
        $this->registry->template->men_titulo = $this->liMenu;
        $this->registry->template->show('headerG');
        $this->registry->template->show('encuesta/tab_encuestag.tpl');
        $this->registry->template->show('footer');
    }

    function load() {
        $this->encuesta = new tab_encuesta();
        $this->encuesta->setRequest2Object($_REQUEST);
        $page = $_REQUEST['page'];
        $rp = $_REQUEST['rp'];
        $sortname = $_REQUEST['sortname'];
        $sortorder = $_REQUEST['sortorder'];
        if (!$sortname){
            $sortname = ' ts.enc_id';
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
            if ($qtype == 'enc_id') {
                $where = " AND ts.enc_id = '$query' ";
            } elseif ($qtype == 'uni_descripcion') {
                $where = " AND tab_unidad.uni_id IN (SELECT uni_id from tab_unidad WHERE uni_descripcion like '%$query%') ";
            } elseif ($qtype == 'enc_par') {
                $where = " AND ts.enc_id IN (SELECT enc_id from tab_encuesta WHERE enc_categoria like '%$query%') ";
            } else {
                $where = " AND $qtype like '%$query%' ";
            }
        }

        $sql = "SELECT
                ts.enc_id,
                tab_unidad.uni_cod,
                tab_unidad.uni_descripcion,
                ts.enc_par,
                ts.enc_codigo,
                ts.enc_categoria,
                (SELECT enc_categoria from tab_encuesta WHERE enc_id=ts.enc_par) AS enc_parent,
                ts.enc_contador
                FROM
                tab_encuesta AS ts
                INNER JOIN tab_unidad ON tab_unidad.uni_id = ts.uni_id
                WHERE ts.enc_estado =  1 
                $where 
                $sort 
                $limit ";
        $result = $this->encuesta->dbselectBySQL($sql);
        $total = $this->encuesta->countBySQL("SELECT COUNT(ts.enc_id)
                                            FROM
                                            tab_encuesta AS ts
                                            INNER JOIN tab_unidad ON tab_unidad.uni_id = ts.uni_id
                                            WHERE ts.enc_estado = 1 
                                            $where ");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
        header("Cache-Control: no-cache, must-revalidate" );
        header("Pragma: no-cache" );
        header("Content-type: text/x-json");
        $json = "";
        $json .= "{\n";
        $json .= "page: $page,\n";
        $json .= "total: $total,\n";
        $json .= "rows: [";
        $rc = false;
$i = 0;
        foreach ($result as $un) {
            if ($rc)
                $json .= ",";
            $json .= "\n{";
            $json .= "id:'" . $un->enc_id . "',";
            $json .= "cell:['" . $un->enc_id . "'";
            $json .= ",'" . addslashes($un->enc_codigo) . "'";
            $json .= ",'" . addslashes($un->enc_categoria) . "'";            
            $json .= ",'" . addslashes($un->uni_descripcion) . "'";
            
            $json .= "]}";
            $rc = true;
            $i++;
        }
        $json .= "]\n";
        $json .= "}";
        echo $json;
    }

    function add() {
        $this->usuario = new usuario ();
        $adm = $this->usuario->esAdm();
        $this->registry->template->adm = $adm;
        
        $departamento = new departamento();
        $this->registry->template->dep_id = $departamento->obtenerSelect();

        $unidad = new unidad();
        $this->registry->template->uni_id = $unidad->obtenerSelect();
        $this->registry->template->enc_id = "";
        $this->registry->template->enc_par = "";
        $this->registry->template->enc_categoria = "";
        $this->registry->template->enc_codigo = "0";

        $this->menu = new menu();
        $this->liMenu = $this->menu->imprimirMenu(VAR1, $_SESSION['USU_ID']);
        $this->registry->template->men_titulo = $this->liMenu;
        $this->registry->template->PATH_WEB = PATH_WEB;
        $this->registry->template->PATH_DOMAIN = PATH_DOMAIN;
        $this->registry->template->PATH_EVENT = "save";
        $this->registry->template->GRID_SW = "false";
        $this->registry->template->PATH_J = "jquery-1.4.1";

        $this->registry->template->show('header');
        $this->registry->template->show('encuesta/tab_encuesta.tpl');
        $this->registry->template->show('footer');
    }

    function save() {
        $this->encuesta = new tab_encuesta ();
        $tencuesta = new tab_encuesta();
        $tencuesta->setRequest2Object($_REQUEST);
        $id_encie = 0;
        $tencuesta->setEnc_id($_REQUEST['enc_id']);
        $tencuesta->setUni_id($_REQUEST['uni_id']);
        $tencuesta->setEnc_par($_REQUEST['enc_par']);
        $tencuesta->setEnc_categoria($_REQUEST['enc_categoria']);
        $tencuesta->setEnc_contador(1);
        $tencuesta->setEnc_estado(1);
        $id_encie = $tencuesta->insert2();

        Header("Location: " . PATH_DOMAIN . "/encuesta/");
    }


    function edit() {
        Header("Location: " . PATH_DOMAIN . "/encuesta/view/" . $_REQUEST["enc_id"] . "/");
    }

    function view() {
        if(! VAR3){ die("Error del sistema 404"); }
        $this->usuario = new usuario ();
        $adm = $this->usuario->esAdm();        
        $this->registry->template->adm = $adm;
        
        $this->encuesta = new tab_encuesta();
        $this->encuesta->setRequest2Object($_REQUEST);
        $row = $this->encuesta->dbselectByField("enc_id", VAR3);
        $row = $row[0];

        $this->registry->template->enc_id = $row->enc_id;
        $this->registry->template->enc_codigo = $row->enc_codigo;
        $unidad = new unidad();
        $this->registry->template->uni_id = $unidad->listUnidad($row->uni_id);        
        $this->registry->template->enc_categoria = utf8_encode($row->enc_categoria);
        $this->registry->template->enc_contador = $row->enc_contador;                

        $this->registry->template->titulo = "Editar ";
        $this->registry->template->PATH_WEB = PATH_WEB;
        $this->registry->template->PATH_DOMAIN = PATH_DOMAIN;
        $this->registry->template->PATH_EVENT = "update";
        $this->registry->template->GRID_SW = "true";
        $this->registry->template->PATH_J = "jquery-1.4.1";
        $this->menu = new menu();
        $this->liMenu = $this->menu->imprimirMenu(VAR1, $_SESSION['USU_ID']);
        $this->registry->template->men_titulo = $this->liMenu;

        $this->registry->template->show('headerF');
        $this->registry->template->show('encuesta/tab_encuesta.tpl');
        $this->registry->template->show('footer');
    }

    function update() {
        $tencuesta = new tab_encuesta();
        $tencuesta->setRequest2Object($_REQUEST);
        $enc_id = $_REQUEST['enc_id'];
        $rows = $tencuesta->dbselectByField("enc_id", $enc_id);
        $rows = $rows[0];
        $tencuesta->setEnc_id($rows->enc_id);
        $tencuesta->setUni_id($_REQUEST['uni_id']);
        $tencuesta->setEnc_codigo($_REQUEST['enc_codigo']);
        if ($_REQUEST['enc_par']) {
            $tencuesta->setEnc_par($_REQUEST['enc_par']);
        } else {
            $tencuesta->setEnc_par(-1);
        }
        $tencuesta->setEnc_categoria($_REQUEST['enc_categoria']);
        $tencuesta->setEnc_estado(1);
        $tencuesta->update();


        Header("Location: " . PATH_DOMAIN . "/encuesta/");
    }

    function delete() {
        if (isset($_REQUEST)) {
            $enc_id = $_REQUEST['enc_id'];
            $this->encuesta = new tab_encuesta();
            $this->encuesta->setRequest2Object($_REQUEST);

            $this->encuesta->setEnc_id($enc_id);
            $this->encuesta->setEnc_estado(2);
            $this->encuesta->update();

            $st = new encietramite();
            $st->delete($enc_id);
            $use = new usu_encuesta();
            $use->deleteXEncie($enc_id);
            $ret = new retdocumental();
            $ret->deleteXEncie($enc_id);
            echo "OK";
        }
    }

    function validaDepen() {
        $encuesta = new encuesta();
        $swDepen = $encuesta->validaDependencia($_REQUEST['enc_id']);
        if ($swDepen != 0) {
            echo 'No se puede eliminar la encie ! \nTiene encuestas o documentos';
        } else {
            echo '';
        }
    }

    function verifTramite() {
        $tramitec = new tab_encietramite();
        $tramite = $_REQUEST['enc_id'];
        $num = $tramitec->countBySQL("SELECT COUNT(enc_id) as num FROM tab_encietramite  WHERE enc_id='$tramite' ");
        if ($num > 0)
            echo true;
        else
            echo false;
    }

    function loadAjaxencuesta() {
        $where = "";        
        if ($_POST["Uni_id"])
        {
            $uni_id = $_POST["Uni_id"];
            $where = " AND tab_encuesta.uni_id =  '$uni_id' ";
        }        
        $sql = "SELECT
                enc_id,
                enc_par,
                enc_nivel,
                enc_categoria
		FROM
		tab_encuesta
		WHERE
                tab_encuesta.enc_estado =  '1' 
                $where 
                ORDER BY enc_orden, 
                enc_codigo ";
        $tab_encuesta = new tab_encuesta();
        $result = $tab_encuesta->dbSelectBySQL($sql);
        $res = array();
        $encuesta = new encuesta();
        foreach ($result as $row) {
            if ($row->enc_par == '-1') {
                $res[$row->enc_id] = $row->enc_categoria;
            } else {
                $spaces = $encuesta->getSpaces($row->enc_nivel);
                $res[$row->enc_id] = $spaces . " " . $row->enc_categoria;
            }
        }
        echo json_encode($res);
    }

    function loadAjaxencuestaAll() {
        $sql = "SELECT
                enc_id,
                enc_par,
                enc_nivel,
                enc_categoria
		FROM
		tab_encuesta
		WHERE
                tab_encuesta.enc_estado =  '1' 
                ORDER BY enc_orden, 
                enc_codigo ";
        $tab_encuesta = new tab_encuesta();
        $result = $tab_encuesta->dbSelectBySQL($sql);
        $res = array();
        $encuesta = new encuesta();
        foreach ($result as $row) {
            if ($row->enc_par == '-1') {
                $res[$row->enc_id] = $row->enc_categoria;
            } else {
                $spaces = $encuesta->getSpaces($row->enc_nivel);
                $res[$row->enc_id] = $spaces . " " . $row->enc_categoria;
            }
        }
        echo json_encode($res);
    }
    
    
    function loadAjax() {
        $res = array();
        $enc_id = $_POST["Enc_id"];
        $encuesta = new tab_encuesta();
        $sql = "SELECT
                    enc_id,
                    enc_codigo,
                    enc_contador
                    FROM
                    tab_encuesta
                    WHERE (enc_estado = '1' AND enc_id='$enc_id')";
        $result = $encuesta->dbSelectBySQL($sql);
        foreach ($result as $row) {
            $res['enc_codigo'] = $row->enc_codigo;
            $res['enc_contador'] = sprintf("%02d", $row->enc_contador + 1);
        }

        echo json_encode($res);
    }

        
}

?>
