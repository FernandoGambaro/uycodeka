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
 
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

date_default_timezone_set("America/Montevideo"); 

//$hoy=date("d/m/Y");

$hoy=$fechahoy=isset($_GET['fecha']) ? $_GET['fecha'] : date("Y-m-d");
$codfactura=isset($_GET['num']) ? $_GET['num'] : null;

$sel_fact="SELECT * FROM `facturas` WHERE `codfactura` ='$codfactura'";
$rs_fact=mysqli_query($GLOBALS["___mysqli_ston"], $sel_fact);

if ( mysqli_num_rows($rs_fact)>0) { 
	if(mysqli_result($rs_fact, 0, "estado")==1 and mysqli_result($rs_fact, 0, "tipo")==0){
		echo "<script language=\"javascript\">
				showWarningToast(\"No puede modificar una factura ya paga.\");
				parent.$('idOfDomElement').colorbox.close();</script>";

	} else {
		header('Location: modificar_factura.php?codfactura='.$codfactura);
	}
}

if(@$_GET['fecha']=='') {
	$fechahoy=date("Y-m-d");
	$hoy=implota($fechahoy);
	$sel_fact="SELECT codfactura FROM `facturas` ORDER BY codfactura DESC, fecha DESC LIMIT 1 ";
	$rs_fact=mysqli_query($GLOBALS["___mysqli_ston"], $sel_fact);
	$codfactura=(int)mysqli_result($rs_fact, 0, "codfactura")+1;
} else {
	$fechahoy=explota($fechahoy);
}

/*
$sel_fact="INSERT INTO facturastmp (codfactura,fecha) VALUE ('$codfactura','$fechahoy')";
$rs_fact=mysql_query($sel_fact);
*/
$codfacturatmp=$codfactura;

$sel_borrar = "DELETE FROM factulineatmp WHERE codfactura='$codfactura'";
$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);


$sel_lineas="SELECT * FROM `impuestos` WHERE `nombre`='IVA' and `borrado`='0'";
$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
$contador=0;
	$iva=mysqli_result($rs_lineas, 0, "valor");

$descuentogral=0;
$tipocambio='';

$tipo=1;
$moneda=1;

$baseimpuestos='';
$preciototal='';
$observacion='';
$codfamilia='';
$shoedetalle=1;
 $observacionaux='Puede hacer efectivo el pago de esta factura: BROU c/a $ 157 0055886, c/a U$S  157 100005 ';
 
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

<script src="../js3/jquery.maskedinput.js" type="text/javascript"></script>
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">


<script type="text/javascript">
var index;

$(document).keydown(function(e) {
    switch(e.keyCode) { 
        case 9:
			e.preventDefault();
        var $this = $(e.target);
        var index = parseFloat($this.attr('data-index'));
	        if (index==1) {
	        	var codigo=document.getElementById("nombre").value;
	        		if (codigo=='') {
						showWarningToast('Ingrese nombre cliente');
	        			$('[data-index="1"]').focus();
	        			return false;
		        		}
		     }
			  if (index==6) {
		     		var codigo=document.getElementById("articulos").value;
		      	if (codigo=='') {
		        		ventanaArticulos();
		        		$('[data-index="' + (index + 3).toString() + '"]').focus();	
		     		}
		     }
		     if (index==10) {
		     	validarstock(10);
		     }		     
			  if (index==14) {
		        		validar();
		        		$('[data-index="5').focus();
		     }		     
		     		$('[data-index="' + (index + 1).toString() + '"]').focus();

        break;
        case 13:
			e.preventDefault();
        var $this = $(e.target);
        var index = parseFloat($this.attr('data-index'));
	        if (index==1) {
	        	var codigo=document.getElementById("aCliente").value;
	        		if (codigo=='') {
						showWarningToast('Ingrese nombre cliente');
	        			$('[data-index="1"]').focus();
	        			return false;
		        		}
		     }
			  if (index==6) {
		     		var codigo=document.getElementById("articulos").value;
		      	if (codigo=='') {
		        		ventanaArticulos();
		        		$('[data-index="' + (index + 3).toString() + '"]').focus();	
		     		}
		     }
		     if (index==10) {
		     	validarstock(10);
		     }
			  if (index==14) {
		        		validar();
		        		$('[data-index="5"]').focus();
		     }		     
		     		$('[data-index="' + (index + 1).toString() + '"]').focus();

        break;
        case 112:
            showWarningToast('Ayuda aún no disponible...');
        break;
       
	 }
});

