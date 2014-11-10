<?php

/**
 * enccampo.class.php Model
 *
 * @package
 * @author lic. castellon
 * @copyright DGGE
 * @version $Id$ 2014.04.24
 * @access public
 */
class enccampo extends tab_enccampo {

    function __construct() {
        //parent::__construct();
        $this->enccampo = new tab_enccampo();
    }

    function obtenerSelect($default = null) {
        $sql = "SELECT *
                FROM tab_enccampo
                WHERE tab_enccampo.ecp_estado = 1
                ORDER BY ecp_id ASC ";
        $row = $this->enccampo->dbselectBySQL($sql);
        $option = "";
        foreach ($row as $val) {
            if ($default == $val->ecp_id)
                $option .="<option value='" . $val->ecp_id . "' selected>" . $val->ecp_tipdat . "</option>";
            else
                $option .="<option value='" . $val->ecp_id . "'>" . $val->ecp_tipdat . "</option>";
        }
        return $option;
    }

    function obtenerSelectTipoDato($default = null) {
        $option = "";
        if ($default == 'Texto') {
            $option .="<option value='Texto' selected>Texto</option>";
            $option .="<option value='Numero'>N&uacute;mero</option>";
            $option .="<option value='Fecha'>Fecha</option>";
            $option .="<option value='Decimal'>Decimal</option>";
            $option .="<option value='Lista'>Lista</option>";
			$option .="<option value='CheckBox'>Casilla de verificaci&oacute;n</option>";
			$option .="<option value='RadioButton'>Boton de opci&oacute;n</option>";
        } else if ($default == 'Numero') {
            $option .="<option value='Texto'>Texto</option>";
            $option .="<option value='Numero' selected>N&uacute;mero</option>";
            $option .="<option value='Fecha'>Fecha</option>";
            $option .="<option value='Decimal'>Decimal</option>";
            $option .="<option value='Lista'>Lista</option>";
			$option .="<option value='CheckBox'>Casilla de verificaci&oacute;n</option>";
			$option .="<option value='RadioButton'>Boton de opci&oacute;n</option>";
        } else if ($default == 'Fecha') {
            $option .="<option value='Texto'>Texto</option>";
            $option .="<option value='Numero'>N&uacute;mero</option>";
            $option .="<option value='Fecha' selected>Fecha</option>";
            $option .="<option value='Decimal'>Decimal</option>";
            $option .="<option value='Lista'>Lista</option>";
			$option .="<option value='CheckBox'>Casilla de verificaci&oacute;n</option>";
			$option .="<option value='RadioButton'>Boton de opci&oacute;n</option>";
        } else if ($default == 'Decimal') {
            $option .="<option value='Texto'>Texto</option>";
            $option .="<option value='Numero'>N&uacute;mero</option>";
            $option .="<option value='Fecha'>Fecha</option>";
            $option .="<option value='Decimal' selected>Decimal</option>";
            $option .="<option value='Lista'>Lista</option>";
			$option .="<option value='CheckBox'>Casilla de verificaci&oacute;n</option>";
			$option .="<option value='RadioButton'>Boton de opci&oacute;n</option>";
        } else if ($default == 'Lista') {
            $option .="<option value='Texto'>Texto</option>";
            $option .="<option value='Numero'>N&uacute;mero</option>";
            $option .="<option value='Fecha'>Fecha</option>";
            $option .="<option value='Decimal'>Decimal</option>";
            $option .="<option value='Lista' selected>Lista</option>";
			$option .="<option value='CheckBox'>Casilla de verificaci&oacute;n</option>";
			$option .="<option value='RadioButton'>Boton de opci&oacute;n</option>";
        } else if ($default == 'CheckBox') {
            $option .="<option value='Texto'>Texto</option>";
            $option .="<option value='Numero'>N&uacute;mero</option>";
            $option .="<option value='Fecha'>Fecha</option>";
            $option .="<option value='Decimal'>Decimal</option>";
            $option .="<option value='Lista'>Lista</option>";
			$option .="<option value='CheckBox' selected>Casilla de verificaci&oacute;n</option>";
			$option .="<option value='RadioButton'>Boton de opci&oacute;n</option>";
        } else if ($default == 'Lista') {
            $option .="<option value='Texto'>Texto</option>";
            $option .="<option value='Numero'>N&uacute;mero</option>";
            $option .="<option value='Fecha'>Fecha</option>";
            $option .="<option value='Decimal'>Decimal</option>";
            $option .="<option value='Lista'>Lista</option>";
			$option .="<option value='CheckBox'>Casilla de verificaci&oacute;n</option>";
			$option .="<option value='RadioButton' selected>Boton de opci&oacute;n</option>";
        } else {
            $option .="<option value='Texto'>Texto</option>";
            $option .="<option value='Numero'>N&uacute;mero</option>";
            $option .="<option value='Fecha'>Fecha</option>";
            $option .="<option value='Decimal'>Decimal</option>";
            $option .="<option value='Lista'>Lista</option>";
			$option .="<option value='CheckBox'>Casilla de verificaci&oacute;n</option>";
			$option .="<option value='RadioButton'>Boton de opci&oacute;n</option>";			
        }

        return $option;
    }

