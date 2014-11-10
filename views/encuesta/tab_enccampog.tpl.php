<div class="clear"></div><br/>
<p><table id="flex1" style="display:none"></table></p>
<div class="clear"></div>
<form id="formA" name="formA" method="post" class="validable"
      action="<?php echo $PATH_DOMAIN ?>/enccampo/<?php echo $PATH_EVENT ?>/">
    <input name="ecp_id" id="ecp_id" type="hidden" value="" />
</form>

<p align="right"><a href="<?php echo $PATH_DOMAIN; ?>/encuesta/"><<<< Volver a encuesta </a></p>

<script type="text/javascript">
    $("#flex1").flexigrid
            ({
                url: '<?php echo $PATH_DOMAIN ?>/enccampo/load/<?php echo VAR3; ?>/',
                dataType: 'json',
                colModel: [
                    {display: 'Id', name: 'ecp_id', width: GetColumnSize(5), sortable: true, align: 'center'},
                    {display: 'Grupo', name: 'egr_nombre', width: GetColumnSize(10), sortable: true, align: 'left'},
                    {display: 'Orden', name: 'ecp_orden', width: GetColumnSize(5), sortable: true, align: 'left'},
                    {display: 'Nombre', name: 'ecp_nombre', width: GetColumnSize(8), sortable: true, align: 'left'},
                    {display: 'Etiqueta', name: 'ecp_eti', width: GetColumnSize(60), sortable: true, align: 'left'},
                    {display: 'Tipo dato', name: 'ecp_tipdat', width: GetColumnSize(10), sortable: true, align: 'left'},
                ],
                buttons: [
                    {name: 'Adicionar', bclass: 'add', onpress: test},
                    {name: 'Eliminar', bclass: 'delete', onpress: test},
                    {name: 'Editar', bclass: 'edit', onpress: test},
                    {separator: true},
                    {name: 'Datos de la lista', bclass: 'add', onpress: test}

                ],
                searchitems: [
                    {display: 'Id', name: 'ecp_id', isdefault: true},
                    {display: 'Orden', name: 'ecp_orden'},
                    {display: 'Nombre campo', name: 'ecp_nombre'},
                    {display: 'Etiqueta', name: 'ecp_eti'},
                    {display: 'Tipo dato', name: 'ecp_tipdat'}
                ],
                sortname: "",
                sortorder: "asc",
                usepager: true,
                title: 'DATOS ADICIONALES - SERIE: <?php echo $enc_categoria; ?> ',
                useRp: true,
                rp: 15,
                minimize: <?php echo $GRID_SW ?>,
                showTableToggleBtn: true,
                width: "100%",
                height: 400
            });

    function dobleClik(grid) {
        if ($('.trSelected div', grid).html())
        {
            $("#ecp_id").val($('.trSelected div', grid).html());
            $("#formA").attr("action", "<?php echo $PATH_DOMAIN ?>/enccampo/edit/");
            document.getElementById('formA').submit();
        }
    }
    function test(com, grid)
    {
        if (com == 'Eliminar')
        {
            if ($('.trSelected div', grid).html()) {
                if (confirm('Esta seguro de eliminar el registro ' + $('.trSelected div', grid).html() + ' ?'))
                    $.post("<?php echo $PATH_DOMAIN ?>/enccampo/delete/", {ecp_id: $('.trSelected div', grid).html(), rand: Math.random()}, function(data) {
                        if (data != true) {
                            $('.pReload', grid.pDiv).click();
                        }
                    });
            } else {
                alert("Por favor, seleccione un registro");
            }
        }
        else if (com == 'Adicionar')
        {
            window.location = "<?php echo $PATH_DOMAIN ?>/enccampo/add/<?php echo VAR3; ?>/";
        }
        else if (com == 'Editar') {
            if ($('.trSelected div', grid).html()) {
                $("#ecp_id").val($('.trSelected div', grid).html());
                $("#formA").attr("action", "<?php echo $PATH_DOMAIN ?>/enccampo/edit/");
                document.getElementById('formA').submit();
            }
            else {
                alert("Por favor, seleccione un registro");
            }
        }
        else if (com == 'Datos de la lista')
        {
            if ($('.trSelected', grid).html())
            {
                $("#ecp_id").val($('.trSelected div', grid).html());
                id = $("#ecp_id").val();
                window.location = "<?php echo $PATH_DOMAIN ?>/enccampolista/index/" + id + "/";
            }
            else
                alert("Por favor, seleccione un registro");
        }
    }

    function GetColumnSize(percent) {
        screen_res = ($(document).width() - 50) * 1;
        col = parseInt((percent * (screen_res / 100)));
        if (percent != 100) {
            return col;
        } else {
            return col;
        }
    }
</script>