<?php
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$codalbarantmp=$_POST["codalbarantmp"];
$codalbaran=$_POST["calbaran"];
$codproveedor=$_POST["codproveedor"];
$moneda=$_POST["amoneda"];
$fecha=explota($_POST["fecha"]);
$iva=$_POST["iva"];
$minimo=0;

if ($accion=="alta") {
	$query_comprobar="SELECT * FROM albaranesp WHERE codalbaran='$codalbaran' AND codproveedor='$codproveedor'";
	$rs_comprobar=mysqli_query($GLOBALS["___mysqli_ston"], $query_comprobar);
	if (mysqli_num_rows($rs_comprobar) > 0 ) {
			?><script>
				alert ("No se puede dar de alta este numero de albaran con este proveedor, ya existe uno en el sistema.");
				parent.$('idOfDomElement').colorbox.close();
			</script><?php
	} else {
			$query_operacion="INSERT INTO albaranesp (codalbaran, codproveedor, codfactura, fecha, iva, moneda, estado) VALUES ('$codalbaran', '$codproveedor',  '0', '$fecha', '$iva', '$moneda', '1')";					
			$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
			if ($rs_operacion) { $mensaje="El albar&aacute;n ha sido dado de alta correctamente"; }
			$query_tmp="SELECT * FROM albalineaptmp WHERE codalbaran='$codalbarantmp' ORDER BY numlinea ASC";
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
				$baseimponible=$baseimponible+$importe;
				$dcto=mysqli_result($rs_tmp, $contador, "dcto");
				$sel_insertar="INSERT INTO albalineap (codalbaran,codproveedor,numlinea,codfamilia,codigo,cantidad,precio,importe,dcto) VALUES 
				('$codalbaran','$codproveedor','$numlinea','$codfamilia','$codigo','$cantidad','$precio','$importe','$dcto')";
				$rs_insertar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insertar);		
				$sel_articulos="UPDATE articulos SET stock=(stock+'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
				$rs_articulos=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulos);
				$sel_comprobar="SELECT codarticulo FROM artpro WHERE codarticulo='".$codigo."' AND codfamilia='$codfamilia' AND codproveedor='".$codproveedor."'";
				$rs_comprobar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_comprobar);
				$precio=sprintf("%01.2f",$precio);
				if (mysqli_num_rows($rs_comprobar) > 0) {
					$sentencia="UPDATE artpro SET precio='".$precio."' WHERE codarticulo='".$codigo."' AND codfamilia='$codfamilia' AND codproveedor='".$codproveedor."'";         } else {
					$sentencia="INSERT into artpro (codarticulo,codfamilia,codproveedor,precio) VALUES ('$codigo','$codfamilia','$codproveedor','$precio')";		
				}
				$ejecutar=mysqli_query($GLOBALS["___mysqli_ston"], $sentencia);
				$sentencia2="UPDATE articulos SET ultimo_precio_costo='".$precio."' AND codproveedor='$codproveedor' WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
				$ejecutar=mysqli_query($GLOBALS["___mysqli_ston"], $sentencia2);
				$contador++;
			}
			$baseimpuestos=$baseimponible*($iva/100);
			$preciototal=$baseimponible+$baseimpuestos;
			//$preciototal=number_format($preciototal,2);	
			$sel_act="UPDATE albaranesp SET totalalbaran='$preciototal' WHERE codalbaran='$codalbaran'";
			$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $sel_act);
			$baseimpuestos=0;
			$preciototal=0;
			$baseimponible=0;
			$cabecera1="Inicio >> Compras &gt;&gt; Nuevo Albar&aacute;n ";
			$cabecera2="INSERTAR ALBAR&Aacute;N ";
		}
} 

