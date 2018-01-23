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
	echo "<script>top.location.href='../index.php'; </script>";
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


include ("../conectar.php");
include("../common/verificopermisos.php");
include("../common/funcionesvarias.php");
include ("../funciones/fechas.php"); 
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
//header('Content-Type: text/html; charset=UTF-8');

$modif='';

$codartiviajatmp=$codartiviaja=$_GET["codartiviaja"];
$sel_alb="SELECT * FROM artiviaja WHERE codartiviaja='$codartiviaja'";
$rs_alb=mysqli_query($GLOBALS["___mysqli_ston"], $sel_alb);
$codcliente=mysqli_result($rs_alb, 0, "codcliente");
$estado=mysqli_result($rs_alb, 0, "estado");
$fechaenvio=implota(mysqli_result($rs_alb, 0, "fechaenvio"));
$fecha=implota(mysqli_result($rs_alb, 0, "fecha"));
$observacion=mysqli_result($rs_alb, 0, "observacion");
$destino=mysqli_result($rs_alb, 0, "destino");


$transportista=mysqli_result($rs_alb, 0, "transportista");
$vehiculo=mysqli_result($rs_alb, 0, "vehiculo");
$chofer=mysqli_result($rs_alb, 0, "chofer");
$fecharentrega=implota(mysqli_result($rs_alb, 0, "fecharentrega"));
$hora=mysqli_result($rs_alb, 0, "hora");

$sel_cliente="SELECT nombre,nif FROM clientes WHERE codcliente='$codcliente'";
$rs_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cliente);
$nombre=mysqli_result($rs_cliente, 0, "nombre");
$nif=mysqli_result($rs_cliente, 0, "nif");

$fechahoy=date("Y-m-d");

$sel_lineas="SELECT * FROM artiviajalinea WHERE codartiviaja='$codartiviaja' ORDER BY numlinea ASC";
$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);

$sel_borrar = "DELETE FROM artiviajalineatmp WHERE codartiviaja='$codartiviaja'";
$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);


$contador=0;
//echo mysql_num_rows($rs_lineas);
while ($contador < mysqli_num_rows($rs_lineas)) {
	$codfamilia=mysqli_result($rs_lineas, $contador, "codfamilia");
	$codarticulo=mysqli_result($rs_lineas, $contador, "codigo");
	$cantidad=mysqli_result($rs_lineas, $contador, "cantidad");
	$detalles=mysqli_result($rs_lineas, $contador, "detalles");

	$sel_tmp="INSERT INTO artiviajalineatmp (codartiviaja,numlinea,codfamilia,codigo,detalles,cantidad) 
	VALUES ('$codartiviajatmp','','$codfamilia','$codarticulo','$detalles','$cantidad')";

	$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tmp);
	$contador++;
}

				$sel_resultado="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
			   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
$moneda1=mysqli_result($res_resultado,0, "simbolo");
$moneda2=mysqli_result($res_resultado, 1, "simbolo");
?>
<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
    <script src="../calendario/jscal2.js"></script>
    <script src="../calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />		
			<script src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="../js3/jquery.colorbox.js"></script>
<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
<script src="../js3/message.js" type="text/javascript"></script>

		<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">
	

<script type="text/javascript">

$(document).keydown(function(e) {
    switch(e.keyCode) { 
        case 13:
			e.preventDefault();
        var $this = $(e.target);
        var index = parseFloat($this.attr('data-index'));
	        if (index==1) {
	        	var codigo=document.getElementById("aCliente").value;
	        		if (codigo=='') {
						abreVentana();
	        			$('[data-index="' + (index + 1).toString() + '"]').focus();
		        		}
		     }
			  if (index==10) {
		     		var codigo=document.getElementById("codbarras").value;
		      	if (codigo=='') {
		        		ventanaArticulos();
		        		$('[data-index="' + (index + 1).toString() + '"]').focus();	
		     		}
		     }
			  if (index==11) {
		        		validar();
		     }		     
		     		$('[data-index="' + (index + 1).toString() + '"]').focus();

        break;
        case 112:
            showWarningToast('Ayuda aún no disponible...');
        break;
       
	 }
});

</script>	
<script type="text/javascript">
$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();
});
</script>

