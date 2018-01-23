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

//ini_set('display_errors', 'On');
//error_reporting(E_ALL);

////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 
session_start();
require_once('../class/class_session.php');
/*
Instantiate a new session object. If session exists, it will be restored,
otherwise, a new session will be created--placing a sid cookie on the user's
computer.
*/
if (!$s = new session()) {
  /*
  There is a problem with the session! The class has a 'log' property that
  contains a log of events. This log is useful for testing and debugging.
  */
  echo "<h2>Ocurrió un error al iniciar session!</h2>";
  echo $s->log;
  exit();
}
if((!$s->data['isLoggedIn']) || !($s->data['isLoggedIn']))
{
/*/user is not logged in*/
		echo "<script>window.top.location.href='../index.php'; </script>";
	   exit;
} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
  		//header("Location:../index.php");	
		echo "<script>window.top.location.href='../index.php'; </script>";
	   exit;
   }
   $s->data['loggedAt']= time();
   $s->save();
}

include ("../conectar.php"); 
include ("../funciones/fechas.php");
include("encrypt_decrypt.php");

$mensaje='';
$accion='';
$query_update='';
$accion=@$_POST["accion"];

//if (!isset($accion)) { $accion=$_GET["accion"]; }

if ($accion=="modificar") {

$coddatos=0;
//$_POST["coddatos"];
$emailpass='';
$efemailpass='';
$email='';

$nombre=$_POST["Anombre"];
$razonsocial=$_POST["Arazonsocial"];
$nif=$_POST["Anif"];
$direccion=$_POST["direccion"];
$provincia=$_POST["cboProvincias"];
$pais=$_POST["codpais"];
$telefono1=$_POST["telefono1"];
$telefono2=$_POST["telefono2"];
$fax=$_POST["fax"];
$web=$_POST["web"];
$mailv=$_POST["mailv"];
$maili=$_POST["maili"];
$descripcion=$_POST["descripcion"];
$fecha=$_POST["fecha"];
if ($fecha<>"") { $fecha=explota($fecha); } else { $fecha="0000-00-00"; }
$login=@$_POST["login"];
$cabezalmail=@$_POST["cabezalmail"];
$piemail=@$_POST["piemail"];
$emailname=@$_POST['emailname'];
$emailsend=$_POST['emailsend'];
$emailreply=$_POST['emailreply'];
$emailhost=$_POST['emailhost'];
$emailssl=$_POST['emailssl'];
$emailpuerto=$_POST['emailpuerto'];

$emailbody=$_POST['message'];
$banco=$_POST['Vbancos'];
$bancos='';
$x=0;
if (isset($_POST['Vbancos']) && is_array($_POST['Vbancos'])){
		foreach($banco as $value) {
		  if($x>0) {
		  	$bancos.="#";
		  }
		$x++;
		  $bancos.=$value;
		}
} else {
	$bancos.=$banco;
}
if($emailpass!='') {
	$emailpass=encrypt_decrypt('encrypt', $_POST['emailpass']);
	$email="`emailpass` = '$emailpass',";
}

$efemailname=$_POST['efemailname'];
$efemailsend=$_POST['efemailsend'];
$efemailhost=$_POST['efemailhost'];
$efemailssl=@$_POST['efemailssl'];
$efemailpuerto=$_POST['efemailpuerto'];
if($efemailpass!='') {
	$efemailpass=encrypt_decrypt('encrypt', $_POST['efemailpass']);
	$efemailpass="`efemailpass` = '$efemailpass',";
}
$papel=$_POST['papel'];
$impresorareporte=$_POST['impresorareporte'];
$servidorreporte=$_POST['servidorreporte'];

$impresorafactura=$_POST['impresorafactura'];
$servidorfactura=$_POST['servidorfactura'];
$lugarmon=$_POST["lugarmon"];
$logofactura=$_POST["logofactura"];
$modelo=$_POST["modelo"];
$reporte=$_POST['reporte'];

$facebook=$_POST['facebook'];
$twitter=$_POST['twitter'];
$serveraux=$_POST['serveraux'];

$fileerror="";
global $mensaje;
$foto_name="";

function savefile($name,$type,$tmp_name,$size,$valor) {
	   /*/ Extrae los contenidos de las fotos
	   # contenido de la foto original*/
	   $fp = fopen($tmp_name, "rb");
	   $tfoto = fread($fp, filesize($tmp_name));
	   $tfoto = addslashes($tfoto);
	   fclose($fp);
	   /*/ Borra archivos temporales si es que existen*/
	   @unlink($tmp_name);
	   /*/ Guardamos todo en la base de datos
	   #nombre de la foto*/
	   
	   if ($valor!=0) {
		   $sqlveri="SELECT * FROM `foto` where `oid`='$valor'";
		   $resver=mysqli_query($GLOBALS["___mysqli_ston"], $sqlveri);
	   }
	   if (mysqli_num_rows($resver)>0)
	   {
		   $query = "UPDATE `foto` SET `fotoname`='$name', `fotosize`='$size', `fototype`='$type', `fotocontent`='$tfoto' WHERE `oid` = ' $valor' ";
	   } else {
	      $query = "INSERT INTO foto (oid, fotoname, fotosize, fototype, fotocontent ) VALUES ('$valor', '$name', '$size', '$type', '$tfoto')";
      } 

		$res=mysqli_query($GLOBALS["___mysqli_ston"], $query);
		if($res!==false) {
			$mensaje.="El archivo ".$name." ha sido guardado con exito";
		} else {
			$mensaje.="Error el archivo ".$name." no ha sido guardado";
		}
}

	if ($_FILES["login"]["name"]!='') {
	
	 $mimetypes = array("image/jpg", "image/jpeg", "image/pjpeg", "image/gif", "image/png");
	  /*/ Variables de la foto*/
	  $name = $_FILES["login"]["name"];
	  $type = $_FILES["login"]["type"];
	  $tmp_name = $_FILES["login"]["tmp_name"];
	  $size = $_FILES["login"]["size"];
	  /*/ Verificamos si el archivo es una imagen válida*/
	/*echo $type."<br>";*/
	  if(!in_array($type, $mimetypes)) {
	    $mensaje.="El archivo Logo de login que subiste no es una imagen válida";
	  } else {
		savefile($name,$type,$tmp_name,$size,11);
	  }
	}

	if (@$_FILES["cabezalmail"]["name"]!='') {
	
	 $mimetypes = array("image/jpg", "image/jpeg", "image/pjpeg", "image/gif", "image/png");
	  /*/ Variables de la foto*/
	  $name = $_FILES["cabezalmail"]["name"];
	  $type = $_FILES["cabezalmail"]["type"];
	  $tmp_name = $_FILES["cabezalmail"]["tmp_name"];
	  $size = $_FILES["cabezalmail"]["size"];
	  /*/ Verificamos si el archivo es una imagen válida*/
	/*echo $type."<br>";*/
	  if(!in_array($type, $mimetypes)) {
	    $mensaje.="El archivo para el Cabezal que subiste no es una imagen válida";
	  } else {
		savefile($name,$type,$tmp_name,$size,12);
	  }
	}
	
	if (@$_FILES["piemail"]["name"]!='') {
	
	 $mimetypes = array("image/jpg", "image/jpeg", "image/pjpeg", "image/gif", "image/png");
	  /*/ Variables de la foto*/
	  $name = $_FILES["piemail"]["name"];
	  $type = $_FILES["piemail"]["type"];
	  $tmp_name = $_FILES["piemail"]["tmp_name"];
	  $size = $_FILES["piemail"]["size"];
	  /*/ Verificamos si el archivo es una imagen válida*/
	/*echo $type."<br>";*/
	  if(!in_array($type, $mimetypes)) {
	    $mensaje.="El archivo que subiste no es una imagen válida";
	  } else {
		savefile($name,$type,$tmp_name,$size,13);
	  }
	}	
	
	$query_update="UPDATE `datos` SET `nombre` = '$nombre', `razonsocial` = '$razonsocial', `nif` = '$nif', `direccion` = '$direccion', `provincia` = '$provincia',
	`pais` = '$pais', `telefono1` = '$telefono1', `telefono2` = '$telefono2', `fax` = '$fax', `web` = '$web', `mailv` = '$mailv',
	  `maili` = '$maili', `facebook`='$facebook', `twitter`='$twitter', `descripcion` = '$descripcion', `fecha` = '$fecha',
	   `login` = '$login', `cabezalmail` = '$cabezalmail', `piemail` = '$piemail', `emailname` = '$emailname', `emailsend` = '$emailsend', `emailreply` = '$emailreply', $email `emailhost` = '$emailhost', 
	   `emailssl` = '$emailssl', `emailpuerto` = '$emailpuerto', `emailbody`='$emailbody', `efemailname` = '$efemailname', `efemailsend` = '$efemailsend', $efemailpass `efemailhost` = '$efemailhost', 
	   `efemailssl` = '$efemailssl', `efemailpuerto` = '$efemailpuerto', `papel`='$papel', `impresorareporte`='$impresorareporte', `servidorreporte`='$servidorreporte',
	    `impresorafactura`='$impresorafactura', `servidorfactura`='$servidorfactura', `lugarmon` = '$lugarmon', `logofactura` = '$logofactura', `modelo` = '$modelo',
	    `reporte`='$reporte', `serveraux` = '$serveraux', `bancos`='$bancos' WHERE `datos`.`coddatos` = 0";
	   
	
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query_update);
	if ($rs_query) { $mensaje.="Los datos de la empresa han sido modificados correctamente"; } else { $mensaje.="Error los datos de la empresa no han sido modificados";}
	$cabecera1="Inicio >> Datos &gt;&gt; Modificar Datos ";
	$cabecera2="MODIFICAR DATOS ".$fileerror;

}

