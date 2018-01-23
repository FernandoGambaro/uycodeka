<?php
require_once '../class/PHPExcel.php';

include("conexion.php");
setlocale (LC_ALL, 'et_EE.ISO-8859-1');


function  cambiaf_a_mysql( $fecha ){
 ereg (  "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})" ,  $fecha ,  $mifecha );
 $fechafinal = $mifecha [ 3 ]. "-" . $mifecha [ 2 ]. "-" . $mifecha [ 1 ];
return  $fechafinal ;
}

$Date=cambiaf_a_mysql(date("d/m/Y"));

if ($ordenado == "" or $orden == "")
{
$ordenado="CALIDADID";
$orden="ASC";
}

$ACTIVIDADID=$_GET['ACTIVIDADID'];
$ANIOID=$_GET['ANIOID'];

if ($ANIOID=="") {
$ANIOID=$s->data['ANIOID'];
}
$criterio="WHERE ";

	if ($ACTIVIDADID !="" and $ANIOID !="") {
		$criterio="WHERE `ANIOID` = '$ANIOID' and `ACTIVIDADID` = '$ACTIVIDADID'";
		}


//echo "<br>".$criterio;

	$ssql=" SELECT * FROM `ORGANIZACION` " .$criterio. " ORDER BY `$ordenado` $orden , CALIDADID ASC, ORGANIZACIONORDEN ASC, CONTACTOSID ASC";
//echo "<br>".$ssql;


	$rs = @mysqli_query( $conectar, $ssql);

// Fin consulto limite
function  cambiaf_a_normal( $fecha ){
 ereg (  "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})" ,  $fecha ,  $mifecha );
 $fechafinal = $mifecha [ 3 ]. "/" . $mifecha [ 2 ]. "/" . $mifecha [ 1 ];
return $fechafinal;
 }

		$c_act=" SELECT * FROM `ACTIVIDAD` WHERE `ACTIVIDADID`='$ACTIVIDADID'";
		$b_act = mysqli_query($GLOBALS["___mysqli_ston"], $c_act);
		while($r_act= mysqli_fetch_array($b_act)){
			if ($r_act['ACTIVIDADSECTOR']!="") {
			$ACTIVIDADSECTOR=$r_act['ACTIVIDADSECTOR'];
			}
			$listado=$r_act['ACTIVIDADDESCRIPCION']."-".$ACTIVIDADSECTOR;
		}


$file = "tmp/HorasProyectoUsuario-".$listado."-".$Date.".xlsx";

//echo $file;


$objPHPExcel = new PHPExcel();
// Set properties
$objPHPExcel->getProperties()->setCreator("Detalles Horas Proyecto")
->setLastModifiedBy("")
->setTitle("Office 2007 XLSX")
->setSubject("Office 2007 XLSX")
->setDescription("")
->setKeywords("office 2007")
->setCategory("Archivo");
$objPHPExcel->getActiveSheet()->setTitle('');


$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);


$fila=1;


//------------Titulo----------------
$objPHPExcel->setActiveSheetIndex(0)
->setCellValueByColumnAndRow(0, $fila, "")
->setCellValueByColumnAndRow(1, $fila, "Integrantes ".$listado)
->setCellValueByColumnAndRow(2, $fila, "")
->setCellValueByColumnAndRow(3, $fila, "")
->setCellValueByColumnAndRow(4, $fila, "")
->setCellValueByColumnAndRow(5, $fila, "")
->setCellValueByColumnAndRow(6, $fila, "");

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

$fila++;

$objPHPExcel->getActiveSheet()->mergeCells('B1:G1');
$objPHPExcel->getActiveSheet()->getStyle('B1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $fila, "");
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $fila, "Nº");
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, "Nombre y Apellido");
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, "Cargo");
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, "Teléfono");
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, "Celular.");
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, "E-Mail.");

