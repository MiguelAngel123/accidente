<?php

/**
 * tab_respuesta.class.php Class
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.24
 * @access public
 */
class tab_respuesta extends db {

    var $res_id;
    var $enc_id;
    var $res_codigo; 
    var $res_titulo;    
    var $res_estado;  
    
    
    function __construct() {
        parent::__construct();
    }

    function getRes_id() {
        return $this->res_id;
    }

    function setRes_id($res_id) {
        $this->res_id = $res_id;
    }

    function getEnc_id() {
        return $this->enc_id;
    }

    function setEnc_id($enc_id) {
        $this->enc_id = $enc_id;
    }

    function getRes_codigo() {
        return $this->res_codigo;
    }

    function setRes_codigo($res_codigo) {
        $this->res_codigo = $res_codigo;
    }    
        
    function getRes_titulo() {
        return $this->res_titulo;
    }

    function setRes_titulo($res_titulo) {
        $this->res_titulo = $res_titulo;
    }    

    
    function getRes_estado() {
        return $this->res_estado;
    }

    function setRes_estado($res_estado) {
        $this->res_estado = $res_estado;
    }
    
    
}

?>