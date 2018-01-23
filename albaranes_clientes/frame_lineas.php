<?php
$modif=$_POST['modif'];
$total_importe=0;
?>

<html>
<title></title>
<head>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<script>
function eliminar_linea(codalbarantmp,numlinea,importe)
{
	if (confirm(" Desea eliminar esta linea ? "))
		parent.document.formulario_lineas.baseimponible.value=parseFloat(parent.document.formulario_lineas.baseimponible.value) - parseFloat(importe);
		var original=parseFloat(parent.document.formulario_lineas.baseimponible.value);		
		var result=Math.round(original*100)/100 ;
		parent.document.formulario_lineas.baseimponible.value=result;

		parent.document.formulario_lineas.baseimpuestos.value=parseFloat(result * parseFloat(parent.document.formulario.iva.value / 100));
		var original1=parseFloat(parent.document.formulario_lineas.baseimpuestos.value);
		var result1=Math.round(original1*100)/100 ;
		parent.document.formulario_lineas.baseimpuestos.value=result1;
		var original2=parseFloat(result + result1);
		var result2=Math.round(original2*100)/100 ;
		parent.document.formulario_lineas.preciototal.value=result2;
		
		document.getElementById("frame_datos").src="eliminar_linea.php?codalbarantmp="+codalbarantmp+"&numlinea=" + numlinea;
}
</script>
<?php 
include ("../conectar.php");
$codalbarantmp=$_POST["codalbarantmp"];
$retorno=0;
if ($modif<>1) {
		if (!isset($codalbarantmp)) { 
			$codalbarantmp=$_GET["codalbarantmp"]; 
			$retorno=1; }
		if ($retorno==0) {	
				$codfamilia=$_POST["codfamilia"];
				$codarticulo=$_POST["codarticulo"];
				$cantidad=$_POST["cantidad"];
				$precio=$_POST["precio"];
				$importe=$_POST["importe"];
				$descuento=$_POST["descuento"];
				$detalles=$_POST["detalles"];
				
				$sel_insert="INSERT INTO albalineatmp (codalbaran,numlinea,codigo,codfamilia,detalles,cantidad,precio,importe,dcto) VALUES ('$codalbarantmp','','$codarticulo','$codfamilia','$detalles','$cantidad','$precio','$importe','$descuento')";
				$rs_insert=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insert);
		}
}
?>
</head>
<body style="margin: 0px; padding: 0px;">

<table class="fuente8" width="100%" cellspacing=0 cellpadding=0 border=0 ID="Table1">

<?php
$sel_lineas="SELECT albalineatmp.*,articulos.*,familias.nombre as nombrefamilia FROM albalineatmp,articulos,familias WHERE albalineatmp.codalbaran='$codalbarantmp' AND albalineatmp.codigo=articulos.codarticulo AND albalineatmp.codfamilia=articulos.codfamilia AND articulos.codfamilia=familias.codfamilia ORDER BY albalineatmp.numlinea ASC";
$tipomon = array( 0=>"&nbsp;", 1=>"Pesos", 2=>"U\$S");

$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
for ($i = 0; $i < mysqli_num_rows($rs_lineas); $i++) {
	$numlinea=mysqli_result($rs_lineas, $i, "numlinea");
	$codfamilia=mysqli_result($rs_lineas, $i, "codfamilia");
	$detalles=mysqli_result($rs_lineas, $i, "detalles");
	$nombrefamilia=mysqli_result($rs_lineas, $i, "nombrefamilia");
	$codarticulo=mysqli_result($rs_lineas, $i, "codarticulo");
	$descripcion=mysqli_result($rs_lineas, $i, "descripcion");
	$cantidad=mysqli_result($rs_lineas, $i, "cantidad");
	$referencia=mysqli_result($rs_lineas, $i, "referencia");
	$precio=mysqli_result($rs_lineas, $i, "precio");
	$moneda=$tipomon[mysqli_result($rs_lineas, $i, "moneda")];
	$importe=mysqli_result($rs_lineas, $i, "importe");
	$total_importe=$total_importe+$importe;
	$descuento=mysqli_result($rs_lineas, $i, "dcto");
	if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>
			<tr class="<?php echo $fondolinea?>">
				<td width="5%" class="aCentro"><?php echo $i+1?></td>
				<td width="20%" class="aCentro"><?php echo $referencia?></td>
				<td width="39%"><?php echo $descripcion; if ($detalles!="") echo " - ".$detalles;?></td>
				<td width="8%" class="aCentro"><?php echo $cantidad?></td>
				<td width="8%" class="aCentro"><?php echo $precio?></td>
				<td width="7%" class="aCentro"><?php echo $descuento?></td>
				<td width="7%" class="aCentro"><?php echo $moneda;?></td>
				<td width="8%" class="aCentro"><?php echo $importe?></td>
				<td width="40px" class="aCentro"><a href="javascript:eliminar_linea(<?php echo $codalbarantmp?>,<?php echo $numlinea?>,<?php echo $importe ?>);">
				<img id="botonBusqueda" src="../img/eliminar.png" border="0"></a></td>
			</tr>
<?php } ?>
</table>
<script type="text/javascript">
parent.pon_baseimponible(<?php echo $total_importe;?>);
</script>
<iframe id="frame_datos" name="frame_datos" width="0%" height="0" frameborder="0">
	<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
</iframe>
</body>
</html>
