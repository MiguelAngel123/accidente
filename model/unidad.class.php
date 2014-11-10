<?php

/**
 * unidad.class.php Model
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.14
 * @access public
 */
class unidad extends Tab_unidad {

    function __construct() {
        $this->unidad = new tab_unidad();
    }

    function obtenerSelect($default = null) {        
        $option = "";
        $sql = "SELECT
                tab_unidad.uni_id,
                tab_unidad.uni_cod,
                tab_unidad.uni_codigo,
                tab_unidad.uni_descripcion,
                tab_unidad.uni_par
                FROM
                tab_unidad
                WHERE
                tab_unidad.uni_estado = 1
                ORDER BY tab_unidad.uni_cod ";

        $rows = $this->unidad->dbSelectBySQL($sql);
        if (count($rows) > 0) {
            foreach ($rows as $unidad) {
                if ($default == $unidad->uni_id) {
                    if ($unidad->uni_par == '-1') {
                        $option .= "<option value='$unidad->uni_id' selected>$unidad->uni_descripcion</option>";
                    } else {
                        $option .= "<option value='$unidad->uni_id' selected>" . "-- " . "$unidad->uni_descripcion</option>";
                    }
                } else {
                    if ($unidad->uni_par == '-1') {
                        $option .= "<option value='$unidad->uni_id'>$unidad->uni_descripcion</option>";
                    } else {
                        $option .= "<option value='$unidad->uni_id'>" . "----- " . "$unidad->uni_descripcion</option>";
                    }
                }
            }
        }
        return $option;
    }

    function obtenerSelectUnidades($default = null) {
        $option = "";
        $sql = "SELECT
                tab_fondo.fon_cod,
                tab_fondo.fon_descripcion,
                tab_unidad.uni_id,
                tab_unidad.uni_cod,
                tab_unidad.uni_codigo,
                tab_unidad.uni_descripcion,
                tab_unidad.uni_par
                FROM
                tab_unidad
                INNER JOIN tab_fondo ON tab_fondo.fon_id = tab_unidad.fon_id
                WHERE
                tab_unidad.uni_estado = 1
                ORDER BY tab_fondo.fon_cod,
                tab_unidad.uni_cod  ";
        $rows = $this->unidad->dbSelectBySQL($sql);        
        if (count($rows) > 0) {
            foreach ($rows as $unidad) {
                if ($default == $unidad->uni_id) {
                    if ($unidad->uni_par == '-1') {
                        $option .= "<option value='$unidad->uni_id' selected>$unidad->uni_descripcion</option>";
                    } else {
                        $option .= "<option value='$unidad->uni_id' selected>" . "-- " . "$unidad->uni_descripcion</option>";
                    }
                } else {
                    if ($unidad->uni_par == '-1') {
                        $option .= "<option value='$unidad->uni_id'>$unidad->uni_descripcion</option>";
                    } else {
                        $option .= "<option value='$unidad->uni_id'>" . "----- " . "$unidad->uni_descripcion</option>";
                    }
                }
            }
        }
        return $option;
    }

