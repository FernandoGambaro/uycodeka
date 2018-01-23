<?php
include ("../conectar.php");
include ("../funciones/fechas.php");
date_default_timezone_set("America/Montevideo");
 
$paginacion=20; 
 
$cadena_busqueda=$_POST["cadena_busqueda"];

$moneda=$_POST["moneda"];

$fechainicio=$_POST["fechainicio"];
if ($fechainicio<>"") { $fechainicio=explota($fechainicio); }
$fechafin=$_POST["fechafin"];
if ($fechafin<>"") { $fechafin=explota($fechafin); }

$codigobarras=$_POST['codigobarras'];
$referencia=$_POST['referencia'];
$descripcion=$_POST['descripcion'];

$where=" 1=1";

if ($codigobarras <> "") { $where.=" AND `codigobarras` like'%".$codigobarras."%'"; }
if ($descripcion <> "") { $where.=" AND `descripcion` like '%".$descripcion."%'"; }
if ($referencia <> "") { $where.=" AND `referencia` LIKE '%".$referencia."%' "; }


if ($moneda <> 0 ) { $where.=" AND facturas.moneda = '".$moneda."'"; }

if (($fechainicio<>"") and ($fechafin<>"")) {
	$where.=" AND fecha between '".$fechainicio."' AND '".$fechafin."'";
} else {
	if ($fechainicio<>"") {
		$where.=" and fecha>='".$fechainicio."'";
	} else {
		if ($fechafin<>"") {
			$where.=" and fecha<='".$fechafin."'";
		}
	}
}



$where.=" GROUP BY articulos.codarticulo ORDER BY articulos.codarticulo ASC , fecha ASC ";

$sel_resultado="SELECT factulinea.codigo, factulinea.codfactura, factulinea.cantidad, factulinea.detalles, facturas.moneda, facturas.codfactura, facturas.fecha, 
articulos.codarticulo, articulos.descripcion, articulos.referencia, sum(factulinea.cantidad) as total FROM factulinea 
INNER JOIN facturas ON facturas.codfactura=factulinea.codfactura INNER JOIN articulos ON factulinea.codigo=articulos.codarticulo  WHERE ".$where;
						
   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
   $filas=mysqli_num_rows($res_resultado);

?>
<html>
	<head>
		<title></title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">
		parent.document.getElementById("msganio").innerHTML=" Listado de artículos vendidos ";

		function ver_proveedor(codproveedor) {
			var url="ver_proveedor.php?codproveedor=" + codproveedor + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			var w='880px';
			var h='400px';
			window.parent.OpenNote(url,w,h);
		}
		
		function modificar_proveedor(codproveedor) {
			var url="modificar_proveedor.php?codproveedor=" + codproveedor + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			var w='880px';
			var h='400px';
			window.parent.OpenNote(url,w,h);
		}
		
		function eliminar_proveedor(codproveedor) {
			var url="eliminar_proveedor.php?codproveedor=" + codproveedor + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			var w='880px';
			var h='400px';
			window.parent.OpenNote(url,w,h);			
		}

		function inicio() {
			var numfilas=document.getElementById("numfilas").value;
			var indi=parent.document.getElementById("iniciopagina").value;
			var contador=1;
			var indice=0;
			if (parseInt(indi)>parseInt(numfilas)) { 
				indi=1; 
			}
			if (parseInt(numfilas) <= <?php echo $paginacion?>) {
					parent.document.getElementById("nextdisab").style.display = 'block';
					parent.document.getElementById("lastdisab").style.display = 'block';
					parent.document.getElementById("last").style.display = 'none';
					parent.document.getElementById("next").style.display = 'none';
			}
			parent.document.form_busqueda.filas.value=numfilas;
			parent.document.form_busqueda.paginas.innerHTML="";

			parent.document.getElementById("prevpagina").value = contador-<?php echo $paginacion?>;
			parent.document.getElementById("currentpage").value = indice+1;
			parent.document.getElementById("nextpagina").value = contador + <?php echo $paginacion?>;
			numfilas=Math.abs(numfilas);
			while (contador<=numfilas) {
				if (parseInt(contador+<?php echo $paginacion?>-1)>numfilas) {
					
				}
				texto=contador + " al " + parseInt(contador+<?php echo $paginacion?>-1);
				if (parseInt(indi)==parseInt(contador)) {
					if (indi==1) {
					parent.document.getElementById("first").style.display = 'none';
					parent.document.getElementById("prev").style.display = 'none';
					parent.document.getElementById("firstdisab").style.display = 'block';
					parent.document.getElementById("prevdisab").style.display = 'block';
					} else {
					parent.document.getElementById("first").style.display = 'block';
					parent.document.getElementById("prev").style.display = 'block';
					parent.document.getElementById("firstdisab").style.display = 'none';
					parent.document.getElementById("prevdisab").style.display = 'none';
					}
					parent.document.getElementById("prevpagina").value = contador-<?php echo $paginacion?>;
					parent.document.getElementById("currentpage").value = indice + 1;
					parent.document.getElementById("nextpagina").value = contador + <?php echo $paginacion?>;

					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.form_busqueda.paginas.options[indice].selected=true;
					indiaux=	indice;				
					
				} else {

					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.getElementById("lastpagina").value = contador;
				}
				indice++;
				contador=Math.abs(contador+<?php echo $paginacion?>);
			}	

					if (parseInt(indiaux) == parseInt(indice)-1 ) {
					parent.document.getElementById("nextdisab").style.display = 'block';
					parent.document.getElementById("lastdisab").style.display = 'block';
					parent.document.getElementById("last").style.display = 'none';
					parent.document.getElementById("next").style.display = 'none';
					} else {
					parent.document.getElementById("nextdisab").style.display = 'none';
					parent.document.getElementById("lastdisab").style.display = 'none';
					parent.document.getElementById("last").style.display = 'block';
					parent.document.getElementById("next").style.display = 'block';
					}

		}
		</script>
	</head>

	<body onload="inicio();">	
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
			<div class="header" style="width:100%;position: fixed;">Listado de Artículos Vendidos</div>
