<?php


define('FPDF_FONTPATH','font/');
require('mysql_table.php');
include("comunes_factura.php");
include ("../conectar.php");
include ("../funciones/fechas.php"); 

$pdf=new FPDF('L','mm',array(210,145));

$pdf->Open();
$pdf->SetMargins(4, 6 , 4);
$pdf->AddPage('L');

$pdf->Image('../img/facturabetty.png', 0, 0, 210, 145);

$pdf->AddFont('MyriadPro-LightSemiCn','','myriadpro-lightsemicn.php');

$pdf->SetAutoPageBreak('auto' ,3);

//$pdf->Ln(10);


//include ("../conectar.php");
  
$codfactura=$_GET["codfactura"];
  
$consulta = "Select *, facturas.tipo as facturatipo from facturas,clientes where facturas.codfactura='$codfactura' and facturas.codcliente=clientes.codcliente";
$resultado = mysqli_query( $conexion, $consulta);
$lafila=mysqli_fetch_array($resultado);


/*Fijo la línea de comienzo*/

if ($lafila["moneda"]==2){
$moneda="U".chr(36)."S";
} else {
$moneda="$";
}

    $pdf->Ln(9);					

/* Establezco el color de las celdas y tipo de letra */

   $pdf->Cell(107); /* ubicación inicial de la celdas */


   $pdf->SetFont('MyriadPro-LightSemiCn','',8);
   $fecha = implota($lafila["fecha"]);
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(20,4,$fecha,0,0,'C',0);	

	$tipo = array( 0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
	$num=$lafila['facturatipo'];
	$tipof=$tipo[$num];

	$pdf->Cell(40,4,$tipof,0,0,'C',0);

	$pdf->Cell(15,4,$codfactura,0,0,'C',0);		
	

	$pdf->Ln(11);


/* --------- */

    $pdf->Cell(121); /* ubicación inicial de la celdas*/
	
	$pdf->Cell(20,4,$lafila["codcliente"],0,0,'',0);

	$pdf->Ln(6);

   $pdf->Cell(130); /* ubicación inicial de la celdas*/

if($lafila["empresa"]!='') {
	$nombre=$lafila["empresa"];
} else {
	$nombre=$lafila["nombre"]." ".$lafila["apellido"];
}

	$pdf->Cell(40,4,$nombre,0,0,'',0);

    $pdf->Cell(56); /* ubicación inicial de la celdas*/
	
   $pdf->Cell(30,4,"RUT COMPRADOR",0,0,'C',0);
   $pdf->Cell(10,4,"C. FINAL",1,0,'C',1);

	$pdf->Ln(5);

    $pdf->Cell(123); /* ubicación inicial de la celdas*/


	$pdf->Cell(50,4,$lafila["direccion"],'0',0,'L',0);

	$pdf->Ln(9);

	$pdf->Cell(26);

	
   if (empty($lafila["nif"])){
   	$consumo="X";
   	} else {
   	$nif=$lafila["nif"];
   }
   $pdf->Cell(20,4,$nif,0,0,'L',0);
   
	$pdf->Cell(37);   
	$pdf->Cell(10,4,$consumo,0,0,'C',0);		
	
	//ahora mostramos las líneas de la factura
	$pdf->Ln(13);		
	
	$consulta32 = "Select * from factulinea where codfactura='$codfactura' order by numlinea";
    $resultado32 = mysqli_query( $conexion, $consulta32);
    
	$contador=0;
	while ($row=mysqli_fetch_array($resultado32))
	{
	$pos=0;

	  $pdf->Cell(4);
	  //$contador++;
	  $codarticulo=mysqli_result($resultado32, $lineas, "codigo");
	  $codfamilia=mysqli_result($resultado32, $lineas, "codfamilia");

	  $detallesA=$detalles=mysqli_result($resultado32, $lineas, "detalles");

	  $sel_articulos="SELECT * FROM articulos WHERE codarticulo='$codarticulo' AND codfamilia='$codfamilia'";
	  $rs_articulos=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulos);
	  
	  $pdf->Cell(20,4,mysqli_result($rs_articulos, 0, "referencia"),0,0,'L');
	  
	  $pdf->Cell(23,4,mysqli_result($resultado32, $lineas, "cantidad"),0,0,'C');	

 		$detallesA=$detalles.=mysqli_result($rs_articulos, 0, "descripcion");

		$largo_ini=strlen($detalles);
		if($largo_ini>100) {
			$largo=1;
			$texto_corto = substr($detalles, 0, 90);
			$pos = strripos($texto_corto,' ');
			if ($pos !== false) { 
    			$acotado = substr($detallesA, 0, $pos);
    			$resta=substr($detallesA, $pos, $largo_ini-$pos );
    			$pos=$pos+1;
			} else {	
				$acotado = substr($detallesA, 0, 90);
    			$resta=substr($detallesA, $pos, $largo_ini-$pos );
			}
		} else {
			$largo=0;
			$acotado = substr($detallesA, 0, 90);
		}

	  $pdf->Cell(110,4,$acotado,0,0,'L');

	  $importe32= number_format(mysqli_result($resultado32, $lineas, "importe"),2,",",".");	  
	  
	  $precio32= number_format(mysqli_result($resultado32, $lineas, "importe")/mysqli_result($resultado32, $lineas, "cantidad"),2,",",".");	 
	   
	  $pdf->Cell(15,4,$precio32,0,0,'R');
	  	  
	  $importe32= number_format(mysqli_result($resultado32, $lineas, "importe"),2,",",".");	  
	  
	  $pdf->Cell(20,4,$importe32,0,0,'R');
	  $pdf->Ln(4);
	  	
		$ini=$pos;
		while ($largo==1){
			$largo_ini=strlen($resta);
			if($largo_ini>90) {
				$largo=1;
				$texto_corto = substr($resta, 0, 90);
				$pos = strripos($texto_corto,' ');
				if ($pos !== false) { 
	    			$acotado = substr($detallesA, $ini, $pos);
	    			$resta=substr($detallesA, $ini+$pos, $largo_ini-$pos );
	    			$pos=$pos+1;
				} else {	
					$acotado = substr($detallesA, $ini, 90);
	    			$resta=substr($detallesA, $ini+$pos, $largo_ini-$pos );
				}
			} else {
				$largo=0;
				$acotado = substr($detallesA, $ini, 90);
			}
			$ini=$ini+$pos;	
		   $pdf->Cell(1);
	  		$pdf->Cell(8);
			$pdf->Cell(20,4,' ',0,0,'L');
			$pdf->Cell(23,4,' ',0,0,'C');	
			$pdf->Cell(110,4,$acotado,0,0,'L');
	  		$pdf->Cell(15,4,' ',0,0,'R');
	  		$pdf->Cell(20,4,' ',0,0,'R');
			$pdf->Ln(4);
		  $contador++;
		}
	  //vamos acumulando el importe
	  $importe=$importe + mysqli_result($resultado32, $lineas, "importe");
	  $contador++;
	  $lineas=$lineas + 1;	  
	};
	
	
	while ($contador<9)
	{
	  $pdf->Cell(1);
      $pdf->Cell(20,4,"",0,0,'C');
      $pdf->Cell(131,4,"",0,0,'C');
	  $pdf->Cell(15,4,"",0,0,'C');	
	  $pdf->Cell(15,4,"",0,0,'C');
	  $pdf->Cell(20,4,"",0,0,'C');
	  $pdf->Ln(4);	
	  $contador=$contador +1;
	}

	$pdf->Ln(10);		

	//$pdf->Ln(8);		

