
<?php
set_time_limit(60);
include("../../DBCONFIG.PHP");
include("../../LoginControl.php");
include("BASICLOGININFO.PHP");

$adminId = $_SESSION['adminId'];

/**
WHILE LOOP LEGEND 
GET EMPLOYEE ID = emparrwhile
JANUARY TOTALS = janpaywhile
FEB TOTALS = febpaywhile
MAR TOTALS = marpaywhile
APR TOTALS = aprpaywhile
MAY TOTALS = maypaywhile
JUNE TOTALS = junepaywhile
JULY TOTALS = julypaywhile
AUGUST TOTALS = augustpaywhile
SEPTEMBER TOTALS = septpaywhile
OCTOBER TOTALS = octpaywhile
NOVEMBER TOTALS = novpaywhile
DECEMBER TOTALS = decpaywhile

**/


session_start();
//WHILELOOP INITIALIZATIONS
$jan = 0;
$feb = 0;
$mar = 0;
$apr = 0;
$may = 0;
$jun = 0;
$jul = 0;
$aug = 0;
$sep = 0;
$oct = 0;
$nov = 0;
$dec = 0;
///f0r d3f
$OCTbpay = 0;
$OCTotpay = 0;
$OCTrhpay = 0;
$OCTrhpay200 = 0;
$OCTotrhpay = 0;
$OCTshpay = 0;
$OCTotshpay = 0;
$OCTrdpay = 0;
$OCTotrdpay = 0;
$OCTrdrhpay = 0;
$OCTotrdrhpay = 0;
$OCTrdshpay = 0;
$OCTotrdshpay = 0;
$OCTlvpay = 0;

$OCTphd = 0;
$OCTsssd = 0;
$OCTpid = 0;
$OCTtd = 0;
$OCTsssld = 0;
$OCTpild = 0;

$OCTearnings = 0;
$OCTdeductions = 0;
$OCTnetpay = 0;
//WHILELOOP INITIALIZATIONS
$yearreport = $_SESSION['reportyear'];
$title = "PAYROLL SUMMARY FOR THE YEAR $yearreport";

