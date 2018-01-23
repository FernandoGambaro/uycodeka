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
 
//ini_set('display_errors', 'On');
//error_reporting(E_ALL);

////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 
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
////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 

include ("../conectar.php"); 
include ("../funciones/fechas.php"); 


$codarticulo=$_GET["codarticulo"];
$cadena_busqueda=$_GET["cadena_busqueda"];

$query="SELECT * FROM articulos WHERE codarticulo='$codarticulo'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
$codigobarras=mysqli_result($rs_query, 0, "codigobarras");

?>
<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script src="js/jquery.min.js"></script>
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">
		
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
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="frmBusqueda">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
	
					<tr>					
					<td valign="top" width="50%">
					<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="15%">C&oacute;digo</td>
							<td width="58%"><?php echo mysqli_result($rs_query, 0, "codarticulo");?></td>

						</tr>
						<tr>
							<td width="15%">Referencia</td>
							<td width="58%"><?php echo mysqli_result($rs_query, 0, "referencia");?></td>
				        </tr>
						<?php
						$codfamilia=mysqli_result($rs_query, 0, "codfamilia");
					  	$query_familia="SELECT * FROM familias WHERE codfamilia='$codfamilia'";
						$res_familia=mysqli_query($GLOBALS["___mysqli_ston"], $query_familia);
						$nombrefamilia=mysqli_result($res_familia, 0, "nombre");
					  ?>
						<tr>
							<td width="15%">Familia</td>
							<td width="58%"><?php echo $nombrefamilia?></td>
				        </tr>
						<tr>
							<td width="15%">Descripci&oacute;n</td>
						    <td width="58%"><?php echo mysqli_result($rs_query, 0, "descripcion");?></td>
				        </tr>
						<tr>
						  <td>Impuesto</td>
						  <td><?php echo mysqli_result($rs_query, 0, "impuesto");?> %</td>
				      </tr>
					  <?php
					  $codproveedor1=mysqli_result($rs_query, 0, "codproveedor1");
					  $codproveedor2=mysqli_result($rs_query, 0, "codproveedor2");
					  	if ($codproveedor1<>0) {
							$query_proveedor="SELECT * FROM proveedores WHERE codproveedor='$codproveedor1'";
							$res_proveedor=mysqli_query($GLOBALS["___mysqli_ston"], $query_proveedor);
							$nombreproveedor=mysqli_result($res_proveedor, 0, "nombre");
						} else {
							$nombreproveedor="Sin determinar";
						}
					  ?>
						<tr>
							<td width="15%">Proveedor1</td>
							<td width="58%"><?php echo $nombreproveedor?></td>
				        </tr>
					<?php
					  	if ($codproveedor2<>0) {
							$query_proveedor="SELECT * FROM proveedores WHERE codproveedor='$codproveedor2'";
							$res_proveedor=mysqli_query($GLOBALS["___mysqli_ston"], $query_proveedor);
							$nombreproveedor=mysqli_result($res_proveedor, 0, "nombre");
						} else {
							$nombreproveedor="Sin determinar";
						}
					  ?>
						<tr>
							<td width="15%">Proveedor2</td>
							<td width="58%"><?php echo $nombreproveedor?></td>
				        </tr>
						<tr>
							<td width="15%">Descripci&oacute;n&nbsp;corta</td>
						    <td width="58%"><?php echo mysqli_result($rs_query, 0, "descripcion_corta");?></td>
				        </tr>
						<?php
						$codubicacion=mysqli_result($rs_query, 0, "codubicacion");
						
					  	if ($codubicacion<>0) {
							$query_ubicacion="SELECT * FROM ubicaciones WHERE codubicacion='$codubicacion'";
							$res_ubicacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_ubicacion);
							$nombreubicacion=mysqli_result($res_ubicacion, 0, "nombre");
						} else {
							$nombreubicacion="Sin determinar";
						}
					  ?>
						<tr>
							<td width="15%">Ubicaci&oacute;n</td>
							<td width="58%"><?php echo $nombreubicacion;?></td>
				        </tr>
						<tr>
							<td>Stock</td>
							<td><?php echo mysqli_result($rs_query, 0, "stock");?> unidades</td>
					    </tr>
						<tr>
							<td>Stock&nbsp;minimo</td>
							<td><?php echo mysqli_result($rs_query, 0, "stock_minimo");?> unidades</td>
					    </tr>
						<tr>
							<td>Aviso&nbsp;M&iacute;nimo</td>
							<td colspan="2"><?php if (mysqli_result($rs_query, 0, "aviso_minimo")==0) { echo "No"; } else { echo "Si"; }?></td>
						</tr>
						<tr>
							<td width="15%">Fecha&nbsp;de&nbsp;alta</td>
							<td colspan="2"><?php echo implota(mysqli_result($rs_query, 0, "fecha_alta"));?></td>
					    </tr>
						<tr>
							<td width="15%">Fecha&nbsp;de&nbspvencimiento</td>
							<td colspan="2"><?php echo implota(mysqli_result($rs_query, 0, "fechavencimiento"));?></td>
					    </tr>					    
						<tr>
							<td>Codigo&nbsp;de&nbsp;barras</td>
							<td colspan="2">
							<img src="../barcode/gd.php?text=<?php echo $codigobarras;?>&height=50"></td></td>
						</tr>

						</table></td><td width="500%" valign="top">
						<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
						
						<tr>
							<td width="15%">Datos&nbsp;del&nbsp;producto</td>
							<td colspan="2"><?php echo mysqli_result($rs_query, 0, "datos_producto");?></td>
					    </tr>
						<?php
						$codembalaje=mysqli_result($rs_query, 0, "codembalaje");
					  	if ($codembalaje<>0) {
							$query_embalaje="SELECT * FROM embalajes WHERE codembalaje='$codembalaje'";
							$res_embalaje=mysqli_query($GLOBALS["___mysqli_ston"], $query_embalaje);
							$nombreembalaje=mysqli_result($res_embalaje, 0, "nombre");
						} else {
							$nombreembalaje="Sin determinar";
						}
					  ?>
						<tr>
							<td width="15%">Embalaje</td>
							<td colspan="2"><?php echo $nombreembalaje?></td>
					    </tr>
						<tr>
							<td>Unidades&nbsp;por&nbsp;caja</td>
							<td colspan="2"><?php echo mysqli_result($rs_query, 0, "unidades_caja");?> unidades</td>
						</tr>
						<tr>
							<td>Preguntar&nbsp;precio&nbsp;ticket</td>
							<td colspan="2"><?php if (mysqli_result($rs_query, 0, "precio_ticket")==0) { echo "No"; } else { echo "Si"; }?></td>
						</tr>
						<tr>
							<td>Modificar&nbsp;descrip.&nbsp;ticket</td>
							<td colspan="2"><?php if (mysqli_result($rs_query, 0, "modificar_ticket")==0) { echo "No"; } else { echo "Si"; }?></td>
						</tr>
						<tr>
							<td>Observaciones</td>
							<td colspan="2"><?php echo mysqli_result($rs_query, 0, "observaciones");?></td>
						</tr>
						<tr>
						<td>Moneda</td><td width="26%"> <select onchange="cambio();" name="amoneda" id="amoneda" class="cajaPequena2">
						<?php $tipof = array(  1=>"Pesos", 2=>"U\$S");
						$moneda=mysqli_result($rs_query, 0, "moneda");
						if ($moneda==" ")
						{
						echo '<OPTION value="" selected>Selecione uno</option>';
						}
						foreach ($tipof as $key => $i ) {
						  	if ( $moneda==$key ) {
								echo "<OPTION value=$key selected>$i</option>";
							} else {
								echo "<OPTION value=$key>$i</option>";
							}
						}
						?>
						</select></td>
						</tr>
						<tr>
							<td>Precio&nbsp;de&nbsp;compra</td>
							<td colspan="2"><?php echo mysqli_result($rs_query, 0, "precio_compra");?></td>
						</tr>
						<tr>
							<td>Precio&nbsp;almac&eacute;n</td>
							<td colspan="2"><?php echo mysqli_result($rs_query, 0, "precio_almacen");?></td>
						</tr>												
						<tr>
							<td>Precio&nbsp;en&nbsp;tienda</td>
							<td colspan="2"><?php echo mysqli_result($rs_query, 0, "precio_tienda");?></td>
						</tr>

						<tr>
							<td>Precio con iva</td>
							<td colspan="2"><?php echo mysqli_result($rs_query, 0, "precio_iva");?></td>
						</tr>
				      <tr>
						  <td colspan="4"><fieldset style="width:80%">
						  <legend>Ubicación</legend>
						  <table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0><tr><td>
						  Sector</td><td><?php echo mysqli_result($rs_query, 0, "sector");?></td><td>
						  Pasillo</td><td><?php echo mysqli_result($rs_query, 0, "pasillo");?></td></tr><tr><td>
						  Módulo</td><td><?php echo mysqli_result($rs_query, 0, "modulo");?></td><td>
						  Estante</td><td><?php echo mysqli_result($rs_query, 0, "estante");?></td></tr><tr><td>
						  </tr>
						  </table>
						  </fieldset>
						  </td>
					</tr>							
					</table>
					</td>
					
					<td valign="top">
					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
					  <tr>
						  <td valign="top" colspan="2">
						  <img src="../fotos/<?php echo mysqli_result($rs_query, 0, 'imagen');?>" style="height: 200px;" >
						  </td>
				      </tr>
				      <tr>
				        	<td>Cód.&nbsp;Cont.&nbsp;Com.</td><td><?php
				        	$plancuentac=mysqli_result($rs_query, 0, "plancuentac");
				        	if($plancuentac!='') {
								$query_busqu="SELECT * FROM plandecuentas WHERE borrado=0 AND codplan='".$plancuentac."'";
								$rs_busqu=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqu);
								echo $nombre=mysqli_result($rs_busqu, 0, "codplan").' - '. mysqli_result($rs_busqu, 0, "nombre");
							}	
				        	?>
				        	</td>
				        	</tr><tr><td>
				        	Cód.&nbsp;Cont.&nbsp;Ven.</td><td><?php
				        	$plancuentav=mysqli_result($rs_query, 0, "plancuentav");
				        	if($plancuentav!='') {
								$query_busqu="SELECT * FROM plandecuentas WHERE borrado=0 AND codplan='".$plancuentav."'";
								$rs_busqu=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqu);
								echo $nombre=mysqli_result($rs_busqu, 0, "codplan").' - '. mysqli_result($rs_busqu, 0, "nombre");
							}	
							?></td>
						</tr>	
						<td valign="top">Código&nbsp;QR<br>
							<?php
							$texto="Código-".$codigobarras."-";
							$texto.="Sector-".@$sector."-Pasillo-".@$pasillo."-Modulo-".@$modulo."-Estante-".@$estante;
							$width = $height = 150;
							$file = "../tmp/qr".$codigobarras.".png";
							
							 echo "<img src='../barcode/qrcode.php?texto=".$texto."&width=".$width."&height=".$height."&file=".$file."&accion=u'>"; ?></td>
						</tr>										      
					</table>
					
					</td></table>
			  </div>
				<br style="line-height:5px">			  
				<div>
						<button class="boletin" onClick="aceptar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
			  </div>			  
			  </div>
			 </div>
		  </div>
		
	</body>