/**/
    $wmax=50; 
	 $s=str_replace("\r",'',utf8_decode( $lafila["observacion"])); 
    $nb=strlen($s); 
    if($nb>0 and $s[$nb-1]=="\n") 
        $nb--; 
    $sep=-1; 
    $i=0; 
    $j=0; 
    $l=0; 
    $nl=0; 
    $obs='';
    
    while($i<$nb and $nl<=7) 
    { 
        $c=$s[$i]; 
        if($c=="\n") 
        { 
            $i++; 
            $sep=-1; 
            $j=$i; 
            $l=0; 
            $nl++; 
            continue; 
        } 
        if($c==' ') 
            $sep=$i; 
        //$l+=$cw[$c]; 
        if($l>$wmax) 
        { 
            if($sep==-1) 
            { 
                if($i==$j) 
                    $i++; 
            } 
            else 
                $i=$sep+1; 
            $sep=-1; 
            $j=$i; 
            $l=0; 
            $nl++; 
        } 
        else{ 
            $obs[$nl].=$s[$i];
            $i++; 
        }
       
    }
/**/	

	
	$pdf->Ln(1);	
	
	$pdf->Ln(1);			
	$pdf->Cell(10);
   $pdf->Cell(50,4,$obs[0],0,0,'L',0);

	$pdf->Cell(49);
