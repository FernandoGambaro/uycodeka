<?php
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 
date_default_timezone_set("America/Montevideo"); 

$codalbaran=$_GET["codalbaran"];
$cadena_busqueda=$_GET["cadena_busqueda"];

$sel_alb="SELECT * FROM albaranes WHERE codalbaran='$codalbaran'";
$rs_alb=mysqli_query($GLOBALS["___mysqli_ston"], $sel_alb);
$codcliente=mysqli_result($rs_alb, 0, "codcliente");
$iva=mysqli_result($rs_alb, 0, "iva");
$fecha=mysqli_result($rs_alb, 0, "fecha");
$tipo=mysqli_result($rs_alb, 0, "tipo");
$moneda=mysqli_result($rs_alb, 0, "moneda");

$descuentogral=mysqli_result($rs_alb, 0, "descuento");

$fechaentrega=mysqli_result($rs_alb, 0, "fechaentrega");
$lugar=mysqli_result($rs_alb, 0, "lugar");
$solicitado=mysqli_result($rs_alb, 0, "solicitado");
$observacion=mysqli_result($rs_alb, 0, "observacion");

$baseimponible=0;
?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
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
			/*location.href="index.php";*/
		}
		
		function imprimir(codalbaran) {
			window.open("../fpdf/imprimir_albaran.php?codalbaran="+codalbaran);
		}
		function inicio() {
			var fecha=$("#fecha").val();
				$.post("busco_tipocambio.php?fecha="+fecha,  function(data){
				$("#tipocambio").val(data);
			})(jQuery);	
		}		
		</script>
	</head>
	<body onload="inicio();">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">VER ORDEN DE COMPRA </div>

				<div id="frmBusqueda">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0>
						<?php 
						 $sel_cliente="SELECT * FROM clientes WHERE codcliente='$codcliente'"; 
						  $rs_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cliente); ?>					
						<tr>
							<td >C&oacute;digo&nbsp;Cliente </td>
					      <td><input NAME="codcliente" type="text" class="cajaPequena" id="codcliente" size="6" maxlength="5" onClick="limpiarcaja();" value="<?php echo $codcliente?>">
					       </td>
							<td>Nombre</td>
						    <td colspan="2"><input NAME="nombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" value="<?php echo mysqli_result($rs_cliente, 0, "nombre");?>" readonly></td>
						    <td colspan="3">Solicitado&nbsp;por
						    <input NAME="solicitado" type="text" class="cajaGrande" id="solicitado" size="45" value="<?php echo $solicitado?>" maxlength="45"></td>
						</tr>
						<tr>
				            <td >RUT</td>
				            <td><input NAME="nif" type="text" class="cajaMedia" id="nif" size="20" maxlength="15" value="<?php echo mysqli_result($rs_cliente, 0, "nif");?>" readonly></td>
								<td>Tipo</td>
				            <td>
				            <select id="tipo" name="atipo" class="cajaPequena">

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
				            <td ><input NAME="iva" type="text" class="cajaPequena" id="iva" size="5" maxlength="5" onChange="cambio_iva();" value="<?php echo $iva?>"> %</td>
								
						<td>Moneda&nbsp;

						<select onchange="cambio();" name="amoneda" id="Amoneda" class="cajaPequena2">
						<?php $tipofa = array(  0=>"Seleccione uno",  1=>"Pesos", 2=>"U\$S");
						foreach ($tipofa as $key => $i ) {
						  	if ( $moneda==$key ) {
								echo "<OPTION value=$key selected>$i</option>";
								$monsh=$i;
							} else {
								echo "<OPTION value=$key>$i</option>";
							}
	
						}
						?>
						</select>						
						</td>
				            <td>Tipo&nbsp;cambio
								<label>U$S -> $&nbsp;</label><span>
								<input NAME="tipocambio" type="text" class="cajaPequena2" id="tipocambio" size="5" maxlength="5" ></span>
								</td>
						</tr>
						<tr>
							<td>Fecha</td><td>
							<?php $hoy=date("d/m/Y"); ?>
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
				            <td >							<?php $hoy=date("d/m/Y"); ?>
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
				            <td colspan="2">Lugar&nbsp;de&nbsp;Entrega
								
								<input name="lugar" type="text" class="cajaGrande" id="lugar" size="25" maxlength="100" value="<?php echo $lugar;?>" ></span>
								</td>
						</tr>
					</table>				
			  </div>	
				
				<div id="frmBusqueda">
					 <table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="5%">ITEM</td>
							<td width="25%">REFERENCIA</td>
							<td width="30%">DESCRIPCION</td>
							<td width="10%">CANTIDAD</td>
							<td width="10%">PRECIO</td>
							<td width="10%">DCTO %</td>
							<td width="10%">IMPORTE</td>
						</tr>
					</table>
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
					  <?php $sel_lineas="SELECT albalinea.*,articulos.*,familias.nombre as nombrefamilia FROM albalinea,articulos,familias WHERE albalinea.codalbaran='$codalbaran' AND albalinea.codigo=articulos.codarticulo AND albalinea.codfamilia=articulos.codfamilia AND articulos.codfamilia=familias.codfamilia ORDER BY albalinea.numlinea ASC";
