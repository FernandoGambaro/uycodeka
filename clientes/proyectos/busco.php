<?php
include ("../../conectar.php");
    $otro=$_GET['otro'];
$sql="";
// PHP5 Implementation - uses MySQLi.// mysqli('localhost', 'yourUsername', 'yourPassword', 'yourDatabase');
header('Content-Type: text/html; charset=UTF-8'); //header('Content-Type: text/html; charset=iso-8859-1');

  if(isset($_POST['queryString'])) { 
    /*/ establecemos que el mapa de caracteres será UTF8 */ 
    @mysqli_query($GLOBALS["___mysqli_ston"], "SET NAMES 'utf8'");  

		$queryString=trim($_POST['queryString']);
    
      
    /*/ ejecutamos nuestra consulta a la base de datos*/
    if($otro=='codcliente') {
		$sql="SELECT * FROM  `clientes` WHERE borrado=0 AND ( `nombre` LIKE '%".$queryString."%' or `apellido` LIKE '%".$queryString."%' or `empresa` LIKE '%".$queryString."%')  LIMIT 0 , 8";
    } elseif ($otro=='codusuario') {
		$sql="SELECT * FROM `usuarios` WHERE (`nombre` LIKE '%".$queryString."%' or `apellido` LIKE '%".$queryString."%') and
		`borrado` = '0' LIMIT 6";
    }  elseif ($otro=='codproyectos') {
		$sql="SELECT * FROM `proyectos` WHERE `descripcion` LIKE '%".$queryString."%' and `codcliente`='".$detalle."' LIMIT 6";
    } 
	$sqlr=mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die ("Error al obtener datos");
	
       
    if ( $sqlr )  
    { 
		  while ($registro=mysqli_fetch_array($sqlr))
        {  
               if ($otro=='codcliente') {
	       echo '<li onClick="otro(\''.$registro['codcliente'].'-'.$registro['nombre'].'\'); ">'.$registro['nombre'].' </li>';               } elseif ($otro=='codusuario') {
	       echo '<li onClick="otro(\''.$registro['codusuarios'].'-'.$registro['nombre'].' '.$registro['apellido'].'\'); ">'.$registro['nombre'].' '.$registro['apellido'].' </li>';               } elseif($otro=='codproyectos') {
	       echo '<li onClick="otro(\''.$registro['codproyectos'].'-'.$registro['descripcion'].'\'); ">'.$registro['descripcion'].' </li>';               }
        }  
    }  
    // cerramos la conexión a la base de datos (no importa si la abrimos en modo persistente)  
    //mysqli_close( $conectar );
    
}

?>