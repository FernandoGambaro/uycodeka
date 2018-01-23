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
 
include ("../../conectar.php"); 
include ("../../funciones/fechas.php"); 

date_default_timezone_set("America/Montevideo"); 

$mensaje='';
$baseimponibledescuento=0;

$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$codautofacturatmp=$_POST["codautofacturatmp"];
$codcliente=$_POST["codcliente"];
$tipo=$_POST["atipo"];
$moneda=$_POST["Amoneda"];
$tipocambio=$_POST['tipocambio'];
$fecha=explota($_POST["fecha"]);
$observacion=$_POST["observacion"];
$descuentogral=$_POST["descuentogral"];
$codformapago=$_POST['codformapago'];

$semanafacturacion=$_POST['Asemanafacturacion'];
$diafacturacion=$_POST['Adiafacturacion'];
$activa=$_POST['activa'];
$emitida=$_POST['emitida'];

$iva=$_POST["impuesto"];
$minimo=0;
$mensaje_minimo='';

/*
$query="SELECT * FROM tipocambio WHERE fecha<='$fecha' order by `fecha` DESC";
$rs_query=mysql_query($query);
$tipocambio=mysql_result($rs_query,0,"valor");
*/

if ($accion=="alta") {
	$query_operacion="INSERT INTO autofacturas (codautofactura, tipo, fecha, iva, codcliente, estado, moneda, tipocambio, descuento, observacion, codformapago, borrado,emitida,semanafacturacion, diafacturacion,activa) 
	VALUES ('$codautofacturatmp', '$tipo', '$fecha', '$iva', '$codcliente', '1', '$moneda', '$tipocambio', '$descuentogral', '$observacion', '$codformapago', '0', '$emitida', '$semanafacturacion', '$diafacturacion', '$activa')";					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	/*$codautofactura=mysql_insert_id(); Anulo pues el nº de factura lo ingreso manualmente.-*/
	$codautofactura=$codautofacturatmp;
	if ($rs_operacion) { $mensaje="La factura ha sido dada de alta correctamente"; }
	$query_tmp="SELECT * FROM autofactulineatmp WHERE codautofactura='$codautofactura' ORDER BY numlinea ASC";
	$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $query_tmp);
	$contador=0;
	$baseimponible=0;
	while ($contador < mysqli_num_rows($rs_tmp)) {
		$codfamilia=mysqli_result($rs_tmp, $contador, "codfamilia");
		$numlinea=mysqli_result($rs_tmp, $contador, "numlinea");
		$codigo=mysqli_result($rs_tmp, $contador, "codigo");
		$codservice=mysqli_result($rs_tmp, $contador, "codservice");
		$detalles=mysqli_result($rs_tmp, $contador, "detalles");
		$cantidad=mysqli_result($rs_tmp, $contador, "cantidad");
		$precio=mysqli_result($rs_tmp, $contador, "precio");
		$importe=mysqli_result($rs_tmp, $contador, "importe");
		$comision=mysqli_result($rs_tmp, $contador, "comision");
		$baseimponible=$baseimponible+$importe;
		$dcto=mysqli_result($rs_tmp, $contador, "dcto");
		$dctopp=mysqli_result($rs_tmp, $contador, "dctopp");
		$comision=mysqli_result($rs_tmp, $contador, "comision");
		

		$sel_insertar="INSERT INTO autofactulinea (codautofactura,numlinea,codfamilia,codigo,codservice,detalles,cantidad,moneda,precio,importe,dcto,dctopp,comision) VALUES 
		('$codautofactura','$numlinea','$codfamilia','$codigo', '$codservice', '$detalles','$cantidad','$moneda','$precio','$importe','$dcto','$dctopp','$comision')";
		$rs_insertar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insertar);
	
		$contador++;
	}
		  	$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".$iva;
			$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
			$ivavalor=mysqli_result($res_iva, 0, "valor");
		
	  if ($descuentogral==0 or $descuentogral=='') {
	  $baseimpuestos=$baseimponible*($ivavalor/100);
     $preciototal=$baseimponible+$baseimpuestos;
	  } else {
	  $baseimponibledescuento=$baseimponible/(1-$descuentogral/100);
	  $baseimponible=$baseimponible*(1-$descuentogral/100);
	  $baseimpuestos=$baseimponible*($ivavalor/100);
     $preciototal=$baseimponible+$baseimpuestos;
	  }

      	
	$sel_act="UPDATE autofacturas SET totalfactura='$preciototal' WHERE codautofactura='$codautofactura'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $sel_act);
	$baseimpuestos=0;
	$baseimponible=0;
	$preciototal=0;
	$cabecera1="Inicio >> Ventas &gt;&gt; Nueva Factura ";
	$cabecera2="INSERTAR FACTURA ";
}

