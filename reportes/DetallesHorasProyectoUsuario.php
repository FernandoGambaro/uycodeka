<?php
include("../conexion.php");
require("../funcionesvarias.php");

$mes=$_GET['mes'];

$sql_usuario="SELECT * FROM `usuarios` WHERE `borrado` = '0' ORDER BY `codusuarios` ASC";
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

$primer_dia="".$anio."-".$mes."-01";
$ultimo_dia="".$anio."-".$mes."-".date('t');

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
		<td height="24" COLSPAN="13" align="LEFT"><font size=4>Detalles de horas realizadas por usuario y por proyecto durante el mes de <?php echo mes($mes) . " - ".$anio;?></font></td>
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
	</tr>
	
	<tr>
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="17" align="LEFT" BGCOLOR="#FF6633">Usuario</td>
<?php
	while ($row_usuario=mysqli_fetch_array($res_usuario))
	{
?>
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="LEFT" BGCOLOR="#FF6633"><?php echo $row_usuario['USUARIONOM']."&nbsp;".$row_usuario['USUARIOAPE'];?></td>
<?php	
	}
?>
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="LEFT" BGCOLOR="#0099FF">Total</td>
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
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="LEFT" BGCOLOR="#000000" SDNUM="14346;0;HH:MM"><br></td>
	</tr>
<?php
$sql_proyecto="SELECT * FROM `horas` WHERE `fecha` BETWEEN '$primer_dia' AND '$ultimo_dia' ORDER BY `CONtrOLFECHA` DESC group by codcliente";
$res_proyecto=mysqli_query($GLOBALS["___mysqli_ston"], $sql_proyecto);
while ($row_proyecto=mysqli_fetch_array($res_proyecto))
{
	$codcliente=$row_proyecto['codcliente'];
	$total_proyecto[$codcliente]=0;
?>
	<tr>
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="16" align="LEFT" BGCOLOR="#CCCCCC"><?php echo str_replace(" ", "&nbsp;",$row_proyecto[companyName]);?></td>
<?php
	$sql_usuario="SELECT * FROM `usuarios` WHERE `borrado` = '0' ORDER BY `codusuarios` ASC";
	$res_usuario=mysqli_query($GLOBALS["___mysqli_ston"], $sql_usuario);
	//echo "<td>".$sql_usuario."</td>";
	while ($row_usuario=mysqli_fetch_array($res_usuario))
	{
		$codusuarios=$row_usuario[codusuarios];
      $sql="SELECT * FROM `CONtrOL` WHERE `codcliente` ='".$codcliente."' AND `codusuarios` ='".$row_usuario['codusuarios']."' AND `CONtrOLFECHA` BETWEEN '$primer_dia' AND '$ultimo_dia' ORDER BY `CONtrOLFECHA` DESC";
//      echo $sql."<br>";
      $ListarTotales = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
   	if (mysqli_num_rows($ListarTotales)>0) {
      while ($Controlrow = mysqli_fetch_array($ListarTotales))
      {
         $total_usuario+=$Controlrow['CONtrOLDATA'];
         $total_proyecto[$codcliente]+=$Controlrow['CONtrOLDATA'];
         $total_usuario_proyecto[$codusuarios]+=$Controlrow['CONtrOLDATA'];
      }
?>				
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="RIGHT" SDVAL="<?php echo $total_usuario;?>"><?php echo $total_usuario;?></td>
<?php
		$total_usuario=0;		
		} else {
?>
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="RIGHT" >0</td>
<?php			
		}
	}
?>	
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="RIGHT" BGCOLOR="#0099FF" SDVAL="<?php echo $total_proyecto[$codcliente];?>"><?php echo $total_proyecto[$codcliente];?></td>
	</tr>
<?php
$total_usuario=0;
}
?>		

	<tr>
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="16" align="RIGHT" BGCOLOR="#FF3333">Total</td>
<?php
	$sql_usuario="SELECT * FROM `usuarios` WHERE `borrado` = '0' ORDER BY `codusuarios` ASC";
	$res_usuario=mysqli_query($GLOBALS["___mysqli_ston"], $sql_usuario);
	while ($row_usuario=mysqli_fetch_array($res_usuario))
	{
		$codusuarios=$row_usuario[codusuarios];
?>
		<td STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="RIGHT" BGCOLOR="#FF3333" SDVAL="<?php echo $total_usuario_proyecto[$codusuarios];?>"><?php echo $total_usuario_proyecto[$codusuarios];?></td>
<?php
	}
?>

		<td align="LEFT" SDNUM="14346;0;HH:MM"><br></td>
	</tr>
</TABLE>
<!-- ************************************************************************** -->
</BODY>

</HTML>
