



<?php
set_time_limit(60);
include("../../DBCONFIG.PHP");
include("../../LoginControl.php");
include("BASICLOGININFO.PHP");
session_start();




$from = $_SESSION['fromreport'];
$toreport = $_SESSION['toreport'];



$checkpayperperiod = "SELECT * FROM PAY_PER_PERIOD WHERE pperiod_range = '$from'";
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
$pdf->Cell(189,5,'Withholding Tax',0,1,'C');
$pdf->Cell(189,5,$from,0,1,'C');//end
$pdf->Cell(189,5,'',0,1,'C');//end
$pdf->SetFont('Arial','B',9);
$pdf->Cell(50,14,'TIN ID',1,0,'C');
$pdf->Cell(30,14,'LAST NAME',1,0,'C');
$pdf->Cell(30,14,'FIRST NAME',1,0,'C');
$pdf->Cell(30,14,'MIDDLE NAME',1,0,'C');
$pdf->Cell(60,14,'WITHHOLDING TAX',1,1,'C');

$totaltax = 0;



//set font to arial, regular, 12pt
$pdf->SetFont('Arial','',10);
while ($check1array = mysqli_fetch_array($checkpayperperiodexec)):;
  
  $empid = $check1array['emp_id'];
  $withholdingtax = $check1array['tax_deduct'];

  $getdetailsquery = "SELECT last_name,first_name,middle_name,TIN_number FROM employees WHERE emp_id = '$empid'";
  $getdetailsexecquery = mysqli_query($conn,$getdetailsquery) or die ("FAILED 2 ".mysqli_error($conn));
  $getdetailsarray = mysqli_fetch_array($getdetailsexecquery);

  $totaltax = $totaltax + $withholdingtax;
   $taxtot = number_format((float)$totaltax,2,'.','');
  if($getdetailsarray){

    $tin = $getdetailsarray['TIN_number'];
    $fname = $getdetailsarray['first_name'];
    $mname = $getdetailsarray['middle_name'];
    $lname = $getdetailsarray['last_name'];
    // $fullname = "$lname, $fname";

  }


$pdf->Cell(50,7,$tin,1,0,'C');
$pdf->Cell(30,7,$fname,1,0,'C');
$pdf->Cell(30,7,$lname,1,0,'C');
$pdf->Cell(30,7,$mname,1,0,'C');
$pdf->Cell(60,7,$withholdingtax,1,1,'C');

endwhile;


//set font arial, bold, 12pt
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,14,'',0,0,'C');
$pdf->Cell(79,14,'TOTAL:',0,0,'L');
$pdf->Cell(60,14,$taxtot,0,1,'C');


// $checkpayperperiod2 = "SELECT * FROM PAY_PER_PERIOD WHERE pperiod_range = '$toreport'";
// $checkpayperperiodexec2 = mysqli_query($conn,$checkpayperperiod2) or die ("FAILED 3".mysqli_error($conn));

// $pdf ->AddPage();

// //set font arial, bold, 14pt
// $pdf->SetFont('Arial','B',14);

// //Spacer
// $pdf->Cell(189,10,'',0,1);//end of line

// //Cell (width,height,text,border,end line, [align])
// $pdf->Cell(189,5,' WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS',0,1,'C');//end


// //set font to arial, regular, 12pt
// $pdf->SetFont('Arial','',9);

// $pdf->Cell(189,5,'82 Izlandville Compound, Sitio Tagbac',0,1,'C');//end
// $pdf->Cell(189,5,'Antipolo City, Philippines, 1870',0,1,'C');//end

// $pdf->Cell(189,10,'',0,1);//space
// $pdf->SetFont('Arial','B',11);
// $pdf->Cell(189,5,'Withholding Tax',0,1,'C');
// $pdf->Cell(189,5,$toreport,0,1,'C');//end
// $pdf->Cell(189,5,'',0,1,'C');//end
// $pdf->SetFont('Arial','B',9);
// $pdf->Cell(50,14,'TIN ID',1,0,'C');
// $pdf->Cell(79,14,'EMPLOYEE FULLNAME',1,0,'C');
// $pdf->Cell(60,14,'WITHHOLDING TAX',1,1,'C');

// $pdf->SetFont('Arial','',10);


// $totaltax2 = 0;

// while ($check2array = mysqli_fetch_array($checkpayperperiodexec2)):;
  
//   $empid2 = $check2array['emp_id'];
//   $withholdingtax2 = $check2array['tax_deduct'];

//   $getdetailsquery2 = "SELECT last_name,first_name,middle_name,TIN_number FROM employees WHERE emp_id = '$empid2'";
//   $getdetailsexecquery2 = mysqli_query($conn,$getdetailsquery2) or die ("FAILED 4 ".mysqli_error($conn));
//   $getdetailsarray2 = mysqli_fetch_array($getdetailsexecquery2);

//   $totaltax2 = $totaltax2 + $withholdingtax2;
//    $taxtot2 = number_format((float)$totaltax2,2,'.','');
//   if($getdetailsarray2){

//     $tin2 = $getdetailsarray2['TIN_number'];
//     $fname2 = $getdetailsarray2['first_name'];
//     $mname2 = $getdetailsarray2['middle_name'];
//     $lname2 = $getdetailsarray2['last_name'];
//     $fullname2 = "$lname2, $fname2";

//   }


// $pdf->Cell(50,7,$tin2,1,0,'C');
// $pdf->Cell(79,7,$fullname2,1,0,'L');
// $pdf->Cell(60,7,$withholdingtax2,1,1,'C');

// endwhile;


// //set font arial, bold, 12pt
// $pdf->SetFont('Arial','B',10);
// $pdf->Cell(50,14,'',0,0,'C');
// $pdf->Cell(79,14,'TOTAL:',0,0,'L');
// $pdf->Cell(60,14,$taxtot2,0,1,'C');




$pdf->Output();
?>


