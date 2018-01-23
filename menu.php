<?php

?><?php
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
 
/*
Include the session class. Modify path according to where you put the class
file.
*/
require_once('class/class_session.php');

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

if((!@$s->data['isLoggedIn']) || !(@$s->data['isLoggedIn']))
{
	header("Location:index.php");
} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
	       header("Location:index.php");	
	       exit;
   }
   $s->data['loggedAt']= time();/*/ update last accessed time*/
   $s->save();
}

//header('Content-Type: text/html; charset=UTF-8'); 

date_default_timezone_set('America/Montevideo');
session_start();

$status_msg = "";

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];
$ip=$s->data['IP'];

$ShowName=$UserNom. " " .$UserApe;

include("conectar.php");
include("common/funcionesvarias.php");
include("common/verificopermisos.php");

 

if ($UserID !=0)
logger($UserID, "Ingreso al sistema");
//else
//header ("Location:index.php?act=logout");

?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<link href='https://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
		<meta charset="utf-8">
		<title>UYCodeka Menú</title>
		<link href="css3/style.css?t=<%= DateTime.Now.Ticks %>" media="screen" rel="stylesheet" type="text/css" />
		<link href="css3/iconic.css?t=<%= DateTime.Now.Ticks %>" media="screen" rel="stylesheet" type="text/css" />
		<script src="js3/prefix-free.js?t=<%= DateTime.Now.Ticks %>"></script>
	<link rel="stylesheet" href="css3/slide.css"> <!-- Resource style -->
	<script src="js3/modernizr.js"></script> <!-- Modernizr -->
		<link href="estilos/estilos.css" type="text/css" rel="stylesheet">
		
<!-- iconos para los botones -->       
<link rel="stylesheet" href="css3/css/font-awesome.min.css">


<?php
$miVariable =  @$_COOKIE["variable"];
	$s->data['alto']= floor($miVariable);
   $s->save();
   
	//$_SESSION['alto'] = $miVariable;
?>	

<script type="text/javascript">
		function setIframeHeight(iframeName) {
		  var iframeEl = document.getElementById? document.getElementById(iframeName): document.all? document.all[iframeName]: null;
		  if (iframeEl) {
		  iframeEl.style.height = "auto"; 
		  var h = alertSize();
		  var new_h = (h-55);
		  document.getElementById("alto").value=new_h;
		  iframeEl.style.height = new_h + "px";

		  }
		}

		function alertSize() {
		  var myHeight = 0;
		  if( typeof( window.innerWidth ) == 'number' ) {
		    //Non-IE
		    myHeight = window.innerHeight;
		  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
		    //IE 6+ in 'standards compliant mode'
		    myHeight = document.documentElement.clientHeight;
		  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
		    //IE 4 compatible
		    myHeight = document.body.clientHeight;
		  }
		
		  //window.alert( 'Height = ' + myHeight );
		  return myHeight;
		}
		
function opinion() {
	var noteId="ayuda/opinion.php?ask=1&control=0";
	var w='800px';
	var h='90%';
	OpenNote(noteId,w,h, scroll=false);
}		
</script>



<?php
$miVariable = @ $_COOKIE["variable"];
	$s->data['alto']= floor($miVariable);
   $s->save();
   
	$_SESSION['alto'] = $miVariable;
?>			

 <style>
   
   .player-frame-wrapper {
  position: relative;
  width: 100%;
 
  /* room for slim timeline */
  padding-bottom: 3px;
}
.player-frame-wrapper-ratio {
  /* same as player ratio */
  padding-top: 41.67%;
}
 
.player-frame {
  /* make iframe fill wrapper */
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border: none;
}

#estado {
    position: absolute;
    height: 98%;
    background-color: green;
  width: 1%;
}

#barContainer {
  width:300px;
  height:15px;
  border:1px solid #ccc;
}

#bar {
  height:100%;
  width:0%;
  background:#0198E1;
}
   </style>	
