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

////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 
////header('Content-Type: text/html; charset=UTF-8');

$codusuarios=$_GET["codusuarios"];

$query="SELECT * FROM usuarios WHERE codusuarios='$codusuarios'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);


?>

<html>
<head>
		<link href="../css3/estilos.css" type="text/css" rel="stylesheet">
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
		
		</script>
<script type="text/javascript">
window.onload = function () {
/*
    document.getElementById('contrasenia').onfocus = function () {
        if (this.defaultValue == this.value) {
            this.type = 'password';
            this.value = '';
        }
    }
    */
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

var initials ={};


$(document).ready(function()
{
	$('input').each(function(){
    initials[this.id] = $(this).attr('value');
	});

  $('.trigger').click(function(e){
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
      
		/*/$(newItemInputId).caretToStart();*/	   
	   /*/$(newItemInputId).focus().select();*/
	   
      e.preventDefault();
   });//Finaliza trigger

  $('.triggerClose').click(function(e){

      if (newItemShowN!='') {
      $(newItemShowN).hide();
      $(newItemHideN).show();
		$('#suggestions').hide();
		$('#suggestionslong').hide();
      }
      trigerclass='';
      e.preventDefault();
   });/*/Finaliza triggerClose*/
   
   	$('.triggerinput').click(function(e) {
   		trigerclass=1;
   		/*/alert(trigerclass);*/
   	});

  $('.ComboBox').click(function(e){
/*alert(this.id);*/
      newItemDel='#'+this.id;
      $(newItemShowN).hide(); $(newItemHideN).show();
      e.preventDefault();
   });

	$('#formulario').submit(function() {
		$('*').find('option').each(function() {
			$(this).attr('selected', 'selected');
		});
	});

$('.simpleinput').click(function(e){
	$(newItemShowN).hide(); $(newItemHideN).show();
	$('#suggestions').hide();
	$('#suggestionslong').hide();
});

/*/veo cual es el campo en el que se esta escribiendo;*/
$('input').focus(function(e){
	$('#suggestions').hide();
	$('#suggestionslong').hide();
   var selected = document.activeElement;
   if (selected.id) {
      var offset = $(this).offset();
      var x = offset.left - this.offsetLeft ;
      var y = offset.top - this.offsetTop + 15;
      document.getElementById("suggestions").style.left = x + 10;
      document.getElementById("suggestions").style.top = y + 21;
      e.preventDefault();
   }
newItemInput='#'+selected.id;

/*/alert('newItemInput -> '+newItemInput);*/

    });
});

$('input').blur(function(){
  if($(this).attr('value')==''){
    $(this).attr('value', initials[this.id]);
  }
  /*/ do other stuff*/
});

function invertHex(hexnum){
  if(hexnum.length != 6) {
    console.error("Hex color must be six hex numbers in length.");
    return false;
  }

  hexnum = hexnum.toUpperCase();
  var splitnum = hexnum.split("");
  var resultnum = "";
  var simplenum = "FEDCBA9876".split("");
  var complexnum = new Array();
  complexnum.A = "5";
  complexnum.B = "4";
  complexnum.C = "3";
  complexnum.D = "2";
  complexnum.E = "1";
  complexnum.F = "0";

  for(i=0; i<6; i++){
    if(!isNaN(splitnum[i])) {
      resultnum += simplenum[splitnum[i]]; 
    } else if(complexnum[splitnum[i]]){
      resultnum += complexnum[splitnum[i]]; 
    } else {
      console.error("Hex colors must only include hex numbers 0-9, and A-F");
      return false;
    }
  }

  return resultnum;
}
</script>
<script type="text/javascript">
	function lookup(id,inputString) {
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
			$.post("buscar.php?otro="+id, {queryString: ""+inputString+""}, function(data){
				if(data.length >0) {
					$('#suggestionslong').show();
					$('#autoSuggestionsListlong').html(data);
				}
			});
		}
	} /*/ lookup
	*/

	function fill(thisValue) {
      $( "#suggestions" ).hide( );
      $( "#suggestionslong" ).hide( );
      $(newItemShowN).hide(); $(newItemHideN).show();
      var item0=thisValue.split("-")[0];
      var item1=thisValue.split("-")[1];
      var item2=thisValue.split("-")[2];      
		//alert(newItemComboN + ' -- ' +item0+ "  --  "+newItemInput + " -- "+item1+ " -- "+item2);
		
		$(newItemInput).val('');
      $(newItemMCombo).append($('<option style="background-color:'+item2+'; color: #'+ invertHex(item2)+';"></option>').attr('value', item0).text(item1));
            
      newItemHide=''; newItemShow=''; newItemHideN=''; newItemShowN=''; newItemInput=''; newItemCombo='';
	}/*/End fill*/	

	
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
            alert('Ayuda aún no disponible...');
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
<script LANGUAGE="JavaScript">
function selectAll(selectBox,selectAll) { 
    /*/ have we been passed an ID */
    if (typeof selectBox == "string") { 
        selectBox = document.getElementById(selectBox);
    } 
    /*/ is the select box a multiple select box? */
    if (selectBox.type == "select-multiple") { 
        for (var i = 0; i < selectBox.options.length; i++) { 
             selectBox.options[i].selected = selectAll; 
        } 
    }
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
<script type="text/javascript">
$(document).ready(function(){
   $("#tratamiento").change(function(){
   	if ($("#tratamiento").val()==2) {
   	  $('.checkbox1').each(function() {
            $(this).attr('checked',true);
        });
   	} else {
   	  $('.checkbox1').each(function() {
            $(this).attr('checked',false);
        });
   	}
	});
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

<title>Usuario</title>
</head>
<body >
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">MODIFICAR DATOS DE USUARIO </div>
				<div id="frmBusqueda">
					<table class="fuente8" cellspacing=0 cellpadding=2 border=0 >
						<tr><td>&nbsp;Tratamiento&nbsp;</td><td>
						<?php
												$tratamiento= mysqli_result($rs_query, 0, "tratamiento");
						?>
						<input  name="Atratamiento" id="Atratamiento" value="<?php echo $tratamiento;?>" type="hidden" />
						<select  type="text" size="1"  id="tratamiento" class="comboGrande"  onchange="document.getElementById('Atratamiento').value =this.value;">					
						<?php
						$Tipox = array(
									0=>'Seleccione uno',
						        1=>"Usuario-Empleado",
						        2=>"Administrador",
						        3=>"Vendedor",
						        4=>"Asistente",
						        5=>"Administrativo",);
						$xx=0;
						$NoEstadox=0;
						foreach($Tipox as $ii) {
						  	if ( $xx== $tratamiento)
							{
								echo "<option value=$xx selected>$ii</option>";
								$NoEstadox=1;
							}
							else
							{
								echo "<option value=$xx>$ii</option>";
							}
							$xx++;
						   }
						?>
						</select>
						</td>
						<td width="100px" colspan="2">
						<input type="hidden" name="estado" value="1">
						<label>  Estado
						<?php
						$estado=mysqli_result($rs_query, 0, "estado");
						 if ( $estado=='' or $estado==0) {
						?>
						<input type="checkbox" name="estado" value="0" checked> Activo
						<?php } else {
						?>
						<input type="checkbox" name="estado" value="0"> Activo
						<?php }
						?>
							<span></span>
						</label>						
						</td>
						</tr><tr>
						<td width="100px">&nbsp;Nombre</td>
						<td><input type="text" size="30" name="Anombre" id="aNombre" value="<?php echo mysqli_result($rs_query, 0, "nombre")?>" class="cajaGrande"></input>
						</td><td width="100px" >&nbsp;Apellido</td>
						<td><input type="text" size="30" name="aapellido" id="aApellido" value="<?php echo mysqli_result($rs_query, 0, "apellido")?>" class="cajaGrande"></input>
						</td></tr>
						<tr><td>&nbsp;Tel&eacute;fono</td>
						<td><input  type="text" size="30" name="atelefono" id="telefono" value="<?php echo mysqli_result($rs_query, 0, "telefono")?>" class="cajaGrande" ></input>
						</td><td width="100px" >&nbsp;Celular</td>
						<td><input  type="text" size="30" name="acelular" id="celular" value="<?php echo mysqli_result($rs_query, 0, "celular")?>" class="cajaGrande"></input>
						</td></tr>
						<tr>
						<td>&nbsp;Nº&nbsp;Documento</td>
						<td><input type="text" size="30" name="Aci" id="aci" value="<?php echo mysqli_result($rs_query,0,"ci");?>" class="cajaGrande" ></input>
						<td>&nbsp;Nº&nbsp;empleado</td>
						<td><input type="text" size="30" name="aempleado" id="aempleado" value="<?php echo mysqli_result($rs_query,0,"empleado");?>" class="cajaMedia"></input>
						</td>
						</tr>
						<tr>
						<td>&nbsp;Medio pago</td>
						<td><input type="text" size="30" name="amedio" id="amedio" value="<?php echo mysqli_result($rs_query,0,"mediopago");?>" class="cajaGrande"></input>
						</td>
						<td>&nbsp;Nº&nbsp;Cta.</td>
						<td><input type="text" size="30" name="acta" id="acta" value="<?php echo mysqli_result($rs_query,0,"cta");?>" class="cajaGrande"></input>
						</td></tr>
						<tr>
						<td width="100px">&nbsp;eMail</td>
						<td><input  type="text" size="30" name="aemail" id="email" value="<?php echo mysqli_result($rs_query, 0, "email")?>" class="cajaGrande"></input>
						</td>
						<td rowspan="7" colspan="2" align="center" valign="top" style="height: 160px;">
						<div style="height: 160px; width:335px; overflow:disable; top:-35px;">
						<table width="320px" border=0 ><tr><td valign="top">
						<div class="header" style="width:335px; top:-33px; padding: 4px 0 4px 0;">PERMISOS</div>

						<div class="fixed-table-container" style="position: relative;top:-20px; width:335px; ">
						      <div class="header-background cabeceraTabla"> </div>      			
						<div class="fixed-table-container-inner">
						<div style="height: 160px; width:335px; overflow:auto;">

			
							<table class="fuente8" width="100%" cellspacing=1 cellpadding=2 border=0>
								<thead>
									<tr>
									<th>&nbsp;</th>
									<th width="30px" ><div class="th-inner">Ver</div></th>
									<th width="30px"><div class="th-inner">Crear</div></th>
									<th width="30px"><div class="th-inner">Mod.</div></th>
									<th width="30px"><div class="th-inner">Elim.</div></th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$sectores=array('proveedores','clientes','equipos cliente','servicios cliente','respaldos cliente','proyectos',
									'familias de articulos','articulos','embalaje','ventas','presupuestos','orden de pedido','compras',
									'cobros rapidos', 'cobros','reportes', 'mantenimiento','usuarios','copias seguridad','dgi');
									foreach($sectores as $xsector) {
									?>
									<tr>
									<td><div align="left"><?php echo ucwords($xsector);?></div></td>
									<?php
									if(strpos($xsector, ' ')!==false){
										$xsector=str_replace(' ', '', $xsector);
									}
									$leer=''; $escribir=''; $modificar=''; $eliminar='';
									$sel_resultado="select * from `permisos` where `seccion` = '$xsector' and `codusuarios`= '".$codusuarios."'";
									$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
								   $contador=0;								
								   
										if ( mysqli_num_rows($res_resultado) > 0 ) {
											if (mysqli_result($res_resultado, $contador, 'leer')==1) {
												$leer="checked";
											}
											if (mysqli_result($res_resultado, $contador, 'escribir')==1) {
												$escribir="checked";
											}
											if (mysqli_result($res_resultado, $contador, 'modificar')==1) {
												$modificar="checked";
											}
											if (mysqli_result($res_resultado, $contador, 'eliminar')==1) {
												$eliminar="checked";
											}
										}
									?>
									<td><input type="hidden" name="PERMISOS[<?php echo $xsector;?>_v]" value="<?php echo $xsector;?>_0">
									<label><input class="checkbox1" type="checkbox" name="PERMISOS[<?php echo $xsector;?>_v]" value="<?php echo $xsector;?>_v" <?php echo $leer;?>>
									<span></span></label></td>
									<td><label><input class="checkbox1" type="checkbox" name="PERMISOS[<?php echo $xsector;?>_c]" value="<?php echo $xsector;?>_c" <?php echo $escribir;?>><span></span></label></td>
									<td><label><input class="checkbox1" type="checkbox" name="PERMISOS[<?php echo $xsector;?>_m]" value="<?php echo $xsector;?>_m" <?php echo $modificar;?>><span></span></label></td>
									<td><label><input class="checkbox1" type="checkbox" name="PERMISOS[<?php echo $xsector;?>_e]" value="<?php echo $xsector;?>_e" <?php echo $eliminar;?>><span></span></label></td>
									</tr>
									<?php } ?>
										
									</tbody>
									</table>	
						</div></div></div>
						</td></tr></table></div>
						</td></tr><tr>
						<td>&nbsp;Dirección</td>
						<td><input type="text" size="30" name="adireccion" id="direccion" value="<?php echo mysqli_result($rs_query, 0, "direccion")?>" class="cajaGrande" ></input>
						</td></tr><tr>
						<td>&nbsp;Usuario</td>
						<td><input type="text" size="30" name="Eusuario" id="usuario" value="<?php echo mysqli_result($rs_query, 0, "usuario")?>" class="cajaGrande" ></input>
						</td></tr><tr>
						<td>&nbsp;Contraseña</td>
						<td><input type="password" name="Acontrasenia" id="contrasenia"  size="30" class="cajaGrande" placeholder="Escriba contraseña"  ></input>
						</td></tr>
						<tr><td>Pregunta</td>
						<td><?php
												 $secQ=mysqli_result($rs_query, 0, "secQ");
						?>
						<input name="secQ" id="secQ" value="<?php echo $secQ;?>" type="hidden" />
						<select id="Pregunta"  type="text" class="comboGrande"  onchange="document.getElementById('secQ').value =this.value;">
						<?php
							$questions = array();
							$questions[0] = "Seleccione uno";
							$questions[1] = "¿En que ciudad nació?";
							$questions[2] = "¿Cúal es su color favorito?";
							$questions[3] = "¿En qué año se graduo de la facultad?";
							$questions[4] = "¿Cual es el segundo nombre de su novio/novia/marido/esposa?";
							$questions[5] = "¿Cúal es su auto favorito?";
							$questions[6] = "¿Cúal es el nombre de su madre?";
						$xx=0;
						foreach($questions as $pregunta) {
						   if ($xx==$secQ) {
						      echo "<option value='$xx' selected>$pregunta</option>";
						   } else {
						      echo "<option value='$xx'>$pregunta</option>";
						   }
						$xx++;
						}
						?>
						</select>
						</td></tr><tr>
						<td>Respuesta</td>
						<td>
						<input type="text" size="26" name="AsecA" id="aRespuesta" value="<?php echo mysqli_result($rs_query, 0, "secA");?>" class="cajaGrande"></input>
						</td></tr>
						<tr><td valign="top">Sectores/Sección</td><td valign="top">
				   	<div id="newItemMHide" style="display:;">
				   	   <img id="newItemM" src="../img/plus.png" width="16" height="16" vspace="0" hspace="0" align="right" border="0" 
				   	   style="cursor:pointer; position:relative; z-index: 99; right: -17px; top: 0px;" class="trigger">
					   </div>
				      <div id="newItemMShow" style="display: none; position:absolute; border: 0px solid #000; z-index:10;">
				         <div class="seleccione">Seleccione uno<div style="position:absolute; right:10px; top:3px"> <img id="newItemM" src="../img/minus.png" width="16" height="16" vspace="0" hspace="0" align="left" border="0" style="cursor:pointer;" class="triggerClose"></div>
				         <div>
				         <input type="text" size="25" id="newItemMInput" onkeyup="lookup('newItemMInput',this.value,'4');" onblur="fill('newItemMInput',);" autocomplete="off"  class="boxinput"/>
				         </div></div>
				      </div>
						<select name="sector[]" class="ComboBox" id="newItemMCombo" 
						style="width: 250px; height:50px; position: relative; z-index: 98; top: -17px" multiple="multiple" size="2">
						<?php
						if( mysqli_result($rs_query, 0, "sector")!='') {
						$Seccion=split('-',mysqli_result($rs_query, 0, "sector"));

						if (is_array($Seccion)) {
							foreach ($Seccion as $x)
							{
							$sql_Seccion="select * from `sector` where `codsector`='$x'";
							$res_Seccion=mysqli_query($GLOBALS["___mysqli_ston"], $sql_Seccion);
							   if ($linea=mysqli_fetch_array($res_Seccion)){
							   echo "<option value='$x' style='background-color:#".$linea['color']."; color: ".color_inverse($linea['color']).";'><span style=\"background-color:#".$linea['color']."; color: ".color_inverse($linea['color']).";\">".$linea['descripcion']."</span></option>";
							   }
							}
						} else {
							$sql_Seccion="select * from `sector` where `codsector`='". mysqli_result($rs_query, 0, "sector")."'";
							$res_Seccion=mysqli_query($GLOBALS["___mysqli_ston"], $sql_Seccion);
							   if ($linea=mysqli_fetch_array($res_Seccion)){
							   echo "<option value='$x' style='background-color:#".$linea['color']."; color: ".color_inverse($linea['color']).";'><span style=\"background-color:#".$linea['color']."; color: ".color_inverse($linea['color']).";\">".$linea['descripcion']."</span></option>";
							   }
						}
						}
						?>
						</select>				      						
						</td></tr>
						<tr><td colspan="4">
						<table class="fuente8" width="100%" cellspacing=1 cellpadding=2 border=0><tr><td align="center">
						<fieldset><legend>&nbsp;Datos para el lector de huellas&nbsp;</legend>						
						<input type="hidden" name="huella" value="1">
						<label>  Huella registrada
						<?php
						$estado=mysqli_result($rs_query, 0, "huella");
						 if ( $estado=='' or $estado==0) {
						?>
						<input type="checkbox" name="huella" value="0" disabled> 
						<?php }
						?>
							<span></span>
						</label>&nbsp;&nbsp;	
						Nº
						<input type="text" size="10" name="pin" id="pin" value="<?php echo mysqli_result($rs_query, 0, "pin")?>" class="cajaPequena2" disabled></input>
						Tipo de rol del usuario
						<input  name="rol" id="rol" hidden >
						<select id="Pregunta"  type="text"  class="comboMedio"  onchange="document.getElementById('rol').value =this.value;">
						<?php 
							$role= mysqli_result($rs_query, 0, "role");
							$opciones = array();
							$opciones[0] = "Nivel Usuario";
							$opciones[2] = "Nivel Enroller";
							$opciones[12] = "Nivel Manager";
							$opciones[14] = "Nivel Super Manager";

						foreach($opciones as $key=>$opt) {
						   if ($key==$role) {
						      echo "<option value='$key' selected>$opt</option>";
						   } else {
						      echo "<option value='$key'>$opt</option>";
						   }
						}
						?>						
						</select> 
						</fieldset>
						</td></tr></table>		
						</td></tr>
						</table>
						</div>
			  <br style="line-height:5px">
				<div>
						<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
			      </div>


					</div>				
			</div>
		</div>
		

<div class="suggestionsBox" id="suggestions" style="display: none;">
      <div class="suggestionList" id="autoSuggestionsList" >
	       &nbsp;
      </div>
</div>

<div class="suggestionsBoxLong" id="suggestionslong" style="display: none;">
      <div class="suggestionListLong" id="autoSuggestionsListlong" >
	       &nbsp;
      </div>
</div> 	
	<script type="text/javascript">
	
		//apply masking to the demo-field
		//pass the field reference, masking symbol, and character limit
		//new MaskedPassword(document.getElementById("contrasenia"), '\u25CF');
	
	</script> 
	
</body></html>