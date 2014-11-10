<?php

/**
 * sistema.class.php Model
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.14
 * @access public
 */
class sistema extends tab_sistema {

    function __construct() {
        $this->sistema = new tab_sistema();
    }

    function obtenerSelect($default = null) {
        $option = "";
        if ($default == '1')
            $option .="<option value='1' selected>BD</option><option value='2'>SERVIDOR</option>";
        else
            $option .="<option value='1'>BD</option><option value='2' selected>SERVIDOR</option>";

        return $option;
    }
    
}

?>