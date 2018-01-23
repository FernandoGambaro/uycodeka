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

$sel_cliente="SELECT nombre,nif FROM proveedores WHERE codproveedor='$codproveedor'";
$rs_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cliente);
$nombre=mysqli_result($rs_cliente, 0, "nombre");
$nif=mysqli_result($rs_cliente, 0, "nif");

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

		<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="js/colorbox.css" />
		<script src="js/jquery.colorbox.js"></script>	
		<script language="javascript">
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		function aceptar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
		function imprimir(codalbaran,codproveedor) {
			location.href="../fpdf/imprimir_albaran_proveedor.php?codalbaran="+codalbaran+"&codproveedor="+codproveedor;
		}
		function inicio() {
			$.post("busco_tipocambio.php?fecha="+fecha,  function(data){
				$("#tipocambio").val(data);
			})(jQuery);
		}
		</script>
	</head>
<body onload="inicio();" >
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">MODIFICAR albaran</div>
				<div id="frmBusqueda">
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
				
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
					  <?php
					  $baseimponible=0;
					  $sel_lineas="SELECT * FROM albalineap,articulos WHERE albalineap.codalbaran='$codalbaran' AND albalineap.codproveedor='$codproveedor' AND albalineap.codigo=articulos.codarticulo AND albalineap.codfamilia=articulos.codfamilia ORDER BY albalineap.numlinea ASC";
						$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
						for ($i = 0; $i < mysqli_num_rows($rs_lineas); $i++) {
							$numlinea=mysqli_result($rs_lineas, $i, "numlinea");
							$codfamilia=mysqli_result($rs_lineas, $i, "codfamilia");
							$codarticulo=mysqli_result($rs_lineas, $i, "codarticulo");
							$descripcion=mysqli_result($rs_lineas, $i, "descripcion");
							$referencia=mysqli_result($rs_lineas, $i, "referencia");
							$cantidad=mysqli_result($rs_lineas, $i, "cantidad");
							$precio=mysqli_result($rs_lineas, $i, "precio");
							$importe=mysqli_result($rs_lineas, $i, "importe");
							$descuento=mysqli_result($rs_lineas, $i, "dcto");
							if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>
									<tr class="<?php echo $fondolinea?>">
										<td width="5%" class="aCentro"><?php echo $i+1?></td>
										<td width="20%"><?php echo $referencia?></td>
										<td width="40%"><?php echo $descripcion?></td>
										<td width="8%" class="aCentro"><?php echo $cantidad?></td>
										<td width="10%" class="aCentro"><?php echo $precio?></td>
										<td width="7%" class="aCentro"><?php echo $descuento?></td>
										<td width="10%" class="aCentro"><?php echo $importe?></td>
									</tr>
									<?php $baseimponible=$baseimponible+$importe; ?>
					<?php } ?>
					</table>
			  </div>
			  <?php $baseimpuestos=$baseimponible*($iva/100);
				$preciototal=$baseimponible+$baseimpuestos;
				 ?>				


			<div id="frmBusqueda">
			<table width="25%" border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
			  <tr>
			    <td width="27%" class="busqueda">Sub-total</td>
				<td width="73%" align="right"><div align="center">
				 <input type="text" class="cajaPequena2" id="monShow" readonly>
			      <input class="cajaTotales" size="12" align="right" value="<?php echo number_format($baseimponible,2);?>" readonly> 
		        </div></td>
			  </tr>
			  <tr>
				<td class="busqueda">IVA</td>
				<td align="right"><div align="center">
				 <input type="text" class="cajaPequena2" id="monSho" readonly>
			      <input class="cajaTotales" name="baseimpuestos" type="text" id="baseimpuestos" size="12" align="right"  value="<?php echo number_format($baseimpuestos,2);?>" readonly>
		        </div></td>
			  </tr>
			  <tr>
				<td class="busqueda">Precio Total</td>
				<td align="right"><div align="center">
				 <input type="text" class="cajaPequena2" id="monSh" readonly>
			      <input class="cajaTotales" name="preciototal" type="text" id="preciototal" size="12" align="right" value="<?php echo number_format($preciototal,2);?>" readonly> 
		        </div></td>
			  </tr>
		</table>
			  </div>			  


			<div align="center">
			<img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar()" border="1" onMouseOver="style.cursor=cursor">
			 <img id="botonBusqueda" src="../img/botonimprimir.jpg" width="79" height="22" border="1" onClick="imprimir('<?php echo $codalbaran?>','<?php echo $codproveedor?>')" onMouseOver="style.cursor=cursor">
        </div>


			 </div>
		  </div>
		
	</body>	
	
	
</html>
