<html>
<head>
<title>Buscador de Clientes</title>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script src="js/jquery.min.js"></script>

<script>
var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
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

function enviar() {
	document.getElementById("form1").submit();
}

</script>
</head>
<?php include ("../conectar.php"); ?>
<body onLoad="buscar();">
<form name="form1" id="form1" method="post" action="frame_clientes.php" target="frame_resultado" onSubmit="buscar();">
<div align="center">
<div id="frmBusqueda">
 <div align="center">
<table class="fuente8" align="center">
     <tr>
		<td class="busqueda">Código:</td>
	    <td><input name="codcliente" type="text" id="codcliente" size="20" class="cajaMedia"></td></tr>
		<tr><td class="busqueda">Nombre/Empresa</td>
	    <td><input name="nombre" type="text" id="nombre" size="50" class="cajaGrande"></td>
		  <td align="center"><img id="botonBusqueda" src="../img/botonbuscar.jpg" width="69" height="22" border="0" onClick="enviar();" onMouseOver="style.cursor=cursor"></td>
	  </tr>
</table>
</div>
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