$query="SELECT * FROM datos WHERE coddatos='0'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);


				  $modeloselected=mysqli_result($rs_query, 0, "modelo");
				  if($modeloselected!='') {
				  	$img_modelo="../img/".$modeloselected.".png";
				  	} else {
				  	$img_modelo="../img/blank.png";
				  	}
?>
<html>
	<head>
	<title>Principal</title>
		<link href="../css3/estilos.css" type="text/css" rel="stylesheet">
		<script src="../js3/jquery.min.js"></script>

	
		<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
		<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js3/message.js" type="text/javascript"></script>
		<script src="../js3/jquery.msgBox.js" type="text/javascript"></script>
		<link href="../js3/msgBoxLight.css" rel="stylesheet" type="text/css">	

		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="../js3/jquery.colorbox.js"></script>

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">
		
<style type="text/css">
#cboxSocials {
	position: relative;
 width:79px;
   margin-top:1px;
   height: 22px;
 float:right;
 margin-right: 50px;
}

.cb_social_elem {
	position: relative;
 float:right;
 margin-right: 10px;
 width:79px;
 height: 22px;
}
</style>		
		
<script type="text/javascript">
jQuery(document).ready(function() {
	var printto = '<div class="cb_social_elem"><button class="boletin" onClick="PrintMe(\'cboxLoadedContent\');" onMouseOver="style.cursor=cursor;" style=" background-color:yellow; width:100px;"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir</button></div>';
	jQuery("#cboxContent").append('<div id="cboxSocials">'+printto+'</div>');
});

</script>

<script language="javascript">
function PrintMe(DivID) {
var disp_setting="toolbar=yes,location=no,";
disp_setting+="directories=yes,menubar=yes,";
disp_setting+="scrollbars=yes,width=650, height=600, left=100, top=25";
   var content_vlue = document.getElementById(DivID).innerHTML;
   var docprint=window.open("","",disp_setting);
   docprint.document.open();
   docprint.document.write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"');
   docprint.document.write('"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">');
   docprint.document.write('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">');
   docprint.document.write('<head><title>Imprimir modelo seleccionado</title>');
   docprint.document.write('<style type="text/css">body{ margin:0px;');
   docprint.document.write('font-family:verdana,Arial;color:#000;');
   docprint.document.write('font-family:Verdana, Geneva, sans-serif; font-size:12px;}');
   docprint.document.write('a{color:#000;text-decoration:none;} </style>');
   docprint.document.write('</head><body onLoad="self.print()"><center>');
   docprint.document.write(content_vlue);
   docprint.document.write('</center></body></html>');
   docprint.document.close();
   docprint.focus();
}
</script>

<script type="text/javascript">
function ver(){
event.preventDefault();
	var archivo = $("#container img").attr('src');
	if (archivo !='../img/blank.png') {
		
	$.colorbox({
	   	href: archivo, open:true,
			scalePhotos: false,
	     maxWidth: '95%',
	     		onComplete: function () {
				var alto=$(".cboxPhoto").height();
				$("#cboxSocials").css("marginTop", "-"+alto+"px");
			}			
	});
	}

}
</script>

<script type="text/javascript">
parent.document.getElementById("msganio").innerHTML="<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp;  Datos del sistema";
var id='';
var oldid='';

	$(document).ready(function(){

		  	$('#<?php echo $modeloselected;?>s').show();
		  	$('#<?php echo $modeloselected;?>').hide();
oldid='<?php echo $modeloselected;?>';
id='<?php echo $modeloselected;?>';
		
		$(".tabContents").hide(); // Hide all tab content divs by default
		$(".tabContents:first").show(); // Show the first div of tab content by default
		
		$(".tabContaier ul li a").click(function(){ //Fire the click event
			
			var activeTab = $(this).attr("href"); // Catch the click link
			$(".tabContaier ul li a").removeClass("active"); // Remove pre-highlighted link
			$(this).addClass("active"); // set clicked link to highlight state
			$(".tabContents").hide(); // hide currently visible tab content div
			$(activeTab).show(); // show the target tab content div by matching clicked link.
			
			return false; //prevent page scrolling on tab click
		});
                     
                    $('#id_radio1').click(function () {
                    	  $('#id_radio2').show();
                       $('#div2').hide();
                       $('#id_radio1').hide();
                       $('#div1').show();
                });
                $('#id_radio2').click(function () {
                	    $('#id_radio1').show();
                	    $('#id_radio2').hide();
                      $('#div1').hide();
                      $('#div2').show();
                 });

$(".basico").click(function(e){
e.preventDefault();
id = $(this).attr("id");
$('#modelo').val(id);
$('#'+id).hide();
$("#"+id).attr('checked', true); 

  if (oldid!='') {
	$("#"+oldid).attr('checked', false); 
	$('#'+oldid).show();
   $('#'+oldid+'s').hide();
  }
  $('#'+id+'s').show();
oldid=id;  

//$('input[type="radio"][id='+id+']').prop('checked', true)
  
if($('#logofactura').is(":checked")){
$.post("modelofactura.php", {"id":id }, function(response){ 
if (response==1) {
	d = new Date();
		$("#containerimg").attr("src", "../tmp/modelofactura.png?"+d.getTime());
		$("#containerimg").show();
} else {
	   $("#container img").attr('src', "../img/"+ id +".png");
}
});
} else {
	$("#container img").attr('src', "../img/"+ id +".png");
}	
fadeIn($("#container img"));
});
	

});
</script>
<script type="text/javascript" >
function prever(){
if (id!='') {
	if($('#logofactura').is(":checked")){
	$.post("modelofactura.php", {"id":id }, function(response){ 
	if (response==1) {
		d = new Date();
			$("#containerimg").attr("src", "../tmp/modelofactura.png?"+d.getTime());
			$("#containerimg").show();
	} else {
		   $("#container img").attr('src', "../img/"+ id +".png");
	}
	});
} else {
	$("#container img").attr('src', "../img/"+ id +".png");
}	
fadeIn($("#container img"));
}
}
function fadeIn(obj) {
    $(obj).fadeIn(1000);
}

</script>

<!-- Tabs -->
<style type="text/css">
a{outline:none;}

