<?php
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
		//echo "<script>window.top.location.href='../index.php'; </script>";
	  // exit;
} else {
   $loggedAt=@$s->data['loggedAt'];
   $timeOut=@$s->data['timeOut'];
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
header('Content-Type: text/html; charset=UTF-8'); 

$usuario=@$_GET['u'];

$cadena_busqueda=@$_GET["cadena_busqueda"];

if (!isset($cadena_busqueda)) { $cadena_busqueda=""; } else { $cadena_busqueda=str_replace("",",",$cadena_busqueda); }

if ($cadena_busqueda<>"") {
	$array_cadena_busqueda=split("~",$cadena_busqueda);
	$codcliente=$array_cadena_busqueda[1];
	$nombre=$array_cadena_busqueda[2];
	$nif=$array_cadena_busqueda[3];
	$provincia=$array_cadena_busqueda[4];
	$localidad=$array_cadena_busqueda[5];
	$telefono=$array_cadena_busqueda[6];
} else {
	$codcliente="";
	$nombre="";
	$nif="";
	$provincia="";
	$localidad="";
	$telefono="";
}

if (empty($_GET['anio'])) {
$anio=date('Y');
} else {
$anio=$_GET['anio'];
}
if (empty($_GET['mes'])) {
$mes=date('m');
} else {
$mes=$_GET['mes'];
}

$fechainicio="01/".$mes."/".$anio;
$fechafin=date('t')."/".$mes."/".$anio;

?>
<html>
	<head>
		<title>Control horas</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
    <script src="../calendario/jscal2.js"></script>
    <script src="../calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />	
		<script type="text/javascript" src="../funciones/validar.js"></script>

		<script src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="../js3/jquery.colorbox.js"></script>

		<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
		<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js3/message.js" type="text/javascript"></script>
		<script src="../js3/jquery.msgBox.js" type="text/javascript"></script>
		<link href="../js3/msgBoxLight.css" rel="stylesheet" type="text/css">	
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">
		
		
<script type="text/javascript">
parent.document.getElementById("msganio").innerHTML="<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp;  Control horas";

$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

		$(".callbacks").colorbox({
			iframe:true, width:"720px", height:"98%",
			onCleanup:function(){ window.location.reload();	}
		});

});
</script>
<script type="text/javascript">
function OpenNote(noteId){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"450", height:"310",
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

function pon_prefijo(pref,nombre,nif) {
	$("#Acodcliente").val(pref);
	$("#nombre").val(nombre);
	$("#nif").val(nif);
	$('idOfDomElement').colorbox.close();
	document.getElementById("form_busqueda").submit();

}

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
		
		function inicio() {
			document.getElementById("firstdisab").style.display = 'block';
			document.getElementById("prevdisab").style.display = 'block';
			document.getElementById("last").style.display = 'block';
			document.getElementById("next").style.display = 'block';
			document.getElementById("form_busqueda").submit();			
		}
		
		function nuevo_horas() {
			var codusuario=document.getElementById("codusuarios").value;
			
			$.colorbox({href:"nuevo_horas.php?codusuario="+codusuario,
			iframe:true, width:"450", height:"310",
			onCleanup:function(){ window.location.reload();	}
		});
			/*location.href="nuevo_cliente.php";*/
		}
		function horas_dias() {
			var codusuario=document.getElementById("codusuarios").value;
			
			$.colorbox({href:"horas_dias.php?codusuario="+codusuario,
			iframe:true, width:"450", height:"330",
			onCleanup:function(){ window.location.reload();	}
		});
			/*location.href="nuevo_cliente.php";*/
		}
		
		function grafico() {
			var codcliente=document.getElementById("Acodcliente").value;
			var codusuario=document.getElementById("codusuarios").value;
			var fechainicio=document.getElementById("fechainicio").value;			
			var fechafin=document.getElementById("fechafin").value;
			
			var url ='';
			var opcionesgrafico=$("#opcionesgrafico").val();
			
			if ($('#opcionesaexcel').is(":checked")){
				
				if (opcionesimpresion==1) {
					url = "../excel/ListaDeArticulosExcel.php?codigobarras="+codigobarras+"&referencia="+referencia+"&descripcion="+descripcion+"&cboProveedores="+proveedores+"&cboFamilias="+familia+"&cboUbicacion="+ubicacion+"&stock="+stock;
				} 

					$.msgBox({ type: "prompt",
					 title: "Ingrese el nombre del archivo sin extención",
					 inputs: [
					 { header: "Nombre de Archivo", type: "text", name: "nombre" }],
					 buttons: [
					 { value: "Aceptar" }, { value:"Cancelar" }],
					 success: function (result, values) {
											$(values).each(function (index, input) {
											v =  input.value ;
					  						});	
							if (v!="") {
								$.get("../excel/preparo.php?file="+v,function (data,status) { });
						      window.parent.progressExcelBar(1,v);
									$.get(url+"&file="+v, function(data, status) {
									if(status == 'success'){	
									$('#downloadFrame').remove(); // This shouldn't fail if frame doesn't exist
			   					$('body').append('<iframe id="downloadFrame" style="display:none"></iframe>');
			   					$('#downloadFrame').attr('src','../tmp/'+v+'.xlsx');	
										return false;
							  		}else{					
									showWarningToast('Se produjo un error, intentelo mas tarde');
							  		}
								});    											
							} else {
								if (result!="Cancelar") {
									showWarningToast('Nombre de archivo incorrecto.');
								}
							}					  														    	
						}
					});	
			} else {
				if (opcionesgrafico==1) {
					if (codcliente=='') {
						showWarningToast('Debe seleccionar cliente.');
					} else {
					var url="../reportes/GraficoBase.php?codcliente="+codcliente+"&fechainicio="+fechainicio+"&fechafin="+fechafin;
						$.colorbox({href:url,
						iframe:true, width:"100%", height:"100%",
						});
					}
				}
				if (opcionesgrafico==2) {
					if (codusuario=='') {
						showWarningToast('Debe seleccionar usuario.');
					} else {
					var url="../reportes/GraficoBaseUsuarios.php?codusuario="+codusuario+"&fechainicio="+fechainicio+"&fechafin="+fechafin;
						$.colorbox({href:url,
						iframe:true, width:"100%", height:"100%",
						});
					}
				}			
				if(opcionesgrafico>=13 ){
				var url="GraficoProyectosMes.php?codcliente="+codcliente+"&codusuario="+codusuario+"&fechainicio="+fechainicio+"&fechafin="+fechafin;
				$.colorbox({href:url,
				iframe:true, width:"100%", height:"100%",
				});			
				} 
				if (opcionesgrafico==3) { 
				var url="horasasignadas.php?codcliente="+codcliente+"&codusuario="+codusuario+"&fechainicio="+fechainicio+"&fechafin="+fechafin;
				$.colorbox({href:url,
				iframe:true, width:"100%", height:"100%",
				});					
				}							
			
			}
		}
		function imprimir() {
			var codcliente=document.getElementById("Acodcliente").value;
			var codusuario=document.getElementById("codusuarios").value;
			var fechainicio=document.getElementById("fechainicio").value;			
			var fechafin=document.getElementById("fechafin").value;
			window.open("ImprimirEstado.php?codcliente="+codcliente+"&codusuario="+codusuario+"&fechainicio="+fechainicio+"&fechafin="+fechafin);
			
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
			var codusuario=document.getElementById("codusuarios").value;
			var codcliente=document.getElementById("Acodcliente").value;
			var fechainicio=document.getElementById("fechainicio").value;
			var fechafin=document.getElementById("fechafin").value;
			var cadena="";
			cadena="~"+codusuario+"~"+codcliente+"~"+fechainicio+"~"+fechafin+"~";
			return cadena;
			}
			
		function limpiar() {
			event.preventDefault();
			document.getElementById("Acodcliente").value='';
			document.getElementById("codusuarios").value='';
			document.getElementById("Acliente").value='';
			document.getElementById("Ausuarios").value='';
			$("#codusuario").val();
			//document.getElementById("fechainicio").value='';			
			//document.getElementById("fechafin").value='';
			
			//document.getElementById("form_busqueda").reset();
			document.getElementById("form_busqueda").submit();
		}
		
		
		</script>
		
<script type="text/javascript">

var newItemHide='';
var newItemShow='';
var newItemHideN='';
var newItemShowN='';
var newItemInput='';
var newItemInputId='';
var newItemInputValue='';
var newItemCombo='';
var newItemComboN='';
var trigerclass='';

$(document).ready( function()
{


  $('.trigger').click( function(e){
		$('#suggestions').hide();
		$('#suggestionslong').hide();
      if (newItemShowN!='') {
      $(newItemShowN).hide();
      $(newItemHideN).show();
      }
		trigerclass='';
      newItemHide=this.id+'Hide';
      newItemShow=this.id+'Show';
      newItemHideN='#'+this.id+'Hide';
      newItemShowN='#'+this.id+'Show';
      newItemInput=this.id+'Input';
      newItemInputId='#'+this.id+'Input';
      newItemInputValue=this.id+'Value';
      newItemCombo=this.id+'Combo';
      newItemComboN='#'+this.id+'Combo';

  	if (document.getElementById(this.id).value=="") {
  		document.getElementById(newItemInputValue).value='';
  	}

      p = $(this);
      var offset = p.offset();
      var x = offset.left - this.offsetLeft + 20 ;
      var y = offset.top - this.offsetTop + 10;
      document.getElementById(newItemShow).style.left = x ;
      document.getElementById(newItemShow).style.top = y + 10;

      $(newItemShowN).show(); $(newItemHideN).hide();
      

      e.preventDefault();
   });//Finaliza trigger

 	$('.cajaGrande').click( function(e){
		$(newItemShowN).hide(); $(newItemHideN).show();
		$('#suggestions').hide();
		$('#suggestionslong').hide();
	});

});


	
</script>		
		

<style type="text/css">
	.suggestionsBox {
      position:absolute;
		overflow:hidden; 
		visibility:visible; z-index:20;
		left:auto;
		margin: 6px 0px 0px 0px;
		width: 150px;
		background-color: #212427;
		-moz-border-radius: 4px;
		-webkit-border-radius: 4px;
		border: 2px solid #000;	
		color: #fff;
                font-size:10pt;
	}
		.suggestionsBoxLong {
      position:absolute;
		overflow:hidden; 
		visibility:visible; z-index:20;
		left:auto;
		margin: 6px 0px 0px 0px;
		width: 350px;
		background-color: #212427;
		-moz-border-radius: 4px;
		-webkit-border-radius: 4px;
		border: 2px solid #000;	
		color: #fff;
                font-size:10pt;
	}
	
	.suggestionList {
		margin: 0px;
		padding: 0px;
	}
	
	.suggestionList li {
		
		margin: 0px 0px 2px 0px;
		padding: 2px;
		cursor: pointer;
	}
	
	.suggestionList li:hover {
		background-color: #659CD8;
	}

	.suggestionListLong {
		margin: 0px;
		padding: 0px;
	}
	
	.suggestionListLong li {
		
		margin: 0px 0px 2px 0px;
		padding: 2px;
		cursor: pointer;
	}
	
	.suggestionListLong li:hover {
		background-color: #659CD8;
	}
        .seleccione { background:#FFF;margin:0 7px 0 7px;border-left:1px solid #EFEFEF;border-right:1px solid #EFEFEF;border-bottom:1px solid #EFEFEF;padding:5px 5px 5px 5px;
 border-right:1px solid #C43303; border-bottom:1px solid #C43303; border-left:1px solid #C43303; border-top:1px solid #C43303; border-radius:5px;
}
</style>
<link href="../js3/jquery-ui.css" rel="stylesheet">
<script src="../js3/jquery-ui.js"></script>
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
 	
    $("#proyectos").autocomplete({
    		source: function(request, response) {
            $.ajax({
                url: "../common/busco_proyectos.php",
                dataType: "json",
                data: {
                    term: request.term,
                    codcliente: $("#Acodcliente").val(), 
                },
                success: function(data) {
							var codigo=$("#Acodcliente").val();
							if (codigo=="") {
								alert("Debe introducir cliente");
								return false;
							}                	
                    response(data);
                }
            });
        },
    	  minLength:1,
        autoFocus:true,
        select: function(event, ui) {
           	
         var name = ui.item.value;
         var thisValue = ui.item.data;
			var codproyectos=thisValue.split("~")[0];
			var proyecto=thisValue.split("~")[1];

			$("#codproyectos").val(codproyectos);
			$("#proyectos").val(proyecto);

		}
	}).autocomplete("widget").addClass("fixed-height");


    $("#Acliente").autocomplete({
        source: '../common/busco_clientes.php',
        minLength:2,
        autoFocus:true,
        select: function(event, ui) {

		var name = ui.item.value;
		var thisValue = ui.item.data;
		var pref=thisValue.split("~")[0];
		var nombre=thisValue.split("~")[1];

		$("#Acodcliente").val(pref);
		$("#Acliente").val(nombre);
			$("#codproyectos").val('');
			$("#proyectos").val('');
/*
			$.ajax({
            url:'../common/busco_proyectos2.php',
            type: 'POST',
            data: {codcliente:pref},
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#second-choice").empty();
                for(var i = 0; i<len; i++){
                    var id = response[i]['id'];
                    var name = response[i]['descipcion'];                    
                    $("#second-choice").append("<option value='"+id+"'>"+name+"</option>");
                }
            }
        });
*/

		}
	}).autocomplete("widget").addClass("fixed-height");

    $("#Ausuarios").autocomplete({
        source: '../common/busco_usuarios.php',
        minLength:2,
        autoFocus:true,
        select: function(event, ui) {
	
			var name = ui.item.value;
			var thisValue = ui.item.data;
			var pref=thisValue.split("~")[0];
			var nombre=thisValue.split("~")[1];
	
			$("#codusuarios").val(pref);
			$("#Ausuarios").val(nombre);

			}
	}).autocomplete("widget").addClass("fixed-height");
	
	
});
</script>		
		
	</head>
	<body onLoad="inicio();" bgcolor="white">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">Buscar Horas realizadas</div>
				<div id="frmBusqueda">
				<form id="form_busqueda" name="form_busqueda" method="post" action="rejilla.php" target="frame_rejilla">
				<table class="fuente8"><tr><td>
					<table class="fuente8"  cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td >Usuario </td>
							<td >
							<?php
							$enable='';
								if(trim($usuario)!='' ) {
									if($UserTpo!=2) {
									$enable='readonly';
									} else {
										$usuario='';
									}
									$query_bus="SELECT * FROM usuarios WHERE codusuarios='".$usuario."' and  tratamiento!=2";
									$rs_bus=mysqli_query($GLOBALS["___mysqli_ston"], $query_bus);
									$nombre= mysqli_result($rs_bus, 0, "nombre").' '. mysqli_result($rs_bus, 0, "apellido");
								}
							?>	
						    <input name="usuarios" type="text" onfocus="this.select();" class="cajaGrande" id="Ausuarios" size="45" value="<?php echo $nombre;?>" maxlength="45" onFocus="this.style.backgroundColor='#FFFF99'"  <?php echo $enable;?> />
						    <input name="codusuario" type="hidden" id="codusuarios" readonly  value="<?php echo $usuario;?>" />
														
							<td>Cliente</td>
							<td>
						    <input value="<?php echo $nombre;?>" name="Acliente" type="text" onfocus="this.select();" class="cajaGrande" id="Acliente" size="45" maxlength="45" onFocus="this.style.backgroundColor='#FFFF99'">
						    <input name="codcliente" type="hidden" id="Acodcliente" value="" >
							
							</td>
							<td rowspan="2">
							<table class="fuente8" cellspacing=1 cellpadding=2 border=0>					    
						<tr>
						<td>
						<fieldset><legend>&nbsp;Opciones de gráficos&nbsp;</legend>
						<select name="opcionesgrafico" id="opcionesgrafico" class="comboGrande" >
						<option value="1">Anual - Clientes seleccionado</option>
						<option value="2">Anual - Usuario seleccionado</option>
						<option value="3">Barras horas asignadas a clientes</option>
						</select>
						<br>&nbsp;<br>
						<!--<label>Exportar a Excel?
  							<input id="opcionesaexcel" name="opcionesaexcel" type="checkbox" checked="true" value="0">
  							<span></span>
						</label>	-->					
						</fieldset>
						</td>
						</tr>
							</table>	</td>
						</tr>										
						<tr>
						  <td>Fecha&nbsp;de&nbsp;inicio</td>
						  <td><input id="fechainicio" type="text" class="cajaPequena" name="Afechainicio" maxlength="10" value="<?php echo $fechainicio;?>" readonly>
						  <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" title="Calendario" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fechainicio",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide();  },
						     dateFormat : "%d/%m/%Y"
						   });
						</script></td>

						  <td>Fecha&nbsp;de&nbsp;fin</td>
						  <td><input id="fechafin" type="text" class="cajaPequena" name="Afechafin" maxlength="10" value="<?php echo $fechafin;?>" readonly>
						  <img src="../img/calendario.png" name="Image2" id="Image2" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fechafin",
						     trigger    : "Image2",
						     						//]]></align		 : "Bl",
						     onSelect   : function() { this.hide();  },
						     dateFormat : "%d/%m/%Y"
						   });
						</script></td>
						
						<td width="200"><button class="boletin" onClick="grafico();" onMouseOver="style.cursor=cursor">
						<i class="fa fa-pie-chart" aria-hidden="true"></i>&nbsp;Gráficar</button>
