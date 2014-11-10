<?php

/**
 * respuesta.class.php Model
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.14
 * @access public
 */
class respuesta extends tab_respuesta {

    function __construct() {
        $this->respuesta = new tab_respuesta ();
    }


    function obtenerCodigo($exp_id) {
        $codigo = '';
        $tab_respuesta = new tab_respuesta();
        $sql = "SELECT
                tab_fondo.fon_cod,
                tab_unidad.uni_cod,
                tab_tipocorr.tco_codigo,
                tab_respuesta.ser_codigo,
                tab_respuesta.exp_codigo
                FROM
                tab_fondo
                INNER JOIN tab_unidad ON tab_fondo.fon_id = tab_unidad.fon_id
                INNER JOIN tab_respuesta ON tab_unidad.uni_id = tab_respuesta.uni_id
                INNER JOIN tab_tipocorr ON tab_tipocorr.tco_id = tab_respuesta.tco_id
                INNER JOIN tab_respuesta ON tab_respuesta.ser_id = tab_respuesta.ser_id
		WHERE tab_respuesta.exp_estado = '1'
                AND tab_respuesta.exp_id ='" . $exp_id . "'  ";
        $respuestas = $tab_respuesta->dbSelectBySQL($sql);
        if (count($respuestas) > 0) {
            foreach ($respuestas as $respuesta) {
                $codigo = $respuesta->fon_cod . DELIMITER . $respuesta->uni_cod . DELIMITER . $respuesta->tco_codigo . DELIMITER . $respuesta->ser_codigo . DELIMITER . $respuesta->exp_codigo;
            }
        }
        return $codigo;
    }

    function obtenerEncuestaNombre($res_id) {
        $nombre = '';
        $tab_respuesta = new tab_respuesta();
        $sql = "SELECT
                tab_encuesta.enc_categoria
                FROM
                tab_encuesta
                INNER JOIN tab_respuesta ON tab_encuesta.enc_id = tab_respuesta.enc_id
		WHERE tab_respuesta.res_estado = '1'
                AND tab_encuesta.enc_estado = '1'
                AND tab_respuesta.res_id ='" . $res_id . "'  ";
        $respuestas = $tab_respuesta->dbSelectBySQL($sql);
        if (count($respuestas) > 0) {
            foreach ($respuestas as $respuesta) {
                $nombre = $respuesta->enc_categoria;
            }
        }
        return $nombre;
    }    

    function estadoEncuesta($res_id) {
        $sql = "SELECT COUNT(res_id)
                FROM
                tab_respuesta
                WHERE
                res_estado =  '2' 
                AND tab_respuesta.res_id =  '$res_id' ";
        $num = $this->respuesta->countBySQL($sql);
        if ($num > 0) {
            return 'OK';
        } else {
            return false;
        }
    }    

