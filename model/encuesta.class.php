<?php

/**
 * encuesta.class.php Model
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.24
 * @access public
 */
class encuesta extends tab_encuesta {

    function __construct() {
        $this->encuesta = new tab_encuesta();
    }

    function obtenerSelect($adm, $usu_id) {
        if ($adm) {
            $sql = "SELECT
		    s.enc_id,
                    s.enc_codigo,
                    s.enc_categoria,
		    s.ser_tipo
		    FROM tab_encuesta s
			WHERE s.enc_estado = 1 AND (s.ser_tipo IS NULL  OR s.ser_tipo = '') ";
        } else {
            $sql = "SELECT DISTINCT s.enc_id,s.enc_codigo, s.enc_categoria, s.ser_tipo
		    FROM tab_encuesta s Inner Join tab_usu_encuesta us ON s.enc_id = us.enc_id
			WHERE us.use_estado = 1
		     AND us.usu_id = '" . $usu_id . "' AND (s.ser_tipo IS NULL  OR s.ser_tipo = '') ";
        }
        $sql .= " ORDER BY s.ser_orden,
                s.enc_codigo ";
        $rows = $this->encuesta->dbselectBySQL($sql);
        $option = '';
        if (count($rows) > 0) {
            foreach ($rows as $val) {
                $option .="<option value='" . $val->enc_id . "'>" . $val->enc_codigo . " - " . $val->enc_categoria . "</option>";
            }
        }
        return $option;
    }

    function obtenerSelectTodas($default = null) {
        $sql = "SELECT
            DISTINCT(tab_encuesta.enc_id),
            tab_encuesta.ser_orden,
            tab_encuesta.enc_codigo,
            f.fon_cod,
            tab_unidad.uni_cod,
            tab_tipocorr.tco_codigo,
            tab_encuesta.enc_codigo,
            tab_encuesta.enc_categoria
                FROM
                tab_fondo as f
                INNER JOIN tab_unidad ON f.fon_id = tab_unidad.fon_id
                INNER JOIN tab_encuesta ON tab_unidad.uni_id = tab_encuesta.uni_id
                INNER JOIN tab_tipocorr ON tab_tipocorr.tco_id = tab_encuesta.tco_id
                INNER JOIN tab_respuesta ON tab_encuesta.enc_id = tab_respuesta.enc_id
                INNER JOIN tab_exparchivo ON tab_respuesta.exp_id = tab_exparchivo.exp_id
                INNER JOIN tab_archivo ON tab_archivo.fil_id = tab_exparchivo.fil_id
                INNER JOIN tab_expisadg ON tab_respuesta.exp_id = tab_expisadg.exp_id
                INNER JOIN tab_encusuario ON tab_respuesta.exp_id = tab_encusuario.exp_id
                INNER JOIN tab_cuerpos ON tab_cuerpos.cue_id = tab_exparchivo.cue_id
                INNER JOIN tab_tramitecuerpos ON tab_cuerpos.cue_id = tab_tramitecuerpos.cue_id
                INNER JOIN tab_tramite ON tab_tramite.tra_id = tab_tramitecuerpos.tra_id
                WHERE
                f.fon_estado = 1 AND
                tab_unidad.uni_estado = 1 AND
                tab_tipocorr.tco_estado = 1 AND
                tab_encuesta.enc_estado = 1 AND
                tab_respuesta.exp_estado = 1 AND
                tab_archivo.fil_estado = 1 AND
                tab_exparchivo.exa_estado = 1 AND
                tab_encusuario.eus_estado = 1
                ORDER BY tab_encuesta.ser_orden,
                tab_encuesta.enc_codigo ";
        $rows = $this->encuesta->dbSelectBySQL($sql);
        $option = '';

        if (count($rows) > 0) {
            foreach ($rows as $val) {
                $spaces = $this->getSpaces($val->ser_nivel);
                if ($default == $val->enc_id) {
                    $option .="<option value='" . $val->enc_id . "' selected>" . $val->fon_cod . DELIMITER . $val->uni_cod . DELIMITER . $val->tco_codigo . DELIMITER . $val->enc_codigo . "  " . $val->enc_categoria . "</option>";
                } else {
                    $option .="<option value='" . $val->enc_id . "'>" . $val->fon_cod . DELIMITER . $val->uni_cod . DELIMITER . $val->tco_codigo . DELIMITER . $val->enc_codigo . "  " . $val->enc_categoria . "</option>";
                }
            }
        }
        return $option;
    }

