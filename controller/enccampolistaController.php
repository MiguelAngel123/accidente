<?php

/**
 * enccampolistaController.php Controller
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.24
 * @access public
 */
class enccampolistaController extends baseController {

    function index() {
        $this->enccampo = new tab_enccampo();        
        $sql = "SELECT
                tab_encuesta.enc_id,
                tab_enccampo.ecp_id,
                tab_enccampo.ecp_nombre
                FROM
                tab_encuesta
                INNER JOIN tab_enccampo ON tab_enccampo.enc_id = tab_encuesta.enc_id
                WHERE tab_enccampo.ecp_id = " . VAR3 . " 
                AND tab_enccampo.ecp_estado = 1 ";        
        $resul = $this->enccampo->dbselectBySQL($sql);
        if (count($resul)) {
            $codigo = $resul[0]->ecp_nombre;
            $ecp_id = $resul[0]->ecp_id;
            $enc_id = $resul[0]->enc_id;
        }
        else{
            $codigo = "";
            $ecp_id = 0;
            $enc_id = 0;
        }
        $this->registry->template->ecl_id = "";
        $this->registry->template->ecp_id = $ecp_id;
        $this->registry->template->enc_id = $enc_id;
        $this->registry->template->ecp_tipdat = $codigo;
              
        $this->registry->template->PATH_WEB = PATH_WEB;
        $this->registry->template->PATH_DOMAIN = PATH_DOMAIN;
        $this->registry->template->PATH_EVENT = "add";
        $this->registry->template->GRID_SW = "false";
        $this->registry->template->FORM_SW = "display:none;";
        $this->registry->template->PATH_J = "jquery";
        $this->menu = new menu ();
        $this->liMenu = $this->menu->imprimirMenu(VAR1, $_SESSION ['USU_ID']);
        $this->registry->template->men_titulo = $this->liMenu;
        $this->registry->template->show('headerG');
        $this->registry->template->show('encuesta/tab_enccampolistag.tpl');
        $this->registry->template->show('footer');        
    }

    function load() {
        $enccampolista = new enccampolista();
        $this->enccampolista = new tab_enccampolista ();
        $this->enccampolista->setRequest2Object($_REQUEST);
        $page = $_REQUEST['page'];
        $rp = $_REQUEST['rp'];
        $sortname = $_REQUEST['sortname'];
        $sortorder = $_REQUEST['sortorder'];
        if (!$sortname)
            $sortname = 'ecl_id';
        if (!$sortorder)
            $sortorder = 'desc';
        $sort = "ORDER BY $sortname $sortorder";
        if (!$page)
            $page = 1;
        if (!$rp)
            $rp = 15;
        $start = (($page - 1) * $rp);
        $limit = "LIMIT $rp OFFSET $start";
        $query = $_REQUEST['query'];
        $qtype = $_REQUEST['qtype'];
        $where = "";
        if ($query) {
            if ($qtype == 'ecl_id')
                $where = " WHERE $qtype = '$query' ";
            else
                $where = " WHERE $qtype LIKE '%$query%' ";
        }
        
        if (VAR3!=""){
            $sql = "SELECT
                    tab_enccampolista.ecp_id,
                    tab_enccampolista.ecl_id,
                    tab_enccampolista.ecl_orden,
                    tab_enccampolista.ecl_valor,
                    tab_enccampolista.ecl_estado
                    FROM
                    tab_enccampo
                    INNER JOIN tab_enccampolista ON tab_enccampo.ecp_id = tab_enccampolista.ecp_id
                    WHERE tab_enccampolista.ecp_id = " . VAR3 . " 
                    AND tab_enccampolista.ecl_estado = 1
                    $where
                    $sort 
                    $limit";                
        }

        $result = $this->enccampolista->dbselectBySQL($sql);
        $total = $this->enccampolista->countBySQL("SELECT COUNT(tab_enccampolista.ecp_id)
                                            FROM
                                            tab_enccampo
                                            INNER JOIN tab_enccampolista ON tab_enccampo.ecp_id = tab_enccampolista.ecp_id
                                            WHERE tab_enccampolista.ecp_id = " . VAR3 . " 
                                            AND tab_enccampolista.ecl_estado = 1
                                            $where ");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
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
            $json .= "id:'" . $un->ecl_id . "',";
            $json .= "cell:['" . $un->ecl_id . "'";
            $json .= ",'" . addslashes(utf8_encode($un->ecl_orden)) . "'";
            $json .= ",'" . addslashes(utf8_encode($un->ecl_valor)) . "'";
            $json .= ",'" . addslashes(utf8_encode($un->ecl_estado)) . "'";
            $json .= "]}";
            $rc = true;
            $i++;
        }
        $json .= "]\n";
        $json .= "}";
        echo $json;
    }
    
