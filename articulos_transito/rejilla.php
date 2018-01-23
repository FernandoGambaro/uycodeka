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
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
//header('Content-Type: text/html; charset=UTF-8'); 

include ("../funciones/fechas.php");

$codcliente=$_POST["codcliente"];
$codartiviaja=$_POST["codartiviaja"];
$estado=$_POST["cboEstados"];
$codarticulo=$_POST["codarticulo"];
$fechainicio=$_POST["fechainicio"];
if ($fechainicio<>"") { $fechainicio=explota($fechainicio); }
$fechafin=$_POST["fechafin"];
if ($fechafin<>"") { $fechafin=explota($fechafin); }

$cadena_busqueda=$_POST["cadena_busqueda"];

$where="1=1";
if ($codcliente <> "") { $where.=" AND artiviaja.codcliente='$codcliente'"; }
if ($codartiviaja <> "") { $where.=" AND artiviaja.codartiviaja='$codartiviaja'"; }
if ($estado > "0") { $where.=" AND artiviaja.estado='$estado' "; }
if ($codarticulo > "0") { $where.=" AND artiviajalinea.codigo='$codarticulo' "; }

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

if($codarticulo=="") {
	$where.=" GROUP BY artiviaja.codartiviaja ";
}
$where.=" ORDER BY fecha DESC";

$query_busqueda="SELECT count(*) as filas FROM artiviaja INNER JOIN artiviajalinea ON artiviaja.codartiviaja = artiviajalinea.codartiviaja
INNER JOIN articulos ON articulos.codarticulo = artiviajalinea.codigo WHERE artiviaja.borrado=0 AND ".$where;
$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
$filas=mysqli_result($rs_busqueda, 0, "filas");

?>
<html>
	<head>
		<title>Articulos en transito</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="js/jquery.toastmessage.css" type="text/css">
		<script src="js/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="js/message.js" type="text/javascript"></script>

    <script src="js/jquery.msgBox.js" type="text/javascript"></script>
    <link href="js/msgBoxLight.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
var estilo="background-color: #2d2d2d; border-bottom: 1px solid #000; color: #fff;";

