<?php

include ("../conectar.php");
include ("../funciones/fechas.php");
$busqueda='';
$SumaTotalVentas='';
$SumaTotalCobros='';
$codcliente=$_POST["codcliente"];
$moneda=(int)$_POST["amoneda"];

$tiporpt = $_POST['tiporpt'];

$deudaanterior='';

$whereCliente="1=1";
if ($codcliente <> "") { $whereCliente.=" AND codcliente='$codcliente'"; }


$whereFactura="1=1";
$whereMoneda="";
$fecharecibos="";

if ($codcliente <> "") { $whereFactura.=" AND facturas.codcliente='$codcliente'"; }
if ($moneda <> "") { $whereFactura.=" AND moneda='$moneda'"; }
if($tiporpt==1) {
	$fechainicio = explota($_POST['fechainicio']);
	$fechafin = explota($_POST['fechafin']);
	$deudaanterior="AND fecha < '". $fechafin . "'";
	$busqueda=implota($fechainicio)." a ".implota($fechafin);
	if (($fechainicio<>"") and ($fechafin<>"")) {
		$whereFactura.=" AND fecha between '".$fechainicio."' AND '".$fechafin."'";
	} else {
		if ($fechainicio<>"") {
			$whereFactura.=" AND fecha>='".$fechainicio."'";
		} else {
			if ($fechafin<>"") {
				$whereFactura.=" AND fecha<='".$fechafin."'";
			}
		}
	}
} else {
	$fechainicio = explota($_POST['fechainicio']);
	$fechafin = explota($_POST['fechafin']);	
	$deudaanterior=" AND fecha <= '". $fechafin. "'";
	$whereFactura.=" AND fecha <='".$fechafin."'";
}
//$whereFactura.=" ORDER BY codcliente"; 
 
$whereCobros=" 1=1";
if ($codcliente <> "") { $whereCobros.=" AND cobros.codcliente='$codcliente'"; }
if ($moneda <> "") { $whereCobros.=" AND moneda='$moneda'"; }

if($tiporpt==1) {
	$fechainicio = explota($_POST['fechainicio']);
	$fechafin = explota($_POST['fechafin']);
	$fecharecibos=" AND fechacobro < '". $fechafin . "'";
	if (($fechainicio<>"") and ($fechafin<>"")) {
		$whereCobros.=" AND fechacobro between '".$fechainicio."' AND '".$fechafin."'";
	} else {
		if ($fechainicio<>"") {
			$whereCobros.=" AND fechacobro>='".$fechainicio."'";
		} else {
			if ($fechafin<>"") {
				$whereCobros.=" AND fechacobro<='".$fechafin."'";
			}
		}
	}
} else {
	$fechainicio = explota($_POST['fechainicio']);
	$fechafin = explota($_POST['fechafin']);
	$fecharecibos=" AND fechacobro <= '". $fechafin. "'";
	$whereCobros.=" AND fechacobro <='".$fechafin."'";
}

if($tiporpt==1) {
	$Reporte="Deudores por ventas";
}
if($tiporpt==2) {
	$Reporte="Estado de cuenta";	
}

if ($moneda <> "" and $moneda !=0) { $whereMoneda.=" AND moneda='$moneda'"; }


$startTime =data_first_month_day(explota($_POST['fechainicio'])); 
$endTime = data_last_month_day(explota($_POST['fechafin'])); 
$sTime=$startTime;