if ($accion=="modificar") {
	$codalbaran=$_POST["codalbaran"];
	$act_albaran="UPDATE albaranesp SET fecha='$fecha', iva='$iva', moneda='$moneda' WHERE codalbaran='$codalbaran' AND codproveedor='$codproveedor'";
	$rs_albaran=mysqli_query($GLOBALS["___mysqli_ston"], $act_albaran);
	$sel_lineas = "SELECT codigo,codfamilia,cantidad FROM albalineap WHERE codalbaran='$codalbaran' AND codproveedor='$codproveedor' order by numlinea";
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
	$sel_borrar = "DELETE FROM albalineap WHERE codalbaran='$codalbaran' AND codproveedor='$codproveedor'";
	$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);
	$sel_lineastmp = "SELECT * FROM albalineaptmp WHERE codalbaran='$codalbarantmp' ORDER BY numlinea";
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
	
		$sel_insert = "INSERT INTO albalineap (codalbaran,codproveedor,numlinea,codigo,codfamilia,cantidad,precio,importe,dcto) 
		VALUES ('$codalbaran','$codproveedor','','$codigo','$codfamilia','$cantidad','$precio','$importe','$dcto')";
		$rs_insert = mysqli_query($GLOBALS["___mysqli_ston"], $sel_insert);
		
		$sel_actualiza="UPDATE articulos SET stock=(stock-'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_actualiza = mysqli_query($GLOBALS["___mysqli_ston"], $sel_actualiza);
		$sel_bajominimo = "SELECT codarticulo,codfamilia,stock,stock_minimo,descripcion FROM articulos WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_bajominimo= mysqli_query($GLOBALS["___mysqli_ston"], $sel_bajominimo);
		$stock=mysqli_result($rs_bajominimo, 0, "stock");
		$stock_minimo=mysqli_result($rs_bajominimo, 0, "stock_minimo");
		$descripcion=mysqli_result($rs_bajominimo, 0, "descripcion");
		
		if (($stock < $stock_minimo) or ($stock <= 0))
		   { 
			  $mensaje_minimo=$mensaje_minimo . " " . $descripcion."<br>";
			  $minimo=1;
		   };
		$contador++;
	}
	$baseimpuestos=$baseimponible*($iva/100);
	$preciototal=$baseimponible+$baseimpuestos;
	//$preciototal=number_format($preciototal,2);	
	$sel_act="UPDATE albaranesp SET totalalbaran='$preciototal' WHERE codalbaran='$codalbaran'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $sel_act);
	$baseimpuestos=0;
	$preciototal=0;
	$baseimponible=0;
	if ($rs_insert) { $mensaje="Los datos del albar&aacute;n han sido modificados correctamente"; }
	$cabecera1="Inicio >> Compras &gt;&gt; Modificar Albar&aacute;n ";
	$cabecera2="MODIFICAR ALBAR&Aacute;N ";
}

if ($accion=="baja") {
	$codalbaran=$_GET["codalbaran"];
	$codproveedor=$_GET["codproveedor"];
	$query="SELECT * FROM albalineap WHERE codalbaran='$codalbaran' AND codproveedor='$codproveedor' ORDER BY numlinea ASC";
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
	if ($rs_tmp) { $mensaje="El albar&aacute;n ha sido eliminado correctamente"; }
	$cabecera1="Inicio >> Compras &gt;&gt; Eliminar Albar&aacute;n";
	$cabecera2="ELIMINAR ALBAR&Aacute;N";
	$query_mostrar="SELECT * FROM albaranesp WHERE codalbaran='$codalbaran' AND codproveedor='$codproveedor'";
	$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);
	$codproveedor=mysqli_result($rs_mostrar, 0, "codproveedor");
	$fecha=mysqli_result($rs_mostrar, 0, "fecha");
	$iva=mysqli_result($rs_mostrar, 0, "iva");
}

