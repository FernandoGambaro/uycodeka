<?php
/*
Include the session class. Modify path according to where you put the class
file.
*/
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
$loggedAt='';
$timeOut='';

$loggedAt=$s->data['loggedAt'];
$timeOut=$s->data['timeOut'];

    if (isset($loggedAt)){
      if(time() - $loggedAt > $timeOut){
      echo "LOGOUT";  
   	$s->data['act']="timeout";
    	$s->save();            
      }
    }else{
      echo "LOGOUT";
   	$s->data['act']="logout";
    	$s->save();        
    }
?>