</td>
					  </tr>
					</table>
			</td></tr></table>
			  </div>
			  <div id="lineaResultado">
			  <table class="fuente8" width="90%" cellspacing=0 cellpadding=3 border=0>
			  	<tr>
				<td width="20%" align="left">Nº de registros encontrados <input id="filas" type="text" class="cajaMinima" name="filas" maxlength="5" readonly></td>
				<td width="60%" align="center">			 	<div>
<button class="boletin" onClick="buscar();" onMouseOver="style.cursor=cursor">
						<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar</button>
<button class="boletin" onClick="limpiar();" onMouseOver="style.cursor=cursor">
						<i class="fa fa-eraser" aria-hidden="true"></i>&nbsp;Limpiar</button>
<button class="boletin" onClick="nuevo_horas();" onMouseOver="style.cursor=cursor">
						<i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;Nuevo&nbsp;horas</button>
<button class="boletin" onClick="horas_dias();" onMouseOver="style.cursor=cursor">
						<i class="fa fa-sun-o" aria-hidden="true"></i>&nbsp;Nuevo&nbsp;días</button>
<button class="boletin" onClick="imprimir();" onMouseOver="style.cursor=cursor">
						<i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir</button>
</div>
					
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
<td>			
				Mostrados</td><td> <select name="paginas" id="paginas" onChange="paginar();">
		          </select></td>
		          
