<?php 
/**
 * UYCODEKA
 * Copyright (C) MCC (http://www.mcc.com.uy)
 *
 * Este programa es software libre: usted puede redistribuirlo y/o
 * modificarlo bajo los términos de la Licencia Pública General Affero de GNU
 * publicada por la Fundación para el Software Libre, ya sea la versión
 * 3 de la Licencia, o (a su elección) cualquier versión posterior de la
 * misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero
 * SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
 * MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
 * Consulte los detalles de la Licencia Pública General Affero de GNU para
 * obtener una información más detallada.
 *
 * Debería haber recibido una copia de la Licencia Pública General Affero de GNU
 * junto a este programa.
 * En caso contrario, consulte <http://www.gnu.org/licenses/agpl.html>.
 */
 
session_start();
require_once('../../class/class_session.php');
/*
Instantiate a new session object. If session exists, it will be restored,
otherwise, a new session will be created--placing a sid cookie on the user's
computer.
*/
if (!$s = new session()) {
  /*
  There is a problem with the session! The class has a 'log' property that
  contains a log of events. This log is useful for testing and debugging.
  */
  echo "<h2>Ocurrió un error al iniciar session!</h2>";
  echo $s->log;
  exit();
}

if((!$s->data['isLoggedIn']) || !($s->data['isLoggedIn']))
{
	/*/user is not logged in*/
	echo "<script>location.href='../../index.php'; </script>";
   //header("Location:../index.php");	

} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
  		//header("Location:../index.php");	
		echo "<script>window.top.location.href='../../index.php'; </script>";
	   exit;
   }
   $s->data['loggedAt']= time();
   $s->save();
}

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];
@$paginacion=$s->data['alto'];

if($paginacion<=0) {
	$paginacion=20;
}
$Seleccionados=array();
$Seleccionados=@$s->data['Selected'];

include ("../../conectar.php");
include("../../common/verificopermisos.php");
include("../../common/funcionesvarias.php");
include ("../../funciones/fechas.php"); 

header('Cache-Control: no-cache');
header('Pragma: no-cache'); 
header('Content-Type: text/html; charset=UTF-8');

 
$codproyectos=$_GET["codproyectos"];

$query="SELECT * FROM proyectos WHERE codproyectos='$codproyectos'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

?>
<html>
	<head>
		<title>Principal</title>
		<link href="../../estilos/estilos.css" type="text/css" rel="stylesheet">
    <script src="../../calendario/jscal2.js"></script>
    <script src="../../calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../../calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../../calendario/css/win2k/win2k.css" />	
		<script type="text/javascript" src="../../funciones/validar.js"></script>

		<script src="../../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../../js3/colorbox.css" />
		<script src="../../js3/jquery.colorbox.js"></script>

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../../css3/css/font-awesome.min.css">
		
<script type="text/javascript">
$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

var headID = window.parent.document.getElementsByTagName("head")[0];         
var newScript = window.parent.document.createElement('script');
newScript.type = 'text/javascript';
newScript.src = '../../js3/jquery.colorbox.js';
headID.appendChild(newScript);
});

</script>
		<script language="javascript">
		
		function cancelar() {
			
			parent.$('idOfDomElement').colorbox.close();
			/*location.href="index.php?cadena_busqueda=<?php echo $cadena_busqueda?>";*/
		}
		
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}
		
		function limpiar() {
			document.getElementById("formulario").reset();
		}
	
		</script>

<script type="text/javascript">

var newItemHide='';
var newItemShow='';
var newItemHideN='';
var newItemShowN='';
var newItemInput='';
var newItemInputId='';
var newItemInputValue='';
var newItemCombo='';
var newItemComboN='';
var trigerclass='';

