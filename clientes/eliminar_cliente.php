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
 
include ("../conectar.php"); 

$codcliente=$_GET["codcliente"];
$cadena_busqueda=$_GET['cadena_busqueda'];

$query="SELECT * FROM clientes WHERE codcliente='$codcliente'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

?>
<html>
	<head>
		<title>Principal</title>
	<link href="../css3/estilos.css" type="text/css" rel="stylesheet">
		<script src="../js3/jquery.min.js"></script>

		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="../js3/jquery.colorbox.js"></script>
		
		<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
		<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js3/message.js" type="text/javascript"></script>
		<script src="../js3/jquery.msgBox.js" type="text/javascript"></script>
		<link href="../js3/msgBoxLight.css" rel="stylesheet" type="text/css">	
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
        	if (index==35) {
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

		$(".callbacks").colorbox({
			iframe:true, width:"99%", height:"99%",
			onCleanup:function(){ window.location.reload();	}
		});

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
		<script language="javascript">
		
		function cancelar() {
			
			parent.$('idOfDomElement').colorbox.close();
			/*location.href="index.php?cadena_busqueda=<?php echo $cadena_busqueda?>";*/
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
		<div id="pagina"><p>&nbsp;</p>
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">ELIMINAR CLIENTE - <?php echo $codcliente?> </div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_cliente.php">
				<table class="fuente8"><tr><td valign="top">
					<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td width="15%">Nombre</td>
						    <td colspan="3"><input name="Anombre" autocomplete="off" type="text" class="cajaGrande" id="aNombre" size="45" maxlength="45" value="<?php echo mysqli_result($rs_query, 0, "nombre");?>" data-index="1"></td>
				        </tr>
						<tr>
							<td width="15%">Apellido</td>
						    <td colspan="3"><input name="aapellido" autocomplete="off" type="text" class="cajaGrande" id="aApellido" size="45" maxlength="45" value="<?php echo mysqli_result($rs_query, 0, "apellido");?>" data-index="2"></td>
				        </tr>
						<tr>
							<td>Empresa</td>
						    <td colspan="3"><input name="aempresa" autocomplete="off" type="text" class="cajaGrande" id="empresa" size="45" maxlength="45" value="<?php echo mysqli_result($rs_query, 0, "empresa");?>" data-index="3"></td>
						</tr>
				        
						<tr>
						  <td><select type=text size=1 name="TTiponif" id="aDocumento" class="comboPequeno" data-index="4">
							<?php
								$tipo = array("Documento", "RUT","CI", "Pasaporte");
							$xx=0;
							foreach($tipo as $tpo) {
								if ($xx==mysqli_result($rs_query, 0, "tiponif")){
							      echo "<option value='$xx' selected>$tpo</option>";
								} else {
							      echo "<option value='$xx'>$tpo</option>";
								}
							$xx++;
							}
							?>
							</select>
						  
						  </td>
						  <td ><input id="nif" type="text" autocomplete="off" class="cajaPequena" name="anif" maxlength="15" value="<?php echo mysqli_result($rs_query, 0, "nif");?>" data-index="5"></td>
							<td>Estado</td>
							<td><select type=text size=1 name="Ttipo" id="aEstado" class="comboMedio" data-index="6">
							<?php
								$tipo = array("Sel. uno", "Cliente","MCC", "Aún no");
							$xx=0;
							foreach($tipo as $tpo) {
								if ($xx==mysqli_result($rs_query, 0, "tipo")){
							      echo "<option value='$xx' selected>$tpo</option>";
								} else {
							      echo "<option value='$xx'>$tpo</option>";
								}
							$xx++;
							}
							?>
							</select></td>
						  
				      </tr>
						<tr>
							<td>Tel&eacute;fono</td>
							<td><input id="telefono" name="atelefono" autocomplete="off" type="text" class="cajaPequena" maxlength="14" value="<?php echo mysqli_result($rs_query, 0, "telefono")?>" data-index="7"></td>

							<td>M&oacute;vil</td>
							<td width="50%"><input id="movil" name="amovil" type="text" class="cajaPequena" maxlength="14" value="<?php echo mysqli_result($rs_query, 0, "movil");?>" data-index="8"></td>
					    </tr>
						<tr>
							<td>Tel&eacute;fono 2</td>
							<td><input id="telefono" name="atelefono2" autocomplete="off" type="text" class="cajaPequena" maxlength="14" value="<?php echo mysqli_result($rs_query, 0, "telefono2")?>" data-index="9"></td>

							<td>Fax</td>
							<td width="50%"><input id="fax" name="afax" type="text" class="cajaPequena" maxlength="14" value="<?php echo mysqli_result($rs_query, 0, "fax");?>" data-index="10"></td>
					    </tr>					    
						<tr>
							<td>Email&nbsp;primario  </td>
							<td colspan="3"><input name="aemail" type="text" class="cajaGrande" id="email" size="35" maxlength="35" value="<?php echo mysqli_result($rs_query, 0, "email");?>" data-index="11"></td>
					    </tr>
						<tr>
							<td>Email&nbsp;secundario</td>
							<td colspan="3"><input name="aemail2" type="text" class="cajaGrande" id="email" size="35" maxlength="35" value="<?php echo mysqli_result($rs_query, 0, "email2");?>" data-index="12"></td>
					    </tr>
						<tr>
							<td>Direcci&oacute;n&nbsp;web </td>
							<td colspan="3"><input name="aweb" type="text" class="cajaGrande" id="web" size="45" maxlength="45" value="<?php echo mysqli_result($rs_query, 0, "web")?>" data-index="13"></td>
					    </tr>				      
					    
						<tr>
							<td>Contraseña  </td>
							<td colspan="3"><input name="contrasenia" type="password" class="cajaGrande" id="contrasenia" size="35" maxlength="35" data-index="14"></td>
					    </tr>
						<tr><td>Pregunta  </td>
						<td colspan="3"><select id="secQ" name="secQ" type="text" class="comboGrande" data-index="15">
							<?php
								$questions = array();
								$questions[0] = "Seleccione una Pregunta";
								$questions[1] = "¿En que ciudad nació?";
								$questions[2] = "¿Cúal es su color favorito?";
								$questions[3] = "¿En qué año se graduo de la facultad?";
								$questions[4] = "¿Cual es el segundo nombre de su novio/novia/marido/esposa?";
								$questions[5] = "¿Cúal es su auto favorito?";
								$questions[6] = "¿Cúal es el nombre de su madre?";
							$xx=0;
							foreach($questions as $pregunta) {
							   if ($xx==mysqli_result($rs_query, 0, 'secQ')) {
							      echo "<option value='$xx' selected>$pregunta</option>";
							   } else {
							      echo "<option value='$xx'>$pregunta</option>";
							   }
							$xx++;
							}
							?>
						</select></td>
						</tr><tr><td>Respuesta  </td>
						<td colspan="3">
						<input type="text" size="26" name="secA" id="secA" class="cajaGrande"  value="<?php echo mysqli_result($rs_query, 0, "secA")?>" data-index="16"></input>
						
						</td></tr><tr><td>Ejecutivo&nbsp;de&nbsp;cuenta</td>
					  <?php
					  	$codusuario=mysqli_result($rs_query, 0, "codusuarios");
					  	$query_usuario="SELECT * FROM usuarios WHERE estado='0'";
						$res_usuario=mysqli_query($GLOBALS["___mysqli_ston"], $query_usuario);
						$contador=0;
					  ?>						
							<td colspan="3" ><select id="codusuario" name="codusuarios" class="comboGrande" data-index="17">
							<option value="0" selected="selected">Seleccione un ejecutivo de cuenta</option>
								<?php
								while ($contador < mysqli_num_rows($res_usuario)) { 
									if ($codusuario== mysqli_result($res_usuario, $contador, "codusuarios")) {?>
								<option value="<?php echo mysqli_result($res_usuario, $contador, 'codusuarios');?>" selected="selected"><?php echo mysqli_result($res_usuario, $contador, "nombre");?> <?php echo mysqli_result($res_usuario, $contador, "apellido");?></option>
								<?php } else { ?>
									<option value="<?php echo mysqli_result($res_usuario, $contador, "codusuarios");?>"><?php echo mysqli_result($res_usuario, $contador, "nombre");?> <?php echo mysqli_result($res_usuario, $contador, "apellido");?></option>
								<?php } $contador++;
								} ?>				
								</select>
							</td>						
						
						</tr>
						
					</table></td>
					
					        <td rowspan="14" align="left" valign="top">
				        
					        &nbsp;
					        
					        </td>
					
					<td>						
						<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0>	
					  <?php
					  	$codpais=mysqli_result($rs_query, 0, "codpais");
					  	$query_pais="SELECT * FROM paises ORDER BY nombre ASC";
						$res_pais=mysqli_query($GLOBALS["___mysqli_ston"], $query_pais);
						$contador=0;
					  ?>
						<tr>
							<td width="15%">País</td>
							<td colspan="3"><select id="codpais" name="codpais" class="comboGrande" data-index="18">
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
						  <td>Localidad</td>
						  <td colspan="3"><input name="alocalidad" type="text" class="cajaGrande" id="localidad" size="35" maxlength="35" value="<?php echo mysqli_result($rs_query, 0, "localidad")?>" data-index="19"></td>
				      </tr>
					  <?php
					  	$codprovincia=mysqli_result($rs_query, 0, "codprovincia");
					  	$query_provincias="SELECT * FROM provincias ORDER BY nombreprovincia ASC";
						$res_provincias=mysqli_query($GLOBALS["___mysqli_ston"], $query_provincias);
						$contador=0;
					  ?>
						<tr>
							<td width="15%">Departamento</td>
							<td colspan="3"><select id="cboProvincias" name="cboProvincias" class="comboGrande" data-index="20">
							<option value="0">Seleccione una provincia</option>
								<?php
								while ($contador < mysqli_num_rows($res_provincias)) { 
									if ($codprovincia == mysqli_result($res_provincias, $contador, "codprovincia")) {?>
								<option value="<?php echo mysqli_result($res_provincias, $contador, "codprovincia")?>" selected="selected"><?php echo mysqli_result($res_provincias, $contador, "nombreprovincia");?></option>
								<?php } else { ?>
									<option value="<?php echo mysqli_result($res_provincias, $contador, "codprovincia")?>"><?php echo mysqli_result($res_provincias, $contador, "nombreprovincia");?></option>
								<?php } $contador++;
								} ?>				
								</select>
							</td>
				        </tr>								      
						<tr>
						  <td>Direcci&oacute;n</td>
						  <td colspan="3"><input name="adireccion" type="text" class="cajaGrande" id="direccion" size="45" maxlength="45" value="<?php echo mysqli_result($rs_query, 0, "direccion")?>" data-index="21"></td>
				      </tr>
						<tr>
							<td>C&oacute;digo&nbsp;postal </td>
							<td colspan="3"><input id="codpostal" type="text" class="cajaPequena" name="acodpostal" maxlength="5" value="<?php echo mysqli_result($rs_query, 0, "codpostal")?>" data-index="22"></td>
					    </tr>

						<?php
						$codformapago=mysqli_result($rs_query, 0, "codformapago");
					  	$query_formapago="SELECT * FROM formapago WHERE borrado=0 ORDER BY nombrefp ASC";
						$res_formapago=mysqli_query($GLOBALS["___mysqli_ston"], $query_formapago);
						$contador=0;
					  ?>
						<tr>
							<td width="15%">Forma&nbsp;de&nbsp;pago</td>
							<td colspan="3"><select id="cboFPago" name="cboFPago" class="comboGrande" data-index="23">
							<option value="0">Seleccione una forma de pago</option>
								<?php
								while ($contador < mysqli_num_rows($res_formapago)) { 
									if ($codformapago == mysqli_result($res_formapago, $contador, "codformapago")) { ?>
								<option value="<?php echo mysqli_result($res_formapago, $contador, "codformapago")?>" selected="selected"><?php echo mysqli_result($res_formapago, $contador, "nombrefp");?></option>
								<?php } else { ?>
								<option value="<?php echo mysqli_result($res_formapago, $contador, "codformapago")?>"><?php echo mysqli_result($res_formapago, $contador, "nombrefp");?></option>
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
							<td width="15%">Entidad&nbsp;Bancaria</td>
							<td colspan="3"><select id="cboBanco" name="cboBanco" class="comboGrande" data-index="24">
							<option value="0">Seleccione&nbsp;una&nbsp;Entidad&nbsp;Bancaria</option>
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
						<tr>
							<td>Cuenta&nbsp;bancaria</td>
							<td colspan="3"><input id="cuentabanco" type="text" class="cajaGrande" name="acuentabanco" maxlength="20" value="<?php echo mysqli_result($rs_query, 0, "cuentabancaria")?>" data-index="25"></td>
					    </tr>
						<tr>					  
						  
						  <tr>
						  <td>
						  Agencia&nbsp;de&nbsp;cargas</td>
						  <td colspan="3">
						  <input name="aagencia" type="text" class="cajaGrande" id="sector" size="30" maxlength="50" value="<?php echo mysqli_result($rs_query, 0, "agencia");?>" data-index="26"></td>
						</tr>	
											  
						<tr>
						<td colspan="3"><fieldset><legend>Recepción</legend>
							<table class="fuente8" width="100%" cellspacing="0" cellpadding="3" border="0">
							<tr>
						  <td>Día/s</td>
						  <td><input name="arecepciondia" type="text" class="cajaPequena2" id="Día recepción" size="30" maxlength="50" value="<?php echo mysqli_result($rs_query, 0, "recepciondia");?>" data-index="27"></td>
						  <td>Hora/s</td>
						  <td><input name="arecepcionhora" type="text" class="cajaPequena2" id="Horario recepción" size="30" maxlength="50" value="<?php echo mysqli_result($rs_query, 0, "recepcionhora");?>" data-index="28"></td>
						  <td>Contacto</td>
						  <td><input name="arecepcioncontacto" type="text" class="cajaMedia" id="Contacto recepcion" size="30" maxlength="50" value="<?php echo mysqli_result($rs_query, 0, "recepcioncontacto");?>" data-index="29"></td>
							</tr>
							</table>						
						</fieldset></td>
						
						</tr>  
						<tr>
						<td colspan="3"><fieldset><legend>Pagos</legend>
							<table class="fuente8" width="100%" cellspacing="0" cellpadding="3" border="0">
							<tr>
						  <td>Día/s</td>
						  <td><input name="apagodia" type="text" class="cajaPequena2" id="Día pago" size="30" maxlength="50" value="<?php echo mysqli_result($rs_query, 0, "pagodia");?>" data-index="30"></td>
						  <td>Hora/s</td>
						  <td><input name="apagohora" type="text" class="cajaPequena2" id="Horario pago" size="30" maxlength="50" value="<?php echo mysqli_result($rs_query, 0, "pagohora");?>" data-index="31"></td>
						  <td>Contacto</td>
						  <td><input name="apagocontacto" type="text" class="cajaMedia" id="Contacto pago" size="30" maxlength="50" value="<?php echo mysqli_result($rs_query, 0, "pagocontacto");?>" data-index="32"></td>
							</tr>
							</table>						
						</fieldset></td>
						
						</tr> 						  
			     		<tr>
							<td>Abonado/Service</td>
							<td colspan="3"><select type=text size=1 name="service" id="service" class="comboPequeno" data-index="33">
							<?php
								$tipo = array("Seleccione un tipo", "Común","Abonado A", "Abonado B");
							$xx=0;
							foreach($tipo as $tpo) {
								if ($xx==mysqli_result($rs_query, 0, 'service')){
							      echo "<option value='$xx' selected>$tpo</option>";
								} else {
							      echo "<option value='$xx'>$tpo</option>";
								}
							$xx++;
							}
							?>
							</select></td>
						  
				      </tr>
				      <tr>
				      <td>Horas&nbsp;Asig./Mes:</td>
				      <td><input id="horas" type="text" class="cajaPequena" name="nhoras" maxlength="5" value="<?php echo mysqli_result($rs_query, 0, "horas")?>" data-index="34">
				      &nbsp;Cód.&nbsp;Cont.</td><td>
							<input type="hidden" id="AplancuentacValue" name="Aplancuentac" value="<?php echo mysqli_result($rs_query, 0, "plancuenta");?>">
							<?php
								$codplanc=mysqli_result($rs_query, 0, "plancuenta");
								if($codplanc!='') {
								$query_busqu="SELECT * FROM plandecuentas WHERE borrado=0 AND codplan='".$codplanc."'";
								$rs_busqu=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqu);
								$nombre=mysqli_result($rs_busqu, 0, "codplan").' - '. mysqli_result($rs_busqu, 0, "nombre");
								}	
							
							?>
							<input type="text" size="26" maxlength="60" id="Aplancuentac" value="<?php echo @$nombre;?>"
							 onkeyup="lookupotro(this.id, this.value);" onblur="otro();" autocomplete="off" class="Atrigger cajaMedia" data-index="35"/>
				        	
				        	</td>
				      </tr>	
					</table>
					</td></tr></table>
			  </div>
			  <br style="line-height:5px">
				<div>
						<button class="boletin" onClick="validar(formulario,true);" onMouseOver="style.cursor=cursor"><i class="fa fa-minus" aria-hidden="true"></i>&nbsp;Eliminar</button>
						<button class="boletin" onClick="cancelar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
					<input id="accion" name="accion" value="baja" type="hidden">
					<input id="cadena_busqueda" name="cadena_busqueda" value="<?php echo $cadena_busqueda?>" value="" type="hidden">
					<input id="codcliente" name="codcliente" value="<?php echo $codcliente?>" type="hidden">
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