<style>
#myProgress {
    position: relative;
    width: 100%;
    height: 20px;
    background-color: grey;
}
#myBar {
    position: absolute;
    width: 1%;
    height: 98%;
    background-color: green;
}

</style>	
<link rel="stylesheet" href="css3/jquery.loadingModal.css">
	</head>
<body onload="setIframeHeight('principal');" onresize="setIframeHeight('principal');">

	<div class="wrap">
		<nav><div style="background-color: #FFFFFF;">
		<img src="datos_sistema/loadimage.php?id=11&default=1" height="37" alt="" style="background-color:#FFF; float: left;color: #000;padding: 3px 5px;vertical-align: middle;
    margin: auto; "></div>
		<ul class="menu">
			<li><a href="central2.php" target="principal"><span class="fa-stack"><i class="fa fa-home fa-2x" aria-hidden="true"></i></span>&nbsp;Inicio</a></li>
			<li><a href="#"><span class="fa-stack"><i class="fa fa-briefcase fa-2x" aria-hidden="true"></i></span>&nbsp;Trabajos</a>
			<ul>
				<li><a href="./controlhoras/index.php" target="principal"><span class="fa-stack"><i class="fa fa-clock-o" aria-hidden="true"></i></span>Control Horas</a></li>
				<li style="height: 2px; top: -14px;">&nbsp;____________________&nbsp;</li>
				<li><a href="./albaranes_clientes/index.php" target="principal"><span class="fa-stack"><i class="fa fa-gears" aria-hidden="true"></i></span>Orden de pedido</a></li>
				<li><a href="./presupuestos_clientes/index.php" target="principal"><span class="fa-stack"><i class="fa fa-puzzle-piece" aria-hidden="true"></i></span>Presupuestos</a></li>
			</ul>
			</li>
			<li><a href="#"><span class="fa-stack"><i class="fa fa-book fa-2x" aria-hidden="true"></i></span>&nbsp;Documentos</a>
			<ul>
				<li><a href="./clientes/autofacturas/index.php" target="principal"><span class="fa-stack"><i class="fa fa-handshake-o" aria-hidden="true"></i></span>Prog. auto facturas</a></li>
				<li><a href="./facturas_clientes/index.php" target="principal"><span class="fa-stack"><i class="fa fa-handshake-o" aria-hidden="true"></i></span>Ventas</a></li>
				<li><a href="./cobros/index.php" target="principal"><span class="fa-stack"><i class="fa fa-money" aria-hidden="true"></i></span>Cobros rápido</a></li>
				<li><a href="./recibos/index.php" target="principal"><span class="fa-stack"><i class="fa fa-money" aria-hidden="true"></i></span>Cobros</a></li>
				<li><a href="./facturas_proveedores/index.php" target="principal"><span class="fa-stack"><i class="fa fa-shopping-cart" aria-hidden="true"></i></span>Compras</a></li>
				<li><a href="./pagos/index.php" target="principal"><span class="fa-stack"><i class="fa fa-money" aria-hidden="true"></i></span>Pagos</a></li>
				<li style="height: 2px; top: -14px;">&nbsp;____________________&nbsp;</li>
				<li><a href="./pagosdgi/index.php" target="principal"><span class="fa-stack"><i class="fa fa-money" aria-hidden="true"></i></span>Pagos DGI</a></li>
			</ul>
			</li>
			<li><a href="#"><span class="fa-stack"><i class="fa fa-pencil fa-2x" aria-hidden="true"></i></span>&nbsp;Reportes</a>
			<ul>
				<li><a href="./reportes/index.php" target="principal"><span class="fa-stack"><i class="fa fa-shopping-basket" aria-hidden="true"></i></span>Varios</a></li>
				<li><a href="./ventas/index.php" target="principal"><span class="fa-stack"><i class="fa fa-flag-o" aria-hidden="true"></i></span>Ventas</a></li>
				<li><a href="./abonados/index.php" target="principal"><span class="fa-stack"><i class="fa fa-users" aria-hidden="true"></i></span>Abonados</a></li>
				<li><a href="./ventas_articulos/index.php" target="principal"><span class="fa-stack"><i class="fa fa-exchange" aria-hidden="true"></i></i></span>Articulos</a></li>
			</ul>
			</li>			
		</ul>