.tabContaier{
	background: url("../img/headerTile.png") repeat-x scroll left top transparent;
	margin: 2px 0 0 0;
	padding:0;
	height:20px;	
	position: relative;
	width: 98%;
	top: 10px;
	border-bottom: 1px solid #666;
	
}
	.tabContaier ul{
		overflow:hidden;
		height:20px;
		position:absolute;
		z-index:100;
		margin:0;
		padding:0;
	}
	.tabContaier li{
		float:left;
		list-style:none;
		border-right:1px solid #fff;
		text-decoration:none;
		text-transform:uppercase;
		z-index: 999;		
	}
	.tabContaier li a{
		background: url("../img/headerTile.png") no-repeat-x left top transparent;
		border-right:0;
		color:#fff;
		cursor:pointer;
		display:block;
		height:20px;
		line-height:20px;
		padding:0 20px;
		text-decoration:none;
		text-transform:uppercase;
		z-index: 999;
		border-top: 1px solid #666;
		border-left: 1px solid #666;;
		border-right: 1px solid #666;		
	}
	
	
	.tabContaier li a:hover{
		background:#fff;
		color:#000;
		border-top: 1px solid #666;
		border-left: 1px solid #666;;
		border-right: 1px solid #666;		
		text-decoration:none;
		text-transform:uppercase;
		z-index: 999;
	}
	.tabContaier li a.active{
		background:#fbfbfb;
		border:1px solid #fff;
		color:#333;
		border-top: 1px solid #666;
		border-left: 1px solid #666;;
		border-right: 1px solid #666;		
		text-decoration:none;
		text-transform:uppercase;
		z-index: 999;
	}
	.tabDetails{
		background:#ddd;
		border:1px solid #fff;
	}
	.tabContents{
		margin: 0;
		padding:0;		
		background:#fff;
		border-bottom: 1px solid #666;
		border-left: 1px solid #666;;
		border-right: 1px solid #666;		
		height: 500px;
		position: relative;
		width: 98%;
		top: 9px;
		left: 1px;
	}

#inner {
	position: relative;
	top: 20px;
	display: table;
	margin: 0 auto;
}

textarea#message {
	width: 300px;
	height: 520px;
	border: 3px solid #cccccc;
	font-family: Tahoma, sans-serif;
	/*background-image: url(bg.gif);*/
	background-position: bottom right;
	background-repeat: no-repeat;
}

</style>


<script type="text/javascript">
var pass='';

window.onload = function () {

    document.getElementById('emailpass').onfocus = function () {
        if (this.defaultValue == this.value) {
            this.type = 'password';
            this.value = '';
        }
    }
}

</script>		
<script language="javascript">
		
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}

		function imprimir() {
			window.open("../fpdf/ejemplo.php");
		}		

		</script>
		
<script src="../js3/tinymce/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea#message' ,
		 language: 'es',
		 height : 350,
		 max_height: 500,
		 menubar: 'file edit insert view format table tools',
		 menubar: false,
		 resize: false,
		 statusbar: false,
		theme: 'modern',
		  plugins: [
    "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
    "table contextmenu directionality emoticons template textcolor paste fullpage textcolor colorpicker textpattern"
  ],

	toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
 	toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
 	toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",

 	menubar: false,
 	toolbar_items_size: 'small',

 	style_formats: [{
    title: 'Bold text',
    inline: 'b'
 	}, {
    title: 'Red text',
    inline: 'span',
    styles: {
      color: '#ff0000'
    }
 	}, {
    title: 'Red header',
    block: 'h1',
    styles: {
      color: '#ff0000'
    }
 	}, {
    title: 'Example 1',
    inline: 'span',
    classes: 'example1'
 	}, {
    title: 'Example 2',
    inline: 'span',
    classes: 'example2'
 	}, {
    title: 'Table styles'
 	}, {
    title: 'Table row 1',
    selector: 'tr',
    classes: 'tablerow1'
  	}],
});

</script>
		 	
<script type="text/javascript">

var newItemHide='';
var newItemShow='';
var newItemHideN='';
var newItemShowN='';
var newItemInput='';
var newItemInputId='';
var newItemInputValue='';
var newItemCombo='';
var newItemComboN='';
var trigerclass='';
var item0=0;
var initials ={};

$(document).ready( function()
{
  $('.trigger').click( function(e){
      if (newItemShowN!='') {
      $(newItemShowN).hide();
      $(newItemHideN).show();
      }
		trigerclass='';
      newItemHide=this.id+'Hide';
      newItemShow=this.id+'Show';
      newItemHideN='#'+this.id+'Hide';
      newItemShowN='#'+this.id+'Show';
      newItemInput=this.id+'Input';
      newItemInputId='#'+this.id+'Input';
      newItemInputValue=this.id+'Value';
      newItemCombo=this.id+'Combo';
      newItemComboN='#'+this.id+'Combo';

      p = $(this);
      var offset = p.offset();
      var x = offset.left - this.offsetLeft - 70 ;
      var y = offset.top - this.offsetTop + 10;
      document.getElementById(newItemShow).style.left = x ;
      document.getElementById(newItemShow).style.top = y;
      $(newItemShowN).show(); $(newItemHideN).hide();
      e.preventDefault();
   } );//Finaliza trigger

  $('.triggerClose').click( function(e){

      if (newItemShowN!='') {
      $(newItemShowN).hide();
      $(newItemHideN).show();
      }
      trigerclass='';
      e.preventDefault();
   } );/*/Finaliza triggerClose*/
   
   	$('.triggerinput').click( function(e) {
   		trigerclass=1;
   		/*/alert(trigerclass);*/
   	});


  $('.ComboBox').click(function(e){
/*//alert(this.id);*/
      newItemDel='#Vbancos';
      $(newItemShowN).hide(); $(newItemHideN).show();
      e.preventDefault();
   });

	$('#formulario').submit(function() {
		$('*').find('option').each(function() {
			$(this).attr('selected', 'selected');
		});
	});

$('.simpleinput').click( function(e){
	$(newItemShowN).hide(); $(newItemHideN).show();
});

/*/veo cual es el campo en el que se esta escribiendo;*/
$('input').focus(function(e){
   var selected = document.activeElement;

   if (selected.id && selected.id!='basicoA4' && selected.id!='basicoA5' ) {
      var offset = $(this).offset();
      var x = offset.left - this.offsetLeft ;
      var y = offset.top - this.offsetTop + 15;
      document.getElementById("suggestions").style.left = x + 10;
      document.getElementById("suggestions").style.top = y + 21;
      e.preventDefault();
   }
newItemInput='#'+selected.id;
});
/*
    if (!document.activeElement) {
        this.each(function() {
            var $this = $(this).data('hasFocus', false);
            $this.focus(function(event) {
                $this.data('hasFocus', true);
            });
            $this.blur(function(event) {
                $this.data('hasFocus', false);
            });
        });
    }
*/
	$('input').blur(function(){
	  if($(this).attr('value')==''){
	    $(this).attr('value', initials[this.id]);
	  }
	});

});
function agregarbanco() {
		var numcuenta=$("#numcuenta").val();
		var Amoneda=$("#Amoneda option:selected").text();
		var cboBanco=$("#cboBanco option:selected").text();
		var cboBancoVal=$("#cboBanco").val();
		if($('#incluir').is(":checked")){
		var incluir="Si";
		}else {
		var incluir="No";	
		}
		
		
		if (numcuenta!='' && Amoneda!='' && eval(cboBancoVal)>0) {
		  $(newItemShowN).hide(); $(newItemHideN).show();
   	   var item1=cboBanco+"-"+Amoneda+"-Nº cuenta:"+numcuenta+"-"+incluir;
               $("#Vbancos").append($('<option></option>').attr('value', item1).text(item1));
               $("#numcuenta").val('');
               $("#Amoneda").val('');
               $("#cboBanco").val('');
               $("#incluir").val("");
      } else {
 			alert('Ingrese datos');
 			return false;
		}       	
}
</script>		 	
<script type="text/javascript" src="../js3/jquery.keyz.js"></script>

