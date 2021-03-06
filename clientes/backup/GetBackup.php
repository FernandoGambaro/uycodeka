<?php
date_default_timezone_set("America/Montevideo"); 
//esta configuración es para poder ejecutarlo desde línea de comando
$path=realpath(dirname(__FILE__));
$largo=strlen($path)-16;
$conexionPath=substr($path,0,$largo);
include(realpath(dirname(__FILE__))."/encrypt_decrypt.php");
include($conexionPath."/conectar.php");
include(realpath(dirname(__FILE__))."/class/class.phpmailer.php");


/*Extraigo datos del mail */

$query="SELECT * FROM datos WHERE coddatos='0'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

$emailname=mysqli_result($rs_query, 0, "emailname");
$emailsend=mysqli_result($rs_query, 0, "emailsend");
$emailpass=encrypt_decrypt('decrypt', mysqli_result($rs_query, 0, "emailpass"));
$emailhost=mysqli_result($rs_query, 0, "emailhost");
$emailssl=mysqli_result($rs_query, 0, "emailssl");
$emailpuerto=mysqli_result($rs_query, 0, "emailpuerto");
$emailbody=mysqli_result($rs_query, 0, "emailbody");

$imap='';
$verifico='';
$Tamano=0;
$Errores=0;

error_reporting(E_ALL);
ini_set('display_errors', true);

header("Content-Type: text/html; charset=iso-8859-1 ");
header("Content-Type: text/html; charset=utf-8");

function  cambiaf_a_normal( $fecha ){
	list($month,$day,$year)=explode("-",$fecha);
	$fechafinal = $year. "/" . $month. "/" . $day;
	return $fechafinal;
}

function  cambiaf_barra( $fecha ){
	list($month,$day,$year)=explode("-",$fecha);
	$fechafinal = $day. "/" . $month. "/" . $year;
	return $fechafinal;
}

function  cambiaf_orden( $fecha ){
	list($month,$day,$year)=explode("-",$fecha);
	$fechafinal = $month. "/" . $day. "/" . $year;
	return $fechafinal;
} 

function  cambiaf_a_mysql( $fecha ){
	list($month,$day,$year)=explode("/",$fecha);
	$fechafinal = $year. "-" . $month. "-" . $day;	
	return  $fechafinal ;
}
/*Esta línea es la que permite conectar con el servidro de mails*/
	$imapaddress = "{barullo.zonaexterior.org:993/imap/ssl/novalidate-cert}";
	$imapmainbox = "INBOX";
	$maxmessagecount = 400;
	$user=$emailsend;
	$password=$emailpass;

    display_mail_summary($imapaddress, $imapmainbox, $user, $password, $maxmessagecount);


function extract_unit($string, $start, $end)
{
	$pos = stripos($string, $start);
	$str = substr($string, $pos);
	$str_two = substr($str, strlen($start));
	$second_pos = stripos($str_two, $end);
	$str_three = substr($str_two, 0, $second_pos);
	$unit = trim($str_three); /*/ remove whitespaces*/
	return $unit;
}

function getBody($uid, $imap) {
    $body = get_part($imap, $uid, "TEXT/HTML");
    /*/ if HTML body is empty, try getting text body*/
    if ($body == "") {
        $body = get_part($imap, $uid, "TEXT/PLAIN");
    }
    return $body;
}

function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false) {
    if (!$structure) {
           $structure = imap_fetchstructure($imap, $uid, FT_UID);
    }
    if ($structure) {
        if ($mimetype == get_mime_type($structure)) {
            if (!$partNumber) {
                $partNumber = 1;
            }
            $text = @imap_fetchbody($imap, $uid, $partNumber, FT_UID);
            switch ($structure->encoding) {
                case 3: return imap_base64($text);
                case 4: return imap_qprint($text);
                default: return $text;
           }
       }

        /*/ multipart */
        if ($structure->type == 1) {
            foreach ($structure->parts as $index => $subStruct) {
                $prefix = "";
                if ($partNumber) {
                    $prefix = $partNumber . ".";
                }
            	 $imap='';

                $data = get_part($imap, $uid, $mimetype, $subStruct, $prefix . ($index + 1));
                if ($data) {
                    return $data;
                }
            }
        }
    }
    return false;
}

