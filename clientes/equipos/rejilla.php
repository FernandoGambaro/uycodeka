<?php
session_start();
require_once('../../class/class_session.php');
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
         /*echo "<script>parent.changeURL('../../index.php' ); </script>";*/
	 header("Location:../index.php");
} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
              echo "<script>window.parent.changeURL('../index.php' ); </script>";
	       /*/header("Location:../index.php");	*/
	       exit;
   }
   $s->data['loggedAt']= time();/*/ update last accessed time*/
   $s->save();
}

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];

include ("../../conectar.php");
include("../../common/verificopermisos.php");
//header('Content-Type: text/html; charset=UTF-8');
include ("../../funciones/fechas.php");

$codcliente=$_POST["codcliente"];
/*
$codequipo=$_POST["codequipo"];
$fecha=$_POST["fecha"];
$service=$_POST["service"];
$descripcion=$_POST["descripcion"];
$cadena_busqueda=$_POST["cadena_busqueda"];
*/
$where="1=1";
if ($codcliente <> "") { $where.=" AND codcliente='$codcliente'"; }
/*
if ($codequipo <> "") { $where.=" AND codequipo='$codequipo'"; }
if ($fecha <> "") { $where.=" AND fecha like '%".$fecha."%'"; }
if ($service > "0") { $where.=" AND service='$service'"; }
if ($descripcion <> "") { $where.=" AND descripcion like '%".$descripcion."%'"; }
*/
$where.=" ORDER BY fecha DESC";
$query_busqueda="SELECT count(*) as filas FROM equipos WHERE borrado=0 AND ".$where;
$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
$filas=mysqli_result($rs_busqueda, 0, "filas");

?>
<html>
	<head>
		<title>equipos</title>
		<link href="../../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">
		
		function ver_equipo(codequipo) {
			parent.location.href="ver_equipo.php?codequipo=" + codequipo + "&codcliente=<?php echo $codcliente;?>";
		}
		
		function modificar_equipo(codequipo) {
			parent.location.href="modificar_equipo.php?codequipo=" + codequipo + "&codcliente=<?php echo $codcliente;?>";
		}
		
		function eliminar_equipo(codequipo) {
			parent.location.href="eliminar_equipo.php?codequipo=" + codequipo + "&codcliente=<?php echo $codcliente;?>";
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

	<body onload=inicio()>	
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
			<div class="header" style="width:100%;position: fixed;">	Listado de equipos  </div>

<div class="fixed-table-container">
      <div class="header-background cabeceraTabla"> </div>      			
<div class="fixed-table-container-inner">
			
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=2 border=0 >
				<thead>
						<tr>
							<th width="8%"><div class="th-inner">Nº</div></th>
							<th width="8%" align="left"><div class="th-inner">FECHA</div></th>
							<th width="16%" align="left"><div class="th-inner">ALIAS</div></th>
							<th width="56%" align="left"><div class="th-inner">EQUIPO</div></th>
							<th width="10%" align="left"><div class="th-inner">&nbsp;Nº</div></th>
							<th width="19%" align="left"><div class="th-inner">&nbsp;SERVICE</div></th>
							<th width="60px" colspan="3"><div class="th-inner">&nbsp;Acción</div></th>
						</tr>
				</thead>
				<tbody>
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas?>">
				<?php $iniciopagina=$_POST["iniciopagina"];
				if ($iniciopagina=='') { $iniciopagina=@$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if ($iniciopagina=='') { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) { ?>
						<?php $sel_resultado="SELECT * FROM equipos WHERE borrado=0 AND ".$where;
						   $sel_resultado=$sel_resultado."  limit ".$iniciopagina.",10";
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;
						   while ($contador < mysqli_num_rows($res_resultado)) { 
								 if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>
						<tr class="<?php echo $fondolinea?>">
							<td class="aCentro" width="8%"><?php echo $contador+1;?></td>
							<td width="8%"><div align="left"><?php echo implota(mysqli_result($res_resultado, $contador, "fecha"));?></div></td>
							<td width="16%"><div align="left"><?php echo mysqli_result($res_resultado, $contador, "alias");?></div></td>
							<td width="56%"><div align="left"><?php echo mysqli_result($res_resultado, $contador, "descripcion");?></div></td>
							<td align="left" width="10%"><div ><?php echo mysqli_result($res_resultado, $contador, "numero");?></div></td>
							<td align="left" width="19%"><div>
							<?php
							 $tipo = array("Sin&nbsp;definir", "Sin&nbsp;Servicio","Con&nbsp;Mantenimiento", "Mantenimiento&nbsp;y&nbsp;Respaldos");
echo $tipo[mysqli_result($res_resultado, $contador, "service")];?>
							</div></td>
							<td width="20px"><div align="center"><a href="#"><img id="botonBusqueda" src="../../img/modificar.png" width="16" height="16" border="0" onClick="modificar_equipo(<?php echo mysqli_result($res_resultado, $contador, "codequipo")?>)" title="Modificar"></a></div></td>
							<td width="20px"><div align="center"><a href="#"><img id="botonBusqueda" src="../../img/ver.png" width="16" height="16" border="0" onClick="ver_equipo(<?php echo mysqli_result($res_resultado, $contador, "codequipo")?>)" title="Visualizar"></a></div></td>
							<td width="20px"><div align="center"><a href="#"><img id="botonBusqueda" src="../../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar_equipo(<?php echo mysqli_result($res_resultado, $contador, "codequipo")?>)" title="Eliminar"></a></div></td>
						</tr>
						<?php $contador++;
							}
						?>	
				</tbody>		
			</table>
					<?php } else { ?>
					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" class="mensaje"><?php echo "No hay ning&uacute;n equipo que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
					</table>					
					<?php } ?>					
				</div>
			</div>
			</div></div>
		  </div>			
		</div>
	</body>
</html>
