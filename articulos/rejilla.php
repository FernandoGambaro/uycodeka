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
 
session_start();
require_once('../class/class_session.php');
/*
Instantiate a new session object. If session exists, it will be restored,
otherwise, a new session will be created--placing a sid cookie on the user's
computer.
*/
if (!$s = new session()) {
  /*
  There is a problem with the session! The class has a 'log' property that
  contains a log of events. This log is useful for testing and debugging.
  */
  echo "<h2>Ocurrió un error al iniciar session!</h2>";
  echo $s->log;
  exit();
}


if((!$s->data['isLoggedIn']) || !($s->data['isLoggedIn']))
{
	/*/user is not logged in*/
	echo "<script>location.href='../index.php'; </script>";
   //header("Location:../index.php");	

} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
  		//header("Location:../index.php");	
		echo "<script>window.top.location.href='../index.php'; </script>";
	   exit;
   }
   $s->data['loggedAt']= time();
   $s->save();
}

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];

$paginacion=$s->data['alto'];
if($paginacion<=0) {
	$paginacion=20;
}

include ("../conectar.php");
include("../common/verificopermisos.php");
////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 
////header('Content-Type: text/html; charset=UTF-8');



$codigobarras=$_POST["codigobarras"];
$descripcion=$_POST["descripcion"];
$codfamilia=$_POST["cboFamilias"];
$referencia=$_POST["referencia"];
$codproveedor=$_POST["cboProveedores"];
$codubicacion=$_POST["cboUbicacion"];
$stock=$_POST['stock'];

$i="";

$cadena_busqueda=$_POST["cadena_busqueda"];

$where="1=1";
if ($codigobarras <> "") { $where.=" AND codigobarras like'%".$codigobarras."%'"; }
if ($descripcion <> "") { $where.=" AND descripcion like '%".$descripcion."%'"; }
if ($codfamilia > "0") { $where.=" AND codfamilia='$codfamilia'"; }
if ($codproveedor > "0") { $where.=" AND (codproveedor1='$codproveedor' OR codproveedor2='$codproveedor')"; }
if ($codubicacion > "0") { $where.=" AND codubicacion='$codubicacion'"; }
if ($referencia <> "") { $where.=" AND `referencia` LIKE '%".$referencia."%' "; }

if ($stock <> "") { $where.=" AND stock >= '".$stock."'"; }

$where.=" ORDER BY codarticulo ASC";
$query_busqueda="SELECT count(*) as filas FROM articulos WHERE borrado=0 AND ".$where;
$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
$filas=mysqli_result($rs_busqueda, 0, "filas");

?>
<html>
	<head>
		<title>Articulos</title>
		<link href="../css3/estilos.css" type="text/css" rel="stylesheet">
		<script src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="../js3/jquery.colorbox.js"></script>

<script type="text/javascript">
var estilo="background-color: #2d2d2d; border-bottom: 1px solid #000; color: #fff;";

var idstyle='';
var indiaux='';

$(document).ready(function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

$('.trigger').click(function(e){
  	if (idstyle!="") {	
		var el = document.getElementById(idstyle);
		el.setAttribute('style', '');    	
  	}
      list=this.id;
      idstyle=this.id;
		var el = document.getElementById(list);
		el.setAttribute('style', estilo);   
		parent.document.getElementById("selid").value=list;
   });
   
   
});

