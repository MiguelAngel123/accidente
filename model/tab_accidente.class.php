<?php

/**
 * tab_usuario.class.php Class
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.14
 * @access public
 */
class Tab_accidente extends db {

    var $acc_id;
    var $acc_descripcion;

    function __construct() {
        parent::__construct();
    }

    function getAcc_id() {
        return $this->acc_id;
    }

    function setAcc_id($usu_id) {
        $this->usu_id = $usu_id;
    }
	
	function getAcc_descripcion() {
        return $this->acc_descripcion;
    }

    function setAcc_descripcion($acc_descripcion) {
        $this->acc_descripcion = $acc_descripcion;
    }
   
}

?>