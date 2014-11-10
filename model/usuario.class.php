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
class usuario extends tab_usuario {

    function __construct() {
        $this->usuario = new tab_usuario();
    }

    function obtenerSelect($default = null) {
        $sql = "SELECT usu_id,
                usu_nombres,
                usu_apellidos
                FROM tab_usuario
                WHERE tab_usuario.usu_estado = 1
                ORDER BY usu_apellidos ASC,
                usu_nombres ASC ";
        $row = $this->usuario->dbselectBySQL($sql);
        $option = "";
        foreach ($row as $val) {
            if ($default == $val->usu_id)
                $option .="<option value='" . $val->usu_id . "' selected>" . $val->usu_apellidos . " " . $val->usu_nombres . "</option>";
            else
                $option .="<option value='" . $val->usu_id . "'>" . $val->usu_apellidos . " " . $val->usu_nombres . "</option>";
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
        $row = $this->usuario->dbselectBySQL($sql);
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
        $rows = $this->usuario->dbselectByField("usu_id", $_SESSION ['USU_ID']);
        if (count($rows) && $rows [0]->rol_id == '2')
            return true;
        return false;
    }

    function generarLogin($nombress, $apellidoss) {
        if ($nombress == "" && $apellidoss == "") {
            $login = "";
            $nombres = strtolower(trim($nombress));
            $apellidos = strtolower(trim($apellidoss));
            $nombre = explode(" ", $nombres);
            $apellido = explode(" ", $apellidos);
            $login = $nombre [0] . "." . $apellido [0];
            if (existeLogin($login)) {
                $login.=rand(0, 100);
            }
            return $login;
        }
        else
            return "user.user";
    }

    function existeLogin($login, $usu_id = null) {
        $row = array();
        if ($usu_id == null) {
            $sql = "select * from tab_usuario where tab_usuario.usu_login = '$login' ";
            $row = $this->usuario->dbselectBySQL($sql);
        } else {
            $sql = "select * from tab_usuario where tab_usuario.usu_login = '$login' AND usu_id<>'$usu_id' ";
            $row = $this->usuario->dbselectBySQL($sql);
        }
        if (count($row) > 0) {
            return true;
        }
        return false;
    }

    function obtenerNombre($usu_id) {
        $sql = "SELECT
                usu_nombres,
                usu_apellidos
                FROM tab_usuario
                WHERE usu_id ='" . $usu_id . "' ";
        $user = $this->usuario->dbSelectBySQL($sql);
        $nom = '';
        if (count($user) > 0) {
            $nom = $user[0]->usu_nombres . " " . $user[0]->usu_apellidos;
        }
        return $nom;
    }

    function obtenerEmail($usu_id) {
        $sql = "SELECT
                usu_email
                FROM tab_usuario
                WHERE usu_id ='" . $usu_id . "' ";
        $user = $this->usuario->dbSelectBySQL($sql);
        $email = '';
        if (count($user) > 0) {
            $nom = $user[0]->usu_email;
        }
        return $email;
    }
    
    function getTipo($usu_id) {
        $this->rol = new tab_rol();
        $rows = array();
        $sql = "SELECT *
                FROM
                tab_usuario AS u
                INNER JOIN tab_rol AS r ON r.rol_id = u.rol_id
                WHERE
                u.usu_id =  '" . $usu_id . "' ";
        $rows = $this->rol->dbSelectBySQL($sql);
        if (count($rows))
            return $rows[0]->rol_cod;
        return "";
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
        $this->usuario = new tab_usuario();
        $row = $this->usuario->dbselectBySQL($sql);
        $res = array();
        if (count($row) > 0) {
            $res = $row[0];
        } else {
            $res = null;
        }
        return $res;
    }

    function getRol($usu_id) {
        $rol_titulo = '';
        $sql = "SELECT
                tab_usuario.rol_id,
                tab_rol.rol_titulo
                FROM
                tab_usuario
                Inner Join tab_rol ON tab_usuario.rol_id = tab_rol.rol_id
                WHERE tab_usuario.usu_id =  '$usu_id'  ";
        $this->usuario = new tab_usuario();
        $row = $this->usuario->dbselectBySQL($sql);
        if (count($row) > 0) {
            $rol_titulo = $row[0]->rol_titulo;
        }
        return $rol_titulo;
    }

    function buscarUsuario($username = null, $pass = null) {
        $row = 0;
        $root = "";
        if ($username == 'root') {
            $where = " AND usu_estado='0' ";
        } else {
            $where = " AND usu_estado='1' ";
        }
        $sql = "SELECT *
                FROM tab_usuario
                WHERE usu_login ='" . pg_escape_string($username) . "' AND usu_pass ='" . $pass . "' $where ";
        $this->usuario = new tab_usuario ();
        if ($username != null || $pass != null) {
            $row = $this->usuario->dbselectBySQL($sql);
            //print_r($row);
            if (count($row))
                return true;
            else
                return false;
        }
        else
            false;
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
            $row = $this->usuario->dbselectBySQL($sql);
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

    function allencuesta() {
        $liMenu = "";
        $i = 0;
        $uni_id = 0;
        $row1 = 1;

        // encuesta
        $sql = "SELECT
                tab_unidad.uni_id,
                tab_unidad.uni_cod,
                tab_unidad.uni_descripcion,
                tab_encuesta.enc_id,
                tab_encuesta.enc_codigo,
                tab_encuesta.enc_categoria
                FROM
                tab_encuesta
                INNER JOIN tab_unidad ON tab_unidad.uni_id = tab_encuesta.uni_id
                WHERE tab_encuesta.enc_estado =  '1'
                ORDER BY tab_encuesta.enc_id ";
        $rows = $this->usuario->dbselectBySQL($sql);
        foreach ($rows as $menus) {
            $encuesta = new encuesta();
            $spaces = "";

            $liMenu .= "<tr " . ($row1 % 2 ? "class='" . $menus->uni_id . "z'" : "class='erow " . $menus->uni_id . "z'") . " >";

            $liMenu .= "<td align='right'>";
            $liMenu .= "<div style='text-align: right; width: 50px;'>" . $menus->enc_id . "</div>";
            $liMenu .= "</td>";

            $liMenu .= "<td align='center'>";
            $liMenu .= "<div style='text-align: center; width: 50px;'>";                    
            $liMenu .= "<input name='lista_encuesta[$i]' type='checkbox' value='" . $menus->enc_id . "'></div>";
            $liMenu .= "</td>";

            $liMenu .= "<input type='hidden' name='id_menu[$i]' value='" . $menus->enc_id . "'>";
            $liMenu .= "<td align='left' class='sorted'>";
            $liMenu .= "<div style='text-align: left; width: 150px;'>" . $menus->enc_codigo . "</div>";
            $liMenu .= "</td>";

            $liMenu .= "<td align='left'>";
            $liMenu .= "<div style='text-align: left; width: 600px;'>" . $spaces . $menus->enc_categoria . "</div>";
            $liMenu .= "</td>";
            $liMenu .= "</tr>";
            $row1++;
            $i++;
            $uni_id = $menus->uni_id;
        }
        return $liMenu;
    }

    function allencuestaSeleccionado($idUsuario) {
        $liMenu = "";
        $sql = "SELECT
                tab_unidad.uni_id,
                tab_unidad.uni_cod,
                tab_unidad.uni_descripcion,
                tab_encuesta.enc_id,
                tab_encuesta.enc_codigo,
                tab_encuesta.enc_categoria
                FROM
                tab_encuesta
                INNER JOIN tab_unidad ON tab_unidad.uni_id = tab_encuesta.uni_id
                WHERE tab_encuesta.enc_estado = '1'
                ORDER BY enc_codigo ";
        $rows = $this->usuario->dbselectBySQL($sql);
        $row1 = 1;
        $i = 0;
        $uni_id = 0;
        foreach ($rows as $menus) {
            $encuesta = new encuesta();
            $spaces = "";

            $sql3 = "SELECT
                    tab_usu_encuesta.usu_id,
                    tab_usu_encuesta.enc_id
                    FROM tab_usu_encuesta
                    WHERE tab_usu_encuesta.usu_id = '" . $idUsuario . "'
                    AND tab_usu_encuesta.enc_id = '" . $menus->enc_id . "'
                    AND tab_usu_encuesta.uen_estado=1
                    ORDER BY tab_usu_encuesta.uen_id ";
            $chek1 = "";
            $rowChek = $this->usuario->dbselectBySQL($sql3);

            if (count($rowChek) > 0) {
                $chek1 = "checked";
            }

            $liMenu .= "<tr " . ($row1 % 2 ? "class='" . $menus->uni_id . "z'" : "class='erow " . $menus->uni_id . "z'") . " >";
            $liMenu .= "<td align='right' class='sorted' >";
            $liMenu .= "<div style='text-align: right; width: 50px;'>" . $menus->enc_id . "</div>";
            $liMenu .= "</td>";

            $liMenu .= "<td align='center'>";
            $liMenu .= "<div style='text-align: center; width: 50px;'>";
            $liMenu .= "<input name='lista_encuesta[$i]' type='checkbox' value='" . $menus->enc_id . "' " . $chek1 . ">";
            $liMenu .= "</div>";
            $liMenu .= "</td>";

            $liMenu .= "<input type='hidden' name='id_menu[$i]' value='" . $menus->enc_id . "'>";
            $liMenu .= "<td align='left'>";
            $liMenu .= "<div style='text-align: left; width: 150px;'>" . $menus->enc_codigo . "</div>";
            $liMenu .= "</td>";

            $liMenu .= "<td align='left'>";
            $liMenu .= "<div style='text-align: left; width: 600px;'>" . $spaces . $menus->enc_categoria . "</div>";
            $liMenu .= "</td>";
            $liMenu .= "</tr>";

            $row1++;
            $i++;
            $uni_id = $menus->uni_id;
        }

        return $liMenu;
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
        $row = $this->usuario->dbselectBySQL2($sql);
        return json_encode($row);
    }

}

?>