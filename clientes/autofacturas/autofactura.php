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
 
include (realpath(dirname(__FILE__))."/../../conectar.php"); 
include (realpath(dirname(__FILE__))."/../../funciones/fechas.php"); 

date_default_timezone_set("America/Montevideo"); 
setlocale(LC_ALL,"es-ES"); 

function weekOfMonth($qDate) {
    $dt = strtotime($qDate);
    $day  = date('j',$dt);
    $month = date('m',$dt);
    $year = date('Y',$dt);
    $totalDays = date('t',$dt);
    $weekCnt = 1;
    $retWeek = 0;
    for($i=1;$i<=$totalDays;$i++) {
        $curDay = date("N", mktime(0,0,0,$month,$i,$year));
        if($curDay==6) {
            if($i==$day) {
                $retWeek = $weekCnt+1;
            }
            $weekCnt++;
        } else {
            if($i==$day) {
                $retWeek = $weekCnt;
            }
        }
    }
    return $retWeek;
}

$dia=(int)date("w"); //El Día de la semana lo comienzo en Domingo.
$semana = (int)weekOfMonth(date("Y-m-d"));
$tipof = array(1=>"Solo registrar", 2=>"Registrar y emitir", 3=>"Registrar y enviar", 4=>"Registrar, emitir y enviar");
//echo "inicio<br>";
if(@$_GET['codautofactura']=='') {
/*Para el día de hoy verifico si existe alguna autofactura para hacer*/
 $sql="SELECT * FROM autofacturas WHERE diafacturacion=".$dia." AND semanafacturacion=".$semana. " AND activa=1";
echo "<p>Para el día de hoy verifico si existe alguna autofactura para hacer<p>";
} else {
$sql="SELECT * FROM autofacturas WHERE codautofactura=".$_GET['codautofactura']. " AND activa=1";
echo "<p>Emito manualmente la autofactura<p>";
}


$consulta=mysqli_query($GLOBALS["___mysqli_ston"], $sql);
if(mysqli_num_rows($consulta)>0) {

	$count=0;
	while($count<mysqli_num_rows($consulta)) {
	$codautofactura=mysqli_result($consulta,  $count,  "codautofactura");
	$emitida=mysqli_result($consulta, $count, "emitida");
	/*Compruebo que no lo he facturado previamente*/
//echo 	"/*Compruebo que no lo he facturado previamente*/<p>";

$anio=(int)date("Y");
$mes=(int)date("m");
$fechahoy=date("Y-m-d");

 $sql_facturado = "SELECT * FROM `historialautofactura` WHERE `codautofactura` = ".$codautofactura ." AND `diafacturacion` = ".$dia." AND `semanafacturacion` = ".$semana. "  AND `anio` = '".$anio."' AND `mes`='".$mes."'";

	$consulta_facturado=mysqli_query($GLOBALS["___mysqli_ston"], $sql_facturado);
	
	if(mysqli_num_rows($consulta_facturado)==0) {
		/*Genero la nueva factura con los datos de la autofactura*/		
		/*Tomo los datos de la autofactura*/		
		$sel_alb="SELECT * FROM autofacturas WHERE codautofactura='$codautofactura'";
		$rs_alb=mysqli_query($GLOBALS["___mysqli_ston"], $sel_alb);
		
		$codcliente=mysqli_result($rs_alb, 0, "codcliente");
		$tipo=mysqli_result($rs_alb, 0, "tipo");
		$moneda=mysqli_result($rs_alb, 0, "moneda");
		$tipocambio=mysqli_result($rs_alb, 0, "tipocambio");
		$iva=mysqli_result($rs_alb, 0, "iva");
		$fecha=mysqli_result($rs_alb, 0, "fecha");
		$codformapago=mysqli_result($rs_alb, 0, "codformapago");
		$observacion=mysqli_result($rs_alb, 0, "observacion");
		$descuentogral=mysqli_result($rs_alb, 0, "descuento");
		
		$sel_fact="SELECT codfactura FROM `facturas` ORDER BY codfactura DESC, fecha DESC LIMIT 1 ";
		$rs_fact=mysqli_query($GLOBALS["___mysqli_ston"], $sel_fact);
		$codfactura=(int)mysqli_result($rs_fact, 0, "codfactura")+1;
	
		$query_operacion="INSERT INTO facturas (codfactura, tipo, fecha, iva, codcliente, estado, moneda, tipocambio, descuento, observacion, codformapago, borrado) 
		VALUES ('$codfactura', '$tipo', '$fechahoy', '$iva', '$codcliente', '1', '$moneda', '$tipocambio', '$descuentogral', '$observacion', '$codformapago', '0')";					
		$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
		$sel_lineas="SELECT * FROM autofactulinea WHERE codautofactura='$codautofactura' ORDER BY numlinea ASC";
		$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
		$contador=0;
		$baseimponible=0;		
		/*Recorro todas las líneas de la autofactura*/
		while ($contador < mysqli_num_rows($rs_lineas)) {
			$codfamilia=mysqli_result($rs_lineas, $contador, "codfamilia");
			$numlinea=mysqli_result($rs_lineas, $contador, "numlinea");			
			$codigolinea=mysqli_result($rs_lineas, $contador, "codigo");
			$codservice=mysqli_result($rs_lineas, $contador, "codservice");
			$cantidad=mysqli_result($rs_lineas, $contador, "cantidad");
			$detalles=mysqli_result($rs_lineas, $contador, "detalles");
			$precio=mysqli_result($rs_lineas, $contador, "precio");
			$importe=mysqli_result($rs_lineas, $contador, "importe");
			$baseimponible=$baseimponible+$importe;
			$dcto=mysqli_result($rs_lineas, $contador, "dcto");
			$dctopp=mysqli_result($rs_lineas, $contador, "dctopp");
		
			$descuentopp=mysqli_result($rs_lineas, $contador, "dctopp");
			$comision=mysqli_result($rs_lineas, $contador, "comision");
			
			 $text = array("*mesvencido*", "*mesactual*"); 
			 $mesvencido = date("m", mktime(0, 0, 0, date("m")-1,date("d"),date("Y")));
			 $mesvencido=" - ".genMonth_Text($mesvencido).' '.date("Y", mktime(0, 0, 0, date("m")-1,date("d"),date("Y")));
			 $mesactual = date("m", mktime(0, 0, 0, date("m"),date("d"),date("Y")));
			 $mesactual=" - ".genMonth_Text($mesactual).' '.date("Y", mktime(0, 0, 0, date("m"),date("d"),date("Y")));

			 $replace = array($mesvencido, $mesactual);
			 $detalles = str_replace($text, $replace, $detalles); 
			if($codfactura!='' and $numlinea >0 and $codigolinea>0 and $detalles!='' and $cantidad>=0 and $importe>=0) {
			/*Grabo los valores en la tabla factulinea*/
		$sel_insertar="INSERT INTO factulinea (codfactura,numlinea,codfamilia,codigo,codservice,detalles,cantidad,moneda,precio,importe,dcto,dctopp,comision) VALUES 
			('$codfactura','$numlinea', '$codfamilia', '$codigolinea', '$codservice', '$detalles','$cantidad','$moneda','$precio','$importe','$dcto','$dctopp','$comision')";
			$rs_insertar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insertar);
			}
		//echo "<br>";
			/*Actualizo el stock*/
			$sel_articulos="UPDATE articulos SET stock=(stock-'$cantidad') WHERE codarticulo='$codigolinea' AND codfamilia='$codfamilia'";
			$rs_articulos=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulos);
	   	
			$contador++;
			}
			
		  	$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".$iva;
			$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
			$ivavalor=mysqli_result($res_iva, 0, "valor");
		
	  if ($descuentogral==0 or $descuentogral=='') {
	  $baseimpuestos=$baseimponible*($ivavalor/100);
     $preciototal=$baseimponible+$baseimpuestos;
	  } else {
	  $baseimponibledescuento=$baseimponible/(1-$descuentogral/100);
	  $baseimponible=$baseimponible*(1-$descuentogral/100);
	  $baseimpuestos=$baseimponible*($ivavalor/100);
     $preciototal=$baseimponible+$baseimpuestos;
	  }
	  			

		$sel_act="UPDATE facturas SET totalfactura='$preciototal' WHERE codfactura='$codfactura'";
		$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $sel_act);

		/*Actualizo el historial de las autofacturas*/
		$sql_historial="INSERT INTO `historialautofactura` (`codhistoria`, `codautofactura`, `diafacturacion`, `semanafacturacion`, `anio`, `mes`, `codfactura`)
 		VALUES (NULL, '$codautofactura', '$dia', '$semana', '$anio', '$mes', '$codfactura');";
 		$rs_historial=mysqli_query($GLOBALS["___mysqli_ston"], $sql_historial);
			
			/*Realizo la acción de Emitir y/o Enviar */
			$tipof = array(1=>"Solo registrar", 2=>"Registrar y emitir", 3=>"Registrar y enviar", 4=>"Registrar, emitir y enviar");

