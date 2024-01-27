<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");


session_start();
$adminId = $_SESSION['adminId'];
$flag=0;
$payperiodfrom = $_SESSION['payperiodfrom'];
$payperiodto = $_SESSION['payperiodto'];
$payperiodrange = $_SESSION['payperiodrange'];

$enddateinit = strtotime($payperiodto);
$enddateconv = date("d", $enddateinit);

$month = date("F",$enddateinit);
$year = date("Y",$enddateinit);

$generatedQuery =   $_SESSION['processallpayroll'];

$getEmployeeIDsQuery = mysqli_query($conn, $generatedQuery) or die("FAILED TO GET EMPLOYEE IDs " . mysqli_error($conn));

// Initialize an array to store employee IDs
$employeeIDs = array();

while ($row = mysqli_fetch_assoc($getEmployeeIDsQuery)) {
    $employeeIDs[] = $row['emp_id'];
}

foreach($employeeIDs as $payID){
    /**GET EMPLOYEE INFORMATION**/

$getempinfoquery = "SELECT * FROM employees WHERE emp_id = '$payID'";
$getempinfoexecquery = mysqli_query($conn,$getempinfoquery) or die ("FAILED TO GET PAY INFO ".mysqli_error($conn));
$getempinfoarray = mysqli_fetch_array($getempinfoexecquery);

if ($getempinfoarray){
	$prefix = $getempinfoarray['prefix_ID'];
	$idno = $getempinfoarray['emp_id'];
	$lname = $getempinfoarray['last_name'];
	$fname = $getempinfoarray['first_name'];
	$mname = $getempinfoarray['middle_name'];
	$dept = $getempinfoarray['dept_NAME'];
	$relstatus = $getempinfoarray['rel_status'];
	$numberofchildren = $getempinfoarray['num_children'];
	$emptype = $getempinfoarray['employment_TYPE'];

  $name = "$lname, $fname $mname";
  $empID = "$prefix$idno";

}

// Set $currentDate to $payperiodfrom
$currentDate = date('Y-m-d', strtotime($payperiodfrom));

// Extract the day of the month
$dayOfMonth = date('d', strtotime($currentDate));

// Check the day of the month and perform actions accordingly
if ($dayOfMonth >= 1 && $dayOfMonth <= 15) {
    
	//echo "It's within the 16-30 pay period range.";
	if ($emptype == 'Permanent'){
		// echo "Permanent.";
		$timekeepinfoquery = "SELECT SUM(hours_work) as hourswork, SUM(rh_hours) as totalRHhours, SUM(sh_hours) as totalSHhours, SUM(lv_hours) as totalLV, SUM(undertime_hours) as undertimehours, SUM(late_hours) as late FROM TIME_KEEPING WHERE emp_id = '$payID' AND timekeep_day BETWEEN '$payperiodfrom' and '$payperiodto' ORDER BY timekeep_day ASC";
		$timekeepinfoexecquery = mysqli_query($conn,$timekeepinfoquery) or die ("FAILED TO GET TIMEKEEPINFO ". mysqli_error($conn));
		$timekeepinfoarray = mysqli_fetch_array($timekeepinfoexecquery);

		if ($timekeepinfoarray){

			
						$hw = $timekeepinfoarray['hourswork'];
						$sh = $timekeepinfoarray['totalSHhours'];
						$rh = $timekeepinfoarray['totalRHhours'];
						$lv = $timekeepinfoarray['totalLV'];
						$undertimehours = $timekeepinfoarray['undertimehours'];
						$late = $timekeepinfoarray['late'];
						$totalhoursworked = $hw - $sh - $rh - $lv;
						// echo $totalhoursworked;
						// echo $sh, $rh, $lv;
					}

					/** GET HOURLY RATE INFORMATION **/

					$payinfoquery = "SELECT * FROM PAYROLLINFO WHERE emp_id = '$payID'";
					$payinfoexecquery = mysqli_query($conn,$payinfoquery) or die ("FAILED TO GET PAY INFO ".mysqli_error($conn));
					$payinfoarray = mysqli_fetch_array($payinfoexecquery);

					if($payinfoarray){

						$rph = $payinfoarray['hourly_rate'];
						$philhealth = $payinfoarray['ph_EE'];
						$pagibig = $payinfoarray['pagibig_EE'];

					}
					$searchquery = "SELECT * FROM PAY_PER_PERIOD WHERE emp_id = '$payID' AND pperiod_range = '$payperiodrange'";
					$searchexecquery = mysqli_query($conn,$searchquery) or die ("FAILED TO SEARCH ".mysqli_error($conn));
					$searchrows = mysqli_num_rows($searchexecquery);
					$searcharray = mysqli_fetch_array($searchexecquery);

					if ($searchrows > 1){

						?>
						
						<script>
						document.addEventListener('DOMContentLoaded', function() {
							swal({
							 //  title: "Good job!",
							  text: "Payroll this pay period has already been processed.",
							  icon: "success",
							  button: "OK",
							 }).then(function() {
								// window.location.href = 'adminMasterfile.php'; // Replace 'your_new_page.php' with the actual URL
								window.history.back();

							});
						});
					 </script>
					 <?php
						// header("Location: adminPAYROLLProcess.php");
						

					} else {

						/** GROSS PAY COMPUTATIONS **/
						$basepay = $totalhoursworked * $rph;
						$SHpay = $sh * ($rph*1.3);
						$RHpay = $rh * $rph;
						$LVpay = $lv * $rph;

						//deductions
						$totaldeduct = $philhealth + $pagibig;
					

					$savepayrollquery = "INSERT INTO PAY_PER_PERIOD (emp_id,pperiod_range,pperiod_month,pperiod_year,rate_per_hour,reg_pay,hday_pay,shday_pay,philhealth_deduct,pagibig_deduct,total_deduct,lv_pay, undertimehours, late) VALUES 
																	('$payID','$payperiodrange','$month','$year','$rph','$basepay','$RHpay','$SHpay','$philhealth','$pagibig','$totaldeduct','$LVpay', $undertimehours, late)";
					$savepayrollexecquery = mysqli_query($conn,$savepayrollquery) or die ("FAILED TO INSERT PAYROLL INFO ".mysqli_error($conn));
					
					

					$activityLog = "Payroll Computed for $payID ($payperiodrange)";
					$adminActivityQuery = "INSERT INTO adminactivity_log (emp_id, activity,log_timestamp) VALUES ('$adminId', '$activityLog', NOW())";
					$adminActivityResult = mysqli_query($conn, $adminActivityQuery);


					$notificationMessage = "Payroll Computed for $payID ($payperiodrange)";
					$insertNotificationQuery = "INSERT INTO empnotifications (admin_id,emp_id, message, type, status) VALUES ('$adminId', '$payID','$notificationMessage','Payroll','unread')";
					mysqli_query($conn, $insertNotificationQuery);

					if ($savepayrollexecquery){
						?>
						
						<script>
						document.addEventListener('DOMContentLoaded', function() {
							swal({
							 //  title: "Good job!",
							  text: "Payroll Processed",
							  icon: "success",
							  button: "OK",
							 }).then(function() {
								// window.location.href = 'adminMasterfile.php'; // Replace 'your_new_page.php' with the actual URL
								window.history.back();

							});
						});
					 </script>
					 <?php
						
					}else{
						echo "<script>alert('hatdof');</script>";
					}
				
				}

			} else if ($emptype == 'Contractual'){
				// echo "Hatdog.";
				
								$timekeepinfoquery = "SELECT SUM(hours_work) as hourswork, SUM(rh_hours) as totalRHhours, SUM(sh_hours) as totalSHhours, SUM(undertime_hours) as undertimehours, SUM(late_hours) as late FROM TIME_KEEPING WHERE emp_id = '$payID' AND timekeep_day BETWEEN '$payperiodfrom' and '$payperiodto' ORDER BY timekeep_day ASC";
								$timekeepinfoexecquery = mysqli_query($conn,$timekeepinfoquery) or die ("FAILED TO GET TIMEKEEPINFO ". mysqli_error($conn));
								$timekeepinfoarray = mysqli_fetch_array($timekeepinfoexecquery);

								if ($timekeepinfoarray){

									
									$hw = $timekeepinfoarray['hourswork'];
									$sh = $timekeepinfoarray['totalSHhours'];
									$rh = $timekeepinfoarray['totalRHhours'];
									$lv = $timekeepinfoarray['totalLV'];
									$undertimehours = $timekeepinfoarray['undertimehours'];
									$totalhoursworked = $hw - $sh - $rh - $lv;
									// echo $totalhoursworked;
									// echo $sh, $rh, $lv;
								}

								/** GET HOURLY RATE INFORMATION **/

								$payinfoquery = "SELECT * FROM PAYROLLINFO WHERE emp_id = '$payID'";
								$payinfoexecquery = mysqli_query($conn,$payinfoquery) or die ("FAILED TO GET PAY INFO ".mysqli_error($conn));
								$payinfoarray = mysqli_fetch_array($payinfoexecquery);

								if($payinfoarray){

									$rph = $payinfoarray['hourly_rate'];

								}
								$searchquery = "SELECT * FROM PAY_PER_PERIOD WHERE emp_id = '$payID' AND pperiod_range = '$payperiodrange'";
								$searchexecquery = mysqli_query($conn,$searchquery) or die ("FAILED TO SEARCH ".mysqli_error($conn));
								$searchrows = mysqli_num_rows($searchexecquery);
								$searcharray = mysqli_fetch_array($searchexecquery);

								if ($searchrows > 1){

									
									?>
						
											<script>
											document.addEventListener('DOMContentLoaded', function() {
												swal({
												//  title: "Good job!",
												text: "Payroll this pay period has already been processed.",
												icon: "success",
												button: "OK",
												}).then(function() {
													// window.location.href = 'adminMasterfile.php'; // Replace 'your_new_page.php' with the actual URL
													window.history.back();

												});
											});
										</script>
										<?php
									// header("Location: adminPAYROLLProcess.php");
									

								} else {

									/** GROSS PAY COMPUTATIONS **/
									$basepay = $totalhoursworked * $rph;
									$SHpay = $sh * ($rph*1.3);
									$RHpay = $rh * $rph;

								

								$savepayrollquery = "INSERT INTO PAY_PER_PERIOD (emp_id,pperiod_range,pperiod_month,pperiod_year,rate_per_hour,reg_pay,hday_pay,shday_pay, lv_pay, undertimehours, late) VALUES 
																				('$payID','$payperiodrange','$month','$year','$rph','$basepay','$RHpay','$SHpay','$LVpay', '$undertimehours', '$late')";
								$savepayrollexecquery = mysqli_query($conn,$savepayrollquery) or die ("FAILED TO INSERT PAYROLL INFO ".mysqli_error($conn));

								$activityLog = "Payroll Computed for $payID ($payperiodrange)";
								$adminActivityQuery = "INSERT INTO adminactivity_log (emp_id, activity,log_timestamp) VALUES ('$adminId', '$activityLog', NOW())";
								$adminActivityResult = mysqli_query($conn, $adminActivityQuery);


								$notificationMessage = "Payroll Computed for $payID ($payperiodrange)";
								$insertNotificationQuery = "INSERT INTO empnotifications (admin_id,emp_id, message, type, status) VALUES ('$adminId', '$payID','$notificationMessage','Payroll','unread')";
								mysqli_query($conn, $insertNotificationQuery);

								if ($savepayrollexecquery){
									 ?>
						
									<script>
									document.addEventListener('DOMContentLoaded', function() {
										swal({
										//  title: "Good job!",
										text: "Payroll Processed",
										icon: "success",
										button: "OK",
										}).then(function() {
											// window.location.href = 'adminMasterfile.php'; // Replace 'your_new_page.php' with the actual URL
											window.history.back();

										});
									});
								</script>
								<?php
												
								}else{
									echo "<script>alert('hatdof');</script>";
								}
							
							}
				
			}
} elseif ($dayOfMonth >= 16 && $dayOfMonth <= 30) {
    // Perform actions for 16-30 pay period
    // echo "It's within the 16-30 pay period range.";
	if ($emptype == 'Permanent'){
		// echo "Permanent.";
		

		/** GET HOURLY RATE INFORMATION **/

		$payinfoquery = "SELECT * FROM PAYROLLINFO WHERE emp_id = '$payID'";
		$payinfoexecquery = mysqli_query($conn,$payinfoquery) or die ("FAILED TO GET PAY INFO ".mysqli_error($conn));
		$payinfoarray = mysqli_fetch_array($payinfoexecquery);

		if($payinfoarray){

			$rph = $payinfoarray['hourly_rate'];
			$gsis= $payinfoarray['gsisEE'];

		}
		$timekeepinfoquery = "SELECT SUM(hours_work) as hourswork, SUM(rh_hours) as totalRHhours, SUM(sh_hours) as totalSHhours, SUM(lv_hours) as totalLV, SUM(undertime_hours) as undertimehours, SUM(late_hours) as late FROM TIME_KEEPING WHERE emp_id = '$payID' AND timekeep_day BETWEEN '$payperiodfrom' and '$payperiodto' ORDER BY timekeep_day ASC";
		$timekeepinfoexecquery = mysqli_query($conn,$timekeepinfoquery) or die ("FAILED TO GET TIMEKEEPINFO ". mysqli_error($conn));
		$timekeepinfoarray = mysqli_fetch_array($timekeepinfoexecquery);

		if ($timekeepinfoarray){

			
			$hw = $timekeepinfoarray['hourswork'];
			$sh = $timekeepinfoarray['totalSHhours'];
			$rh = $timekeepinfoarray['totalRHhours'];
			$lv = $timekeepinfoarray['totalLV'];
			$totalhoursworked = $hw - $sh - $rh - $lv;
			$undertimehours = $timekeepinfoarray['undertimehours'];
			$late = $timekeepinfoarray['late'];
			

		}

		$otinfoquery = "SELECT SUM(overtime_hours) as ot, SUM(ot_rh) as otrh, SUM(ot_sh) as ot_sh FROM TIME_KEEPING WHERE emp_id = '$payID' AND DATE_FORMAT(timekeep_day, '%M') = '$month' ORDER BY timekeep_day ASC";
		$otinfoexecquery = mysqli_query($conn,$otinfoquery) or die ("FAILED TO GET TIMEKEEPINFO ". mysqli_error($conn));
		$otinfoarray = mysqli_fetch_array($otinfoexecquery);

		if ($otinfoarray){

			
			$ot = $otinfoarray['ot'];
			$otrh = $otinfoarray['otrh'];
			$otsh = $otinfoarray['ot_sh'];

			$ot = $ot * ($rph*1.25);
			$otsh = $otsh * ($rph*1.30);
			$otrh = $otrh * $rph;
			

		}
		/** PAG-IBIG LOAN DEDUCTIONS **/
		$pagibigloanquery = "SELECT emp_id,monthly_deduct,no_of_pays, loan_balance, start_date, end_date FROM LOANpagibig WHERE emp_id = '$payID' AND MONTH(curdate()) BETWEEN MONTH(start_date) AND MONTH(end_date)";
		$pagibigloanexecqry = mysqli_query($conn,$pagibigloanquery) or die ("FAILED TO CHECK PAG-IBIG LOANS");
			$pagibigloanarray = mysqli_fetch_array($pagibigloanexecqry);

			if ($pagibigloanarray){

				$pagibigmonthlyloan = $pagibigloanarray['monthly_deduct'];
				$pagibignoofpays = $pagibigloanarray['no_of_pays'];
				$pagibigbalance = $pagibigloanarray['loan_balance'];

				$pagibigbalance = $pagibigbalance - $pagibigmonthlyloan;

				if($pagibignoofpays>0){
					
					$pagibigremain = ($pagibignoofpays - 1);
					$pagibigloanupdatequery = "SELECT emp_id,monthly_deduct,no_of_pays, loan_balance, start_date, end_date FROM LOANpagibig WHERE emp_id = '$payID' AND MONTH(curdate()) BETWEEN MONTH(start_date) AND MONTH(end_date)";
					$pagibigloanupdateexecqry = mysqli_query($conn,$pagibigloanupdateqry) or die ("FAILED TO CHECK PAGIBIG LOANS ".mysqli_error($conn));
					if ($pagibigremain == 0){
						$updatepagibigloan = "UPDATE LOANpagibig SET status = 'Paid' WHERE emp_id = '$payID' AND MONTH(curdate()) BETWEEN MONTH(start_date) AND MONTH(end_date)";
						$updatepagibigloanexecqry = mysqli_query($conn,$updatepagibigloan) or die ("FAILED TO CHECK PAG-IBIG LOANS");
						}
					} else {
	
					$updatepagibigloan = "UPDATE LOANpagibig SET status = 'Paid' WHERE emp_id = '$payID' AND MONTH(curdate()) BETWEEN MONTH(start_date) AND MONTH(end_date)";
					$updatepagibigloanexecqry = mysqli_query($conn,$updatepagibigloan) or die ("FAILED TO CHECK PAG-IBIG LOANS1");
					$pagibigmonthlyloan = 0.00;
				}
			} else {

				$pagibigmonthlyloan = 0.00;
			}
		/** PAG-IBIG LOAN DEDUCTIONS **/
		/** GSIS LOAN DEDUCTIONS **/
			$gsisloanquery = "SELECT emp_id,monthly_deduct,no_of_pays, loan_balance, loanstart_date, loanend_date FROM LOANgsis WHERE emp_id = '$payID' AND MONTH(curdate()) BETWEEN MONTH(loanstart_date) AND MONTH(loanend_date)";
			$gsisloanexecqry = mysqli_query($conn,$gsisloanquery) or die ("FAILED TO CHECK gsis LOANS ".mysqli_error($conn));
			$gsisloanarray = mysqli_fetch_array($gsisloanexecqry);

			if ($gsisloanarray){

				$$gsismonthlyloan = $gsisloanarray['monthly_deduct'];
				$gsisnoofpays = $gsisloanarray['no_of_pays'];
				$gsisbalance = $gsisloanarray['loan_balance'];

				$gsisbalance = $gsisbalance - $gsismonthlyloan;


				if ($gsisnoofpays>0){

					$gsisremain = ($gsisnoofpays - 1);
					$gsisloanupdateqry = "UPDATE LOANgsis SET no_of_pays = '$gsisremain', loan_balance = '$gsisbalance' WHERE emp_id = '$payID' AND MONTH(curdate()) BETWEEN MONTH(loanstart_date) AND MONTH(loanend_date)";
					$gsisloanupdateexecqry = mysqli_query($conn,$gsisloanupdateqry) or die ("FAILED TO UPDATE gsis LOAN RECORD ".mysqli_error($conn)); 
					if ($gsisremain == 0){
					$updategsisloan = "UPDATE LOANgsis SET status = 'Paid'  WHERE emp_id = '$payID' AND MONTH(curdate()) BETWEEN MONTH(loanstart_date) AND MONTH(loanend_date)";
					$updategsisloanexecqry = mysqli_query($conn,$updategsisloan) or die ("FAILED TO CHECK PAG-IBIG LOANS");
					}
				} else {

					$updategsisloan = "UPDATE LOANgsis SET status = 'Paid'  WHERE emp_id = '$payID' AND MONTH(curdate()) BETWEEN MONTH(loanstart_date) AND MONTH(loanend_date)";
					$updategsisloanexecqry = mysqli_query($conn,$updategsisloan) or die ("FAILED TO CHECK PAG-IBIG LOANS");
					$gsismonthlyloan = 0.00;
				}

				

			} else {
				$gsismonthlyloan = 0.00;
			}
		/** GSIS LOAN DEDUCTIONS **/

			




		
		$searchquery = "SELECT * FROM PAY_PER_PERIOD WHERE emp_id = '$payID' AND pperiod_range = '$payperiodrange'";
		$searchexecquery = mysqli_query($conn,$searchquery) or die ("FAILED TO SEARCH ".mysqli_error($conn));
		$searchrows = mysqli_num_rows($searchexecquery);
		$searcharray = mysqli_fetch_array($searchexecquery);

		if ($searchrows == 1){

			?>
						
						<script>
						document.addEventListener('DOMContentLoaded', function() {
							swal({
							 //  title: "Good job!",
							  text: "Payroll this pay period has already been processed.",
							  icon: "success",
							  button: "OK",
							 }).then(function() {
								// window.location.href = 'adminMasterfile.php'; // Replace 'your_new_page.php' with the actual URL
								window.history.back();

							});
						});
					 </script>
					 <?php
			// $_SESSION['payrollprocess'] = "Payroll for $empID for this pay period has already been processed.";
			// header("Location: adminPAYROLLProcess.php");
			

		} else {

			/** GROSS PAY COMPUTATIONS **/
			$basepay = $totalhoursworked * $rph;
			$SHpay = $sh * ($rph*1.3);
			$RHpay = $rh * $rph;
			$LVpay = $lv * $rph;


			/** TOTAL DEDUCTIONS COMPUTATION **/

			$totaldeduct = ($gsis + $gsismonthlyloan + $pagibigmonthlyloan);

			/** TOTAL DEDUCTIONS COMPUTATION **/
			$savepayrollquery = "INSERT INTO PAY_PER_PERIOD (emp_id,pperiod_range,pperiod_month,pperiod_year,rate_per_hour,reg_pay,hday_pay,shday_pay, sss_deduct, total_deduct,lv_pay, undertimehours, late) VALUES 
														('$payID','$payperiodrange','$month','$year','$rph','$basepay','$RHpay','$SHpay','$gsis','$totaldeduct','$LVpay', '$undertimehours','$late')";
			$savepayrollexecquery = mysqli_query($conn,$savepayrollquery) or die ("FAILED TO INSERT PAYROLL INFO ".mysqli_error($conn));
			
			$activityLog = "Payroll Computed for $payID ($payperiodrange)";
					$adminActivityQuery = "INSERT INTO adminactivity_log (emp_id, activity,log_timestamp) VALUES ('$adminId', '$activityLog', NOW())";
					$adminActivityResult = mysqli_query($conn, $adminActivityQuery);


					$notificationMessage = "Payroll Computed for $payID ($payperiodrange)";
					$insertNotificationQuery = "INSERT INTO empnotifications (admin_id,emp_id, message, type, status) VALUES ('$adminId', '$payID','$notificationMessage','Payroll','unread')";
					mysqli_query($conn, $insertNotificationQuery);

			if ($savepayrollexecquery){
				?>
						
									<script>
									document.addEventListener('DOMContentLoaded', function() {
										swal({
										//  title: "Good job!",
										text: "Payroll Processed",
										icon: "success",
										button: "OK",
										}).then(function() {
											// window.location.href = 'adminMasterfile.php'; // Replace 'your_new_page.php' with the actual URL
											window.history.back();

										});
									});
								</script>
								<?php
				
			}else{
				echo "<script>alert('hatdof');</script>";
			}
		}

	
			} else if ($emptype == 'Contractual'){
				// echo "Hatdog.";
				/** GET HOURLY RATE INFORMATION **/

		$payinfoquery = "SELECT * FROM PAYROLLINFO WHERE emp_id = '$payID'";
		$payinfoexecquery = mysqli_query($conn,$payinfoquery) or die ("FAILED TO GET PAY INFO ".mysqli_error($conn));
		$payinfoarray = mysqli_fetch_array($payinfoexecquery);

		if($payinfoarray){

			$rph = $payinfoarray['hourly_rate'];
			$gsis= $payinfoarray['gsisEE'];

		}
		$timekeepinfoquery = "SELECT SUM(hours_work) as hourswork, SUM(rh_hours) as totalRHhours, SUM(sh_hours) as totalSHhours, SUM(lv_hours) as totalLV, SUM(undertime_hours) as undertimehours, SUM(late_hours) as late FROM TIME_KEEPING WHERE emp_id = '$payID' AND timekeep_day BETWEEN '$payperiodfrom' and '$payperiodto' ORDER BY timekeep_day ASC";
		$timekeepinfoexecquery = mysqli_query($conn,$timekeepinfoquery) or die ("FAILED TO GET TIMEKEEPINFO ". mysqli_error($conn));
		$timekeepinfoarray = mysqli_fetch_array($timekeepinfoexecquery);

		if ($timekeepinfoarray){

			
			$hw = $timekeepinfoarray['hourswork'];
			$sh = $timekeepinfoarray['totalSHhours'];
			$rh = $timekeepinfoarray['totalRHhours'];
			$lv = $timekeepinfoarray['totalLV'];
			$totalhoursworked = $hw - $sh - $rh - $lv;
			$undertimehours = $timekeepinfoarray['undertimehours'];
			$late = $timekeepinfoarray['late'];
			

		}

		$otinfoquery = "SELECT SUM(overtime_hours) as ot, SUM(ot_rh) as otrh, SUM(ot_sh) as ot_sh FROM TIME_KEEPING WHERE emp_id = '$payID' AND DATE_FORMAT(timekeep_day, '%M') = '$month' ORDER BY timekeep_day ASC";
		$otinfoexecquery = mysqli_query($conn,$otinfoquery) or die ("FAILED TO GET TIMEKEEPINFO ". mysqli_error($conn));
		$otinfoarray = mysqli_fetch_array($otinfoexecquery);

		if ($otinfoarray){

			
			$ot = $otinfoarray['ot'];
			$otrh = $otinfoarray['otrh'];
			$otsh = $otinfoarray['ot_sh'];

			$ot = $ot * ($rph*1.25);
			$otsh = $otsh * ($rph*1.30);
			$otrh = $otrh * $rph;
			

		}
		// /** PAG-IBIG LOAN DEDUCTIONS **/
		// 	$pagibigloanquery = "SELECT emp_id,monthly_deduct,no_of_pays FROM LOANpagibig WHERE emp_id = '$payID'";
		// 	$pagibigloanexecqry = mysqli_query($conn,$pagibigloanquery) or die ("FAILED TO CHECK PAG-IBIG LOANS");
		// 	$pagibigloanarray = mysqli_fetch_array($pagibigloanexecqry);

		// 	if ($pagibigloanarray){

		// 		$pagibigmonthlyloan = $pagibigloanarray['monthly_deduct'];
		// 		$pagibignoofpays = $pagibigloanarray['no_of_pays'];

		// 		if($pagibignoofpays>0){
					
		// 			$pagibigremain = ($pagibignoofpays - 1);
		// 			$pagibigloanupdateqry = "UPDATE LOANpagibig SET no_of_pays = '$pagibigremain'";
		// 			$pagibigloanupdateexecqry = mysqli_query($conn,$pagibigloanupdateqry) or die ("FAILED TO CHECK PAGIBIG LOANS ".mysqli_error($conn));

		// 		} else {

		// 			$pagibigmonthlyloan = 0.00;
		// 		}
		// 	} else {

		// 		$pagibigmonthlyloan = 0.00;
		// 	}
		// /** PAG-IBIG LOAN DEDUCTIONS **/
		// /** GSIS LOAN DEDUCTIONS **/
		// 	$gsisloanquery = "SELECT emp_id,monthly_deduct,no_of_pays FROM LOANgsis WHERE emp_id = '$payID'";
		// 	$gsisloanexecqry = mysqli_query($conn,$gsisloanquery) or die ("FAILED TO CHECK gsis LOANS ".mysqli_error($conn));
		// 	$gsisloanarray = mysqli_fetch_array($gsisloanexecqry);

		// 	if ($gsisloanarray){

		// 		$gsismonthlyloan = $gsisloanarray['monthly_deduct'];
		// 		$gsisnoofpays = $gsisloanarray['no_of_pays'];

		// 		if ($gsisnoofpays>0){

		// 			$gsisremain = ($gsisnoofpays - 1);
		// 			$gsisloanupdateqry = "UPDATE LOANgsis SET no_of_pays = '$gsisremain'";
		// 			$gsisloanupdateexecqry = mysqli_query($conn,$gsisloanupdateqry) or die ("FAILED TO UPDATE gsis LOAN RECORD ".mysqli_error($conn)); 

		// 		} else {

		// 			$gsismonthlyloan = 0.00;
		// 		}

				

		// 	} else {
		// 		$gsismonthlyloan = 0.00;
		// 	}
		/** GSIS LOAN DEDUCTIONS **/

			




		
		$searchquery = "SELECT * FROM PAY_PER_PERIOD WHERE emp_id = '$payID' AND pperiod_range = '$payperiodrange'";
		$searchexecquery = mysqli_query($conn,$searchquery) or die ("FAILED TO SEARCH ".mysqli_error($conn));
		$searchrows = mysqli_num_rows($searchexecquery);
		$searcharray = mysqli_fetch_array($searchexecquery);

		if ($searchrows == 1){

			?>
						
									<script>
									document.addEventListener('DOMContentLoaded', function() {
										swal({
										//  title: "Good job!",
										text: "Payroll this pay period has already been processed.",
										icon: "success",
										button: "OK",
										}).then(function() {
											// window.location.href = 'adminMasterfile.php'; // Replace 'your_new_page.php' with the actual URL
											window.history.back();

										});
									});
								</script>
								<?php
			// $_SESSION['payrollprocess'] = "Payroll for $empID for this pay period has already been processed.";
			// header("Location: adminPAYROLLProcess.php");
			

		} else {

			/** GROSS PAY COMPUTATIONS **/
			$basepay = $totalhoursworked * $rph;
			$SHpay = $sh * ($rph*1.3);
			$RHpay = $rh * $rph;
			$LVpay = $lv * $rph;


			/** TOTAL DEDUCTIONS COMPUTATION **/

			// $totaldeduct = ($gsis + $gsismonthlyloan + $pagibigmonthlyloan);

			/** TOTAL DEDUCTIONS COMPUTATION **/
			$savepayrollquery = "INSERT INTO PAY_PER_PERIOD (emp_id,pperiod_range,pperiod_month,pperiod_year,rate_per_hour,reg_pay,hday_pay,shday_pay, lv_pay, undertimehours, late) VALUES 
														('$payID','$payperiodrange','$month','$year','$rph','$basepay','$RHpay','$SHpay, '$LVpay', '$undertimehours','$late')";
			$savepayrollexecquery = mysqli_query($conn,$savepayrollquery) or die ("FAILED TO INSERT PAYROLL INFO ".mysqli_error($conn));
			$activityLog = "Payroll Computed for $payID ($payperiodrange)";
					$adminActivityQuery = "INSERT INTO adminactivity_log (emp_id, activity,log_timestamp) VALUES ('$adminId', '$activityLog', NOW())";
					$adminActivityResult = mysqli_query($conn, $adminActivityQuery);


					$notificationMessage = "Payroll Computed for $payID ($payperiodrange)";
					$insertNotificationQuery = "INSERT INTO empnotifications (admin_id,emp_id, message, type, status) VALUES ('$adminId', '$payID','$notificationMessage','Payroll','unread')";
					mysqli_query($conn, $insertNotificationQuery);

			if ($savepayrollexecquery){
				?>
						
									<script>
									document.addEventListener('DOMContentLoaded', function() {
										swal({
										//  title: "Good job!",
										text: "Payroll Processed",
										icon: "success",
										button: "OK",
										}).then(function() {
											// window.location.href = 'adminMasterfile.php'; // Replace 'your_new_page.php' with the actual URL
											window.history.back();

										});
									});
								</script>
								<?php
				
			}else{
				echo "<script>alert('hatdof');</script>";
			}
		}
			}
} else {
    // Invalid day
    // echo "Invalid day.";
}







}


    // // if ($savepayrollexecquery) {
    //     // $_SESSION['payrollprocess'] = "Payroll for $empID is processed.";
    //     echo "<script>alert('$flag')</script>";
    //     echo "<script>setTimeout(function() { window.location.href = 'adminPAYROLLProcess.php'; }, 700);</script>";
    // // } else {
    //     // echo "<script>alert('Failed to process payroll for $payID.');</script>";
    // // }


?>