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
include ('../class/Encryption.php');

date_default_timezone_set("America/Montevideo"); 

$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$nombre=$_POST["Anombre"];
$apellido=$_POST["aapellido"];
$empresa=$_POST["aempresa"];
$nif=$_POST["anif"];
$tiponif=$_POST["TTiponif"];
$direccion=$_POST["adireccion"];
$localidad=$_POST["alocalidad"];
$codprovincia=$_POST["cboProvincias"];
$codformapago=$_POST["cboFPago"];
$codentidad=$_POST["cboBanco"];
$cuentabanco=$_POST["acuentabanco"];
$codpostal=$_POST["acodpostal"];
$telefono=$_POST["atelefono"];
$telefono2=$_POST["atelefono2"];
$movil=$_POST["amovil"];
$fax=$_POST["afax"];
$email=$_POST["aemail"];
$email2=$_POST["aemail2"];
$web=$_POST["aweb"];
$tipo=$_POST["Ttipo"];
$horas=$_POST["nhoras"];
$plancuenta=$_POST['Aplancuentac'];
$contrasenia=$_POST["contrasenia"];
//$cadena_busqueda=$_POST["cadena_busqueda"];
$codusuarios=$_POST['codusuarios'];
$codpais=$_POST['codpais'];
$agencia=$_POST["aagencia"];

$recepciondia=$_POST["arecepciondia"];
$recepcionhora=$_POST["arecepcionhora"];
$recepcioncontacto=$_POST["arecepcioncontacto"];
$pagodia=$_POST["apagodia"];
$pagohora=$_POST["apagohora"];
$pagocontacto=$_POST["apagocontacto"];



$converter = new Encryption;
$contrasenia = $converter->encode($contrasenia );

$secQ=$_POST["secQ"];
$secA=$_POST["secA"];
$service=$_POST["service"];


if ($accion=="alta") {
	$query_operacion="INSERT INTO clientes (codcliente, nombre, apellido, empresa, nif, tiponif, plancuenta, codpais, direccion, codprovincia, localidad, 
	codformapago, codentidad, cuentabancaria, agencia, recepciondia, recepcionhora, recepcioncontacto, pagodia, pagohora, pagocontacto, codpostal, telefono, telefono2, fax, movil,
	 email, email2, web, tipo, contrasenia, sessionid, secQ, secA, codusuarios, service, horas, borrado) VALUES 
	 ('', '$nombre', '$apellido', '$empresa', '$nif', '$tiponif', '$plancuenta','$codpais', '$direccion', '$codprovincia', '$localidad',
	 '$codformapago', '$codentidad', '$cuentabanco', '$agencia', '$recepciondia', '$recepcionhora', '$recepcioncontacto', '$pagodia', '$pagohora', '$pagocontacto', '$codpostal', '$telefono',
	 '$telefono2', '$fax', '$movil',
	  '$email', '$email2', '$web', '$tipo', '$contrasenia', '', '$secQ', '$secA', '$codusuarios', '$service', '$horas', '0')";					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	if ($rs_operacion) { $mensaje="El cliente ha sido dado de alta correctamente"; }
	$cabecera1="Inicio >> Clientes &gt;&gt; Nuevo Cliente ";
	$cabecera2="INSERTAR CLIENTE ";
	$sel_maximo="SELECT max(codcliente) as maximo FROM clientes";
	$rs_maximo=mysqli_query($GLOBALS["___mysqli_ston"], $sel_maximo);
	$codcliente=mysqli_result($rs_maximo, 0, "maximo");
}

if ($accion=="modificar") {
	
	if (!empty($contrasenia)) {
	$pass="contrasenia='".$contrasenia."',";	
	} else {
	$pass='';
	}	
	
	$codcliente=$_POST["codcliente"];
	$query="UPDATE clientes SET nombre='$nombre', apellido='$apellido', empresa='$empresa', nif='$nif', tiponif='$tiponif', plancuenta='$plancuenta', codpais='$codpais', direccion='$direccion', codprovincia='$codprovincia', 
	localidad='$localidad', codformapago='$codformapago', codentidad='$codentidad',
	 cuentabancaria='$cuentabanco', agencia='$agencia', recepciondia='$recepciondia', recepcionhora='$recepcionhora', recepcioncontacto='$recepcioncontacto', pagodia='$pagodia', 
	 pagohora='$pagohora', pagocontacto='$pagocontacto', codpostal='$codpostal', telefono='$telefono', telefono2='$telefono2', 
	 movil='$movil', email='$email', email2='$email2', web='$web',
	 tipo='$tipo', ".$pass." secQ='$secQ', secA='$secA', codusuarios='$codusuarios', service='$service', horas='$horas',
	  borrado=0 WHERE codcliente='$codcliente'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="Los datos del cliente han sido modificados correctamente"; }
	$cabecera1="Inicio >> Clientes &gt;&gt; Modificar Cliente ";
	$cabecera2="MODIFICAR CLIENTE ";
}

