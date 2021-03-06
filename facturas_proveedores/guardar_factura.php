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

$mensaje_minimo='';

$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$codfacturatmp=$_POST["codfacturatmp"];
$codfactura=$_POST["cfactura"];
$codproveedor=$_POST["codproveedor"];
$fecha=explota($_POST["fecha"]);

$moneda=$_POST["amoneda"];
$tipo=$_POST["atipo"];


$iva=$_POST["impuesto"];
$minimo=0;

if ($accion=="alta") {
	$query_comprobar="SELECT * FROM facturasp WHERE codfactura='$codfactura' AND codproveedor='$codproveedor'";
	$rs_comprobar=mysqli_query($GLOBALS["___mysqli_ston"], $query_comprobar);
	if (mysqli_num_rows($rs_comprobar) > 0 ) {
?>
			<script>
				alert ("No se puede dar de alta este numero de factura con este proveedor, ya existe uno en el sistema.");
				parent.$('idOfDomElement').colorbox.close();
			</script>
<?php
	} else {
			$query_operacion="INSERT INTO facturasp (codfactura, codproveedor, fecha, iva, estado, moneda, tipo) VALUES ('$codfactura', '$codproveedor', '$fecha', '$iva', '1', '$moneda', '$tipo')";					
			$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
			
			if ($rs_operacion) { $mensaje="La factura ha sido dada de alta correctamente"; }
			$query_tmp="SELECT * FROM factulineaptmp WHERE codfactura='$codfacturatmp' ORDER BY numlinea ASC";
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
				if($codigo>0) {
					$sel_insertar="INSERT INTO factulineap (codfactura,codproveedor,numlinea,codfamilia,codigo,cantidad,precio,importe,dcto) VALUES 
					('$codfactura','$codproveedor','$numlinea','$codfamilia','$codigo','$cantidad','$precio','$importe','$dcto')";
					$rs_insertar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insertar);
					
					/*Para cada artículo comprado actualizo el stock en la tabla artículos, Nota: el artículo ya existe en la tabla articulos*/		
					$sel_articulos="UPDATE articulos SET stock=(stock+'$cantidad') WHERE codarticulo='$codigo'"; // AND codfamilia='$codfamilia'";
					$rs_articulos=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulos);
					
					/*Mantengo actualizado el precio de la última compra compra*/
					$sel_comprobar="SELECT codarticulo FROM artpro WHERE codarticulo='".$codigo."' AND codfamilia='$codfamilia' AND codproveedor='".$codproveedor."'";
					$rs_comprobar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_comprobar);
					
					//$precio=sprintf("%01.2f",$precio);
					if (mysqli_num_rows($rs_comprobar) > 0) {
						$sentencia="UPDATE artpro SET precio='".$precio."' WHERE codarticulo='".$codigo."' AND codfamilia='$codfamilia' AND codproveedor='".$codproveedor."'";
					} else {
						$sentencia="INSERT into artpro (codarticulo,codfamilia,codproveedor,precio) VALUES ('$codigo','$codfamilia','$codproveedor','$precio')";		
					}
					/*Fin*/
					
					$ejecutar=mysqli_query($GLOBALS["___mysqli_ston"], $sentencia);
					$sentencia2="UPDATE articulos SET precio_compra='".$precio."' AND (codproveedor1='$codproveedor' or codproveedor2='$codproveedor') WHERE codarticulo='$codigo'";// AND codfamilia='$codfamilia'";
					$ejecutar=mysqli_query($GLOBALS["___mysqli_ston"], $sentencia2);
				}
				$contador++;
			}
		  	$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".$iva;
			$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
			$baseimpuestos=$baseimponible*(mysqli_result($res_iva, 0, "valor")/100);
			$preciototal=$baseimponible+$baseimpuestos;
			//$preciototal=number_format($preciototal,2);	
			$sel_act="UPDATE facturasp SET totalfactura='$preciototal' WHERE codfactura='$codfactura'";
			$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $sel_act);
			$baseimpuestos=0;
			$preciototal=0;
			$baseimponible=0;
			$cabecera1="Inicio >> Compras &gt;&gt; Nueva Factura ";
			$cabecera2="INSERTAR FACTURA";
		}
} 

