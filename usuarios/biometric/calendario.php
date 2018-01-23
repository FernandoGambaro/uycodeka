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
 

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];
@$paginacion=$s->data['alto'];

if($paginacion<=0) {
	$paginacion=20;
}
$Seleccionados=array();
$Seleccionados=@$s->data['Selected'];

include ("../../conectar.php");
include("../../common/verificopermisos.php");
include("../../common/funcionesvarias.php");

$date=isset($_GET['defaultDate']) ? $_GET['defaultDate'] : date('Y-m-j') ;

$prev_day = date ('Y-m-j', (strtotime ( '-1 day' , strtotime ($date ) ) ) );
$next_day = date ('Y-m-j', (strtotime ( '+1 day' , strtotime ($date ) ) ) ) ;
$first_day= date('Y-m-j', mktime(0, 0, 0, date("n"), date("j") - date("N") + 1));

      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$date)) {
              list($dia1,$mes1,$anio1)=split("/",$date);
	         $date=$anio1."-".$mes1."-".$dia1;
	         $date =date ('Y-m-j',  strtotime ($date ) );
				$prev_day = date ('Y-m-j', (strtotime ( '-1 day' , strtotime ($date ) ) ) );
				$next_day = date ('Y-m-j', (strtotime ( '+1 day' , strtotime ($date ) ) ) ) ;
          }
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$date)) {
              list($dia1,$mes1,$anio1)=split("-",$date);
          /*/$date=$anio1."-".$mes1."-".$dia1;*/
          /*/$date =date ('Y-m-j', strtotime ( '+2 day' , strtotime ($date ) ) );*/
			}

/*/echo $date."<br>";*/



$nuevafecha =strtotime ( $date ) ;
$fecha = date ( 'Y-m-j' , $nuevafecha );

$nuevafecha1 = strtotime ( '+1 day' , strtotime ( $date ) ) ;
$fecha1 = date ( 'Y-m-j' , $nuevafecha1 );

$nuevafecha2 = strtotime ( '+2 day' , strtotime ($date ) ) ;
$fecha2 = date ( 'Y-m-j' , $nuevafecha2 );

$nuevafecha3 = strtotime ( '+3 day' , strtotime ( $date ) ) ;
$fecha3 = date ( 'Y-m-j' , $nuevafecha3 );

$nuevafecha4 = strtotime ( '+4 day' , strtotime ( $date ) ) ;
$fecha4 = date ( 'Y-m-j' , $nuevafecha4 );

$nuevafecha5 = strtotime ( '+5 day' , strtotime ( $date ) ) ;
$fecha5 = date ( 'Y-m-j' , $nuevafecha5 );

$nuevafecha6 = strtotime ( '+6 day' , strtotime ( $date ) ) ;
$fecha6 = date ( 'Y-m-j' , $nuevafecha6 );

 $dia[0]= dia_semana_avr( date ('w',$nuevafecha))." ". date ( 'j' , $nuevafecha )." ".mes_avre( date ( 'm' , $nuevafecha ));
 $dia[1]= dia_semana_avr( date ('w',$nuevafecha1))." ". date ( 'j' , $nuevafecha1 )." ".mes_avre( date ( 'm' , $nuevafecha1 ));
 $dia[2]= dia_semana_avr( date ('w',$nuevafecha2))." ". date ( 'j' , $nuevafecha2 )." ".mes_avre( date ( 'm' , $nuevafecha2 ));
 $dia[3]= dia_semana_avr( date ('w',$nuevafecha3))." ". date ( 'j' , $nuevafecha3 )." ".mes_avre( date ( 'm' , $nuevafecha3 ));
 $dia[4]= dia_semana_avr( date ('w',$nuevafecha4))." ". date ( 'j' , $nuevafecha4 )." ".mes_avre( date ( 'm' , $nuevafecha4 ));
 $dia[5]= dia_semana_avr( date ('w',$nuevafecha5))." ". date ( 'j' , $nuevafecha5 )." ".mes_avre( date ( 'm' , $nuevafecha5 ));
 $dia[6]= dia_semana_avr( date ('w',$nuevafecha6))." ". date ( 'j' , $nuevafecha6 )." ".mes_avre( date ( 'm' , $nuevafecha6 ));