<div id="msganio" style="display:flex; float: left;text-align: left; color: #fff; font-size: 15px;padding: 11px 25px;  margin:0 auto; text-align: center; font-weight: bold; z-index: 15;">UYCodeka&nbsp;
    
</div>
<script src="js3/jquery-2.1.1.js"></script>


<div style="position:inherit; float: left; margin-top:1px; font-family: Tahoma; 
font-weight: bold; font-size: 11px; color:#000; display:flex; top:10px; ">
	<div id="check" style="display:none; ">

	<div style="display:flex; top:-3px; border: 1px solid #0099CC; padding: 1px;
	position: relative;border-radius: 2px; text-align: left; background: #fff; 
	box-shadow: inset 1px 1px 1px rgba(0, 0, 0, 0.12); width: 139px; height: 18px;">
  		<div id="estado"></div>	
  		<div id="estadotxt" style="position: relative; top:5px; z-index: 99;"></div>
  		
  	
	</div>
	
	</div>&nbsp;
<div id="cancel" style="display: none; float: left; margin-top:-6px;">
<button class="boletin" style=" background-color: #FFFFFF;"> Cancelar</button>
</div>
<div style="clear:both; font-size:1px;"></div>
</div>
  


		<ul class="menu2">
			<li><a href="salir.php" ><span class="fa-stack"><i class="fa fa-sign-out fa-2x" aria-hidden="true"></i></span>Salir</a></li>
				<li><a href="#"><span class="fa-stack"><i class="fa fa-cog fa-2x" aria-hidden="true"></i></span>Mantenimiento</a>
				<ul>
		<?php	
		$leer=verificopermisos('mantenimiento', 'leer', $UserID);
		$escribir=verificopermisos('mantenimiento', 'escribir', $UserID);
		$modificar=verificopermisos('mantenimiento', 'modificar', $UserID);
		$eliminar=verificopermisos('mantenimiento', 'eliminar', $UserID);		
			if ( $UserTpo == 2 or ($leer=="true" or $escribir=="true" or $modificar=="true" )) {
					$leer=verificopermisos('clientes', 'leer', $UserID);
					if ( $UserTpo == 2 or $leer=="true") { ?>
					<li><a href="./clientes/index.php" target="principal">Clientes</a></li>	
					<?php } ?>			
					<li><a href="./proveedores/index.php" target="principal">Proveedores</a></li>				
					<li><a href="./usuarios/index.php" target="principal">Usuarios</a></li>				
					<li><a href="./articulos_transito/index.php" target="principal">Artículos en tránsito</a></li>				
					<li style="height: 2px; top: -14px;">&nbsp;____________________&nbsp;</li>			
					<li><a href="./articulos/index.php" target="principal">Artículos</a></li>				
					<li><a href="./familias/index.php" target="principal">Familias artículos</a></li>				
					<li><a href="./sectores/index.php" target="principal">Sectores de la empresa</a></li>
					<li><a href="./datos_sistema/index.php" target="principal">Datos del sistema</a></li>
					<li><a href="./usuarios/biometric/index.php" target="principal">Equipos huella digital</a></li>
					<li><a href="./entidades/index.php" target="principal">Bancos</a></li>
					<li><a href="./plandecuentas/index.php" target="principal">Plan de cuentas</a></li>
					<li style="height: 2px; top: -14px;">&nbsp;____________________&nbsp;</li>			
					<li><a href="./ubicaciones/index.php" target="principal">Locales</a></li>				
					<li><a href="./embalajes/index.php" target="principal">Embalajes</a></li>				
					<li><a href="./formaspago/index.php" target="principal">Formas de pago</a></li>				
					<li><a href="./tipocambio/index.php" target="principal">Tipo de cambio</a></li>				
					<li><a href="./impuestos/index.php" target="principal">Impuestos</a></li>				
					<li><a href="./monedas/index.php" target="principal">Monedas</a></li>				
					<li><a href="./etiquetas/index.php" target="principal">Etiquetas</a></li>	
					<li style="height: 2px; top: -14px;">&nbsp;____________________&nbsp;</li>
					<li><a href="./cron/index.php" target="principal"><span class="fa-stack"><i class="fa fa-clock-o" aria-hidden="true"></i></span>Tareas programadas</a>

					<li><a href="./phpjobscheduler/pjsfiles/" target="principal"><span class="fa-stack"><i class="fa fa-clock-o" aria-hidden="true"></i></span>PhpJobScheduler</a>

		<?php } ?>								
					<li><a href="#" onclick="opinion();">Sugerencias</a></li>	
					<li><a href="./ayuda/index.php" target="_blank">Ayuda</a></li>	
					</li>					
				</ul>
			</li>

		</ul>		
		<div class="clearfix"></div>
	</nav>
	</div>


