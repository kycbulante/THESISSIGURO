
<?php
set_time_limit(60);
include("../../DBCONFIG.PHP");
include("../../LoginControl.php");
include("BASICLOGININFO.PHP");
session_start();
$govrepempid = $_SESSION['govrepempid'];
$govrepyear = $_SESSION['govrepyear'];

  $getdetailsquery = "SELECT last_name,first_name,middle_name,PAGIBIG_idno FROM employees WHERE emp_id = '$govrepempid'";
  $getdetailsexecquery = mysqli_query($conn,$getdetailsquery) or die ("FAILED 2 ".mysqli_error($conn));
  $getdetailsarray = mysqli_fetch_array($getdetailsexecquery);

  if($getdetailsarray){

    $pagibigidno = $getdetailsarray['PAGIBIG_idno'];
    $fname = $getdetailsarray['first_name'];
    $mname = $getdetailsarray['middle_name'];
    $lname = $getdetailsarray['last_name'];
    $fullname = "$lname, $fname";

  }


$getemppayrec = "SELECT * FROM PAY_PER_PERIOD WHERE emp_id = '$govrepempid' AND pperiod_year = '$govrepyear' AND pagibigloan_deduct != '0.00'";
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
$pdf->Cell(189,5,'PAG-IBIG Loan ',0,1,'C');
$pdf->Cell(189,5,$govrepyear,0,1,'C');//end
$pdf->Cell(189,5,'',0,1,'C');//end

$pdf->SetFont('Arial','',11);
$pdf->Cell(35,14,'',0,0,'C');
$pdf->Cell(30,7,'PAG-IBIG Number:',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(50,7,$pagibigidno,0,1);//END

$pdf->SetFont('Arial','',11);
$pdf->Cell(35,14,'',0,0,'C');
$pdf->Cell(12.5,7,'Name:',0,0);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(50,7,$fullname,0,1);//END

$pdf->Cell(35,14,'',0,0,'C');
$pdf->Cell(54.3,14,'MONTH',1,0,'C');
$pdf->Cell(56,14,'AMOUNT',1,1,'C');

$pdf->SetFont('Arial','',10);
$totalloan = 0;
if(mysqli_num_rows($getemppayrecexec) > 0){
while ($check1array = mysqli_fetch_array($getemppayrecexec)):;
  
  $empid = $check1array['emp_id'];
  $pperiod = $check1array['pperiod_range'];
  $pagibigloan = $check1array['pagibigloan_deduct'];

$payperiodquery = "SELECT pperiod_end FROM payperiods WHERE pperiod_range = '$pperiod'";
$payperiodexecquery = mysqli_query($conn,$payperiodquery) or die ("FAILED1 ".mysqli_error($conn));
$payperiodarray = mysqli_fetch_array($payperiodexecquery);
if ($payperiodarray){
  $enddate = $payperiodarray['pperiod_end'];
}

$conv = strtotime($enddate);
$monthyear = date("F Y", $conv);

  
$totalloan = $totalloan + $pagibigloan;

$pdf->Cell(35,14,'',0,0,'C');
$pdf->Cell(54.3,7,$monthyear,1,0,'C');
$pdf->Cell(56,7,$pagibigloan,1,1,'C');//end


endwhile;
}else {
  $pdf->Cell(54.3,7,'No records found',1,0,'C');
  $pdf->Cell(56,7,'',1,1,'C');//end
}

//set font arial, bold, 12pt
$pdf->SetFont('Arial','B',10);
$pdf->Cell(35,14,'',0,0,'C');
$pdf->Cell(54.3,14,'TOTAL:',0,0,'L');
$pdf->Cell(56,14,$totalloan,0,1,'C');



$pdf->Output();