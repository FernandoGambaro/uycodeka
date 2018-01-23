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
@$paginacion=$s->data['alto'];

if($paginacion<=0) {
	$paginacion=20;
}
$Seleccionados=array();
$Seleccionados=@$s->data['Selected'];

include ("../conectar.php");
include("../common/verificopermisos.php");
include("../common/funcionesvarias.php");

//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
//header('Content-Type: text/html; charset=UTF-8');

$codproveedor=$_GET["codproveedor"];

$query="SELECT * FROM proveedores WHERE codproveedor='$codproveedor'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

?>
<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="../funciones/validar.js"></script>
<script src="js/jquery.min.js"></script>
<link rel="stylesheet" href="js/jquery.toastmessage.css" type="text/css">
<script src="js/jquery.toastmessage.js" type="text/javascript"></script>
<script src="js/message.js" type="text/javascript"></script>
<script type="text/javascript">

$(document).keydown(function(e) {
    switch(e.keyCode) { 
        case 13:
			e.preventDefault();
        var $this = $(e.target);
        var index = parseFloat($this.attr('data-index'));
        $('[data-index="' + (index + 1).toString() + '"]').focus();
        	if (index==12) {
        		validar(formulario,true);
        	}
        break;
        case 112:
            showWarningToast('Ayuda aún no disponible...');
        break;
       
	 }
});
</script>		
<script type="text/javascript">
$(document).ready( function(){
	$("form:not(.filter) :input:visible:enabled:first").focus();
});
</script>		
		<script language="javascript">
		
		function cancelar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}
		
		function limpiar() {
			document.getElementById("formulario").reset();
		}
		
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">VER PROVEEDOR <?php echo $codproveedor?> </div>
				<div id="frmBusqueda">
				<table class="fuente8" width="100%" border="0"><tr><td>
					<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="15%">Nombre</td>
						    <td width="43%"><input NAME="Anombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" value="<?php echo mysqli_result($rs_query, 0, "nombre")?>" data-index="1"></td>
				        </tr>
						<tr>
						  <td>RUT</td>
						  <td><input id="nif" type="text" class="cajaPequena" NAME="anif" maxlength="15" value="<?php echo mysqli_result($rs_query, 0, "nif")?>" data-index="2"></td>
				      </tr>
					  <?php
					  	$codpais=mysqli_result($rs_query, 0, "codpais");
					  	$query_pais="SELECT * FROM paises ORDER BY nombre ASC";
						$res_pais=mysqli_query($GLOBALS["___mysqli_ston"], $query_pais);
						$contador=0;
					  ?>
						<tr>
							<td width="15%">País</td>
							<td colspan="3"><select id="codpais" name="codpais" class="comboGrande" data-index="3">
							<option value="0">Seleccione una pais</option>
								<?php
								while ($contador < mysqli_num_rows($res_pais)) { 
									if ($codpais == mysqli_result($res_pais, $contador, "codpais") and $codpais !=0) {?>
								<option value="<?php echo mysqli_result($res_pais, $contador, "codpais")?>" selected="selected"><?php echo mysqli_result($res_pais, $contador, "nombre");?></option>
								<?php } elseif($codpais==0 and mysqli_result($res_pais, $contador, "codpais")==238) {?>
								<option value="<?php echo mysqli_result($res_pais, $contador, "codpais")?>" selected="selected"><?php echo mysqli_result($res_pais, $contador, "nombre");?></option>
								<?php } else{ ?>
									<option value="<?php echo mysqli_result($res_pais, $contador, "codpais")?>"><?php echo mysqli_result($res_pais, $contador, "nombre");?></option>
								<?php } $contador++;
								} ?>				
								</select>
							</td>
						</tr>					      
						<tr>
						  <td>Direcci&oacute;n</td>
						  <td><input NAME="adireccion" type="text" class="cajaGrande" id="direccion" size="45" maxlength="45" value="<?php echo mysqli_result($rs_query, 0, "direccion")?>" data-index="4"></td>
				      </tr>
						<tr>
						  <td>Localidad</td>
						  <td><input NAME="alocalidad" type="text" class="cajaGrande" id="localidad" size="35" maxlength="35" value="<?php echo mysqli_result($rs_query, 0, "localidad")?>" data-index="5"></td>
				      </tr>
					  <?php
					  	$codprovincia=mysqli_result($rs_query, 0, "codprovincia");
					  	$query_provincias="SELECT * FROM provincias ORDER BY nombreprovincia ASC";
						$res_provincias=mysqli_query($GLOBALS["___mysqli_ston"], $query_provincias);
						$contador=0;
					  ?>
						<tr>
							<td width="15%">Departamento</td>
							<td width="43%"><select id="cboProvincias" name="cboProvincias" class="comboGrande" data-index="6">
							<option value="0">Seleccione una provincia</option>
								<?php
								while ($contador < mysqli_num_rows($res_provincias)) { 
									if ($codprovincia == mysqli_result($res_provincias, $contador, "codprovincia")) {?>
								<option value="<?php echo mysqli_result($res_provincias, $contador, "codprovincia")?>" selected="selected"><?php echo mysqli_result($res_provincias, $contador, "nombreprovincia")?></option>
								<?php } else { ?>
									<option value="<?php echo mysqli_result($res_provincias, $contador, "codprovincia")?>"><?php echo mysqli_result($res_provincias, $contador, "nombreprovincia")?></option>
								<?php } $contador++;
								} ?>				
								</select>							</td>
				        </tr>						
						<?php
						$codentidad=mysqli_result($rs_query, 0, "codentidad");
					  	$query_entidades="SELECT * FROM entidades WHERE borrado=0 ORDER BY nombreentidad ASC";
						$res_entidades=mysqli_query($GLOBALS["___mysqli_ston"], $query_entidades);
						$contador=0;
					  ?>
						<tr>
							<td width="15%" height="26">Entidad Bancaria</td>
							<td width="43%"><select id="cboBanco" name="cboBanco" class="comboGrande" data-index="7">
							<option value="0">Seleccione una Entidad Bancaria</option>
									<?php
								while ($contador < mysqli_num_rows($res_entidades)) { 
									if ($codentidad == mysqli_result($res_entidades, $contador, "codentidad")) { ?>
								<option value="<?php echo mysqli_result($res_entidades, $contador, "codentidad")?>" selected="selected"><?php echo mysqli_result($res_entidades, $contador, "nombreentidad")?></option>
								<?php } else { ?>
								<option value="<?php echo mysqli_result($res_entidades, $contador, "codentidad")?>"><?php echo mysqli_result($res_entidades, $contador, "nombreentidad")?></option>
								<?php } $contador++;
								} ?>
											</select>							</td>
				        </tr>
						</table></td><td>					    
					    <table class="fuente8" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td>C&oacute;digo&nbsp;postal </td>
							<td><input id="codpostal" type="text" class="cajaPequena" NAME="acodpostal" maxlength="5" value="<?php echo mysqli_result($rs_query, 0, "codpostal")?>" data-index="8"></td>
					    </tr>
						<tr>
							<td>Tel&eacute;fono </td>
							<td><input id="telefono" name="atelefono" type="text" class="cajaPequena" maxlength="14" value="<?php echo mysqli_result($rs_query, 0, "telefono")?>" data-index="9">
							&nbsp;M&oacute;vil&nbsp;<input id="movil" name="amovil" type="text" class="cajaPequena" maxlength="14" value="<?php echo mysqli_result($rs_query, 0, "movil")?>" data-index="10">
							</td>
					    </tr>
						<tr>
							<td>Rubro</td>
							<td><input id="movil" name="amovil" type="text" class="cajaPequena" maxlength="14" value="<?php echo mysqli_result($rs_query, 0, "rubro")?>" data-index="11"></td>
					    </tr>
						<tr>
							<td>Correo&nbsp;electr&oacute;nico  </td>
							<td><input NAME="aemail" type="text" class="cajaGrande" id="email" size="35" maxlength="35" value="<?php echo mysqli_result($rs_query, 0, "email")?>" data-index="12"></td>
					    </tr>
												<tr>
							<td>Direcci&oacute;n&nbsp;web </td>
							<td><input NAME="aweb" type="text" class="cajaGrande" id="web" size="45" maxlength="45" value="<?php echo mysqli_result($rs_query, 0, "web")?>" data-index="13"></td>
					    </tr>
						<tr>
							<td>Cuenta&nbsp;bancaria</td>
							<td><input id="cuentabanco" type="text" class="cajaGrande" NAME="acuentabanco" maxlength="20" value="<?php echo mysqli_result($rs_query, 0, "cuentabancaria")?>" data-index="14"></td>
					    </tr>					    
					</table>
				</td></tr></table>
			  </div>
			  <br style="line-height:5px">
				<div>
						<button class="boletin" onClick="cancelar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
			  
			  </div>
			  </div>
		  </div>
		</div>
				
	</body>
</html>
