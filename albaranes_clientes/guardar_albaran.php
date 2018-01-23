<?php
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

$accion=$_POST["accion"];

if (!isset($accion)) { $accion=$_GET["accion"]; }

$codalbarantmp=$_POST["codalbarantmp"];
$codcliente=$_POST["codcliente"];
$fecha=explota($_POST["fecha"]);
$iva=$_POST["iva"];
$totalalbaran=$_POST["baseimponible"];
$moneda=$_POST["amoneda"];
$tipo=$_POST["tipo"];
$fechaentrega=explota($_POST["fechaentrega"]);
$lugar=$_POST["lugar"];
$solicitado=$_POST["solicitado"];
$observacion=$_POST["observacion"];
$descuento=$_POST["descuentogral"];
$codformapago=$_POST["codformapago"];

$minimo=0;

$query="SELECT * FROM tipocambio WHERE fecha<='$fecha' order by `fecha` DESC";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
$tipocambio=mysqli_result($rs_query, 0, "valor");

if ($accion=="alta") {
	$query_operacion="INSERT INTO albaranes (codalbaran, codfactura, tipo, fecha, iva, codcliente, moneda, estado, fechaentrega, lugar, solicitado, totalalbaran, descuento, observacion, codformapago, borrado) 
	VALUES ('', '0', '$tipo', '$fecha', '$iva', '$codcliente', '$moneda', '1', '$fechaentrega', '$lugar', '$solicitado', '$totalalbaran', '$descuento', '$observacion', '$codformapago', '0')";					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	$codalbaran=((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
	if ($rs_operacion) { $mensaje="El remito ha sido dado de alta correctamente"; 
	$query_tmp="SELECT * FROM albalineatmp WHERE codalbaran='$codalbarantmp' ORDER BY numlinea ASC";
	$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $query_tmp);
	$contador=0;
	$baseimponible=0;
	while ($contador < mysqli_num_rows($rs_tmp)) {
		$codfamilia=mysqli_result($rs_tmp, $contador, "codfamilia");
		$numlinea=mysqli_result($rs_tmp, $contador, "numlinea");
		$codigo=mysqli_result($rs_tmp, $contador, "codigo");
		$cantidad=mysqli_result($rs_tmp, $contador, "cantidad");
		$precio=mysqli_result($rs_tmp, $contador, "precio");
		$importe=mysqli_result($rs_tmp, $contador, "importe");
		$detalles=mysqli_result($rs_tmp, $contador, "detalles");
		
		$baseimponible=$baseimponible+$importe;
		$dcto=mysqli_result($rs_tmp, $contador, "dcto");
		$sel_insertar="INSERT INTO albalinea (codalbaran,numlinea,codfamilia,detalles,codigo,cantidad,precio,importe,dcto) VALUES 
		('$codalbaran','$numlinea','$codfamilia','$detalles','$codigo','$cantidad','$precio','$importe','$dcto')";
		$rs_insertar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insertar);		
		$sel_articulos="UPDATE articulos SET stock=(stock-'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_articulos=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulos);
		$sel_minimos = "SELECT stock,stock_minimo,descripcion FROM articulos where codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_minimos= mysqli_query($GLOBALS["___mysqli_ston"], $sel_minimos);
		if ((mysqli_result($rs_minimos, 0, "stock") < mysqli_result($rs_minimos, 0, "stock_minimo")) or (mysqli_result($rs_minimos, 0, "stock") <= 0))
	   		{ 
		  		$mensaje_minimo=$mensaje_minimo . " " . mysqli_result($rs_minimos, 0, "descripcion")."<br>";
				$minimo=1;
   			};
		$contador++;
	}
	$baseimpuestos=$baseimponible*($iva/100);
	$preciototal=$baseimponible+$baseimpuestos;
	//$preciototal=number_format($preciototal,2);	
	$sel_act="UPDATE albaranes SET totalalbaran='$preciototal' WHERE codalbaran='$codalbaran'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $sel_act);
	$baseimponible=0;
	$preciototal=0;
	$baseimpuestos=0;
	$cabecera1="Inicio >> Ventas &gt;&gt; Nuevo Albar&aacute;n ";
	$cabecera2="INSERTAR REMITO ";
	
	} else {
		$mensaje="Error al insertar remito";		
	}
}

