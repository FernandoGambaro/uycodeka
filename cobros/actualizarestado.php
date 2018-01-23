<?php
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
?>
<html>
<head>
</head>
<?php include ("../conectar.php"); ?>
<body>
<?php
	$estado=$_GET["estado"];
	$codfactura=$_GET["codfactura"];
	$act_factura="UPDATE facturas SET estado='$estado' WHERE codfactura='$codfactura'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $act_factura);
?>
</body>
</html>
