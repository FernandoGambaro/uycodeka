<html>
<head>
<title>Buscador de Clientes</title>

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

/*	if (document.getElementById("iniciopagina").value=="") {
		document.getElementById("iniciopagina").value=1;
	} else {
		document.getElementById("iniciopagina").value=document.getElementById("paginas").value;
	}
	*/
	document.getElementById("form1").submit();
	document.getElementById("tabla_resultado").style.display="";
}

</script>

<script language="javascript">

function pon_prefijo(pref,nombre,nif) {
	parent.pon_prefijo(pref,nombre,nif);
	parent.$('idOfDomElement').colorbox.close();
}

		function cancelar() {
			parent.$('idOfDomElement').colorbox.close();
		}
</script>

<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

</head>
<?php include ("../conectar.php"); ?>
<body onLoad="buscar();">
<form name="form1" id="form1" method="post" action="frame_clientes.php" target="frame_resultado" onSubmit="buscar();">
<div align="center">
<div id="frmBusqueda">
	<table class="fuente8" align="center">
     <tr>
	    <td class="busqueda">Nombre:</td>
	    <td><input name="nombre" type="text" id="nombre" size="20" class="cajaMedia"></td></tr>
		<tr>
		  <td colspan="2" align="center">
		  <img id="botonBusqueda" src="../img/botonbuscar.jpg" width="69" height="22" border="0" onClick="buscar();" onMouseOver="style.cursor=cursor"></td>
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
