<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

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
@$paginacion=$s->data['alto'];

if($paginacion<=0) {
	$paginacion=20;
}
$Seleccionados=array();
$Seleccionados=@$s->data['Selected'];

include ("../conectar.php");
include("../common/verificopermisos.php");
include("../common/funcionesvarias.php");

////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 
////header('Content-Type: text/html; charset=UTF-8'); 

include ("../funciones/fechas.php");


$a="";
$data=array();
$a=isset($_GET['a']) ? $_GET['a'] : null ;
$a=array_recibe($a);

if(is_array($a) && !empty($a)){
	/*/echo "veo array()<br>";*/
	
	foreach ($a as $key => $value)
	{

		$temp = is_array($value) ? $value : trim($value);
	    $$key = $temp;
	    $data[$$key]=$temp;
			if($key=='cboEstados') {
				$estado=$temp;
			}
	}
	$data=$a;
} else { 

$codcliente=$data['codcliente']=isset($_GET['codcliente']) ? $_GET['codcliente'] : $_POST["codcliente"] ;
$numfactura=$data['numfactura']=isset($_GET['numfactura']) ? $_GET['numfactura'] : $_POST["numfactura"];
$estado=$data['cboEstados']=isset($_GET['cboEstados']) ? $_GET['cboEstados'] : $_POST["cboEstados"];
$fechainicio=$data['fechainicio']=isset($_GET['fechainicio']) ? $_GET['fechainicio'] : $_POST["fechainicio"] ;
$fechafin=$data['fechafin']=isset($_GET['fechafin']) ? $_GET['fechafin'] : $_POST["fechafin"] ;
$codarticulo=$data['codarticulo']=isset($_GET['codarticulo']) ? $_GET['codarticulo'] : $_POST['codarticulo'] ;

$moneda=$data['Amoneda']=isset($_GET['Amoneda']) ? $_GET['Amoneda'] : $_POST['Amoneda'] ;

$cadena_busqueda=$data['cadena_busqueda']=isset($_GET['cadena_busqueda']) ? $_GET['cadena_busqueda'] : $_POST["cadena_busqueda"];
$codncredito=@$data['codncredito']=isset($_GET['codncredito']) ? $_GET['codncredito'] : $_POST["codncredito"] ;

$iniciopagina=$data['iniciopagina']=isset($_GET['iniciopagina']) ? $_GET['iniciopagina'] : $_POST["iniciopagina"];
}

$data=array_envia($data); 

$ordenado=isset($_GET['ordenado']) ? $_GET['ordenado'] : '' ;
$orden=isset($_GET['orden']) ? $_GET['orden'] : '' ;

$ordeno=explode('-', $ordenado);
foreach($ordeno as $x){
	if($x!='') {
		$ordenado=$x;
		break;
	}
}

if($orden=="ASC,"){
	$neworden="DESC,";
} else {
	$neworden="ASC,";
}

if ($fechainicio<>"") { $fechainicio=explota($fechainicio); }
if ($fechafin<>"") { $fechafin=explota($fechafin); }

$where="1=1";
$whereN="1=1";
if ($codcliente <> "") { $whereN.=$where.=" AND facturas.codcliente='$codcliente'"; }
if ($numfactura <> "") { $where.=" AND facturas.codfactura='$numfactura'"; }
if ($estado > "0") { $where.=" AND facturas.estado='$estado' and facturas.tipo = 1 "; }
if (($fechainicio<>"") and ($fechafin<>"")) {
	$whereN.=$where.=" AND facturas.fecha between '".$fechainicio."' AND '".$fechafin."'";
} else {
	if ($fechainicio<>"") {
		$whereN.=$where.=" and facturas.fecha>='".$fechainicio."'";
	} else {
		if ($fechafin<>"") {
			$whereN.=$where.=" and facturas.fecha<='".$fechafin."'";
		}
	}
}
if ($codarticulo <> "") { $where.=" AND factulinea.codigo='$codarticulo'"; }
if ($moneda <> "") { $where.=" AND facturas.moneda='$moneda'"; }

$where.=" ORDER BY ".$ordenado." ".$orden." facturas.fecha DESC , facturas.codfactura DESC";

