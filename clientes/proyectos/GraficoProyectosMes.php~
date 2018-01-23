<?php // content="text/plain; charset=utf-8"

//require_once ('jpgraph/src/jpgraph.php');
//require_once ('jpgraph/src/jpgraph_log.php');
//require_once ('jpgraph/src/jpgraph_bar.php');


include("../../conexion.php");
require("../../funcionesvarias.php");
include ("../../funciones/fechas.php");

setlocale (LC_ALL, 'et_EE.ISO-8859-1');

$titulo=@$_GET['t'];

if (empty($_GET['anio'])) {
$anio='2012'; //date('Y');
} else {
$anio=$_GET['anio'];
}
if (empty($_GET['mes'])) {
$mes=date('m');
} else {
$mes=$_GET['mes'];
}

$SubTitle=mes($mes);
$primer_dia="".$anio."-".$mes."-01";
$ultimo_dia="".$anio."-".$mes."-".date('t');


$codcliente=$_GET["codcliente"];
$codusuario=$_GET["codusuario"];

$fechafin=$_GET["fechafin"];
$fechainicio=$_GET["fechainicio"];

$where="AND 1=1";
if ($codcliente <> "") { $wherec=" AND codcliente='".$codcliente."'"; }
if ($codusuario <> "") { $where.=" AND codusuario = '".$codusuario."' ";
	$query_bus="SELECT * FROM USUARIOS WHERE USUARIOID='".$codusuario."'";
	$rs_bus=mysqli_query($GLOBALS["___mysqli_ston"], $query_bus);
	$SubTitle.= " / ". mysqli_result($rs_bus, 0, "USUARIONOM").' '. mysqli_result($rs_bus, 0, "USUARIOAPE");
 }

if ($fechainicio <> "") { $where.=" AND fecha >= '".explota($fechainicio)."'"; }
if ($fechafin <> "") { $where.=" AND fecha <= '".explota($fechafin)."'"; }

// funcion que devuelve un array con los elementos de una hora
function parteHora( $hora )
{ 
$horaSplit = explode(":", $hora);
if( count($horaSplit) < 3 )
{
$horaSplit[2] = 0;
}
return $horaSplit;
}
// funcion que devuelve la suma de dos horas en formato horas:minutos:segundos
// Devuelve FALSE si se ha producido algun error
function SumaHoras( $time1, $time2 )
{
list($hour1, $min1, $sec1) = parteHora($time1);
list($hour2, $min2, $sec2) = parteHora($time2);
return date('H:i:s', mktime( $hour1 + $hour2, $min1 + $min2, $sec1 + $sec2));
} 

function time2seconds($time='00:00:00')
{
    list($hours, $mins, $secs) = explode(':', $time);
    return ($hours * 3600 ) + ($mins * 60 ) + $secs;
}

$Pinto=array('', 'aliceblue','lightsalmon','antiquewhite','lightseagreen','aqua','lightskyblue','aquamarine','lightslategray','azure','lightsteelblue','beige','lightyellow','bisque','lime','black','limegreen','blanchedalmond','linen','blue','magenta','blueviolet','maroon','brown','mediumaquamarine','burlywood','mediumblue','cadetblue','mediumorchid','chartreuse','mediumpurple','chocolate','mediumseagreen','coral','mediumslateblue','cornflowerblue','mediumspringgreen','cornsilk','mediumturquoise','mediumvioletred','cyan','midnightblue','darkblue','mintcream','darkcyan','mistyrose','darkgoldenrod','moccasin','darkgray','navajowhite','darkgreen','navy','darkkhaki','oldlace','darkmagenta','olive','darkolivegreen','olivedrab','darkorange','orange','darkorchid','orangered','darkred','orchid','darksalmon','palegoldenrod','darkseagreen','palegreen','darkslateblue','paleturquoise','darkslategray','palevioletred','darkturquoise','papayawhip','darkviolet','peachpuff','deeppink','peru','deepskyblue','pink','dimgray','plum','dodgerblue','powderblue','firebrick','purple','floralwhite','red','forestgreen','rosybrown','fuchsia','royalblue','gainsboro','saddlebrown','ghostwhite','salmon','gold','sandybrown','goldenrod','seagreen','gray','seashell','green','sienna','greenyellow','silver','honeydew','skyblue','hotpink','slateblue','indianred','slategray','indigo','snow','ivory','springgreen','khaki','steelblue','lavender','tan','lavenderblush','teal','lawngreen','thistle','lemonchiffon','tomato','lightblue','turquoise','lightcoral','violet','lightcyan','wheat','lightgoldenrodyellow','white','lightgreen','whitesmoke','lightgrey','yellow','lightpink','yellowgreen');