    function obtenerSelectDefault($usu_id, $default = null) {
        if ($default) {
            $sql = "SELECT DISTINCT
                    s.enc_id,
                    s.enc_categoria
		    FROM tab_encuesta s
                    Inner Join tab_usu_encuesta us ON s.enc_id = us.enc_id
                    WHERE s.enc_estado = 1
                    AND us.usu_id = '" . $usu_id . "' 
                    ORDER BY s.ser_orden,
                    s.enc_codigo ";
        } else {
            $sql = "SELECT DISTINCT
                    s.enc_id,
                    s.enc_categoria
		    FROM tab_encuesta s
                    Inner Join tab_usu_encuesta us ON s.enc_id = us.enc_id
                    WHERE s.enc_estado = 1
                    AND us.usu_id = '" . $usu_id . "' 
                    ORDER BY s.ser_orden,
                    s.enc_codigo ";
        }
        $row = $this->encuesta->dbselectBySQL($sql);
        $option = "";
        foreach ($row as $val) {
            if ($default == $val->enc_id) {
                $option .="<option value='" . $val->enc_id . "' selected>" . $val->enc_categoria . "</option>";
            } else {
                $option .="<option value='" . $val->enc_id . "'>" . $val->enc_categoria . "</option>";
            }
        }
        return $option;
    }
    
    function obtenerSelectencuesta($default = null) {
        $sql = "SELECT
                ts.enc_id,
                ts.enc_categoria
                FROM
                tab_encuesta ts
                WHERE
                ts.enc_estado =  '1'
                AND ts.enc_id IN(SELECT use.enc_id FROM tab_usu_encuesta use WHERE use.usu_id='" . $_SESSION['USU_ID'] . "' AND use.use_estado = '1')
                ORDER BY ts.ser_orden,
                ts.enc_codigo ";
        $rows = $this->encuesta->dbSelectBySQL($sql);
        if (count($rows) > 0) {
            $option = "";
            foreach ($rows as $val) {
                if ($default == $val->enc_id)
                    $selected = "selected";
                else
                    $selected = " ";
                $option .="<option value='" . $val->enc_id . "' " . $selected . ">" . $val->enc_categoria . "</option>";
            }
            return $option;
        }
        else
            return "";
    }

    function obtenerSelectencuestaDefault($default = null) {
        $sql = "SELECT
                ts.enc_id,
                ts.enc_categoria
                FROM
                tab_encuesta ts
                WHERE
                ts.enc_estado =  '1'
                ORDER BY ts.ser_orden,
                ts.enc_codigo ";
        $rows = $this->encuesta->dbSelectBySQL($sql);
        if (count($rows) > 0) {
            $option = "";
            foreach ($rows as $val) {
                if ($default == $val->enc_id) {
                    $option .="<option value='" . $val->enc_id . "' selected >" . $val->enc_categoria . "</option>";
                } else {
                    $option .="<option value='" . $val->enc_id . "' >" . $val->enc_categoria . "</option>";
                }
            }
            return $option;
        }
        else
            return "";
    }

    
    function obtenerEncuestaNombre($enc_id) {
        $nombre = '';
        $encuesta = new tab_encuesta();
        $sql = "SELECT
                tab_encuesta.enc_categoria
                FROM
                tab_encuesta
		WHERE tab_encuesta.enc_estado = '1'
                AND tab_encuesta.enc_id ='" . $enc_id . "'  ";
        $encuestas = $encuesta->dbSelectBySQL($sql);
        if (count($encuestas) > 0) {
            foreach ($encuestas as $encuesta) {
                $nombre = $encuesta->enc_categoria;
            }
        }
        return $nombre;
    }
    
