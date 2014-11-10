<!--
  respuestaView

  @package
  @author lic. castellon
  @copyright DGGE
  @version $Id$ 2014.04.14
  @access public
-->

<link href="<?php echo $PATH_WEB ?>/js/javascript/msgbox/jquery.msgbox.css" rel="stylesheet" type="text/css" />
<script languaje="javascript" type="text/javascript" src="<?php echo $PATH_WEB ?>/js/javascript/msgbox/jquery.msgbox.js"></script>

<div align="left"><a href="<?php echo $PATH_DOMAIN ?>/respuesta/"><img src="<?php echo $PATH_WEB ?>/img/back.png"></a>
</div>

<p>
<table id="flex1" style="display: none"></table>
</p>
<div class="clear"></div>

<form id="formA" name="formA" method="post" class="validable"
      action="<?php echo $PATH_DOMAIN ?>/respuesta/<?php echo $PATH_EVENT ?>/" enctype="multipart/form-data">
    <input name="res_id" id="res_id" type="hidden" value="<?php echo $res_id; ?>" />


    <strong style="color: red" align="left"><?php echo $msm ?></strong>
    <p>
    <table border="0" width="100%">
        <caption class="titulo">ENCUESTA</caption>

            <tr>
                <td>Unidad:</td>
                <td>
                    <?php echo $uni_descripcion; ?>
                </td>
            </tr>
            
            <tr>
                <td>Encuesta:</td>
                <td>
                    <select name="enc_id" id="enc_id" class="required">
                        <?php echo $encuesta; ?>
                    </select> <span class="error-requerid">*</span>
                </td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <input name="res_codigo" 
                           type="hidden" 
                           id="res_codigo"
                           value="<?php echo $res_codigo; ?>" 
                           size="5" 
                           autocomplete="off"
                           class="" 
                           maxlength="10" 
                           title="C&oacute;digo" />
                </td>
            </tr>            

            
            <tr>
                <td width="5">
                    <label id="nro-slider">1</label>
                </td>
                <td>
                    <input type="button" id="title-slider2"  align="left" value="PARTE A" onclick='$("#div1").toggle(500);'>
                </td>
            </tr>
    </table>
    
    <div id="div1" style="background-color:#eeeeee;border:0px solid">
        <table width="100%" border="0">


            
            
            <tr>
                <td></td>
                <td colspan="3">
                    <input name="res_titulo" 
                           type="hidden" id="res_titulo"
                           value="<?php echo $res_titulo; ?>" 
                           size="120" 
                           autocomplete="off"
                           maxlength="1024" 
                           class="required alphanum" 
                           title="En este campo se debe registrar el t&iacute;tulo del encuesta." />                       
                </td>
            </tr>




        </table>
        
        <table width="100%" border="0">


            <tr>
                <td><b></b></td>
                <td>
                </td>
                <?php echo $enccampo; ?>
            </tr>            
        </table>
    </div>
</p>







<table width="100%" border="0"> 
    <tr><td colspan="2" height="15"></td></tr>
    <tr id="tr-botones">
        <td colspan="10" class="botones">
            <input type="hidden" name="accion" id="accion" value="guardar" />
            <input id="btnSub3" type="button" value="Guardar" class="button" /> 
            <!--<input id="btnSubB" type="button" value="Guardar y Nuevo" class="button2" />-->             
            <input id="btnSub" type="button" value="Guardar y salir" class="button2" />             
            <input name="cancelar" id="cancelar" type="button" class="button" value="Cancelar" />
        </td>
    </tr>
</table>


</form>


<script type="text/javascript">

    jQuery(document).ready(function($) {

        $("#cancelar").click(function() {
            location.href = "<?php echo $PATH_DOMAIN ?>/respuesta/";
        });

        $("#btnSub").click(function() {
            $("#accion").val('guardar');
            $('form#formA').submit();
        });

        $("#btnSubB").click(function() {
            $("#accion").val('guardarnuevo');
            $('form#formA').submit();
        })
        $("#btnSub3").click(function() {
            $("#accion").val('guardarsinsalir');
            $('form#formA').submit();
        })
    });


    $(function() {

        $("#enc_id").change(function() {
            if ($("#enc_id").val() == "") {
            } else {
                $.ajax({
                    url: '<?php echo $PATH_DOMAIN ?>/encuesta/loadCodigoAjax/',
                    type: 'POST',
                    data: 'Ser_id=' + $("#enc_id").val(),
                    dataType: "json",
                    success: function(datos) {
                        if (datos) {
                            $("#enc_codigo").val(datos.enc_codigo);
                        }
                    }
                });
            }
        });

    });

</script>