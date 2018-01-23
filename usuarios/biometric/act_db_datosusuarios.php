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

$codbiometric=1;

$slq="SELECT direccionip,udp_port FROM `biometric` WHERE `codbiometric`='".$codbiometric."'";
$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $slq);
$ip=mysqli_result($rs_busqueda, 0, "direccionip");
$udp_port=mysqli_result($rs_busqueda, 0, "udp_port");
 
$zk = new ZKLibrary($ip, $udp_port);
if ($zk->ping()>=0){
$zk->setTimeout(60,0);
$zk->connect();


	$zk->disableDevice();
	sleep(1);

	$all_user_info=$zk->getUser();
	/*Extraigo los datos del usuario seleccionado y lo almaceno en un array()*/
	
	foreach($all_user_info as $key=>$user){
	
				$query_busqueda="SELECT count(*) as filas FROM `biometricuser` WHERE `pin`='".$key."'";
				$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
				$filas=mysqli_result($rs_busqueda, 0, "filas");
					if($filas==0) {
						$sql="INSERT INTO `biometricuser` (`codbiometricuser`, `pin`, `codusuarios`, `name`, `card`, `role`, `password`) VALUES
						( null, '$user[0]', '$key',   '$user[1]', '$user[2]', '$user[3]', '$user[4]');";
					} else {
						$sql="UPDATE `biometricuser` SET  `name` = '". $user[1]."', `card` = '". $user[2]."', `role` = '".$user[3]."',  `password` = '". $user[4]."' 
						WHERE `biometricuser`.`pin` = '".$key."'";
					}
					$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $sql);
					if ($rs_operacion) {
						$sql_user="UPDATE `usuarios` SET `pin` = '".$key."', `role` = '".$user[3]."' WHERE `usuarios`.`codusuarios` = '".$key."';";
						$rs_bus=mysqli_query($GLOBALS["___mysqli_ston"], $sql_user);						 
					} 
			$sql='';
			$x=0;
			while($x<9){
				$template=$zk->getUserTemplate($key, $x);
				$query_busqueda="SELECT count(*) as filas FROM `biometricusertemplate` WHERE `pin`='".$key."' AND `fingerid`='".$x."'";
				$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
				$filas=mysqli_result($rs_busqueda, 0, "filas");
					if($filas==0 and $template[3]<>'') {
						array($template_size, $uid, $finger, $valid, $user_data);
						$sql="INSERT INTO `biometricusertemplate` (`codtemplate`, `size`, `pin`, `fingerid`, `valid`,  `template`) VALUES
						( null,  '".$template[0]."',  '".$template[1]."',  '".$x."', '".$template[3]."', '". mysql_escape_string($template[4])."');";
					} elseif($filas>0) {
						$sql="UPDATE `biometricusertemplate` SET  `size` = '". $template[0]."', `valid` = '". $template[3]."', `template` = '". mysql_escape_string($template[4])."' 
						WHERE `pin` = '".$key."' AND `fingerid`='". $x."'";
					}
					if($template[3]<>'') {
							$rs_opera=mysqli_query($GLOBALS["___mysqli_ston"], $sql);
							if ($rs_opera) { echo "Actualizacion exitosa"; } else { echo "<br>Un error";}
					}
					$x++;
			}
	}
	
$zk->enableDevice();
$zk->disconnect();
}
$data = $zk->ping();	

    echo json_encode($data);
    flush();  

?>