/*Sub-total*/   
   $importe4=number_format($importe,2,",",".");	
   $pdf->Cell(20,4,$moneda." ".$importe4,0,0,'R',0);

/*Descuento*/
	$descuento=$lafila["descuento"];
	$impodescuento=$impodesc=$importe*(1-$descuento/100);
	$impodesc=sprintf("%01.2f", $impodesc); 
	$impodesc=number_format($impodesc,2,",",".");
	
if ($descuento!=0)	{
   $pdf->Cell(20,4,$descuento."%",0,0,'R',0);
	$pdf->Cell(10);
} else {
   $pdf->Cell(20,4,"",0,0,'R',0);
	$pdf->Cell(10);
}
/*IVA*/
	$pdf->Cell(10,4,$lafila["iva"]."%",0,0,'L',0);

	$ivai=$lafila["iva"];
	$impoiva=$importeiva=$impodescuento*($ivai/100);
	$impoiva=sprintf("%01.2f", $impoiva); 
	$impoiva=number_format($impoiva,2,",",".");

//	$impo=$impo*($ivai/100);
	$total=round($impodescuento+$importeiva,2); 
   $total=sprintf("%01.2f", $total);
   $total=round($total, 0, PHP_ROUND_HALF_UP);
	$total2= number_format($total,2,",",".");	
	
	$pdf->Cell(22,4,$moneda." ".$total2,0,0,'R',0);


	$pdf->Ln(3);
	$pdf->Cell(10);
   $pdf->Cell(50,4,$obs[1],0,0,'L',0);



	$impo=number_format($impo,2,",",".");
	$pdf->Cell(70);
if ($descuento==0) {	
	$pdf->Cell(20,4," ",0,0,'R',0);
} else {
	$pdf->Cell(20,4,$moneda." ".$impodesc,0,0,'R',0);
}  
/*muestro iporte de iva*/
	$pdf->Cell(20,4,$moneda." ".$impoiva,0,0,'R',0);  

	$pdf->Ln(3);
	$pdf->Cell(10);
   $pdf->Cell(50,4,$obs[2],0,0,'L',0);
	$pdf->Ln(3);
	$pdf->Cell(10);
   $pdf->Cell(50,4,$obs[3],0,0,'L',0);
	$pdf->Ln(3);
	$pdf->Cell(10);
   $pdf->Cell(50,4,$obs[4],0,0,'L',0);
	$pdf->Ln(3);
	$pdf->Cell(10);
   $pdf->Cell(50,4,$obs[5],0,0,'L',0);
	$pdf->Ln(3);
	$pdf->Cell(10);
   $pdf->Cell(50,4,$obs[6],0,0,'L',0);


	$pdf->Ln(3);
	$pdf->Ln(3);
	$pdf->Ln(3);
	$pdf->Ln(3);
	$pdf->Cell(130);
   $pdf->Cell(30,4,"Original Cliente",0,0,'L',0);

      @((mysqli_free_result($resultado) || (is_object($resultado) && (get_class($resultado) == "mysqli_result"))) ? true : false); 
      @((mysqli_free_result($query) || (is_object($query) && (get_class($query) == "mysqli_result"))) ? true : false);
	  @((mysqli_free_result($resultado2) || (is_object($resultado2) && (get_class($resultado2) == "mysqli_result"))) ? true : false); 
	  @((mysqli_free_result($query3) || (is_object($query3) && (get_class($query3) == "mysqli_result"))) ? true : false);	
	
