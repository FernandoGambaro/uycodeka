<?php
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
		//echo "<script>window.top.location.href='../index.php'; </script>";
	  // exit;
} else {
   $loggedAt=@$s->data['loggedAt'];
   $timeOut=@$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
              echo "<script>window.parent.changeURL('../../index.php' ); </script>";
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
include ("../../conectar.php");
header('Content-Type: text/html; charset=UTF-8'); 


if (!isset($cadena_busqueda)) { $cadena_busqueda=""; } else { $cadena_busqueda=str_replace("",",",$cadena_busqueda); }

if ($cadena_busqueda<>"") {
	$array_cadena_busqueda=split("~",$cadena_busqueda);
	$codcliente=isset($_GET['e']) ? $_GET['e'] : $array_cadena_busqueda[1] ;
	$estado=$array_cadena_busqueda[2];
} else {
	$codcliente=isset($_GET['e']) ? $_GET['e'] : null ;
	$estado="";
}

$query="SELECT * FROM clientes WHERE codcliente='$codcliente'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

?>
<html>
	<head>
		<title>Clientes</title>
		<link href="../../estilos/estilos.css" type="text/css" rel="stylesheet">
    <script src="../../calendario/jscal2.js"></script>
    <script src="../../calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../../calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../../calendario/css/win2k/win2k.css" />	
		<script type="text/javascript" src="../../funciones/validar.js"></script>

		<script src="../../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../../js3/colorbox.css" />
		<script src="../../js3/jquery.colorbox.js"></script>
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../../css3/css/font-awesome.min.css">
			
<script type="text/javascript">
parent.document.getElementById("msganio").innerHTML="<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp;  Proyectos ";

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
			iframe:true, width:"420", height:"270",
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
	$("#codcliente").val(pref);
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
		
		function nuevo_proyecto() {
			var codcliente=document.getElementById("codcliente").value;
			$.colorbox({href:"nuevo_proyecto.php?codcliente="+codcliente,
			iframe:true, width:"420", height:"270",
			onCleanup:function(){ window.location.reload();	}
		});
			/*location.href="nuevo_cliente.php";*/
		}
		
		function imprimir(codcliente) {
			var codcliente=document.getElementById("codcliente").value;
			var estado=document.getElementById("estado").value;
			window.open("GraficoProyectosMes.php?codcliente="+codcliente+"&estado="+estado);
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

	function lookupotro(id,inputString) {
		newItemInputValue=id+'Value';
		newItemInput='#'+id;
 		var offset = $(newItemInput).offset();
      var x = offset.left;
      var y = offset.top + 7;
      document.getElementById("suggestionslong").style.left = x + 10;
      document.getElementById("suggestionslong").style.top = y + 5;

      		
		if(inputString.length == 0) {
			/*/ Hide the suggestion box.*/
			$('#suggestionslong').hide();
		} else {
			$.post("busco.php?otro="+id, {queryString: ""+inputString+""}, function(data){
				if(data.length >0) {
					$('#suggestionslong').show();
					$('#autoSuggestionsListlong').html(data);
				}
			});
		}
	}
	 /*/ lookup
	*/
	function otro(thisValue) {
/*//alert(newItemInput + ' -> ' + thisValue);*/
      var item0=thisValue.split("-")[0];
      var item1=thisValue.split("-")[1];
/*alert(newItemInputValue + ' -- ' +item0+ "  --  "+newItemInput + " -- "+item1);*/

		document.getElementById(newItemInputValue).value=item0;
		$(newItemInput).val(item1);

		setTimeout("$('#suggestionslong').hide();", 200);
	}
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
		
	</head>
	<body onLoad="inicio();" bgcolor="white">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">Buscar Proyectos</div>
				<div id="frmBusqueda">
				<form id="form_busqueda" name="form_busqueda" method="post" action="rejilla.php" target="frame_rejilla">
				<table class="fuente8" width="98%"><tr><td>
					<table class="fuente8" width="50%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td>Cliente</td>
							<td>
							<input type="hidden" id="codcliente" name="codcliente" value="<?php echo $codcliente;?>">
							<input type="text" size="26" maxlength="60"  value="<?php echo mysqli_result($rs_query, 0, "empresa"). " - ". mysqli_result($rs_query, 0, "nombre")
							." ". mysqli_result($rs_query, 0, "apellido");?>"
  class="trigger cajaGrande" onFocus="this.style.backgroundColor='#FFFF99'" />
							</td>
							<td>&nbsp;</td>
						</tr>										
						<tr>
						  	<td>Estado</td><td>
							<select name="tipo" id="tipo" class="cajaPequena2">
								<option value="0">Activo</option>
								<option value="1">Borrado</option>
							</select>						
							</td>
					  </tr>
					</table>
			</td></tr></table>
			  </div>
			  <div id="lineaResultado">
			  <table class="fuente8" width="90%" cellspacing=0 cellpadding=3 border=0>
			  	<tr>
				<td width="30%" align="left">Nº de registros encontrados <input id="filas" type="text" class="cajaMinima" NAME="filas" maxlength="5" readonly></td>
				<td width="50%" align="center">			 	<div>

<button class="boletin" onClick="nuevo_proyecto();" onMouseOver="style.cursor=cursor">
						<i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;Nuevo&nbsp;proyecto</button>
<button class="boletin" onClick="imprimir();" onMouseOver="style.cursor=cursor">
						<i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir</button>
					</div>
</td>
				<td width="20%" align="right">
				<table class="fuente8" cellspacing=1 cellpadding=1 border=0>
<td>				
		<input type="hidden" id="firstpagina" name="firstpagina" value="1">
		<img style="display: none;" src="../../img/paginar/first.gif" id="first" border="0" height="13" width="13" onClick="firstpage();" onMouseOver="style.cursor=cursor">

		<img style="display: none;" src="../../img/paginar/firstdisab.gif" id="firstdisab" border="0" height="13" width="13"></td>
<td>
		<input type="hidden" id="prevpagina" name="prevpagina" value="">
		<img style="display: none;" src="../../img/paginar/prev.gif" id="prev" border="0" height="13" width="13" onClick="prevpage();" onMouseOver="style.cursor=cursor">

		<img style="display: none;" src="../../img/paginar/prevdisab.gif" id="prevdisab" border="0" height="13" width="13">
</td><td>
<input id="currentpage" type="text" class="cajaMinima" >
</td><td>
		<input type="hidden" id="nextpagina" name="nextpagina" value="">
		<img style="display: none;" src="../../img/paginar/next.gif" id="next" border="0" height="13" width="13" onClick="nextpage();" onMouseOver="style.cursor=cursor">

		<img style="display: none;" src="../../img/paginar/nextdisab.gif" id="nextdisab" border="0" height="13" width="13"></td>
<td>
		<input type="hidden" id="lastpagina" name="lastpagina" value="">
		<img style="display: none;" src="../../img/paginar/last.gif" id="last" border="0" height="13" width="13" onClick="lastpage();" onMouseOver="style.cursor=cursor">

		<img style="display: none;" src="../../img/paginar/lastdisab.gif" id="lastdisab" border="0" height="13" width="13"></td>
<td>			
				Mostrados</td><td> <select name="paginas" id="paginas" onChange="paginar();">
		          </select></td>
		          
</table>	</td></tr>		          
			  </table>
				</div>
				<input type="hidden" id="iniciopagina" name="iniciopagina">
				<input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
				<input type="hidden" id="selid" name="selid">
				<input type="hidden" id="stylesel" name="stylesel">

			</form>
					<iframe width="90%" height="340" id="frame_rejilla" name="frame_rejilla" frameborder="0" style=" overflow-y: scroll">
						<ilayer width="90%" height="340" id="frame_rejilla" name="frame_rejilla"></ilayer>
					</iframe>
					<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0" style=" overflow-y: scroll">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>
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