<script type="text/javascript">

$(document).unbind('keypress');
$(document).keydown(function(e) {
/*//alert(e.keyCode);*/
    switch(e.keyCode) { 
        case 46:
         if(newItemDel!='') {
         $(newItemDel).find('option:selected').remove();
         newItemDel="";
         }
        break;
        case 112:
            alert('Ayuda aún no disponible...');
        break;
        case 13:

        break;
        
    }
});

</script>
<script LANGUAGE="JavaScript">
function selectAll(selectBox,selectAll) { 
    /*/ have we been passed an ID */
    if (typeof selectBox == "string") { 
        selectBox = document.getElementById(selectBox);
    } 
    /*/ is the select box a multiple select box? */
    if (selectBox.type == "select-multiple") { 
        for (var i = 0; i < selectBox.options.length; i++) { 
             selectBox.options[i].selected = selectAll; 
        } 
    }
}

</script>		 		
	</head>
	<body>

		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<form id="formulario" name="formulario" method="post" action="index.php" enctype="multipart/form-data">
				<input id="accion" name="accion" value="modificar" type="hidden">
<?php echo htmlspecialchars(@$_POST['emailpass']);?>
<div class="tabContaier">
	<ul>
    	<li><a class="active" href="#tab1">Datos Generales</a></li>
    	<li><a href="#tab2">Reportes</a></li>
    	<li><a href="#tab3">Envios mail</a></li>
    	<li><a href="#tab4">Facturación</a></li>
    	<li><a href="#tab5">eFactura</a></li>
    	<li><a href="#tab6">Misceláneo</a></li>
    </ul><!-- //Tab buttons -->
