<?php
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
include ("../conectar.php");
include ("../funciones/fechas.php");
date_default_timezone_set("America/Montevideo"); 

$cadena_busqueda=@$_GET["cadena_busqueda"];

if (!isset($cadena_busqueda)) { $cadena_busqueda=""; } else { $cadena_busqueda=str_replace("",",",$cadena_busqueda); }

if ($cadena_busqueda<>"") {
	$array_cadena_busqueda=split("~",$cadena_busqueda);
	$fechainicio=$array_cadena_busqueda[1];
	$fechafin=$array_cadena_busqueda[2];
} else {
	$fechainicio=date('Y-m-d');
	$fechafin=date('Y-m-d');
}

?>
<html>
	<head>
		<title>Facturas</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="../common/timeout.js"></script>
		
		<link href="../calendario/calendar-blue.css" rel="stylesheet" type="text/css">
		<script src="../calendario/jscal2.js"></script>
		<script src="../calendario/lang/es.js"></script>
		<link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
		<link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
		<link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />		
		<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="js/colorbox.css" />
		<script src="js/jquery.colorbox.js"></script>
		<script src="js/jquery.msgBox.js" type="text/javascript"></script>
		<link href="js/msgBoxLight.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="js/jquery.toastmessage.css" type="text/css">
		<script src="js/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="js/message.js" type="text/javascript"></script>
		
    
<script type="text/javascript">
parent.document.getElementById("msganio").innerHTML=" Ventas artículos ";

$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

		$(".callbacks").colorbox({
			iframe:true, width:"720px", height:"98%",
			onCleanup:function(){ window.location.reload();	}
		});

});
</script>
<script type="text/javascript">
function OpenNote(noteId){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"98%", height:"98%",
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
function pon_prefijo(referencia,odbarras,descripcion) {
	$("#referencia").val(referencia);
	$("#codigobarras").val(odbarras);
	$("#descripcion").val(descripcion);
	$('idOfDomElement').colorbox.close();
	document.getElementById("form_busqueda").submit();

}	
</script>		
		
		<script language="javascript">
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}
		
		function inicio() {
			document.getElementById("firstdisab").style.display = 'block';
			document.getElementById("prevdisab").style.display = 'block';
			document.getElementById("last").style.display = 'block';
			document.getElementById("next").style.display = 'block';
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
		
		function limpiar() {
			document.getElementById("form_busqueda").reset();
			document.getElementById("iniciopagina").value=1;
			document.getElementById("form_busqueda").submit();
		}

		function hacer_cadena_busqueda() {
			var fechainicio=document.getElementById("fechainicio").value;
			var fechafin=document.getElementById("fechafin").value;
			var moneda=document.getElementById("moneda").value;			
			var referencia=document.getElementById("referencia").value;
			var codigobarras=document.getElementById("codigobarras").value;			
			var cadena="";
			cadena="~"+fechainicio+"~"+fechafin+"~"+moneda+"~"+codigobarras+"~"+referencia;
			return cadena;
		}

		function imprimir() {
			var fechainicio=document.getElementById("fechainicio").value;
			var fechafin=document.getElementById("fechafin").value;
			var moneda=document.getElementById("moneda").value;			
			window.open("../fpdf/articulos_vendidos.php?fechainicio="+fechainicio+"&fechafin="+fechafin+"&moneda="+moneda);
		}
		function ventanaArticulos(){
			$.colorbox({
	   	href: "ventana_articulos.php", open:true,
			iframe:true, width:"99%", height:"99%"
			});
			
		}		
		</script>
	</head>
	<body onLoad="inicio();">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="frmBusqueda">
				<form id="form_busqueda" name="form_busqueda" method="post" action="rejilla.php" target="frame_rejilla">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0><tr><td valign="top" width="50%">
					  <table class="fuente8" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td >Referencia</td>
							<td ><input id="referencia" type="text" class="cajaPequena" name="referencia" maxlength="15" value="<?php echo @$referencia;?>" onkeyup="document.getElementById('form_busqueda').submit();">
							 <img id="botonBusqueda" src="../img/ver.png" width="16" height="16" onClick="ventanaArticulos();" onMouseOver="style.cursor=cursor" title="Buscar articulos" style="vertical-align: middle; margin-top: -1px;"></td>
							<td>C&oacute;digo&nbsp;de&nbsp;barras</td>
							<td><input id="codigobarras" name="codigobarras" type="text" class="cajaGrande" maxlength="20" value="<?php echo @$codigobarras;?>" onkeyup="document.getElementById('form_busqueda').submit();"></td>
							<td>Descripci&oacute;n</td>
							<td><input id="descripcion" name="descripcion" type="text" class="cajaGrande" maxlength="60" value="<?php echo @$descripcion?>" onkeyup="document.getElementById('form_busqueda').submit();"></td>

						</tr>		
						
					  <tr>
						  <td>Fecha&nbsp;de&nbsp;inicio</td>
						  <td><input id="fechainicio" type="text" class="cajaPequena" NAME="fechainicio" maxlength="10" value="<?php echo implota($fechainicio);?>" readonly>
						  <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario" style="vertical-align: middle; margin-top: -1px;">					  
						  </td>
						  <td>Fecha&nbsp;de&nbsp;fin</td>
						  <td><input id="fechafin" type="text" class="cajaPequena" NAME="fechafin" maxlength="10" value="<?php echo implota($fechafin);?>" readonly>
						  <img src="../img/calendario.png" name="Image11" id="Image11" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
					  </td>
						<td>Moneda</td><td width="26%">
						 <select onchange="cambio();" name="moneda" id="moneda" class="cajaPequena2">
								<option value="0" selected="selected">Ambos</option>
								<option value="1" >Pesos</option>
								<option value="2">U$S</option>
  							</select></td>						  
						  <td>&nbsp;</td>
					  </tr>
					  <tr><td>
					  
					  </td></tr>
					</table></td></tr></table>
			  </div>
 <div id="lineaResultado">
			  <table class="fuente8" width="95%" cellspacing=0 cellpadding=3 border=0>
			  	<tr>
				<td width="30%" align="left">Nº de encontrados <input id="filas" type="text" class="cajaMinima" NAME="filas" maxlength="5" readonly></td>
				<td width="50" align="center">
			 	<div>
				<img id="botonBusqueda" src="../img/botonbuscar.jpg" width="69" height="22" border="1" onClick="buscar();" onMouseOver="style.cursor=cursor">
			 	<img id="botonBusqueda" src="../img/botonlimpiar.jpg" width="69" height="22" border="1" onClick="limpiar();" onMouseOver="style.cursor=cursor">
				<img id="botonBusqueda" src="../img/botonimprimir.jpg" height="22" border="1" onClick="imprimir();" onMouseOver="style.cursor=cursor">					
				</div>				</td>
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
				Mostrados</td><td> <select name="paginas" id="paginas" onChange="paginar();">
		          </select></td>
		          
</table></td>
			  </table>
				</div>

				<input type="hidden" id="iniciopagina" name="iniciopagina">
				<input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
			</form>
					<p align="center"><iframe width="95%" height="430" id="frame_rejilla" name="frame_rejilla" frameborder="0">
						<ilayer width="95%" height="430" id="frame_rejilla" name="frame_rejilla"></ilayer>
					</iframe></p>
					<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>
			</div>
		  </div>			
		</div>
 <script type="text/javascript">//<![CDATA[
     var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide(), actualizar(); },
          showTime: true
      });
      cal.manageFields("Image1", "fechainicio", "%d/%m/%Y");
      cal.manageFields("Image11", "fechafin", "%d/%m/%Y");
//]]></script>	  	
		
	</body>
</html>
