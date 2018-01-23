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
 

/*
Include the session class. Modify path according to where you put the class
file.
*/
require_once('class/class_session.php');

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
	?>
<script language="JavaScript" type="text/javascript">
   parent.changeURL('index.ph' );
</script>
	<?php
} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
	       header("Location:index.php");	
	       exit;
   }
   $s->data['loggedAt']= time();/*/ update last accessed time*/
   $s->save();
}

//header('Content-Type: text/html; charset=UTF-8'); 
include ("conectar.php"); 
date_default_timezone_set('America/Montevideo');
session_start();

$status_msg = "";

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];
$ip=$s->data['IP'];
$ask= $s->data['ask'];


$hoy=date("Y-m-d");

$sel_tmp="SELECT codalbaran FROM albaranestmp WHERE datediff('$hoy',fecha) > 2";
$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tmp);
$contador=0;
while ($contador < mysqli_num_rows($rs_tmp)) {
	$codalbaran=mysqli_result($rs_tmp, $contador, "codalbaran");
	$sel_borrar="DELETE FROM albalineatmp WHERE codalbaran='$codalbaran'";
	$rs_borrar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);
	$contador++;
}

$sel_borrar="DELETE FROM albaranestmp WHERE datediff('$hoy',fecha) > 2";
$rs_borrar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);

$sel_tmp="SELECT codalbaran FROM albaranesptmp WHERE datediff('$hoy',fecha) > 2";
$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tmp);
$contador=0;
while ($contador < mysqli_num_rows($rs_tmp)) {
	$codalbaran=mysqli_result($rs_tmp, $contador, "codalbaran");
	$sel_borrar="DELETE FROM albalineaptmp WHERE codalbaran='$codalbaran'";
	$rs_borrar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);
	$contador++;
}

$sel_borrar="DELETE FROM albaranesptmp WHERE datediff('$hoy',fecha) > 2";
$rs_borrar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);

$sel_tmp="SELECT codfactura FROM facturastmp WHERE datediff('$hoy',fecha) > 2";
$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tmp);
$contador=0;
while ($contador < mysqli_num_rows($rs_tmp)) {
	$codfactura=mysqli_result($rs_tmp, $contador, "codfactura");
	$sel_borrar="DELETE FROM factulineatmp WHERE codfactura='$codfactura'";
	$rs_borrar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);
	$contador++;
}

$sel_borrar="DELETE FROM facturastmp WHERE datediff('$hoy',fecha) > 2";
$rs_borrar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);

$sel_tmp="SELECT codfactura FROM facturasptmp WHERE datediff('$hoy',fecha) > 2";
$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tmp);
$contador=0;
while ($contador < mysqli_num_rows($rs_tmp)) {
	$codfactura=mysqli_result($rs_tmp, $contador, "codfactura");
	$sel_borrar="DELETE FROM factulineaptmp WHERE codfactura='$codfactura'";
	$rs_borrar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);
	$contador++;
}

$sel_borrar="DELETE FROM facturasptmp WHERE datediff('$hoy',fecha) > 2";
$rs_borrar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
		<link href="estilos/estilos.css" type="text/css" rel="stylesheet">
		<script src="calendario/jscal2.js"></script>
		<script src="calendario/lang/es.js"></script>
		<link rel="stylesheet" type="text/css" href="calendario/css/jscal2.css" />
		<link rel="stylesheet" type="text/css" href="calendario/css/border-radius.css" />
		<link rel="stylesheet" type="text/css" href="calendario/css/win2k/win2k.css" />		

<!-- iconos para los botones -->       
<link rel="stylesheet" href="css3/css/font-awesome.min.css">
<link rel="stylesheet" href="css3/styles.css">


<script type="text/javascript" >

</script>		
		
<script type="text/javascript">
		function listar_service(codcliente) {
			var url="clientes/service/index.php?e=" + codcliente ;
			var w='97%';
			var h='500px';
			parent.OpenNote(url,w,h);
		}
		
parent.document.getElementById("msganio").innerHTML=" Inicio ";

</script>

<style type="text/css">