if ($accion=="modificar") {
	$codautofactura=$_POST["codautofacturatmp"];
	$act_albaran="UPDATE autofacturas SET codcliente='$codcliente', tipo='$tipo', fecha='$fecha', iva='$iva', moneda='$moneda', tipocambio='$tipocambio', descuento='$descuentogral', observacion='$observacion',
	 codformapago='$codformapago', emitida='$emitida', semanafacturacion='$semanafacturacion', diafacturacion='$diafacturacion', activa='$activa' WHERE codautofactura='$codautofactura'";
	$rs_albaran=mysqli_query($GLOBALS["___mysqli_ston"], $act_albaran);
	$sel_lineas = "SELECT codigo,codfamilia,cantidad FROM autofactulinea WHERE codautofactura='$codautofactura' order by numlinea";
	$rs_lineas = mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
	$contador=0;
	while ($contador < mysqli_num_rows($rs_lineas)) {
		$codigo=mysqli_result($rs_lineas, $contador, "codigo");
		$codfamilia=mysqli_result($rs_lineas, $contador, "codfamilia");
		$cantidad=mysqli_result($rs_lineas, $contador, "cantidad");

		$contador++;
	}

	$sel_borrar = "DELETE FROM autofactulinea WHERE codautofactura='$codautofactura'";
	$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);
	
	$sel_lineastmp = "SELECT * FROM autofactulineatmp WHERE codautofactura='$codautofactura' ORDER BY numlinea ASC";
	$rs_lineastmp = mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineastmp);

	$contador1=0;
	$baseimponible=0;
	while ($contador1 < mysqli_num_rows($rs_lineastmp)) {
		$numlinea=mysqli_result($rs_lineastmp, $contador1, "numlinea");
		$codigo=mysqli_result($rs_lineastmp, $contador1, "codigo");
		$codservice=mysqli_result($rs_lineastmp, $contador1, "codservice");
		$codfamilia=mysqli_result($rs_lineastmp, $contador1, "codfamilia");
		$detalles=mysqli_result($rs_lineastmp, $contador1, "detalles");
		$cantidad=mysqli_result($rs_lineastmp, $contador1, "cantidad");
		$precio=mysqli_result($rs_lineastmp, $contador1, "precio");
		$importe=mysqli_result($rs_lineastmp, $contador1, "importe");
		$baseimponible=$baseimponible+$importe;
		$dcto=mysqli_result($rs_lineastmp, $contador1, "dcto");
		$dctopp=mysqli_result($rs_lineastmp, $contador1, "dctopp");
		$comision=mysqli_result($rs_lineastmp, $contador1, "comision");
	
				if($detalles!=''){
				$sel_insert = "INSERT INTO autofactulinea (codautofactura,numlinea,codfamilia,codigo,codservice,detalles,cantidad,moneda,precio,importe,dcto,dctopp,comision) VALUES 
				('$codautofactura','','$codfamilia','$codigo', '$codservice', '$detalles','$cantidad','$moneda','$precio','$importe','$dcto','$dctopp','$comision')";
				$rs_insert = mysqli_query($GLOBALS["___mysqli_ston"], $sel_insert);
				}
		$contador1++;
	}
		  	$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".$iva;
			$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
			$ivavalor=mysqli_result($res_iva, 0, "valor");
		
	  if ($descuentogral==0 or $descuentogral=='') {
	  $baseimpuestos=$baseimponible*($ivavalor/100);
     $preciototal=$baseimponible+$baseimpuestos;
	  } else {
	  $baseimponibledescuento=$baseimponible/(1-$descuentogral/100);
	  $baseimponible=$baseimponible*(1-$descuentogral/100);
	  $baseimpuestos=$baseimponible*($ivavalor/100);
     $preciototal=$baseimponible+$baseimpuestos;
	  }

	$sel_act="UPDATE autofacturas SET totalfactura='$preciototal' WHERE codautofactura='$codautofactura'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $sel_act);

	if ($rs_act) { $mensaje="Los datos de la factura han sido modificados correctamente"; }
	$cabecera1="Inicio >> Ventas &gt;&gt; Modificar Factura ";
	$cabecera2="MODIFICAR FACTURA ";
}