if ($accion=="baja") {
	$codcliente=$_POST["codcliente"];
	$query="UPDATE clientes SET borrado=1 WHERE codcliente='$codcliente'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="El cliente ha sido eliminado correctamente"; }
	$cabecera1="Inicio >> Clientes &gt;&gt; Eliminar Cliente ";
	$cabecera2="ELIMINAR CLIENTE ";
	$query_mostrar="SELECT * FROM clientes WHERE codcliente='$codcliente'";
	$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);
	$nombre=mysqli_result($rs_mostrar, 0, "nombre");
	$apellido=mysqli_result($rs_mostrar, 0, "apellido");
	$empresa=mysqli_result($rs_mostrar, 0, "empresa");
	$tiponif=mysqli_result($rs_mostrar, 0, "tiponif");
	$nif=mysqli_result($rs_mostrar, 0, "nif");
	$direccion=mysqli_result($rs_mostrar, 0, "direccion");
	$localidad=mysqli_result($rs_mostrar, 0, "localidad");
	$codprovincia=mysqli_result($rs_mostrar, 0, "codprovincia");
	$codformapago=mysqli_result($rs_mostrar, 0, "codformapago");
	$codentidad=mysqli_result($rs_mostrar, 0, "codentidad");
	$cuentabanco=mysqli_result($rs_mostrar, 0, "cuentabancaria");
	$codpostal=mysqli_result($rs_mostrar, 0, "codpostal");
	$telefono=mysqli_result($rs_mostrar, 0, "telefono");
	$movil=mysqli_result($rs_mostrar, 0, "movil");
	$email=mysqli_result($rs_mostrar, 0, "email");
	$web=mysqli_result($rs_mostrar, 0, "web");
	$service=mysqli_result($rs_mostrar, 0, "service");
	$tipo=mysqli_result($rs_mostrar, 0, "tipo");
	$horas=mysqli_result($rs_mostrar, 0, "horas");
	$codpais=mysqli_result($rs_mostrar, 0, "codpais");

	$telefono2=mysqli_result($rs_mostrar, 0, "telefono2");
	$email2=mysqli_result($rs_mostrar, 0, "email2");
	$fax=mysqli_result($rs_mostrar, 0, "fax");
	$agencia=mysqli_result($rs_mostrar, 0, "agencia");
	$recepciondia=mysqli_result($rs_mostrar, 0, "recepciondia");
	$recepcionhora=mysqli_result($rs_mostrar, 0, "horas");
	$recepcioncontacto=mysqli_result($rs_mostrar, 0, "horas");
	$pagodia=mysqli_result($rs_mostrar, 0, "horas");
	$pagohora=mysqli_result($rs_mostrar, 0, "horas");
	$pagocontacto=mysqli_result($rs_mostrar, 0, "horas");

	$plancuenta=mysqli_result($rs_mostrar, 0, "Aplancuenta");
	
}

?>

<html>
	<head>
		<title>Guardar Cliente</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		
