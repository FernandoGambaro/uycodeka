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
////header('Content-Type: text/html; charset=UTF-8'); 
////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 

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
<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
<script src="../js3/message.js" type="text/javascript"></script>
<link rel="stylesheet" href="../js3/colorbox.css" />
<script src="../js3/jquery.colorbox.js"></script>

<script src="../js3/jquery.maskedinput.js" type="text/javascript"></script>
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
        	if (index==32) {
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

$(document).ready( function(){
	
$("form:not(.filter) :input:visible:enabled:first").focus();


var headID = window.parent.document.getElementsByTagName("head")[0];         
var newScript = window.parent.document.createElement('script');
newScript.type = 'text/javascript';
newScript.src = 'js/jquery.colorbox.js';
headID.appendChild(newScript);


  $('.Atrigger').click( function(e){
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

      e.preventDefault();
   });//Finaliza trigger

});

	function lookupotro(id,inputString) {
	
		newItemInputValue=id+'Value';
		newItemInput='#'+id;
 		var offset = $(newItemInput).offset();
      var x = offset.left;
      var y = offset.top + 7;
      document.getElementById("suggestionslong").style.left = x - 30;
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
//alert(newItemInputValue + ' -- ' +item0+ "  --  "+newItemInput + " -- "+item1);

		document.getElementById(newItemInputValue).value=item0;
		$(newItemInput).val(item0+'-'+item1);

		setTimeout("$('#suggestionslong').hide();", 200);
	}

</script>
<script type="text/javascript">
function round(value, exp) {
  if (typeof exp === 'undefined' || +exp === 0)
    return Math.round(value);

  value = +value;
  exp  = +exp;

  if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
    return NaN;

  // Shift
  value = value.toString().split('e');
  value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

  // Shift back
  value = value.toString().split('e');
  return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
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
		
		function limpiar() {
			document.getElementById("referencia").value="";
			document.getElementById("descripcion").value="";
			document.getElementById("descripcion_corta").value="";
			document.getElementById("stock_minimo").value="";
			document.getElementById("stock").value="";
			document.getElementById("datos").value="";
			document.getElementById("fecha").value="";
			document.getElementById("unidades_caja").value="";
			document.getElementById("observaciones").value="";
			document.getElementById("precio_compra").value="";
			document.getElementById("precio_almacen").value="";
			document.getElementById("precio_tienda").value="";
			document.getElementById("moneda").value="";
			/*/document.getElementById("pvp").value="";*/
			document.getElementById("precio_iva").value="";
			document.getElementById("foto").value="";
			document.formulario.cboFamilias.options[0].selected = true;
			document.formulario.oImpuesto.options[0].selected = true;
			document.formulario.cboProveedores1.options[0].selected = true;
			document.formulario.cboProveedores2.options[0].selected = true;
			document.formulario.cboUbicacion.options[0].selected = true;
			document.formulario.cboEmbalaje.options[0].selected = true;
			document.formulario.Aaviso_minimo.options[0].selected = true;
			document.formulario.Aprecio_ticket.options[0].selected = true;
			document.formulario.Amodif_descrip.options[0].selected = true;
		}
		function precioiva() {
			var tipoiva=document.formulario.Aiva.options[document.formulario.Aiva.selectedIndex].value;
			var valorimpuesto = tipoiva.split("~")[1];
			var codimpuesto = tipoiva.split("~")[0];

			document.getElementById("impuesto").value=codimpuesto;

			var precio_publico=document.getElementById('precio_tienda').value;
			var valor=parseFloat(precio_publico) + (parseFloat(precio_publico) * parseFloat(valorimpuesto) / 100);
			document.getElementById("precio_iva").value=round(valor,2);			
		}	
		</script>

	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">INSERTAR ARTICULO </div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_articulo.php" enctype="multipart/form-data">
				<input id="accion" name="accion" value="alta" type="hidden">
				<table class="fuente8" width="98%"><tr><td valign="top" width="33%">
				<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
						<tr>
						<td width="15%">Referencia</td>
					      <td width="30%" colspan="3"><input name="Areferencia" id="areferencia" value="" maxlength="20" alt="Referencia" class="cajaGrande" type="text" data-index="1"></td>
				          <td width="55%" rowspan="15" align="left" valign="top"></td>
						</tr>
						<?php
					  	$query_familias="SELECT * FROM familias WHERE borrado=0 ORDER BY nombre ASC";
						$res_familias=mysqli_query($GLOBALS["___mysqli_ston"], $query_familias);
						$contador=0;
					  ?>
						<tr>
							<td width="17%">Familia</td>
							<td colspan="3"><select id="AFamilias" name="AcboFamilias" class="comboGrande" data-index="2">
							
								<option value="0">Seleccione una familia</option>
								<?php
								while ($contador < mysqli_num_rows($res_familias)) { ?>
								<option value="<?php echo mysqli_result($res_familias, $contador, "codfamilia")?>"><?php echo mysqli_result($res_familias, $contador, "nombre")?></option>
								<?php $contador++;
								} ?>				
								</select>							</td>
				        </tr>
						<tr>
							<td width="17%">Descripci&oacute;n</td>
						    <td colspan="3"><textarea name="Adescripcion" cols="35" rows="2" id="adescripcion" class="areaTexto" data-index="3"></textarea></td>
				        </tr>
						<tr>
							<td width="17%">Impuesto</td>
							<td colspan="3">

				            <?php
					  	$query_iva="SELECT * FROM impuestos WHERE borrado=0 ORDER BY nombre ASC";
						$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
						$contador=0;
					  ?>
					  			<input name="AcboImpuestos" type="hidden"  id="impuesto" value="">
								<select id="Aiva" name="Aiva" class="comboPequeno simpleinput"  onchange="precioiva();" data-index="4">
							
								<option value="0">Seleccione una</option>
								<?php
								while ($contador < mysqli_num_rows($res_iva)) {
									if(mysqli_result($res_iva, $contador, "codimpuesto") == 1) {
									?>
								<option value="<?php echo mysqli_result($res_iva, $contador, "codimpuesto").'~'.mysqli_result($res_iva, $contador, "valor");?>" selected="selected"><?php echo mysqli_result($res_iva, $contador, "nombre")?></option>
								<?php
									} else {
									?>
								<option value="<?php echo mysqli_result($res_iva, $contador, "codimpuesto").'~'.mysqli_result($res_iva, $contador, "valor");?>"><?php echo mysqli_result($res_iva, $contador, "nombre")?></option>
								<?php
									} 	
								 $contador++;
								} ?>				
								</select>						            
								</td>
				        </tr>
						<?php
					  	$query_proveedores="SELECT codproveedor,nombre,nif FROM proveedores WHERE borrado=0 ORDER BY nombre ASC";
						$res_proveedores=mysqli_query($GLOBALS["___mysqli_ston"], $query_proveedores);
						$contador=0;
					  ?>
						<tr>
							<td>Proveedor&nbsp;1</td>
							<td colspan="3"><select id="cboProveedores1" name="acboProveedores1" class="comboGrande" data-index="5">
							<option value="0">Todos los proveedores</option>
								<?php
								while ($contador < mysqli_num_rows($res_proveedores)) { 
									if ( mysqli_result($res_proveedores, $contador, "codproveedor") == @$proveedor) { ?>
								<option value="<?php echo mysqli_result($res_proveedores, $contador, "codproveedor")?>" selected><?php echo mysqli_result($res_proveedores, $contador, "nif")?> -- <?php echo mysqli_result($res_proveedores, $contador, "nombre")?></option>
								<?php } else { ?> 
								<option value="<?php echo mysqli_result($res_proveedores, $contador, "codproveedor")?>"><?php echo mysqli_result($res_proveedores, $contador, "nif")?> -- <?php echo mysqli_result($res_proveedores, $contador, "nombre")?></option>
								<?php }
								$contador++;
								} ?>				
								</select>					
								</td>
					    </tr>
					<?php
					  	$query_proveedores="SELECT codproveedor,nombre,nif FROM proveedores WHERE borrado=0 ORDER BY nombre ASC";
						$res_proveedores=mysqli_query($GLOBALS["___mysqli_ston"], $query_proveedores);
						$contador=0;
					  ?>
						<tr>
							<td>Proveedor&nbsp;2</td>
							<td colspan="3"><select id="cboProveedores2" name="acboProveedores2" class="comboGrande" data-index="6">
							<option value="0">Todos los proveedores</option>
								<?php
								while ($contador < mysqli_num_rows($res_proveedores)) { 
									if ( mysqli_result($res_proveedores, $contador, "codproveedor") == @$proveedor) { ?>
								<option value="<?php echo mysqli_result($res_proveedores, $contador, "codproveedor")?>" selected><?php echo mysqli_result($res_proveedores, $contador, "nif")?> -- <?php echo mysqli_result($res_proveedores, $contador, "nombre")?></option>
								<?php } else { ?> 
								<option value="<?php echo mysqli_result($res_proveedores, $contador, "codproveedor")?>"><?php echo mysqli_result($res_proveedores, $contador, "nif")?> -- <?php echo mysqli_result($res_proveedores, $contador, "nombre")?></option>
								<?php }
								$contador++;
								} ?>				
								</select>							</td>
					    </tr>
						<tr>
						  <td>Descripci&oacute;n&nbsp;corta</td>
						  <td colspan="3"><input name="Adescripcion_corta" id="adescripcion_corta" type="text" class="cajaGrande"  maxlength="30" data-index="7"></td>
				      </tr>
					  <?php
					  	$query_ubicacion="SELECT codubicacion,nombre FROM ubicaciones WHERE borrado=0 ORDER BY nombre ASC";
						$res_ubicacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_ubicacion);
						$contador=0;
					  ?>
						<tr>
							<td>Ubicaci&oacute;n</td>
							<td colspan="3"><select id="oUbicacion" name="AcboUbicacion" class="comboGrande" data-index="8">
							<option value="0">Todas las ubicaciones</option>
								<?php
								while ($contador < mysqli_num_rows($res_ubicacion)) { 
									if ( mysqli_result($res_ubicacion, $contador, "codubicacion") == @$ubicacion) { ?>
								<option value="<?php echo mysqli_result($res_ubicacion, $contador, "codubicacion")?>" selected><?php echo mysqli_result($res_ubicacion, $contador, "nombre")?></option>
								<?php } else { ?> 
								<option value="<?php echo mysqli_result($res_ubicacion, $contador, "codubicacion")?>"><?php echo mysqli_result($res_ubicacion, $contador, "nombre")?></option>
								<?php }
								$contador++;
								} ?>				
								</select>							</td>
					    </tr>
						<tr>
						 <td>Stock</td>
						  <td><input name="nstock" type="text" class="cajaMinima" id="stock" size="10" maxlength="10" data-index="9"> unidades</td>
						 <td>Stock m&iacute;nimo</td>
						  <td><input name="nstock_minimo" type="text" class="cajaMinima" id="stock_minimo" size="8" maxlength="8" data-index="10"> unidades</td>
				      </tr>
					  	<tr>
						 <td>Aviso m&iacute;nimo</td>
						  <td colspan="3"><select name="aaviso_minimo" id="aviso_minimo" class="comboPequeno" data-index="11">
						  <option value="0" selected="selected">No</option>
						  <option value="1">Si</option>
						  </select></td>
				      </tr>
						<tr>
							<td>Fecha&nbsp;de&nbsp;alta</td>
							<td colspan="3"><input name="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" readonly data-index="12">
							<img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'">
						<script type="text/javascript">//<![CDATA[
						   Calendar.setup({
						     inputField : "fecha",
						     trigger    : "Image1",
						     onSelect   : function() { this.hide() },
						     showTime   : 12,
						     dateFormat : "%d/%m/%Y"
						   });
						//]]></script>							
						</td>
					    </tr>
						<tr>
							<td>Fecha&nbsp;de&nbsp;vencimiento</td>
							<td colspan="3"><input name="fechavencimiento" type="text" class="cajaPequena" id="fechavencimiento" size="10" maxlength="10" readonly data-index="13">
							<img src="../img/calendario.png" name="Image2" id="Image2" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'">
						<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fechavencimiento",
						     trigger    : "Image2",
						     onSelect   : function() { this.hide() },
						     showTime   : 12,
						     dateFormat : "%d/%m/%Y"
						   });
						</script>							
						</td>
					    </tr>					    
				      
				      </table></td><td width="30%" valign="top">
						<table class="fuente8" width="100%" cellspacing="0" cellpadding="2" border="0">

						  <tr>
							<td>Datos&nbsp;del&nbsp;producto</td>
						    <td colspan="3"><textarea name="adatos" cols="31" rows="2" id="datos" class="areaTexto" data-index="14"></textarea></td>
				        </tr>
						 <?php
					  	$query_embalaje="SELECT codembalaje,nombre FROM embalajes WHERE borrado=0 ORDER BY nombre ASC";
						$res_embalaje=mysqli_query($GLOBALS["___mysqli_ston"], $query_embalaje);
						$contador=0;
					  ?>
						<tr>
							<td>Embalaje</td>
							<td colspan="3"><select id="oEmbalaje" name="AcboEmbalaje" class="comboGrande" data-index="15">
							<option value="0">Todos los embalajes</option>
								<?php
								while ($contador < mysqli_num_rows($res_embalaje)) { 
									if ( mysqli_result($res_embalaje, $contador, "codembalaje") == @$embalaje) { ?>
								<option value="<?php echo mysqli_result($res_embalaje, $contador, "codembalaje")?>" selected><?php echo mysqli_result($res_embalaje, $contador, "nombre")?></option>
								<?php } else { ?> 
								<option value="<?php echo mysqli_result($res_embalaje, $contador, "codembalaje")?>"><?php echo mysqli_result($res_embalaje, $contador, "nombre")?></option>
								<?php }
								$contador++;
								} ?>				
								</select>							</td>
					    </tr>
						<tr>
						 <td>Unidades&nbsp;por&nbsp;caja</td>
						  <td><input name="nunidades_caja" type="text" class="cajaMinima" id="unidades_caja" size="10" maxlength="10" data-index="16">&nbsp;unidades</td>
						  <td>Comisión</td><td>
						  <input name="comision" type="text" class="cajaMinima" id="comision" size="10" maxlength="10" data-index="17"> %</td>

				      </tr>
					  <tr>
						 <td>Preguntar&nbsp;precio&nbsp;ticket</td>
						  <td colspan="3">
						  <select name="aprecio_ticket" id="precio_ticket" class="comboPequeno" data-index="18">
						  <option value="0" selected="selected">No</option>
						  <option value="1">Si</option>
						  </select></td>
				      </tr>
					  <tr>
						 <td>Modificar&nbsp;descrip.&nbsp;en&nbsp;ticket</td>
						  <td colspan="3">
						  <select name="amodif_descrip" id="modif_descrip" class="comboPequeno" data-index="19">
						  <option value="0" selected="selected">No</option>
						  <option value="1">Si</option>
						  </select></td>
				      </tr>
					  	 <tr>
							<td width="17%">Observaciones</td>
						    <td colspan="3"><textarea name="aobservaciones" cols="31" rows="2" id="observaciones" class="areaTexto" data-index="20"></textarea></td>
				        </tr>
				        <tr><td>Moneda</td><td colspan="3">
						 <select name="amoneda" id="moneda" class="cajaPequena2" data-index="21">
