<html>
<head>
<title>Buscador de Proveedores</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		
		<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="js/colorbox.css" />
		<script src="js/jquery.colorbox.js"></script>
	
<link rel="stylesheet" href="js/jquery.toastmessage.css" type="text/css">
<script src="js/jquery.toastmessage.js" type="text/javascript"></script>
<script src="js/message.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).keydown(function(e) {
    switch(e.keyCode) { 
        case 13:
			parent.$('idOfDomElement').colorbox.close();

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

		function cancelar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
function pon_prefijo(pref,nombre) {
parent.pon_prefijo(pref,nombre);
parent.$('idOfDomElement').colorbox.close();
}		

function enviar() {
	document.getElementById("form1").submit();
}


</script>
</head>
<?php include ("../conectar.php"); ?>
<body onLoad="buscar()">

<form name="form1" id="form1" method="post" action="frame_proveedores_ini.php" target="frame_resultado" onSubmit="buscar();">

<div id="frmBusqueda2">
 <div align="center">
	<table class="fuente8" align="center">
     <tr>
		<td class="busqueda">Código:</td>
	    <td><input name="codproveedor" type="text" id="codproveedor" size="20" class="cajaMedia"></td></tr>
		<tr><td class="busqueda">Descripci&oacute;n:</td>
	    <td><input name="nombre" type="text" id="nombre" size="50" class="cajaGrande"></td>
		</tr>
		<tr>

		  <td colspan="4" align="center"><img id="botonBusqueda" src="../img/botonbuscar.jpg" width="69" height="22" border="1" onClick="enviar();" onMouseOver="style.cursor=cursor"></td>
	  </tr>
</table>
</div>
</div>

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


