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
?>
<html>
<head>
		<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">
	
<title>Buscador de Articulos</title>
<script>
var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
function buscar() {
	if (document.getElementById("iniciopagina").value=="") {
		document.getElementById("iniciopagina").value=1;
	} else {
		document.getElementById("iniciopagina").value=document.getElementById("paginas").value;
	}
	document.getElementById("form1").submit();
	document.getElementById("tabla_resultado").style.display="";
}

function inicio() {
	var combo_familia=document.getElementById("cmbfamilia").value;
	if (combo_familia==0) {
		buscar();
	} else {
		document.getElementById("tabla_resultado").style.display="none";
	}
}

function paginar() {
	document.getElementById("iniciopagina").value=document.getElementById("paginas").value;
	document.getElementById("form1").submit();
}

function enviar() {
	document.getElementById("form1").submit();
}

</script>
<script language="javascript">

function pon_prefijo_a(codfamilia,codigobarras,referencia,descripcion,precio_compra,codarticulo,moneda,descripcion) {
	parent.pon_prefijo_Fb(codfamilia,codigobarras,referencia,descripcion,precio_compra,codarticulo,moneda,descripcion);
}

		function cancelar() {
			parent.$('idOfDomElement').colorbox.close();
		}
</script>

<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style></head>
<?php 

$anterior='';
$codproveedor=$_GET["codproveedor"];
?>
<body onLoad="buscar();">
<form name="form1" id="form1" method="post" action="frame_articulos.php" target="frame_resultado" onSubmit="buscar();">
 <div align="center">
	<table class="fuente8" align="center">
     <tr>
	    <td>Familia:</td>
	    <td>
		  <select id="cmbfamilia" name="cmbfamilia" class="comboGrande">
		  <?php
		    $consultafamilia="select * from familias where borrado=0 order by nombre ASC";
			$queryfamilia=mysqli_query($GLOBALS["___mysqli_ston"], $consultafamilia);
			?><option value=0>Todos los articulos</option><?php
			while ($rowfamilia=mysqli_fetch_row($queryfamilia))
			  { 
			  	if ($anterior==$rowfamilia[0]) { ?>
					<option value="<?php echo $rowfamilia[0]?>" selected><?php echo utf8_encode($rowfamilia[1])?></option>
			<?php	} else { ?>
					<option value="<?php echo $rowfamilia[0]?>"><?php echo utf8_encode($rowfamilia[1])?></option>
			<?php	}   
		   	  };
		  ?>
	    </select></td>
		<td class="busqueda">Referencia:</td>
	    <td><input name="referencia" type="text" id="referencia" size="20" class="cajaMedia"></td></tr>
		<tr><td class="busqueda">Descripci&oacute;n:</td>
	    <td><input name="descripcion" type="text" id="descripcion" size="50" class="cajaGrande"></td>
		<td class="busqueda">Mostrar todos los art&iacute;culos:</td>
	    <td><select name="todos" id="todos" class="comboPequeno">
						<option value=0 selected="selected">No</option>
						<option value=1>Si</option>
						</select></td></tr>
		<tr>

		  <td colspan="4" align="center"><img id="botonBusqueda" src="../img/botonbuscar.jpg" width="69" height="22" border="1" onClick="enviar();" onMouseOver="style.cursor=cursor"></td>
	  </tr>
</table>
</div>
  <table width="95%" id="tabla_resultado" name="tabla_resultado" style="display:none" align="center">
	<tr>
  		<td>
			<iframe width="100%" height="300" id="frame_resultado" name="frame_resultado">
				<ilayer width="100%" height="300" id="frame_resultado" name="frame_resultado"></ilayer>
			</iframe>
		</td>
	</tr>
</table>
<input type="hidden" id="iniciopagina" name="iniciopagina">
<input type="hidden" id="codproveedor" name="codproveedor" value="<?php echo $codproveedor?>">
<table width="100%" border="0">
  <tr>
    <td>
    <div align="center">
						<button class="boletin" onClick="cancelar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
    </div></td>
  </tr>
</table>

</form>
</div>
</body>
</html>
