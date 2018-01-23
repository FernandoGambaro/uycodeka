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

include ("../conectar.php");
include("../common/verificopermisos.php");
////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 
////header('Content-Type: text/html; charset=UTF-8'); 

$cadena_busqueda=@$_GET["cadena_busqueda"];


if (!isset($cadena_busqueda)) { $cadena_busqueda=""; } else { $cadena_busqueda=str_replace("",",",$cadena_busqueda); }

if ($cadena_busqueda<>"") {
	$array_cadena_busqueda=split("~",$cadena_busqueda);
	$codigobarras=$array_cadena_busqueda[1];
	$referencia=$array_cadena_busqueda[2];
	$familia=$array_cadena_busqueda[3];
	$descripcion=$array_cadena_busqueda[4];
	$codproveedor=$array_cadena_busqueda[5];
	$codubicacion=$array_cadena_busqueda[6];
	$stock=$array_cadena_busqueda[7];
	//$familia==$array_cadena_busqueda[8];
} else {
	$codigobarras="";
	$referencia="";
	$familia="";
	$descripcion="";
	$codproveedor="";
	$codubicacion="";
	$stock="";
	//$familia='';
}

?>
<html>
	<head>
		<title>Articulos</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script src="../js3/jquery.min.js"></script>

		<link rel="stylesheet" href="../js3/colorbox.css" />
<!--		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
		<script src="../js3/jquery.colorbox.js"></script>
				

		<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
		<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js3/message.js" type="text/javascript"></script>
		<script src="../js3/jquery.msgBox.js" type="text/javascript"></script>
		<link href="../js3/msgBoxLight.css" rel="stylesheet" type="text/css">	
		
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">

<script type="text/javascript">
parent.document.getElementById("msganio").innerHTML="<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp;  Artículos ";

$(document).ready( function()
{
var body = document.body,
    html = document.documentElement;
var height = Math.max( body.scrollHeight, body.offsetHeight, 
                       html.clientHeight, html.scrollHeight, html.offsetHeight );	
var width = Math.max( body.scrollWidth, body.offsetWidth, 
                       html.clientWidth, html.scrollWidth, html.offsetWidth );
    width=width-10;
    height=height-158;
    
	var alto=height;
	var totales=alto/22;
	totales=totales-1;
	document.cookie ='variable='+totales+'; expires=Thu, 2 Aug 2021 20:47:11 UTC; path=/';

   document.getElementById("frame_rejilla").height = (height) + "px";
   document.getElementById("frame_rejilla").width = (width) + "px";
	
	$("form:not(.filter) :input:visible:enabled:first").focus();

});
</script>
<?php
$miVariable =  $_COOKIE["variable"];
	$s->data['alto']= floor($miVariable);
   $s->save();
   
	//$_SESSION['alto'] = $miVariable;
?>	

<script type="text/javascript">
$(function() {
	$("#estadistica").click(function() {
		//event.preventDefault();
		var codarticulo=$("#selid").val();
		if (codarticulo!='') {
				var url = "../reportes/GraficoArticulosBase.php?codarticulo="+codarticulo;  
				var w="1024";
				var h="99%";
					$.colorbox({
					   	href: url, open:true,	iframe:true, width:w, height:h, scrolling: true,
					});
			}else{					
		showWarningToast('Seleccione un artículo');
  		}
	});
});
</script>
		<script language="javascript">
function OpenNote(noteId,w,h){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:w, height:h,
			scrolling: false,
			onCleanup:function(){ document.getElementById("form_busqueda").submit(); }
	});
}
function OpenList(noteId){
	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"99%", height:"99%",
			onCleanup:function(){ document.getElementById("form_busqueda").submit(); }
	});
}

function pon_prefijo(referencia,odbarras,descripcion) {
	$("#referencia").val(referencia);
	$("#codigobarras").val(odbarras);
	$("#descripcion").val(descripcion);
	$('idOfDomElement').colorbox.close();
	document.getElementById("form_busqueda").submit();
}

