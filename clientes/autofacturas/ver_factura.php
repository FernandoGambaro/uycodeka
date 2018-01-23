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
 
include ("../../conectar.php"); 
include ("../../funciones/fechas.php"); 
include("../../common/funcionesvarias.php");

date_default_timezone_set("America/Montevideo"); 

$codautofactura=$_GET["codautofactura"];
$cadena_busqueda=$_GET["cadena_busqueda"];


$a="";
$data=array();
$a=isset($_GET['a']) ? $_GET['a'] : null ;
$a=array_recibe($a);

$codautofacturaant=0;
$codautofacturasig=0;
	
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
	if ($codcliente <> "") { $where.=" AND autofacturas.codcliente='$codcliente'"; }
	//if ($nombre <> "") { $where.=" AND (clientes.nombre like '%".$nombre."%' or clientes.apellido like '%".$nombre."%' or clientes.empresa like '%".$nombre."%')"; }
	if ($numfactura <> "") { $where.=" AND autofacturas.codautofactura>='$numfactura'"; }
	if ($estado > "0") { $where.=" AND autofacturas.estado='$estado' and autofacturas.tipo = 1 "; }
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
	
	$where.=" ORDER BY ";
	

	/*verifico si existe la factura anterior y la siguiente*/

	$prevquery= "SELECT * FROM autofacturas WHERE codautofactura < $codautofactura ".$where." autofacturas.codautofactura DESC LIMIT 1"; 
	$prevresult = mysqli_query($GLOBALS["___mysqli_ston"], $prevquery) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	while($prevrow = mysqli_fetch_array($prevresult))
	{
	$codautofacturaant  = $prevrow['codautofactura'];
	}
	
	$nextquery= "SELECT * FROM autofacturas WHERE codautofactura > $codautofactura ".$where." autofacturas.codautofactura ASC LIMIT 1"; 
	$nextresult = mysqli_query($GLOBALS["___mysqli_ston"], $nextquery) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	while($nextrow = mysqli_fetch_array($nextresult))
	{
	$codautofacturasig  = $nextrow['codautofactura'];
	}
	
}

$codautofacturatmp=$codautofactura=$_GET["codautofactura"];

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
$emitida=mysqli_result($rs_alb, 0, "emitida");

$sel_cliente="SELECT nombre,apellido,empresa,nif,agencia FROM clientes WHERE codcliente='$codcliente'";
$rs_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cliente);
$nombre=mysqli_result($rs_cliente, 0, "nombre");
$nombre=mysqli_result($rs_cliente, 0, "nombre")." ".mysqli_result($rs_cliente, 0, "apellido")." - ".mysqli_result($rs_cliente, 0, "empresa");
$agencia=mysqli_result($rs_cliente, 0, "agencia");
$nif=mysqli_result($rs_cliente, 0, "nif");


?>

