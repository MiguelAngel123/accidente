<?php

/**
 * encgrupo.class.php Model
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.14
 * @access public
 */
class encgrupo extends tab_encgrupo {

    function __construct() {
        $this->rol = new Tab_rol();
    }

    function obtenerSelect($default = null) {
        $sql = "SELECT egr_id,
                egr_nombre
                FROM tab_encgrupo
                WHERE egr_estado = '1' 
                ORDER BY egr_id ";
        $rows = $this->rol->dbSelectBySQL($sql);
        $option = "";
        if (count($rows)) {
            foreach ($rows as $val) {
                if ($default == $val->egr_id)
                    $selected = "selected";
                else
                    $selected = "";
                $option .="<option value='" . $val->egr_id . "' " . $selected . ">" . $val->egr_nombre . "</option>";
            }
        }
        return $option;
    }

}

?>
