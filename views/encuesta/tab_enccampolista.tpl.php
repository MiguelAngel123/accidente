<!--
  enccampolistaView

  @package
  @author lic. castellon
  @copyright DGGE
  @version $Id$ 2014.04.24
  @access public
-->

<div class="clear"></div>
    
<form id="formA" name="formA" method="post" class="validable"
      action="<?php echo $PATH_DOMAIN ?>/enccampolista/<?php echo $PATH_EVENT ?>/">  
    
    <input name="ecl_id" id="ecl_id" type="hidden" value="<?php echo $ecl_id; ?>" />
    <input name="ecp_id" id="ecp_id" type="hidden" value="<?php echo $ecp_id; ?>" />    
    
    <table width="100%" border="0">
        <caption class="titulo"><?php echo $titulo ?>Valor de la lista</caption>
        <tr><td>Campo de lista:</td><td colspan=2><?php echo $ecp_nombre; ?></td></tr>
        
        <tr>
            <td width="75">Orden:</td>
            <td colspan="2"><input name="ecl_orden" type="text" id="ecl_orden"
                                   value="<?php echo $ecl_orden; ?>" size="3" autocomplete="off"
                                   class="onlynumeric" maxlength="10" title="Orden" /></td>
        </tr>
        
        <tr>
            <td>Valor:</td>
            <td colspan="2"><input name="ecl_valor" type="text"
                                   id="ecl_valor" value="<?php echo $ecl_valor; ?>"
                                   size="40" autocomplete="off" class="required alphanum"
                                   title="Valor del campo" maxlength="1024" /></td>
        </tr>
        
        <tr>
            <td colspan="3" class="botones">
                <input name="guardar" id="btnSub" type="submit" value="Guardar" class="button" />
                <input name="cancelar" id="cancelar" type="button" class="button" value="Cancelar" />
            </td>
        </tr>
        
    </table>
</form>

<script type="text/javascript">

    jQuery(document).ready(function($) {
        $("#btnSub").click(function(){
            window.location="<?php echo $PATH_DOMAIN ?>/enccampolista/";
        });
        $("#cancelar").click(function(){
            window.location="<?php echo $PATH_DOMAIN ?>/enccampolista/index/<?php echo VAR3; ?>/";
        });
    });

</script>