jQuery(function($){
   $("#fecha").mask("99/99/9999",{placeholder:"dd/mm/yyyy"});
});
</script>		

<script type="text/javascript">
$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();


});
</script>
<script type="text/javascript">
		function cambio() {
		var tipoaux='';
			var Index = document.formulario.Amoneda.options[document.formulario.Amoneda.selectedIndex].value;
			var monArray = new Array();
			monArray[0]="Selecione uno";
			monArray[1]="<?php echo $moneda1;?>";
			monArray[2]="<?php echo $moneda2;?>";
			$("#monShow").val(monArray[Index]);
			$("#monSho").val(monArray[Index]);
			$("#monSh").val(monArray[Index]);

				if (tipoaux==1 && Index == 2){
					document.getElementById("baseimponible").value=round(($("#baseimponible").val() / parseFloat($("#tipocambio").val())),2)
					document.getElementById("baseimpuestos").value=round(($("#baseimpuestos").val() / parseFloat($("#tipocambio").val())),2);
					document.getElementById("preciototal").value=round(($("#preciototal").val() / parseFloat($("#tipocambio").val())),2);
				}
				if (tipoaux==2 && Index == 1){
					document.getElementById("baseimponible").value=round(($("#baseimponible").val() * parseFloat($("#tipocambio").val())),2);
					document.getElementById("baseimpuestos").value=round(($("#baseimpuestos").val() * parseFloat($("#tipocambio").val())),2);
					document.getElementById("preciototal").value=round(($("#preciototal").val() * parseFloat($("#tipocambio").val())),2);
				}
			tipoaux=Index;
		}		
		function actualizar_importe(){
				/*Si la factura es en pesos y el articulo esta en dolares aplico el tipo de cambio*/
				var tipocambiofactura=document.formulario.Amoneda.options[document.formulario.Amoneda.selectedIndex].value;
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
				var descuentopp=document.getElementById("descuentopp").value;
				descuento=descuento/100;
				descuentopp=descuentopp/100;
				
				total=precio*cantidad;

				descuento=total*descuento;
				total=total-descuento;

				descuentopp=total*descuentopp;
				total=total-descuentopp;

				var original=parseFloat(total);
				var result=round(original,2) ;
				document.getElementById("importe").value=result;
			}			
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

function pon_prefijo_b(pref,nombre,nif,agencia) {
	$("#aCliente").val(pref);
	$("#nombre").val(nombre);
	$("#nif").val(nif);
	$("#agencia").val(agencia);
	$('idOfDomElement').colorbox.close();
}

function pon_prefijo_Fb(codfamilia,referencia,codigobarras,nombre,precio,codarticulo,moneda,codservice,detalles,comision) {
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
	$("#comision").val(comision);
	
	$("#descripcion").val(nombre);
	$("#precio").val(precio);
	$("#moneda").val(moneda);
	$("#monedaShow").val(monArray[moneda]);
	$("#importe").val(precio);
	$("#codarticulo").val(codarticulo);
	$('idOfDomElement').colorbox.close();
	cambio();
	actualizar_importe();
}

function pon_baseimponible(baseimponible) {
	$("#baseimponible").val(baseimponible);
	actualizo_descuento();
}

