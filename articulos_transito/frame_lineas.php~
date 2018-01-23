<html>
<title></title>
<head>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<script>
function eliminar_linea(codartiviajatmp,numlinea)
{
	if (confirm(" Desea eliminar esta linea ? "))
		document.getElementById("frame_datos").src="eliminar_linea.php?codartiviajatmp="+codartiviajatmp+"&numlinea=" + numlinea;
}
</script>
</head>
<?php 
include ("../conectar.php");
$total_importe='';
$codartiviajatmp=@$_POST["codartiviajatmp"];
$retorno=0;
$modif=@$_POST["modif"];
if ($modif!=1) {
		if (!isset($codartiviajatmp)) { 
			$codartiviajatmp=$_GET["codartiviajatmp"]; 
			$retorno=1; }
		if ($retorno==0) {	
				$codfamilia=$_POST["codfamilia"];
				$codarticulo=$_POST["codarticulo"];
				$cantidad=@$_POST["cantidad"];
				$detalles=@$_POST["detalles"];
				
				$sel_insert="INSERT INTO artiviajalineatmp (codartiviaja,numlinea,codfamilia,codigo,detalles,cantidad) 
				VALUES ('$codartiviajatmp','','$codfamilia','$codarticulo','$detalles','$cantidad')";
				$rs_insert=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insert);
		}
}
?>
<body>
<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 id="Table1">
<?php
$sel_lineas="SELECT artiviajalineatmp.*,articulos.*,familias.nombre as nombrefamilia FROM artiviajalineatmp,articulos,familias 
WHERE artiviajalineatmp.codartiviaja='$codartiviajatmp' AND artiviajalineatmp.codigo=articulos.codarticulo AND artiviajalineatmp.codfamilia=articulos.codfamilia 
AND articulos.codfamilia=familias.codfamilia ORDER BY artiviajalineatmp.numlinea ASC";
$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
for ($i = 0; $i < mysqli_num_rows($rs_lineas); $i++) {
	$numlinea=mysqli_result($rs_lineas, $i, "numlinea");
	$codfamilia=mysqli_result($rs_lineas, $i, "codfamilia");
	$nombrefamilia=mysqli_result($rs_lineas, $i, "nombrefamilia");
	$referencia=mysqli_result($rs_lineas, $i, "referencia");
	$codarticulo=mysqli_result($rs_lineas, $i, "codarticulo");
	$descripcion=mysqli_result($rs_lineas, $i, "descripcion");
	$detalles=mysqli_result($rs_lineas, $i, "detalles");
	$cantidad=mysqli_result($rs_lineas, $i, "cantidad");
	if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>
			<tr class="<?php echo $fondolinea?>">
				<td width="3%"><?php echo $i+1?></td>
				<td width="14%"><?php echo $referencia;?></td>
				<td width="34%"><?php echo $descripcion;?></td>
				<td width="42%"><?php echo $detalles;?></td>

				<td width="8%" class="aCentro"><?php echo $cantidad;?></td>
				<td width="4%"><a href="javascript:eliminar_linea(<?php echo $codartiviajatmp;?>,<?php echo $numlinea;?>)">
				<img id="botonBusqueda" src="../img/eliminar.png" border="0"></a></td>
			</tr>
<?php }
 ?>
</table>
<iframe id="frame_datos" name="frame_datos" width="0%" height="0" frameborder="0">
	<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
</iframe>
</body>
</html>