$empqry = "SELECT emp_id FROM employees";
$empexecqry = mysqli_query($conn,$empqry) or die ("FAILED EMP ".mysqli_error($conn));
while($emparr = mysqli_fetch_array($empexecqry)):;//emparrwhile

	$empid = $emparr['emp_id'];
	//JANUARYPAY
	$janpayqry = "SELECT * FROM PAY_PER_PERIOD WHERE emp_id = '$empid' AND pperiod_month = 'January' AND pperiod_year = '$yearreport'";
	$janpayexecqry = mysqli_query($conn,$janpayqry) or die ("FAILED JANPAY ".mysqli_error($conn));
	
	while($janpayarray = mysqli_fetch_array($janpayexecqry)):;//janpaywhile
		$jan = $jan + 1;
		//JAN PAYS
		$JANbpay = number_format((float) $JANbpay + $janpayarray['reg_pay'],2,'.','');
		$JANotpay = number_format((float) $JANotpay + $janpayarray['ot_pay'],2,'.','');
		$JANrhpay = number_format((float) $JANrhpay + $janpayarray['hday_pay'],2,'.','');
		$JANrhpay200 = number_format((float) $JANrhpay200 + $janpayarray['hday200_pay'],2,'.','');
		$JANotrhpay = number_format((float) $JANotrhpay + $janpayarray['otrh_pay'],2,'.','');
		$JANshpay = number_format((float) $JANshpay  + $janpayarray['shday_pay'],2,'.','');
		$JANotshpay = number_format((float) $JANotshpay + $janpayarray['otsh_pay'],2,'.','');
		$JANrdpay = number_format((float) $JANrdpay + $janpayarray['rd_pay'],2,'.','');
		$JANotrdpay = number_format((float) $JANotrdpay + $janpayarray['otrd_pay'],2,'.','');
		$JANrdrhpay = number_format((float) $JANrdrhpay+ $janpayarray['rdrh_pay'],2,'.','');
		$JANotrdrhpay = number_format((float) $JANotrdrhpay + $janpayarray['otrdrh_pay'],2,'.','');
		$JANrdshpay = number_format((float) $JANrdshpay + $janpayarray['rdsh_pay'],2,'.','');
		$JANotrdshpay = number_format((float) $JANotrdshpay + $janpayarray['otrdsh_pay'],2,'.','');
		$JANlvpay = number_format((float) $JANlvpay + $janpayarray['lv_pay'],2,'.','');
		
		$JANearnings = ($JANbpay + $JANotpay + $JANrhpay + $JANrhpay200 + $JANotrhpay + $JANshpay + $JANotshpay + $JANrdpay + $JANotrdpay + $JANrdrhpay + $JANotrdrhpay + $JANrdshpay + $JANotrdshpay + $JANlvpay);
		$JANearnings = number_format((float) $JANearnings,2,'.','');
		//JAN DEDUCTS
		$JANphd = number_format((float) $JANphd + $janpayarray['philhealth_deduct'],2,'.','');
		$JANsssd = number_format((float) $JANsssd + $janpayarray['sss_deduct'],2,'.','');
		$JANpid = number_format((float) $JANpid + $janpayarray['pagibig_deduct'],2,'.','');
		$JANtd = number_format((float) $JANtd + $janpayarray['tax_deduct'],2,'.','');
		$JANsssld = number_format((float) $JANsssld + $janpayarray['sssloan_deduct'],2,'.','');
		$JANpild = number_format((float) $JANpild + $janpayarray['pagibigloan_deduct'],2,'.','');
	
		$JANdeductions = ($JANphd + $JANsssd + $JANpid + $JANtd + $JANsssld + $JANpild);
		$JANdeductions = number_format((float) $JANdeductions,2,'.','');
		//JAN NET PAYS
		$JANnetpay = $JANearnings - $JANdeductions;
		$JANnetpay = number_format((float) $JANnetpay,2,'.','');
	endwhile;//janpaywhile

		if($jan==0){
			$JANbpay = number_format((float)0,2,'.','');
			$JANotpay = number_format((float)0,2,'.','');
			$JANrhpay = number_format((float)0,2,'.','');
			$JANrhpay200 = number_format((float)0,2,'.','');
			$JANotrhpay = number_format((float)0,2,'.','');
			$JANshpay = number_format((float)0,2,'.','');
			$JANotshpay = number_format((float)0,2,'.','');
			$JANrdpay = number_format((float)0,2,'.','');
			$JANotrdpay = number_format((float)0,2,'.','');
			$JANrdrhpay = number_format((float)0,2,'.','');
			$JANotrdrhpay = number_format((float)0,2,'.','');
			$JANrdshpay = number_format((float)0,2,'.','');
			$JANotrdshpay = number_format((float)0,2,'.','');
			$JANlvpay = number_format((float)0,2,'.','');
			$JANearnings = number_format((float)0,2,'.','');
			$JANphd = number_format((float)0,2,'.','');
			$JANsssd = number_format((float)0,2,'.','');
			$JANpid = number_format((float)0,2,'.','');
			$JANtd = number_format((float)0,2,'.','');
			$JANsssld = number_format((float)0,2,'.','');
			$JANpild = number_format((float)0,2,'.','');
			$JANdeductions = number_format((float)0,2,'.','');
			$JANnetpay = number_format((float)0,2,'.','');
		}
	
	//FEBRUARYPAY
	$febpayqry = "SELECT * FROM PAY_PER_PERIOD WHERE emp_id = '$empid' AND pperiod_month = 'February' AND pperiod_year = '$yearreport'";
	$febpayexecqry = mysqli_query($conn,$febpayqry) or die ("FAILED FEBPAY ".mysqli_error($conn));
	while($febpayarray = mysqli_fetch_array($febpayexecqry)):;//FEBpaywhile
		$feb = $feb+1;
		//FEB PAYS
		$FEBbpay = number_format((float) $FEBbpay + $febpayarray['reg_pay'],2,'.','');
		$FEBotpay = number_format((float) $FEBotpay + $febpayarray['ot_pay'],2,'.','');
		$FEBrhpay = number_format((float) $FEBrhpay + $febpayarray['hday_pay'],2,'.','');
		$FEBrhpay200 = number_format((float) $FEBrhpay200 + $febpayarray['hday200_pay'],2,'.','');
		$FEBotrhpay = number_format((float) $FEBotrhpay + $febpayarray['otrh_pay'],2,'.','');
		$FEBshpay = number_format((float) $FEBshpay  + $febpayarray['shday_pay'],2,'.','');
		$FEBotshpay = number_format((float) $FEBotshpay + $febpayarray['otsh_pay'],2,'.','');
		$FEBrdpay = number_format((float) $FEBrdpay + $febpayarray['rd_pay'],2,'.','');
		$FEBotrdpay = number_format((float) $FEBotrdpay + $febpayarray['otrd_pay'],2,'.','');
		$FEBrdrhpay = number_format((float) $FEBrdrhpay+ $febpayarray['rdrh_pay'],2,'.','');
		$FEBotrdrhpay = number_format((float) $FEBotrdrhpay + $febpayarray['otrdrh_pay'],2,'.','');
		$FEBrdshpay = number_format((float) $FEBrdshpay + $febpayarray['rdsh_pay'],2,'.','');
		$FEBotrdshpay = number_format((float) $FEBotrdshpay + $febpayarray['otrdsh_pay'],2,'.','');
		$FEBlvpay = number_format((float) $FEBlvpay + $febpayarray['lv_pay'],2,'.','');
		
		$FEBearnings = ($FEBbpay + $FEBotpay + $FEBrhpay + $FEBrhpay200 + $FEBotrhpay + $FEBshpay + $FEBotshpay + $FEBrdpay + $FEBotrdpay + $FEBrdrhpay + $FEBotrdrhpay + $FEBrdshpay + $FEBotrdshpay + $FEBlvpay);
		$FEBearnings = number_format((float) $FEBearnings,2,'.','');
		//FEB DEDUCTS
		$FEBphd = number_format((float) $FEBphd + $febpayarray['philhealth_deduct'],2,'.','');
		$FEBsssd = number_format((float) $FEBsssd + $febpayarray['sss_deduct'],2,'.','');
		$FEBpid = number_format((float) $FEBpid + $febpayarray['pagibig_deduct'],2,'.','');
		$FEBtd = number_format((float) $FEBtd + $febpayarray['tax_deduct'],2,'.','');
		$FEBsssld = number_format((float) $FEBsssld + $febpayarray['sssloan_deduct'],2,'.','');
		$FEBpild = number_format((float) $FEBpild + $febpayarray['pagibigloan_deduct'],2,'.','');
	
		$FEBdeductions = ($FEBphd + $FEBsssd + $FEBpid + $FEBtd + $FEBsssld + $FEBpild);
		$FEBdeductions = number_format((float) $FEBdeductions,2,'.','');
		//FEB NET PAYS
		$FEBnetpay = $FEBearnings - $FEBdeductions;
		$FEBnetpay = number_format((float) $FEBnetpay,2,'.','');

	endwhile;//FEBpaywhile
		if ($feb == 0 ){
			$FEBbpay = number_format((float)0,2,'.','');
			$FEBotpay = number_format((float)0,2,'.','');
			$FEBrhpay = number_format((float)0,2,'.','');
			$FEBrhpay200 = number_format((float)0,2,'.','');
			$FEBotrhpay = number_format((float)0,2,'.','');
			$FEBshpay = number_format((float)0,2,'.','');
			$FEBotshpay = number_format((float)0,2,'.','');
			$FEBrdpay = number_format((float)0,2,'.','');
			$FEBotrdpay = number_format((float)0,2,'.','');
			$FEBrdrhpay = number_format((float)0,2,'.','');
			$FEBotrdrhpay = number_format((float)0,2,'.','');
			$FEBrdshpay = number_format((float)0,2,'.','');
			$FEBotrdshpay = number_format((float)0,2,'.','');
			$FEBlvpay = number_format((float)0,2,'.','');
			$FEBearnings = number_format((float)0,2,'.','');
			$FEBphd = number_format((float)0,2,'.','');
			$FEBsssd = number_format((float)0,2,'.','');
			$FEBpid = number_format((float)0,2,'.','');
			$FEBtd = number_format((float)0,2,'.','');
			$FEBsssld = number_format((float)0,2,'.','');
			$FEBpild = number_format((float)0,2,'.','');
			$FEBdeductions = number_format((float)0,2,'.','');
			$FEBnetpay = number_format((float)0,2,'.','');

		}

	//MARCHPAY
	$marpayqry = "SELECT * FROM PAY_PER_PERIOD WHERE emp_id = '$empid' AND pperiod_month = 'March' AND pperiod_year = '$yearreport'";
	$marpayexecqry = mysqli_query($conn,$marpayqry) or die ("FAILED MARPAY ".mysqli_error($conn));
	while($marpayarray = mysqli_fetch_array($marpayexecqry)):;//MARpaywhile
		$mar = $mar+1;
		//MAR PAYS
		$MARbpay = number_format((float) $MARbpay + $marpayarray['reg_pay'],2,'.','');
		$MARotpay = number_format((float) $MARotpay + $marpayarray['ot_pay'],2,'.','');
		$MARrhpay = number_format((float) $MARrhpay + $marpayarray['hday_pay'],2,'.','');
		$MARrhpay200 = number_format((float) $MARrhpay200 + $marpayarray['hday200_pay'],2,'.','');
		$MARotrhpay = number_format((float) $MARotrhpay + $marpayarray['otrh_pay'],2,'.','');
		$MARshpay = number_format((float) $MARshpay  + $marpayarray['shday_pay'],2,'.','');
		$MARotshpay = number_format((float) $MARotshpay + $marpayarray['otsh_pay'],2,'.','');
		$MARrdpay = number_format((float) $MARrdpay + $marpayarray['rd_pay'],2,'.','');
		$MARotrdpay = number_format((float) $MARotrdpay + $marpayarray['otrd_pay'],2,'.','');
		$MARrdrhpay = number_format((float) $MARrdrhpay+ $marpayarray['rdrh_pay'],2,'.','');
		$MARotrdrhpay = number_format((float) $MARotrdrhpay + $marpayarray['otrdrh_pay'],2,'.','');
		$MARrdshpay = number_format((float) $MARrdshpay + $marpayarray['rdsh_pay'],2,'.','');
		$MARotrdshpay = number_format((float) $MARotrdshpay + $marpayarray['otrdsh_pay'],2,'.','');
		$MARlvpay = number_format((float) $MARlvpay + $marpayarray['lv_pay'],2,'.','');
		
		$MARearnings = ($MARbpay + $MARotpay + $MARrhpay + $MARrhpay200 + $MARotrhpay + $MARshpay + $MARotshpay + $MARrdpay + $MARotrdpay + $MARrdrhpay + $MARotrdrhpay + $MARrdshpay + $MARotrdshpay + $MARlvpay);
		$MARearnings = number_format((float) $MARearnings,2,'.','');
		//MAR DEDUCTS
		$MARphd = number_format((float) $MARphd + $marpayarray['philhealth_deduct'],2,'.','');
		$MARsssd = number_format((float) $MARsssd + $marpayarray['sss_deduct'],2,'.','');
		$MARpid = number_format((float) $MARpid + $marpayarray['pagibig_deduct'],2,'.','');
		$MARtd = number_format((float) $MARtd + $marpayarray['tax_deduct'],2,'.','');
		$MARsssld = number_format((float) $MARsssld + $marpayarray['sssloan_deduct'],2,'.','');
		$MARpild = number_format((float) $MARpild + $marpayarray['pagibigloan_deduct'],2,'.','');
	
		$MARdeductions = ($MARphd + $MARsssd + $MARpid + $MARtd + $MARsssld + $MARpild);
		$MARdeductions = number_format((float) $MARdeductions,2,'.','');
		//FEB NET PAYS
		$MARnetpay = $MARearnings - $MARdeductions;
		$MARnetpay = number_format((float) $MARnetpay,2,'.','');

	endwhile;//MARpayendwhile
		if ($mar == 0 ){
			$MARbpay = number_format((float)0,2,'.','');
			$MARotpay = number_format((float)0,2,'.','');
			$MARrhpay = number_format((float)0,2,'.','');
			$MARrhpay200 = number_format((float)0,2,'.','');
			$MARotrhpay = number_format((float)0,2,'.','');
			$MARshpay = number_format((float)0,2,'.','');
			$MARotshpay = number_format((float)0,2,'.','');
			$MARrdpay = number_format((float)0,2,'.','');
			$MARotrdpay = number_format((float)0,2,'.','');
			$MARrdrhpay = number_format((float)0,2,'.','');
			$MARotrdrhpay = number_format((float)0,2,'.','');
			$MARrdshpay = number_format((float)0,2,'.','');
			$MARotrdshpay = number_format((float)0,2,'.','');
			$MARlvpay = number_format((float)0,2,'.','');
			$MARearnings = number_format((float)0,2,'.','');
			$MARphd = number_format((float)0,2,'.','');
			$MARsssd = number_format((float)0,2,'.','');
			$MARpid = number_format((float)0,2,'.','');
			$MARtd = number_format((float)0,2,'.','');
			$MARsssld = number_format((float)0,2,'.','');
			$MARpild = number_format((float)0,2,'.','');
			$MARdeductions = number_format((float)0,2,'.','');
			$MARnetpay = number_format((float)0,2,'.','');

		}
	//MARCHPAYEND

	//APR PAY
	$aprpayqry = "SELECT * FROM PAY_PER_PERIOD WHERE emp_id = '$empid' AND pperiod_month = 'April' AND pperiod_year = '$yearreport'";
	$aprpayexecqry = mysqli_query($conn,$aprpayqry) or die ("FAILED APRPAY ".mysqli_error($conn));
	while($aprpayarray = mysqli_fetch_array($aprpayexecqry)):;//APRpaywhile
		$apr = $apr+1;
		//APR PAYS
		$APRbpay = number_format((float) $APRbpay + $aprpayarray['reg_pay'],2,'.','');
		$APRotpay = number_format((float) $APRotpay + $aprpayarray['ot_pay'],2,'.','');
		$APRrhpay = number_format((float) $APRrhpay + $aprpayarray['hday_pay'],2,'.','');
		$APRrhpay200 = number_format((float) $APRrhpay200 + $aprpayarray['hday200_pay'],2,'.','');
		$APRotrhpay = number_format((float) $APRotrhpay + $aprpayarray['otrh_pay'],2,'.','');
		$APRshpay = number_format((float) $APRshpay  + $aprpayarray['shday_pay'],2,'.','');
		$APRotshpay = number_format((float) $APRotshpay + $aprpayarray['otsh_pay'],2,'.','');
		$APRrdpay = number_format((float) $APRrdpay + $aprpayarray['rd_pay'],2,'.','');
		$APRotrdpay = number_format((float) $APRotrdpay + $aprpayarray['otrd_pay'],2,'.','');
		$APRrdrhpay = number_format((float) $APRrdrhpay+ $aprpayarray['rdrh_pay'],2,'.','');
		$APRotrdrhpay = number_format((float) $APRotrdrhpay + $aprpayarray['otrdrh_pay'],2,'.','');
		$APRrdshpay = number_format((float) $APRrdshpay + $aprpayarray['rdsh_pay'],2,'.','');
		$APRotrdshpay = number_format((float) $APRotrdshpay + $aprpayarray['otrdsh_pay'],2,'.','');
		$APRlvpay = number_format((float) $APRlvpay + $aprpayarray['lv_pay'],2,'.','');
		
		$APRearnings = ($APRbpay + $APRotpay + $APRrhpay + $APRrhpay200 + $APRotrhpay + $APRshpay + $APRotshpay + $APRrdpay + $APRotrdpay + $APRrdrhpay + $APRotrdrhpay + $APRrdshpay + $APRotrdshpay + $APRlvpay);
		$APRearnings = number_format((float) $APRearnings,2,'.','');
		//APR DEDUCTS
		$APRphd = number_format((float) $APRphd + $aprpayarray['philhealth_deduct'],2,'.','');
		$APRsssd = number_format((float) $APRsssd + $aprpayarray['sss_deduct'],2,'.','');
		$APRpid = number_format((float) $APRpid + $aprpayarray['pagibig_deduct'],2,'.','');
		$APRtd = number_format((float) $APRtd + $aprpayarray['tax_deduct'],2,'.','');
		$APRsssld = number_format((float) $APRsssld + $aprpayarray['sssloan_deduct'],2,'.','');
		$APRpild = number_format((float) $APRpild + $aprpayarray['pagibigloan_deduct'],2,'.','');
	
		$APRdeductions = ($APRphd + $APRsssd + $APRpid + $APRtd + $APRsssld + $APRpild);
		$APRdeductions = number_format((float) $APRdeductions,2,'.','');
		//APR NET PAYS
		$APRnetpay = $APRearnings - $APRdeductions;
		$APRnetpay = number_format((float) $APRnetpay,2,'.','');

	endwhile;//APRpayendwhile
		if ($apr == 0 ){
			$APRbpay = number_format((float)0,2,'.','');
			$APRotpay = number_format((float)0,2,'.','');
			$APRrhpay = number_format((float)0,2,'.','');
			$APRrhpay200 = number_format((float)0,2,'.','');
			$APRotrhpay = number_format((float)0,2,'.','');
			$APRshpay = number_format((float)0,2,'.','');
			$APRotshpay = number_format((float)0,2,'.','');
			$APRrdpay = number_format((float)0,2,'.','');
			$APRotrdpay = number_format((float)0,2,'.','');
			$APRrdrhpay = number_format((float)0,2,'.','');
			$APRotrdrhpay = number_format((float)0,2,'.','');
			$APRrdshpay = number_format((float)0,2,'.','');
			$APRotrdshpay = number_format((float)0,2,'.','');
			$APRlvpay = number_format((float)0,2,'.','');
			$APRearnings = number_format((float)0,2,'.','');
			$APRphd = number_format((float)0,2,'.','');
			$APRsssd = number_format((float)0,2,'.','');
			$APRpid = number_format((float)0,2,'.','');
			$APRtd = number_format((float)0,2,'.','');
			$APRsssld = number_format((float)0,2,'.','');
			$APRpild = number_format((float)0,2,'.','');
			$APRdeductions = number_format((float)0,2,'.','');
			$APRnetpay = number_format((float)0,2,'.','');

		}
	//APR PAYEND

	//MAY PAYS
	$maypayqry = "SELECT * FROM PAY_PER_PERIOD WHERE emp_id = '$empid' AND pperiod_month = 'May' AND pperiod_year = '$yearreport'";
	$maypayexecqry = mysqli_query($conn,$maypayqry) or die ("FAILED MAYPAY ".mysqli_error($conn));
	while($maypayarray = mysqli_fetch_array($maypayexecqry)):;//MAYpaywhile
		$may = $may+1;
		//MAY PAYS
		$MAYbpay = number_format((float) $MAYbpay + $maypayarray['reg_pay'],2,'.','');
		$MAYotpay = number_format((float) $MAYotpay + $maypayarray['ot_pay'],2,'.','');
		$MAYrhpay = number_format((float) $MAYrhpay + $maypayarray['hday_pay'],2,'.','');
		$MAYrhpay200 = number_format((float) $MAYrhpay200 + $maypayarray['hday200_pay'],2,'.','');
		$MAYotrhpay = number_format((float) $MAYotrhpay + $maypayarray['otrh_pay'],2,'.','');
		$MAYshpay = number_format((float) $MAYshpay  + $maypayarray['shday_pay'],2,'.','');
		$MAYotshpay = number_format((float) $MAYotshpay + $maypayarray['otsh_pay'],2,'.','');
		$MAYrdpay = number_format((float) $MAYrdpay + $maypayarray['rd_pay'],2,'.','');
		$MAYotrdpay = number_format((float) $MAYotrdpay + $maypayarray['otrd_pay'],2,'.','');
		$MAYrdrhpay = number_format((float) $MAYrdrhpay+ $maypayarray['rdrh_pay'],2,'.','');
		$MAYotrdrhpay = number_format((float) $MAYotrdrhpay + $maypayarray['otrdrh_pay'],2,'.','');
		$MAYrdshpay = number_format((float) $MAYrdshpay + $maypayarray['rdsh_pay'],2,'.','');
		$MAYotrdshpay = number_format((float) $MAYotrdshpay + $maypayarray['otrdsh_pay'],2,'.','');
		$MAYlvpay = number_format((float) $MAYlvpay + $maypayarray['lv_pay'],2,'.','');
		
		$MAYearnings = ($MAYbpay + $MAYotpay + $MAYrhpay + $MAYrhpay200 + $MAYotrhpay + $MAYshpay + $MAYotshpay + $MAYrdpay + $MAYotrdpay + $MAYrdrhpay + $MAYotrdrhpay + $MAYrdshpay + $MAYotrdshpay + $MAYlvpay);
		$MAYearnings = number_format((float) $MAYearnings,2,'.','');
		//MAY DEDUCTS
		$MAYphd = number_format((float) $MAYphd + $maypayarray['philhealth_deduct'],2,'.','');
		$MAYsssd = number_format((float) $MAYsssd + $maypayarray['sss_deduct'],2,'.','');
		$MAYpid = number_format((float) $MAYpid + $maypayarray['pagibig_deduct'],2,'.','');
		$MAYtd = number_format((float) $MAYtd + $maypayarray['tax_deduct'],2,'.','');
		$MAYsssld = number_format((float) $MAYsssld + $maypayarray['sssloan_deduct'],2,'.','');
		$MAYpild = number_format((float) $MAYpild + $maypayarray['pagibigloan_deduct'],2,'.','');
	
		$MAYdeductions = ($MAYphd + $MAYsssd + $MAYpid + $MAYtd + $MAYsssld + $MAYpild);
		$MAYdeductions = number_format((float) $MAYdeductions,2,'.','');
		//MAY NET PAYS
		$MAYnetpay = $MAYearnings - $MAYdeductions;
		$MAYnetpay = number_format((float) $MAYnetpay,2,'.','');

	endwhile;//maypayendwhile
		if ($may == 0 ){
			$MAYbpay = number_format((float)0,2,'.','');
			$MAYotpay = number_format((float)0,2,'.','');
			$MAYrhpay = number_format((float)0,2,'.','');
			$MAYrhpay200 = number_format((float)0,2,'.','');
			$MAYotrhpay = number_format((float)0,2,'.','');
			$MAYshpay = number_format((float)0,2,'.','');
			$MAYotshpay = number_format((float)0,2,'.','');
			$MAYrdpay = number_format((float)0,2,'.','');
			$MAYotrdpay = number_format((float)0,2,'.','');
			$MAYrdrhpay = number_format((float)0,2,'.','');
			$MAYotrdrhpay = number_format((float)0,2,'.','');
			$MAYrdshpay = number_format((float)0,2,'.','');
			$MAYotrdshpay = number_format((float)0,2,'.','');
			$MAYlvpay = number_format((float)0,2,'.','');
			$MAYearnings = number_format((float)0,2,'.','');
			$MAYphd = number_format((float)0,2,'.','');
			$MAYsssd = number_format((float)0,2,'.','');
			$MAYpid = number_format((float)0,2,'.','');
			$MAYtd = number_format((float)0,2,'.','');
			$MAYsssld = number_format((float)0,2,'.','');
			$MAYpild = number_format((float)0,2,'.','');
			$MAYdeductions = number_format((float)0,2,'.','');
			$MAYnetpay = number_format((float)0,2,'.','');

		}
	//MAY PAYEND

	//JUN PAYS
	$junpayqry = "SELECT * FROM PAY_PER_PERIOD WHERE emp_id = '$empid' AND pperiod_month = 'June' AND pperiod_year = '$yearreport'";
	$junpayexecqry = mysqli_query($conn,$junpayqry) or die ("FAILED JUNPAY ".mysqli_error($conn));
	while($junpayarray = mysqli_fetch_array($junpayexecqry)):;//MAYpaywhile
		$jun = $jun+1;
		//JUN PAYS
		$JUNbpay = number_format((float) $JUNbpay + $junpayarray['reg_pay'],2,'.','');
		$JUNotpay = number_format((float) $JUNotpay + $junpayarray['ot_pay'],2,'.','');
		$JUNrhpay = number_format((float) $JUNrhpay + $junpayarray['hday_pay'],2,'.','');
		$JUNrhpay200 = number_format((float) $JUNrhpay200 + $junpayarray['hday200_pay'],2,'.','');
		$JUNotrhpay = number_format((float) $JUNotrhpay + $junpayarray['otrh_pay'],2,'.','');
		$JUNshpay = number_format((float) $JUNshpay  + $junpayarray['shday_pay'],2,'.','');
		$JUNotshpay = number_format((float) $JUNotshpay + $junpayarray['otsh_pay'],2,'.','');
		$JUNrdpay = number_format((float) $JUNrdpay + $junpayarray['rd_pay'],2,'.','');
		$JUNotrdpay = number_format((float) $JUNotrdpay + $junpayarray['otrd_pay'],2,'.','');
		$JUNrdrhpay = number_format((float) $JUNrdrhpay+ $junpayarray['rdrh_pay'],2,'.','');
		$JUNotrdrhpay = number_format((float) $JUNotrdrhpay + $junpayarray['otrdrh_pay'],2,'.','');
		$JUNrdshpay = number_format((float) $JUNrdshpay + $junpayarray['rdsh_pay'],2,'.','');
		$JUNotrdshpay = number_format((float) $JUNotrdshpay + $junpayarray['otrdsh_pay'],2,'.','');
		$JUNlvpay = number_format((float) $JUNlvpay + $junpayarray['lv_pay'],2,'.','');
		
		$JUNearnings = ($JUNbpay + $JUNotpay + $JUNrhpay + $JUNrhpay200 + $JUNotrhpay + $JUNshpay + $JUNotshpay + $JUNrdpay + $JUNotrdpay + $JUNrdrhpay + $JUNotrdrhpay + $JUNrdshpay + $JUNotrdshpay + $JUNlvpay);
		$JUNearnings = number_format((float) $JUNearnings,2,'.','');
		//JUN DEDUCTS
		$JUNphd = number_format((float) $JUNphd + $junpayarray['philhealth_deduct'],2,'.','');
		$JUNsssd = number_format((float) $JUNsssd + $junpayarray['sss_deduct'],2,'.','');
		$JUNpid = number_format((float) $JUNpid + $junpayarray['pagibig_deduct'],2,'.','');
		$JUNtd = number_format((float) $JUNtd + $junpayarray['tax_deduct'],2,'.','');
		$JUNsssld = number_format((float) $JUNsssld + $junpayarray['sssloan_deduct'],2,'.','');
		$JUNpild = number_format((float) $JUNpild + $junpayarray['pagibigloan_deduct'],2,'.','');
	
		$JUNdeductions = ($JUNphd + $JUNsssd + $JUNpid + $JUNtd + $JUNsssld + $JUNpild);
		$JUNdeductions = number_format((float) $JUNdeductions,2,'.','');
		//JUN NET PAYS
		$JUNnetpay = $JUNearnings - $JUNdeductions;
		$JUNnetpay = number_format((float) $JUNnetpay,2,'.','');

	endwhile;//junpayendwhile
		if ($jun == 0 ){
			$JUNbpay = number_format((float)0,2,'.','');
			$JUNotpay = number_format((float)0,2,'.','');
			$JUNrhpay = number_format((float)0,2,'.','');
			$JUNrhpay200 = number_format((float)0,2,'.','');
			$JUNotrhpay = number_format((float)0,2,'.','');
			$JUNshpay = number_format((float)0,2,'.','');
			$JUNotshpay = number_format((float)0,2,'.','');
			$JUNrdpay = number_format((float)0,2,'.','');
			$JUNotrdpay = number_format((float)0,2,'.','');
			$JUNrdrhpay = number_format((float)0,2,'.','');
			$JUNotrdrhpay = number_format((float)0,2,'.','');
			$JUNrdshpay = number_format((float)0,2,'.','');
			$JUNotrdshpay = number_format((float)0,2,'.','');
			$JUNlvpay = number_format((float)0,2,'.','');
			$JUNearnings = number_format((float)0,2,'.','');
			$JUNphd = number_format((float)0,2,'.','');
			$JUNsssd = number_format((float)0,2,'.','');
			$JUNpid = number_format((float)0,2,'.','');
			$JUNtd = number_format((float)0,2,'.','');
			$JUNsssld = number_format((float)0,2,'.','');
			$JUNpild = number_format((float)0,2,'.','');
			$JUNdeductions = number_format((float)0,2,'.','');
			$JUNnetpay = number_format((float)0,2,'.','');

		}	

	//JUN PAYEND

	//JUL PAYS
	$julpayqry = "SELECT * FROM PAY_PER_PERIOD WHERE emp_id = '$empid' AND pperiod_month = 'July' AND pperiod_year = '$yearreport'";
	$julpayexecqry = mysqli_query($conn,$julpayqry) or die ("FAILED JULPAY ".mysqli_error($conn));
	while($julpayarray = mysqli_fetch_array($julpayexecqry)):;//JULpaywhile
		$jul = $jul+1;
		//JUL PAYS
		$JULbpay = number_format((float) $JULbpay + $julpayarray['reg_pay'],2,'.','');
		$JULotpay = number_format((float) $JULotpay + $julpayarray['ot_pay'],2,'.','');
		$JULrhpay = number_format((float) $JULrhpay + $julpayarray['hday_pay'],2,'.','');
		$JULrhpay200 = number_format((float) $JULrhpay200 + $julpayarray['hday200_pay'],2,'.','');
		$JULotrhpay = number_format((float) $JULotrhpay + $julpayarray['otrh_pay'],2,'.','');
		$JULshpay = number_format((float) $JULshpay  + $julpayarray['shday_pay'],2,'.','');
		$JULotshpay = number_format((float) $JULotshpay + $julpayarray['otsh_pay'],2,'.','');
		$JULrdpay = number_format((float) $JULrdpay + $julpayarray['rd_pay'],2,'.','');
		$JULotrdpay = number_format((float) $JULotrdpay + $julpayarray['otrd_pay'],2,'.','');
		$JULrdrhpay = number_format((float) $JULrdrhpay+ $julpayarray['rdrh_pay'],2,'.','');
		$JULotrdrhpay = number_format((float) $JULotrdrhpay + $julpayarray['otrdrh_pay'],2,'.','');
		$JULrdshpay = number_format((float) $JULrdshpay + $julpayarray['rdsh_pay'],2,'.','');
		$JULotrdshpay = number_format((float) $JULotrdshpay + $julpayarray['otrdsh_pay'],2,'.','');
		$JULlvpay = number_format((float) $JULlvpay + $julpayarray['lv_pay'],2,'.','');
		
		$JULearnings = ($JULbpay + $JULotpay + $JULrhpay + $JULrhpay200 + $JULotrhpay + $JULshpay + $JULotshpay + $JULrdpay + $JULotrdpay + $JULrdrhpay + $JULotrdrhpay + $JULrdshpay + $JULotrdshpay + $JULlvpay);
		$JULearnings = number_format((float) $JULearnings,2,'.','');
		//JUL DEDUCTS
		$JULphd = number_format((float) $JULphd + $julpayarray['philhealth_deduct'],2,'.','');
		$JULsssd = number_format((float) $JULsssd + $julpayarray['sss_deduct'],2,'.','');
		$JULpid = number_format((float) $JULpid + $julpayarray['pagibig_deduct'],2,'.','');
		$JULtd = number_format((float) $JULtd + $julpayarray['tax_deduct'],2,'.','');
		$JULsssld = number_format((float) $JULsssld + $julpayarray['sssloan_deduct'],2,'.','');
		$JULpild = number_format((float) $JULpild + $julpayarray['pagibigloan_deduct'],2,'.','');
	
		$JULdeductions = ($JULphd + $JULsssd + $JULpid + $JULtd + $JULsssld + $JULpild);
		$JULdeductions = number_format((float) $JULdeductions,2,'.','');
		//JUL NET PAYS
		$JULnetpay = $JULearnings - $JULdeductions;
		$JULnetpay = number_format((float) $JULnetpay,2,'.','');

	endwhile;//julpayendwhile
		if ($jul == 0 ){
			$JULbpay = number_format((float)0,2,'.','');
			$JULotpay = number_format((float)0,2,'.','');
			$JULrhpay = number_format((float)0,2,'.','');
			$JULrhpay200 = number_format((float)0,2,'.','');
			$JULotrhpay = number_format((float)0,2,'.','');
			$JULshpay = number_format((float)0,2,'.','');
			$JULotshpay = number_format((float)0,2,'.','');
			$JULrdpay = number_format((float)0,2,'.','');
			$JULotrdpay = number_format((float)0,2,'.','');
			$JULrdrhpay = number_format((float)0,2,'.','');
			$JULotrdrhpay = number_format((float)0,2,'.','');
			$JULrdshpay = number_format((float)0,2,'.','');
			$JULotrdshpay = number_format((float)0,2,'.','');
			$JULlvpay = number_format((float)0,2,'.','');
			$JULearnings = number_format((float)0,2,'.','');
			$JULphd = number_format((float)0,2,'.','');
			$JULsssd = number_format((float)0,2,'.','');
			$JULpid = number_format((float)0,2,'.','');
			$JULtd = number_format((float)0,2,'.','');
			$JULsssld = number_format((float)0,2,'.','');
			$JULpild = number_format((float)0,2,'.','');
			$JULdeductions = number_format((float)0,2,'.','');
			$JULnetpay = number_format((float)0,2,'.','');
		}
	//JUL PAYEND

	//AUG PAYS
	$augpayqry = "SELECT * FROM PAY_PER_PERIOD WHERE emp_id = '$empid' AND pperiod_month = 'August' AND pperiod_year = '$yearreport'";
	$augpayexecqry = mysqli_query($conn,$augpayqry) or die ("FAILED AUGPAY ".mysqli_error($conn));
	while($AUGpayarray = mysqli_fetch_array($augpayexecqry)):;//AUGpaywhile
		$aug = $aug+1;
		//AUG PAYS
		$AUGbpay = number_format((float) $AUGbpay + $AUGpayarray['reg_pay'],2,'.','');
		$AUGotpay = number_format((float) $AUGotpay + $AUGpayarray['ot_pay'],2,'.','');
		$AUGrhpay = number_format((float) $AUGrhpay + $AUGpayarray['hday_pay'],2,'.','');
		$AUGrhpay200 = number_format((float) $AUGrhpay200 + $AUGpayarray['hday200_pay'],2,'.','');
		$AUGotrhpay = number_format((float) $AUGotrhpay + $AUGpayarray['otrh_pay'],2,'.','');
		$AUGshpay = number_format((float) $AUGshpay  + $AUGpayarray['shday_pay'],2,'.','');
		$AUGotshpay = number_format((float) $AUGotshpay + $AUGpayarray['otsh_pay'],2,'.','');
		$AUGrdpay = number_format((float) $AUGrdpay + $AUGpayarray['rd_pay'],2,'.','');
		$AUGotrdpay = number_format((float) $AUGotrdpay + $AUGpayarray['otrd_pay'],2,'.','');
		$AUGrdrhpay = number_format((float) $AUGrdrhpay+ $AUGpayarray['rdrh_pay'],2,'.','');
		$AUGotrdrhpay = number_format((float) $AUGotrdrhpay + $AUGpayarray['otrdrh_pay'],2,'.','');
		$AUGrdshpay = number_format((float) $AUGrdshpay + $AUGpayarray['rdsh_pay'],2,'.','');
		$AUGotrdshpay = number_format((float) $AUGotrdshpay + $AUGpayarray['otrdsh_pay'],2,'.','');
		$AUGlvpay = number_format((float) $AUGlvpay + $AUGpayarray['lv_pay'],2,'.','');
		
		$AUGearnings = ($AUGbpay + $AUGotpay + $AUGrhpay + $AUGrhpay200 + $AUGotrhpay + $AUGshpay + $AUGotshpay + $AUGrdpay + $AUGotrdpay + $AUGrdrhpay + $AUGotrdrhpay + $AUGrdshpay + $AUGotrdshpay + $AUGlvpay);
		$AUGearnings = number_format((float) $AUGearnings,2,'.','');
		//AUG DEDUCTS
		$AUGphd = number_format((float) $AUGphd + $AUGpayarray['philhealth_deduct'],2,'.','');
		$AUGsssd = number_format((float) $AUGsssd + $AUGpayarray['sss_deduct'],2,'.','');
		$AUGpid = number_format((float) $AUGpid + $AUGpayarray['pagibig_deduct'],2,'.','');
		$AUGtd = number_format((float) $AUGtd + $AUGpayarray['tax_deduct'],2,'.','');
		$AUGsssld = number_format((float) $AUGsssld + $AUGpayarray['sssloan_deduct'],2,'.','');
		$AUGpild = number_format((float) $AUGpild + $AUGpayarray['pagibigloan_deduct'],2,'.','');
	
		$AUGdeductions = ($AUGphd + $AUGsssd + $AUGpid + $AUGtd + $AUGsssld + $AUGpild);
		$AUGdeductions = number_format((float) $AUGdeductions,2,'.','');
		//AUG NET PAYS
		$AUGnetpay = $AUGearnings - $AUGdeductions;
		$AUGnetpay = number_format((float) $AUGnetpay,2,'.','');

	endwhile;//AUGpayendwhile
		if ($aug == 0 ){
			$AUGbpay = number_format((float)0,2,'.','');
			$AUGotpay = number_format((float)0,2,'.','');
			$AUGrhpay = number_format((float)0,2,'.','');
			$AUGrhpay200 = number_format((float)0,2,'.','');
			$AUGotrhpay = number_format((float)0,2,'.','');
			$AUGshpay = number_format((float)0,2,'.','');
			$AUGotshpay = number_format((float)0,2,'.','');
			$AUGrdpay = number_format((float)0,2,'.','');
			$AUGotrdpay = number_format((float)0,2,'.','');
			$AUGrdrhpay = number_format((float)0,2,'.','');
			$AUGotrdrhpay = number_format((float)0,2,'.','');
			$AUGrdshpay = number_format((float)0,2,'.','');
			$AUGotrdshpay = number_format((float)0,2,'.','');
			$AUGlvpay = number_format((float)0,2,'.','');
			$AUGearnings = number_format((float)0,2,'.','');
			$AUGphd = number_format((float)0,2,'.','');
			$AUGsssd = number_format((float)0,2,'.','');
			$AUGpid = number_format((float)0,2,'.','');
			$AUGtd = number_format((float)0,2,'.','');
			$AUGsssld = number_format((float)0,2,'.','');
			$AUGpild = number_format((float)0,2,'.','');
			$AUGdeductions = number_format((float)0,2,'.','');
			$AUGnetpay = number_format((float)0,2,'.','');
		}

	//AUG PAYEND

	//SEP PAYS
	$seppayqry = "SELECT * FROM PAY_PER_PERIOD WHERE emp_id = '$empid' AND pperiod_month = 'September' AND pperiod_year = '$yearreport'";
	$seppayexecqry = mysqli_query($conn,$seppayqry) or die ("FAILED SEP PAY ".mysqli_error($conn));
	while($SEPpayarray = mysqli_fetch_array($seppayexecqry)):;//SEPpaywhile
		$sep = $sep+1;
		//SEP PAYS
		$SEPbpay = number_format((float) $SEPbpay + $SEPpayarray['reg_pay'],2,'.','');
		$SEPotpay = number_format((float) $SEPotpay + $SEPpayarray['ot_pay'],2,'.','');
		$SEPrhpay = number_format((float) $SEPrhpay + $SEPpayarray['hday_pay'],2,'.','');
		$SEPrhpay200 = number_format((float) $SEPrhpay200 + $SEPpayarray['hday200_pay'],2,'.','');
		$SEPotrhpay = number_format((float) $SEPotrhpay + $SEPpayarray['otrh_pay'],2,'.','');
		$SEPshpay = number_format((float) $SEPshpay  + $SEPpayarray['shday_pay'],2,'.','');
		$SEPotshpay = number_format((float) $SEPotshpay + $SEPpayarray['otsh_pay'],2,'.','');
		$SEPrdpay = number_format((float) $SEPrdpay + $SEPpayarray['rd_pay'],2,'.','');
		$SEPotrdpay = number_format((float) $SEPotrdpay + $SEPpayarray['otrd_pay'],2,'.','');
		$SEPrdrhpay = number_format((float) $SEPrdrhpay+ $SEPpayarray['rdrh_pay'],2,'.','');
		$SEPotrdrhpay = number_format((float) $SEPotrdrhpay + $SEPpayarray['otrdrh_pay'],2,'.','');
		$SEPrdshpay = number_format((float) $SEPrdshpay + $SEPpayarray['rdsh_pay'],2,'.','');
		$SEPotrdshpay = number_format((float) $SEPotrdshpay + $SEPpayarray['otrdsh_pay'],2,'.','');
		$SEPlvpay = number_format((float) $SEPlvpay + $SEPpayarray['lv_pay'],2,'.','');
		
		$SEPearnings = ($SEPbpay + $SEPotpay + $SEPrhpay + $SEPrhpay200 + $SEPotrhpay + $SEPshpay + $SEPotshpay + $SEPrdpay + $SEPotrdpay + $SEPrdrhpay + $SEPotrdrhpay + $SEPrdshpay + $SEPotrdshpay + $SEPlvpay);
		$SEPearnings = number_format((float) $SEPearnings,2,'.','');
		//SEP DEDUCTS
		$SEPphd = number_format((float) $SEPphd + $SEPpayarray['philhealth_deduct'],2,'.','');
		$SEPsssd = number_format((float) $SEPsssd + $SEPpayarray['sss_deduct'],2,'.','');
		$SEPpid = number_format((float) $SEPpid + $SEPpayarray['pagibig_deduct'],2,'.','');
		$SEPtd = number_format((float) $SEPtd + $SEPpayarray['tax_deduct'],2,'.','');
		$SEPsssld = number_format((float) $SEPsssld + $SEPpayarray['sssloan_deduct'],2,'.','');
		$SEPpild = number_format((float) $SEPpild + $SEPpayarray['pagibigloan_deduct'],2,'.','');
	
		$SEPdeductions = ($SEPphd + $SEPsssd + $SEPpid + $SEPtd + $SEPsssld + $SEPpild);
		$SEPdeductions = number_format((float) $SEPdeductions,2,'.','');
		//AUG NET PAYS
		$SEPnetpay = $SEPearnings - $SEPdeductions;
		$SEPnetpay = number_format((float) $SEPnetpay,2,'.','');

	endwhile;//AUGpayendwhile
		if ($sep == 0 ){
			$SEPbpay = number_format((float)0,2,'.','');
			$SEPotpay = number_format((float)0,2,'.','');
			$SEPrhpay = number_format((float)0,2,'.','');
			$SEPrhpay200 = number_format((float)0,2,'.','');
			$SEPotrhpay = number_format((float)0,2,'.','');
			$SEPshpay = number_format((float)0,2,'.','');
			$SEPotshpay = number_format((float)0,2,'.','');
			$SEPrdpay = number_format((float)0,2,'.','');
			$SEPotrdpay = number_format((float)0,2,'.','');
			$SEPrdrhpay = number_format((float)0,2,'.','');
			$SEPotrdrhpay = number_format((float)0,2,'.','');
			$SEPrdshpay = number_format((float)0,2,'.','');
			$SEPotrdshpay = number_format((float)0,2,'.','');
			$SEPlvpay = number_format((float)0,2,'.','');
			$SEPearnings = number_format((float)0,2,'.','');
			$SEPphd = number_format((float)0,2,'.','');
			$SEPsssd = number_format((float)0,2,'.','');
			$SEPpid = number_format((float)0,2,'.','');
			$SEPtd = number_format((float)0,2,'.','');
			$SEPsssld = number_format((float)0,2,'.','');
			$SEPpild = number_format((float)0,2,'.','');
			$SEPdeductions = number_format((float)0,2,'.','');
			$SEPnetpay = number_format((float)0,2,'.','');
		}
	//SEP PAYEND	

	//OCT PAYS
	$octpayqry = "SELECT * FROM PAY_PER_PERIOD WHERE emp_id = '$empid' AND pperiod_month = 'October' AND pperiod_year = '$yearreport'";
	$octpayexecqry = mysqli_query($conn,$octpayqry) or die ("FAILED OCT PAY ".mysqli_error($conn));
	while($OCTpayarray = mysqli_fetch_array($octpayexecqry)):;//OCTpaywhile
		$oct = $oct+1;
		//OCT PAYS
		$OCTbpay = number_format((float) $OCTbpay + $OCTpayarray['reg_pay'],2,'.','');
		$OCTotpay = number_format((float) $OCTotpay + $OCTpayarray['ot_pay'],2,'.','');
		$OCTrhpay = number_format((float) $OCTrhpay + $OCTpayarray['hday_pay'],2,'.','');
		$OCTrhpay200 = number_format((float) $OCTrhpay200 + $OCTpayarray['hday200_pay'],2,'.','');
		$OCTotrhpay = number_format((float) $OCTotrhpay + $OCTpayarray['otrh_pay'],2,'.','');
		$OCTshpay = number_format((float) $OCTshpay  + $OCTpayarray['shday_pay'],2,'.','');
		$OCTotshpay = number_format((float) $OCTotshpay + $OCTpayarray['otsh_pay'],2,'.','');
		$OCTrdpay = number_format((float) $OCTrdpay + $OCTpayarray['rd_pay'],2,'.','');
		$OCTotrdpay = number_format((float) $OCTotrdpay + $OCTpayarray['otrd_pay'],2,'.','');
		$OCTrdrhpay = number_format((float) $OCTrdrhpay+ $OCTpayarray['rdrh_pay'],2,'.','');
		$OCTotrdrhpay = number_format((float) $OCTotrdrhpay + $OCTpayarray['otrdrh_pay'],2,'.','');
		$OCTrdshpay = number_format((float) $OCTrdshpay + $OCTpayarray['rdsh_pay'],2,'.','');
		$OCTotrdshpay = number_format((float) $OCTotrdshpay + $OCTpayarray['otrdsh_pay'],2,'.','');
		$OCTlvpay = number_format((float) $OCTlvpay + $OCTpayarray['lv_pay'],2,'.','');
		
		$OCTearnings = ($OCTbpay + $OCTotpay + $OCTrhpay + $OCTrhpay200 + $OCTotrhpay + $OCTshpay + $OCTotshpay + $OCTrdpay + $OCTotrdpay + $OCTrdrhpay + $OCTotrdrhpay + $OCTrdshpay + $OCTotrdshpay + $OCTlvpay);
		$OCTearnings = number_format((float) $OCTearnings,2,'.','');
		//OCT DEDUCTS
		$OCTphd = number_format((float) $OCTphd + $OCTpayarray['philhealth_deduct'],2,'.','');
		$OCTsssd = number_format((float) $OCTsssd + $OCTpayarray['sss_deduct'],2,'.','');
		$OCTpid = number_format((float) $OCTpid + $OCTpayarray['pagibig_deduct'],2,'.','');
		$OCTtd = number_format((float) $OCTtd + $OCTpayarray['tax_deduct'],2,'.','');
		$OCTsssld = number_format((float) $OCTsssld + $OCTpayarray['sssloan_deduct'],2,'.','');
		$OCTpild = number_format((float) $OCTpild + $OCTpayarray['pagibigloan_deduct'],2,'.','');
	
		$OCTdeductions = ($OCTphd + $OCTsssd + $OCTpid + $OCTtd + $OCTsssld + $OCTpild);
		$OCTdeductions = number_format((float) $OCTdeductions,2,'.','');
		//OCT NET PAYS
		$OCTnetpay = $OCTearnings - $OCTdeductions;
		$OCTnetpay = number_format((float) $OCTnetpay,2,'.','');

	endwhile;//OCTpayendwhile
		if ($oct == 0 ){
			$OCTbpay = number_format((float)0,2,'.','');
			$OCTotpay = number_format((float)0,2,'.','');
			$OCTrhpay = number_format((float)0,2,'.','');
			$OCTrhpay200 = number_format((float)0,2,'.','');
			$OCTotrhpay = number_format((float)0,2,'.','');
			$OCTshpay = number_format((float)0,2,'.','');
			$OCTotshpay = number_format((float)0,2,'.','');
			$OCTrdpay = number_format((float)0,2,'.','');
			$OCTotrdpay = number_format((float)0,2,'.','');
			$OCTrdrhpay = number_format((float)0,2,'.','');
			$OCTotrdrhpay = number_format((float)0,2,'.','');
			$OCTrdshpay = number_format((float)0,2,'.','');
			$OCTotrdshpay = number_format((float)0,2,'.','');
			$OCTlvpay = number_format((float)0,2,'.','');
			$OCTearnings = number_format((float)0,2,'.','');
			$OCTphd = number_format((float)0,2,'.','');
			$OCTsssd = number_format((float)0,2,'.','');
			$OCTpid = number_format((float)0,2,'.','');
			$OCTtd = number_format((float)0,2,'.','');
			$OCTsssld = number_format((float)0,2,'.','');
			$OCTpild = number_format((float)0,2,'.','');
			$OCTdeductions = number_format((float)0,2,'.','');
			$OCTnetpay = number_format((float)0,2,'.','');
		}
	//OCT PAYEND

	//NOV PAYS
	$novpayqry = "SELECT * FROM PAY_PER_PERIOD WHERE emp_id = '$empid' AND pperiod_month = 'November' AND pperiod_year = '$yearreport'";
	$novpayexecqry = mysqli_query($conn,$novpayqry) or die ("FAILED NOV PAY ".mysqli_error($conn));
	while($NOVpayarray = mysqli_fetch_array($novpayexecqry)):;//NOVpaywhile
		$nov = $nov+1;
		//NOV PAYS
		$NOVbpay = number_format((float) $NOVbpay + $NOVpayarray['reg_pay'],2,'.','');
		$NOVotpay = number_format((float) $NOVotpay + $NOVpayarray['ot_pay'],2,'.','');
		$NOVrhpay = number_format((float) $NOVrhpay + $NOVpayarray['hday_pay'],2,'.','');
		$NOVrhpay200 = number_format((float) $NOVrhpay200 + $NOVpayarray['hday200_pay'],2,'.','');
		$NOVotrhpay = number_format((float) $NOVotrhpay + $NOVpayarray['otrh_pay'],2,'.','');
		$NOVshpay = number_format((float) $NOVshpay  + $NOVpayarray['shday_pay'],2,'.','');
		$NOVotshpay = number_format((float) $NOVotshpay + $NOVpayarray['otsh_pay'],2,'.','');
		$NOVrdpay = number_format((float) $NOVrdpay + $NOVpayarray['rd_pay'],2,'.','');
		$NOVotrdpay = number_format((float) $NOVotrdpay + $NOVpayarray['otrd_pay'],2,'.','');
		$NOVrdrhpay = number_format((float) $NOVrdrhpay+ $NOVpayarray['rdrh_pay'],2,'.','');
		$NOVotrdrhpay = number_format((float) $NOVotrdrhpay + $NOVpayarray['otrdrh_pay'],2,'.','');
		$NOVrdshpay = number_format((float) $NOVrdshpay + $NOVpayarray['rdsh_pay'],2,'.','');
		$NOVotrdshpay = number_format((float) $NOVotrdshpay + $NOVpayarray['otrdsh_pay'],2,'.','');
		$NOVlvpay = number_format((float) $NOVlvpay + $NOVpayarray['lv_pay'],2,'.','');
		
		$NOVearnings = ($NOVbpay + $NOVotpay + $NOVrhpay + $NOVrhpay200 + $NOVotrhpay + $NOVshpay + $NOVotshpay + $NOVrdpay + $NOVotrdpay + $NOVrdrhpay + $NOVotrdrhpay + $NOVrdshpay + $NOVotrdshpay + $NOVlvpay);
		$NOVearnings = number_format((float) $NOVearnings,2,'.','');
		//NOV DEDUCTS
		$NOVphd = number_format((float) $NOVphd + $NOVpayarray['philhealth_deduct'],2,'.','');
		$NOVsssd = number_format((float) $NOVsssd + $NOVpayarray['sss_deduct'],2,'.','');
		$NOVpid = number_format((float) $NOVpid + $NOVpayarray['pagibig_deduct'],2,'.','');
		$NOVtd = number_format((float) $NOVtd + $NOVpayarray['tax_deduct'],2,'.','');
		$NOVsssld = number_format((float) $NOVsssld + $NOVpayarray['sssloan_deduct'],2,'.','');
		$NOVpild = number_format((float) $NOVpild + $NOVpayarray['pagibigloan_deduct'],2,'.','');
	
		$NOVdeductions = ($NOVphd + $NOVsssd + $NOVpid + $NOVtd + $NOVsssld + $NOVpild);
		$NOVdeductions = number_format((float) $NOVdeductions,2,'.','');
		//NOV NET PAYS
		$NOVnetpay = $NOVearnings - $NOVdeductions;
		$NOVnetpay = number_format((float) $NOVnetpay,2,'.','');

	endwhile;//OCTpayendwhile
		if ($nov == 0 ){
			$NOVbpay = number_format((float)0,2,'.','');
			$NOVotpay = number_format((float)0,2,'.','');
			$NOVrhpay = number_format((float)0,2,'.','');
			$NOVrhpay200 = number_format((float)0,2,'.','');
			$NOVotrhpay = number_format((float)0,2,'.','');
			$NOVshpay = number_format((float)0,2,'.','');
			$NOVotshpay = number_format((float)0,2,'.','');
			$NOVrdpay = number_format((float)0,2,'.','');
			$NOVotrdpay = number_format((float)0,2,'.','');
			$NOVrdrhpay = number_format((float)0,2,'.','');
			$NOVotrdrhpay = number_format((float)0,2,'.','');
			$NOVrdshpay = number_format((float)0,2,'.','');
			$NOVotrdshpay = number_format((float)0,2,'.','');
			$NOVlvpay = number_format((float)0,2,'.','');
			$NOVearnings = number_format((float)0,2,'.','');
			$NOVphd = number_format((float)0,2,'.','');
			$NOVsssd = number_format((float)0,2,'.','');
			$NOVpid = number_format((float)0,2,'.','');
			$NOVtd = number_format((float)0,2,'.','');
			$NOVsssld = number_format((float)0,2,'.','');
			$NOVpild = number_format((float)0,2,'.','');
			$NOVdeductions = number_format((float)0,2,'.','');
			$NOVnetpay = number_format((float)0,2,'.','');
		}
	//NOV PAYEND

	//DEC PAYS
	$decpayqry = "SELECT * FROM PAY_PER_PERIOD WHERE emp_id = '$empid' AND pperiod_month = 'December' AND pperiod_year = '$yearreport'";
	$decpayexecqry = mysqli_query($conn,$decpayqry) or die ("FAILED NOV PAY ".mysqli_error($conn));
	while($DECpayarray = mysqli_fetch_array($decpayexecqry)):;//DECpaywhile
		$dec = $dec+1;
		//DEC PAYS
		$DECbpay = number_format((float) $DECbpay + $DECpayarray['reg_pay'],2,'.','');
		$DECotpay = number_format((float) $DECotpay + $DECpayarray['ot_pay'],2,'.','');
		$DECrhpay = number_format((float) $DECrhpay + $DECpayarray['hday_pay'],2,'.','');
		$DECrhpay200 = number_format((float) $DECrhpay200 + $DECpayarray['hday200_pay'],2,'.','');
		$DECotrhpay = number_format((float) $DECotrhpay + $DECpayarray['otrh_pay'],2,'.','');
		$DECshpay = number_format((float) $DECshpay  + $DECpayarray['shday_pay'],2,'.','');
		$DECotshpay = number_format((float) $DECotshpay + $DECpayarray['otsh_pay'],2,'.','');
		$DECrdpay = number_format((float) $DECrdpay + $DECpayarray['rd_pay'],2,'.','');
		$DECotrdpay = number_format((float) $DECotrdpay + $DECpayarray['otrd_pay'],2,'.','');
		$DECrdrhpay = number_format((float) $DECrdrhpay+ $DECpayarray['rdrh_pay'],2,'.','');
		$DECotrdrhpay = number_format((float) $DECotrdrhpay + $DECpayarray['otrdrh_pay'],2,'.','');
		$DECrdshpay = number_format((float) $DECrdshpay + $DECpayarray['rdsh_pay'],2,'.','');
		$DECotrdshpay = number_format((float) $DECotrdshpay + $DECpayarray['otrdsh_pay'],2,'.','');
		$DEClvpay = number_format((float) $DEClvpay + $DECpayarray['lv_pay'],2,'.','');
		
		$DECearnings = ($DECbpay + $DECotpay + $DECrhpay + $DECrhpay200 + $DECotrhpay + $DECshpay + $DECotshpay + $DECrdpay + $DECotrdpay + $DECrdrhpay + $DECotrdrhpay + $DECrdshpay + $DECotrdshpay + $DEClvpay);
		$DECearnings = number_format((float) $DECearnings,2,'.','');
		//DEC DEDUCTS
		$DECphd = number_format((float) $DECphd + $DECpayarray['philhealth_deduct'],2,'.','');
		$DECsssd = number_format((float) $DECsssd + $DECpayarray['sss_deduct'],2,'.','');
		$DECpid = number_format((float) $DECpid + $DECpayarray['pagibig_deduct'],2,'.','');
		$DECtd = number_format((float) $DECtd + $DECpayarray['tax_deduct'],2,'.','');
		$DECsssld = number_format((float) $DECsssld + $DECpayarray['sssloan_deduct'],2,'.','');
		$DECpild = number_format((float) $DECpild + $DECpayarray['pagibigloan_deduct'],2,'.','');
	
		$DECdeductions = ($DECphd + $DECsssd + $DECpid + $DECtd + $DECsssld + $DECpild);
		$DECdeductions = number_format((float) $DECdeductions,2,'.','');
		//DEC NET PAYS
		$DECnetpay = $DECearnings - $DECdeductions;
		$DECnetpay = number_format((float) $DECnetpay,2,'.','');

	endwhile;//DECpayendwhile
		if ($dec == 0 ){
			$DECbpay = number_format((float)0,2,'.','');
			$DECotpay = number_format((float)0,2,'.','');
			$DECrhpay = number_format((float)0,2,'.','');
			$DECrhpay200 = number_format((float)0,2,'.','');
			$DECotrhpay = number_format((float)0,2,'.','');
			$DECshpay = number_format((float)0,2,'.','');
			$DECotshpay = number_format((float)0,2,'.','');
			$DECrdpay = number_format((float)0,2,'.','');
			$DECotrdpay = number_format((float)0,2,'.','');
			$DECrdrhpay = number_format((float)0,2,'.','');
			$DECotrdrhpay = number_format((float)0,2,'.','');
			$DECrdshpay = number_format((float)0,2,'.','');
			$DECotrdshpay = number_format((float)0,2,'.','');
			$DEClvpay = number_format((float)0,2,'.','');
			$DECearnings = number_format((float)0,2,'.','');
			$DECphd = number_format((float)0,2,'.','');
			$DECsssd = number_format((float)0,2,'.','');
			$DECpid = number_format((float)0,2,'.','');
			$DECtd = number_format((float)0,2,'.','');
			$DECsssld = number_format((float)0,2,'.','');
			$DECpild = number_format((float)0,2,'.','');
			$DECdeductions = number_format((float)0,2,'.','');
			$DECnetpay = number_format((float)0,2,'.','');
		}
	//DEC PAYEND
	//TOTALS
	$TOTALbpay =  $FEBbpay + $MARbpay + $APRbpay + $MAYbpay + $JUNbpay + $JULbpay + $AUGbpay + $SEPbpay + $OCTbpay + $NOVbpay + $DECbpay;
	$TOTALotpay =  $FEBotpay + $MARotpay + $APRotpay + $MAYotpay + $JUNotpay + $JULotpay + $AUGotpay + $SEPotpay + $OCTotpay + $NOVotpay + $DECotpay;
	$TOTALrhpay =  $FEBrhpay + $MARrhpay + $APRrhpay + $MAYrhpay + $JUNrhpay + $JULrhpay + $AUGrhpay + $SEPrhpay + $OCTrhpay + $NOVrhpay + $DECrhpay;
	$TOTALrhpay200 =  $FEBrhpay200 + $MARrhpay200 + $APRrhpay200 + $MAYrhpay200 + $JUNrhpay200 + $JULrhpay200 + $AUGrhpay200 + $SEPrhpay200 + $OCTrhpay200 + $NOVrhpay200 + $DECrhpay200;
	$TOTALotrhpay = $FEBotrhpay + $MARotrhpay + $APRotrhpay + $MAYotrhpay + $JUNotrhpay + $JULotrhpay + $AUGotrhpay + $SEPotrhpay + $OCTotrhpay + $NOVotrhpay + $DECotrhpay;
	$TOTALshpay =  $FEBshpay + $MARshpay + $APRshpay + $MAYshpay + $JUNshpay + $JULshpay + $AUGshpay + $SEPshpay + $OCTshpay + $NOVshpay + $DECshpay;
	$TOTALotshpay =  $FEBotshpay + $MARotshpay + $APRotshpay + $MAYotshpay + $JUNotshpay + $JULotshpay + $AUGotshpay + $SEPotshpay + $OCTotshpay + $NOVotshpay + $DECotshpay;
	$TOTALrdpay =  $FEBrdpay + $MARrdpay + $APRrdpay + $MAYrdpay + $JUNrdpay + $JULrdpay + $AUGrdpay + $SEPrdpay + $OCTrdpay + $NOVrdpay + $DECrdpay;
	$TOTALotrdpay =  $FEBotrdpay + $MARotrdpay + $APRotrdpay + $MAYotrdpay + $JUNotrdpay + $JULotrdpay + $AUGotrdpay + $SEPotrdpay + $OCTotrdpay + $NOVotrdpay + $DECotrdpay;
	$TOTALrdrhpay = $FEBrdrhpay + $MARrdrhpay + $APRrdrhpay + $MAYrdrhpay + $JUNrdrhpay + $JULrdrhpay + $AUGrdrhpay + $SEPrdrhpay + $OCTrdrhpay + $NOVrdrhpay + $DECrdrhpay;
	$TOTALotrdrhpay =  $FEBotrdrhpay + $MARotrdrhpay + $APRotrdrhpay + $MAYotrdrhpay + $JUNotrdrhpay + $JULotrdrhpay + $AUGotrdrhpay + $SEPotrdrhpay + $OCTotrdrhpay + $NOVotrdrhpay + $DECotrdrhpay;
	$TOTALrdshpay =  $FEBrdshpay + $MARrdshpay + $APRrdshpay + $MAYrdshpay + $JUNrdshpay + $JULrdshpay + $AUGrdshpay + $SEPrdshpay + $OCTrdshpay + $NOVrdshpay + $DECrdshpay;
	$TOTALotrdshpay = + $FEBotrdshpay + $MARotrdshpay + $APRotrdshpay + $MAYotrdshpay + $JUNotrdshpay + $JULotrdshpay + $AUGotrdshpay + $SEPotrdshpay + $OCTotrdshpay + $NOVotrdshpay + $DECotrdshpay;
	$TOTALlvpay =  $FEBlvpay + $MARlvpay + $APRlvpay + $MAYlvpay + $JUNlvpay + $JULlvpay + $AUGlvpay + $SEPlvpay + $OCTlvpay + $NOVlvpay + $DEClvpay;

	$TOTALphd =  $FEBphd + $MARphd + $APRphd + $MAYphd + $JUNphd + $JULphd + $AUGphd + $SEPphd + $OCTphd + $NOVphd + $DECphd;
	$TOTALsssd =  $FEBsssd + $MARsssd + $APRsssd + $MAYsssd + $JUNsssd + $JULsssd + $AUGsssd + $SEPsssd + $OCTsssd + $NOVsssd + $DECsssd;
	$TOTALpid =  $FEBpid + $MARpid + $APRpid + $MAYpid + $JUNpid + $JULpid + $AUGpid + $SEPpid + $OCTpid + $NOVpid + $DECpid;
	$TOTALtd =  $FEBtd + $MARtd + $APRtd + $MAYtd + $JUNtd + $JULtd + $AUGtd + $SEPtd + $OCTtd + $NOVtd + $DECtd;
	$TOTALsssld =  $FEBsssld + $MARsssld + $APRsssld + $MAYsssld + $JUNsssld + $JULsssld + $AUGsssld + $SEPsssld + $OCTsssld + $NOVsssld + $DECsssld;
	$TOTALpild =  $FEBpild + $MARpild + $APRpild + $MAYpild + $JUNpild + $JULpild + $AUGpild + $SEPpild + $OCTpild + $NOVpild + $DECpild;
	$TOTALearnings =  $FEBearnings + $MARearnings + $APRearnings + $MAYearnings + $JUNearnings + $JULearnings + $AUGearnings + $SEPearnings + $OCTearnings + $NOVearnings + $DECearnings;
	$TOTALdeductions =  $FEBdeductions + $MARdeductions + $APRdeductions + $MAYdeductions + $JUNdeductions + $JULdeductions + $AUGdeductions + $SEPdeductions + $OCTdeductions + $NOVdeductions + $DECdeductions;
	$TOTALnetpay =  $FEBnetpay + $MARnetpay + $APRnetpay + $MAYnetpay + $JUNnetpay + $JULnetpay + $AUGnetpay + $SEPnetpay + $OCTnetpay + $NOVnetpay + $DECnetpay;

	$TOTALbpay = number_format((float) $TOTALbpay,2,'.','');
	$TOTALotpay = number_format((float) $TOTALotpay,2,'.','');
	$TOTALrhpay = number_format((float) $TOTALrhpay,2,'.','');
	$TOTALrhpay200 = number_format((float) $TOTALrhpay200,2,'.','');
	$TOTALotrhpay = number_format((float) $TOTALotrhpay,2,'.','');
	$TOTALshpay = number_format((float) $TOTALshpay,2,'.','');
	$TOTALotshpay = number_format((float) $TOTALotshpay,2,'.','');
	$TOTALrdpay = number_format((float) $TOTALrdpay,2,'.','');
	$TOTALotrdpay = number_format((float) $TOTALotrdpay,2,'.','');
	$TOTALrdrhpay = number_format((float) $TOTALrdrhpay,2,'.','');
	$TOTALotrdrhpay = number_format((float) $TOTALotrdrhpay,2,'.','');
	$TOTALrdshpay = number_format((float) $TOTALrdshpay,2,'.','');
	$TOTALotrdshpay = number_format((float) $TOTALotrdshpay,2,'.','');
	$TOTALlvpay = number_format((float) $TOTALlvpay,2,'.','');
	$TOTALphd = number_format((float) $TOTALphd,2,'.','');
	$TOTALsssd = number_format((float) $TOTALsssd,2,'.','');
	$TOTALpid = number_format((float) $TOTALpid,2,'.','');
	$TOTALtd = number_format((float) $TOTALtd,2,'.','');
	$TOTALsssld = number_format((float) $TOTALsssld,2,'.','');
	$TOTALpild = number_format((float) $TOTALpild,2,'.','');
	$TOTALearnings = number_format((float) $TOTALearnings,2,'.','');
	$TOTALdeductions = number_format((float) $TOTALdeductions,2,'.','');
	$TOTALnetpay = number_format((float) $TOTALnetpay,2,'.','');

