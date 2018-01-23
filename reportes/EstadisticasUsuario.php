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
 

setlocale('LC_ALL', 'es_ES');

include ("../conectar.php");
include ("../funciones/fechas.php");
date_default_timezone_set('America/Montevideo');

$codusuario=@$_POST['cod'];
$tipoconsulta=@$_POST['tipo'];

//$tipoconsulta=1;
//$codusuario=21;

if(!isset($_POST['anio'])) {
	$anio=date("Y");
} else {
	$anio=$_POST['anio'];
}

$control=0;
//Si no se selecciona cliente reccoro todos

//Para un usuarios activos
	$query_bus="SELECT * FROM usuarios WHERE borrado=0 AND codusuarios=$codusuario";
	$rs_bus=mysqli_query($GLOBALS["___mysqli_ston"], $query_bus);
	$Descripcion=mysqli_result($rs_bus, 0, "nombre").' '. mysqli_result($rs_bus, 0, "apellido");

//Todos los clientes activos';
$query_busqu="SELECT * FROM clientes WHERE borrado=0 AND service=2";
$rs_busqu=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqu);
$contador=0;
$buscototal=mysqli_num_rows($rs_busqu);
	$pro="[ ";
/* Para cada cliente calculo la cantidad de horas y las sumo entre todos */
while ($contador < mysqli_num_rows($rs_busqu)) {
$codcliente=mysqli_result($rs_busqu, $contador, "codcliente");
	$pro.=' {"label": "'.mysqli_result($rs_busqu, $contador, "nombre").'", "data": [';	

	for($mes=1;$mes<=12;$mes++) {
	$totalhoras='00:00';
	$total=0;
	
	$fechaini=data_first_month_day($anio.'-'.$mes.'-01');
	$fechafin=data_last_month_day($anio.'-'.$mes.'-01');
		
		
		 	$query="SELECT * FROM horas WHERE codcliente=$codcliente AND codusuario=$codusuario AND borrado=0 AND fecha >= '$fechaini' AND fecha <='$fechafin' ";
		 	//echo "<br>";
			$rs=mysqli_query($GLOBALS["___mysqli_ston"], $query);
			$cont=0;
			if(mysqli_num_rows($rs)>0) {
					while ($cont < mysqli_num_rows($rs)) {
						$parcial=mysqli_result($rs, $cont, "horas").':00';
						if(mysqli_result($rs, $cont, "horas")!='0:00') {
						$totalhoras=SumaHoras($parcial,$totalhoras);
						}
						$cont++;
					}
					if($totalhoras!='00:00') {
					$total= str_replace(",",".",time2seconds($totalhoras)/60/60);
					}
			}
			
		   if($total<>0) {		   
					$pro.='['.$mes.','.$total;
					$control=1;
		   } else {
					$pro.='['.$mes.',0';
		   }
		   if($mes<12) {
		   	$pro.='],';
		   } else {
		   	$pro.=']';
		   }
		$total=0;	
		}
		   if($contador<($buscototal-1)) {
		   	$pro.=']},';
				$control=1;

		   } else {
		   	$pro.=']}';
		   }		
	$codcliente='';
	$contador++;
	$totalhoras='00:00';

}
$pro.=' ]';
    //$pro = '[ {"label": "foo", "data": [[1,300], [2,300], [3,300], [4,300], [5,300]]}, {"label": "bar", "data": [[1,800], [2,600], [3,400], [4,200], [5,0]]}, {"label": "baz", "data": [[1,100], [2,200], [3,300], [4,400], [5,500]]} ]';

			$arr['control'] = $control;
			$arr['valu']= $Descripcion;
        	$arr['data']= $pro;
        	$arr['tipo']= $tipoconsulta;
        	$arr['datoTxt']= $pro;
        	
	       //$da[] = $arr;
//var_dump($pro);

header("Content-Type: application/json");
echo json_encode($arr);
  
?>