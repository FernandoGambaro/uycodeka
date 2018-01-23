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


date_default_timezone_set("America/Montevideo"); 
?>
<html>
<head>
<title>Buscador de Articulos</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<script>
var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}

function buscar() {
	if (document.getElementById("iniciopagina").value=="") {
		document.getElementById("iniciopagina").value=1;
	} else {
		document.getElementById("iniciopagina").value=document.getElementById("paginas").value;
	}
	document.getElementById("form1").submit();
	document.getElementById("tabla_resultado").style.display="";
}

function inicio() {

	var combo_familia=document.getElementById("cmbfamilia").value;
	if (combo_familia==0) {
		buscar();
	} else {
		document.getElementById("tabla_resultado").style.display="none";
	}
			
}

function paginar() {
	document.getElementById("iniciopagina").value=document.getElementById("paginas").value;
	document.getElementById("form1").submit();
}

function enviar() {
	document.getElementById("form1").submit();
}

</script>
<script language="javascript">
function pon_prefijo_a(codfamilia,referencia,codigobarras,nombre,precio,codarticulo,moneda,codservice,detalles,comision) {
	parent.pon_prefijo_Fb(codfamilia,referencia,codigobarras,nombre,precio,codarticulo,moneda,'',detalles,comision);
}

</script>

</head>

<body onLoad="buscar();">
<div style="width: 100%;  margin: 0 auto;">

<form name="form1" id="form1" method="post" action="frame_articulos.php" target="frame_resultado" onSubmit="buscar()">

<div id="frmBusqueda">
 <div align="center">

	<table class="fuente8" align="center" width="95%">
     <tr>
	    <td width="36%">Familia:</td>
	    <td width="64%">
		  <select id="cmbfamilia" name="cmbfamilia" class="comboGrande">
		  <?php
		  $anterior='';
		    $consultafamilia="select * from familias where borrado=0 order by nombre ASC";
			$queryfamilia=mysqli_query($GLOBALS["___mysqli_ston"], $consultafamilia);
			?><option value=0>Todos los articulos</option><?php
			while ($rowfamilia=mysqli_fetch_row($queryfamilia))
			  { 
			  	if ($anterior==$rowfamilia[0]) { ?>
					<option value="<?php echo $rowfamilia[0]?>" selected><?php echo utf8_encode($rowfamilia[1])?></option>
			<?php	} else { ?>
					<option value="<?php echo $rowfamilia[0]?>"><?php echo utf8_encode($rowfamilia[1])?></option>
			<?php	}   
		   	 };
		  ?>
	    </select>		</td></tr>
		<tr>
		<td width="36%" class="busqueda">Referencia:</td>
	    <td width="64%"><input name="referencia" type="text" id="referencia" size="20" class="cajaMedia"></td></tr>
		<tr><td width="36%" class="busqueda">Descripci&oacute;n:</td>
	    <td width="64%"><input name="descripcion" type="text" id="descripcion" size="50" class="cajaGrande"></td></tr>
		<tr>
		  <td class="busqueda">&nbsp;</td>
		  <td><img id="botonBusqueda" src="../img/botonbuscar.jpg" width="69" height="22" border="1" onClick="enviar();" onMouseOver="style.cursor=cursor"></td>
	  </tr>
</table>
</div></div>


			<iframe width="100%" height="300" id="frame_resultado" name="frame_resultado" frameborder="0" >
				<ilayer width="100%" height="300" id="frame_resultado" name="frame_resultado"></ilayer>
			</iframe>

<input type="hidden" id="iniciopagina" name="iniciopagina">
<div align="center">
      <img id="botonBusqueda" src="../img/botoncerrar.jpg" width="70" height="22" onClick="parent.$('idOfDomElement').colorbox.close();" border="0" onMouseOver="style.cursor=cursor">
    </div>
<br>&nbsp;
</form>
</body>
</html>