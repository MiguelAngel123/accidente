<?php

/**
 * usuario.class.php Model
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.14
 * @access public
 */
class accidente extends tab_accidente {

    function __construct() {
        $this->accidente = new tab_accidente();
    }

    function obtenerSelect($default = null) {
        $sql = "SELECT acc_id,
                acc_descripcion
                FROM tab_accidente";
        $row = $this->accidente->dbselectBySQL($sql);
        $option = "";
        foreach ($row as $val) {
            if ($default == $val->usu_id)
                $option .="<option value='" . $val->acc_id . "' selected>" . $val->acc_descripcion . "</option>";
            else
                $option .="<option value='" . $val->acc_id . "'>" . $val->acc_descripcion . "</option>";
        }
        return $option;
    }

    function obtenerSelectAdmin($default = null) {
        $sql = "SELECT usu_id,
                usu_nombres,
                usu_apellidos,
                rol_id
                FROM tab_usuario
                WHERE tab_usuario.usu_estado = 1
                ORDER BY usu_apellidos ASC,
                usu_nombres ASC ";
        $row = $this->accidente->dbselectBySQL($sql);
        $option = "";
        foreach ($row as $val) {
            if ($val->rol_id==2){
                if ($default == $val->usu_id)
                    $option .="<option value='" . $val->usu_id . "' selected>" . $val->usu_apellidos . " " . $val->usu_nombres . "</option>";
                else
                    $option .="<option value='" . $val->usu_id . "'>" . $val->usu_apellidos . " " . $val->usu_nombres . "</option>";
            }
        }
        return $option;
    }

    
    function esAdm() {
        $rows = array();
        $rows = $this->accidente->dbselectByField("usu_id", $_SESSION ['USU_ID']);
        if (count($rows) && $rows [0]->rol_id == '2')
            return true;
        return false;
    }


    function getDatos($usu_id) {
        $sql = "SELECT
                ttu.usu_id,
                ttu.uni_id,
                ttu.usu_nombres,
                ttu.usu_apellidos,
                ttu.rol_id,
                tab_unidad.uni_codigo,
                tab_unidad.uni_descripcion,
                tab_rol.rol_cod
                FROM
                tab_usuario AS ttu
                Inner Join tab_unidad ON tab_unidad.uni_id = ttu.uni_id
                Inner Join tab_rol ON ttu.rol_id = tab_rol.rol_id
                WHERE ttu.usu_id =  '$usu_id'  ";
        $row = "";
        $this->accidente = new tab_accidente();
        $row = $this->accidente->dbselectBySQL($sql);
        $res = array();
        if (count($row) > 0) {
            $res = $row[0];
        } else {
            $res = null;
        }
        return $res;
    }


    function obtenerDatosUsuario($username = null, $pass = null) {
        $row = "";
        $root = "";
        if ($username == 'root')
            $root = "OR usu_estado=0";
        if ($username != null || $pass != null) {
            $sql = "SELECT
                    tab_usuario.usu_id,
                    tab_usuario.uni_id,
                    tab_usuario.usu_nombres,
                    tab_usuario.usu_apellidos,
                    tab_usuario.usu_fono,
                    tab_usuario.usu_email,
                    tab_usuario.usu_login,
                    tab_usuario.rol_id,
                    tab_rol.rol_cod,
                    tab_rol.rol_descripcion
                    FROM
                    tab_usuario
                    INNER JOIN tab_rol ON tab_usuario.rol_id = tab_rol.rol_id
                    AND usu_login ='" . $username . "' AND usu_pass ='" . $pass . "' AND usu_estado=1 $root ";
            $row = $this->accidente->dbselectBySQL($sql);
            $row = $row [0];
            if (is_object($row))
                return $row;
            else
                return 0;
        }
        else
            0;
    }

    function verifyFields($id) {
        $unidad = new unidad ();
        //el ingreso es normal
        $sql = "SELECT *
                FROM tab_usuario
                WHERE usu_id='" . $id . "'";
        $row = $unidad->dbselectBySQL($sql);
        if (count($row)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    function listUsuarioJson() {
        $where = "";
        $default = $_POST ["uni_id"];
        if ($default) {
            $where .= " AND tu.uni_id = '" . $default . "' ";
        }
        $usuario = $_SESSION ['USU_ID'];
        $sql = "SELECT
                tu.usu_id,
                tu.uni_id,
                tu.usu_nombres,
                tu.usu_apellidos
                FROM tab_usuario AS tu
                Inner Join tab_rol AS tr ON tr.rol_id = tu.rol_id
                WHERE tu.usu_estado =  1  " . $where . " 
                AND tu.usu_id<>$usuario
                ORDER BY tu.usu_nombres ASC, 
                tu.usu_apellidos ASC";
        $row = $this->accidente->dbselectBySQL2($sql);
        return json_encode($row);
    }

}

?>