$diasasig=array($fecha, $fecha1, $fecha2, $fecha3, $fecha4, $fecha5, $fecha6);

$hoy=date('Y-m-j');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../css3/table.css" media="screen" />
<link rel="stylesheet" type="text/css" href="../../css3/SearchBox.css" media="screen"/>

<script type="text/javascript" src="../../js3/jquery.min.js"></script>

<link rel="stylesheet" href="../../css3/jquery.toastmessage.css" type="text/css">
<script src="../../js3/jquery.toastmessage.js" type="text/javascript"></script>
<script src="../../js3/message.js" type="text/javascript"></script>

<link rel="stylesheet" href="../../css3/colorbox.css" />
<script src="../../js3/jquery.colorbox.js"></script>


<link media="all" href="../../css3/calendarmain.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="../../css3/tooltipster.css" />
    <script type="text/javascript" src="../../js3/jquery.tooltipster.min.js"></script>

<style>

.boton {
	-moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
	-webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
	box-shadow:inset 0px 1px 0px 0px #ffffff;
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #ededed), color-stop(1, #dfdfdf) );
	background:-moz-linear-gradient( center top, #ededed 5%, #dfdfdf 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#dfdfdf');
	background-color:#ededed;
	-webkit-border-top-left-radius:6px;
	-moz-border-radius-topleft:6px;
	border-top-left-radius:6px;
	-webkit-border-top-right-radius:6px;
	-moz-border-radius-topright:6px;
	border-top-right-radius:6px;
	-webkit-border-bottom-right-radius:6px;
	-moz-border-radius-bottomright:6px;
	border-bottom-right-radius:6px;
	-webkit-border-bottom-left-radius:6px;
	-moz-border-radius-bottomleft:6px;
	border-bottom-left-radius:6px;
	text-indent:0;
	border:1px solid #dcdcdc;
	display:inline-block;
	color:#777777;
	font-family:arial;
	/*font-size:15px;*/
	font-weight:bold;
	font-style:normal;

	line-height:14px;

	text-decoration:none;
	text-align:center;
	text-shadow:1px 1px 0px #ffffff;
	padding: 2px;
}
.boton:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #dfdfdf), color-stop(1, #ededed) );
	background:-moz-linear-gradient( center top, #dfdfdf 15%, #ededed 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfdfdf', endColorstr='#ededed');
	background-color:#dfdfdf;
}.boton:active {
	position:relative;
	top:1px;
}
select {
    border: 1px solid gray;
    padding: 2px;
    margin: 5px 0px;
    width: 400px;
    background-color: rgb(242, 242, 242);
    font-family: Arial,sans-serif,georgia,verdana;
}
</style>

<script type="text/javascript">
var newItemShow='';
var newItemShowN='';
var newItemInput='';
var newItemInputId='';
var newItemInputValue='';
var newItemCombo='';
var newItemComboN='';
var trigerclass='';
var estudio='';
var oid='';

$(document).ready( function()
{

	$(".callbacks").colorbox({
		iframe:true, width:"800px", height:"98%",
		onCleanup:function(){ window.location.reload(); }
	});

	$(".delbacks").colorbox({
		iframe:true, width:"80px", height:"55px",
		onCleanup:function(){ window.location.reload(); }
	});

	$(".selectbacks").colorbox({
		iframe:true, width:"380px", height:"555px",
		onCleanup:function(){ window.location.reload(); }
	});
				
$('.tooltip').tooltipster();  
  

$('.unhide-earlier').live('click', function(e) {
	e.preventDefault();
	$('.earlier').toggle();
	return false;
	}
	); 
$('.unhide-later').live('click', function(e) {
	e.preventDefault();
	$('.later').toggle();
	return false;
	}
	); 


  $('.TriggerShow').click( function(e){
      newItemShow='newItemESShow';
      newItemShowN='#newItemESShow';


		var cate=$('#category').val();
		var typ=$('#type').val();
		
	  	if ( this.id!="0") {
			var url="ficha.php?oid="+this.id;
			window.frames['datos'].location.replace(url);  
	  	}
      $(newItemShowN).toggle("slow","swing");

      e.preventDefault();
   } );//Finaliza trigger

  $('.triggerClose').click( function(e){


      $(newItemShowN).toggle("slow","swing");

      e.preventDefault();
   } );//Finaliza triggerClose
     
	  
});
</script>

<script type="text/javascript">      
      
      function UpdateTableHeaders() {
   $(".persist-area").each(function() {
   
       var el             = $(this),
           offset         = el.offset(),
           scrollTop      = $(window).scrollTop(),
           floatingHeader = $(".floatingHeader", this)
       
       if ((scrollTop > offset.top) && (scrollTop < offset.top + el.height())) {
           floatingHeader.css({
            "visibility": "visible"
           });
       } else {
           floatingHeader.css({
            "visibility": "hidden"
           });      
       };
   });
}

// DOM Ready      
$(function() {

   var clonedHeaderRow;

   $(".persist-area").each(function() {
       clonedHeaderRow = $(".persist-header", this);
       clonedHeaderRow
         .before(clonedHeaderRow.clone())
         .css("width", clonedHeaderRow.width())
         .addClass("floatingHeader");
         
   });
   
   $(window)
    .scroll(UpdateTableHeaders)
    .trigger("scroll");
   
});
</script>
<style type="text/css">

.floatingHeader {
  position: fixed;
  top: 0;
  visibility: hidden;
}
</style>


</head>
<body>
<body>

    <noscript>
    <style type="text/css">#background, #footer {display:none;} body {background: #eee;}</style>
    <div style="width: 740px; margin: 0px auto; background: #fff; padding: 15px; margin-top: 20px;">
        <h2></h2>
        </p>
    </div>
    </noscript>

        <div class="border-rounded-bottom" id="container">

<div id="content-header">

    <div id="buttons" class="noprint">
   
        <a class="button smaller gray right" href="calendario.php?defaultDate=<?php echo $next_day;?>" oldtitle="próximo día" title="">></a>
        <a class="button smaller gray right" href="calendario.php?defaultDate=<?php echo $prev_day;?>" oldtitle="día previo" title=""><</a> 
        <div class="button smaller gray nopadding">
       
        <input type="hidden" value="" id="defaultDate" name="defaultDate"
         onchange="location.href='calendario.php?defaultDate='+this.value;">
        <img class="button image orange" src="images/calendar.png" alt="Elija fecha" width="80%" height="80%"
         onclick="javascript:NewCssCal('defaultDate', 'ddMMyyyy')" title="Elija fecha">
         </div> 
        <a class="button smaller gray" href="calendario.php?defaultDate=<?php echo date('Y-m-j');?>" oldtitle="go to today view" title="">Para hoy</a> 
        <a class="button smaller gray" href="calendario.php?defaultDate=<?php echo $first_day;?>" oldtitle="Vista semanal" title="">Esta semana</a> 
        <div class="nopadding"><div class="clearer">
  <font size="+2" class="titulo-negrita">&nbsp;Del&nbsp;<?php echo dia_semana( date ('w',$nuevafecha))." ". date ( 'j' , $nuevafecha );?> de <?php echo mes( date ( 'm' , $nuevafecha ));?>
     al <?php echo dia_semana( date ('w',$nuevafecha6))." ". date ( 'j' , $nuevafecha6 );?> de <?php echo mes( date ('m', $nuevafecha6));?> de <?php echo date ('Y', $nuevafecha6);?>
     </font></div></div>
    </div>
</div>

<div class="clearer"></div>

<?php
?>
<article class="persist-area">
<ul class="week">
    <li class="persist-header" style="z-index: 1000; width:100%">
        <div class="earlier-block">
            <a href="" class="unhide-earlier">Antes</a>
        </div>
        <div class="dates">
        <?php
        	for ($t=0;$t<=6; $t++) {
        		if ($diasasig[$t]==$hoy) {
        ?>
                            <div class="datetoday"><?php echo $dia[$t];?>
                </div>
        <?php
				} else {
        ?>
                            <div class="date"><?php echo $dia[$t];?>
                </div>
        <?php
					
				}        
        }
        ?>
                    </div>
    </li>


<?php
$horas = array();
$y=0;
foreach (range(0,23) as $fullhour) 
{
    $parthour = $fullhour;
    /*/$sufix = $fullhour > 11 ? " pm" : " am";*/

    $horas[$y] = $parthour.":00";
    $y++;
    $horas[$y] = $parthour.":30";
    $y++;
}

for ($x=0; $x<=47; $x++) {
/*/Recorro todas las horas*/
	
	if ($x<=15) {	
?>        
      <li class="earlier" style="display:none;">
<?php
	} elseif($x>=42) {
?>	
		<li class="later" style="display:none;">
<?php
	} else {
?>
		<li>
<?php
	}
	$ESPAR=par($x);
if ($ESPAR==0) {
/*/Encabezo si es par*/	
?>
       <div class="bold hour"></div>

<?php
} else {/*/Fin encabezo si es par*/
?>
		<div class="bold hour"><?php echo $horas[$x];?></div>
<?php
}


foreach ($diasasig as $getdia) {
/*/Recorro para cada día cada hora*/
	
//$sql="select * from `estudios` where `fecha` = '".$getdia."' and `horainiagen`='".$x."' order by `horainiagen` DESC ";
/*/echo $sql."<br>";*//*
$con=mysqli_query( $conectar, $sql) or die ('Error');
		if($row=mysqli_fetch_array($con)) { //Si encuentro muestro
			$oid=$row['oid'];
			$oidpaciente=$row['oidpaciente'];
			$horainiagen=$row['horainiagen'];
			$horafinagen=$row['horafinagen'];
			$newoidmedico=$row['newoidmedico'];
			$oidtipoestudio=$row['oidtipoestudio'];
			$newoidtipoestudio=$row['newoidtipoestudio'];*/
			/*/echo $row['oid']."<br>";*//*
		   $paciente="";
		   $estudio="";
		   $medico="";
			$difhoras=$horafinagen-$horainiagen;
			$alto=36;
			$top=-5;

			 $ESPAR=par($horainiagen);*/
			/*/if ($ESPAR!=0) {*/
			/*/	$top=19;*/
			/*/}*//*
			if ($difhoras>=2) {
			$alto=36*$difhoras;	
			}
			if ($difhoras>=3) {
			$alto=30*$difhoras;	
			}
			if ($difhoras>=4) {
			$alto=36*$difhoras;	
			}
			if ($difhoras>=5) {
			$alto=36*$difhoras;	
			}		
			*/
			if ($getdia==$hoy) {
				?>
				<div class="appointmentstoday" style="height:36px">
				<?php				
			} else {
				?>
				<div class="appointments" style="height:36px">
				<?php				
			}
?>
                            <div class="appointment placeholder">         </div>
                                 <div class="appointment confirmed" style="position:relative; height:<?php echo $alto;?>px; top:<?php echo $top;?>px;">
                                 <?php
                                 /*
                                    $sqlpaciente="select * from `pacientes` where `oid`= '$oidpaciente'";
                                    $conpaciente=mysqli_query( $conectar, $sqlpaciente) or die ('error pacietne');
                                    if ($pacrow=mysqli_fetch_array($conpaciente)){
                                    	$paciente=$pacrow['nombre']." <br>".$pacrow['apellido'];	
                                    }
                                    */
                                    /*
                                    $sqlestudio="select * from `tipoestudio` where `oid`= '$oidtipoestudio'";
                                    $conestudio=mysql_query($sqlestudio, $conectar) or die ('error pacietne');
                                    if ($estrow=mysql_fetch_array($conestudio)){
                                    	$estudio =$estrow['descripcion'];	
                                    }
                                    */
/*/////////////////////////////////////////////////////////////////////*/
					if (!empty($newoidtipoestudio)){
						$newoidtipoestudio=$newoidtipoestudio;
					} else {
						$newoidtipoestudio=$oidtipoestudio;
					}
					/*/echo $newoidtipoestudio."---<br>";*/
					$array=split('-',$newoidtipoestudio);
					if (!is_array($array)){
						$array[0]=$newoidtipoestudio;
					}
					/*
					foreach ($array as $key => $value) {
						$tipoestudio="select  * from `tipoestudio` where `oid`='".$value."'";
						///echo $tipoestudio."<br>";
						$tipoestudio_con=mysqli_query( $conectar, $tipoestudio) or die ("Error al obtener datos del tipo estudio II");
						while ($tipoestudio_row=mysqli_fetch_array($tipoestudio_con))
						{
							$estudio="&nbsp;".$tipoestudio_row['descripcion'];
                  }               
              }               
/*/////////////////////////////////////////////////////////////////////*/   
/*                                 
												if(!empty($newoidmedico)) {
												$med=split('-',$newoidmedico);
												
												if (is_array($med)) {
												foreach ($med as $xmed)
												{
												$sql_med="select * from `contactos` where `oid`='$xmed'";
												$res_med=mysqli_query( $conectar, $sql_med) or die ("No se obtiene enfermero");
												   if ($linea=mysqli_fetch_array($res_med)){
												   	;
												   $medico.=str_replace(" ", "&nbsp;", $linea['nombre'].' '.$linea['apellido'])."<br>";
												   }
												}    
												} else {
												$sql_med="select * from `contactos` where `oid`='$newoidmedico'";
												$res_med=mysqli_query( $conectar, $sql_med) or die ("No se obtiene enfermero");
												   if ($linea=mysqli_fetch_array($res_med)){
												   $medico.=$linea['nombre'].' '.$linea['apellido']."<br>";
												   }
												
												}
												} */
												$newoidmedico="";                              
                                    ?>
                            <img src="images/lista.png" class="TriggerShow boton tooltip" id="<?php echo $oidpaciente;?>" alt=" " title="<?php echo $paciente;?>">
                            <img src="images/description.png" class="tooltip" alt="" title="<?php echo $estudio;?>" >
									<?php if (trim($newoidtipoestudio) == '1611' ) { ?>
									<a class="callbacks" href="ConsultaForm.php?accion=UPD&oid=<?php echo $oid;?>&oidpaciente=<?php echo $oidpaciente;?>" alt="" title="" aria-describedby="ui-tooltip-88" >
									<?php }  else { ?>                                                                                
                           <a class="callbacks" href="EstudiosForm.php?accion=UPD&oid=<?php echo $oid;?>&oidpaciente=<?php echo $oidpaciente;?>" alt="" title="" aria-describedby="ui-tooltip-88">
                           <?php } ?>
                                 *&nbsp;<?php echo $medico;
                                 $medico="";?>
                                 *&nbsp;<?php echo substr($estudio, 0, 17);
                                    ?>                                 
                                </a>
                            </div>
                </div>	               
<?php
		//} else { // Si no encuentro muestro
			/*/$ESPAR=par($x);*/
			/*/if ($ESPAR==0) { */		
			if ($getdia==$hoy) {
				?>
				<div class="appointmentstoday" style="height:36px">
                    <div class="appointmenttoday placeholdertoday">
				<?php				
			} else {
				?>
				<div class="appointments" style="height:36px">
                    <div class="appointment placeholder">
				<?php				
			}
?>
	                                
                        <a href="listado_pacientes.php?fecha=<?php echo $getdia;?>&hora=<?php echo $x;?>" class="tooltip"
                         title="Ingresa nueva coordinación seleccionando paciente" target="contenido">+ nuevo</a>
                    </div>
                </div>

<?php		
			//}
		//}
	} /*/Recorro para cada dia cada hora*/
/*//////////////////////////////////////////////////////////////////////////////////*/
if ($x==15) {
	/*/Muestro los que no tienen hora asignada*/
?>
            <div class="clearer"></div>
            
		</li>
      <li>
       <div class="bold hour">&nbsp;</div>
<?php
foreach ($diasasig as $getdia) {
?>
<div class="appointmentsblank" style="height:36px">
<?php
/*
$sql="select * from `estudios` where `fecha` = '".$getdia."' and (`horainiagen` IS NULL or `horainiagen` ='-1') order by `horainiagen` DESC ";
/echo $sql."<br>";*/
/*
$con=mysqli_query( $conectar, $sql) or die ('Error estudios sin hora');
if (mysqli_num_rows($con)>=1) {
		while($row=mysqli_fetch_array($con)) { /Si encuentro muestro*/
		/*
			$oid=$row['oid'];
			$oidpaciente=$row['oidpaciente'];
			$horainiagen=$row['horainiagen'];
			$horafinagen=$row['horafinagen'];
			$newoidmedico=$row['newoidmedico'];
			$oidtipoestudio=$row['oidtipoestudio'];
			$newoidtipoestudio=$row['newoidtipoestudio'];
			//echo $row['oid']."<br>";*//*
		   $paciente="";
		   $estudio="";
		   $medico="";
			$difhoras=$horafinagen-$horainiagen;
			$alto=36;
			$top=-5;
*/

?>

                   
                            <div class="appointmentblank placeholder">         </div>
                                 <div class="appointment tentative" style="position:relative; height:<?php echo $alto;?>px; top:<?php echo $top;?>px;">
                                 <?php
                                 /*
                                    $sqlpaciente="select * from `pacientes` where `oid`= '$oidpaciente'";
                                    $conpaciente=mysqli_query( $conectar, $sqlpaciente) or die ('error pacietne');
                                    if ($pacrow=mysqli_fetch_array($conpaciente)){
                                    	$paciente=$pacrow['nombre']." <br>".$pacrow['apellido'];	
                                    }
                                    */
                                    /*
                                    $sqlestudio="select * from `tipoestudio` where `oid`= '$oidtipoestudio'";
                                    $conestudio=mysql_query($sqlestudio, $conectar) or die ('error pacietne');
                                    if ($estrow=mysql_fetch_array($conestudio)){
                                    	$estudio =$estrow['descripcion'];	
                                    }
                                    */
/*//////////////////////////////////////////////////////////////////////*/
/*
					if (!empty($newoidtipoestudio)){
						$newoidtipoestudio=$newoidtipoestudio;
					} else {
						$newoidtipoestudio=$oidtipoestudio;
					}
					
					//echo $newoidtipoestudio."---<br>";*/
					/*
					$array=split('-',$newoidtipoestudio);
					if (!is_array($array)){
						$array[0]=$newoidtipoestudio;
					}
					foreach ($array as $key => $value) {
						$tipoestudio="select  * from `tipoestudio` where `oid`='".$value."'";
						//echo $tipoestudio."<br>";*/
						/*$tipoestudio_con=mysqli_query( $conectar, $tipoestudio) or die ("Error al obtener datos del tipo estudio II");
						while ($tipoestudio_row=mysqli_fetch_array($tipoestudio_con))
						{
							$estudio="&nbsp;".$tipoestudio_row['descripcion'];
                  }               
              }               
/*/////////////////////////////////////////////////////////////////////*/     
/*                                 
												if(!empty($newoidmedico)) {
												$med=split('-',$newoidmedico);
												
												if (is_array($med)) {
												foreach ($med as $xmed)
												{
												$sql_med="select * from `contactos` where `oid`='$xmed'";
												$res_med=mysqli_query( $conectar, $sql_med) or die ("No se obtiene enfermero");
												   if ($linea=mysqli_fetch_array($res_med)){
												   $medico.=$linea['nombre'].' '.$linea['apellido']."<br>";
												   }
												}    
												} else {
												$sql_med="select * from `contactos` where `oid`='$newoidmedico'";
												$res_med=mysqli_query( $conectar, $sql_med) or die ("No se obtiene enfermero");
												   if ($linea=mysqli_fetch_array($res_med)){
												   $medico.=$linea['nombre'].' '.$linea['apellido']."<br>";
												   }
													
												}
												}
												*/
                                		$newoidmedico="";
                                    ?>
                            <img src="images/lista.png" class="TriggerShow boton tooltip" id="<?php echo $oidpaciente;?>" alt=" " title="<?php echo $paciente;?>">
                            <img src="images/description.png" class="tooltip" alt="" title="<?php echo $estudio;?>" >
									<?php if (trim($newoidtipoestudio) == '1611' ) { ?>
									<a class="callbacks" href="ConsultaForm.php?accion=UPD&oid=<?php echo $oid;?>&oidpaciente=<?php echo $oidpaciente;?>" alt="" title="" aria-describedby="ui-tooltip-88" >
									<?php }  else { ?>                                                                                
                           <a class="callbacks" href="EstudiosForm.php?accion=UPD&oid=<?php echo $oid;?>&oidpaciente=<?php echo $oidpaciente;?>" alt="" title="" aria-describedby="ui-tooltip-88">
                           <?php } ?>                            
                                 *&nbsp;<?php echo $medico;
                                 $medico="";?>
                                 *&nbsp;<?php
                                 echo substr($estudio, 0, 17);
                                    ?>
                                </a>
                            </div>
                 
                       
<?php
	} // Fin de si encuentro muestro
} else { // Si no encuentro muestro
			//$ESPAR=par($x);
			//if ($ESPAR==0) { 		
			if ($getdia==$hoy) {
				?>
				<div class="appointmentstoday" style="height:36px">
				
                    <div class="appointmenttoday placeholdertoday" 
style="background: none repeat scroll 0 0 #DDDDDD; border: 0;">
				<?php				
			} else {
				?>
				<div class="appointmentsblank" style="height:36px">
                    <div class="appointmentblank placeholder">
                    
				<?php				
			}			
?>		
   
                    </div>
                </div>

 
<?php		
			//}
}	
?>                 
</div>	 
<?php

		//}

//} //Fin Muestro los que no tienen hora asignada

?>                 
            <div class="clearer"></div>
            
		</li>                
<?php

	
} //para todas las horas
?>                

        <li class="white">
        <div class="later-block">
            <a class="unhide-later" href="">Despúes</a>
        </div>
    </li>
</ul>
</article>

<div class="clearer"></div>  </div><!-- close #container -->


<div  id="newItemESShow" style="display:none; padding:5px; border: 5px solid #E63C1E; width: 240px; height: 430px; position:fixed; float:right; top: 30px; right:10px; margin-right: 5px; z-index: 999; box-shadow: 10px 10px 5px #888;" class="boton" >
<div id="nombre">&nbsp;</div><p>
<iframe width="98%" height="395px" name="datos" id="datos" scrolling="no" frameborder="10" src="#" style="vertical-align: top;">
 </iframe>
 <div class="seleccione"><div style="position:absolute; right:10px; top:3px"> 
         <img id="newItemES" src="images/minus.png" width="16" height="16" vspace="0" hspace="0" align="left" border="0" 
         style="cursor:pointer;" class="triggerClose boton"></div>
</div>
</div>

</body>

</html>