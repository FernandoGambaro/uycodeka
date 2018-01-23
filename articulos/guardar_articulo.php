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

include ("../conectar.php"); 
include ("../funciones/fechas.php");

////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 
include ("../funciones/image_resize.php"); 




$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$referencia=$_POST["Areferencia"];
$codfamilia=$_POST["AcboFamilias"];
$plancuentac=$_POST["Aplancuentac"];
$plancuentav=$_POST["Aplancuentav"];
$descripcion=$_POST["Adescripcion"];
$codimpuesto=$_POST["AcboImpuestos"];
$codproveedor1=$_POST["acboProveedores1"];
$codproveedor2=$_POST["acboProveedores2"];
$descripcion_corta=$_POST["Adescripcion_corta"];
$codubicacion=$_POST["AcboUbicacion"];
$stock_minimo=$_POST["nstock_minimo"];
$stock=$_POST["nstock"];
$aviso_minimo=$_POST["aaviso_minimo"];
$datos=$_POST["adatos"];
$fecha=$_POST["fecha"];
$fechalis=$fecha;
if ($fecha<>"") { $fecha=explota($fecha); } else { $fecha="0000-00-00"; }
$fechavencimiento=$_POST["fechavencimiento"];
$fechavencimientolis=$fechavencimiento;
if ($fechavencimiento<>"") { $fechavencimiento=explota($fechavencimiento); } else { $fechavencimiento="0000-00-00"; }

$codembalaje=$_POST["AcboEmbalaje"];
$unidades_caja=$_POST["nunidades_caja"];
$comision=$_POST['comision'];
$precio_ticket=$_POST["aprecio_ticket"];
$modif_descrip=$_POST["amodif_descrip"];
$observaciones=$_POST["aobservaciones"];
$moneda=$_POST["amoneda"];
$precio_compra=$_POST["qprecio_compra"];
$precio_almacen=$_POST["qprecio_almacen"];
$precio_tienda=$_POST["qprecio_tienda"];
//$pvp=$_POST["qpvp"];
$precio_iva=$_POST["qprecio_iva"];

$sector=$_POST["sector"];
$pasillo=@$_POST["pasillo"];
$modulo=$_POST["modulo"];
$estante=$_POST["stante"];

$codigobarras=$_POST["codigobarras"];

$fileerror="";

