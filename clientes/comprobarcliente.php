<?php
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
?>
<html>
<head>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
</head>
<script language="javascript">

function pon_prefijo(nombre,nif) {
	parent.document.form_busqueda.nombre.value=nombre;
	parent.document.form_busqueda.nif.value=nif;
}

function limpiar() {
	parent.document.form_busqueda.nombre.value="";
	parent.document.form_busqueda.nif.value="";
	parent.document.form_busqueda.codcliente.value="";
}

</script>
<?php include ("../conectar.php"); ?>
<body>
<?php
	$codcliente=$_GET["codcliente"];
	$consulta="SELECT * FROM clientes WHERE codcliente='$codcliente' AND borrado=0";
	$rs_tabla = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
	if (mysqli_num_rows($rs_tabla)>0) {
		?>
		<script languaje="javascript">
		pon_prefijo("<?php echo mysqli_result($rs_tabla, 0, nombre) ?> <?php echo mysqli_result($rs_tabla, 0, apellido) ?>","<?php echo mysqli_result($rs_tabla, 0, nif) ?>");
		parent.$('idOfDomElement').colorbox.close();
		</script>
		<?php 
	} else { ?>
	<script>
	alert ("No existe ningun cliente con ese codigo");
	limpiar();
	parent.$('idOfDomElement').colorbox.close();
	</script>
	<?php }
?>
<br>
Validando...
</div>
</body>
</html>