endwhile;//emparrwhile
	


require_once("../fpdf181/fpdf.php");

$pdf = new FPDF ('L','mm','LEGAL');
$pdf ->AddPage();
//set font arial, bold, 14pt
$pdf->SetFont('Arial','B',12);
//WRitable horizontal : 336

$pdf->Cell(336,3,'',0,1);//end of line


$pdf->Cell(336,4,'WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS',0,1,'C');//end of line
$pdf->SetFont('Arial','','8');
$pdf->Cell(336,4,'Cavite',0,1,'C');//end of line
$pdf->Cell(336,4,'Cavite',0,1,'C');//end of line

$pdf->Cell(336,5,'',0,1);//end of line

$pdf->SetFont('Arial','B','10');
$pdf->Cell(336,4,$title,0,1,'C');//end of line

$pdf->SetFont('Arial','','10');
//$pdf->Cell(336,4,$payperiod,0,1,'C');//end of line

$pdf->Cell(336,0.2,'',1,1);//end of line
$pdf->SetFont('Arial','B','9');
$pdf->Cell(39,4,'DESCRIPTION',0,0,'C');
$pdf->Cell(22.84,5,'January',0,0,'C');
$pdf->Cell(22.84,5,'February',0,0,'C');
$pdf->Cell(22.84,5,'March',0,0,'C');
$pdf->Cell(22.84,5,'April',0,0,'C');
$pdf->Cell(22.84,5,'May',0,0,'C');
$pdf->Cell(22.84,5,'June',0,0,'C');
$pdf->Cell(22.84,5,'July',0,0,'C');
$pdf->Cell(22.84,5,'August',0,0,'C');
$pdf->Cell(22.84,5,'September',0,0,'C');
$pdf->Cell(22.84,5,'October',0,0,'C');
$pdf->Cell(22.84,5,'November',0,0,'C');
$pdf->Cell(22.84,5,'December',0,0,'C');
$pdf->Cell(22.84,5,'TOTAL',0,1,'C');

