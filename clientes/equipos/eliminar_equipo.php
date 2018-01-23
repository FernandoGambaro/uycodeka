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

$codequipo=$_GET["codequipo"];
@$cadena_busqueda=$_GET["cadena_busqueda"];

$query="SELECT * FROM equipos WHERE codequipo='$codequipo'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../../estilos/estilos.css" type="text/css" rel="stylesheet">
		<!-- iconos para los botones -->       
<link rel="stylesheet" href="../../css3/css/font-awesome.min.css">		
		
		<script language="javascript">
		
		function aceptar(codequipo) {
			location.href="guardar_equipo.php?codequipo=" + codequipo + "&accion=baja" + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
		}
		
		function cancelar() {
			parent.$('idOfDomElement').colorbox.close();
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
				<div id="tituloForm" class="header">ELIMINAR equipo </div>
				<div id="frmBusqueda">
				<table class="fuente8"><tr><td valign="top">
					<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td>Fecha&nbsp;de&nbsp;alta</td>
							<td><input NAME="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" value="<?php echo mysqli_result($rs_query, 0, "fecha");?>" readonly> 
							<img src="../../img/calendario.png" name="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'">
        <script type="text/javascript">
					Calendar.setup(
					  {
					inputField : "fecha",
					ifFormat   : "%d/%m/%Y",
					button     : "Image1"
					  }
					);
		</script></td>
					    </tr>
					    <tr>
						  <td>Nº</td>
						  <td colspan="3"><input id="numero" type="text" autocomplete="off" class="cajaPequena" NAME="anumero" value="<?php echo mysqli_result($rs_query, 0, "numero");?>" maxlength="15"></td>
				      </tr>
						<tr>
							<td>Descripción </td>
							<td colspan="3"><input NAME="adescripcion" type="text" class="cajaGrande" id="descripcion" size="45" value="<?php echo mysqli_result($rs_query, 0, "descripcion");?>" maxlength="45"></td>
					    </tr>
					</table></td><td>						
						<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td>Service</td>
							<td><SELECT type=text size=1 name="aservice" id="service" class="comboMedio">
							<?php
								$tipo = array("Sin&nbsp;definir", "Sin Servicio","Con Mantenimiento");
							$xx=0;
							foreach($tipo as $tpo) {
								if ($xx=mysqli_result($rs_query, 0, "service")){
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
						    <td><textarea name="adetalles" cols="31" rows="4" id="detalles" class="areaTexto"><?php echo mysqli_result($rs_query, 0, "detalles");?></textarea></td>
					    </tr>
					</table>
				</td></tr></table>				

			  </div>
			  <p>
				<div>
						<button class="boletin" onClick="aceptar(<?php echo $codequipo?>);" onMouseOver="style.cursor=cursor"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Guardar</button>
						<button class="boletin" onClick="cancelar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
			  </div>
			  </div>
		  </div>
		</div>
	</body>
</html>
