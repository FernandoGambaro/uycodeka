<?php
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 

include ("../../conectar.php");

$codautofactura=$_GET["codautofacturatmp"];
$numlinea=$_GET["numlinea"];


$consulta = "DELETE FROM autofactulineatmp WHERE codautofactura ='".$codautofactura."' AND numlinea='".$numlinea."'";
$rs_consulta = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);


echo "<script>parent.location.href='frame_lineas.php?codautofacturatmp=".$codautofactura."';</script>";

?>