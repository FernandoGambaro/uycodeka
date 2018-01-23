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
 
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

date_default_timezone_set("America/Montevideo"); 

include ("../funciones/image_resize.php");

$foto_name="";
$fotoname="";

$accion=@$_POST["accion"];
if (!isset($accion)) { $accion=@$_GET["accion"]; }

$nombre=@$_POST["Anombre"];

$mensaje='';


if ($accion=="alta") {
	$sel_comp="SELECT * FROM codfamilia WHERE nombre LIKE '$nombre'";
	$rs_comp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_comp);
	if (@mysqli_num_rows($rs_comp) > 0) {
	?><script>
				alert ("No se puede dar de alta a esa familia, ya existe uno con esta descripción.");
				parent.$('idOfDomElement').colorbox.close();
				</script>
				
		<?php
	} else {
		$consultaprevia = "SELECT max(codfamilia) as maximo FROM familias";
		$rs_consultaprevia=mysqli_query($GLOBALS["___mysqli_ston"], $consultaprevia);
		$codfamilia=mysqli_result($rs_consultaprevia, 0, "maximo");
		if ($codfamilia=="") { $codfamilia=0; }
		$codfamilia++;
	}	
	



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
        $fotoname= "foto".$codfamilia .$_FILES["foto"]["name"];
        if (file_exists("../fotos/" ."foto".$codfamilia . $_FILES["foto"]["name"]))
        {
            $fileerror= " - foto".$codfamilia .$_FILES["foto"]["name"] . " existe. ";
        }
        else
        {
			if ( isset($_FILES['foto']) ) {
			
			 $filename  = $_FILES['foto']['tmp_name'];
			 
			 $handle    = fopen($filename, "r");
			 $data      = fread($handle, filesize($filename));
			 $post_array = array(
			   'file' => base64_encode($data),
			   'name' => "foto".$codfamilia . $_FILES["foto"]["name"]
			 );
			
			 
			    $ch = curl_init();
			    curl_setopt($ch, CURLOPT_HEADER, 0);
			    curl_setopt($ch, CURLOPT_VERBOSE, 0);
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
			    curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_URL, 'https://uymcc.no-ip.org:8082/familias/handle.php' );
				
			    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_array);
			    $response = curl_exec($ch);
			    
				move_uploaded_file($_FILES["foto"]["tmp_name"], "../fotos/"."foto".$codfamilia . $_FILES["foto"]["name"]);
				$fileerror=resize_image('max',"../fotos/"."foto".$codfamilia . $_FILES["foto"]["name"],"../fotos/"."foto".$codfamilia . $_FILES["foto"]["name"],200,200);
			    
			}			    
        }
      }
    }

	
	
	$query_operacion="INSERT INTO familias (codfamilia, nombre, imagen, borrado) 
					VALUES ('', '$nombre', '$fotoname', '0')";					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	if ($rs_operacion) { $mensaje="La familia ha sido dada de alta correctamente"; }
	$cabecera1="Inicio >> Familias &gt;&gt; Nueva Familia ";
	$cabecera2="INSERTAR FAMILIA ";
	$sel_maximo="SELECT max(codfamilia) as maximo FROM familias";
	$rs_maximo=mysqli_query($GLOBALS["___mysqli_ston"], $sel_maximo);
	$codfamilia=mysqli_result($rs_maximo, 0, "maximo");
}

if ($accion=="modificar") {
	$codfamilia=$_POST["Zid"];
	
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
        $fotoname= "foto".$codfamilia .$_FILES["foto"]["name"];
        if (file_exists("../fotos/" ."foto".$codfamilia . $_FILES["foto"]["name"]))
        {
            $fileerror= " - foto".$codfamilia .$_FILES["foto"]["name"] . " ya existe. ";
        }
        else
        {
			if ( isset($_FILES['foto']) ) {
			
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
				curl_setopt($ch, CURLOPT_URL, 'https://uymcc.no-ip.org:8082/familias/handle.php' );
				
			    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_array);
			    $response = curl_exec($ch);
			
				move_uploaded_file($_FILES["foto"]["tmp_name"], "../fotos/"."foto".$codfamilia . $_FILES["foto"]["name"]);
				$fileerror=resize_image('max',"../fotos/"."foto".$codfamilia . $_FILES["foto"]["name"],"../fotos/"."foto".$codfamilia . $_FILES["foto"]["name"],200,200);
			    
			}

     }
      }
    } 
		if (@$fotoname!='')
		{
		   $foto_name="imagen='".$fotoname."', ";
		};	
	
	$query="UPDATE familias SET nombre='$nombre', $foto_name borrado=0 WHERE codfamilia='$codfamilia'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="Los datos de la familia han sido modificados correctamente"; }
	$cabecera1="Inicio >> Familias &gt;&gt; Modificar Familia ";
	$cabecera2="MODIFICAR FAMILIA ";
}

if ($accion=="baja") {
	$codfamilia=$_GET["codfamilia"];
	$query_comprobar="SELECT * FROM articulos WHERE codfamilia='$codfamilia' AND borrado=0";
	$rs_comprobar=mysqli_query($GLOBALS["___mysqli_ston"], $query_comprobar);
	if (mysqli_num_rows($rs_comprobar) > 0 ) {
		?><script>
			alert ("No se puede eliminar esta familia porque tiene articulos asociados.");
			location.href="eliminar_familia.php?codfamilia=<?php echo $codfamilia?>";
		</script>
		<?php
	} else {
		$query="UPDATE familias SET borrado=1 WHERE codfamilia='$codfamilia'";
		$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
		if ($rs_query) { $mensaje="La familia ha sido eliminada correctamente"; }
		$cabecera1="Inicio >> Familias &gt;&gt; Eliminar Familia ";
		$cabecera2="ELIMINAR FAMILIA ";
		$query_mostrar="SELECT * FROM familias WHERE codfamilia='$codfamilia'";
		$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);
		$codfamilia=mysqli_result($rs_mostrar, 0, "codfamilia");
		$nombre=mysqli_result($rs_mostrar, 0, "nombre");
		$fotoname=mysqli_result($rs_mostrar, 0, "imagen");
	}
}

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">
		
		function aceptar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
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
							<td width="85%" colspan="2" class="mensaje"><?php echo $mensaje;?></td>
					    </tr>
						<tr>
							<td width="15%">C&oacute;digo</td>
							<td width="85%"><?php echo $codfamilia?></td>
					    </tr>
						<tr>
							<td width="15%">Nombre</td>
						    <td width="85%"><?php echo $nombre?></td>
					    </tr>
						<td valign="top" colspan="2" align="center">
						  <img src="../fotos/<?php echo $fotoname;?>" style="height: 200px;" >
						</td>					    						
					</table>
			  </div>
				<div>
					<img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar()" border="1" onMouseOver="style.cursor=cursor">
			  </div>
			 </div>
		  </div>
		</div>
	</body>
</html>
