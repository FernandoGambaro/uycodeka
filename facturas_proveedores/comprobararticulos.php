<?php
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
?>
<html>
<head>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
</head>
<script language="javascript">

function pon_prefijo(codfamilia,codigobarras,referencia,descripcion,precio_compra,codarticulo,moneda,descripcion) {
	parent.pon_prefijo_Fb(codfamilia,codigobarras,referencia,descripcion,precio_compra,codarticulo,moneda,descripcion);
	parent.$('idOfDomElement').colorbox.close();

}
</script>
<?php include ("../conectar.php"); 
$codbarras=$_GET["codbarras"];

$where="1=1";

if ($codbarras<>'') { $where.=" AND ( articulos.referencia like '$codbarras' or articulos.codigobarras like '$codbarras') "; }

 ?>
<body >
<?php
	$tipomon = array( 0=>"Selecione uno", 1=>"Pesos", 2=>"U\$S");
	$consulta="SELECT articulos.*,familias.nombre as nombrefamilia FROM articulos,familias WHERE ".$where." AND articulos.codfamilia = familias.codfamilia AND articulos.borrado=0 ORDER BY articulos.codfamilia ASC,articulos.descripcion ASC";
	$rs_tabla = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
	$nrs=mysqli_num_rows($rs_tabla);
 if ($nrs>0) {
			for ($i = 0; $i < mysqli_num_rows($rs_tabla); $i++) {

				
			$codfamilia=mysqli_result($rs_tabla, $i, "codfamilia");
			$codigobarras=mysqli_result($rs_tabla, $i, "codigobarras");
			$nombrefamilia=mysqli_result($rs_tabla, $i, "nombrefamilia");
			$referencia=mysqli_result($rs_tabla, $i, "referencia");
			$codarticulo=mysqli_result($rs_tabla, $i, "codarticulo");				
			$descripcion=mysqli_result($rs_tabla, $i, "descripcion");
			$descripcion=str_replace(array("'", "\""), "&quot;", $descripcion);				
			$descripcion_corta=mysqli_result($rs_tabla, $i, "descripcion_corta");
			$precio_compra=mysqli_result($rs_tabla, $i, "precio_compra");
			$moneda=mysqli_result($rs_tabla, $i, "moneda");				
			$precio_compra=mysqli_result($rs_tabla, $i, "precio_compra");				

	}
?>
<script language="javascript">
pon_prefijo('<?php echo $codfamilia;?>','<?php echo $codigobarras;?>',
'<?php echo $referencia;?>','<?php echo htmlentities($descripcion, ENT_QUOTES);?>',
'<?php echo $precio_compra;?>','<?php echo $codarticulo;?>','<?php echo $moneda;?>',
'<?php echo $descripcion;?>');

</script>

<?php
} else {
?>
<br>
<div class="header">
No Existe ese código de artículo
</div>
<script>setTimeout(function() {parent.$('#codbarras').focus(); parent.$('idOfDomElement').colorbox.close();}, 1000);</script>
<?php
}
?>
</body>
</html>