    function obtenerSelectCampos($enc_id = null) {
        $this->enccampolista = new tab_enccampolista();
        $sql = "SELECT
                tab_enccampo.ecp_id,
                tab_enccampo.enc_id,
                tab_enccampo.egr_id,
                tab_enccampo.ecp_orden,
                tab_enccampo.ecp_eti,
                tab_enccampo.ecp_tipdat,
                tab_enccampo.ecp_estado,
                tab_enccampo.ecp_nombre
                FROM
                tab_enccampo
                WHERE tab_enccampo.ecp_estado = 1
                AND tab_enccampo.enc_id = $enc_id
                ORDER BY tab_enccampo.egr_id,
                tab_enccampo.ecp_orden ASC ";
        $row = $this->enccampo->dbselectBySQL($sql);
        $option = "";
        foreach ($row as $val) {
            if ($val->ecp_tipdat == 'Lista') {
                $option .="<tr><td>" . $val->ecp_eti . "</td>";
                $option .= "<td colspan='3'>
				
				<select name='" . $val->ecp_id . "' id='" . $val->ecp_id . "' title='" . $val->ecp_nombre . "' class='required'>
				<option value='' selected='selected'>(seleccionar)</option>";

                // Lista
                $sql = "SELECT
                        tab_enccampo.ecp_id,
                        tab_enccampolista.ecl_id,
                        tab_enccampolista.ecl_orden,
                        tab_enccampolista.ecl_valor,
                        tab_enccampolista.ecl_estado
                        FROM
                        tab_enccampo
                        INNER JOIN tab_enccampolista ON tab_enccampo.ecp_id = tab_enccampolista.ecp_id
                        WHERE tab_enccampolista.ecl_estado = 1 
                        AND tab_enccampo.ecp_id = $val->ecp_id 
                        ORDER BY tab_enccampolista.ecl_orden ";
                $row2 = $this->enccampolista->dbselectBySQL($sql);
                foreach ($row2 as $val2) {
                    $option .="<option value='" . $val2->ecl_id . "'>" . $val2->ecl_valor . "</option>";
                }
                $option .= "</select><span class='error-requerid'>*</span>";
                $option .="</td>";
                $option .="</tr>";

            // CheckBox
            }else if ($val->ecp_tipdat == 'CheckBox') {
                $option .="<tr><td>" . $val->ecp_eti . "</td>";
                $option .= "<td colspan='3'>";
				
                // Lista
                $sql = "SELECT
                        tab_enccampo.ecp_id,
                        tab_enccampolista.ecl_id,
                        tab_enccampolista.ecl_orden,
                        tab_enccampolista.ecl_valor,
                        tab_enccampolista.ecl_estado
                        FROM
                        tab_enccampo
                        INNER JOIN tab_enccampolista ON tab_enccampo.ecp_id = tab_enccampolista.ecp_id
                        WHERE tab_enccampolista.ecl_estado = 1 
                        AND tab_enccampo.ecp_id = $val->ecp_id 
                        ORDER BY tab_enccampolista.ecl_orden ";
                $row2 = $this->enccampolista->dbselectBySQL($sql);
                foreach ($row2 as $val2) {
                    //$option .="<option value='" . $val2->ecl_id . "'>" . $val2->ecl_valor . "</option>";
			$option .="<input class='required' type='checkbox' name='rcv_valorC[]' id='" . $val2->ecl_id . "' value='" . $val2->ecl_id . "'>" . $val2->ecl_valor . "";					
                }
                $option .= "<span class='error-requerid'>*</span>";
                $option .="</td>";
                $option .="</tr>";
				
            // RadioButton
            }else if ($val->ecp_tipdat == 'RadioButton') {
                $option .="<tr><td>" . $val->ecp_eti . "</td>";
                $option .= "<td colspan='3'>";
				
                // Lista
                $sql = "SELECT
                        tab_enccampo.ecp_id,
                        tab_enccampolista.ecl_id,
                        tab_enccampolista.ecl_orden,
                        tab_enccampolista.ecl_valor,
                        tab_enccampolista.ecl_estado
                        FROM
                        tab_enccampo
                        INNER JOIN tab_enccampolista ON tab_enccampo.ecp_id = tab_enccampolista.ecp_id
                        WHERE tab_enccampolista.ecl_estado = 1 
                        AND tab_enccampo.ecp_id = $val->ecp_id 
                        ORDER BY tab_enccampolista.ecl_orden ";
                $row2 = $this->enccampolista->dbselectBySQL($sql);
                foreach ($row2 as $val2) {
                    //$option .="<option value='" . $val2->ecl_id . "'>" . $val2->ecl_valor . "</option>";
                    $option .="<input class='required' type='radio' name='rcv_valor' id='" . $val2->ecl_id . "' value='" . $val2->ecl_id . "'> " . $val2->ecl_valor . "";
                }
                $option .= "<span class='error-requerid'>*</span>";
                $option .="</td>";
                $option .="</tr>";
				
            } else {
                // Campo
                $option .="<tr><td>" . $val->ecp_eti . "</td>";
                if ($val->ecp_tipdat == 'Texto') {
                    $option .="<td colspan='3'>
                              <input name='" . $val->ecp_id . "' id='" . $val->ecp_id . "' value='' type='text'
                                     size='40' autocomplete='off' maxlength='1024' class=''
                                     title='" . $val->ecp_nombre . "' />
                              <span class='error-requerid'>*</span>
                              </td>";
                } else if ($val->ecp_tipdat == 'Numero') {
                    $option .="<td colspan='3'>
                              <input name='" . $val->ecp_id . "' id='" . $val->ecp_id . "' value='' type='text'
                                     size='40' autocomplete='off' maxlength='20' class='onlynumeric'
                                     title='" . $val->ecp_nombre . "' />
                              <span class='error-requerid'>*</span>
                              </td>";
                } else if ($val->ecp_tipdat == 'Decimal') {
                    $option .="<td colspan='3'>
                              <input name='" . $val->ecp_id . "' id='" . $val->ecp_id . "' value='' type='text'
                                     size='40' autocomplete='off' maxlength='20' class='numeric'
                                     title='" . $val->ecp_nombre . "' />
                              <span class='error-requerid'>*</span>
                              </td>";
                } else if ($val->ecp_tipdat == 'Fecha') {
                    $option .="<td colspan='3'>
                              <input name='" . $val->ecp_id . "' id='" . $val->ecp_nombre . "' value='' type='text'
                                     size='40' autocomplete='off' maxlength='20' class=''
                                     title='" . $val->ecp_nombre . "' />
                              <span class='error-requerid'>*</span>
                              </td>";


                    $option .="<script type='text/javascript'>";
                    $option .= "jQuery(document).ready(function($) { ";
                    $option .= "$('#" . $val->ecp_nombre . "').datepicker({
                                changeMonth: true,
                                changeYear: true,
                                yearRange:'c-5:c+10',
                                dateFormat: 'yy-mm-dd',
                                dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo',
                                    'Junio', 'Julio', 'Agosto', 'Septiembre',
                                    'Octubre', 'Noviembre', 'Diciembre'],
                                monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr',
                                    'May', 'Jun', 'Jul', 'Ago',
                                    'Sep', 'Oct', 'Nov', 'Dic']
                            });";
                    $option .= "});";
                    $option .="</script>";
                }

                $option .="</tr>";
            }
        }
        return $option;
    }

    function obtenerSelectCamposEdit($enc_id = null, $res_id = null) {
        $this->enccampolista = new tab_enccampolista();
        $sql = "SELECT
                tab_enccampo.ecp_id,
                tab_enccampo.enc_id,
                tab_enccampo.ecp_orden,
                tab_enccampo.ecp_eti,
                tab_enccampo.ecp_tipdat,
                tab_enccampo.ecp_estado,
                tab_enccampo.ecp_nombre
                FROM
                tab_enccampo
                WHERE tab_enccampo.ecp_estado = 1
                AND tab_enccampo.enc_id = $enc_id
                ORDER BY tab_enccampo.ecp_orden, 
                tab_enccampo.ecp_orden ASC ";
        $row = $this->enccampo->dbselectBySQL($sql);
        $option = "";

        // Trace
        $valor = "";
        foreach ($row as $val) {
            if ($val->ecp_tipdat == 'Lista') {
                $option .="<tr><td>" . $val->ecp_eti . "</td>";
                $option .= "<td colspan='3'>
                    <select name='" . $val->ecp_id . "' id='" . $val->ecp_id . "' title='" . $val->ecp_nombre . "' class='required'>
                    <option value=''>(seleccionar)</option>";

                // Lista
                $sql = "SELECT
                        tab_enccampo.ecp_id,
                        tab_enccampolista.ecl_id,
                        tab_enccampolista.ecl_orden,
                        tab_enccampolista.ecl_valor,
                        tab_enccampolista.ecl_estado
                        FROM
                        tab_enccampo
                        INNER JOIN tab_enccampolista ON tab_enccampo.ecp_id = tab_enccampolista.ecp_id
                        WHERE tab_enccampolista.ecl_estado = 1 
                        AND tab_enccampo.ecp_id = $val->ecp_id 
                        ORDER BY tab_enccampolista.ecl_orden ";
                $row2 = $this->enccampolista->dbselectBySQL($sql);
                foreach ($row2 as $val2) {
                    // Find value
                    // Find Value
                    $sql = "SELECT
                            tab_rescampovalor.rcv_id,
                            tab_rescampovalor.res_id,
                            tab_rescampovalor.ecp_id,
                            tab_rescampovalor.ecl_id,
                            tab_rescampovalor.rcv_valor,
                            tab_rescampovalor.rcv_estado
                            FROM
                            tab_enccampo
                            INNER JOIN tab_rescampovalor ON tab_enccampo.ecp_id = tab_rescampovalor.ecp_id
                            WHERE tab_rescampovalor.res_id = '$res_id'
                            AND tab_enccampo.ecp_id = $val->ecp_id
                            ORDER BY tab_enccampo.egr_id,
                            tab_enccampo.ecp_orden ";
                    $row5 = $this->enccampo->dbselectBySQL($sql);
                    // $valor = $row5[0]->ecl_id;
                    foreach ($row5 as $list) {
                        $valor = $list->ecl_id;
                    }

                    if ($valor == $val2->ecl_id)
                        $option .="<option value='" . $val2->ecl_id . "' selected>" . $val2->ecl_valor . "</option>";
                    else
                        $option .="<option value='" . $val2->ecl_id . "'>" . $val2->ecl_valor . "</option>";
                }
                $option .= "</select><span class='error-requerid'>*</span>";
                $option .="</td>";
                $option .="</tr>";
				
            // CheckBox
            } else if ($val->ecp_tipdat == 'CheckBox') {
                $option .="<tr><td>" . $val->ecp_eti . "</td>";
                $option .= "<td colspan='3'>";

                // CheckBox
                $sql = "SELECT
                        tab_enccampo.ecp_id,
                        tab_enccampolista.ecl_id,
                        tab_enccampolista.ecl_orden,
                        tab_enccampolista.ecl_valor,
                        tab_enccampolista.ecl_estado
                        FROM
                        tab_enccampo
                        INNER JOIN tab_enccampolista ON tab_enccampo.ecp_id = tab_enccampolista.ecp_id
                        WHERE tab_enccampolista.ecl_estado = 1 
                        AND tab_enccampo.ecp_id = $val->ecp_id 
                        ORDER BY tab_enccampolista.ecl_orden ";
                $row2 = $this->enccampolista->dbselectBySQL($sql);
                foreach ($row2 as $val2) {
                    // Find value
                    $sql = "SELECT
                            tab_rescampovalor.rcv_id,
                            tab_rescampovalor.res_id,
                            tab_rescampovalor.ecp_id,
                            tab_rescampovalor.ecl_id,
                            tab_rescampovalor.rcv_valor,
                            tab_rescampovalor.rcv_estado
                            FROM
                            tab_enccampo
                            INNER JOIN tab_rescampovalor ON tab_enccampo.ecp_id = tab_rescampovalor.ecp_id
                            WHERE tab_rescampovalor.res_id = '$res_id'
                            AND tab_enccampo.ecp_id = $val->ecp_id
                            ORDER BY tab_enccampo.egr_id,
                            tab_enccampo.ecp_orden ";
                    $row5 = $this->enccampo->dbselectBySQL($sql);
                    // $valor = $row5[0]->ecl_id;
                                        
                    if ($row5){
                        foreach ($row5 as $list) {
                            // Revisar
                            $flag = false;
                            $valores = explode (',',$list->rcv_valor);
                            foreach ($valores as $valor) {
                                if ($valor == $val2->ecl_id){
                                    $flag = true;
                                    break;
                                }
                            }                                                        
                        }
                        if ($flag == true){
                            $option .="<input type='checkbox' name='rcv_valorC[]' id='" . $val2->ecl_id . "' value='" . $val2->ecl_id . "' checked='checked' >" . $val2->ecl_valor . "";
                        }else{
                            $option .="<input type='checkbox' name='rcv_valorC[]' id='" . $val2->ecl_id . "' value='" . $val2->ecl_id . "'> " . $val2->ecl_valor . "";
                        }
                    }else{
                        if ($valor == $val2->ecl_id){                            
                            $option .="<input type='checkbox' name='rcv_valorC[]' id='" . $val2->ecl_id . "' value='" . $val2->ecl_id . "' checked='checked' >" . $val2->ecl_valor . "";                           
                        }else{
                            $option .="<input type='checkbox' name='rcv_valorC[]' id='" . $val2->ecl_id . "' value='" . $val2->ecl_id . "'> " . $val2->ecl_valor . "";                        
                        }
                    }
                    
                }
                $option .= "<span class='error-requerid'>*</span>";
                $option .="</td>";
                $option .="</tr>";
            
            // RadioButton
            } else if ($val->ecp_tipdat == 'RadioButton') {
                $option .="<tr><td>" . $val->ecp_eti . "</td>";
                $option .= "<td colspan='3'>";

                // Lista
                $sql = "SELECT
                        tab_enccampo.ecp_id,
                        tab_enccampolista.ecl_id,
                        tab_enccampolista.ecl_orden,
                        tab_enccampolista.ecl_valor,
                        tab_enccampolista.ecl_estado
                        FROM
                        tab_enccampo
                        INNER JOIN tab_enccampolista ON tab_enccampo.ecp_id = tab_enccampolista.ecp_id
                        WHERE tab_enccampolista.ecl_estado = 1 
                        AND tab_enccampo.ecp_id = $val->ecp_id 
                        ORDER BY tab_enccampolista.ecl_orden ";
                $row2 = $this->enccampolista->dbselectBySQL($sql);
                foreach ($row2 as $val2) {
                    // Find value
                    // Find Value
                    $sql = "SELECT
                            tab_rescampovalor.rcv_id,
                            tab_rescampovalor.res_id,
                            tab_rescampovalor.ecp_id,
                            tab_rescampovalor.ecl_id,
                            tab_rescampovalor.rcv_valor,
                            tab_rescampovalor.rcv_estado
                            FROM
                            tab_enccampo
                            INNER JOIN tab_rescampovalor ON tab_enccampo.ecp_id = tab_rescampovalor.ecp_id
                            WHERE tab_rescampovalor.res_id = '$res_id'
                            AND tab_enccampo.ecp_id = $val->ecp_id
                            ORDER BY tab_enccampo.egr_id,
                            tab_enccampo.ecp_orden ";
                    $row5 = $this->enccampo->dbselectBySQL($sql);
                    // $valor = $row5[0]->ecl_id;
                    foreach ($row5 as $list) {
                        $valor = $list->rcv_valor;
                    }
                    if ($valor == $val2->ecl_id)
                        $option .="<input type='radio' name='rcv_valor' id='" . $val2->ecl_id . "' value='" . $val2->ecl_id . "' checked='checked' > " . $val2->ecl_valor . "";
                    else
                        $option .="<input type='radio' name='rcv_valor' id='" . $val2->ecl_id . "' value='" . $val2->ecl_id . "'> " . $val2->ecl_valor . "";
                }
                $option .= "<span class='error-requerid'>*</span>";
                $option .="</td>";
                $option .="</tr>";
				
				
            } else {
                // Find Value
                $sql = "SELECT
                        tab_rescampovalor.rcv_id,
                        tab_rescampovalor.res_id,
                        tab_rescampovalor.ecp_id,
                        tab_rescampovalor.ecl_id,
                        tab_rescampovalor.rcv_valor,
                        tab_rescampovalor.rcv_estado
                        FROM
                        tab_enccampo
                        INNER JOIN tab_rescampovalor ON tab_enccampo.ecp_id = tab_rescampovalor.ecp_id
                        WHERE tab_rescampovalor.res_id = '$res_id'
                        AND tab_enccampo.ecp_id = $val->ecp_id
                        ORDER BY tab_enccampo.egr_id,
                        tab_enccampo.ecp_orden";
                $this->rescampovalor = new tab_rescampovalor();
                $row5 = $this->rescampovalor->dbselectBySQL($sql);
                if (isset($row5[0]->rcv_valor)) {
                    $valor = $row5[0]->rcv_valor;
                } else {
                    $valor = "";
                }
                // Campo
                $option .="<tr><td>" . $val->ecp_eti . "</td>";
                if ($val->ecp_tipdat == 'Texto') {
                    $option .="<td colspan='3'>
                              <input name='" . $val->ecp_id . "' id='" . $val->ecp_id . "' value='" . $valor . "' type='text'
                                     size='40' autocomplete='off' maxlength='1024' class='required'
                                     title='" . $val->ecp_nombre . "' />
                              <span class='error-requerid'>*</span>
                              </td>";
                } else if ($val->ecp_tipdat == 'Numero') {
                    $option .="<td colspan='3'>
                              <input name='" . $val->ecp_id . "' id='" . $val->ecp_id . "' value='" . $valor . "' type='text'
                                     size='40' autocomplete='off' maxlength='20' class='required onlynumeric'
                                     title='" . $val->ecp_nombre . "' />
                              <span class='error-requerid'>*</span>
                              </td>";
                } else if ($val->ecp_tipdat == 'Decimal') {
                    $option .="<td colspan='3'>
                              <input name='" . $val->ecp_id . "' id='" . $val->ecp_id . "' value='" . $valor . "' type='text'
                                     size='40' autocomplete='off' maxlength='20' class='required numeric'
                                     title='" . $val->ecp_nombre . "' />
                              <span class='error-requerid'>*</span>
                              </td>";
                } else if ($val->ecp_tipdat == 'Fecha') {
                    $option .="<td colspan='3'>
                              <input name='" . $val->ecp_id . "' id='" . $val->ecp_nombre . "' value='" . $valor . "' type='text'
                                     size='10' autocomplete='off' maxlength='16' class='required'
                                     title='" . $val->ecp_nombre . "' />
                              <span class='error-requerid'>*</span>
                              </td>";


                    $option .="<script type='text/javascript'>";
                    $option .= "jQuery(document).ready(function($) { ";
                    $option .= "$('#" . $val->ecp_nombre . "').datepicker({
                                changeMonth: true,
                                changeYear: true,
                                yearRange:'c-5:c+10',
                                dateFormat: 'yy-mm-dd',
                                dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo',
                                    'Junio', 'Julio', 'Agosto', 'Septiembre',
                                    'Octubre', 'Noviembre', 'Diciembre'],
                                monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr',
                                    'May', 'Jun', 'Jul', 'Ago',
                                    'Sep', 'Oct', 'Nov', 'Dic']
                            });";
                    $option .= "});";
                    $option .="</script>";
                }

                $option .="</tr>";
            }
        }
        return $option;
    }

    function obtenerSelectCamposShow($enc_id = null, $res_id = null) {
        $this->enccampolista = new tab_enccampolista();
        $sql = "SELECT
                tab_enccampo.ecp_id,
                tab_enccampo.enc_id,
                tab_enccampo.ecp_orden,
                tab_enccampo.ecp_eti,
                tab_enccampo.ecp_tipdat,
                tab_enccampo.ecp_estado,
                tab_enccampo.ecp_nombre
                FROM
                tab_enccampo
                WHERE tab_enccampo.ecp_estado = 1
                AND tab_enccampo.enc_id = $enc_id
                ORDER BY tab_enccampo.egr_id, 
                tab_enccampo.ecp_orden ASC ";
        $row = $this->enccampo->dbselectBySQL($sql);
        $option = "";
        $count = 0;
        // Trace
        if (count($row) > 0) {
            foreach ($row as $val) {

                if ($val->ecp_tipdat == 'Lista') {

                    $option .="<tr>";

                    // Etiqueta
                    $option .='<td>' . '<b>' . $val->ecp_eti . '</b>';

                    // Lista
                    $sql = "SELECT
                            tab_enccampo.ecp_id,
                            tab_enccampolista.ecl_id,
                            tab_enccampolista.ecl_valor,
                            tab_enccampolista.ecl_estado
                            FROM
                            tab_enccampo
                            INNER JOIN tab_enccampolista ON tab_enccampo.ecp_id = tab_enccampolista.ecp_id
                            WHERE tab_enccampolista.ecl_estado = 1 
                            AND tab_enccampo.ecp_id = $val->ecp_id 
                            ORDER BY tab_enccampolista.ecl_orden ";
                    $row2 = $this->enccampolista->dbselectBySQL($sql);
                    foreach ($row2 as $val2) {
                        // Find value
                        $sql = "SELECT
                                tab_rescampovalor.rcv_id,
                                tab_rescampovalor.res_id,
                                tab_rescampovalor.ecp_id,
                                tab_rescampovalor.ecl_id,
                                tab_rescampovalor.rcv_valor,
                                tab_rescampovalor.rcv_estado
                                FROM
                                tab_enccampo
                                INNER JOIN tab_rescampovalor ON tab_enccampo.ecp_id = tab_rescampovalor.ecp_id
                                WHERE tab_rescampovalor.res_id = '$res_id'
                                AND tab_enccampo.ecp_id = $val->ecp_id
                                ORDER BY tab_enccampo.egr_id, 
                                tab_enccampo.ecp_orden";
                        $row5 = $this->enccampo->dbselectBySQL($sql);
                        if (isset($val2->ecl_valor)) {
                            $valor = $row5[0]->ecl_id;
                            if ($row5[0]->ecl_id == $val2->ecl_id)
                                $option .= ' ' . $val2->ecl_valor;
                        }else {
                            $valor = "";
                            $option .= "";
                        }
                    }

                    $option .="</td>";
                    $option .="</tr>";
                
                    
                }else if ($val->ecp_tipdat == 'CheckBox') {

                    $option .="<tr>";

                    // Etiqueta
                    $option .='<td>' . '<b>' . $val->ecp_eti . '</b>';

                    // Lista
                    $sql = "SELECT
                            tab_enccampo.ecp_id,
                            tab_enccampolista.ecl_id,
                            tab_enccampolista.ecl_valor,
                            tab_enccampolista.ecl_estado
                            FROM
                            tab_enccampo
                            INNER JOIN tab_enccampolista ON tab_enccampo.ecp_id = tab_enccampolista.ecp_id
                            WHERE tab_enccampolista.ecl_estado = 1 
                            AND tab_enccampo.ecp_id = $val->ecp_id 
                            ORDER BY tab_enccampolista.ecl_orden ";
                    $row2 = $this->enccampolista->dbselectBySQL($sql);
                    foreach ($row2 as $val2) {
                        // Find value
                        $sql = "SELECT
                                tab_rescampovalor.rcv_id,
                                tab_rescampovalor.res_id,
                                tab_rescampovalor.ecp_id,
                                tab_rescampovalor.ecl_id,
                                tab_rescampovalor.rcv_valor,
                                tab_rescampovalor.rcv_estado
                                FROM
                                tab_enccampo
                                INNER JOIN tab_rescampovalor ON tab_enccampo.ecp_id = tab_rescampovalor.ecp_id
                                WHERE tab_rescampovalor.res_id = '$res_id'
                                AND tab_enccampo.ecp_id = $val->ecp_id
                                ORDER BY tab_enccampo.egr_id, 
                                tab_enccampo.ecp_orden";
                        $row5 = $this->enccampo->dbselectBySQL($sql);
                        if (isset($row5)) {                            
                            foreach ($row5 as $list) {
                                // Revisar
                                $flag = false;
                                $valores = explode (',',$list->rcv_valor);
                                foreach ($valores as $valor) {
                                    if ($valor == $val2->ecl_id){
                                        $flag = true;
                                        break;
                                    }
                                }                                                        
                            }
                            if ($flag == true){
                                $option .= ' ' . $val2->ecl_valor . ', '  ;
                            }else{
                                $option .= ' ';
                            }                            
                            
                        }else {
                            $valor = "";
                            $option .= "";
                        }
                    }

                    $option .="</td>";
                    $option .="</tr>";

                }else if ($val->ecp_tipdat == 'RadioButton') {

                    $option .="<tr>";

                    // Etiqueta
                    $option .='<td>' . '<b>' . $val->ecp_eti . '</b>';

                    // Lista
                    $sql = "SELECT
                            tab_enccampo.ecp_id,
                            tab_enccampolista.ecl_id,
                            tab_enccampolista.ecl_valor,
                            tab_enccampolista.ecl_estado
                            FROM
                            tab_enccampo
                            INNER JOIN tab_enccampolista ON tab_enccampo.ecp_id = tab_enccampolista.ecp_id
                            WHERE tab_enccampolista.ecl_estado = 1 
                            AND tab_enccampo.ecp_id = $val->ecp_id 
                            ORDER BY tab_enccampolista.ecl_orden ";
                    $row2 = $this->enccampolista->dbselectBySQL($sql);
                    foreach ($row2 as $val2) {
                        // Find value
                        $sql = "SELECT
                                tab_rescampovalor.rcv_id,
                                tab_rescampovalor.res_id,
                                tab_rescampovalor.ecp_id,
                                tab_rescampovalor.ecl_id,
                                tab_rescampovalor.rcv_valor,
                                tab_rescampovalor.rcv_estado
                                FROM
                                tab_enccampo
                                INNER JOIN tab_rescampovalor ON tab_enccampo.ecp_id = tab_rescampovalor.ecp_id
                                WHERE tab_rescampovalor.res_id = '$res_id'
                                AND tab_enccampo.ecp_id = $val->ecp_id
                                ORDER BY tab_enccampo.egr_id, 
                                tab_enccampo.ecp_orden";
                        $row5 = $this->enccampo->dbselectBySQL($sql);
                        if (isset($val2->ecl_valor)) {
                            $valor = $row5[0]->ecl_id;
                            if ($row5[0]->ecl_id == $val2->ecl_id)
                                $option .= ' ' . $val2->ecl_valor;
                        }else {
                            $valor = "";
                            $option .= "";
                        }
                    }

                    $option .="</td>";
                    $option .="</tr>";
                    
                    
                } else {
                    $option .="<tr>";
                    // Find Value
                    $sql = "SELECT
                            tab_rescampovalor.rcv_id,
                            tab_rescampovalor.res_id,
                            tab_rescampovalor.ecp_id,
                            tab_rescampovalor.ecl_id,
                            tab_rescampovalor.rcv_valor,
                            tab_rescampovalor.rcv_estado
                            FROM
                            tab_enccampo
                            INNER JOIN tab_rescampovalor ON tab_enccampo.ecp_id = tab_rescampovalor.ecp_id
                            WHERE tab_rescampovalor.res_id = '$res_id'
                            AND tab_enccampo.ecp_id = $val->ecp_id
                            ORDER BY tab_enccampo.egr_id, 
                            tab_enccampo.ecp_orden";
                    $this->rescampovalor = new tab_rescampovalor();
                    $row5 = $this->rescampovalor->dbselectBySQL($sql);
                    if (isset($row5[0]->rcv_valor)) {
                        $valor = $row5[0]->rcv_valor;
                    } else {
                        $valor = "";
                    }
                    // Etiqueta
                    $option .='<td>' . '<b>' . $val->ecp_eti . '</b>' . ' ' . $valor . "</td>";
                    $option .="</tr>";
                }
            }
        }
        return $option;
    }
	
    function obtenerSelectCamposMostrar($enc_id = null, $res_id = null) {
        $this->enccampolista = new tab_enccampolista();
        $sql = "SELECT
                tab_enccampo.ecp_id,
                tab_enccampo.enc_id,
                tab_enccampo.ecp_orden,
                tab_enccampo.ecp_eti,
                tab_enccampo.ecp_tipdat,
                tab_enccampo.ecp_estado,
                tab_enccampo.ecp_nombre
                FROM
                tab_enccampo
                WHERE tab_enccampo.ecp_estado = 1
                AND tab_enccampo.enc_id = $enc_id
                ORDER BY ecp_orden ASC ";
        $row = $this->enccampo->dbselectBySQL($sql);
        $option = "";

        // Trace
        foreach ($row as $val) {
            if ($val->ecp_tipdat == 'Lista') {
                // Lista
                $sql = "SELECT
                        tab_enccampo.ecp_id,
                        tab_enccampolista.ecl_id,
                        tab_enccampolista.ecl_valor,
                        tab_enccampolista.ecl_estado
                        FROM
                        tab_enccampo
                        INNER JOIN tab_enccampolista ON tab_enccampo.ecp_id = tab_enccampolista.ecp_id
                        WHERE tab_enccampolista.ecl_estado = 1 AND tab_enccampo.ecp_id = $val->ecp_id ";
                $row2 = $this->enccampolista->dbselectBySQL($sql);
                if ($row2){                     
                    foreach ($row2 as $val2) {
                        // Find value
                        $sql = "SELECT
                                tab_rescampovalor.rcv_id,
                                tab_rescampovalor.res_id,
                                tab_rescampovalor.ecp_id,
                                tab_rescampovalor.ecl_id,
                                tab_rescampovalor.rcv_valor,
                                tab_rescampovalor.rcv_estado
                                FROM
                                tab_enccampo
                                INNER JOIN tab_rescampovalor ON tab_enccampo.ecp_id = tab_rescampovalor.ecp_id
                                WHERE tab_rescampovalor.res_id = '$res_id'
                                AND tab_enccampo.ecp_id = $val->ecp_id
                                ORDER BY
                                tab_enccampo.ecp_orden";
                        $row5 = $this->enccampo->dbselectBySQL($sql);
                        if($row5){
                             
                            foreach ($row5 as $list) {
                                $rcv_valor = $list->rcv_valor;
                                $ecl_id = $list->ecl_id;
                            }
                            if ($rcv_valor) {
                                if ($row5[0]->ecl_id == $val2->ecl_id){
                                    // List
                                    $option .="<tr><td><strong>" . $val->ecp_eti . "</strong></td>";
                                    $option .= "<td colspan='3'>";                                
                                    $valor = $ecl_id;                                    
                                    $option .= $val2->ecl_valor;
                                    $option .="</td>";
                                    $option .="</tr>";                                    
                                }
                                                                 
//                            }else {
//                                $valor = "";
//                                $option .= "";
                            }                                                       
                        }
                    }                    
                }                
            } else {
                // Find Value
                $sql = "SELECT
                        tab_rescampovalor.rcv_id,
                        tab_rescampovalor.res_id,
                        tab_rescampovalor.ecp_id,
                        tab_rescampovalor.ecl_id,
                        tab_rescampovalor.rcv_valor,
                        tab_rescampovalor.rcv_estado
                        FROM
                        tab_enccampo
                        INNER JOIN tab_rescampovalor ON tab_enccampo.ecp_id = tab_rescampovalor.ecp_id
                        WHERE tab_rescampovalor.res_id = '$res_id'
                        AND tab_enccampo.ecp_id = $val->ecp_id
                        ORDER BY
                        tab_enccampo.ecp_orden";
                $this->rescampovalor = new tab_rescampovalor();
                $row5 = $this->rescampovalor->dbselectBySQL($sql);
                if ($row5){
                    if ($row5[0]->rcv_valor) {
                        $valor = $row5[0]->rcv_valor;
                        $option .="<tr><td><strong>" . $val->ecp_eti . "</strong></td>";
                        $option .="<td colspan='3'> " . $valor . "</td>";
                        $option .="</tr>";                        
                    }
                }
            }
        }
        return $option;
    }

    function obtenerCampos($enc_id = null) {
        $this->enccampo = new tab_enccampo();
        $sql = "SELECT *
                FROM
                tab_enccampo
                WHERE tab_enccampo.ecp_estado = 1
                AND tab_enccampo.enc_id = $enc_id
                ORDER BY ecp_orden ASC ";
        $row = $this->enccampo->dbselectBySQL($sql);
        if (count($row)) {
            return $row;
        } else {
            return null;
        }
    }
	
    function obtenerSelectCamposRepH($enc_id = null) {
        $width = 50;
        $this->enccampolista = new tab_enccampolista();
        $sql = "SELECT
                tab_enccampo.ecp_id,
                tab_enccampo.enc_id,
                tab_enccampo.ecp_orden,
                tab_enccampo.ecp_eti,
                tab_enccampo.ecp_tipdat,
                tab_enccampo.ecp_estado,
                tab_enccampo.ecp_nombre
                FROM
                tab_enccampo
                WHERE tab_enccampo.ecp_estado = 1
                AND tab_enccampo.enc_id = $enc_id
                ORDER BY ecp_orden ASC ";
        $row = $this->enccampo->dbselectBySQL($sql);
        $option = "";

        // Trace
        foreach ($row as $val) {
            $option .='<td width="' . $width . '" rowspan="3" align="center" valign="middle"><span style="font-size: 11px ;font-weight: bold;">' . $val->ecp_eti . '</span></td>';
        }
        return $option;
    }

    function obtenerSelectCamposRepC($enc_id = null, $res_id = null) {
        $width = 50;
        $this->enccampolista = new tab_enccampolista();
        $sql = "SELECT
                tab_enccampo.ecp_id,
                tab_enccampo.enc_id,
                tab_enccampo.ecp_orden,
                tab_enccampo.ecp_eti,
                tab_enccampo.ecp_tipdat,
                tab_enccampo.ecp_estado,
                tab_enccampo.ecp_nombre
                FROM
                tab_enccampo
                WHERE tab_enccampo.ecp_estado = 1
                AND tab_enccampo.enc_id = $enc_id
                ORDER BY ecp_orden ASC ";
        $row = $this->enccampo->dbselectBySQL($sql);
        $option = "";


        // Trace
        foreach ($row as $val) {
            if ($val->ecp_tipdat == 'Lista') {
                //$option .="<tr><td><strong>" . $val->ecp_eti . "</strong></td>";
                $option .= '<td bgcolor="#FFFFFF" width="' . $width . '">';
                // Lista
                $sql = "SELECT
                        tab_enccampo.ecp_id,
                        tab_enccampolista.ecl_id,
                        tab_enccampolista.ecl_valor,
                        tab_enccampolista.ecl_estado
                        FROM
                        tab_enccampo
                        INNER JOIN tab_enccampolista ON tab_enccampo.ecp_id = tab_enccampolista.ecp_id
                        WHERE tab_enccampolista.ecl_estado = 1 AND tab_enccampo.ecp_id = $val->ecp_id ";
                $row2 = $this->enccampolista->dbselectBySQL($sql);
                foreach ($row2 as $val2) {
                    // Find value
                    $sql = "SELECT
                            tab_rescampovalor.rcv_id,
                            tab_rescampovalor.res_id,
                            tab_rescampovalor.ecp_id,
                            tab_rescampovalor.ecl_id,
                            tab_rescampovalor.rcv_valor,
                            tab_rescampovalor.rcv_estado
                            FROM
                            tab_enccampo
                            INNER JOIN tab_rescampovalor ON tab_enccampo.ecp_id = tab_rescampovalor.ecp_id
                            WHERE tab_rescampovalor.res_id = '$res_id'
                            AND tab_enccampo.ecp_id = $val->ecp_id
                            ORDER BY
                            tab_enccampo.ecp_orden";
                    $row5 = $this->enccampo->dbselectBySQL($sql);
                    if (isset($row5[0]->rcv_valor)) {
                        $valor = $row5[0]->ecl_id;
                        if ($row5[0]->ecl_id == $val2->ecl_id)
                            $option .= '<span style="font-size: 11px;">' . $val2->ecl_valor . '</span>';
                    }else {
                        $valor = "";
                        $option .= '<span style="font-size: 11px;"></span>';
                    }
                }
                //$option .= "</select>";
                $option .="</td>";
                //$option .="</tr>";
            } else {
                // Find Value
                $sql = "SELECT
                        tab_rescampovalor.rcv_id,
                        tab_rescampovalor.res_id,
                        tab_rescampovalor.ecp_id,
                        tab_rescampovalor.ecl_id,
                        tab_rescampovalor.rcv_valor,
                        tab_rescampovalor.rcv_estado
                        FROM
                        tab_enccampo
                        INNER JOIN tab_rescampovalor ON tab_enccampo.ecp_id = tab_rescampovalor.ecp_id
                        WHERE tab_rescampovalor.res_id = '$res_id'
                        AND tab_enccampo.ecp_id = $val->ecp_id
                        ORDER BY
                        tab_enccampo.ecp_orden";
                $this->rescampovalor = new tab_rescampovalor();
                $row5 = $this->rescampovalor->dbselectBySQL($sql);
                if (isset($row5[0]->rcv_valor)) {
                    $valor = $row5[0]->rcv_valor;
                } else {
                    $valor = "";
                }

                // Campos
                //$option .="<tr><td><strong>" . $val->ecp_eti . "</strong></td>";
                $option .='<td bgcolor="#FFFFFF" width="' . $width . '"><span style="font-size: 11px;">' . $valor . '</span></td>';
                //$option .="</tr>";
            }
        }
        return $option;
    }

    function obtenerSelectCamposRepDoc($enc_id = null, $res_id = null) {
        $this->enccampolista = new tab_enccampolista();
        $sql = "SELECT
                tab_enccampo.ecp_id,
                tab_enccampo.enc_id,
                tab_enccampo.ecp_orden,
                tab_enccampo.ecp_eti,
                tab_enccampo.ecp_tipdat,
                tab_enccampo.ecp_estado,
                tab_enccampo.ecp_nombre
                FROM
                tab_enccampo
                WHERE tab_enccampo.ecp_estado = 1
                AND tab_enccampo.enc_id = $enc_id
                ORDER BY ecp_orden ASC ";
        $row = $this->enccampo->dbselectBySQL($sql);
        $option = "";
        $count = 0;
        // Trace
        if (count($row) > 0) {
            foreach ($row as $val) {


                if ($count == 0) {
                    $option .="<tr>";
                }
                if ($val->ecp_tipdat == 'Lista') {
                    $option .='<td bgcolor="#CCCCCC"><span style="font-size: 14px;font-weight: bold;">' . $val->ecp_eti . ':</span></td>';
                    $option .= '<td><span style="font-size: 14px;">';
                    // Lista
                    $sql = "SELECT
                        tab_enccampo.ecp_id,
                        tab_enccampolista.ecl_id,
                        tab_enccampolista.ecl_valor,
                        tab_enccampolista.ecl_estado
                        FROM
                        tab_enccampo
                        INNER JOIN tab_enccampolista ON tab_enccampo.ecp_id = tab_enccampolista.ecp_id
                        WHERE tab_enccampolista.ecl_estado = 1 AND tab_enccampo.ecp_id = $val->ecp_id ";
                    $row2 = $this->enccampolista->dbselectBySQL($sql);
                    foreach ($row2 as $val2) {
                        // Find value
                        $sql = "SELECT
                            tab_rescampovalor.rcv_id,
                            tab_rescampovalor.res_id,
                            tab_rescampovalor.ecp_id,
                            tab_rescampovalor.ecl_id,
                            tab_rescampovalor.rcv_valor,
                            tab_rescampovalor.rcv_estado
                            FROM
                            tab_enccampo
                            INNER JOIN tab_rescampovalor ON tab_enccampo.ecp_id = tab_rescampovalor.ecp_id
                            WHERE tab_rescampovalor.res_id = '$res_id'
                            AND tab_enccampo.ecp_id = $val->ecp_id
                            ORDER BY
                            tab_enccampo.ecp_orden";
                        $row5 = $this->enccampo->dbselectBySQL($sql);
                        if (isset($val2->ecl_valor)) {
                            $valor = $row5[0]->ecl_id;
                            if ($row5[0]->ecl_id == $val2->ecl_id)
                                $option .= $val2->ecl_valor;
                        }else {
                            $valor = "";
                            $option .= "";
                        }
                    }

                    $option .="</span></td>";
                } else {
                    // Find Value
                    $sql = "SELECT
                        tab_rescampovalor.rcv_id,
                        tab_rescampovalor.res_id,
                        tab_rescampovalor.ecp_id,
                        tab_rescampovalor.ecl_id,
                        tab_rescampovalor.rcv_valor,
                        tab_rescampovalor.rcv_estado
                        FROM
                        tab_enccampo
                        INNER JOIN tab_rescampovalor ON tab_enccampo.ecp_id = tab_rescampovalor.ecp_id
                        WHERE tab_rescampovalor.res_id = '$res_id'
                        AND tab_enccampo.ecp_id = $val->ecp_id
                        ORDER BY
                        tab_enccampo.ecp_orden";
                    $this->rescampovalor = new tab_rescampovalor();
                    $row5 = $this->rescampovalor->dbselectBySQL($sql);
                    if (isset($row5[0]->rcv_valor)) {
                        $valor = $row5[0]->rcv_valor;
                    } else {
                        $valor = "";
                    }
                    // Campos
                    $option .='<td bgcolor="#CCCCCC"><span style="font-size: 14px;font-weight: bold;">' . $val->ecp_eti . ":</span></td>";
                    $option .='<td><span style="font-size: 14px;">' . $valor . "</span></td>";
                }
                $count++;
                if ($count == 2) {
                    $option .="</tr>";
                    $count = 0;
                }
            }
            if ($count < 2) {
                $option .="</tr>";
            }
        }
        return $option;
    }



}

?>