function validarstock(index){
//Función para validad stock, utilizando el código de producto y la cantidad seleccionada
	var codarticulo=document.getElementById("codarticulo").value;
	var cantidad=document.getElementById("cantidad").value;

	jQuery.ajax({
		 type: "POST",
		 url: "monitoreostock.php",
		 data: {codarticulo:codarticulo, cantidad:cantidad },
		 async: true,
		 	 cache: false,
		 success: function(data){ 
		 if (parseFloat(data)< parseFloat(cantidad))  {
			showWarningToast("Error, no hay stock suficiente, hay: "+data);
			$('[data-index="' + (index).toString() + '"]').focus();
		 } else {
		 	actualizar_importe();
		 }
		},
		  error: function() {
		   showWarningToast("Error");
		}
	});        
}

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
		<script language="javascript">
		var top;
		function callGpsDiag(xx,numfactura,top){
			parent.callGpsDiag(xx,numfactura,top);
		}		
		
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}

		function inicio() {
			var fecha=$("#fecha").val();
				$.post('busco_tipocambio.php', {fecha : fecha },  function(data){
				$("#tipocambio").val(data);
			});	
		}	
			
		function abreVentana(){
			$.colorbox({
	   	href: "ventana_clientes_ini.php", open:true,
			iframe:true, width:"580", height:"450",
			onCleanup:function() {
				$('#tipo').focus();
				}
			});
			
		}
		
		function ventanaArticulos(){
			var codigo=document.getElementById("aCliente").value;
			if (codigo=="") {
				showWarningToast("Debe introducir el código del cliente");
			} else {

				var moneda=document.getElementById("Amoneda").value;
				if (moneda=='0') {
					showWarningToast("Debe seleccionar moneda");
				} else {
					$.colorbox({href:"ver_articulos.php",
					iframe:true, width:"95%", height:"95%",
					onCleanup:function() {
						$('#precio').focus();
						}
					});
				}
			}
		}
		function ventanaService(){
			var codigo=document.getElementById("aCliente").value;
			if (codigo=="") {
				showWarningToast("Debe introducir el código del cliente");
			} else {
				var moneda=document.getElementById("Amoneda").value;
				if (moneda=='0') {
					showWarningToast("Debe seleccionar moneda");
				} else {
					$.colorbox({href:"ver_service.php?codcliente="+codigo,
					iframe:true, width:"95%", height:"95%",
					});
				}
			}
		}		
		
		function validarcliente(){
			var codigo=document.getElementById("aCliente").value;
				$.colorbox({href:"comprobarcliente.php?codcliente="+codigo,
				iframe:true, width:"350", height:"200",
				
				});
		}	
		
		function validarArticulo() {
			//preventDefault();
			var codigo=document.getElementById("aCliente").value;
			if (codigo=="") {
				showWarningToast("Debe introducir el código del cliente");
				limpiar();
				$('[data-index="1"]').focus();
			} else {			
			var codbarras=document.getElementById("articulos").value;
				if (codbarras!="") {
				$.colorbox({href:"comprobararticulos.php?codbarras="+codbarras,
				iframe:true, width:"350", height:"200",
				});
				}
			}			
		}		
		
		function limpiarcaja() {
			document.getElementById("aCliente").value="";
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
					document.getElementById("comision").value=0;
					document.getElementById("descuentopp").value=0;
		}		
		
		
		function validar_cabecera()
			{
				var mensaje="";
				if (document.getElementById("nombre").value=="") mensaje+="  - Nombre<br>";
				if (document.getElementById("fecha").value=="") mensaje+="  - Fecha<br>";
				if (mensaje!="") {
					showWarningToast("Errores detectados:<br><br>"+mensaje);
				} else {
					document.getElementById("descuentogral").value=document.getElementById("descuentogralaux").value;
					document.getElementById("observacion").value=document.getElementById("observacionaux").value;
					document.getElementById("formulario").submit();
				}
			}	
		
		function validar() 
			{
				event.preventDefault();
				var mensaje="";
				var entero=0;
				var enteroo=0;
		
				if (document.getElementById("codbarras").value=="") mensaje="  - Codigo de barras<br>";
				if (document.getElementById("descripcion").value=="") mensaje+="  - Descripción<br>";
				if (document.getElementById("precio").value=="") { 
							mensaje+="  - Falta el precio<br>"; 
						} else {
							if (isNaN(document.getElementById("precio").value)==true) {
								mensaje+="  - El precio debe ser numérico<br>";
							}
						}
				if (document.getElementById("cantidad").value=="") 
						{ 
						mensaje+="  - Falta la cantidad<br>";
						} else {
							enteroo=parseInt(document.getElementById("cantidad").value);
							if (isNaN(enteroo)==true) {
								mensaje+="  - La cantidad debe ser numérica<br>";
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
								mensaje+="  - El descuento debe ser numérico<br>";
							} else {
								document.getElementById("descuento").value=entero;
							}
						}
				if (document.getElementById("descuentopp").value=="") 
						{ 
						document.getElementById("descuentopp").value=0 
						} else {
							entero=parseInt(document.getElementById("descuentopp").value);
							if (isNaN(entero)==true) {
								mensaje+="  - El descuento pronto pago debe ser numérico<br>";
							} else {
								document.getElementById("descuentopp").value=entero;
							}
						}						 
				if (document.getElementById("importe").value=="") mensaje+="  - Falta el importe<br>";
				
				if (mensaje!="") {
					showWarningToast("Errores detectados:<br>"+mensaje);
					return false;
				} else {
					var descuentogral=document.getElementById("descuentogralaux").value;
					document.getElementById("baseimponible").value=parseFloat(document.getElementById("baseimponible").value) + parseFloat(document.getElementById("importe").value);
					document.getElementById("baseimponibledescuento").value=	round((parseFloat(document.getElementById("baseimponible").value) / (1+descuentogral/100)),2);
					cambio_iva();
					document.getElementById("formulario_lineas").submit();
					document.getElementById("codbarras").value="";
					document.getElementById("articulos").value="";
					document.getElementById("detalles").value="";
					document.getElementById("descripcion").value="";
					document.getElementById("precio").value="";
					document.getElementById("cantidad").value=1;
					document.getElementById("moneda").value="";
					document.getElementById("monedaShow").value="";
					document.getElementById("importe").value="";
					document.getElementById("descuento").value=0;						
					document.getElementById("descuentopp").value=0;						
				}
				$('#codbarras').focus();
			}
			
		function cambio_iva() {
			var original=parseFloat(document.getElementById("baseimponible").value);
			var tipoiva=document.formulario.Aiva.options[document.formulario.Aiva.selectedIndex].value;
			var valorimpuesto = tipoiva.split("~")[1];
			var codimpuesto = tipoiva.split("~")[0];
			var result=round(original,2) ;
			document.getElementById("baseimponible").value=result;
			document.getElementById("impuesto").value=codimpuesto;
			
			var descuentogral=document.getElementById("descuentogralaux").value;
			if (descuentogral=0) {
			var original=parseFloat(document.getElementById("baseimponible").value);
			} else {
			var original=parseFloat(document.getElementById("baseimponibledescuento").value);
			}
			var result=round(original,2) ;
	
			document.getElementById("baseimpuestos").value=parseFloat(result * parseFloat(valorimpuesto / 100));
			var original1=parseFloat(document.getElementById("baseimpuestos").value);
			var result1=round(original1,1) ;
			document.getElementById("baseimpuestos").value=result1;
			var original2=parseFloat(result + result1);
			var result2=round(original2,2) ;
			document.getElementById("preciototal").value=result2;
		}	

		function actualizo_descuento() {
				var descuentogral=parseFloat(document.getElementById("descuentogralaux").value);
				document.getElementById("baseimponibledescuento").value=	round((parseFloat(document.getElementById("baseimponible").value) * (1-descuentogral/100)),2);
				cambio_iva();
		}		
		
		function busco_tipocambio() {
			var fecha=$("#fecha").val();
				$.post('busco_tipocambio.php', {fecha : fecha },  function(data){
				$("#tipocambio").val(data);
			});			
	 		
		}	
		
		</script>
