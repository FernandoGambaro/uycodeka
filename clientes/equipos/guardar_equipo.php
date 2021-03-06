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
 

date_default_timezone_set('America/Montevideo');
include ("../../conectar.php"); 
include ("../../funciones/fechas.php"); 

$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$fecha=$_POST["fecha"];
if ($fecha<>"") { $fecha=explota($fecha); } else { $fecha="0000-00-00"; }

$codequipo=@$_POST["codequipo"];
$numero=$_POST["znumero"];
$service=$_POST["aservice"];
$alias=$_POST["aalias"];
$descripcion=$_POST["Adescripcion"];
$detalles=$_POST["adetalles"];
$codcliente=$_POST['codcliente'];

if ($accion=="alta") {
	$query_operacion="INSERT INTO equipos (`codequipo` ,`codcliente` ,`fecha` ,`service` ,`numero` ,`descripcion` ,`detalles`, `alias`,
	`borrado`) VALUES ('', '$codcliente', '$fecha', '$service', '$numero', '$descripcion', '$detalles', '$alias', '0')";					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	if ($rs_operacion) { $mensaje="El equipo ha sido dado de alta correctamente"; }
	$cabecera1="Inicio >> Equipo &gt;&gt; Nuevo Equipo ";
	$cabecera2="INSERTAR CLIENTE ";
	$sel_maximo="SELECT max(codequipo) as maximo FROM equipos";
	$rs_maximo=mysqli_query($GLOBALS["___mysqli_ston"], $sel_maximo);
	$codequipo=mysqli_result($rs_maximo, 0, "maximo");
}

if ($accion=="modificar") {
	$query="UPDATE equipos SET fecha='$fecha', service='$service', numero='$numero',
	 descripcion='$descripcion', detalles='$detalles', alias='$alias', borrado=0 WHERE codequipo='$codequipo'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="Los datos del equipo han sido modificados correctamente"; } else {
	 $mensaje="Error al modificar satos";	}
	$cabecera1="Inicio >> Equipo &gt;&gt; Modificar Equipo ";
	$cabecera2="MODIFICAR Equipo ";
}

if ($accion=="baja") {
	$codequipo=$_GET["codequipo"];
	$query="UPDATE equipos SET borrado=1 WHERE codequipo='$codequipo'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="El equipo ha sido eliminado correctamente"; }
	$cabecera1="Inicio >> Clientes &gt;&gt; Eliminar Equipo ";
	$cabecera2="ELIMINAR EQUIPO ";
	$query_mostrar="SELECT * FROM equipos WHERE codequipo='$codequipo'";
	$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);
	
	$fecha=mysqli_result($rs_mostrar, 0, "fecha");
	$fecha=explota($fecha);
	$service=mysqli_result($rs_mostrar, 0, "service");
	$numero=mysqli_result($rs_mostrar, 0, "numero");
	$descripcion=mysqli_result($rs_mostrar, 0, "descripcion");
	$detalles=mysqli_result($rs_mostrar, 0, "detalles");
	$codcliente=mysqli_result($rs_mostrar, 0, "codcliente");
}

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../../estilos/estilos.css" type="text/css" rel="stylesheet">
		<!-- iconos para los botones -->       
<link rel="stylesheet" href="../../css3/css/font-awesome.min.css">		
		
		<script language="javascript">
		
		function aceptar() {
			//var e=document.getElementById("codcliente").value;
			location.href="index.php?codcliente=<?php echo $codcliente;?>";
		}
		
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
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
				<table class="fuente8">
				
						<tr>

							<td width="85%" colspan="2" class="mensaje"><?php echo $mensaje;?></td>
					    </tr>
				
				<tr><td valign="top">
				<table class="fuente8"><tr><td valign="top">
					<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td>Fecha&nbsp;de&nbsp;alta</td>
							<td><input NAME="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" value="<?php echo $fecha;?>" readonly> 
							</td>
					    </tr>
					    <tr>
						  <td>Nº</td>
						  <td ><input id="numero" type="text" autocomplete="off" class="cajaPequena" NAME="anumero" value="<?php echo $numero;?>" maxlength="15"></td>
				      </tr>
						<tr>
							<td>Descripción </td>
							<td colspan="3"><input NAME="adescripcion" type="text" class="cajaGrande" id="descripcion" size="45" value="<?php echo $descripcion;?>" maxlength="45"></td>
					    </tr>
						<tr>
							<td>Alias </td>
							<td colspan="3"><input NAME="aalias" type="text" class="cajaGrande" id="alias" size="45"  value="<?php echo $alias;?>" maxlength="45"></td>
					    </tr>					</table></td><td>						
					</table></td><td valign="top">						
						<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>	
						<tr>
							<td>Service</td>
							<td>
							<?php
							 $tipo = array("Sin&nbsp;definir", "Sin&nbsp;Servicio","Con&nbsp;Mantenimiento", "Mantenimiento&nbsp;y&nbsp;Respaldos");
							      echo @$tipo[$service];
							?>
							</td>
							</tr>
						<tr>
							<td valign="top">Detalles</td>
						    <td><textarea name="adetalles" cols="41" rows="4" id="detalles" class="areaTexto"><?php echo $detalles;?></textarea></td>
					    </tr>
					</table>
				</td></tr></table>
				</div>
			  <p>
				<div>
						<button class="boletin" onClick="aceptar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
			  </div>
			 </div>
		  </div>
		</div>
	</body>
</html>
