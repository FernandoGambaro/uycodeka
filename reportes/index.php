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
	echo "<script>location.href='../index.php'; </script>";
   //header("Location:../index.php");	

} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
  		//header("Location:../index.php");	
		echo "<script>window.top.location.href='../index.php'; </script>";
	   exit;
   }
   $s->data['loggedAt']= time();
   $s->save();
}

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];

$paginacion=$s->data['alto'];
if($paginacion<=0) {
	$paginacion=20;
}
 

include ("../conectar.php");
include ("../funciones/fechas.php");
include("../common/verificopermisos.php");
//header('Content-Type: text/html; charset=UTF-8'); 

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

		$reportes=verificopermisos('reportes', 'leer', $UserID);		

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
			
		<script src="../js3/jquery.min.js"></script>

		<link rel="stylesheet" href="../js3/colorbox.css" />
<script src="../js3/jquery.min.js"></script>
<script src="../js3/jquery.colorbox.js"></script>
		
		<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
		<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js3/message.js" type="text/javascript"></script>
		<script src="../js3/jquery.msgBox.js" type="text/javascript"></script>
		<link href="../js3/msgBoxLight.css" rel="stylesheet" type="text/css">	

	<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">
<script type="text/javascript">
parent.document.getElementById("msganio").innerHTML="<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp;  Reportes&nbsp;";

var tst='';
var alto=parent.document.getElementById("alto").value-160;

var totales=alto/22;
	 totales=totales-1;