/* ************************************************************************************************   Segunda hoja  */
$detallesA='';
$detalles='';
$acotado='';
$pos='';
$texto_corto='';
$largo_ini='';
$largo='';
$lineas=0;
$lafila='';
$descuento=0;
$impoiva=0;
$ivai=0;
$total=0;
$total2=0;
$precio2=0;
$descuento=0;

$impo=$impodesc=$impodescuento=$importeiva=$importe=0;


$pdf->SetMargins(4, 6 , 4);
$pdf->AddPage('L');

//$pdf->Image('../img/facturabetty.png', 0, 0, 210, 145);

$pdf->AddFont('MyriadPro-LightSemiCn','','myriadpro-lightsemicn.php');

$pdf->SetAutoPageBreak(auto ,3);

$codfactura=$_GET["codfactura"];

$consulta = "Select *, facturas.tipo as facturatipo from facturas,clientes where facturas.codfactura='$codfactura' and facturas.codcliente=clientes.codcliente";
$resultado = mysqli_query( $conexion, $consulta);
$lafila=mysqli_fetch_array($resultado);


/*Fijo la línea de comienzo*/

if ($lafila["moneda"]==2){
$moneda="U".chr(36)."S";
} else {
$moneda="$";
}

    $pdf->Ln(9);					

/* Establezco el color de las celdas y tipo de letra */

   $pdf->Cell(107); /* ubicación inicial de la celdas */


   $pdf->SetFont('MyriadPro-LightSemiCn','',8);
   $fecha = implota($lafila["fecha"]);
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(20,4,$fecha,0,0,'C',0);	

	$tipo = array( 0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
	$num=$lafila['facturatipo'];
	$tipof=$tipo[$num];

	$pdf->Cell(40,4,$tipof,0,0,'C',0);

	$pdf->Cell(15,4,$codfactura,0,0,'C',0);		
	

	$pdf->Ln(11);


/* --------- */

    $pdf->Cell(120); /* ubicación inicial de la celdas*/
	
	$pdf->Cell(20,4,$lafila["codcliente"],0,0,'',0);

	$pdf->Ln(6);

   $pdf->Cell(130); /* ubicación inicial de la celdas*/

if($lafila["empresa"]!='') {
	$nombre=$lafila["empresa"];
} else {
	$nombre=$lafila["nombre"]." ".$lafila["apellido"];
}

	$pdf->Cell(40,4,$nombre,0,0,'',0);

    $pdf->Cell(56); /* ubicación inicial de la celdas*/
	
   $pdf->Cell(30,4,"RUT COMPRADOR",0,0,'C',0);
   $pdf->Cell(10,4,"C. FINAL",1,0,'C',1);

	$pdf->Ln(5);

    $pdf->Cell(123); /* ubicación inicial de la celdas*/


	$pdf->Cell(50,4,$lafila["direccion"],'0',0,'L',0);

	$pdf->Ln(9);

	$pdf->Cell(26);

	
   if (empty($lafila["nif"])){
   	$consumo="X";
   	} else {
   	$nif=$lafila["nif"];
   }
   $pdf->Cell(20,4,$nif,0,0,'L',0);
   
	$pdf->Cell(37);   
	$pdf->Cell(10,4,$consumo,0,0,'C',0);		
	
	//ahora mostramos las líneas de la factura
	$pdf->Ln(13);		
	
	$consulta2 = "Select * from factulinea where codfactura='$codfactura' order by numlinea";
    $resultado2 = mysqli_query( $conexion, $consulta2);
    
	$contador=0;
	while ($row=mysqli_fetch_array($resultado2))
	{
	$pos=0;

	  $pdf->Cell(4);
	  //$contador++;
	  $codarticulo=mysqli_result($resultado2, $lineas, "codigo");
	  $codfamilia=mysqli_result($resultado2, $lineas, "codfamilia");

	  $detallesA=$detalles=mysqli_result($resultado2, $lineas, "detalles");

	  $sel_articulos="SELECT * FROM articulos WHERE codarticulo='$codarticulo' AND codfamilia='$codfamilia'";
	  $rs_articulos=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulos);
	  
	  $pdf->Cell(20,4,mysqli_result($rs_articulos, 0, "referencia"),0,0,'L');
	  
	  $pdf->Cell(23,4,mysqli_result($resultado2, $lineas, "cantidad"),0,0,'C');	

 		$detallesA=$detalles.=mysqli_result($rs_articulos, 0, "descripcion");

		$largo_ini=strlen($detalles);
		if($largo_ini>100) {
			$largo=1;
			$texto_corto = substr($detalles, 0, 90);
			$pos = strripos($texto_corto,' ');
			if ($pos !== false) { 
    			$acotado = substr($detallesA, 0, $pos);
    			$resta=substr($detallesA, $pos, $largo_ini-$pos );
    			$pos=$pos+1;
			} else {	
				$acotado = substr($detallesA, 0, 90);
    			$resta=substr($detallesA, $pos, $largo_ini-$pos );
			}
		} else {
			$largo=0;
			$acotado = substr($detallesA, 0, 90);
		}

	  $pdf->Cell(110,4,$acotado,0,0,'L');

	  $importe2= number_format(mysqli_result($resultado2, $lineas, "importe"),2,",",".");	  
	  
	  $precio2= number_format(mysqli_result($resultado2, $lineas, "importe")/mysqli_result($resultado2, $lineas, "cantidad"),2,",",".");	 
	   
	  $pdf->Cell(15,4,$precio2,0,0,'R');
	  	  
	  $importe2= number_format(mysqli_result($resultado2, $lineas, "importe"),2,",",".");	  
	  
	  $pdf->Cell(20,4,$importe2,0,0,'R');
	  $pdf->Ln(4);
	  	
		$ini=$pos;
		while ($largo==1){
			$largo_ini=strlen($resta);
			if($largo_ini>90) {
				$largo=1;
				$texto_corto = substr($resta, 0, 90);
				$pos = strripos($texto_corto,' ');
				if ($pos !== false) { 
	    			$acotado = substr($detallesA, $ini, $pos);
	    			$resta=substr($detallesA, $ini+$pos, $largo_ini-$pos );
	    			$pos=$pos+1;
				} else {	
					$acotado = substr($detallesA, $ini, 90);
	    			$resta=substr($detallesA, $ini+$pos, $largo_ini-$pos );
				}
			} else {
				$largo=0;
				$acotado = substr($detallesA, $ini, 90);
			}
			$ini=$ini+$pos;	
		   $pdf->Cell(1);
	  		$pdf->Cell(8);
			$pdf->Cell(20,4,' ',0,0,'L');
			$pdf->Cell(23,4,' ',0,0,'C');	
			$pdf->Cell(110,4,$acotado,0,0,'L');
	  		$pdf->Cell(15,4,' ',0,0,'R');
	  		$pdf->Cell(20,4,' ',0,0,'R');
			$pdf->Ln(4);
		  $contador++;
		}
	  //vamos acumulando el importe
	  $importe=$importe + mysqli_result($resultado2, $lineas, "importe");
	  $contador++;
	  $lineas=$lineas + 1;	  
	};
	
	
	while ($contador<9)
	{
	  $pdf->Cell(1);
      $pdf->Cell(20,4,"",0,0,'C');
      $pdf->Cell(131,4,"",0,0,'C');
	  $pdf->Cell(15,4,"",0,0,'C');	
	  $pdf->Cell(15,4,"",0,0,'C');
	  $pdf->Cell(20,4,"",0,0,'C');
	  $pdf->Ln(4);	
	  $contador=$contador +1;
	}

	$pdf->Ln(8);		
	

