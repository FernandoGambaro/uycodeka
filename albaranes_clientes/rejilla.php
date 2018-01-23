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

include ("../conectar.php");
include("../common/verificopermisos.php");
//header('Content-Type: text/html; charset=UTF-8'); 
include ("../funciones/fechas.php");

//verificopermisos('rep', 'leer', $USERID);


$codcliente=$_POST["codcliente"];
$nombre=$_POST["nombre"];
$numalbaran=$_POST["numalbaran"];
$estado=$_POST["cboEstados"];
$fechainicio=$_POST["fechainicio"];
if ($fechainicio<>"") { $fechainicio=explota($fechainicio); }
$fechafin=$_POST["fechafin"];
if ($fechafin<>"") { $fechafin=explota($fechafin); }

$cadena_busqueda=$_POST["cadena_busqueda"];

$where="1=1";
if ($codcliente <> "") { $where.=" AND albaranes.codcliente='$codcliente'"; }
if ($nombre <> "") { $where.=" AND clientes.nombre like '%".$nombre."%'"; }
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
$query_busqueda="SELECT count(*) as filas FROM albaranes,clientes WHERE albaranes.borrado=0 AND albaranes.codcliente=clientes.codcliente AND ".$where;

$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
$filas=mysqli_result($rs_busqueda, 0, "filas");

?>
<html>
	<head>
		<title>Clientes</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">

		<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="js/colorbox.css" />
		<script src="js/jquery.colorbox.js"></script>

    <script src="js/jquery.msgBox.js" type="text/javascript"></script>
    <link href="js/msgBoxLight.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="js/jquery.toastmessage.css" type="text/css">
