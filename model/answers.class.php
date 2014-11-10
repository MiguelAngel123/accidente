<?php

/**
 * answers.class.php Model
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.14
 * @access public
 */
class answers extends tab_answers {

    function __construct() {
        $this->answers = new tab_answers();
    }

    function obtenerSelect($default = null) {
        $sql = "SELECT aid
                FROM tab_answers ";
        $rows = $this->answers->dbSelectBySQL($sql);
        $option = "";
        if (count($rows)) {
            foreach ($rows as $val) {
                if ($default == $val->aid)
                    $selected = "selected";
                else
                    $selected = "";
                $option .="<option value='" . $val->aid . "' " . $selected . ">" . $val->aid . "</option>";
            }
        }
        return $option;
    }

}

?>
