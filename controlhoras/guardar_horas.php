<?php
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

header('Content-Type: text/html; charset=UTF-8'); 
$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }


$codhoras=$_POST["codhoras"];
$codusuario=$_POST["codusuarios"];
$fecha=explota($_POST["Afecha"]);
$mantar=@$_POST["Amantar"];
$codcliente=$_POST["Acodcliente"];
$descripcion=trim($_POST["descripcion"]);
$horas=$_POST["horas"];

//if ($_POST['codproyectosaux']!='') {
$codproyectos=$_POST['Acodproyectos'];
//}else {
//$codproyectos=0;
//}


if ($accion=="alta") {
	
	if(@$_POST['Afechafin']!='') {
		
		$fechaInicio=strtotime($fecha);
		$fechaFin=@explota($_POST['Afechafin']);
		$fechaFin=strtotime($fechaFin);
		
		for ( ; $fechaInicio <= $fechaFin; $fechaInicio = strtotime('+1 day', $fechaInicio)){ 
    	$fecha= date('Y-m-d', $fechaInicio);
    		
			echo $query_operacion=" INSERT INTO `horas` (`codhoras`, `codusuario`, `fecha`, `mantar`, `codcliente`, `codproyectos`, `descripcion`, `horas`, `borrado`)
			 VALUES (NULL, '$codusuario', '$fecha', '$mantar', '$codcliente', '$codproyectos', '$descripcion', '$horas', '0')";

			echo $rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
		}		
			if ($rs_operacion) { $mensaje="El control ha sido dado de alta correctamente"; }
			$cabecera1="Inicio >> Horas &gt;&gt; Nuevo control horas ";
			$cabecera2="INSERTAR CONTROL HORAS VARIOS DÍAS ";

	} else {
	
	$query_operacion="INSERT INTO `horas` (`codhoras`, `codusuario`, `fecha`, `mantar`, `codcliente`, `codproyectos`, `descripcion`, `horas`, `borrado`)
	 VALUES (NULL, '$codusuario', '$fecha', '$mantar', '$codcliente', '$codproyectos', '$descripcion', '$horas', '0')";
 					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	if ($rs_operacion) { $mensaje="El control ha sido dado de alta correctamente"; }
	$cabecera1="Inicio >> Horas &gt;&gt; Nuevo control horas ";
	$cabecera2="INSERTAR CONTROL HORAS ";
	/*$sel_maximo="SELECT max(codhoras) as maximo FROM horas";
	$rs_maximo=mysql_query($sel_maximo);
	$codhoras=mysql_result($rs_maximo,0,"maximo");*/
	}
}

if ($accion=="modificar") {

	$codhoras=$_POST["codhoras"];
	$query="UPDATE `horas` SET `codusuario` = '$codusuario', `fecha` = '$fecha', `mantar` = '$mantar', 
	`codcliente` = '$codcliente', `codproyectos`='$codproyectos', `descripcion` = '$descripcion', `horas` = '$horas' WHERE `codhoras` = $codhoras";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="Los datos del control han sido modificados correctamente"; }
	$cabecera1="Inicio >> Horas &gt;&gt; Modificar control horas ";
	$cabecera2="MODIFICAR CONTROL HORAS ";
}

if ($accion=="baja") {
	$codhoras=$_GET["codhoras"];
	$query="UPDATE horas SET borrado=1 WHERE codhoras='$codhoras'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="El control ha sido eliminado correctamente"; }
	$cabecera1="Inicio >> Horas &gt;&gt; Eliminar control horas ";
	$cabecera2="ELIMINAR CONTROL HORAS ";
	$query_mostrar="SELECT * FROM horas WHERE codhoras='$codhoras'";
	$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);

	$codusuario=mysqli_result($rs_mostrar, 0, "codusuario");
	$fecha=mysqli_result($rs_mostrar, 0, "fecha");
	$mantar=mysqli_result($rs_mostrar, 0, "mantar");
	$codcliente=mysqli_result($rs_mostrar, 0, "codcliente");
	$descripcion=mysqli_result($rs_mostrar, 0, "descripcion");
	$horas=mysqli_result($rs_mostrar, 0, "horas");
	$codproyectos=mysqli_result($rs_mostrar, 0, "codproyectos");

}

if ($accion=="alta" and @$_POST['Afechafin']!='' ) {
?>
<script type="text/javascript" >
document.location.href="nuevo_horas.php?codusuario="+<?php echo $codusuario;?>;
</script>
<?php
}else {
?>
	<script type="text/javascript" >
	//event.preventDefault();
	parent.$('idOfDomElement').colorbox.close();
	</script>

<?php
}
?>