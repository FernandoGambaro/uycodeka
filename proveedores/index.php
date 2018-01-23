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
require_once('../class/class_session.php');
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
		echo "<script>window.top.location.href='../index.php'; </script>";
	   exit;
} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
              echo "<script>window.parent.changeURL('../../index.php' ); </script>";
	       /*/header("Location:../index.php");	*/
	       exit;
   }
   $s->data['loggedAt']= time();/*/ update last accessed time*/
   $s->save();
}

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];

include ("../conectar.php");
include("../common/verificopermisos.php");
//header('Content-Type: text/html; charset=UTF-8'); 

$cadena_busqueda=@$_GET["cadena_busqueda"];

if (!isset($cadena_busqueda)) { $cadena_busqueda=""; } else { $cadena_busqueda=str_replace("",",",$cadena_busqueda); }

if ($cadena_busqueda<>"") {
	$array_cadena_busqueda=split("~",$cadena_busqueda);
	$codproveedor=$array_cadena_busqueda[1];
	$nif=$array_cadena_busqueda[2];
	$provincia=$array_cadena_busqueda[3];
	$localidad=$array_cadena_busqueda[4];
	$telefono=$array_cadena_busqueda[5];
} else {
	$codproveedor="";
	$nif="";
	$provincia="";
	$localidad="";
	$telefono="";
}

?>
<html>
	<head>
		<title>Proveedores</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
    <script src="../calendario/jscal2.js"></script>
    <script src="../calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />	
		<script type="text/javascript" src="../funciones/validar.js"></script>
    	
<script src="js/jquery.min.js"></script>

<link rel="stylesheet" href="js/jquery.toastmessage.css" type="text/css">
<script src="js/jquery.toastmessage.js" type="text/javascript"></script>
<script src="js/message.js" type="text/javascript"></script>
<link rel="stylesheet" href="js/colorbox.css" />
<script src="js/jquery.colorbox.js"></script>
		<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">

<script type="text/javascript">
parent.document.getElementById("msganio").innerHTML="<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp;  Proveedores ";

$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

var headID = window.parent.document.getElementsByTagName("head")[0];         
var newScript = window.parent.document.createElement('script');
newScript.type = 'text/javascript';
newScript.src = '../js3/jquery.colorbox.js';
headID.appendChild(newScript);
});

		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}
</script>		
<script type="text/javascript">
var tst='';
var alto=parent.document.getElementById("alto").value-160;

var totales=alto/22;
	 totales=totales-1;

$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

});
</script>
<script>
var miVariable = totales;
document.cookie ='variable='+miVariable+'; expires=Thu, 2 Aug 2021 20:47:11 UTC; path=/';
</script>

<?php
$miVariable =  $_COOKIE["variable"];
	$s->data['alto']= floor($miVariable);
   $s->save();
   
	$_SESSION['alto'] = $miVariable;
?>
<script type="text/javascript">
function OpenNote(noteId,w,h){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:w, height:h,
			scrolling: false,
			onCleanup:function(){ document.getElementById("form_busqueda").submit(); }
	});
}

function OpenList(noteId){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"99%", height:"99%",
			onCleanup:function(){ document.getElementById("form_busqueda").submit(); }
	});

}

function pon_prefijo(pref,nombre,nif) {
	$("#codcliente").val(pref);
	$("#nombre").val(nombre);
	$("#nif").val(nif);
	$('idOfDomElement').colorbox.close();
	document.getElementById("form_busqueda").submit();

}
function setIframeHeight(id) {
    var ifrm = document.getElementById(id);
    var doc = ifrm.contentDocument? ifrm.contentDocument: 
        ifrm.contentWindow.document;
    ifrm.style.visibility = 'hidden';
    ifrm.style.height = "10px"; // reset to minimal height ...
    // IE opt. for bing/msn needs a bit added or scrollbar appears
    ifrm.style.height = alto + "px";
    ifrm.style.visibility = 'visible';
}