$y=0;
$tiempos=array();
$datay=array();
$Pinto=array();

		$query_busqu="SELECT * FROM clientes WHERE borrado=0 ".$wherec;
		$rs_busqu=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqu);
		$contador=0;
		$cod=0;
		while ($contador < mysqli_num_rows($rs_busqu)) {
			$codcliente=mysqli_result($rs_busqu, $contador, "codcliente");
			
				$query="SELECT * FROM horas WHERE codcliente=$codcliente AND borrado=0 ".$where;
				$rs=mysqli_query($GLOBALS["___mysqli_ston"], $query);
				$cont=0;
				$ini='00:00';
							
				while ($cont < mysqli_num_rows($rs)) {
					$tiempos[$codcliente][$cont]=mysqli_result($rs, $cont, "horas");
					$parcial=mysqli_result($rs, $cont, "horas");
					$ini=SumaHoras($ini,$parcial);
					$cont++;
				}
				if($ini!='00:00') {
				$nombre[$cod]=mysqli_result($rs_busqu, $contador, "nombre")." - ".$ini;
				$datay[$cod]= time2seconds($ini)/60;
				$color[$cod]=$Pinto[$cod];
				$cod++;
				}
			$contador++;
		}


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
/*
recorro($tiempos);

echo "Recorro Datay<br><br>";
recorro($datay);
echo "Recorro Nombre<br><br>";
recorro($nombre);
*/


function graficarBarra($datay,$datax,$Pinto, $SubTitle) {

require_once ('../../jpgraph/jpgraph.php');
require_once ('../../jpgraph/jpgraph_log.php');
require_once ('../../jpgraph/jpgraph_bar.php');


//$Pinto=array('', 'aliceblue','lightsalmon','antiquewhite','lightseagreen','aqua','lightskyblue','aquamarine','lightslategray','azure','lightsteelblue','beige','lightyellow','bisque','lime','black','limegreen','blanchedalmond','linen','blue','magenta','blueviolet','maroon','brown','mediumaquamarine','burlywood','mediumblue','cadetblue','mediumorchid','chartreuse','mediumpurple','chocolate','mediumseagreen','coral','mediumslateblue','cornflowerblue','mediumspringgreen','cornsilk','mediumturquoise','mediumvioletred','cyan','midnightblue','darkblue','mintcream','darkcyan','mistyrose','darkgoldenrod','moccasin','darkgray','navajowhite','darkgreen','navy','darkkhaki','oldlace','darkmagenta','olive','darkolivegreen','olivedrab','darkorange','orange','darkorchid','orangered','darkred','orchid','darksalmon','palegoldenrod','darkseagreen','palegreen','darkslateblue','paleturquoise','darkslategray','palevioletred','darkturquoise','papayawhip','darkviolet','peachpuff','deeppink','peru','deepskyblue','pink','dimgray','plum','dodgerblue','powderblue','firebrick','purple','floralwhite','red','forestgreen','rosybrown','fuchsia','royalblue','gainsboro','saddlebrown','ghostwhite','salmon','gold','sandybrown','goldenrod','seagreen','gray','seashell','green','sienna','greenyellow','silver','honeydew','skyblue','hotpink','slateblue','indianred','slategray','indigo','snow','ivory','springgreen','khaki','steelblue','lavender','tan','lavenderblush','teal','lawngreen','thistle','lemonchiffon','tomato','lightblue','turquoise','lightcoral','violet','lightcyan','wheat','lightgoldenrodyellow','white','lightgreen','whitesmoke','lightgrey','yellow','lightpink','yellowgreen');
	if (!empty($datay)) {
	// Create the graph. These two calls are always required
	$graph = new Graph(1000,550, "auto");
	$graph->SetMarginColor('white');
	$graph->SetScale("textlin");
	$graph->SetFrame(false); 
	// Add a drop shadow
	$graph->SetShadow();
	 
	// Adjust the margin a bit to make more room for titles
	$graph->SetMargin(60,50,20,220);
	
	// Show the gridlines
	$graph->ygrid->Show(true,true);
	$graph->xgrid->Show(true,false);
	// Create a bar pot
	$bplot = new BarPlot($datay);
	 
	// Adjust fill color
	$bplot->SetFillColor($Pinto);
	$bplot->SetWidth(.7);
	$graph->Add($bplot);
	
	// Setup the titles
	$graph->title->Set('Acumulados del mes para cada proyecto');
	$graph->subtitle->Set("--".$SubTitle."--");
	
	$graph->xaxis->title->Set('');
	$graph->yaxis->title->Set('minutos');
	
	$graph->xaxis->SetTickLabels($datax);
	$graph->xaxis->SetTextLabelInterval(1);
	$graph->xaxis->SetLabelAngle(90);
	 
	$graph->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	 
	// Display the graph
	$graph->Stroke();
	} else {
	echo "No hay datos para mostrar";
	
	}
}


