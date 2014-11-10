<!--
  encuestaView

  @package
  @author lic. castellon
  @copyright DGGE
  @version $Id$ 2014.04.24
  @access public
-->

<div class="clear"></div>
<form id="formA" name="formA" method="post" class="validable"
      action="<?php echo $PATH_DOMAIN ?>/encuesta/<?php echo $PATH_EVENT ?>/">

    <input name="enc_id" id="enc_id" type="hidden" value="<?php echo $enc_id; ?>" />
    <input name="delimiter" id="delimiter" type="hidden" value="<?php echo $delimiter; ?>" />

    <table width="100%" border="0">
        <caption class="titulo"><?php echo $titulo ?> Serie</caption>
        

            <tr>
                
                <td>C&oacute;digo: </td>
                <td colspan="3">                     
                    <input name="enc_codigo" type="text"
                            id="enc_codigo" value="<?php echo $enc_codigo; ?>" size="6"
                            autocomplete="off" class="" maxlength="8"
                            title="C&oacute;digo de encuesta" />
                </td>
                
            </tr> 
        
        
        
        
            <tr>
                <td>Entidad</td>
                <td colspan="1">
                    <select name="uni_id" id="uni_id" class="required">
                        <option value="0">(Seleccionar)</option>
                        <?php echo $uni_id ?>
                    </select>
                    <span class="error-requerid">*</span>
                </td>                
            </tr>

            
        
        
        <tr>
            <td>Subencuesta de:</td>
            <td colspan="3">
                <select name="enc_par" id="enc_par">
                    <option value="0">(Seleccionar)</option>
                    <?php echo $enc_par ?>
                </select>
            </td>
        </tr>

        <tr>
            <td>Nombre de la Serie o Subencie:</td>
            <td colspan="3"><input name="enc_categoria" type="text"
                                   id="enc_categoria" value="<?php echo $enc_categoria; ?>" size="100"
                                   autocomplete="off" class="required alphanum" maxlength="256"
                                   onkeyup="this.value=this.value.toUpperCase()"
                                   title="Categor&iacute;a" />
            <span class="error-requerid">*</span>
            </td>
        </tr>


        
        
        

        

        <tr>
            <td class="botones" colspan="4">
                <input id="btnSub" type="submit" value="Guardar" class="button" /> 
                <input name="cancelar" id="cancelar" type="button" class="button" value="Cancelar" /></td>
        </tr>
    </table>
</form>


<p>&nbsp;</p>
<?php if ($LISTA_TRAMITES_SELECT != '') { ?>
    <div id="listaSerie1" class="seccion">Tr&aacute;mites de esta encie
        <ul>
            <?php echo $LISTA_TRAMITES_SELECT; ?>
        </ul>
    </div>
<?php } ?>


<script type="text/javascript">

    jQuery(document).ready(function($) {

        $("#cancelar").click(function(){
            window.location="<?php echo $PATH_DOMAIN ?>/encuesta/";
        });

        $("#uni_id").change(function(){
            var delimiter = $("#delimiter").val();
            
            
            $.ajax({
                url: '<?php echo $PATH_DOMAIN ?>/encuesta/loadAjaxUnidades/',
                type: 'POST',
                data: 'Uni_id='+$("#uni_id").val(),
                dataType:  		"json",
                success: function(datos){
                    if(datos){
                        $("#enc_par").find("option").remove();
                        $("#enc_par").append("<option value=''>(seleccionar)</option>");
                        jQuery.each(datos, function(i,item){
                            $("#enc_par").append("<option value='"+i+"'>"+item+"</option>");
                        });
                    }else{
                        $("#enc_par").find("option").remove();
                        $("#enc_par").append("<option value=''>-No hay encuesta-</option>");
                    }
                }
            });            
            
            
            
            
        });
        
        
        

    });

</script>