<?php

/**
 * enccampolista.class.php Model
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.24
 * @access public
 */
class enccampolista extends tab_enccampolista {

    function __construct() {
        $this->enccampolista = new tab_enccampolista();
    }

    function obtenerSelect($default = null) {
        $sql = "SELECT *
                FROM tab_enccampolista
                WHERE tab_enccampolista.ecl_estado = 1
                ORDER BY ecl_id ASC ";
        $row = $this->enccampolista->dbselectBySQL($sql);
        $option = "";
        foreach ($row as $val) {
            if ($default == $val->ecl_id)
                $option .="<option value='" . $val->ecl_id . "' selected>" . $val->ecl_tipdat . "</option>";
            else
                $option .="<option value='" . $val->ecl_id . "'>" . $val->ecl_tipdat . "</option>";
        }
        return $option;
    }


}

?>