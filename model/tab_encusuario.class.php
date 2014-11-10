<?php

/**
 * tab_encusuario.class.php Class
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.24
 * @access public
 */
class tab_encusuario extends db {

    var $eus_id;
    var $usu_id;
    var $res_id;
    var $eus_estado;

    function __construct() {
        parent::__construct();
    }

    function getEus_id() {
        return $this->eus_id;
    }

    function setEus_id($eus_id) {
        $this->eus_id = $eus_id;
    }

    function getUsu_id() {
        return $this->usu_id;
    }

    function setUsu_id($usu_id) {
        $this->usu_id = $usu_id;
    }

    function getRes_id() {
        return $this->res_id;
    }

    function setRes_id($res_id) {
        $this->res_id = $res_id;
    }

    function getEus_estado() {
        return $this->eus_estado;
    }

    function setEus_estado($eus_estado) {
        $this->eus_estado = $eus_estado;
    }


}

?>