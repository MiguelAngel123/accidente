<?php

/**
 * rescampovalor.class.php Model
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.24
 * @access public
 */
class rescampovalor extends tab_rescampovalor {

    function __construct() {
        $this->rescampovalor = new tab_rescampovalor();
    }

    function obtenerSelect($default = null) {
        $sql = "SELECT *
                FROM tab_rescampovalor
                WHERE tab_rescampovalor.rcv_estado = 1
                ORDER BY rcv_id ASC ";
        $row = $this->rescampovalor->dbselectBySQL($sql);
        $option = "";
        foreach ($row as $val) {
            if ($default == $val->rcv_id)
                $option .="<option value='" . $val->rcv_id . "' selected>" . $val->rcv_valor . "</option>";
            else
                $option .="<option value='" . $val->rcv_id . "'>" . $val->rcv_valor . "</option>";
        }
        return $option;
    }

    function obtenerIdCampoValor($ecp_id, $rcv_valor) {
        $row = "";
        $this->rescampovalor = new tab_rescampovalor();
        $row = $this->rescampovalor->dbselectBySQL("SELECT
                    ecl_id
                    FROM
                    tab_tab_rescampovalor
                    WHERE
                    rcv_estado = 1
                    AND ecp_id = '$ecp_id'
                    AND rcv_valor = '$rcv_valor' ");
        if (count($row) > 0)
            return $row[0]->ecl_id;
        else
            return 0;
    }

    function obtenerIdCampoValorporencuesta($ecp_id, $res_id) {
        $row = "";
        $this->rescampovalor = new tab_rescampovalor();
        $row = $this->rescampovalor->dbselectBySQL("SELECT
                    rcv_id
                    FROM
                    tab_rescampovalor
                    WHERE
                    rcv_estado = 1
                    AND ecp_id = '$ecp_id'
                    AND res_id = '$res_id' ");
        if (count($row) > 0)
            return $row[0]->rcv_id;
        else
            return 0;
    }

}

?>