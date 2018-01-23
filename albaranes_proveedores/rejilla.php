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
 
include ("../conectar.php");
include ("../funciones/fechas.php");

$codproveedor=$_POST["codproveedor"];
$nombre=$_POST["nombre"];
$numalbaran=$_POST["numalbaran"];
$estado=$_POST["cboEstados"];
$fechainicio=$_POST["fechainicio"];
if ($fechainicio<>"") { $fechainicio=explota($fechainicio); }
$fechafin=$_POST["fechafin"];
if ($fechafin<>"") { $fechafin=explota($fechafin); }

$cadena_busqueda=$_POST["cadena_busqueda"];

$where="1=1";
if ($codproveedor <> "") { $where.=" AND albaranesp.codproveedor='$codproveedor'"; }
if ($nombre <> "") { $where.=" AND proveedores.nombre like '%".$nombre."%'"; }
if ($numalbaran <> "") { $where.=" AND codalbaran='$numalbaran'"; }
if ($estado > "0") { $where.=" AND estado='$estado'"; }
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

$where.=" ORDER BY codalbaran DESC";
$query_busqueda="SELECT count(*) as filas FROM albaranesp,proveedores WHERE albaranesp.codproveedor=proveedores.codproveedor AND ".$where;
$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
$filas=mysqli_result($rs_busqueda, 0, "filas");