</div>
     	<div id="tab1" class="tabContents">
        	<div id="inner"><?php if ($mensaje!=""){ ?>
        	<div id="tituloForm" class="header"><?php echo $mensaje;?></div><?php } ?>
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0><td valign="top">
    				<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
						<tr>
						<td>Nombre</td>
						<?php $nombre=mysqli_result($rs_query, 0, "nombre");?>
					      <td colspan="2"><input name="Anombre" id="nombre" value="<?php echo mysqli_result($rs_query, 0, "nombre")?>" maxlength="50" class="cajaGrande" type="text" onblur="actualizo(name1);"></td>
				          <td width="4%" rowspan="15" align="left" valign="top"><ul id="lista-errores"></ul></td>
						</tr>
						<tr>
						<td>Razón&nbsp;Social</td>
						<?php $razonsocial=mysqli_result($rs_query, 0, "razonsocial");?>
					      <td colspan="2"><input name="Arazonsocial" id="razonsocial" value="<?php echo mysqli_result($rs_query, 0, "razonsocial")?>" maxlength="50" class="cajaGrande" type="text"></td>
						</tr>
						<tr>
						<td>RUT</td>
						<?php $nif=mysqli_result($rs_query, 0, "nif");?>
					      <td colspan="2"><input name="Anif" id="nif" value="<?php echo mysqli_result($rs_query, 0, "nif")?>" maxlength="20" class="cajaGrande" type="text"></td>
						</tr>
						<tr>
						<td>Direccion</td>
						<?php $direccion=mysqli_result($rs_query, 0, "direccion");?>
					      <td colspan="2"><input name="direccion" id="direccion" value="<?php echo mysqli_result($rs_query, 0, "direccion")?>" maxlength="50" class="cajaGrande" type="text"></td>
						</tr>
						<tr>
						<td>Teléfono&nbsp;principal</td>
						<?php $telefono1=mysqli_result($rs_query, 0, "telefono1");?>
					      <td colspan="2"><input name="telefono1" id="telefono1" value="<?php echo mysqli_result($rs_query, 0, "telefono1")?>" maxlength="50" class="cajaGrande" type="text"></td>
						</tr>
						<tr>
						<td>Teléfono&nbsp;secundario</td>
						<?php $telefono2=mysqli_result($rs_query, 0, "telefono2");?>
					      <td colspan="2"><input name="telefono2" id="telefono2" value="<?php echo mysqli_result($rs_query, 0, "telefono2")?>" maxlength="50" class="cajaGrande" type="text"></td>
						</tr>
						<tr>
						<td>Fax</td>
						<?php $fax=mysqli_result($rs_query, 0, "fax");?>
					      <td colspan="2"><input name="fax" id="fax" value="<?php echo mysqli_result($rs_query, 0, "fax")?>" maxlength="50" class="cajaGrande" type="text"></td>
						</tr>
						<tr>
						<td>web</td>
						<?php $web=mysqli_result($rs_query, 0, "web");?>
					      <td colspan="2"><input name="web" id="web" value="<?php echo mysqli_result($rs_query, 0, "web")?>" maxlength="50" class="cajaGrande" type="text"></td>
						</tr>
						<tr>
						<td>Mail&nbsp;ventas</td>
						<?php $mailv=mysqli_result($rs_query, 0, "mailv");?>
					      <td colspan="2"><input name="mailv" id="mailv" value="<?php echo mysqli_result($rs_query, 0, "mailv")?>" maxlength="50" class="cajaGrande" type="text"></td>
						</tr>
						<tr>
						<td>Mail&nbsp;info</td>
						<?php $maili=mysqli_result($rs_query, 0, "maili");?>
					      <td colspan="2"><input name="maili" id="maili" value="<?php echo mysqli_result($rs_query, 0, "maili")?>" maxlength="50" class="cajaGrande" type="text"></td>
						</tr>

						<tr>
							<td width="11%">Descripci&oacute;n</td>
						    <td colspan="2"><textarea name="descripcion" cols="41" rows="2" id="descripcion" class="areaTexto"><?php echo mysqli_result($rs_query, 0, "descripcion")?></textarea></td>
				        </tr>
						<tr>
							<td>Fecha&nbsp;de&nbsp;alta</td>
							<td colspan="2"><input NAME="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" readonly value="<?php echo implota(mysqli_result($rs_query, 0, "fecha"))?>">
							<img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'">
							<script type="text/javascript">
							   Calendar.setup({
							     inputField : "fecha",
							     trigger    : "Image1",
							     onSelect   : function() { this.hide() },
							     showTime   : 12,
							     dateFormat : "%d/%m/%Y"
							   });
							</script>	</td>
					    </tr>

				      </table>
				      </td><td valign="top">
						<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
						<tr>
						<td>Facebook</td>
						<td><input name="facebook" id="facebook" value="<?php echo mysqli_result($rs_query, 0, "facebook")?>" maxlength="50" class="cajaGrande" type="text"></td></tr>
						<tr><td>twitter</td>
						<td><input name="twitter" id="twitter" value="<?php echo mysqli_result($rs_query, 0, "twitter")?>" maxlength="50" class="cajaGrande" type="text"></td></tr>

						<tr>
							<td width="15%">País</td>
							<td colspan="3">
							<input type="hidden"  name="codpais" id="codpais" value="<?php echo mysqli_result($rs_query, 0, "pais");?>"/>
							<select class="comboGrande" id="bonusPa"  onchange="$('#codpais').val($('#bonusPa option:selected').val());">							
							<option value="0">Seleccione una pais</option>
								<?php
							$pais=mysqli_result($rs_query, 0, "pais");
						 	$query_pais="SELECT * FROM paises  ORDER BY nombre ASC";
							$res_pais=mysqli_query($GLOBALS["___mysqli_ston"], $query_pais);
							$conta=0;								
								while ($conta < mysqli_num_rows($res_pais)) { 
									if(mysqli_result($res_pais, $conta, "codpais")==$pais) {?>
								<option value="<?php echo mysqli_result($res_pais, $conta, "codpais")?>" selected="selected"><?php echo mysqli_result($res_pais, $conta, "nombre");?></option>
								<?php } else { ?>
									<option value="<?php echo mysqli_result($res_pais, $conta, "codpais")?>"><?php echo mysqli_result($res_pais, $conta, "nombre");?></option>
								<?php } 
								$conta++;
								} ?>				
								</select>
							</td>
						</tr>	

						<tr>
							<td width="15%">Departamento</td>
							<td colspan="3">
							<input type="hidden"  name="cboProvincias" id="cboProvincias" value="<?php echo mysqli_result($rs_query, 0, "provincia");?>"/>
							<select class="comboGrande" id="bonusP"  onchange="$('#cboProvincias').val($('#bonusP option:selected').val());">							
							<option value="0">Seleccione uno</option>
								<?php
							$provincia=	mysqli_result($rs_query, 0, "provincia");
							$query_provincias="SELECT * FROM provincias  ORDER BY nombreprovincia ASC";
							$res_provincias=mysqli_query($GLOBALS["___mysqli_ston"], $query_provincias);
							$conta=0;
								while ($conta < mysqli_num_rows($res_provincias)) { 
								if(mysqli_result($res_provincias, $conta, "codprovincia")==$provincia) {
 								?>
								<option value="<?php echo mysqli_result($res_provincias, $conta, "codprovincia")?>" selected="selected"><?php echo mysqli_result($res_provincias, $conta, "nombreprovincia");?></option>
								<?php	} else {	?>
								<option value="<?php echo mysqli_result($res_provincias, $conta, "codprovincia")?>"><?php echo mysqli_result($res_provincias, $conta, "nombreprovincia");?></option>
								<?php  
								}
								$conta++;
								} ?>				
								</select>							</td>
				        </tr>						
					  <tr>
						  <td valign="top" colspan="2">Cambiar imagen de login
						  <br>[Formato&nbsp;jpg]&nbsp;[336x173]
						  <br>
						  </td></tr><tr>
						  <td valign="top" colspan="2"><input type="file" name="login" id="login" class="cajaMedia" accept="image/jpg" onchange="Preview('login');" />
						  </td>
					  </tr>
					  <tr>
						  <td height="180px" width="400px" colspan="2">
							<img src="loadimage.php?id=11&default=1" style=" float: right;" id="uploadlogin" >
						  </td>
				      </tr>

					</table>	
					
					</td></table>			      
				      
				      
				</div>
				      
        </div><!-- //tab1 -->


    	<div id="tab2" class="tabContents">
        	<div id="inner">
        	
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
					  <tr>
						  <td valign="top" colspan="2" width="100%">
						  <div id="tituloForm" class="header" style="width:100%;">Selección de cabezal y pie de página para la impresión de reportes y listas </div>
						  </td></tr>
						  <tr>
						  <td valign="top">
						  Cabezal&nbsp;Imagen&nbsp;[Formato&nbsp;jpg&nbsp;png]&nbsp;[977x75]</td></tr>
						  <tr>
						  <td valign="top">	
						  	  <input type="file" name="cabezalmail" id="cabezalmail" class="cajaMedia" accept="image/jpg" onchange="Preview('cabezalmail');" />
						  	  </td><td height="70px">
								<img src="loadimage.php?id=12&default=2" style="width:750px; height:70px; float: left;" id="uploadcabezalmail" >
						  </td>
				      </tr>
				      <tr><td height="70px"></td><td height="70px"></td></tr>
					  <tr>
						  <td valign="top">Pie&nbsp;de&nbsp;mail&nbsp;Imagen&nbsp;[Formato&nbsp;jpg&nbsp;png]&nbsp;[977x60]</td></tr>
						  <tr>
						  <td valign="top">
						  <input type="file" name="piemail" id="piemail" class="cajaMedia" accept="image/jpg" onchange="Preview('piemail');" />
						  </td><td height="70px">
									<img src="loadimage.php?id=13&default=3" style=" width:750px; height:70px; float: left;" id="uploadpiemail" >
						  </td>
				      </tr>
				      <tr><td>Tamaño&nbsp;de&nbsp;papel</td><td>
						<select type=text size=1 name="papel" id="papel" class="comboMedio">
							<?php
								$tipo = array("0"=>"Seleccione uno", "297x420xP"=>"A3 - Vertical","210x297xP"=>"A4 - Vertical","148x210xL"=>"A5 - Horizontal","279,4x210xP"=>"Carta - Vertical","355,6x210xP"=>"Legal - Vertical");
							foreach($tipo as $key => $tpo) {
								if ($key==mysqli_result($rs_query, 0, "papel")){
							      echo "<option value='$key' selected>$tpo</option>";
								} else {
							      echo "<option value='$key'>$tpo</option>";
								}

							}
							?>
							</select>				      
				      </td></tr>
				      
				      <tr><td>Servidor&nbsp;y&nbsp;nombre&nbsp;impresora&nbsp;Reportes</td>
				      <td>
				      &nbsp;<input name="servidorreporte" id="servidorreporte" size="20" class="cajaMedia" value="<?php echo mysqli_result($rs_query, 0, "servidorreporte");?>" placeholder="Servidor impresión">
				      &nbsp;<input name="impresorareporte" id="impresorareporte" size="20" class="cajaMedia" value="<?php echo mysqli_result($rs_query, 0, "impresorareporte");?>" placeholder="Nombre impresora">
				      &nbsp;<span style="font-size: 10px;">Nombre de la impresora del sistema donde imprimir reporte.</span>
				      </td></tr>
				      <tr><td>&nbsp;</td>
				      <td>
				<?php
				$reportes="";
					if (mysqli_result($rs_query, 0, "reporte")==1) {
						$reportes="checked";
					}
				?>	
						<input type="hidden" name="reporte" value="0">
						<label><input class="checkbox1" type="checkbox" name="reporte" value="1" <?php echo $reportes;?> ><span></span></label>							
				      &nbsp;<span style="font-size: 10px;">Envia la impresión directamente a la impresora sin cuadro de diálogo.</span>
				      </td></tr>				      
				      <tr><td colspan="2">
				      <button class="boletin" onClick="imprimir();;" onMouseOver="style.cursor=cursor;"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir muestra</button>
				      </td></tr>
					</table>  
				<script type="text/javascript">
				
				    function Preview(item) {
				        var oFReader = new FileReader();
				        oFReader.readAsDataURL(document.getElementById(item).files[0]);
							var upload="upload"+item;
							var numupload="#upload"+item;
							
				        oFReader.onload = function (oFREvent) {
				            document.getElementById(upload).src = oFREvent.target.result;
				            $(numupload).show();
				        };
				    };
				
				</script>
			</div> 
			</div> 
<!-- //tab2 -->
			<div id="tab3" class="tabContents">
        	<div id="inner">

			<table class="fuente8" width="100%" cellspacing=5 cellpadding=5 border=0 >
			<tbody><tr><td valign="top" width="20%">
			<table class="fuente8" width="100%" cellspacing=2 cellpadding=3 border=0 >
				<tbody>
				<tr>
				<td colspan="2">Configurar cuenta de mail</td>
				</tr><tr>
				<td>Nombre:</td>
				<td><input name="emailname" id="emailname" value="<?php echo mysqli_result($rs_query, 0, "emailname");?>" maxlength="50" class="cajaMedia" type="text"></td>
				</tr><tr>
				<td>Email:</td>
				<td><input name="emailsend" id="emailsend" value="<?php echo mysqli_result($rs_query, 0, "emailsend");?>" maxlength="50" class="cajaMedia" type="text"></td>
				</tr><tr>
				<td>Responder a:</td>
				<td><input name="emailreply" id="emailreply" value="<?php echo mysqli_result($rs_query, 0, "emailreply");?>" maxlength="50" class="cajaMedia" type="text"></td>
				</tr><tr>
				<td>Password:</td>
				<td>
				<input type="password" name="emailpass" id="emailpass" size="30" class="cajaMedia" placeholder="Escriba contraseña"  ></input>				
				</td>
				</tr><tr>
				<td>Servidor/Host:</td>
				<td><input name="emailhost" id="emailhost" value="<?php echo mysqli_result($rs_query, 0, "emailhost");?>" maxlength="50" class="cajaMedia" type="text"></td>
				</tr><tr>
				<td>Utilizar ssl</td>
				<td>
				<?php
				$ssl="";
					if (mysqli_result($rs_query, 0, "emailssl")==1) {
						$ssl="checked";
					}
				?>	
							
				<label><input type="checkbox" name="emailssl" value="1" <?php echo $ssl;?> style="vertical-align: middle; margin-top: -1px;" ><span></span></label>	
				&nbsp;
				Puerto:&nbsp;
				<input type="hidden"  name="emailpuerto" id="emailpuerto" value="<?php echo mysqli_result($rs_query, 0, "emailpuerto");?>" />
				<select class="comboPequeno" id="bonus"  onchange="$('#emailpuerto').val($('#bonus option:selected').val());">
				<?php $tipopuerto = array(0=>"25", 1=>"465", 2=>"993");
					if (mysqli_result($rs_query, 0, "emailpuerto")==" ")
					{
					echo '<option value="" selected>Selecione uno</option>';
					}
					foreach($tipopuerto as $key=>$i) {
					  	if ( $i==mysqli_result($rs_query, 0, "emailpuerto")) {
					  		?>
							<option value="<?php echo $i;?>" selected><?php echo $i;?></option>
							<?php
						} else {
							?>
							<option value="<?php echo $i;?>"><?php echo $i;?></option>
							<?php
						}
					}
					?>				
				</select>
				</td>
				</tr>
