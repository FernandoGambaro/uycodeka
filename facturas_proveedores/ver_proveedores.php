<html>
<head>
<title>Buscador de Proveedores</title>
		<link href="../css3/estilos.css" type="text/css" rel="stylesheet">
		
		<script src="../js3/calendario/jscal2.js"></script>
		<script src="../js3/calendario/lang/es.js"></script>
		<link rel="stylesheet" type="text/css" href="../js3/calendario/css/jscal2.css" />
		<link rel="stylesheet" type="text/css" href="../js3/calendario/css/border-radius.css" />
		<link rel="stylesheet" type="text/css" href="../js3/calendario/css/win2k/win2k.css" />		
		<script src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="../js3/jquery.colorbox.js"></script>

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">

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

</script>

<script language="javascript">
function pon_prefijo(pref,nombre,nif) {
parent.pon_prefijo(pref,nombre,nif);
parent.$('idOfDomElement').colorbox.close();
}
		function cancelar() {
			parent.$('idOfDomElement').colorbox.close();
		}

function enviar() {
	document.getElementById("form1").submit();
}

</script>
<script type="text/javascript">

$(document).keydown(function(e) {
    switch(e.keyCode) { 
        case 13:
			e.preventDefault();
        var $this = $(e.target);
        var index = parseFloat($this.attr('data-index'));
		     		$('[data-index="' + (index + 1).toString() + '"]').focus();

        break;
        case 112:
            showWarningToast('Ayuda aún no disponible...');
        break;
       
	 }
});

</script>
<script type="text/javascript">
$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

		$(".callbacks").colorbox({
			iframe:true, width:"98%", height:"98%",
			onCleanup:function(){ window.location.reload();	}
		});

});
</script>	

</head>
<?php include ("../conectar.php"); ?>
<body onLoad="buscar()">
<form name="form1" id="form1" method="post" action="frame_proveedores.php" target="frame_resultado" onSubmit="buscar()">

<div id="frmBusqueda2">
 <div align="center">
	<table class="fuente8" align="center">
     <tr>
		<td class="busqueda">Código:</td>
	    <td><input name="codproveedor" type="text" id="codproveedor" size="20" class="cajaMedia" onkeyup="document.getElementById('form1').submit();" data-index="1"></td></tr>
		<tr><td class="busqueda">Descripci&oacute;n:</td>
	    <td><input name="nombre" type="text" id="nombre" size="50" class="cajaGrande" onkeyup="document.getElementById('form1').submit();" data-index="2"></td>
		</tr>
		<tr>

		  <td colspan="4" align="center">
 		<button class="boletin" onClick="enviar();" onMouseOver="style.cursor=cursor"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar</button>		  
	  </tr>
</table>
</div>
</div>

			<iframe width="100%" height="300" id="frame_resultado" name="frame_resultado" frameborder="2" >
				<ilayer width="100%" height="300" id="frame_resultado" name="frame_resultado"></ilayer>
			</iframe>

<input type="hidden" id="iniciopagina" name="iniciopagina">
<div align="center">
<button class="boletin" onClick="parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Cancelar</button>

    </div>
<br>&nbsp;
</form>
</body>
</html>