if ($accion=="modificar") {
	$codalbaran=$_POST["codalbaran"];
	$act_albaran="UPDATE albaranes SET codcliente='$codcliente', tipo='$tipo', fecha='$fecha', moneda='$moneda', iva='$iva', fechaentrega='$fechaentrega',lugar='$lugar', solicitado='$solicitado',
	descuento='$descuento', observacion='$observacion', codformapago='$codformapago' WHERE codalbaran='$codalbaran'";
	$rs_albaran=mysqli_query($GLOBALS["___mysqli_ston"], $act_albaran);
	$sel_lineas = "SELECT codigo,codfamilia,cantidad FROM albalinea WHERE codalbaran='$codalbaran' order by numlinea";
	$rs_lineas = mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
	$contador=0;
	while ($contador < mysqli_num_rows($rs_lineas)) {
		$codigo=mysqli_result($rs_lineas, $contador, "codigo");
		$codfamilia=mysqli_result($rs_lineas, $contador, "codfamilia");
		$cantidad=mysqli_result($rs_lineas, $contador, "cantidad");
		$sel_actualizar="UPDATE `articulos` SET stock=(stock+'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_actualizar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_actualizar);
		$contador++;
	}
	$sel_borrar = "DELETE FROM albalinea WHERE codalbaran='$codalbaran'";
	$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);
	$sel_lineastmp = "SELECT * FROM albalineatmp WHERE codalbaran='$codalbarantmp' ORDER BY numlinea";
	$rs_lineastmp = mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineastmp);
	$contador=0;
	$baseimponible=0;
	while ($contador < mysqli_num_rows($rs_lineastmp)) {
		$numlinea=mysqli_result($rs_lineastmp, $contador, "numlinea");
		$codigo=mysqli_result($rs_lineastmp, $contador, "codigo");
		$codfamilia=mysqli_result($rs_lineastmp, $contador, "codfamilia");
		$cantidad=mysqli_result($rs_lineastmp, $contador, "cantidad");
		$precio=mysqli_result($rs_lineastmp, $contador, "precio");
		$importe=mysqli_result($rs_lineastmp, $contador, "importe");
		$baseimponible=$baseimponible+$importe;
		$dcto=mysqli_result($rs_lineastmp, $contador, "dcto");
		$detalles=mysqli_result($rs_lineastmp, $contador, "detalles");
	
		$sel_insert = "INSERT INTO albalinea (codalbaran,numlinea,codigo,codfamilia,detalles,cantidad,precio,importe,dcto) 
		VALUES ('$codalbaran','','$codigo','$codfamilia','$detalles','$cantidad','$precio','$importe','$dcto')";
		$rs_insert = mysqli_query($GLOBALS["___mysqli_ston"], $sel_insert);
		
		$sel_actualiza="UPDATE articulos SET stock=(stock-'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_actualiza = mysqli_query($GLOBALS["___mysqli_ston"], $sel_actualiza);
		$sel_bajominimo = "SELECT codarticulo,codfamilia,stock,stock_minimo,descripcion FROM articulos WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_bajominimo= mysqli_query($GLOBALS["___mysqli_ston"], $sel_bajominimo);
		$stock=mysqli_result($rs_bajominimo, 0, "stock");
		$stock_minimo=mysqli_result($rs_bajominimo, 0, "stock_minimo");
		$descripcion=mysqli_result($rs_bajominimo, 0, "descripcion");
		
		if ((($stock < $stock_minimo) or ($stock <= 0)) and mysqli_num_rows($rs_bajominimo)>0)
		   { 
			  $mensaje_minimo=$mensaje_minimo . " " . $descripcion."<br>";
			  $minimo=1;
		   };
		$contador++;
	}
	$baseimpuestos=$baseimponible*($iva/100);
	$preciototal=$baseimponible+$baseimpuestos;
	//$preciototal=number_format($preciototal,2);	
	$sel_act="UPDATE albaranes SET totalalbaran='$preciototal' WHERE codalbaran='$codalbaran'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $sel_act);
	$baseimponible=0;
	$preciototal=0;
	$baseimpuestos=0;
	if ($rs_query) { $mensaje="Los datos de la orden de compra han sido modificados correctamente"; }
	$cabecera1="Inicio >> Ventas &gt;&gt; Modificar orden de compras ";
	$cabecera2="MODIFICAR ORDEN DE COMPRA ";
}