.sprite {
    background-image: url("central/imagenes-cotizacion-dolar.png");
    background-repeat: no-repeat;
    display: block;
    float: left;
    top: -5px;

}
.sprite-Argentinax24 {
    background-position: -5px -5px;
    height: 24px;
    margin-top: -4px;
    width: 24px;
    top: -5px;
}
.sprite-Argentinax32 {
    background-position: -39px -5px;
    height: 32px;
    width: 32px;
    top: -5px;
}
.sprite-Brazilx24 {
    background-position: -81px -5px;
    height: 24px;
    margin-top: -4px;
    width: 24px;
    top: -5px;
}
.sprite-Brazilx32 {
    background-position: -115px -5px;
    height: 32px;
    width: 32px;
    top: -5px;
}
.sprite-Chilex24 {
    background-position: -5px -47px;
    height: 24px;
    margin-top: -4px;
    width: 24px;
    top: -5px;
}
.sprite-Chilex32 {
    background-position: -39px -47px;
    height: 32px;
    width: 32px;
    top: -5px;
}
.sprite-European-Unionx24 {
    background-position: -81px -47px;
    height: 24px;
    margin-top: -4px;
    width: 24px;
    top: -5px;
}
.sprite-European-Unionx32 {
    background-position: -115px -47px;
    height: 32px;
    width: 32px;
    top: -5px;
}
.sprite-United-StatesUnited-Statesx32 {
    background-position: -5px -89px;
    height: 32px;
    width: 32px;
    top: -5px;
}
.sprite-United-Statesx24 {
    background-position: -47px -89px;
    height: 24px;
    margin-top: -4px;
    width: 24px;
    top: -5px;
}
.sprite-Uruguayx24 {
    background-position: -81px -89px;
    height: 24px;
    margin-top: -4px;
    width: 24px;
    top: -5px;
}
.sprite-Uruguayx32 {
    background-position: -115px -89px;
    height: 32px;
    width: 32px;
    top: -5px;
}