if ($accion=="alta") {
	$sel_comp="SELECT * FROM articulos WHERE referencia='$referencia'";
	$rs_comp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_comp);
	if (mysqli_num_rows($rs_comp) > 0) {
		
		?><script >
				alert ("No se puede dar de alta a este articulo, ya existe uno con esta referencia." );
				parent.$('idOfDomElement').colorbox.close();
				</script>
				
		<?php
		
	} else {
		$consultaprevia = "SELECT max(codarticulo) as maximo FROM articulos";
		$rs_consultaprevia=mysqli_query($GLOBALS["___mysqli_ston"], $consultaprevia);
		$codarticulo=mysqli_result($rs_consultaprevia, 0, "maximo");
		if ($codarticulo=="") { $codarticulo=0; }
		$codarticulo++;
		
		
$foto_name="";

   if ($_FILES["foto"]["error"] == 0)
    {
    	
 $mimetypes = array("image/jpeg", "image/pjpeg", "image/gif", "image/png");
  // Variables de la foto/*
  $name = $_FILES["foto"]["name"];
  $type = $_FILES["foto"]["type"];
  $tmp_name = $_FILES["foto"]["tmp_name"];
  $size = $_FILES["foto"]["size"];
  // Verificamos si el archivo es una imagen válida/*

  if(!in_array($type, $mimetypes)) { 
    $fileerror= " - El archivo que subio no es una imagen válida";
  } else {
        $fotoname= "foto".$codarticulo .$_FILES["foto"]["name"];
        if (file_exists("../fotos/" ."foto".$codarticulo . $_FILES["foto"]["name"]))
        {
            $fileerror= " - foto".$codarticulo .$_FILES["foto"]["name"] . " existe. ";
        }
        else
        {
			if ( isset($_FILES['foto']) ) {


			$sql_datos="SELECT serveraux FROM `datos` where `coddatos` = 0";
			$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
			$serveraux=mysqli_result($rs_datos, 0, "serveraux");

			
			 $filename  = $_FILES['foto']['tmp_name'];
			 $handle    = fopen($filename, "r");
			 $data      = fread($handle, filesize($filename));
			 $post_array = array(
			   'file' => base64_encode($data),
			   'name' => "foto".$codarticulo . $_FILES["foto"]["name"]
			 );
			
			 
			    $ch = curl_init();
			    curl_setopt($ch, CURLOPT_HEADER, 0);
			    curl_setopt($ch, CURLOPT_VERBOSE, 0);
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
			    curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_URL, $serveraux.'/articulos/handle.php' );
				
			    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_array);
			    $response = curl_exec($ch);
			    
				move_uploaded_file($_FILES["foto"]["tmp_name"], "../fotos/"."foto".$codarticulo . $_FILES["foto"]["name"]);
				$fileerror=resize_image('max',"../fotos/"."foto".$codarticulo . $_FILES["foto"]["name"],"../fotos/"."foto".$codarticulo . $_FILES["foto"]["name"],200,200);
			    
			}			    
        }
      }
    }

		if (@$fotoname!='')
		{
		   @$foto_name="imagen='".$fotoname."', ";
		};
		
		@$query_operacion="INSERT INTO articulos (codarticulo, codfamilia, plancuentac, plancuentav, referencia, descripcion, impuesto, codproveedor1, codproveedor2, descripcion_corta, codubicacion, stock, stock_minimo, aviso_minimo, datos_producto, fecha_alta, fecha_vencimiento, codembalaje, unidades_caja, comision, precio_ticket, modificar_ticket, observaciones, precio_compra, precio_almacen, precio_tienda, precio_iva, moneda, codigobarras, imagen, sector, pasillo, modulo, estante, borrado) 
						VALUES (null, '$codfamilia', '$plancuentac','$plancuentav', '$referencia', '$descripcion', '$codimpuesto', '$codproveedor1', '$codproveedor2', '$descripcion_corta', '$codubicacion', '$stock', '$stock_minimo', '$aviso_minimo', '$datos', '$fecha', '$fechavencimiento', '$codembalaje', '$unidades_caja', '$comision', '$precio_ticket', '$modificar_ticket', '$observaciones', '$precio_compra', '$precio_almacen', '$precio_tienda', '$precio_iva', '$moneda', '$codigobarras', '$fotoname', '$sector', '$pasillo', '$modulo', '$estante', '0')";				
		$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
		
		if (trim($codigobarras)=='') {
			$codarticulo=((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
			$codaux=$codarticulo;
			while (strlen($codaux)<4) {
				$codaux="0".$codaux;
			}
			/*/ el 0000 representa el código de la empresa*/
			$codigobarras="3773".$codaux;
			@$pares=$codigobarras[0] + $codigobarras[2] + $codigobarras[4] + $codigobarras[6] + $codigobarras[8] + $codigobarras[10];
			@$impares=$codigobarras[1] + $codigobarras[3] + $codigobarras[5] + $codigobarras[7] + $codigobarras[9] + $codigobarras[11];
			$impares=$impares * 3;
			$total=$impares + $pares;
			$resto = $total % 10;
				if($resto == 0){
					$valor = 0;
				}else{
					$valor = 10 - $resto;
				}
			$codigobarras=$codigobarras."".$valor;
			$sel_actualizar="UPDATE articulos SET codigobarras='$codigobarras' WHERE codarticulo='$codarticulo'";
			$rs_actualizar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_actualizar);
		}
		if ($rs_operacion) { $mensaje="El articulo ha sido dado de alta correctamente"; }
		$cabecera1="Inicio >> Articulos &gt;&gt; Nuevo Articulo ";
		$cabecera2="INSERTAR ARTICULO ";
		}
}

