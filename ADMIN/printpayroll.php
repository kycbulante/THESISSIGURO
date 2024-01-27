<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");
require_once("./fpdf181/fpdf.php");

// Function to fetch and display data as PDF
function printDataAsPDF($result, $adminId) {
    $pdf = new FPDF('L', 'mm', 'A4');
    $pdf->AddPage();

    // Header
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(20, 10, 'Employee ID', 1);
    $pdf->Cell(20, 10, 'Last Name', 1);
    $pdf->Cell(20, 10, 'First Name', 1);
    $pdf->Cell(20, 10, 'Middle Name', 1);
    $pdf->Cell(20, 10, 'Department', 1);
    $pdf->Cell(20, 10, 'Employment Type', 1);
    $pdf->Cell(20, 10, 'Shift', 1);
    $pdf->Cell(20, 10, 'Base Pay', 1);
    $pdf->Cell(20, 10, 'Daily Rate', 1);
    $pdf->Cell(20, 10, 'Hourly Rate', 1);
    $pdf->Cell(20, 10, 'SSS', 1);
    $pdf->Cell(20, 10, 'Philhealth', 1);
    $pdf->Cell(20, 10, 'PAG-IBIG/HDMF', 1);
    // Add more columns as needed

    // Data
    $pdf->SetFont('Arial', '', 10);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $pdf->Ln();
            $pdf->Cell(20, 10, $row['emp_id'], 1);
            $pdf->Cell(20, 10, $row['fingerprint_id'], 1);
            $pdf->Cell(20, 10, $row['last_name'], 1);
            $pdf->Cell(20, 10, $row['first_name'], 1);
            $pdf->Cell(20, 10, $row['middle_name'], 1);
            $pdf->Cell(20, 10, $row['dept_NAME'], 1);
            $pdf->Cell(20, 10, $row['shift_SCHEDULE'], 1);
            $pdf->Cell(20, 10, $row['base_pay'], 1);
            $pdf->Cell(20, 10, $row['daily_rate'], 1);
            $pdf->Cell(20, 10, $row['hourly_rate'], 1);
            $pdf->Cell(20, 10, $row['sss_tcEE'], 1);
            $pdf->Cell(20, 10, $row['ph_EE'], 1);
            $pdf->Cell(20, 10, $row['pagibig_EE'], 1);
            // Add more cells for additional columns
        }
    } else {
        $pdf->Cell(100, 10, 'No data found', 1, 1);
    }
    $pdf->Ln();
    $pdf->Cell(20, 10, 'Printed by:', 1);
    $pdf->Cell(100, 10, $adminId, 1, 1);

    // Output the PDF
    ob_start();  // Start output buffering
    $pdf->Output();
    ob_end_flush();  // Flush output buffer
}

// Check if the print button is clicked
if (isset($_GET['printAll'])) {
    // Print data as PDF query
    $query = "SELECT * FROM employees,PAYROLLINFO WHERE employees.emp_id = PAYROLLINFO.emp_id";
    $result = mysqli_query($conn, $query);

    if ($result === false) {
        die("Failed to fetch data: " . mysqli_error($conn));
    }
    $adminId = $_SESSION['adminId'];
    printDataAsPDF($result, $adminId);
} elseif (isset($_GET['printDisplayed'])) {
    // Print displayed masterlist query
    session_start();

    // Debugging: Check if the session variable is set
    var_dump($_SESSION['printpayroll_query']);

    // $queryResult = isset($_SESSION['printpayroll_query']) ? mysqli_query($conn, $_SESSION['printpayroll_query']) : 'SELECT * FROM employees,PAYROLLINFO WHERE employees.emp_id = PAYROLLINFO.emp_id';
    $queryResult = isset($_SESSION['printpayroll_query']) ? mysqli_query($conn, $_SESSION['printpayroll_query']) : mysqli_query($conn, 'SELECT * FROM employees INNER JOIN PAYROLLINFO ON employees.emp_id = PAYROLLINFO.emp_id');

    if ($queryResult === false) {
        die("Failed to fetch data: " . mysqli_error($conn));
    }
    $adminId = $_SESSION['adminId'];
    printDataAsPDF($queryResult, $adminId);
}

mysqli_close($conn);
?>
