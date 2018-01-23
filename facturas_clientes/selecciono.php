<?php
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


$Seleccionados=@$s->data['Selected'];

$codfactura=$_GET['codfactura'];
$total=0;
   if(isset($_POST['q']) and $_POST['q']!='-1') {
   $Seleccionados[$codfactura]=$_POST['q'];
	$s->data['Selected'][$codfactura]=$_POST['q'];
	$s->save();

		if(is_array($Seleccionados) && !empty($Seleccionados)){
			foreach ($Seleccionados as $key => $value)
			{
				if ($value==1) {
					$total=$total+1;
				}
			//echo $clave.=' '. $key.'->'. $value."<br>";
			}
		
		}	
	
	
	} else {

	$s->data['codfactura']='';
	$s->save();
	}

?>