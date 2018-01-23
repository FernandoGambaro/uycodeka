<?php
include ("../conectar.php");
header('Content-Type: text/html; charset=UTF-8'); 
require("../funciones/funcionesvarias.php");
include ("../funciones/fechas.php");

header('Cache-Control: no-cache');
header('Pragma: no-cache'); 
header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set('America/Montevideo');

$sql_usuario="SELECT * FROM `usuarios` WHERE `borrado` = '0' and tipo!=1 ORDER BY `codusuarios` ASC";
$res_usuario=mysqli_query($GLOBALS["___mysqli_ston"], $sql_usuario);
$num_usuario = mysqli_num_rows($res_usuario);

if (empty($_GET['anio'])) {
$anio=date('Y');
} else {
$anio=$_GET['anio'];
}
if (empty($_GET['mes'])) {
$mes=date('m');
} else {
$mes=$_GET['mes'];
}

$fechainicio=isset($_POST["fechainicio"]) ? $_POST["fechainicio"] : null ;
$fechafin=isset($_POST["fechafin"]) ? $_POST["fechafin"] : null ;


function lastDateOfMonth($Month, $Year=-1) {
    if ($Year < 0) $Year = 0+date("Y");
    $aMonth         = mktime(0, 0, 0, $Month, 1, $Year);
    $NumOfDay       = 0+date("t", $aMonth);
    $LastDayOfMonth = mktime(0, 0, 0, $Month, $NumOfDay, $Year);
    return $LastDayOfMonth;
}

if($fechainicio<>"") {
$primer_dia=explota($fechainicio);	

} else {
$primer_dia="".$anio."-".$mes."-01";
}
if($fechafin<>"") {
$ultimo_dia=explota($fechafin);
$detalle="las fechas ".$fechainicio." y ".$fechafin;
} else {
$ultimo_dia=date("Y-n-j", lastDateOfMonth($mes,$anio));
$detalle="el mes de ".mes($mes) . " - ".$anio;
}
//$ultimo_dia="".$anio."-".$mes."-".date('t');




$dividefecha = explode("-", explota($fechainicio));
$dividefecha1 = explode("-", explota($fechafin));
 
// $dividefecha[0] = Mes
// $dividefecha[1] = Dia
// $dividefecha[2] = Ano
 
$fecha_previa = mktime(0, 0, $dividefecha[0], $dividefecha[1], $dividefecha[2]); //Convertimos $fecha_desde en formato timestamp
$fecha_hasta = mktime(0, 0, $dividefecha1[0], $dividefecha1[1], $dividefecha1[2]); //Convertimos $fecha_desde en formato timestamp
 
$segundos = $fecha_previa - $fecha_hasta; // Obtenemos los segundos entre esas dos fechas
$segundos = abs($segundos); //en caso de errores
 
$semanas = floor($segundos / 604800); //Obtenemos las semanas entre esas fechas.

if($semanas==0) { 
$semanas=1;
}
$total_usuario_proyecto='';
?>
<html>
<head>
	
	<meta HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
	<title></title>
	<meta name="generator" content="Bluefish 2.2.8" >
	<meta name="author" content="root" >
	<meta NAME="CREATED" CONTENT="20121119;11123800">
	<meta NAME="CHANGEDBY" CONTENT="Fernando Gámbaro">
	<meta NAME="CHANGED" CONTENT="20121119;11230400">
	
	<STYLE>
		<!-- 
		BODY,DIV,TABLE,THEAD,TBODY,TFOOT,tr,TH,td,P { font-family:"Arial"; font-size:x-small }
		 -->
	</STYLE>
	
</head>

<body text="#000000">
<table cellspacing="0" border="0">
	<tr>
		<td height="24" COLSPAN="13" align="LEFT"><font size=4>Detalles de horas realizadas por usuario y por proyecto durante <?php echo $detalle;?>
		<br>Valores expresados en números</font></td>
<?php
	for ($x=1; $x<=$num_usuario; $x++) {
?>		
		<td align="LEFT"><br></td>
<?php
	}
?>
		<td align="LEFT"><br></td>
	</tr>
	
	<tr>
		<td height="16" align="LEFT"><br></td>
<?php
	for ($x=1; $x<=$num_usuario; $x++) {
?>		
		<td align="LEFT"><br></td>
<?php
	}
?>
		<td align="LEFT"><br></td>
		<td align="LEFT"><br></td>
	</tr>
	
	<tr>
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="17" align="LEFT" BGCOLOR="#FF6633">Usuario</td>
<?php
	while ($row_usuario=@mysqli_fetch_array($res_usuario))
	{
?>
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="LEFT" BGCOLOR="#FF6633"><?php echo $row_usuario['nombre']."&nbsp;".$row_usuario['apellido'];?></td>
<?php	
	}
?>
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="LEFT" BGCOLOR="#0099FF">Total</td>
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="LEFT" BGCOLOR="#FFFFFF">Horas</td>
	</tr>
	<tr>
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="16" align="LEFT" BGCOLOR="#CCCCCC">Cliente</td>
<?php
	for ($x=1; $x<=$num_usuario; $x++) {
?>		
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="LEFT" BGCOLOR="#000000" SDNUM="14346;0;HH:MM"><br></td>
<?php
	}