<tr>
			<td valign="top" colspan="2"> Puede incluir estos campos que serán sustituidos al enviar el mail<p>
				Datos del Cliente:<p>
				Empresa: *empresa*<br>
				Contacto: *nombre* *apellido*<br>
				<p>Firma, nombre y apellido del usuario que inicia el sistema y envia el mail<p>
				Usuario: *usuario*<br>
				<p>
				Puede indicar el tipo de documento que esta enviando<p>
				Tipo: *documento*<br>
				(la factura), (la orden de compra), (el resumen de cuenta)
				</p>
				</td></tr>
				</table>
				<td align="center" valign="top" width="50%" >
					
			<div id="compose" style="width: 806px; height: 300px;">
				<textarea cols="64" rows="15" id="message" name="message">
				<?php
				$emailbody=mysqli_result($rs_query, 0, "emailbody");
				if(trim($emailbody) != '')	{ 
					echo $emailbody;
				} else {				
				?>
						<table border="0" cellspacing="0" cellpadding="0" width="100%" bgcolor="#1f1f1f">
						<tbody>
						<tr>
						<td style="font-size: 0pt; line-height: 0pt; text-align: left;"><img src="../img/logo.jpg" border="0" alt="" width="71" height="52" /></td>
						</tr>
						</tbody>
						</table>
						<table border="0" cellspacing="0" cellpadding="0" width="100%">
						<tbody>
						<tr>
						<td style="font-size: 0pt; line-height: 0pt; text-align: left;" width="90">&nbsp;</td>
						<td width="440">
						<p>&nbsp;</p>
						<p><span style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;">
						<span style="color: #1f1f1f; line-height: 12px; text-align: center;">*empresa*</span></span></p>
						<p><span style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;">
						<span style="color: #1f1f1f; line-height: 12px; text-align: center;">&nbsp;Estimado: *nombre* *apellido*</span></span></p>
						<p><span style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;">
						<span style="color: #1f1f1f; line-height: 12px; text-align: center;">&nbsp;</span></span>
						<span style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;">Seg&uacute;n lo solicitado tenemos el agrado de adjuntarle los detalles de *documento* en un archivo PDF.
						</span></p>
						<p><span style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;">&nbsp;</span>
						<span style="color: #1f1f1f; font-family: tahoma, arial, helvetica, sans-serif; line-height: 24px; text-align: center; font-size: small;">Aguardamos sus comentarios</span></p>
						<p><span style="color: #1f1f1f; font-family: tahoma, arial, helvetica, sans-serif; line-height: 24px; text-align: center; font-size: small;">&nbsp;</span>
						<span style="color: #1f1f1f; font-family: tahoma, arial, helvetica, sans-serif; line-height: 24px; text-align: center; font-size: small;">Saludos</span></p>
						<p><span style="color: #1f1f1f; font-family: tahoma, arial, helvetica, sans-serif; line-height: 24px; text-align: center; font-size: small;">&nbsp;</span>
						<span style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;"><strong><span style="color: #1f1f1f; line-height: 24px; text-align: center;">*usuario*</span>
						</strong></span></p>
						<p>MCC - Soporte t&eacute;cnico <br />Mobile : (+598) (0) 96-261570 <br />Montevideo: (+598) 2486.3046 &nbsp;&nbsp;</p>
						<p style="font-size: 0pt; line-height: 0pt; height: 30px;"><span style="color: #1f1f1f; font-family: Tahoma; font-size: 20px; font-weight: bold; line-height: 24px; text-align: center;">
						<br /></span></p>
						
						<div class="text-center" style="color: #868686; font-family: Tahoma; font-size: 14px; line-height: 18px; text-align: center;">Enviado desde UYCODEKA Facturaci&oacute;n WEB<br />
						 Con UYCODEKA obtenga r&aacute;pidamente informaci&oacute;n sobre el estado de su empresa</div>
						<div class="text-center" style="color: #868686; font-family: Tahoma; font-size: 14px; line-height: 18px; text-align: center;">&nbsp;</div>
						</td>
						<td style="font-size: 0pt; line-height: 0pt; text-align: left;" width="90">&nbsp;</td>
						</tr>
						</tbody>
						</table>
						<table style="width: 100%; background-color: #7f807e;" border="0" cellspacing="0" cellpadding="0">
						<tbody>
						<tr>
						<td style="text-align: justify;">
						 <address class="footer" style="color: #a9aaa9; font-family: Arial; font-size: 11px; line-height: 20px; text-align: center;">
						 <span style="color: #ffffff;">Juan Ram&oacute;n G&oacute;mez 2671 apto 8  MCC Soporte T&eacute;cnico soporte@mcc.com.uy</span><br />
						 <span style="color: #ffffff;"> Copyright &copy;  MCC Soporte T&eacute;cnico.</span></address>
						 <address class="footer" style="color: #a9aaa9; font-family: Arial; font-size: 11px; line-height: 20px; text-align: center;">
						 <span style="color: #ffffff;"><br /></span></address> <address><span style="color: #ffffff; font-size: xx-small; font-family: tahoma, arial, helvetica, sans-serif;">
						 Por favor considere el medio ambiente y no imprima este correo a menos que lo necesite. </span></address><address>
						 <span style="color: #ffffff; font-size: xx-small; font-family: tahoma, arial, helvetica, sans-serif;"> El presente correo electr&oacute;nico y cualquier posible archivo adjunto 
						 est&aacute; dirigido  								&uacute;nicamente al destinatario del mismo y contiene informaci&oacute;n que puede ser confidencial.  								
						 Si Ud. no es el destinatario correcto por favor notifique al remitente respondiendo  								este mensaje y elimine inmediatamente de su sistema, el correo
						  electr&oacute;nico y los posibles  								archivos adjuntos al mismo. Est&aacute; prohibida cualquier utilizaci&oacute;n, difusi&oacute;n o copia de  
						  								este correo electr&oacute;nico por cualquier persona o entidad que no sean las espec&iacute;ficas  								
						  								destinatarias del mensaje. MCC - Soporte T&eacute;cnico no acepta ninguna responsabilidad  								
						  								con respecto a cualquier comunicaci&oacute;n que haya sido emitida incumpliendo lo previsto  								
						  								en la Ley 18.331 de Protecci&oacute;n de Datos Personales.</span> </address></td>
						</tr>
						</tbody>
						</table>
						<?php }
						?>
						</textarea>
			</div><!-- #compose -->
									
						
					</td>
				</tr>

				<!-- END Footer -->
			</tbody>
		</table>
					</td>
			</tbody>
		</table>       	
        	</div>

