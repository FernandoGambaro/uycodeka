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
 

date_default_timezone_set('America/Montevideo');
include ("../../conectar.php"); 
include ("../../funciones/fechas.php"); 

$codequipo=$_GET["codequipo"];
$codcliente=isset($_POST["codcliente"]) ? @$_GET["codcliente"] : @$_POST["codcliente"];

$query="SELECT * FROM equipos WHERE codequipo='$codequipo'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

?>
<html>
	<head>
		<title>Principal</title>
		<link href="../../estilos/estilos.css" type="text/css" rel="stylesheet">
    <script src="../../calendario/jscal2.js"></script>
    <script src="../../calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../../calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../../calendario/css/win2k/win2k.css" />		
		<script type="text/javascript" src="../../funciones/validar.js"></script>
		<!-- iconos para los botones -->       
<link rel="stylesheet" href="../../css3/css/font-awesome.min.css">		
		<script language="javascript">
		
		function cancelar() {
			//parent.$('idOfDomElement').colorbox.close();
			var e=document.getElementById("codcliente").value;
			location.href="index.php?codcliente="+e;
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
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">MODIFICAR equipo </div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_equipo.php">
				
				<table class="fuente8"><tr><td valign="top">
					<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td>Fecha&nbsp;de&nbsp;alta</td>
							<td><input name="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" value="<?php echo implota(mysqli_result($rs_query, 0, "fecha"));?>" readonly> 
							<img src="../../img/calendario.png" name="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'">
						<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fecha",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide() },
						     dateFormat : "%d/%m/%Y"
						   });
						</script>							
						</td>
					    </tr>
					    <tr>
						  <td>Nº</td>
						  <td colspan="3"><input id="numero" type="text" autocomplete="off" class="cajaPequena" name="znumero" value="<?php echo mysqli_result($rs_query, 0, "numero");?>" maxlength="15"></td>
				      </tr>
						<tr>
							<td>Descripción </td>
							<td colspan="3"><input name="Adescripcion" type="text" class="cajaGrande" id="descripcion" size="45" value="<?php echo mysqli_result($rs_query, 0, "descripcion");?>" maxlength="45"></td>
					    </tr>
						<tr>
							<td>Alias </td>
							<td colspan="3"><input name="aalias" type="text" class="cajaGrande" id="alias" size="45" value="<?php echo mysqli_result($rs_query, 0, "alias");?>" maxlength="45"></td>
					    </tr>					</table></td><td>						
						<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td>Service</td>
							<td><select type=text size=1 name="aservice" id="service" class="comboMedio">
							<?php
							 $tipo = array("Sin&nbsp;definir", "Sin&nbsp;Servicio","Con&nbsp;Mantenimiento", "Mantenimiento&nbsp;y&nbsp;Respaldos");
							$xx=0;
							foreach($tipo as $key => $tpo) {
								if ($key==mysqli_result($rs_query, 0, "service")){
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
							<td valign="top">Detalles</td>
						    <td><textarea name="adetalles" cols="41" rows="4" id="detalles" class="areaTexto"><?php echo mysqli_result($rs_query, 0, "detalles");?></textarea></td>
					    </tr>
					</table>
				</td></tr></table>				
				
			  </div>
			  <p>
				<div>
						<button class="boletin" onClick="validar(formulario,true);" onMouseOver="style.cursor=cursor"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Guardar</button>
						<button class="boletin" onClick="cancelar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>

					<input id="accion" name="accion" value="modificar" type="hidden">
					<input id="id" name="id" value="" type="hidden">
					<input id="codequipo" name="codequipo" value="<?php echo $codequipo?>" type="hidden">
					<input id="codcliente" name="codcliente" value="<?php echo mysqli_result($rs_query, 0, "codcliente");?>" type="hidden">
			  </div>
			  </form>
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