</script>		
		<script language="javascript">
		
		function inicio() {
			document.getElementById("firstdisab").style.display = 'block';
			document.getElementById("prevdisab").style.display = 'block';
			document.getElementById("last").style.display = 'block';
			document.getElementById("next").style.display = 'block';
			document.getElementById("form_busqueda").submit();			
		}
		
		function imprimir() {
			var url ='';
			var codproveedor=document.getElementById("codproveedor").value;
			var nif=document.getElementById("nif").value;			
			var provincia=document.getElementById("cboProvincias").value;
			var localidad=document.getElementById("localidad").value;
			var telefono=document.getElementById("telefono").value;			
			
			var opcionesimpresion=document.getElementById("opcionesimpresion").value;
			if ($('#opcionesaexcel').is(":checked")){
				
				if (opcionesimpresion==1) {
					url = "../excel/ListaDeProveedoresExcel.php?codproveedor="+codproveedor+"&nif="+nif+"&provincia="+provincia+"&localidad="+localidad+"&telefono="+telefono;
				} 
					$.msgBox({ type: "prompt",
					 title: "Ingrese el nombre del archivo sin extención",
					 inputs: [
					 { header: "Nombre de Archivo", type: "text", name: "nombre" }],
					 buttons: [
					 { value: "Aceptar" }, { value:"Cancelar" }],
					 success: function (result, values) {
											$(values).each(function (index, input) {
											v =  input.value ;
					  						});	
							if (v!="") {
								$.get("../excel/preparo.php?file="+v,function (data,status) { });
						      window.parent.progressExcelBar(1,v);
									$.get(url+"&file="+v, function(data, status) {
									if(status == 'success'){	
									$('#downloadFrame').remove(); // This shouldn't fail if frame doesn't exist
			   					$('body').append('<iframe id="downloadFrame" style="display:none"></iframe>');
			   					$('#downloadFrame').attr('src','../tmp/'+v+'.xlsx');	
										return false;
							  		}else{					
									showWarningToast('Se produjo un error, intentelo mas tarde');
							  		}
								});    											
							} else {
								if (result!="Cancelar") {
									showWarningToast('Nombre de archivo incorrecto.');
								}
							}					  														    	
						}
					});	
			} else {
				if (opcionesimpresion==1) {
					window.open("../fpdf/proveedores.php?codproveedor="+codproveedor+"&nif="+nif+"&provincia="+provincia+"&localidad="+localidad+"&telefono="+telefono);
				}
			}			
		}
		
		function buscar() {
			var cadena;
			cadena=hacer_cadena_busqueda();
			document.getElementById("cadena_busqueda").value=cadena;
			if (document.getElementById("iniciopagina").value=="") {
				document.getElementById("iniciopagina").value=1;
			} else {
				document.getElementById("iniciopagina").value=document.getElementById("paginas").value;
			}
			document.getElementById("form_busqueda").submit();
		}
		
		function paginar() {
			document.getElementById("iniciopagina").value=document.getElementById("paginas").value;
			document.getElementById("form_busqueda").submit();
		}

		function firstpage() {
			document.getElementById("iniciopagina").value=document.getElementById("firstpagina").value;
			document.getElementById("form_busqueda").submit();
		}
		function prevpage() {
			document.getElementById("iniciopagina").value=document.getElementById("prevpagina").value;
			document.getElementById("form_busqueda").submit();
		}
		function nextpage() {
			document.getElementById("iniciopagina").value=document.getElementById("nextpagina").value;
			document.getElementById("form_busqueda").submit();
		}
		function lastpage() {
			document.getElementById("iniciopagina").value=document.getElementById("lastpagina").value;
			document.getElementById("form_busqueda").submit();
		}
		
		function hacer_cadena_busqueda() {
			var codproveedor=document.getElementById("codproveedor").value;
			var nif=document.getElementById("nif").value;			
			var provincia=document.getElementById("cboProvincias").value;
			var localidad=document.getElementById("localidad").value;
			var telefono=document.getElementById("telefono").value;
			var cadena="";
			cadena="~"+codproveedor+"~"+nif+"~"+provincia+"~"+localidad+"~"+telefono+"~";
			return cadena;
			}

		function limpiar() {
			document.getElementById("form_busqueda").reset();
			document.getElementById("iniciopagina").value=1;
			document.getElementById("form_busqueda").submit();
		}		
		function validarproveedor(){
			var codigo=document.getElementById("codproveedor").value;
			$.colorbox({
	   	href: "comprobarproveedor.php?codproveedor="+codigo, open:true,
			iframe:true, width:"100", height:"100"
			});
		}
		</script>
		
<link href="../js3/jquery-ui.css" rel="stylesheet">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <style type="text/css">
  
		.fixed-height {
			padding: 1px;
			max-height: 200px;
			overflow: auto;
		}  
/****** jQuery Autocomplete CSS *************/

.ui-corner-all {
  -moz-border-radius: 0;
  -webkit-border-radius: 0;
  border-radius: 0;
}

.ui-menu {
  border: 1px solid lightgray;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;
}

.ui-menu .ui-menu-item a {
  color: #000;
}