<div class="fixed-table-container">
      <div class="header-background cabeceraTabla"> </div>      			
<div class="fixed-table-container-inner">

					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
					<thead>
					  <tr>
							<th width="6%"><div class="th-inner">CODIGO</div></th>
							<th width="48%"><div class="th-inner">DESCRIPCIÓN</div> </th>
							<th width="25%"><div class="th-inner">CANTIDAD</div></th>
						</tr>
			
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas?>">
				<?php $iniciopagina=$_POST["iniciopagina"];
				if ($iniciopagina=='') { $iniciopagina=@$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if ($iniciopagina=='') { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) { ?>
						<?php $sel_resultado="SELECT factulinea.codigo, factulinea.codfactura, factulinea.cantidad, factulinea.detalles, facturas.moneda, facturas.codfactura, facturas.fecha,
						 articulos.codarticulo,articulos.codigobarras, articulos.descripcion, articulos.referencia, sum(factulinea.cantidad) as total FROM factulinea 
						 INNER JOIN facturas ON facturas.codfactura=factulinea.codfactura INNER JOIN articulos ON factulinea.codigo=articulos.codarticulo  WHERE ".$where;
						
						   $sel_resultado=$sel_resultado."  limit ".$iniciopagina.",".$paginacion;
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;
						   while ($contador < mysqli_num_rows($res_resultado)) {
								 if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }		 
						?>								 
						<tr class="<?php echo $fondolinea?>">
						<?php if ($codigobarras!='') { ?> 
							<td width="6%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "codigobarras");?></div></td>
						<?php } else { ?>
							<td width="6%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "referencia");?></div></td>
						<?php } ?>
							<td width="48%"><div align="left"><?php echo mysqli_result($res_resultado, $contador, "descripcion");
							 if(mysqli_result($res_resultado, $contador, "detalles")!='') { echo " - ". mysqli_result($res_resultado, $contador, "detalles");}?></div></td>
							<td class="aDerecha" width="25%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "total");?></div></td>
						</tr>
						<?php $contador++;
							}
						?>			
					</table>
					
					<?php } else { ?>
					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" class="mensaje"><?php echo "No hay ning&uacute;n proveedor que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
					</table>				
					<?php } ?>					
				</div>
			</div>
		  </div>			
		</div>
	</body>
</html>
