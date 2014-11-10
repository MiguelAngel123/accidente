<?php

/**
 * usu_encuesta.class.php Model
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.14
 * @access public
 */
class usu_encuesta extends tab_usu_encuesta {

    function __construct() {
        $this->usu_encuesta = new tab_usu_encuesta();
    }

    function existe($serie, $usu_id) {
        $rows = $this->usu_encuesta->dbselectBy2Field("enc_id", $serie, "usu_id", $usu_id);
        if (count($rows) > 0) {
            return $rows[0]->use_id;
        } else {
            return false;
        }
    }

    function deleteXSerie($enc_id) {
        $del = "DELETE FROM tab_usu_encuesta 
                WHERE enc_id = '$enc_id' 
                AND uen_estado = '1' ";
        $this->usu_encuesta->dbBySQL($del);
    }

    function deleteXUsuario($usu_id) {
        $del = "DELETE FROM tab_usu_encuesta 
                WHERE usu_id = '$usu_id' 
                AND uen_estado = '1' ";
        $this->usu_encuesta->dbBySQL($del);
    }

    function tieneencuesta($usu_id) {
        $sql = "SELECT COUNT(us.uen_id)
                FROM
                tab_usu_encuesta AS us
                Inner Join tab_encuesta AS s ON s.enc_id = us.enc_id
                WHERE
                us.uen_estado =  '1' AND
                us.usu_id =  '$usu_id' ";
        $num = $this->usu_encuesta->countBySQL($sql);
        if ($num > 0) {
            return 'OK';
        } else {
            return false;
        }
    }

}

?>