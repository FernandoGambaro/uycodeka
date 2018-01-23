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
 
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 
include("../common/funcionesvarias.php");

date_default_timezone_set("America/Montevideo"); 

$codfactura=$_GET["codfactura"];

$a="";
$data=array();
$a=isset($_GET['a']) ? $_GET['a'] : null ;
$a=array_recibe($a);

$codfacturaant=0;
$codfacturasig=0;
	
	
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
	
$data=array_envia($a); 
	
	$where=" AND 1=1";
	if ($codcliente <> "") { $where.=" AND facturas.codcliente='$codcliente'"; }
	//if ($nombre <> "") { $where.=" AND (clientes.nombre like '%".$nombre."%' or clientes.apellido like '%".$nombre."%' or clientes.empresa like '%".$nombre."%')"; }
	if ($numfactura <> "") { $where.=" AND facturas.codfactura>='$numfactura'"; }
	if ($Amoneda <> "") { $where.=" AND facturas.moneda>='$Amoneda'"; }
	if ($estado > "0") { $where.=" AND facturas.estado='$estado' and facturas.tipo = 1 "; }
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
	//if ($codarticulo <> "") { $where.=" AND factulinea.codigo='$codarticulo'"; }
	
	$where.=" ORDER BY ";
	/*verifico si existe la factura anterior y la siguiente*/

	$prevquery= "SELECT * FROM facturas WHERE codfactura < $codfactura ".$where." facturas.codfactura DESC LIMIT 1"; 
	$prevresult = mysqli_query($GLOBALS["___mysqli_ston"], $prevquery) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	while($prevrow = mysqli_fetch_array($prevresult))
	{
	$codfacturaant  = $prevrow['codfactura'];
	}
	
	$nextquery= "SELECT * FROM facturas WHERE codfactura > $codfactura ".$where." facturas.codfactura ASC LIMIT 1"; 
	$nextresult = mysqli_query($GLOBALS["___mysqli_ston"], $nextquery) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	while($nextrow = mysqli_fetch_array($nextresult))
	{
	$codfacturasig  = $nextrow['codfactura'];
	}
	
}

$query="SELECT * FROM facturas WHERE codfactura='$codfactura'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
$codcliente=mysqli_result($rs_query, 0, "codcliente");
$fecha=mysqli_result($rs_query, 0, "fecha");
$iva=mysqli_result($rs_query, 0, "iva");
$moneda=mysqli_result($rs_query, 0, "moneda");
$tipocambio=mysqli_result($rs_query, 0, "tipocambio");
$emitida=mysqli_result($rs_query, 0, "emitida");

$tipo=mysqli_result($rs_query, 0, "tipo");
$observacion =mysqli_result($rs_query, 0, "observacion");
$descuentogral=mysqli_result($rs_query, 0, "descuento");

$sel_cliente="SELECT nombre,nif, empresa, apellido FROM clientes WHERE codcliente='$codcliente'";
$rs_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cliente);
$nombre=mysqli_result($rs_cliente, 0, "nombre");
$nif=mysqli_result($rs_cliente, 0, "nif");

				$sel_resultado="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
			   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
$moneda1=mysqli_result($res_resultado,0, "simbolo");
$moneda2=mysqli_result($res_resultado, 1, "simbolo");

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script src="../calendario/jscal2.js"></script>
		<script src="../calendario/lang/es.js"></script>
		<link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
		<link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
		<link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />		

		<script type="text/javascript" src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
		<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js3/message.js" type="text/javascript"></script>

		<script src="../js3/jquery.msgBox.js" type="text/javascript"></script>
		<link href="../js3/msgBoxLight.css" rel="stylesheet" type="text/css"> 
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">


