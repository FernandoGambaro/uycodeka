<?php
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 

include ("../conectar.php");

$codartiviaja=$_GET["codartiviajatmp"];
$numlinea=$_GET["numlinea"];

$consulta = "DELETE FROM artiviajalineatmp WHERE codartiviaja ='".$codartiviaja."' AND numlinea='".$numlinea."'";
$rs_consulta = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
echo "<script>parent.location.href='frame_lineas.php?codartiviajatmp=".$codartiviaja."';</script>";

?>