



<?php
set_time_limit(60);
include("../../DBCONFIG.PHP");
include("../../LoginControl.php");
include("BASICLOGININFO.PHP");

session_start();





$adminId = $_SESSION['adminId'];
$from = $_SESSION['fromreport'];
$toreport = $_SESSION['toreport'];

$payperiodquery = "SELECT pperiod_end FROM payperiods WHERE pperiod_range = '$toreport'";
$payperiodexecquery = mysqli_query($conn,$payperiodquery) or die ("FAILED1 ".mysqli_error($conn));
$payperiodarray = mysqli_fetch_array($payperiodexecquery);
if ($payperiodarray){
  $enddate = $payperiodarray['pperiod_end'];
}

$conv = strtotime($enddate);
$monthyear = date("F Y", $conv);

$checkpayperperiod = "SELECT * FROM PAY_PER_PERIOD WHERE pperiod_range = '$toreport'";
$checkpayperperiodexec = mysqli_query($conn,$checkpayperperiod) or die ("FAILED ".mysqli_error($conn));




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
$pdf->Cell(189,5,'GSIS Contribution ',0,1,'C');
$pdf->Cell(189,5,$monthyear,0,1,'C');//end
$pdf->Cell(189,5,'',0,1,'C');//end
$pdf->SetFont('Arial','B',9);
$pdf->Cell(44.25,14,'SSS NUMBER',1,0,'C');
$pdf->Cell(23,14,'LAST NAME',1,0,'C');
$pdf->Cell(23,14,'FIRST NAME',1,0,'C');
$pdf->Cell(23,14,'MIDDLE NAME',1,0,'C');
$pdf->Cell(25,14,'ER',1,0,'C');
$pdf->Cell(25,14,'EE',1,0,'C');
$pdf->Cell(34,14,'TOTAL',1,1,'C');



$ertotal = 0;
$eetotal = 0;
$total = 0;


//set font to arial, regular, 12pt
$pdf->SetFont('Arial','',10);
while ($check1array = mysqli_fetch_array($checkpayperperiodexec)):;
  
  $empid = $check1array['emp_id'];
  $getsssinfoquery = "SELECT pagibig_EE,pagibig_ER,pagibig_TOTAL FROM PAYROLLINFO WHERE emp_id = '$empid'";
  $getsssinfoexecquery = mysqli_query($conn,$getsssinfoquery) or die ("FAILED 1".mysqli_error($conn));
  $sssinfoarray = mysqli_fetch_array($getsssinfoexecquery);
  if($sssinfoarray){
    $pagibigEE = $sssinfoarray['pagibig_EE'];
    $pagibigER = $sssinfoarray['pagibig_ER'];
    $pagibigTOTAL = $sssinfoarray['pagibig_TOTAL'];

    $eetot = $eetotal + $pagibigEE;
    $ertot = $ertotal + $pagibigER;
    $total = $total + $pagibigTOTAL;
    $pagibigtot = number_format((float)$total,2,'.','');
    $eetotal = number_format((float)$eetot,2,'.','');
    $ertotal = number_format((float)$ertot,2,'.','');
  }

  $getdetailsquery = "SELECT last_name,first_name,middle_name,pagibig_idno FROM employees WHERE emp_id = '$empid'";
  $getdetailsexecquery = mysqli_query($conn,$getdetailsquery) or die ("FAILED 2 ".mysqli_error($conn));
  $getdetailsarray = mysqli_fetch_array($getdetailsexecquery);

  if($getdetailsarray){

    $pagibigidno = $getdetailsarray['pagibig_idno'];
    $fname = $getdetailsarray['first_name'];
    $mname = $getdetailsarray['middle_name'];
    $lname = $getdetailsarray['last_name'];
    $fullname = "$lname, $fname";

  }


$pdf->Cell(44.25,7,$pagibigidno,1,0,'C');
$pdf->Cell(23,7,$fname,1,0,'C');
$pdf->Cell(23,7,$lname,1,0,'C');
$pdf->Cell(23,7,$mname,1,0,'C');
$pdf->Cell(25,7,$pagibigER,1,0,'C');
$pdf->Cell(25,7,$pagibigEE,1,0,'C');
$pdf->Cell(34,7,$pagibigTOTAL,1,1,'C');//end

endwhile;


//set font arial, bold, 12pt
$pdf->SetFont('Arial','B',10);
$pdf->Cell(44.25,14,'',0,0,'C');
$pdf->Cell(54.3,14,'TOTAL:',0,0,'L');
$pdf->Cell(28,14,$ertotal,0,0,'C');
$pdf->Cell(28,14,$eetotal,0,0,'C');
// $pdf->Cell(34,14,$gsistot,0,1,'C');

$pdf->Cell(34, 14, 'printed by ' . $adminId, 0, 1, 'C');









$pdf->Output();
?>