//$whereCobros.=" ORDER BY codcliente";  

	$tipo = array( 0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
	$tipomoneda = array(0=>"Ambas", 1=>"\$", 2=>"U\$S");

$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$razonsocial=mysqli_result($rs_datos, 0, "razonsocial");
$direccion=mysqli_result($rs_datos, 0, "direccion");
$telefono=mysqli_result($rs_datos, 0, "telefono1");
$fax=mysqli_result($rs_datos, 0, "fax");
$email=mysqli_result($rs_datos, 0, "mailv");
$web=mysqli_result($rs_datos, 0, "web");

 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title></title>
	<meta name="generator" content="Bluefish 2.2.10" >
	<meta name="created" content="20140722;2825872280191">
	<meta name="changed" content="20140722;231034824935761">
	
	<style type="text/css"><!-- 
		body,div,table,thead,tbody,tfoot,tr,th,td,p { font-family:"Liberation Sans"; font-size:x-small }
		 -->
	</style>
	
</head>

<body text="#000000">
<table cellspacing="0" border="0">
	<colgroup span="2" width="38"></colgroup>
	<colgroup width="270"></colgroup>
	<colgroup width="27"></colgroup>
	<colgroup width="91"></colgroup>
	<colgroup width="2"></colgroup>
	<colgroup width="94"></colgroup>
	<colgroup width="2"></colgroup>
	<colgroup width="87"></colgroup>
	<tr>
		<td colspan=3 height="22" align="left" valign=middle><b><i><font face="Tahoma" size=3></font></i></b></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td colspan=5 align="right" valign=middle><b><font face="Tahoma" size=3><?php echo $razonsocial;?></font></b></td>
		</tr>
	<tr>
		<td height="17" align="left"><font face="Tahoma"><br></font></td>
		<td colspan="2" align="left"><font face="Tahoma" size=2><?php echo $Reporte;?></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td colspan=5 align="right" valign=middle><font face="Tahoma"><?php echo $direccion;?></font></td>
		</tr>
	<tr>
		<td height="17" align="center" valign=middle><font face="Tahoma"><br></font></td>
		<td colspan=2 align="left" valign=middle><font face="Tahoma">
 <?php echo $busqueda;?>
		
		</font></td>
		<td colspan=2 align="left" valign=middle><font face="Tahoma">Moneda <?php echo $tipomoneda[$moneda] ;?></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
	</tr>
	<tr style="line-height:5px">
		<td height="6" align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
	</tr>
	<?php 
	/*Fin de cabezal sin titulos */
	?>
	<tr>
		<td height="17" align="left"><font face="Tahoma">Fecha</font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma">Concepto</font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="right"><font face="Tahoma">Debe</font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="right"><font face="Tahoma">Haber</font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="right"><font face="Tahoma">Saldo</font></td>
	</tr>
	<tr style="line-height:5px">
		<td height="6" align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
	</tr>
	<tr style="line-height:5px">
		<td height="6" align="left"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left"><font face="Tahoma"><br style="line-height:5px"></font></td>
	</tr>
	<?php



	/*Recorro todos los clientes y verifico si tienen factura o cobro dentro de las fechas especificas*/
	$sel_cliente="SELECT * FROM clientes  WHERE ".$whereCliente." order by codcliente ASC";
	$res_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cliente);
	$con_cliente=0;
	$clientecondeuda=array();
	
	while($con_cliente < mysqli_num_rows($res_cliente)) { 

	
	$codcliente=mysqli_result($res_cliente, $con_cliente, "codcliente");
	if($tiporpt==2) {
			$sel_factura="SELECT * FROM facturas WHERE facturas.tipo=1 AND facturas.borrado=0 AND codcliente =". $codcliente. " AND moneda='1' order by fecha ASC";            
			$res_factura= mysqli_query($GLOBALS["___mysqli_ston"], $sel_factura);
			$num_factura= mysqli_num_rows($res_factura);
			$fechainicio=$startTime=data_first_month_day(mysqli_result($res_factura, 0, "fecha"));
			$sTime=$startTime;
			$clientecondeuda[$codcliente]=$num_factura;
	} else {
		/*Chequeo que tenga algún saldo*/	
			
		if($moneda==1) {
			$sel_factura="SELECT sum(totalfactura) as factura FROM facturas WHERE facturas.tipo=1 AND facturas.borrado=0 AND codcliente =". $codcliente. " AND moneda='1'".$deudaanterior;            
			$res_factura= mysqli_query($GLOBALS["___mysqli_ston"], $sel_factura);
			$num_factura= mysqli_num_rows($res_factura);
			$factura=mysqli_result($res_factura, 0, "factura");
	
			$sel_nota="SELECT sum(totalfactura) as nota FROM facturas WHERE facturas.tipo=2 AND facturas.borrado=0 AND codcliente =". $codcliente. " AND moneda='1' ".$deudaanterior;
			$res_nota= mysqli_query($GLOBALS["___mysqli_ston"], $sel_nota);
			$num_nota= mysqli_num_rows($res_nota);
			$nota=mysqli_result($res_nota, 0, "nota");
			
			$sel_Cobros="SELECT sum(importe) as recibo FROM cobros WHERE codcliente =". $codcliente. " AND moneda='1' ".$fecharecibos;
			$res_Cobros= mysqli_query($GLOBALS["___mysqli_ston"], $sel_Cobros);
			$num_Cobros= mysqli_num_rows($res_Cobros);
			$recibo=mysqli_result($res_Cobros, 0, "recibo");
			
			$resultadopesos=$factura-$nota-$recibo;
	
			if (round($resultadopesos) > 1) {
				$clientecondeuda[$codcliente]=$resultadopesos;
			}
		}
		if($moneda==2) {
			$sel_factura="SELECT sum(totalfactura) as factura FROM facturas WHERE facturas.tipo=1 AND facturas.borrado=0 AND codcliente =". $codcliente. " AND moneda='2' ".$deudaanterior;      
			$res_factura= mysqli_query($GLOBALS["___mysqli_ston"], $sel_factura);
			$num_factura= mysqli_num_rows($res_factura);
			$factura=mysqli_result($res_factura, 0, "factura");
	
			$sel_nota="SELECT sum(totalfactura) as nota FROM facturas WHERE facturas.tipo=2 AND facturas.borrado=0 AND codcliente =". $codcliente. " AND moneda='2' ".$deudaanterior;
			$res_nota= mysqli_query($GLOBALS["___mysqli_ston"], $sel_nota);
			$num_nota= mysqli_num_rows($res_nota);
			$nota=mysqli_result($res_nota, 0, "nota");
	
			
			$sel_Cobros="SELECT sum(importe) as recibo FROM cobros WHERE codcliente =". $codcliente. " AND moneda='2' ". $fecharecibos;
			$res_Cobros= mysqli_query($GLOBALS["___mysqli_ston"], $sel_Cobros);
			$num_Cobros= mysqli_num_rows($res_Cobros);
			$recibo=mysqli_result($res_Cobros, 0, "recibo");
			
			$resultadodolares=$factura-$nota-$recibo;
	/*Fin de cálculo */	
	
			if (round($resultadodolares) > 1) {
				$clientecondeuda[$codcliente]=$resultadodolares;
			}
		}
	}	
	$con_cliente++;	
	}


