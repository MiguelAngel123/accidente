<!--
  sistemagView

  @package
  @author lic. castellon
  @copyright DGGE
  @version $Id$ 2014.04.14
  @access public
-->


<div class="clear"></div><br>
<p>
<table id="flex1" style="display: none"></table>
</p>
<div class="clear"></div>
<form class="validable" id="formA" method="post" name="formA"
      action="<?php echo $PATH_DOMAIN ?>/sistema/<?php echo $PATH_EVENT ?>/">
    <input name="sis_id" id="sis_id" type="hidden" value="<?php echo $sis_id; ?>" />
</form>

<script type="text/javascript">
    $("#flex1").flexigrid
    ({
        url: '<?php echo $PATH_DOMAIN ?>/sistema/load/',
        dataType: 'json',
        colModel: [
            {display: 'Id', name: 'sis_id', width: GetColumnSize(5), sortable: true, align: 'center'},
            {display: 'C&oacute;digo', name: 'sis_codigo', width: GetColumnSize(5), sortable: true, align: 'left'},
            {display: 'Nombre del Sistema', name: 'sis_nombre', width: GetColumnSize(30), sortable: true, align: 'left'},
            {display: 'Tipo de Carga', name: 'sis_tipcarga', width: GetColumnSize(10), sortable: true, align: 'left'},
            {display: 'Tama&ntilde;o Maximo (Kb.)', name: 'sis_tammax', width: GetColumnSize(15), sortable: true, align: 'left'},
            {display: 'Ruta de Carga', name: 'sis_ruta', width: GetColumnSize(10), sortable: true, align: 'left'},
            {display: 'Ruta de Carga Importaci&oacute;n Excel', name: 'sis_rutaexcel', width: GetColumnSize(10), sortable: true, align: 'left'},
            {display: 'Recordar Palabras clave', name: 'sis_palclave', width: GetColumnSize(15), sortable: true, align: 'left'},
        ],
        buttons: [
            {name: 'Editar', bclass: 'edit', onpress: test},
            {separator: true}
        ],
        searchitems: [
            {display: 'Id', name: 'sis_id', isdefault: true},
            {display: 'C&oacute;digo', name: 'sis_codigo'},
            {display: 'Nombre del Sistema', name: 'sis_nombre'},
            {display: 'Tipo de Carga', name: 'sis_tipcarga'},
            {display: 'Tama&ntilde;o Maximo (Kb.)', name: 'sis_tammax'},
            {display: 'Ruta de Carga Importaci&oacute;n Excel', name: 'sis_rutaexcel'},
            {display: 'Recordar Palabras clave', name: 'sis_palclave'},
        ],
        sortname: "sis_id",
        sortorder: "asc",
        usepager: true,
        title: 'LISTA DE DATOS DEL SISTEMA',
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
            $("#sis_id").val($('.trSelected div', grid).html());
            $("#formA").attr("action", "<?php echo $PATH_DOMAIN ?>/sistema/edit/");
            document.getElementById('formA').submit();
        }
    }

    function test(com, grid)
    {
        if (com == 'Editar') {
            if ($('.trSelected div', grid).html()) {
                $("#sis_id").val($('.trSelected div', grid).html());
                $("#formA").attr("action", "<?php echo $PATH_DOMAIN ?>/sistema/edit/");
                document.getElementById('formA').submit();
            }
            else {
                alert("Por favor, seleccione un registro");
            }
        }
    }
    
    
     function GetColumnSize(percent){ 
        screen_res = ($(document).width()-100)*1;         
        col = parseInt((percent*(screen_res/100))); 
        if (percent != 100){ 
            return col; 
        }else{ 
            return col; 
        } 
    } 

</script>