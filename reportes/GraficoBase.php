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
 
session_start();
require_once('../class/class_session.php');
/*
Instantiate a new session object. If session exists, it will be restored,
otherwise, a new session will be created--placing a sid cookie on the user's
computer.
*/
if (!$s = new session()) {
  /*
  There is a problem with the session! The class has a 'log' property that
  contains a log of events. This log is useful for testing and debugging.
  */
  echo "<h2>Ocurrió un error al iniciar session!</h2>";
  echo $s->log;
  exit();
}


if((!$s->data['isLoggedIn']) || !($s->data['isLoggedIn']))
{
	/*/user is not logged in*/
	echo "<script>location.href='../index.php'; </script>";
   //header("Location:../index.php");	

} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
  		//header("Location:../index.php");	
		echo "<script>window.top.location.href='../index.php'; </script>";
	   exit;
   }
   $s->data['loggedAt']= time();
   $s->save();
}

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];

include ("../conectar.php");
include("../common/verificopermisos.php");
include ("../funciones/fechas.php");

////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 
////header('Content-Type: text/html; charset=UTF-8'); 
$codcliente=isset($_GET["codcliente"]) ? $_GET["codcliente"] : "0";

//$codcliente=$_GET['codcliente'];


$anio=date("Y", strtotime(explota($_GET['fechainicio'])));

?>
<!doctype html>
<html lang="es">
<head>
<meta name="robots" content="noindex,nofollow">
<link rel="copyright" title="GNU General Public License" href="http://www.gnu.org/copyleft/gpl.html#SEC1">
<title>Horas</title>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../js3/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="../css3/css/font-awesome.min.css">
<script type="text/javascript" src="../js3/jquery.min.js"></script>
<script type="text/javascript" src="../js3/jquery-ui.min.js"></script>
<script type="text/javascript" src="../common/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="../common/flot/jquery.flot.pie.min.js"></script>
<script type="text/javascript" src="../common/flot/jquery.flot.stack.min.js"></script>

	<link href="../common/flot/examples/examples.css" rel="stylesheet" type="text/css">
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../common/flot/excanvas.min.js"></script><![endif]-->
	<script language="javascript" type="text/javascript" src="../common/flot/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="../common/flot/jquery.flot.js"></script>
	<script language="javascript" type="text/javascript" src="../common/flot/jquery.flot.categories.js"></script>
	<script language="javascript" type="text/javascript" src="../common/flot/jquery.flot.resize.min.js"></script>
	<script type="text/javascript" src="../common/flot/jquery.flot.axislabels.js"></script>
	<link rel="stylesheet" href="../css3/styles.css">
</head>

<body>
<div style="overflow: auto;">
<div class="fichecenter">
<div class="fichehalfleft">
<table class="noborder" width="100%"><tr class="liste_titre">
<td><div id="titleA">Total de horas por mes</div></td>
<td align="right">
<a href="">
<img src="../img/xls.png" alt="" title="<?php echo date("d/m/Y");?>" class="inline-block valigntextbottom"></a>
</td></tr><tr class="impair">
<td colspan="2" class="nohover" align="center"><!-- Build using jflot -->
<div id="placeholder" style="width:380px;height:260px;" class="dolgraph"></div>
<script>
var codcliente=<?php echo $codcliente;?>;
var anio=<?php echo $anio;?>;
tipo=1;

	var cod=codcliente;
	var Barurl='EstadisticasCliente.php';
		$.ajax({
        type:'post', 
        data: {cod:cod, tipo:tipo, anio:anio},
        url:Barurl,
			success: function(data) {
				var dat=data['data'];
				var valu=data['valu'];
				var placeholder="#placeholder";
				var varLabel=valu;
				var color="#548200";
				$("#titleA").html("Total de horas por mes: "+valu);
       		graficar(dat, valu,placeholder,varLabel,color);
			},
		});

		