<div style="position:absolute; top: 44px; width:100%; text-align:center; bottom:0px; padding: 0px 0px 0px 0px;">


<iframe src="central2.php" name="principal" id="principal" title="principal" frameborder="0" width="100%" height="100%" marginheight="0" marginwidth="0"
 scrolling="yes" align="center" allowtransparency="true"></iframe>		

</div>


	<div class="cd-panel from-right" style="z-index: 9999;">
		<header class="cd-panel-header">
			<h1><div class="helptext">Ayuda</div></h1>
			<a href="#0" class="cd-panel-close">Close</a>
		</header>

		<div class="cd-panel-container">
			<div class="cd-panel-content">

   <!-- the player -->
<div class="player-frame-wrapper">
 
   <iframe id="help" src=""   class="player-frame"   mozallowfullscreen   webkitallowfullscreen    allowfullscreen></iframe>
 
    <div class="player-frame-wrapper-ratio"></div>
</div>			
				
			</div> <!-- cd-panel-content -->
		</div> <!-- cd-panel-container -->
	</div> <!-- cd-panel -->
<script src="js3/jquery-2.1.1.js"></script>

<script type="text/javascript" >
	function help(id,texto){
		$( "div.helptext" ).text( texto );
		$("#help").attr("src", "ayuda/ayuda.php?id="+id);
		$('.cd-panel').addClass('is-visible');	
	}

jQuery(document).ready(function($){
	//open the lateral panel

	//clode the lateral panel
	$('.cd-panel').on('click', function(event){
		if( $(event.target).is('.cd-panel') || $(event.target).is('.cd-panel-close') ) { 
			$('.cd-panel').removeClass('is-visible');
			$( "div.helptext" ).text('--');
			event.preventDefault();
			$("#help").attr("src", "ayuda/ayuda.php");
		}
	});
});
</script>


<div style="position: fixed; bottom: 0; left: 0; background:#2A2A2A; width:100%; height:27px; margin: 0 0 0 0; padding-buttom: 2px;" >
<div style="position: fixed; bottom:4px; left:20px; font-family: Tahoma; font-weight: bold; font-size: 11px; color:#fff;  padding: 4px 10px 4px 10px;">
<font color="white"> MCC © 2018 <a href="http://www.mcc.com.uy" title="MCC - Soporte Técnico">MCC</a></font>&nbsp;
<?php echo $ip;?></div>
</div>

<div style="position: fixed; bottom:4px; font-family: Tahoma; font-weight: bold; font-size: 11px; color:#fff; padding: 4px 10px 4px 10px; left:0; right: 0;  margin: 0 auto;" align="center">versión <?php echo $s->data['version'];?></div>


<div id="UserData" align="right" style="position: fixed; bottom:4px; right:20px; font-family: Tahoma; font-weight: bold; font-size: 11px; color:#fff; padding: 4px 10px 4px 10px;">Bienvenido
 <?php echo $ShowName;?></div>
</div>
<input type="hidden" name="alto" id="alto" value=""></input>