var idstyle='';

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
    
		<script type="text/javascript">
			function resetMeIfChecked(radio){           
			    if(radio.checked && radio.value == window.lastrv){
			        $(radio).removeAttr('checked');
			        window.lastrv = 0;
			    }
			    else
			        window.lastrv = radio.value;
			}

			function getRadioVal() {
			    var val;
			    for (var i=0, len=radio.length; i<len; i++) {
			        if ( radio[i].checked ) { 
			            val = radio[i].value;
			            break; 
			        }
			    }
			    return val; 
			}
			
		</script>		
		
		<script language="javascript">
		var indiaux='';
		
		function ver_artiviaja(codartiviaja) {
			var url="ver_artiviaja.php?codartiviaja=" + codartiviaja + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			var w='780';
			var h='550';
			window.parent.OpenNote(url,w,h);
		}
				
		function modificar_artiviaja(codartiviaja,emitido) {
			if (emitido!=1) {
				var url="modificar_artiviaja.php?codartiviaja=" + codartiviaja + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
				var w='780';
				var h='600';
				window.parent.OpenNote(url,w,h);
			} else {
				$.msgBox({
				    title: "Alerta",
				    content: "Quiere modificar remito transito emitido?",
				    type: "confirm",
				    buttons: [{ value: "Si" }, { value: "No" }, { value: "Cancelar"}],
				    success: function (result) {
				        if (result == "Si") {
								$.msgBox({ type: "prompt",
								    title: "Autorización",
								    inputs: [
								    { header: "Contraseña", type: "password", name: "password" }],
								    buttons: [
								    { value: "Aceptar" }, { value:"Cancelar" }],
								    success: function (result, values) {
											$(values).each(function (index, input) {
                     					v =  input.value ;
                 						});	
                 														    	
    										if (v=="1234") {
												var url="modificar_artiviaja.php?codartiviaja=" + codartiviaja + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
												var w='750';
												var h='600';
												window.parent.OpenNote(url,w,h);
											} else {
												showWarningToast('Contraseña erronea');
											}
										}
								});					        	
				        }
				    }
				})(jQuery);	
			}
		}
		
		function eliminar_artiviaja(codartiviaja, emitida) {
			var pass='';
			var v='';
			if (emitida==1) {
				$.msgBox({
				    title: "Alerta",
				    content: "Va a proceder a la eliminacion de una artículo en tránsito. Desea continuar?",
				    type: "confirm",
				    buttons: [{ value: "Eliminar" }, { value: "Cancelar"}],
				    success: function (result) {
				        if (result == "Si") {
								$.msgBox({ type: "prompt",
								    title: "Autorización",
								    inputs: [
								    { header: "Contraseña de un admininistrador?", type: "password", name: "password" }],
								    buttons: [
								    { value: "Aceptar" }, { value:"Cancelar" }],
								    success: function (result, values) {
											$(values).each(function (index, input) {
                     					v =  input.value ;
                 						});	
											$.post("../common/verifico_password.php", {"password":v }, function(response){ 
												 if (response==1) {
												var url="eliminar_artiviaja.php?codartiviaja=" + codartiviaja + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
												var w='750';
												var h='600';
												window.parent.OpenNote(url,w,h);
												} else {
												showWarningToast('Contraseña erronea');
												}
											});  
										        event.preventDefault();
										}
								});					        	
				        }
				    }
				})(jQuery);				
				} else {
						var url="eliminar_artiviaja.php?codartiviaja=" + codartiviaja + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
						var w='750';
						var h='600';
						window.parent.OpenNote(url,w,h);					
				}
			}		

		function imprimir() {
			var val = getRadioVal();
			if(val>0){
			return val;
			} else {
			return false;
			}
		}

		function enviar_artiviaja(codartiviaja) {
		var windowObjectReference;
			windowObjectReference = window.open('../enviomail/envia.php',"EnvioMail", "resizable,scrollbars,status, width=300,height=100,right=300,top=200");
			setTimeout(function() {windowObjectReference.location.href="../fpdf/imprimir_artiviaja.php?codartiviaja="+codartiviaja+"&envio=1"}, 1000);					
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
			list=parent.document.getElementById("selid").value;
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
			<div class="header" style="width:100%;position: fixed; font-size: 140%;">Listado de Artículos en Tránsito</div>
			<div class="fixed-table-container">
      		<div class="header-background cabeceraTabla"> </div>      			
			<div class="fixed-table-container-inner">
				<table class="fuente8" width="100%" cellspacing="0" cellpadding="3" border="0" style="font-size: 130%;">
					<thead>			
						<tr class="cabeceraTabla">
							<th colspan="2"><div class="th-inner">Nº</div></th>
							<th width="20" ><div class="th-inner">FECHA</div></th>
							<th align="left"><div class="th-inner">CLIENTE</div> </th>							
							<th><div class="th-inner">DESTINO</div></th>
							<th width="40"><div class="th-inner">TOTAL</div></th>
							<th width="40"><div class="th-inner">ESTADO</div></th>
							<th><div class="th-inner">TRANSPORTISTA</div></th>
							<th><div class="th-inner">ENVIO</div></th>
							<th colspan="3" width="30" align="center"><div class="th-inner">ACCIÓN</div></th>
							</tr>
							</thead>
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas;?>">
				<?php $iniciopagina=$_POST["iniciopagina"];
				
						$esta = array( 0=>"Entregado", 1=>"En&nbsp;transito");
						$tipo = array(1=>"1", 2=>" ");

				if ($iniciopagina=='') { $iniciopagina=@$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if ($iniciopagina=='') { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) { ?>
						<?php						
							$leer=verificopermisos('articulos', 'leer', $UserID);
							$escribir=verificopermisos('articulos', 'escribir', $UserID);
							$modificar=verificopermisos('articulos', 'modificar', $UserID);
							$eliminar=verificopermisos('articulos', 'eliminar', $UserID);
													
						 $sel_resultado=" SELECT artiviaja.codartiviaja as codartiviaja, artiviaja.emitido as emitido, artiviaja.fecha, destino, estado, codigo, detalles, artiviaja.transportista as trasportista,
						  clientes.empresa, clientes.nombre, clientes.apellido, artiviaja.fechaenvio as fechaenvio ,artiviaja.fecharentrega, chofer FROM
						  artiviaja INNER JOIN artiviajalinea on artiviaja.codartiviaja=artiviajalinea.codartiviaja 
						  INNER JOIN clientes on clientes.codcliente=artiviaja.codcliente WHERE artiviaja.borrado=0 AND ".$where;
					   $sel_resultado=$sel_resultado."  limit ".$iniciopagina.",10";
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;
						   while ($contador < mysqli_num_rows($res_resultado)) { 
								if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }
								$codartiviaja=mysqli_result($res_resultado, $contador, "codartiviaja");
								/*Sumo la cantidad de articulos que tiene cada remito*/
								
								$query_total="SELECT count(*) as fil FROM  `artiviajalinea` WHERE codartiviaja='$codartiviaja'";
								$rs_total=mysqli_query($GLOBALS["___mysqli_ston"], $query_total);
								$total=mysqli_result($rs_total, 0, "fil");
								?>
						<tr id="<?php echo mysqli_result($res_resultado, $contador, "codartiviaja");?>"	 class="<?php echo $fondolinea?> trigger">
							<td ><div align="center">
							<input type="radio" name="radio" id="radio" onClick="resetMeIfChecked(this)" value="<?php echo mysqli_result($res_resultado, $contador, "codartiviaja");?>" style="border:0">
							</div></td>
							<td><div align="center"><?php echo $codartiviaja;?></div></td>
							
							<td width="20"><?php echo implota(mysqli_result($res_resultado, $contador, "fecha"));?></td>
							<?php if (mysqli_result($res_resultado, $contador, "empresa")!='') {?>
							<td><div align="left"><?php echo mysqli_result($res_resultado, $contador, "empresa")?></div></td>
							<?php } elseif (mysqli_result($res_resultado, $contador, "apellido")=='') {?>
							<td><div align="left"><?php echo mysqli_result($res_resultado, $contador, "nombre")?></div></td>
							<?php } else { ?>
							<td><div align="left"><?php echo mysqli_result($res_resultado, $contador, "nombre")?>
							 <?php echo mysqli_result($res_resultado, $contador, "apellido")?></div></td>
							<?php } ?>

							<td ><div align="center"><?php echo mysqli_result($res_resultado, $contador, "destino");?></div></td>
							<td width="80" ><div align="center"><?php echo $total;?></div></td>
							<td width="80"><div align="center"><?php 
							if (mysqli_result($res_resultado, $contador, "estado")!="")							
								echo $esta[mysqli_result($res_resultado, $contador, "estado")];?></div></td>
							<td><div><?php echo mysqli_result($res_resultado, $contador, "trasportista");?></div></td>
							<td  width="20"><div><?php echo implota(mysqli_result($res_resultado, $contador, "fechaenvio"));?></div></td>								
							<?php if ( $modificar=="true") { ?>								
							<td width="10" ><div><a href="#"><img id="botonBusqueda" src="../img/modificar.png" width="16" height="16" border="0" onClick="modificar_artiviaja(<?php echo mysqli_result($res_resultado, $contador, "codartiviaja");?>,<?php echo mysqli_result($res_resultado, $contador, "emitido");?>);" title="Modificar"></a></div></td>
							<?php } else { ?>
							<td width="10">&nbsp;</td>
							<?php } if ( $leer=="true") { ?>								
							<td width="10" ><div><a href="#"><img id="botonBusqueda" src="../img/ver.png" width="16" height="16" border="0" onClick="ver_artiviaja(<?php echo mysqli_result($res_resultado, $contador, "codartiviaja");?>);" title="Visualizar"></a></div></td>
							<?php } else { ?>
							<td width="10">&nbsp;</td>
							<?php } if ( $eliminar=="true") { ?>
							<td width="10" ><div><a href="#"><img id="botonBusqueda" src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar_artiviaja(<?php echo mysqli_result($res_resultado, $contador, "codartiviaja");?>,<?php echo mysqli_result($res_resultado, $contador, "emitido");?>);" title="Eliminar"></a></div></td>
							<?php } else { ?>
							<td width="10">&nbsp;</td>
							<?php } ?>	
						</tr>
						<?php $contador++;
							}
						?>			
					</table>
					<?php } else { ?>
					<table class="fuente8" width="87%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" class="mensaje"><?php echo "No hay ninguna artiviaja que cumpla con los criterios de b&uacute;squeda<br>";
							?></td>
					    </tr>
					</table>					
					<?php } ?>					
				</div>
			</div>
		  </div>			
		</div>
	</body>
</html>