$pdf->Cell(336,0.2,'',1,1);//end of 

$pdf->Cell(336,2,'',0,1);//end of line

//DESCRIPTION
$pdf->SetFont('Arial','','8');
$pdf->Cell(39,5,'Basic',0,0,'');
$pdf->Cell(22.84,5,$JANbpay,0,0,'R');//Jan
$pdf->Cell(22.84,5,$FEBbpay,0,0,'R');//Feb
$pdf->Cell(22.84,5,$MARbpay,0,0,'R');//Mar
$pdf->Cell(22.84,5,$APRbpay,0,0,'R');//Apr
$pdf->Cell(22.84,5,$MAYbpay,0,0,'R');//May
$pdf->Cell(22.84,5,$JUNbpay,0,0,'R');//Jun
$pdf->Cell(22.84,5,$JULbpay,0,0,'R');//Jul
$pdf->Cell(22.84,5,$AUGbpay,0,0,'R');//Aug
$pdf->Cell(22.84,5,$SEPbpay,0,0,'R');//Sept
$pdf->Cell(22.84,5,$OCTbpay,0,0,'R');//Oct
$pdf->Cell(22.84,5,$NOVbpay,0,0,'R');//Nov
$pdf->Cell(22.84,5,$DECbpay,0,0,'R');//Dec
$pdf->Cell(22.84,5,$TOTALbpay,0,1,'R');//Total
//end