<script>   
/* Función para mostrar una barra de estado en la barra de menú*/

   jQuery.ajaxSetup({cache: false});       
	var xx;
   var numDoc;    //callGpsDiag(xx);             

    function callGpsDiag(xy,num,cod){
		xx=xy;
		numDoc=num;
		codnum=cod;
	  jQuery.ajax({
	    type: "POST",
	    url: "fpdf/monitoreo.php",
	    data: {execGpsDiag:xx, numDoc:numDoc},
	    async: true,
    	 cache: false,
	    success: function(data){ 
	    	if (data!="Fin") {
	        if (xx==1) {
	        	$("#check").show();
  				$("#cancel").show();
  				AvisarImpresion("F", xy, 1);
	        }
			move();
	        document.getElementById("estadotxt").innerHTML=" "+data;
	        setTimeout(function(){ 
	        xx=xx+1;
	            callGpsDiag(xx,numDoc,codnum);
	        },3500);
	        
	    	} else {
				var $p = $("#estado");
    				$p.stop()
      			.css("background-color","green");
     				$("#check").hide();
     				$("#cancel").hide();
	    	}
	   },
	     error: function() {
	     	     	$("#check").toggle();
     				$("#cancel").toggle();

	        //alert('fail');
	     }
  	 });        
	}

$("#cancel").click(function() {
	xx=0;
  	  jQuery.ajax({
	    type: "POST",
	    url: "fpdf/monitoreo.php",
	    data: {execGpsDiag:xx, numDoc:numDoc},
	    success: function(data){ 
	    	if (data=="Fin") {
      			$("#cancel").toggle();
	        		$("#check").toggle();
//	        	callGpsDiag(0);
	        }
	   },
	     error: function() {
	        alert('fallo al cancelar');
	     }
  	 });
});
            

function move() {
    var elem = document.getElementById("estado"); 
    var width = 1;
    var id = setInterval(frame, 50);
    function frame() {
        if (width >= 100) {
            clearInterval(id);
        } else {
            width++; 
            elem.style.width = width + '%'; 
         }
    }
}

 </script>

 <script>   
    jQuery.ajaxSetup({cache: false});       
		var xx=1;
		var file='';
        progressExcelBar(xx, file);             

    function progressExcelBar(xx, file){
    	if (file!='') {
	  jQuery.ajax({
	    type: "POST",
	    url: "excel/monitoreo.php",
	    data: {Estado: xx, File: file} ,
	    success: function(data){ 
	    	if (data!="Fin") {
	        if (xx==1) {
	        	$("#check").show();
  				$("#cancel").show();
	        }
			movelo();
			document.getElementById("estadotxt").innerHTML=" Generando Excel... ";
	        setTimeout(function(){ 
	        xx=xx+1;
	            progressExcelBar(xx, file);
	        },3100);
	        
	    	} else {
				var $p = $("#estado");
    				$p.stop()
      			.css("background-color","green");
      			if (xx>1) {
	        	$("#check").hide();
  				$("#cancel").hide();
		        	}
	    	}
	   },
	     error: function() {
	        //alert('fail');
	     }
  	 }); 
	}       
}
function movelo() {
    var elem = document.getElementById("estado"); 
    var width = 1;
    var id = setInterval(frame, 25);
    function frame() {
        if (width >= 100) {
            clearInterval(id);
        } else {
            width++; 
            elem.style.width = width + '%'; 
            //document.getElementById("label").innerHTML = width * 1 + '%';
        }
    }
}

</script>
<?php
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
?>
<img src="<?php echo $actual_link;?>/phpjobscheduler/firepjs.php?return_image=1" border="0" alt="phpJobScheduler"
 style="position:absolute; display: none;">	

		<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script-->
<script src="js3/jquery-2.1.1.js"></script>
		
		<link rel="stylesheet" href="js3/colorbox.css">
		<style type="text/css">