<!-- -->				
</div><!-- //Tab Container -->
<div id="tab4" class="tabContents">
        	<div id="inner">

			<table class="fuente8" width="100%" cellspacing=2 cellpadding=2 border=0 style="min-width: 750px;" >
				<tr><td>Servidor&nbsp;y&nbsp;nombre&nbsp;de&nbsp;la&nbsp;impresora&nbsp;de&nbsp;Facturación</td>
				      <td>
				     <input name="servidorfactura" id="servidorfactura" size="20" class="cajaMedia" value="<?php echo mysqli_result($rs_query, 0, "servidorfactura");?>" placeholder="Servidor impresión">
				      </td></tr>		      			
<tr>
<td><span>Nombre de la impresora del sistema donde imprimir las facturas.</span>
</td><td><input name="impresorafactura" id="impresorafactura" size="20" class="cajaMedia" value="<?php echo mysqli_result($rs_query, 0, "impresorafactura");?>" placeholder="Nombre impresora">
</td>	</tr>
<tr>
			<td colspan="2">
			<table class="fuente8" cellspacing=2 cellpadding=2 border=0 >
			<tr>
				<td><div align="left">Lugar donede aparece impreso el símbolo de la moneda en uso</div></td>
				<?php
					if (mysqli_result($rs_query, 0, "lugarmon")==1) {
						$izquierda="checked";
						$derecha="";
					} elseif (mysqli_result($rs_query, 0, "lugarmon")==2) {
						$derecha="checked";
						$izquierda="";
					} else {
						$izquierda="";
						$derecha="";
				  }
				?>
			<td><input type="hidden" name="lugarmon" value="<?php echo mysqli_result($rs_query, 0, "lugarmon");?>">
					<input type="radio" name="lugarmon" value="1" id="id_radio1" <?php echo $izquierda;?>>
                   <div id="div1" style="display: none;">$</div>
			</td>
			<td>10000.11</td>
			<td>
                   <div id="div2" style="display: none;">$</div>
					<input  type="radio" name="lugarmon" value="2" id="id_radio2" <?php echo $derecha;?>>
			</td>			
			</tr>	
			</table></td>
			</tr>
			<tr>
			<td colspan="2">
			<table class="fuente8" cellspacing=2 cellpadding=2 border=0 style="min-width: 760px;">
			<tr>
			<td colspan="3">
			 <div id="tituloForm" class="header" style="width:100%; height: 40px;">Seleccione modelo de factura a utilizar como predeterminado, 
			 <br>&nbsp;para el caso de la facuración electrónica el sistema determinará cual utilizar&nbsp; </div>
			</td>
			</tr><tr>
			<td valign="top">
				<?php
				$logofactura='';
					if (mysqli_result($rs_query, 0, "logofactura")==1) {
						$logofactura="checked";
					}
				?>
			<table class="fuente8" cellspacing=2 cellpadding=2 border=0 width="170"  style=" max-width:170px;">
			<tr><td>Incluir logo y datos&nbsp;
						<input type="hidden" name="logofactura" value="0">
						<label><input class="checkbox1" type="checkbox" name="logofactura" id="logofactura" value="1" onclick="prever();" <?php echo $logofactura;?>><span></span></label>			
			</td></tr>
			<tr>
			<td valign="top" style="height: 16px;">
				<?php
					if (mysqli_result($rs_query, 0, "modelo")==1) {
						$basicoA4="checked";
					} elseif (mysqli_result($rs_query, 0, "modelo")==2) {
						$basicoA5="checked";
					} else {
						$basicoA4="";
						$basicoA5="";
				  }

				?><input type="hidden" name="modelo" id="modelo" value="<?php echo $modeloselected;?>">
			Modelo&nbsp;Básico&nbsp;DINA4
			</td><td style="width: 15px;" valign="top"><input type="radio" name="modelo" value="1" class="basico"  id="basicoA4" style="position:relative;top:-5px; z-index: 999;" <?php echo $basicoA4;?>>
			<i class="fa fa-check" style="display: none; z-index: 99; position: relative;" id="basicoA4s"></i>
			</td>
			</tr><tr>
			<td valign="top" style="height: 16px;">
			Modelo&nbsp;Básico&nbsp;DINA5
			</td><td style="width: 15px;" valign="top"><input type="radio" name="modelo" value="2" class="basico" id="basicoA5"  style="position:relative;top:-5px; z-index: 999;" <?php echo $basicoA5;?>>
			<i class="fa fa-check" style="display: none; z-index: 99; position: relative;" id="basicoA5s"></i>
			</td>
			</tr><tr>
			<td colspan="2" align="center">
			<button class="boletin" onClick="ver();" onMouseOver="style.cursor=cursor">
						<i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir prueba</button>
			</td></tr>			
			</table>
			<td style="max-width:600px; height: 300px;" valign="top">
			<input type="hidden" id="archivo" name="archivo">
			<div id="container" style="height:300px;  max-width:600px;  overflow:auto; background:#fff;">
    				<img  id="containerimg" src="<?php echo $img_modelo;?>" style=" max-width:600px; " />
			</div>
			</td>			
			</tr>
			</table>			
			</td>			
			</tr>
			</table>
</div>
</div><!-- //Tab Container -->
			<div id="tab5" class="tabContents">
        	<div id="inner">
			<table class="fuente8" width="100%" cellspacing=2 cellpadding=3 border=0 >
				<tbody>
				<tr>
				<td colspan="2">Configurar mail eFactura</td>
				</tr><tr>
				<td>Nombre:</td>
				<td><input name="efemailname" id="efemailname" value="<?php echo mysqli_result($rs_query, 0, "efemailname");?>" maxlength="50" class="cajaMedia" type="text"></td>
				</tr><tr>
				<td>Email:</td>
				<td><input name="efemailsend" id="efemailsend" value="<?php echo mysqli_result($rs_query, 0, "efemailsend");?>" maxlength="50" class="cajaMedia" type="text"></td>
				</tr><tr>
				<td>Password:</td>
				<td>
				<input type="password" name="efemailpass" id="efemailpass" size="30" class="cajaMedia" placeholder="Escriba contraseña"  ></input>				
				</td>
				</tr><tr>
				<td>Servidor/Host:</td>
				<td><input name="efemailhost" id="efemailhost" value="<?php echo mysqli_result($rs_query, 0, "efemailhost");?>" maxlength="50" class="cajaMedia" type="text"></td>
				</tr><tr>
				<td>Utilizar ssl</td>
				<td>
				<?php
				$ssl="";
					if (mysqli_result($rs_query, 0, "efemailssl")==1) {
						$ssl="checked";
					}
				?>	
				<label><input class="checkbox1" type="checkbox" name="efemailssl" value="1" <?php echo $ssl;?> ><span></span></label>	
				&nbsp;
				Puerto:&nbsp;
				<input type="hidden"  name="efemailpuerto" id="efemailpuerto"/>
				<select class="comboPequeno" id="bonus2"  onchange="$('#efemailpuerto').val($('#bonus2 option:selected').val());">				
				<?php $tipopuerto= array(0=>"25", 1=>"465", 2=>"993");
					if (mysqli_result($rs_query, 0, "efemailpuerto")==" ")
					{
					echo '<option value="" selected>Selecione uno</option>';
					}
					foreach($tipopuerto as $i) {
					  	if ( $i==mysqli_result($rs_query, 0, "efemailpuerto")) {
					  		?>
							<option value="<?php echo $i;?>" selected><?php echo $i;?></option>
							<?php
						} else {
							?>
							<option value="<?php echo $i;?>"><?php echo $i;?></option>
							<?php
						}
					}
					?>				
				</select>
				</td>
				
				</tr>
				<tr>
				<td>Autenticación</td>
				<td>
				<?php
				$aut="";
					if (mysqli_result($rs_query, 0, "efemailaut")==1) {
						$aut="checked";
					}
				?>	
				<label><input class="checkbox1" type="checkbox" name="efemailaut" value="1" > <span></span></label>	
				</td>
				
				</tr>	
				<tr><td>Archivos enviados por e-mail</td><td><select class="comboGrande" name="efemailtipo">
				<?php $tipof = array(0=>"XML Entre Empresas", 1=>"XML Entre Empresas y Versión Impresa", 2=>"Versión impresa", 3=>"No enviar");
					if (mysqli_result($rs_query, 0, "efemailtipo")==" ")
					{
					echo '<option value="" selected>Selecione uno</option>';
					}
					foreach($tipof as $i) {
					  	if ( $i==mysqli_result($rs_query, 0, "efemailtipo")) {
							echo "<option value=$i selected>$i</option>";
						} else {
							echo "<option value=$i>$i</option>";
						}
					}
					?>				
				</select></td></tr>			
				</tbody>
			</table>
     	
        	</div>