?>
<html>
	<head>
		<title>Clientes</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">

		function ver_albaran(codalbaran,codproveedor) {
			var url="ver_albaran.php?codalbaran=" + codalbaran + "&codproveedor=" + codproveedor + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			window.parent.OpenNote(url);
		}
				
		function modificar_albaran(codalbaran,codproveedor,marcaestado) {
			if (marcaestado==1) {
				var url="modificar_albaran.php?codalbaran=" + codalbaran + "&codproveedor=" + codproveedor + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
				window.parent.OpenNote(url);
			} else {
				alert ("No puede modificar un albaran emitido.");
			}
		}
		
		function eliminar_albaran(codalbaran,codproveedor) {

				if (confirm("Atencion va a proceder a la eliminacion de un albaran. Desea continuar?")) {
					var url="eliminar_albaran.php?codalbaran=" + codalbaran + "&codproveedor=" + codproveedor + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
					window.parent.OpenNote(url);
				}
		}
		

		
		function convertir_albaran(codalbaran,codproveedor,marcaestado) {
			if (marcaestado==1) {
				url="convertir_albaran.php?codalbaran=" + codalbaran + "&codproveedor=" + codproveedor + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
				window.parent.OpenNote(url);
			} else {
				alert ("No se puede convertir un albaran ya facturado.");
			}
		}
		

		function inicio() {
			var numfilas=document.getElementById("numfilas").value;
			var indi=parent.document.getElementById("iniciopagina").value;
			var contador=1;
			var indice=0;
			if (parseInt(indi)>parseInt(numfilas)) { 
				indi=1; 
			}
			if (parseInt(numfilas) <= 10) {
					parent.document.getElementById("nextdisab").style.display = 'block';
					parent.document.getElementById("lastdisab").style.display = 'block';
					parent.document.getElementById("last").style.display = 'none';
					parent.document.getElementById("next").style.display = 'none';
			}
			parent.document.form_busqueda.filas.value=numfilas;
			parent.document.form_busqueda.paginas.innerHTML="";

			parent.document.getElementById("prevpagina").value = contador-10;
			parent.document.getElementById("currentpage").value = indice+1;
			parent.document.getElementById("nextpagina").value = contador + 10;
			numfilas=Math.abs(numfilas);
			while (contador<=numfilas) {
				if (parseInt(contador+9)>numfilas) {
					
				}
				texto=contador + " al " + parseInt(contador+9);
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
					parent.document.getElementById("prevpagina").value = contador-10;
					parent.document.getElementById("currentpage").value = indice + 1;
					parent.document.getElementById("nextpagina").value = contador + 10;

					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.form_busqueda.paginas.options[indice].selected=true;
					indiaux=	indice;				
					
				} else {

					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.getElementById("lastpagina").value = contador;
				}
				indice++;
				contador=contador+10;
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
				<div class="header">Listado de ordenes de compra </div>			
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="8%">ITEM</td>
							<td width="8%">N. ALBARAN</td>
							<td width="30%">PROVEEDORES</td>
							<td width="10%">MONEDA</td>
							<td width="10%">IMPORTE</td>
							<td width="20%">FECHA</td>
							<td width="10%">ESTADO</td>
							<td width="40px" colspan="4">ACCION</td>
						</tr>			
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas?>">
				<?php $iniciopagina=$_POST["iniciopagina"];
				$tipomon = array( 0=>"&nbsp;", 1=>"Pesos", 2=>"U\$S");
			
				if ($iniciopagina=='') { $iniciopagina=@$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if ($iniciopagina=='') { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) { ?>
						<?php $sel_resultado="SELECT codalbaran,proveedores.nombre as nombre,albaranesp.fecha as fecha,proveedores.codproveedor,moneda,totalalbaran,estado FROM albaranesp,proveedores WHERE albaranesp.codproveedor=proveedores.codproveedor AND ".$where;
						   $sel_resultado=$sel_resultado."  limit ".$iniciopagina.",10";
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;
						   $marcaestado=0;						   
						   while ($contador < mysqli_num_rows($res_resultado)) { 
						   $marcaestado=mysqli_result($res_resultado, $contador, "estado");
				   		$moneda=$tipomon[mysqli_result($res_resultado, $contador, "moneda")];
						   if (mysqli_result($res_resultado, $contador, "estado")==1) { $estado="Sin facturar"; } else { $estado="Facturado"; }
								if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>
						<tr class="<?php echo $fondolinea?>">
							<td class="aCentro" width="8%"><?php echo $contador+1;?></td>
							<td width="8%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "codalbaran")?></div></td>
							<td width="30%"><div align="left"><?php echo mysqli_result($res_resultado, $contador, "nombre")?></div></td>							
							<td width="12%"><div align="center"><?php echo $moneda;?></div></td>
							<td width="12%"><div align="center"><?php echo number_format(mysqli_result($res_resultado, $contador, "totalalbaran"),2,",",".")?></div></td>
							<td class="aDerecha" width="20%"><div align="center"><?php echo implota(mysqli_result($res_resultado, $contador, "fecha"))?></div></td>
							<td width="10%"><div align="center"><?php echo $estado?></div></td>
							<td><div align="center"><a href="#"><img src="../img/modificar.png" width="16" height="16" border="0" onClick="modificar_albaran('<?php echo mysqli_result($res_resultado, $contador, "codalbaran")?>',<?php echo mysqli_result($res_resultado, $contador, "codproveedor")?>,<?php echo $marcaestado?>)" title="Modificar"></a></div></td>
							<td><div align="center"><a href="#"><img src="../img/ver.png" width="16" height="16" border="0" onClick="ver_albaran('<?php echo mysqli_result($res_resultado, $contador, "codalbaran")?>',<?php echo mysqli_result($res_resultado, $contador, "codproveedor")?>)" title="Visualizar"></a></div></td>
							<td><div align="center"><a href="#"><img src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar_albaran('<?php echo mysqli_result($res_resultado, $contador, "codalbaran")?>',<?php echo mysqli_result($res_resultado, $contador, "codproveedor")?>)" title="Eliminar"></a></div></td>
							<td ><div align="center"><a href="#"><img src="../img/convertir.png" width="16" height="16" border="0" onClick="convertir_albaran('<?php echo mysqli_result($res_resultado, $contador, "codalbaran")?>',<?php echo mysqli_result($res_resultado, $contador, "codproveedor")?>,<?php echo $marcaestado?>)" title="Facturar"></a></div></td>
						</tr>
						<?php $contador++;
							}
						?>			
					</table>
					<?php } else { ?>
					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" class="mensaje"><?php echo "No hay ning&uacute;na orden de compra que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
					</table>					
					<?php } ?>	
				</div>				
				</div>
		  </div>			
		</div>
	</body>
</html>
