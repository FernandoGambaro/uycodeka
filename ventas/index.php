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

$cadena_busqueda=@$_GET["cadena_busqueda"];

if (!isset($cadena_busqueda)) { $cadena_busqueda=""; } else { $cadena_busqueda=str_replace("",",",$cadena_busqueda); }

if ($cadena_busqueda<>"") {
	$array_cadena_busqueda=split("~",$cadena_busqueda);
	$codcliente=$array_cadena_busqueda[1];
	$nombre=$array_cadena_busqueda[2];
	$numfactura=$array_cadena_busqueda[3];
	$cboEstados=$array_cadena_busqueda[4];
	$fechainicio=$array_cadena_busqueda[5];
	$fechafin=$array_cadena_busqueda[6];
} else {
	$codcliente="";
	$nombre="";
	$numfactura="";
	$cboEstados="";
	$fechainicio =data_first_month_day(date('Y-m-d')); 
	$fechafin = data_last_month_day(date('Y-m-d')); 
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
parent.document.getElementById("msganio").innerHTML=" Reporte de ventas ";

$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

		$(".callbacks").colorbox({
			iframe:true, width:"720px", height:"98%",scrolling: false,
			onCleanup:function(){ window.location.reload();	}
		});



});
</script>
<script type="text/javascript">
function OpenNote(noteId){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"98%", height:"98%",scrolling: false,
			onCleanup:function(){ document.getElementById("form_busqueda").submit(); }
	});

}
function OpenList(noteId){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"99%", height:"99%",scrolling: false,
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

function Comparar_Fecha(Obj1,Obj2)
{
	String1 = Obj1;
	String2 = Obj2;
	// Si los dias y los meses llegan con un valor menor que 10
	// Se concatena un 0 a cada valor dentro del string
	if (String1.substring(1,2)=="/") {
	String1="0"+String1
	}
	if (String1.substring(4,5)=="/"){
	String1=String1.substring(0,3)+"0"+String1.substring(3,9)
	}
	
	if (String2.substring(1,2)=="/") {
	String2="0"+String2
	}
	if (String2.substring(4,5)=="/"){
	String2=String2.substring(0,3)+"0"+String2.substring(3,9)
	}
	
	dia1=String1.substring(0,2);
	mes1=String1.substring(3,5);
	anyo1=String1.substring(6,10);
	dia2=String2.substring(0,2);
	mes2=String2.substring(3,5);
	anyo2=String2.substring(6,10);
	
	
	if (dia1 == "08") // parseInt("08") == 10 base octogonal
	dia1 = "8";
	if (dia1 == '09') // parseInt("09") == 11 base octogonal
	dia1 = "9";
	if (mes1 == "08") // parseInt("08") == 10 base octogonal
	mes1 = "8";
	if (mes1 == "09") // parseInt("09") == 11 base octogonal
	mes1 = "9";
	if (dia2 == "08") // parseInt("08") == 10 base octogonal
	dia2 = "8";
	if (dia2 == '09') // parseInt("09") == 11 base octogonal
	dia2 = "9";
	if (mes2 == "08") // parseInt("08") == 10 base octogonal
	mes2 = "8";
	if (mes2 == "09") // parseInt("09") == 11 base octogonal
	mes2 = "9";
	
	dia1=parseInt(dia1);
	dia2=parseInt(dia2);
	mes1=parseInt(mes1);
	mes2=parseInt(mes2);
	anyo1=parseInt(anyo1);
	anyo2=parseInt(anyo2);
	
	if (anyo1>anyo2)
	{
	return false;
	}
	
	if ((anyo1==anyo2) && (mes1>mes2))
	{
	return false;
	}
	if ((anyo1==anyo2) && (mes1==mes2) && (dia1>dia2))
	{
	return false;
	}

return true;
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
			document.getElementById("form_busqueda").submit();			
		}
		
		
		function buscar() {
			var tipo=document.getElementById('tiporpt').value;
			var codcliente=document.getElementById('codcliente').value;
			if (tipo==2 && codcliente == '') {
				showWarningToast('Tipo de reporte requiere seleccionar cliente');
				return false
			} else {
			document.getElementById("form_busqueda").submit();
			}
		}
		
		function limpiar() {
			document.getElementById("form_busqueda").reset();
			document.getElementById("iniciopagina").value=1;
			document.getElementById("form_busqueda").submit();
		}
		
	function actualizar() {
			var fechainicio=document.getElementById("fechainicio").value;
			var fechafin=document.getElementById("fechafin").value;

		if (Comparar_Fecha(fechainicio, fechafin)){
 			document.getElementById("form_busqueda").submit();
 	    } 		
	}	

		function abreVentana(){
			$.colorbox({
	   	href: "ventana_clientes.php", open:true,
			iframe:true, width:"580", height:"450",scrolling: false,
			});
		}
		
		function validarcliente(){
			var codigo=document.getElementById("codcliente").value;
			$.colorbox({
	   	href: "comprobarcliente_ini.php?codcliente="+codigo, open:true,
			iframe:true, width:"99%", height:"99%",scrolling: false,
			});
		}

		</script>
	</head>
	<body onLoad="inicio()">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="frmBusqueda">
				<form id="form_busqueda" name="form_busqueda" method="post" action="cierremes.php" target="frame_rejilla">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0><tr><td valign="top" width="50%">
					  <table class="fuente8" cellspacing=0 cellpadding=3 border=0>

						<tr>
							<td >Cliente </td>
							<td ><input id="codcliente" type="text" class="cajaPequena" NAME="codcliente" maxlength="10" value="<?php echo $codcliente?>">
							 <img id="botonBusqueda" src="../img/ver.png" width="16" height="16" onClick="abreVentana();" title="Buscar cliente" onMouseOver="style.cursor=cursor" style="vertical-align: middle; margin-top: -1px;">
							  <img id="botonBusqueda" src="../img/cliente.png" width="16" height="16" onClick="validarcliente();" title="Validar cliente" onMouseOver="style.cursor=cursor" style="vertical-align: middle; margin-top: -1px;">
							  </td>
							<td>Nombre</td>
							<td><input id="nombre" name="nombre" type="text" class="cajaGrande" maxlength="45" value="<?php echo $nombre?>"></td>
							
						<td>Moneda</td><td width="26%">
						 <select onchange="cambio();" name="amoneda" id="moneda" class="cajaPequena">
								<option value="1" selected="selected">Pesos</option>
								<option value="2">U$S</option>
  							</select></td>							
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
						  <td>Tipo&nbsp;reporte</td>
						  <td><select name="tiporpt" id="tiporpt" class="comboMedio">
						  <option value="1">Deudores por venta</option>
						  <option value="2">Estado de cuenta</option>
						  </select>
						  &nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
					</table></td></tr></table>
			  </div>

			  <table class="fuente8" width="90%" cellspacing=0 cellpadding=3 border=0>
			  	<tr>
				<td width="30%" align="left"></td>
				<td width="50" align="center">
			 	<div>
			 	<img id="botonBusqueda" src="../img/botonlimpiar.jpg" width="69" height="22" border="1" onClick="limpiar();" onMouseOver="style.cursor=cursor">
				<img id="botonBusqueda" src="../img/botonbuscar.jpg" width="69" height="22" border="1" onClick="buscar();" onMouseOver="style.cursor=cursor">
				</div>				</td>
				<td width="20%" align="right">
				
</td>
			  </table>

				<input type="hidden" id="iniciopagina" name="iniciopagina">
				<input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
			</form>
					<p align="center"><iframe width="90%" height="410" id="frame_rejilla" name="frame_rejilla" frameborder="0">
						<ilayer width="90%" height="410" id="frame_rejilla" name="frame_rejilla"></ilayer>
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