foreach($clientecondeuda as $codcliente => $deuda){

	$totalVentas=0;
	$totalCobros=0;
	$num_Cobros=0;	
	$num_factura=0;
				
	$sel_cliente="SELECT * FROM clientes  WHERE codcliente=".$codcliente;
	$res_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cliente);
		
			
			if (mysqli_result($res_cliente, 0, "empresa")!='') {
					$nombre= mysqli_result($res_cliente, 0, "empresa");
					} elseif (mysqli_result($res_cliente, 0, "apellido")=='') {
						$nombre= mysqli_result($res_cliente, 0, "nombre");
					} else {
						$nombre= mysqli_result($res_cliente, 0, "nombre"). ' ' . mysqli_result($res_cliente, 0, "apellido");
					}

$SaldoParcial=0;
			
/*Calculo el saldo anterior*/
		$SaldoTotal_Ventas=0;
		$SaldoTotal_Cobros=0;
		$sel_resultado="SELECT codcliente,fecha,totalfactura,codfactura,moneda,tipo FROM facturas WHERE facturas.tipo >= 1 AND facturas.borrado=0 AND codcliente='". $codcliente."' ".$whereMoneda." 
		AND fecha < '". $fechainicio. "' ";
		$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		$contador=0;
		$total=0;
	while ($contador < mysqli_num_rows($res_resultado)) { 
	
		 $total= mysqli_result($res_resultado, $contador, "totalfactura");
		 
	if ($moneda==0){
		 if (mysqli_result($res_resultado, $contador, "moneda")!=2){
			 	if(mysqli_result($res_resultado, $contador, "tipo")==1) {
			 $SaldoTotal_Ventas+=$total;
			 	} else {
			 $SaldoTotal_Ventas-=$total;
			 	}
		 } else {
			$fechaTipoCambio=date ("Y-m-d", strtotime("-1 day", strtotime(mysqli_result($res_resultado, $contador, "fecha"))));
   		$sel_tipocambio="SELECT valor FROM tipocambio WHERE fecha <='".$fechaTipoCambio."'";
   		$res_tipocambio=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tipocambio);
   		while ($row=mysqli_fetch_array($res_tipocambio)) {
   			$tipocambio=$row['valor'];
   		} 	
			 	if(mysqli_result($res_resultado, $contador, "tipo")==1) {
			 $SaldoTotal_Ventas+=$total*$tipocambio;
			 	} else {
			 $SaldoTotal_Ventas-=$total*$tipocambio;
			 	}
		 }
		 $total=0;	
	} else {
			 	if(mysqli_result($res_resultado, $contador, "tipo")==1) {
				$SaldoTotal_Ventas+=$total;
			 	} else {
				$SaldoTotal_Ventas-=$total;
			 	}
	}
		 $contador++;
	}
	$contador=0;
	