div.cotizacion-contenido {
    background-image: linear-gradient(to bottom, #d4d4d4 21%, #f5f5f5 100%);
    border: 1px solid rgb(204, 204, 204);
    border-radius: 7px;
    box-shadow: 0 -1px 1px #aaa;
    display: table;
    margin-left: auto;
    margin-right: auto;
    padding-top: 2px;
    /*width: 100%;*/
}
div.cc-1 {
    background-color: rgb(72, 72, 72);
    border: 1px solid rgb(153, 153, 153);
    color: white;
    float: left;
    font-family: Arial,Helvetica,sans-serif;
    font-size: 1em;
    font-weight: bold;
    height: 16px;
    margin-bottom: -3px;
    margin-left: 1%;
    margin-top: 3px;
    min-width: 33%;
    padding: 5px 1%;
    text-shadow: -2px 2px 3px rgb(0, 0, 0);
}
div.cc-2 {
    background-color: rgb(72, 72, 72);
    border: 1px solid rgb(153, 153, 153);
    float: left;
    height: 16px;
    margin-bottom: 3px;
    margin-left: 1%;
    margin-top: 3px;
    min-width: 26%;
    padding-left: 1%;
    padding-right: 1%;
    /*padding-top: 10px;*/
    text-align: center;
    text-shadow: -2px 2px 3px rgb(0, 0, 0);
    width: 26%;
}
div.cc-3 {
    background-color: rgb(72, 72, 72);
    border: 1px solid rgb(153, 153, 153);
    float: left;
    height: 16px;
    margin: 3px 1%;
    min-width: 26%;
    padding-left: 1%;
    padding-right: 1%;
    /*padding-top: 10px;*/
    text-align: center;
    text-shadow: -2px 2px 3px rgb(0, 0, 0);
    width: 26%;
}
div.cc-1b {
    background-color: #989b87;
    border: 1px solid rgb(153, 153, 153);
    color: white;
    float: left;
    font-family: Arial,Helvetica,sans-serif;
    font-size: 1em;
    font-weight: bold;
    height: 24px;
    margin-bottom: 3px;
    margin-left: 1%;
    margin-top: 3px;
    min-width: 33%;
    padding-left: 1%;
    padding-right: 1%;
    padding-top: 10px;
    text-shadow: -2px 2px 3px rgb(91, 107, 73);
}
div.cc-2b {
    background-color: rgb(234, 234, 234);
    border: 1px solid rgb(204, 204, 204);
    float: left;
    height: 24px;
    margin-bottom: 10px;
    margin-left: 1%;
    margin-top: 3px;
    max-width: 26%;
    min-width: 26%;
    padding-left: 1%;
    padding-right: 1%;
    /*padding-top: 5px;*/
    text-align: center;
}
div.cc-3b {
    background-color: rgb(234, 234, 234);
    border: 1px solid rgb(204, 204, 204);
    float: left;
    height: 24px;
    margin-bottom: 10px;
    margin-left: 1%;
    margin-top: 3px;
    max-width: 26%;
    min-width: 26%;
    padding-left: 1%;
    padding-right: 1%;
    /*padding-top: 5px;*/
    text-align: center;
    width: 26%;
}
div.cc-1, div.cc-2, div.cc-3, div.cc-1b, div.cc-2b, div.cc-3b {
    border-radius: 7px;
}
span.cotizacion-num {
    color: blue;
    font-size: 16px;
    font-weight: bold;
    text-shadow: -1px 1px 1px rgb(153, 153, 153);
}
div.cotizacion-billete {
    display: table;
    font-size: 12px;
    min-width: 100px;
    position: relative;
/*    position: absolute;*/
    visibility: visible;
    top: -18px;
    float: left; 
}
div.cotizacion-billete b {
   position: relative;
   float: left;
   left: 5px;
	top: 10px;
}
div.cotizacion-titulo1 {
    display: table;
    min-width: 100px;
    position: relative;
    visibility: visible; 
    top: -8px;   
}
span.cotizacion-titulo1 {
    color: rgb(255, 165, 0);
    font-size: 12px;
    font-weight: bold;
    margin-left: 5px;
}
span.cotizacion-titulo2 {
    color: rgb(255, 165, 0);
    font-size: 10px;
    font-weight: bold;
    top: -8px;     
}
div.cotizaciones-a {
    background: linear-gradient(to bottom, #f5f5f5 10%, #d4d4d4 100%) repeat scroll 0 0 rgba(0, 0, 0, 0);
    border: 1px solid rgb(204, 204, 204);
    border-radius: 7px;
    box-shadow: 0 2px 6px #aaa;
    display: table;
    font-family: Arial,Helvetica,sans-serif;
    margin-left: 1%;
    min-width: 180px;
    padding: 10px;
    visibility: visible;
    width: 86%;
}
@media all and (max-width: 783px) {
div.cotizacion-billete {
    font-size: 16px;
}
@media all and (max-width: 602px) {
div.cotizacion-billete {
    font-size: 16px;
}
@media all and (max-width: 480px) {
div.cotizaciones-a {
    display: block;
    margin-left: 1px;
    min-width: 142px;
    overflow: visible;
    padding: 1px;
    width: 94%;
}
span.cotizacion-num {
    color: blue;
    font-size: 16px;
    font-weight: bold;
    text-shadow: -1px 1px 1px rgb(153, 153, 153);
}
div.cotizacion-billete {
    box-sizing: border-box;
    display: table;
    font-size: 10px;
    overflow: visible;
/*    position: absolute;*/
    visibility: visible;
    top: -15px;
    float: left;
}
div.cc-1, div.cc-1b, div.cc-2b, div.cc-3b {
    height: 38px;
    margin-left: 1px;
    /*min-width: 126px;*/
    overflow: visible;
    padding-left: 1px;
    padding-right: 1px;
    width: 95%;
    float: left;
}
div.cc-1b {
    height: 32px;
}
div.cc-2b, div.cc-3b {
    min-width: 45%;
}
span.cotizacion-titulo1 {
    font-size: 12px;
}
span.cotizacion-titulo2 {
    font-size: 11px;
}
div.cc-1, div.cc-2, div.cc-3, div.cc-1b, div.cc-2b, div.cc-3b {
    float: left;
    overflow: visible;
}
#cc-2a1, #cc-2a2 {
    float: left;
    overflow: visible;
    width: 45%;
}
}
}
}


-->
</style>
</head>

<body>
<table class="fuente8" width="100%" cellspacing=3 cellpadding=3 border=0>
  <tr>
    <td valign="top"  style="min-width: 320px;"><div align="center">
    <img id="logo_" src="img/central.png" width="200" height="108" border="0" usemap="#map" />

<map name="map">
<!-- #$-:Image map file created by GIMP Image Map plug-in -->
<!-- #$-:GIMP Image Map plug-in by Maurits Rijk -->
<!-- #$-:Please do not edit lines starting with "#$" -->
<!-- #$VERSION:2.3 -->
<!-- #$AUTHOR:fernando -->
<area shape="rect" coords="0,0,268,149" href="https://codeka-uy.blogspot.com" target="_blank" />
</map>
    </div>
<p>&nbsp;</p>    
<div align="center">Fuente <a href="http://uy.cotizacion-dolar.com/cotizacion_hoy_uruguay.php" target="_blank">http://uy.cotizacion-dolar.com/</a></div>
   <div class="cotizaciones">
<?php
include_once("./funciones/simple_html_dom.php");

function getHTML($url,$timeout)
{
       $ch = curl_init($url); // initialize curl with given url
       curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]); // set  useragent
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // write the response to a variable
       //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects if any
       curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); // max. seconds to execute
       curl_setopt($ch, CURLOPT_FAILONERROR, 1); // stop when it encounters an error
       return @curl_exec($ch);
}

