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

include ("../../conectar.php");
include "zklibrary.php";
iconv_set_encoding('internal_encoding', 'UTF-8');
mb_internal_encoding('UTF-8');

$codbiometric=$_POST['codbiometric'];

$slq="SELECT direccionip,udp_port FROM `biometric` WHERE `codbiometric`='".$codbiometric."'";
$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $slq);
$ip=mysqli_result($rs_busqueda, 0, "direccionip");
$udp_port=mysqli_result($rs_busqueda, 0, "udp_port");
 
$zk = new ZKLibrary($ip, $udp_port);
if ($zk->ping()>=0){
sleep(1);
$zk->setTimeout(60);
$zk->connect();
$zk->disableDevice();

$attendance =$zk->getAttendance();

            //var_dump($attendance );
       foreach($attendance as $key=>$attendancedata){
			$query_busqueda="SELECT count(*) as filas FROM `biometriclog` WHERE `codbiometric`='".$codbiometric."' AND `codusuarios`='".$attendancedata[1]."'	AND `datetime`='".$attendancedata[3] ."'";
			$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
			$filas=mysqli_result($rs_busqueda, 0, "filas");
			if($filas==0) {
			$sql="INSERT INTO `biometriclog` (`codlog`, `codbiometric`, `codusuarios`, `pin`, `status`, `datetime`) 
			VALUES (NULL, '".$codbiometric."',  '".$attendancedata[1] ."', '".$attendancedata[0]."', '".$attendancedata[2]."', '".$attendancedata[3] ."');";
			} 
			$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $sql);
		}

$zk->enableDevice();
$zk->disconnect();
}
$data = $zk->ping();	

    echo json_encode($data);
    flush();  

?>