</script>
		<script language="javascript">
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}
		
		function inicio() {
			document.getElementById("firstdisab").style.display = 'block';
			document.getElementById("prevdisab").style.display = 'block';
			document.getElementById("last").style.display = 'block';
			document.getElementById("next").style.display = 'block';
			document.getElementById("form_busqueda").submit();			
		}
	
		function nuevo_articulo() {
			$.colorbox({href:"nuevo_articulo.php",
			iframe:true, 
			width:"98%", height:"550px", open:true,
			scrolling: false,			
			onCleanup:function(){ window.location.reload();	}
		});	
		}
		
		function imprimir() {
			var url ='';
			var codigobarras=document.getElementById("codigobarras").value;
			var referencia=document.getElementById("referencia").value;
			var descripcion=document.getElementById("descripcion").value;
			var proveedores=document.getElementById("cboProveedores").value;			
			var familia=document.getElementById("cboFamilias").value;
			var ubicacion=document.getElementById("cboUbicacion").value;
			var stock=document.getElementById("stock").value;
			var opcionesimpresion=document.getElementById("opcionesimpresion").value;
			
			if ($('#opcionesaexcel').is(":checked")){
				
				if (opcionesimpresion==1) {
					url = "../excel/ListaDeArticulosExcel.php?codigobarras="+codigobarras+"&referencia="+referencia+"&descripcion="+descripcion+"&cboProveedores="+proveedores+"&cboFamilias="+familia+"&cboUbicacion="+ubicacion+"&stock="+stock;
				} 
				if (opcionesimpresion==2) {
					url = "../excel/ListaDePreciosGralExcel.php?codigobarras="+codigobarras+"&referencia="+referencia+"&descripcion="+descripcion+"&cboProveedores="+proveedores+"&cboFamilias="+familia+"&cboUbicacion="+ubicacion+"&stock="+stock;
				}
				if (opcionesimpresion==3) {
					url = "../excel/ListaDePreciosExcel.php?codigobarras="+codigobarras+"&referencia="+referencia+"&descripcion="+descripcion+"&cboProveedores="+proveedores+"&cboFamilias="+familia+"&cboUbicacion="+ubicacion+"&stock="+stock+"&cat=1";
				}
				if (opcionesimpresion==4) {
					url = "../excel/ListaDePreciosExcel.php?codigobarras="+codigobarras+"&referencia="+referencia+"&descripcion="+descripcion+"&cboProveedores="+proveedores+"&cboFamilias="+familia+"&cboUbicacion="+ubicacion+"&stock="+stock+"&cat=1"+"&logo=1";
				}
				if (opcionesimpresion==5) {
					url = "../excel/ListaDeArticulosExcel.php?codigobarras="+codigobarras+"&referencia="+referencia+"&descripcion="+descripcion+"&cboProveedores="+proveedores+"&cboFamilias="+familia+"&cboUbicacion="+ubicacion+"&stock="+stock+"&comision=1";
				} 
				if (opcionesimpresion==6) {
					url = "../excel/ListaDeArticulosExcel.php?codigobarras="+codigobarras+"&referencia="+referencia+"&descripcion="+descripcion+"&cboProveedores="+proveedores+"&cboFamilias="+familia+"&cboUbicacion="+ubicacion+"&stock="+stock+"&baja=1";
				} 
					$.msgBox({ type: "prompt",
					 title: "Ingrese el nombre del archivo sin extención",
					 inputs: [
					 { header: "Nombre de Archivo", type: "text", name: "nombre" }],
					 buttons: [
					 { value: "Aceptar" }, { value:"Cancelar" }],
					 success: function (result, values) {
											$(values).each(function (index, input) {
											v =  input.value ;
					  						});	
							if (v!="") {
								$.get("../excel/preparo.php?file="+v,function (data,status) { });
						      window.parent.progressExcelBar(1,v);
									$.get(url+"&file="+v, function(data, status) {
									if(status == 'success'){	
									$('#downloadFrame').remove(); // This shouldn't fail if frame doesn't exist
			   					$('body').append('<iframe id="downloadFrame" style="display:none"></iframe>');
			   					$('#downloadFrame').attr('src','../tmp/'+v+'.xlsx');	
										return false;
							  		}else{					
									showWarningToast('Se produjo un error, intentelo mas tarde');
							  		}
								});    											
							} else {
								if (result!="Cancelar") {
									showWarningToast('Nombre de archivo incorrecto.');
								}
							}					  														    	
						}
					});	
			} else {
				if (opcionesimpresion==1) {
					window.open("../fpdf/articulos.php?codigobarras="+codigobarras+"&referencia="+referencia+"&descripcion="+descripcion+"&cboProveedores="+proveedores+"&cboFamilias="+familia+"&cboUbicacion="+ubicacion+"&stock="+stock);
				}
				if (opcionesimpresion==7) {
					window.open("../fpdf/imprimir_bajo_minimos.php?codigobarras="+codigobarras+"&referencia="+referencia+"&descripcion="+descripcion+"&cboProveedores="+proveedores+"&cboFamilias="+familia+"&cboUbicacion="+ubicacion+"&stock="+stock);
				}
				if (opcionesimpresion==8) {
					window.open("../fpdf/imprimir_articulos_proveedor.php?codigobarras="+codigobarras+"&referencia="+referencia+"&descripcion="+descripcion+"&cboProveedores="+proveedores+"&cboFamilias="+familia+"&cboUbicacion="+ubicacion+"&stock="+stock);
				}
			
			}
		}
		
		function limpiar() {
			document.getElementById("form_busqueda").reset();
			document.getElementById("iniciopagina").value=1;
			document.getElementById("form_busqueda").submit();
		}		
		function buscar() {
			var cadena;
			cadena=hacer_cadena_busqueda();
			document.getElementById("cadena_busqueda").value=cadena;
			if (document.getElementById("iniciopagina").value=="") {
				document.getElementById("iniciopagina").value=1;
			} else {
				document.getElementById("iniciopagina").value=document.getElementById("paginas").value;
			}
			document.getElementById("form_busqueda").submit();
		}
		
		function paginar() {
			document.getElementById("iniciopagina").value=document.getElementById("paginas").value;
			document.getElementById("form_busqueda").submit();
		}

		function firstpage() {
			document.getElementById("iniciopagina").value=document.getElementById("firstpagina").value;
			document.getElementById("form_busqueda").submit();
		}
		function prevpage() {
			document.getElementById("iniciopagina").value=document.getElementById("prevpagina").value;
			document.getElementById("form_busqueda").submit();
		}
		function nextpage() {
			document.getElementById("iniciopagina").value=document.getElementById("nextpagina").value;
			document.getElementById("form_busqueda").submit();
		}
		function lastpage() {
			document.getElementById("iniciopagina").value=document.getElementById("lastpagina").value;
			document.getElementById("form_busqueda").submit();
		}
		
		function hacer_cadena_busqueda() {
			var codigobarras=document.getElementById("codigobarras").value;
			var referencia=document.getElementById("referencia").value;
			var descripcion=document.getElementById("descripcion").value;
			var proveedores=document.getElementById("cboProveedores").value;			
			var familia=document.getElementById("cboFamilias").value;
			var ubicacion=document.getElementById("cboUbicacion").value;
			var stock=document.getElementById("stock").value;
			//var familia=document.getElementById("familia").value;
			var cadena="";
			cadena="~"+codigobarras+"~"+referencia+"~"+familia+"~"+descripcion+"~"+proveedores+"~"+ubicacion+"~"+stock+"~"+familia+"~";
			return cadena;
			}
		
		function ventanaArticulos(){
			$.colorbox({
	   	href: "ventana_articulos.php", open:true,
			iframe:true, width:"99%", height:"99%",
			});
		}
		</script>
	</head>
	<body onload="inicio();">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="frmBusqueda">
				<form id="form_busqueda" name="form_busqueda" method="post" action="rejilla.php" target="frame_rejilla">
					<table class="fuente8" width="98%" cellspacing="0" cellpadding="2" border="0"><tr>
					<td valign="top">
					<table class="fuente8" width="98%" cellspacing="0" cellpadding="2" border="0">										
					<tr>
					<td valign="top" rowspan="3">
			 	<div>
	<?php 
		$escribir=verificopermisos('articulos', 'escribir', $UserID);
		$reportes=verificopermisos('reportes', 'leer', $UserID);		

	if ($escribir=="true") { ?>	
	<!-- iconos para los botones -->       
<button class="boletin" onClick="nuevo_articulo();"><i class="fa fa-file-o" aria-hidden="true"></i>&nbsp;N.&nbsp;artículo</button>
	<?php } 	?>	
				</div>					
					</td>						
							<td>Código</td>
							<td><input id="referencia" type="text" class="cajaPequena" name="referencia" maxlength="15" value="<?php echo $referencia;?>" onkeyup="document.getElementById('form_busqueda').submit();">
							 <img id="botonBusqueda" src="../img/ver.png" width="16" height="16" onClick="ventanaArticulos();" onMouseOver="style.cursor=cursor" title="Buscar articulos" style="vertical-align: middle; margin-top: -1px;"></td>

						</tr>
						<tr>
							<td>C&oacute;digo&nbsp;de&nbsp;barras</td>
							<td><input id="codigobarras" name="codigobarras" type="text" class="cajaGrande" maxlength="20" value="<?php echo $codigobarras;?>" onkeyup="document.getElementById('form_busqueda').submit();"></td>
						</tr>
						<?php
					  	$query_familias="SELECT * FROM familias ORDER BY nombre ASC";
						$res_familias=mysqli_query($GLOBALS["___mysqli_ston"], $query_familias);
						$contador=0;
					  ?>
						<tr>
							<td>Familia</td>
							<td><select id="cboFamilias" name="cboFamilias" class="comboMedio" onchange="document.getElementById('form_busqueda').submit();">
							<option value="0">Todas las familias</option>
								<?php
								while ($contador < mysqli_num_rows($res_familias)) { 
									if ( mysqli_result($res_familias, $contador, "codfamilia") == $familia) { ?>
								<option value="<?php echo mysqli_result($res_familias, $contador, "codfamilia")?>" selected><?php echo mysqli_result($res_familias, $contador, "nombre")?></option>
								<?php } else { ?> 
								<option value="<?php echo mysqli_result($res_familias, $contador, "codfamilia")?>"><?php echo mysqli_result($res_familias, $contador, "nombre")?></option>
								<?php }
								$contador++;
								} ?>				
								</select>	
								</td>
					    </tr>
							</table></td><td valign="top">
					  		<table class="fuente8" cellspacing=0 cellpadding=2 border=0>					    
						<tr>
							<td>Descripci&oacute;n</td>
							<td><input id="descripcion" name="descripcion" type="text" class="cajaGrande" maxlength="60" value="<?php echo $descripcion?>" onkeyup="document.getElementById('form_busqueda').submit();"></td>
						</tr>
						<?php
					  	$query_proveedores="SELECT codproveedor,nombre,nif FROM proveedores WHERE borrado=0 ORDER BY nombre ASC";
						$res_proveedores=mysqli_query($GLOBALS["___mysqli_ston"], $query_proveedores);
						$contador=0;
					  ?>
						<tr>
							<td>Proveedor</td>
							<td><select id="cboProveedores" name="cboProveedores" class="comboGrande" onchange="document.getElementById('form_busqueda').submit();">
							<option value="0">Todos los proveedores</option>
								<?php
								while ($contador < mysqli_num_rows($res_proveedores)) { 
									if ( mysqli_result($res_proveedores, $contador, "codproveedor") == $codproveedor) { ?>
								<option value="<?php echo mysqli_result($res_proveedores, $contador, "codproveedor")?>" selected><?php echo mysqli_result($res_proveedores, $contador, "nif")?> -- <?php echo mysqli_result($res_proveedores, $contador, "nombre")?></option>
								<?php } else { ?> 
								<option value="<?php echo mysqli_result($res_proveedores, $contador, "codproveedor")?>"><?php echo mysqli_result($res_proveedores, $contador, "nif")?> -- <?php echo mysqli_result($res_proveedores, $contador, "nombre")?></option>
								<?php }
								$contador++;
								} ?>				
								</select>							</td>
					    </tr>
					<?php
					  	$query_ubicacion="SELECT codubicacion,nombre FROM ubicaciones WHERE borrado=0 ORDER BY nombre ASC";
						$res_ubicacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_ubicacion);
						$contador=0;
					  ?>
						<tr>
							<td>Ubicaci&oacute;n</td>
							<td><select id="cboUbicacion" name="cboUbicacion" class="comboGrande" onchange="document.getElementById('form_busqueda').submit();">
							<option value="0">Todas las ubicaciones</option>
								<?php
								while ($contador < mysqli_num_rows($res_ubicacion)) { 
									if ( mysqli_result($res_ubicacion, $contador, "codubicacion") == $codubicacion) { ?>
								<option value="<?php echo mysqli_result($res_ubicacion, $contador, "codubicacion")?>" selected><?php echo mysqli_result($res_ubicacion, $contador, "nombre")?></option>
								<?php } else { ?> 
								<option value="<?php echo mysqli_result($res_ubicacion, $contador, "codubicacion")?>"><?php echo mysqli_result($res_ubicacion, $contador, "nombre")?></option>
								<?php }
								$contador++;
								} ?>				
								</select>							</td>
					    </tr>
					</table></td>
					<td valign="top">
					  		<table class="fuente8" cellspacing=1 cellpadding=2 border=0>					    
						<tr>
							<td>Stock&nbsp;>&nbsp;<input id="stock" name="stock" type="text" class="cajaMinima" maxlength="60" value="<?php echo $stock;?>"></td>
						<td>
						<fieldset><legend>&nbsp;Opciones de impresión&nbsp;</legend>
						<select name="opcionesimpresion" id="opcionesimpresion" class="comboGrande" >
						<option value="1">Listado de artículos activos</option>
						<option value="2">Lista de precios general</option>
						<option value="3">Lista de precios por familias</option>
						<option value="4">Lista de precios por familias con logos</option>
						<option value="5">Listado de artículos con comisión</option>
						<option value="6">Listado de artículos de baja</option>
						<option value="7">Listado de artículos stock bajo mínimo</option>
						<option value="8">Listado de artículos por proveedor</option>
						</select>
						<br>&nbsp;<br>
						<label>Exportar a Excel?
  							<input id="opcionesaexcel" name="opcionesaexcel" type="checkbox" checked="true" value="0">
  							<span></span>
						</label>						
						</fieldset>
						</td>
						</tr>
							</table>					
					</td>
					<td>