//DESCRIPTION

$pdf->Cell(39,5,'Regular OT',0,0,'');
$pdf->SetFont('Arial','','8');
$pdf->Cell(22.84,5,$JANotpay,0,0,'R');//Jan
$pdf->Cell(22.84,5,$FEBotpay,0,0,'R');//Feb
$pdf->Cell(22.84,5,$MARotpay,0,0,'R');//Mar
$pdf->Cell(22.84,5,$APRotpay,0,0,'R');//Apr
$pdf->Cell(22.84,5,$MAYotpay,0,0,'R');//May
$pdf->Cell(22.84,5,$JUNotpay,0,0,'R');//Jun
$pdf->Cell(22.84,5,$JULotpay,0,0,'R');//Jul
$pdf->Cell(22.84,5,$AUGotpay,0,0,'R');//Aug
$pdf->Cell(22.84,5,$SEPotpay,0,0,'R');//Sept
$pdf->Cell(22.84,5,$OCTotpay,0,0,'R');//Oct
$pdf->Cell(22.84,5,$NOVotpay,0,0,'R');//Nov
$pdf->Cell(22.84,5,$DECotpay,0,0,'R');//Dec
$pdf->Cell(22.84,5,$TOTALotpay,0,1,'R');//Total
//end

//DESCRIPTION