if ($accion=="convertir") {
	$codalbaran=$_POST["codalbaran"];
	$fecha=$_POST["fecha"];
	$codalbara=$_POST["Acodalbara"];
	$fecha=explota($fecha);
	$query_comprobar="SELECT * FROM albarasp WHERE codalbara='$codalbara' AND codproveedor='$codproveedor'";
	$rs_comprobar=mysqli_query($GLOBALS["___mysqli_ston"], $query_comprobar);
	if (mysqli_num_rows($rs_comprobar) > 0 ) {
			?><script>
				alert ("No se puede dar de alta este numero de albara con este proveedor, ya existe uno en el sistema.");
				location.href="index.php";
			</script><?php
	} else {
		$sel_albaran="SELECT * FROM albaranesp WHERE codalbaran='$codalbaran' AND codproveedor='$codproveedor'";
		$rs_albaran=mysqli_query($GLOBALS["___mysqli_ston"], $sel_albaran);
		$iva=mysqli_result($rs_albaran, 0, "iva");
		$codproveedor=mysqli_result($rs_albaran, 0, "codproveedor");
		$totalalbara=mysqli_result($rs_albaran, 0, "totalalbaran");
		$sel_albara="INSERT INTO albarasp (codalbara,fecha,iva,codproveedor,estado,totalalbara,borrado) VALUES 
			('$codalbara','$fecha','$iva','$codproveedor','1','$totalalbara','0')";
		$rs_albara=mysqli_query($GLOBALS["___mysqli_ston"], $sel_albara);
		$act_albaran="UPDATE albaranesp SET codfactura='$codfactura',estado='2' WHERE codalbaran='$codalbaran' AND codproveedor='$codproveedor'";
		$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $act_albaran);
		$sel_lineas="SELECT * FROM albalineap WHERE codalbaran='$codalbaran' AND codproveedor='$codproveedor' ORDER BY numlinea ASC";
		$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
		$contador=0;
		while ($contador < mysqli_num_rows($rs_lineas)) {
			$codfamilia=mysqli_result($rs_lineas, $contador, "codfamilia");
			$codigo=mysqli_result($rs_lineas, $contador, "codigo");
			$cantidad=mysqli_result($rs_lineas, $contador, "cantidad");
			$precio=mysqli_result($rs_lineas, $contador, "precio");
			$importe=mysqli_result($rs_lineas, $contador, "importe");
			$dcto=mysqli_result($rs_lineas, $contador, "dcto");
			$sel_insert="INSERT INTO albalineap (codalbara,codproveedor,numlinea,codfamilia,codigo,cantidad,precio,importe,dcto) VALUES 
				('$codalbara','$codproveedor','','$codfamilia','$codigo','$cantidad','$precio','$importe','$dcto')";
			$rs_insert=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insert);
			$contador++;
		}
		$mensaje="El albar&aacute;n ha sido convertido correctamente";
		$cabecera1="Inicio >> Compras &gt;&gt; Convertir Albar&aacute;n";
		$cabecera2="CONVERTIR ALBAR&Aacute;N";
	}
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
		
		function imprimir(codalbaran,codproveedor) {
			window.open("../fpdf/imprimir_albaran_proveedor.php?codalbaran="+codalbaran+"&codproveedor="+codproveedor);
		}
		
		function imprimirf(codalbara,codproveedor) {
			window.open("../fpdf/imprimir_albara_proveedor.php?codalbara="+codalbara+"&codproveedor="+codproveedor);
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
						<td width="85%" colspan="2" class="mensaje"><?php echo $mensaje;?></td>
					</tr><tr><td>
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						
						<?php 
						 $sel_proveedores="SELECT * FROM proveedores WHERE codproveedor='$codproveedor'"; 
						  $rs_proveedores=mysqli_query($GLOBALS["___mysqli_ston"], $sel_proveedores); ?>
						<tr>
							<td width="15%">Proveedor</td>
							<td width="85%" colspan="2"><?php echo mysqli_result($rs_proveedores, 0, "nombre");?></td>
					    </tr>
						<tr>
							<td width="15%">RUT</td>
						    <td width="85%" colspan="2"><?php echo mysqli_result($rs_proveedores, 0, "nif");?></td>
					    </tr>
						<tr>
						  <td>Direcci&oacute;n</td>
						  <td colspan="2"><?php echo mysqli_result($rs_proveedores, 0, "direccion"); ?></td>
					  </tr>
					  </table>
					  </td><td>
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
						  <td>C&oacute;digo de factura</td>
						  <td colspan="2"><?php echo $codalbaran;?></td>
					  </tr>
					  <tr>
						  <td>Fecha</td>
						  <td colspan="2"><?php echo implota($fecha);?></td>
					  </tr>
					  <tr>
						  <td>IVA</td>
						  <td colspan="2"><?php echo $iva;?> %</td>
					  </tr>
					  <tr>
						  <td></td>
						  <td colspan="2"></td>
					  </tr>
					  	</table>
					</td></tr>					  
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
					  <?php
					$sel_lineas="SELECT albalineap.*, articulos.descripcion, articulos.referencia, familias.nombre as nombrefamilia FROM albalineap,articulos,familias WHERE albalineap.codalbaran='$codalbaran' AND albalineap.codproveedor='$codproveedor' AND albalineap.codigo=articulos.codarticulo AND albalineap.codfamilia=articulos.codfamilia AND articulos.codfamilia=familias.codfamilia ORDER BY albalineap.numlinea ASC";					   
$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
						for ($i = 0; $i < mysqli_num_rows($rs_lineas); $i++) {
							$numlinea=mysqli_result($rs_lineas, $i, "numlinea");
							$codfamilia=mysqli_result($rs_lineas, $i, "codfamilia");
							$nombrefamilia=mysqli_result($rs_lineas, $i, "nombrefamilia");
							$codarticulo=mysqli_result($rs_lineas, $i, "codigo");
							$referencia=mysqli_result($rs_lineas, $i, "referencia");
							$descripcion=mysqli_result($rs_lineas, $i, "descripcion");
							$cantidad=mysqli_result($rs_lineas, $i, "cantidad");
							$precio=mysqli_result($rs_lineas, $i, "precio");
							$importe=mysqli_result($rs_lineas, $i, "importe");
							$baseimponible=$baseimponible+$importe;
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
					<?php } ?>
					</table>			  
			  </div>			  
				  <?php
				  $baseimpuestos=$baseimponible*($iva/100);
			      $preciototal=$baseimponible+$baseimpuestos;
			      $preciototal=number_format($preciototal,2);
			      
					$tipof = array(  1=>"Pesos", 2=>"U\$S");
					foreach ($tipof as $key => $monedai ) {
					  	if ( $moneda==$key ) {
							echo "$monedai";
							break;
						}
					}
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
			  
			  <?php if ($accion=="baja") { 
					  $query="DELETE FROM albaranesp WHERE codalbaran='$codalbaran' AND codproveedor='$codproveedor'";
						$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
						$borrar_lineas="DELETE FROM albalineap WHERE codalbaran='$codalbaran' AND codproveedor='$codproveedor'";
						$rs_borrar_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $borrar_lineas);
				} ?>
				<div>
					<div align="center">
					  <img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar();" border="1" onMouseOver="style.cursor=cursor">
					  <?php if ($accion<>"baja") { ?>
						   <?php if ($accion=="convertir") { ?>
						   <img id="botonBusqueda" src="../img/botonimprimir.jpg" width="79" height="22" border="1" onClick="imprimirf('<?php echo $codalbara?>',<?php echo $codproveedor ?>)" onMouseOver="style.cursor=cursor">
						   <?php } else { ?>
						   <img id="botonBusqueda" src="../img/botonimprimir.jpg" width="79" height="22" border="1" onClick="imprimir('<?php echo $codalbaran?>',<?php echo $codproveedor ?>)" onMouseOver="style.cursor=cursor">
						   <?php } ?>
				 <?php } ?>
				        </div>
					</div>
			  </div>
		  </div>
		</div>
	</body>
</html>