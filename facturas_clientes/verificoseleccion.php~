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
$total=0;

	if(is_array($Seleccionados) && $Seleccionados!='') {
		foreach ($Seleccionados as $key => $value)
		{
			if ($value==1) {
				$total=$total+1;
			}
		}
		//echo "0";
	}
	if($total>=1) {
	echo "1";
	} else {
	echo "No";
	}		

	
?>