if ($accion=="baja") {
	$codautofactura=$_GET["codautofactura"];
	$query="UPDATE autofacturas SET borrado=1 WHERE codautofactura='$codautofactura'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

	if ($rs_query) { $mensaje="La factura ha sido eliminada correctamente"; }
	$cabecera1="Inicio >> Ventas &gt;&gt; Eliminar Factura";
	$cabecera2="ELIMINAR FACTURA";
}

	$sel_borrar = "DELETE FROM autofactulineatmp WHERE codautofactura='$codautofactura'";
	$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);

?>
<html>
	<head>
		<title>Principal</title>
		<link href="../../css3/estilos.css" type="text/css" rel="stylesheet">
<script src="../../js3/jquery.min.js"></script>
<link rel="stylesheet" href="../../js3/jquery.toastmessage.css" type="text/css">
<script src="../../js3/jquery.toastmessage.js" type="text/javascript"></script>
<script src="../../js3/message.js" type="text/javascript"></script>
<link rel="stylesheet" href="../js/colorbox.css" />
<script src="../../js3/jquery.colorbox.js"></script>

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../../css3/css/font-awesome.min.css">


<script type="text/javascript">
$(document).keydown(function(e) {
    switch(e.keyCode) { 
        case 13:
			parent.$('idOfDomElement').colorbox.close();
        break;
        case 112:
            showWarningToast('Ayuda aún no disponible...');
        break; 
	 }
});
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
		
		function aceptar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		</script>
	</head>
	<body>
	<input id="msg" value="<?php echo $mensaje_minimo;?>" type="hidden">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header"><?php echo $cabecera2; ?></div>
				<div id="frmBusqueda">
				<?php if ($minimo==1) { ?>
				<script>
				var msg='Los siguientes artículos estan bajo mínimo: '+document.getElementById("msg").value;
				showWarningToast(msg);
				</script>				
				<?php } ?> 				
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="85%" colspan="6" class="mensaje"><?php echo $mensaje;?></td>
					    </tr>
						<?php 
						 $sel_cliente="SELECT * FROM clientes WHERE codcliente='$codcliente'"; 
						  $rs_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cliente); ?>
						<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0>
						<tr>

							<td width="6%">Nombre</td>
						    <td width="27%"><?php echo mysqli_result($rs_cliente, 0, "nombre")." ".mysqli_result($rs_cliente, 0, "apellido")." - ".mysqli_result($rs_cliente, 0, "empresa");?></td>
						  <td>Nº&nbsp;factura</td>
						  <td colspan="2"><?php echo $codautofactura;?></td>	

							<td>Día&nbsp;de&nbsp;facturación&nbsp;

					<?php $tipof = array(1=>"1º", 2=>"2º", 3=>"3º", 4=>"4º");

					$x=1;

					foreach($tipof as $i) {
					  	if ( $x==$semanafacturacion) {
						echo $i;
						}
						$x++;
					}
					?>
					<?php $tipof = array(1=>"Lunes", 2=>"Martes", 3=>"Miércoles", 4=>"Jueves", 5=>"Viernes");

					$x=1;
					$NoEstado=0;
					foreach($tipof as $i) {
					  	if ( $x==$diafacturacion) {
							echo $i;
						}
						$x++;
					}
					?>
						</select>								
						</td>	
						<td>	
						<label>Estado
						<?php
						 if ( $activa==1 ) {
						?>
						<input type="checkbox" name="activa" value="1" checked> Activo
						<?php } else {
						?>
						<input type="checkbox" name="activa" value="1"> Activo
						<?php }
						?>
						<span></span>
						</label>&nbsp;							
						Acción&nbsp;
					<?php $tipof = array(1=>"Solo registrar", 2=>"Registrar y emitir", 3=>"Registrar y enviar", 4=>"Registrar, emitir y enviar");

					$x=1;
					$NoEstado=0;
					foreach($tipof as $i) {
					  	if ( $x==$emitida) {
							echo $i;
						}
						$x++;
					}
					?>
						</td>							  
						  			         					        					
						</tr>
						<tr>
				            <td width="5%">RUT</td>
				            <td><?php echo mysqli_result($rs_cliente, 0, "nif");?>
				            </td>
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
						&nbsp;Forma&nbsp;de&nbsp;pago&nbsp;
						<?php
					  	$query_fp="SELECT * FROM formapago WHERE codformapago='$codformapago' AND borrado=0 ORDER BY dias ASC";
						$res_fp=mysqli_query($GLOBALS["___mysqli_ston"], $query_fp);
						$contador=0;
					  ?>
								<?php if(mysqli_num_rows($res_fp)>0){ echo mysqli_result($res_fp, 0, "nombrefp");}?>
					
						</td>
						<td>Moneda</td><td>
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
							<td width="6%">Fecha</td>
						    <td width="27%">
						    <?php echo implota($fecha)?>
								</td>
				            <td width="3%">IVA</td>
				            <td ><?php echo $iva?>%</td>
				            <td colspan="3">Tipo&nbsp;cambio
								<label>U$S -> $&nbsp;</label><span>
								<?php echo $tipocambio;?></span>
								</td>
					</tr>
				  </table>
			  </div>	
			  <br style="line-height:5px">			  			  
					 <table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="54%" colspan="2" align="left">&nbsp;DESCRIPCIÓN</td>
							<td width="8%">CANTIDAD</td>
							<td width="8%">PRECIO</td>
							<td width="7%">DCTO %</td>
							<td width="6%">MONEDA</td>
							<td width="8%">IMPORTE</td>
						</tr>
					</table>
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
					  <?php
					  $baseimponible=0;
					  $filastotal=10;
					   $tipomon = array( 0=>"&nbsp;", 1=>"Pesos", 2=>"U\$S");
						$sel_lineas="SELECT autofactulinea.*,articulos.*,familias.nombre as nombrefamilia FROM autofactulinea,articulos,familias WHERE autofactulinea.codautofactura='$codautofactura' AND autofactulinea.codigo=articulos.codarticulo AND autofactulinea.codfamilia=articulos.codfamilia AND articulos.codfamilia=familias.codfamilia ORDER BY autofactulinea.numlinea ASC";
						$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
						for ($i = 0; $i < mysqli_num_rows($rs_lineas); $i++) {
							$numlinea=mysqli_result($rs_lineas, $i, "numlinea");
							$codfamilia=mysqli_result($rs_lineas, $i, "codfamilia");
							$nombrefamilia=mysqli_result($rs_lineas, $i, "nombrefamilia");
							$codarticulo=mysqli_result($rs_lineas, $i, "codarticulo");
							$detalles=mysqli_result($rs_lineas, $i, "detalles");
							$descripcion=mysqli_result($rs_lineas, $i, "descripcion");
							$referencia=mysqli_result($rs_lineas, $i, "referencia");
							$cantidad=mysqli_result($rs_lineas, $i, "cantidad");
							$precio=mysqli_result($rs_lineas, $i, "precio");
							$moneda=$tipomon[mysqli_result($rs_lineas, $i, "moneda")];
							$importe=mysqli_result($rs_lineas, $i, "importe");
							$baseimponible=$baseimponible+$importe;
							$descuento=mysqli_result($rs_lineas, $i, "dcto");
							if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>

									<tr class="<?php echo $fondolinea?>">
										<td width="54%"><?php echo $detalles?></td>
										<td width="8%" class="aCentro"><?php echo $cantidad;?></td>
										<td width="8%" class="aCentro"><?php echo $precio;?></td>
										<td width="8%" class="aCentro"><?php echo $descuento;?></td>
										<td width="10%" class="aCentro"><?php echo $moneda;?></td>
										<td width="8%" class="aCentro" ><?php echo $importe;?></td>									
									</tr>
					<?php
					$filastotal--;
					}
					for ($i = 0; $i <= $filastotal; $i++) {
					?>
									<tr>
										<td width="14%">&nbsp;</td>
										<td width="42%">&nbsp;</td>
										<td width="8%" class="aCentro">&nbsp;</td>
										<td width="8%" class="aCentro">&nbsp;</td>
										<td width="8%" class="aCentro">&nbsp;</td>
										<td width="10%" class="aCentro">&nbsp;</td>
										<td width="8%" class="aCentro" >&nbsp;</td>									
									</tr>
					<?php					
					
					}
					 ?>
					</table>
				  <?php
				  $baseimponibleaux=$baseimponible;
		  	$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".$iva;
			$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
			$ivavalor=mysqli_result($res_iva, 0, "valor");
		
	  if ($descuentogral==0 or $descuentogral=='') {
	  $baseimpuestos=$baseimponible*($ivavalor/100);
     $preciototal=$baseimponible+$baseimpuestos;
	  } else {
	  $baseimponibledescuento=$baseimponible/(1-$descuentogral/100);
	  $baseimponible=$baseimponible*(1-$descuentogral/100);
	  $baseimpuestos=$baseimponible*($ivavalor/100);
     $preciototal=$baseimponible+$baseimpuestos;
	  }				  
		  
		      $preciototal=number_format($preciototal,2);
			  	  ?>
			<table width="100%" border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
			<tr>
			<td align="rigth" valign="top">
				<table border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
				<tr>
				<td valign="top">Observaciones</td>
				<td valign="top" rowspan="3"><textarea name="observacion" id="observacion" rows="4" cols="40"> <?php echo $observacion;?></textarea>
				</td>
				<td colspan="6"></td>
			    <td class="busqueda">Sub-total</td>
				<td align="right"><div align="center">
				 <input type="text" class="cajaPequena2" id="monShow" value="<?php echo $monedai;?>" readonly>
			      <input class="cajaTotales" name="baseimponible" type="text" id="baseimponible" size="12" value="<?php echo number_format($baseimponibleaux,2);?>" align="right" readonly> 
		        </div></td>
				</tr>
				<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td class="busqueda">Descuento</td>
				<td class="busqueda"><input id="descuentogral" name="descuentogral" value="<?php echo $descuentogral;?>" class="cajaMinima"></td>
				<td class="busqueda">&nbsp;%</td>
				<td>	
				<input class="cajaTotales" name="baseimponibledescuento" type="text" id="baseimponibledescuento" value="<?php echo number_format($baseimponible,2);?>" size="12" align="right" readonly> 
				</td>
				<td class="busqueda">IVA</td>
				<td align="right"><div align="center">
				 <input type="text" class="cajaPequena2" id="monSho" value="<?php echo $monedai;?>" readonly>
			      <input class="cajaTotales" name="baseimpuestos" type="text" id="baseimpuestos" size="12" align="right" value="<?php echo number_format($baseimpuestos,2);?>" readonly>
		        </div></td>
 				</tr>
				<tr>
				<td></td>
				<td colspan="2">

				</td>
				<td colspan="4"></td>
				<td class="busqueda">Precio&nbsp;Total</td>
				<td align="right"><div align="center">
				 <input type="text" class="cajaPequena2" id="monSh" value="<?php echo $monedai;?>" readonly>
			      <input class="cajaTotales" name="preciototal" type="text" id="preciototal" size="12" align="right" value="<?php echo $preciototal;?>" readonly> 
		        </div></td>
				</tr>
				<tr><td></td>				<td colspan="2">
					<div align="center">
						<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-window-close-o" aria-hidden="true"></i>&nbsp;Salir</button>					
					</div>
				</td></tr> 				
				</table>
				</td><td >
				
			  </tr>
		</table>				
			  </div>
		  </div>
		</div>
	</body>
</html>