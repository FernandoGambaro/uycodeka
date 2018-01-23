<?php

$codproveedor=$_POST["codproveedor"];
$nombre=$_POST["nombre"];

$where="1=1";

if ($codcliente<>"") { $where.=" AND proveedores.codproveedor like '%$codproveedor%'"; }
if ($nombre<>"") { $where.=" AND proveedores.nombre like '%$nombre%'"; }

//header('Content-Type: text/html; charset=UTF-8'); 
date_default_timezone_set('America/Montevideo');

//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); ?>
<html>
<head>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<script src="js/jquery.min.js"></script>
</head>


<script language="javascript">

function pon_prefijo(pref,nombre,nif) {
window.top.principal.pon_prefijo(pref,nombre,nif);
}

</script>

<?php include ("../conectar.php"); ?>
<body>
<?php
	
	$consulta="SELECT * FROM proveedores WHERE ".$where." AND borrado=0 ORDER BY codproveedor ASC";
	$rs_tabla = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
	$nrs=mysqli_num_rows($rs_tabla);
?>
<form id="form1" name="form1">
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
			
			<div class="header" style="width:100%;position: fixed;">	BÚSQUEDA de PROVEEDORES </div>

<div class="fixed-table-container">
      <div class="header-background cabeceraTabla"> </div>      			
<div class="fixed-table-container-inner">
	
<?php if ($nrs>0) { ?>
		<table class="fuente8" width="100%" cellspacing=0 cellpadding=2 border=0>
		  <tr>
			<td width="10%"><div class="th-inner"><b>Código</b></div></td>
			<td width="60%"><div class="th-inner"><b>Proveedor</b></div></td>
			<td width="20%"><div class="th-inner"><b>CI/RUT</b></div></td>
			<td width="10%"><div class="th-inner"></td>
		  </tr>
		<?php
			for ($i = 0; $i < mysqli_num_rows($rs_tabla); $i++) {
				$codproveedor=mysqli_result($rs_tabla, $i, "codproveedor");
				$nombre=mysqli_result($rs_tabla, $i, "nombre");
				$nif=mysqli_result($rs_tabla, $i, "nif");
				 if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>
						<tr class="<?php echo $fondolinea?>">
					<td>
        <div align="center"><?php echo $codproveedor;?></div></td>
					<td>
        <div align="left"><?php echo $nombre;?></div></td>
					<td><div align="center"><?php echo $nif;?></div></td>
					<td align="center"><div align="center"><a href="javascript:pon_prefijo(<?php echo $codproveedor?>,'<?php echo $nombre?>','<?php echo $nif?>')"><img src="../img/convertir.png" border="0" title="Seleccionar"></a></div></td>					
				</tr>
			<?php }
		?>
  </table>
		<?php 
		}  ?>
<input type="hidden" id="accion" name="accion">
</div>
</div>
</div>
</div>
</div>
</form>

</body>
</html>