</table>	</td></tr>		          
			  </table>
				</div>

				<input type="hidden" id="iniciopagina" name="iniciopagina">
				<input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
			</form>
					<iframe width="90%" height="340" id="frame_rejilla" name="frame_rejilla" frameborder="0" style=" overflow-y: scroll">
						<ilayer width="90%" height="340" id="frame_rejilla" name="frame_rejilla"></ilayer>
					</iframe>
					<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0" style=" overflow-y: scroll">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>
					<div>Total&nbsp;de&nbsp;horas&nbsp;<input type="text" id="Total" name="Total" class="cajaPequena"></div>
			</div>
		  </div>			
		</div>
			
<div class="suggestionsBox" id="suggestions" style="display: none;">
      <div class="suggestionList" id="autoSuggestionsList" style="text-align: left;">
	       &nbsp;
      </div>
</div>

<div class="suggestionsBoxLong" id="suggestionslong" style="display: none;">
      <div class="suggestionListLong" id="autoSuggestionsListlong" style="text-align: left;" >
	       &nbsp;
      </div>
</div>
			  
			  </div>
		  </div>
		</div>
		<div id="ErrorBusqueda" class="fuente8">
 <ul id="lista-errores" style="display:none; 
	clear: both; 
	max-height: 75%; 
	overflow: auto; 
	position:relative; 
	top: 85px; 
	margin-left: 30px; 
	z-index:999; 
	padding-top: 10px; 
	background: #FFFFFF; 
	width: 585px; 
	-moz-box-shadow: 0 0 5px 5px #888;
	-webkit-box-shadow: 0 0 5px 5px#888;
 	box-shadow: 0 0 5px 5px #888; 
 	bottom: 10px;"></ul>	
 
 	</div>		
	</body>
</html>