    function contRespuestas() {
        $where = "";
        $respuesta = new tab_respuesta();
        if ($_SESSION ["ROL_COD"] != 'ADM') {
            $where .= " AND tab_usuario.usu_id ='" . $_SESSION['USU_ID'] . "' ";
        }         
        $total = $respuesta->countBySQL("SELECT count (tab_respuesta.res_id)
                                                FROM
                                                tab_usuario
                                                INNER JOIN tab_encusuario ON tab_usuario.usu_id = tab_encusuario.usu_id
                                                INNER JOIN tab_respuesta ON tab_encusuario.res_id = tab_respuesta.res_id
                                                INNER JOIN tab_encuesta ON tab_respuesta.enc_id = tab_encuesta.enc_id
                                                INNER JOIN tab_unidad ON tab_unidad.uni_id = tab_encuesta.uni_id
                                                WHERE tab_unidad.uni_estado = 1
                                                AND tab_encuesta.enc_estado = 1
                                                AND (tab_respuesta.res_estado = 1 OR tab_respuesta.res_estado = 2)
                                                AND tab_encusuario.eus_estado = 1
                                                $where ");        
        
        return $total;
        
    }    
    
    function searchTree($exp_id, $cue_id2, $tra_id) {
        $tree = "";
        $this->usuario = new tab_usuario ();
        $this->tramite = new tab_tramite ();
        $this->respuesta = new tab_respuesta ();
        $this->cuerpos = new tab_cuerpos ();
        $row_usu = $this->usuario->dbselectByField("usu_id", $_SESSION ['USU_ID']);
        $this->usuario = $row_usu [0];
        $row = $this->respuesta->dbselectByField("exp_id", $exp_id);
        if (is_null($row)) {
            $tree .= "<li><a href='javascript:void(0)' onclick='return false;' class='suboptActx'>NO EXISTEN TIPOS DOCUMENTALES PARA EL respuesta</a></li>";
        } else {
            $row = $row [0];
            $ser_id = $row->ser_id;
            $rowt = $this->tramite->dbSelectBySQL("SELECT
                                                    tt.tra_id,
                                                    tt.tra_codigo,
                                                    tt.tra_descripcion,
                                                    st.ser_id
                                                    FROM tab_serietramite st
                                                    Inner Join tab_tramite tt ON st.tra_id = tt.tra_id
                                                    WHERE
                                                    st.ser_id =  '" . $ser_id . "' AND
                                                    st.sts_estado =  '1' AND
                                                    tt.tra_estado =  '1'
                                                    ORDER BY tt.tra_orden ASC");
            if (!is_null($rowt) && count($rowt)) {
                foreach ($rowt as $un) {
                    $rowc = $this->cuerpos->dbSelectBySQL("SELECT
                                                            tc.tra_id,
                                                            tc.trc_id,
                                                            cc.cue_id,
                                                            cc.cue_codigo,
                                                            cc.cue_descripcion,
                                                            cc.cue_orden
                                                            FROM
                                                            tab_tramitecuerpos tc
                                                            Inner Join tab_cuerpos cc ON tc.cue_id = cc.cue_id
                                                            WHERE
                                                            tc.trc_estado =  '1' AND
                                                            cc.cue_estado =  '1' AND
                                                            tc.tra_id =  '" . $un->tra_id . "'
                                                            ORDER BY cc.cue_orden ");

                    if (!is_null($rowc) && count($rowc)) {

                        $tree .= "<li id='grupo$un->tra_id' class='grupo'><span id='aparecegrupo$un->tra_id'><a href='javascript:void(0)'";
                        if ($tra_id == $un->tra_id) {
                            $tree .="onclick='cerrargrupo(" . $un->tra_id . ")'";
                        } else {
                            $tree .="onclick='abrirgrupo(" . $un->tra_id . ")'";
                        }

                        $tree .=" style='position:absolute' >.</a></span><a href='javascript:void(0)'  di='" . $un->tra_id . "a' class='pagAct' >" . "&nbsp; " . $un->tra_descripcion . " <font color='red' style='font-size:8px'>($un->tra_id)</font></a>";
                        $tree .= "<ul di='" . $un->tra_id . "aa'";
                        if ($tra_id == $un->tra_id) {
                            $tree .="style='display:block' ";
                        } else {
                            $tree .="style='display:none' ";
                        }
                        $tree .=">";
                        foreach ($rowc as $unc) {
                            $tree .= "        <li>"
                                    . "<a href='javascript:void(0)' onclick='return false;' id='" . $unc->cue_id . "-" . $un->tra_id . "' cue_id='" . $unc->cue_id . "' tra_id='" . $un->tra_id . "'";
                            if ($cue_id2 == $unc->cue_id) {
                                $tree.=" style='color:green;font-weight:bold' ";
                            }
                            $tree.="><span id='des$unc->cue_id'>"
                                    . "<img src='" . PATH_DOMAIN . "/web/lib/32/arrow-down.png' tra='$un->tra_id' cue='$unc->cue_id' title='Desplegar' border='0' width='30' onclick='desple($unc->cue_id)'/></span>"
                                    . "<img src='" . PATH_DOMAIN . "/web/lib/32/document-add.png' tra='$un->tra_id' cue='$unc->cue_id' class='addFile icon' title='Adicionar documento' />"
                                    . $unc->cue_descripcion . " <font color='#3366CC' style='font-size:9px'>($unc->cue_id)</font></a>";
                            $tree .= "<div id='$unc->cue_id'";
                            if ($cue_id2 == $unc->cue_id) {
                                $tree .="style='display:block'>";
                            } else {
                                $tree .="style='display:none'>";
                            }
                            $tree .="<ul id='" . $unc->cue_id . "-" . $un->tra_id . "x'>";

                            $sql = "SELECT usu.uni_id,
                                usu.usu_id,
                                fil.fil_id,
                                fil.fil_titulo,
                                fil.fil_subtitulo,
                                fil.fil_confidencialidad,
                                (SELECT fil_nomoriginal FROM tab_archivo_digital WHERE fil_id = fil.fil_id) as fil_nomoriginal,
                                (SELECT fil_extension FROM tab_archivo_digital WHERE fil_id = fil.fil_id) as fil_extension,
                                fil.fil_nro
                                FROM
                                tab_exparchivo AS exa
                                INNER JOIN tab_archivo AS fil ON exa.fil_id = fil.fil_id
                                INNER JOIN tab_encusuario AS eus ON exa.exp_id = eus.exp_id
                                INNER JOIN tab_usuario AS usu ON eus.usu_id = usu.usu_id
                                WHERE exa.tra_id = '" . $un->tra_id . "'
                                AND exa.cue_id = '" . $unc->cue_id . "'
                                AND exa.exp_id = '" . VAR3 . "'
                                AND eus.eus_estado = 1
                                AND fil.fil_estado = 1
                                AND usu.usu_estado = 1
                                AND exa.exa_estado=1 ORDER BY fil.fil_nro ";
                            $rowa = $this->cuerpos->dbSelectBySQL($sql);
                            foreach ($rowa as $una) {
                                $verarch = "";
                                switch ($una->fil_confidencialidad) {
                                    case '1' :
                                        $verarch = '<li><a href="#" onclick="return false"><span onclick="editardoc(' . $una->fil_id . ')" id="dis' . $una->fil_id . '"><b style="color:green" >' . $una->fil_nro . '</b></span><span style="display:none" id="editarc' . $una->fil_id . '"><input type="text" id="nrodoc' . $una->fil_id . '" style="text-align:center" size="1" value="' . $una->fil_nro . '"><button class="boton_google" onclick="guardardoc(' . $una->fil_id . ')">Guardar</button> <button class="boton_google" onclick="cancelardoc(' . $una->fil_id . ')">Cancelar</button></span>';
//                                        $verarch .= "<img src='" . PATH_DOMAIN . "/web/lib/32/document-add.png' tra='$un->tra_id' cue='$unc->cue_id' class='addFile icon' title='Adicionar documento'/>";
                                        $verarch .= "<img src='" . PATH_DOMAIN . "/web/lib/32/document-edit.png' file='$una->fil_id' class='updateFile icon' title='Editar Descripci&oacute;n del Documento'/>";
                                        $verarch .= "<img src='" . PATH_DOMAIN . "/web/lib/32/document-delete.png' file='$una->fil_id' class='deleteFile icon' title='Borrar Documento'/>";
                                        $verarch .= "<img src='" . PATH_DOMAIN . "/web/lib/32/b_view.png' file='$una->fil_id' restric='$una->fil_confidencialidad' class='view icon' title='Ver Datos Documento' />";
                                        if ($una->fil_extension) {
                                            $verarch .= "<img src='" . PATH_DOMAIN . "/web/lib/32/document-" . $una->fil_extension . ".png' file='$una->fil_id' restric='$una->fil_confidencialidad' class='viewFile icon' title='Ver Documento Digital'   />";
                                        }                                        
                                        $verarch .= "<img src='" . PATH_DOMAIN . "/web/lib/32/book3.png' file='$una->fil_id' restric='$una->fil_confidencialidad' class='tipodocumento icon' title='Cambiar de tipo documental' /><span style='display:none' id='cambiartipodoc" . $una->fil_id . "'><select name='' id='tra_id" . $una->fil_id . "' onchange='buscarcuerpo(this.value,$una->fil_id)'>";
                                        $verarch .="<option value='0'>-</option>";
                                        foreach ($rowt as $un1) {
                                            $verarch .="<option value=" . $un1->tra_id . ">" . $un1->tra_id . "</option>";
                                        }
                                        $verarch .="</select>";
                                        
                                        $verarch .="<span id='cuerpocambio" . $una->fil_id . "'><select id='cue_id" . $una->fil_id . "'><option></option></select></span>";
                                        $verarch .="<button class='boton_google' onclick='guardartipodocumento(" . $una->fil_id . ")'>Cambiar</button> <button class='boton_google' onclick='cancelarcambiardoc(" . $una->fil_id . ")'>Cancelar</button> </span> ";
                                        if ($una->fil_subtitulo) {
                                            if ($una->fil_nomoriginal) {
                                                $verarch .= $una->fil_titulo . " - " . $una->fil_subtitulo . " (" . $una->fil_nomoriginal . ")" . '</a></li>';
                                            } else {
                                                $verarch .= $una->fil_titulo . " - " . $una->fil_subtitulo . '</a></li>';
                                            }
                                        } else {
                                            if ($una->fil_nomoriginal) {
                                                $verarch .= $una->fil_titulo . " (" . $una->fil_nomoriginal . ")" . '</a></li>';
                                            } else {
                                                $verarch .= $una->fil_titulo . '</a></li>';
                                            }
                                        }

                                        break;


                                    case '2' :
                                        $verarch = '<li><a href="#" onclick="return false"><span onclick="editardoc(' . $una->fil_id . ')" id="dis' . $una->fil_id . '"><b style="color:green" >' . $una->fil_nro . '</b></span><span style="display:none" id="editarc' . $una->fil_id . '"><input type="text" id="nrodoc' . $una->fil_id . '" style="text-align:center" size="1" value="' . $una->fil_nro . '"><button class="boton_google" onclick="guardardoc(' . $una->fil_id . ')">Guardar</button> <button class="boton_google" onclick="cancelardoc(' . $una->fil_id . ')">Cancelar</button></span>';
//                                        $verarch .= "<img src='" . PATH_DOMAIN . "/web/lib/32/document-add.png' tra='$un->tra_id' cue='$unc->cue_id' class='addFile icon' title='Adicionar documento'/>";
                                        $verarch .= "<img src='" . PATH_DOMAIN . "/web/lib/32/document-edit.png' file='$una->fil_id' class='updateFile icon' title='Editar Descripci&oacute;n del Documento'/>";
                                        $verarch .= "<img src='" . PATH_DOMAIN . "/web/lib/32/document-delete.png' file='$una->fil_id' class='deleteFile icon' title='Borrar Documento'/>";
                                        $verarch .= "<img src='" . PATH_DOMAIN . "/web/lib/32/b_view.png' file='$una->fil_id' restric='$una->fil_confidencialidad' class='view icon' title='Ver Datos Documento' />";
                                        if ($una->fil_extension) {
                                            $verarch .= "<img src='" . PATH_DOMAIN . "/web/lib/32/document-" . $una->fil_extension . ".png' file='$una->fil_id' restric='$una->fil_confidencialidad' class='viewFile icon' title='Ver Documento Digital'   />";
                                        }                                        
                                        $verarch .= "<img src='" . PATH_DOMAIN . "/web/lib/32/book3.png' file='$una->fil_id' restric='$una->fil_confidencialidad' class='tipodocumento icon' title='Cambiar de tipo documental' /><span style='display:none' id='cambiartipodoc" . $una->fil_id . "'><select name='' id='tra_id" . $una->fil_id . "' onchange='buscarcuerpo(this.value,$una->fil_id)'>";
                                        $verarch .="<option value='0'>-</option>";
                                        foreach ($rowt as $un1) {
                                            $verarch .="<option value=" . $un1->tra_id . ">" . $un1->tra_id . "</option>";
                                        }
                                        $verarch .="</select>";
                                        
                                        $verarch .="<span id='cuerpocambio" . $una->fil_id . "'><select id='cue_id" . $una->fil_id . "'><option></option></select></span>";
                                        $verarch .="<button class='boton_google' onclick='guardartipodocumento(" . $una->fil_id . ")'>Cambiar</button> <button class='boton_google' onclick='cancelarcambiardoc(" . $una->fil_id . ")'>Cancelar</button> </span> ";
                                        if ($una->fil_subtitulo) {
                                            if ($una->fil_nomoriginal) {
                                                $verarch .= $una->fil_titulo . " - " . $una->fil_subtitulo . " (" . $una->fil_nomoriginal . ")" . '</a></li>';
                                            } else {
                                                $verarch .= $una->fil_titulo . " - " . $una->fil_subtitulo . '</a></li>';
                                            }
                                        } else {
                                            if ($una->fil_nomoriginal) {
                                                $verarch .= $una->fil_titulo . " (" . $una->fil_nomoriginal . ")" . '</a></li>';
                                            } else {
                                                $verarch .= $una->fil_titulo . '</a></li>';
                                            }
                                        }
                                        break;


                                }
                                $tree .= $verarch;
                            }
                            $tree .= "</ul></div>";
                            $tree .= "</li>";
                        }
                        $tree .= "</ul>";
                        $tree .= "</li>";
                    }
                }
            } else {
                $tree .= "<li><a href='#' class='pagAct' onclick='return false'>NO EXISTEN TIPOS DOCUMENTALES PARA EL respuesta ESCOGIDO</a></li>";
            }
        }
        return $tree;
    }

    function linkTree($exp_id, $tra_id, $cue_id) {
        $respuesta = new tab_respuesta ();
        $tab_respuesta = $respuesta->dbselectById($exp_id);

        $tab_expisadg = new tab_expisadg ();
        $expisadg = $tab_expisadg->dbselectById($exp_id);

        $serie = new respuesta ();
        $tab_tramite = new Tab_tramite ();
        $tab_tramite = $tab_tramite->dbselectById($tra_id);
        $tab_cuerpo = new Tab_cuerpos ();
        $tab_cuerpo = $tab_cuerpo->dbselectById($cue_id);

        $flecha = "<img src='" . PATH_DOMAIN . "/web/img/arrow.png' width=\"12px\" height=\"12px\"/>";
        $serie_des = utf8_decode($serie->getTitle($tab_respuesta->getSer_id()));
        $exp_des = utf8_decode($expisadg->getExp_titulo());
        $tramite = utf8_decode($tab_tramite->getTra_descripcion());
        $cuerpo = utf8_decode($tab_cuerpo->getCue_descripcion());

        return "<a href='" . PATH_DOMAIN . "/estrucDocumental/'> $serie_des</a> $flecha
                <a href='" . PATH_DOMAIN . "/estrucDocumental/viewTree/" . $exp_id . "/'> $exp_des</a> $flecha
                <a href='" . PATH_DOMAIN . "/estrucDocumental/viewTree/" . $exp_id . "/'> $tramite </a> $flecha
                <a href='" . PATH_DOMAIN . "/estrucDocumental/viewTree/$exp_id/'> $cuerpo </a> ";
    }

    function linkTreeUno($exp_id) {
        $exp = new respuesta ();
        $tree = "";
        $this->respuesta = new respuesta ();
        $this->tramite = new tab_tramite ();
        $this->respuesta = new tab_respuesta ();
        $this->cuerpos = new tab_cuerpos ();
        $row = $this->respuesta->dbselectByField("exp_id", $exp_id);
        if (is_null($row)) {
            return $tree;
        } else {
            $row = $row [0];
            $ser_id = $row->ser_id;

            // Expisag
            $tab_expisadg = new Tab_expisadg();
            $row2 = $tab_expisadg->dbselectByField("exp_id", $exp_id);
            $row2 = $row2 [0];

            $pathAnterior = PATH_DOMAIN . "/" . VAR1 . "/";
            $titulo = $this->respuesta->getTitle($ser_id);
            $nuevoEnlace = $row2->exp_titulo;
            $pathActual = PATH_DOMAIN . "/" . VAR1 . "/" . VAR2 . "/" . VAR3;
            return array($pathAnterior, $titulo, $pathActual, $nuevoEnlace);
        }
    }

    function generaCodigo($ser_id) {
        $res = "";
        $this->respuesta = new tab_respuesta ();
        $sql = "SELECT
                ser_exp
                FROM tab_respuesta
                WHERE ser_id = $ser_id
                ORDER BY 1 DESC
                LIMIT 1 OFFSET 0";
        $result = $this->respuesta->dbSelectBySQL($sql);
        if ($result != null) {
            foreach ($result as $row) {
                $res = $row->ser_exp + 1;
            }
        }
        return $res;
    }

}

?>