function graficarTorta($datay,$datax,$Pinto, $SubTitle) {


include ("../../jpgraph/jpgraph.php");
include ("../../jpgraph/jpgraph_pie.php");
include ("../../jpgraph/jpgraph_pie3d.php");

$tmpx=array();
$tmpy=array();
$tmpname=array();
$x=1;
arsort($datay);
//echo "ver array<br>";
	foreach($datay as $key=>$value){
		       //si es un elemento lo muestro
		$tmpx[$x] = $datax[$key];
		$tmpy[$x]=$value;
		$tmpname[$x]=' (%.1f%%)';		   
//		echo $key.'=>'.$value.'<br>';
	$x++;
	}

		$datax=$tmpx;
		$datay=$tmpy;
		$name=$tmpname;

// Create the Pie Graph.
$graph = new PieGraph(1024,768);
$graph->SetAntiAliasing();
$graph->SetShadow();

// Set A title for the plot
$graph->title->Set($m);
$graph->title->SetFont(FF_VERDANA,FS_BOLD,12);
$graph->title->SetColor('black');
$graph->title->SetMargin(5);

$graph->subtitle->SetFont(FF_ARIAL, FS_BOLD, 10);
$graph->subtitle->Set($SubTitle);


// Create pie plot
$p1 = new PiePlot($datay);

$p1->SetSize(0.34);
$p1->SetCenter(0.5,0.42);


$p1->ExplodeAll(6);
$p1->SetShadow(); 

$p1->SetSliceColors($color);

$p1->SetGuideLines(true,false);
$p1->SetGuideLinesAdjust(0.91);

$p1->SetStartAngle(200);

// Setup the labels to be displayed
$p1->SetLabels($name);


$p1->SetLegends($datax);
$graph->legend->SetPos(0.49,0.97,'center','bottom');
$graph->legend->SetColumns(5);

// This method adjust the position of the labels. This is given as fractions
// of the radius of the Pie. A value < 1 will put the center of the label
// inside the Pie and a value >= 1 will pout the center of the label outside the
// Pie. By default the label is positioned at 0.5, in the middle of each slice.
$p1->SetLabelPos(0.55);

// Setup the label formats and what value we want to be shown (The absolute)
// or the percentage.
$p1->SetLabelType(PIE_VALUE_PER);
$p1->value->Show();
$p1->value->SetFont(FF_ARIAL,FS_NORMAL,9);
$p1->value->SetColor('black');

// Add and stroke
$graph->Add($p1);
$graph->Stroke();



}
$tipo=$_GET['tipo'];
if ($tipo==1) {
graficarBarra($datay,$nombre, $color, $SubTitle);
} else {
graficarTorta($datay,$nombre, $Pinto, $SubTitle);
}

/*
$cuento=1;
$x=1;
while ($cuento< count($datay)) {
	if ($x<=47) {
		//if (isset($datay[$cuento])) {
		$datayy[]=$datay[$cuento];
		$dataxx[]=$datax[$cuento];
		$x++;
		//}
	} else {
	$datayy[]=$datay[$cuento];
	$dataxx[]=$datax[$cuento];
	graficar($datayy,$dataxx, $Pinto, $SubTitle);
	$x=1;
	}
 $cuento++;
}
*/

?>