$html=getHTML("http://uy.cotizacion-dolar.com/cotizacion_hoy_uruguay.php",10);

preg_match('{<div\s+class="cotizaciones-a"\s*>((?:(?:(?!<div[^>]*>|</div>).)++|<div[^>]*>(?1)</div>)*)</div>}si', $html, $match);
echo @$match[1];

?>    
</div>    
    
    
    </td>
    <td valign="top">
    
<div class="box">    
<?php
$dashboar=array('proveedores' => array('imagen' =>"img/dashboard/proveedores.png",
																												'href' =>"proveedores/index.php",
																												'sql'=>'SELECT count(*) as filas FROM proveedores WHERE borrado=0;',
																												'parm'=>''),
													'clientes' => array('imagen' =>"img/dashboard/clientes.png",
																												'href' =>"clientes/index.php",
																												'sql'=>'SELECT count(*) as filas FROM clientes WHERE borrado=0;',
																												'parm'=>''),
													'servicios cliente' => array('imagen' =>"img/dashboard/servicios.png",
																												'href' =>"clientes/index.php",
																												'sql'=>'SELECT count(*) as filas FROM service,clientes WHERE service.borrado=0 AND service.codcliente=clientes.codcliente;',
																												'parm'=>''),
													'respaldos cliente' => array('imagen' =>"img/dashboard/respaldos.png",
																												'href' =>"clientes/index.php",
																												'sql'=>'SELECT count(*) as filas FROM respaldospc;',
																												'parm'=>''),
									'familias de articulos' => array('imagen' =>"img/dashboard/familias.png",
																												'href' =>"familias/index.php",
																												'sql'=>'SELECT count(*) as filas FROM familias WHERE borrado=0',
																												'parm'=>''),
									'articulos' => array('imagen' =>"img/dashboard/articulos.png",
																												'href' =>"articulos/index.php",
																												'sql'=>'SELECT count(*) as filas FROM articulos WHERE borrado=0',
																												'parm'=>''),
									'embalaje' => array('imagen' =>"img/dashboard/clientes.png",
																												'href' =>"embalajes/index.php",
																												'sql'=>'SELECT count(*) as filas FROM embalajes WHERE borrado=0 ',
																												'parm'=>''),
									'ventas' => array('imagen' =>"img/dashboard/ventas.png",
																												'href' =>"facturas_clientes/index.php",
																												'sql'=>'SELECT count( * ) AS filas FROM clientes RIGHT JOIN facturas ON clientes.codcliente = facturas.codcliente
	LEFT JOIN ncredito ON clientes.codcliente = ncredito.codcliente',
																												'parm'=>'AND facturas.tipo=1'),
									'presupuestos' => array('imagen' =>"img/dashboard/presupuestos.png",
																												'href' =>"presupuestos_clientes/index.php",
																												'sql'=>'SELECT count(*) as filas FROM presupuestos,clientes WHERE presupuestos.borrado=0 AND presupuestos.codcliente=clientes.codcliente;',
																												'parm'=>''),
									'orden de pedido' => array('imagen' =>"img/dashboard/orden.png",
																												'href' =>"clientes/index.php",
																												'sql'=>'SELECT count(*) as filas FROM clientes WHERE borrado=0',
																												'parm'=>''),
									'compras' => array('imagen' =>"img/dashboard/compras.png",
																												'href' =>"facturas_proveedores/index.php",
																												'sql'=>'SELECT count(*) as filas FROM facturasp,proveedores WHERE facturasp.codproveedor=proveedores.codproveedor',
																												'parm'=>''),
									'cobros rapidos' => array('imagen' =>"img/dashboard/cobros.png",
																												'href' =>"clientes/index.php",
																												'sql'=>'SELECT count(*) as filas FROM clientes WHERE borrado=0',
																												'parm'=>''),
									'cobros' => array('imagen' =>"img/dashboard/cobros.png",
																												'href' =>"clientes/index.php",
																												'sql'=>'SELECT count(*) as filas FROM clientes WHERE borrado=0',
																												'parm'=>''),
									'tipo de cambio' => array('imagen' =>"img/dashboard/tipocambio.png",
																												'href' =>"tipocambio/index.php",
																												'sql'=>'SELECT count(*) as filas FROM tipocambio',
																												'parm'=>''),
									'Mantenimiento' => array('imagen' =>"img/dashboard/articulos.png",
																												'href' =>"clientes/index.php",
																												'sql'=>'SELECT count(*) as filas FROM clientes WHERE borrado=0',
																												'parm'=>''),
									'Clientes' => array('imagen' =>"img/dashboard/usuarios.png",
																												'href' =>"clientes/index.php",
																												'sql'=>'SELECT count(*) as filas FROM clientes WHERE borrado=0',
																												'parm'=>''),
									'copias seguridad' => array('imagen' =>"img/dashboard/respaldos.png",
																												'href' =>"backup/hacerbak.php",
																												'sql'=>'SELECT count(*) as filas FROM clientes WHERE borrado=0',
																												'parm'=>''),
									'dgi' => array('imagen' =>"img/dashboard/dgi.png",
																												'href' =>"clientes/index.php",
																												'sql'=>'SELECT count(*) as filas FROM clientes WHERE borrado=0',
																												'parm'=>''),
							)