/*Parte de recibos*/

			$sel_recibo="SELECT codcliente,fechacobro,importe,moneda,numdocumento FROM cobros WHERE codcliente=". $codcliente." ".$whereMoneda." AND fechacobro < '". $fechainicio ."'";
			$res_recibo=mysqli_query($GLOBALS["___mysqli_ston"], $sel_recibo);
		   $contadorrecibo=0;
			$total=0;		   	
	while ($contadorrecibo < mysqli_num_rows($res_recibo)) { 
		 $total= mysqli_result($res_recibo, $contadorrecibo, "importe");
	if ($moneda==0){
		 if (mysqli_result($res_recibo, $contadorrecibo, "moneda")!=2){
		 $SaldoTotal_Cobros+=$total;		 
		 } else {
			$fechaTipoCambio=date ("Y-m-d", strtotime("-1 day", strtotime(mysqli_result($res_recibo, $contadorrecibo, "fecha"))));
   		$sel_tipocambio="SELECT valor FROM tipocambio WHERE fecha <='".$fechaTipoCambio."'";
   		$res_tipocambio=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tipocambio);
   		while ($row=mysqli_fetch_array($res_tipocambio)) {
   			$tipocambio=$row['valor'];
   		}
		 $SaldoTotal_Cobros+=$total*$tipocambio;
		 }
		 $total=0;	
	} else {
	 $SaldoTotal_Cobros+=$total;		 
	}
	   $contadorrecibo++;
	}
	   $contadorrecibo=0;
			
			
			$totalVentas=$SaldoTotal_Ventas;
			$totalCobros=$SaldoTotal_Cobros;
			$SaldoTotal=$SaldoTotal_Ventas-$SaldoTotal_Cobros;

	?>
	<tr>
		<td align="center" valign=middle><font face="Tahoma"><b><?php echo strtoupper ($codcliente); ?></b></font></td>
		<td colspan=2 height="17" align="center" valign=middle><font face="Tahoma"><b>&nbsp;<?php echo strtoupper ($nombre); ?></b></font></td>
		<td align="center" valign=middle><font face="Tahoma">SALDO&nbsp;ANTERIOR</font></td>
		<td align="right" sdnum="3082;0;DD/MM/AA"><font face="Tahoma"><?php echo number_format($SaldoTotal_Ventas,2,",",".");?>&nbsp;</font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br></font></td>
		<td align="right"><font face="Tahoma"><?php echo number_format($SaldoTotal_Cobros,2,",",".");?>&nbsp;</font>
		&nbsp;</td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br></font></td>
		<td align="right"><font face="Tahoma"><?php echo number_format($SaldoTotal,2,",",".");?>&nbsp;</font></td>		
		</tr>

	
<?php

$SaldoParcial=$SaldoTotal_Ventas-$SaldoTotal_Cobros;

$Iva_Compras=0;
$Iva_Ventas=0;
$Cant_Ventas=0;
$Cant_Compras=0;	
$TCobros=0;	

$startTime=$fechafin;