<script type="text/javascript" >
function actualizovencimeinto() {
	var pago = document.getElementById("codformapago").value;
	var d = pago.split("~")[1];
	var fecha=document.getElementById('fecha').value;
	var Fecha = new Date();
	var sFecha = fecha || (Fecha.getDate() + "/" + (Fecha.getMonth() +1) + "/" + Fecha.getFullYear());
	var sep = sFecha.indexOf('/') != -1 ? '/' : '-'; 
	var aFecha = sFecha.split(sep);
	var fecha = aFecha[2]+'/'+aFecha[1]+'/'+aFecha[0];
	fecha= new Date(fecha);
	fecha.setDate(fecha.getDate()+parseInt(d));
	var anno=fecha.getFullYear();
	var mes= fecha.getMonth()+1;
	var dia= fecha.getDate();
	mes = (mes < 10) ? ("0" + mes) : mes;
	dia = (dia < 10) ? ("0" + dia) : dia;
	var fechaFinal = dia+sep+mes+sep+anno;
	document.getElementById('vencimiento').value=fechaFinal;
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
			var stock=thisValue.split("~")[10];
		
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
			$("#comision").val(comision);
			
			$("#descripcion").val(nombre);
			$("#precio").val(precio);
			$("#moneda").val(moneda);
			$("#monedaShow").val(monArray[moneda]);
			$("#importe").val(precio);
			$("#codarticulo").val(codarticulo);
			cambio();
			actualizar_importe();
	 		$('[data-index="9"]').focus();
		}
	}).autocomplete("widget").addClass("fixed-height");


    $("#nombre").autocomplete({
        source: '../common/busco.php',
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
	<body onload="inicio();" >
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">NUEVA FACTURA</div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_factura.php">
					<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td>Nombre</td>
						    <td colspan="2"><input name="nombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" data-index="1"></td>
						    <td width="5%">C&oacute;digo&nbsp;Cliente </td>
					      <td><input name="codcliente" type="text" class="cajaPequena" id="aCliente" size="6" maxlength="5" readonly >
					      </td>
					      <td>
							Nº&nbsp;factura&nbsp;</td><td>
							<input id="codfacturatmp" class="cajaPequena" name="codfacturatmp" value="<?php echo $codfacturatmp?>" disabled="true">						  
						  </input></td>				         					        					
						</tr>
						<tr>
				            <td>RUT</td>
				            <td colspan="2"><input name="nif" type="text" class="cajaMedia" id="nif" size="20" maxlength="15" readonly></td>
								<td>Tipo</td>
				            <td>
				            <select id="tipo" name="atipo" class="comboMedio" data-index="2">

					<?php $tipof = array(0=>"Contado", 1=>"Credito");
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
								</select></td><td>Forma&nbsp;de&nbsp;pago&nbsp;</td><td>
														<?php
					  	$query_fp="SELECT * FROM formapago WHERE borrado=0 ORDER BY dias ASC";
						$res_fp=mysqli_query($GLOBALS["___mysqli_ston"], $query_fp);
						$contador=0;
					  ?>
								<select id="codformapago" name="codformapago" class="comboMedio simpleinput" onchange="actualizovencimeinto();" data-index="3">
							
								<option value="0">Seleccione una</option>
								<?php
								while ($contador < mysqli_num_rows($res_fp)) { ?>
								<option value="<?php echo mysqli_result($res_fp, $contador, "codformapago").'~'.mysqli_result($res_fp, $contador, "dias");?>"><?php echo mysqli_result($res_fp, $contador, "nombrefp")?></option>
								<?php 	
								 $contador++;
								} ?>				
								</select>																
						</td>		
						<td>Moneda
		 					<select onchange="cambio();" name="Amoneda" id="Amoneda" class="comboPequeno" data-index="4">
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
							</select>
							</td>
						</tr>
						<tr>
							<td>Fecha</td><td>
						    <input name="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" value="<?php echo $hoy;?>" > 
						    <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fecha",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide(); actualizovencimeinto(); },
						     dateFormat : "%d/%m/%Y"
						   });
						</script></td><td>
						Vencimiento <input name="vencimiento" type="text" class="cajaPequena" id="vencimiento" size="10" maxlength="10" value="<?php echo $hoy;?>" readonly>
						
						</td>
				            <td>IVA</td><td>
				            <?php 
					  	$query_iva="SELECT * FROM impuestos WHERE borrado=0 ORDER BY nombre ASC";
						$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
						$contador=0;
					  ?>
					  			<input name="impuesto" type="hidden"  id="impuesto" value="<?php echo $iva;?>">
								<select id="Aiva" name="Aiva" class="comboPequeno simpleinput" onchange="cambio_iva();" data-index="5">
							
								<option value="0">Seleccione una</option>
								<?php
								while ($contador < mysqli_num_rows($res_iva)) {
									if(mysqli_result($res_iva, $contador, "codimpuesto") ==1) {
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
				            <td>Tipo&nbsp;cambio</td><td>
								<label><?php echo $moneda1;?>&nbsp;->&nbsp;	<?php echo $moneda2;?></label><span>
								<input name="tipocambio" type="text" class="cajaPequena2" id="tipocambio" size="5" maxlength="5" value="<?php echo $tipocambio; ?>" onChange="cambio_iva();"></span>
								</td>
						  <td><label>Agencia&nbsp;</label><span><input name="agencia" type="text" class="cajaGrande" id="agencia" size="5" maxlength="5" value=""></span>
						  &nbsp;</td>				         					        					
						</tr>
					</table>										
			  </div>
			  <input id="codfacturatmp" name="codfacturatmp" value="<?php echo $codfacturatmp?>" type="hidden">
			  <input id="baseimpuestos2" name="baseimpuestos" value="<?php echo $baseimpuestos?>" type="hidden">
			  <input id="baseimponible2" name="baseimponible" value="" type="hidden">
			  <input id="preciototal2" name="preciototal" value="<?php echo $preciototal?>" type="hidden">
			  <input id="descuentogral" name="descuentogral" value="<?php echo $descuentogral?>" type="hidden">
			  <input id="observacion" name="observacion" value="<?php echo $observacion?>" type="hidden">
			  <input id="accion" name="accion" value="alta" type="hidden">
			  </form>
			  <br style="line-height:5px">
			  <div id="frmBusqueda">
				<form id="formulario_lineas" name="formulario_lineas" method="post" action="frame_lineas.php" target="frame_lineas">
				<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
				  <tr>
					<td >Codigo </td>
					<td colspan="2" valign="middle">
					<input type="hidden" name="codbarras" class="cajaMedia" id="codbarras" size="15" maxlength="15">
					<input type="text" size="26" maxlength="60" value="" id="articulos" autocomplete="off" class="cajaMedia" onBlur="validarArticulo();" data-index="6"/>
					 <img id="botonBusqueda" src="../img/ver.png" width="16" height="16" onClick="ventanaArticulos();" onMouseOver="style.cursor=cursor" title="Buscar articulo" style="vertical-align: middle; margin-top: -1px;">
					 <img id="botonBusqueda" src="../img/service.png" width="16" height="16" onClick="ventanaService();" onMouseOver="style.cursor=cursor" title="Buscar Service" style="vertical-align: middle; margin-top: -1px;">
					&nbsp;Descripcion</td>
					<td colspan="5" ><input name="descripcion" type="text" class="cajaGrande" id="descripcion" size="45" maxlength="50" data-index="7"></td>
					<td>Moneda&nbsp;&nbsp;&nbsp;</td>					 
					 <td colspan="2">
					 <input name="monedaShow" type="text" class="cajaPequena2" id="monedaShow" size="10" maxlength="10" readonly>&nbsp;&nbsp;&nbsp;
					 <input name="moneda"  id="moneda" type="hidden" >
					 </td>
				  </tr>
				  <tr>
  				  <?php if($shoedetalle==1) { ?>
					<td valign="top">Detalles</td>
					<td colspan="2"><textarea name="detalles" rows="2" cols="40" class="areaTexto" id="detalles" data-index="8"> </textarea></td>
					<?php } ?>
					<td colspan="2" valign="top" >Precio&nbsp;<input name="precio" type="text" class="cajaPequena2" id="precio" size="10" maxlength="10" onChange="actualizar_importe();" data-index="9"></td>
					<td colspan="2" valign="top" >Cantidad&nbsp;<input name="cantidad" type="text" class="cajaMinima" id="cantidad" size="10" maxlength="10" value="" data-index="10"></td>
					<td colspan="2" valign="top" >Dcto.&nbsp;<input name="descuento" type="text" class="cajaMinima" id="descuento" size="10" maxlength="10" onChange="actualizar_importe();" data-index="11"> %</td>
					<td colspan="2" valign="top" >Dcto.&nbsp;PP&nbsp;<input name="descuentopp" type="text" class="cajaMinima" id="descuentopp" size="10" maxlength="10" onChange="actualizar_importe();" data-index="12">&nbsp;%</td>
					<td colspan="2" valign="top">Comisión&nbsp;<input name="comision" type="text" class="cajaMinima" id="comision" size="10" maxlength="10" data-index="13" style="background-color: yellow;" >&nbsp;%</td>
					<td colspan="2" valign="top" >Importe&nbsp;<input name="importe" type="text" class="cajaPequena2" id="importe" size="10" maxlength="10" readonly data-index="14"></td>
					<td valign="top" align="right">
					<button class="boletin" onClick="validar();" onMouseOver="style.cursor=cursor"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Agregar</button>					
					</td>
					<td valign="top" align="right">
					<button class="boletin" onClick="limpiar();" onMouseOver="style.cursor=cursor"><i class="fa fa-eraser" aria-hidden="true"></i>&nbsp;Limpiar</button>
					</td>
				  </tr>
				</table>
<!--//fin del form incio-->

				</div>
				<input name="codarticulo" value="" type="hidden" id="codarticulo">
				<input name="codservice" value="" type="hidden" id="codservice">
				<br style="line-height:5px">
				<div id="frmBusqueda">
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=0 border=0 ID="Table1">

						<tr><td width="100%" colspan="11">
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
				<td valign="top" rowspan="3"><textarea id="observacionaux" rows="4" cols="40" class="areaTexto"><?php echo $observacionaux;?></textarea>
				</td>
				<td colspan="5"></td>
			    <td class="busqueda">Sub-total</td>
				<td align="left"><div align="left">
				 <input type="text" class="cajaPequena2" id="monShow" readonly>
			      <input class="cajaTotales" name="baseimponible" type="text" id="baseimponible" size="12" value=0 align="right" readonly> 
		        </div></td>
				</tr>
				<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td class="busqueda" >Descuento</td>
				<td class="busqueda" ><input id="descuentogralaux" name="descuentogral" value="0" class="cajaMinima" onChange="actualizo_descuento();"></td>
				<td class="busqueda">&nbsp;%</td>
				<td>	
				<input class="cajaTotales" name="baseimponibledescuento" type="text" id="baseimponibledescuento" size="12" value=0 align="right" readonly> 
				</td>
				<td class="busqueda">IVA</td>
				<td align="left"><div align="left">
				 <input type="text" class="cajaPequena2" id="monSho" readonly>
			      <input class="cajaTotales" name="baseimpuestos" type="text" id="baseimpuestos" size="12" align="right" value=0 readonly>
		        </div></td>
 				</tr>
				<tr>
				<td></td>
				<td>
				<br style="line-height:5px">
					<div align="center">
						<button class="boletin" onClick="validar_cabecera();" onMouseOver="style.cursor=cursor"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Guardar</button>
						<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Cancelar</button>
					  	<input id="codfamilia" name="codfamilia" value="<?php echo $codfamilia?>" type="hidden">
				    	<input id="codfacturatmp" name="codfacturatmp" value="<?php echo $codfacturatmp?>" type="hidden">	
						<input id="preciototal2" name="preciototal" value="<?php echo $preciototal?>" type="hidden">
						<input id="modif" name="modif" value="0" type="hidden">				    
					</div>
				</td>
				<td colspan="4"></td>
				<td class="busqueda">Precio&nbsp;Total</td>
				<td align="left"><div align="left">
				 <input type="text" class="cajaPequena2" id="monSh" readonly>
			      <input class="cajaTotales" name="preciototal" type="text" id="preciototal" size="12" align="right" value=0 readonly> 
		        </div></td>				
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


		<script type="text/javascript">
		cambio();
		</script>		
	</body>
</html>