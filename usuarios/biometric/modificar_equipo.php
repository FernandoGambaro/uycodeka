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
require_once('../../class/class_session.php');
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
	echo "<script>location.href='../../index.php'; </script>";
   //header("Location:../../index.php");	

} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
  		//header("Location:../../index.php");	
		echo "<script>window.top.location.href='../../index.php'; </script>";
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

include ("../../conectar.php");
include("../../common/verificopermisos.php");
include("../../common/funcionesvarias.php");

////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 
////header('Content-Type: text/html; charset=UTF-8');

$codbiometric=$_GET["codbiometric"];

$query="SELECT * FROM biometric WHERE codbiometric='$codbiometric'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);


?>

<html>
<head>
		<link href="../../css3/estilos.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="../../funciones/validar.js"></script>
		
		<script src="../../js3/jquery.min.js"></script>

		<link rel="stylesheet" href="../../js3/colorbox.css" />
		<script src="../../js3/jquery.colorbox.js"></script>
		
		<link rel="stylesheet" href="../../js3/jquery.toastmessage.css" type="text/css">
		<script src="../../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../../js3/message.js" type="text/javascript"></script>
		<script src="../../js3/jquery.msgBox.js" type="text/javascript"></script>
		<link href="../../js3/msgBoxLight.css" rel="stylesheet" type="text/css">	

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../../css3/css/font-awesome.min.css">

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
			event.preventDefault();
			document.getElementById("formulario").reset();
		}
		function consultar() {
			var ip=$("#adireccionip").val();
			var udp_port=$("#audpport").val();

			if ($("#adireccionip").val().length>5 && $("#audpport").val()>0) {
				parent.showModal(1);
				jQuery.ajax({
				 type: "POST",
				 url: "consulto.php",
				 data: {ip:ip, udp_port:udp_port },
				 async: true,
				 cache: false,
				 success: function(data){ 
				var thisValue = data.replace(new RegExp('"', 'g'),"");;
				thisValue =thisValue.replace(new RegExp('u0000', 'g'),"");
				thisValue =thisValue.replace(/[~!@#$%^&()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi,'');
		
				var aplataform=thisValue.split("*")[0];
				var afirmware=thisValue.split("*")[1];
				var aserialnumber=thisValue.split("*")[2];
				var adevicename=thisValue.split("*")[3];
		
				$("#aplataform").val(aplataform);
				$("#afirmware").val(afirmware);
				$("#aserialnumber").val(aserialnumber);		 
				$("#adevicename").val(adevicename);		
				parent.showModal(0); 
				},
				  error: function() {
				   parent.showModal(0);
				   showWarningToast("Error");
				}
				});
		} 	else {
			showWarningToast("Ingrese dirección válida");
		}		
	}
		</script>

<script type="text/javascript">

$(document).unbind('keypress');
$(document).keydown(function(e) {
/*//alert(e.keyCode);*/
    switch(e.keyCode) { 
        case 46:
/*/alert(newItemDel);*/
         if(newItemDel!='') {
         $(newItemDel).find('option:selected').remove();
         newItemDel="";
         }
        break;
        case 112:
            showWarningToast('Ayuda aún no disponible...');
        break;
        case 13:
			var $targ = $(e.target);
        if (!$targ.is("textarea") && !$targ.is(":button,:submit")) {
            var focusNext = false;
            $(this).find(":input:visible:not([disabled],[readonly]), a").each(function(){
                if (this === e.target) {
                    focusNext = true;
                }
                else if (focusNext){
                    $(this).focus();
                    return false;
                }
            });

            return false;
        }
        break;
        
    }
});

</script>

<style type="text/css">
@font-face
    {
    font-family:'dotsfont';
    src:url('dotsfont.eot');
    src:url('dotsfont.eot?#iefix')  format('embedded-opentype'),
        url('dotsfont.svg#font')    format('svg'),
        url('dotsfont.woff')        format('woff'),
        url('dotsfont.ttf')         format('truetype');
    font-weight:normal;
    font-style:normal;
}
</style>

<title>Equipo</title>
</head>
<body >
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">MODIFICAR DATOS DEL EQUIPO</div>
				<form  id="formulario" name="formulario" action="guardar_equipo.php" method="POST">
				<div id="frmBusqueda">
					<table class="fuente8" cellspacing=0 cellpadding=2 border=0 >
						<tr><td>&nbsp;Nombre&nbsp;</td><td>
						<input  name="Anombre" id="anombre" value="<?php echo mysqli_result($rs_query, 0, "nombre")?>" />
						</td>

					  <?php
					  	$codubicacion=mysqli_result($rs_query, 0, "codubicacion");
					  	$query_ubicacion="SELECT codubicacion,nombre FROM ubicaciones WHERE borrado=0 ORDER BY nombre ASC";
						$res_ubicacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_ubicacion);
						$contador=0;
					  ?>
							<td>Ubicaci&oacute;n</td>
							<td colspan="2"><select id="codubicacion" name="Acodubicacion" class="comboGrande" data-index="8">
							<option value="0">Todas las ubicaciones</option>
								<?php
								while ($contador < mysqli_num_rows($res_ubicacion)) { 
									if ( mysqli_result($res_ubicacion, $contador, "codubicacion") == $codubicacion) { ?>
								<option value="<?php echo mysqli_result($res_ubicacion, $contador, "codubicacion")?>" selected><?php echo mysqli_result($res_ubicacion, $contador, "nombre")?></option>
								<?php } else { ?> 
								<option value="<?php echo mysqli_result($res_ubicacion, $contador, "codubicacion")?>"><?php echo mysqli_result($res_ubicacion, $contador, "nombre")?></option>
								<?php }
								$contador++;
								} ?>				
								</select>							</td>
					    </tr>						
						<tr>
						
						<td width="100px">&nbsp;Dirección IP/WEB</td>
						<td><input type="text" size="30" name="Adireccionip" id="adireccionip" value="<?php echo mysqli_result($rs_query, 0, "direccionip")?>" class="cajaGrande"></input>
						</td>
						<td colspan="2">
												<button class="boletin" onClick="event.preventDefault();consultar();" onMouseOver="style.cursor=cursor">
												<i class="fa fa-camera-retro" aria-hidden="true"></i>&nbsp;Consultar </button>
						</td>
						<tr>
						<td>&nbsp;Puerto UDP</td>
						<td><input  type="text" size="30" name="audpport" id="audpport" value="<?php echo mysqli_result($rs_query, 0, "udp_port")?>" class="cajaPequena" ></input>
						</td>
						<td>
						<input type="hidden" name="ainternalid" value="1">
						<label>  Principal
						<?php
						$estado=mysqli_result($rs_query, 0, "internal_id");
						 if ( $estado=='' or $estado==0) {
						?>
						<input type="checkbox" name="ainternalid" value="0" checked> Si
						<?php } else {
						?>
						<input type="checkbox" name="ainternalid" value="0" onclick="showWarningToast('Si cambia a activo, éste equipo será el principal para setear los datos de usuario');"> Si
						<?php }
						?>
							<span></span>
						</label>
						</td>
							
						</tr>
						<tr>
						<td>&nbsp;Firmware</td>
						<td><input type="text" size="30" name="afirmware" id="afirmware" value="<?php echo mysqli_result($rs_query,0,"firmware");?>" class="cajaGrande"></input>
						</td>
						<td>&nbsp;Número de serie</td>
						<td><input type="text" size="30" name="aserialnumber" id="aserialnumber" value="<?php echo mysqli_result($rs_query,0,"serialnumber");?>" class="cajaGrande"></input>
						</td></tr>
						
						<tr>
						<td width="100px">&nbsp;Plataforma</td>
						<td><input  type="text" size="30" name="aplataform" id="aplataform" value="<?php echo mysqli_result($rs_query, 0, "plataform")?>" class="cajaGrande"></input>
						</td>

						<td>&nbsp;Device name</td>
						<td><input type="text" size="30" name="adevicename" id="adevicename" value="<?php echo mysqli_result($rs_query, 0, "devicename")?>" class="cajaGrande" ></input>
						</td></tr>
						</table>
						</div>
			  <br style="line-height:5px">
				<div>
						<button class="boletin" onClick="validar(formulario,true);" onMouseOver="style.cursor=cursor"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Guardar</button>
						<button class="boletin" onClick="limpiar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Limpiar</button>										
						<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
					<input id="accion" name="accion" value="modificar" type="hidden">
					<input id="codbiometric" name="codbiometric" value="<?php echo $codbiometric;?>" type="hidden">
			      </div>
				</form>

					</div>				
			</div>
		</div>
		
	
</body></html>