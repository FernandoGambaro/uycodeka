<?php
include ("../conectar.php");
include ("../funciones/fechas.php"); 

$codalbaran=$_GET["codalbaran"];
$codproveedor=$_GET["codproveedor"];
$cadena_busqueda=$_GET["cadena_busqueda"];

$query="SELECT * FROM albaranesp WHERE codalbaran='$codalbaran' AND codproveedor='$codproveedor'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
$codproveedor=mysqli_result($rs_query, 0, "codproveedor");
$fecha=mysqli_result($rs_query, 0, "fecha");
$iva=mysqli_result($rs_query, 0, "iva");

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		function aceptar(codalbaran,codproveedor) {
			location.href="guardar_albaran.php?codalbaran=" + codalbaran + "&codproveedor=" + codproveedor + "&accion=baja" + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
		}
		
		function cancelar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
		</script>
	</head>

<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">ELIMINAR ALBARÁN</div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_albaran.php">				
				<table class="fuente8" width="98%" cellspacing="0" cellpadding="3" border="0">
						<tr>
							<td>C&oacute;digo&nbsp;Proveedor </td>
					      <td><input NAME="codproveedor" type="text" class="cajaPequena" id="codproveedor" size="6" maxlength="5"  value="<?php echo $codproveedor?>"></input>
					        </td>					
						  <td>Cod.&nbsp;albaran</td>
						  <td><input NAME="calbaran" type="text" class="cajaMedia" id="calbaran" size="20" maxlength="20" value="<?php echo $codalbaran?>"></input></td>
						<?php $hoy=date("d/m/Y"); ?>
							<td>Fecha</td>
						    <td width="27%"><input NAME="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" value="<?php echo implota($fecha)?>" readonly>
						    <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">//<![CDATA[
						   Calendar.setup({
						     inputField : "fecha",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide(); },
						     dateFormat : "%d/%m/%Y"
						   });
						//]]></script></td>		
				 		<td>Moneda</td><td width="26%">
						 <select onchange="cambio();" name="amoneda" id="amoneda" class="cajaPequena2">
							<?php if($moneda==1) { ?>						 
								<option value="1" selected="selected">Pesos</option>
								<option value="2">U$S</option>
							<?php } else { ?>
								<option value="1">Pesos</option>
								<option value="2" selected="selected">U$S</option>
							<?php } ?>						 

  							</select></td>											  
						</tr>
						<tr>
							<td>Nombre</td>
						    <td><input NAME="nombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" value="<?php echo $nombre?>" readonly></td>
				            <td>RUT</td>
				            <td><input NAME="nif" type="text" class="cajaMedia" id="nif" size="20" maxlength="15" value="<?php echo $nif?>" readonly></td>
							
				            <td>IVA</td>
				            <td><input NAME="iva" type="text" class="cajaPequena" id="iva" size="5" maxlength="5" value="<?php echo $iva;?>" onChange="cambio_iva();"> %</td>

							<td colspan="4">Tipo&nbsp;cambio
								<label>U$S -> $&nbsp;</label><span>
								<input NAME="tipocambio" type="text" class="cajaPequena2" id="tipocambio" size="5" maxlength="5" value="<?php echo $tipocambio; ?>" readonly=""></span>
							</td>
						</tr>
					</table>										
			  </div>
			  <input id="codalbarantmp" name="codalbarantmp" value="<?php echo $codalbarantmp?>" type="hidden">
			  <input id="codalbaran" name="codalbaran" value="<?php echo $codalbaran?>" type="hidden">
			  <input id="baseimpuestos2" name="baseimpuestos" value="<?php echo $baseimpuestos?>" type="hidden">
			  <input id="baseimponible2" name="baseimponible" value="<?php echo $baseimponible?>" type="hidden">
			  <input id="preciototal2" name="preciototal" value="<?php echo $preciototal?>" type="hidden">
			  <input id="accion" name="accion" value="modificar" type="hidden">			  
			  </form>
			  <br>
				<div id="frmBusqueda">
				<form id="formulario_lineas" name="formulario_lineas" method="post" action="frame_lineas.php" target="frame_lineas">
				<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0>
				  <tr>
					<td width="11%">Codigo barras </td>
					<td valign="middle"><input NAME="codbarras" type="text" class="cajaMedia" id="codbarras" size="15" maxlength="15">
					 <img id="botonBusqueda" src="../img/calculadora.jpg" border="1" align="absmiddle" onClick="validarArticulo();" onMouseOver="style.cursor=cursor" title="Validar codigo de barras" style="vertical-align: middle; margin-top: -1px;">
					 <img id="botonBusqueda" src="../img/ver.png" width="16" height="16" onClick="ventanaArticulos();" onMouseOver="style.cursor=cursor" title="Buscar articulo" style="vertical-align: middle; margin-top: -1px;"></td>
					<td>Descripcion</td>
					<td colspan="5" ><input NAME="descripcion" type="text" class="cajaGrande" id="descripcion" size="30" maxlength="30" readonly></td>
					<td>Moneda</td>					 
					 <td colspan="2">
					 <input NAME="monedaShow" type="text" class="cajaPequena2" id="monedaShow" size="10" maxlength="10" readonly>
					 <input NAME="moneda" id="moneda" type="hidden" >
					 </td>
				  </tr>
				  <tr>
					<td valign="top">Detalles</td>
					<td><textarea name="detalles" rows="2" cols="50" class="areaTexto" id="detalles"> </textarea>
					</td>
					<td width="5%">Precio</td>
					<td width="11%"><input NAME="precio" type="text" class="cajaPequena2" id="precio" size="10" maxlength="10" onChange="actualizar_importe()"></td>
					<td width="5%">Cantidad</td>
					<td width="5%"><input NAME="cantidad" type="text" class="cajaMinima" id="cantidad" size="10" maxlength="10" value="1" onChange="actualizar_importe()"></td>
					<td width="4%">Dcto.</td>
					<td width="9%"><input NAME="descuento" type="text" class="cajaMinima" id="descuento" size="10" maxlength="10" onChange="actualizar_importe()"> %</td>
					<td width="5%">Importe</td>
					<td width="11%"><input NAME="importe" type="text" class="cajaPequena2" id="importe" size="10" maxlength="10" value="0" readonly></td>
					<td width="15%"><img id="botonBusqueda" src="../img/botonagregar.jpg" width="72" height="22" border="1" onClick="validar()" onMouseOver="style.cursor=cursor" title="Agregar articulo"></td>
				  </tr>
				</table>
				</div>
				<input type="hidden" name="codarticulo" id="codarticulo" value="<?php echo $codarticulo?>">
				<br>
				<div id="frmBusqueda">
				<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="5%">ITEM</td>
							<td width="20%">REFERENCIA</td>
							<td width="39%">DESCRIPCION</td>
							<td width="8%">CANTIDAD</td>
							<td width="8%">PRECIO</td>
							<td width="7%">DCTO %</td>
							<td width="8%">IMPORTE</td>
							<td width="3%">&nbsp;</td>
						</tr>
				</table>
					<iframe width="100%" height="200" id="frame_lineas" name="frame_lineas" frameborder="0">
						<ilayer width="100%" height="200" id="frame_lineas" name="frame_lineas"></ilayer>
					</iframe>				
			  </div>
			  <div id="frmBusqueda">
			  

<div id="frmBusqueda">
			<table width="25%" border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
			  <tr>
			    <td width="27%" class="busqueda">Sub-total</td>
				<td width="73%" align="right"><div align="center">
				 <input type="text" class="cajaPequena2" id="monShow" readonly>
			      <input class="cajaTotales" name="baseimponible" type="text" id="baseimponible" size="12" value=0 align="right" value="<?php echo number_format($baseimponible,2)?>" readonly> 
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
				  <div align="center">
				    <img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="validar_cabecera()" border="1" onMouseOver="style.cursor=cursor">
				    <input id="codfamilia" name="codfamilia" value="<?php echo $codfamilia?>" type="hidden">
				    <input id="codalbarantmp" name="codalbarantmp" value="<?php echo $codalbarantmp?>" type="hidden">
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
