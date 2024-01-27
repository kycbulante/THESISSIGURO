<?php
set_time_limit(60);
include("../../DBCONFIG.PHP");
include("../../LoginControl.php");
include("BASICLOGININFO.PHP");

session_start();

$adminId = $_SESSION['adminId'];
$payperiod = $_SESSION['pregisterpayperiod'];
$printfrom = $_SESSION['payperiodfrom'];
$printto=$_SESSION['payperiodto'];



require_once("../fpdf181/fpdf.php");

$pdf = new FPDF ('L','mm','LEGAL');
$pdf ->AddPage();
//set font arial, bold, 14pt
$pdf->SetFont('Arial','B',12);
//WRitable horizontal : 260

$pdf->Cell(336,3,'',0,1);//end of line


$pdf->Cell(336,4,'WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS',0,1,'C');//end of line
$pdf->SetFont('Arial','','8');
$pdf->Cell(336,4,'Cavite',0,1,'C');//end of line
$pdf->Cell(336,4,'Cavite',0,1,'C');//end of line

$pdf->Cell(336,8,'',0,1);//end of line

$pdf->SetFont('Arial','B','10');
$pdf->Cell(336,4,'Payroll Register',0,1,'C');//end of line

$pdf->SetFont('Arial','','10');
$pdf->Cell(336,4,$payperiod,0,1,'C');//end of line

$pdf->Cell(336,4,'',0,1);//end of line

$pdf->SetFont('Arial','B','9');
$pdf->Cell(84,4,'EMPLOYEE',0,0,'C');
$pdf->Cell(84,4,'EARNINGS',0,0,'C');
$pdf->Cell(84,4,'DEDUCTIONS',0,0,'C');
$pdf->Cell(84,4,'TOTALS',0,1,'C');//end of line

$pdf->Cell(336,0.2,'',1,1);//end of line

$pdf->SetFont('Arial','','8');
//EMPLOYEE
$pdf->Cell(84,3,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'Description',0,0);
$pdf->Cell(22,5,'Hr/Min',0,0);
$pdf->Cell(20,5,'Amount',0,0);
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'Description',0,0);
$pdf->Cell(22,5,'Hr/Min',0,0);
$pdf->Cell(20,5,'Amount',0,0);
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(84,5,'',0,1);//end

//EMPLOYEE
$pdf->Cell(84,0.1,'',0,0);
//EARNINGS
$pdf->Cell(80,0.1,'',1,0);
$pdf->Cell(4,0.1,'',0,0);
//DEDUCTIONS
$pdf->Cell(80,0.1,'',1,0);
$pdf->Cell(4,0.1,'',0,0);
//TOTALS
$pdf->Cell(84,0.1,'',0,1);//end

$getppqry = "SELECT * FROM PAY_PER_PERIOD WHERE pperiod_range = '$payperiod'";
$getppexecqry = mysqli_query($conn,$getppqry) or die ("FAILED TO GET PAYROLL PERIOD DETAILS ".mysqli_error($conn));
$GTbp = 0;
$GTotp = 0;
$GTrhp = 0;
$GTrhp200 = 0;
$GTotrhp = 0;
$GTotshp = 0;
$GTotrdp = 0;
$GTrdrhp = 0;
$GTrdrhp = 0;
$GTotrdrhp = 0;
$GTrdshp = 0;
$GTotrdshp  = 0;
$GTlvp = 0;
$GTshp = 0;
$GTrdp  = 0;
$GTEarnings = 0;
$GTphealth = 0;
$GTsssded = 0;
$GTpagibigded = 0;
$GTsssloanded = 0;
$GTpagibigloanded = 0;
$GTDeductions = 0;
$GTnp = 0;

while($pparray = mysqli_fetch_array($getppexecqry)):;

$empid = $pparray['emp_id'];

$timekeepinfo = "SELECT SUM(ot_sh) as totalOTSH, SUM(ot_rh) as totalOTRH, SUM(lv_hours) as totalLH, SUM(sh_hours) as totalSH, SUM(rh_hours) as totalRH, SUM(undertime_hours) as totalUT ,SUM(overtime_hours) as totalOT, SUM(hours_work) as totalWORKhours, SUM(late_hours) as totalLATEhours, SUM(hours_work+overtime_hours+ot_rh+ot_sh) as totalness FROM TIME_KEEPING WHERE emp_id = '$empid' AND timekeep_day BETWEEN '$printfrom' and '$printto' ORDER BY timekeep_day ASC";
$timekeepinfoexecqry = mysqli_query($conn,$timekeepinfo) or die ("FAILED TO GET TIMEKEEP INFO ".mysqli_error($conn));
$timekeepinfoarray = mysqli_fetch_array($timekeepinfoexecqry);
if ($timekeepinfoarray){

	$hw = $timekeepinfoarray['totalWORKhours'];

	$oth = $timekeepinfoarray['totalOT'];
	$otsh = $timekeepinfoarray['totalOTSH'];
	$otrh = $timekeepinfoarray['totalOTRH'];
	$ut = $timekeepinfoarray['totalUT'];


	$othrs = ($oth + $otrh + $otsh);

	$latehours = $timekeepinfoarray['totalLATEhours'];

	$rhhrs = $timekeepinfoarray['totalRH'];
	$shhrs = $timekeepinfoarray['totalSH'];
	$lvhrs = $timekeepinfoarray['totalLH'];
	$hrswrk = $hw - ($rhhrs + $shhrs + $lvhrs);
}

