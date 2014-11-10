<?php

/**
 * tab_rol.class.php Class
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.14
 * @access public
 */
class Tab_rol extends db {

    var $rol_id;
    var $rol_titulo;
    var $rol_descripcion;
    var $rol_cod;
    var $rol_estado;
    var $nuevo; 

        public function getNuevo() {
        return $this->nuevo;
    }

    public function setNuevo($nuevo) {
        $this->nuevo = $nuevo;
    }

    
    function __construct() {
        parent::__construct();
    }

    function getRol_id() {
        return $this->rol_id;
    }

    function setRol_id($rol_id) {
        $this->rol_id = $rol_id;
    }

    function getRol_titulo() {
        return $this->rol_titulo;
    }

    function setRol_titulo($rol_titulo) {
        $this->rol_titulo = $rol_titulo;
    }

    function getRol_descripcion() {
        return $this->rol_descripcion;
    }

    function setRol_descripcion($rol_descripcion) {
        $this->rol_descripcion = $rol_descripcion;
    }

    function getRol_cod() {
        return $this->rol_cod;
    }

    function setRol_cod($rol_cod) {
        $this->rol_cod = $rol_cod;
    }


    function getRol_estado() {
        return $this->rol_estado;
    }

    function setRol_estado($rol_estado) {
        $this->rol_estado = $rol_estado;
    }

}

?>