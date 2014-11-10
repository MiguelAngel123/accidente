<!--
  unidadgView

  @package
  @author lic. castellon
  @copyright DGGE
  @version $Id$ 2014.04.24
  @access public
-->

<div class="clear"></div><br/><br/>
<p><table id="flex1" style="display:none"></table></p>
<div class="clear"></div>

<form id="formA" name="formA" method="post" class="validable" 
      action="<?php echo $PATH_DOMAIN ?>/unidad/<?php echo $PATH_EVENT ?>/" >
    <input name="uni_id" id="uni_id" type="hidden" value="" />
</form>

<form id="formB" name="formB" method="post" class="validable" 
      action="<?php echo $PATH_DOMAIN ?>/unidad/<?php echo $PATH_EVENT ?>/" target="_blank" >
    <input name="uni_id2" id="uni_id2" type="hidden" value="" />
</form>

<script type="text/javascript">
    $("#flex1").flexigrid
    ({
        url: '<?php echo $PATH_DOMAIN ?>/unidad/load/',
        dataType: 'json',
        colModel: [
            {display: 'Id', name: 'uni_id', width: GetColumnSize(5), sortable: true, align: 'center'},
            {display: 'C&oacute;digo', name: 'uni_cod', width: GetColumnSize(10), sortable: true, align: 'left'},
            {display: 'Nombre Secci&oacute;n o Subsecci&oacute;n', name: 'uni_descripcion', width: GetColumnSize(40), sortable: true, align: 'left'},
            {display: 'Subsecci&oacute;n de', name: 'uni_par_cod', width: GetColumnSize(25), sortable: true, align: 'left'},
            {display: 'Fondo o Subfondo', name: 'fon_descripcion', width: GetColumnSize(15), sortable: true, align: 'left'},
            {display: 'Contador', name: 'uni_contador', width: GetColumnSize(5), sortable: true, align: 'left'}
        ],
        buttons: [
            {name: 'Adicionar', bclass: 'add', onpress: test}, 
            {name: 'Eliminar', bclass: 'delete', onpress: test}, 
            {name: 'Editar', bclass: 'edit', onpress: test},{separator: true},
            {name: 'Imprimir cuadro clasificacion', bclass: 'pdf', onpress: test}, 
            {name: 'Imprimir cuadro clasificacion Excel', bclass: 'page_excel', onpress: test}, 
        ],
        searchitems: [
            {display: 'Id', name: 'uni_id'},
            {display: 'Fondo o Subfondo', name: 'fon_descripcion'},
            {display: 'Secci&oacute;n o Subsecci&oacute;n', name: 'uni_descripcion'},
        ],
        sortname: "",
        sortorder: "asc",
        usepager: true,
        title: 'LISTA DE SECCIONES O SUBSECCIONES',
        useRp: true,
        rp: 15,
        minimize: <?php echo $GRID_SW ?>,
        showTableToggleBtn: true,
        width: "100%",
        height: 400
    });

    function dobleClik(grid) {
        if ($('.trSelected div', grid).html()) {
            $("#uni_id").val($('.trSelected div', grid).html());
            $("#formA").attr("action", "<?php echo $PATH_DOMAIN ?>/unidad/edit/");
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
                        url: '<?php echo $PATH_DOMAIN ?>/unidad/delete/',
                        type: 'POST',
                        data: 'uni_id=' + $('.trSelected div', grid).html(),
                        dataType: "json",
                        success: function(datos) {
                            var j = 0;
                            if (datos) {
                                alerta = "No se puede eliminar la unidad! \nTiene los siguientes dependientes:\n";
                                jQuery.each(datos, function(i, item) {
                                    j++;
                                    alerta = alerta + item + ", ";
                                });
                            }
                            if (j == 0) {
                                $('.pReload', grid.pDiv).click();
                                $('#flex1.pReload', grid.pDiv).click();
                            }
                            else {
                                alert(alerta);
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
            window.location = "<?php echo $PATH_DOMAIN ?>/unidad/add/";
        }
        else if (com == 'Editar') {
            if ($('.trSelected div', grid).html()) {
                $("#uni_id").val($('.trSelected div', grid).html());
                $("#formA").attr("action", "<?php echo $PATH_DOMAIN ?>/unidad/edit/");
                document.getElementById('formA').submit();
            }
            else {
                alert("Por favor, seleccione un registro o una seccion");
            }
        }
        else if (com == 'Imprimir')
        {
            window.location = "<?php echo $PATH_DOMAIN ?>/unidad/rpteUnidad/";
        }
        else if (com == 'Imprimir cuadro clasificacion')
        {
            $("#formB").attr("action", "<?php echo $PATH_DOMAIN ?>/unidad/impresion/");
            document.getElementById('formB').submit();
        }
        else if (com == 'Imprimir cuadro clasificacion Excel')
        {
            $("#formA").attr("action", "<?php echo $PATH_DOMAIN ?>/unidad/impresionExcel/");
            document.getElementById('formA').submit();
        }         
        else {
            $(".qsbox").val(com);
            $('.Search', grid.pDiv).click();
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