if ($accion=="baja") {
	$codalbaran=$_GET["codalbaran"];
	$query="UPDATE albaranes SET borrado=1 WHERE codalbaran='$codalbaran'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$query="SELECT * FROM albalinea WHERE codalbaran='$codalbaran' ORDER BY numlinea ASC";
	$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$contador=0;
	$baseimponible=0;
	while ($contador < mysqli_num_rows($rs_tmp)) {
		$codfamilia=mysqli_result($rs_tmp, $contador, "codfamilia");
		$codigo=mysqli_result($rs_tmp, $contador, "codigo");
		$cantidad=mysqli_result($rs_tmp, $contador, "cantidad");
		$sel_articulos="UPDATE articulos SET stock=(stock+'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_articulos=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulos);
		$contador++;
	}
	if ($rs_query) { $mensaje="El albar&aacute;n ha sido eliminado correctamente"; }
	$cabecera1="Inicio >> Ventas &gt;&gt; Eliminar Albar&aacute;n";
	$cabecera2="ELIMINAR ALBAR&Aacute;N";
	$query_mostrar="SELECT * FROM albaranes WHERE codalbaran='$codalbaran'";
	$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);
	$codcliente=mysqli_result($rs_mostrar, 0, "codcliente");
	$fecha=mysqli_result($rs_mostrar, 0, "fecha");
	$iva=mysqli_result($rs_mostrar, 0, "iva");
}

