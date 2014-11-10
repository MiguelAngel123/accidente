<?php

/**
 * enccampoController.php Controller
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2012
 * @access public
 */
class enccampoController extends baseController {

    function index() {
        $this->encuesta = new tab_encuesta();
        $sql = "SELECT 
                enc_categoria 
                FROM tab_encuesta 
                WHERE enc_id = " . VAR3;
        $resul = $this->encuesta->dbselectBySQL($sql);
        if (count($resul))
            $codigo = $resul[0]->enc_categoria;
        else
            $codigo = "";
        $this->registry->template->ecp_id = "";
        $this->registry->template->enc_categoria = $codigo;
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
        $this->registry->template->show('encuesta/tab_enccampog.tpl');
        $this->registry->template->show('footer');
    }

    function load() {
        $this->enccampo = new tab_enccampo ();
        $this->enccampo->setRequest2Object($_REQUEST);
        $page = $_REQUEST['page'];
        $rp = $_REQUEST['rp'];
        $sortname = $_REQUEST['sortname'];
        $sortorder = $_REQUEST['sortorder'];
        if (!$sortname)
            $sortname = ' tab_enccampo.egr_id, tab_enccampo.ecp_orden';
        if (!$sortorder)
            $sortorder = 'desc';
        $sort = "ORDER BY $sortname $sortorder";
        if (!$page)
            $page = 1;
        if (!$rp)
            $rp = 15;
        $start = (($page - 1) * $rp);
        $limit = "LIMIT $rp OFFSET $start ";
        $query = $_REQUEST['query'];
        $qtype = $_REQUEST['qtype'];
        $where = "";
        if ($query) {
            if ($qtype == 'ecp_id')
                $where = " where $qtype = '$query' ";
            else
                $where = " WHERE $qtype LIKE '%$query%' ";
        } 
        
        if (VAR3!=""){               
           $sql = "SELECT
                tab_encuesta.enc_id,
                tab_enccampo.ecp_id,
                tab_encgrupo.egr_nombre,
                tab_enccampo.ecp_orden,
                tab_enccampo.ecp_nombre,
                tab_enccampo.ecp_eti,
                tab_enccampo.ecp_tipdat,
                tab_enccampo.ecp_estado
                FROM
                tab_encuesta
                INNER JOIN tab_enccampo ON tab_enccampo.enc_id = tab_encuesta.enc_id
                INNER JOIN tab_encgrupo ON tab_encgrupo.egr_id = tab_enccampo.egr_id
                WHERE tab_encuesta.enc_id = " . VAR3 . " 
                AND tab_enccampo.ecp_estado = 1 
                $where
                $sort 
                $limit";                
        }            
        
        $result = $this->enccampo->dbselectBySQL($sql);
        $total = $this->enccampo->countBySQL("SELECT COUNT(tab_encuesta.enc_id)
                                            FROM
                                            tab_encuesta
                                            INNER JOIN tab_enccampo ON tab_enccampo.enc_id = tab_encuesta.enc_id
                                            WHERE tab_encuesta.enc_id = " . VAR3 . " 
                                            AND tab_enccampo.ecp_estado = 1
                                            $where ");
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
        foreach ($result as $un) {
            if ($rc)
                $json .= ",";
            $json .= "\n{";
            $json .= "id:'" . $un->ecp_id . "',";
            $json .= "cell:['" . $un->ecp_id . "'";
            $json .= ",'" . addslashes($un->egr_nombre) . "'";
            $json .= ",'" . addslashes($un->ecp_orden) . "'";
            $json .= ",'" . addslashes($un->ecp_nombre) . "'";
            $json .= ",'" . addslashes($un->ecp_eti) . "'";
            $json .= ",'" . addslashes($un->ecp_tipdat) . "'";
            $json .= ",'" . addslashes($un->ecp_estado) . "'";
            $json .= "]}";
            $rc = true;
            $i++;
        }
        $json .= "]\n";
        $json .= "}";
        echo $json;
    }
    
    function add() {
        
        
        
        $this->encuesta = new tab_encuesta();
        $sql = "SELECT * 
                FROM tab_encuesta 
                WHERE enc_id = " . VAR3;
        $resul = $this->encuesta->dbselectBySQL($sql);
        if (count($resul))
            $codigo2 = $resul[0]->enc_categoria;
        else
            $codigo2 = "";
       
        $this->registry->template->enc_id = VAR3;        
        $this->registry->template->enc_categoria = $codigo2;
        $this->registry->template->ecp_id = "";
        $encgrupo = new encgrupo();
        $this->registry->template->egr_id = $encgrupo->obtenerSelect();
        $this->registry->template->ecp_orden = "";
        $this->registry->template->ecp_nombre = "";
        $this->registry->template->ecp_eti = "";
        $enccampo = new enccampo();
        $this->registry->template->ecp_tipdat = $enccampo->obtenerSelectTipoDato();
        
        $this->registry->template->titulo = "NUEVO CAMPO encuesta ";
        $this->registry->template->PATH_WEB = PATH_WEB;
        $this->registry->template->PATH_DOMAIN = PATH_DOMAIN;
        $this->registry->template->PATH_EVENT = "save";
        $this->registry->template->GRID_SW = "false";
        $this->registry->template->PATH_J = "jquery-1.4.1";
        $this->menu = new menu ();
        $this->liMenu = $this->menu->imprimirMenu(VAR1, $_SESSION ['USU_ID']);
        $this->registry->template->men_titulo = $this->liMenu;
                               
        $this->registry->template->show('headerF');
        $this->registry->template->show('encuesta/tab_enccampo.tpl');
        $this->registry->template->show('footer');
    }

    function save() {
        $this->enccampo = new tab_enccampo ();
        $this->enccampo->setRequest2Object($_REQUEST);
        $this->enccampo->setEnc_id($_REQUEST['enc_id']);
        $this->enccampo->setEgr_id($_REQUEST['egr_id']);
        $this->enccampo->setEcp_orden($_REQUEST['ecp_orden']);
        $this->enccampo->setEcp_nombre($_REQUEST['ecp_nombre']);
        $this->enccampo->setEnc_id($_REQUEST['enc_id']);
        $this->enccampo->setEcp_eti($_REQUEST['ecp_eti']);
        $this->enccampo->setEcp_tipdat($_REQUEST['ecp_tipdat']);
        $this->enccampo->setEcp_estado(1);
        $ecp_id = $this->enccampo->insert();
        
        Header("Location: " . PATH_DOMAIN . "/enccampo/index/" . $_REQUEST['enc_id'] . "/");
        //Header("Location: " . PATH_DOMAIN . "/enccampo/");
    }
    
    function edit() {
        Header("Location: " . PATH_DOMAIN . "/enccampo/view/" . $_REQUEST["ecp_id"] . "/");
    }

    function view() {
        if(! VAR3){ die("Error del sistema 404"); }
        
        $enc_categoria = "";
        $this->enccampo = new tab_enccampo();
        $sql = "SELECT * 
                FROM tab_enccampo 
                WHERE ecp_id = " . VAR3;
        $resul = $this->enccampo->dbselectBySQL($sql);
        
        if(! $resul){ die("Error del sistema 404"); }
        
        if (count($resul)){
            $enc_id = $resul[0]->enc_id;
            $encuesta = new encuesta();
            $enc_categoria = $encuesta->getTitle($enc_id);                        
        }else
            $enc_id = "";        
        
        $this->enccampo->setRequest2Object($_REQUEST);
        $ecp_id = VAR3;
        $row = $this->enccampo->dbselectByField("ecp_id", $ecp_id);
        $row = $row [0];
        $this->registry->template->enc_categoria = $enc_categoria;
        $this->registry->template->enc_id = $enc_id;
        $encgrupo = new encgrupo();
        $this->registry->template->egr_id = $encgrupo->obtenerSelect($row->egr_id);
        
        $this->registry->template->ecp_id = $row->ecp_id;
        $this->registry->template->ecp_orden = $row->ecp_orden;
        $this->registry->template->ecp_nombre = $row->ecp_nombre;
        $this->registry->template->ecp_eti = $row->ecp_eti;
        $enccampo = new enccampo();
        $this->registry->template->ecp_tipdat = $enccampo->obtenerSelectTipoDato($row->ecp_tipdat);
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
        $this->registry->template->show('encuesta/tab_enccampo.tpl');
        $this->registry->template->show('footer');
    }



    function update() {
        $this->enccampo = new tab_enccampo ();
        $this->enccampo->setRequest2Object($_REQUEST);
        $enccampo_id = $_REQUEST['ecp_id'];
        $this->enccampo->setEcp_id($enccampo_id);
        $this->enccampo->setEcp_orden($_REQUEST['ecp_orden']);
        $this->enccampo->setEcp_nombre($_REQUEST['ecp_nombre']);
        $this->enccampo->setEnc_id($_REQUEST['enc_id']);
        $this->enccampo->setEcp_eti($_REQUEST['ecp_eti']);
        $this->enccampo->setEcp_tipdat($_REQUEST['ecp_tipdat']);
//        $this->enccampo->setEcp_fecha_mod(date("Y-m-d"));
//        $this->enccampo->setEcp_usuario_mod($_SESSION ['USU_ID']);
        $this->enccampo->update();

        Header("Location: " . PATH_DOMAIN . "/enccampo/index/" . $_REQUEST['enc_id'] . "/");
    }

    function delete() {
        $this->enccampo = new tab_enccampo ();
        $this->enccampo->setRequest2Object($_REQUEST);
        $ecp_id = $_REQUEST['ecp_id'];
        $this->enccampo->setEcp_id($ecp_id);
        $this->enccampo->setEcp_estado(2);
        $this->enccampo->update();

    }


}

?>