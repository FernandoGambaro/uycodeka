<html>
<title></title>
<head>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<script type="text/javascript">
function round(value, exp) {
  if (typeof exp === 'undefined' || +exp === 0)
    return Math.round(value);

  value = +value;
  exp  = +exp;

  if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
    return NaN;

  // Shift
  value = value.toString().split('e');
  value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

  // Shift back
  value = value.toString().split('e');
  return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
}
</script>
<script>
function eliminar_linea_nc(codncreditotmp,numlinea,importe,codservice)
{
	if (confirm(" Desea eliminar esta linea ? "))
		parent.document.formulario_lineas_nc.baseimponible.value=parseFloat(parent.document.formulario_lineas_nc.baseimponible.value) - parseFloat(importe);
		var original=parseFloat(parent.document.formulario_lineas_nc.baseimponible.value);
		var result=round(original,2) ;
		parent.document.formulario_lineas_nc.baseimponible.value=result;

		parent.document.formulario_lineas_nc.baseimpuestos.value=parseFloat(result * parseFloat(parent.document.formulario.iva.value / 100));
		var original1=parseFloat(parent.document.formulario_lineas_nc.baseimpuestos.value);
		var result1=round(original1,2) ;
		parent.document.formulario_lineas_nc.baseimpuestos.value=result1;
		var original2=parseFloat(result + result1);
		var result2=round(original2,2) ;
		parent.document.formulario_lineas_nc.preciototal.value=result2;
		parent.cambio();
		document.getElementById("frame_datos").src="eliminar_linea_nc.php?codncreditotmp="+codncreditotmp+"&numlinea=" + numlinea+"&codservice=" + codservice;
}
</script>
</head>
<?php 
include ("../conectar.php");
$total_importe='';
$codncreditotmp=$_POST["codncreditotmp"];
$retorno=0;
$modif=@$_POST["modif"];
if ($modif!=1) {
		if (!isset($codncreditotmp)) { 
			$codncreditotmp=$_GET["codncreditotmp"]; 
			$retorno=1; }
		if ($retorno==0) {	
				$codfamilia=$_POST["codfamilia"];
				$codfactura=$_POST["codfactura"];
				$codarticulo=$_POST["codarticulo"];
				$cantidad=$_POST["cantidad"];
				$precio=$_POST["precio"];
				$importe=$_POST["importe"];
				$detalles=$_POST["detalles"];
				$moneda=$_POST["moneda"];
				$codservice=$_POST["codservice"];
				
				
				$sel_insert="INSERT INTO `ncreditolineatmp` (`codncredito`, `numlinea`, `codfactura`, `codfamilia`, `codigo`, `codservice`, `detalles`, `cantidad`, `moneda`, `precio`, `importe`)
				VALUES ('$codncreditotmp','','$codfamilia', '$codfactura', '$codigo','$codservice','$detalles','$cantidad','$moneda','$precio','$importe',)";	 

				$rs_insert=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insert);
/*
		$sel_actualiza="UPDATE service SET ncredito='$codncreditotmp' WHERE codservice='$codservice'";
		$rs_actualiza = mysql_query($sel_actualiza);
		*/
		}
}
?>
<body>
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">

			<div class="header" style="width:100%;position: fixed;">	DETALLES DE LA NOTA DE CRÉDITO </div>
			<div class="fixed-table-container">
      		<div class="header-background cabeceraTabla"> </div>      			
			<div class="fixed-table-container-inner">
			
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=0 border=0 id="Table1">
					<thead>
					  <tr class="cabeceraTabla">	
							<th width="14%" align="left"><div class="th-inner">REFERENCIA</div></th>
							<th width="10%" align="left"><div class="th-inner">Nº FACTURA</div></th>
							<th width="46%" align="left"><div class="th-inner">DESCRIPCION/DETALLES</div> </th>
							<th width="8%" align="left"><div class="th-inner">CANTIDAD</div></th>
							<th width="8%" align="left"><div class="th-inner">PRECIO</div></th>
							<th width="5%" align="left"><div class="th-inner">MON.</div></th>
							<th width="75px" align="left"><div class="th-inner">IMPORTE</div></th>
							<th width="20px"><div class="th-inner"></div></th>
							<th width="3%" align="left"><div class="th-inner">Com.%</div></th>
						</tr>
					</thead>
					<tbody>
<?php
$tipomon = array( 0=>"&nbsp;", 1=>"Pesos", 2=>"U\$S");
$total_importe='';
echo $sel_lineas="SELECT ncreditolineatmp.*, articulos.*,familias.nombre as nombrefamilia FROM ncreditolineatmp,articulos,familias 
WHERE ncreditolineatmp.codncredito='$codncreditotmp' AND ncreditolineatmp.codigo=articulos.codarticulo AND ncreditolineatmp.codfamilia=articulos.codfamilia 
AND articulos.codfamilia=familias.codfamilia ORDER BY ncreditolineatmp.numlinea ASC";
$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
for ($i = 0; $i < mysqli_num_rows($rs_lineas); $i++) {
	$numlinea=mysqli_result($rs_lineas, $i, "numlinea");
	$codfamilia=mysqli_result($rs_lineas, $i, "codfamilia");
	$codfactura=mysqli_result($rs_lineas, $i, "codfactura");
	$nombrefamilia=mysqli_result($rs_lineas, $i, "nombrefamilia");
	$referencia=mysqli_result($rs_lineas, $i, "referencia");
	$codarticulo=mysqli_result($rs_lineas, $i, "codarticulo");
	$descripcion=mysqli_result($rs_lineas, $i, "descripcion");
	$detalles=mysqli_result($rs_lineas, $i, "detalles");
	$cantidad=mysqli_result($rs_lineas, $i, "cantidad");
	$precio=mysqli_result($rs_lineas, $i, "precio");
	$codservice=mysqli_result($rs_lineas, $i, "codservice");
	$moneda=$tipomon[mysqli_result($rs_lineas, $i, "moneda")];
	$importe=mysqli_result($rs_lineas, $i, "importe");
	$total_importe=$total_importe+$importe;
	
	if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>
			<tr class="<?php echo $fondolinea?>">
				<?php if (trim($detalles)==''){ ?>
				<td width="14%">&nbsp;<?php echo $referencia;?></td>
				<td width="46%"><?php echo $descripcion;?></td>
				<?php } else { ?>
				<td width="14%">&nbsp;<?php echo $referencia;?></td>
				<td width="46%"><?php echo $detalles;?></td>
				<?php } ?>
				<td width="8%" class="aCentro"><?php echo $cantidad;?></td>
				<td width="8%" class="aCentro"><?php echo $precio;?></td>
				<td width="5%" class="aCentro"><?php echo $moneda;?></td>
				<td width="75px" class="aCentro cajaTotales" ><?php echo $importe;?>&nbsp;</td>
				<td width="20px" ><a href="javascript:eliminar_linea(<?php echo $codncreditotmp;?>,<?php echo $numlinea;?>,<?php echo $importe; ?>,<?php echo $codservice; ?>)"><img id="botonBusqueda" src="../img/eliminar.png" border="0"></a></td>
				
			</tr>
<?php }
 ?>
 </tbody>
</table>
</div></div></div></div></div>
<script type="text/javascript">
parent.pon_baseimponible(<?php echo $total_importe;?>);
</script>
<iframe id="frame_datos" name="frame_datos" width="0%" height="0" frameborder="0">
	<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
</iframe>
</body>
</html>
<?php
//mysql_close($rs_lineas);
?>