if ($accion=="modificar") {
	$codfactura=$_POST["codfactura"];
	$act_albaran="UPDATE facturasp SET fecha='$fecha', iva='$iva', moneda='$moneda', tipo='$tipo' WHERE codfactura='$codfactura' AND codproveedor='$codproveedor'";
	$rs_albaran=mysqli_query($GLOBALS["___mysqli_ston"], $act_albaran);
	$sel_lineas = "SELECT codigo,codfamilia,cantidad FROM factulineap WHERE codfactura='$codfactura' AND codproveedor='$codproveedor' order by numlinea";
	$rs_lineas = mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
	$contador=0;
	while ($contador < mysqli_num_rows($rs_lineas)) {
		$codigo=mysqli_result($rs_lineas, $contador, "codigo");
		$codfamilia=mysqli_result($rs_lineas, $contador, "codfamilia");
		$cantidad=mysqli_result($rs_lineas, $contador, "cantidad");
		$sel_actualizar="UPDATE `articulos` SET stock=(stock+'$cantidad') WHERE codarticulo='$codigo'";
		$rs_actualizar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_actualizar);
		$contador++;
	}
	$sel_borrar = "DELETE FROM factulineap WHERE codfactura='$codfactura' AND codproveedor='$codproveedor'";
	$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);
	$sel_lineastmp = "SELECT * FROM factulineaptmp WHERE codfactura='$codfacturatmp' ORDER BY numlinea";
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
		if($codigo>0) {	
			$sel_insert = "INSERT INTO factulineap (codfactura,codproveedor,numlinea,codigo,codfamilia,cantidad,precio,importe,dcto) 
			VALUES ('$codfactura','$codproveedor','','$codigo','$codfamilia','$cantidad','$precio','$importe','$dcto')";
	
			$rs_insert = mysqli_query($GLOBALS["___mysqli_ston"], $sel_insert);
			
			$sel_actualiza="UPDATE articulos SET stock=(stock+'$cantidad') WHERE codarticulo='$codigo'"; // AND codfamilia='$codfamilia'";
			$rs_actualiza = mysqli_query($GLOBALS["___mysqli_ston"], $sel_actualiza);
			
			$sel_bajominimo = "SELECT codarticulo,codfamilia,stock,stock_minimo,descripcion FROM articulos WHERE codarticulo='$codigo'";
			$rs_bajominimo= mysqli_query($GLOBALS["___mysqli_ston"], $sel_bajominimo);
			if($rs_bajominimo) {
			$stock=@mysqli_result($rs_bajominimo, 0, "stock");
			$stock_minimo=@mysqli_result($rs_bajominimo, 0, "stock_minimo");
			$descripcion=@mysqli_result($rs_bajominimo, 0, "descripcion");
			}
		
			if (($stock < $stock_minimo) or ($stock <= 0))
		   { 
			  $mensaje_minimo=$mensaje_minimo . " " . $descripcion."<br>";
			  $minimo=1;
		   };
		}
		$contador++;
	}
		  	$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".$iva;
			$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
			$baseimpuestos=$baseimponible*(mysqli_result($res_iva, 0, "valor")/100);
	$preciototal=$baseimponible+$baseimpuestos;
	//$preciototal=number_format($preciototal,2);	
	$sel_act="UPDATE facturasp SET totalfactura='$preciototal' WHERE codfactura='$codfactura'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $sel_act);
	$baseimpuestos=0;
	$preciototal=0;
	$baseimponible=0;
	if ($rs_act) { $mensaje="Los datos de la factura han sido modificados correctamente"; }
	$cabecera1="Inicio >> Compras &gt;&gt; Modificar Factura ";
	$cabecera2="MODIFICAR FACTURA";
}

