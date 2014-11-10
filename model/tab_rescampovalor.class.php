<?php

/**
 * tab_rescampovalor.class.php Class
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.24
 * @access public
 */
class tab_rescampovalor extends db {
    
    var $rcv_id;
    var $res_id;
    var $ecp_id;
    var $ecl_id;
    var $rcv_valor;
    var $rcv_estado;

    function __construct() {
        parent::__construct();
    }

    function getRcv_id() {
        return $this->rcv_id;
    }

    function setRcv_id($rcv_id) {
        $this->rcv_id = $rcv_id;
    }

    function getRes_id() {
        return $this->res_id;
    }

    function setRes_id($res_id) {
        $this->res_id = $res_id;
    }
    
    function getEcp_id() {
        return $this->ecp_id;
    }

    function setEcp_id($ecp_id) {
        $this->ecp_id = $ecp_id;
    }
    
    function getEcl_id() {
        return $this->ecl_id;
    }

    function setEcl_id($ecl_id) {
        $this->ecl_id = $ecl_id;
    }

    function getRcv_valor() {
        return $this->rcv_valor;
    }

    function setRcv_valor($rcv_valor) {
        $this->rcv_valor = $rcv_valor;
    }
    
    function getRcv_estado() {
        return $this->rcv_estado;
    }

    function setRcv_estado($rcv_estado) {
        $this->rcv_estado = $rcv_estado;
    }

}

?>