<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
@$paginacion=$s->data['alto'];

if($paginacion<=0) {
	$paginacion=20;
}
$Seleccionados=array();
$Seleccionados=@$s->data['Selected'];

include ("../conectar.php");
include("../common/verificopermisos.php");
include("../common/funcionesvarias.php");
include ("../funciones/fechas.php");

header('Content-Type: text/html; charset=UTF-8'); @mysqli_query($GLOBALS["___mysqli_ston"], "SET NAMES 'utf8'");  
$cadena_busqueda='';

$codcliente=$_POST["codcliente"];
$codusuario=$_POST["codusuario"];

$fechafin=$_POST["Afechafin"];
$fechainicio=$_POST["Afechainicio"];

$where="1=1";
if ($codcliente <> "") { $where.=" AND codcliente='$codcliente'"; }
if ($codusuario <> "") { $where.=" AND codusuario = '".$codusuario."' "; }

if ($fechainicio <> "" and $fechafin == "") { $where.=" AND fecha >= '".explota($fechainicio)."'"; }
if ($fechainicio == "" and $fechafin <> "") { $where.=" AND fecha <= '".explota($fechafin)."'"; }

if ($fechafin <> "" and $fechainicio <> "") { $where.=" AND fecha BETWEEN  '".explota($fechainicio)."' and '".explota($fechafin)."'"; }


$where.=" ORDER BY fecha DESC";

$query_busqueda="SELECT count(*) as filas FROM horas WHERE borrado=0 AND ".$where;
$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
$filas=mysqli_result($rs_busqueda, 0, "filas");

$query="SELECT * FROM horas WHERE borrado=0 AND ".$where;
$rs_C=mysqli_query($GLOBALS["___mysqli_ston"], $query);
$Total="";
while ($Datos = mysqli_fetch_array($rs_C)){
	$Total=SumaHoras($Datos["horas"],$Total);	       
}


?>
<html>
	<head>
		<title>Clientes</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="../js3/jquery.colorbox.js"></script>
<script type="text/javascript">
var indiaux='';

$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

var headID = window.parent.document.getElementsByTagName("head")[0];         
var newScript = window.parent.document.createElement('script');
newScript.type = 'text/javascript';
newScript.src = '../js3/jquery.colorbox.js';
headID.appendChild(newScript);
});

