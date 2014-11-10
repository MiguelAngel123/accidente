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

<div class="clear"></div><br/>
<p><table id="flex1" style="display:none"></table></p>
<p><table id="flex2" style="display:none"></table></p>
<div class="clear"></div>

<form id="formA" name="formA" method="post" class="validable"
      action="<?php echo $PATH_DOMAIN ?>/respuesta/<?php echo $PATH_EVENT ?>/" >
    <input name="res_id" id="res_id" type="hidden" value="<?php echo $res_id; ?>" />
    <input name="enc_id" id="enc_id" type="hidden" value="<?php echo $enc_id; ?>" />
</form>

<form id="formB" name="formB" method="post" class="validable"
      action="<?php echo $PATH_DOMAIN ?>/respuesta/<?php echo $PATH_EVENT ?>/" target="_blank">
    <input name="res_id2" id="res_id2" type="hidden" value="<?php echo $res_id; ?>" />
    <input name="enc_id2" id="enc_id2" type="hidden" value="<?php echo $enc_id; ?>" />
</form>

<script type="text/javascript">
    
    
    $("#flex1").flexigrid
    ({
        url: '<?php echo $PATH_DOMAIN ?>/respuesta/loadSerie/<?php echo $enc_id; ?>/',
        dataType: 'json',
        colModel: [
            {display: 'Id', name: 'enc_id', width: GetColumnSize(3), sortable: true, align: 'center'},
            {display: 'C&oacute;digo', name: 'enc_codigo', width: GetColumnSize(12), sortable: true, align: 'left'},
            {display: 'Unidad', name: 'uni_descripcion', width: GetColumnSize(35), sortable: true, align: 'left'},
            {display: 'Encuesta', name: 'enc_categoria', width: GetColumnSize(50), sortable: true, align: 'left'},
        ],
        buttons: [
            {name: 'Seleccionar encuesta', bclass: 'save1', onpress: test}, 
        ],
        searchitems: [
            {display: 'Id', name: 'enc_id', isdefault: true},  
            {display: 'Unidad', name: 'uni_descripcion'},
            {display: 'Encuesta', name: 'enc_categoria'}
        ],
        sortname: "",
        sortorder: "asc",
        usepager: true,
        title: "LISTA DE ENCUESTAS",
        useRp: true,
        rp: 5,
        minimize: <?php echo $GRID_SW ?>,
        showTableToggleBtn: true,
        width: "100%",
        height: 140,
        autoload: true
    });

    $("#flex2").flexigrid
    ({
        url: '<?php echo $PATH_DOMAIN ?>/respuesta/load/<?php echo $enc_id; ?>/',
        dataType: 'json',
        colModel: [
            {display: 'Id', name: 'res_id', width: GetColumnSize(3), sortable: true, align: 'center'},
            {display: 'C&oacute;digo', name: 'res_codigo', width: GetColumnSize(12), sortable: true, align: 'left'},            
            {display: 'Unidad', name: 'uni_descripcion', width: GetColumnSize(10), sortable: true, align: 'left'},   
            {display: 'Encuesta', name: 'enc_categoria', width: GetColumnSize(50), sortable: true, align: 'left'},  
            {display: 'Estado', name: 'res_estado', width: GetColumnSize(10), sortable: true, align: 'left'},            
            {display: 'Encargado', name: 'encargado', width: GetColumnSize(15), sortable: true, align: 'left'}
        ],
        buttons: [
            <?php if ($total==0) { ?>            
            {name: 'Adicionar', bclass: 'add', onpress: test2},
            <?php } ?>            
            {name: 'Editar', bclass: 'edit', onpress: test2},
            {name: 'Eliminar', bclass: 'delete', onpress: test2}, 
            {separator: true},
            {name: 'Ver encuesta', bclass: 'pdf', onpress: test2}, 
            {name: 'Cerrar encuesta', bclass: 'mail', onpress: test2},
            <?php if (!$adm) { ?>            
            <?php } ?>             
            
        ],
        searchitems: [
            {display: 'Id', name: 'res_id', isdefault: true},            
            {display: 'Unidad', name: 'uni_descripcion'},
            {display: 'Encuesta', name: 'enc_categoria'},
            {display: 'Respuesta', name: 'res_titulo'},
            {display: 'Custodio', name: 'encargado'}
        ],
        sortname: "",
        sortorder: "asc",
        usepager: true,
        title: 'LISTA DE ENCUESTAS REGISTRADAS',
        useRp: true,
        rp: 10,
        minimize: <?php echo $GRID_SW ?>,
        showTableToggleBtn: true,
        width: "100%",
        height: 280,
        autoload: true
    });

    function dobleClik(grid) {
        if ($('.trSelected div', grid).html()) {
            
            if ($("table", grid).attr('id') == "flex1") {
                $("#enc_id").val($('.trSelected div', grid).html());
                $("#formA").attr("action", "<?php echo $PATH_DOMAIN ?>/respuesta/");
                document.getElementById('formA').submit();
            }            
            
            if ($("table", grid).attr('id') == "flex2") {
                $("#res_id").val($('.trSelected div', grid).html());
                $("#formA").attr("action", "<?php echo $PATH_DOMAIN ?>/respuesta/edit/");
                document.getElementById('formA').submit();
            }
            
        }
    }

    function test(com, grid)
    {
        if (com === 'Seleccionar encuesta')
        {
            if ($('.trSelected div', grid).html())
            {
                $("#enc_id").val($('.trSelected div', grid).html());
                $("#formA").attr("action", "<?php echo $PATH_DOMAIN ?>/respuesta/"+ $('.trSelected div', grid).html() + "/");
                document.getElementById('formA').submit();
            } else {
                alert("Por favor, seleccione un registro");
            }
        }
        else {
            $(".qsbox").val($('.trSelected div', grid).html());
            $(".qtype").val('enc_id');
            $('.Search').click();
        }

    }

    function test2(com, grid)
    {
        if (com == 'Adicionar')
        {
            if (!$("#enc_id").val()){
                alert("Seleccione una serie");
                return false;
            }

            $.post("<?php echo $PATH_DOMAIN ?>/respuesta/verifencuesta/", {rand: Math.random()}, function(data) {
                if (data != 'OK') {
                    alert("No puede adicionar encuestas porque no tiene permiso para ninguna Serie.");
                } else {
                    window.location = "<?php echo $PATH_DOMAIN ?>/respuesta/add/";
                }
            });
        }
        

        else if (com == 'Editar') {
            if ($('.trSelected div', grid).html()) {
                $.post("<?php echo $PATH_DOMAIN ?>/respuesta/verificaEstado/",{res_id:$('.trSelected div',grid).html(),rand:Math.random() } ,function(data){
                    if (data == 'OK') {
                        alert("No puede editar la encuesta. Ya se cerró la misma");
                    } else {

                        $("#res_id").val($('.trSelected div', grid).html());
                        $("#formA").attr("action", "<?php echo $PATH_DOMAIN ?>/respuesta/edit/");
                        document.getElementById('formA').submit();
                    }
                });                                        
            } else {
                alert("Por favor, seleccione un registro");
            }
        }

        else if (com == 'Eliminar')
        {
            if ($('.trSelected div', grid).html()) {
                if (confirm('Esta seguro de eliminar el registro ' + $('.trSelected div', grid).html() + ' ?'))
                    $.post("<?php echo $PATH_DOMAIN ?>/respuesta/delete/", {res_id: $('.trSelected div', grid).html(), rand: Math.random()}, function(data) {
                        if (data != true) {
                            $('.pReload', grid.pDiv).click();
                        }
                    });
            } else {
                alert("Por favor, seleccione un registro");
            }
        }
        else if (com == 'Ver encuesta') {
            if ($('.trSelected div', grid).html()) {
                url = "<?php echo $PATH_DOMAIN ?>/respuesta/printFichaEncuesta/" + $('.trSelected div', grid).html() + "/";
                abrir(url);
            } else {
                alert("Por favor, seleccione un registro");
            }
        }
        else if (com == 'Cerrar encuesta') {
            $.post("<?php echo $PATH_DOMAIN ?>/respuesta/verificaEstado/",{res_id:$('.trSelected div',grid).html(),rand:Math.random() } ,function(data){
                if (data == 'OK') {
                    alert("Ya se cerró la encuesta");
                } else {
                    if (confirm('Esta seguro de cerrar el encuesta ' + $('.trSelected div', grid).html() + ' ?')){
                        if ($('.trSelected div', grid).html()) {
                            $("#res_id").val($('.trSelected div', grid).html());
                            $("#formA").attr("action", "<?php echo $PATH_DOMAIN ?>/respuesta/sendMail/");
                            document.getElementById('formA').submit();
                        } else {
                            alert("Por favor, seleccione un registro");
                        }
                    }
                }
            });
                
        }
        
        
    }
    
    function GetColumnSize(percent) {
        screen_res = ($(document).width() - 100) * 1;
        col = parseInt((percent * (screen_res / 100)));
        if (percent != 100) {
            return col;
        } else {
            return col;
        }
    }    
</script>