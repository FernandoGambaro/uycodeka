<?php


$your_ip = '192.168.1.124'; // Set here your device's ip

$options = array(
    'location' => 'http://' . $your_ip . '/iWsService',
    'uri' => 'http://www.zksoftware/Service/message/'
);

$client = new SoapClient(null, $options);
$soapRequest = "<GetAllUserInfo><ArgComKey>0</ArgComKey></GetAllUserInfo>";

$response = $client->__doRequest($soapRequest, 'http://' . $your_ip . '/iWsService', '', '1.1');

echo '<pre>', var_dump($response), '</pre>'; // Please copy the whole string generated here and send it to me

require 'tad/lib/TADFactory.php';
require 'tad/lib/TAD.php';
require 'tad/lib/TADResponse.php';
require 'tad/lib/Providers/TADSoap.php';
require 'tad/lib/Providers/TADZKLib.php';
require 'tad/lib/Exceptions/ConnectionError.php';
require 'tad/lib/Exceptions/FilterArgumentError.php';
require 'tad/lib/Exceptions/UnrecognizedArgument.php';
require 'tad/lib/Exceptions/UnrecognizedCommand.php';

$tad_factory = new TADPHP\TADFactory();

use TADPHP\TADFactory;
use TADPHP\TAD;

$comands = TAD::commands_available();

  $options = [
    'ip' => '192.168.1.124',   // '169.254.0.1' by default (totally useless!!!).
    'internal_id' => 1,    // 1 by default.
    'com_key' => 0,        // 0 by default.
    'description' => 'TAD1', // 'N/A' by default.
    'soap_port' => 80,     // 80 by default,
    'udp_port' => 4370,      // 4370 by default.
    'encoding' => 'utf-8' ,   // iso8859-1 by default.
  ];
  
  $tad_factory = new TADFactory($options);
  $tad = $tad_factory->get_instance(); 
var_dump($tad);
// Get a full list of commands supported by TADPHP\TAD class.
$commands_list = TAD::commands_available();

// Get a list of commands implemented via TADPHP\TADSoap.
$soap_commands = TAD::soap_commands_available();

// Get a list of commands implemented via TAD\PHP\TADSoap.
$zklib_commands = TAD::zklib_commands_available();

//var_dump($soap_commands);

/*
// Getting current time and date
$dt = $tad->get_date();

// Setting device's date to '2014-01-01' (time will be set to now!)
$response = $tad->set_date(['date'=>'2014-01-01']);

// Setting device's time to '12:30:15' (date will be set to today!)
$response = $tad->set_date(['time'=>'12:30:15']);

// Setting device's date & time
$response = $tad->set_date(['date'=>'2014-01-01', 'time'=>'12:30:15']);

// Setting device's date & time to now.
$response = $tad->set_date();
*/
// Getting attendance logs from all users.
$logs = $tad->get_att_log();
var_dump($logs);
// Getting attendance logs from one user.
//$logs = $tad->get_att_log(['pin'=>7]);



// Getting info from a specific user.
$user_info = $tad->get_user_template(['pin'=>7])->to_array();

// Getting info from all users.
$all_user_info = $tad->get_all_user_info();
var_dump($all_user_info->to_array());
//var_dump($tad->get_user_info(['pin'=>8])->to_array());
//var_dump($user_info->to_array()['Row']);
echo "<p>";

/*Extraigo los datos del template del usuario seleccionado y lo almaceno en un array()*/
$template=[];
$actualizar=0;
if(is_array($user_info['Row'])) {
	$actualizar=1;
foreach($user_info['Row'] as $row=>$contenido){
	foreach($contenido as $key=>$dato){
		$key=strtolower($key);
		if ($key=='fingerid'){
			$key='finger_id';
		}
   	$template[$row][$key]=$dato;	
	}
}
}
 /*Luego de tener los datos del template almacenados actualizo los datos del usuario*/

$r = $tad->set_user_info([
    'pin' => 8,
    'name'=> 'Otra prueba',
    'privilege'=> 0,
    'card'=> 4053967,    
    'pin2'=> 67,    
    'password' => 123
]);
/*Para el usuario seleccionado actualizo los datos del template que almacene previamente en un array()*/
if($actualizar==1) {
foreach($template as $template_data){
$tad->set_user_template( $template_data );
}
}


// Getting attendance logs from all users.
$logs = $tad->get_att_log(['pin'=>7]);

$r = $tad->get_att_log(['pin'=>7]); // This employee does not have any logs!!!

if ($r->is_empty_response()) {
    echo 'The employee does not have logs recorded';
} else {
	echo $logs_number = $r->count();
}

//var_dump($logs->to_array());

// Delete all attendance logs stored.
//$tad->delete_data(['value'=>3]);




?>