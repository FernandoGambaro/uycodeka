<?php
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
include("../common/funcionesvarias.php");
date_default_timezone_set("America/Montevideo"); 
//header('Content-Type: text/html; charset=UTF-8'); 

include ("../funciones/fechas.php");

$hoy=date("d/m/Y");

if (@$_POST["accion"]=="") { $accion=@$_GET["accion"]; } else { $accion=@$_POST["accion"]; }

if ($accion=="ver") {
	$codfactura=@$_GET["codfactura"];
}

if ($accion=="insertar") {
	$importe=$_POST["Rimporte"];
	$codcliente=$_POST["codcliente"];
	$codfactura=$_POST["codfactura"];
	$formapago=$_POST["AcboFP"];
	$numdocumento=$_POST["anumdocumento"];
	$observaciones=$_POST["observaciones"];
	$tipocambio=$_POST['tipocambio'];
	$resguardo=$_POST['resguardo'];
	$moneda=$_POST['Amoneda'];
	/*/$estado=$_POST["cboEstados"];*/
	$fechacobro=$_POST["fechacobro"];
	if ($fechacobro<>"") { $fechacobro=explota($fechacobro); }
	$sel_insertar="INSERT INTO cobros (id,codfactura,codcliente,importe,moneda,codformapago,numdocumento,fechacobro,observaciones,resguardo,tipocambio) VALUES 
('null','$codfactura','$codcliente','$importe','$moneda','$formapago','$numdocumento','$fechacobro','$observaciones','$resguardo','$tipocambio')";
	$rs_insertar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insertar);
	
	/*/1 compra*/
	/*/2 venta*/
	
	$sel_libro="INSERT INTO librodiario (id,fecha,tipodocumento,coddocumento,codcomercial,codformapago,numpago,moneda,total) VALUES 
	('','$fechacobro','2','$codfactura','$codcliente','$formapago','$numdocumento', '$moneda','$importe')";
	$rs_libro=mysqli_query($GLOBALS["___mysqli_ston"], $sel_libro);
	
	?>
	<script>
	parent.document.getElementById("observaciones").value="";
	parent.document.getElementById("Rimporte").value="";
	parent.document.getElementById("anumdocumento").value="";
	parent.document.getElementById("AcboFP").value="";
	parent.document.getElementById("fechacobro").value="<?php echo $hoy?>";
	var importe=<?php echo $importe?>;
	var total=parent.document.getElementById("pendiente").value - parseFloat(importe);
	var original=parseFloat(total);
	var result=Math.round(original*100)/100 ;
	parent.document.getElementById("pendiente").value=result;
	</script><?php
}

$query_busqueda="SELECT count(*) as filas FROM cobros,clientes WHERE cobros.codcliente=clientes.codcliente AND cobros.codfactura='$codfactura' order BY id DESC";
$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
$filas=mysqli_result($rs_busqueda, 0, "filas");

?>
<html>
	<head>
		<title>Clientes</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">

		<script language="javascript">

		function abreVentana(observaciones){
			//miPopup = window.open("ver_observaciones.php?observaciones="+observaciones,"miwin","width=380,height=240,scrollbars=yes");
			//miPopup.focus();
			var url="ver_observaciones.php?observaciones="+observaciones;
			window.parent.OpenMiniNote(url);
			
		}
		
		function eliminar(codfactura,idmov,fechacobro,moneda,importe){	
			miPopup = window.open("eliminar.php?codfactura="+codfactura+"&idmov="+idmov+"&fechacobro="+fechacobro+"&moneda="+moneda+"&importe="+importe,"frame_datos","width=380,height=240,scrollbars=yes");
			history.go(0);
			document.getElementById('frame_cobros').src = document.getElementById('frame_cobros').src;
		}
		

		</script>
	</head>
	<body style="margin:0;">	
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
			  <div class="header">LISTADO de COBROS </div>
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="10%">ITEM</td>
							<td width="12%">FECHA</td>
							<td width="12%">Moneda </td>							
							<td width="12%">IMPORTE </td>							
							<td width="20%">FORMA PAGO</td>
							<td width="20%">N. DOCUMENTO</td>
							<td width="15%">FECHA VTO.</td>
							<td>OBV.</td>
							<td>&nbsp;</td>
						</tr>
			<form name="form1" id="form1">					
				<?php	if ($filas > 0) { ?>
						<?php $sel_resultado="SELECT *,cobros.moneda as Mon FROM facturas,cobros,clientes,formapago WHERE cobros.codfactura='$codfactura' AND 
						cobros.codfactura=facturas.codfactura AND cobros.codcliente=clientes.codcliente AND cobros.codformapago=formapago.codformapago ORDER BY cobros.id DESC";
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;	
   						$moneda = array(1=>"\$", 2=>"U\$S");
						   while ($contador < mysqli_num_rows($res_resultado)) { 
								if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>
						<tr class="<?php echo $fondolinea?>">
							<td class="aCentro" width="10%"><?php echo $contador+1;?></td>
							<td width="12%"><div align="center"><?php echo implota(mysqli_result($res_resultado, $contador, "fechacobro"))?></div></td>
							<td width="7%"><div align="center"><?php echo $moneda[mysqli_result($res_resultado, $contador, "Mon")];?></div></td>
							<td width="12%"><div align="center"><?php echo number_format(mysqli_result($res_resultado, $contador, "importe"),2,",",".")?></div></td>							
							<td width="20%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "nombrefp")?></div></td>
							<td class="aDerecha" width="20%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "numdocumento");
							if (mysqli_result($res_resultado, $contador, "resguardo") >0 ){
								echo "&nbsp;Resguardo";
								}
								?>
							</div></td>
							<td class="aDerecha" width="15%"><div align="center"><?php echo implota(mysqli_result($res_resultado, $contador, "fechavencimiento"))?></div></td>
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/observaciones.png" width="16" height="16" border="0"
							 onClick="abreVentana('<?php echo mysqli_result($res_resultado, $contador, "observaciones")?>')" title="Ver Observaciones"></a></div></td>
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/eliminar.png" width="16" height="16" border="0"
							 onClick="eliminar('<?php echo mysqli_result($res_resultado, $contador, "codfactura")?>',<?php echo mysqli_result($res_resultado, $contador, "id")?>,<?php echo mysqli_result($res_resultado, $contador, "fechacobro")?>,<?php echo mysqli_result($res_resultado, $contador, "moneda")?>,<?php echo mysqli_result($res_resultado, $contador, "importe")?>)" title="Eliminar"></a></div></td>
						</tr>
						<?php $contador++;
							}
						?>			
					</table>
					<?php } else { ?>
					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" class="mensaje"><?php echo "Todav&iacute;a no se ha producido ning&uacute;n cobro de esta factura.";?></td>
					    </tr>
					</table>					
					<?php } ?>	
					</form>				
				</div>
				<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>
				</div>
		  </div>			
	</body>
</html>
