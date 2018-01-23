<?php 
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 
date_default_timezone_set("America/Montevideo"); 

$baseimponible=0;

$codalbaran=$_GET["codalbaran"];
$sel_alb="SELECT * FROM albaranes WHERE codalbaran='$codalbaran'";
$rs_alb=mysqli_query($GLOBALS["___mysqli_ston"], $sel_alb);
$codcliente=mysqli_result($rs_alb, 0, "codcliente");
$iva=mysqli_result($rs_alb, 0, "iva");
$fecha=mysqli_result($rs_alb, 0, "fecha");
$tipo=mysqli_result($rs_alb, 0, "tipo");
$moneda=mysqli_result($rs_alb, 0, "moneda");

$fechaentrega=mysqli_result($rs_alb, 0, "fechaentrega");
$lugar=mysqli_result($rs_alb, 0, "lugar");
$solicitado=mysqli_result($rs_alb, 0, "solicitado");
$descuentogral=mysqli_result($rs_alb, 0, "descuento");
$observacion=mysqli_result($rs_alb, 0, "observacion");
$codformapago=mysqli_result($rs_alb, 0, "codformapago");

$sel_cliente="SELECT nombre,nif FROM clientes WHERE codcliente='$codcliente'";
$rs_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cliente);
$nombre=mysqli_result($rs_cliente, 0, "nombre");
$nif=mysqli_result($rs_cliente, 0, "nif");

$fechahoy=date("Y-m-d");
/*$sel_albaran="INSERT INTO albaranestmp (codalbaran,fecha) VALUE ('','$fechahoy')";
$rs_albaran=mysql_query($sel_albaran);
$codalbarantmp=mysql_insert_id();
*/
$sel_borrar = "DELETE FROM albalineatmp WHERE codalbaran='$codalbaran'";
$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);

$codalbarantmp=$codalbaran;

$sel_lineas="SELECT * FROM albalinea WHERE codalbaran='$codalbaran' ORDER BY numlinea ASC";
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
	$detalles=mysqli_result($rs_lineas, $contador, "detalles");

	$contador++;
	$sel_tmp="INSERT INTO albalineatmp (codalbaran,numlinea,codfamilia,detalles,codigo,cantidad,precio,importe,dcto) 
	VALUES ('$codalbarantmp','','$codfamilia','$detalles','$codigo','$cantidad','$precio','$importe','$dcto')";
	$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tmp);

}

$baseimpuestos=$baseimponible*($iva/100);
$preciototal=$baseimponible+$baseimpuestos;
//$preciototal=number_format($preciototal,2);
?>
<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="../funciones/validar.js"></script>
		
    <script src="../calendario/jscal2.js"></script>
    <script src="../calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />
    
			<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="js/colorbox.css" />
		<script src="js/jquery.colorbox.js"></script>		
<!--///////////////////////////////////////////////////////-->

<script type="text/javascript">
$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

		$(".callbacks").colorbox({
			iframe:true, width:"99%", height:"98%",
			onCleanup:function(){ window.location.reload();	}
		});

});
</script>
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
}

function pon_prefijo_Fb (codfamilia,pref,referencia,nombre,precio,codarticulo,moneda) {
	var monArray = new Array();
	monArray[0]="Selecione uno";
	monArray[1]="Pesos";
	monArray[2]="U\$S";
	$("#codfamilia").val(codfamilia);
	$("#codbarras").val(pref);
	$("#referencia").val(referencia);
	$("#descripcion").val(nombre);
	$("#precio").val(precio);
	$("#moneda").val(moneda);
	$("#monedaShow").val(monArray[moneda]);
	$("#importe").val(precio);
	$("#codarticulo").val(codarticulo);
	$('idOfDomElement').colorbox.close();
	actualizar_importe();
}

