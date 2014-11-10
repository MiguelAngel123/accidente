<?php

/**
 * encusuario.class.php Model
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2012
 * @access public
 */
class encusuario extends tab_encusuario {

    function __construct() {

    }

    function saveExp($exp_id, $usu_id) {

        $this->encusuario = new tab_encusuario();
        $this->encusuario->eus_id = '';
        $this->encusuario->exp_id = $exp_id;
        $this->encusuario->usu_id = $usu_id;
        $this->encusuario->eus_fecha_crea = date("Y-m-d");
        $this->encusuario->eus_estado = '1';
        return $this->encusuario->insert();
    }

}

?>
