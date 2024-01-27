
<?php
set_time_limit(60);
include("../../DBCONFIG.PHP");
include("../../LoginControl.php");
include("BASICLOGININFO.PHP");

session_start();
$govrepempid = $_SESSION['govrepempid'];
$govrepyear = $_SESSION['govrepyear'];

  $getdetailsquery = "SELECT last_name,first_name,middle_name,SSS_idno FROM employees WHERE emp_id = '$govrepempid'";
  $getdetailsexecquery = mysqli_query($conn,$getdetailsquery) or die ("FAILED 2 ".mysqli_error($conn));
  $getdetailsarray = mysqli_fetch_array($getdetailsexecquery);

  if($getdetailsarray){

    $sssidno = $getdetailsarray['SSS_idno'];
    $fname = $getdetailsarray['first_name'];
    $mname = $getdetailsarray['middle_name'];
    $lname = $getdetailsarray['last_name'];
    $fullname = "$lname, $fname, $mname";

  }


$getemppayrec = "SELECT * FROM PAY_PER_PERIOD WHERE emp_id = '$govrepempid' AND pperiod_year = '$govrepyear' AND sss_deduct != '0.00'";
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
$pdf->Cell(189,5,'SSS Contribution ',0,1,'C');
$pdf->Cell(189,5,$govrepyear,0,1,'C');//end
$pdf->Cell(189,5,'',0,1,'C');//end

$pdf->SetFont('Arial','',11);
$pdf->Cell(23,14,'',0,0,'C');
$pdf->Cell(25,7,'SSS Number:',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(50,7,$sssidno,0,1);//END

$pdf->SetFont('Arial','',11);
$pdf->Cell(23,14,'',0,0,'C');
$pdf->Cell(12.5,7,'Name:',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(50,7,$fullname,0,1);//END

$pdf->Cell(23,14,'',0,0,'C');
$pdf->Cell(54.3,14,'MONTH',1,0,'C');
$pdf->Cell(28,14,'ER',1,0,'C');
$pdf->Cell(28,14,'EE',1,0,'C');
$pdf->Cell(34,14,'TOTAL',1,1,'C');
$pdf->SetFont('Arial','',10);

$eetotal=0;
$ertotal=0;
$total=0;
while ($check1array = mysqli_fetch_array($getemppayrecexec)):;
  
  $empid = $check1array['emp_id'];
  $pperiod = $check1array['pperiod_range'];

$payperiodquery = "SELECT pperiod_end FROM payperiods WHERE pperiod_range = '$pperiod'";
$payperiodexecquery = mysqli_query($conn,$payperiodquery) or die ("FAILED1 ".mysqli_error($conn));
$payperiodarray = mysqli_fetch_array($payperiodexecquery);
if ($payperiodarray){
  $enddate = $payperiodarray['pperiod_end'];
}

$conv = strtotime($enddate);
$monthyear = date("F Y", $conv);

  $getsssinfoquery = "SELECT sss_tcEE,sss_tcER,sss_tcTOTAL FROM PAYROLLINFO WHERE emp_id = '$empid'";
  $getsssinfoexecquery = mysqli_query($conn,$getsssinfoquery) or die ("FAILED 1".mysqli_error($conn));
  $sssinfoarray = mysqli_fetch_array($getsssinfoexecquery);
  if($sssinfoarray){
    $sssEE = $sssinfoarray['sss_tcEE'];
    $sssER = $sssinfoarray['sss_tcER'];
    $sssTOTAL = $sssinfoarray['sss_tcTOTAL'];

    $eetot = $eetotal + $sssEE;
    $ertot = $ertotal + $sssER;
    $total = $total + $sssTOTAL;
    $ssstot = number_format((float)$total,2,'.','');
    $eetotal = number_format((float)$eetot,2,'.','');
    $ertotal = number_format((float)$ertot,2,'.','');
  }




$pdf->Cell(23,14,'',0,0,'C');
$pdf->Cell(54.3,7,$monthyear,1,0,'C');
$pdf->Cell(28,7,$sssER,1,0,'C');
$pdf->Cell(28,7,$sssEE,1,0,'C');
$pdf->Cell(34,7,$sssTOTAL,1,1,'C');//end

endwhile;


//set font arial, bold, 12pt
$pdf->SetFont('Arial','B',10);
$pdf->Cell(23,14,'',0,0,'C');
$pdf->Cell(54.3,14,'TOTAL:',0,0,'L');
$pdf->Cell(28,14,$ertotal,0,0,'C');
$pdf->Cell(28,14,$eetotal,0,0,'C');
$pdf->Cell(34,14,$ssstot,0,1,'C');


$pdf->Output();