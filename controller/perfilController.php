<?php

/**
 * perfilController
 *
 * @package
 * @author lic. castellon
 * @copyright ITEAM
 * @version $Id$ 2014.04.24
 * @access public
 */
class perfilController Extends baseController {

    function index() {
        $this->registry->template->usu_id = "";
        $this->registry->template->PATH_WEB = PATH_WEB;
        $this->registry->template->PATH_DOMAIN = PATH_DOMAIN;
        $this->registry->template->PATH_EVENT = "add";
        $this->registry->template->GRID_SW = "false";
        $this->registry->template->FORM_SW = "display:none;";
        $this->registry->template->PATH_J = "jquery";
        $this->menu = new menu();
        $this->liMenu = $this->menu->imprimirMenu(VAR1, $_SESSION['USU_ID']);
        $this->registry->template->men_titulo = $this->liMenu;

        $this->registry->template->show('header');
        $this->registry->template->show('tab_usuariog.tpl');
    }

    function edit() {
        Header("Location: " . PATH_DOMAIN . "/perfil/view/" . $_REQUEST["usu_id"] . "/");
    }

    function view() {

        $this->usuario = new tab_usuario();
        $rows = $this->usuario->dbselectByField("usu_id", $_SESSION['USU_ID']);
        $row = $rows[0];
        $this->registry->template->usu_id = $row->usu_id;
        
        $this->registry->template->usu_nombres = $row->usu_nombres;
        $this->registry->template->usu_apellidos = $row->usu_apellidos;
        $this->registry->template->usu_fono = $row->usu_fono;
        $this->registry->template->usu_email = $row->usu_email;
        $this->registry->template->usu_nro_item = $row->usu_nro_item;

        $this->registry->template->usu_login = $row->usu_login;
        
        $this->menu = new menu();
        $this->liMenu = $this->menu->imprimirMenu(VAR1, $_SESSION['USU_ID']);
        $this->registry->template->men_titulo = $this->liMenu;

        $this->registry->template->titulo = "PERFIL DEL USUARIO";
        $this->registry->template->PATH_WEB = PATH_WEB;
        $this->registry->template->PATH_DOMAIN = PATH_DOMAIN;
        $this->registry->template->PATH_EVENT = "update";
        $this->registry->template->PATH_EVENT_DIALOG = "updatePass";
        $this->registry->template->PATH_J = "jquery";
        $this->registry->template->GRID_SW = "true";
        $this->registry->template->FORM_SW = "";
        $this->registry->template->show('header');
        $this->registry->template->show('usuario/tab_perfil.tpl');
    }

    function update() {
        $this->usuario = new tab_usuario();
        $this->usuario->setRequest2Object($_REQUEST);
        $this->usuario->update();
        $id = $this->usuario->usu_id;
        if ($_REQUEST['usu_email'] == '')
            $this->usuario->updateValue("usu_email", '', $id);
        if ($_REQUEST['usu_fono'] == '')
            $this->usuario->updateValue("usu_fono", '', $id);
        Header("Location: " . PATH_DOMAIN . "/login/show/");
    }

    function updatePass() {
        $this->usuario = new tab_usuario();
        $this->usuario->setRequest2Object($_REQUEST);
        $this->usuario->setUsu_id($_SESSION['USU_ID']);
        $this->usuario->setUsu_pass(md5($_REQUEST['pass3']));
        $this->usuario->update();
        Header("Location: " . PATH_DOMAIN . "/perfil/view/");
    }

}

?>
