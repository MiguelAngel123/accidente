<!--
  departamentoView

  @package
  @author lic. castellon
  @copyright DGGE
  @version $Id$ 2014.04.24
  @access public
-->

<div class="clear"></div>
<form id="formA" name="formA" method="post" class="validable"
      action="<?php echo $PATH_DOMAIN ?>/departamento/<?php echo $PATH_EVENT ?>/"><input
        name="dep_id" type="hidden" id="dep_id" value="<?php echo $dep_id; ?>" />
        <input name="path_event" id="path_event" type="hidden"
           value="<?php echo $PATH_EVENT; ?>" />
    <table width="100%" border="0">
        <caption class="titulo"><?php echo $titulo; ?></caption>

        <tr>
            <td>C&oacute;digo:</td>
            <td><input class="required onlynumeric"
                       id="dep_codigo"
                       maxlength="64"
                       name="dep_codigo" 
                       size="60" 
                       title="c&oacute;digo"
                       type="text" 
                       value="<?php echo $dep_codigo; ?>"                       
                        />
                <span class="error-requerid">*</span>
            </td>
        </tr>
        <tr>
            <td>Nombre Departamento:</td>
            <td><input class="required alphanum" 
                       id="dep_nombre" 
                       maxlength="126"
                       name="dep_nombre"
                       size="60"
                       onkeyup="this.value=this.value.toUpperCase()"
                       type="text"
                       title="nombre"
                       value="<?php echo $dep_nombre; ?>"
                        />
                <span class="error-requerid">*</span>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="botones">
                <input id="btnSub" type="submit" value="Guardar" class="button" /> 
                <input name="cancelar" id="cancelar" type="button" class="button" value="Cancelar" />

        </tr>
    </table>
</form>
<script type="text/javascript">

    jQuery(document).ready(function($) {
        $("#cancelar").click(function(){
            window.location="<?php echo $PATH_DOMAIN ?>/departamento/";
        });
       
        $('#dep_codigo').change(function(){
            if($(this).val()!=''){
                $.ajax({
                    url: '<?php echo $PATH_DOMAIN ?>/departamento/verifCodigo/',
                    type: 'POST',
                    data: 'Dep_codigo='+$(this).val()+ '&Dep_id='+$('#dep_id').val(),
                    dataType:  		"text",
                    success: function(datos){
                        if(datos!=''){
                            $('#dep_codigo').val('');
                            $('#dep_codigo').focus();
                            alert(datos);
                        }
                    }
                });
            }
        });
        
    });

</script>