$fill = array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => 'C2C2C2') );
$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,$fila)->getFill()->applyFromArray($fill);
$fill = array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => 'C2C2C2') );
$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,$fila)->getFill()->applyFromArray($fill);
$fill = array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => 'C2C2C2') );
$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,$fila)->getFill()->applyFromArray($fill);
$fill = array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => 'C2C2C2') );
$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,$fila)->getFill()->applyFromArray($fill);
$fill = array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => 'C2C2C2') );
$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5,$fila)->getFill()->applyFromArray($fill);
$fill = array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => 'C2C2C2') );
$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6,$fila)->getFill()->applyFromArray($fill);

for ($i=1; $i <7; $i++) {
$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,$fila)->applyFromArray($styleArray);
}

//unset($styleArray);
//$objPHPExcel->getActiveSheet()->getRowDimension($fila)->setRowHeight(-1);
//$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
//$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(7);
//$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(19);
//$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
//$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
//$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
//$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(27);



$impresos=1;
$fila++;

   while($DATA = mysqli_fetch_array($rs)){
      $contactos=" SELECT * FROM `CONTACTOS` WHERE CONTACTOSID=".$DATA[CONTACTOSID]."";
      $r_contactos = mysqli_query($GLOBALS["___mysqli_ston"], $contactos);
      while($DATACON = mysqli_fetch_array($r_contactos)){

	$c_usuario=" SELECT * FROM `CALIDAD` WHERE `CALIDADID`= '$DATA[CALIDADID]'";
	$b_usuario = mysqli_query($GLOBALS["___mysqli_ston"], $c_usuario);
	while($r_usuario= mysqli_fetch_array($b_usuario)){
	$CALIDADDESCRIPCION=$r_usuario['CALIDADDESCRIPCION'];
	}
	 //echo $fila."hola<br>";
	 $objPHPExcel->setActiveSheetIndex(0)
	 ->setCellValueByColumnAndRow(0, $fila, $impresos.")")
	 ->setCellValueByColumnAndRow(1, $fila, $DATACON['CONTACTOSNUMERO']." " )
	 ->setCellValueByColumnAndRow(2, $fila, $DATACON['CONTACTOSNOMBRE']." ".$DATACON['CONTACTOSAPELLIDO'])
	 ->setCellValueByColumnAndRow(3, $fila, $CALIDADDESCRIPCION." ")
	 ->setCellValueByColumnAndRow(4, $fila, $DATACON['CONTACTOSTELEFONO']." ")
	 ->setCellValueByColumnAndRow(5, $fila, $DATACON['CONTACTOSCELULAR']." ")
	 ->setCellValueByColumnAndRow(6, $fila, $DATACON['CONTACTOSMAILPRI']." ");
	 
	 $fill = array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => 'C2C2C2') );
	 
	 $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0,$fila)->getFill()->applyFromArray($fill);
     $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(1,$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
   //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(2,$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
   //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(3,$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
     $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(4,$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
     $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(5,$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
   //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(6,$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	 
	 //for ($i=1; $i <7; $i++) {
	 //$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i,$fila)->applyFromArray($styleArray);
	 //}
       }

$fila++;
$impresos=$impresos+1;

}

$objPHPExcel->setActiveSheetIndex(0)
->setCellValueByColumnAndRow(0,$fila, "")
->setCellValueByColumnAndRow(1,$fila, "" )
->setCellValueByColumnAndRow(2,$fila, "")
->setCellValueByColumnAndRow(3, $fila, "")
->setCellValueByColumnAndRow(4, $fila, "")
->setCellValueByColumnAndRow(5, $fila, "")
->setCellValueByColumnAndRow(6, $fila, "");


require_once '../class/PHPExcel/IOFactory.php';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
// Si queremos crear un PDF
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
$objWriter->save($file);



?>
<html><head><TITLE></TITLE></head>
<body onload="window.open('<?php echo $file;?>');window.close();">
<a href="<?php echo $file;?>">Ver Archivo</a>
</body>
</html>