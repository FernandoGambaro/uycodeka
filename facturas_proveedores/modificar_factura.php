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


include ("../conectar.php");
include("../common/verificopermisos.php");
include("../common/funcionesvarias.php");
include ("../funciones/fechas.php"); 
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
//header('Content-Type: text/html; charset=UTF-8');

date_default_timezone_set("America/Montevideo");
$baseimponible='';
$tipocambio=''; 
$modif='';
$total_importe='';
$codarticulo='';

$codfactura=$_GET["codfactura"];
$codproveedor=$_GET["codproveedor"];
$sel_alb="SELECT * FROM facturasp WHERE codfactura='$codfactura' AND codproveedor='$codproveedor'";
$rs_alb=mysqli_query($GLOBALS["___mysqli_ston"], $sel_alb);
$codproveedor=mysqli_result($rs_alb, 0, "codproveedor");
$iva=mysqli_result($rs_alb, 0, "iva");
$fecha=mysqli_result($rs_alb, 0, "fecha");
$moneda=mysqli_result($rs_alb, 0, "moneda");
$tipo=mysqli_result($rs_alb, 0, "tipo");

$sel_cliente="SELECT nombre,nif FROM proveedores WHERE codproveedor='$codproveedor'";
$rs_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cliente);
$nombre=mysqli_result($rs_cliente, 0, "nombre");
$nif=mysqli_result($rs_cliente, 0, "nif");


$fechahoy=date("Y-m-d");
$sel_albaran="INSERT INTO facturasptmp (codfactura,fecha,moneda) VALUE ('','$fechahoy','$moneda')";
$rs_albaran=mysqli_query($GLOBALS["___mysqli_ston"], $sel_albaran);
$codfacturatmp=((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);

$sel_lineas="SELECT * FROM factulineap WHERE codfactura='$codfactura' AND codproveedor='$codproveedor' ORDER BY numlinea ASC";
$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
$contador=0;
while ($contador < mysqli_num_rows($rs_lineas)) {
	$codfamilia=mysqli_result($rs_lineas, $contador, "codfamilia");
	$codigo=mysqli_result($rs_lineas, $contador, "codigo");
	$cantidad=mysqli_result($rs_lineas, $contador, "cantidad");
	$precio=mysqli_result($rs_lineas, $contador, "precio");
	$importe=mysqli_result($rs_lineas, $contador, "importe");
	$baseimponible=$baseimponible+$importe;
	$dcto=mysqli_result($rs_lineas, $contador, "dcto");
	
	if($codigo>0 and trim($codigo) !="" ) {
	$sel_tmp="INSERT INTO factulineaptmp (codfactura,numlinea,codfamilia,codigo,cantidad,precio,importe,dcto) 
	VALUES ('$codfacturatmp','','$codfamilia','$codigo','$cantidad','$precio','$importe','$dcto')";
	$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tmp);
	}
	$contador++;
}

		  	$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".$iva;
			$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
			$baseimpuestos=$baseimponible*(mysqli_result($res_iva, 0, "valor")/100);

$preciototal=$baseimponible+$baseimpuestos;
//$preciototal=number_format($preciototal,2);
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

<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
<script src="../js3/message.js" type="text/javascript"></script>
<link rel="stylesheet" href="../js3/colorbox.css" />
<script src="../js3/jquery.colorbox.js"></script>
		<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">
	
<script type="text/javascript">

