<?php

/**
 * tab_encgrupo.class.php Class
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.14
 * @access public
 */
class Tab_encgrupo extends db {

    var $egr_id;
    var $egr_codigo;
    var $egr_nombre;    
    var $egr_estado;

    function __construct() {
        parent::__construct();
    }

    function getEgr_id() {
        return $this->rol_id;
    }

    function setEgr_id($rol_id) {
        $this->rol_id = $rol_id;
    }

    function getEgr_codigo() {
        return $this->rol_codigo;
    }

    function setEgr_codigo($rol_codigo) {
        $this->rol_codigo = $rol_codigo;
    }
    
    
    function getEgr_nombre() {
        return $this->rol_nombre;
    }

    function setEgr_nombre($rol_nombre) {
        $this->rol_nombre = $rol_nombre;
    }

    function getEgr_estado() {
        return $this->rol_estado;
    }

    function setEgr_estado($rol_estado) {
        $this->rol_estado = $rol_estado;
    }

}

?>