    function obtenerSelectPorSerie($default = null) {
        $option = '';
        $where = "";
//        if ($_SESSION ["ROL_COD"] != "AA") {
//            $where = " AND tab_usu_encuesta.usu_id = '" . $_SESSION ['USU_ID'] .  "'";
//        }
        
        if ($_SESSION ["ROL_COD"] == 'AAOR') {
            $where .= " AND tab_fondo.fon_id ='" . $_SESSION['FON_ID'] . "' ";
        } else if ($_SESSION ["ROL_COD"] != 'AA') {
            $where .= " AND tab_usu_encuesta.usu_id ='" . $_SESSION['USU_ID'] . "' ";
        }
        
        $sql = "SELECT DISTINCT
                tab_fondo.fon_cod,
                tab_fondo.fon_descripcion,
                tab_unidad.uni_id,
                tab_unidad.uni_cod,
                tab_unidad.uni_codigo,
                tab_unidad.uni_descripcion,
                tab_unidad.uni_par
                FROM
                tab_unidad
                INNER JOIN tab_fondo ON tab_fondo.fon_id = tab_unidad.fon_id
                INNER JOIN tab_encuesta ON tab_unidad.uni_id = tab_encuesta.uni_id
                INNER JOIN tab_usu_encuesta ON tab_encuesta.ser_id = tab_usu_encuesta.ser_id                
                WHERE
                tab_unidad.uni_estado = 1
                $where 
                ORDER BY tab_fondo.fon_cod,
                tab_unidad.uni_cod ";        
        
        $rows = $this->unidad->dbSelectBySQL($sql);
        
        $option .="<option value=''>Seleccione una Secci&oacute;n</option>";
        if (count($rows) > 0) {
            foreach ($rows as $unidad) {
                if ($default == $unidad->uni_id) {
                    if ($unidad->uni_par == '-1') {
                        $option .= "<option value='$unidad->uni_id' selected>$unidad->uni_descripcion</option>";
                    } else {
                        $option .= "<option value='$unidad->uni_id' selected>" . "-- " . "$unidad->uni_descripcion</option>";
                    }
                } else {
                    if ($unidad->uni_par == '-1') {
                        $option .= "<option value='$unidad->uni_id'>$unidad->uni_descripcion</option>";
                    } else {
                        $option .= "<option value='$unidad->uni_id'>" . "----- " . "$unidad->uni_descripcion</option>";
                    }
                }
            }
        }
        return $option;
    }
    
    function obtenerSelectPadres($default = null) {
        $add = "";
        $option = "";
        if ($default != '-1') {
            if ($default != null) {
                $padre = new Tab_unidad();
                $r_padres = $padre->dbselectBy2Field("uni_id", $default, "uni_estado", 1);
                if (count($r_padres) > 0) {
                    $padre = $r_padres[0];
                }
            }

            $sql = "SELECT 
                    tu.uni_id, 
                    tu.uni_codigo, 
                    tu.uni_par, 
                    tu.uni_descripcion
                    FROM tab_unidad tu
                    WHERE (tu.uni_estado = '10' OR tu.uni_estado = '1') $add
                    ORDER BY tu.uni_cod ASC ";
            $rows = $this->unidad->dbSelectBySQL($sql);
            if (count($rows) > 0) {
                foreach ($rows as $val) {
                    if ($default == $val->uni_id)
                        $selected = "selected";
                    else
                        $selected = " ";

                    if ($val->uni_par == -1) {
                        $option .="<option value='" . $val->uni_id . "' " . $selected . ">" . $val->uni_descripcion . "</option>";
                    } else {
                        $option .="<option value='" . $val->uni_id . "' " . $selected . ">" . "-- " . $val->uni_descripcion . "</option>";
                    }
                }
            }
        }
        return $option;
    }

    function obtenerSeccion($valor) {

        $usuario = new tab_unidad();
        $sql = "SELECT
        f.uni_descripcion,
        (SELECT uni_descripcion FROM tab_unidad WHERE uni_id =f.uni_par) AS tab_sec
        FROM
        tab_unidad as f
        WHERE
        f.uni_id  = $valor";
        $uni_descripcion = "";
        $nombre = $usuario->dbSelectBySQL($sql);
        foreach ($nombre as $row) {
            $uni_descripcion = $row->uni_descripcion;
        }

        return $uni_descripcion;
    }

    function listUnidad($default = null) {
        $add = "";
        $sql = "SELECT 
                uni_id ,
                uni_descripcion
                FROM tab_unidad 
                WHERE uni_estado = 1 $add 
                ORDER BY uni_id ";
        $row = $this->unidad->dbselectBySQL($sql);
        $option = "";
        foreach ($row as $val) {
            if ($default == $val->uni_id)
                $selected = "selected";
            else
                $selected = " ";
            $option .="<option value='" . $val->uni_id . "' $selected>" . $val->uni_descripcion . "</option>";
        }
        return $option;
    }

    function getTitle($id) {
        $row = $this->unidad->dbselectBySQL("select * from tab_unidad where uni_id = $id");
        $option = "";
        foreach ($row as $val) {
            $option = $val->uni_descripcion;
        }
        return $option;
    }

    function getCodigo($id) {
        $sql = "select 
                uni_cod 
                from tab_unidad 
                where uni_id = $id";
        $row = $this->unidad->dbselectBySQL($sql);
        $option = "";
        foreach ($row as $val) {
            $option = $val->uni_cod;
        }
        return $option;
    }

