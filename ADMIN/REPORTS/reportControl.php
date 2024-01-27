<?php
session_start();
$_SESSION['reporttype'] = $_POST['selreportoption'];

$reporttype = $_SESSION['reporttype'];



if (isset($_POST['submit_btn'])){

	$_SESSION['fromreport'] = $_POST['fromreport'];
	$_SESSION['toreport'] = $_POST['toreport'];
	$month = $_POST['fromreport'];

$monthconv = strtotime($month);
$conv = date("F-Y", $monthconv);

$_SESSION['month'] = $conv;


	if ($reporttype == 'GSIS'){

    	header("Location: adminPrintSSSreport.php");

  	} elseif ($reporttype == 'Philhealth'){

    	header("Location: adminPrintPhilhealthreport.php");

	  }elseif ($reporttype == 'Pag-Ibig'){

    	header("Location: adminPrintPagibigreport.php");

  	}

 }elseif (isset($_POST['submitbyempid_btn'])){

 	$_SESSION['govrepempid'] = $_POST['empidno'];
 	$_SESSION['govrepyear'] = $_POST['yearoption'];

 	if ($reporttype == 'GSIS'){

    	header("Location: adminPrintSSSreportbyEmpid.php");

  	} elseif ($reporttype == 'Philhealth'){

    	header("Location: adminPrintPhilhealthreportbyEmpid.php");

	  }elseif ($reporttype == 'Withholding Tax'){

    	header("Location: adminPrintTaxReportbyEmpid.php");

  	}elseif ($reporttype == 'GSIS Loan'){

  		header("Location: adminPrintSSSLoanbyEmpid.php");

  	}elseif ($reporttype == 'PAG-IBIG Loan'){

  		header("Location: adminPrintPAGIBIGLoanbyEmpid.php");
  	}
 }