if($_GET['codautofactura']=='') {

			switch($emitida) {
				case 1:{
					echo "<br>Solo registrar";
				break;				
				}
				case 2:{
					echo "<br>Registrar y emitir";
					?>
					<script type="text/javascript" >
					var codfactura=<?php echo $codfactura;?>;
					var top = window.open("../../fpdf/imprimir_factura_mail.php?codfactura="+codfactura, "factura", "location=1,status=1,scrollbars=1");
					</script>
					<?php
					echo $tipof[2];
				break;
				}
				case 3:{
					echo "<br>Registrar y enviar";
					?>
					<script type="text/javascript" >
					var codfactura=<?php echo $codfactura;?>;
					windowObjectReference = window.open('../../enviomail/envia.php',"EnvioMail", "resizable,scrollbars,status, width=300,height=110,right=300,top=200");
					setTimeout(function() {windowObjectReference.location.href="../../fpdf/imprimir_factura_mail.php?codfactura="+codfactura+"&envio=1"}, 1000);
					</script>
					<?php
					echo $tipof[3];
				break;				
				}
				case 4: {
					echo "<br>Registrer, emitir y enviar";
					?>
					<script type="text/javascript" >
					var codfactura=<?php echo $codfactura;?>;
					var top = window.open("../../fpdf/imprimir_factura_mail.php?codfactura="+codfactura, "factura", "location=1,status=1,scrollbars=1");
					
					windowObjectReference = window.open('../../enviomail/envia.php',"EnvioMail", "resizable,scrollbars,status, width=300,height=110,right=300,top=200");
					setTimeout(function() {windowObjectReference.location.href="../../fpdf/imprimir_factura_mail.php?codautofactura="+codfactura+"&envio=1"}, 1000);
					</script>
					<?php
					
					echo $tipof[4];
				break;
				}
			}
		} else {
			echo "<script>parent.$('idOfDomElement').colorbox.close();>/script>";
		}
		}	
	$count++;	
	}
} else {
	echo "<br>Nada que facturar";
}
echo "<script>parent.$('idOfDomElement').colorbox.close();</script>";
?>