[data-notify="container"][class*="alert-pastel-"] {
	background-color: rgb(255, 255, 238);
	border-width: 0px;
	border-left: 15px solid rgb(255, 240, 106);
	border-radius: 0px;
	box-shadow: 0px 0px 5px rgba(51, 51, 51, 0.3);
	font-family: 'Old Standard TT', serif;
	letter-spacing: 1px;
}
[data-notify="container"].alert-pastel-info {
	border-left-color: rgb(255, 179, 40);
}
[data-notify="container"].alert-pastel-danger {
	border-left-color: rgb(255, 103, 76);
}
[data-notify="container"][class*="alert-pastel-"] > [data-notify="title"] {
	color: rgb(80, 80, 57);
	display: block;
	font-weight: 70;
	margin-bottom: 15px;
}
[data-notify="container"][class*="alert-pastel-"] > [data-notify="message"] {
	font-weight: 70;
}
		</style>
<link href="css3/animate.min.css" rel="stylesheet">		


<script src="js3/bootstrap/bootstrap-notify.min.js"></script>		
		<script src="js3/jquery.colorbox.js"></script>
<script type="text/javascript" >
function AvisarImpresion(tipo, num, estado) {
	if (estado==1) {
		if (tipo=="F") {
			var textoTipo="Factura";
		}
		if (tipo=="R") {
			var textoTipo="Recibo";
		}
		$.notify({
			title: textoTipo+' nº: '+num,
			message: ' Impresión iniciada'
		},{
			type: 'pastel-warning',
			delay: 5000,
			template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
				'<span data-notify="title">{1}</span>' +
				'<span data-notify="message">{2}</span>' +
			'</div>'
		});
	}
}
function mensaje(msg) {
		$.notify({
			title: msg,
		},{
			type: 'pastel-info',
			delay: 5000,
			template: '<div data-notify="container" class=" alert alert-{0}" role="alert">' +
				'<span data-notify="title">{1}</span>' +

			'</div>'
		});	
}

function OpenNote(noteId,w,h, scroll=false){
	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:w, height:h,
			scrolling: scroll,

	    });
}
</script>

<script src="js3/jquery.loadingModal.js"></script>
<script>
    function showModal(action) {
    	if (action==1) {    	
        $('body').loadingModal({text: 'Procesando...'});
        var delay = function(ms){ return new Promise(function(r) { setTimeout(r, ms) }) };
        var time = 2000;
        delay(time)
                .then(function() { $('body').loadingModal('animation', 'rotatingPlane').loadingModal('backgroundColor', 'red'); return delay(time);})
                .then(function() { $('body').loadingModal('animation', 'wave'); return delay(time);})
                .then(function() { $('body').loadingModal('animation', 'wanderingCubes').loadingModal('backgroundColor', 'green'); return delay(time);})
                .then(function() { $('body').loadingModal('animation', 'spinner'); return delay(time);})
                .then(function() { $('body').loadingModal('animation', 'chasingDots').loadingModal('backgroundColor', 'blue'); return delay(time);})
                .then(function() { $('body').loadingModal('animation', 'threeBounce'); return delay(time);})
                .then(function() { $('body').loadingModal('animation', 'circle').loadingModal('backgroundColor', 'black'); return delay(time);})
                .then(function() { $('body').loadingModal('animation', 'cubeGrid'); return delay(time);})
                .then(function() { $('body').loadingModal('animation', 'fadingCircle').loadingModal('backgroundColor', 'gray'); return delay(time);})
                .then(function() { $('body').loadingModal('animation', 'foldingCube'); return delay(time); } )
                .then(function() { $('body').loadingModal('color', 'black').loadingModal('text', 'Tiempo agotado').loadingModal('backgroundColor', 'yellow');  return delay(time); } )
                .then(function() { $('body').loadingModal('hide'); return delay(time); } )
                .then(function() { $('body').loadingModal('destroy') ;} );
    }else {
    	$('body').loadingModal('destroy');
    }
 }

</script>
<link href="js3/toastr/toastr.css" rel="stylesheet">
<script src="js3/toastr/toastr.js"></script>

 </body>
</html>