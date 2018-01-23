<?php
define('PW_SALT','(+3%_');

include("encrypt_decrypt.php");

function getCurrentUrl($full = true) {
    if (isset($_SERVER['REQUEST_URI'])) {
        $parse = parse_url(
            (isset($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'off') ? 'https://' : 'http://') .
            (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '')) . (($full) ? $_SERVER['REQUEST_URI'] : null)
        );
        $parse['port'] = $_SERVER["SERVER_PORT"]; // Setup protocol for sure (80 is default)
        return http_build_url('', $parse);
    }
}


function full_path()
{
    $s = &$_SERVER;
    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
    $sp = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port = $s['SERVER_PORT'];
    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
    $host = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
    $uri = $protocol . '://' . $host . $s['REQUEST_URI'];
    $segments = explode('?', $uri, 2);
    $url = $segments[0];
    return $url;
}

function checkUNEmail($uname,$email)
{
	global $mySQL;
	$error = array('status'=>false,'userID'=>0);
	if (isset($email) && trim($email) != '') {
		//email was entered
		if ($SQL = $mySQL->prepare("SELECT `codusuarios` FROM `usuarios` WHERE `email` = ? LIMIT 1"))
		{
			$SQL->bind_param('s',trim($email));
			$SQL->execute();
			$SQL->store_result();
			$numRows = $SQL->num_rows();
			$SQL->bind_result($userID);
			$SQL->fetch();
			$SQL->close();
			if ($numRows >= 1) return array('status'=>true,'userID'=>$userID);
		} else { return $error; }
	} elseif (isset($uname) && trim($uname) != '') {
		//username was entered
		if ($SQL = $mySQL->prepare("SELECT `codusuarios` FROM `usuarios` WHERE usuario = ? LIMIT 1"))
		{
			$SQL->bind_param('s',trim($uname));
			$SQL->execute();
			$SQL->store_result();
			$numRows = $SQL->num_rows();
			$SQL->bind_result($userID);
			$SQL->fetch();
			$SQL->close();
			if ($numRows >= 1) return array('status'=>true,'userID'=>$userID);
		} else { return $error; }
	} else {
		//nothing was entered;
		return $error;
	}
}
function getSecurityQuestion($userID)
{
	global $mySQL;
	$questions = array();
	$questions[1] = "¿En que ciudad nació?";
	$questions[2] = "¿Cúal es su color favorito?";
	$questions[3] = "¿En qué año se graduo de la facultad?";
	$questions[4] = "¿Cual es el segundo nombre de su novio/novia/marido/esposa?";
	$questions[5] = "¿Cúal es su auto favorito?";
	$questions[6] = "¿Cúal es el nombre de su madre?";
	if ($SQL = $mySQL->prepare("SELECT `secQ` FROM `usuarios` WHERE `codusuarios` = ? LIMIT 1"))
	{
		$SQL->bind_param('i',$userID);
		$SQL->execute();
		$SQL->store_result();
		$SQL->bind_result($secQ);
		$SQL->fetch();
		$SQL->close();
		return $questions[$secQ];
	} else {
		return false;
	}
}

function checkSecAnswer($userID,$answer)
{
	global $mySQL;
	if ($SQL = $mySQL->prepare("SELECT `usuario` FROM `usuarios` WHERE `codusuarios` = ? AND LOWER(`secA`) = ? LIMIT 1"))
	{
		$answer = strtolower($answer);
		$SQL->bind_param('is',$userID,$answer);
		$SQL->execute();
		$SQL->store_result();
		$numRows = $SQL->num_rows();
		$SQL->close();
		if ($numRows >= 1) { return true; }
	} else {
		return false;
	}
}

