<?php
include ("../conectar.php"); 

$codfamilia=$_GET["codfamilia"];
$cadena_busqueda=$_GET["cadena_busqueda"];

$query="SELECT * FROM familias WHERE codfamilia='$codfamilia'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		function aceptar(codfamilia) {
			var url="guardar_familia.php?codfamilia=" + codfamilia + "&accion=baja" + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			window.parent.OpenNote(url);
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
				<div id="tituloForm" class="header">MODIFICAR FAMILIA </div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_familia.php" enctype="multipart/form-data">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td>C&oacute;digo</td>
							<td><?php echo $codfamilia?></td>
						    <td width="42%" rowspan="2" align="left" valign="top"><ul id="lista-errores"></ul></td>
						</tr>
						<tr>
							<td width="15%">Nombre</td>
						    <td width="43%"><input NAME="Anombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" value="<?php echo mysqli_result($rs_query, 0, "nombre")?>"></td>
				        </tr>
							<tr>
						  <td valign="top" colspan="2">
						  <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
						  <div class="file-upload btn btn-primary"><span>Imagen&nbsp;[Formato&nbsp;jpg]&nbsp;[200x200]</span> 
								<input class="upload" type="file" name="foto" id="foto" accept="image/jpg" onchange="PreviewImage();">
								</div>	
								
						  </td></tr><tr>
						  <td valign="top" colspan="2" align="center">
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
					</table>
			  </div>
			   <br style="line-height:5px">			  
				<div>
						<button class="boletin" onClick="aceptar(<?php echo $codfamilia?>);" onMouseOver="style.cursor=cursor"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Eliminar</button>
						<button class="boletin" onClick="cancelar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
					<input id="accion" name="accion" value="baja" type="hidden">
					<input id="id" name="Zid" value="<?php echo $codfamilia?>" type="hidden">
			  </div>
			  </form>
			 </div>
		  </div>
		</div>
		
	</body>	
</html>
