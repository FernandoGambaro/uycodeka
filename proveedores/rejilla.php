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
//header('Content-Type: text/html; charset=UTF-8'); 

//$paginacion=30;

$codproveedor=$_POST["codproveedor"];
$nif=$_POST["nif"];
$codprovincia=$_POST["cboProvincias"];
$localidad=$_POST["localidad"];
$telefono=$_POST["telefono"];
$cadena_busqueda=$_POST["cadena_busqueda"];

$where="1=1";
if ($codproveedor <> "") { $where.=" AND codproveedor = '".$codproveedor."'"; }
if ($nif <> "") { $where.=" AND nif like '%".$nif."%'"; }
if ($codprovincia > "0") { $where.=" AND codprovincia='$codprovincia'"; }
if ($localidad <> "") { $where.=" AND localidad like '%".$localidad."%'"; }
if ($telefono <> "") { $where.=" AND telefono like '%".$telefono."%'"; }

$where.=" ORDER BY nombre ASC";
$query_busqueda="SELECT count(*) as filas FROM proveedores WHERE borrado=0 AND ".$where;
$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
$filas=mysqli_result($rs_busqueda, 0, "filas");

?>
<html>
	<head>
		<title>Proveedores</title>
		<link href="../css3/estilos.css" type="text/css" rel="stylesheet">

		<script src="../js3/jquery.min.js"></script>
		
<script type="text/javascript">
var estilo="background-color: #2d2d2d; border-bottom: 1px solid #000; color: #fff;";

var oldstyle='';
var idstyle='';
var indiaux='';

$(document).ready(function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

$('.trigger').click(function(e){
  	if (idstyle!="") {	
		var el = document.getElementById(idstyle);
		el.setAttribute('style', oldstyle);    	
  	}
      list=this.id;
		oldstyle = $(this).prop('style');
      idstyle=this.id;
		var el = document.getElementById(list);
		el.setAttribute('style', estilo); 
		parent.document.getElementById("selid").value=list;
		parent.document.getElementById("stylesel").value=oldstyle;		     
   }); 
});

