<!--  
  departamentogView 

  @package
  @author lic. castellon
  @copyright DGGE
  @version $Id$ 2014.04.14
  @access public
-->
 
<div class="clear"></div><br/>
<p>
<table id="flex1" style="display: none"></table>
</p>
<div class="clear"></div>
<form id="formA" name="formA" method="post" class="validable"
      action="<?php echo $PATH_DOMAIN ?>/departamento/<?php echo $PATH_EVENT ?>/">
    <input name="dep_id" id="dep_id" type="hidden" value="<?php echo $dep_id; ?>" />
</form>

<script type="text/javascript">
    $("#flex1").flexigrid
            ({
                url: '<?php echo $PATH_DOMAIN ?>/departamento/load/',
                dataType: 'json',
                colModel: [
                    {display: 'Id', name: 'dep_id', width: GetColumnSize(5), sortable: true, align: 'center'},
                    {display: 'C&oacute;digo', name: 'dep_codigo', width: GetColumnSize(5), sortable: true, align: 'left'},
                    {display: 'Departamento', name: 'dep_nombre', width: GetColumnSize(90), sortable: true, align: 'left'},
                ],
                buttons: [
                    {name: 'Adicionar', bclass: 'add', onpress: test},
                    {name: 'Eliminar', bclass: 'delete', onpress: test},
                    {name: 'Editar', bclass: 'edit', onpress: test},
                    {separator: true},
                    {name: 'Provincias', bclass: 'add', onpress: test}
                ],
                searchitems: [
                    {display: 'Id', name: 'dep_id', isdefault: true},
                    {display: 'C&oacute;digo', name: 'dep_codigo'},
                    {display: 'Departamento', name: 'dep_nombre'},
                ],
                sortname: "dep_id",
                sortorder: "asc",
                usepager: true,
                title: 'LISTA DE DEPARTAMENTOS',
                useRp: true,
                rp: 15,
                minimize: <?php echo $GRID_SW ?>,
                showTableToggleBtn: true,
                width: "100%",
                height: 390
            });

    function dobleClik(grid) {
        if ($('.trSelected div', grid).html())
        {
            $("#dep_id").val($('.trSelected div', grid).html());
            $("#formA").attr("action", "<?php echo $PATH_DOMAIN ?>/departamento/edit/");
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
                        url: '<?php echo $PATH_DOMAIN ?>/departamento/validaDepen/',
                        type: 'POST',
                        data: 'dep_id=' + $('.trSelected div', grid).html(),
                        dataType: "text",
                        success: function(datos)
                        {
                            if (datos != '')
                            {
                                alert(datos);
                            }
                            else
                            {
                                $.post("<?php echo $PATH_DOMAIN ?>/departamento/delete/", {dep_id: $('.trSelected div', grid).html(), rand: Math.random()}, function(data) {
                                    if (data != true) {
                                        $('.pReload', grid.pDiv).click();
                                    }
                                });
                            }
                        }
                    });
            }
            else {
                alert("Por favor, seleccione un registro");
            }
        }
        else if (com == 'Adicionar')
        {
            window.location = "<?php echo $PATH_DOMAIN ?>/departamento/add/";
        }
        else if (com == 'Editar') {
            if ($('.trSelected div', grid).html()) {
                $("#dep_id").val($('.trSelected div', grid).html());
                $("#formA").attr("action", "<?php echo $PATH_DOMAIN ?>/departamento/edit/");
                document.getElementById('formA').submit();
            }
            else {
                alert("Por favor, seleccione un registro");
            }
        }
        else if (com == 'Provincias')
        {
            if ($('.trSelected', grid).html())
            {
                $("#dep_id").val($('.trSelected div', grid).html());
                window.location = "<?php echo $PATH_DOMAIN ?>/provincia/index/" + $("#dep_id").val() + "/";
            }
            else
                alert("Por favor, seleccione un registro");
        }
    }
    
    function GetColumnSize(percent){ 
        screen_res = ($(document).width()-50)*1;         
        col = parseInt((percent*(screen_res/100))); 
        if (percent != 100){ 
            return col; 
        }else{ 
            return col; 
        } 
    } 
    
</script>