if($codarticulo=='') {
	$query_busqueda="SELECT count( * ) AS filas FROM clientes RIGHT JOIN facturas ON clientes.codcliente = facturas.codcliente
	LEFT JOIN ncredito ON clientes.codcliente = ncredito.codcliente WHERE ".$where;
} else {	
	$query_busqueda="SELECT count( * ) AS filas FROM facturas LEFT JOIN factulinea ON facturas.codfactura = factulinea.codfactura 
	LEFT JOIN clientes on clientes.codcliente=facturas.codcliente WHERE ".$where;
}

$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
$filas=mysqli_result($rs_busqueda, 0, "filas");

if ($codcliente <> "") { $whereN.=" AND ncreditos.codcliente='$codcliente'"; }
if ($codncredito <> "") { $whereN.=" AND ncreditos.codncredito='$codncredito'"; }


$whereN.=" ORDER BY ncredito.codncredito DESC";
$query_busquedaN="SELECT count(*) as filas FROM ncredito,clientes WHERE ncredito.borrado=0 AND ncredito.codcliente=clientes.codcliente AND ".$whereN;
$rs_busquedaN=mysqli_query($GLOBALS["___mysqli_ston"], $query_busquedaN);
if(mysqli_query($GLOBALS["___mysqli_ston"], $query_busquedaN)) {
	$filasN=mysqli_result($rs_busquedaN, 0, "filas");
} else {
	$filasN=0;
}


?>
<html>
	<head>
		<title>Factura Clientes</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
		<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js3/message.js" type="text/javascript"></script>

    <script src="../js3/jquery.msgBox.js" type="text/javascript"></script>
    <link href="../js3/msgBoxLight.css" rel="stylesheet" type="text/css">

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">

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
      oidd='#n'+this.id;
		var el = document.getElementById(list);
		el.setAttribute('style', estilo);    
		parent.document.getElementById("selid").value=list;
		
		if ($(oidd).is(':checked') == true) {
		var x = 1;
		} else {
		var x = 0;
		}
 		$.post("selecciono.php?codfactura="+list, {q: ""+x+""}, function(data){

		});		
		
   });   
   
});