</script>		
		<script language="javascript">
		
		function ver_horas(codhoras) {
			var url="ver_horas.php?codhoras=" + codhoras + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			window.parent.OpenNote(url);
			
			/*parent.location.href="ver_cliente.php?codcliente=" + codcliente + "&cadena_busqueda=<?php echo $cadena_busqueda?>";*/
		}
		
		function modificar_horas(codhoras) {
			var url="modificar_horas.php?codhoras=" + codhoras + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			window.parent.OpenNote(url);
		}
		
		function eliminar_horas(codhoras) {
			var url="eliminar_horas.php?codhoras=" + codhoras + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			window.parent.OpenNote(url);
		}

		function inicio() {
			parent.document.getElementById("Total").value = document.getElementById("Total").value;
			var numfilas=document.getElementById("numfilas").value;
			var indi=parent.document.getElementById("iniciopagina").value;
			var contador=1;
			var indice=0;
			if (parseInt(indi)>parseInt(numfilas)) { 
				indi=1; 
			}
			if (parseInt(numfilas) <= 10) {
					parent.document.getElementById("nextdisab").style.display = 'block';
					parent.document.getElementById("lastdisab").style.display = 'block';
					parent.document.getElementById("last").style.display = 'none';
					parent.document.getElementById("next").style.display = 'none';
			}
			parent.document.form_busqueda.filas.value=numfilas;
			parent.document.form_busqueda.paginas.innerHTML="";

			parent.document.getElementById("prevpagina").value = contador-10;
			parent.document.getElementById("currentpage").value = indice+1;
			parent.document.getElementById("nextpagina").value = contador + 10;

			while (contador<=numfilas) {
				if (parseInt(contador+9)>numfilas) {
					
				}
				texto=contador + " al " + parseInt(contador+9);
				if (parseInt(indi)==parseInt(contador)) {
					if (indi==1) {
					parent.document.getElementById("first").style.display = 'none';
					parent.document.getElementById("prev").style.display = 'none';
					parent.document.getElementById("firstdisab").style.display = 'block';
					parent.document.getElementById("prevdisab").style.display = 'block';
					} else {
					parent.document.getElementById("first").style.display = 'block';
					parent.document.getElementById("prev").style.display = 'block';
					parent.document.getElementById("firstdisab").style.display = 'none';
					parent.document.getElementById("prevdisab").style.display = 'none';
					}
					parent.document.getElementById("prevpagina").value = contador-10;
					parent.document.getElementById("currentpage").value = indice + 1;
					parent.document.getElementById("nextpagina").value = contador + 10;

					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.form_busqueda.paginas.options[indice].selected=true;
					indiaux=	indice;				
					
				} else {

					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.getElementById("lastpagina").value = contador;
				}
				indice++;
				contador=contador+10;
			}	

					if (parseInt(indiaux) == parseInt(indice)-1 ) {
					parent.document.getElementById("nextdisab").style.display = 'block';
					parent.document.getElementById("lastdisab").style.display = 'block';
					parent.document.getElementById("last").style.display = 'none';
					parent.document.getElementById("next").style.display = 'none';
					} else {
					parent.document.getElementById("nextdisab").style.display = 'none';
					parent.document.getElementById("lastdisab").style.display = 'none';
					parent.document.getElementById("last").style.display = 'block';
					parent.document.getElementById("next").style.display = 'block';
					}

		}	
		</script>
	</head>

	<body onload="inicio();">	
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
			<div class="header">Listado de horas realizadas</div>
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="13%" align="left" colspan="2">&nbsp;Usuario </td>
							<td width="13%">Fecha</td>
							<td width="13%">-</td>
							<td width="13%">Cliente/Proyecto</td>
							<td width="39%">Descripción</td>
							<td width="19%">Horas</td>
							<td width="50px" colspan="3">&nbsp;Acción</td>
						</tr>
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas;?>">
			<input type="hidden" name="Total" id="Total" value="<?php echo $Total;?>">
			
				<?php 
				$iniciopagina=isset($_POST['iniciopagina']) ? $_POST['iniciopagina'] : null ;

				$tipo = array( 0=>"Mañana", 1=>"Tarde");


				if (""==($iniciopagina)) { $iniciopagina=@$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if (""==($iniciopagina)) { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) { ?>
						<?php $sel_resultado="SELECT * FROM horas WHERE borrado=0 AND ".$where;
						   $sel_resultado=$sel_resultado."  limit ".$iniciopagina.",10";
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;
						   while ($contador < mysqli_num_rows($res_resultado)) { 
						   												   
								 if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>
						<tr class="<?php echo $fondolinea?>">

							<td colspan="2"><div align="left"><?php $codusuario=mysqli_result($res_resultado, $contador, "codusuario");
							
								$query_bus="SELECT * FROM usuarios WHERE codusuarios='".$codusuario."'";
								$rs_bus=mysqli_query($GLOBALS["___mysqli_ston"], $query_bus);
								echo mysqli_result($rs_bus, 0, "nombre").' '. mysqli_result($rs_bus, 0, "apellido");							
							
							?></div></td>
							<td width="13%"><div align="left"><?php echo implota(mysqli_result($res_resultado, $contador, "fecha"));?></div></td>
							<td width="13%"><div align="left"><?php  if(mysqli_result($res_resultado, $contador, "mantar")!='') { echo $tipo[mysqli_result($res_resultado, $contador, "mantar")];}?></div></td>
							<td width="19%"><div align="left"><?php
								$codcliente=mysqli_result($res_resultado, $contador, "codcliente");
								$query_busqu="SELECT * FROM clientes WHERE borrado=0 AND codcliente='".$codcliente."'";
								$rs_busqu=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqu);
								echo mysqli_result($rs_busqu, 0, "nombre").' '. mysqli_result($rs_busqu, 0, "apellido");	


								$codproyectos= mysqli_result($res_resultado, $contador, "codproyectos");
								$query_busq="SELECT * FROM proyectos WHERE borrado=0 AND codproyectos='".$codproyectos."'";
								$rs_busq=mysqli_query($GLOBALS["___mysqli_ston"], $query_busq);
								if(mysqli_num_rows($rs_busq)>0) {
								echo " / ". mysqli_result($rs_busq, 0, "descripcion");
								}
							
							?></div></td>
							<td class="aDerecha" width="19%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "descripcion");?></div></td>
							<td class="aDerecha" width="19%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "horas");?></div></td>
							<td width="18px"><div align="center"><a href="#"><img id="botonBusqueda" src="../img/modificar.png" width="16" height="16" border="0" onClick="modificar_horas(<?php echo mysqli_result($res_resultado, $contador, "codhoras")?>)" title="Modificar"></a></div></td>
							<td width="18px"><div align="center"><a href="#"><img id="botonBusqueda" src="../img/ver.png" width="16" height="16" border="0" onClick="ver_horas(<?php echo mysqli_result($res_resultado, $contador, "codhoras")?>)" title="Visualizar"></a></div></td>
							<td width="18px"><div align="center"><a href="#"><img id="botonBusqueda" src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar_horas(<?php echo mysqli_result($res_resultado, $contador, "codhoras")?>)" title="Eliminar"></a></div></td>
						</tr>
						<?php $contador++;
							}
						?>			
					</table>
					<?php } else { ?>
					<table class="fuente8" width="87%" cellspacing=0 cellpadding=3 border=0>
						<tr>
						<td><?php //echo $query;?></td></tr><tr>
							<td width="100%" class="mensaje"><?php echo "No hay ning&uacute;n cliente que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
					</table>					
					<?php } ?>					
				</div>
			</div>
		  </div>		
		</div>
	</body>
</html>
