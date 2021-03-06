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
	echo "<script>location.href='../index.php'; </script>";
   //header("Location:../index.php");	

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

setlocale('LC_ALL', 'es_ES');
header('Content-Type: text/html; charset=UTF-8');
include ("../conectar.php");
include ("../funciones/fechas.php");
date_default_timezone_set('America/Montevideo');

$codusuario=isset($_POST["codusuario"]) ? $_POST["codusuario"] : "0";

$tipoconsulta=@$_POST['tipo'];

$tipoconsulta=1;
//$codusuario=21;


if(!isset($_POST['anio'])) {
	$anio=date("Y");
} else {
	$anio=$_POST['anio'];
}

if(!isset($_POST['mes'])) {
	$mes=date("m");
} else {
	$mes=$_POST['mes'];
}
$control=0;
$total=0;
$horasTotales='00:00';
 $pro="[ ";	
//for($mes=1;$mes<=12;$mes++) {
	$fechaini=data_first_month_day($anio.'-'.$mes.'-01');
	$fechafin=data_last_month_day($anio.'-'.$mes.'-01');

//Para un usuarios activos
	$query_bus="SELECT * FROM usuarios WHERE borrado=0 AND codusuarios=$codusuario";
	$rs_bus=mysqli_query($GLOBALS["___mysqli_ston"], $query_bus);
	$usuario=mysqli_result($rs_bus, 0, "nombre").' '. mysqli_result($rs_bus, 0, "apellido");
	
	$conteo=0;
	
	/*Para todos los cliente*/
	$query_busqu="SELECT * FROM clientes WHERE borrado=0";
	$rs_busqu=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqu);


while ($conteo < mysqli_num_rows($rs_busqu)) {
	$codcliente=mysqli_result($rs_busqu, $conteo, "codcliente");
	$Descripcion=mysqli_result($rs_busqu, $conteo, "nombre")." ".mysqli_result($rs_busqu, $conteo, "apellido");
	if(mysqli_result($rs_busqu, 0, "empresa")!='') {
	$Descripcion=mysqli_result($rs_busqu, $conteo, "empresa");
	}
	
	 	$query="SELECT * FROM horas WHERE codcliente=$codcliente AND codusuario=$codusuario AND borrado=0 AND fecha >= '$fechaini' AND fecha <='$fechafin' ";
	 	//echo "<br>";
		$rs=mysqli_query($GLOBALS["___mysqli_ston"], $query);
		$cont=0;
		if(mysqli_num_rows($rs)>0) {
				while ($cont < mysqli_num_rows($rs)) {
					$parcial=mysqli_result($rs, $cont, "horas").':00';
					if(mysqli_result($rs, $cont, "horas")!='0:00') {
					$totalhoras=SumaHoras($parcial,$totalhoras);
					$horasTotales=SumaHoras($parcial,$horasTotales);
					}
					$cont++;
				}
				if($totalhoras!='00:00') {
				$total= str_replace(",",".",time2seconds($totalhoras)/60/60);
				}

		   if($total<>0) {		   
					$pro.='{ "label": "'.$Descripcion.': '.$total.'", "data": ['.$total.'] },';
					$control=1;
		   } 
		   /*
		   else {
					$pro.='{ "label": "'.$Descripcion.'", "data": [0]},';
		   }*/
		$total=0;
		$totalhoras='00:00';
   	}

$conteo++;
}
//}


$pro.=" ]";
			$arr['control'] = $control;
			$arr['valu'] = $Descripcion;
        	$arr['data']= str_replace("}, ]", "} ]", $pro);
        	$arr['mes']= $mes;
        	$arr['mesTxt']= "Horas realizadas en ".genMonth_Text($mes)." - ".$anio.' Total: '.str_replace(",",".",time2seconds($horasTotales)/60/60);
        	
	       //$da[] = $arr;
//var_dump($pro);

header("Contnt-Type: application/json");
echo json_encode($arr);
  
?>