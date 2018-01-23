<?php
$path=realpath(dirname(__FILE__));
$largo=strlen($path)-4;
$conexionPath=substr($path,0,$largo);

include('cron_class.php');

$crons=Crontab::getJobs();
$php=shell_exec('which php')." ".$conexionPath;

function extract_unit($string, $start, $end)
{
	$pos = stripos($string, $start);
	$str = substr($string, $pos);
	$str_two = substr($str, strlen($start));
	$second_pos = stripos($str_two, $end);
	$str_three = substr($str_two, 0, $second_pos);
	$unit = trim($str_three); /*/ remove whitespaces*/
	return $unit;
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Crontab Code Generator</title>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">


<style type="text/css">
/* <![CDATA[ */
h3 {
	margin:0;
}
br {
	clear:both;
}
.box {
	width:200px;
	float:left;
	font:8pt helvetica;
}
/* ]]> */
</style>
<script src="jsl.js" type="text/javascript"></script>
<script src="common.js" type="text/javascript"></script>
	<script src="jquery.min.js"></script>
	<script src="jquery-ui.min.js"></script>	

<script language="javascript" type="text/javascript">
/* <![CDATA[ */
function init() {
	JSL.dom(".chooser").click(function(e) {
		var for_element = this.name.replace(/_chooser/,"");

		JSL.dom(for_element).disabled = (this.value !== "1");
	});
	
	JSL.dom("crontab-form").on("submit", function(e) {
		JSL.event(e).stop();
		
		var minute, hour, day, month, weekday,tareas;
		
		minute	= getSelection('minute');
		hour	= getSelection('hour');
		day		= getSelection('day');
		month	= getSelection('month');
		weekday	= getSelection('weekday');
		tareas = $("#tareas").find('option:selected').val();
		
		var command = JSL.dom("command").value;
		command=command.trim() + tareas.trim();
		JSL.dom("cron").value = minute + "\t" + hour + "\t" + day + "\t" + month + "\t" + weekday + "\t" + command;
		
		var cron=$("#cron").val();

			jQuery.ajax({
				 type: "POST",
				 url: "addcron.php",
				 data: {cron:cron },
				 async: true,
				 cache: false,
				 success: function(data){
				 	 window.location.href='crontab3.php';
					}
			}); 		
		
	});
}

function getSelection(name) {
	var chosen;
	if(JSL.dom(name + "_chooser_every").checked) {
		chosen = '*';
	} else {
		var all_selected = [];
		JSL.dom("#" + name+ " option").each(function(ele) {
			if(ele.selected)
				all_selected.push(ele.value);
		});
		if(all_selected.length)
			chosen = all_selected.join(",");
		else
			chosen = '*';
	}
	return chosen;
}

function eliminar(cron) {
				jQuery.ajax({
				 type: "POST",
				 url: "delcron.php",
				 data: {cron:cron },
				 async: true,
				 cache: false,
				 success: function(data){
				 	window.location.href='index.php';
					}
			});
}
/* ]]> */
</script>
</head>

<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">Ver tareas programadas</div>
				<div id="frmBusqueda">

<form method="post" action="" id="crontab-form">

línea previa: <input type="text" name="command" id="command" size="70" value="<?php echo $php;?>" readonly="readonly">
&nbsp;Tarea&nbsp;
<select name="tareas" id="tareas" class="cajaMedia">
<option value="clientes/backup/NewGetBackup.php">Verifico respaldos </option>
<option value="clientes/backup/ComunicoError.php">Comunico errores </option>
<option value="clientes/autofacturas/autofactura.php">Auto facturación mensual </option>
</select>
<br>

<div class="box">
<h3>Minuto</h3>
<label for="minute_chooser_every">Cada Minuto</label>
<input type="radio" name="minute_chooser" id="minute_chooser_every" class="chooser" value="0" checked="checked"><br>

<label for="minute_chooser_choose">En el/los minuto/s</label>
<input type="radio" name="minute_chooser" id="minute_chooser_choose" class="chooser" value="1"><br>


<select name="minute" id="minute" multiple="multiple" disabled="disabled" class="cajaMediaMulti">
<option value="0">0</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
<option value="32">32</option>
<option value="33">33</option>
<option value="34">34</option>
<option value="35">35</option>
<option value="36">36</option>
<option value="37">37</option>
<option value="38">38</option>
<option value="39">39</option>
<option value="40">40</option>
<option value="41">41</option>
<option value="42">42</option>
<option value="43">43</option>
<option value="44">44</option>
<option value="45">45</option>
<option value="46">46</option>
<option value="47">47</option>
<option value="48">48</option>
<option value="49">49</option>
<option value="50">50</option>
<option value="51">51</option>
<option value="52">52</option>
<option value="53">53</option>
<option value="54">54</option>
<option value="55">55</option>
<option value="56">56</option>
<option value="57">57</option>
<option value="58">58</option>
<option value="59">59</option>
</select>
</div>

<div class="box">
<h3>Hora</h3>
<label for="hour_chooser_every">CadaHora</label>
<input type="radio" name="hour_chooser" id="hour_chooser_every" class="chooser" value="0" checked="checked"><br>

<label for="hour_chooser_choose">Elja la hora</label>
<input type="radio" name="hour_chooser" id="hour_chooser_choose" class="chooser" value="1"><br>

<select name="hour" id="hour" multiple="multiple" disabled="disabled" class="cajaMediaMulti">
<option value="0">12 Medianoche</option>
<option value="1">1 AM</option><option value="2">2 AM</option><option value="3">3 AM</option>
<option value="4">4 AM</option><option value="5">5 AM</option><option value="6">6 AM</option>
<option value="7">7 AM</option><option value="8">8 AM</option><option value="9">9 AM</option>
<option value="10">10 AM</option><option value="11">11 AM</option><option value="12">12 Mediodía</option>
<option value="13">1 PM</option><option value="14">2 PM</option><option value="15">3 PM</option>
<option value="16">4 PM</option><option value="17">5 PM</option><option value="18">6 PM</option>
<option value="19">7 PM</option><option value="20">8 PM</option><option value="21">9 PM</option>
<option value="22">10 PM</option><option value="23">11 PM</option></select>
</div>

<div class="box">
<h3>Día</h3>
<label for="day_chooser_every">Todos los días</label>
<input type="radio" name="day_chooser" id="day_chooser_every" class="chooser" value="0" checked="checked"><br>

<label for="day_chooser_choose">Elija el día</label>
<input type="radio" name="day_chooser" id="day_chooser_choose" class="chooser" value="1"><br>

<select name="day" id="day" multiple="multiple" disabled="disabled" class="cajaMediaMulti">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
</select>
</div>

<div class="box">
<h3>Mes</h3>
<label for="month_chooser_every">Todos los mese</label>
<input type="radio" name="month_chooser" id="month_chooser_every" class="chooser" value="0" checked="checked"><br>

<label for="month_chooser_choose">Elja el mes</label>
<input type="radio" name="month_chooser" id="month_chooser_choose" class="chooser" value="1"><br>

<select name="month" id="month" multiple="multiple" disabled="disabled" class="cajaMediaMulti">
<option value="1">Enero</option>
<option value="2">Febrero</option>
<option value="3">Marzo</option>
<option value="4">Abril</option>
<option value="5">Mayo</option>
<option value="6">Junio</option>
<option value="7">Julio</option>
<option value="8">Augosto</option>
<option value="9">Setiembre</option>
<option value="10">Octubre</option>
<option value="11">Noviembre</option>
<option value="12">Diciembre</option>
</select>
</div>

<div class="box">
<h3>Día de la semana</h3>
<label for="weekday_chooser_every">Todos los días</label>
<input type="radio" name="weekday_chooser" id="weekday_chooser_every" class="chooser" value="0" checked="checked"><br>

<label for="weekday_chooser_choose">Elija día</label>
<input type="radio" name="weekday_chooser" id="weekday_chooser_choose" class="chooser" value="1"><br>

<select name="weekday" id="weekday" multiple="multiple" disabled="disabled" class="cajaMediaMulti">
<option value="0">Domingo</option>
<option value="1">Lunes</option>
<option value="2">Martes</option>
<option value="3">Miércoles</option>
<option value="4">Jueves</option>
<option value="5">Viernes</option>
<option value="6">Sábado</option>
</select>
</div>

<br><br>
Resultado:<br>
<textarea name="cron" id="cron" rows="1" cols="140"></textarea>

<!--input type="submit" name="action" id="action" value="Crear programación"-->
<button class="boletin" onClick='"$("#crontab-form").submit();' onMouseOver="style.cursor=cursor">
<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Crear tarea</button>
</form>
</div>
			<div align="center">

			<div class="header" style="width:90%;position: relative; font-size: 140%; text-align: center;">Listado de tareas programadas </div>
			<div class="fixed-table-container" style="width:90%;position: relative; top: -19px;">
      		<div class="header-background cabeceraTabla"> </div>      			
			<div class="fixed-table-container-inner">
				<table class="fuente8" width="90%" cellspacing=0 cellpadding=3 border=0 style="font-size: 130%;">
					<thead>			
						<tr class="cabeceraTabla">
							<th><div class="th-inner">Tarea programada</div></th>
							<th ><div class="th-inner">ACCIÓN</div></th>
						</tr>
					</thead>
					<tbody>


<?php 
$contador=0;
if(isset($crons)) {
	foreach($crons as $key=>$item){
		if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>
		<tr id="<?php echo key;?>"	 class="<?php echo $fondolinea?> trigger">
		<td class="aDerecha"><div align="center"><?php echo $item;?></div></td>	
		<td ><div align="center"><a href="#"><img id="botonBusqueda" src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar('<?php echo $item;?>');" title="Eliminar tarea"></a></div></td>
		</tr>						
		<?php
		$contador++;
	}
 } else { ?>
						<tr>
							<td width="100%" class="mensaje" colspan="12"><?php echo "No hay ninguna factura que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
					<?php } ?>					
					</table>
					
		<?php
				$estado=shell_exec('grep "/usr/bin/php" /var/log/cron');					
			?>
			<p></p>
			<div class="header" style="width:90%;position: fixed; font-size: 140%; text-align: center;">Listado de tareas realizadas </div>
			<div class="fixed-table-container" style="width:100%;">
      		<div class="header-background cabeceraTabla"> </div>      			
			<div class="fixed-table-container-inner">
				<table class="fuente8" width="90%" cellspacing=0 cellpadding=3 border=0 style="font-size: 130%;">
					<thead>			
						<tr class="cabeceraTabla">
							<th><div class="th-inner">Tarea realizadas</div></th>
						</tr>
					</thead>
				<tbody>
				
				
<?php
$lines = array();
$lines = explode("\n", $estado);
$lin=array_reverse($lines, true);

foreach ($lin as $key=>$line) {
	if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }
		if(extract_unit($line, "", "localhost")!="") {
		echo "<tr  class=\"". $fondolinea."\"><td>Fecha: ". extract_unit($line, "", "localhost")."-  Tarea: ".extract_unit($line, "clientes/", ".php")."</td></tr>";
		}
$contador++;
}					
					
					?>	
					</tbody>
</table>
</div></div></div>
</div>
</div>
</div>
</div>
</div>
</div>
</body>
</html>
