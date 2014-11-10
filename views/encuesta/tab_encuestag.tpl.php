<!--
  encuestagView

  @package
  @author lic. castellon
  @copyright DGGE
  @version $Id$ 2014.04.24
  @access public
-->

<div class="clear"></div><br>
<p>
<table id="flex1" style="display: none"></table>
</p>
<div class="clear"></div>
<form id="formA" name="formA" method="post" class="validable"
      action="<?php echo $PATH_DOMAIN ?>/encuesta/<?php echo $PATH_EVENT ?>/">
    <input name="enc_id" id="enc_id" type="hidden"
           value="<?php echo $enc_id; ?>" />
</form>

<script type="text/javascript">
    $("#flex1").flexigrid
            ({
                url: '<?php echo $PATH_DOMAIN ?>/encuesta/load/',
                dataType: 'json',
                colModel: [
                    {display: 'Id', name: 'enc_id', width: GetColumnSize(3), sortable: true, align: 'center'},
                    {display: 'C&oacute;digo', name: 'ser_codigo', width: GetColumnSize(12), sortable: true, align: 'left'},
                    {display: 'Nombre de Serie o Subserie', name: 'ser_categoria', width: GetColumnSize(35), sortable: true, align: 'left'},
                    {display: 'Secci&oacute;n o Subsecci&oacute;n', name: 'uni_descripcion', width: GetColumnSize(10), sortable: true, align: 'left'},
                ],
                buttons: [
                    {name: 'Adicionar', bclass: 'add', onpress: test},
                    {name: 'Eliminar', bclass: 'delete', onpress: test},
                    {name: 'Editar', bclass: 'edit', onpress: test}, {separator: true},
                    {name: 'Datos encuesta', bclass: 'add', onpress: test},
                ],
                searchitems: [
                    {display: 'Id', name: 'enc_id', isdefault: true},                    
                    {display: 'Secci&oacute;n o Subsecci&oacute;n', name: 'uni_descripcion'},
                    {display: 'Serie', name: 'ser_categoria'},
                ],
                sortname: "",
                sortorder: "asc",
                usepager: true,
                title: 'LISTA DE ENCUESTAS CONFIGURADAS',
                useRp: true,
                rp: 15,
                minimize: <?php echo $GRID_SW ?>,
                showTableToggleBtn: true,
                width: "100%",
                height: 320
            });

    function dobleClik(grid) {
        if ($('.trSelected div', grid).html())
        {
            $("#enc_id").val($('.trSelected div', grid).html());
            $("#formA").attr("action", "<?php echo $PATH_DOMAIN ?>/encuesta/edit/");
            document.getElementById('formA').submit();
        }
    }
    function test(com, grid)
    {
        if (com == 'Eliminar')
        {
            if ($('.trSelected div', grid).html()) {
                if (confirm('Esta seguro de eliminar el registro ' + $('.trSelected div', grid).html() + ' ?'))
                    $.ajax({
                        url: '<?php echo $PATH_DOMAIN ?>/encuesta/validaDepen/',
                        type: 'POST',
                        data: 'enc_id=' + $('.trSelected div', grid).html(),
                        dataType: "text",
                        success: function(datos)
                        {
                            if (datos != '')
                            {
                                alert(datos);
                            }
                            else
                            {
                                $.post("<?php echo $PATH_DOMAIN ?>/encuesta/delete/", {enc_id: $('.trSelected div', grid).html(), rand: Math.random()}, function(data) {
                                    if (data != true) {
                                        $('.pReload', grid.pDiv).click();
                                    }
                                });
                            }
                        }
                    });


            } else {
                alert("Por favor, seleccione un registro");
            }
        }
        else if (com == 'Adicionar')
        {
            window.location = "<?php echo $PATH_DOMAIN ?>/encuesta/add/";
        }
        else if (com == 'Editar') {
            if ($('.trSelected div', grid).html()) {
                $("#enc_id").val($('.trSelected div', grid).html());
                $("#formA").attr("action", "<?php echo $PATH_DOMAIN ?>/encuesta/edit/");
                document.getElementById('formA').submit();
            }
            else {
                alert("Por favor, seleccione un registro o escoja una serie ");
            }
        }
        else if (com == 'Datos encuesta')
        {
            if ($('.trSelected', grid).html())
            {
                $("#enc_id").val($('.trSelected div', grid).html());
                id = $("#enc_id").val();
                window.location = "<?php echo $PATH_DOMAIN ?>/enccampo/index/" + id + "/";
            }
            else
                alert("Por favor, seleccione un registro");
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