function get_mime_type($structure) {
    $primaryMimetype = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");

    if ($structure->subtype) {
       return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
    }
    return "TEXT/PLAIN";
}

function display_mail_summary($imapaddress, $imapmainbox, $imapuser, $imappassword, $maxmessagecount)
{
    $imapaddressandbox = $imapaddress . $imapmainbox;

    $connection = imap_open ($imapaddressandbox, $imapuser, $imappassword)
        or die("No se puede conectar a '" . $imapaddress . 
        "' con el usuario '" . $imapuser . 
        "' y la contraseña '" . $imappassword .
        "': " . imap_last_error());

    if (@!$imap) {
        print imap_last_error();
    }  
  
	$totalmessagecount = imap_num_msg($connection) 
		or die("No hay mensajes para mostrar: " . imap_last_error());    

	if ($totalmessagecount<$maxmessagecount)
		$displaycount = $totalmessagecount;
	else
		$displaycount = $maxmessagecount;


$check = imap_mailboxmsginfo($connection);
    $size = number_format($check->Size / 1024, 2);

	$CobianCount=0;
	$MccCount=0;
	$AcroniCount=0;  
	$Tamano=0;
/*///////--------------------------------------------------------------------------------------*/
$imap=$connection;
$numMessages = imap_num_msg($imap);
/*////////////Comienzo a recorrer todos los mails*/
$z=0;
$xz=0;
for ($i = $numMessages; $i > ($numMessages - $totalmessagecount); $i--) {
	$very='';
	$z++;
    $header = imap_header($imap, $i);

/*/if ($header->Unseen == "U") {*/
	
    $fromInfo = $header->from[0];
    $replyInfo = $header->reply_to[0];

    $details = array(
        "fromAddr" => (isset($fromInfo->mailbox) && isset($fromInfo->host))
            ? $fromInfo->mailbox . "@" . $fromInfo->host : "",
        "fromName" => (isset($fromInfo->personal))
            ? $fromInfo->personal : "",
        "replyAddr" => (isset($replyInfo->mailbox) && isset($replyInfo->host))
            ? $replyInfo->mailbox . "@" . $replyInfo->host : "",
        "replyName" => (isset($replyTo->personal))
            ? $replyto->personal : "",
        "subject" => (isset($header->subject))
            ? $header->subject : "",
        "udate" => (isset($header->udate))
            ? $header->udate : ""
    );

    $uid = imap_uid($imap, $i);
    
	 $class = ($header->Unseen == "U") ? "unreadMsg" : "readMsg";
	$cliente=$details["fromName"];

	 
 // $message =imap_body($imap, $i)	or die("Can't fetch body for message " . $i . " : " . imap_last_error());
  
 echo $message = getBody($uid, $imap);
echo "<br><p>";
$found='';
/********** Para CobianBK **********************/

$pos = strpos($message,"Cobian Backup Amanita");
if($pos !== false) {
	$version= "Cobian Backup 9";
	$cliente=$details["subject"];	

$elementos = imap_mime_header_decode($cliente);
/*for ($i=0; $i<count($elementos); $i++) {
    echo "Conjunto de caracteres: {$elementos[$i]->charset}";
    echo "Texto: {$elementos[$i]->text}";
}*/
	$cliente=$elementos[0]->text;	
	$very=9;	
	$verifico=9;
	//echo extract_unit($details["subject"], "[", "]");
	//echo "<----<br><p>";
	$items = array("Copiando el directorio");
	$aux=strpos($message,"****");

	$found=1;
}

$pos = strpos($details["subject"],"Cobian Backup 10");
if($pos !== false and $very!=9) {
	$version= "Cobian Backup 10";
	/*/echo extract_unit($details["subject"], "[", "]");*/
	$items = array("*** La tarea");
	$aux=21;
	$found=1;
}
$pos = strpos($message,"Cobian Backup 10");
if($pos !== false and $very!=9) {
	$version= "Cobian Backup 10";
	/*/echo extract_unit($details["subject"], "[", "]");*/
	$items = array("*** La tarea");
	$aux=21;
	$found=1;
}
$pos = strpos($cliente,"Cobian Backup 10");
if($pos !== false and $very!=9) {
	$version= "Cobian Backup 10";
	$items = array("*** La tarea ");
	$aux=21;
	$found=1;
}

$pos = strpos($details["subject"],"Cobian Backup 11");
if($pos !== false and $very!=9) {
	$version= "Cobian Backup 11";
	/*/echo extract_unit($details["subject"], "(", ")");*/
	$items = array("** Respaldo terminado para la tarea ");
	$aux=21;
	$found=1;
}
/*///////////////////////////////////*/
$pos = strpos($cliente,"Cobian Backup 11");
if($pos !== false and @$very!=9) {
	$version= "Cobian Backup 11";
	$items = array("** Respaldo terminado para la tarea ");
	$aux=21;
	$found=1;
}

	$Equipo=extract_unit($details["subject"], "(", ")");

/************* Para acronis ******************************/
//echo "<br>".$details["subject"]."<br>";

$pos = strpos($details["subject"],"[ABR11.5]");
if($pos !== false) {
	$version= "Acronis 11.5";
	$ClienteEquipo=extract_unit($details["subject"], "(", ")");
	$items = array("Tarea '");
	$aux=21;
	$found=2;
}
/* Ingles */
$pos = strpos($details["subject"],"Task");
if($pos !== false) {
	$version= "Acronis 11.5";
	//$porciones = explode("machine ", $details["subject"]);
 	//$Equipo=$porciones[1];
	$items = array("Task '");
	$aux=21;
	$found=3;
}
/* Español */
$pos = strpos($cliente,"[ABR11.5]");
if($pos !== false) {
	$version= "Acronis 11.5";
	$items = array("Tarea '");
	$aux=21;
	$found=2;
}
/********** Para MCC***********************/
 $pos = strpos($cliente,"[MCC]");
if($pos !== false) {
	$version= "MCC BK";
	$items = array("Tarea '");
	$aux=21;
	$found=4;
	//echo "encontré mcc<br>";
}
 $pos = strpos($details["subject"],"[MCC]");
if($pos !== false) {
	$version= "MCC BK";
	$Equipo=extract_unit($details["subject"], "(", ")");
	$items = array("Tarea '");
	$aux=21;
	$found=4;
	//echo "encontré mcc<br>";
}



/*///////////////////////////////////////////////////*/
   $appearsCount = 0;
   $Count=0;

if ($found===4) {   
/////////////////// MCC //////////////////////
	$NombreCliente=explode("-", $Equipo);
	$buscar=$NombreCliente[0];
	$EquipoUsuario=$NombreCliente[1];


if ($buscar!='') { 
$sql_cliente="SELECT * FROM  `clientes` WHERE  `empresa` LIKE  '%".$buscar."%'";

	/*echo $sql_cliente."<br>";*/
	$con_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sql_cliente);
	if ($row=mysqli_fetch_array($con_cliente)) {
		$clientenom=$row['nombre']." ".$row['apellido'];
		$codcliente=$row['codcliente'];
	}	
		$criterio="";
		
		 /*echo $message."<br>------------------<p>";*/ 
$pos = strpos($message,"-");
$Fecha = trim(substr($message, $pos-2,10));

		 
$FechaAux = cambiaf_barra($Fecha);

$FechaAux = cambiaf_a_mysql($FechaAux);
		 
$pos = strpos($message,"'Copia de seguridad diaria'");
		
$rest = substr($message, $pos);

$rest = str_replace("'",'|',$rest);
$Detalle=explode("|", $rest);
	
	$equipo=@explode(".", $Detalle[3])[0];

$pos = strpos($message,"530");
if ($pos !== false) {
 	$Errores=1;
 	$Procesados=0;
 	$Respaldados=0;
}
$pos = strpos($message,"230");
if ($pos !== false) {
 	$Errores=0;
 	$Procesados=1;
 	$Respaldados=1;
}

//Archivos Respaldados:

$Tamano=extract_unit($message, "Tamaño:", "/");

 	$Tarea=$Detalle[1];
		
	//$message=str_replace("'", "|", $message);
	$message=((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $message) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

	if ($codcliente>0){
	$con_check=0;	
	$check="SELECT * FROM `respaldospc` WHERE `fecha` = '".$FechaAux."' AND `tarea` LIKE '".$Tarea."' AND `errores` like '".$Errores."'
	AND `procesados` ='".$Procesados."' AND `respaldados` ='".$Respaldados."' AND `codcliente` ='".$codcliente."' AND
	`usuario` LIKE '".$EquipoUsuario."'";	
		
	//echo $check."<br>";
	$con_check=mysqli_query($GLOBALS["___mysqli_ston"], $check);
	
		if (mysqli_num_rows($con_check) ==0){	
		mysqli_query($GLOBALS["___mysqli_ston"], "BEGIN");

		$sql="INSERT INTO `respaldospc` 
		(`codrespaldos`, `fecha`, `message`, `tarea`, `errores`, `procesados`, `respaldados`, `tamano`, `codcliente`, `usuario`, `version`)
		 VALUES (NULL, '".$FechaAux."', '".$message."', '".$Tarea."', '".$Errores."', '".$Procesados."', '".$Respaldados."', '".$Tamano."', '".$codcliente."',
		  '".$EquipoUsuario."', '".$version."')";
		 //echo "+++MCC ".$sql."<br><p>";
				$cons=mysqli_query($GLOBALS["___mysqli_ston"], $sql);
				if ($cons == false){
					$Falla.=((is_object($conectar)) ? mysqli_error($conectar) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
					echo "Error al guardar datos MCC ". $Falla."<br>";
					mysqli_query($GLOBALS["___mysqli_ston"], "ROLLBACK");
					$save="No";
				} else {
					mysqli_query($GLOBALS["___mysqli_ston"], "COMMIT");		
					$save="Si";	
					$MccCount++;
				}
		} else {
		$save="existe";	
		}
	}


	}
	$save="No";
	$Tam="";
	$Errores="";
	$Tamano="";
	$Fecha="";
	$Tarea="";
	$Respaldados="";
	$Procesados="";
	$version="";
	$clientenom="";		
	$codcliente="";		
		    $appearsCount = 0;   
		    $pos=0;
		    $stopI=0;
		    $startI=0;
		    $Falla="";
		    $codcliente='';
		    $message='';
/****************** Fin MCC ******************/
   
   
} elseif ($found===3) {
/////////////////// Acronis ingles /////////////////////
$NombreCliente=explode("-", $ClienteEquipo);
$buscar=$NombreCliente[0];
$EquipoUsuario=$NombreCliente[1];
$EquipoUsuario = str_replace("'",'',$EquipoUsuario);

if ($buscar!='') { 
$sql_cliente="SELECT * FROM  `clientes` WHERE  `empresa` LIKE  '%".$buscar."%'";

	//echo "<br><p>". $sql_cliente."<br>";
	$con_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sql_cliente);
	if ($row=mysqli_fetch_array($con_cliente)) {
		$clientenom=$row['nombre']." ".$row['apellido'];
		$codcliente=$row['codcliente'];
	}	
		$criterio="";
		
		//echo $EquipoUsuario."<br><p>";
		 $message."<br>------------------<p>"; 
$pos = strpos($message,"/");
//echo "<br>";
 $Fecha = trim(substr($message, $pos-2,10));
//echo "<br>";		 
		 
if (strpos($EquipoUsuario, "2") > 0 and strpos($message, "PM") > 0 ) {
$FechaAux = cambiaf_orden($Fecha);
} else {		 
$FechaAux = $Fecha;
}

//echo "Pos fecha " . strpos($message, "PM") . "<br>";
$FechaAux = cambiaf_a_mysql($FechaAux);
//echo "<br><p>";

$pos = strpos($message,'Command ');
$rest = substr($message, $pos);
$pos1 = strpos($rest,'\'\'')+$pos;
$Tarea = substr($message, $pos, $pos1-$pos);
$Tarea = str_replace("'",'',$Tarea);
$pose=strpos($message, 'Task ');

$Errores=substr($message,$pose);
$pose1=strpos($Errores, '.');


$Errores=substr($Errores,$pose1-$pose);
$Errores = str_replace("'",'',$Errores);

//echo "<br><p>";	
	//$message=str_replace("'", "|", $message);
	$message=((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $message) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

	if ($codcliente>0 and $FechaAux!="0000-00-00"){
	$con_check=0;	
	$check="SELECT * FROM `respaldospc` WHERE `fecha` = '".$FechaAux."' AND `fecha` != '0000-00-00' AND `tarea` LIKE '".$Tarea."' AND `errores` like '".$Errores."'
	AND `procesados` ='".$Procesados."' AND `respaldados` ='".$Respaldados."' AND `codcliente` ='".$codcliente."' AND
	`usuario` LIKE '".$EquipoUsuario."'";	
		
	//echo "<br><p>".$check;
	//echo "<br><p>";
	$con_check=mysqli_query($GLOBALS["___mysqli_ston"], $check);
	
	//echo "<br>encontrado ". mysql_num_rows($con_check)."<br><p>";
	
		if (mysqli_num_rows($con_check)==0){	
		mysqli_query($GLOBALS["___mysqli_ston"], "BEGIN");

		$sql="INSERT INTO `respaldospc` 
		(`codrespaldos`, `fecha`, `message`, `tarea`, `errores`, `procesados`, `respaldados`, `tamano`, `codcliente`, `usuario`, `version`)
		 VALUES (NULL, '".$FechaAux."', '".$message."', '".$Tarea."', '".$Errores."', '".$Procesados."', '".$Respaldados."', '".$Tamano."', '".$codcliente."',
		  '".$EquipoUsuario."', '".$version."')";
		//echo "+++Acronis  ".$sql."<br><p>";
		
				$cons=mysqli_query($GLOBALS["___mysqli_ston"], $sql);
				if ($cons == false){
					$Falla.=((is_object($conectar)) ? mysqli_error($conectar) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
					echo "Error al guardar datos Acronis ". $Falla."<br>";
					mysqli_query($GLOBALS["___mysqli_ston"], "ROLLBACK");
					$save="No";
				} else {
					mysqli_query($GLOBALS["___mysqli_ston"], "COMMIT");		
					$save="Si";	
					$AcroniCount++;
				}
				
		} else {
		$save="existe";	
		}
	}


	}
	$save="No";
	$Tam="";
	$Errores="";
	$Tamano="";
	$Fecha="";
	$Tarea="";

	$Procesados="";
	$version="";
	$clientenom="";		
	$codcliente="";		
		    $appearsCount = 0;   
		    $pos=0;
		    $stopI=0;
		    $startI=0;
		    $Falla="";
		    $codcliente='';
		    $message='';
		    $FechaAux='';
/****************** Fin Acronis ******************/
} elseif ($found===2) {
/////////////////// Acronis español /////////////////////
$NombreCliente=explode("-", $Equipo);
$buscar=$NombreCliente[0];
$EquipoUsuario=@$NombreCliente[1];


if ($buscar!='') { 
$sql_cliente="SELECT * FROM  `clientes` WHERE  `empresa` LIKE  '%".$buscar."%'";

	//echo $sql_cliente."<br>";
	$con_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sql_cliente);
	if ($row=mysqli_fetch_array($con_cliente)) {
		$clientenom=$row['nombre']." ".$row['apellido'];
		$codcliente=$row['codcliente'];
	}	
		$criterio="";
		
		//echo $EquipoUsuario."<br><p>";
		// echo $message."<br>------------------<p>"; 
$pos = strpos($message,"/");
//echo "<br>";
 $Fecha = trim(substr($message, $pos-2,10));
//echo "<br>";		 
		 
if (strpos($EquipoUsuario, "2") > 0 and strpos($message, "PM") > 0 ) {
$FechaAux = cambiaf_orden($Fecha);
} else {		 
$FechaAux = $Fecha;
}


//echo "Pos fecha " . strpos($message, "PM") . "<br>";
$FechaAux = cambiaf_a_mysql($FechaAux);
//echo "<br><p>";

$pos = strpos($message,'"Copia');
$rest = substr($message, $pos+1);

$pos1 = strpos($rest,'"')+$pos;

$Tarea = substr($message, $pos+1, $pos1-$pos);



$pose=strpos($message, 'Tarea ');

$Errores=substr($message,$pose);
$pose1=strpos($Errores, '.');


$Errores=substr($Errores,$pose1-$pose);
$Errores = str_replace("'",'',$Errores);

$Respaldados='';
$Procesados='';
if(strpos($message,'Necesita interacción')!==FALSE) {
$FechaAux=date("Y-m-d", $details["udate"]);
$largo_message=strlen($message);
$pos_punto=strpos($message,'.');
$Tarea=substr($message,0,$pos_punto);
$pos_detalle=strpos($message, "Detalles:");
$Errores=substr($message,$pos_detalle+9,$largo_message-$pos_detalle-9);
$Procesados=0;
$Respaldados=0;
$Tamano='';
}
		
	//$message=str_replace("'", "|", $message);
	$message=((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $message) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

	if ($codcliente>0 and $FechaAux!="0000-00-00"){
	$con_check=0;	
	$check="SELECT * FROM `respaldospc` WHERE `fecha` = '".$FechaAux."' AND `fecha` != '0000-00-00' AND `tarea` LIKE '".$Tarea."' AND `errores` like '".$Errores."'
	AND `procesados` ='".$Procesados."' AND `respaldados` ='".$Respaldados."' AND `codcliente` ='".$codcliente."' AND
	`usuario` LIKE '".$EquipoUsuario."'";	
		
	//echo "<br><p>".$check;
	$con_check=mysqli_query($GLOBALS["___mysqli_ston"], $check);
	
	//echo "<br>encontrado ". mysql_num_rows($con_check)."<br><p>";
	
		if (mysqli_num_rows($con_check)==0){	
		mysqli_query($GLOBALS["___mysqli_ston"], "BEGIN");

		$sql="INSERT INTO `respaldospc` 
		(`codrespaldos`, `fecha`, `message`, `tarea`, `errores`, `procesados`, `respaldados`, `tamano`, `codcliente`, `usuario`, `version`)
		 VALUES (NULL, '".$FechaAux."', '".$message."', '".$Tarea."', '".$Errores."', '".$Procesados."', '".$Respaldados."', '".$Tamano."', '".$codcliente."',
		  '".$EquipoUsuario."', '".$version."')";
		 //echo "+++Acronis  ".$sql."<br>";
		
				$cons=mysqli_query($GLOBALS["___mysqli_ston"], $sql);
				if ($cons == false){
					$Falla.=((is_object($conectar)) ? mysqli_error($conectar) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
					echo "Error al guardar datos Acronis ". $Falla."<br>";
					mysqli_query($GLOBALS["___mysqli_ston"], "ROLLBACK");
					$save="No";
				} else {
					mysqli_query($GLOBALS["___mysqli_ston"], "COMMIT");		
					$save="Si";	
					$AcroniCount++;
				}
				
		} else {
		$save="existe";	
		}
	}


	}
	$save="No";
	$Tam="";
	$Errores="";
	$Tamano="";
	$Fecha="";
	$Tarea="";
	$Respaldados="";
	$Procesados="";
	$version="";
	$clientenom="";		
	$codcliente="";		
		    $appearsCount = 0;   
		    $pos=0;
		    $stopI=0;
		    $startI=0;
		    $Falla="";
		    $codcliente='';
		    $message='';
		    $FechaAux='';
/****************** Fin Acronis ******************/
/****************** Empieza Cobian ***************/
} elseif ($found===1) {
	
		//echo $message."<br>--------------------<p>"; 	 		 
	
	
 $NombreCliente=explode("-", $cliente);
 $buscar=$NombreCliente[0];

 $EquipoUsuario=@$NombreCliente[1];

		//echo "Proceso Cobian Bakcup 0.<br>";		


if ($buscar!='') { 
 $sql_cliente="SELECT * FROM  `clientes` WHERE  `empresa` LIKE  '%".$buscar."%'";
//echo "<p>";
	$con_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sql_cliente);
	if ($row=mysqli_fetch_array($con_cliente)) {
		$clientenom=$row['nombre']." ".$row['apellido'];
		$codcliente=$row['codcliente'];
	}	
		$criterio="";
		
		
		foreach($items as $item)
		{
		$string=$message;
		 $t_message=$message;
	    $Count=0;
	    $appearsCount += substr_count($string, $item);
		/*/ echo "<br>--$item aparece $appearsCount veces<br>";*/
		$fin=strlen($string);
		$NuevaCadena=$string;	 
		 
		 
			$startIni=0;
			for ($Count=1;$Count<=$appearsCount;$Count++) {
				$xz++;
				$save="No";
		//echo "Proceso Cobian Bakcup.<br>";		
				
if (@$verifico==9) {
	/* Comienzo búsqueda para cobian backup 9 */
			/*Extraigo el texto a partir de donde encontre la cadena*/				
			/*para ello averiguo el largo total del string y le resto la posición inicial de la cadena*/				
			$pos_ini=strpos($NuevaCadena, $item);
			$largoItem=strlen($item);
			$NuevaCadena=substr($NuevaCadena, $pos_ini+$largoItem, $fin-$pos_ini-$largoItem);
			$fin=strlen($NuevaCadena);
			/*Recorro la nueva cadena hasta encontrar \ */
			$pos_fecha=strpos($NuevaCadena, '/');
			/*Obtengo la fecha*/
			$fecha_nueva=substr($NuevaCadena, $pos_fecha-2,10);
			$FechaAux=cambiaf_a_mysql($fecha_nueva);

			 $Tarea=extract_unit($NuevaCadena, "\"", "\"");

			$punto=strpos($NuevaCadena,'".');
			$parentesis=strpos($NuevaCadena,")");
			$Procesados=substr($NuevaCadena, $punto+2, $parentesis-$punto);
			$pos_control=strpos($NuevaCadena, $item);
			$pos_terminado=strpos($NuevaCadena, "terminado.");

			$largo_terminado=strlen("terminado.");

			if( $pos_terminado < $pos_control or $pos_control=='' ) {
				$CadenaFinal=substr($NuevaCadena, $pos_terminado+$largo_terminado, $fin- $pos_terminado-$largo_terminado);
				$parentesis=strpos($CadenaFinal,")");
				$Respaldados=substr($CadenaFinal, 0, $parentesis+1);
			}
	/*Fin de seccion para Cobian Backup 9*/
} else {				
				
			/*/echo "<br>-> ";*/
			$fin=strlen($string);
			$stop="*"; 
			$startI = strpos($string, $item); 
			$stopI = strpos($string, $stop, $startI+4); 
			
			if ($stopI > $startI) 
			   $vero=substr($string, $startI-$aux, $stopI - $startI+$aux);
				$Fecha=extract_unit($vero, "", " *");
				$FechaAux=trim(substr($Fecha, 0,10));
				
				$Fecha= cambiaf_a_normal(substr($Fecha, 0,10));
				$Tarea= extract_unit($vero, "\"", "\"");
	
				$Errores=extract_unit($vero, "Errores:", " ");
				
				if ($Errores==''){
				$Errores=extract_unit($vero, "Errores:", ".");
				}
				$Procesados =extract_unit($vero, "Ficheros procesados:", ".");
				$Respaldados=extract_unit($vero, "Ficheros respaldados:", ".");
				
				$Tam=extract_unit($vero, " Tamaño total: ", "bytes");
				$Tamano=$Tam." bytes";
				if ($Tam=='') {
				$Tam=extract_unit($vero, " Tamaño total: ", "KB");
				$Tamano=(int)$Tam." KB";
				}
				if ($Tam=='') {
				$Tam=extract_unit($vero, " Tamaño total: ", "MB");
				$Tamano=(int)$Tam." MB";
				}
				if ($Tam=='') {
				$Tam=extract_unit($vero, " Tamaño total: ", "GB");
				$Tamano=(int)$Tam." GB";
				}
				if ($Tam=='') {
				$Tamano=" 0 bytes ";
				}
			$string=substr($string, $stopI, $fin);   
			if ($Errores>0) {
				$Alerta="red";
			} else {
				$Alerta="";
			}
			/*echo $codcliente." --- Errores ".$Errores."<br>";*/
			if ($Errores==1){
		 /*echo $message."<br>--------------------<p>";*/ 				
				if(strpos($message, "ya existe")!==false){
					
				$Errores=0;
				}
			}
	}		
			
			
			
	/*//////////*****************************************/
	$Errores=(int)$Errores;
	$Procesados=(int)$Procesados;
	$Respaldados=(int)$Respaldados;

 if (strlen($t_message) > 200001) {
	$string=substr($string, 0, 200);
	$t_message=str_replace("'", "|", $string);
	$Errores="Mensaje generado por cobian demasiado largo";

 } else {
	$message=str_replace("'", "|", $t_message); 
 }
	$message=((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $message) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

	if ($codcliente>0 and $FechaAux!="0000-00-00"){
	$con_check=0;	
	/*echo "<br>".*/
	$check="SELECT * FROM `respaldospc` WHERE `fecha` = '".$FechaAux."' AND `tarea` LIKE '".$Tarea."' AND `errores` like '".$Errores."'
	AND `procesados` ='".$Procesados."' AND `respaldados` ='".$Respaldados."' AND `codcliente` ='".$codcliente."' AND
	`usuario` LIKE '".$EquipoUsuario."' ";	


	$con_check=mysqli_query($GLOBALS["___mysqli_ston"], $check);
		if (mysqli_num_rows($con_check) == false){	
		mysqli_query($GLOBALS["___mysqli_ston"], "BEGIN");
		$sql="INSERT INTO `respaldospc` 
		(`codrespaldos`, `fecha`, `message`, `tarea`, `errores`, `procesados`, `respaldados`, `tamano`, `codcliente`, `usuario`, `version`)
		 VALUES (NULL, '".$FechaAux."', '".$message."', '".$Tarea."', '".$Errores."', '".$Procesados."', '".$Respaldados."', '".$Tamano."', '".$codcliente."',
		  '".$EquipoUsuario."', '".$version."')";
		 //echo "+++Cobian ".$sql."<br><p>";
		 
				$cons=mysqli_query($GLOBALS["___mysqli_ston"], $sql);
				if ($cons == false){
					$Falla.=((is_object($conectar)) ? mysqli_error($conectar) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
					
					echo "Error al guardar datos Cobian ". $Falla." - " . $EquipoUsuario ." - Cliente ".$codcliente."<br>". $sql ."<br><p>";
				
					
					mysqli_query($GLOBALS["___mysqli_ston"], "ROLLBACK");
					$save="No";
				} else {
					mysqli_query($GLOBALS["___mysqli_ston"], "COMMIT");		
					$save="Si";	
					$CobianCount++;
				}
		} else {
		 $save="existe";	
		}
	}
	
	
	$save="No";
	$Tam="";
	$Errores="";
	$Tamano="";
	$Fecha="";
	$Tarea="";
	$Respaldados="";
	$Procesados="";
	/*/////////*****************************************/
			}
	$verifico='';
	$version="";
	$clientenom="";		
	$codcliente="";		
		    $appearsCount = 0;   
		    $pos=0;
		    $stopI=0;
		    $startI=0;
		    $Falla="";
		    $codcliente='';
		    
		}
		$very=0;
	}
	$message="";
}
/*
		if ($totalmessagecount>60 and $i<($totalmessagecount -57)){
			imap_delete($connection, $i);
		}
*/
	}

	imap_expunge($connection);
	imap_close($connection);

echo "Cantidad Respaldos MCC ". $MccCount. "<br>";
echo "Cantidad Respaldos Cobian ". $CobianCount. "<br>";
echo "Cantidad Respaldos Acronis ". $AcroniCount;

}


?>