if ($accion=="baja") {
	$codfactura=$_POST["codfactura"];
	$codproveedor=$_POST["codproveedor"];
	$query="SELECT * FROM factulineap WHERE codfactura='$codfactura' AND codproveedor='$codproveedor' ORDER BY numlinea ASC";
	$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$contador=0;
	$baseimponible=0;
	while ($contador < mysqli_num_rows($rs_tmp)) {
		$codfamilia=mysqli_result($rs_tmp, $contador, "codfamilia");
		$codigo=mysqli_result($rs_tmp, $contador, "codigo");
		$cantidad=mysqli_result($rs_tmp, $contador, "cantidad");
		$sel_articulos="UPDATE articulos SET stock=(stock-'$cantidad') WHERE codarticulo='$codigo'";
		$rs_articulos=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulos);
		$contador++;
	}
	if ($rs_tmp) { $mensaje="La factura ha sido eliminada correctamente"; }
	$cabecera1="Inicio >> Compras &gt;&gt; Eliminar Factura";
	$cabecera2="ELIMINAR FACTURA";
	$query_mostrar="SELECT * FROM facturasp WHERE codfactura='$codfactura' AND codproveedor='$codproveedor'";
	$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);
	$codproveedor=mysqli_result($rs_mostrar, 0, "codproveedor");
	$fecha=mysqli_result($rs_mostrar, 0, "fecha");

		  	$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".mysqli_result($rs_mostrar, 0, "iva");
			$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
	$iva=mysqli_result($res_iva, 0, "valor");
}

			$sel_resultado="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
		   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
			$moneda1=mysqli_result($res_resultado,0, "simbolo");
			$moneda2=mysqli_result($res_resultado, 1, "simbolo");

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../css3/estilos.css" type="text/css" rel="stylesheet">
		
		<script src="../js3/calendario/jscal2.js"></script>
		<script src="../js3/calendario/lang/es.js"></script>
		<link rel="stylesheet" type="text/css" href="../js3/calendario/css/jscal2.css" />
		<link rel="stylesheet" type="text/css" href="../js3/calendario/css/border-radius.css" />
		<link rel="stylesheet" type="text/css" href="../js3/calendario/css/win2k/win2k.css" />		
		<script src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="../js3/jquery.colorbox.js"></script>

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">	
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
		
		function imprimir(codfactura,codproveedor) {
			window.open("../fpdf/imprimir_factura_proveedor.php?codfactura="+codfactura+"&codproveedor="+codproveedor);
		}
		
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header"><?php echo $cabecera2?></div>
				<div id="frmBusqueda">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
					<tr>
					<td width="85%" colspan="3" class="mensaje"><?php echo $mensaje;?></td>
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
						  <td colspan="2"><?php echo $codfactura?></td>
					  </tr>
					  <tr>
						  <td>Fecha</td>
						  <td colspan="2"><?php echo implota($fecha)?></td>
					  </tr>
					  <tr>
						  <td>IVA</td>
						  <td colspan="2"><?php echo $iva?> %</td>
					  </tr>
					  <tr>
						  <td></td>
						  <td colspan="2"></td>
					  </tr>
					  	</table>
					</td>
					<td>
					<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
						<tr>
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
					  </tr>
					  <tr>
						  <td>Moneda</td>
<td> 
						<select  name="amoneda" id="amoneda" class="cajaPequena2" data-index="5">
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
						  <td>&nbsp;</td>
						  <td colspan="2">&nbsp;</td>
					  </tr>
					  	</table>
					</td></tr>				  
				  </table>
			  </div>
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
					  <?php $sel_lineas="SELECT factulineap.*, articulos.referencia,articulos.descripcion, familias.nombre as nombrefamilia FROM factulineap,articulos,familias WHERE factulineap.codfactura='$codfactura' AND factulineap.codproveedor='$codproveedor' AND factulineap.codigo=articulos.codarticulo AND factulineap.codfamilia=articulos.codfamilia AND articulos.codfamilia=familias.codfamilia ORDER BY factulineap.numlinea ASC";
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

				  <?php
		  	$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".$iva;
			$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
			$baseimpuestos=$baseimponible*(mysqli_result($res_iva, 0, "valor")/100);
			      $preciototal=$baseimponible+$baseimpuestos;
			      $preciototal=number_format($preciototal,2);
			      
					$tipof = array(  1=>$moneda1, 2=>$moneda2);
					foreach ($tipof as $key => $monedai ) {
					  	if ( $moneda==$key ) {
							//echo "$monedai";
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
					  $query="DELETE FROM facturasp WHERE codfactura='$codfactura' AND codproveedor='$codproveedor'";
						$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
						$borrar_lineas="DELETE FROM factulineap WHERE codfactura='$codfactura' AND codproveedor='$codproveedor'";
						$rs_borrar_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $borrar_lineas);
				} ?>
				<div>
				<br style="line-height:5px">
					<div align="center">
						<button class="boletin" onClick="imprimir('<?php echo $codfactura?>',<?php echo $codproveedor ?>);" onMouseOver="style.cursor=cursor"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir</button>
						<button class="boletin" onClick="aceptar();" onMouseOver="style.cursor=cursor"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Aceptar</button>
				        </div>
					</div>
			  </div>
		  </div>
		</div>
	</body>
</html>