?>
<table summary="Estadísticas" class="noborder boxtable nohover" width="100%">
<tbody>
<tr class="liste_titre"><th class="liste_titre">Estadísticas</th></tr>
<tr class="impair"><td class="tdboxstats nohover flexcontainer">
<?php
foreach($dashboar as $key=>$xsector) {

 $query_busqueda=$xsector['sql'].' '.$xsector['parm'];

if($query_busqueda!=' ') {
$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
$filas=mysqli_result($rs_busqueda, 0, "filas");	
}
?>
<a href="<?php echo $xsector['href'];?>" class="boxstatsindicator thumbstat nobold nounderline"><div class="boxstats">
<span class="boxstatstext" title="<?php echo ucwords($key);?>">
<img src="<?php echo $xsector['imagen'];?>" alt="" title="" class="inline-block valigntextbottom"> <?php echo ucwords($key);?></span>
<br><span class="boxstatsindicator"><?php echo $filas;?></span></div></a>
<?php } ?>


</td>
</tr></tbody></table>    
</div>    
</td>
    <td valign="top">
<table summary="Estadísticas" class="noborder boxtable nohover" width="100%">
<tbody>
<tr class="liste_titre"><th class="liste_titre">Facturas</th></tr> <td class="tdboxstats nohover flexcontainer">   
    <div align="center"> 
    <object type="text/html" data="central/jpg_ventasvscompras.php" width="380px" height="200px" style="overflow:auto;border:5px ">
    </object></div>
<p>&nbsp;</p>
   <div align="center"> 
    <object type="text/html" data="central/jpg_facturas.php" width="360px" height="200px" style="overflow:auto;border:5px ">
    </object></div>    
    
</td></tr>
</tbody></table>  
</td>
  </tr>
</table>
<?php
include ("funciones/fechas.php");
$fechain = date ('Y-m-d', strtotime('-1 month')) ;
$startTime =data_first_month_day($fechain); 
$endTime = data_last_month_day($fechain);

 if(file_exists(".listo.php")) {
include(".listo.php");
$fechafin = date('Y-m-d', strtotime('+1 month')) ;
 $fechacomparar=date('Y-m-d', strtotime(explota($Fecha_Instalacion)));

if(($fechafin-$fechacomparar)>0 and $ask==1) {

  ?>
<script type="text/javascript" >
var noteId="ayuda/opinion.php?ask=<?php echo $ask;?>";
var w='800px';
var h='90%';
parent.OpenNote(noteId,w,h, scroll=false);
</script>
<?php } 
}
?>
</body>
</html>