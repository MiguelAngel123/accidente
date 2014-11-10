<?php

/**
 * idioma.class.php Model
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.24
 * @access public
 */
class idioma extends tab_idioma {

    function __construct() {
        $this->idioma = new tab_idioma();
    }

    function obtenerSelect($default = null) {
        $where = "";
        $sql = "select *
                from tab_idioma
                where tab_idioma.idi_estado = 1
                ORDER BY idi_id ASC ";
        $row = $this->idioma->dbselectBySQL($sql);
        $option = "";
        foreach ($row as $val) {
            if ($default == $val->idi_id)
                $option .="<option value='" . $val->idi_id . "' selected>" . $val->idi_nombre . "</option>";
            else
                $option .="<option value='" . $val->idi_id . "'>" . $val->idi_nombre . "</option>";
        }
        return $option;
    }
    
    function obtenerIdioma($default = null) {
        $sql = "SELECT idi_nombre
                FROM tab_idioma
                WHERE tab_idioma.idi_estado = 1
                AND idi_id = '$default'
                ORDER BY idi_id ASC ";
        $row = $this->idioma->dbselectBySQL($sql);
        $option = "";
        foreach ($row as $val) {
            $option = $val->idi_nombre;
        }
        return $option;
    }    


}

?>