<script src="js/jquery.toastmessage.js" type="text/javascript"></script>
<script src="js/message.js" type="text/javascript"></script>
		
		<script language="javascript">
		
		function ver_albaran(codalbaran) {
			var url="ver_albaran.php?codalbaran=" + codalbaran + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			var w='99%';
			var h='99%';
			window.parent.OpenNote(url,w,h);			
		}
		
		function imprimir_etiquetas(codalbaran) {
				window.open("../fpdf/codigocontinuo.php?codalbaran="+codalbaran);
		}
		
		function modificar_albaran(codalbaran,marcaestado) {
			if (marcaestado==1) {
				var url="modificar_albaran.php?codalbaran=" + codalbaran + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
				var w='99%';
				var h='99%';
				window.parent.OpenNote(url,w,h);			
			} else {
				alert ("No puede modificar un albaran facturado");
			}
		}

		var windowObjectReference;
		function enviar_albaran(codalbaran) {
			windowObjectReference = window.open('../enviomail/envia.html',"EnvioMail", "resizable,scrollbars,status, width=300,height=100,right=300,top=200");
			setTimeout(function() {windowObjectReference.location.href="../fpdf/imprimir_albaran.php?codalbaran="+codalbaran+"&envio=1"}, 1000);					
		}
		
		function convertir_albaran(codalbaran,marcaestado) {
		  $.msgBox({
			 title: "Nueva factura",
		    type    : "prompt",
		    inputs  : [
		      {type: "text", maxlength: 30, name:"num", header: "Nº factura:", size: 10, required: true},
		      {type: "text", name: "fecha",	header: "Fecha:", maxlength: 15, size: 15, required: true}],
		    buttons : [
		      {value: "OK"}, {value: "Cancel"}],
		    success: function (result, values) {
					if (result=="OK") {
						var n='';
						var f='';
						$(values).each(function (index, input) {
               	 	if (input.name == "fecha") {
            	    	 f=input.value;
         	       	}         
      	          	if (input.name == "num") {
   	             	 n=input.value;
	                	}         
            		});
            		if (n !='' && f != ''){
            			if (marcaestado==1) {
            				window.parent.OpenNote("convertir_albaran.php?codalbaran=" + codalbaran + "&cadena_busqueda=<?php echo $cadena_busqueda?>&num="+n+"&fecha="+f,880,450);
            			} else {
								alert ("No se puede facturar una orden de compra ya facturada");
							}
						} else {
							showWarningToast('Falta un dato');	
						}
					}
				}
			});
		}
		
		function eliminar_albaran(codalbaran) {
			var url="eliminar_albaran.php?codalbaran=" + codalbaran + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			var w='99%';
			var h='99%';
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
				if (contador+paginacion-1>numfilas) {
					
				}
				texto=contador + " al " + (contador+paginacion-1);
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
		/*	idstyle=list;
			var el = document.getElementById(list);
			if (!document.getElementById(list)) {
			idstyle='';
			} else {
			el.setAttribute('style', estilo);   
		}
		*/
		}
		</script>
	</head>

	<body onload="inicio();">	
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
				<div class="header">LISTADO DE ORDENES DE PEDIDO</div>
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="8%">Nº.</td>
							<td width="29%" class="aIzquierda">CLIENTE </td>
							<td width="10%">MONEDA </td>							
							<td width="10%">IMPORTE </td>							
							<td width="10%">FECHA</td>
							<td width="10%">FECHA ENTREGA</td>
							<td width="10%">ESTADO </td>
							<td colspan="6">ACCION</td>
						</tr>
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas?>">
				<?php $iniciopagina=$_POST["iniciopagina"];
				$tipomon = array( 0=>"&nbsp;", 1=>"Pesos", 2=>"U\$S");
				if ($iniciopagina=='') { $iniciopagina=@$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if ($iniciopagina=='') { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) { ?>
						<?php
							$leer=verificopermisos('albaran', 'leer', $UserID);
							$escribir=verificopermisos('albaran', 'escribir', $UserID);
							$modificar=verificopermisos('albaran', 'modificar', $UserID);
							$eliminar=verificopermisos('albaran', 'eliminar', $UserID);
						
						 $sel_resultado="SELECT codalbaran,clientes.empresa,clientes.nombre,clientes.apellido as apellido,albaranes.fecha as fecha,fechaentrega,totalalbaran,moneda,estado 
						FROM albaranes,clientes WHERE albaranes.borrado=0 AND albaranes.codcliente=clientes.codcliente  AND ".$where;
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
							<td width="8%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "codalbaran");?></div></td>
							<?php if (mysqli_result($res_resultado, $contador, "empresa")!='') { ?>
							<td width="45%" ><div align="left"><?php echo mysqli_result($res_resultado, $contador, "empresa");?></div></td>
							<?php } elseif (mysqli_result($res_resultado, $contador, "apellido")=='') { ?>
							<td width="45%"><div align="left"><?php echo mysqli_result($res_resultado, $contador, "nombre");?></div></td>
							<?php } else { ?>
							<td width="45%"><div align="left"><?php echo mysqli_result($res_resultado, $contador, "nombre")." ". mysqli_result($res_resultado, $contador, "apellido");?></div></td>
							<?php } ?>
							<td width="10%"><div align="center"><?php echo $moneda;?></div></td>							
							<td width="10%"><div align="center"><?php echo number_format(mysqli_result($res_resultado, $contador, "totalalbaran"),2,",",".");?></div></td>							
							<td class="aDerecha" width="10%"><div align="center"><?php echo implota(mysqli_result($res_resultado, $contador, "fecha"));?></div></td>
							<td class="aDerecha" width="10%"><div align="center"><?php echo implota(mysqli_result($res_resultado, $contador, "fechaentrega"));?></div></td>
							<td width="10%"><div align="center"><?php echo $estado?></div></td>
						<?php if ( $modificar=="true") { ?>	
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/modificar.png" width="16" height="16" border="0" onClick="modificar_albaran(<?php echo mysqli_result($res_resultado, $contador, "codalbaran")?>,<?php echo $marcaestado?>)" title="Modificar"></a></div></td>
						<?php } else { ?>
							<td width="20px">&nbsp;</td>
						<?php } if ( $leer=="true") { ?>								
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/ver.png" width="16" height="16" border="0" onClick="ver_albaran(<?php echo mysqli_result($res_resultado, $contador, "codalbaran")?>)" title="Visualizar"></a></div></td>
							<?php } else { ?>
							<td width="20px">&nbsp;</td>
							<?php } if ( $eliminar=="true") { ?>
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar_albaran(<?php echo mysqli_result($res_resultado, $contador, "codalbaran")?>,<?php echo $marcaestado?>)" title="Eliminar"></a></div></td>
							<?php } else { ?>
							<td width="20px">&nbsp;</td>
							<?php } ?>	
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/sobre.png" width="16" height="16" border="0" onClick="enviar_albaran(<?php echo mysqli_result($res_resultado, $contador, "codalbaran")?>)" title="Enviar al cliente"></a></div></td>
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/convertir.png" width="16" height="16" border="0" onClick="convertir_albaran(<?php echo mysqli_result($res_resultado, $contador, "codalbaran")?>,<?php echo $marcaestado?>)" title="Facturar"></a></div></td>
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/imprimir.png" width="16" height="16" border="0" onClick="imprimir_etiquetas(<?php echo mysqli_result($res_resultado, $contador, "codalbaran")?>)" title="Imprimir etiquetas"></a></div></td>
						</tr>
						<?php $contador++;
							}
						?>			
					</table>
					<?php } else { ?>
					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" class="mensaje"><?php echo "No hay ning&uacute;n remito que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
					</table>					
					<?php } ?>					
				</div>
			</div>
		  </div>			
		</div>
	</body>
</html>
<?php

?>