/**/
    $wmax=50; 
	 $s=str_replace("\r",'',utf8_decode( $lafila["observacion"])); 
    $nb=strlen($s); 
    if($nb>0 and $s[$nb-1]=="\n") 
        $nb--; 
    $sep=-1; 
    $i=0; 
    $j=0; 
    $l=0; 
    $nl=0; 
    $obs='';
    
    while($i<$nb and $nl<=7) 
    { 
        $c=$s[$i]; 
        if($c=="\n") 
        { 
            $i++; 
            $sep=-1; 
            $j=$i; 
            $l=0; 
            $nl++; 
            continue; 
        } 
        if($c==' ') 
            $sep=$i; 
        //$l+=$cw[$c]; 
        if($l>$wmax) 
        { 
            if($sep==-1) 
            { 
                if($i==$j) 
                    $i++; 
            } 
            else 
                $i=$sep+1; 
            $sep=-1; 
            $j=$i; 
            $l=0; 
            $nl++; 
        } 
        else{ 
            $obs[$nl].=$s[$i];
            $i++; 
        }
    }
/**/	

	
	$pdf->Ln(1);
	
	$pdf->Ln(1);			
	$pdf->Cell(10);
   $pdf->Cell(50,4,$obs[0],0,0,'L',0);

	$pdf->Cell(49);
