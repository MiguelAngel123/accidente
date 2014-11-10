<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <link href="<?php echo $PATH_WEB ?>/css/style.css" rel="stylesheet" type="text/css" />
                        <script type="text/javascript" src="<?php echo $PATH_WEB ?>/js/jquery-1.3.2.js"></script>	

    </head>
    <body>
<form id="formA" name="formA" method="post" class="validable"
      action="<?php echo $PATH_DOMAIN ?>/editSecciones/<?php echo $PATH_EVENT ?>/"
      target="_blank">
<center>
    <table width="600" border="0">
        <caption class="titulo">
            LISTADO INVENTARIO POR SECCIONES DE encuestaS
        </caption>
        <tr>
            <td>Secci&oacute;n/Subsecci&oacute;n:</td>
            <td>
                <select name="filtro_seccion" style="width: 300px;" id="filtro_seccion" >
                    <?php echo ($seccion) ?>
                </select></td>
        </tr>

        <tr>
            <td class="botones" colspan="2">
       <input id="btnSub" type="submit" value="Reporte" class="button" /> 
            </td>
        </tr>
        
        
    </table>
</center>
</form>
<style type="text/css">
   
    .button2 {
	cursor: pointer;
	color: #2980c4;
	text-align: center;
	font-weight: bold;
	border: none;
	background: url(../lib/btn_form.gif) no-repeat;
	width: 70px;
	height: 33px;
}
</style>
    <?php 
echo $contenido;
?>

<script languaje="javascript">