function graficar(dat, valu,placeholder,varLabel,color) {

Value=$.parseJSON(dat);

var dataset = [ { label: '', data: Value, color: color } ] ;
	$(function() {
		$.plot(placeholder, dataset, {
			series: {
				bars: {
					show: true,
					barWidth: 0.5,
					align: "center"
				}
			},
			xaxis: {
				mode: "categories",
				tickLength: 0
			},
            yaxis: {
                axisLabel: varLabel,
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial',
                axisLabelPadding: 3,
                tickFormatter: function (v, axis) {
                    return v ;
                }
            },			
            grid: {
                hoverable: true,
                borderWidth: 2,
                backgroundColor: { colors: ["#ffffff", "#EDF5FF"] }
            }		
		});
 		$(placeholder).UseTooltip();
	});
}
        var previousPoint = null, previousLabel = null;
 
        $.fn.UseTooltip = function () {
            $(this).bind("plothover", function (event, pos, item) {
                if (item) {
                    if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                        previousPoint = item.dataIndex;
                        previousLabel = item.series.label;
                        $("#tooltip").remove();
 
                        var x = item.datapoint[0];
                        var y = item.datapoint[1];
 
                        var color = item.series.color;
                        showTooltip(item.pageX,
                        item.pageY,
                        color,
                        "<strong>" + item.series.label + "</strong><br>" + item.series.xaxis.ticks[x].label + " : <strong>" + y + "</strong>");
                    }
                } else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            });
        };
 
        function showTooltip(x, y, color, contents) {
            $('<div id="tooltip">' + contents + '</div>').css({
                position: 'absolute',
                display: 'none',
                width:'200px',
                top: y - 40,
                left: x - 120,
                border: '2px solid ' + color,
                padding: '3px',
                'font-size': '9px',
                'border-radius': '5px',
                'background-color': '#fff',
                'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                opacity: 0.9
            }).appendTo("body").fadeIn(200);
        }
	
		
</script>
</td></tr></table>
</div>

<div class="fichehalfright">
<div class="ficheaddleft">
<table class="noborder" width="100%"><tr class="liste_titre">
<td><div id="titleB"></div></td><td align="right"><a href="/product/stats/card.php?id=&action=recalcul&mode=byunit&search_year=2017&search_categ=">
<img src="../img/xls.png" alt="" title="<?php echo date("d/m/Y");?>" class="inline-block valigntextbottom"></a></td></tr><tr class="impair">
<td colspan="2" class="nohover" align="center"><!-- Build using jflot -->
<div id="placeholderB" style="width:380px;height:160px;" class="dolgraph"></div>




</td></tr></table>
</div></div></div>


<?php
$x=1; 
while($x<= 12) {
?>
<div class="clear">

<div class="fichecenter"><div class="fichehalfleft">
<table class="noborder" width="100%"><tr class="liste_titre"><td><div id="title<?php echo $x;?>"></div></td>
<td align="right">
<a href="#">
<img src="../img/xls.png" alt="" title="<?php echo date("d/m/Y");?>" class="inline-block valigntextbottom"></a></td>
</tr><tr class="impair"><td colspan="2" class="nohover" align="center"><!-- Build using jflot -->
<div id="placeholder<?php echo $x;?>" style="width:480px;height:260px;" class="dolgraph"></div>
<?php $x++;?>

</td></tr></table>
</div>

<div class="fichehalfright"><div class="ficheaddleft">
<table class="noborder" width="100%"><tr class="liste_titre">
<td><div id="title<?php echo $x;?>"></div></td><td align="right">
<a href="#"><img src="../img/xls.png" alt="" title="<?php echo date("d/m/Y");?>" class="inline-block valigntextbottom"></a></td></tr>
<tr class="impair"><td colspan="2" class="nohover" align="center"><!-- Build using jflot -->
<div id="placeholder<?php echo $x?>" style="width:480px;height:260px;" class="dolgraph"></div>

</td></tr></table>
</div></div></div>
</div>
<?php
$x++;
 } ?>

</div>

	<script language="javascript" type="text/javascript" src="../common/flot/jquery.flot.pie.js"></script>
<script type="text/javascript" >

for (var i = 1; i <= 12; i++){

var codcliente=<?php echo $codcliente;?>;;//
//var anio=<?php echo $anio;?>;
var mes=i;

		$.ajax({
        type:'post', 
        dataType: 'json',
        data: {codcliente:codcliente, mes:mes, anio:anio},
        url:'EstadisticasTortaCliente.php',
			success: function(data) {
				var dat=data['data'];
				var control=data['control'];
				var mes=data['mes'];
				var valu=data['valu'];
				$("#title"+mes).html(data['mesTxt']);
				if (control==1) {
       			graficar2(dat,valu,mes);
       		}
			},
		});
		
}


function graficar2(dat, valu, mes) {

Value=$.parseJSON(dat);
//alert(Value);
var placeholder = $("#placeholder"+mes);

	placeholder.unbind();


	$.plot(placeholder, Value, {
		series: {
			pie: { 
				show: true,
				radius:.9,
				
			}
		},
    legend: {
        show: true
    },
		grid: {
			hoverable: true,
			clickable: true
		}
	});
			placeholder.bind("plotclick", function(event, pos, obj) {

				if (!obj) {
					return;
				}

				percent = parseFloat(obj.series.percent).toFixed(2);
				alert(""  + obj.series.label + ": " + percent + "%");
			});		
}


	function labelFormatter(label, series) {
		return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
	}

</script>

</body>
</html>