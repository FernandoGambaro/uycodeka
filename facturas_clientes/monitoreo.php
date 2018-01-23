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
 
include ("../conectar.php");

if(isset($_POST['rejillaDiag'])) {
$sql_datos="SELECT table_schema,table_name,update_time FROM information_schema.tables WHERE table_schema='uycodeka' AND table_name='facturas' AND update_time > (NOW() - INTERVAL 30 MINUTE_SECOND)";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
	if (mysqli_num_rows($rs_datos)>0 ) {
		echo "1";
	} else {
		echo '0';
	}
}
?>