.ui-menu .ui-menu-item:hover {
  display: block;
  text-decoration: none;
  color: #3D3D3D;
  cursor: pointer;
 /* background-color: lightgray;
  background-image: none;*/
  border: 1px solid lightgray;
}

.ui-widget-content .ui-state-hover,
.ui-widget-content .ui-state-focus {
  border: 1px solid lightgray;
  /*background-image: none;
  background-color: lightgray;*/
  font-weight: bold;
  color: #3D3D3D;
}
  
  </style>		
	
<script>
$.ui.autocomplete.prototype._renderItem = function(ul, item) {
  var re = new RegExp($.trim(this.term.toLowerCase()));
  var t = item.label.replace(re, "<span style='color:#5C5C5C;'>" + $.trim(this.term.toLowerCase()) +
    "</span>");
  return $("<li></li>")
    .data("item.autocomplete", item)
    .append("<a>" + t + "</a>")
    .appendTo(ul);
};
$(document).ready(function () {
 	
    $("#nombre").autocomplete({
        source: 'busco.php',
        minLength:2,
        autoFocus:true,
        select: function(event, ui) {

		var name = ui.item.value;
		var thisValue = ui.item.data;
		var pref=thisValue.split("~")[0];
		var nombre=thisValue.split("~")[1];
		var nif=thisValue.split("~")[2];

		$("#aProveedor").val(pref);
		$("#nombre").val(nombre);
		$("#nif").val(nif);
	
		$('[data-index="2"]').focus();
		}
	}).autocomplete("widget").addClass("fixed-height");
});
</script>
	</head>
	<body onLoad="inicio();" bgcolor="white">
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
				<div id="frmBusqueda">
				<form id="form_busqueda" name="form_busqueda" method="post" action="rejilla.php" target="frame_rejilla">
				<table width="100%" border="0"><tr><td valign="top">
					<table class="fuente8" cellspacing="2" cellpadding="2" border="0" width="100%">					
						<tr><td>
						<?php 
						$escribir=verificopermisos('proveedores', 'escribir', $UserID);
						$reportes=verificopermisos('reportes', 'leer', $UserID);
						if ($escribir=="true") { ?>						
						<button class="boletin" onClick="OpenNote('nuevo_proveedor.php',880,320);" onMouseOver="style.cursor=cursor">
						<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Nuevo&nbsp;proveedor</button>
						<?php } ?>						
							</td>
							<td>Proveedor </td>
					      <td><input name="codproveedor" type="hidden" class="cajaPequena" id="aProveedor" size="6" maxlength="5"></input>
							<input name="nombre" type="text" class="cajaGrande" id="nombre"  size="45" maxlength="45" data-index="1"></td>					        
						</tr>
						<tr>
						<td></td>
						  <td>RUT</td>
						  <td><input id="nif" type="text" class="cajaPequena" name="nif" maxlength="15" value="<?php echo $nif?>" onkeyup="document.getElementById('form_busqueda').submit();"></td>
					  </tr>
					  </table></td>
					  <td valign="top">
					  <table class="fuente8" cellspacing=0 cellpadding=3 border=0>
						<?php
					  	$query_provincias="SELECT * FROM provincias ORDER BY nombreprovincia ASC";
						$res_provincias=mysqli_query($GLOBALS["___mysqli_ston"], $query_provincias);
						$contador=0;
					  ?>
						<tr>
							<td>Departamento</td>
							<td><select id="cboProvincias" name="cboProvincias" class="comboMedio" onchange="document.getElementById('form_busqueda').submit();">
								<option value="0" selected>Todas las provincias</option>
								<?php
								while ($contador < mysqli_num_rows($res_provincias)) { 
									if ( mysqli_result($res_provincias, $contador, "codprovincia") == $provincia) { ?>
								<option value="<?php echo mysqli_result($res_provincias, $contador, "codprovincia")?>" selected><?php echo mysqli_result($res_provincias, $contador, "nombreprovincia")?></option>
								<?php } else { ?> 
								<option value="<?php echo mysqli_result($res_provincias, $contador, "codprovincia")?>"><?php echo mysqli_result($res_provincias, $contador, "nombreprovincia")?></option>
								<?php }
								$contador++;
								} ?>				
								</select>	</td>
							</tr>
					  <tr>
						  <td>Localidad</td>
						  <td><input id="localidad" type="text" class="cajaGrande" name="localidad" maxlength="30" value="<?php echo $localidad?>" onkeyup="document.getElementById('form_busqueda').submit();"></td>
					  </tr>
						<tr>
						  <td>Tel&eacute;fono</td>
						  <td><input id="telefono" type="text" class="cajaPequena" name="telefono" maxlength="15" value="<?php echo $telefono?>" onkeyup="document.getElementById('form_busqueda').submit();"></td>
					  </tr>
					</table>
			  </td>
				<td rowspan="3">
					<table class="fuente8" cellspacing=0 cellpadding=1 border=0>					    
						<tr>
						<td>
						<fieldset><legend>&nbsp;Opciones de impresión&nbsp;</legend>
						<select name="opcionesimpresion" id="opcionesimpresion" class="comboGrande" >
						<option value="1">Listado de proveedores</option>

						</select>
						<br>&nbsp;<br>
						<label>Exportar a Excel?
  							<input id="opcionesaexcel" name="opcionesaexcel" type="checkbox" checked="true" value="0">
  							<span></span>
						</label>						
						</fieldset>
						</td>
						</tr>
							</table>
			</td>
			<td>
			<table>
			<tr><td><button class="boletin" onClick="buscar();" onMouseOver="style.cursor=cursor"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar</button></td>
			  							</tr><tr>
						<td><button class="boletin" onClick="limpiar();" onMouseOver="style.cursor=cursor"><i class="fa fa-eraser" aria-hidden="true"></i>&nbsp;Limpiar</button></td>
						</tr><tr>
						<td>
						<?php 
						 if ($reportes=="true") {?>
						 <button class="boletin" onClick="imprimir();" onMouseOver="style.cursor=cursor">
						<i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir</button>
						<?php } ?>							
						</td>						
			  </tr>			
			</table>
			</td>	

			  </table>					
			  </div>
			  <div id="lineaResultado">
			  <table class="fuente8" width="95%" cellspacing=0 cellpadding=3 border=0>
			  	<tr>
				<td width="30%" align="left">Cantidad <input id="filas" type="text" class="cajaMinima" name="filas" maxlength="5" readonly></td>
				<td width="50%" align="center">