<html>
	<head>
		<title>Principal</title>
		<link href="../../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script src="../../calendario/jscal2.js"></script>
		<script src="../../calendario/lang/es.js"></script>
		<link rel="stylesheet" type="text/css" href="../../calendario/css/jscal2.css" />
		<link rel="stylesheet" type="text/css" href="../../calendario/css/border-radius.css" />
		<link rel="stylesheet" type="text/css" href="../../calendario/css/win2k/win2k.css" />		

		<script type="text/javascript" src="../js/jquery.min.js"></script>
		<link rel="stylesheet" href="../js/jquery.toastmessage.css" type="text/css">
		<script src="../js/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js/message.js" type="text/javascript"></script>

		<script src="../js/jquery.msgBox.js" type="text/javascript"></script>
		<link href="../js/msgBoxLight.css" rel="stylesheet" type="text/css"> 
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../../css3/css/font-awesome.min.css">


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
			location.href="ver_factura.php?codautofactura="+numero+"&cadena_busqueda=<?php echo $cadena_busqueda;?>&a=<?php echo $data;?>";
		}		
		
		function imprimir(codautofactura,emitida) {
			if (emitida!=1) {
			var top = window.open("../fpdf/imprimir_factura_mcc.php?codautofactura="+codautofactura, "factura", "location=1,status=1,scrollbars=1");
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
												top = window.open("../fpdf/imprimir_factura_mcc.php?codautofactura="+codautofactura, "factura", "location=1,status=1,scrollbars=1");
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
					<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td>Nombre</td>
						    <td colspan="2"><input name="nombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" value="<?php echo $nombre;?>" readonly data-index="1">
						    <input name="codcliente" type="hidden" class="cajaPequena" id="aCliente" value="<?php echo $codcliente;?>"></td>
							<td>Nº&nbsp;</td><td>
							<input id="codautofacturatmp" class="cajaPequena" name="codautofacturatmp" value="<?php echo $codautofactura;?>" disabled="true">						  
						  </input></td>						  
							<td>Día de facturación</td>	
						  <td><select id="semanafacturacion" name="Asemanafacturacion" class="comboPequeno2" data-index="2">

					<?php $tipof = array(1=>"1º", 2=>"2º", 3=>"3º", 4=>"4º");

					$x=1;
					$NoEstado=0;
					foreach($tipof as $i) {
					  	if ( $x==0) {
							echo "<option value=$x selected>$i</option>";
						} else {
							echo "<option value=$x>$i</option>";
						}
						$x++;
					}
					?>
					</select>
					<select id="diafacturacion" name="Adiafacturacion" class="comboPequeno" data-index="3">

					<?php $tipof = array(0=>"Lunes", 1=>"Martes", 2=>"Miércoles", 3=>"Jueves", 4=>"Viernes");

					$x=0;
					$NoEstado=0;
					foreach($tipof as $i) {
					  	if ( $x==0) {
							echo "<option value=$x selected>$i</option>";
						} else {
							echo "<option value=$x>$i</option>";
						}
						$x++;
					}
					?>
						</select>								
						</td>	<td>	
						<label>
						<input type="checkbox" name="activa" value="1" checked> Activo
	
							<span></span>
						</label>	&nbsp;Acción:&nbsp;
					<?php $tipof = array(1=>"Solo registrar", 2=>"Registrar y emitir", 3=>"Registrar y enviar", 4=>"Registrar, emitir y enviar");

					$x=1;
					$NoEstado=0;
					foreach($tipof as $i) {
					  	if ( $x==$emitida) {
							echo $i;
						}
						$x++;
					}
					?>					  				         					        					
						</tr>
						<tr>
				            <td>RUT</td>
				            <td colspan="2"><input name="nif" type="text" class="cajaMedia" id="nif" size="20" maxlength="15" value="<?php echo $nif?>" readonly></td>
								<td>Tipo</td>
				            <td>
				            <select id="tipo" name="atipo" class="cajaPequena" data-index="2">

					<?php $tipof = array(0=>"Contado", 1=>"Credito");
					if ($tipo==" ")
					{
					echo '<option value="" selected>Selecione uno</option>';
					}
					$x=0;
					$NoEstado=0;
					foreach($tipof as $i) {
					  	if ( $x==$tipo) {
							echo "<option value=$x selected>$i</option>";
							$NoEstado=1;
						} else {
							echo "<option value=$x>$i</option>";
						}
						$x++;
					}
					?>
								</select>
						</td><td>Forma&nbsp;de&nbsp;pago</td><td>
														<?php
					  	$query_fp="SELECT * FROM formapago WHERE borrado=0 ORDER BY dias ASC";
						$res_fp=mysqli_query($GLOBALS["___mysqli_ston"], $query_fp);
						$contador=0;
					  ?>
								<select id="codformapago" name="codformapago" class="comboMedio simpleinput" onchange="actualizovencimeinto();" data-index="3">
							
								<option value="0">Seleccione una</option>
								<?php
								while ($contador < mysqli_num_rows($res_fp)) { 
								if (mysqli_result($res_fp, $contador, "codformapago")==$codformapago) { ?>
								<option value="<?php echo mysqli_result($res_fp, $contador, "codformapago").'~'.mysqli_result($res_fp, $contador, "dias");?>" selected><?php echo mysqli_result($res_fp, $contador, "nombrefp")?></option>
								<?php 
									} else {
								?>
								<option value="<?php echo mysqli_result($res_fp, $contador, "codformapago").'~'.mysqli_result($res_fp, $contador, "dias");?>" ><?php echo mysqli_result($res_fp, $contador, "nombrefp")?></option>
								<?php
								}
								 $contador++;
								} ?>				
								</select>									
								
								</td>
						<td>Moneda&nbsp; 
					<?php
					$tipof = array(  1=>"Pesos", 2=>"U\$S");
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
							<td>Fecha</td><td>
							<input name="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" value="<?php echo implota($fecha)?>" readonly> 
						    <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fecha",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { actualizovencimeinto(); this.hide(); },
						     dateFormat : "%d/%m/%Y"
						   });
						</script>
						</td><td>
						Vencimiento <input name="vencimiento" type="text" class="cajaPequena" id="vencimiento" size="10" maxlength="10" value="<?php echo $hoy;?>" readonly>
						</td>
				      <td>IVA</td>
				      <td><input name="iva" type="text" class="cajaPequena2" id="iva" size="5" maxlength="5" onChange="cambio_iva()" value="<?php echo $iva;?>"> %</td>
				       <td>Tipo&nbsp;Cambio</td><td>
								<label>$&nbsp;->&nbsp;U$S</label><span>
								<input name="tipocambio" type="text" class="cajaPequena" id="tipocambio" size="5" maxlength="5" value="<?php echo $tipocambio; ?>" onChange="cambio();"></span>
								<!--<input type="checkbox" name="usartc" style="vertical-align: middle; margin-top: -1px;">Buscar T/C</input>-->
						</td>
						<td><label>Agencia&nbsp;</label><span><input name="agencia" type="text" class="cajaGrande" id="agencia" size="5" maxlength="5" value="<?php echo $agencia; ?>"></span>
						  &nbsp;</td>				         					        					
						
				            
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
						$sel_lineas="SELECT autofactulinea.*,articulos.*,familias.nombre as nombrefamilia FROM autofactulinea,articulos,familias WHERE autofactulinea.codautofactura='$codautofactura' AND autofactulinea.codigo=articulos.codarticulo AND autofactulinea.codfamilia=articulos.codfamilia AND articulos.codfamilia=familias.codfamilia ORDER BY autofactulinea.numlinea ASC";
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
					if($codautofacturaant==0) {
					?>
						<button class="boletin" disabled><i class="fa fa-arrow-left" aria-hidden="true"></i> Anterior</button>					
					<?php
					} else {
					?>
						<button class="boletin" onClick="ver(<?php echo $codautofacturaant;?>);"><i class="fa fa-arrow-left" aria-hidden="true"></i> Anterior</button>					
					<?php
					}
					?>
					<button class="boletin" onClick="parent.$('idOfDomElement').colorbox.close();"onMouseOver="style.cursor=cursor"><i class="fa fa-window-close" aria-hidden="true"></i>
Cerrar</button>
					
					<?php
					if($codautofacturasig==0) {
					?>
						<button class="boletin" disabled>Siguiente <i class="fa fa-arrow-right" aria-hidden="true"></i></button>					
					<?php
					} else {
					?>
						<button class="boletin" onClick="ver(<?php echo $codautofacturasig;?>);">Siguiente <i class="fa fa-arrow-right" aria-hidden="true"></i></button>					
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