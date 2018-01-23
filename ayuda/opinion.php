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
         /*echo "<script>parent.changeURL('../../index.php' ); </script>";*/
	 header("Location:../index.php");
} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
              echo "<script>window.parent.changeURL('../index.php' ); </script>";
	       /*/header("Location:../index.php");	*/
	       exit;
   }
   $s->data['loggedAt']= time();/*/ update last accessed time*/
   $s->save();
}

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];

include ("../conectar.php");
include("../common/verificopermisos.php");
//header('Content-Type: text/html; charset=UTF-8'); 

date_default_timezone_set("America/Montevideo"); 
include("../class/PHPMailerAutoload.php");
include("../funciones/fechas.php");
include("../enviomail/encrypt_decrypt.php");

/*Extraigo datos del mail */
$query="SELECT * FROM datos WHERE coddatos='0'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

$emailname=mysqli_result($rs_query, 0, "emailname");
$emailsend=mysqli_result($rs_query, 0, "emailsend");
$emailreply=mysqli_result($rs_query, 0, "emailreply");
$emailpass=encrypt_decrypt('decrypt', mysqli_result($rs_query, 0, "emailpass"));
$emailhost=mysqli_result($rs_query, 0, "emailhost");
$emailssl=mysqli_result($rs_query, 0, "emailssl");
$emailpuerto=mysqli_result($rs_query, 0, "emailpuerto");


$usuario=$UserNom." ".$UserApe;

$nombre=isset($_POST["nombre"]) ? @$_POST["nombre"] : null ;
$message=isset($_POST["message"]) ? @$_POST["message"] : null ;

if(isset($_POST["ask"])) {
 	$ask=$_POST["ask"];
 } elseif(isset($_GET)) {
 	$ask=$_GET["ask"];
 } else { 
	$ask= $s->data['ask'];
}
$control=isset($_GET["control"]) ? @$_GET["control"] : 1 ;

$agree=isset($_POST["agree"]) ? @$_POST["agree"] : null ;

$emailbody=$message;

//echo $nombre."<>".$message."<>".$ask."<>".$agree."<C>".$control;

	if ($nombre!='' and $agree==1) {
	$uname=$nombre." ".$apellido;
$uemail="soporte@mcc.com.uy";
	$ssl="ssl://".$emailhost.":".$emailpuerto."";
			// Instanciando el Objeto  
			$mail = new PHPMailer(); 
			$mail->IsSMTP(); 
			 //Servidor SMTP - GMAIL usa SSL/TLS  
			 //como protocolo de comunicación/autenticación por un puerto 465.  
			 $mail->Host = $ssl;
			 //$mail->Host = 'ssl://smtp.gmail.com:465';  
			 // True para que verifique autentificación  
			 $mail->SMTPAuth = true;  
			 // Cuenta de E-Mail & Password  
			 $mail->Username = $emailsend;
			 $mail->Password = $emailpass;
		
			 $mail->From = $emailsend;
			 $mail->FromName = $emailname;
			 $mail->Subject = $subject;
			 // Cuenta de E-Mail Destinatario  
			 $mail->CharSet = "UTF-8";
			 if($emailreply!='') {
			 $mail->AddReplyTo($emailreply, $emailname);
			 } else {
			 $mail->AddReplyTo($emailsend, $emailname);
			 }
				/*Datos del destinatario*/
			 $mail->AddAddress($uemail,$uname);
			 
			 $mail->WordWrap = 900;
			 $mail->IsHTML(true);
		
						$message = $emailbody;
				
			$mail->MsgHTML($message);
			
        if (!$mail->send()) {
            $msg = 'Algo funcionó mal, intente mas tarde.';
        } else {
            $msg = 'Mesaje enviado! Gracias por su tiempo.';
        } 
							?>
								<script type="text/javascript" >
								parent.$('idOfDomElement').colorbox.close();
								</script>
							<?php        
		((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
		} else {
			if($ask==0) {
							$query="UPDATE usuarios SET `ask`=0 WHERE codusuarios=$UserID";
							$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
							   $s->data['ask']=0;
    							$s->save();  
							?>
								<script type="text/javascript" >
								parent.$('idOfDomElement').colorbox.close();
								</script>
							<?php
			}
?>				

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Valoramos su opinión</title>
		<link href="../css3/estilos.css" type="text/css" rel="stylesheet">

		<script src="../js3/jquery.min.js"></script>
<script src="../js3/tinymce/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea#message' ,
		 language: 'es',
		 height : 250,
		 max_height: 350,
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

</head>
<body>
<h1>Valoramos su opinión</h1>
<?php if (!empty($msg)) {
    echo "<h2>$msg</h2>";
}  else { ?>
    				<table class="fuente8" width="100%" cellspacing=1 cellpadding=1 border=0>
<?php if($control==1) { ?>    				
<tr><td colspan="2">
<div><h2>
Tal como lo expresamos en el manual de instalación, luego 30 días de haber instalado UYCODEKA le solicitaríamos tome unos minutos y nos envíe sus comentarios.
</h2>
</div>
</td></tr>
<?php } ?>
<form method="post" action="opinion.php" >
						<tr>
						<td>Nombre:&nbsp;<input name="nombre" id="nombre" value="<?php echo $nombre;?>" maxlength="50" class="cajaGrande" type="text">
						&nbsp;<label><input type="checkbox" name="agree" value="1" checked> Acepto enviar estas sugerencias&nbsp;<span></span></label></td>
						</tr>
						<tr>
						<td style="width: 700px; height: 150px;">
				<textarea cols="67" rows="14" id="message" name="message">	</textarea>
						</td>
						</tr>
						<tr><td>
						<?php if($ask==1 and $control==1) { ?> 
						<label><input type="checkbox" name="ask" value="0" > No volver a preguntar&nbsp;<span></span></label>&nbsp;
						<?php } ?>
						<input type="submit" value="Enviar"></td></tr>
						</table>
    
</form>
<?php 
}
}
?>
</body>
</html>