</script>		
		<script language="javascript">

		function ver_factura(codfactura) {
			var url="ver_factura.php?codfactura=" + codfactura + "&a=<?php echo $data;?>";
			var w='800';
			var h='99%';
			window.parent.OpenNote(url,w,h);
		}
				
		function modificar_factura(codfactura,marcaestado,emitida) {
			if (marcaestado!='-' && emitida!=1) {
				var url="modificar_factura.php?codfactura=" + codfactura + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
				var w='99%';
				var h='99%';
				window.parent.OpenNote(url,w,h);
			} else {
				$.msgBox({
				    title: "Alerta",
				    content: "Quiere modificar factura emitida?",
				    type: "confirm",
				    buttons: [{ value: "Si" }, { value: "Cancelar"}],
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
												var url="modificar_factura.php?codfactura=" + codfactura + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
												var w='99%';
												var h='99%';
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
		
		function eliminar_factura(codfactura, emitida) {
			if (emitida!=1) {
					$.msgBox({
				    title: "Alerta",
				    content: "Va a eliminar una factura. Desea continuar?",
				    type: "confirm",
				    buttons: [{ value: "Eliminar" }, { value: "Cancelar"}],
				    success: function (result) {
				        if (result != "Cancelar") {
				        	var accion='';
				        	if (result=="Eliminar") {
				        		accion="eliminar";
				        	}
								$.msgBox({ type: "prompt",
								    title: "Autorización",
								    inputs: [
								    { header: "Contraseña de un admininistrador?", type: "password", name: "password" }],
								    buttons: [
								    { value: "Aceptar" }, { value:"Cancelar" }],
								    success: function (resultado, values) {
											$(values).each(function (index, input) {
                     					v =  input.value ;
                 						});	
											$.post("../common/verifico_password.php", {"password":v }, function(response){ 
												 if (response==1) {
													var url="eliminar_factura.php?codfactura=" + codfactura + "&accion="+accion;
												var w='99%';
												var h='99%';
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
					$.msgBox({
				    title: "Alerta",
				    content: "No se puede eliminar factura emitida",
				    type: "confirm",
				    buttons: [ { value: "Aceptar"}],
				    success: function (result) {
							}
					});						
				}
			}

		function enviar_factura(codfactura, envio) {
		var windowObjectReference;
			if (envio!=1) {
				window.parent.mensaje("Enviando mail al cliente");
			windowObjectReference = window.open('../enviomail/envia.php',"EnvioMail", "resizable,scrollbars,status, width=300,height=110,right=300,top=200");
			setTimeout(function() {windowObjectReference.location.href="../fpdf/imprimir_factura_mail.php?codfactura="+codfactura+"&envio=1"}, 1000);
			} else {
					$.msgBox({
				    title: "Alerta",
				    content: "Quiere reenviar factura?",
				    type: "confirm",
				    buttons: [{ value: "Si" }, { value: "Cancelar"}],
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
											windowObjectReference = window.open('../enviomail/envia.php',"EnvioMail", "resizable,scrollbars,status, width=300,height=110,right=300,top=200");
											setTimeout(function() {windowObjectReference.location.href="../fpdf/imprimir_factura_mail.php?codfactura="+codfactura+"&envio=1"}, 1000);
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
		
		
		function inicio() {
			var list=parent.document.getElementById("selid").value;
			var paginacion=<?php echo $paginacion;?>;
			var numfilas=document.getElementById("numfilas").value;
			var indi=parent.document.getElementById("iniciopagina").value;
			var contador=1;
			var indice=0;
			if (parseInt(indi)>parseInt(numfilas)) { 
				indi=1; 
			}
			if (parseInt(numfilas) <= paginacion) {
					parent.document.getElementById("nextdisab").style.display = 'block';
					parent.document.getElementById("lastdisab").style.display = 'block';
					parent.document.getElementById("last").style.display = 'none';
					parent.document.getElementById("next").style.display = 'none';
			}
			parent.document.form_busqueda.filas.value=' '+numfilas;
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
			
			idstyle=list;
			var el = document.getElementById(list);
			//if(jQuery("#"+list).length){
			//alert('pp')			;
			//}
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
			<div class="header" style="width:100%;position: fixed; font-size: 140%;">Listado de Facturas Ventas </div>
			<div class="fixed-table-container">
      		<div class="header-background cabeceraTabla"> </div>      			
			<div class="fixed-table-container-inner">
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 style="font-size: 130%;">
					<thead>			
						<tr class="cabeceraTabla">
							<th width="5%"><div class="th-inner">FECHA
							<a href="<?php echo $_SERVER['PHP_SELF'];?>?a=<?php echo $data;?>&ordenado=facturas.fecha&orden=<?php echo $neworden;?>">
							<?php if($orden=="ASC," and $ordenado=="facturas.fecha") { ?>
							<i class="fa fa-caret-down" aria-hidden="true"></i>
							<?php } else { ?>
							<i class="fa fa-caret-up" aria-hidden="true"></i>
							<?php } ?>
							</a></div></th>
							<th width="5%"><div class="th-inner">Nº&nbsp;
							<a href="<?php echo $_SERVER['PHP_SELF'];?>?a=<?php echo $data;?>&ordenado=facturas.codfactura&orden=<?php echo $neworden;?>">
							<?php if($orden=="ASC," and $ordenado=="facturas.codfactura") { ?>
							<i class="fa fa-caret-down" aria-hidden="true"></i>
							<?php } else { ?>
							<i class="fa fa-caret-up" aria-hidden="true"></i>
							<?php } ?>
							</a></div></th>
							<th width="8%"><div class="th-inner">Tipo
							<a href="<?php echo $_SERVER['PHP_SELF'];?>?a=<?php echo $data;?>&ordenado=facturas.tipo&orden=<?php echo $neworden;?>">
							<?php if($orden=="ASC," and $ordenado=="facturas.tipo") { ?>
							<i class="fa fa-caret-down" aria-hidden="true"></i>
							<?php } else { ?>
							<i class="fa fa-caret-up" aria-hidden="true"></i>
							<?php } ?>
							</a></div></th>
							<th width="48%" align="left"><div class="th-inner">&nbsp;CLIENTE
							<a href="<?php echo $_SERVER['PHP_SELF'];?>?a=<?php echo $data;?>&ordenado=facturas.codcliente&orden=<?php echo $neworden;?>">
							<?php if($orden=="ASC," and $ordenado=="facturas.codcliente") { ?>
							<i class="fa fa-caret-down" aria-hidden="true"></i>
							<?php } else { ?>
							<i class="fa fa-caret-up" aria-hidden="true"></i>
							<?php } ?>
							</a></div> </th>							
							<th width="8%"><div class="th-inner">MON.
							<a href="<?php echo $_SERVER['PHP_SELF'];?>?a=<?php echo $data;?>&ordenado=facturas.moneda&orden=<?php echo $neworden;?>">
							<?php if($orden=="ASC," and $ordenado=="facturas.moneda") { ?>
							<i class="fa fa-caret-down" aria-hidden="true"></i>
							<?php } else { ?>
							<i class="fa fa-caret-up" aria-hidden="true"></i>
							<?php } ?>
							</a>							</div></th>
							<th width="8%"><div class="th-inner">IMP.
							<a href="<?php echo $_SERVER['PHP_SELF'];?>?a=<?php echo $data;?>&ordenado=facturas.totalfactura&orden=<?php echo $neworden;?>">
							<?php if($orden=="ASC," and $ordenado=="facturas.totalfactura") { ?>
							<i class="fa fa-caret-down" aria-hidden="true"></i>
							<?php } else { ?>
							<i class="fa fa-caret-up" aria-hidden="true"></i>
							<?php } ?>
							</a>														
							</div></th>
							<th width="5%"><div class="th-inner">ESTADO
							<a href="<?php echo $_SERVER['PHP_SELF'];?>?a=<?php echo $data;?>&ordenado=facturas.estado&orden=<?php echo $neworden;?>">
							<?php if($orden=="ASC," and $ordenado=="facturas.estado") { ?>
							<i class="fa fa-caret-down" aria-hidden="true"></i>
							<?php } else { ?>
							<i class="fa fa-caret-up" aria-hidden="true"></i>
							<?php } ?>
							</a></div></th>
							<th colspan="4" ><div class="th-inner">ACCIÓN</div></th>
						</tr>
					</thead>
					<tbody>
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas;?>">
			<input type="hidden" name="numfilasN" id="numfilasN" value="<?php echo $filasN;?>">
				<?php //$iniciopagina=$_POST["iniciopagina"];
				
						$tipo = array( 0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
						$moneda = array();

				if ($iniciopagina=='') { $iniciopagina=@$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if ($iniciopagina=='') { $iniciopagina=0; }
				if ($iniciopagina>($filas+$filasN)) { $iniciopagina=0; }
					if (($filas + $filasN) > 0) { ?>
						<?php						
							$leer=verificopermisos('ventas', 'leer', $UserID);
							$escribir=verificopermisos('ventas', 'escribir', $UserID);
							$modificar=verificopermisos('ventas', 'modificar', $UserID);
							$eliminar=verificopermisos('ventas', 'eliminar', $UserID);

							/*Genero un array con los simbolos de las monedas*/
							$sel_monedas="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
						   $res_monedas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_monedas);
						   $con_monedas=0;
							$xmon=1;
						 while ($con_monedas < mysqli_num_rows($res_monedas)) {
						 	$descripcion=explode(" ", mysqli_result($res_monedas, $con_monedas, "simbolo"));
						 	 $moneda[$xmon]= $descripcion[0];
						 	 $con_monedas++;
						 	 $xmon++;
						 }	
												
						if($codarticulo=="") {													
						 $sel_resultado="SELECT facturas.codfactura as facturacod,codfactura,clientes.nombre as nombre,facturas.fecha,totalfactura,estado,facturas.tipo,facturas.moneda,facturas.emitida,facturas.enviada,
						clientes.empresa,clientes.apellido
						 FROM facturas,clientes WHERE facturas.borrado=0 AND facturas.codcliente=clientes.codcliente AND ".$where;
						} else {
						$sel_resultado="SELECT clientes.nombre as nombre,facturas.codfactura as facturacod,facturas.fecha,totalfactura,estado,facturas.tipo, facturas.moneda,facturas.emitida,facturas.enviada,
						 clientes.empresa,clientes.apellido FROM facturas LEFT JOIN factulinea ON facturas.codfactura = factulinea.codfactura 
						LEFT JOIN clientes on clientes.codcliente=facturas.codcliente WHERE facturas.codcliente=clientes.codcliente AND facturas.borrado=0 AND ".$where;							
													
						}
						 
						   $sel_resultado=$sel_resultado." limit ".$iniciopagina.",". $paginacion;
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado); 

    if (mysqli_errno($GLOBALS["___mysqli_ston"])) {
        echo 'error' . mysqli_error($GLOBALS["___mysqli_ston"]);
        die();
    }						   
						   
						   $contador=0;
						   $marcaestado=0;						   
						   while ($contador < mysqli_num_rows($res_resultado)) { 
								$marcaestado=mysqli_result($res_resultado, $contador, "estado");
								if (mysqli_result($res_resultado, $contador, "emitida")==0) { $estado="Sin emitir"; } else {
								if (mysqli_result($res_resultado, $contador, "estado") == 1 and mysqli_result($res_resultado, $contador, "tipo") != 0) 
								{ $estado="Por&nbsp;cobrar"; } elseif(mysqli_result($res_resultado, $contador, "estado") == 2) { $estado="Cobrada"; } 
								else {  $estado="Parcial";}
								} 
								if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }
								$tipoc=$tipo[mysqli_result($res_resultado, $contador, "tipo")];
						?>
								
						<tr id="<?php echo mysqli_result($res_resultado, $contador, "facturacod");?>"	 class="<?php echo $fondolinea?> trigger">
							<td class="aDerecha" width="10%"><div align="center"><?php echo implota(mysqli_result($res_resultado, $contador, "fecha"));?></div></td>	
							<td width="5%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "facturacod");?></div></td>
							<td width="8%"><div align="center"><?php echo $tipoc;?></div></td>
							<?php if (mysqli_result($res_resultado, $contador, "empresa")!='') {?>
							<td width="45%" ><div align="left"><?php echo mysqli_result($res_resultado, $contador, "empresa")?></div></td>
							<?php } elseif (mysqli_result($res_resultado, $contador, "apellido")=='') {?>
							<td width="45%"><div align="left"><?php echo mysqli_result($res_resultado, $contador, "nombre")?></div></td>
							<?php } else { ?>
							<td width="45%"><div align="left"><?php echo mysqli_result($res_resultado, $contador, "nombre")?>
							 <?php echo mysqli_result($res_resultado, $contador, "apellido")?></div></td>
							<?php } ?>

							<td width="7%"><div align="center"><?php echo $moneda[mysqli_result($res_resultado, $contador, "moneda")];?></div></td>
							<td width="7%"><div align="right"><?php echo number_format(mysqli_result($res_resultado, $contador, "totalfactura"),2,".",".");?></div></td>
							<td class="aDerecha" width="17%"><div align="center"><?php echo $estado;?></div></td>
							<?php if ( $modificar=="true") { ?>								
							<td ><div align="center"><a href="#"><img id="botonBusqueda" src="../img/modificar.png" width="16" height="16" border="0" onClick="modificar_factura('<?php echo mysqli_result($res_resultado, $contador, "facturacod");?>','<?php echo $marcaestado;?>','<?php echo mysqli_result($res_resultado, $contador, "emitida");?>');" title="Modificar"></a></div></td>
							<?php } else { ?>
							<td width="20px">&nbsp;</td>
							<?php } if ( $leer=="true") { ?>								
							<td ><div align="center"><a href="#"><img id="botonBusqueda" src="../img/ver.png" width="16" height="16" border="0" onClick="ver_factura('<?php echo mysqli_result($res_resultado, $contador, "facturacod");?>');" title="Visualizar"></a></div></td>
							<?php } else { ?>
							<td width="20px">&nbsp;</td>
							<?php }
							if (mysqli_result($res_resultado, $contador, "enviada")!=1) { ?>								
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/sobre.png" width="16" height="16" border="0" onClick="enviar_factura('<?php echo mysqli_result($res_resultado, $contador, "facturacod");?>','0');" title="Enviar al cliente"></a></div></td>
							<?php } else { ?>
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/sobreok.png" width="16" height="16" border="0" onClick="enviar_factura('<?php echo mysqli_result($res_resultado, $contador, "facturacod");?>','1');" title="Enviado al cliente"></a></div></td>
							<?php }
							 if ( $eliminar=="true") { ?>
							<td ><div align="right"><a href="#"><img id="botonBusqueda" src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar_factura('<?php echo mysqli_result($res_resultado, $contador, "facturacod");?>','<?php echo mysqli_result($res_resultado, $contador, "emitida");?>');" title="Eliminar"></a></div></td>
							<?php } else { ?>
							<td width="20px">&nbsp;</td>
							<?php } ?>	
						</tr>						
						<?php
						 $contador++;
							}
						?>			
					<?php } else { ?>
						<tr>
							<td width="100%" class="mensaje" colspan="12"><?php echo "No hay ninguna factura que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
				
					<?php } ?>					
					</table>	
				</div>
			</div>
		  </div>			
		</div></div>
	</body>
</html>
<?php
((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
?>