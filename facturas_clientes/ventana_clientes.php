<html>
<head>
<title>Buscador de Clientes</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		
		<script src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="../js3/jquery.colorbox.js"></script>
	
<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
<script src="../js3/message.js" type="text/javascript"></script>
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
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}


function enviar() {
	document.getElementById("form1").submit();
	document.getElementById("tabla_resultado").style.display="";
}

function pon_prefijo(pref,nombre,nif) {
	parent.pon_prefijo(pref,nombre,nif);
	parent.$('idOfDomElement').colorbox.close();
}

</script>
</head>
<?php include ("../conectar.php"); ?>
<body onLoad="enviar();">

<form name="form1" id="form1" method="post" action="frame_clientes_ini.php" target="frame_resultado" onSubmit="buscar();">
<div align="center">
<div id="frmBusqueda">
 <div align="center">
	<table class="fuente8" align="center" width="95%" border="0">
     <tr>
		<td class="busqueda">Código:</td>
	    <td><input name="codcliente" type="text" id="codcliente" size="20" class="cajaMedia"></td></tr>
		<tr><td class="busqueda">Descripci&oacute;n:</td>
	    <td><input name="nombre" type="text" id="nombre" size="50" class="cajaGrande"></td>
		</tr>
		<tr>

		  <td colspan="4" align="center"><img id="botonBusqueda" src="../img/botonbuscar.jpg" width="69" height="22" border="1" onClick="enviar();" onMouseOver="style.cursor=cursor"></td>
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
<?php
((is_null($___mysqli_res = mysqli_close($descriptor))) ? false : $___mysqli_res);
?>