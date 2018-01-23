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
function agregar_factura(codfactura,moneda)
{
document.getElementById(codfactura).style.display = 'none';	
parent.agregar_factura(codfactura,moneda);

}
</script>
</head>
<?php 
include ("../conectar.php");
include ("../funciones/fechas.php");

$codcliente=$_POST["cdocliente"];
$moneda=$_POST["moneda"];

$where="1=1";
if ($codcliente <> "") { $where.=" AND facturas.codcliente='$codcliente'"; }
if ($moneda <> "") { $where.=" AND facturas.moneda='$moneda'"; }
?>
<body>
<div id="pagina">
	<div id="zonaContenido">
	<div align="center">
	<div class="header" style="width:100%;position: fixed;">FACTURAS PENDIENTES DE COBRO</div>
	<div class="fixed-table-container">
		<div class="header-background cabeceraTabla"> </div>      			
	<div class="fixed-table-container-inner">			
	
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=2 border=0>
			<thead>			
				<tr class="cabeceraTabla">
					<th><div class="th-inner">Nº</div></th>
					<th><div class="th-inner">FECHA</div></th>
					<th><div class="th-inner">MON.&nbsp;</div></th>
					<th><div class="th-inner">&nbsp;TOTAL</div> </th>							
					<th ><div class="th-inner">&nbsp;</div></th>
				</tr>
			</thead>
			<tbody>




<?php
							/*Genero un array con los simbolos de las monedas*/
							$moneda = array();
							$sel_monedas="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
						   $res_monedas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_monedas);
						   $con_monedas=0;
							$xmon=1;
						 while ($con_monedas < mysqli_num_rows($res_monedas)) {
						 	$descripcion=split(" ", mysqli_result($res_monedas, $con_monedas, "simbolo"));
						 	 $moneda[$xmon]= $descripcion[0];
						 	 $con_monedas++;
						 	 $xmon++;
						 }			
$sel_facturas="SELECT codcliente,codfactura,fecha,tipo,moneda,estado,totalfactura FROM facturas WHERE borrado=0 AND tipo=1 AND (estado=1 or estado=4) AND ".$where;
$rs_facturas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_facturas);

for ($i = 0; $i < mysqli_num_rows($rs_facturas); $i++) {
	if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>
			<tr class="<?php echo $fondolinea?>" id="<?php echo mysqli_result($rs_facturas, $i, "codfactura");?>">
			
				<th class="aCentro"><?php echo mysqli_result($rs_facturas, $i, "codfactura");?></th>
				<th class="aCentro"><?php echo implota(mysqli_result($rs_facturas, $i, "fecha"));?></th>
				<th class="aCentro"><div align="center"><?php echo $moneda[mysqli_result($rs_facturas, $i, "moneda")];?></div></th>
				<th class="aCentro"><?php echo mysqli_result($rs_facturas, $i, "totalfactura");?></th>
				<?php if ($codcliente <> "") { ?>
				<th width="20px" ><a href="javascript:agregar_factura(<?php echo mysqli_result($rs_facturas, $i, "codfactura");?>,<?php echo mysqli_result($rs_facturas, $i, "moneda");?>)">
				<img id="botonBusqueda" src="../img/convertir.png" border="0"></a></th>
				<?php } else { ?>
				<th>&nbsp;<img id="botonBusqueda" src="../img/blank.png" width="16" height="16" border="0" ></th>
				<?php } ?>
				
			
			</tr>
<?php }
 ?>
</table>
</div></div></div></div></div>

</body>
</html>