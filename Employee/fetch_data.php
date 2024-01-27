<?php
// Include your database connection logic here

include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");


$payperiod =$_SESSION['payperiods'];
// Example query to fetch data
if ($payperiod != 'noset'){
$searchquery = "SELECT * FROM employees, PAY_PER_PERIOD WHERE employees.emp_id = PAY_PER_PERIOD.emp_id AND PAY_PER_PERIOD.emp_id = '$empid' AND PAY_PER_PERIOD.pperiod_range = '$payperiod' ORDER BY pperiod_range";
$search_result = filterTable($searchquery);
$numRows = mysqli_num_rows($search_result);

if ($numRows > 0) {


        $data = array();
        while ($row = mysqli_fetch_assoc($search_result)) {
        $data[] = [
                'label' => 'Base Pay',
                'value' => $row['reg_pay'],
            ];
            $data[] = [
                'label' => 'Overtime Pay',
                'value' => $row['ot_pay'],
            ];
            $data[] = [
                'label' => 'Special Holiday Pay',
                'value' => $row['shday_pay'],
            ];
            $data[] = [
                'label' => 'Regular Holiday Pay',
                'value' => $row['hday_pay'],
            ];
            $data[] = [
                'label' => 'Philhealth',
                'value' => $row['philhealth_deduct'],
            ];
            $data[] = [
                'label' => 'GSIS',
                'value' => $row['sss_deduct'],
            ];
            $data[] = [
                'label' => 'Pag-Ibig',
                'value' => $row['pagibig_deduct'],
            ];
            $data[] = [
                'label' => 'GSIS Loan',
                'value' => $row['sssloan_deduct'],
            ];
            $data[] = [
                'label' => 'Pag-Ibig Loan',
                'value' => $row['pagibigloan_deduct'],
            ];
            }
}else {
    $defaultData = [
        ['label' => 'Default Label 1', 'value' => 100],
        ['label' => 'Default Label 2', 'value' => 100],


        // Add more default data as needed
    ];
    $data = $defaultData;
}
} else {
    $defaultData = [
        ['label' => 'Default Label 1', 'value' => 100],
        ['label' => 'Default Label 2', 'value' => 100],
        
    ];
    $data = $defaultData;
}

echo json_encode($data);


function filterTable($searchquery)
{

     $conn1 = mysqli_connect("localhost:3307","root","","masterdb");
     $filter_Result = mysqli_query($conn1,$searchquery) or die ("failed to query masterfile ".mysqli_error($conn1));
     return $filter_Result;
}
?>
