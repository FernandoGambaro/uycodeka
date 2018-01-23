<?php
if(isset($_GET['id']))
{
$id=$_GET['id'];
$default=$_GET['default'];

include("../conexion.php");
/*/ Configurar las dos lineas siguientes*/

$query = "SELECT * FROM `foto` where `oid`='$id'";

$resulta = mysqli_query($GLOBALS["___mysqli_ston"], $query) or die("Error");

  if($result=mysqli_fetch_array($resulta)){		
    header("Content-type:".$result['fototype']);
    header('Content-Disposition: inline; filename="'.$result['fotoname'].'"');
    echo $result['fotocontent'];			
  } else {	
		$query = "SELECT * FROM `foto` where `oid`='$default'";
		$resulta = mysqli_query($GLOBALS["___mysqli_ston"], $query) or die("Error");
		  if($result=mysqli_fetch_array($resulta)){		
		    header("Content-type:".$result['fototype']);
		    header('Content-Disposition: inline; filename="'.$result['fotoname'].'"');
		    echo $result['fotocontent'];			
		  }  
  }

}

?>