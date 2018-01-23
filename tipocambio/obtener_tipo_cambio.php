<?php
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
		echo "<script>window.top.location.href='../index.php'; </script>";
	   exit;
} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
              echo "<script>window.parent.changeURL('../index.php' ); </script>";
	       /*/header("Location:../index.php");	*/
	       exit;
   }
   $s->data['loggedAt']= time();/*/ update last accessed time*/
   $s->save();
}

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];

include ("../conectar.php");
include("../common/verificopermisos.php");
//header('Content-Type: text/html; charset=UTF-8'); 

ini_set('display_errors', true);
ini_set('soap.wsdl_cache_enabled',0);
ini_set('soap.wsdl_cache_ttl',0);


$location_URL = 'https://cotizaciones.bcu.gub.uy/wscotizaciones/servlet';
$wsdl = 'https://cotizaciones.bcu.gub.uy/wscotizaciones/servlet/awsbcucotizaciones?WSDL';
/*Código de monedas segun BCU*/

$wsdl_monedas = 'https://cotizaciones.bcu.gub.uy/wscotizaciones/servlet/awsbcumonedas?WSDL';

require_once("../efactura/nusoap/lib/nusoap.php");
//web Services Monedas
$client = new nusoap_client($wsdl_monedas , 'wsdl');
//display errors

$err = $client->getError();
if ($err)
{
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
	echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
	exit();
}

//Consulto tipo de moneda-------------------------------------------------------
$parameters = array(
     'Entrada' => array(
          'Grupo' => 2
     )
);
var_dump($respuesta=$client->call('Execute', $parameters));

echo "<br> Código-> ". $respuesta['Salida']['wsmonedasout.Linea'][0]['Codigo'];
echo "<br> Nombre-> ". $respuesta['Salida']['wsmonedasout.Linea'][0]['Nombre'];


//Web Services Cotizaciones
$client = new nusoap_client($wsdl , 'wsdl');
//display errors

$err = $client->getError();
if ($err)
{
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
	echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
	exit();
}

//Consulto cotización -------------------------------------------------------Formato de Fecha YYYY-mm-dd
$parameters = array(
					'Entrada'=>array(
								'Moneda' =>array('item'=>2223),
								'FechaDesde' => '2017-01-02',
								'FechaHasta' => '2017-02-03',
								'Grupo' => '2'
								)
					);
var_dump($respuesta=$client->call('Execute', $parameters));
echo "<br>status-> ". $respuesta['Salida']['respuestastatus']['status'];
echo "<br>codigoerror->  ". $respuesta['Salida']['respuestastatus']['codigoerror'];
echo "<br> Mensaje-> ". $respuesta['Salida']['respuestastatus']['mensaje'];

echo "<br> Nombre-> ". $respuesta['Salida']['datoscotizaciones']['datoscotizaciones.dato'][0]['Nombre'];
echo "<br> TCC-> ".          $respuesta['Salida']['datoscotizaciones']['datoscotizaciones.dato'][0]['TCC'];

//Web Services Cotizaciones Cierre
$wsdl = 'https://cotizaciones.bcu.gub.uy/wscotizaciones/servlet/awsultimocierre?WSDL';

$client = new nusoap_client($wsdl , 'wsdl');
//display errors

$err = $client->getError();
if ($err)
{
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
	echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
	exit();
}

//Consulto cotización -------------------------------------------------------
$parameters = array(
					'Entrada'=>array(
								)
					);
var_dump($respuesta=$client->call('Execute', $parameters));
echo $respuesta['Salida']['Fecha'];

?>