function pon_baseimponible(baseimponible) {
	$("#baseimponible").val(baseimponible);
	actualizo_descuento();
}
</script>			
		
		<script language="javascript">
		function cancelar() {
			parent.$('idOfDomElement').colorbox.close();
			/*location.href="index.php";*/
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
			document.getElementById("modif").value=1;
			document.formulario_lineas.submit();
			document.getElementById("modif").value=0;
			var fecha=$("#fecha").val();
				$.post("busco_tipocambio.php?fecha="+fecha,  function(data){
				$("#tipocambio").val(data);
			})(jQuery);				
		}			
		
		function ventanaArticulos(){
			var codigo=document.getElementById("codcliente").value;
			if (codigo=="") {
				alert ("Debe introducir el codigo del cliente");
			} else {

				var moneda=document.getElementById("Amoneda").value;
				if (moneda=='0') {
					alert ("Debe seleccionar moneda");
				} else {
					$.colorbox({href:"ver_articulos.php",
					iframe:true, width:"95%", height:"95%",
					});
				}
			}
		}
		function ventanaService(){
			var codigo=document.getElementById("codcliente").value;
			if (codigo=="") {
				alert ("Debe introducir el codigo del cliente");
			} else {
				var moneda=document.getElementById("Amoneda").value;
				if (moneda=='0') {
					alert ("Debe seleccionar moneda");
				} else {
					$.colorbox({href:"ver_service.php?codcliente="+codigo,
					iframe:true, width:"95%", height:"95%",
					});
				}
			}
		}		
		
		function validarcliente(){
			var codigo=document.getElementById("codcliente").value;
				$.colorbox({href:"comprobarcliente.php?codcliente="+codigo,
				iframe:true, width:"95%", height:"95%",
				
				});
		}	
		
		function validarArticulo() {
			var codbarras=document.getElementById("codbarras").value;
				if (codbarras!="") {
				$.colorbox({href:"comprobararticulos.php?codbarras="+codbarras,
				iframe:true, width:"95%", height:"95%",
				});
				}			
		}		
		
		function limpiarcaja() {
			document.getElementById("codcliente").value="";
			document.getElementById("nombre").value="";
			document.getElementById("nif").value="";
		}
		
		function actualizar_importe()
			{
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
				var mensaje="";
				if (document.getElementById("nombre").value=="") mensaje+="  - Nombre\n";
				if (document.getElementById("fecha").value=="") mensaje+="  - Fecha\n";
				if (mensaje!="") {
					alert("Atencion, se han detectado las siguientes incorrecciones:\n\n"+mensaje);
				} else {
					document.getElementById("descuentogral").value=document.getElementById("descuentogralaux").value;
					document.getElementById("observacion").value=document.getElementById("observacionaux").value;
					document.getElementById("formulario").submit();
				}
			}	
		
		function validar() 
			{
				var mensaje="";
				var entero=0;
				var enteroo=0;
		
				if (document.getElementById("codbarras").value=="") mensaje="  - Codigo de barras\n";
				if (document.getElementById("descripcion").value=="") mensaje+="  - Descripcion\n";
				if (document.getElementById("precio").value=="") { 
							mensaje+="  - Falta el precio\n"; 
						} else {
							if (isNaN(document.getElementById("precio").value)==true) {
								mensaje+="  - El precio debe ser numerico\n";
							}
						}
				if (document.getElementById("cantidad").value=="") 
						{ 
						mensaje+="  - Falta la cantidad\n";
						} else {
							enteroo=parseInt(document.getElementById("cantidad").value);
							if (isNaN(enteroo)==true) {
								mensaje+="  - La cantidad debe ser numerica\n";
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
								mensaje+="  - El descuento debe ser numerico\n";
							} else {
								document.getElementById("descuento").value=entero;
							}
						} 
				if (document.getElementById("importe").value=="") mensaje+="  - Falta el importe\n";
				
				if (mensaje!="") {
					alert("Atencion, se han detectado las siguientes incorrecciones:\n\n"+mensaje);
				} else {
					var descuentogral=document.getElementById("descuentogralaux").value;
					document.getElementById("baseimponible").value=parseFloat(document.getElementById("baseimponible").value) + parseFloat(document.getElementById("importe").value);
					
					document.getElementById("baseimponibledescuento").value=	Math.round((parseFloat(document.getElementById("baseimponible").value) / (1+descuentogral/100))*100)/100;
					cambio_iva();
					document.getElementById("formulario_lineas").submit();
					document.getElementById("codbarras").value="";
					document.getElementById("detalles").value="";
					document.getElementById("descripcion").value="";
					document.getElementById("precio").value="";
					document.getElementById("cantidad").value=1;
					document.getElementById("moneda").value="";
					document.getElementById("monedaShow").value="";
					document.getElementById("importe").value="";
					document.getElementById("descuento").value=0;						
				}
			}
			
		function cambio_iva() {
			var original=parseFloat(document.getElementById("baseimponible").value);

			var result=Math.round(original*100)/100 ;
			document.getElementById("baseimponible").value=result;

			var descuentogral=document.getElementById("descuentogralaux").value;
			if (descuentogral=0) {
			var original=parseFloat(document.getElementById("baseimponible").value);
			} else {
			var original=parseFloat(document.getElementById("baseimponibledescuento").value);
			}
			var result=Math.round(original*100)/100 ;
	
			document.getElementById("baseimpuestos").value=parseFloat(result * parseFloat(document.getElementById("iva").value / 100));
			var original1=parseFloat(document.getElementById("baseimpuestos").value);
			var result1=Math.round(original1*100)/100 ;
			document.getElementById("baseimpuestos").value=result1;
			var original2=parseFloat(result + result1);
			var result2=Math.round(original2*100)/100 ;
			document.getElementById("preciototal").value=result2;
		}	

		function actualizo_descuento() {
				var descuentogral=parseFloat(document.getElementById("descuentogralaux").value);
				document.getElementById("baseimponibledescuento").value=	Math.round((parseFloat(document.getElementById("baseimponible").value) * (1-descuentogral/100))*100)/100;
				cambio_iva();
		}		
		
		function busco_tipocambio() {
			var fecha=$("#fecha").val();
				$.post("busco_tipocambio.php?fecha="+fecha,  function(data){
				$("#tipocambio").val(data);
			})(jQuery);			
	 		
		}		
		var tipoaux='';
		function cambio() {
			var Index = document.formulario.Amoneda.options[document.formulario.Amoneda.selectedIndex].value;
			var monArray = new Array();
			monArray[0]="";
			monArray[1]="Pesos";
			monArray[2]="U\$S";
			$("#monShow").val(monArray[Index]);
			$("#monSho").val(monArray[Index]);
			$("#monSh").val(monArray[Index]);

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
	
	</head>

<!--///////////////////////////////////////////////////////-->		
		
	</head>
	<body onLoad="inicio()">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">Modificar orden de pedido Nº <?php echo $codalbaran;?></div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_albaran.php">
				

					<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td >C&oacute;digo&nbsp;Cliente </td>
					      <td><input NAME="codcliente" type="text" class="cajaPequena" id="codcliente" size="6" maxlength="5" onClick="limpiarcaja();" value="<?php echo $codcliente?>">
					      <img id="botonBusqueda" src="../img/ver.png" width="16" height="16" onClick="OpenNote('ventana_clientes.php',700,450);" title="Buscar cliente" onMouseOver="style.cursor=cursor" style="vertical-align: middle; margin-top: -1px;">
					       <img id="botonBusqueda" src="../img/cliente.png" width="16" height="16" onClick="validarcliente()" title="Validar cliente" onMouseOver="style.cursor=cursor" style="vertical-align: middle; margin-top: -1px;"></td>
							<td>Nombre</td>
						    <td colspan="6"><input NAME="nombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" value="<?php echo $nombre?>" readonly>
						    &nbsp;Solicitado&nbsp;por
						    <input NAME="solicitado" type="text" class="cajaGrande" id="solicitado" size="45" value="<?php echo $solicitado?>" maxlength="45"></td>
						</tr>
						<tr>
				            <td >RUT</td>
				            <td><input NAME="nif" type="text" class="cajaMedia" id="nif" size="20" maxlength="15" value="<?php echo $nif?>" readonly></td>
								<td>Tipo</td>
				            <td>
				            <select id="tipo" name="tipo" class="cajaPequena">

<?php $tipof = array(0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
					if ($tipo==" ")
					{
					echo '<OPTION value="" selected>Selecione uno</option>';
					}
					$x=0;
					$NoEstado=0;
					foreach($tipof as $i) {
					  	if ( $x==$tipo) {
							echo "<OPTION value=$x selected>$i</option>";
							$NoEstado=1;
						} else {
							echo "<OPTION value=$x>$i</option>";
						}
						$x++;
					}
					?>
								</select></td>
				            <td>IVA</td>
				            <td><input NAME="iva" type="text" class="cajaMinima" id="iva" size="5" maxlength="5" onChange="cambio_iva();" value="<?php echo $iva?>"> %</td>
								<td>Forma&nbsp;de&nbsp;pago&nbsp;
														<?php
					  	$query_fp="SELECT * FROM formapago WHERE borrado=0 ORDER BY nombrefp ASC";
						$res_fp=mysqli_query($GLOBALS["___mysqli_ston"], $query_fp);
						$contador=0;
					  ?>
								<select id="codformapago" name="codformapago" class="comboMedio">
							
								<option value="0">Seleccione una</option>
								<?php
								while ($contador < mysqli_num_rows($res_fp)) { 
									if ($codformapago==mysqli_result($res_fp, $contador, "codformapago")) { ?>
								<option value="<?php echo mysqli_result($res_fp, $contador, "codformapago");?>" selected><?php echo mysqli_result($res_fp, $contador, "nombrefp")?></option>
								<?php	} else {
								?>
								<option value="<?php echo mysqli_result($res_fp, $contador, "codformapago");?>"><?php echo mysqli_result($res_fp, $contador, "nombrefp")?></option>
								<?php 
									}
								 $contador++;
								} ?>				
								</select>					  
								
								&nbsp;Moneda&nbsp;

						<select onchange="cambio();" name="amoneda" id="Amoneda" class="cajaPequena2">
						<?php $tipofa = array(  0=>"Seleccione uno",  1=>"Pesos", 2=>"U\$S");
						foreach ($tipofa as $key => $i ) {
						  	if ( $moneda==$key ) {
								echo "<OPTION value=$key selected>$i</option>";
							} else {
								echo "<OPTION value=$key>$i</option>";
							}
	
						}
						?>
						</select>						
						</td>
				            <td>Tipo&nbsp;cambio
								<label>U$S -> $&nbsp;</label><span>
								<input NAME="tipocambio" type="text" class="cajaPequena2" id="tipocambio" size="5" maxlength="5" value="1" ></span>
								</td>
						</tr>
						<tr>
							<td>Fecha</td><td>
							
						    <input NAME="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" value="<?php echo implota($fecha)?>" readonly> 
						    <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">//<![CDATA[
						   Calendar.setup({
						     inputField : "fecha",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide() },
						     dateFormat : "%d/%m/%Y"
						   });
						//]]></script></td>
				            <td>Fecha Entrega</td>
				            <td >							
						    <input NAME="fechaentrega" type="text" class="cajaPequena" id="fechaentrega" size="10" maxlength="10" value="<?php echo implota($fechaentrega)?>" readonly> 
						    <img src="../img/calendario.png" name="Image2" id="Image2" width="16" height="16" border="0"  onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">//<![CDATA[
						   Calendar.setup({
						     inputField : "fechaentrega",
						     trigger    : "Image2",
						     align		 : "Bl",
						     onSelect   : function() { this.hide() },
						     dateFormat : "%d/%m/%Y"
						   });
						//]]></script></td>
				            <td colspan="3">Lugar&nbsp;de&nbsp;Entrega
								
								<input name="lugar" type="text" class="cajaGrande" id="lugar" size="25" maxlength="100" value="<?php echo $lugar;?>" ></span>
								</td>
						</tr>
					</table>				
													
			  </div>			  
			  <input id="codalbarantmp" name="codalbarantmp" value="<?php echo $codalbarantmp?>" type="hidden">
			  <input id="codalbaran" name="codalbaran" value="<?php echo $codalbaran?>" type="hidden">
			  <input id="baseimpuestos2" name="baseimpuestos" value="<?php echo $baseimpuestos?>" type="hidden">
			  <input id="baseimponible2" name="baseimponible" value="<?php echo $baseimponible?>" type="hidden">
			  <input id="preciototal2" name="preciototal" value="<?php echo $preciototal?>" type="hidden">
			  <input id="descuentogral" name="descuentogral" value="<?php echo $descuentogral;?>" type="hidden">
			  <input id="observacion" name="observacion" value="<?php echo $observacion;?>" type="hidden">			  
			  <input id="accion" name="accion" value="modificar" type="hidden">			  
			  </form>
			  <br style="line-height:5px">
				<div id="frmBusqueda">
				<form id="formulario_lineas" name="formulario_lineas" method="post" action="frame_lineas.php" target="frame_lineas">
				<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0>
				  <tr>
					<td width="11%">Codigo </td>
					<td colspan="3" valign="middle"><input NAME="codbarras" type="text" class="cajaMedia" id="codbarras" size="15" maxlength="15">
					 <img id="botonBusqueda" src="../img/ver.png" width="16" height="16" onClick="ventanaArticulos();" onMouseOver="style.cursor=cursor" title="Buscar articulo" style="vertical-align: middle; margin-top: -1px;">
					<td>Descripcion</td>
					<td colspan="3" ><input NAME="descripcion" type="text" class="cajaMedia" id="descripcion" size="30" maxlength="30" readonly></td>
					 
					<td>Moneda </td>					 
					 <td colspan="2">
					 <input NAME="monedaShow" type="text" class="cajaPequena2" id="monedaShow" size="10" maxlength="10" readonly>
					 <input NAME="moneda"  id="moneda" type="hidden" >
					 </td>
				  </tr>
				  <tr>
					<td valign="top">Detalles</td>
					<td ><textarea name="detalles" rows="2" cols="50" class="areaTexto" id="detalles"> </textarea>
					</td>
					<td width="5%">Precio</td>
					<td width="11%"><input NAME="precio" type="text" class="cajaPequena2" id="precio" size="10" maxlength="10" onChange="actualizar_importe();"></td>
					<td width="5%">Cantidad</td>
					<td width="5%"><input NAME="cantidad" type="text" class="cajaMinima" id="cantidad" size="10" maxlength="10" value="1" onChange="actualizar_importe();"></td>
					<td width="4%">Dcto.</td>
					<td width="9%"><input NAME="descuento" type="text" class="cajaMinima" id="descuento" size="10" maxlength="10" onChange="actualizar_importe();"> %</td>
					<td width="5%">Importe</td>
					<td width="11%"><input NAME="importe" type="text" class="cajaPequena2" id="importe" size="10" maxlength="10" value="0" readonly></td>
					<td width="15%"><img id="botonBusqueda" src="../img/botonagregar.jpg" width="72" height="22" border="1" onClick="validar();" onMouseOver="style.cursor=cursor" title="Agregar articulo"></td>
				  </tr>
				</table>
				</div>
				<br>
				<div id="frmBusqueda">
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="5%">ITEM</td>
							<td width="20%">REFERENCIA</td>
							<td width="39%" class="aIzquierda">DESCRIPCION</td>
							<td width="8%">CANTIDAD</td>
							<td width="8%">PRECIO</td>
							<td width="7%">DCTO %</td>
							<td width="7%">MONEDA</td>
							<td width="8%">IMPORTE</td>
							<td width="30px">&nbsp;ACCION</td>
						</tr>
					<tr><td width="100%" colspan="10">
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
				<td valign="top" rowspan="3"><textarea id="observacionaux" rows="4" cols="40"><?php echo $observacion;?></textarea>
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
				<td class="busqueda" ><input id="descuentogralaux" name="descuentogral" value="<?php echo $descuentogral;?>" class="cajaMinima" onChange="actualizo_descuento();"></td>
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
					<div align="center">
				   	<img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="validar_cabecera();" border="1" onMouseOver="style.cursor=cursor">
						<img id="botonBusqueda" src="../img/botoncancelar.jpg" width="85" height="22" onClick="cancelar();" border="1" onMouseOver="style.cursor=cursor">
				    	<input id="codfamilia" name="codfamilia" value="<?php echo $codfamilia;?>" type="hidden">
						<input id="preciototal2" name="preciototal" value="<?php echo $preciototal;?>" type="hidden">
						<input id="modif" name="modif" value="0" type="hidden">				    
					</div>
				</td>
				<td colspan="4"></td>
				<td class="busqueda">Precio&nbsp;Total</td>
				<td align="left"><div align="left">
				 <input type="text" class="cajaPequena2" id="monSh" readonly>
			      <input class="cajaTotales" name="preciototal" type="text" id="preciototal" size="12" align="right" value=0 readonly>
				   <input id="codalbarantmp" name="codalbarantmp" value="<?php echo $codalbarantmp;?>" type="hidden">				    
			       
		        </div></td>				
				</tr> 				
				</table>
				</td>
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
