<?php

/**
 * tab_usu_encuesta.class.php Class
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.14
 * @access public
 */
class tab_usu_encuesta extends db {

    var $uen_id;
    var $usu_id;
    var $enc_id;
    var $uen_estado;

    function __construct() {
        parent::__construct();
    }

    function getUen_id() {
        return $this->uen_id;
    }

    function setUen_id($uen_id) {
        $this->uen_id = $uen_id;
    }

    function getUsu_id() {
        return $this->usu_id;
    }

    function setUsu_id($usu_id) {
        $this->usu_id = $usu_id;
    }

    function getEnc_id() {
        return $this->enc_id;
    }

    function setEnc_id($enc_id) {
        $this->enc_id = $enc_id;
    }

    function getUen_estado() {
        return $this->uen_estado;
    }

    function setUen_estado($uen_estado) {
        $this->uen_estado = $uen_estado;
    }

}

?>