$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

});
</script>
<script type="text/javascript">
function OpenNote(noteId,w="98%",h="98%"){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:w, height:h,
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
	$("#aCliente").val(pref);
	$("#nombre").val(nombre);
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
			$('#enviomail').hide();
			document.getElementById("form_busqueda").submit();			
		}
		
		function imprimir() {
			//defaultPrevented;

			event.preventDefault();
			var url ='';
			var fechainicio=document.getElementById("fechainicio").value;
			var fechafin=document.getElementById("fechafin").value;
			var codusuarios=document.getElementById("codusuarios").value;
			var localidad=document.getElementById("localidad").value;
			var codcliente=document.getElementById("aCliente").value;
			var cboProvincias=document.getElementById("cboProvincias").value;
			var moneda=document.getElementById("moneda").value;
			
			var tiporeporte=document.getElementById("tiporeporte").value;
			
			if ($('#opcionesaexcel').is(":checked")){
				if (tiporeporte==1) {
					url = "../excel/CierreAnualizadoExcel.php?codcliente="+codcliente+"&provincia="+cboProvincias+"&localidad="+localidad+"&codusuarios="+codusuarios+"&fechainicio="+fechainicio+"&fechafin="+fechafin;
				} 
				if (tiporeporte==2) {
					url = "../excel/LiquidacionDeComisionesExcel.php?codcliente="+codcliente+"&provincia="+cboProvincias+"&localidad="+localidad+"&codusuarios="+codusuarios+"&fechainicio="+fechainicio+"&fechafin="+fechafin;
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
					//Finaliza exportar a excel
			} else {
				if (tiporeporte==1) {
					window.open("../fpdf/cierremes.php?codcliente="+codcliente+"&provincia="+cboProvincias+"&localidad="+localidad+"&codusuarios="+codusuarios+"&fechainicio="+fechainicio+"&fechafin="+fechafin);
				}
				if (tiporeporte==2) {
					window.open("../fpdf/EstadoDeCuenta.php?moneda="+moneda+"&codcliente="+codcliente+"&provincia="+cboProvincias+"&localidad="+localidad+"&codusuarios="+codusuarios+"&fechainicio="+fechainicio+"&fechafin="+fechafin);
				}
			
			}
		}	
				
		function buscar() {
			var tiporeporte=document.getElementById("tiporeporte").value;
			var codcliente=document.getElementById('aCliente').value;
			if ( tiporeporte==2 && codcliente == '') {
				showWarningToast('Tipo de reporte requiere seleccionar cliente');
				return false;
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

	function submitformrepo() {
		//event.preventDefault();
		$("#Image11").prop('disabled', true);
		
		$('#grafico').hide();
		$('#graficodetalles').hide();		
		$('#enviomail').hide();
			var codcliente=$('#aCliente').val();

		var tiporeporte=form_busqueda.tiporeporte.options[form_busqueda.tiporeporte.selectedIndex].value;
		if (tiporeporte==1) {
		$('#grafico').show();
		$('#graficodetalles').show();	
		$('#form_busqueda').attr('action', 'cierremes.php');
			document.getElementById("form_busqueda").submit();
			return false;
		}
		if (tiporeporte==2 && codcliente == '') {//Estado de cuenta de un cliente
				showWarningToast('Tipo de reporte requiere seleccionar cliente');
				$("#tiporeporte").val('1').attr("selected", "selected");
				$("#tiporeporte").selectmenu('refresh');
				return false;
				} 
				 if (tiporeporte==2) {
				$('#enviomail').show();
				$('#form_busqueda').attr('action', 'EstadoDeCuenta.php');
				document.getElementById("form_busqueda").submit();
		}
		if (tiporeporte==5) {
			var tipomoneda=form_busqueda.moneda.options[form_busqueda.moneda.selectedIndex].value;
				if (tipomoneda==0) {
					showWarningToast('Tipo de reporte requiere seleccionar moneda');
					$("#tiporeporte").val('1').attr("selected", "selected");
					$("#tiporeporte").selectmenu('refresh');
					return false;
				}
				$("#fechafin").val($("#fechainicio").val());
				$("#Image11").prop('disabled', true);
				$('#form_busqueda').attr('action', 'EstadoDeCuentaTodosLosClientes.php');
				document.getElementById("form_busqueda").submit();
		}					
		if (tiporeporte==3) {
				$('#form_busqueda').attr('action', 'comisiones.php');
				document.getElementById("form_busqueda").submit();
		}
		if (tiporeporte==4) {
				$('#form_busqueda').attr('action', 'calculodeiva.php');
				document.getElementById("form_busqueda").submit();		
		}
		return true;	
	} 

	function jpgrafico(tipo) {
		event.preventDefault();
			var fechainicio=document.getElementById("fechainicio").value;
			var fechafin=document.getElementById("fechafin").value;
			if (tipo=='d') {			
			document.getElementById('frame_rejilla').src = 'graficotorta.php?fechainicio='+fechainicio+'&fechafin='+fechafin;				
			} else {
			document.getElementById('frame_rejilla').src = 'graficobarras.php?fechainicio='+fechainicio+'&fechafin='+fechafin;	
			}
	}
	
function progressExcelBar(xx,file) {
	window.parent.progressExcelBar(xx,file);
}		
	
		</script>
		
<link href="../js3/jquery-ui.css" rel="stylesheet">
<script src="../js3/jquery-ui.js"></script>
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
		var agencia=thisValue.split("~")[3];

		$("#aCliente").val(pref);
		$("#nombre").val(nombre);
		$("#nif").val(nif);

		$("#agencia").val(agencia);
	
		$('[data-index="2"]').focus();
		}
	}).autocomplete("widget").addClass("fixed-height");
});
</script>		
		
	</head>
	<body onLoad="inicio()">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="frmBusqueda">
				<form id="form_busqueda" name="form_busqueda" method="post" action="cierremes.php" target="frame_rejilla">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0><tr>
					<td valign="top" width="98%">
					  <table class="fuente8" cellspacing=0 cellpadding=2 border=0 width="100%"><tr>
							 <td valign="top">Cliente</td>
						    <td colspan="2"><input name="nombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" data-index="1">
						    <input name="codcliente" type="hidden" id="aCliente">
					      </td>						  
						<td valign="top">
							Fecha&nbsp;de&nbsp;inicio</td>
						  <td valign="top"><input id="fechainicio" type="text" class="cajaPequena" name="fechainicio" maxlength="10" value="<?php echo implota($fechainicio);?>" readonly>
						  <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario" style="vertical-align: middle; margin-top: -1px;">					  
						  </td>
						  <td valign="top">Fecha&nbsp;de&nbsp;fin</td>
						  <td valign="top"><input id="fechafin" type="text" class="cajaPequena" name="fechafin" maxlength="10" value="<?php echo implota($fechafin);?>" readonly>
						  <img src="../img/calendario.png" name="Image11" id="Image11" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
					  </td>
						<td rowspan="3" valign="top" colspan="2">
						<fieldset><legend>&nbsp;Tipo de reporte&nbsp;</legend>
						<select name="tiporeporte" id="tiporeporte" class="comboGrande"  onchange="submitformrepo();">
							<option value="1">Detalles compra/venta</option>
							<option value="2">Estado de cuenta cliente</option>
							<option value="5">Estado de cuenta todos los cliente</option>
							<option value="3">Liquidación de comisiones</option>
							<option value="4">Cierre anualizado</option>							
						</select>					
						<br>&nbsp;<br>
						<label>En lugar de imprimir, exportar a Excel?
  							<input id="opcionesaexcel" name="opcionesaexcel" type="checkbox" checked="true" value="0">
  							<span></span>
						</label>						
						</fieldset>
						</td>
					  </tr>
					  <tr>										  
					  <td valign="top">Moneda</td><td valign="top">
						 <select onchange="cambio();" name="amoneda" id="moneda" class="cajaMedia">
						 <option value="0" selected="selected">Todas las monedas</option>
						 <?php
						$sel_resultado="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;
							$xmon=1;
						 while ($contador < mysqli_num_rows($res_resultado)) { 
								?>
						 				<option value="<?php echo $xmon;?>"><?php echo mysqli_result($res_resultado, $contador, "descripcion");?></option>
 								<?php
									$xmon++;						 
						 $contador++;
						 }
						?>
						</select></td>	
						<td valign="top">Vendedor</td>
					  <?php
					  	$query_usuario="SELECT * FROM usuarios WHERE tratamiento=3 AND estado='0'";
						$res_usuario=mysqli_query($GLOBALS["___mysqli_ston"], $query_usuario);
						$contador=0;
					  ?>						
							<td colspan="2" valign="top"><select id="codusuarios" name="codusuarios" class="comboGrande">
							<option value="0" selected="selected">Seleccione un ejecutivo de cuenta</option>
								<?php
								while ($contador < mysqli_num_rows($res_usuario)) { 
 								?>
									<option value="<?php echo mysqli_result($res_usuario, $contador, "codusuarios");?>"><?php echo mysqli_result($res_usuario, $contador, "nombre");?> <?php echo mysqli_result($res_usuario, $contador, "apellido");?></option>
								<?php $contador++;
								} ?>				
								</select>
							</td>											  
							<td valign="top" rowspan="2" colspan="2">

						</td>		
						</tr>						  
						<tr>
					<?php
					  	$query_provincias="SELECT * FROM provincias ORDER BY nombreprovincia ASC";
						$res_provincias=mysqli_query($GLOBALS["___mysqli_ston"], $query_provincias);
						$contador=0;
					  ?>					  
						<td valign="top">Departamentos</td>
							<td valign="top"><select id="cboProvincias" name="cboProvincias" class="comboMedio" onchange="document.getElementById('form_busqueda').submit();">
								<option value="0" selected>Todos los departamentos</option>
								<?php
								while ($contador < mysqli_num_rows($res_provincias)) { 
 								?> 
								<option value="<?php echo mysqli_result($res_provincias, $contador, "codprovincia");?>"><?php echo mysqli_result($res_provincias, $contador, "nombreprovincia");?></option>
								<?php 
								$contador++;
								} ?>				
						</select>
						</td>
						<td valign="top">Localidad</td>
						<td valign="top" colspan="2"><input id="localidad" type="text" class="cajaGrande" name="localidad" maxlength="30" onkeyup="document.getElementById('form_busqueda').submit();"></td>
						
					</tr></table>

			  </div>
			  <table class="fuente8" width="90%" cellspacing=0 cellpadding=2 border=0>
			  	<tr>

				<td width="100%" align="center">
			 	<div>
			 	<button class="boletin" onClick="limpiar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Limpiar</button>			 	
				<button class="boletin" onClick="buscar();" onMouseOver="style.cursor=cursor"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar</button>
				<button class="boletin" onClick="javascript:jpgrafico('d');" onMouseOver="style.cursor=cursor" id="graficodetalles"><i class="fa fa-pie-chart" aria-hidden="true"></i>&nbsp;Gráfico con detalles</button>				
				<button class="boletin" onClick="javascript:jpgrafico('s');" onMouseOver="style.cursor=cursor" id="grafico"><i class="fa fa-pie-chart" aria-hidden="true"></i>&nbsp;Gráfico simple</button>				
				<button class="boletin" onClick="javascript:envio();" onMouseOver="style.cursor=cursor" id="enviomail"><i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp;Envio mail cliente</button>				
						<?php 
						 if ($reportes=="true") {?>
						 <button class="boletin" onClick="imprimir();" onMouseOver="style.cursor=cursor">
						<i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir</button>
						<?php } ?>	
				</div>
				</td>
			  </table>

				<input type="hidden" id="iniciopagina" name="iniciopagina">
				<input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
			</form>
					<p align="center"><iframe width="90%" height="410" id="frame_rejilla" name="frame_rejilla" frameborder="0">
						
					</iframe></p>
					<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					
					</iframe>
			</div>
		  </div>			
		</div>
 <script type="text/javascript">
     var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide(), actualizar(); },
          showTime: true
      });
      cal.manageFields("Image1", "fechainicio", "%d/%m/%Y");
      cal.manageFields("Image11", "fechafin", "%d/%m/%Y");
</script>	  	
		
	</body>
</html>