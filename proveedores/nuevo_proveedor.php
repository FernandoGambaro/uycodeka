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
?>
<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script src="js/jquery.min.js"></script>
<link rel="stylesheet" href="js/jquery.toastmessage.css" type="text/css">
<script src="js/jquery.toastmessage.js" type="text/javascript"></script>
<script src="js/message.js" type="text/javascript"></script>
<script type="text/javascript" src="../funciones/validar2.js"></script>

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">

<script type="text/javascript">

$(document).keydown(function(e) {
    switch(e.keyCode) { 
        case 13:
			e.preventDefault();
        var $this = $(e.target);
        var index = parseFloat($this.attr('data-index'));
        $('[data-index="' + (index + 1).toString() + '"]').focus();
        	if (index==15) {
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
				<div id="tituloForm" class="header">NUEVO PROVEEDOR </div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="#">
				<table class="fuente8" width="100%" border="0"><tr><td valign="top">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="15%">Nombre</td>
						    <td width="43%"><input name="Anombre" type="text" class="cajaGrande" id="aNombre" size="45" maxlength="45" data-alt="Nombre" data-index="1"></td>
					        <td width="42%" rowspan="12" align="left" valign="top"></td>
						</tr>
						<tr>
						  <td>RUT</td>
						  <td><input id="nif" type="text" class="cajaPequena" name="anif" maxlength="15" data-alt="RUT" data-index="2"></td>
				      </tr>
						<tr>
						  <td>Direcci&oacute;n</td>
						  <td><input name="adireccion" type="text" class="cajaGrande" id="direccion" size="45" maxlength="45" data-index="3"></td>
				      </tr>
						<tr>
						  <td>Localidad</td>
						  <td><input name="alocalidad" type="text" class="cajaGrande" id="localidad" size="35" maxlength="35" data-index="4"></td>
				      </tr>
					  <?php
					  	$query_provincias="SELECT * FROM provincias ORDER BY nombreprovincia ASC";
						$res_provincias=mysqli_query($GLOBALS["___mysqli_ston"], $query_provincias);
						$contador=0;
					  ?>
						<tr>
							<td width="15%">Departamento</td>
							<td width="43%"><select id="cboProvincias" name="cboProvincias" class="comboGrande" data-index="5">
							<option value="0">Seleccione una provincia</option>
								<?php
								while ($contador < mysqli_num_rows($res_provincias)) { ?>
								<option value="<?php echo mysqli_result($res_provincias, $contador, "codprovincia")?>"><?php echo mysqli_result($res_provincias, $contador, "nombreprovincia")?></option>
								<?php $contador++;
								} ?>				
								</select>	
								</td>
				        </tr>
						<?php
					  	$query_entidades="SELECT * FROM entidades WHERE borrado=0 ORDER BY nombreentidad ASC";
						$res_entidades=mysqli_query($GLOBALS["___mysqli_ston"], $query_entidades);
						$contador=0;
					  ?>
						<tr>
							<td width="15%">Entidad&nbsp;Bancaria</td>
							<td width="43%"><select id="cboBanco" name="cboBanco" class="comboGrande" data-index="6">
							<option value="0">Seleccione una Entidad Bancaria</option>
									<?php
								while ($contador < mysqli_num_rows($res_entidades)) { ?>
								<option value="<?php echo mysqli_result($res_entidades, $contador, "codentidad")?>"><?php echo mysqli_result($res_entidades, $contador, "nombreentidad")?></option>
								<?php $contador++;
								} ?>
											</select>	
							</td>
				        </tr>
						<tr>
							<td>Cuenta&nbsp;bancaria</td>
							<td><input id="cuentabanco" type="text" class="cajaGrande" name="acuentabanco" maxlength="20" data-index="7"></td>
					    </tr>
						</table></td><td valign="top">					    
					    <table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td>C&oacute;digo&nbsp;postal </td>
							<td><input id="codpostal" type="text" class="cajaPequena" name="acodpostal" maxlength="5" data-index="8"></td>
					    </tr>
						<tr>
							<td>Tel&eacute;fono </td>
							<td><input id="telefono" name="atelefono" type="text" class="cajaPequena" maxlength="14" data-alt="Teléfono" data-index="9">
							&nbsp;M&oacute;vil&nbsp;<input id="movil" name="amovil" type="text" class="cajaPequena" maxlength="14" data-index="10">
							</td>
					    </tr>
						<tr>
							<td>Rubro</td>
							<td><input id="rubro" name="arubro" type="text" class="cajaGrande" maxlength="64" data-index="11"></td>
					    </tr>
						<tr>
							<td>Correo&nbsp;electr&oacute;nico  </td>
							<td><input name="aemail" type="text" class="cajaGrande" id="email" size="35" maxlength="35" data-index="12"></td>
					    </tr>
						<tr>
							<td>Direcci&oacute;n&nbsp;web </td>
							<td><input name="aweb" type="text" class="cajaGrande" id="web" size="45" maxlength="45" data-index="13"></td>
					    </tr>
					    <tr>
							<td>Nº&nbsp;Cuenta&nbsp;bancaria</td>
							<td><input id="cuentabanco" type="text" class="cajaGrande" name="acuentabanco" maxlength="20"  data-index="14"></td>
					    </tr>	
						<tr>
							<td>Horario de atención</td>
							<td><input id="horario" type="text" class="cajaGrande" name="horario" maxlength="20" data-index="15"></td>
					    </tr>
					</table>
				</td></tr></table>
			  </div>
			  <br style="line-height:5px">
				<div>
						<button class="boletin" onClick="validar(formulario,true);event.preventDefault();" onMouseOver="style.cursor=cursor"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Guardar</button>
						<button class="boletin" onClick="limpiar();" onMouseOver="style.cursor=cursor"><i class="fa fa-eraser" aria-hidden="true"></i>&nbsp;Limpiar</button>										
						<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
					<input id="accion" name="accion" value="alta" type="hidden">
					<input id="id" name="id" value="" type="hidden">
			  </div>
			  </form>
		  </div>
		  </div>
		</div>
					
	</body>
</html>
