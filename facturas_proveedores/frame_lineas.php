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

?>
<html>
<title></title>
<head>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<script>
function eliminar_linea(codfacturatmp,numlinea,importe){
	if (confirm(" Desea eliminar esta linea ? ")){
		/*
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
		*/
		document.getElementById("frame_datos").src="eliminar_linea.php?codfacturatmp="+codfacturatmp+"&numlinea=" + numlinea;
		} else {
			return false;
		}		
}
</script>

<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<?php 
include ("../conectar.php");
$modif='';
$total_importe='';
$codfacturatmp=@$_POST["codfacturatmpa"];
$retorno=0;
if ($modif<>1) {
		if (!isset($codfacturatmp)) { 
			$codfacturatmp=$_GET["codfacturatmp"]; 
			$retorno=1;
			 }
		if ($retorno==0 and @$_POST['accion']!='baja') {	
				$codfamilia=$_POST["codfamilia"];
				$codarticulo=$_POST["codarticulo"];
				$cantidad=$_POST["cantidad"];
				$precio=$_POST["precio"];
				$importe=$_POST["importe"];
				$descuento=$_POST["descuento"];
				if($codarticulo>0 and $codarticulo!="") {
				$sel_insert="INSERT INTO factulineaptmp (codfactura,numlinea,codigo,codfamilia,cantidad,precio,importe,dcto) 
								VALUES ('$codfacturatmp','','$codarticulo','$codfamilia','$cantidad','$precio','$importe','$descuento')";
				$rs_insert=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insert);
				}
		}
}
?>

</head>
<body>
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">

			<div class="header" style="width:100%;position: fixed;">	DETALLES DE LA FACTURA </div>
			<div class="fixed-table-container">
      		<div class="header-background cabeceraTabla"> </div>      			
			<div class="fixed-table-container-inner">
			
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=0 border=0 id="Table1">
					<thead>
					  <tr class="cabeceraTabla">	
					  
							<th width="5%"><div class="th-inner">ITEM</div></th>
							<th width="20%"><div class="th-inner">REFERENCIA</div></th>
							<th width="39%"><div class="th-inner">DESCRIPCION</div></th>
							<th width="8%"><div class="th-inner">CANTIDAD</div></th>
							<th width="8%"><div class="th-inner">PRECIO</div></th>
							<th width="7%"><div class="th-inner">DCTO %</div></th>
							<th width="8%"><div class="th-inner">IMPORTE</div></th>
							<th width="3%"><div class="th-inner">&nbsp;</div></th>
						</tr>
					</thead>
					<tbody>


<?php
$sel_lineas="SELECT factulineaptmp.*,articulos.*,familias.nombre as nombrefamilia FROM factulineaptmp,articulos,familias WHERE factulineaptmp.codfactura='$codfacturatmp' AND factulineaptmp.codigo=articulos.codarticulo AND factulineaptmp.codfamilia=articulos.codfamilia AND articulos.codfamilia=familias.codfamilia ORDER BY factulineaptmp.numlinea ASC";
$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
for ($i = 0; $i < mysqli_num_rows($rs_lineas); $i++) {
	$numlinea=mysqli_result($rs_lineas, $i, "numlinea");
	$codfamilia=mysqli_result($rs_lineas, $i, "codfamilia");
	$nombrefamilia=mysqli_result($rs_lineas, $i, "nombrefamilia");
	$codarticulo=mysqli_result($rs_lineas, $i, "codarticulo");
	$descripcion=mysqli_result($rs_lineas, $i, "descripcion");
	$cantidad=mysqli_result($rs_lineas, $i, "cantidad");
	$precio=mysqli_result($rs_lineas, $i, "precio");
	$importe=mysqli_result($rs_lineas, $i, "importe");
	$referencia=mysqli_result($rs_lineas, $i, "referencia");
	$total_importe=$total_importe+$importe;
	$descuento=mysqli_result($rs_lineas, $i, "dcto");
	if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>
			<tr class="<?php echo $fondolinea?>">
				<td width="5%"><?php echo $i+1?></td>
				<td width="20%"><?php echo $referencia?></td>
				<td width="40%"><?php echo $descripcion?></td>
				<td width="8%" class="aCentro"><?php echo $cantidad?></td>
				<td width="8%" class="aCentro"><?php echo $precio?></td>
				<td width="7%" class="aCentro"><?php echo $descuento?></td>
				<td width="8%" class="aCentro cajaTotales"><?php echo $importe?></td>
				<td width="3%"><a href="javascript:eliminar_linea(<?php echo $codfacturatmp;?>,<?php echo $numlinea;?>,<?php echo $importe;?>)"><img src="../img/eliminar.png" border="0"></a></td>
			</tr>
<?php } ?>
</tbody>
</table>

<script type="text/javascript">
parent.pon_baseimponible(<?php echo $total_importe;?>);
</script>

<iframe id="frame_datos" name="frame_datos" width="0%" height="0" frameborder="0">
	<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
</iframe>
