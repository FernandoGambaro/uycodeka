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
 

date_default_timezone_set('America/Montevideo');

include ("../conectar.php");
include ("../funciones/fechas.php");

$mes='';
$total='';
$fechainicio = explota($_POST['fechainicio']);
$fechafin = explota($_POST['fechafin']);

$startTime =data_first_month_day(explota($_POST['fechainicio'])); 
$endTime = data_last_month_day(explota($_POST['fechafin'])); 

$fechainicial = new DateTime(explota($_POST['fechainicio']));
$fechafinal = new DateTime(explota($_POST['fechafin']));


$diferencia = $fechainicial->diff($fechafinal);
$meses = ( $diferencia->y * 12 ) + $diferencia->m;
if($meses==0) {
	$meses=1;
}


$sTime=$startTime;
 
setlocale (LC_ALL, 'et_EE.ISO-8859-1');

$datay[]='';
$datax=array();
$color=array();
$nam[]='';
$Pinto=array(
'', 'aliceblue','antiquewhite','lightsalmon','lightseagreen','aqua', 'bisque','lime','black','lightyellow','limegreen','linen','blue', 'lightskyblue','blanchedalmond','aquamarine','lightslategray','azure','lightsteelblue','beige', 'magenta','blueviolet','maroon','brown','mediumaquamarine','burlywood','mediumblue', 'coral','mediumslateblue','cornflowerblue','mediumspringgreen','cornsilk','mediumturquoise', 'cadetblue','mediumorchid','chartreuse','mediumpurple','chocolate','mediumseagreen', 'mediumvioletred','cyan','midnightblue','darkblue','mintcream','darkcyan','mistyrose', 'oldlace','darkmagenta','olive','darkolivegreen','olivedrab','darkorange','orange', 'darkgoldenrod','moccasin','darkgray','navajowhite','darkgreen','navy','darkkhaki', 'darkorchid','orangered','darkred','orchid','darksalmon','palegoldenrod','darkseagreen', 'papayawhip','darkviolet','peachpuff','deeppink','peru','deepskyblue','pink','dimgray', 'plum','dodgerblue','powderblue','firebrick','purple','floralwhite','red','forestgreen', 'palegreen','darkslateblue','paleturquoise','darkslategray','palevioletred','darkturquoise', 'rosybrown','fuchsia','royalblue','gainsboro','saddlebrown','ghostwhite','salmon','gold', 'honeydew','skyblue','hotpink','slateblue','indianred','slategray','indigo','snow','ivory', 'sandybrown','goldenrod','seagreen','gray','seashell','green','sienna','greenyellow','silver', 'springgreen','khaki','steelblue','lavender','tan','lavenderblush','teal','lawngreen','thistle', 'lightgoldenrodyellow','white','lightgreen','whitesmoke','lightgrey','yellow','lightpink','yellowgreen', 'lemonchiffon','tomato','lightblue','turquoise','lightcoral','violet','lightcyan','wheat');


	$tipo = array( 0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
	$moneda = array(1=>"\$", 2=>"U\$S");
	
$Iva_Compras=0;
$Iva_Ventas=0;
$Total_Compras=0;
$Total_Ventas=0;	
$Cant_Ventas=0;
$Cant_Compras=0;	
$horasTotales=0;

$x=0;
	 $sel_clientesmes="SELECT * FROM clientes WHERE borrado=0 AND service=2 order by codcliente ASC";
	$res_clientesmes=mysqli_query($GLOBALS["___mysqli_ston"], $sel_clientesmes);
	$contador_clientesmes=0;
	while ($contador_clientesmes < mysqli_num_rows($res_clientesmes)) {
	$codcliente=mysqli_result($res_clientesmes, $contador_clientesmes, "codcliente");

				if (mysqli_result($res_clientesmes, $contador_clientesmes, "empresa")!='') {
					$nombre= mysqli_result($res_clientesmes, $contador_clientesmes, "empresa");
					$nam=explode(" ",$nombre);
					@$nombre=$nam[0]. " ".$nam[1];
					
					} elseif (mysqli_result($res_clientesmes, $contador_clientesmes, "apellido")=='') {
						$nombre= mysqli_result($res_clientesmes, $contador_clientesmes, "nombre");
					$nam=explode(" ",$nombre);
					$nombre=$nam[0]. " ".$nam[1];
					} else {
						$nombre= mysqli_result($res_clientesmes, $contador_clientesmes, "nombre"). ' ' . mysqli_result($res_clientesmes, $contador_clientesmes, "apellido");
					$nam=explode(" ",$nombre);
					$nombre=$nam[0]. " ".$nam[1];
					}
			$datax[$x]=	mysqli_result($res_clientesmes, $contador_clientesmes, "horas")*$meses;

			$sel_resultado="SELECT * FROM service WHERE borrado=0 AND codcliente=". $codcliente. "	AND fecha >='".$startTime."' AND fecha <='".$endTime."' ";

			$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		   $contador=0;
		   while ($contador < mysqli_num_rows($res_resultado)) { 
				 $total= mysqli_result($res_resultado, $contador, "horas");
				 @$datay[$x]+=$total;
				 $horasTotales+=$total;		 
			$contador++;		
			}
			if ($total==0){
			$datay[$x]=0;
			}
			$total=0;
			$nombre=rtrim($nombre);
			$name[$x]=str_replace('', '&nbsp;',$nombre);
			$color[$x]=$Pinto[$x];
	$codcliente='';
	$contador_clientesmes++;
	$x++;
	}

//echo $cantindad."<br>";

function recorro($matriz){
//echo "ver array<br>";
	foreach($matriz as $key=>$value){
		if (is_array($value)){  
                        //si es un array sigo recorriendo
			echo '<br>Proyecto:'. $key.'<br> ';
			recorro($value);
		}else{  
		       //si es un elemento lo muestro
			echo $key.'=>'.$value.'<br>';
		}
	}
}

//recorro($datay);
//recorro($name);
//recorro($Pinto);


$x=$x-1;

$titulo='Horas asignadas/realizadas - clientes abonados';
$lateral=genMonth_Text(date("m",strtotime($startTime))).' de '. date("Y",strtotime($startTime));
$subtitulo="Horas totales ". $horasTotales;

if ($datay!='') {

require_once ('../jpgraph/jpgraph.php');
require_once ('../jpgraph/jpgraph_bar.php');


$graph = new Graph(600,600,'auto');    
$graph->SetScale("textlin");
$graph->SetShadow();
$graph->img->SetMargin(80,30,40,260);


$graph->yaxis->title->Set($lateral);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD,12);

$graph->title->Set($titulo);
$graph->title->SetFont(FF_FONT1,FS_BOLD,12);
$graph->subtitle->Set($subtitulo);
$graph->subtitle->SetFont(FF_FONT1,FS_ITALIC,10);

$graph->xaxis->SetTickLabels($name);
$graph->xaxis->SetTextLabelInterval(1);
$graph->xaxis->SetLabelAngle(60);

$bplot1 = new BarPlot($datay);
$bplot1->SetFillColor("orange");
$bplot1->SetLegend("Realizadas");

$bplot2 = new BarPlot($datax);
$bplot2->SetFillColor("brown");
$bplot2->SetLegend("Asignadas");

$graph->legend->SetShadow('gray@0.4',5);
$graph->legend->SetPos(0.1,0.1,'right','top');
$graph->legend->SetColumns(1);
$graph->legend->SetVColMargin(1);

$gbarplot = new GroupBarPlot(array($bplot2,$bplot1));
$gbarplot->SetWidth(0.7);
$graph->Add($gbarplot);

$graph->Stroke();


} else {
echo "Nada que mostrar";	
}

?>