if ($accion=="modificar") {
	$codarticulo=$_POST["id"];

$foto_name="";
$fileerror=$_FILES["foto"]["error"];
   if ($_FILES["foto"]["error"] == 0)
    {
    	
 $mimetypes = array("image/jpeg", "image/pjpeg", "image/gif", "image/png");
  // Variables de la foto/*
  $name = $_FILES["foto"]["name"];
  $type = $_FILES["foto"]["type"];
  $tmp_name = $_FILES["foto"]["tmp_name"];
  $size = $_FILES["foto"]["size"];
  // Verificamos si el archivo es una imagen válida/*

  if(!in_array($type, $mimetypes)) { 
   $fileerror= " - El archivo que subio no es una imagen válida";
  } else {
        $fotoname= "foto".$codarticulo .$_FILES["foto"]["name"];
        if (file_exists("../fotos/" ."foto".$codarticulo . $_FILES["foto"]["name"]))
        {
            $fileerror= " - foto".$codarticulo .$_FILES["foto"]["name"] . " ya existe. ";
        }
        else
        {
			if ( isset($_FILES['foto']) ) {

			$sql_datos="SELECT serveraux FROM `datos` where `coddatos` = 0";
			$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
			$serveraux=mysqli_result($rs_datos, 0, "serveraux");

			 $filename  = $_FILES['foto']['tmp_name'];
			 $handle    = fopen($filename, "r");
			 $data      = fread($handle, filesize($filename));
			 $post_array = array(
			   'file' => base64_encode($data),
			   'name' => $fotoname
			 );
			
			 
			    $ch = curl_init();
			    curl_setopt($ch, CURLOPT_HEADER, 0);
			    curl_setopt($ch, CURLOPT_VERBOSE, 0);
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
			    curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_URL, $serveraux.'/articulos/handle.php' );
				
			    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_array);
			    $response = curl_exec($ch);
			
				move_uploaded_file($_FILES["foto"]["tmp_name"], "../fotos/"."foto".$codarticulo . $_FILES["foto"]["name"]);
				$fileerror=resize_image('max',"../fotos/"."foto".$codarticulo . $_FILES["foto"]["name"],"../fotos/"."foto".$codarticulo . $_FILES["foto"]["name"],200,200);
			    
			}

     }
      }
    } 
		if (@$fotoname!='')
		{
		   $foto_name="imagen='".$fotoname."', ";
		};

	$query="UPDATE  `articulos` SET codfamilia='$codfamilia', plancuentac='$plancuentac', plancuentav='$plancuentav', referencia='$referencia', descripcion='$descripcion', impuesto='$codimpuesto', codproveedor1='$codproveedor1', 
	codproveedor2='$codproveedor2', descripcion_corta='$descripcion_corta', codubicacion='$codubicacion', stock='$stock', stock_minimo='$stock_minimo', 
	aviso_minimo='$aviso_minimo', datos_producto='$datos', fecha_alta='$fecha', fecha_vencimiento='$fechavencimiento', codembalaje='$codembalaje', unidades_caja='$unidades_caja', comision='$comision', precio_ticket='$precio_ticket', 
	modificar_ticket='$modif_descrip', observaciones='$observaciones', precio_compra='$precio_compra', precio_almacen='$precio_almacen', precio_tienda='$precio_tienda', 
	precio_iva='$precio_iva', moneda='$moneda', sector='$sector', pasillo='$pasillo', modulo='$modulo', estante='$estante', codigobarras='$codigobarras', ".$foto_name." borrado=0 WHERE  `articulos`.`codarticulo` ='$codarticulo'";
	
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="Los datos del articulo han sido modificados correctamente"; }
	$cabecera1="Inicio >> Articulos &gt;&gt; Modificar Articulo ";
	$cabecera2="MODIFICAR ARTICULO ".$fileerror;
	$sel_img="SELECT imagen,codigobarras FROM articulos WHERE codarticulo='$codarticulo'";
	$rs_img=mysqli_query($GLOBALS["___mysqli_ston"], $sel_img);
	$fotoname=mysqli_result($rs_img, 0, "imagen");
	$codigobarras=mysqli_result($rs_img, 0, "codigobarras");
}