$pdf->Cell(39,5,'Regular Holiday',0,0,'');
$pdf->SetFont('Arial','','8');
$pdf->Cell(22.84,5,$JANrhpay,0,0,'R');//Jan
$pdf->Cell(22.84,5,$FEBrhpay,0,0,'R');//Feb
$pdf->Cell(22.84,5,$MARrhpay,0,0,'R');//Mar
$pdf->Cell(22.84,5,$APRrhpay,0,0,'R');//Apr
$pdf->Cell(22.84,5,$MAYrhpay,0,0,'R');//May
$pdf->Cell(22.84,5,$JUNrhpay,0,0,'R');//Jun
$pdf->Cell(22.84,5,$JULrhpay,0,0,'R');//Jul
$pdf->Cell(22.84,5,$AUGrhpay,0,0,'R');//Aug
$pdf->Cell(22.84,5,$SEPrhpay,0,0,'R');//Sept
$pdf->Cell(22.84,5,$OCTrhpay,0,0,'R');//Oct
$pdf->Cell(22.84,5,$NOVrhpay,0,0,'R');//Nov
$pdf->Cell(22.84,5,$DECrhpay,0,0,'R');//Dec
$pdf->Cell(22.84,5,$TOTALrhpay,0,1,'R');//Total
//end

//DESCRIPTION