?>
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="LEFT" BGCOLOR="#000000"><br></td>
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="LEFT" BGCOLOR="#ffffff">asig.</td>
	</tr>
<?php
 $sql_proyecto="SELECT horas.codusuario,horas.horas,horas.codcliente, clientes.nombre, clientes.empresa FROM
 `horas`, clientes WHERE horas.codcliente=clientes.codcliente AND `fecha` BETWEEN '$primer_dia' AND '$ultimo_dia' GROUP BY codcliente ORDER BY `fecha` DESC";
 $sql_proyecto="SELECT * FROM clientes WHERE service=2 ORDER BY nombre ASC";
$res_proyecto=mysqli_query($GLOBALS["___mysqli_ston"], $sql_proyecto);
while ($row_proyecto=mysqli_fetch_array($res_proyecto))
{
	$codcliente=$row_proyecto['codcliente'];
	$total_proyecto[$codcliente]=0;
?>
	<tr>
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="16" align="LEFT"
		 BGCOLOR="#CCCCCC"><?php echo str_replace(" ", "&nbsp;",$row_proyecto['nombre']);?></td>
<?php
	$sql_usuario="SELECT * FROM `usuarios` WHERE `borrado` = '0' and tipo!=1 ORDER BY `codusuarios` ASC";
	$res_usuario=mysqli_query($GLOBALS["___mysqli_ston"], $sql_usuario);
	//echo "<td>".$sql_usuario."</td>";
	while ($row_usuario=mysqli_fetch_array($res_usuario))
	{
		$codusuarios=$row_usuario['codusuarios'];  
		
		$sql="SELECT horas FROM horas WHERE `codcliente`='".$codcliente."' AND `codusuario` ='".$codusuarios."' AND borrado=0  AND fecha >= '".$primer_dia."'  
		AND fecha <= '".$ultimo_dia. "' ORDER BY `fecha` DESC";
     // $sql="SELECT horas FROM `horas` WHERE `codcliente` ='".$codcliente."' AND `codusuario` ='".$codusuarios."'
     //  AND `fecha` BETWEEN '$primer_dia' AND '$ultimo_dia' ORDER BY `fecha` DESC";
       
    //echo $sql."<br>";
      $ListarTotales = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
      
   	if (mysqli_num_rows($ListarTotales)>0) {
   		$total_usuario="";

      while ($Controlrow = mysqli_fetch_array($ListarTotales))
      {
      	$total_usuario=@SumaHoras(@$Controlrow['horas'],$total_usuario);
         $total_proyecto[$codcliente]=@SumaHoras($total_proyecto[$codcliente],@$Controlrow['horas']);
			$total_usuario_proyecto[$codusuarios]=@SumaHoras($total_usuario_proyecto[$codusuarios],@$Controlrow['horas']);         
         
         //$total_usuario_proyecto[$codusuarios]=date("H:i",strtotime($total_usuario)+strtotime($total_usuario_proyecto[$codusuarios]));
      }
			$array = split(":", $total_usuario);
			$total_usuario1=($array[0]*60+$array[1])/60;
			$total_usuario1=number_format($total_usuario1, 2, ',', ' ');
?>				
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="RIGHT"
		 SDVAL="<?php echo $total_usuario;?>"><?php echo $total_usuario1;?></td>
<?php
		$total_usuario="";		
		} else {
?>
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="RIGHT" >&nbsp;</td>
<?php			
		}
	}
	$array = split(":", $total_proyecto[$codcliente]);
	$total_usuario1=($array[0]*60+@$array[1])/60;
	$total_proyecto[$codcliente]=number_format($total_usuario1, 2, ',', ' ');
	
?>	
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="RIGHT" BGCOLOR="#0099FF" SDVAL="<?php echo $total_proyecto[$codcliente];?>">
		<?php echo $total_proyecto[$codcliente];?></td>
				<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="RIGHT" BGCOLOR="#FFFFFF" SDVAL="<?php echo $row_proyecto['horas'];?>">
		<?php echo $row_proyecto['horas']*$semanas;?></td>
	</tr>
<?php
$total_usuario="";
}
?>		

	<tr>
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="16" align="RIGHT" BGCOLOR="#FF3333">Total</td>
<?php
	$sql_usuario="SELECT * FROM `usuarios` WHERE `borrado` = '0' and tipo!=1 ORDER BY `codusuarios` ASC";
	$res_usuario=mysqli_query($GLOBALS["___mysqli_ston"], $sql_usuario);
	while ($row_usuario=@mysqli_fetch_array($res_usuario))
	{
		$codusuarios=$row_usuario['codusuarios'];
	$array = @split(":", $total_usuario_proyecto[$codusuarios]);
	$total_usuario1=@($array[0]*60+$array[1])/60;
	@$total_usuario_proyecto[$codusuarios]=number_format($total_usuario1, 2, ',', ' ');		
?>
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="RIGHT" BGCOLOR="#FF3333" SDVAL="<?php echo $total_usuario_proyecto[$codusuarios];?>"><?php echo $total_usuario_proyecto[$codusuarios];?></td>
<?php
	}
?>

		<td align="LEFT" SDNUM="14346;0;HH:MM"><br></td>
		<td align="LEFT" SDNUM="14346;0;HH:MM"><br></td>
	</tr>
</TABLE>
<!-- ************************************************************************** -->
</BODY>

</HTML>
