<?php
include ("../conectar.php"); 
include ("../funciones/fechas.php");

header('Content-Type: text/html; charset=UTF-8');     @mysqli_query($GLOBALS["___mysqli_ston"], "SET NAMES 'utf8'");
$codhoras=$_GET["codhoras"];
$cadena_busqueda=$_GET['cadena_busqueda'];

$query="SELECT * FROM horas WHERE codhoras='$codhoras'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script src="js/jquery.min.js"></script>
		<script language="javascript">
		
		function aceptar(codhoras) {
			location.href="guardar_horas.php?codhoras=" + codhoras + "&accion=baja" + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
		}
		
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
		
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">ELIMINAR HORAS  </div>
				<div id="frmBusqueda">
					<table class="fuente8"><tr><td valign="top">
					<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td>Usuario</td>
						    <td colspan="3">
							<input type="hidden" id="codusuarioValue" name="codusuario" value="<?php echo mysqli_result($rs_query, 0, "codusuario");?>">
							<?php
							$codusuario=mysqli_result($rs_query, 0, "codusuario");
							
							$query_bus="SELECT * FROM USUARIOS WHERE USUARIOID='".$codusuario."'";
							$rs_bus=mysqli_query($GLOBALS["___mysqli_ston"], $query_bus);
							$USUARIONOM= mysqli_result($rs_bus, 0, "USUARIONOM").' '. mysqli_result($rs_bus, 0, "USUARIOAPE");							
							?>							
							<input type="text" size="26" maxlength="60" id="codusuario"  value="<?php echo $USUARIONOM;?>"
							 onkeyup="lookupotro(this.id, this.value);" onblur="fillotro();" autocomplete="off" class="trigger cajaGrande" onFocus="this.style.backgroundColor='#FFFF99'" 
							 onBlur="this.style.backgroundColor='white'"  value="<?php echo $nombre;?>" /></td>
						</tr>
						  <td>Fecha&nbsp;de&nbsp;fin</td>
						  <td><input id="fechafin" type="text" class="cajaPequena" NAME="Afecha" maxlength="10" value="<?php echo implota(mysqli_result($rs_query, 0, "fecha"));?>" readonly>
						  <img src="../img/calendario.png" name="Image2" id="Image2" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">//<![CDATA[
						   Calendar.setup({
						     inputField : "fechafin",
						     trigger    : "Image2",
						     align		 : "Bl",
						     onSelect   : function() { this.hide();  },
						     dateFormat : "%d/%m/%Y"
						   });
						//]]></script>
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
							<td>Cliente</td><td>
							<input type="hidden" id="codclienteValue" name="codcliente" value="<?php echo mysqli_result($rs_query, 0, "codcliente");?>">
							<?php
								$codcliente=mysqli_result($rs_query, 0, "codcliente");
								
								$query_busqu="SELECT * FROM clientes WHERE borrado=0 AND codcliente='".$codcliente."'";
								$rs_busqu=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqu);
								$nombre=mysqli_result($rs_busqu, 0, "nombre").' '. mysqli_result($rs_busqu, 0, "apellido");	
							?>
							<input type="text" size="26" maxlength="60" id="codcliente" value="<?php echo $nombre;?>"
							 onkeyup="lookupotro(this.id, this.value);" onblur="fillotro();" autocomplete="off" class="trigger cajaGrande" onFocus="this.style.backgroundColor='#FFFF99'" 
							 onBlur="this.style.backgroundColor='white'"/>
							</td>
					    </tr>
						<tr>
							
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
			  </div>
				<div>
					<img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar(<?php echo $codhoras;?>)" border="1" onMouseOver="style.cursor=cursor">
					<img id="botonBusqueda" src="../img/botoncancelar.jpg" width="85" height="22" onClick="cancelar()" border="1" onMouseOver="style.cursor=cursor">
			  </div>
			  </div>
		  </div>
		</div>
	</body>
</html>