<?php
						$sel_resultado="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;
							$xmon=1;
						 while ($contador < mysqli_num_rows($res_resultado)) { 
						 			if (mysqli_result($res_resultado, 0, "moneda")==$xmon) {
						 				?>
						 				<option value="<?php echo $xmon;?>" selected="selected"><?php echo mysqli_result($res_resultado, $contador, "simbolo");?></option>
 										<?php
						 			} else {
						 				?>
						 				<option value="<?php echo $xmon;?>"><?php echo mysqli_result($res_resultado, $contador, "simbolo");?></option>
 										<?php
									}
									$xmon++;						 
						 $contador++;
						 }
						?>						 
  							</select></td>
  						</tr>
						<tr>
						  <td>Precio&nbsp;de&nbsp;compra</td>
						  <td colspan="3"><input name="qprecio_compra" type="text" class="cajaPequena" id="precio_compra" value="0" size="10" maxlength="10" data-index="22">
						  </td>
				      </tr>
					  	<tr>
						  <td>Precio&nbsp;de&nbsp;almac&eacute;n</td>
						  <td colspan="3"><input name="qprecio_almacen" type="text" class="cajaPequena" id="precio_almacen" value="0" size="10" maxlength="10" data-index="23">
						   </td>
				      </tr>
						<tr>
						  <td>Precio&nbsp;público</td>
						  <td colspan="3"><input name="qprecio_tienda" type="text" class="cajaPequena" id="precio_tienda" size="10" maxlength="10" value="0" onblur="precioiva();" data-index="24">
						  </td>
				      </tr>
					  	<!--<tr>
						  <td>Pvp</td>
						  <td><input name="qpvp" type="text" class="cajaPequena" id="pvp" size="10" maxlength="10"> &#8364;</td>
				      </tr>-->
					  <tr>
						  <td>Precio&nbsp;con&nbsp;iva</td>
						  <td colspan="3"><input name="qprecio_iva" type="text" class="cajaPequena" id="precio_iva" size="10" maxlength="10" data-index="25"> </td>
				      </tr>
					</table>
					</td>
					<td valign="top">
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
					  <tr>
						  <td valign="top" colspan="2">
						  <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
						  <div class="file-upload btn btn-primary"><span>Imagen&nbsp;[Formato&nbsp;jpg]&nbsp;[200x200]</span> 
								<input class="upload" type="file" name="foto" id="foto" accept="image/jpg" onchange="PreviewImage();">
								</div>						  
						  </td></tr><tr>
						  <td valign="top" colspan="2">
								<img id="uploadPreview" style="height: 200px; display: none;" />
						<script type="text/javascript">
						
						    function PreviewImage() {
						        var oFReader = new FileReader();
						        oFReader.readAsDataURL(document.getElementById("foto").files[0]);
						
						        oFReader.onload = function (oFREvent) {
						            document.getElementById("uploadPreview").src = oFREvent.target.result;
						            $("#uploadPreview").show();
						        };
						    };
						
						</script>						  
						  
						  </td>
				      </tr>
				      <tr>
				        	<td>Cód.&nbsp;Cont.&nbsp;Comp.</td><td>
							<input type="hidden" id="AplancuentacValue" name="Aplancuentac"  >

							<input type="text" size="26" maxlength="60" id="Aplancuentac"
							 onkeyup="lookupotro(this.id, this.value);" onblur="otro();" autocomplete="off" class="Atrigger cajaMedia"  data-index="26"/>
				        	
				        	</td>
				        	</tr><tr><td>
				        	Cód.&nbsp;Cont.&nbsp;Ven.</td><td>
				        	
							<input type="hidden" id="AplancuentavValue" name="Aplancuentav" >

							<input type="text" size="26" maxlength="60" id="Aplancuentav"
							 onkeyup="lookupotro(this.id, this.value);" onblur="otro();" autocomplete="off" class="Atrigger cajaMedia"  data-index="27"/>
							 				        	
				        	</td>
						</tr>				      
				      <tr>
						  <td colspan="2">Código Barras</td></tr><tr>
						  <td colspan="2"><input name="codigobarras" type="text" class="cajaMedia" id="codigobarras" size="20" maxlength="20" data-index="28"> </td>
				      </tr>
				      <tr>
						  <td colspan="4"><fieldset style="width:80%">
						  <legend>Ubicación</legend>
						  <table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0><tr><td>
						  Sector</td><td><input name="sector" type="text" class="cajaPequena" id="sector" size="30" maxlength="50"  data-index="29"></td><td>
						  Pasillo</td><td><input name="spasillo" type="text" class="cajaPequena" id="pasillo" size="30" maxlength="50" data-index="30"></td></tr><tr><td>
						  Módulo</td><td><input name="modulo" type="text" class="cajaPequena" id="modulo" size="30" maxlength="50" data-index="31"></td><td>
						  Estante</td><td><input name="stante" type="text" class="cajaPequena" id="estante" size="30" maxlength="50" data-index="32"></td></tr><tr><td>
						  </tr>
						  </table>
						  </fieldset>
						  </td>
					</tr>

					</table>
					</td>
					</tr></table>
			  </div>
			  	<br style="line-height:5px">	
				<div>
						<button class="boletin" onClick="validar(formulario,true);" onMouseOver="style.cursor=cursor"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Guardar</button>
						<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Cancelar</button>
					<input type="hidden" name="id" id="id" value="">					
			  </div>
			  </form>	
<div class="suggestionsBoxLong" id="suggestionslong" style="display: none;">
      <div class="suggestionListLong" id="autoSuggestionsListlong" style="text-align: left;" >
	       &nbsp;
      </div>
</div>			  
			 </div>			
		  </div>
		</div>
				
		
	</body>
</html>
