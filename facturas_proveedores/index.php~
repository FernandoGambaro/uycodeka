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
		echo "<script>window.top.location.href='../index.php'; </script>";
	   exit;
} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
              echo "<script>window.parent.changeURL('../index.php' ); </script>";
	       /*/header("Location:../index.php");	*/
	       exit;
   }
   $s->data['loggedAt']= time();/*/ update last accessed time*/
   $s->save();
}

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];



include ("../conectar.php");
include("../common/verificopermisos.php");
//header('Content-Type: text/html; charset=UTF-8'); 

date_default_timezone_set("America/Montevideo"); 

$cadena_busqueda=@$_GET["cadena_busqueda"];

if (!isset($cadena_busqueda)) { $cadena_busqueda=""; } else { $cadena_busqueda=str_replace("",",",$cadena_busqueda); }

if ($cadena_busqueda<>"") {
	$array_cadena_busqueda=split("~",$cadena_busqueda);
	$codproveedor=$array_cadena_busqueda[1];
	$nombre=$array_cadena_busqueda[2];
	$numfactura=$array_cadena_busqueda[3];
	$cboEstados=$array_cadena_busqueda[4];
	$fechainicio=$array_cadena_busqueda[5];
	$fechafin=$array_cadena_busqueda[6];
} else {
	$codproveedor="";
	$nombre="";
	$numfactura="";
	$cboEstados="";
	$fechainicio="";
	$fechafin="";
}

?>
<html>
	<head>
		<title>Facturas</title>
		<link href="../css3/estilos.css" type="text/css" rel="stylesheet">
		
		<script src="../js3/calendario/jscal2.js"></script>
		<script src="../js3/calendario/lang/es.js"></script>
		<link rel="stylesheet" type="text/css" href="../js3/calendario/css/jscal2.css" />
		<link rel="stylesheet" type="text/css" href="../js3/calendario/css/border-radius.css" />
		<link rel="stylesheet" type="text/css" href="../js3/calendario/css/win2k/win2k.css" />		
		<script src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="../js3/jquery.colorbox.js"></script>

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">


<script type="text/javascript">
parent.document.getElementById("msganio").innerHTML="<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp;  Compra proveedores ";

var alto=parent.document.getElementById("alto").value-160;

var totales=alto/22;
	 totales=totales-1;

$(document).ready( function()
{
var body = document.body,
    html = document.documentElement;
var height = Math.max( body.scrollHeight, body.offsetHeight, 
                       html.clientHeight, html.scrollHeight, html.offsetHeight );	
var width = Math.max( body.scrollWidth, body.offsetWidth, 
                       html.clientWidth, html.scrollWidth, html.offsetWidth );
    width=width-10;

    height=height-158;

    document.getElementById("frame_rejilla").height = (height) + "px";
    document.getElementById("frame_rejilla").width = (width) + "px";	
	
$("form:not(.filter) :input:visible:enabled:first").focus();

		$(".callbacks").colorbox({
			iframe:true, width:"99%", height:"99%",
			onCleanup:function(){ window.location.reload();	}
		});

});
</script>
<script>
var miVariable = totales;
document.cookie ='variable='+miVariable+'; expires=Thu, 2 Aug 2021 20:47:11 UTC; path=/';
</script>

<?php
$miVariable =  $_COOKIE["variable"];
	$s->data['alto']= floor($miVariable);
   $s->save();
   
	$_SESSION['alto'] = $miVariable;
?>

<script type="text/javascript">
function OpenNote(noteId,w,h){
	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:w, height:h,
			scrolling: false,
			onCleanup:function(){ document.getElementById("form_busqueda").submit(); }
	});
}

function OpenList(noteId){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"99%", height:"99%",
			onCleanup:function(){ document.getElementById("form_busqueda").submit(); }
	});

}

function pon_prefijo(pref,nombre) {
	$("#codproveedor").val(pref);
	$("#nombre").val(nombre);
	$('idOfDomElement').colorbox.close();
	document.getElementById("form_busqueda").submit();

}

