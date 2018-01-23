<?php
include ("../../conectar.php");
include("../../common/verificopermisos.php");
include("../../common/funcionesvarias.php");
include ("../../funciones/fechas.php"); 


$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }


$codproyectos=$_POST["codproyectos"];
$codcliente=$_POST["codcliente"];
$descripcion=trim($_POST["descripcion"]);
$fechaini=explota($_POST["Afechaini"]);
$fechafin=explota($_POST["fechafin"]);
$estado=$_POST["estado"];



if ($accion=="alta") {
	$query_operacion="INSERT INTO `proyectos` (`codproyectos`, `codcliente`, `fechaini`, `fechafin`, `descripcion`, `borrado`)
	 VALUES (NULL, '$codcliente', '$fechaini', '$fechafin', '$descripcion', '$estado')";
	 					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	if ($rs_operacion) { $mensaje="El proyecto ha sido dado de alta correctamente"; }
	$cabecera1="Inicio >> Proyecto &gt;&gt; Nuevo proyecto ";
	$cabecera2="INSERTAR proyecto ";
	$sel_maximo="SELECT max(codproyecto) as maximo FROM proyectos";
	$rs_maximo=mysqli_query($GLOBALS["___mysqli_ston"], $sel_maximo);
	$codproyecto=mysqli_result($rs_maximo, 0, "maximo");
}

if ($accion=="modificar") {
	
	if (!empty($contrasenia)) {
	$pass="contrasenia='".$contrasenia."',";	
	} else {
	$pass='';
	}	
	$query="UPDATE `proyectos` SET `codcliente` = '$codcliente', `fechaini`='$fechaini', `fechafin`='$fechafin', `descripcion` = '$descripcion' WHERE `codproyectos` = $codproyectos";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="Los datos del proyecto han sido modificados correctamente"; }
	$cabecera1="Inicio >> proyecto &gt;&gt; Modificar proyecto ";
	$cabecera2="MODIFICAR proyecto ";
}

if ($accion=="baja") {
	$codproyectos=$_POST["codproyectos"];
	$query="UPDATE proyectos SET borrado=1 WHERE codproyectos='$codproyectos'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="El proyecto ha sido dado de baja correctamente"; }
	$cabecera1="Inicio >> proyecto &gt;&gt; Eliminar proyecto ";
	$cabecera2="ELIMINAR proyecto ";
	$query_mostrar="SELECT * FROM proyectos WHERE codproyectos='$codproyectos'";
	$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);

	$codcliente=mysqli_result($rs_mostrar, 0, "codcliente");
	$descripcion=mysqli_result($rs_mostrar, 0, "descripcion");
	$estado=mysqli_result($rs_mostrar, 0, "borrado");

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
			parent.$('idOfDomElement').colorbox.close();
			/*location.href="index.php";*/
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
				<div id="tituloForm" class="header"><?php echo $cabecera2; ?></div>
				<div id="frmBusqueda">
				<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td>Cliente</td><td colspan="3">
							<?php
								$query_busqu="SELECT * FROM clientes WHERE borrado=0 AND codcliente='".$codcliente."'";
								$rs_busqu=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqu);
								$nombre=mysqli_result($rs_busqu, 0, "nombre").' '. mysqli_result($rs_busqu, 0, "apellido");	
							?>
							<input type="text" size="26" maxlength="60" id="codcliente" value="<?php echo $nombre;?>"
							  class="trigger cajaGrande" onFocus="this.style.backgroundColor='#FFFF99'" 
							 onBlur="this.style.backgroundColor='white'"/>
							</td>
					    </tr>
					    <tr>
						  <td>Fecha&nbsp;inicio</td>
						  <td><input id="fechaini" type="text" class="cajaPequena"  maxlength="10" value="<?php echo implota($fechaini);?>" readonly>

						</td>
						  <td>Fecha&nbsp;finalización</td>
						  <td><input id="fechafin" type="text" class="cajaPequena" maxlength="10" value="<?php echo implota($fechafin);?>" readonly>

						</td>						
						</tr>
							
				      <tr><td>Estado</td>
				      <td colspan="3">
						<select name="estado" id="estado" class="cajaPequena2">
						<?php
						if($estado==0)	{
						?>
							<option value="0" selected>Activo</option>
							<option value="1">Borrado</option>
						<?php } else { ?>
							<option value="0" >Activo</option>
							<option value="1" selected>Borrado</option>
						<?php	
						}
						?>
						</select>
				      </tr>					
						<tr>
							<td>Descripción</td><td colspan="3"><textarea id="descripcion" name="descripcion" autocomplete="off" rows="2" cols="35" ><?php echo $descripcion;?></textarea></td>
					    </tr>					    
					</table>
			  </div>
				<div>
					<button class="boletin" onClick="aceptar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Aceptar</button>
			  </div>
			 </div>
		  </div>
		</div>
	</body>
</html>