<script src="js/jquery.min.js"></script>
<link rel="stylesheet" href="js/jquery.toastmessage.css" type="text/css">
<script src="js/jquery.toastmessage.js" type="text/javascript"></script>
<script src="js/message.js" type="text/javascript"></script>
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
		
		function aceptar() {

			parent.$('idOfDomElement').colorbox.close();
			/*location.href="index.php";*/
		}
		
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}
		
		</script>
	</head>
	<body>
	
	<?php
	$estilo="margin: .5em 0 0.5em; border: 1px solid;padding: 2px 2px 2px 2px; background-repeat: no-repeat; background-position: 10px 50%; box-shadow: 0 1px 1px #fff inset;";
	?>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header"><?php echo $cabecera2;?> - <?php echo $codcliente;?></div>
				<div id="frmBusqueda">
				<table class="fuente8" width="100%"><tr><td valign="top">
					<table class="fuente8" width="100%" cellspacing=3 cellpadding=3 border=0>
						<tr>
							<td width="15%">Nombre</td>
						    <td colspan="3"><?php echo $nombre;?></td>
				        </tr>
						<tr>
							<td width="15%">Apellido</td>
						    <td colspan="3"><?php echo $apellido;?></td>
				        </tr>
						<tr>
							<td>Empresa</td>
						    <td colspan="3"><?php echo $empresa;?></td>
						</tr>
				        
						<tr>
						  <td><?php
						  $tipo = array("Sel. uno", "RUT","CI", "Pasaporte");
						   echo $tipo[$tiponif];?></td>
						  <td ><?php echo $nif;?></td>
							<td>Tipo</td>
							<td>
							<?php
								$tipox = array("Seleccione uno", "Cliente","MCC", "Aún no");
							      echo @$tipox[$tipo];
							?>
						</td>
				      </tr>
						<tr>
							<td>Tel&eacute;fono</td>
							<td><?php echo $telefono;?></td>

							<td>M&oacute;vil</td>
							<td width="50%"><?php echo $movil;?></td>
					    </tr>
						<tr>
							<td>Tel&eacute;fono 2</td>
							<td><?php echo $telefono2;?></td>

							<td>Fax</td>
							<td width="50%"><?php echo $fax;?></td>
					    </tr>					    
						<tr>
							<td>Email&nbsp;primario  </td>
							<td colspan="3"><?php echo $email;?></td>
					    </tr>
						<tr>
							<td>Email&nbsp;secundario</td>
							<td colspan="3"><?php echo $email2;?></td>
					    </tr>
					    <tr>
					    	<td>Direcci&oacute;n&nbsp;web </td>
							<td colspan="3"><?php echo $web;;?></td>
						</tr>
						<tr>
							<td>Contraseña  </td>
							<td colspan="3">******************</td>
					    </tr>
						<tr><td>Pregunta  </td>
						<td colspan="3">
							<?php
								$questions = array();
								$questions[0] = "Seleccione una Pregunta";
								$questions[1] = "¿En que ciudad nació?";
								$questions[2] = "¿Cúal es su color favorito?";
								$questions[3] = "¿En qué año se graduo de la facultad?";
								$questions[4] = "¿Cual es el segundo nombre de su novio/novia/marido/esposa?";
								$questions[5] = "¿Cúal es su auto favorito?";
								$questions[6] = "¿Cúal es el nombre de su madre?";

							echo $questions[$secQ];
							?>
						</select></td>
						</tr><tr><td>Respuesta</td><td colspan="3"><?php echo $secA;?></input>
						
						</td></tr><tr><td>Ejecutivo&nbsp;de&nbsp;cuenta</td>
					  <?php

					  ?>						
						<td colspan="3" ><?php
						if($codusuarios!='' and $codusuarios>0) {
						  	$query_usuario="SELECT * FROM usuarios WHERE estado='0' and codusuarios='$codusuarios'";
							$res_usuario=mysqli_query($GLOBALS["___mysqli_ston"], $query_usuario);
							$contador=0;
						echo mysqli_result($res_usuario, 0, "nombre") ." ". mysqli_result($res_usuario, 0, "apellido");
						}
						?>
						</td>						
						</tr>
					</table></td>
					        <td rowspan="14" align="left" valign="top">
					        &nbsp;
					        </td>
					
					<td>						
						<table class="fuente8" width="98%" cellspacing=3 cellpadding=3 border=0>	
				      
					  <?php
					  	$query_pais="SELECT * FROM paises WHERE codpais=".$codpais;
						$res_pais=mysqli_query($GLOBALS["___mysqli_ston"], $query_pais);
						$contador=0;
					  ?>
						<tr>
							<td width="15%">País</td>
							<td colspan="3">
								<?php
								if ($codpais == mysqli_result($res_pais, 0, "codpais")) {
									 echo mysqli_result($res_pais, 0, "nombre");
							   }
							   ?>				
							</td>
					    </tr>				      
						<tr>
						  <td>Direcci&oacute;n</td>
						  <td colspan="3"><?php echo $direccion;;?></td>
				      </tr>
						<tr>
							<td>C&oacute;digo&nbsp;postal </td>
							<td colspan="3"><?php echo $codpostal;?></td>
					    </tr>
						<tr>
						  <td>Localidad</td>
						  <td colspan="3"><?php echo $localidad;?></td>
				      </tr>
					  <?php
	

					  ?>
						<tr>
							<td width="15%">Departamento</td>
							<td colspan="3"><?php
								if($codprovincia!='') {
									$query_provincias="SELECT * FROM provincias WHERE codprovincia='$codprovincia'";
									$res_provincias=mysqli_query($GLOBALS["___mysqli_ston"], $query_provincias);
									echo mysqli_result($res_provincias, 0, "nombreprovincia");
								}
								?>
							</td>
				        </tr>
						<tr>
							<td width="15%">Forma&nbsp;de&nbsp;pago</td>
							<td colspan="3"><?php
							if($codformapago!='' and $codformapago>0) {
								$query_formapago="SELECT * FROM formapago WHERE borrado=0 AND codformapago='$codformapago'";
								$res_formapago=mysqli_query($GLOBALS["___mysqli_ston"], $query_formapago);
								$contador=0;
								echo mysqli_result($res_formapago, 0, "nombrefp");
								}
								?>
							</td>
				        </tr>
						<tr>
							<td width="15%">Entidad&nbsp;Bancaria</td>
							<td colspan="3">							
								<?php
								if($codentidad!='' and $codentidad>0) {
							  	$query_entidades="SELECT * FROM entidades WHERE borrado=0 AND codentidad='$codentidad'";
								$res_entidades=mysqli_query($GLOBALS["___mysqli_ston"], $query_entidades);
								$contador=0;
								 echo mysqli_result($res_entidades, 0, "nombreentidad");
								 }
								?>
							</td>
				        </tr>
						<tr>
							<td>Cuentabancaria</td>
							<td colspan="3"><?php echo $cuentabanco;?></td>
					    </tr>
						<tr>					  
						  
						  <tr>
						  <td>
						  Agencia&nbsp;de&nbsp;cargas</td><td>
						  <?php echo $agencia;?></td>
						</tr>	
											  
						<tr>
						<td colspan="4"><fieldset><legend>Recepción</legend>
							<table class="fuente8" width="100%" cellspacing="3" cellpadding="3" border="0">
							<tr>
						  <td>Día</td>
						  <td><?php echo $recepciondia;?></td>
						  <td>Hora</td>
						  <td><?php echo $recepcionhora;?></td>
						  <td>Contacto</td>
						  <td><?php echo $recepcioncontacto;?></td>
							</tr>
							</table>						
						</fieldset></td>
						
						</tr>  
						<tr>
						<td colspan="4"><fieldset><legend>Pagos</legend>
							<table class="fuente8" width="100%" cellspacing="0" cellpadding="3" border="0">
							<tr>
						  <td>Día</td>
						  <td><?php echo $pagodia;?></td>
						  <td>Hora</td>
						  <td><?php echo $pagohora;?></td>
						  <td>Contacto</td>
						  <td><?php echo $pagocontacto;?></td>
							</tr>
							</table>						
						</fieldset></td>
						
						</tr> 						  
			     		<tr>
							<td>Abonado/Service</td>
							<td colspan="4">
							<?php
							if($service!='') {
								$tipo = array("Seleccione un tipo", "Común","Abonado A", "Abonado B");
								echo $tipo[$service];
							}
							?>
							</td>
						  
				      </tr>
				      <tr>
				      <td>Horas&nbsp;Asig./Mes:</td>
				      <td><?php echo $horas;?></td>
				      
				      <td>Cód.&nbsp;Cont.</td><td>
							<?php
								if($plancuenta!='') {
								$query_busqu="SELECT * FROM plandecuentas WHERE borrado=0 AND codplan='".$plancuenta."'";
								$rs_busqu=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqu);
								echo mysqli_result($rs_busqu, 0, "codplan").' - '. mysqli_result($rs_busqu, 0, "nombre");
								}	
							?>

				        	</td>
				      </tr>	
					</table>
					</td></tr></table>
			  </div>
			   <br style="line-height:5px">
				<div>
						<button class="boletin" onClick="aceptar();" onMouseOver="style.cursor=cursor"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Salir</button>
			  </div>
			 </div>
		  </div>
		</div>
	</body>
</html>
