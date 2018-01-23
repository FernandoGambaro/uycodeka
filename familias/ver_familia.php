<?php include ("../conectar.php"); 

$codfamilia=$_GET["codfamilia"];

$query="SELECT * FROM familias WHERE codfamilia='$codfamilia'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

?>
<html>
	<head>
		<title>Principal</title>
	<link href="../css3/estilos.css" type="text/css" rel="stylesheet">
		<script src="../js3/jquery.min.js"></script>

		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="../js3/jquery.colorbox.js"></script>
		
		<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
		<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js3/message.js" type="text/javascript"></script>
		<script src="../js3/jquery.msgBox.js" type="text/javascript"></script>
		<link href="../js3/msgBoxLight.css" rel="stylesheet" type="text/css">	
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">
		
<script type="text/javascript">
$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

var headID = window.parent.document.getElementsByTagName("head")[0];         
var newScript = window.parent.document.createElement('script');
newScript.type = 'text/javascript';
newScript.src = 'js/jquery.colorbox.js';
headID.appendChild(newScript);
});

</script>			
		<script language="javascript">
		
		function cancelar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">VER FAMILIA </div>
				<div id="frmBusqueda">
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
						<button class="boletin" onClick="cancelar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
			  </div>

			 </div>
		  </div>
		</div>
		
	</body>
</html>
