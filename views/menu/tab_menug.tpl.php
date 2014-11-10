<!--  
  menugView 

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

<form id="formA" name="formA" method="post" class="validable"
      action="<?php echo $PATH_DOMAIN ?>/menu/<?php echo $PATH_EVENT ?>/">
    <input name="men_id" id="men_id" type="hidden"
           value="<?php echo $men_id; ?>" />
</form>

<script type="text/javascript">
    $("#flex1").flexigrid
    ({
        url: '<?php echo $PATH_DOMAIN ?>/menu/load/',
        dataType: 'json',
        colModel : [
            {display: 'Id', name : 'men_id', width : GetColumnSize(5), sortable : true, align: 'center'},
            {display: 'Titulo', name : 'men_titulo', width : GetColumnSize(50), sortable : true, align: 'left'},
            {display: 'Enlace', name : 'men_enlace', width : GetColumnSize(30), sortable : true, align: 'left'},
            {display: 'Posicion', name : 'men_posicion', width : GetColumnSize(15), sortable : true, align: 'left'}
        ],
        buttons : [
            {name: 'Adicionar', bclass: 'add', onpress : test},
            {name: 'Eliminar', bclass: 'delete', onpress : test},
            {name: 'Editar', bclass: 'edit', onpress : test},
            {separator: true}
        ],
        searchitems : [
            {display: 'Id', name : 'men_id', isdefault: true},
            {display: 'Titulo', name : 'men_titulo'},
            {display: 'Enlace', name : 'men_enlace'},
            {display: 'Posicion', name : 'men_posicion'}
        ],
        sortname: "men_id",
        sortorder: "asc",
        usepager: true,
        title: 'LISTA DE MEN&Uacute;S DE OPCIONES',
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
            $("#men_id").val($('.trSelected div',grid).html());
            $("#formA").attr("action","<?php echo $PATH_DOMAIN ?>/menu/edit/");
            document.getElementById('formA').submit();
        }
    }
    
    function test(com,grid)
    {
        if (com=='Eliminar')
        {
            if($('.trSelected div',grid).html()){
                if(confirm('Esta seguro de eliminar el registro ' + $('.trSelected div',grid).html() + ' ?'))
                    $.post("<?php echo $PATH_DOMAIN ?>/menu/delete/",{men_id:$('.trSelected div',grid).html(),rand:Math.random() } ,function(data){
                        if(data != true){
                            $('.pReload',grid.pDiv).click();
                        }
                });
            }else{
                alert("Por favor, seleccione un registro");
            }
        }
        else if (com=='Adicionar')
        {
            window.location="<?php echo $PATH_DOMAIN ?>/menu/add/";
        } 
        else if (com=='Editar'){
            if($('.trSelected div',grid).html()){
                $("#men_id").val($('.trSelected div',grid).html());
                $("#formA").attr("action","<?php echo $PATH_DOMAIN ?>/menu/edit/");
                document.getElementById('formA').submit();
            }	
            else{
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