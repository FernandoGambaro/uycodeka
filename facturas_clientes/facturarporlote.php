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

?>
<html><title>tt</title><head></head>
<body>
Imprimiendo
<?php
$Seleccionados=$s->data['Selected'];

	if(is_array($Seleccionados) && $Seleccionados!='') {
		echo "<br>Empieso";
		foreach ($Seleccionados as $key => $value)
		{
			if($value==1) {
			echo $key."<br>";
?>
<script type="text/javascript">
			var top = window.open("../fpdf/imprimir_factura_mcc.php?codfactura="+<?php echo $key;?>, "factura", "location=1,status=1,scrollbars=1");
			//top.close();
</script>
<?php			
			
		//echo $clave.=' '. $key.'->'. $value."<br>";
			}
		}
	$s->data['Selected']='';
	$s->save();	
	}	
	

?>
<script type="text/javascript">
			parent.$('idOfDomElement').colorbox.close();
</script>
</body></html>

