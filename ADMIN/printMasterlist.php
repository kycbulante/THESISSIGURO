<?php
include("../DBCONFIG.PHP");
include("../LoginControl.php");
include("../BASICLOGININFO.PHP");
require_once("./fpdf181/fpdf.php");
require_once("../phpqrcode/qrlib.php");


// Check if the print button is clicked
if (isset($_GET['printAll'])) {
    
    // Print data as PDF query
    $query = "SELECT * FROM employees";
    $result = mysqli_query($conn, $query);

    if ($result === false) {
        die("Failed to fetch data: " . mysqli_error($conn));
    }
    $urlhehe = 'hi';

    $adminId = $_SESSION['adminId'];
    // Embed QR code into PDF
    printDataAsPDF($result, $urlhehe,$adminId);
    
    // Clean up temporary QR code image file
    unlink($qrCodeFile);
} elseif (isset($_GET['printDisplayed'])) {
    // Print displayed masterlist query
    session_start();
    

    // Debugging: Check if the session variable is set
    var_dump($_SESSION['print_query']);

    $queryResult = isset($_SESSION['print_query']) ? mysqli_query($conn, $_SESSION['print_query']) : '';

    if ($queryResult === false) {
        die("Failed to fetch data: " . mysqli_error($conn));
    }

    $adminId = $_SESSION['adminId'];
    // Embed QR code into PDF
    printDataAsPDF($queryResult, $urlhehe,$adminId);
    // Clean up temporary QR code image file
    unlink($qrCodeFile);
}

// Function to fetch and display data as PDF
function printDataAsPDF($result, $urlhehe,$adminId) {
    $pdf = new FPDF('L', 'mm', 'A4');
    $pdf->AddPage();
    
    $pdfIdentifier = time();

    // Generate QR code data with a link to download the PDF
    // $qrCodeData = "http://localhost:8080/thesissiguro/ADMIN/download_pdf.php?pdf={$pdfIdentifier}";
    // $qrCodeFile = 'temp_qr_code.png';
    // QRcode::png($qrCodeData, $qrCodeFile);

    // $pdf->Image($qrCodeFile, 10, 10, 30, 30, 'png');
    // $pdf->SetY(50);

    // Header
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(20, 10, 'Employee ID', 1);
    $pdf->Cell(20, 10, 'Fingerprint ID', 1);
    $pdf->Cell(20, 10, 'Last Name', 1);
    $pdf->Cell(20, 10, 'First Name', 1);
    $pdf->Cell(20, 10, 'Middle Name', 1);
    $pdf->Cell(20, 10, 'Username', 1);
    $pdf->Cell(20, 10, 'Department', 1);
    $pdf->Cell(20, 10, 'Employment Type', 1);
    $pdf->Cell(20, 10, 'Shift', 1);
    $pdf->Cell(20, 10, 'Contact Number', 1);
    $pdf->Cell(20, 10, 'Date Hired', 1);
    $pdf->Cell(20, 10, 'Date Regularized', 1);
    $pdf->Cell(20, 10, 'Date Resigned', 1);
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
            $pdf->Cell(20, 10, $row['user_name'], 1);
            $pdf->Cell(20, 10, $row['dept_NAME'], 1);
            $pdf->Cell(20, 10, $row['shift_SCHEDULE'], 1);
            $pdf->Cell(20, 10, $row['contact_number'], 1);
            $pdf->Cell(20, 10, $row['date_hired'], 1);
            $pdf->Cell(20, 10, $row['date_regularized'], 1);
            $pdf->Cell(20, 10, $row['date_resigned'], 1);
            $pdf->Cell(20, 10, $row['emp_id'], 1);
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



mysqli_close($conn);
?>