function editar(str){
   
    $("#titulo"+str).css("display","block");
    $("#subtitulo"+str).css("display","block");
    $("#tomovol"+str).css("display","block");
    $("#nrocaj"+str).css("display","block");
    $("#sala"+str).css("display","block");
    $("#estante"+str).css("display","block");
    $("#balda"+str).css("display","block");
    $("#cuerpo"+str).css("display","block");
    $("#editar"+str).css("display","none");
    $("#guardar"+str).css("display","block");
    $("#fechai"+str).css("display","block");
    $("#fechaf"+str).css("display","block");
    $("#obs"+str).css("display","block");
    $("#editar"+str).css("display","none");
    $("#guardar"+str).css("display","block");
    $("#nrofojas"+str).css("display","block");
    $("#productor"+str).css("display","block");
    $("#responsable"+str).css("display","block");
    $("#palclaves"+str).css("display","block");
    $("#alccon"+str).css("display","block");
    $("#cantpaq"+str).css("display","block");
    $("#nropaq"+str).css("display","block");
   
    $("#text-alccon"+str).css("display","none");
    $("#text-resproc"+str).css("display","none");
    $("#text-nrofojas"+str).css("display","none");
    $("#text-obs"+str).css("display","none");
    $("#text-fecha"+str).css("display","none");
    $("#text-titulo"+str).css("display","none");
    $("#text-tomovol"+str).css("display","none");
    $("#text-nrocaj"+str).css("display","none");
    $("#text-sala"+str).css("display","none");
    $("#text-estante"+str).css("display","none");
    $("#text-balda"+str).css("display","none");
    $("#text-cuerpo"+str).css("display","none");
    
    $("#text-palclaves"+str).css("display","none");
}
function guardar(str,id){


      $.ajax({
url: '<?php echo $PATH_DOMAIN ?>/editSecciones/guardarDocumento/',
type: 'POST',
data: 'id_doc='+id+'&titulo='+ $("#titulo"+str).val()+'&subtitulo='+ $("#subtitulo"+str).val()+'&tomovol='+$("#tomovol"+str).val()+'&nrocaj='+$("#nrocaj"+str).val()+'&sala='+$("#sala"+str).val()+'&estante='+$("#estante"+str).val()+'&balda='+$("#balda"+str).val()+'&cuerpo='+$("#cuerpo"+str).val()+'&fechai='+$("#fechai"+str).val()+'&fechaf='+$("#fechaf"+str).val()+'&obs='+$("#obs"+str).val()+'&nrofojas='+$("#nrofojas"+str).val()+'&productor='+$("#productor"+str).val()+'&responsable='+$("#responsable"+str).val()+'&palclaves='+$("#palclaves"+str).val()+'&alccon='+$("#alccon"+str).val()+'&cantpaq='+$("#cantpaq"+str).val()+'&nropaq='+$("#nropaq"+str).val(),
dataType:  'text',
                success: function(datos){
                    alert(datos);
                }
            });
    $("#titulo"+str).css("display","none");
    $("#subtitulo"+str).css("display","none");
    $("#tomovol"+str).css("display","none");
    $("#nrocaj"+str).css("display","none");
    $("#sala"+str).css("display","none");
    $("#estante"+str).css("display","none");
    $("#balda"+str).css("display","none");
    $("#cuerpo"+str).css("display","none");
    $("#fechai"+str).css("display","none");
    $("#fechaf"+str).css("display","none");
    $("#obs"+str).css("display","none");
    $("#nrofojas"+str).css("display","none");
    $("#alccon"+str).css("display","none");
    $("#cantpaq"+str).css("display","none");
    $("#nropaq"+str).css("display","none");
    
   
    $("#text-cantpaq"+str).css("display","block");
    $("#text-alccon"+str).css("display","block");
    $("#text-nrofojas"+str).css("display","block");
    $("#palclaves"+str).css("display","none");
    $("#text-palclaves"+str).css("display","block");
    $("#guardar"+str).css("display","none");
    $("#editar"+str).css("display","block");
    $("#productor"+str).css("display","none");
    $("#responsable"+str).css("display","none");
    
    $("#text-resproc"+str).css("display","block");
    $("#text-obs"+str).css("display","block");
    $("#text-fecha"+str).css("display","block");
    $("#text-titulo"+str).css("display","block");
    $("#text-tomovol"+str).css("display","block");
    $("#text-nrocaj"+str).css("display","block");
    $("#text-sala"+str).css("display","block");
    $("#text-estante"+str).css("display","block");
    $("#text-balda"+str).css("display","block");
    $("#text-cuerpo"+str).css("display","block");
 
    $("#text-nropaq"+str).html($("#nropaq"+str).val());
    $("#text-cantpaq"+str).html($("#cantpaq"+str).val());
    $("#text-obs"+str).html($("#obs"+str).val());
    $("#text-fecha"+str).html($("#fechai"+str).val()+"-"+$("#fechaf"+str).val());
    $("#text-titulo"+str).html($("#titulo"+str).val()+" - "+$("#subtitulo"+str).val());
    $("#text-tomovol"+str).html($("#tomovol"+str).val());
    $("#text-nrocaj"+str).html($("#nrocaj"+str).val());
    $("#text-sala"+str).html($("#sala"+str).val());
    $("#text-estante"+str).html($("#estante"+str).val());
    $("#text-balda"+str).html($("#balda"+str).val());
    $("#text-cuerpo"+str).html($("#cuerpo"+str).val());
    $("#text-nrofojas"+str).html($("#nrofojas"+str).val());
    $("#text-alccon"+str).html('<b>Alcance y Contenido: </b><br>'+$("#alccon"+str).val());
    $("#text-palclaves"+str).html('<b>Palabras Claves: </b>'+$("#palclaves"+str).val());
    $("#text-resproc"+str).html('<b>Responsable:</b><br>'+$("#responsable"+str).val()+'<br><b>Productor:</b><br>'+$("#productor"+str).val());
}
function editarexp(str){
   
    $("#tituloexp"+str).css("display","block");
    $("#tomovolexp"+str).css("display","block");
    $("#nrocajexp"+str).css("display","block");
    $("#salaexp"+str).css("display","block");
    $("#estanteexp"+str).css("display","block");
    $("#baldaexp"+str).css("display","block");
    $("#cuerpoexp"+str).css("display","block");
    $("#guardarexp"+str).css("display","block");
    $("#obsexp"+str).css("display","block");
    $("#exp_anioi"+str).css("display","block");
    $("#exp_aniof"+str).css("display","block");
    $("#notasexp"+str).css("display","block");
    $("#alconexp"+str).css("display","block");
    $("#editarexp"+str).css("display","none");
    $("#text-productorexp"+str).css("display","none");
    $("#text-nrofojasexp"+str).css("display","none");
    
    $("#nrofojasexp"+str).css("display","block");
    $("#productorexp"+str).css("display","block");
    $("#text-obsexp"+str).css("display","none");
    $("#text-fechaexp"+str).css("display","none");
    $("#text-tituloexp"+str).css("display","none");
    $("#text-tomovolexp"+str).css("display","none");
    $("#text-nrocajexp"+str).css("display","none");
    $("#text-salaexp"+str).css("display","none");
    $("#text-estanteexp"+str).css("display","none");
    $("#text-baldaexp"+str).css("display","none");
    $("#text-cuerpoexp"+str).css("display","none");
    $("#text-alconexp"+str).css("display","none");
    $("#text-notasexp"+str).css("display","none");
    $("#text-alconexp"+str).css("display","none");
    $("#text-contenidoexp"+str).css("display","none");
   
}
function guardarencuestas(str,idexp){
    
       $("#tituloexp"+str).css("display","none");
    $("#tomovolexp"+str).css("display","none");
    $("#nrocajexp"+str).css("display","none");
    $("#salaexp"+str).css("display","none");
    $("#estanteexp"+str).css("display","none");
    $("#baldaexp"+str).css("display","none");
    $("#cuerpoexp"+str).css("display","none");
    $("#guardarexp"+str).css("display","none");
    $("#obsexp"+str).css("display","none");
    $("#exp_anioi"+str).css("display","none");
    $("#exp_aniof"+str).css("display","none");
    $("#notasexp"+str).css("display","none");
    $("#alconexp"+str).css("display","none");
    $("#editarexp"+str).css("display","block");
         $.ajax({
url: '<?php echo $PATH_DOMAIN ?>/editSecciones/guardarencuesta/',
type: 'POST',
data: 'id_exp='+idexp+'&tituloexp='+ $("#tituloexp"+str).val()+'&alconexp='+ $("#alconexp"+str).val()+'&notasexp='+ $("#notasexp"+str).val()+'&tomovolexp='+$("#tomovolexp"+str).val()+'&nrocajexp='+$("#nrocajexp"+str).val()+'&salaexp='+$("#salaexp"+str).val()+'&estanteexp='+$("#estanteexp"+str).val()+'&baldaexp='+$("#baldaexp"+str).val()+'&cuerpoexp='+$("#cuerpoexp"+str).val()+'&exp_anioi='+$("#exp_anioi"+str).val()+'&exp_aniof='+$("#exp_aniof"+str).val()+'&obsexp='+$("#obsexp"+str).val()+'&productorexp='+$("#productorexp"+str).val()+'&nrofojasexp='+$("#nrofojasexp"+str).val(),
dataType:  'text',
                success: function(datos){
                    alert(datos);
                }
            });
 
    
    $("#text-obsexp"+str).css("display","block");
    $("#text-fechaexp"+str).css("display","block");
    $("#text-tituloexp"+str).css("display","block");
    $("#text-tomovolexp"+str).css("display","block");
    $("#text-nrocajexp"+str).css("display","block");
    $("#text-salaexp"+str).css("display","block");
    $("#text-estanteexp"+str).css("display","block");
    $("#text-baldaexp"+str).css("display","block");
    $("#text-cuerpoexp"+str).css("display","block");
    $("#text-alconexp"+str).css("display","block");
    $("#text-notasexp"+str).css("display","block");
    $("#text-alconexp"+str).css("display","block");
    $("#text-contenidoexp"+str).css("display","block");
    
    $("#text-productorexp"+str).css("display","block");
    $("#text-nrofojasexp"+str).css("display","block");
    
    $("#nrofojasexp"+str).css("display","none");
    $("#productorexp"+str).css("display","none");
    
    $("#text-obsexp"+str).html($("#obsexp"+str).val());
    $("#text-fechaexp"+str).html($("#exp_anioi"+str).val()+"-"+$("#exp_aniof"+str).val());
    $("#text-tituloexp"+str).html('<b>encuesta:</b>'+$("#tituloexp"+str).val());
    $("#text-tomovolexp"+str).html('<b>'+$("#tomovolexp"+str).val()+'</b>');
    $("#text-nrocajexp"+str).html('<b>'+$("#nrocajexp"+str).val()+'</b>');
    $("#text-salaexp"+str).html('<b>'+$("#salaexp"+str).val()+'</b>');
    $("#text-estanteexp"+str).html('<b>'+$("#estanteexp"+str).val()+'</b>');
    $("#text-baldaexp"+str).html('<b>'+$("#baldaexp"+str).val()+'</b>');
    $("#text-cuerpoexp"+str).html('<b>'+$("#cuerpoexp"+str).val()+'</b>');
    $("#text-alconexp"+str).html($("#alconexp"+str).val());
    $("#text-notasexp"+str).html($("#notasexp"+str).val());
    $("#text-contenidoexp"+str).html('<b>Alcance y contenido:</b><br>'+$("#alconexp"+str).val()+'<br><b>&Aacute;rea de Notas:</b><br>'+$("#notasexp"+str).val());
    $("#text-productorexp"+str).html($("#productorexp"+str).val());
    $("#text-nrofojasexp"+str).html($("#nrofojasexp"+str).val());
}
 </script></body>
</html>