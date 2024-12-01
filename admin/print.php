<?php
define('FPDF_FONTPATH', 'font/');
require('dbconn.php'); // Include your database connection
require('fpdf.php'); // Include FPDF library

// Create a new PDF instance
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

// Add title
$pdf->Cell(0, 10, 'Payment Transactions', 0, 1, 'C');

// Add table header
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(40, 10, 'Transaction ID', 1);
$pdf->Cell(50, 10, 'Name', 1);
$pdf->Cell(40, 10, 'User ID', 1);
$pdf->Cell(30, 10, 'Amount', 1);
$pdf->Cell(30, 10, 'Payment Date', 1);
$pdf->Ln();

// Query to retrieve payment information, joining with the user table
$sql = "SELECT p.*, u.Name FROM payments p JOIN user u ON p.user_id = u.IDNo WHERE 1=1";

// Add date filtering to SQL query if dates are provided
if (isset($_GET['start_date']) && !empty($_GET['start_date'])) {
    $sql .= " AND payment_date >= '" . $_GET['start_date'] . "'";
}
if (isset($_GET['end_date']) && !empty($_GET['end_date'])) {
    $sql .= " AND payment_date <= '" . $_GET['end_date'] . "'";
}

// Default sort order
$sql .= " ORDER BY payment_date DESC";

// Execute the query
if ($result = $conn->query($sql)) {
    // Check if there are records
    if ($result->num_rows > 0) {
        // Fetch all payment records
        while ($row = $result->fetch_assoc()) {
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(40, 10, htmlspecialchars($row['id']), 1);
            $pdf->Cell(50, 10, htmlspecialchars($row['Name']), 1);
            $pdf->Cell(40, 10, htmlspecialchars($row['user_id']), 1);
            $pdf->Cell(30, 10, htmlspecialchars($row['amount']), 1);
            $pdf->Cell(30, 10, htmlspecialchars($row['payment_date']), 1);
            $pdf->Ln();
        }
    } else {
        $pdf->Cell(0, 10, 'No transactions found.', 0, 1);
    }
    // Free the result set
    $result->free();
} else {
    $pdf->Cell(0, 10, 'Error retrieving transactions: ' . $conn->error, 0, 1);
}

// Close the database connection
$conn->close();

// Output the PDF
$pdf->Output('I', 'payment_transactions.pdf'); // Change 'D' to 'I' to display inline
?>