$pdf->Cell(39,5,'Special Holiday',0,0,'');
$pdf->SetFont('Arial','','8');
$pdf->Cell(22.84,5,$JANshpay,0,0,'R');//Jan
$pdf->Cell(22.84,5,$FEBshpay,0,0,'R');//Feb
$pdf->Cell(22.84,5,$MARshpay,0,0,'R');//Mar
$pdf->Cell(22.84,5,$APRshpay,0,0,'R');//Apr
$pdf->Cell(22.84,5,$MAYshpay,0,0,'R');//May
$pdf->Cell(22.84,5,$JUNshpay,0,0,'R');//Jun
$pdf->Cell(22.84,5,$JULshpay,0,0,'R');//Jul
$pdf->Cell(22.84,5,$AUGshpay,0,0,'R');//Aug
$pdf->Cell(22.84,5,$SEPshpay,0,0,'R');//Sept
$pdf->Cell(22.84,5,$OCTshpay,0,0,'R');//Oct
$pdf->Cell(22.84,5,$NOVshpay,0,0,'R');//Nov
$pdf->Cell(22.84,5,$DECshpay,0,0,'R');//Dec
$pdf->Cell(22.84,5,$TOTALshpay,0,1,'R');//Total
//end

//DESCRIPTION

$pdf->Cell(39,5,'RH OT',0,0,'');
$pdf->SetFont('Arial','','8');
$pdf->Cell(22.84,5,$JANotrhpay,0,0,'R');//Jan
$pdf->Cell(22.84,5,$FEBotrhpay,0,0,'R');//Feb
$pdf->Cell(22.84,5,$MARotrhpay,0,0,'R');//Mar
$pdf->Cell(22.84,5,$APRotrhpay,0,0,'R');//Apr
$pdf->Cell(22.84,5,$MAYotrhpay,0,0,'R');//May
$pdf->Cell(22.84,5,$JUNotrhpay,0,0,'R');//Jun
$pdf->Cell(22.84,5,$JULotrhpay,0,0,'R');//Jul
$pdf->Cell(22.84,5,$AUGotrhpay,0,0,'R');//Aug
$pdf->Cell(22.84,5,$SEPotrhpay,0,0,'R');//Sept
$pdf->Cell(22.84,5,$OCTotrhpay,0,0,'R');//Oct
$pdf->Cell(22.84,5,$NOVotrhpay,0,0,'R');//Nov
$pdf->Cell(22.84,5,$DECotrhpay,0,0,'R');//Dec
$pdf->Cell(22.84,5,$TOTALotrhpay,0,1,'R');//Total
//end

//DESCRIPTION
$pdf->Cell(39,5,'SH OT',0,0,'');
$pdf->SetFont('Arial','','8');
$pdf->Cell(22.84,5,$JANotshpay,0,0,'R');//Jan
$pdf->Cell(22.84,5,$FEBotshpay,0,0,'R');//Feb
$pdf->Cell(22.84,5,$MARotshpay,0,0,'R');//Mar
$pdf->Cell(22.84,5,$APRotshpay,0,0,'R');//Apr
$pdf->Cell(22.84,5,$MAYotshpay,0,0,'R');//May
$pdf->Cell(22.84,5,$JUNotshpay,0,0,'R');//Jun
$pdf->Cell(22.84,5,$JULotshpay,0,0,'R');//Jul
$pdf->Cell(22.84,5,$AUGotshpay,0,0,'R');//Aug
$pdf->Cell(22.84,5,$SEPotshpay,0,0,'R');//Sept
$pdf->Cell(22.84,5,$OCTotshpay,0,0,'R');//Oct
$pdf->Cell(22.84,5,$NOVotshpay,0,0,'R');//Nov
$pdf->Cell(22.84,5,$DECotshpay,0,0,'R');//Dec
$pdf->Cell(22.84,5,$TOTALotshpay,0,1,'R');//Total
//ens

// $pdf->Cell(39,5,'Special Holiday(SH)',0,0,'');
// $pdf->SetFont('Arial','','8');
// $pdf->Cell(22.84,5,$JANshpay,0,0,'R');//Jan
// $pdf->Cell(22.84,5,$FEBshpay,0,0,'R');//Feb
// $pdf->Cell(22.84,5,$MARshpay,0,0,'R');//Mar
// $pdf->Cell(22.84,5,$APRshpay,0,0,'R');//Apr
// $pdf->Cell(22.84,5,$MAYshpay,0,0,'R');//May
// $pdf->Cell(22.84,5,$JUNshpay,0,0,'R');//Jun
// $pdf->Cell(22.84,5,$JULshpay,0,0,'R');//Jul
// $pdf->Cell(22.84,5,$AUGshpay,0,0,'R');//Aug
// $pdf->Cell(22.84,5,$SEPshpay,0,0,'R');//Sept
// $pdf->Cell(22.84,5,$OCTshpay,0,0,'R');//Oct
// $pdf->Cell(22.84,5,$NOVshpay,0,0,'R');//Nov
// $pdf->Cell(22.84,5,$DECshpay,0,0,'R');//Dec
// $pdf->Cell(22.84,5,$TOTALshpay,0,1,'R');//Total
// //end

//DESCRIPTION



//DESCRIPTION

// $pdf->Cell(39,5,'Rest Day(RD)',0,0,'');
// $pdf->SetFont('Arial','','8');
// $pdf->Cell(22.84,5,$JANrdpay,0,0,'R');//Jan
// $pdf->Cell(22.84,5,$FEBrdpay,0,0,'R');//Feb
// $pdf->Cell(22.84,5,$MARrdpay,0,0,'R');//Mar
// $pdf->Cell(22.84,5,$APRrdpay,0,0,'R');//Apr
// $pdf->Cell(22.84,5,$MAYrdpay,0,0,'R');//May
// $pdf->Cell(22.84,5,$JUNrdpay,0,0,'R');//Jun
// $pdf->Cell(22.84,5,$JULrdpay,0,0,'R');//Jul
// $pdf->Cell(22.84,5,$AUGrdpay,0,0,'R');//Aug
// $pdf->Cell(22.84,5,$SEPrdpay,0,0,'R');//Sept
// $pdf->Cell(22.84,5,$OCTrdpay,0,0,'R');//Oct
// $pdf->Cell(22.84,5,$NOVrdpay,0,0,'R');//Nov
// $pdf->Cell(22.84,5,$DECrdpay,0,0,'R');//Dec
// $pdf->Cell(22.84,5,$TOTALrdpay,0,1,'R');//Total
// //end

//DESCRIPTION

// $pdf->Cell(39,5,'RD OT',0,0,'');
// $pdf->SetFont('Arial','','8');
// $pdf->Cell(22.84,5,$JANotrdpay,0,0,'R');//Jan
// $pdf->Cell(22.84,5,$FEBotrdpay,0,0,'R');//Feb
// $pdf->Cell(22.84,5,$MARotrdpay,0,0,'R');//Mar
// $pdf->Cell(22.84,5,$APRotrdpay,0,0,'R');//Apr
// $pdf->Cell(22.84,5,$MAYotrdpay,0,0,'R');//May
// $pdf->Cell(22.84,5,$JUNotrdpay,0,0,'R');//Jun
// $pdf->Cell(22.84,5,$JULotrdpay,0,0,'R');//Jul
// $pdf->Cell(22.84,5,$AUGotrdpay,0,0,'R');//Aug
// $pdf->Cell(22.84,5,$SEPotrdpay,0,0,'R');//Sept
// $pdf->Cell(22.84,5,$OCTotrdpay,0,0,'R');//Oct
// $pdf->Cell(22.84,5,$NOVotrdpay,0,0,'R');//Nov
// $pdf->Cell(22.84,5,$DECotrdpay,0,0,'R');//Dec
// $pdf->Cell(22.84,5,$TOTALotrdpay,0,1,'R');//Total
// //end

// //DESCRIPTION

// $pdf->Cell(39,5,'RD/LH',0,0,'');
// $pdf->SetFont('Arial','','8');
// $pdf->Cell(22.84,5,$JANrdrhpay,0,0,'R');//Jan
// $pdf->Cell(22.84,5,$FEBrdrhpay,0,0,'R');//Feb
// $pdf->Cell(22.84,5,$MARrdrhpay,0,0,'R');//Mar
// $pdf->Cell(22.84,5,$APRrdrhpay,0,0,'R');//Apr
// $pdf->Cell(22.84,5,$MAYrdrhpay,0,0,'R');//May
// $pdf->Cell(22.84,5,$JUNrdrhpay,0,0,'R');//Jun
// $pdf->Cell(22.84,5,$JULrdrhpay,0,0,'R');//Jul
// $pdf->Cell(22.84,5,$AUGrdrhpay,0,0,'R');//Aug
// $pdf->Cell(22.84,5,$SEPrdrhpay,0,0,'R');//Sept
// $pdf->Cell(22.84,5,$OCTrdrhpay,0,0,'R');//Oct
// $pdf->Cell(22.84,5,$NOVrdrhpay,0,0,'R');//Nov
// $pdf->Cell(22.84,5,$DECrdrhpay,0,0,'R');//Dec
// $pdf->Cell(22.84,5,$TOTALrdrhpay,0,1,'R');//Total
// //end

//DESCRIPTION

// $pdf->Cell(39,5,'RD/LH OT',0,0,'');
// $pdf->SetFont('Arial','','8');
// $pdf->Cell(22.84,5,$JANotrdrhpay,0,0,'R');//Jan
// $pdf->Cell(22.84,5,$FEBotrdrhpay,0,0,'R');//Feb
// $pdf->Cell(22.84,5,$MARotrdrhpay,0,0,'R');//Mar
// $pdf->Cell(22.84,5,$APRotrdrhpay,0,0,'R');//Apr
// $pdf->Cell(22.84,5,$MAYotrdrhpay,0,0,'R');//May
// $pdf->Cell(22.84,5,$JUNotrdrhpay,0,0,'R');//Jun
// $pdf->Cell(22.84,5,$JULotrdrhpay,0,0,'R');//Jul
// $pdf->Cell(22.84,5,$AUGotrdrhpay,0,0,'R');//Aug
// $pdf->Cell(22.84,5,$SEPotrdrhpay,0,0,'R');//Sept
// $pdf->Cell(22.84,5,$OCTotrdrhpay,0,0,'R');//Oct
// $pdf->Cell(22.84,5,$NOVotrdrhpay,0,0,'R');//Nov
// $pdf->Cell(22.84,5,$DECotrdrhpay,0,0,'R');//Dec
// $pdf->Cell(22.84,5,$TOTALotrdrhpay,0,1,'R');//Total
// //end

//DESCRIPTION

// $pdf->Cell(39,5,'RD/SH',0,0,'');
// $pdf->SetFont('Arial','','8');
// $pdf->Cell(22.84,5,$JANrdshpay,0,0,'R');//Jan
// $pdf->Cell(22.84,5,$FEBrdshpay,0,0,'R');//Feb
// $pdf->Cell(22.84,5,$MARrdshpay,0,0,'R');//Mar
// $pdf->Cell(22.84,5,$APRrdshpay,0,0,'R');//Apr
// $pdf->Cell(22.84,5,$MAYrdshpay,0,0,'R');//May
// $pdf->Cell(22.84,5,$JUNrdshpay,0,0,'R');//Jun
// $pdf->Cell(22.84,5,$JULrdshpay,0,0,'R');//Jul
// $pdf->Cell(22.84,5,$AUGrdshpay,0,0,'R');//Aug
// $pdf->Cell(22.84,5,$SEPrdshpay,0,0,'R');//Sept
// $pdf->Cell(22.84,5,$OCTrdshpay,0,0,'R');//Oct
// $pdf->Cell(22.84,5,$NOVrdshpay,0,0,'R');//Nov
// $pdf->Cell(22.84,5,$DECrdshpay,0,0,'R');//Dec
// $pdf->Cell(22.84,5,$TOTALrdshpay,0,1,'R');//Total
// //end

// //DESCRIPTION

// $pdf->Cell(39,5,'RD/SH OT',0,0,'');
// $pdf->SetFont('Arial','','8');
// $pdf->Cell(22.84,5,$JANotrdshpay,0,0,'R');//Jan
// $pdf->Cell(22.84,5,$FEBotrdshpay,0,0,'R');//Feb
// $pdf->Cell(22.84,5,$MARotrdshpay,0,0,'R');//Mar
// $pdf->Cell(22.84,5,$APRotrdshpay,0,0,'R');//Apr
// $pdf->Cell(22.84,5,$MAYotrdshpay,0,0,'R');//May
// $pdf->Cell(22.84,5,$JUNotrdshpay,0,0,'R');//Jun
// $pdf->Cell(22.84,5,$JULotrdshpay,0,0,'R');//Jul
// $pdf->Cell(22.84,5,$AUGotrdshpay,0,0,'R');//Aug
// $pdf->Cell(22.84,5,$SEPotrdshpay,0,0,'R');//Sept
// $pdf->Cell(22.84,5,$OCTotrdshpay,0,0,'R');//Oct
// $pdf->Cell(22.84,5,$NOVotrdshpay,0,0,'R');//Nov
// $pdf->Cell(22.84,5,$DECotrdshpay,0,0,'R');//Dec
// $pdf->Cell(22.84,5,$TOTALotrdshpay,0,1,'R');//Total
// //end

