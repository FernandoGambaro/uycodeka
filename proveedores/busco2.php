<?php
include ("../conectar.php"); 
    //get search term
    $searchTerm = $_GET['term'];
    
    //get matched data from skills table
	$sql="SELECT * FROM  `articulos` WHERE `codarticulo` LIKE '%".$searchTerm."%' or `referencia` LIKE '%".$searchTerm."%' or `descripcion` LIKE '%".$searchTerm."%'";    
	$sqlr=mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die ("Error al obtener datos del tipo estudio");

	while ($registro=mysqli_fetch_array($sqlr)){
			$arr['value'] =$registro['referencia'].'-'. substr($registro['descripcion'], 0, 40);
        	$arr['data'] = $registro['codfamilia'].'~'.$registro['codigobarras'].'~'.$registro['referencia'].'~'.addslashes(str_replace('"','&quot;',$registro['descripcion'])).'~'.
	       $registro['precio_compra'].'~'.$registro['codarticulo'].'~'.$registro['moneda'].'~ ~'.
	       $registro['descripcion'].'~'.$registro['comision'];
	       $data[] = $arr;
        
   }
    
    //return json data
    echo json_encode($data);
?>