    function obtenerCheck($uni_id, $usu_id = null) {
        $check = '';
        $add = "";
        if ($usu_id != null || $usu_id != '') {
            $add = " (SELECT
            us.sec_id
            FROM
            tab_usu_sec AS us
            WHERE
            us.sec_id =  s.sec_id AND us.use_estado = '1' AND us.usu_id='$usu_id') as seleccionado ";
        } else {
            $add = " NULL as seleccionado ";
        }

        $sql = "SELECT
            s.sec_id,
            s.sec_codigo,
            s.sec_nombre,
            $add
            FROM
            tab_seccion AS s
            WHERE
            s.sec_estado =  '1' AND s.uni_id='$uni_id'
            ORDER BY s.sec_codigo ASC  ";
        $rows = $this->unidad->dbSelectBySQL($sql);
        $i = 0;
        $check .= '<table class="marcaRegistro" width="100%">';
        foreach ($rows as $serie) {
            $ck = '';
            if ($serie->seleccionado != null)
                $ck = ' checked="checked" ';

            $check.='<tr><td><input type="checkbox" name="lista_sec[' . $i . ']" ' . $ck . ' id="serie-' . $serie->sec_id . '" value="' . $serie->sec_id . '" /></td> <td>' . $serie->sec_codigo . '</td> <td>' . $serie->sec_nombre . '</td> </tr>';
            $i++;
        }
        $check .= '</table>';
        return $check;
    }

    function obtenerDatosUnidad($username = null, $pass = null) {
        $row = "";
        $root = "";
        if ($username == 'root')
            $root = "OR tab_usuario.usu_estado='0' ";
        $this->unidad = new tab_unidad();
        if ($username != null || $pass != null) {
            $sql = "SELECT
                        tab_unidad.uni_codigo,
                        tab_unidad.uni_descripcion
                        FROM
                        tab_unidad
                        INNER JOIN tab_usuario ON tab_unidad.uni_id = tab_usuario.uni_id
                        WHERE tab_usuario.usu_login ='" . $username . "' AND tab_usuario.usu_pass ='" . $pass . "' AND usu_estado=1 $root ";
            $row = $this->unidad->dbselectBySQL($sql);
            $row = $row [0];
            if (is_object($row))
                return $row;
            else
                return 0;
        }
        else
            0;
    }

    function existeCodigo($uni_codigo) {
        $row = array();
        if ($uni_codigo != null) {
            $sql = "select * 
                    from tab_unidad 
                    where tab_unidad.uni_codigo = '$uni_codigo' ";
            $row = $this->unidad->dbselectBySQL($sql);
        } else {
            return false;
        }
        if (count($row) > 0) {
            return true;
        }
        else
            return false;
    }

    function obtenerPadre($default = null, $default2 = null) {
        $uni_descripcion = "";
        while (true) {
            $sql = "SELECT 
                    uni_id,
                    uni_par,
                    uni_descripcion 
                    from tab_unidad WHERE uni_id='$default' ";
            $rows = $this->unidad->dbSelectBySQL($sql);
            if (count($rows) > 0) {
                foreach ($rows as $val) {
                    if ($val->uni_par == -1) {
                        $uni_descripcion = $val->uni_descripcion;
                        $default = -1;
                        break;
                    } else {
                        $default = $val->uni_par;
                    }
                }
            } else {
                $uni_descripcion = $default2;
            }

            if ($default == -1) {
                break;
            }
        }
        return $uni_descripcion;
    }

    function obtenerPadres($default = null, $default2 = null) {
        $uni_descripcion = "";
        while (true) {
            $sql = "SELECT 
                    uni_id,
                    uni_par,
                    uni_descripcion 
                    from tab_unidad WHERE uni_id='$default' ";
            $rows = $this->unidad->dbSelectBySQL($sql);
            if (count($rows) > 0) {
                foreach ($rows as $val) {
                    if ($val->uni_par == -1) {
                        $uni_descripcion = $uni_descripcion . $default2;
                        $default = -1;
                        break;
                    } else {
                        $uni_descripcion = $val->uni_descripcion . '/' . $uni_descripcion;
                        $default = $val->uni_par;
                    }
                }
            }
            if ($default == -1) {
                break;
            }
        }
        return $uni_descripcion;
    }

}

?>