function sendPasswordEmail($userID)
{
	global $mySQL;
	if ($SQL = $mySQL->prepare("SELECT `usuario`,`email`,`contrasenia` FROM `usuarios` WHERE `codusuarios` = ? LIMIT 1"))
	{
		$SQL->bind_param('i',$userID);
		$SQL->execute();
		$SQL->store_result();
		$SQL->bind_result($uname,$email,$pword);
		$SQL->fetch();
		$SQL->close();
		$expFormat = mktime(date("H"), date("i"), date("s"), date("m")  , date("d")+3, date("Y"));
		$expDate = date("Y-m-d H:i:s",$expFormat);
		$key = md5($uname . '_' . $email . rand(0,10000) .$expDate . PW_SALT);

		if ($SQL = $mySQL->prepare("INSERT INTO `recoverymail` (`usuario`,`key`,`expDate`) VALUES (?,?,?)"))
		{
			$SQL->bind_param('iss',$userID,$key,$expDate);
			$SQL->execute();
			$SQL->close();


	if ($SQL2 = $mySQL->prepare("SELECT emailname,emailsend,emailpass,emailhost,emailssl,emailpuerto,emailbody FROM datos WHERE coddatos='0' LIMIT 1"))
	{
		@$SQL2->bind_param('s',$userID);
		$SQL2->execute();
		$SQL2->store_result();
		$SQL2->bind_result($emailname,$emailsend,$emailpass,$emailhost,$emailssl,$emailpuerto,$emailbody);
		$SQL2->fetch();
		$SQL2->close();
	}
	
$emailpass=encrypt_decrypt('decrypt',$emailpass);

	 //Servidor SMTP - GMAIL usa SSL/TLS  
	 //como protocolo de comunicación/autenticación por un puerto 465.  
	$ssl="ssl://".$emailhost.":".$emailpuerto."";
	// Instanciando el Objeto  
	$mail = new PHPMailer(); 
	$mail->IsSMTP(); 
	 //Servidor SMTP - GMAIL usa SSL/TLS  
	 //como protocolo de comunicación/autenticación por un puerto 465.  
	 $mail->Host = $ssl;
	 // True para que verifique autentificación  
	 $mail->SMTPAuth = true;  
	 // Cuenta de E-Mail & Password  
	 $mail->Username = $emailsend;
	 $mail->Password = $emailpass;
	 $mail->From = $emailsend;
	 $mail->FromName = $emailname;
	 $mail->CharSet = "UTF-8";
	 $mail->AddReplyTo($emailsend, $emailname);
	 $mail->Subject = "Su nueva contraseña es:";
	 // Cuenta de E-Mail Destinatario  
	 $mail->AddAddress($email,$email);
	 //$mail->AddAddress("fernandogambaro@gmail.com","Fernando Gambaro");
	 $mail->WordWrap = 900;
	 $mail->IsHTML(true);
	 
				$passwordLink = "<a href=\"". full_path(). "?a=recover&email=" . $key . "&u=" . urlencode(base64_encode($userID)) . "\">". full_path(). "?a=recover&email=" . $key . "&u=" . urlencode(base64_encode($userID)) . "</a>";
				$message = "Estimado/a $uname,<br>";
				$message .= "Para terminar el proceso de recuperación de contraseña visite el siguiente link:<p>";
				$message .= "<br>";
				$message .= "$passwordLink<br>";
				$message .= "<br>";
				$message .= "Asegurece de copiar la dirección en su navegador favorito, la misma expirará luego de 3 dias.<p>";
				$message .= "Si Ud. no solicito el cambio de contraseña, ningun cambio se realizara almeno que visite el link de arriba. De todas formas le recomendamos que ingrese con su cuenta y cambie la contraseña por razones de seguridad.<p>";
				$message .= "Gracias,<p>";
				$message .= "El equipo de UYCodeka";

 	 $mail->Body = $message;  
	 //$mail->Send();  
		 
	 // Notificamos al usuario del estado del mensaje  
	 if(!$mail->Send()){  
	 $message="El envío del mail fallo, pongace en contacto con el administrador! ($site_email)".$mail->ErrorInfo;  
	} else {
	$message="Revice su bandeja de entrada, le hemos enviado un mail con las instrucciones.\r\n";
	$message.="El equipo de Codeka";
	}

/*
			@mail($email,$subject,$message,$headers);
*/
			return str_replace("\r\n","<br/ >",$message);
		}

	}
}

function checkEmailKey($key,$userID)
{
	global $mySQL;
	$curDate = date("Y-m-d H:i:s");
	if ($SQL = $mySQL->prepare("SELECT `usuario` FROM `recoverymail` WHERE `key` = ? AND `usuario` = ? AND `expDate` >= ?"))
	{
		$SQL->bind_param('sis',$key,$userID,$curDate);
		$SQL->execute();
		$SQL->execute();
		$SQL->store_result();
		$numRows = $SQL->num_rows();
		$SQL->bind_result($userID);
		$SQL->fetch();
		$SQL->close();
		if ($numRows > 0 && $userID != '')
		{
			return array('status'=>true,'userID'=>$userID);
		}
	}
	return false;
}

function updateUserPassword($userID,$password,$key)
{
	global $mySQL;
	if (checkEmailKey($key,$userID) === false) return false;
	if ($SQL = $mySQL->prepare("UPDATE `usuarios` SET `contrasenia` = ? WHERE `codusuarios` = ?"))
	{
		$converter = new Encryption;
		$password = $converter->encode($password );
//echo $password;
		//$password = md5(trim($password) . PW_SALT);
		$SQL->bind_param('si',$password,$userID);
		$SQL->execute();
		$SQL->close();
		$SQL = $mySQL->prepare("DELETE FROM `recoverymail` WHERE `Key` = ?");
		$SQL->bind_param('s',$key);
		$SQL->execute();

	}
}

function getUserName($userID)
{
	global $mySQL;
	if ($SQL = $mySQL->prepare("SELECT `usuario` FROM `usuarios` WHERE `codusuarios` = ?"))
	{
		$SQL->bind_param('i',$userID);
		$SQL->execute();
		$SQL->store_result();
		$SQL->bind_result($uname);
		$SQL->fetch();
		$SQL->close();
	}
	return $uname;
}
