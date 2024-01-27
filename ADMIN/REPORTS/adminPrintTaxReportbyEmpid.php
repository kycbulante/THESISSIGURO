
<?php
set_time_limit(60);
include("../../DBCONFIG.PHP");
include("../../LoginControl.php");
include("BASICLOGININFO.PHP");

session_start();
$govrepempid = $_SESSION['govrepempid'];
$govrepyear = $_SESSION['govrepyear'];

  $getdetailsquery = "SELECT last_name,first_name,middle_name,TIN_number FROM employees WHERE emp_id = '$govrepempid'";
  $getdetailsexecquery = mysqli_query($conn,$getdetailsquery) or die ("FAILED 2 ".mysqli_error($conn));
  $getdetailsarray = mysqli_fetch_array($getdetailsexecquery);

  if($getdetailsarray){

    $TINidno = $getdetailsarray['TIN_number'];
    $fname = $getdetailsarray['first_name'];
    $mname = $getdetailsarray['middle_name'];
    $lname = $getdetailsarray['last_name'];
    $fullname = "$lname, $fname";

  }


$getemppayrec = "SELECT * FROM PAY_PER_PERIOD WHERE emp_id = '$govrepempid' AND pperiod_year = '$govrepyear'";
$getemppayrecexec = mysqli_query($conn,$getemppayrec) or die ("FAILED ".mysqli_error($conn)); 



require_once("../fpdf181/fpdf.php");

//A4 width: 219mm
//default margin : 10mm each side
//writable horizontal: 219.(10*2)= 189mm

$pdf = new FPDF ('P','mm','LETTER');

$pdf ->AddPage();

//set font arial, bold, 14pt
$pdf->SetFont('Arial','B',14);

//Spacer
$pdf->Cell(189,10,'',0,1);//end of line

//Cell (width,height,text,border,end line, [align])
$pdf->Cell(189,5,'WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS',0,1,'C');//end


//set font to arial, regular, 12pt
$pdf->SetFont('Arial','',9);

$pdf->Cell(189,5,'Cavite',0,1,'C');//end
$pdf->Cell(189,5,'Cavite',0,1,'C');//end

$pdf->Cell(189,10,'',0,1);//space
$pdf->SetFont('Arial','B',11);
$pdf->Cell(189,5,'Withholding Tax',0,1,'C');//end
$pdf->Cell(189,5,$govrepyear,0,1,'C');//end
$pdf->Cell(189,5,'',0,1,'C');//end

$pdf->SetFont('Arial','',11);
$pdf->Cell(35,14,'',0,0,'C');
$pdf->Cell(10,7,'TIN:',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(50,7,$TINidno,0,1);//END

$pdf->SetFont('Arial','',11);
$pdf->Cell(35,14,'',0,0,'C');
$pdf->Cell(12.5,7,'Name:',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(50,7,$fullname,0,1);//END

$pdf->Cell(35,14,'',0,0,'C');
$pdf->Cell(56.3,14,'PAYROLL PERIOD',1,0,'C');
$pdf->Cell(56,14,'WITHHOLDING TAX',1,1,'C');

$totaltax = 0;

while ($check1array = mysqli_fetch_array($getemppayrecexec)):;
  
  $empid = $check1array['emp_id'];
  $payperiod = $check1array['pperiod_range'];
  $withholdingtax = $check1array['tax_deduct'];
  $totaltax = $totaltax + $withholdingtax;
  $taxtot = number_format((float)$totaltax,2,'.','');


 


$pdf->SetFont('Arial','',11);
$pdf->Cell(35,14,'',0,0,'C');
$pdf->Cell(56.3,14,$payperiod,1,0,'C');
$pdf->Cell(56,14,$withholdingtax,1,1,'C');

endwhile;


//set font arial, bold, 12pt
$pdf->SetFont('Arial','B',10);
$pdf->Cell(35,14,'',0,0,'C');
$pdf->Cell(56.3,14,'TOTAL:',0,0,'L');
$pdf->Cell(56,14,$taxtot,0,1,'C');



$pdf->Output();