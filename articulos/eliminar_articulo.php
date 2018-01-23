<?php
////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 

include ("../conectar.php"); 
include ("../funciones/fechas.php"); 


$codarticulo=$_GET["codarticulo"];
$cadena_busqueda=$_GET["cadena_busqueda"];

$query="SELECT * FROM articulos WHERE codarticulo='$codarticulo'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
$codigobarras=mysqli_result($rs_query, 0, "codigobarras");

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">

		
		<script language="javascript">
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		function aceptar(codarticulo) {
			location.href="guardar_articulo.php?codarticulo=" + codarticulo + "&accion=baja" + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
		}
		
		function cancelar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
		</script>
	</head>
		<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">MODIFICAR ARTICULO </div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_articulo.php" enctype="multipart/form-data">
				<input id="accion" name="accion" value="modificar" type="hidden">
				<input id="id" name="id" value="<?php echo $codarticulo?>" type="hidden">
				<input id="codarticulo" name="codarticulo" value="<?php echo $codarticulo?>" type="hidden">
				<table class="fuente8" width="98%"><tr><td valign="top" width="30%">
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
						<tr>
						<td width="20%">Referencia</td>
						<?php $referencia=mysqli_result($rs_query, 0, "referencia");?>
					      <td colspan="2"><input name="Areferencia" id="areferencia" value="<?php echo mysqli_result($rs_query, 0, "referencia")?>" maxlength="20" class="cajaGrande" type="text" data-index="1"></td>
						</tr>
						<?php
						$familia=mysqli_result($rs_query, 0, "codfamilia");
					  	$query_familias="SELECT * FROM familias WHERE borrado=0 ORDER BY nombre ASC";
						$res_familias=mysqli_query($GLOBALS["___mysqli_ston"], $query_familias);
						$contador=0;
					  ?>
						<tr>
							<td width="11%">Familia</td>
							<td colspan="2"><select id="oFamilias" name="AcboFamilias" class="comboGrande" data-index="2">
							
								<option value="0">Seleccione una familia</option>
								<?php
								while ($contador < mysqli_num_rows($res_familias)) { 
									if ($familia==mysqli_result($res_familias, $contador, "codfamilia")) {?>
								<option value="<?php echo mysqli_result($res_familias, $contador, "codfamilia")?>" selected="selected"><?php echo mysqli_result($res_familias, $contador, "nombre")?></option>
								<?php } else { ?>
								<option value="<?php echo mysqli_result($res_familias, $contador, "codfamilia")?>"><?php echo mysqli_result($res_familias, $contador, "nombre")?></option>
								<?php } 
									$contador++;
								} ?>				
								</select>							</td>
				        </tr>
						<tr>
							<td width="11%">Descripci&oacute;n</td>
						    <td colspan="2"><textarea name="Adescripcion" cols="41" rows="2" id="descripcion" class="areaTexto" data-index="3"><?php echo mysqli_result($rs_query, 0, "descripcion")?></textarea></td>
				        </tr>
						<?php
						$impuesto=mysqli_result($rs_query, 0, "impuesto");
					  	$query_impuesto="SELECT codimpuesto,valor FROM impuestos WHERE borrado=0 ORDER BY nombre ASC";
						$res_impuesto=mysqli_query($GLOBALS["___mysqli_ston"], $query_impuesto);
						$contador=0;
					  ?>
						<tr>
							<td width="11%">Impuesto</td>
							<td colspan="2"><select id="oImpuestos" name="AcboImpuestos" class="comboMedio" data-index="4">
							
								<option value="0">Seleccione un impuesto</option>
								<?php
								while ($contador < mysqli_num_rows($res_impuesto)) { 
									if ($impuesto==mysqli_result($res_impuesto, $contador, "valor")) { ?>
								<option value="<?php echo mysqli_result($res_impuesto, $contador, "valor")?>" selected="selected"><?php echo mysqli_result($res_impuesto, $contador, "valor")?></option>
								<?php } else { ?>
								<option value="<?php echo mysqli_result($res_impuesto, $contador, "valor")?>"><?php echo mysqli_result($res_impuesto, $contador, "valor")?></option>
								<?php } 
								$contador++;
								} ?>				
								</select> %							</td>
				        </tr>
						<?php
						$proveedor1=mysqli_result($rs_query, 0, "codproveedor1");
					  	$query_proveedores="SELECT codproveedor as codproveedor1,nombre,nif FROM proveedores WHERE borrado=0 ORDER BY nombre ASC";
						$res_proveedores=mysqli_query($GLOBALS["___mysqli_ston"], $query_proveedores);
						$contador=0;
					  ?>
						<tr>
							<td>Proveedor&nbsp;1</td>
							<td colspan="2"><select id="cboProveedores1" name="acboProveedores1" class="comboGrande" data-index="5">
							<?php if ($proveedor1==0) { ?>
							<option value="0" selected="selected">Todos los proveedores</option>
							<?php } else { ?>
							<option value="0">Todos los proveedores</option>
							<?php } ?>
								<?php
								while ($contador < mysqli_num_rows($res_proveedores)) { 
									if ( mysqli_result($res_proveedores, $contador, "codproveedor1") == $proveedor1) { ?>
								<option value="<?php echo mysqli_result($res_proveedores, $contador, "codproveedor1")?>" selected><?php echo mysqli_result($res_proveedores, $contador, "nif")?> -- <?php echo mysqli_result($res_proveedores, $contador, "nombre")?></option>
								<?php } else { ?> 
								<option value="<?php echo mysqli_result($res_proveedores, $contador, "codproveedor1")?>"><?php echo mysqli_result($res_proveedores, $contador, "nif")?> -- <?php echo mysqli_result($res_proveedores, $contador, "nombre")?></option>
								<?php }
								$contador++;
								} ?>				
								</select>							</td>
					    </tr>
					<?php
						$proveedor2=mysqli_result($rs_query, 0, "codproveedor2");
					  	$query_proveedores="SELECT codproveedor as codproveedor2,nombre,nif FROM proveedores WHERE borrado=0 ORDER BY nombre ASC";
						$res_proveedores=mysqli_query($GLOBALS["___mysqli_ston"], $query_proveedores);
						$contador=0;
					  ?>
						<tr>
							<td>Proveedor&nbsp;2</td>
							<td colspan="2"><select id="cboProveedores2" name="acboProveedores2" class="comboGrande" data-index="6">
							<option value="0">Todos los proveedores</option>
								<?php
								while ($contador < mysqli_num_rows($res_proveedores)) { 
									if ( mysqli_result($res_proveedores, $contador, "codproveedor2") == $proveedor2) { ?>
								<option value="<?php echo mysqli_result($res_proveedores, $contador, "codproveedor2")?>" selected><?php echo mysqli_result($res_proveedores, $contador, "nif")?> -- <?php echo mysqli_result($res_proveedores, $contador, "nombre")?></option>
								<?php } else { ?> 
								<option value="<?php echo mysqli_result($res_proveedores, $contador, "codproveedor2")?>"><?php echo mysqli_result($res_proveedores, $contador, "nif")?> -- <?php echo mysqli_result($res_proveedores, $contador, "nombre")?></option>
								<?php }
								$contador++;
								} ?>				
								</select>							</td>
					    </tr>
						<tr>
						  <td>Descripci&oacute;n&nbsp;corta</td>
						  <td colspan="2"><input NAME="Adescripcion_corta" id="adescripcion_corta" type="text" class="cajaGrande"  maxlength="30" value="<?php echo mysqli_result($rs_query, 0, "descripcion_corta")?>" data-index="7"></td>
				      </tr>
					  <?php
					  	$codubicacion=mysqli_result($rs_query, 0, "codubicacion");
					  	$query_ubicacion="SELECT codubicacion,nombre FROM ubicaciones WHERE borrado=0 ORDER BY nombre ASC";
						$res_ubicacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_ubicacion);
						$contador=0;
					  ?>
						<tr>
							<td>Ubicaci&oacute;n</td>
							<td colspan="2"><select id="oUbicacion" name="AcboUbicacion" class="comboGrande" data-index="8">
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
						 <td>Stock</td>
						  <td colspan="2"><input NAME="nstock" type="text" class="cajaPequena" id="stock" size="10" maxlength="10" value="<?php echo mysqli_result($rs_query, 0, "stock")?>" data-index="9"> unidades</td>
				      </tr>
					  	<tr>
						 <td>Stock&nbsp;m&iacute;nimo</td>
						  <td colspan="2"><input NAME="nstock_minimo" type="text" class="cajaPequena" id="stock_minimo" size="8" maxlength="8" value="<?php echo mysqli_result($rs_query, 0, "stock_minimo")?>"  data-index="10"> unidades</td>
				      </tr>
					  	<tr>
						 <td>Aviso&nbsp;m&iacute;nimo</td>
						  <td colspan="2"><select name="aaviso_minimo" id="aviso_minimo" class="comboPequeno" data-index="11">
						  <?php if (mysqli_result($rs_query, 0, "aviso_minimo")==0) { ?>
						  <option value="0" selected="selected">No</option>
						  <option value="1">Si</option>
						  <?php } else { ?>
						  <option value="0">No</option>
						  <option value="1" selected="selected">Si</option>
						  <?php } ?>
						  </select></td>
				      </tr>
						<tr>
							<td>Fecha&nbsp;de&nbsp;alta</td>
							<td colspan="2"><input NAME="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" readonly value="<?php echo implota(mysqli_result($rs_query, 0, "fecha_alta"))?>" data-index="12">
							<img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'">
							<script type="text/javascript">//<![CDATA[
							   Calendar.setup({
							     inputField : "fecha",
							     trigger    : "Image1",
							     onSelect   : function() { this.hide() },
							     showTime   : 12,
							     dateFormat : "%d/%m/%Y"
							   });
							//]]></script>	</td>
					    </tr>
						<tr>
							<td>Fecha&nbsp;de&nbsp;vencimiento</td>
							<td colspan="2"><input NAME="fechavencimiento" type="text" class="cajaPequena" id="fechavencimiento" size="10" maxlength="10" readonly value="<?php echo implota(mysqli_result($rs_query, 0, "fecha_vencimiento"))?>" data-index="13">
							<img src="../img/calendario.png" name="Image2" id="Image2" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'">
						<script type="text/javascript">//<![CDATA[
						   Calendar.setup({
						     inputField : "fechavencimiento",
						     trigger    : "Image2",
						     onSelect   : function() { this.hide() },
						     showTime   : 12,
						     dateFormat : "%d/%m/%Y"
						   });
						//]]></script>	</td>
					    </tr>					    
				      <tr>
						  <td>Código Barras</td>
						  <td><input NAME="codigobarras" type="text" class="cajaMedia" id="codigobarras" size="20" maxlength="20" value="<?php echo mysqli_result($rs_query, 0, "codigobarras")?>" data-index="14"> </td>
				      </tr>					    


				      </table></td><td width="30%" valign="top">
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
				      
				      
					  <tr>
							<td width="11%">Datos&nbsp;del&nbsp;producto</td>
						    <td colspan="3"><textarea name="adatos" cols="41" rows="2" id="adatos" class="areaTexto" data-index="15"><?php echo mysqli_result($rs_query, 0, "datos_producto");?></textarea></td>
				        </tr>
						 <?php
						 $embalaje=mysqli_result($rs_query, 0, "codembalaje");
					  	$query_embalaje="SELECT codembalaje,nombre FROM embalajes WHERE borrado=0 ORDER BY nombre ASC";
						$res_embalaje=mysqli_query($GLOBALS["___mysqli_ston"], $query_embalaje);
						$contador=0;
					  ?>
						<tr>
							<td>Embalaje</td>
							<td colspan="3"><select id="oEmbalaje" name="AcboEmbalaje" class="comboGrande" data-index="16">
							<option value="0">Todos los embalajes</option>
								<?php
								while ($contador < mysqli_num_rows($res_embalaje)) { 
									if ( mysqli_result($res_embalaje, $contador, "codembalaje") == $embalaje) { ?>
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
						  <td colspan="3"><input NAME="nunidades_caja" type="text" class="cajaPequena" id="unidades_caja" size="10" maxlength="10" value="<?php echo mysqli_result($rs_query, 0, "unidades_caja")?>" data-index="17"> unidades</td>
				      </tr>
					  <tr>
						 <td>Preguntar&nbsp;precio&nbsp;ticket</td>
						  <td colspan="3"><select name="aprecio_ticket" id="precio_ticket" class="comboPequeno" data-index="18">
						  <?php if (mysqli_result($rs_query, 0, "precio_ticket")==0) { ?> 
						  <option value="0" selected="selected">No</option>
						  <option value="1">Si</option>
						  <?php } else { ?>
						   <option value="0">No</option>
						  <option value="1" selected="selected">Si</option>
						  <?php } ?>
						  </select></td>
				      </tr>
					  <tr>
						 <td>Modificar&nbsp;descrip.&nbsp;en&nbsp;ticket</td>
						  <td colspan="3"><select name="amodif_descrip" id="modif_descrip" class="comboPequeno" data-index="19">
						   <?php if (mysqli_result($rs_query, 0, "modificar_ticket")==0) { ?>
						  <option value="0" selected="selected">No</option>
						  <option value="1">Si</option>
						  <?php } else { ?>
						  <option value="0">No</option>
						  <option value="1" selected="selected">Si</option>
						  <?php } ?>
						  </select></td>
				      </tr>
					  	 <tr>
							<td width="11%">Observaciones</td>
						    <td  colspan="3"><textarea name="aobservaciones" cols="41" rows="2" id="observaciones" class="areaTexto" data-index="20"><?php echo mysqli_result($rs_query, 0, "observaciones")?></textarea></td>
				        </tr>
				        <tr><td>Moneda</td><td width="26%" colspan="3">
						 <select name="amoneda" id="moneda" class="cajaPequena2">
						  <?php if (mysqli_result($rs_query, 0, "moneda")==1) { ?>
								<option value="1" selected="selected">Pesos</option>
								<option value="2">U$S</option>
						  <?php } else { ?>
								<option value="1">Pesos</option>
								<option value="2" selected="selected">U$S</option>
						  <?php } ?>						 
  							</select></td>
  						</tr>
						<tr>
						  <td>Precio&nbsp;de&nbsp;compra</td>
						  <td><input NAME="qprecio_compra" type="text" class="cajaPequena" id="precio_compra" size="10" maxlength="10" value="<?php echo mysqli_result($rs_query, 0, "precio_compra")?>" data-index="21">
						  </td>
						  <td>Precio&nbsp;de&nbsp;almac&eacute;n</td>
						  <td><input NAME="qprecio_almacen" type="text" class="cajaPequena" id="precio_almacen" size="10" maxlength="10" value="<?php echo mysqli_result($rs_query, 0, "precio_almacen")?>" data-index="22">
						  </td>
				      </tr>
						<tr>
						  <td>Precio&nbsp;de&nbsp;público</td>
						  <td><input NAME="qprecio_tienda" type="text" class="cajaPequena" id="precio_tienda" size="10" maxlength="10" value="<?php echo mysqli_result($rs_query, 0, "precio_tienda")?>" data-index="23">
						  </td>
						  <td>Precio&nbsp;con&nbsp;iva</td>
						  <td><input NAME="qprecio_iva" type="text" class="cajaPequena" id="precio_iva" size="10" maxlength="10" value="<?php echo mysqli_result($rs_query, 0, "precio_iva")?>" data-index="24">
						  </td>
				      </tr>
				      
				      <tr>
						  <td colspan="4"><fieldset style="width:80%">
						  <legend>Ubicación</legend>
						  <table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0><tr><td>
						  Sector</td><td><input name="sector" type="text" class="cajaPequena" id="sector" size="30" maxlength="50" value="<?php echo mysqli_result($rs_query, 0, "sector");?>" data-index="25"></td><td>
						  Pasillo</td><td><input name="pasillo" type="text" class="cajaPequena" id="pasillo" size="30" maxlength="50" value="<?php echo mysqli_result($rs_query, 0, "pasillo");?>" data-index="26"></td></tr><tr><td>
						  Módulo</td><td><input name="modulo" type="text" class="cajaPequena" id="modulo" size="30" maxlength="50" value="<?php echo mysqli_result($rs_query, 0, "modulo");?>" data-index="27"></td><td>
						  Estante</td><td><input name="stante" type="text" class="cajaPequena" id="stante" size="30" maxlength="50" value="<?php echo mysqli_result($rs_query, 0, "estante");?>" data-index="28"></td></tr><tr><td>
						  </tr>
						  </table>
						  </fieldset>
						  </td>
					</tr>				      
				      
					</table>
					</td>
					<td valign="top" width="30%" >
					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
					  <tr>
						  <td valign="top" colspan="2">
						  <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
						  <div class="file-upload btn btn-primary"><span>Imagen&nbsp;[Formato&nbsp;jpg]&nbsp;[200x200]</span> 
								<input class="upload" type="file" name="foto" id="foto" accept="image/jpg" onchange="PreviewImage();">
								</div>	
								
						  </td></tr><tr>
						  <td valign="top" colspan="2">
						  <?php if (mysqli_result($rs_query, 0, "imagen")!=""){ ?>   
								<img id="uploadPreview" style="height: 170px; " src="../fotos/<?php echo mysqli_result($rs_query, 0, "imagen");?>" border="0">					  
						 <?php } else { ?>
								<img id="uploadPreview" style="height: 170px; display: none;" />
						 <?php }	 ?>
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
							<input type="hidden" id="AplancuentacValue" name="Aplancuentac" value="<?php echo mysqli_result($rs_query, 0, "plancuentac");?>">
							<?php
								$codplanc=mysqli_result($rs_query, 0, "plancuentac");
								if($codplanc!='') {
								$query_busqu="SELECT * FROM plandecuentas WHERE borrado=0 AND codplan='".$codplanc."'";
								$rs_busqu=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqu);
								$nombre=mysqli_result($rs_busqu, 0, "codplan").' - '. mysqli_result($rs_busqu, 0, "nombre");
								}	
							
							?>
							<input type="text" size="26" maxlength="60" id="Aplancuentac" value="<?php echo @$nombre;?>"
							 onkeyup="lookupotro(this.id, this.value);" onblur="otro();" autocomplete="off" class="Atrigger cajaMedia"  data-index="29"/>
				        	
				        	</td>
				        	</tr><tr><td>
				        	Cód.&nbsp;Cont.&nbsp;Ven.</td><td>
				        	
							<input type="hidden" id="AplancuentavValue" name="Aplancuentav" value="<?php echo mysqli_result($rs_query, 0, "plancuentav");?>" >
							<?php
								$codplanv=mysqli_result($rs_query, 0, "plancuentav");
								if($codplanv!='') {
								$query_busqu="SELECT * FROM plandecuentas WHERE borrado=0 AND codplan='".$codplanv."'";
								$rs_busqu=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqu);
								$nombre=mysqli_result($rs_busqu, 0, "codplan").' - '. mysqli_result($rs_busqu, 0, "nombre");
								}	
							
							?>
							<input type="text" size="26" maxlength="60" id="Aplancuentav" value="<?php echo @$nombre;?>"
							 onkeyup="lookupotro(this.id, this.value);" onblur="otro();" autocomplete="off" class="Atrigger cajaMedia"  data-index="30"/>
							 				        	
				        	</td>
						</tr>

						<tr>
							<td valign="top" >Codigo&nbsp;de&nbsp;barras<br>
							<img src="../barcode/gd.php?text=<?php echo $codigobarras;?>&height=50"></td>
							<td valign="top">Código&nbsp;QR<br>
							<?php
							$texto="Código-".$codigobarras."-";
							$texto.="Sector-".mysqli_result($rs_query, 0, "sector")."-Pasillo-".mysqli_result($rs_query, 0, "pasillo")."-Modulo-".mysqli_result($rs_query, 0, "modulo")."-Estante-".mysqli_result($rs_query, 0, "estante");
							$width = $height = 150;
							$file = "../tmp/qr".$codigobarras.".png";
							
							 echo "<img src='../barcode/qrcode.php?texto=".$texto."&width=".$width."&height=".$height."&file=".$file."&accion=u'>"; ?></td>
						</tr>						

					</table>
					</td>					
					</td></tr></table>
								  </div>
				<br style="line-height:5px">
				<div>
						<button class="boletin" onClick="aceptar(<?php echo $codarticulo?>);" onMouseOver="style.cursor=cursor"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Eliminar</button>
						<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Cancelar</button>
			  </div>
			 </div>
		  </div>
		</div>
	</body>
</html>