$(document).ready( function()
{


  $('.trigger').click( function(e){
		$('#suggestions').hide();
		$('#suggestionslong').hide();
      if (newItemShowN!='') {
      $(newItemShowN).hide();
      $(newItemHideN).show();
      }
		trigerclass='';
      newItemHide=this.id+'Hide';
      newItemShow=this.id+'Show';
      newItemHideN='#'+this.id+'Hide';
      newItemShowN='#'+this.id+'Show';
      newItemInput=this.id+'Input';
      newItemInputId='#'+this.id+'Input';
      newItemInputValue=this.id+'Value';
      newItemCombo=this.id+'Combo';
      newItemComboN='#'+this.id+'Combo';
      p = $(this);
      var offset = p.offset();
      var x = offset.left - this.offsetLeft + 20 ;
      var y = offset.top - this.offsetTop + 10;
      document.getElementById(newItemShow).style.left = x ;
      document.getElementById(newItemShow).style.top = y + 10;

      $(newItemShowN).show(); $(newItemHideN).hide();
      

      e.preventDefault();
   });//Finaliza trigger

 	$('.cajaGrande').click( function(e){
		$(newItemShowN).hide(); $(newItemHideN).show();
		$('#suggestions').hide();
		$('#suggestionslong').hide();
	});

});

	function lookupotro(id,inputString) {
		newItemInputValue=id+'Value';
		newItemInput='#'+id;
 		var offset = $(newItemInput).offset();
      var x = offset.left;
      var y = offset.top + 7;
      document.getElementById("suggestionslong").style.left = x + 10;
      document.getElementById("suggestionslong").style.top = y + 5;

      		
		if(inputString.length == 0) {
			/*/ Hide the suggestion box.*/
			$('#suggestionslong').hide();
		} else {
			$.post("busco.php?otro="+id, {queryString: ""+inputString+""}, function(data){
				if(data.length >0) {
					$('#suggestionslong').show();
					$('#autoSuggestionsListlong').html(data);
				}
			});
		}
	}
	 /*/ lookup
	*/
	function otro(thisValue) {
/*//alert(newItemInput + ' -> ' + thisValue);*/
      var item0=thisValue.split("-")[0];
      var item1=thisValue.split("-")[1];
/*alert(newItemInputValue + ' -- ' +item0+ "  --  "+newItemInput + " -- "+item1);*/

		document.getElementById(newItemInputValue).value=item0;
		$(newItemInput).val(item1);

		setTimeout("$('#suggestionslong').hide();", 200);
	}
</script>		