</script>
			
		<script language="javascript">
		function estadisticas() {
			var item=parent.document.getElementById("selid").value;
			if (item!='') {
				var url="../reportes/.php?codarticulo=" + codarticulo ;
				var w='98%';
				var h='550px';
				window.parent.OpenNote(url,w,h);	
			}
		}
		function ver_articulo(codarticulo) {
			var url="ver_articulo.php?codarticulo=" + codarticulo + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			var w='98%';
			var h='550px';
			window.parent.OpenNote(url,w,h);			
		}
		
		function modificar_articulo(codarticulo) {
			var url="modificar_articulo.php?codarticulo=" + codarticulo + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			var w='98%';
			var h='550px';
			window.parent.OpenNote(url,w,h);
		}
		
		function eliminar_articulo(codarticulo) {
			var url="eliminar_articulo.php?codarticulo=" + codarticulo + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			var w='98%';
			var h='550px';
			window.parent.OpenNote(url,w,h);
		}

		function inicio(){		
			var list=parent.document.getElementById("selid").value;
			var numfilas=parseInt(document.getElementById("numfilas").value);
			var paginacion=parseInt(<?php echo $paginacion;?>);
			var indi=parseInt(parent.document.getElementById("iniciopagina").value);
			var contador=1;
			var indice=0;
			var indiaux=0;			
			
			if (indi>numfilas) { 
				indi=1; 
			}
			if (numfilas <= paginacion) {
					parent.document.getElementById("nextdisab").style.display = 'block';
					parent.document.getElementById("lastdisab").style.display = 'block';
					parent.document.getElementById("last").style.display = 'none';
					parent.document.getElementById("next").style.display = 'none';
			}
			parent.document.form_busqueda.filas.value=numfilas;
			parent.document.form_busqueda.paginas.innerHTML="";

			parent.document.getElementById("prevpagina").value = contador-paginacion;
			parent.document.getElementById("currentpage").value = indice+1;
			parent.document.getElementById("nextpagina").value = contador + paginacion;
			numfilas=Math.abs(numfilas);
			while (contador<=numfilas) {
				if (parseInt(contador+paginacion-1)>numfilas) {
					texto=contador + " al " + parseInt(numfilas);
				} else {
					texto=contador + " al " + parseInt(contador+paginacion-1);
				}
				if (indi==contador) {
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
					parent.document.getElementById("prevpagina").value = contador-paginacion;
					parent.document.getElementById("currentpage").value = indice + 1;
					parent.document.getElementById("nextpagina").value = contador + paginacion;

					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.form_busqueda.paginas.options[indice].selected=true;
					indiaux=	indice;				
					
				} else {

					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.getElementById("lastpagina").value = contador;
				}
				indice++;
				contador=Math.abs(contador+paginacion);
			}	

					if (indiaux == (indice-1) ) {
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
			idstyle=list;
			var el = document.getElementById(list);
			if (!document.getElementById(list)) {
			idstyle='';
			} else {
			el.setAttribute('style', estilo);   
		}
		}	
		
		</script>
	</head>

	<body onload="inicio();">	
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">

			<div class="header" style="width:100%;position: fixed;">	Listado de Articulos </div>
			<div class="fixed-table-container">
      		<div class="header-background cabeceraTabla"> </div>      			
			<div class="fixed-table-container-inner">
			
					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 style="font-size: 130%;">
					<thead>
					  <tr class="cabeceraTabla">	
							<th width="11%" ><div class="th-inner">CODIGO</div></th>
							<th width="15%" align="left"><div class="th-inner">REFERENCIA</div></th>
							<th width="32%" align="left"><div class="th-inner">DESCRIPCION</div> </th>
							<th width="11%" align="left"><div class="th-inner">FAMILIA</div></th>
							<th width="11%" align="left"><div class="th-inner">PRECIO</div></th>
							<th width="11%" align="left"><div class="th-inner">P + IVA</div></th>
							<th width="5%" align="left"><div class="th-inner">MON.</div></th>
							<th width="11%" align="left"><div class="th-inner">STO.</div></th>
							<th colspan="3"><div class="th-inner">&nbsp;ACCIÓN</div></th>
						</tr>
					</thead>
					<tbody>
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas;?>">
				<?php $iniciopagina=$_POST["iniciopagina"];
				if ($iniciopagina=='') { $iniciopagina=@$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if ($iniciopagina=='') { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) {

							$leer=verificopermisos('articulos', 'leer', $UserID);
							$escribir=verificopermisos('articulos', 'escribir', $UserID);
							$modificar=verificopermisos('articulos', 'modificar', $UserID);
							$eliminar=verificopermisos('articulos', 'eliminar', $UserID);
								
							/*Genero un array con las descripciones de las monedas*/
							$tipomon = array();
							$sel_monedas="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
						   $res_monedas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_monedas);
						   $con_monedas=0;
							$xmon=1;
						 while ($con_monedas < mysqli_num_rows($res_monedas)) {
						 	$descripcion=split(" ", mysqli_result($res_monedas, $con_monedas, "descripcion"));
						 	 $tipomon[$xmon]= $descripcion[0];
						 	 $con_monedas++;
						 	 $xmon++;
						 }								
								
							$sel_resultado="SELECT * FROM articulos WHERE borrado=0 AND ".$where;
						   $sel_resultado=$sel_resultado."  limit ".$iniciopagina.",".$paginacion;
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;
						   
						   while ($contador < mysqli_num_rows($res_resultado)) { 
						   		if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla";	}?>
						   		
						<tr id="<?php echo mysqli_result($res_resultado, $contador, "codarticulo");?>"	 class="<?php echo $fondolinea?> trigger">
							<td><div align="center"><?php echo mysqli_result($res_resultado, $contador, "codigobarras")?></div></td>
							<td><div align="left"><?php echo mysqli_result($res_resultado, $contador, "referencia")?></div></td>
							<td><div align="left"><?php echo mysqli_result($res_resultado, $contador, "descripcion")?></div></td>
							<td><div align="left">
							<?php $codfamilia=mysqli_result($res_resultado, $contador, "codfamilia");
							$query_familia="SELECT nombre FROM familias WHERE codfamilia='$codfamilia'";
							$rs_familia=mysqli_query($GLOBALS["___mysqli_ston"], $query_familia);
							$nombre_familia=mysqli_result($rs_familia, 0, "nombre");
							echo $nombre_familia;			
							?>
							</div></td>
							<td class="aCentro"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "precio_tienda")?></div></td>
							<td class="aCentro"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "precio_iva")?></div></td>
							
							<td class="aCentro"><div align="center">
							<?php
							
							
							 echo $tipomon[mysqli_result($res_resultado, $contador, "moneda")];
							 ?>
							
							</div></td>
							<td class="aCentro"><?php echo mysqli_result($res_resultado, $contador, "stock")?></td>
	<?php if ( $modificar=="true") { ?>				 	  		
							<td ><div align="center"><a href="#"><img id="botonBusqueda" src="../img/modificar.png" width="16" height="16" border="0" onClick="modificar_articulo(<?php echo mysqli_result($res_resultado, $contador, "codarticulo")?>)" title="Modificar"></a></div></td>
	<?php } else { ?>
							<td ><div align="center"></div></td>
	<?php } if ( $leer=="true") { ?>
							<td ><div align="center"><a href="#"><img id="botonBusqueda" src="../img/ver.png" width="16" height="16" border="0" onClick="ver_articulo(<?php echo mysqli_result($res_resultado, $contador, "codarticulo")?>)" title="Visualizar"></a></div></td>
	<?php } else { ?>
							<td ><div align="center"></div></td>
	<?php } if ( $eliminar=="true") { ?>			 	  		
 							<td ><div align="center"><a href="#"><img id="botonBusqueda" src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar_articulo(<?php echo mysqli_result($res_resultado, $contador, "codarticulo")?>)" title="Eliminar"></a></div></td>
	<?php } else { ?>
							<td ><div align="center"></div></td>
	<?php } ?> 

						</tr>
						<?php $contador++;
							}
						?>	

					<?php } else { ?>
						<tr>
							<td width="100%" class="mensaje" colspan="11">No hay ning&uacute;n art&iacute;culo que cumpla con los criterios de b&uacute;squeda</td>
					    </tr>
				
					<?php } ?>					
					</tbody>								
					</table>
					</div>
			</div></div></div>
		  </div>			
	</body>
</html>
