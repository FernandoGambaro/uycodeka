<?php
include("../conexion.php");
$otro=$_GET['otro'];
$sql="";
// PHP5 Implementation - uses MySQLi.// mysqli('localhost', 'yourUsername', 'yourPassword', 'yourDatabase');
//header('Content-Type: text/html; charset=UTF-8'); //header('Content-Type: text/html; charset=iso-8859-1');

  if(isset($_POST['queryString'])) { 
    /*/ establecemos que el mapa de caracteres será UTF8 */ 
    @mysqli_query($GLOBALS["___mysqli_ston"], "SET NAMES 'utf8'");  

		$queryString=trim($_POST['queryString']);
    
      
    /*/ ejecutamos nuestra consulta a la base de datos*/
    if($otro=='Aplancuentac' or $otro=='Aplancuentav') {
    	if(is_numeric($queryString)) {
		$sql="SELECT * FROM  `plandecuentas` WHERE borrado=0 AND `codplan` >= '".$queryString."'  LIMIT 0 , 8";
		} else {
		$sql="SELECT * FROM  `plandecuentas` WHERE borrado=0 AND `nombre` LIKE '%".$queryString."%' LIMIT 0 , 8";
		}
    }  
	$sqlr=mysqli_query( $conectar, $sql) or die ("Error al obtener datos Plan de cuentas");
	
       
    if ( $sqlr )  
    { 
		  while ($registro=mysqli_fetch_array($sqlr))
        {  
               if ($otro=='Aplancuentac' or $otro=='Aplancuentav') {
	       echo '<li onClick="otro(\''.$registro['codplan'].'-'.$registro['nombre'].'\'); ">'.$registro['codplan'].' - '.$registro['nombre'].' </li>';               }
        }  
    }  
    // cerramos la conexión a la base de datos (no importa si la abrimos en modo persistente)  
    //mysqli_close( $conectar );
    
}

?>