if ($accion=="convertir") {
	$codalbaran=$_POST["codalbaran"];
	$fecha=$_POST["fecha"];
	$fecha=explota($fecha);
	$codfactura=$_POST["numero"];
	$sel_albaran="SELECT * FROM albaranes WHERE codalbaran='$codalbaran'";
	$rs_albaran=mysqli_query($GLOBALS["___mysqli_ston"], $sel_albaran);
	$iva=mysqli_result($rs_albaran, 0, "iva");
	$codcliente=mysqli_result($rs_albaran, 0, "codcliente");
	$totalfactura=mysqli_result($rs_albaran, 0, "totalalbaran");
	$sel_factura="INSERT INTO facturas (codfactura,fecha,iva,codcliente,estado,totalfactura,borrado) VALUES 
		('$codfactura','$fecha','$iva','$codcliente','1','$totalfactura','0')";
	/*$rs_factura=mysql_query($sel_factura);
	$codfactura=mysql_insert_id();*/
	$act_albaran="UPDATE albaranes SET codfactura='$codfactura',estado='2' WHERE codalbaran='$codalbaran'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $act_albaran);
	$sel_lineas="SELECT * FROM albalinea WHERE codalbaran='$codalbaran' ORDER BY numlinea ASC";
	$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
	$contador=0;
	while ($contador < mysqli_num_rows($rs_lineas)) {
		$codfamilia=mysqli_result($rs_lineas, $contador, "codfamilia");
		$codigo=mysqli_result($rs_lineas, $contador, "codigo");
		$cantidad=mysqli_result($rs_lineas, $contador, "cantidad");
		$precio=mysqli_result($rs_lineas, $contador, "precio");
		$importe=mysqli_result($rs_lineas, $contador, "importe");
		$dcto=mysqli_result($rs_lineas, $contador, "dcto");
		$sel_insert="INSERT INTO factulinea (codfactura,numlinea,codfamilia,codigo,cantidad,precio,importe,dcto) VALUES 
			('$codfactura','','$codfamilia','$codigo','$cantidad','$precio','$importe','$dcto')";
		$rs_insert=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insert);
		$contador++;
	}
	$mensaje="El albar&aacute;n ha sido convertido correctamente";
	$cabecera1="Inicio >> Ventas &gt;&gt; Convertir Albar&aacute;n";
	$cabecera2="CONVERTIR ALBAR&Aacute;N";
}

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

		function aceptar() {
			parent.$('idOfDomElement').colorbox.close();
		}		
		
		function imprimir(codalbaran) {
			window.open("../fpdf/imprimir_albaran.php?codalbaran="+codalbaran);
			parent.$('idOfDomElement').colorbox.close();
		}
		
		function imprimirf(codfactura) {
			window.open("../fpdf/imprimir_factura.php?codfactura="+codfactura);
			parent.$('idOfDomElement').colorbox.close();
		}
		
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header"><?php echo $cabecera2;?></div>
				<div id="frmBusqueda">
				
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
						 <td width="100%" colspan="2" class="mensaje"><?php echo $mensaje;?></td>
					   </tr>
					    <?php if ($minimo==1) { ?>
						<tr>
							<td width="100%" colspan="2" class="mensajeminimo">Los siguientes art&iacute;culos est&aacute;n bajo m&iacute;nimo:<br><?php echo $mensaje_minimo;?></td>
					   </tr>
						<?php } 
						 $sel_cliente="SELECT * FROM clientes WHERE codcliente='$codcliente'"; 
						  $rs_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cliente); ?>
					<tr><td width="100%">
					<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td width="5%">Cliente&nbsp;Nº: </td>
					      <td width="5%"><?php echo $codcliente;?></td>
							<td width="6%">Nombre:</td>
						   <td>
								<?php if (mysqli_result($rs_cliente, 0, "empresa")!='') {?>
								<div align="left"><?php echo mysqli_result($rs_cliente, 0, "empresa")?></div>
								<?php } elseif (mysqli_result($rs_cliente, 0, "apellido")=='') {?>
								<div align="left"><?php echo mysqli_result($rs_cliente, 0, "nombre")?></div>
								<?php } else { ?>
								<div align="left"><?php echo mysqli_result($rs_cliente, 0, "nombre")?>
								 <?php echo mysqli_result($rs_cliente, 0, "apellido")?></div>
								<?php } ?>
						   </td>
						  <?php if ($accion=="convertir") { ?>
						   <td width="6%">C&oacute;digo&nbsp;de&nbsp;factura:</td>
							<td><?php echo $codfactura?></td>
						  <?php } else { ?>
						   <td width="6%">C&oacute;digo&nbsp;de&nbsp;remito:</td>
						   <td><?php echo $codalbaran?></td>
						  <?php } ?>
						</tr>
						<tr>
			            <td width="5%">RUT</td>
			            <td width="5%"><?php echo mysqli_result($rs_cliente, 0, "nif");?></td>
							<td>Tipo</td>
			            <td>
							<?php $tipof = array(0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
							$NoEstado=0;
							foreach($tipof as $key => $facturai ) {
							  	if ( $key==$tipo) {
									echo "$facturai";
									break;
								}
							}
							?>
						</td>
						<td>Moneda:</td><td>
					<?php $tipof = array(  1=>"Pesos", 2=>"U\$S");
					foreach ($tipof as $key => $monedai ) {
					  	if ( $moneda==$key ) {
							echo "$monedai";
							break;
						}
					}
					?>
					</td>
						</tr>
						<?php $hoy=date("d/m/Y"); ?>
						<tr>
							<td width="6%">Fecha:</td>
						   <td width="5%"><?php echo implota($fecha)?></td>
			            <td>IVA:</td>
			            <td ><?php echo $iva?>%</td>
			            <td colspan="2">Tipo&nbsp;cambio:
							<label>U$S -> $&nbsp;</label><span>
							<?php echo $tipocambio;?></span>
							</td>
						</tr>
				  	</table>
					</td>
					  </tr>
				  </table>
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
							$referencia=mysqli_result($rs_lineas, $i, "referencia");
							$descripcion=mysqli_result($rs_lineas, $i, "descripcion");
							$detalles=mysqli_result($rs_lineas, $i, "detalles");
							$cantidad=mysqli_result($rs_lineas, $i, "cantidad");
							$precio=mysqli_result($rs_lineas, $i, "precio");
							$importe=mysqli_result($rs_lineas, $i, "importe");
							$baseimponible=$baseimponible+$importe;
							$descuento=mysqli_result($rs_lineas, $i, "dcto");
							if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>
									<tr class="<?php echo $fondolinea?>">
										<td width="5%" class="aCentro"><?php echo $i+1?></td>
										<td width="20%"><?php echo $referencia;?></td>
										<td width="30%"><?php echo $descripcion; if($detalles!='') echo " - ".$detalles; ?></td>
										<td width="10%" class="aCentro"><?php echo $cantidad?></td>
										<td width="10%" class="aCentro"><?php echo $precio?></td>
										<td width="10%" class="aCentro"><?php echo $descuento?></td>
										<td width="10%" class="aCentro"><?php echo $importe?></td>
									</tr>
					<?php } ?>
					</table>
			  </div>
				  <?php
				  $baseimpuestos=$baseimponible*($iva/100);
			      $preciototal=$baseimponible+$baseimpuestos;
			      $preciototal=number_format($preciototal,2);
			  	  ?>			  
			<div id="frmBusqueda">
			<table width="25%" border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
			  <tr>
			    <td width="27%" class="busqueda">Sub-total</td>
				<td width="73%" align="right"><div align="center">
				 <input type="text" class="cajaPequena2" id="monShow" value="<?php echo $monedai;?>" readonly>
			      <input class="cajaTotales" name="baseimponible" type="text" id="baseimponible" size="12" value="<?php echo number_format($baseimponible,2);?>" align="right" readonly> 
		        </div></td>
			  </tr>
			  <tr>
				<td class="busqueda">IVA</td>
				<td align="right"><div align="center">
				 <input type="text" class="cajaPequena2" id="monSho" value="<?php echo $monedai;?>" readonly>
			      <input class="cajaTotales" name="baseimpuestos" type="text" id="baseimpuestos" size="12" align="right" value="<?php echo number_format($baseimpuestos,2);?>" readonly>
		        </div></td>
			  </tr>
			  <tr>
				<td class="busqueda">Precio Total</td>
				<td align="right"><div align="center">
				 <input type="text" class="cajaPequena2" id="monSh" value="<?php echo $monedai;?>" readonly>
			      <input class="cajaTotales" name="preciototal" type="text" id="preciototal" size="12" align="right" value="<?php echo $preciototal?>" readonly> 
		        </div></td>
			  </tr>
			</table>
			  </div>				  
			  
				<div>
					<div align="center">
					 <img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar();" border="1" onMouseOver="style.cursor=cursor">
					  <?php if ($accion=="convertir") { ?>
					   <img id="botonBusqueda" src="../img/botonimprimir.jpg" width="79" height="22" border="1" onClick="imprimirf(<?php echo $codfactura?>);" onMouseOver="style.cursor=cursor">
					   <?php } else { ?>
					   <img id="botonBusqueda" src="../img/botonimprimir.jpg" width="79" height="22" border="1" onClick="imprimir(<?php echo $codalbaran?>);" onMouseOver="style.cursor=cursor">
					   <?php } ?>
				        </div>
					</div>
			  </div>
		  </div>
		</div>
	</body>
</html>