while (strtotime($startTime) >= strtotime($fechainicio)) {

	$fechaTipoCambio=date ("Y-m-d", strtotime("-1 day", strtotime($startTime)));
	$sel_tipocambio="SELECT valor FROM tipocambio WHERE fecha <='".$fechaTipoCambio."'";
	$res_tipocambio=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tipocambio);
	while ($row=mysqli_fetch_array($res_tipocambio)) {
		$tipocambio=$row['valor'];
	} 

	$sel_resultado="SELECT codcliente,fecha,totalfactura,codfactura,moneda,tipo FROM facturas WHERE facturas.tipo>=1 AND facturas.borrado=0 AND codcliente='".$codcliente."' ".$whereMoneda." 
	AND fecha ='".$startTime."' ";
	

	$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
   $contador=0;
   $marcaestado=0;
		   	
	if (mysqli_num_rows($res_resultado)>0) {					   
		   while ($contador < mysqli_num_rows($res_resultado)) { 
				$tipoc=$tipo[mysqli_result($res_resultado, $contador, "tipo")];
	?>		
	<tr>
		<td colspan=2 height="17" align="left" valign=middle sdval="41852" sdnum="3082;0;DD/MM/AAAA"><font face="Tahoma"><?php echo implota($startTime);?></font></td>
		<td align="left" valign=middle><font face="Tahoma"><?php echo $tipoc."  Nº".mysqli_result($res_resultado, $contador, "codfactura");?></font></td>
		<td  align="left" valign=middle><font face="Tahoma"><br></font></td>
		
		<td align="right" sdval="123456789" sdnum="3082;0;0,00"><font face="Tahoma">
	<?php
		 
		$total= mysqli_result($res_resultado, $contador, "totalfactura");
		if ($moneda==0){
			 if (mysqli_result($res_resultado, $contador, "moneda")!=2){
			 $Total_Ventas=$total;		 
				$TVentas= number_format($total,2,",","."); 
			 } else {
			 $Total_Ventas=$total*$tipocambio;
				$TVentas= number_format($total*$tipocambio,2,",","."); 
			 }
		} else {
			if(mysqli_result($res_resultado, $contador, "tipo")==1) {
		 $SaldoTotal_Cobros+=$total;
		 } else {
		 $SaldoTotal_Cobros-=$total;
		 }		 
			 $Total_Ventas=$total;		 
				$TVentas= number_format($total,2,",","."); 
		}	
		 
		 $total=0;
		 
		echo $TVentas;
		if(mysqli_result($res_resultado, $contador, "tipo")==2) {
		$totalVentas=$totalVentas-$Total_Ventas;
		} else {
		$totalVentas=$totalVentas+$Total_Ventas;
		}
		$contador++;
		$Cant_Ventas++;

		?>		
		&nbsp;</font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br></font></td>
		<td align="right" sdval="123456789" sdnum="0;0;0,00"><font face="Tahoma"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br></font></td>
		<td align="right" sdval="12345679" sdnum="0;0;0,00"><font face="Tahoma">
		<?php
		if(mysqli_result($res_resultado, $contador, "tipo")==2) {		
				$SaldoParcial=-$SaldoParcial+$Total_Ventas;
				echo number_format($SaldoParcial,2,",",".");
		} else {
				$SaldoParcial=$SaldoParcial+$Total_Ventas;
				echo number_format($SaldoParcial,2,",",".");
		}
		?>&nbsp;
		</font></td>
		</tr>
		<?php
		}
	}
			
			
			$sel_recibo="SELECT codcliente,fechacobro,importe,moneda,numdocumento FROM cobros WHERE codcliente=". $codcliente . $whereMoneda. " AND fechacobro = '".$startTime."'";
		
			$res_recibo=mysqli_query($GLOBALS["___mysqli_ston"], $sel_recibo);
		   $contadorrecibo=0;
		   $marcaestadorecibo=0;	
	if (mysqli_num_rows($res_recibo)>0) {
		   while ($contadorrecibo < mysqli_num_rows($res_recibo)) { 

?>	

	<tr>
		<td colspan=2 height="17" align="left" valign=middle sdval="41852" sdnum="3082;0;DD/MM/AAAA"><font face="Tahoma"><?php echo implota($startTime);?></font></td>
		<td align="left" valign=middle><font face="Tahoma">Recibo Nº <?php echo mysqli_result($res_recibo, $contadorrecibo, "numdocumento");?></font></td>
		<td align="left" sdnum="3082;0;DD/MM/AA"><font face="Tahoma"><br></font></td>
		<td align="left" sdnum="3082;0;DD/MM/AA"><font face="Tahoma"><br></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br></font></td>
		<td align="right"><font face="Tahoma">
			
		<?php
		
		 $total= mysqli_result($res_recibo, $contadorrecibo, "importe");
		if ($moneda==0){
		 if (mysqli_result($res_recibo, $contadorrecibo, "moneda")!=2){
		 $Total_Cobros=$total;		 
			$TCobros= number_format($total,2,",","."); 
		 } else {
		 $Total_Cobros=$total*$tipocambio;
			$TCobros= number_format($total*$tipocambio,2,",","."); 
		 }
		} else {
		 $SaldoTotal_Cobros+=$total;		 
		 $Total_Cobros=$total;		 
			$TCobros= number_format($total,2,",","."); 
		}
		 $total=0;	
		 
		 echo $TCobros;	
		 $totalCobros=$totalCobros+$Total_Cobros;
		
		?>
		&nbsp;</font>	
		
		</td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br></font></td>
		<td align="right"><font face="Tahoma">
		<?php 
		$SaldoParcial=$SaldoParcial-$Total_Cobros;
		echo number_format($SaldoParcial,2,",",".");
		?>&nbsp;		
		</font></td>
	</tr>
	
<?php
		   
		   $contadorrecibo++;
		   }


			}
				$startTime = date ("Y-m-d", strtotime("-1 day", strtotime($startTime)));
		}
		