    function obtenerSerieTramites($tra_id = null) {
        $add = "";
        if ($tra_id != null) {
            $add = " (CASE
                         WHEN (SELECT COUNT(tc.enc_id)
                               From tab_serietramite as tc
                               WHERE tc.sts_estado = '1'
                               AND tc.enc_id=ts.enc_id
                               AND tc.tra_id='" . $tra_id . "' )>0 THEN
                           'checked'
                        ELSE
                               ''
                        END) as checked";
        } else {
            $add = "' ' as checked";
        }
        $sql = "SELECT
                ts.enc_id,
                ts.enc_codigo,
                ts.enc_categoria,
                $add
                FROM
                tab_encuesta AS ts
                WHERE ts.enc_estado = '1'
                ORDER BY ts.ser_orden,
                ts.enc_codigo ";
        $rows = $this->encuesta->dbSelectBySQL($sql);
        if (count($rows) > 0)
            return $rows;
        else
            return "";
    }

    function obtenerSeccionDeParaCajas($default = null) {
        $where = "";
        if ($_SESSION ["ROL_COD"] == 'AAOR') {
            $where .= " AND tab_fondo.fon_id ='" . $_SESSION['FON_ID'] . "' ";
        } else if ($_SESSION ["ROL_COD"] != 'AA') {
            $where .= " AND tab_encusuario.usu_id ='" . $_SESSION['USU_ID'] . "' ";
        }        
        
        $sql = "SELECT
                DISTINCT(tab_unidad.uni_descripcion),
                tab_unidad.uni_id,
                tab_unidad.uni_cod
                FROM
                tab_usuario
                INNER JOIN tab_encusuario ON tab_usuario.usu_id = tab_encusuario.usu_id
                INNER JOIN tab_respuesta ON tab_encusuario.exp_id = tab_respuesta.exp_id
                INNER JOIN tab_expfondo ON tab_respuesta.exp_id = tab_expfondo.exp_id
                INNER JOIN tab_encuesta ON tab_respuesta.enc_id = tab_encuesta.enc_id
                INNER JOIN tab_usu_encuesta ON tab_encuesta.enc_id = tab_usu_encuesta.enc_id
                INNER JOIN tab_expisadg ON tab_respuesta.exp_id = tab_expisadg.exp_id
                INNER JOIN tab_unidad ON tab_unidad.uni_id = tab_encuesta.uni_id
                INNER JOIN tab_fondo ON tab_fondo.fon_id = tab_unidad.fon_id
                INNER JOIN tab_tipocorr ON tab_tipocorr.tco_id = tab_encuesta.tco_id
                WHERE tab_fondo.fon_estado = 1
                AND tab_unidad.uni_estado = 1
                AND tab_encuesta.enc_estado = 1
                AND tab_tipocorr.tco_estado = 1
                AND tab_respuesta.exp_estado = 1
                AND tab_expisadg.exp_estado = 1
                AND tab_expfondo.exf_estado = 1
                AND tab_encusuario.eus_estado = 1                
                AND trim(tab_respuesta.exp_nrocaj)<>''                 
                $where  ";
        // AND tab_encusuario.usu_id=" . $_SESSION ['USU_ID'] . " 
        $rows = $this->encuesta->dbSelectBySQL($sql);
        $option = '';
        $option .="<option value=''>Seleccione una Secci&oacute;n</option>";
        if (count($rows) > 0) {
            foreach ($rows as $val) {


                $option .="<option value='" . $val->uni_id . "'>" . $val->uni_descripcion . "</option>";
            }
        }
        return $option;
    }

    function obtenerSeccion($default = null) {
        $sql = "SELECT
                DISTINCT(tab_unidad.uni_descripcion),
                tab_unidad.uni_id,
                tab_unidad.uni_cod
                FROM
                tab_usuario
                INNER JOIN tab_encusuario ON tab_usuario.usu_id = tab_encusuario.usu_id
                INNER JOIN tab_respuesta ON tab_encusuario.exp_id = tab_respuesta.exp_id
                INNER JOIN tab_expfondo ON tab_respuesta.exp_id = tab_expfondo.exp_id
                INNER JOIN tab_encuesta ON tab_respuesta.enc_id = tab_encuesta.enc_id
                INNER JOIN tab_usu_encuesta ON tab_encuesta.enc_id = tab_usu_encuesta.enc_id
                INNER JOIN tab_expisadg ON tab_respuesta.exp_id = tab_expisadg.exp_id
                INNER JOIN tab_unidad ON tab_unidad.uni_id = tab_encuesta.uni_id
                INNER JOIN tab_fondo ON tab_fondo.fon_id = tab_unidad.fon_id
                INNER JOIN tab_tipocorr ON tab_tipocorr.tco_id = tab_encuesta.tco_id
                WHERE tab_fondo.fon_estado = 1
                AND tab_unidad.uni_estado = 1
                AND tab_encuesta.enc_estado = 1
                AND tab_tipocorr.tco_estado = 1
                AND tab_respuesta.exp_estado = 1
                AND tab_expisadg.exp_estado = 1
                AND tab_expfondo.exf_estado = 1
                AND tab_encusuario.eus_estado = 1
                AND tab_encusuario.usu_id=" . $_SESSION ['USU_ID'];
        $rows = $this->encuesta->dbSelectBySQL($sql);
        $option = '';
        $option .="<option value=''>Seleccione una Secci&oacute;n</option>";
        if (count($rows) > 0) {
            foreach ($rows as $val) {
                $option .="<option value='" . $val->uni_id . "'>" . $val->uni_descripcion . "</option>";
            }
        }
        return $option;
    }

    function obtenerSeccionInventario($default = null) {
//        if ($_SESSION ["ROL_COD"] != "AA") {
//            $where = " AND tab_unidad.uni_id = '" . $_SESSION ['UNI_ID'] . "'";
//        }
        
        if ($_SESSION ["ROL_COD"] == 'AAOR') {
            $where .= " AND tab_fondo.fon_id ='" . $_SESSION['FON_ID'] . "' ";
        } else if ($_SESSION ["ROL_COD"] != 'AA') {
            $where .= " AND tab_unidad.uni_id = '" . $_SESSION ['UNI_ID'] . "'";
        }        
//            $sql = "SELECT 
//                    DISTINCT
//                    tab_unidad.uni_id,
//                    tab_unidad.uni_descripcion
//                    FROM
//                    tab_unidad
//                    INNER JOIN tab_encuesta ON tab_unidad.uni_id = tab_encuesta.uni_id
//                    INNER JOIN tab_usu_encuesta ON tab_encuesta.enc_id = tab_usu_encuesta.enc_id
//                    INNER JOIN tab_usuario ON tab_usuario.usu_id = tab_usu_encuesta.usu_id
//                    ORDER BY
//                    tab_unidad.uni_id ASC ";
//        } else {
//            $sql = "SELECT DISTINCT
//                    tab_unidad.uni_id,
//                    tab_unidad.uni_descripcion
//                    FROM
//                    tab_unidad
//                    INNER JOIN tab_encuesta ON tab_unidad.uni_id = tab_encuesta.uni_id
//                    INNER JOIN tab_usu_encuesta ON tab_encuesta.enc_id = tab_usu_encuesta.enc_id
//                    INNER JOIN tab_usuario ON tab_usuario.usu_id = tab_usu_encuesta.usu_id
//                    WHERE tab_usu_encuesta.usu_id = " . $_SESSION ['USU_ID'] . "
//                    ORDER BY
//                    tab_unidad.uni_id ASC ";
//        }

        
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
                $where 
                ORDER BY tab_fondo.fon_cod,
                tab_unidad.uni_cod ";        
        
        $rows = $this->encuesta->dbSelectBySQL($sql);
        $option = '';
        $option .="<option value=''>Seleccione una Secci&oacute;n</option>";
        if (count($rows) > 0) {
            foreach ($rows as $val) {
                if ($val->uni_par == '-1') {
//                $option .="<option value='" . $val->uni_id . "'>" . $val->uni_descripcion . "</option>";                    
                    $option .= "<option value='$val->uni_id'>$val->uni_descripcion</option>";
                } else {
                    $option .= "<option value='$val->uni_id'>" . "----- " . "$val->uni_descripcion</option>";
                }

            }
        }
        return $option;
    }



    function validarCodigoSerie($enc_id) {
        $ser_cod = "";
        $rows = "";
        $sql = "SELECT
                tab_fondo.fon_cod,
                tab_unidad.uni_cod,
                tab_tipocorr.tco_codigo,
                tab_encuesta.enc_codigo
                FROM
                tab_fondo
                INNER JOIN tab_unidad ON tab_fondo.fon_id = tab_unidad.fon_id
                INNER JOIN tab_encuesta ON tab_unidad.uni_id = tab_encuesta.uni_id
                INNER JOIN tab_tipocorr ON tab_tipocorr.tco_id = tab_encuesta.tco_id
                WHERE tab_fondo.fon_estado = 1
                AND tab_unidad.uni_estado = 1
                AND tab_tipocorr.tco_estado = 1
                AND tab_encuesta.enc_estado = 1
                AND tab_encuesta.enc_id = '$enc_id' ";

        $rows = $this->encuesta->dbselectBySQL($sql);
        foreach ($rows as $listt) {
            $tco_codigo = $listt->tco_codigo;
        }

        return $tco_codigo;
    }

    function obtenerCodigoSerie($enc_id) {
        $ser_cod = "";
        $rows = "";
        $sql = "SELECT
                tab_unidad.uni_cod,
                tab_encuesta.enc_codigo
                FROM
                tab_unidad
                INNER JOIN tab_encuesta ON tab_unidad.uni_id = tab_encuesta.uni_id
                WHERE tab_unidad.uni_estado = 1
                AND tab_encuesta.enc_estado = 1
                AND tab_encuesta.enc_id = '$enc_id' ";

        $rows = $this->encuesta->dbselectBySQL($sql);
        foreach ($rows as $val) {
            $ser_cod = $val->uni_cod . DELIMITER . $val->enc_codigo;
        }
        return $ser_cod;
    }

    function obtenerCategoriaSerie($enc_id) {
        $enc_categoria = "";
        $rows = "";
        $sql = "select enc_categoria
		from tab_encuesta
		WHERE enc_estado = '1'
                AND enc_id = '$enc_id' ";
        $rows = $this->encuesta->dbselectBySQL($sql);
        foreach ($rows as $val) {
            $enc_categoria = $val->enc_categoria;
        }
        return $enc_categoria;
    }

    function getSpaces($ser_nivel) {
        $spaces = "";
        for ($i = 0; $i < $ser_nivel; $i++) {
            $spaces .= "----";
        }
        return $spaces;
    }

    function obtenerTipoCorrSerie($enc_id) {
        $ser_corr = "";
        $rows = "";
        $sql = "select ser_corr
		from tab_encuesta
		WHERE enc_estado = '1'
                AND enc_id = '$enc_id' ";
        $rows = $this->encuesta->dbselectBySQL($sql);
        foreach ($rows as $val) {
            $ser_corr = $val->ser_corr;
        }
        return $ser_corr;
    }

    function obtenerSeccionSerie($enc_id) {
        $uni_descripcion = "";
        $rows = "";
        $sql = "SELECT
                tab_unidad.uni_descripcion
                FROM
                tab_unidad
                INNER JOIN tab_encuesta ON tab_unidad.uni_id = tab_encuesta.uni_id
                WHERE tab_encuesta.enc_id = '$enc_id' ";

        $rows = $this->encuesta->dbselectBySQL($sql);
        foreach ($rows as $val) {
            $uni_descripcion = $val->uni_descripcion;
        }
        return $uni_descripcion;
    }

    function obtenerSeccionIdSerie($enc_id) {
        $uni_descripcion = "";
        $rows = "";
        $sql = "SELECT
                tab_unidad.uni_id
                FROM
                tab_unidad
                INNER JOIN tab_encuesta ON tab_unidad.uni_id = tab_encuesta.uni_id
                WHERE tab_encuesta.enc_id = '$enc_id' ";

        $rows = $this->encuesta->dbselectBySQL($sql);
        foreach ($rows as $val) {
            $uni_id = $val->uni_id;
        }
        return $uni_id;
    }

    function obtenerSelectSeccionDefault($usu_id, $uni_id, $default = null) {
        if ($uni_id) {
            $sql = "SELECT
                    tab_unidad.uni_id,
                    s.enc_id,
                    s.enc_categoria
                    FROM
                    tab_encuesta AS s
                    INNER JOIN tab_usu_encuesta AS us ON s.enc_id = us.enc_id
                    INNER JOIN tab_unidad ON tab_unidad.uni_id = s.uni_id
                    WHERE
                    s.enc_estado = 1
                    AND us.usu_id = $usu_id
                    AND tab_unidad.uni_id = $uni_id
                    ORDER BY s.enc_codigo";
        } else {
            $sql = "SELECT
                    s.enc_id,
                    s.enc_categoria
		    FROM tab_encuesta s
                    INNER JOIN tab_usu_encuesta us ON s.enc_id = us.enc_id
                    WHERE s.enc_estado = 1
                    AND us.usu_id = '" . $usu_id . "'
                    ORDER BY s.enc_codigo ";
        }
        $row = $this->encuesta->dbselectBySQL($sql);
        $option = "";

        foreach ($row as $val) {
            $spaces = "";
            if ($default == $val->enc_id) {
                $option .="<option value='" . $val->enc_id . "' selected>" . $spaces . $val->enc_categoria . "</option>";
            } else {
                $option .="<option value='" . $val->enc_id . "'>" . $spaces . $val->enc_categoria . "</option>";
            }
        }
        return $option;
    }

    function obtenerSelectSeccionDefaultEdit($usu_id, $uni_id, $default = null) {
        if ($uni_id) {
            $sql = "SELECT
                    tab_unidad.uni_id,
                    s.enc_id,
                    s.enc_categoria
                    FROM
                    tab_encuesta AS s
                    INNER JOIN tab_usu_encuesta AS us ON s.enc_id = us.enc_id
                    INNER JOIN tab_unidad ON tab_unidad.uni_id = s.uni_id
                    WHERE
                    s.enc_estado = 1
                    AND us.usu_id = $usu_id
                    AND tab_unidad.uni_id = $uni_id
                    ORDER BY s.enc_id";
        } else {
            $sql = "SELECT
                    s.enc_id,
                    s.enc_categoria
		    FROM tab_encuesta s
                    INNER JOIN tab_usu_encuesta us ON s.enc_id = us.enc_id
                    WHERE s.enc_estado = 1
                    AND us.usu_id = '" . $usu_id . "'
                    ORDER BY s.ser_orden,
                    s.enc_codigo ";
        }
        $row = $this->encuesta->dbselectBySQL($sql);
        $option = "";

        foreach ($row as $val) {
            $spaces = "";
            if ($default == $val->enc_id) {
                $option .="<option value='" . $val->enc_id . "' selected>" . $spaces . $val->enc_categoria . "</option>";
                break;
            }
        }
        return $option;
    }

    // Modified freddy: 2014.02.25
    function obtenerSeccionGeneral($default = null) {
        $sql = "SELECT
            DISTINCT(tab_unidad.uni_descripcion),
            tab_unidad.uni_id,
           tab_unidad.uni_cod

            FROM
            tab_usuario
            INNER JOIN tab_encusuario ON tab_usuario.usu_id = tab_encusuario.usu_id
            INNER JOIN tab_respuesta ON tab_encusuario.exp_id = tab_respuesta.exp_id
            INNER JOIN tab_expfondo ON tab_respuesta.exp_id = tab_expfondo.exp_id
            INNER JOIN tab_encuesta ON tab_respuesta.enc_id = tab_encuesta.enc_id
            INNER JOIN tab_usu_encuesta ON tab_encuesta.enc_id = tab_usu_encuesta.enc_id
            INNER JOIN tab_expisadg ON tab_respuesta.exp_id = tab_expisadg.exp_id
            INNER JOIN tab_unidad ON tab_unidad.uni_id = tab_encuesta.uni_id
            INNER JOIN tab_fondo ON tab_fondo.fon_id = tab_unidad.fon_id
            INNER JOIN tab_tipocorr ON tab_tipocorr.tco_id = tab_encuesta.tco_id
            WHERE tab_fondo.fon_estado = 1
            AND tab_unidad.uni_estado = 1
            AND tab_encuesta.enc_estado = 1
            AND tab_tipocorr.tco_estado = 1
            AND tab_respuesta.exp_estado = 1
            AND tab_expisadg.exp_estado = 1
            AND tab_expfondo.exf_estado = 1
            AND tab_encusuario.eus_estado = 1";
        $rows = $this->encuesta->dbSelectBySQL($sql);
        $option = '';
        $option .="<option value=''>Seleccione una Secci&oacute;n</option>";
        if (count($rows) > 0) {
            foreach ($rows as $val) {


                $option .="<option value='" . $val->uni_id . "'>" . $val->uni_descripcion . "</option>";
            }
        }
        return $option;
    }

    function validaDependencia($enc_id) {
        $option = 0;
        $sql = "SELECT COUNT (enc_id) from tab_encuesta WHERE ser_par=$enc_id";
        $algo = $this->encuesta->countBySQL($sql);
        if ($algo == 0) {
            $sql = "SELECT COUNT (tab_encuesta.enc_id)
                    FROM tab_encuesta
                    INNER JOIN tab_respuesta ON tab_encuesta.enc_id = tab_respuesta.enc_id
                    INNER JOIN tab_exparchivo ON tab_respuesta.exp_id = tab_exparchivo.exp_id
                    WHERE tab_encuesta.enc_id = $enc_id ";
            $algo = $this->encuesta->countBySQL($sql);
            if ($algo != 0) {
                $option = 1;
            }
        } else {
            $option = 1;
        }
        return $option;
    }

    function getTitle($enc_id) {
        $row = $this->encuesta->dbselectByField("enc_id", $enc_id);
        if (!is_null($row))
            return $row[0]->enc_categoria;
        else
            return "";
    }

    function loadMenu($adm = false, $fun = null) {
        if ($fun == null) {
            $fun = "test";
        }
        $usu_id = null;
        if (!$adm) {
            $usu_id = $_SESSION ['USU_ID'];
        }
        $row = $this->getencuesta($usu_id);
        $search = "";
        $search .= "{separator: true},{name: 'TODOS', bclass: 'enc_categoria', onpress : $fun},";
        /* PUSE EN COMENTARIO TEMPORALMENTE
          foreach ($row as $val) {
          $search .= "{separator: true},{name: '" . $val->enc_categoria . "', bclass: 'enc_categoria', onpress : $fun},";
          }
         */
        $search = substr($search, 0, -1);
        return $search;
    }

    function getencuesta($usu_id = null) {
        $rows = array();
        $where = "";
        if ($usu_id != null) {
            $where .= " AND u.usu_id =  '" . $usu_id . "' ";
        }
        $sql = "SELECT DISTINCT
                    s.enc_id,
                    s.enc_categoria
                    FROM
                    tab_usu_encuesta AS u
                    Inner Join tab_encuesta s ON u.enc_id = s.enc_id
                    WHERE
                    u.use_estado = '1'
                    AND s.enc_estado = '1' $where
                    ORDER BY s.ser_orden,
                    s.enc_codigo ";
        //echo ($sql);die();
        $rows = $this->encuesta->dbSelectBySQL($sql);
        return $rows;
    }

}

?>