<script type="text/javascript">
function OpenNote(noteId){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"90%", height:"80%",
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

function pon_prefijo_b(pref,nombre,nif) {
	$("#aCliente").val(pref);
	$("#aNombre").val(nombre);
	$("#nif").val(nif);
	$('idOfDomElement').colorbox.close();
}

function pon_prefijo_Fb (codfamilia,pref,nombre,precio,codarticulo,moneda,codservice,detalles) {
	var monArray = new Array();
	$("#codfamilia").val(codfamilia);
	$("#codbarras").val(pref);

	$("#detalles").val(detalles);
	
	$("#descripcion").val(nombre);
	$("#codarticulo").val(codarticulo);
	$('idOfDomElement').colorbox.close();
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

		function abreVentana(){
			$.colorbox({
	   	href: "ventana_clientes_ini.php", open:true,
			iframe:true, width:"99%", height:"99%",
			onCleanup:function() {
				$('#aEstado').focus();
				}
			});			
		}		
		
		function inicio() {
			document.getElementById("modif").value=1;
			document.formulario_lineas.submit();
			document.getElementById("modif").value=0;
			//var fecha=$("#fecha").val();
			//	$.post("busco_tipocambio.php?fecha="+fecha,  function(data){
			//	$("#tipocambio").val(data);
			//})(jQuery);	
			
		}
		
		function ventanaArticulos(){
			var codigo=document.getElementById("aCliente").value;
			if (codigo=="") {
				showWarningToast("Debe introducir el codigo del cliente");
			} else {
				$.colorbox({href:"ver_articulos.php",
				iframe:true, width:"95%", height:"95%",
					onCleanup:function() {
						$('#cantidad').focus();
						}				
				});
			}
		}
		function validarArticulo() {
			var codbarras=document.getElementById("codbarras").value;
				if (codbarras!="") {
				$.colorbox({href:"comprobararticulos.php?codbarras="+codbarras,
				iframe:true, width:"95%", height:"95%",
				});
				}			
		}
				
		function validarcliente(){
			var codigo=document.getElementById("aCliente").value;
				$.colorbox({href:"comprobarcliente.php?codcliente="+codigo,
				iframe:true, width:"95%", height:"95%",
				
				});
		}
		
		function cancelar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
		function limpiarcaja() {
			document.getElementById("aNombre").value="";
			document.getElementById("nif").value="";
		}
		
		function validar_cabecera()	{
				var mensaje="";
				if (document.getElementById("aNombre").value=="") mensaje+="  - Nombre<br>";
				if (document.getElementById("fechaenvio").value=="") mensaje+="  - Fecha salida<br>";
				if (mensaje!="") {
					showWarningToast("Errores detectados:<br><br>"+mensaje);
				} else {
					document.getElementById("observacion").value=document.getElementById("observacionaux").value;
					document.getElementById("formulario").submit();
				}
			}			
		
		function validar() {
				var mensaje="";
				var entero=0;
				var enteroo=0;
		
				if (document.getElementById("codbarras").value=="") mensaje="  - Codigo de barras<br>";
				if (document.getElementById("descripcion").value=="") mensaje+="  - Descripcion<br>";
				if (document.getElementById("cantidad").value=="") 
						{ 
						mensaje+="  - Falta la cantidad<br>";
						} else {
							enteroo=parseInt(document.getElementById("cantidad").value);
							if (isNaN(enteroo)==true) {
								mensaje+="  - La cantidad debe ser numerica<br>";
							} else {
									document.getElementById("cantidad").value=enteroo;
							}
						}
				
				if (mensaje!="") {
					showWarningToast("Errores detectados:<br>"+mensaje);
				} else {
					document.getElementById("formulario_lineas").submit();
					document.getElementById("codbarras").value="";
					document.getElementById("detalles").value="";
					document.getElementById("descripcion").value="";
					document.getElementById("cantidad").value="1";
				}
				$('#codbarras').focus();
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
 	
    $("#descripcion").autocomplete({
        source: 'busco2.php',
        minLength:2,
        autoFocus:true,
        select: function(event, ui) {
			var codigo=document.getElementById("aCliente").value;
			if (codigo=="") {
				showWarningToast("Debe introducir el código del cliente");
				$("#descripcion").val('');
				$('[data-index="1"]').focus();
				return false;
			}           	
         var name = ui.item.value;
         var thisValue = ui.item.data;
			var codfamilia=thisValue.split("~")[0];
			var codigobarras=thisValue.split("~")[1];
			var referencia=thisValue.split("~")[2];
			var nombre=thisValue.split("~")[3];
			var precio=thisValue.split("~")[4];
			var codarticulo=thisValue.split("~")[5];
			var moneda=thisValue.split("~")[6];
			var codservice=thisValue.split("~")[7];
			var detalles=thisValue.split("~")[8];
			var comision=thisValue.split("~")[9];
		
			var monArray = new Array();
			monArray[0]="Selecione uno";
			monArray[1]="<?php echo $moneda1;?>";
			monArray[2]="<?php echo $moneda2;?>";
			$("#codfamilia").val(codfamilia);
			if (codigobarras!='') {
				$("#codbarras").val(codigobarras);
			} else {
				$("#codbarras").val(referencia);
			}
			$("#articulos").val(referencia);
			$("#codservice").val(codservice);
			$("#detalles").val(detalles);
//			$("#comision").val(comision);
			
			$("#descripcion").val(nombre);
//			$("#precio").val(precio);
//			$("#moneda").val(moneda);
//			$("#monedaShow").val(monArray[moneda]);
//			$("#importe").val(precio);
			$("#codarticulo").val(codarticulo);
//			cambio();
//			actualizar_importe();
	 		$('[data-index="8"]').focus();
		}
	}).autocomplete("widget").addClass("fixed-height");


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
		var agencia=thisValue.split("~")[2];

		$("#aCliente").val(pref);
		$("#nombre").val(nombre);

//		$("#agencia").val(agencia);
	
		$('[data-index="2"]').focus();
		}
	}).autocomplete("widget").addClass("fixed-height");
});
</script>			
		
	</head>
	<body onload="inicio();" >
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">Nuevo Artículos en transito </div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_artiviaja.php">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td width="5%">Cliente </td>
					      <td colspan="2"><input name="nombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" value="<?php echo $nombre?>">
					      <input name="codcliente" type="hidden" id="aCliente" value="<?php echo $codcliente?>">
							</td>
						  <td>Nº&nbsp;Comprobante&nbsp;<input id="codartiviajatmp" class="cajaPequena" name="codartiviajatmp" value="<?php echo $codartiviajatmp;?>" readonly disabled="true"></td>
						  		         					        					
						</tr>
						<tr>
						<td>Estado</td>
				      <td>
			            <select id="estado" name="Aestado" class="cajaPequena" data-index="2">
								<?php $tipof = array(0=>"Entragado", 1=>"En transito");
								if ($estado==" ")
								{
								echo '<OPTION value="" selected>Selecione uno</option>';
								}
								$x=0;
								$NoEstado=0;
								foreach($tipof as $i) {
								  	if ( $x==$estado) {
										echo "<OPTION value=$x selected>$i</option>";
										$NoEstado=1;
									} else {
										echo "<OPTION value=$x>$i</option>";
									}
									$x++;
								}
								?>
						</select>
						</td>

						<td>Fecha&nbsp;salida</td>
						<td>
						    <input name="fechaenvio" type="text" class="cajaPequena" id="fechaenvio" size="10" maxlength="10" value="<?php echo $fechaenvio;?>" readonly data-index="3"> 
						    <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fechaenvio",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide() },
						     dateFormat : "%d/%m/%Y"
						   });
						</script></td>

						</tr>
						<tr>						
						<td colspan="4" width="100%" align="center"><div id="tituloForm" class="header">DATOS DEL TRANSPORTISTA</div></td>
						</tr>
						<tr><td>Empresa</td><td><input type="text" size="50" name="transportista" id="transportista" class="cajaGrande" maxlength="50" value="<?php echo $transportista;?>"  data-index="4"></td>
						<td>Datos&nbsp;del&nbsp;Vehículo</td><td><input type="text" name="vehiculo" id="vehiculo" class="cajaGrande" size="20" maxlength="50" value="<?php echo $vehiculo;?>" data-index="5"> </td>
						</tr>
						<tr><td>Chofer</td><td><input type="text" size="50" name="chofer" id="chofer" class="cajaGrande" maxlength="50" value="<?php echo $chofer;?>"  data-index="6"></td>
						</tr>
						<tr>						
						<td>Lugar&nbsp;de&nbsp;entrega</td><td>
						<input name="adestino" type="text" class="cajaGrande" id="destino" size="20" maxlength="50" value="<?php echo $destino;?>"  data-index="7">
						</td>
							<td width="6%">Fecha&nbsp;entrega</td><td>
						    <input name="fecharentrega" type="text" class="cajaPequena" id="fecharentrega" size="10" maxlength="10" value="<?php echo $fecharentrega;?>" readonly data-index="8"> 
						    <img src="../img/calendario.png" name="Image2" id="Image2" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fecharentrega",
						     trigger    : "Image2",
						     align		 : "Bl",
						     onSelect   : function() { this.hide() },
						     dateFormat : "%d/%m/%Y"
						   });
						</script>
						&nbsp;Hora:&nbsp;<input type="text" name="hora" id="hora" size="10" class="cajaPequena" value="<?php echo $hora;?>" data-index="9">
						</td>
						</tr>
					</table>										
			  </div>
			  <input id="codartiviajatmp" name="codartiviajatmp" value="<?php echo $codartiviajatmp;?>" type="hidden">
			  <input id="observacion" name="observacion" value="<?php echo $observacion;?>" type="hidden">
			  <input id="accion" name="accion" value="modificar" type="hidden">
			  </form>
			  <br style="line-height:5px">
			  <div id="frmBusqueda">
				<form id="formulario_lineas" name="formulario_lineas" method="post" action="frame_lineas.php" target="frame_lineas">
				<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0>
				  <tr>
					<td>Codigo </td>
					<td valign="middle"><input name="codbarras" type="text" class="cajaMedia" id="codbarras" size="15" maxlength="15" data-index="10">
					 <img id="botonBusqueda" src="../img/calculadora.jpg" border="1" align="absmiddle" onClick="validarArticulo();" onMouseOver="style.cursor=cursor" title="Validar codigo de barras" style="vertical-align: middle; margin-top: -1px;">
					 <img id="botonBusqueda" src="../img/ver.png" width="16" height="16" onClick="ventanaArticulos();" onMouseOver="style.cursor=cursor" title="Buscar articulo" style="vertical-align: middle; margin-top: -1px;">
					<td>Descripcion</td>
					<td><input name="descripcion" type="text" class="cajaGrande" id="descripcion" size="30" maxlength="30"></td>
					 
				  </tr>
				  <tr>
					<td valign="top">Detalles</td>
					<td ><textarea name="detalles" rows="2" cols="50" class="areaTexto" id="detalles"> </textarea>
					</td>
					<td>Cantidad</td>
					<td><input name="cantidad" type="text" class="cajaMinima" id="cantidad" size="10" maxlength="10" value="1" data-index="11">&nbsp;&nbsp;
					<button class="boletin" onClick="validar();" onMouseOver="style.cursor=cursor"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Agregar</button>
					</td>
				  </tr>
				</table>