function setIframeHeight(id) {
    var ifrm = document.getElementById(id);
    var doc = ifrm.contentDocument? ifrm.contentDocument: 
        ifrm.contentWindow.document;
    ifrm.style.visibility = 'hidden';
    ifrm.style.height = "10px"; // reset to minimal height ...
    // IE opt. for bing/msn needs a bit added or scrollbar appears
    ifrm.style.height = alto + "px";
    ifrm.style.visibility = 'visible';
}
</script>		
		
		<script language="javascript">
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		function inicio() {
			document.getElementById("firstdisab").style.display = 'block';
			document.getElementById("prevdisab").style.display = 'block';
			document.getElementById("last").style.display = 'block';
			document.getElementById("next").style.display = 'block';
			document.getElementById("form_busqueda").submit();			
		}		
		
		function nueva_factura() {
			$.colorbox({href:"nueva_factura.php",
			iframe:true, width:"99%", height:"99%",
			onCleanup:function(){ window.location.reload();	}
			});	
		}
		
		function buscar() {
			var cadena;
			cadena=hacer_cadena_busqueda();
			document.getElementById("cadena_busqueda").value=cadena;
			if (document.getElementById("iniciopagina").value=="") {
				document.getElementById("iniciopagina").value=1;
			} else {
				document.getElementById("iniciopagina").value=document.getElementById("paginas").value;
			}
			document.getElementById("form_busqueda").submit();
		}
		
		function paginar() {
			document.getElementById("iniciopagina").value=document.getElementById("paginas").value;
			document.getElementById("form_busqueda").submit();
		}

		function firstpage() {
			document.getElementById("iniciopagina").value=document.getElementById("firstpagina").value;
			document.getElementById("form_busqueda").submit();
		}
		function prevpage() {
			document.getElementById("iniciopagina").value=document.getElementById("prevpagina").value;
			document.getElementById("form_busqueda").submit();
		}
		function nextpage() {
			document.getElementById("iniciopagina").value=document.getElementById("nextpagina").value;
			document.getElementById("form_busqueda").submit();
		}
		function lastpage() {
			document.getElementById("iniciopagina").value=document.getElementById("lastpagina").value;
			document.getElementById("form_busqueda").submit();
		}
		
		function hacer_cadena_busqueda() {
			var codproveedor=document.getElementById("codproveedor").value;
			var nombre=document.getElementById("nombre").value;
			var numfactura=document.getElementById("numfactura").value;			
			var cboEstados=document.getElementById("cboEstados").value;
			var fechainicio=document.getElementById("fechainicio").value;
			var fechafin=document.getElementById("fechafin").value;
			var cadena="";
			cadena="~"+codproveedor+"~"+nombre+"~"+numfactura+"~"+cboEstados+"~"+fechainicio+"~"+fechafin+"~";
			return cadena;
			}
		
		function limpiar() {
			document.getElementById("form_busqueda").reset();
			document.getElementById("codarticulo").value='';
			document.getElementById("codproveedor").value='';
			
			document.getElementById("iniciopagina").value=1;
			document.getElementById("form_busqueda").submit();
		}		
		function abreVentana(){
			$.colorbox({
	   	href: "ventana_proveedores.php", open:true,
			iframe:true, width:"580", height:"450",
			scrolling: false,
			});			
		}
		
		function validarproveedor(){
			var codigo=document.getElementById("codproveedor").value;
			$.colorbox({
	   	href: "comprobarproveedor_ini.php?codproveedor="+codigo, open:true,
			iframe:true, width:"250", height:"200"
			});
		}
		</script>

<link href="../js3/jquery-ui.css" rel="stylesheet">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <style type="text/css">
  
		.fixed-height {
			padding: 1px;
			max-height: 200px;
			overflow: auto;
		}  
/****** jQuery Autocomplete CSS *************/

.ui-corner-all {
  -moz-border-radius: 0;
  -webkit-border-radius: 0;
  border-radius: 0;
}

.ui-menu {
  border: 1px solid lightgray;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;
}

.ui-menu .ui-menu-item a {
  color: #000;
}

.ui-menu .ui-menu-item:hover {
  display: block;
  text-decoration: none;
  color: #3D3D3D;
  cursor: pointer;
 /* background-color: lightgray;
  background-image: none;*/
  border: 1px solid lightgray;
}