$(document).keydown(function(e) {
    switch(e.keyCode) { 
        case 13:
			event.preventDefault();
        var $this = $(e.target);
        var index = parseFloat($this.attr('data-index'));
	        if (index==1) {
	        	var codigo=document.getElementById("aProveedor").value;
	        		if (codigo=='') {
						abreVentana();
	        			$('[data-index="' + (index + 1).toString() + '"]').focus();
		        		}
		     }
		     if (index==5) {
		     		var tipocambiofactura=document.formulario.amoneda.options[document.formulario.amoneda.selectedIndex].value;
					if (tipocambiofactura==0) showWarningToast("  - Seleccione moneda<br>");
					$("#amoneda").focus();
		     }
			  if (index==8) {
		     		var tipocambiofactura=document.formulario.amoneda.options[document.formulario.amoneda.selectedIndex].value;
					if (tipocambiofactura==0) { showWarningToast("  - Seleccione moneda<br>");
					$("#amoneda").focus();
					return false;
					}
		     		var codigo=document.getElementById("articulos").value;
		      	if (codigo=='') {
		        		ventanaArticulos();
		        		$('[data-index="' + (index + 1).toString() + '"]').focus();	
		     		}
		     }
			  if (index==13) {
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
function round(value, exp) {
  if (typeof exp === 'undefined' || +exp === 0)
    return Math.round(value);

  value = +value;
  exp  = +exp;

  if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
    return NaN;

  // Shift
  value = value.toString().split('e');
  value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

  // Shift back
  value = value.toString().split('e');
  return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
}
</script>
				
		<script type="text/javascript">
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

function pon_prefijo(pref,nombre,nif) {
	$("#aProveedor").val(pref);
	$("#nombre").val(nombre);
	$("#nif").val(nif);
	$('idOfDomElement').colorbox.close();
}

function pon_prefijo_Fb (codfamilia,codigobarras,referencia,descripcion,precio_compra,codarticulo,moneda,detalles) {
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
			$("#detalles").val(detalles);
			
			$("#descripcion").val(descripcion);
			$("#precio").val(precio_compra);
			$("#moneda").val(moneda);
			$("#monedaShow").val(monArray[moneda]);
			$("#importe").val(precio_compra);
			$("#codarticulo").val(codarticulo);
			$('idOfDomElement').colorbox.close();
			
			cambio();
			actualizar_importe();
	 		$('[data-index="11"]').focus();	
}

</script>

		
		<script language="javascript">
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		function inicio() {
			document.getElementById("modif").value=1;
			document.formulario_lineas.submit();
			document.getElementById("modif").value=0;			
			var fecha=$("#fecha").val();
				$.post("busco_tipocambio.php?fecha="+fecha,  function(data){
				$("#tipocambio").val(data);
			});	
		}	
		function abreVentana(){
			var bi=document.getElementById("baseimponible").value;
			if (bi==0) {
				$.colorbox({
		   	href: "ver_proveedores.php", open:true,
				iframe:true, width:"99%", height:"99%",
					onCleanup:function() {
						$('#cfactura').focus();
						}
				});			
			} else {
				showWarningToast("Ha comenzado la factura. No puede cambiar de proveedor");
			}
		}
		
		function ventanaArticulos(){
     		var tipocambiofactura=document.formulario.amoneda.options[document.formulario.amoneda.selectedIndex].value;
			if (tipocambiofactura==0) { showWarningToast("  - Seleccione moneda<br>");
			$("#amoneda").focus();
			return false;
			}
			
			var codprov=document.getElementById("aProveedor").value;
			if (codprov=="") {
				showWarningToast("Debe introducir antes el codigo de proveedor");
			} else {
				document.getElementById("aProveedor").ReadOnly=true;
				$.colorbox({href:"ver_articulos.php?codproveedor="+codprov,
				iframe:true, width:"95%", height:"95%",
					onCleanup:function() {
						$('#precio').focus();
						}
				});				
			}
		}
		
		function validarproveedor(){
			var bi=document.getElementById("baseimponible").value;
			if (bi==10) {
				var codigo=document.getElementById("aProveedor").value;
				$.colorbox({href:"comprobarproveedor.php?codproveedor="+codigo,
				iframe:true, width:"95%", height:"95%",
				});				
			} 
		}	
		
		function comprobarestado() {
			var codpro=document.getElementById("aProveedor").value;
			var bi=document.getElementById("baseimponible").value;
			if (bi>0) {
				showWarningToast("Ha comenzado la factura. No puede cambiar de proveedor");
				document.getElementById("aProveedor").blur();
			}
		}
		
		function cancelar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
		function limpiarcaja() {
			document.getElementById("nombre").value="";
			document.getElementById("nif").value="";
		}

		function limpiar() {
					document.getElementById("codbarras").value="";
					document.getElementById("articulos").value="";
					document.getElementById("detalles").value="";
					document.getElementById("descripcion").value="";
					document.getElementById("precio").value="";
					document.getElementById("cantidad").value="";
					document.getElementById("moneda").value="";
					document.getElementById("monedaShow").value="";
					document.getElementById("importe").value="";
					document.getElementById("descuento").value=0;	
					document.getElementById("descuentopp").value=0;
		}
		
		function pon_baseimponible(baseimponible) {
			$("#baseimponible").val(baseimponible);
			cambio_iva();
		}
		
		function actualizar_importe()
			{
				/*Si la factura es en peso y el articulo esta en dolares aplico el tipo de cambio*/
				var tipocambiofactura=document.formulario.amoneda.options[document.formulario.amoneda.selectedIndex].value;
				var tipocambioarcticulo=document.getElementById("moneda").value;
				if (tipocambiofactura==1 && tipocambioarcticulo == 2){
					var precio=document.getElementById("precio").value * parseFloat(document.getElementById("tipocambio").value);
				}
				if (tipocambiofactura==2 && tipocambioarcticulo == 1){
					var precio=document.getElementById("precio").value / parseFloat(document.getElementById("tipocambio").value);
				}
				if ((tipocambiofactura==1 && tipocambioarcticulo == 1) || (tipocambiofactura==2 && tipocambioarcticulo == 2)){
				var precio=document.getElementById("precio").value;
				}
			
				var cantidad=document.getElementById("cantidad").value;
				var descuento=document.getElementById("descuento").value;
				descuento=descuento/100;
				total=precio*cantidad;
				descuento=total*descuento;
				total=total-descuento;
				var original=parseFloat(total);
				var result=Math.round(original*100)/100 ;
				document.getElementById("importe").value=result;
			}
			
		function validar_cabecera()
			{
				//event.preventDefault();
				var mensaje="";
				if (document.getElementById("nombre").value=="") mensaje+="  - Nombre<br>";
				if (document.getElementById("fecha").value=="") mensaje+="  - Fecha<br>";
				if (document.getElementById("cfactura").value=="") mensaje+="  - Cod. Factura<br>";
				if (mensaje!="") {
					showWarningToast("Errores detectados:<br>"+mensaje);
				} else {
					document.getElementById("formulario").submit();
				}
			}	
		
		function validar() 
			{
				var mensaje="";
				var entero=0;
				var enteroo=0;
		
				if (document.getElementById("codbarras").value=="") mensaje="  - Artículo<br>";
				if (document.getElementById("descripcion").value=="") mensaje+="  - Descripcion<br>";
				if (document.getElementById("precio").value=="") { 
							mensaje+="  - Falta el precio<br>"; 
				} else {
					if (isNaN(document.getElementById("precio").value)==true) {
						mensaje+="  - El precio debe ser numerico<br>";
					}
				}
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
				if (document.getElementById("descuento").value=="") 
						{ 
						document.getElementById("descuento").value=0 
						} else {
							entero=parseInt(document.getElementById("descuento").value);
							if (isNaN(entero)==true) {
								mensaje+="  - El descuento debe ser numerico<br>";
							} else {
								document.getElementById("descuento").value=entero;
							}
						} 
				if (document.getElementById("importe").value=="") mensaje+="  - Falta el importe<br>";
				
				if (mensaje!="") {
					showWarningToast("Errores detectados:<br>"+mensaje);
				} else {
					document.getElementById("baseimponible").value=parseFloat(document.getElementById("baseimponible").value) + parseFloat(document.getElementById("importe").value);	
					cambio_iva();
					document.getElementById("formulario_lineas").submit();
					document.getElementById("codbarras").value="";
					document.getElementById("articulos").value="";
					document.getElementById("descripcion").value="";
					document.getElementById("detalles").value="";
					document.getElementById("precio").value="";
					document.getElementById("moneda").value="";
					$("#monedaShow").val('');
					document.getElementById("cantidad").value=0;
					document.getElementById("importe").value="0";
					document.getElementById("descuento").value=0;						
				}
				$('#articulos').focus();				
			}
		function validarArticulo() {
			var articulos=document.getElementById("articulos").value;
			var descripcion=document.getElementById("descripcion").value;
				if (articulos!="" && articulos.length > 3 &&  descripcion=="") {
				$.colorbox({href:"comprobararticulos.php?codbarras="+articulos,
				iframe:true, width:"350", height:"100",
				});
				}			
		}			
		function cambio_iva() {
			var original=parseFloat(document.getElementById("baseimponible").value);
			var tipoiva=document.formulario.Aiva.options[document.formulario.Aiva.selectedIndex].value;
			var valorimpuesto = tipoiva.split("~")[1];
			var codimpuesto = tipoiva.split("~")[0];
			var result=round(original,2) ;
			document.getElementById("baseimponible").value=result;
			document.getElementById("impuesto").value=codimpuesto;
	
			document.getElementById("baseimpuestos").value=parseFloat(result * valorimpuesto / 100);
			var original1=parseFloat(document.getElementById("baseimpuestos").value);
			var result1=Math.round(original1*100)/100 ;
			document.getElementById("baseimpuestos").value=result1;
			var original2=parseFloat(result + result1);
			var result2=Math.round(original2*100)/100 ;
			document.getElementById("preciototal").value=result2;
		}	
		
		function busco_tipocambio() {
			var fecha=$("#fecha").val();
				$.post("busco_tipocambio.php?fecha="+fecha,  function(data){
				$("#tipocambio").val(data);
			});			
	 		
		}		
		var tipoaux='';
		function cambio() {
			var Index = document.formulario.amoneda.options[document.formulario.amoneda.selectedIndex].value;
			var monArray = new Array();
			monArray[0]="Selecione uno";
			monArray[1]="<?php echo $moneda1;?>";
			monArray[2]="<?php echo $moneda2;?>";
			$("#monShow").val(monArray[Index]);
			$("#monSho").val(monArray[Index]);
			$("#monSh").val(monArray[Index]);

			var moneda=document.getElementById("moneda").value;
			var cantidad=document.getElementById("cantidad").value;
			var descuento=document.getElementById("descuento").value;

				if (moneda==1 && Index == 2){
					precio= $("#precio").val() / parseFloat($("#tipocambio").val());
					descuento=descuento/100;
					total=precio*cantidad;
					descuento=total*descuento;
					total=total-descuento;
					var original=parseFloat(total);
					var result=Math.round(original*100)/100 ;
					document.getElementById("importe").value=result;
				}
				if (moneda==2 && Index == 1){
					precio= $("#precio").val() * parseFloat($("#tipocambio").val());
					descuento=descuento/100;
					total=precio*cantidad;
					descuento=total*descuento;
					total=total-descuento;
					var original=parseFloat(total);
					var result=Math.round(original*100)/100 ;
					document.getElementById("importe").value=result;
				}
				if (moneda== Index){
					precio= $("#precio").val();
					descuento=descuento/100;
					total=precio*cantidad;
					descuento=total*descuento;
					total=total-descuento;
					var original=parseFloat(total);
					var result=Math.round(original*100)/100  ;
					document.getElementById("importe").value=result;
				}

				if (tipoaux==1 && Index == 2){
					document.getElementById("baseimponible").value=Math.round(( $("#baseimponible").val() / parseFloat($("#tipocambio").val())) * 100) / 100;
					document.getElementById("baseimpuestos").value=Math.round(( $("#baseimpuestos").val() / parseFloat($("#tipocambio").val())) * 100) / 100;
					document.getElementById("preciototal").value=Math.round(($("#preciototal").val() / parseFloat($("#tipocambio").val())) * 100) / 100;
				}
				if (tipoaux==2 && Index == 1){
					document.getElementById("baseimponible").value=Math.round(($("#baseimponible").val() * parseFloat($("#tipocambio").val())) * 10) / 10;
					document.getElementById("baseimpuestos").value=Math.round(($("#baseimpuestos").val() * parseFloat($("#tipocambio").val())) * 10) / 10;
					document.getElementById("preciototal").value=Math.round(($("#preciototal").val() * parseFloat($("#tipocambio").val())) * 10) / 10;
				}
			tipoaux=Index;
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
			var codigo=document.getElementById("aProveedor").value;
			if (codigo=="") {
				showWarningToast("Debe introducir el código del proveedor");
				$("#descripcion").val('');
				$('[data-index="1"]').focus();
				return false;
			}           	
         var name = ui.item.value;
         var thisValue = ui.item.data;
			var codfamilia=thisValue.split("~")[0];
			var codigobarras=thisValue.split("~")[1];
			var referencia=thisValue.split("~")[2];
			var descripcion=thisValue.split("~")[3];
			var precio_compra=thisValue.split("~")[4];
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
			$("#detalles").val(detalles);
			
			$("#descripcion").val(descripcion);
			$("#precio").val(precio_compra);
			$("#moneda").val(moneda);
			$("#monedaShow").val(monArray[moneda]);
			$("#importe").val(precio_compra);
			$("#codarticulo").val(codarticulo);
			cambio();
			actualizar_importe();
	 		$('[data-index="11"]').focus();
		}
	}).autocomplete("widget").addClass("fixed-height");


    $("#aProveedor").autocomplete({
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

		$("#aProveedor").val(pref);
		$("#nombre").val(nombre);
		$("#nif").val(nif);
		$("#agencia").val(agencia);
	
		$('[data-index="2"]').focus();
		}
	}).autocomplete("widget").addClass("fixed-height");
});
</script>		
	</head>
	<body onLoad="inicio();">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">MODIFICAR FACTURA</div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_factura.php">				
				<table class="fuente8" width="98%" cellspacing="0" cellpadding="3" border="0">
						<tr>
							<td>C&oacute;digo&nbsp;Proveedor </td>
					      <td><input name="codproveedor" type="text" class="cajaPequena" id="aProveedor" size="6" maxlength="5"  value="<?php echo $codproveedor;?>" data-index="1"></input>
					        </td>					
						  <td>Cod.&nbsp;Factura</td>
						  <td><input name="cfactura" type="text" class="cajaMedia" id="cfactura" size="20" maxlength="20" value="<?php echo $codfactura;?>" data-index="2"></input></td>
						<?php $hoy=date("d/m/Y"); ?>
							<td>Fecha</td>
						    <td ><input name="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" value="<?php echo implota($fecha);?>" readonly data-index="3">
						    <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fecha",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide(); },
						     dateFormat : "%d/%m/%Y"
						   });
						</script></td>
						<td>Tipo</td>
				            <td>
				            <select id="tipo" name="atipo" class="cajaPequena" data-index="4">
								<?php $tipof = array(0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
									if ($tipo==" ")
									{
										echo '<option value="" selected>Selecione uno</option>';
									}
									$x=0;
									$NoEstado=0;
									foreach($tipof as $i) {
								  		if ( $x==$tipo) {
											echo "<option value=$x selected>$i</option>";
											$NoEstado=1;
										} else {
											echo "<option value=$x>$i</option>";
										}
										$x++;
									}
								?>
								</select></td>									
				 		<td>Moneda</td><td> 
						<select onchange="cambio();" name="amoneda" id="amoneda" class="cajaPequena2" data-index="5">
						<?php
						   $contador=0;
							$xmon=1;
						 while ($contador < mysqli_num_rows($res_resultado)) { 
						 			if ($moneda==$xmon) {
						 				?>
						 				<option value="<?php echo $xmon;?>" selected="selected"><?php echo mysqli_result($res_resultado, $contador, "simbolo");?></option>
 										<?php
						 			} else {
						 				?>
						 				<option value="<?php echo $xmon;?>"><?php echo mysqli_result($res_resultado, $contador, "simbolo");?></option>
 										<?php
									}
									$xmon++;						 
						 $contador++;
						 }
						?>
					</td>											  
						</tr>
						<tr>
							<td>Nombre</td>
						    <td><input name="nombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" value="<?php echo $nombre?>" readonly></td>
				            <td>RUT</td>
				            <td><input name="nif" type="text" class="cajaMedia" id="nif" size="20" maxlength="15" value="<?php echo $nif?>" readonly></td>
							
				            <td>IVA</td>
								<td>
				            <?php 
							  	$query_iva="SELECT * FROM impuestos WHERE borrado=0 ORDER BY nombre ASC";
								$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
								$contador=0;
							  ?>				      
				            <input name="impuesto" type="hidden"  id="impuesto"  value="<?php echo $iva;?>" >
								<select id="Aiva" name="Aiva" class="comboPequeno simpleinput" onchange="cambio_iva();" data-index="6">
								<option value="0">Seleccione una</option>
								<?php
								while ($contador < mysqli_num_rows($res_iva)) {
									if(mysqli_result($res_iva, $contador, "codimpuesto") ==$iva) {
									?>
								<option value="<?php echo mysqli_result($res_iva, $contador, "codimpuesto").'~'.mysqli_result($res_iva, $contador, "valor");?>" selected="selected"><?php echo mysqli_result($res_iva, $contador, "nombre")?></option>
								<?php
									} else {
									?>
								<option value="<?php echo mysqli_result($res_iva, $contador, "codimpuesto").'~'.mysqli_result($res_iva, $contador, "valor");?>"><?php echo mysqli_result($res_iva, $contador, "nombre")?></option>
								<?php
									} 	
								 $contador++;
								} ?>				
								</select>						            				            
				            </td>				            
							<td colspan="4">Tipo&nbsp;cambio
								<label><?php echo $moneda1;?>&nbsp;->&nbsp;	<?php echo $moneda2;?></label><span>
								<input name="tipocambio" type="text" class="cajaPequena2" id="tipocambio" size="5" maxlength="5" value="<?php echo $tipocambio; ?>" readonly="" data-index="7"></span>
							</td>
						</tr>
					</table>										
			  </div>
			  <input id="codfacturatmp" name="codfacturatmp" value="<?php echo $codfacturatmp;?>" type="hidden">
			  <input id="codfactura" name="codfactura" value="<?php echo $codfactura;?>" type="hidden">
			  <input id="baseimpuestos2" name="baseimpuestos" value="<?php echo $baseimpuestos;?>" type="hidden">
			  <input id="baseimponible2" name="baseimponible" value="<?php echo $baseimponible;?>" type="hidden">
			  <input id="preciototal2" name="preciototal" value="<?php echo $preciototal;?>" type="hidden">
			  <input id="accion" name="accion" value="modificar" type="hidden">			  
			  </form>
			  <br>
				<div id="frmBusqueda">
				<form id="formulario_lineas" name="formulario_lineas" method="post" action="frame_lineas.php" target="frame_lineas">
				<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0>
				  <tr>
					<td >Codigo barras/Referencia </td>
					<td>
					<input type="hidden" name="codbarras" class="cajaMedia" id="codbarras" size="15" maxlength="15">
					<input type="text" size="26" maxlength="60" id="articulos" name="articulos" autocomplete="off" class="cajaMedia" onBlur="validarArticulo();" data-index="8"/>

					 <img id="botonBusqueda" src="../img/calculadora.jpg" border="1" align="absmiddle" onClick="validarArticulo();" onMouseOver="style.cursor=cursor" title="Validar codigo de barras" style="vertical-align: middle; margin-top: -1px;">
					 <img id="botonBusqueda" src="../img/ver.png" width="16" height="16" onClick="ventanaArticulos();" onMouseOver="style.cursor=cursor" title="Buscar articulo" style="vertical-align: middle; margin-top: -1px;"></td>
					
					<td>Descripcion</td>
					<td colspan="5" ><input name="descripcion" type="text" class="cajaGrande" id="descripcion" size="45" maxlength="50" data-index="9">
					<td>Moneda</td>					 
					 <td colspan="2">
					 <input name="monedaShow" type="text" class="cajaPequena2" id="monedaShow" size="10" maxlength="10" readonly>
					 <input name="moneda" id="moneda" type="hidden" >
					 
					 </td>
				  </tr>
				  <tr>
					<td valign="top">Detalles</td>
					<td><textarea name="detalles" rows="2" cols="50" class="areaTexto" id="detalles" data-index="10"> </textarea>
					</td>
					<td>Precio</td>
					<td><input name="precio" type="text" class="cajaPequena2" id="precio" size="10" maxlength="10" onChange="actualizar_importe()" data-index="11"></td>
					<td>Cantidad</td>
					<td><input name="cantidad" type="text" class="cajaMinima" id="cantidad" size="10" maxlength="10" value="1" onChange="actualizar_importe()" data-index="12"></td>
					<td>Dcto.</td>
					<td><input name="descuento" type="text" class="cajaMinima" id="descuento" size="10" maxlength="10" onChange="actualizar_importe()" data-index="13"> %</td>
					<td>Importe</td>
					<td><input name="importe" type="text" class="cajaPequena2" id="importe" size="10" maxlength="10" value="0" readonly></td>
					<td >
						<button class="boletin" onClick="validar();" onMouseOver="style.cursor=cursor"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Agregar</button>
						</td>
					<td align="right">
					<button class="boletin" onClick="limpiar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Limpiar</button>
					</td>
				  </tr>
				</table>
				</div>
				<input type="hidden" name="codarticulo" id="codarticulo" value="<?php echo $codarticulo?>">
				<br>
				<div id="frmBusqueda">

					<iframe width="100%" height="200" id="frame_lineas" name="frame_lineas" frameborder="0">
						<ilayer width="100%" height="200" id="frame_lineas" name="frame_lineas"></ilayer>
					</iframe>				
			  </div>
			  <div id="frmBusqueda">
			  

<div id="frmBusqueda">
			<table width="25%" border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
			  <tr>
			    <td  class="busqueda">Sub-total</td>
				<td align="right"><div align="center">
				 <input type="text" class="cajaPequena2" id="monShow" readonly>
			      <input class="cajaTotales" name="baseimponible" type="text" id="baseimponible" size="12" value=0 align="right" value="<?php echo number_format((int)$baseimponible,2)?>" readonly> 
		        </div></td>
			  </tr>
			  <tr>
				<td class="busqueda">IVA</td>
				<td align="right"><div align="center">
				 <input type="text" class="cajaPequena2" id="monSho" readonly>
			      <input class="cajaTotales" name="baseimpuestos" type="text" id="baseimpuestos" size="12" align="right"  value="<?php echo number_format($baseimpuestos,2)?>" readonly>
		        </div></td>
			  </tr>
			  <tr>
				<td class="busqueda">Precio Total</td>
				<td align="right"><div align="center">
				 <input type="text" class="cajaPequena2" id="monSh" readonly>
			      <input class="cajaTotales" name="preciototal" type="text" id="preciototal" size="12" align="right" value="<?php echo number_format($preciototal,2)?>" readonly> 
		        </div></td>
			  </tr>
		</table>
			  </div>			  
			
			  </div>
				<div>
				<br style="line-height:5px">					
				  <div align="center">
						<button class="boletin" onClick="event.preventDefault();validar_cabecera();" onMouseOver="style.cursor=cursor"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Aceptar</button>
						<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
				    <input id="codfamilia" name="codfamilia" value="<?php echo @$codfamilia;?>" type="hidden">
				    <input id="codfacturatmpa" name="codfacturatmpa" value="<?php echo $codfacturatmp;?>" type="hidden">
					<input id="modif" name="modif" value="0" type="hidden">				    
			      </div>
				</div>
			  		<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>
			  </form>
			 </div>
		  </div>
		</div>
		<script type="text/javascript">
		cambio();
		</script>
	</body>
</html>