$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
						for ($i = 0; $i < mysqli_num_rows($rs_lineas); $i++) {
							$numlinea=mysqli_result($rs_lineas, $i, "numlinea");
							$codfamilia=mysqli_result($rs_lineas, $i, "codfamilia");
							$nombrefamilia=mysqli_result($rs_lineas, $i, "nombrefamilia");
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
										<td width="25%"><?php echo $referencia?></td>
										<td width="30%"><?php echo $descripcion?></td>
										<td width="10%" class="aCentro"><?php echo $cantidad?></td>
										<td width="10%" class="aCentro"><?php echo $precio?></td>
										<td width="10%" class="aCentro"><?php echo $descuento?></td>
										<td width="10%" class="aCentro"><?php echo $importe?></td>
									</tr>
									<?php $baseimponible=$baseimponible+$importe; ?>
					<?php } ?>
					</table>
			  </div>
			  <?php $baseimpuestos=$baseimponible*($iva/100);
				$preciototal=$baseimponible+$baseimpuestos;
				$preciototal=number_format($preciototal,2); ?>
					<div id="frmBusqueda">
					

<table border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
				<tr>
				<td valign="top">Observaciones</td>
				<td valign="top" rowspan="3"><textarea id="observacionaux" rows="4" cols="40"><?php echo $observacion;?></textarea>
				</td>
				<td colspan="5"></td>
			    <td class="busqueda">Sub-total</td>
				<td align="left"><div align="left">
				 <input type="text" class="cajaPequena2" id="monShow" value="<?php echo $monsh;?>" readonly>
			      <input class="cajaTotales" name="baseimponible" type="text" id="baseimponible" size="12" value="<?php echo number_format($baseimponible,2);?>" align="right" readonly> 
		        </div></td>
				</tr>
				<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td class="busqueda" >Descuento</td>
				<td class="busqueda" ><input id="descuentogralaux" name="descuentogral" value="<?php echo $descuentogral;?>" class="cajaMinima"></td>
				<td class="busqueda">&nbsp;%</td>
				<td>	
				<input class="cajaTotales" name="baseimponibledescuento" type="text" id="baseimponibledescuento" size="12" value=0 align="right" readonly> 
				</td>
				<td class="busqueda">IVA</td>
				<td align="left"><div align="left">
				 <input type="text" class="cajaPequena2" id="monSho" value="<?php echo $monsh;?>" readonly>
			      <input class="cajaTotales" name="baseimpuestos" type="text" id="baseimpuestos" size="12" align="right" value="<?php echo number_format($baseimpuestos,2);?>" readonly>
		        </div></td>
 				</tr>
				<tr>
				<td></td>
				<td>
				</td>
				<td colspan="4"></td>
				<td class="busqueda">Precio&nbsp;Total</td>
				<td align="left"><div align="left">
				 <input type="text" class="cajaPequena2" id="monSh" value="<?php echo $monsh;?>" readonly>
			      <input class="cajaTotales" name="preciototal" type="text" id="preciototal" size="12" align="right" value="<?php echo $preciototal?>" readonly>
		        </div></td>				
				</tr> 				
				</table>
			  </div>
				<div>
					<div align="center">
					 <img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar();" border="1" onMouseOver="style.cursor=cursor">
					 <img id="botonBusqueda" src="../img/botonimprimir.jpg" width="79" height="22" border="1" onClick="imprimir(<?php echo $codalbaran?>);" onMouseOver="style.cursor=cursor">
				   </div>
					</div>
			  </div>
		  </div>
		</div>
	</body>
</html>
