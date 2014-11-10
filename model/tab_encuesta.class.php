<?php

/**
 * tab_encuesta.class.php Class
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.14
 * @access public
 */
class tab_encuesta extends db {

    var $enc_id;
    var $uni_id;
    var $enc_par;
    var $enc_codigo;
    var $enc_categoria;
    var $enc_contador;
    var $enc_estado;
    
    
    function __construct() {
        parent::__construct();
    }

    function getEnc_id() {
        return $this->enc_id;
    }

    function setEnc_id($enc_id) {
        $this->enc_id = $enc_id;
    }

    function getUni_id() {
        return $this->uni_id;
    }

    function setUni_id($uni_id) {
        $this->uni_id = $uni_id;
    }
        
    function getEnc_par() {
        return $this->enc_par;
    }

    function setEnc_par($enc_par) {
        $this->enc_par = $enc_par;
    }    
    
    function getEnc_codigo() {
        return $this->enc_codigo;
    }

    function setEnc_codigo($enc_codigo) {
        $this->enc_codigo = $enc_codigo;
    }
    
    function getEnc_categoria() {
        return $this->enc_categoria;
    }
    
    function setEnc_categoria($enc_categoria) {
        $this->enc_categoria = $enc_categoria;
    }

    function getEnc_contador() {
        return $this->enc_contador;
    }

    function setEnc_contador($enc_contador) {
        $this->enc_contador = $enc_contador;
    } 
    
    function getEnc_estado() {
        return $this->enc_estado;
    }

    function setEnc_estado($enc_estado) {
        $this->enc_estado = $enc_estado;
    }    
    
   
}

?>