?>	

	<tr>
		<td colspan=2 height="17" align="left" valign=middle sdval="41852" sdnum="3082;0;DD/MM/AAAA"><font face="Tahoma"><br></font></td>
		<td align="left" valign=middle><font face="Tahoma"><br></font></td>
		<td align="left" sdnum="3082;0;DD/MM/AA"><font face="Tahoma">Total&nbsp;cuenta</font></td>
		<td align="right" sdnum="3082;0;DD/MM/AA" style="border: 1pt solid #000000; border-top: 1pt dotted #ff0000;"><font face="Tahoma">
		<?php echo number_format($totalVentas,2,",",".");
		$SumaTotalVentas+=$totalVentas; 
		?> 
		
		&nbsp;</font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br></font></td>
		<td align="right" style="border: 1pt solid #000000; border-top: 1pt dotted #ff0000;"><font face="Tahoma">
		<?php echo number_format($totalCobros,2,",",".");
		$SumaTotalCobros+=$totalCobros;
		?>
		
		&nbsp;</font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br></font></td>
		<td align="right"style="border: 1pt solid #000000;  border-top: 1pt dotted #ff0000;"><font face="Tahoma">
		
		<?php echo number_format($totalVentas-$totalCobros,2,",",".");?>&nbsp;</font></td>
	</tr>
	
<?php		
	$SaldoTotal_Ventas=0;
	$SaldoTotal_Cobros=0;	
		
	//}	
	//$con_cliente++;

}
?>
	<tr >
		<td colspan="9" style="line-height:5px; border: 0px solid #000000; border-top: 1px solid #000000;"><font face="Tahoma"><br>&nbsp;</font></td>
	</tr>

	
	<tr>
		<td height="17" align="left"><font face="Tahoma"><br></font></td>
		<td colspan=2 align="left" valign=middle><font face="Tahoma">Total débitos</font></td>
		<td colspan=2 align="right" valign=middle sdval="123456789" sdnum="3082;0;0,00"><font face="Tahoma"><?php  echo @number_format($SumaTotalVentas,2,",",".");?>&nbsp;</font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
	</tr>
	<tr>
		<td height="17" align="left"><font face="Tahoma"><br></font></td>
		<td colspan=2 align="left" valign=middle><font face="Tahoma">Total créditos</font></td>
		<td colspan=2 align="right" valign=middle sdnum="3082;0;0,00"><font face="Tahoma"><?php echo @number_format($SumaTotalCobros,2,",",".");?>&nbsp;</font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
	</tr>
	<tr>
		<td height="17" align="left"><font face="Tahoma"><br></font></td>
		<td colspan=2 align="left" valign=middle><font face="Tahoma">Total saldo total</font></td>
		<td colspan=2 align="right" valign=middle sdval="3456789012" sdnum="3082;0;0,00"><font face="Tahoma"><?php echo number_format($SumaTotalVentas-$SumaTotalCobros,2,",",".");?>&nbsp;</font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
	</tr>

</table>
<!-- ************************************************************************** -->
</body>

</html>


