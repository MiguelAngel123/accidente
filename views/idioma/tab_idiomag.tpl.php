<!--
  idiomagView

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
      action="<?php echo $PATH_DOMAIN ?>/idioma/<?php echo $PATH_EVENT ?>/">    
    <input name="idi_id" id="idi_id" type="hidden" value="<?php echo $idi_id; ?>" />
</form>

<script type="text/javascript">
    $("#flex1").flexigrid
    ({
        url: '<?php echo $PATH_DOMAIN ?>/idioma/load/',
        dataType: 'json',
        colModel : [
            {display: 'Id', name : 'idi_id', width : GetColumnSize(5), sortable : true, align: 'center'},
            {display: 'C&oacute;digo', name : 'idi_codigo', width : GetColumnSize(5), sortable : true, align: 'left'},
            {display: 'Nombre', name : 'idi_nombre', width : GetColumnSize(90), sortable : true, align: 'left'},
        ],
        buttons : [
            {name: 'Adicionar', bclass: 'add', onpress : test},
            {name: 'Eliminar', bclass: 'delete', onpress : test},
            {name: 'Editar', bclass: 'edit', onpress : test}
        ],
        searchitems : [
            {display: 'Id', name : 'idi_id', isdefault: true},
            {display: 'C&oacute;digo', name : 'idi_codigo'},
            {display: 'Nombre', name : 'idi_nombre'},
        ],
        sortname: "idi_id",
        sortorder: "asc",
        usepager: true,
        title: 'LISTA DE IDIOMAS',
        useRp: true,
        rp: 15,
        minimize: <?php echo $GRID_SW ?>,
        showTableToggleBtn: true,
        width: "100%",
        height: 400
    });

    function dobleClik(grid){
        if($('.trSelected div',grid).html())
        {
            $("#idi_id").val($('.trSelected div',grid).html());
            $("#formA").attr("action","<?php echo $PATH_DOMAIN ?>/idioma/edit/");
            document.getElementById('formA').submit();
        }
    }
    
    function test(com,grid)
    {
        if (com=='Eliminar')
        {
            if($('.trSelected div',grid).html()){
                if(confirm('Esta seguro de eliminar el registro ' + $('.trSelected div',grid).html() + ' ?')){
                    $.post("<?php echo $PATH_DOMAIN ?>/idioma/delete/",{idi_id:$('.trSelected div',grid).html(),rand:Math.random() } ,function(data){
                        if(data != true){
                            $('.pReload',grid.pDiv).click();
                        }
                    });
                }
            }else {
                alert("Por favor, seleccione un registro");
            }
        }
        
        else if (com=='Adicionar')
        {
            window.location="<?php echo $PATH_DOMAIN ?>/idioma/add/";
        } 
        else if (com=='Editar'){
            if($('.trSelected div',grid).html()){
                $("#idi_id").val($('.trSelected div',grid).html());
                $("#formA").attr("action","<?php echo $PATH_DOMAIN ?>/idioma/edit/");
                document.getElementById('formA').submit();
            }	
            else{
                alert("Por favor, seleccione un registro");
            }
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