</td>
				<td width="20%" align="right">
				<table class="fuente8" cellspacing=1 cellpadding=1 border=0>
<td>				
		<input type="hidden" id="firstpagina" name="firstpagina" value="1">
		<img style="display: none;" src="../img/paginar/first.gif" id="first" border="0" height="13" width="13" onClick="firstpage();" onMouseOver="style.cursor=cursor">

		<img style="display: none;" src="../img/paginar/firstdisab.gif" id="firstdisab" border="0" height="13" width="13"></td>
<td>
		<input type="hidden" id="prevpagina" name="prevpagina" value="">
		<img style="display: none;" src="../img/paginar/prev.gif" id="prev" border="0" height="13" width="13" onClick="prevpage();" onMouseOver="style.cursor=cursor">

		<img style="display: none;" src="../img/paginar/prevdisab.gif" id="prevdisab" border="0" height="13" width="13">
</td><td>
<input id="currentpage" type="text" class="cajaMinima" >
</td><td>
		<input type="hidden" id="nextpagina" name="nextpagina" value="">
		<img style="display: none;" src="../img/paginar/next.gif" id="next" border="0" height="13" width="13" onClick="nextpage();" onMouseOver="style.cursor=cursor">

		<img style="display: none;" src="../img/paginar/nextdisab.gif" id="nextdisab" border="0" height="13" width="13"></td>
<td>
		<input type="hidden" id="lastpagina" name="lastpagina" value="">
		<img style="display: none;" src="../img/paginar/last.gif" id="last" border="0" height="13" width="13" onClick="lastpage();" onMouseOver="style.cursor=cursor">

		<img style="display: none;" src="../img/paginar/lastdisab.gif" id="lastdisab" border="0" height="13" width="13"></td>
<td>			
				Mostrados</td><td> <select name="paginas" id="paginas" onChange="paginar();" class="comboPequeno">
		          </select></td>
		          
</table></td>
			  </table>
				</div>

				<div id="frmResultado">

				</div>
				<input type="hidden" id="iniciopagina" name="iniciopagina">
				<input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
				<input type="hidden" id="selid" name="selid">
				<input type="hidden" id="stylesel" name="stylesel">
				
			</form>
						<iframe width="98%" height="70%" id="frame_rejilla" name="frame_rejilla" frameborder="0" onload="setIframeHeight(this.id)" style=" overflow-y: scroll">
						<ilayer width="98%" height="70%" id="frame_rejilla" name="frame_rejilla"></ilayer>						
					</iframe>
					<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>
			</div>
		  </div>			
		</div>
	</body>
</html>