<script type="text/javascript">
$(document).keydown(function(e) {
    switch(e.keyCode) { 
        case 13:
			parent.$('idOfDomElement').colorbox.close();
        break;
        case 112:
            showWarningToast('Ayuda aún no disponible...');
        break; 
	 }
});
</script>		
		<script language="javascript">
		function callGpsDiag(xx,numDoc){
			window.parent.callGpsDiag(xx,numDoc);
		}
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		function aceptar() {
			parent.$('idOfDomElement').colorbox.close();
			//location.href="index.php?cadena_busqueda=<?php echo $cadena_busqueda?>";
		}

		function ver(numero) {
			location.href="ver_factura.php?codfactura="+numero+"&cadena_busqueda=<?php echo $cadena_busqueda;?>&a=<?php echo $data;?>";
		}		
		
		function imprimir(codfactura,emitida) {
			if (emitida!=1) {
			var top = window.open("../fpdf/imprimir_factura_mail.php?codfactura="+codfactura, "factura", "location=1,status=1,scrollbars=1");
			//setTimeout(top.close(), 3000000);				
				//parent.$('idOfDomElement').colorbox.close();
			} else {
				$.msgBox({
				    title: "Alerta",
				    content: "Quiere reimprimir factura emitida?",
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
												top = window.open("../fpdf/imprimir_factura_mail.php?codfactura="+codfactura, "factura", "location=1,status=1,scrollbars=1");
												//parent.$('idOfDomElement').colorbox.close();
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
			var fecha=$("#fecha").val();
				$.post("busco_tipocambio.php?fecha="+fecha,  function(data){
				$("#tipocambio").val(data);
			})(jQuery);		
		}		
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">VER FACTURA</div>
				<div id="frmBusqueda">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td width="6%">Cliente:</td>
						    <td colspan="3"><?php echo mysqli_result($rs_cliente, 0, "empresa").' - '.mysqli_result($rs_cliente, 0, "nombre").' '.mysqli_result($rs_cliente, 0, "apellido");?></td>
						  <td>Nº&nbsp;factura:</td>
						  <td colspan="2"><?php echo $codfactura;?></td>				         					        					
						</tr>
						<tr>
				            <td width="5%">RUT:</td>
				            <td><?php echo $nif;?>
				            </td>
								<td>Tipo:</td>
				            <td>
					<?php $tipof = array(0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
					$NoEstado=0;
					foreach($tipof as $key => $facturai ) {
					  	if ( $key==$tipo) {
							echo "$facturai";
							break;
						}
					}
					?>
						</td>
						<td>Moneda:</td><td width="26%">
					<?php $tipof = array(  1=>"Pesos", 2=>"U\$S");
					foreach ($tipof as $key => $monedai ) {
					  	if ( $moneda==$key ) {
							echo "$monedai";
							break;
						}
					}
					?>
					</td>				            
						</tr>
						<?php $hoy=date("d/m/Y"); ?>
						<tr>
							<td width="6%">Fecha:</td>
						    <td width="27%">
						    <?php echo implota($fecha)?>
								</td>
				            <td width="3%">IVA:</td>
				            <td ><?php
				            
					  	$query_iva="SELECT * FROM impuestos WHERE  codimpuesto=".$iva;
						$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
										            
				             echo mysqli_result($res_iva, 0, "nombre")?></td>
				            <td colspan="3">Tipo&nbsp;cambio:
								<label><?php echo $moneda1;?>"&nbsp;->&nbsp;	<?php echo $moneda2;?>"</label><span>
								<?php echo $tipocambio;?></span>
								</td>
					</tr>

					  <tr>
						  <td></td>
						  <td colspan="2"></td>
					  </tr>
				  </table>
			  </div>	
			  <br style="line-height:5px">			  			  
					 <table class="fuente8" width="95%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="40%" align="left">&nbsp;DESCRIPCION</td>
							<td width="12%" align="left">&nbsp;DETALLES</td>
							<td width="8%">CANTIDAD</td>
							<td width="8%">PRECIO</td>
							<td width="4%">DCTO%</td>
							<td width="4%">D. PP%</td>
							<td width="5%">MONEDA</td>
							<td width="8%">IMPORTE</td>
							<td width="4%">COM.%</td>
						</tr>
					  <?php
					  $baseimponible=0;
					  $filastotal=10;
					   $tipomon = array( 0=>"&nbsp;", 1=>"Pesos", 2=>"U\$S");
						$sel_lineas="SELECT factulinea.*,articulos.*,familias.nombre as nombrefamilia FROM factulinea,articulos,familias WHERE factulinea.codfactura='$codfactura' AND factulinea.codigo=articulos.codarticulo AND factulinea.codfamilia=articulos.codfamilia AND articulos.codfamilia=familias.codfamilia ORDER BY factulinea.numlinea ASC";
						$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
						for ($i = 0; $i < mysqli_num_rows($rs_lineas); $i++) {
							$numlinea=mysqli_result($rs_lineas, $i, "numlinea");
							$codfamilia=mysqli_result($rs_lineas, $i, "codfamilia");
							$nombrefamilia=mysqli_result($rs_lineas, $i, "nombrefamilia");
							$codarticulo=mysqli_result($rs_lineas, $i, "codarticulo");
							$detalles=mysqli_result($rs_lineas, $i, "detalles");
							$descripcion=mysqli_result($rs_lineas, $i, "descripcion");
							$referencia=mysqli_result($rs_lineas, $i, "referencia");
							$cantidad=mysqli_result($rs_lineas, $i, "cantidad");
							$precio=mysqli_result($rs_lineas, $i, "precio");
							$moneda=$tipomon[mysqli_result($rs_lineas, $i, "moneda")];
							$importe=mysqli_result($rs_lineas, $i, "importe");
							$baseimponible=$baseimponible+$importe;
							$descuento=mysqli_result($rs_lineas, $i, "dcto");
							$descuentopp=mysqli_result($rs_lineas, $i, "dctopp");
							$comision=mysqli_result($rs_lineas, $i, "comision");
							
							if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>

									<tr class="<?php echo $fondolinea?>">
										<td width="40%"><?php echo substr($descripcion, 0, 50);?></td>
										<td width="12%"><?php echo $detalles?></td>
										<td width="8%" class="aCentro"><?php echo $cantidad;?></td>
										<td width="8%" class="aCentro"><?php echo $precio;?></td>
										<td width="4%" class="aCentro"><?php echo $descuento;?></td>
										<td width="4%" class="aCentro"><?php echo $descuentopp;?></td>
										<td width="5%" class="aCentro"><?php echo $moneda;?></td>
										<td width="8%" class="aCentro" ><?php echo $importe;?></td>
										<td width="4%" class="aCentro"><?php echo $comision;?></td>
																			
									</tr>
					<?php
					$filastotal--;
					}
					for ($i = 0; $i <= $filastotal; $i++) {
					?>
									<tr>
										<td width="10%">&nbsp;</td>
										<td width="52%">&nbsp;</td>
										<td width="8%" class="aCentro">&nbsp;</td>
										<td width="8%" class="aCentro">&nbsp;</td>
										<td width="4%" class="aCentro">&nbsp;</td>
										<td width="4%" class="aCentro">&nbsp;</td>
										<td width="5%" class="aCentro" >&nbsp;</td>									
										<td width="8%" class="aCentro" >&nbsp;</td>									
										<td width="4%" class="aCentro" >&nbsp;</td>									
									</tr>
					<?php					
					
					}

		  	$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".$iva;
			$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
			$iva=mysqli_result($res_iva, 0, "valor");
			
				  $baseimponibleaux=$baseimponible;
				  if ($descuentogral==0 or $descuentogral=='') {
				  $baseimpuestos=$baseimponible*($iva/100);
			     $preciototal=$baseimponible+$baseimpuestos;
				  } else {
				  $baseimponible=$baseimponible*(1-$descuentogral/100);
				  $baseimpuestos=$baseimponible*($iva/100);
			     $preciototal=$baseimponible+$baseimpuestos;
				  }				  
		      $preciototal=number_format($preciototal,2);
			  	  ?>
			  	  

			<td align="center" valign="top" colspan="10">
				<table border=0 align="center" cellpadding=1 cellspacing=0 class="fuente8">
				<tr>
				<td valign="top">Observaciones</td>
				<td colspan="7"></td>
				</tr><tr>
				<td valign="top" rowspan="2"><textarea name="observacion" id="observacion" rows="4" cols="40"> <?php echo $observacion;?></textarea>
				</td><td colspan="4"></td>
			   <td class="busqueda" align="right">Sub-total</td>
				<td align="right">
				 <input type="text" class="cajaPequena2" id="monShow" value="<?php echo $monedai;?>" readonly>
				 </td><td>
			      <input class="cajaTotales" name="baseimponible" type="text" id="baseimponible" size="12" value="<?php echo number_format($baseimponibleaux,2);?>" align="right" readonly> 
		       </td>
				</tr>
				<tr>
				<td class="busqueda" align="right">Descuento</td>
				<td class="busqueda"><input id="descuentogral" name="descuentogral" value="<?php echo $descuentogral;?>" class="cajaMinima"></td>
				<td class="busqueda">&nbsp;%</td>
				<td>	
				<input class="cajaTotales" name="baseimponibledescuento" type="text" id="baseimponibledescuento" value="<?php echo number_format($baseimponible,2);?>" size="12" align="right" readonly> 
				</td>
				<td class="busqueda" align="right">IVA</td>
				<td align="right">

				 <input type="text" class="cajaPequena2" id="monSho" value="<?php echo $monedai;?>" readonly>
				 </td><td>
			      <input class="cajaTotales" name="baseimpuestos" type="text" id="baseimpuestos" size="12" align="right" value="<?php echo number_format($baseimpuestos,2);?>" readonly>
				</td>
 				</tr>
				<tr>
				<td colspan="5"></td>
				<td class="busqueda" align="right">Precio&nbsp;Total</td>
				<td align="right">
				 <input type="text" class="cajaPequena2" id="monSh" value="<?php echo $monedai;?>" readonly>
				 </td><td>
			      <input class="cajaTotales" name="preciototal" type="text" id="preciototal" size="12" align="right" value="<?php echo $preciototal;?>" readonly> 
		      </td>
				</tr> 	
				<tr>
				<td colspan="8" align="center">
					<div align="center">
					<?php
					if($codfacturaant==0) {
					?>
						<button class="boletin" disabled><i class="fa fa-arrow-left" aria-hidden="true"></i> Anterior</button>					
					<?php
					} else {
					?>
						<button class="boletin" onClick="ver(<?php echo $codfacturaant;?>);"><i class="fa fa-arrow-left" aria-hidden="true"></i> Anterior</button>					
					<?php
					}
					?>
					<button class="boletin" onClick="parent.$('idOfDomElement').colorbox.close();"onMouseOver="style.cursor=cursor"><i class="fa fa-window-close" aria-hidden="true"></i>
Cerrar</button>
					<button class="boletin" onClick="imprimir(<?php echo $codfactura;?>,<?php echo $emitida;?>)" onMouseOver="style.cursor=cursor"><i class="fa fa-print" aria-hidden="true"></i>
Imprimir</button>
					<?php
					if($codfacturasig==0) {
					?>
						<button class="boletin" disabled>Siguiente <i class="fa fa-arrow-right" aria-hidden="true"></i></button>					
					<?php
					} else {
					?>
						<button class="boletin" onClick="ver(<?php echo $codfacturasig;?>);">Siguiente <i class="fa fa-arrow-right" aria-hidden="true"></i></button>					
					<?php
					}
					?>
					</div>
				</td>
				</tr>			
				</table>
				</td>
				</tr>
				<tr>
				<td colspan="9" align="center">
		  	  
		 
				
				</td>
			  </tr>
		</table>				
		</div>
			  </div>
		  </div>
		</div>

	</body>
</html>