<table>
			<tr><td><button class="boletin" onClick="buscar();" onMouseOver="style.cursor=cursor"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar</button></td>
			  							</tr><tr>
						<td><button class="boletin" onClick="limpiar();" onMouseOver="style.cursor=cursor"><i class="fa fa-eraser" aria-hidden="true"></i>&nbsp;Limpiar</button></td>
						</tr><tr>
						<td>
						<?php 
						 if ($reportes=="true") {?>
						 <button class="boletin" onClick="imprimir();" onMouseOver="style.cursor=cursor">
						<i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir</button>
						<?php } ?>							
						</td>						
			  </tr>			
			</table>					
					</td>
					</tr></table>
			  </div>
					<div>
			  <div id="lineaResultado">
			  <table class="fuente8" width="90%" cellspacing=0 cellpadding=3 border=0>
			  	<tr>
				<td width="30%" align="left">Cantidad <input id="filas" type="text" class="cajaMinima" name="filas" maxlength="5" readonly></td>
				<td  align="center">
				<td><button class="boletin" id="estadistica" onMouseOver="style.cursor=cursor">
				<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Estadística</button></td>
				</td>
				<td width="20%" align="right">
					<table class="fuente8" cellspacing=1 cellpadding=1 border=0>
		<td>				
		<input type="hidden" id="firstpagina" name="firstpagina" value="1">
		<img style="display: none;" src="../img/paginar/first.gif" id="first" border="0" height="13" width="13" onClick="firstpage()" onMouseOver="style.cursor=cursor">
		<img style="display: none;" src="../img/paginar/firstdisab.gif" id="firstdisab" border="0" height="13" width="13"></td>
		<td>
		<input type="hidden" id="prevpagina" name="prevpagina" value="">
		<img style="display: none;" src="../img/paginar/prev.gif" id="prev" border="0" height="13" width="13" onClick="prevpage()" onMouseOver="style.cursor=cursor">
		<img style="display: none;" src="../img/paginar/prevdisab.gif" id="prevdisab" border="0" height="13" width="13">
		</td><td>
		<input id="currentpage" type="text" class="cajaMinima" >
		</td><td>
		<input type="hidden" id="nextpagina" name="nextpagina" value="">
		<img style="display: none;" src="../img/paginar/next.gif" id="next" border="0" height="13" width="13" onClick="nextpage()" onMouseOver="style.cursor=cursor">
		<img style="display: none;" src="../img/paginar/nextdisab.gif" id="nextdisab" border="0" height="13" width="13"></td>
		<td>
		<input type="hidden" id="lastpagina" name="lastpagina" value="">
		<img style="display: none;" src="../img/paginar/last.gif" id="last" border="0" height="13" width="13" onClick="lastpage()" onMouseOver="style.cursor=cursor">
		<img style="display: none;" src="../img/paginar/lastdisab.gif" id="lastdisab" border="0" height="13" width="13"></td>
		<td>Mostrados</td><td> <select name="paginas" id="paginas" onChange="paginar();" class="comboPequeno">
		          </select></td>      
		</table>	</td>
			  </table>
				</div>
				<input type="hidden" id="iniciopagina" name="iniciopagina">
				<input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
				<input type="hidden" id="selid" name="selid">
			</form>
					<iframe width="98%" height="330" id="frame_rejilla" name="frame_rejilla" frameborder="0">
						<ilayer width="98%" height="330" id="frame_rejilla" name="frame_rejilla"></ilayer>
					</iframe>
					<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>


			</div>
		  </div>			
		</div>
	</body>
</html>