//DESCRIPTION

$pdf->Cell(39,5,'Leaves',0,0,'');
$pdf->SetFont('Arial','','8');
$pdf->Cell(22.84,5,$JANlvpay,0,0,'R');//Jan
$pdf->Cell(22.84,5,$FEBlvpay,0,0,'R');//Feb
$pdf->Cell(22.84,5,$MARlvpay,0,0,'R');//Mar
$pdf->Cell(22.84,5,$APRlvpay,0,0,'R');//Apr
$pdf->Cell(22.84,5,$MAYlvpay,0,0,'R');//May
$pdf->Cell(22.84,5,$JUNlvpay,0,0,'R');//Jun
$pdf->Cell(22.84,5,$JULlvpay,0,0,'R');//Jul
$pdf->Cell(22.84,5,$AUGlvpay,0,0,'R');//Aug
$pdf->Cell(22.84,5,$SEPlvpay,0,0,'R');//Sept
$pdf->Cell(22.84,5,$OCTlvpay,0,0,'R');//Oct
$pdf->Cell(22.84,5,$NOVlvpay,0,0,'R');//Nov
$pdf->Cell(22.84,5,$DEClvpay,0,0,'R');//Dec
$pdf->Cell(22.84,5,$TOTALlvpay,0,1,'R');//Total
//end
$pdf->Cell(336,1,'',0,1); //spacer
//DESCRIPTION
$pdf->SetFont('Arial','B','9');
$pdf->Cell(39,5,'EARNINGS',0,0,'');
$pdf->Cell(22.84,5,$JANearnings,0,0,'R');//Jan
$pdf->Cell(22.84,5,$FEBearnings,0,0,'R');//Feb
$pdf->Cell(22.84,5,$MARearnings,0,0,'R');//Mar
$pdf->Cell(22.84,5,$APRearnings,0,0,'R');//Apr
$pdf->Cell(22.84,5,$MAYearnings,0,0,'R');//May
$pdf->Cell(22.84,5,$JUNearnings,0,0,'R');//Jun
$pdf->Cell(22.84,5,$JULearnings,0,0,'R');//Jul
$pdf->Cell(22.84,5,$AUGearnings,0,0,'R');//Aug
$pdf->Cell(22.84,5,$SEPearnings,0,0,'R');//Sept
$pdf->Cell(22.84,5,$OCTearnings,0,0,'R');//Oct
$pdf->Cell(22.84,5,$NOVearnings,0,0,'R');//Nov
$pdf->Cell(22.84,5,$DECearnings,0,0,'R');//Dec
$pdf->Cell(22.84,5,$TOTALearnings,0,1,'R');//Total
//end


$pdf->Cell(336,3,'',0,1); //spacer

//DESCRIPTION
$pdf->SetFont('Arial','','8');
$pdf->Cell(39,5,'Philhealth',0,0,'');
$pdf->Cell(22.84,5,$JANphd,0,0,'R');//Jan
$pdf->Cell(22.84,5,$FEBphd,0,0,'R');//Feb
$pdf->Cell(22.84,5,$MARphd,0,0,'R');//Mar
$pdf->Cell(22.84,5,$APRphd,0,0,'R');//Apr
$pdf->Cell(22.84,5,$MAYphd,0,0,'R');//May
$pdf->Cell(22.84,5,$JUNphd,0,0,'R');//Jun
$pdf->Cell(22.84,5,$JULphd,0,0,'R');//Jul
$pdf->Cell(22.84,5,$AUGphd,0,0,'R');//Aug
$pdf->Cell(22.84,5,$SEPphd,0,0,'R');//Sept
$pdf->Cell(22.84,5,$OCTphd,0,0,'R');//Oct
$pdf->Cell(22.84,5,$NOVphd,0,0,'R');//Nov
$pdf->Cell(22.84,5,$DECphd,0,0,'R');//Dec
$pdf->Cell(22.84,5,$TOTALphd,0,1,'R');//Total
//end

//DESCRIPTION
$pdf->Cell(39,5,'GSIS',0,0,'');
$pdf->Cell(22.84,5,$JANsssd,0,0,'R');//Jan
$pdf->Cell(22.84,5,$FEBsssd,0,0,'R');//Feb
$pdf->Cell(22.84,5,$MARsssd,0,0,'R');//Mar
$pdf->Cell(22.84,5,$APRsssd,0,0,'R');//Apr
$pdf->Cell(22.84,5,$MAYsssd,0,0,'R');//May
$pdf->Cell(22.84,5,$JUNsssd,0,0,'R');//Jun
$pdf->Cell(22.84,5,$JULsssd,0,0,'R');//Jul
$pdf->Cell(22.84,5,$AUGsssd,0,0,'R');//Aug
$pdf->Cell(22.84,5,$SEPsssd,0,0,'R');//Sept
$pdf->Cell(22.84,5,$OCTsssd,0,0,'R');//Oct
$pdf->Cell(22.84,5,$NOVsssd,0,0,'R');//Nov
$pdf->Cell(22.84,5,$DECsssd,0,0,'R');//Dec
$pdf->Cell(22.84,5,$TOTALsssd,0,1,'R');//Total
//end

//DESCRIPTION
$pdf->Cell(39,5,'PAG-IBIG',0,0,'');
$pdf->Cell(22.84,5,$JANpid,0,0,'R');//Jan
$pdf->Cell(22.84,5,$FEBpid,0,0,'R');//Feb
$pdf->Cell(22.84,5,$MARpid,0,0,'R');//Mar
$pdf->Cell(22.84,5,$APRpid,0,0,'R');//Apr
$pdf->Cell(22.84,5,$MAYpid,0,0,'R');//May
$pdf->Cell(22.84,5,$JUNpid,0,0,'R');//Jun
$pdf->Cell(22.84,5,$JULpid,0,0,'R');//Jul
$pdf->Cell(22.84,5,$AUGpid,0,0,'R');//Aug
$pdf->Cell(22.84,5,$SEPpid,0,0,'R');//Sept
$pdf->Cell(22.84,5,$OCTpid,0,0,'R');//Oct
$pdf->Cell(22.84,5,$NOVpid,0,0,'R');//Nov
$pdf->Cell(22.84,5,$DECpid,0,0,'R');//Dec
$pdf->Cell(22.84,5,$TOTALpid,0,1,'R');//Total
//end

// //DESCRIPTION
// $pdf->Cell(39,5,'Withholding Tax',0,0,'');
// $pdf->Cell(22.84,5,$JANtd,0,0,'R');//Jan
// $pdf->Cell(22.84,5,$FEBtd,0,0,'R');//Feb
// $pdf->Cell(22.84,5,$MARtd,0,0,'R');//Mar
// $pdf->Cell(22.84,5,$APRtd,0,0,'R');//Apr
// $pdf->Cell(22.84,5,$MAYtd,0,0,'R');//May
// $pdf->Cell(22.84,5,$JUNtd,0,0,'R');//Jun
// $pdf->Cell(22.84,5,$JULtd,0,0,'R');//Jul
// $pdf->Cell(22.84,5,$AUGtd,0,0,'R');//Aug
// $pdf->Cell(22.84,5,$SEPtd,0,0,'R');//Sept
// $pdf->Cell(22.84,5,$OCTtd,0,0,'R');//Oct
// $pdf->Cell(22.84,5,$NOVtd,0,0,'R');//Nov
// $pdf->Cell(22.84,5,$DECtd,0,0,'R');//Dec
// $pdf->Cell(22.84,5,$TOTALtd,0,1,'R');//Total
// //end

//DESCRIPTION
$pdf->Cell(39,5,'GSIS Loan',0,0,'');
$pdf->Cell(22.84,5,$JANsssld,0,0,'R');//Jan
$pdf->Cell(22.84,5,$FEBsssld,0,0,'R');//Feb
$pdf->Cell(22.84,5,$MARsssld,0,0,'R');//Mar
$pdf->Cell(22.84,5,$APRsssld,0,0,'R');//Apr
$pdf->Cell(22.84,5,$MAYsssld,0,0,'R');//May
$pdf->Cell(22.84,5,$JUNsssld,0,0,'R');//Jun
$pdf->Cell(22.84,5,$JULsssld,0,0,'R');//Jul
$pdf->Cell(22.84,5,$AUGsssld,0,0,'R');//Aug
$pdf->Cell(22.84,5,$SEPsssld,0,0,'R');//Sept
$pdf->Cell(22.84,5,$OCTsssld,0,0,'R');//Oct
$pdf->Cell(22.84,5,$NOVsssld,0,0,'R');//Nov
$pdf->Cell(22.84,5,$DECsssld,0,0,'R');//Dec
$pdf->Cell(22.84,5,$TOTALsssld,0,1,'R');//Total
//en

//DESCRIPTION
$pdf->Cell(39,5,'PAG-IBIG Loan',0,0,'');
$pdf->Cell(22.84,5,$JANpild,0,0,'R');//Jan
$pdf->Cell(22.84,5,$FEBpild,0,0,'R');//Feb
$pdf->Cell(22.84,5,$MARpild,0,0,'R');//Mar
$pdf->Cell(22.84,5,$APRpild,0,0,'R');//Apr
$pdf->Cell(22.84,5,$MAYpild,0,0,'R');//May
$pdf->Cell(22.84,5,$JUNpild,0,0,'R');//Jun
$pdf->Cell(22.84,5,$JULpild,0,0,'R');//Jul
$pdf->Cell(22.84,5,$AUGpild,0,0,'R');//Aug
$pdf->Cell(22.84,5,$SEPpild,0,0,'R');//Sept
$pdf->Cell(22.84,5,$OCTpild,0,0,'R');//Oct
$pdf->Cell(22.84,5,$NOVpild,0,0,'R');//Nov
$pdf->Cell(22.84,5,$DECpild,0,0,'R');//Dec
$pdf->Cell(22.84,5,$TOTALpild,0,1,'R');//Total
//end
$pdf->Cell(336,1,'',0,1); //spacer

 //DESCRIPTION
$pdf->SetFont('Arial','B','9');
$pdf->Cell(39,5,'DEDUCTIONS',0,0,'');
$pdf->Cell(22.84,5,$JANdeductions,0,0,'R');//Jan
$pdf->Cell(22.84,5,$FEBdeductions,0,0,'R');//Feb
$pdf->Cell(22.84,5,$MARdeductions,0,0,'R');//Mar
$pdf->Cell(22.84,5,$APRdeductions,0,0,'R');//Apr
$pdf->Cell(22.84,5,$MAYdeductions,0,0,'R');//May
$pdf->Cell(22.84,5,$JUNdeductions,0,0,'R');//Jun
$pdf->Cell(22.84,5,$JULdeductions,0,0,'R');//Jul
$pdf->Cell(22.84,5,$AUGdeductions,0,0,'R');//Aug
$pdf->Cell(22.84,5,$SEPdeductions,0,0,'R');//Sept
$pdf->Cell(22.84,5,$OCTdeductions,0,0,'R');//Oct
$pdf->Cell(22.84,5,$NOVdeductions,0,0,'R');//Nov
$pdf->Cell(22.84,5,$DECdeductions,0,0,'R');//Dec
$pdf->Cell(22.84,5,$TOTALdeductions,0,1,'R');//Total
//end


$pdf->Cell(336,3,'',0,1); //spacer

//DESCRIPTION
$pdf->SetFont('Arial','B','9');
$pdf->Cell(39,5,'NET PAY TOTALS',0,0,'');
$pdf->Cell(22.84,5,$JANnetpay,0,0,'R');//Jan
$pdf->Cell(22.84,5,$FEBnetpay,0,0,'R');//Feb
$pdf->Cell(22.84,5,$MARnetpay,0,0,'R');//Mar
$pdf->Cell(22.84,5,$APRnetpay,0,0,'R');//Apr
$pdf->Cell(22.84,5,$MAYnetpay,0,0,'R');//May
$pdf->Cell(22.84,5,$JUNnetpay,0,0,'R');//Jun
$pdf->Cell(22.84,5,$JULnetpay,0,0,'R');//Jul
$pdf->Cell(22.84,5,$AUGnetpay,0,0,'R');//Aug
$pdf->Cell(22.84,5,$SEPnetpay,0,0,'R');//Sept
$pdf->Cell(22.84,5,$OCTnetpay,0,0,'R');//Oct
$pdf->Cell(22.84,5,$NOVnetpay,0,0,'R');//Nov
$pdf->Cell(22.84,5,$DECnetpay,0,0,'R');//Dec
$pdf->Cell(22.84,5,$TOTALnetpay,0,1,'R');//Total
//end


$pdf->Cell(336,3,'',0,1); //spacer
$pdf->Cell(336,0.2,'',1,1);//end of line
$pdf->Cell(336, 10, 'Printed by ' . $adminId, 0, 1, 'R');

$pdf->Output();

?>