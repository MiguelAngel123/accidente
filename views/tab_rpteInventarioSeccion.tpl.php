<form id="formA" name="formA" method="post" class="validable"
      action="<?php echo $PATH_DOMAIN ?>/rpteInventarioSecciones/<?php echo $PATH_EVENT ?>/"
      target="_blank">
    <input name="nro_cajas" id="nro_cajas" type="hidden" value="" />

    <table width="100%" border="0">
        <caption class="titulo">
            LISTADO INVENTARIO POR SECCIONES DE encuestaS
        </caption>
        <tr>
            <td>Secci&oacute;n/Subsecci&oacute;n:</td>
            <td>
                <select name="filtro_seccion" style="width: 300px;" id="filtro_seccion" >
                    <?php echo ($seccion) ?>
                </select></td>
        </tr>
       <tr>
            <td>Tipo: </td>
            <td>
                <select name="tiporeporte" id="tiporeporte">
                    <option value="3">Html</option>     
                     <option value="1">Pdf</option>
                     <option value="2">Excel</option>
                                   
                </select>
            </td>
        </tr>
        <tr>
            <td class="botones" colspan="2"><input id="btnSub" type="button"
                                       value="Reporte" class="button" /> 

<!--                <input name="cancelar"
                                                   id="cancelar" type="button" class="button" value="Cancelar" />-->
            </td>
        </tr>
    </table>
  
</form>

<script type="text/javascript">
    jQuery(document).ready(function($) {

        $("#btnSub").click(function() {

            $('#dialogNro').dialog('open');

        });


    });


</script>


<div id="dialogNro" title="Cajas de secciones" align="left"><br>
    <form name="formNro" id="formNro">
        <span id="alert">N&uacute;mero total de cajas: </span>
        <input name="nro_ini" id="nro_ini" type="text" value="" size="5" maxlength="11" class="required" />
    </form>
</div>

<script>
    $(function() {

        $("#dialogNro").dialog({
            stackfix: true,
            autoOpen: false,
            height: 150,
            width: 300,
            modal: true,
            buttons: {
                Aceptar: function() {                    
                    if ($('#nro_ini').val()) {                        
                        $('#nro_cajas').val($('#nro_ini').val())
                        $('#formA').submit();
                        $(this).dialog('close');
                    }
                    else {
                        alert("Ingrese el numero de cajas...");
                    }
                }
            },
            close: function() {
            }
        });
       
        
    });

</script>