.ui-widget-content .ui-state-hover,
.ui-widget-content .ui-state-focus {
  border: 1px solid lightgray;
  /*background-image: none;
  background-color: lightgray;*/
  font-weight: bold;
  color: #3D3D3D;
}
  
  </style>		
<script>
$.ui.autocomplete.prototype._renderItem = function(ul, item) {
  var re = new RegExp($.trim(this.term.toLowerCase()));
  var t = item.label.replace(re, "<span style='color:#5C5C5C;'>" + $.trim(this.term.toLowerCase()) +
    "</span>");
  return $("<li></li>")
    .data("item.autocomplete", item)
    .append("<a>" + t + "</a>")
    .appendTo(ul);
};

 $(document).ready(function () {
 	
    $("#descripcion").autocomplete({
        source: 'busco2.php',
        minLength:2,
        autoFocus:true,
        select: function(event, ui) {
         var name = ui.item.value;
         var thisValue = ui.item.data;
			var codfamilia=thisValue.split("~")[0];
			var codigobarras=thisValue.split("~")[1];
			var referencia=thisValue.split("~")[2];
			var nombre=thisValue.split("~")[3];
			var precio=thisValue.split("~")[4];
			var codarticulo=thisValue.split("~")[5];
			var moneda=thisValue.split("~")[6];
			var codservice=thisValue.split("~")[7];
			var detalles=thisValue.split("~")[8];
			var comision=thisValue.split("~")[9];

			$("#descripcion").val(nombre);
			$("#codarticulo").val(codarticulo);
		 document.getElementById('form_busqueda').submit();
		}
	}).autocomplete("widget").addClass("fixed-height");


    $("#nombre").autocomplete({
        source: 'busco.php',
        minLength:2,
        autoFocus:true,
        select: function(event, ui) {

		var name = ui.item.value;
		var thisValue = ui.item.data;
		var pref=thisValue.split("~")[0];
		var nombre=thisValue.split("~")[1];
		var nif=thisValue.split("~")[2];
		var agencia=thisValue.split("~")[3];

		$("#aProveedor").val(pref);
		$("#nombre").val(nombre);
		 document.getElementById('form_busqueda').submit();
		}
	}).autocomplete("widget").addClass("fixed-height");
});
</script>		
	</head>
	<body onLoad="inicio();">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="frmBusqueda">
				<form id="form_busqueda" name="form_busqueda" method="post" action="rejilla.php" target="frame_rejilla">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=1 border=0>	
					<td valign="top">
			 	<div>
				<?php 
						$escribir=verificopermisos('compras', 'escribir', $UserID);
					if ($escribir=="true") { ?>	
					<!-- iconos para los botones -->       
				<button class="boletin" onClick="nueva_factura();"><i class="fa fa-file-o" aria-hidden="true"></i>&nbsp;Nueva&nbsp;factura</button>
						 	
				<?php } 	?>	
				</div>					
					</td>					
					<td valign="top">				
					<table class="fuente8" cellspacing=0 cellpadding=3 border=0>					
						<tr><td>Proveedor</td>
					      <td><input name="codproveedor" type="hidden" class="cajaPequena" id="aProveedor"></input>
							<input name="nombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45"></td>					        

						</tr>
						<tr><td>
						Mercaderia/Artículo</td>
						<input type="hidden" value="" id="codarticulo" name="codarticulo" >
						<td ><input name="descripcion" type="text" class="cajaGrande" id="descripcion" size="45" maxlength="50"></td>
						</tr>						<tr>
						  <td>Num.&nbsp;Factura</td>
						  <td><input id="numfactura" type="text" class="cajaPequena" NAME="numfactura" maxlength="15" value="<?php echo $numfactura?>" onkeyup="document.getElementById('form_busqueda').submit();"></td>

					  </tr>
					  </table>
					  </td><td>
  					<table class="fuente8"  cellspacing=0 cellpadding=3 border=0>					

						<tr>
							<td>Estado</td>
							<td><select id="cboEstados" name="cboEstados" class="comboMedio" onchange="document.getElementById('form_busqueda').submit();">
								<option value="0" selected>Todos los estados</option>
								<option value="1">Sin Pagar</option>
								<option value="2">Pagada</option>			
								</select></td>
					    </tr>
					  <tr>
						  <td>Fecha de inicio</td>
						  <td><input id="fechainicio" type="text" class="cajaPequena" NAME="fechainicio" maxlength="10" value="<?php echo $fechainicio?>" readonly>
						  <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" title="Calendario" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fechainicio",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide(); document.getElementById('form_busqueda').submit();  },
						     dateFormat : "%d/%m/%Y"
						   });
						</script></td>
						  <td>
						  <button class="boletin" onClick="buscar();" onMouseOver="style.cursor=cursor"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar</button>
						  </td>
					  </tr>
						<tr>
						  <td>Fecha de fin</td>
						  <td><input id="fechafin" type="text" class="cajaPequena" NAME="fechafin" maxlength="10" value="<?php echo $fechafin?>" readonly>
						  <img src="../img/calendario.png" name="Image2" id="Image2" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fechafin",
						     trigger    : "Image2",
						     align		 : "Bl",
						     onSelect   : function() { this.hide(); document.getElementById('form_busqueda').submit();  },
						     dateFormat : "%d/%m/%Y"
						   });
						</script></td>
						  <td>
						 	<button class="boletin" onClick="limpiar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Limpiar</button>
						  </td>
					  </tr>
					  </table></td>
					</table>
			  </div>
			 	<div>
				</div>
			  <div id="lineaResultado">
			  <table class="fuente8" width="90%" cellspacing=0 cellpadding=3 border=0 style="max-width: 800px;">
			  	<tr>
				<td width="30%" align="left">Facturas&nbsp;<input id="filas" type="text" class="cajaPequena" NAME="filas" maxlength="5" readonly></td>
				<td  align="center">
				
				</td>
			<td width="20%" align="right">
		<table class="fuente8" cellspacing=1 cellpadding=1 border=0>
		<td>				
		<input type="hidden" id="firstpagina" name="firstpagina" value="1">
		<img style="display: none;" src="../img/paginar/first.gif" id="first" border="0" height="13" width="13" onClick="firstpage();" onMouseOver="style.cursor=cursor">
		<img style="display: none;" src="../img/paginar/firstdisab.gif" id="firstdisab" border="0" height="13" width="13"></td>
		<td>
		<input type="hidden" id="prevpagina" name="prevpagina" value="">
		<img style="display: none;" src="../img/paginar/prev.gif" id="prev" border="0" height="13" width="13" onClick="prevpage();" onMouseOver="style.cursor=cursor">
		<img style="display: none;" src="../img/paginar/prevdisab.gif" id="prevdisab" border="0" height="13" width="13">
		</td><td>
		<input id="currentpage" type="text" class="cajaMinima" >
		</td><td>
		<input type="hidden" id="nextpagina" name="nextpagina" value="">
		<img style="display: none;" src="../img/paginar/next.gif" id="next" border="0" height="13" width="13" onClick="nextpage();" onMouseOver="style.cursor=cursor">
		<img style="display: none;" src="../img/paginar/nextdisab.gif" id="nextdisab" border="0" height="13" width="13"></td>
		<td>
		<input type="hidden" id="lastpagina" name="lastpagina" value="">
		<img style="display: none;" src="../img/paginar/last.gif" id="last" border="0" height="13" width="13" onClick="lastpage();" onMouseOver="style.cursor=cursor">

		<img style="display: none;" src="../img/paginar/lastdisab.gif" id="lastdisab" border="0" height="13" width="13"></td>
		<td>Mostrados</td><td> <select name="paginas" id="paginas" onChange="paginar();" class="comboPequeno">
		          </select></td> 
		</table>	</td>										
				</table>
				</div>
				<input type="hidden" id="iniciopagina" name="iniciopagina">
				<input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
				<input type="hidden" id="selid" name="selid">
				
			</form>
					<iframe width="98%" height="300" id="frame_rejilla" name="frame_rejilla" frameborder="0" style="max-width: 800px;">
						<ilayer width="98%" height="300" id="frame_rejilla" name="frame_rejilla"></ilayer>
					</iframe>
			</div>
		  </div>			
		</div>
	</body>
</html>
