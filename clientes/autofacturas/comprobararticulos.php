<?php
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
?>
<html>
<head>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
</head>
<script language="javascript">

function pon_prefijo(codfamilia,referencia,codigobarras,nombre,precio,codarticulo,moneda,codservice,detalles,comision) {
	parent.pon_prefijo_Fb(codfamilia,referencia,codigobarras,nombre,precio,codarticulo,moneda,'',detalles,comision);
}

</script>
<?php
include ("../../conectar.php"); 
include("../../common/funcionesvarias.php");

$codbarras=$_GET["codbarras"];

$where="1=1";

if ($codbarras<>'') { $where.=" AND ( articulos.codigobarras='$codbarras' or articulos.referencia='$codbarras')"; }

 ?>
<body >
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">

<?php
	$tipomon = array( 0=>"Selecione uno", 1=>"Pesos", 2=>"U\$S");
	$consulta="SELECT articulos.*,familias.nombre as nombrefamilia FROM articulos,familias WHERE ".$where." AND articulos.codfamilia=familias.codfamilia AND articulos.borrado=0 ORDER BY articulos.codfamilia ASC,articulos.descripcion ASC";
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
				$moneda=mysqli_result($rs_tabla, $i, "moneda");				
				$costo=mysqli_result($rs_tabla, $i, "precio_compra");
				$precio=mysqli_result($rs_tabla, $i, "precio_tienda");
				$comision=mysqli_result($rs_tabla, $i, "comision");				
	}
?>
<script language="javascript">
pon_prefijo ('<?php echo $codfamilia;?>','<?php echo $referencia;?>','<?php echo $codigobarras;?>','<?php echo addslashes(str_replace('"','&quot;',$descripcion));?>','<?php echo $precio;?>','<?php echo $codarticulo;?>','<?php echo $moneda;?>','','<?php echo addslashes(str_replace('"','&quot;',$descripcion));?>','<?php echo $comision;?>');
</script>

<?php
} else {
?>
<span style="position: absolute; left:33%; top: 21%;" >
<img src="../img/noexiste.png" width="67" height="66" alt=""><br>
No Existe ese artículo
</span>
<?php
}
?>
</div></div></div>
</body>
</html>