if ($accion=="baja") {
	$codarticulo=$_GET["codarticulo"];
	$query="UPDATE articulos SET borrado=1 WHERE codarticulo='$codarticulo'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="El articulo ha sido eliminado correctamente"; }
	$cabecera1="Inicio >> Articulos &gt;&gt; Eliminar Articulo ";
	$cabecera2="ELIMINAR ARTICULO ";
	$query_mostrar="SELECT * FROM articulos WHERE codarticulo='$codarticulo'";
	$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);
	$codarticulo=mysqli_result($rs_mostrar, 0, "codarticulo");
	$referencia=mysqli_result($rs_mostrar, 0, "referencia");
	$codfamilia=mysqli_result($rs_mostrar, 0, "codfamilia");
	$plancuentac=mysqli_result($rs_mostrar, 0, "plancuentac");
	$plancuentav=mysqli_result($rs_mostrar, 0, "plancuentav");
	$descripcion=mysqli_result($rs_mostrar, 0, "descripcion");
	$codimpuesto=mysqli_result($rs_mostrar, 0, "impuesto");
	$codproveedor1=mysqli_result($rs_mostrar, 0, "codproveedor1");
	$codproveedor2=mysqli_result($rs_mostrar, 0, "codproveedor2");
	$descripcion_corta=mysqli_result($rs_mostrar, 0, "descripcion_corta");
	$codubicacion=mysqli_result($rs_mostrar, 0, "codubicacion");
	$stock_minimo=mysqli_result($rs_mostrar, 0, "stock_minimo");
	$stock=mysqli_result($rs_mostrar, 0, "stock");
	$aviso_minimo=mysqli_result($rs_mostrar, 0, "aviso_minimo");
	$datos=mysqli_result($rs_mostrar, 0, "datos_producto");
	$fecha=mysqli_result($rs_mostrar, 0, "fecha_alta");
	if ($fecha<>"0000-00-00") { $fechalis=implota($fecha); }
	$fechavencimiento=mysqli_result($rs_mostrar, 0, "fecha_vencimiento");
	if ($fechavencimiento<>"0000-00-00") { $fechavencimientolis=implota($fechavencimiento); }	
	$codembalaje=mysqli_result($rs_mostrar, 0, "codembalaje");
	$unidades_caja=mysqli_result($rs_mostrar, 0, "unidades_caja");
	$precio_ticket=mysqli_result($rs_mostrar, 0, "precio_ticket");
	$modif_descrip=mysqli_result($rs_mostrar, 0, "modificar_ticket");
	$observaciones=mysqli_result($rs_mostrar, 0, "observaciones");
	$moneda=mysqli_result($rs_mostrar, 0, "moneda");
	$precio_compra=mysqli_result($rs_mostrar, 0, "precio_compra");
	$precio_almacen=mysqli_result($rs_mostrar, 0, "precio_almacen");
	$precio_tienda=mysqli_result($rs_mostrar, 0, "precio_tienda");
	/*/$pvp=mysql_result($rs_mostrar,0,"precio_pvp");*/
	$precio_iva=mysqli_result($rs_mostrar, 0, "precio_iva");
	$fotoname=mysqli_result($rs_mostrar, 0, "imagen");

	$sector=mysqli_result($rs_mostrar, 0, "sector");
	$pasillo=mysqli_result($rs_mostrar, 0, "pasillo");
	$modulo=mysqli_result($rs_mostrar, 0, "modulo");
	$estante=mysqli_result($rs_mostrar, 0, "estante");
	
	
	
	$codigobarras=trim(mysqli_result($rs_mostrar, 0, "codigobarras"));
}

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script src="../js3/jquery.min.js"></script>
		<script type="text/javascript" src="../js3/jquery.keyz.js"></script>
		
<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
<script src="../js3/message.js" type="text/javascript"></script>
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">
	