$getempdetailsqry = "SELECT prefix_ID,last_name,first_name,middle_name,dept_name FROM employees WHERE emp_id = '$empid'";
$getempdetailsexecqry = mysqli_query($conn,$getempdetailsqry) or die ("FAILED TO GET EMP DETAILS ".mysqli_error($conn));
$emparray = mysqli_fetch_array($getempdetailsexecqry);
if($emparray){
	$prefixid = $emparray['prefix_ID'];
	$lname = $emparray['last_name'];
	$fname = $emparray['first_name'];
	$dname = $emparray['dept_name'];
	$name = "$lname, $fname";
	$compempid = "$prefixid$empid";
}

$gettkqry = 

//EARNINGS
$bpay = $pparray['reg_pay'];
$otpay = $pparray['ot_pay'];
$rhpay = $pparray['hday_pay'];
$otrhpay = $pparray['otrh_pay'];
$shpay = $pparray['shday_pay'];
$otshpay = $pparray['otsh_pay'];
$lvpay = $pparray['lv_pay'];

$bpay1 = $bpay + ($ut);

$te = ($bpay + $otpay + $rhpay + $otrhpay + $shpay + $otshpay + $lvpay);
$totearnings = number_format((float)$te,2,'.','');
//GT earnings
$GTbp = $GTbp + $bpay;
$GTbpay = number_format((float)$GTbp,2,'.','');

$GTotp = $GTotp + $otpay;
$GTotpay = number_format((float)$GTotp,2,'.','');

$GTrhp = $GTrhp + $rhpay;
$GTrhpay = number_format((float)$GTrhp,2,'.','');


$GTotrhp = $GTotrhp + $otrhpay;
$GTotrhpay = number_format((float)$GTotrhp,2,'.','');

$GTshp = $GTshp + $shpay;
$GTshpay = number_format((float)$GTshp,2,'.','');

$GTotshp = $GTotshp + $otshpay;
$GTotshpay = number_format((float)$GTotshp,2,'.','');


$GTlvp = $GTlvp + $lvpay;
$GTlvpay = number_format((float)$GTlvp,2,'.','');

$GTEarnings = $GTEarnings + $te;
$GTE = number_format((float)$GTEarnings,2,'.','');

//DEDUCTS

$phdeduct = $pparray['philhealth_deduct'];
$gsisdeduct = $pparray['sss_deduct'];
$pagibigdeduct = $pparray['pagibig_deduct'];
$sssloandeduct = $pparray['sssloan_deduct'];
$pagibigloandeduct = $pparray['pagibigloan_deduct'];

$td = ($phdeduct + $gsisdeduct + $pagibigdeduct +  $sssloandeduct + $pagibigloandeduct);
$totdeduct = number_format((float)$td,2,'.','');
//GTDeductions
$GTphealth = $GTphealth + $phdeduct;
$GTph = number_format((float)$GTphealth,2,'.','');

$GTsssded = $GTsssded + $gsisdeduct;
$GTsss = number_format((float)$GTsssded,2,'.','');

$GTpagibigded = $GTpagibigded + $pagibigdeduct;
$GTpagibig = number_format((float)$GTpagibigded,2,'.','');


$GTsssloanded = $GTsssloanded + $sssloandeduct;
$GTsssloan = number_format((float)$GTsssloanded,2,'.','');

$GTpagibigloanded = $GTpagibigloanded + $pagibigloandeduct;
$GTpagibigloan = number_format((float)$GTpagibigloanded,2,'.','');

$GTDeductions = $GTDeductions + $td;
$GTD = number_format((float)$GTDeductions,2,'.','');