<!-- -->				
</div><!-- //Tab Container -->
<div id="tab6" class="tabContents">
        	<div id="inner">

			<table class="fuente8" width="100%" cellspacing=5 cellpadding=5 border=0 >
			<tr><td valign="top">
			<table class="fuente8" cellspacing=2 cellpadding=2 border=0 ><td>
			
			<img src="../img/miscelaneo.png" width="563" height="224" alt="">

			</td>
			</table></td>		
			<td>
			<table class="fuente8" cellspacing=2 cellpadding=2 border=0 >
			<td>
			<p>Para habilitar el envió de SMS es necesario tener instalado en el servidor donde se instalo UYCODEKA, Gammu Gateway SMS con acceso a la base de UYCODEKA en mysql</p>
			<p>http://www.syednetworks.com/opensourc-sms-gateway-using-gammu-and-mysql</p>
			<p>http://nuurwahidanshary.wordpress.com/2012/02/10/configure-and-manage-gammu-sms-gateway-with-mysql-on-ubuntu-platform/</p>
			
			</td>
			
			<tr>
				<td><div align="left">SMS - Aviso service terminado</div></td>
				<?php
					if (mysqli_result($rs_query, 0, "smsservice")==1) {
						$leer="checked";
					}
				?>
				<td>
				<input type="hidden" name="smsservice" value="1">
				<input class="checkbox1" type="checkbox" name="smsservice" value="<?php echo mysqli_result($rs_query, 0, "smsservice");?>" ></td>
			<td width="70%">&nbsp;</td>
			</tr>	
			<tr>
				<td><div align="left">SMS - Aviso envío factura por mail</div></td>
				<?php
					if (mysqli_result($rs_query, 0, "smsfactura")==1) {
						$leer="checked";
					}
				?>
				<td>
				<input type="hidden" name="smsfactura" value="1">
				<input class="checkbox1" type="checkbox" name="smsfactura" value="<?php echo mysqli_result($rs_query, 0, "smsfactura");?>" ></td>
			<td>&nbsp;</td>
			</tr>					
			</table></td>
			<td valign="top">
			<table class="fuente8" cellspacing=2 cellpadding=2 border=0 >
				<tr><td>

<?php
					  	$query_entidades="SELECT * FROM entidades WHERE borrado=0 ORDER BY nombreentidad ASC";
						$res_entidades=mysqli_query($GLOBALS["___mysqli_ston"], $query_entidades);
						$contador=0;
					  ?>
						<tr>

<td valign="top">Banco/Moneda/Cuenta/Incluir&nbsp;en&nbsp;Factura:
   <div id="newItemMHide" style="display:;">
      <img id="newItemM" src="../img/plus.png" width="16" height="16" vspace="0" hspace="0" align="right" border="0" style="cursor:pointer;" class="trigger">
   </div>
      <div id="newItemMShow" style="display: none; position:absolute; border: 0px solid #000; z-index:10;">
         <div class="seleccione"><div style="position:absolute; right:10px; top:3px;"> 
         <img id="newItemM" src="../img/minus.png" width="16" height="16" vspace="0" hspace="0" align="left" border="0" style="cursor:pointer;" class="triggerClose"></div><div>
<div style="position:relative; top: 15px;">
<table class="fuente8" cellspacing=2 cellpadding=2 border=0><tr><td>


Banco        </td><td> 
<select id="cboBanco" name="cboBanco" class="comboGrande">
							<option value="0" selected="">Seleccione&nbsp;una&nbsp;Entidad&nbsp;Bancaria</option>
									<?php
								while ($contador < mysqli_num_rows($res_entidades)) { 
								?>
								<option value="<?php echo mysqli_result($res_entidades, $contador, "codentidad")?>"><?php echo mysqli_result($res_entidades, $contador, "nombreentidad")?></option>
								<?php 
								 $contador++;
								} ?>
								</select>         
</td></tr><tr><td>Moneda</td><td>
		 					<select onchange="cambio();" name="Amoneda" id="Amoneda" class="comboMedio">
							<?php $tipofa = array(  1=>"Pesos", 2=>"U\$S");
							if (@$moneda==" ")
							{
							echo '<option value="" selected>Selecione uno</option>';
							}
							foreach ($tipofa as $key => $i ) {
							  	if ( @$moneda==$key ) {
									echo "<option value=$key selected>$i</option>";
								} else {
									echo "<option value=$key>$i</option>";
								}
		
							}
							?>
							</select>
							</td></tr><tr><td>Nº&nbsp;Cuenta</td><td>
         <input name="numero" id="numcuenta" class="cajaPequena"/> </input>
         </td></tr><tr><td>
						<label> Incluir&nbsp;en&nbsp;Factura
						<?php
						$est='';
						$banco=mysqli_result($rs_query, 0, "bancos");
							if(!empty($banco)) {
							$est=split('#',$banco);
							}
						?>
							</label>		</td><td valign="top">
						<label>&nbsp;<input type="checkbox" name="incluir" id="incluir" value="0">
							<span></span>
						</label>
         <input name="incluir" id="incluir" type="checkbox" class="cajaPequena"/>
          </input>
          </td></tr></table>
          </div>         
         
         <div style="text-align: right;"><div class="boletin" onClick="agregarbanco();"><i class="fa fa-file-o" aria-hidden="true"></i>&nbsp;Aceptar</div></div>
         </div>
      </div>
</td>
</tr><tr>
<td valign="top">      
   <select name="Vbancos[]" id="Vbancos"   style="width: 300px; height:50px" multiple="true" size="2" class="comboMedio ComboBox">
<?php
if (is_array($est)) {
foreach ($est as $x)
	{
		   echo "<option value='$x' >".$x."</option>";
	}
} else {
	if (!empty($banco)) {
	   echo "<option value='$banco' >".$banco."</option>";
   }
}

?>
    </select>

</td>						
				
				</td></tr>
			</table>
			</td>			
			</tr>
			<tr><td>
			
			<table class="fuente8" width="50%" cellspacing=5 cellpadding=5 border=0 >
			<tr><td colspan="2">Servidor auxiliar.
			<p> Este servidor es donde se aloja las imágenes de los articulos.
			</td>
			</tr><tr>
			<td>Server:</td>
			<td><input name="serveraux" id="serveraux" value="<?php echo mysqli_result($rs_query, 0, "serveraux");?>" maxlength="50" class="cajaGrande" type="text"></td>
			</tr>			
			</table>
			
			</td></tr>
			
			</table>


</div>
</div>
<br style="line-height:15px">
	<div style="position: fixed; width: 100%; margin:0 auto; ">
					<div align="center">
						<button class="boletin" onClick="guardar();" onMouseOver="style.cursor=cursor"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Guardar</button>
					</div>
	</div>
			  </div>
			  </form>
			 </div>				
		  </div>
<script>
function guardar() {
	   selectAll('Vbancos',true)	  
    document.getElementById("formulario").submit();
}
</script>
<script type="text/javascript">
		//apply masking to the demo-field
		//pass the field reference, masking symbol, and character limit
	//new MaskedPassword(document.getElementById("emailpass"), '\u25CF');
</script> 	

</body>
</html>			