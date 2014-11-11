<!--  
  usuarioView 

  @package
  @author lic. castellon
  @copyright DGGE
  @version $Id$ 2014.04.14
  @access public
-->

<div class="clear"></div>
<form id="formA" name="formA" method="post" class="validable"
      action="<?php echo $PATH_DOMAIN ?>/accidente/<?php echo $PATH_EVENT ?>/">

    <input name="usu_id" id="usu_id" type="hidden" value="<?php echo $usu_id; ?>" />

    <table width="100%" border="0">
        <caption class="titulo"><?php echo $titulo; ?></caption>

        <tr>
            <td>Id :</td>
            <td><select autocomplete="off"
                        class="required"
                        id="acc_id"
                        name="acc_id"
                        title="Id accidente">
                    <option value="">-Seleccionar-</option>
                    <?php echo $roles; ?>
                </select>
                <span class="error-requerid">*</span></td>
        </tr>
        
               
        <tr>
            <td>Descripcion:</td>
            <td><input autocomplete="off"
                       class="required alpha"
                       id="acc_descripcion"
                       maxlength="70"
                       name="acc_descripcion"
                       size="25"
                       title="Descripcion"
                       type="text"
                       value="<?php echo $acc_descripcion; ?> "/>
                <span class="error-requerid">*</span>
            </td>
        </tr>

        <tr>
            <td colspan="4" class="botones">
                <input type="hidden" name="usu_pass_leer" id="usu_pass_leer" value="" />
                <input type="hidden" name="usu_pass_dias" id="usu_pass_dias" value="" />
                <input id="btnSub" type="button" value="Guardar" class="button" />
                <input name="cancelar" id="cancelar" type="button" class="button" value="Cancelar" />
			</td>
        </tr>
    </table>
</form>

<div class="flexigrid" style="width: 100%;">
    <div class="nBtn srtd" style="left: 36px; top: 51px; display: none;">
        <div></div>
    </div>
    <div class="mDiv">
        <div class="ftitle">LISTA DE encuesta DOCUMENTALES</div>
    </div>
    <div class="hDiv">
        <div class="hDivBox">
            <table cellspacing="0" cellpadding="0">
                <thead>                    
                    <tr>
                        <th align="right" abbr="enc_id" axis="col4">
                            <div style="text-align: right; width: 50px;">Id</div>
                        </th>
                        
                        <th align="center" abbr="exp_fecha_exi" axis="col4">
                            <div style="text-align: center; width: 50px;">Ver</div>
                        </th>
                        <th align="left" abbr="ser_id" axis="col0" class="">
                            <div class="sasc" style="text-align: left; width: 150px;">C&oacute;digo</div>
                        </th>
                        <th align="left" abbr="enc_id" axis="col1" class="">
                            <div style="text-align: left; width: 600px;" class="">Serie</div>
                       </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="bDiv" style="">
        <table cellspacing="0" cellpadding="0" border="0" style="" id="flex1">
            <tbody>
                <?php echo $lista_encuestas; ?>
            </tbody>
        </table>

        <div class="iDiv" style="display: none;"></div>
    </div>
    <div class="pDiv">
        <div style="clear: both;"></div>
    </div>
    <div class="vGrip"><span></span></div>
    <div class="hGrip" style="height: 348px;"><span></span></div>

</div>



<div class="clear"></div>

<script type="text/javascript">

    jQuery(document).ready(function($) {

        $("#btnSub").click(function(){
            $(".flexigrid").clone().hide().appendTo("#formA");
            return false;
        });

        $("#btnSub").click(function(){
            if($("#usu_pass").val() == $("#usu_pass2").val()){
                $('form#formA').submit();
            }else{
                alert("Las contrase&ntilde;as deben ser iguales");
            }


        });

        $("#cancelar").click(function(){
            window.location="<?php echo $PATH_DOMAIN ?>/usuario/";
        });

        $(".evenw").click(function(){
            //alert("aqui "+$(this).attr('id'));
            if($("."+$(this).attr('id')+"z").is(':visible')){
                $("."+$(this).attr('id')+"z").hide()
            }else{
                $("."+$(this).attr('id')+"z").show()
            }
        });

    });


    $(function() {
        $('#formAX .text').change(function(){
            $(this).removeClass('ui-state-error');
        });

        $('#usu_login').change(function(){
            if($(this).val()!=''){
                $.ajax({
                    url: '<?php echo $PATH_DOMAIN ?>/usuario/verifLogin/',
                    type: 'POST',
                    data: 'login='+$(this).val()+ '&usu_id='+$('#usu_id').val(),
                    dataType:  		"text",
                    success: function(datos){
                        if(datos!=''){
                            $('#usu_login').val('');
                            $('#usu_login').focus();
                            alert(datos);
                        }
                    }
                });
            }
        });

        $('#usu_leer_doc').change(function() {
            $('#pass1').val('');
            $('#pass2').val('');
            $('#dias').val('');
            $('#usu_pass_leer').val('');
            $('#usu_pass_dias').val('');
            if($(this).val()=='1')
                $('#dialog').dialog('open');
        });

        var name = $("#pass1"),
        allFields = $([]).add(name),
        tips = $("#validateTips");

        function updateTips(t) {
            tips.text(t).effect("highlight",{},1500);
        }

        function evalPass(o, p, q) {
            if(o.val() == "" || p.val() == "" || q.val() == "" ){
                if(o.val() == "")
                    o.addClass('ui-state-error');
                if(p.val() == "")
                    p.addClass('ui-state-error');
                if(q.val() == "")
                    q.addClass('ui-state-error');
                updateTips("Todos los datos son requeridos");
            }else{
                if(o.val() == p.val() ){
                    if(q.val().match(/[^0-9]/g) )
                    {
                        updateTips("Por favor ingrese solo numeros como cantidad de D�as");
                        q.addClass('ui-state-error');
                        q.attr("value","");
                        q.focus();
                    }
                    else{
                        $('#usu_pass_leer').val(p.val());
                        $('#usu_pass_dias').val(q.val());//alert($('#usu_pass_leer').val());
                        $("#dialog").dialog('close');
                    }
                }
                else {
                    updateTips("La contrase�a y su confirmaci�n deben ser iguales");
                    o.val('');
                    p.val('');
                    o.addClass('ui-state-error');
                    p.addClass('ui-state-error');
                }
            }
        }

        $("#dialog").dialog({
            bgiframe: true,
            autoOpen: false,
            height: 220,
            width: 400,
            modal: true,
            buttons: {
                Aceptar: function() {
                    allFields.removeClass('ui-state-error');
                    evalPass($("#pass1"),$("#pass2"),$("#dias"));
                },
                Cancelar: function() {
                    //allFields.val('').removeClass('ui-state-error');
                    $("#usu_leer_doc").val('2');
                    $(this).dialog('close');
                }
            },
            close: function() {
                allFields.val('').removeClass('ui-state-error');
            }
        });
    });
</script>