<!--//fin del form incio-->

				</div>
				<input name="codarticulo" value="" type="hidden" id="codarticulo">
				<br style="line-height:5px">
				<div id="frmBusqueda">
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=0 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="3%">ITEM</td>
							<td width="14%">REFERENCIA</td>
							<td width="34%">DESCRIPCION</td>
							<td width="34%">DETALLES</td>
							<td width="8%">CANTIDAD</td>
							<td width="10px">&nbsp;</td>
						</tr>
						<tr><td width="100%" colspan="9">
					<iframe width="100%" height="160" id="frame_lineas" name="frame_lineas" frameborder="0">
						<ilayer width="100%" height="160" id="frame_lineas" name="frame_lineas"></ilayer>
					</iframe>
				</td></tr>					
				</table>
			  </div>
			  <div id="frmBusqueda">
			<table width="100%" border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
			<tr>
			<td align="rigth" valign="top">
				<table border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
				<tr>
				<td valign="top">Observaciones</td>
				<td valign="top" rowspan="3"><textarea id="observacionaux" rows="4" cols="40"></textarea>
				</td>
				
				<td>
					<div align="center">
						<button class="boletin" onClick="validar_cabecera();" onMouseOver="style.cursor=cursor"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Aceptar</button>
						<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
				    	<input id="codfamilia" name="codfamilia" value="<?php echo $codfamilia?>" type="hidden">
				    	<input id="codartiviajatmp" name="codartiviajatmp" value="<?php echo $codartiviajatmp;?>" type="hidden">	
						<input id="modif" name="modif" value="0" type="hidden">				    
					</div>
				</td>
								
				</tr> 				
				</table>
				</td><td >
				
			  </tr>
		</table>
			  </div>
			  		<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>
			  </form>
			 </div>
	 
			 </div>
		</div>
	
	</body>
</html>
