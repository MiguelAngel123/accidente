<!--  
  usuariogView 

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
      action="<?php echo $PATH_DOMAIN ?>/usuario/<?php echo $PATH_EVENT ?>/">
    <input name="usu_id" id="usu_id" type="hidden" value="<?php echo $usu_id; ?>" />
</form>

<script type="text/javascript">
    $("#flex1").flexigrid
    ({
        url: '<?php echo $PATH_DOMAIN ?>/usuario/load/',
        dataType: 'json',
        colModel : [
            {display: 'Id', name : 'usu_id', width : GetColumnSize(5), sortable : true, align: 'center'},            
            {display: 'Nombres', name : 'usu_nombres', width : GetColumnSize(10), sortable : true, align: 'left'},
            {display: 'Apellidos', name : 'usu_apellidos', width : GetColumnSize(10), sortable : true, align: 'left'},
            {display: 'Rol', name : 'rol_descripcion', width : GetColumnSize(20), sortable : true, align: 'left'},
            {display: 'Entidad/Unidad', name : 'uni_descripcion', width : GetColumnSize(20), sortable : true, align: 'left'},            
            {display: 'Tel&eacute;fono', name : 'usu_fono', width : GetColumnSize(10), sortable : true, align: 'left'},
            {display: 'Email', name : 'usu_email', width : GetColumnSize(15), sortable : true, align: 'left'},            
            {display: 'Login', name : 'usu_login', width : GetColumnSize(10), sortable : true, align: 'left'}
        ],
        buttons : [
            {name: 'Adicionar', bclass: 'add', onpress : test},
            {name: 'Eliminar', bclass: 'delete', onpress : test},
            {name: 'Editar', bclass: 'edit', onpress : test},
            {separator: true},
            {name: 'Clonar', bclass: 'add', onpress : test}
        ],
        searchitems : [
            {display: 'Id', name : 'usu_id', isdefault: true},            
            {display: 'Nombres', name : 'usu_nombres'},
            {display: 'Apellidos', name : 'usu_apellidos'},
            {display: 'Rol', name : 'rol_descripcion'},
            {display: 'Unidad', name : 'unidad'},            
            {display: 'Tel&eacute;fono', name : 'usu_fono'},
            {display: 'Email', name : 'usu_email'},
            {display: 'Login', name : 'usu_login'},
        ],
        sortname: "usu_id",
        sortorder: "asc",
        usepager: true,
        title: 'LISTA DE USUARIOS DEL SISTEMA',
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
            $("#usu_id").val($('.trSelected div',grid).html());
            $("#formA").attr("action","<?php echo $PATH_DOMAIN ?>/usuario/edit/");
            document.getElementById('formA').submit();
        }
    }
    
    function test(com,grid)
    {
        if (com=='Eliminar')
        {
            if($('.trSelected div',grid).html()){
                if(confirm('Esta seguro de eliminar el registro ' + $('.trSelected div',grid).html() + ' ?'))
                    $.post("<?php echo $PATH_DOMAIN ?>/usuario/delete/",{usu_id:$('.trSelected div',grid).html(),rand:Math.random() } ,function(data){
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
            window.location="<?php echo $PATH_DOMAIN ?>/usuario/add/";
        }
        else if (com=='Editar'){
            if($('.trSelected div',grid).html()){
                $("#usu_id").val($('.trSelected div',grid).html());
                $("#formA").attr("action","<?php echo $PATH_DOMAIN ?>/usuario/edit/");
                document.getElementById('formA').submit();
            }
            else{
                alert("Por favor, seleccione un registro");
            }
        }
        else if (com=='Clonar'){
            if($('.trSelected div',grid).html()){
                $("#usu_id").val($('.trSelected div',grid).html());
                $("#formA").attr("action","<?php echo $PATH_DOMAIN ?>/usuario/clonar/");
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
