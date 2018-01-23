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
    //get search term
    $searchTerm = $_GET['term'];
    $codcliente= $_GET['codcliente'];
    if($codcliente<>'') {
    	$where = " and `codcliente`='".$codcliente."'";
    }
    //get matched data from skills table
	$sql="SELECT * FROM `proyectos` WHERE `descripcion` LIKE '%".$searchTerm."%' ".$where;     
	$sqlr=mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die ("Error al obtener datos de proyectos");

	while ($registro=mysqli_fetch_array($sqlr)){
			$arr['value'] = substr($registro['descripcion'], 0, 45);
        	$arr['data'] = $registro['codproyectos'].'~'.$registro['descripcion'];
	      $data[] = $arr;
   }
    
    //return json data
    echo json_encode($data);
    flush();    
    
?>