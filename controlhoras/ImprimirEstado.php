<?php
include ("../conectar.php"); 
include ("../funciones/fechas.php");

setlocale (LC_ALL, 'et_EE.ISO-8859-1');
mysqli_query($GLOBALS["___mysqli_ston"], "SET NAMES utf8"); //Soluciona el tema de las ñ y los tildes
 

?>
<html>
	<head>
	   <title>Pendientes de registrar horas mes de
<?php 
/* LEER VARIABLES DE $_POST */
$where='';
$usuario=@$_GET['codusuario'];
if($usuario <> '') {
	$where=" AND (codusuarios='$usuario' AND (estado=0 or estado=NULL))";
}
$where.=" AND (tratamiento !=2 AND (estado=0 or estado=NULL)) ";
$fechainicio=explota($_GET['fechainicio']);

$ano=date("Y", strtotime($fechainicio));
$mes=date("m", strtotime($fechainicio));
$nombremes=array(
    1=>"Enero",
    2=>"Febrero",
    3=>"Marzo",
    4=>"Abril",
    5=>"Mayo",
    6=>"Junio",
    7=>"Julio",
    8=>"Agosto",
    9=>"Septiembre",
    10=>"Octubre",
    11=>"Noviembre",
    12=>"Diciembre"
);

echo $nombremes[$mes].", ".date("Y", strtotime($fechainicio));

 ?>
	   </title> 
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
</head> 
<body> 
<table class="fuente8" width="70%" cellspacing=2 cellpadding=3 border=0>
<tr class="cabeceraTabla">
<td colspan="2" align="center">Usuarios que tienen horas pendientes de registrar para el mes de
<?php 
/* LEER VARIABLES DE $_POST */


$fechainicio=explota($_GET['fechainicio']);
$ano=date("Y", strtotime($fechainicio));
$mes=(int)date("m", strtotime($fechainicio));
$nombremes=array(
    1=>"Enero",
    2=>"Febrero",
    3=>"Marzo",
    4=>"Abril",
    5=>"Mayo",
    6=>"Junio",
    7=>"Julio",
    8=>"Agosto",
    9=>"Septiembre",
    10=>"Octubre",
    11=>"Noviembre",
    12=>"Diciembre"
);

echo $nombremes[$mes].", ".date("Y", strtotime($fechainicio));

 ?>
</td>
</tr>
<tr class="cabeceraTabla">
  <td width="20%"> D&iacute;a</td>
  <td> Usuario/s</td>
</tr>
<?php
// Genera las filas necesarias para cada mes

if ($mes==2){
    $numerodedias=28;
}
elseif (($mes==4)or ($mes==6) or ($mes==9) or ($mes==11)){
    $numerodedias=30;
}
else{
    $numerodedias=31;
}

$dia=array(
    0=>"Domingo",
    1=>"Lunes",
    2=>"Martes",
    3=>"Mi&eacute;rcoles",
    4=>"Jueves",
    5=>"Viernes",
    6=>"Sábado",
);

$i=1;

while($i<=$numerodedias){
	
$w= date("w",mktime(0,0,0,$mes,$i,$ano));	
$fecha=$ano."-".$mes."-".$i;

	if($w!=0 and $w!=6) {
		
	if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }
$ok=1;

		$query_bus="SELECT * FROM usuarios WHERE  `borrado` = '0' ".$where;

		$rs_bus=mysqli_query($GLOBALS["___mysqli_ston"], $query_bus);
		$contador=0;
			while ($contador < mysqli_num_rows($rs_bus)) {
			$codusuario=mysqli_result($rs_bus, $contador, "codusuarios");
		    
			$query="SELECT * FROM horas WHERE borrado=0 AND fecha = '". $fecha ."' and codusuario='".$codusuario."' group by codusuario";				
			$rs=mysqli_query($GLOBALS["___mysqli_ston"], $query);
			if (mysqli_num_rows($rs)<=0){
				if($ok==1) {
				?>
					<tr class="<?php echo $fondolinea?>"><td>
				<?php
    			echo $dia[$w]." -> ". $i."</td>        				
    				<td>";
    				$ok = 0;
				}				
				echo mysqli_result($rs_bus, $contador, "nombre")." ". mysqli_result($rs_bus, $contador, "apellido")."&nbsp;-&nbsp;";
			}
			$contador++;
    		}    
    echo "
    </td></tr>";
    
 	}

    $i++;
}

?>
</table>
<br> 
</body> 
</html>