</script>
		
		<script language="javascript">
		
		function ver_proveedor(codproveedor) {
			var url="ver_proveedor.php?codproveedor=" + codproveedor + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			var w='840px';
			var h='340px';
			window.parent.OpenNote(url,w,h);
		}
		
		function modificar_proveedor(codproveedor) {
			var url="modificar_proveedor.php?codproveedor=" + codproveedor + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			var w='840px';
			var h='340px';
			window.parent.OpenNote(url,w,h);
		}
		
		function eliminar_proveedor(codproveedor) {
			var url="eliminar_proveedor.php?codproveedor=" + codproveedor + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			var w='840px';
			var h='340px';
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
			if (parseInt(numfilas) <= <?php echo $paginacion;?>) {
					parent.document.getElementById("nextdisab").style.display = 'block';
					parent.document.getElementById("lastdisab").style.display = 'block';
					parent.document.getElementById("last").style.display = 'none';
					parent.document.getElementById("next").style.display = 'none';
			}			
			parent.document.form_busqueda.filas.value=numfilas;
			parent.document.form_busqueda.paginas.innerHTML="";

			parent.document.getElementById("prevpagina").value = contador-<?php echo $paginacion;?>;
			parent.document.getElementById("currentpage").value = indice+1;
			parent.document.getElementById("nextpagina").value = contador + <?php echo $paginacion;?>;
			numfilas=Math.abs(numfilas);
			while (contador<=numfilas) {

				texto=contador + " al " + parseInt(contador+<?php echo $paginacion;?>-1);
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
					parent.document.getElementById("prevpagina").value = contador-<?php echo $paginacion;?>;
					parent.document.getElementById("currentpage").value = indice + 1;
					parent.document.getElementById("nextpagina").value = contador + <?php echo $paginacion;?>;

					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.form_busqueda.paginas.options[indice].selected=true;
					indiaux=	indice;				
					
				} else {

					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.getElementById("lastpagina").value = contador;
				}
				indice++;
				contador=contador+<?php echo $paginacion;?>;
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
					
			list=parent.document.getElementById("selid").value;
			idstyle=list;
			var el = document.getElementById(list);
			if (!el) {
			idstyle='';
			oldstyle='';
			} else {
			el.setAttribute('style', estilo);   
			oldstyle=parent.document.getElementById("stylesel").value;
			}
		}
		function vermapa_proveedor(codproveedor) {
			var url="vermapa.php?codproveedor=" + codproveedor + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			var w='700px';
			var h='500px';
			window.parent.OpenNote(url,w,h);
		}		
		</script>
	</head>

	<body onload=inicio()>	
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
			<div class="header" style="width:100%;position: fixed; font-size: 140%;">	Listado de Proveedores  </div>
			<div class="fixed-table-container">
			      <div class="header-background cabeceraTabla"> </div>      			
			<div class="fixed-table-container-inner">
						
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 style="font-size: 110%;">
					<thead>
					  <tr>
							<th width="48%"><div class="th-inner">NOMBRE</div> </th>
							<th width="13%"><div class="th-inner">RUT</div></th>
							<th width="13%"><div class="th-inner">EMAIL</div></th>
							<th width="25%"><div class="th-inner">TELEFONO/S</div></th>
							<th width="60px" colspan="4"><div class="th-inner">&nbsp;ACCIÓN</div></th>
						</tr>
			
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas?>">
				<?php $iniciopagina=$_POST["iniciopagina"];
				if ($iniciopagina=='') { $iniciopagina=@$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if ($iniciopagina=='') { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) { ?>
						<?php
						$MainSector='proveedores';
							$leer=verificopermisos($MainSector, 'leer', $UserID);
							$escribir=verificopermisos($MainSector, 'escribir', $UserID);
							$modificar=verificopermisos($MainSector, 'modificar', $UserID);
							$eliminar=verificopermisos($MainSector, 'eliminar', $UserID);
													 
							$sel_resultado="SELECT * FROM proveedores WHERE borrado=0 AND ".$where;
						   $sel_resultado=$sel_resultado."  limit ".$iniciopagina.",".$paginacion;
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;
						   while ($contador < mysqli_num_rows($res_resultado)) {
								 if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>

						<tr id="<?php echo mysqli_result($res_resultado, $contador, "codproveedor");?>"	 class="<?php echo $fondolinea?> trigger">
							<td width="48%"><div align="left"><?php echo mysqli_result($res_resultado, $contador, "nombre");?></div></td>
							<td class="aDerecha" width="13%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "nif");?></div></td>
							<td class="aDerecha" width="13%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "email");?></div></td>
							<td class="aDerecha" width="25%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "telefono");?> 
							<?php if (mysqli_result($res_resultado, $contador, "movil")!='') { echo "/ ".mysqli_result($res_resultado, $contador, "movil"); }?></div></td>
							<td><div align="center"><a href="#"><?php if(mysqli_result($res_resultado, $contador, "direccion")!="") {?>
							<img id="botonBusqueda" src="../img/Google-Maps-icon.png" width="16" height="16" border="0" onClick="vermapa_proveedor(<?php echo mysqli_result($res_resultado, $contador, "codproveedor")?>)" title="Ver mapa"></a></div></td>
							<?php } else { ?>
							&nbsp;</a></div></td>
							<?php } ?>	

	<?php if ( $modificar=="true") { ?>		
							<td width="20px"><div align="center"><a href="#"><img id="botonBusqueda" src="../img/modificar.png" width="16" height="16" border="0" onClick="modificar_proveedor(<?php echo mysqli_result($res_resultado, $contador, "codproveedor");?>)" title="Modificar"></a></div></td>
	<?php } else { ?>
							<td width="20px">&nbsp;</td>
	<?php } if ( $leer=="true") { ?>							
							<td width="20px"><div align="center"><a href="#"><img id="botonBusqueda" src="../img/ver.png" width="16" height="16" border="0" onClick="ver_proveedor(<?php echo mysqli_result($res_resultado, $contador, "codproveedor");?>)" title="Visualizar"></a></div></td>
	<?php } else { ?>
							<td width="20px">&nbsp;</td>
	<?php } if ( $eliminar=="true") { ?>							
							<td width="20px"><div align="center"><a href="#"><img id="botonBusqueda" src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar_proveedor(<?php echo mysqli_result($res_resultado, $contador, "codproveedor")?>)" title="Eliminar"></a></div></td>
	<?php } else { ?>
							<td width="20px">&nbsp;</td>
	<?php } ?>							
						</tr>
						<?php $contador++;
							}
						?>			
					</table>
					<?php } else { ?>
					<table class="fuente8" width="87%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" class="mensaje"><?php echo "No hay ning&uacute;n proveedor que cumpla con los criterios de b&uacute;squeda ".$query_busqueda;?></td>
					    </tr>
					</table>					
					<?php } ?>					
				</div>
			</div>
		  </div>			
		</div>
	</body>
</html>
