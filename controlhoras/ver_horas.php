<?php 
include ("../conectar.php"); 
include ("../funciones/fechas.php");

header('Content-Type: text/html; charset=UTF-8');     @mysqli_query($GLOBALS["___mysqli_ston"], "SET NAMES 'utf8'");  
 
$codhoras=$_GET["codhoras"];

$query="SELECT * FROM horas WHERE codhoras='$codhoras'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

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
		<script type="text/javascript" src="../funciones/validar.js"></script>

		<script src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="../js3/jquery.colorbox.js"></script>

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">
	

<script type="text/javascript">
$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

var headID = window.parent.document.getElementsByTagName("head")[0];         
var newScript = window.parent.document.createElement('script');
newScript.type = 'text/javascript';
newScript.src = 'js/jquery.colorbox.js';
headID.appendChild(newScript);
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
			if (id=="codproyectos") {
				var detalle=document.getElementById("codclienteValue").value;
				if (detalle=='') {
					alert('Elija cliente');
					return
				}
			}
			$.post("busco.php?otro="+id+"&detalle="+detalle, {queryString: ""+inputString+""}, function(data){
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
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">MODIFICAR CONTROL HORA</div>
				<div id="frmBusqueda">
				<table class="fuente8"><tr><td valign="top">
					<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<?php
							$enable='';
							$usuario=mysqli_result($rs_query, 0, "codusuario");
								if(trim($usuario)!='' ) {
									if($UserTpo!=2) {
									$enable='readonly';
									} else {
										$usuario='';
									}
									$query_bus="SELECT * FROM usuarios WHERE codusuarios='".$usuario."'";
									$rs_bus=mysqli_query($GLOBALS["___mysqli_ston"], $query_bus);
									$nombre= mysqli_result($rs_bus, 0, "nombre").' '. mysqli_result($rs_bus, 0, "apellido");
								}
							?>						
							<td>Usuario</td>
						    <td colspan="2">
						    <input name="usuarios" type="text" onfocus="this.select();" class="cajaGrande" id="Ausuarios" size="45" value="<?php echo $nombre;?>" maxlength="45" onFocus="this.style.backgroundColor='#FFFF99'"  <?php echo $enable;?> />
						    <input name="codusuarios" type="hidden" id="codusuarios" readonly  value="<?php echo $usuario;?>" />
					      </td>	
						</tr>
						  <td>Fecha</td>
						  <td><input id="fechafin" type="text" class="cajaPequena" name="Afecha" maxlength="10" value="<?php echo implota(mysqli_result($rs_query, 0, "fecha"));?>" readonly>
						  <img src="../img/calendario.png" name="Image2" id="Image2" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fechafin",
						     trigger    : "Image2",
						     align		 : "Bl",
						     onSelect   : function() { this.hide();  },
						     dateFormat : "%d/%m/%Y"
						   });
						</script>
						<?php 
						$mantar= mysqli_result($rs_query, 0, "mantar");
						if ($mantar==0){
							$ma="checked";
							$ta='';
						} else {
							$ta="checked";
							$ma='';
						}
						?>
								<input type="radio" name="mantar" value="0" style="vertical-align: middle; margin-top: -1px;" <?php echo $ma;?> >Mañana
								<input type="radio" name="mantar" value="1" style="vertical-align: middle; margin-top: -1px;" <?php echo $ta;?> >Tarde 						
						</td>						
						<tr>
							<td>Cliente</td>
							<?php
								$codcliente=mysqli_result($rs_query, 0, "codcliente");
								
								$query_busqu="SELECT * FROM clientes WHERE borrado=0 AND codcliente='".$codcliente."'";
								$rs_busqu=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqu);
								$nombre=mysqli_result($rs_busqu, 0, "nombre").' '. mysqli_result($rs_busqu, 0, "apellido");	
							?>							
						    <td colspan="2">
						    <input value="<?php echo $nombre;?>" name="Acliente" type="text" onfocus="this.select();" class="cajaGrande" id="Acliente" size="45" maxlength="45" onFocus="this.style.backgroundColor='#FFFF99'">
						    <input name="Acodcliente" type="hidden" id="Acodcliente" value="<?php if (mysqli_result($rs_query, 0, "codcliente")!=0) echo mysqli_result($rs_query, 0, "codcliente") ;?>" >
					      </td>	
					    </tr>
						<tr>
							<td>Proyecto</td><td>
							<?php
								$codproyectos= mysqli_result($rs_query, 0, "codproyectos");
								$query_busq="SELECT * FROM proyectos WHERE borrado=0 AND codproyectos='".$codproyectos."'";
								$rs_busq=mysqli_query($GLOBALS["___mysqli_ston"], $query_busq);
								$nom = mysqli_result($rs_busq, 0, "descripcion");
							?>							
							
<select id="second-choice" style="display: none">
  <option>Seleccione una opción</option>
</select>
							
						    <input name="proyectos" type="text" class="cajaGrande" id="proyectos" onfocus="this.select();" size="45" value="<?php echo $nom;?>" maxlength="45" onFocus="this.style.backgroundColor='#FFFF99'">
						    <input name="Acodproyectos" type="hidden" id="Acodproyectos" value="<?php echo mysqli_result($rs_query, 0, "codproyectos");?>" >
							</td>							
						</tr>	
						
									      
				      <tr><td>Horas</td>
				      <td>
						<select name="horas" id="horas" class="cajaPequena2">
						<?php
						
						$horas= mysqli_result($rs_query, 0, "horas");
						for ($i=0; $i<12; $i++)
						{
							for ($x=0; $x<=3; $x++) {
								if ($x==0){
								$h1=$i.":00";
								} else {
								$h1=$i.":". $x*15;
								}
							if($horas==$h1) {
								?>
								<option value="<?php echo $h1;?>" selected><?php echo $h1;?></option>
								<?php
								$h1='';
								} else {
								?>
								<option value="<?php echo $h1;?>"><?php echo $h1;?></option>
								<?php
								$h1='';
								}									
									
							}
						}
						?>
						</select>
				      </tr>					
						<tr>
							<td>Descripción</td><td><textarea id="descripcion" name="descripcion" autocomplete="off" rows="2" cols="35" ><?php echo trim(mysqli_result($rs_query, 0, "descripcion"));?></textarea></td>
					    </tr>					    
					</table>
				</td></tr></table>
			  </div><br>
				<div>
					<button class="boletin" onClick="event.preventDefault();validar(formulario,true);" onMouseOver="style.cursor=cursor"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Agregar</button>					
					<button class="boletin" onClick="limpiar();" onMouseOver="style.cursor=cursor"><i class="fa fa-eraser" aria-hidden="true"></i>&nbsp;Limpiar</button>
					<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();"onMouseOver="style.cursor=cursor"><i class="fa fa-window-close" aria-hidden="true"></i>
Cerrar</button>				
			  </div>
			  
			  
			  </div>
		  </div>
		</div>
	
	</body>
</html>