<style type="text/css">
	.suggestionsBox {
      position:absolute;
		overflow:hidden; 
		visibility:visible; z-index:20;
		left:auto;
		margin: 6px 0px 0px 0px;
		width: 150px;
		background-color: #212427;
		-moz-border-radius: 4px;
		-webkit-border-radius: 4px;
		border: 2px solid #000;	
		color: #fff;
                font-size:10pt;
	}
		.suggestionsBoxLong {
      position:absolute;
		overflow:hidden; 
		visibility:visible; z-index:20;
		left:auto;
		margin: 6px 0px 0px 0px;
		width: 350px;
		background-color: #212427;
		-moz-border-radius: 4px;
		-webkit-border-radius: 4px;
		border: 2px solid #000;	
		color: #fff;
                font-size:10pt;
	}
	
	.suggestionList {
		margin: 0px;
		padding: 0px;
	}
	
	.suggestionList li {
		
		margin: 0px 0px 2px 0px;
		padding: 2px;
		cursor: pointer;
	}
	
	.suggestionList li:hover {
		background-color: #659CD8;
	}

	.suggestionListLong {
		margin: 0px;
		padding: 0px;
	}
	
	.suggestionListLong li {
		
		margin: 0px 0px 2px 0px;
		padding: 2px;
		cursor: pointer;
	}
	
	.suggestionListLong li:hover {
		background-color: #659CD8;
	}
        .seleccione { background:#FFF;margin:0 7px 0 7px;border-left:1px solid #EFEFEF;border-right:1px solid #EFEFEF;border-bottom:1px solid #EFEFEF;padding:5px 5px 5px 5px;
 border-right:1px solid #C43303; border-bottom:1px solid #C43303; border-left:1px solid #C43303; border-top:1px solid #C43303; border-radius:5px;
}
</style>			
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">Modificar Proyecto</div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_proyectos.php">
				<table class="fuente8"><tr><td valign="top">
					<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td>Cliente</td><td colspan="3">
							<input type="hidden" id="codclienteValue" name="codcliente" value="<?php echo mysqli_result($rs_query, 0, "codcliente");?>">
							<?php
								$codcliente=mysqli_result($rs_query, 0, "codcliente");
								
								$query_busqu="SELECT * FROM clientes WHERE borrado=0 AND codcliente='".$codcliente."'";
								$rs_busqu=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqu);
								$nombre=mysqli_result($rs_busqu, 0, "nombre").' '. mysqli_result($rs_busqu, 0, "apellido");	
							?>
							<input type="text" size="26" maxlength="60" id="codcliente" value="<?php echo $nombre;?>"/>
							</td>
					    </tr>
					    <tr>
						  <td>Fecha&nbsp;inicio</td>
						  <td><input id="fechaini" type="text" class="cajaPequena" NAME="Afechaini" maxlength="10" value="<?php echo implota(mysqli_result($rs_query, 0, "fechaini"));?>" readonly>
						  <img src="../../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fechaini",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide();  },
						     dateFormat : "%d/%m/%Y"
						   });
						</script>
						</td>
						  <td>Fecha&nbsp;finalización</td>
						  <td><input id="fechafin" type="text" class="cajaPequena" NAME="fechafin" maxlength="10" value="<?php echo implota(mysqli_result($rs_query, 0, "fechafin"));?>">
						  <img src="../../img/calendario.png" name="Image2" id="Image2" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fechafin",
						     trigger    : "Image2",
						     align		 : "Bl",
						     onSelect   : function() { this.hide();  },
						     dateFormat : "%d/%m/%Y"
						   });
						</script>
						</td>						
						</tr>							
				      <tr><td>Estado</td>
				      <td colspan="3">
						<select name="estado" id="estado" class="cajaPequena2">
						<?php
						$estado= mysqli_result($rs_query, 0, "borrado");
						if($estado==0)	{
						?>
							<option value="0" selected>Activo</option>
							<option value="1">Borrado</option>
						<?php } else { ?>
							<option value="0" >Activo</option>
							<option value="1" selected>Borrado</option>
						<?php	
						}
						?>
						</select>
				      </tr>					
						<tr>
							<td>Descripción</td><td colspan="3"><textarea id="descripcion" name="descripcion" autocomplete="off" rows="2" cols="35" ><?php echo trim(mysqli_result($rs_query, 0, "descripcion"));?></textarea></td>
					    </tr>					    
					</table>
				</td></tr></table>
			  </div>
			  <br style="line-height:5px">			  
				<div>
						<button class="boletin" onClick="validar(formulario,true);" onMouseOver="style.cursor=cursor"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Guardar</button>
						<button class="boletin" onClick="limpiar();" onMouseOver="style.cursor=cursor"><i class="fa fa-eraser" aria-hidden="true"></i>&nbsp;Limpiar</button>										
						<button class="boletin" onClick="cancelar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
					<input id="codproyecto" name="codproyectos" type="hidden" value="<?php echo mysqli_result($rs_query, 0, "codproyectos");?>">
					<input id="accion" name="accion" value="modificar" type="hidden">
					<input id="id" name="id" value="" type="hidden">
					<input id="id" name="Zid" value="" type="hidden">
			  </div>
			  </form>
			  
<div class="suggestionsBox" id="suggestions" style="display: none;">
      <div class="suggestionList" id="autoSuggestionsList" style="text-align: left;">
	       &nbsp;
      </div>
</div>

<div class="suggestionsBoxLong" id="suggestionslong" style="display: none;">
      <div class="suggestionListLong" id="autoSuggestionsListlong" style="text-align: left;" >
	       &nbsp;
      </div>
</div>
			  
			  </div>
		  </div>
		</div>
		<div id="ErrorBusqueda" class="fuente8">
 <ul id="lista-errores" style="display:none; 
	clear: both; 
	max-height: 75%; 
	overflow: auto; 
	position:relative; 
	top: 85px; 
	margin-left: 30px; 
	z-index:999; 
	padding-top: 10px; 
	background: #FFFFFF; 
	width: 585px; 
	-moz-box-shadow: 0 0 5px 5px #888;
	-webkit-box-shadow: 0 0 5px 5px#888;
 	box-shadow: 0 0 5px 5px #888; 
 	bottom: 10px;"></ul>	
 
 	</div>
	
	</body>
</html>