/*Sub-total*/   
   $importe4=number_format($importe,2,",",".");	
   $pdf->Cell(20,4,$moneda." ".$importe4,0,0,'R',0);

/*Descuento*/
	$descuento=$lafila["descuento"];
	$impodescuento=$impodesc=$importe*(1-$descuento/100);
	$impodesc=sprintf("%01.2f", $impodesc); 
	$impodesc=number_format($impodesc,2,",",".");
	
if ($descuento!=0)	{
   $pdf->Cell(20,4,$descuento."%",0,0,'R',0);
	$pdf->Cell(10);
} else {
   $pdf->Cell(20,4,"",0,0,'R',0);
	$pdf->Cell(10);
}
/*IVA*/
	$pdf->Cell(10,4,$lafila["iva"]."%",0,0,'L',0);

	$ivai=$lafila["iva"];
	$impoiva=$importeiva=$impodescuento*($ivai/100);
	$impoiva=sprintf("%01.2f", $impoiva); 
	$impoiva=number_format($impoiva,2,",",".");

//	$impo=$impo*($ivai/100);
	$total=round($impodescuento+$importeiva,2); 
   $total=sprintf("%01.2f", $total);
   $total=round($total, 0, PHP_ROUND_HALF_UP);
   
	$total2= number_format($total,2,",",".");	
	
	$pdf->Cell(22,4,$moneda." ".$total2,0,0,'R',0);


	$pdf->Ln(3);
	$pdf->Cell(10);
   $pdf->Cell(50,4,$obs[1],0,0,'L',0);

	$impo=number_format($impo,2,",",".");
	$pdf->Cell(70);
if ($descuento==0) {	
	$pdf->Cell(20,4," ",0,0,'R',0);
} else {
	$pdf->Cell(20,4,$moneda." ".$impodesc,0,0,'R',0);
}  
/*muestro iporte de iva*/
	$pdf->Cell(20,4,$moneda." ".$impoiva,0,0,'R',0);  

	$pdf->Ln(3);
	$pdf->Cell(10);
   $pdf->Cell(50,4,$obs[2],0,0,'L',0);
	$pdf->Ln(3);
	$pdf->Cell(10);
   $pdf->Cell(50,4,$obs[3],0,0,'L',0);
	$pdf->Ln(3);
	$pdf->Cell(10);
   $pdf->Cell(50,4,$obs[4],0,0,'L',0);
	$pdf->Ln(3);
	$pdf->Cell(10);
   $pdf->Cell(50,4,$obs[5],0,0,'L',0);
	$pdf->Ln(3);
	$pdf->Cell(10);
   $pdf->Cell(50,4,$obs[6],0,0,'L',0);


	$pdf->Ln(3);
	$pdf->Ln(3);
	$pdf->Ln(3);
	$pdf->Ln(3);
	$pdf->Cell(130);
   $pdf->Cell(30,4,"Copia Archivo",0,0,'L',0);
   
   
      @((mysqli_free_result($resultado) || (is_object($resultado) && (get_class($resultado) == "mysqli_result"))) ? true : false); 
      @((mysqli_free_result($query) || (is_object($query) && (get_class($query) == "mysqli_result"))) ? true : false);
	  @((mysqli_free_result($resultado2) || (is_object($resultado2) && (get_class($resultado2) == "mysqli_result"))) ? true : false); 
	  @((mysqli_free_result($query3) || (is_object($query3) && (get_class($query3) == "mysqli_result"))) ? true : false);

$pdf->Output('Factura'.$codfactura,'I');
?> 