<script type="text/javascript">
$(document).keydown(function(e) {
//alert(e.keyCode);
    switch(e.keyCode) { 
        case 13:
				parent.$('idOfDomElement').colorbox.close();
        break;
        case 45:

        break;
        case 117:
	      	showWarningToast('F6');
        break;
        case 112:
            showWarningToast('Ayuda aún no disponible...');
        break;
        case 118:
	      	showWarningToast('F7');
        break;
        case 119:
	      	showWarningToast('F8');
        break;
        case 120:
	      	showWarningToast('F9');
        break;
        case 121:
	      	showWarningToast('F10');

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
	<?php
	$estilo="margin: .5em 0 0.5em; border: 1px solid;padding: 2px 2px 2px 2px; background-repeat: no-repeat; background-position: 10px 50%; box-shadow: 0 1px 1px #fff inset;";
	?>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="frmBusqueda">
					<table class="fuente8" width="98%" cellspacing="0" cellpadding="2" border="0"><tr>
					<td colspan="3" class="header">
							<?php echo $cabecera2; ?>
					</td></tr>
						<tr>
							<td colspan="3" class="mensaje" style="<?php echo $estilo;?>"><?php echo $mensaje;?></td>
					    </tr>
		
					<tr>					
					<td valign="top" width="50%">
					<table class="fuente8" cellspacing="2" cellpadding="3" border="0">
						<tr>
							<td width="15%">C&oacute;digo</td>
							<td width="58%" style="<?php echo $estilo;?>"><?php echo $codarticulo;?></td>

						</tr>
						<tr>
							<td width="15%">Referencia</td>
							<td width="58%" style="<?php echo $estilo;?>"><?php echo $referencia;?></td>
				        </tr>
						<?php
					  	$query_familia="SELECT * FROM familias WHERE codfamilia='$codfamilia'";
						$res_familia=mysqli_query($GLOBALS["___mysqli_ston"], $query_familia);
						$nombrefamilia=mysqli_result($res_familia, 0, "nombre");
					  ?>
						<tr>
							<td width="15%">Familia</td>
							<td width="58%" style="<?php echo $estilo;?>"><?php echo $nombrefamilia;?></td>
				        </tr>
						<tr>
							<td width="15%">Descripci&oacute;n</td>
						    <td width="58%" style="<?php echo $estilo;?>"><?php echo $descripcion;?></td>
				        </tr>
						<tr>
						  <td>Impuesto</td>
						  <td style="<?php echo $estilo;?>"><?php
									  	$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".$codimpuesto;
										$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
										$ivavalor=mysqli_result($res_iva, 0, "valor");
										$ivatxt=mysqli_result($res_iva, 0, "nombre");						  
						  
						   echo $ivatxt;?></td>
				      </tr>
					  <?php
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
							<td width="58%" style="<?php echo $estilo;?>"><?php echo $nombreproveedor;?></td>
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
							<td width="58%" style="<?php echo $estilo;?>"><?php echo $nombreproveedor;?></td>
				        </tr>
						<tr>
							<td width="15%">Descripci&oacute;n&nbsp;corta</td>
						    <td width="58%" style="<?php echo $estilo;?>"><?php echo $descripcion_corta;?></td>
				        </tr>
						<?php
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
							<td width="58%" style="<?php echo $estilo;?>"><?php echo $nombreubicacion;?></td>
				        </tr>
						<tr>
							<td>Stock</td>
							<td style="<?php echo $estilo;?>"><?php echo $stock?> unidades</td>
					    </tr>
						<tr>
							<td>Stock&nbsp;minimo</td>
							<td style="<?php echo $estilo;?>"><?php echo $stock_minimo;?> unidades</td>
					    </tr>
						<tr>
							<td>Aviso&nbsp;M&iacute;nimo</td>
							<td colspan="2" style="<?php echo $estilo;?>"><?php if ($aviso_minimo==0) { echo "No"; } else { echo "Si"; }?></td>
						</tr>
						<tr>
							<td width="15%">Fecha&nbsp;de&nbsp;alta</td>
							<td colspan="2" style="<?php echo $estilo;?>"><?php echo $fechalis;?></td>
					    </tr>
						<tr>
							<td width="15%">Fecha&nbsp;de&nbspvencimiento</td>
							<td colspan="2" style="<?php echo $estilo;?>"><?php echo $fechavencimientolis;?></td>
					    </tr>					    
						</table></td><td width="500%" valign="top">
						<table class="fuente8" cellspacing="2" cellpadding="3" border="0">
						
						<tr>
							<td width="15%">Datos&nbsp;del&nbsp;producto</td>
							<td colspan="3" style="<?php echo $estilo;?>"><?php echo $datos;?></td>
					    </tr>
						<?php
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
							<td colspan="3" style="<?php echo $estilo;?>"><?php echo $nombreembalaje;?></td>
					    </tr>
						<tr>
							<td>Unidades&nbsp;por&nbsp;caja</td>
							<td style="<?php echo $estilo;?>"><?php echo $unidades_caja;?> unidades</td>
							<td>Comisión</td><td style="<?php echo $estilo;?>">
						  <?php echo$comision;?> %</td>
						</tr>
						<tr>
							<td>Preguntar&nbsp;precio&nbsp;ticket</td>
							<td colspan="3" style="<?php echo $estilo;?>"><?php if ($precio_ticket==0) { echo "No"; } else { echo "Si"; }?></td>
						</tr>
						<tr>
							<td>Modificar&nbsp;descrip.&nbsp;ticket</td>
							<td colspan="3" style="<?php echo $estilo;?>"><?php if ($modif_descrip==0) { echo "No"; } else { echo "Si"; }?></td>
						</tr>
						<tr>
							<td>Observaciones</td>
							<td colspan="3" style="<?php echo $estilo;?>"><?php echo $observaciones;?></td>
						</tr>
						<tr>
						<td>Moneda</td><td width="26%"> <select onchange="cambio();" name="amoneda" id="amoneda" class="cajaPequena2">
						<?php $tipof = array(  1=>"Pesos", 2=>"U\$S");
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
							<td colspan="3" style="<?php echo $estilo;?>"><?php echo $precio_compra?></td>
						</tr>
						<tr>
							<td>Precio&nbsp;almac&eacute;n</td>
							<td colspan="3" style="<?php echo $estilo;?>"><?php echo $precio_almacen?></td>
						</tr>												
						<tr>
							<td>Precio&nbsp;en&nbsp;tienda</td>
							<td colspan="3" style="<?php echo $estilo;?>"><?php echo $precio_tienda?></td>
						</tr>
						<!--<tr>
							<td>Pvp</td>
							<td colspan="2" style="<?php echo $estilo;?>"><?php /*echo $pvp;*/?> &#8364;</td>
						</tr>-->
						<tr>
							<td>Precio con iva</td>
							<td colspan="3" style="<?php echo $estilo;?>"><?php echo $precio_iva?></td>
						</tr>
				      <tr>
						  <td colspan="4"><fieldset style="width:80%">
						  <legend>Ubicación</legend>
						  <table class="fuente8" width="100%" cellspacing="2" cellpadding="3" border="0"><tr><td>
						  Sector</td><td style="<?php echo $estilo;?>"><?php echo $sector;?></td><td>
						  Pasillo</td><td style="<?php echo $estilo;?>"><?php echo $pasillo;?></td></tr><tr><td>
						  Módulo</td><td style="<?php echo $estilo;?>"><?php echo $modulo;?></td><td>
						  Estante</td><td style="<?php echo $estilo;?>"><?php echo $estante;?></td></tr><tr><td>
						  </tr>
						  </table>
						  </fieldset>
						  </td>
					</tr>							
					</table>
					</td>
					
					<td valign="top">
					<table class="fuente8" width="100%" cellspacing="2" cellpadding="3" border="0">
					  <tr>
						  <td valign="top" colspan="2">
						  <img src="../fotos/<?php echo @$fotoname;?>" style="height: 200px;" >
						  </td>
				      </tr>
				      <tr>
				        	<td>Cód.&nbsp;Cont.&nbsp;Com.</td><td width="85%" style="<?php echo $estilo;?>"><?php
				        	if($plancuentac!='') {
								$query_busqu="SELECT * FROM plandecuentas WHERE borrado=0 AND codplan='".$plancuentac."'";
								$rs_busqu=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqu);
								echo $nombre=mysqli_result($rs_busqu, 0, "codplan").' - '. mysqli_result($rs_busqu, 0, "nombre");
							}	
				        	?>
				        	</td>
				        	</tr><tr><td>
				        	Cód.&nbsp;Cont.&nbsp;Ven.</td><td width="85%" style="<?php echo $estilo;?>"><?php
				        	if($plancuentav!='') {
								$query_busqu="SELECT * FROM plandecuentas WHERE borrado=0 AND codplan='".$plancuentav."'";
								$rs_busqu=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqu);
								echo $nombre=mysqli_result($rs_busqu, 0, "codplan").' - '. mysqli_result($rs_busqu, 0, "nombre");
							}	
							?></td>
						</tr>	
						<td>Codigo&nbsp;de&nbsp;barras</td>
							<td >
							<img src="../barcode/gd.php?text=<?php echo $codigobarras;?>&height=40"></td></td>						
						<td valign="top">Código&nbsp;QR<br>
							<?php
							$texto="Código-".$codigobarras."-";
							$texto.="Sector-".$sector."-Pasillo-".$pasillo."-Modulo-".$modulo."-Estante-".$estante;
							$width = $height = 150;
							$file = "../tmp/qr".$codigobarras.".png";
							
							 echo "<img src='../barcode/qrcode.php?texto=".$texto."&width=".$width."&height=".$height."&file=".$file."&accion=u'>"; ?></td>
						</tr>										      
					</table>
					
					</td></table>
			  </div>
			  <br style="line-height:5px">
				<div >
						<button class="boletin" onClick="aceptar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>				
			  </div>
			 </div>
		  </div>
		</div>
		
	</body>
</html>