//NETPAY 
$np = ($te - $td);
$netpay = number_format((float)$np,2,'.','');
$GTnp = $GTnp + $np;
$GTN = number_format((float)$GTnp,2,'.','');
//EMPLOYEE
$pdf->Cell(22,5,'Employee ID:',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(62,5,$compempid,0,0);
//EARNINGS
$pdf->Cell(1,6.1,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,6.1,'Basic',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,6.1,$hrswrk,0,0);
$pdf->Cell(20,6.1,$bpay,0,0,'R');
$pdf->Cell(2,6.1,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,6.1,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,6.1,'Philhealth',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,6.1,'',0,0);
$pdf->Cell(20,6.1,$phdeduct,0,0,'R');
$pdf->Cell(2,6.1,'',0,0);
//TOTALS
$pdf->Cell(1,6.1,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,6.1,'Total Earnings',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,6.1,'',0,0);
$pdf->Cell(20,6.1,$totearnings,0,0,'R');
$pdf->Cell(2,6.1,'',0,1);//end

//EMPLOYEE
$pdf->Cell(22,5,'Name:',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(62,5,$name,0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'Regular OT',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,$othrs,0,0);
$pdf->Cell(20,5,$otpay,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'GSIS',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$gsisdeduct,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'Total Deductions',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$totdeduct,0,0,'R');
$pdf->Cell(2,5,'',0,1);//end

//EMPLOYEE
$pdf->Cell(22,5,'Dept name:',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(62,5,$dname,0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'Regular Holiday',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,$rhhrs,0,0);
$pdf->Cell(20,5,$rhpay,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'PAG-IBIG',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$pagibigdeduct,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end

//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'Special Holiday',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,$shhrs,0,0);
$pdf->Cell(20,5,$shpay,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0,'R');
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end
//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'RH OT',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,$otrh,0,0);
$pdf->Cell(20,5,$otrhpay,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'GSIS Loan',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$sssloandeduct,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end

//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'SH OT',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,$shhrs,0,0);
$pdf->Cell(20,5,$shpay,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'PAG-IBIG Loan',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$pagibigloandeduct,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'NET PAY',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$netpay,0,0,'R');
$pdf->Cell(2,5,'',0,1);//end

//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end

//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end

//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end


//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end


//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end


//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end

//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end

//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'Vac. Leave/Sick Leave',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,$lvhrs,0,0);
$pdf->Cell(20,5,$lvpay,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end

$pdf->Cell(336,0.2,'',1,1);//end of line

endwhile;

//GRAND TOTAL
$pdf->SetFont('Arial','','8');

//EMPLOYEE
$pdf->Cell(42,5,'GRAND TOTAL FOR',0,0);
$pdf->Cell(42,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'Basic',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$GTbpay,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'Philhealth',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$GTph,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'Total Earnings',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$GTE,0,0,'R');
$pdf->Cell(2,5,'',0,1);//end

//EMPLOYEE
$pdf->Cell(84,3,$payperiod,0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'Regular OT',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$GTotpay,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'GSIS',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$GTsss,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'Total Deductions',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$GTD,0,0,'R');
$pdf->Cell(2,5,'',0,1);//end

//EMPLOYEE
$pdf->Cell(4,5,'',0,0);
$pdf->Cell(80,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'Regular Holiday',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$GTrhpay,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'PAG-IBIG',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$GTpagibig,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end

//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'Special Holiday',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$GTshpay,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0,'R');
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end
//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'RH OT',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$GTotrhpay,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'GSIS Loan',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$GTsss,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end

//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'SH OT',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$GTotshpay,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'PAG-IBIG Loan',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$GTpagibigloan,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'NET PAY',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$GTN,0,0,'R');
$pdf->Cell(2,5,'',0,1);//end

//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end

//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end

//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end


//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end


//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end


//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end

//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end

//EMPLOYEE
$pdf->Cell(84,5,'',0,0);
//EARNINGS
$pdf->Cell(1,5,'',0,0);
$pdf->SetFont('Arial','B','8');
$pdf->Cell(39,5,'Vac. Leave/Sick Leave',0,0);
$pdf->SetFont('Arial','','8');
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,$GTlvpay,0,0,'R');
$pdf->Cell(2,5,'',0,0);
//DEDUCTIONS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,0);
//TOTALS
$pdf->Cell(1,5,'',0,0);
$pdf->Cell(39,5,'',0,0);
$pdf->Cell(22,5,'',0,0);
$pdf->Cell(20,5,'',0,0);
$pdf->Cell(2,5,'',0,1);//end

$pdf->Cell(336,0.2,'',1,1);//end of line
// Add "Printed by" information
$pdf->SetFont('Arial','',10);
$pdf->Cell(336,5,'Printed by: ' . $adminId,0,1,'R'); 








$pdf->Output();
unset($_SESSION['pregisterpayperiod']);
?>