    function add() {
        $this->enccampo = new tab_enccampo();
        $sql = "SELECT ecp_nombre
                FROM tab_enccampo 
                WHERE ecp_id = " . VAR3;
        $resul = $this->enccampo->dbselectBySQL($sql);
        if (count($resul))
            $codigo = $resul[0]->ecp_nombre;
        else
            $codigo = "";

        $this->registry->template->titulo = "Nuevo valor de lista";
        $this->registry->template->ecp_nombre = $codigo;        
        $this->registry->template->ecp_id = VAR3;        
        $this->registry->template->ecl_id = "";
        $this->registry->template->ecl_orden = "";
        $this->registry->template->ecl_valor = "";
        $this->registry->template->titulo = "Nuevo ";
        $this->registry->template->PATH_WEB = PATH_WEB;
        $this->registry->template->PATH_DOMAIN = PATH_DOMAIN;
        $this->registry->template->PATH_EVENT = "save";
        $this->registry->template->GRID_SW = "false";
        $this->registry->template->PATH_J = "jquery";

        $this->menu = new menu ();
        $this->liMenu = $this->menu->imprimirMenu(VAR1, $_SESSION ['USU_ID']);
        $this->registry->template->men_titulo = $this->liMenu;
        $this->registry->template->show('headerF');
        $this->registry->template->show('encuesta/tab_enccampolista.tpl');
        $this->registry->template->show('footer');
    }

    function save() {
        $this->enccampolista = new tab_enccampolista ();
        $this->enccampolista->setRequest2Object($_REQUEST);        
        $this->enccampolista->setEcl_id($_REQUEST['ecl_id']);
        $this->enccampolista->setEcl_orden($_REQUEST['ecl_orden']);
        $this->enccampolista->setEcp_id($_REQUEST['ecp_id']);
        $this->enccampolista->setEcl_valor($_REQUEST['ecl_valor']);
//        $this->enccampolista->setEcl_fecha_crea(date("Y-m-d"));
//        $this->enccampolista->setEcl_usuario_crea($_SESSION ['USU_ID']);
        $this->enccampolista->setEcl_estado(1);
        $ecl_id = $this->enccampolista->insert();
        
        Header("Location: " . PATH_DOMAIN . "/enccampolista/index/" . $_REQUEST['ecp_id'] . "/");
        //Header("Location: " . PATH_DOMAIN . "/enccampolista/");
    }    
    
    function edit() {
        Header("Location: " . PATH_DOMAIN . "/enccampolista/view/" . $_REQUEST["ecl_id"] . "/");
    }

    function view() {    
    if(! VAR3){ die("Error del sistema 404"); }
        $this->enccampo = new tab_enccampo();
        $sql = "SELECT
                tab_enccampo.ecp_id,                
                tab_enccampo.ecp_nombre,
                tab_enccampolista.ecl_id
                FROM
                tab_enccampolista
                INNER JOIN tab_enccampo ON tab_enccampo.ecp_id = tab_enccampolista.ecp_id
                WHERE tab_enccampolista.ecl_id = " . VAR3;
        $resul = $this->enccampo->dbselectBySQL($sql);
        if(! $resul){ die("Error del sistema 404"); }
        if (count($resul))
            $codigo = $resul[0]->ecp_nombre;
        else
            $codigo = "";
        
        
        $this->enccampolista = new tab_enccampolista ();
        $this->enccampolista->setRequest2Object($_REQUEST);
        $ecl_id = VAR3;
        $row = $this->enccampolista->dbselectByField("ecl_id", $ecl_id);
        $row = $row [0];
        
        $this->registry->template->ecp_nombre = $codigo;        
        $this->registry->template->ecp_id = $row->ecp_id;
        $this->registry->template->ecl_id = $row->ecl_id;
        $this->registry->template->ecl_orden = $row->ecl_orden;
        $this->registry->template->ecl_valor = $row->ecl_valor;
        $this->registry->template->ecl_estado = $row->ecl_estado;
        $this->registry->template->titulo = "Editar ";

        $this->registry->template->PATH_WEB = PATH_WEB;
        $this->registry->template->PATH_DOMAIN = PATH_DOMAIN;
        $this->registry->template->PATH_EVENT = "update";
        $this->registry->template->GRID_SW = "true";
        $this->registry->template->PATH_J = "jquery";
        $this->menu = new menu ();
        $this->liMenu = $this->menu->imprimirMenu(VAR1, $_SESSION ['USU_ID']);
        $this->registry->template->men_titulo = $this->liMenu;
        $this->registry->template->show('headerF');
        $this->registry->template->show('encuesta/tab_enccampolista.tpl');
        $this->registry->template->show('footer');
    }

    function update() {
        $this->enccampolista = new tab_enccampolista ();
        $this->enccampolista->setRequest2Object($_REQUEST);
        $ecl_id = $_REQUEST['ecl_id'];
        $this->enccampolista->setEcl_id($ecl_id);  
        $this->enccampolista->setEcl_orden($_REQUEST['ecl_orden']);  
        $this->enccampolista->setEcp_id($_REQUEST['ecp_id']);  
        $this->enccampolista->setEcl_valor($_REQUEST['ecl_valor']);        
//        $this->enccampolista->setEcl_fecha_mod(date("Y-m-d"));
//        $this->enccampolista->setEcl_usuario_mod($_SESSION ['USU_ID']);
        $this->enccampolista->update();
        
        Header("Location: " . PATH_DOMAIN . "/enccampolista/index/" . $_REQUEST['ecp_id'] . "/");
    }

    function delete() {
        $tenccampolista = new tab_enccampolista();
        $tenccampolista->setRequest2Object($_REQUEST);

        $tenccampolista->setEcl_id($_REQUEST['ecl_id']);
        $tenccampolista->setEcl_estado(2);
//        $tenccampolista->setEcl_fecha_mod(date("Y-m-d"));
//        $tenccampolista->setEcl_usuario_mod($_SESSION['USU_ID']);
        $tenccampolista->update();
    }

}

?>
