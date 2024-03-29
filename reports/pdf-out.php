<?php
require('../fpdf/fpdf.php');
 
class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
            $this->Image('../cvsu.png',10,10,30);
        // Arial bold 15
        $this->SetFont('Arial','',10);
        // Title
        $this->Cell(0,5,'Republic of the Philippines',0,1,'C');
        $this->SetFont('Arial','B',15);
        // Title
        $this->Cell(0,5,'CAVITE STATE UNIVERSITY',0,1,'C');
        // Arial bold 12
        $this->SetFont('Arial','B',12);
        $this->Cell(0,5,'GENERAL TRIAS CITY CAMPUS',0,1,'C');
        // Arial regular 10
        $this->SetFont('Arial','',10);
        $this->Cell(0,5,'Brgy. Vibora, City of General Trias, Cavite, 4107',0,1,'C');
        $this->Cell(0,5,'(046) 884 - 0570',0,1,'C');
        $this->Cell(0,5,'cvsgeneraltrias@cvsu.edu.ph',0,1,'C');
        // Line break

        $this->SetFont('Arial','B',10);
        $this->Cell(180, 15, 'Monthly Product Inventory (OUT)', 0, 1, 'C');

        $this->Ln(1);
    }
    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}



 
// Replace these values with your actual database credentials
$hostname = "your_database_host";
$username = "your_database_username";
$password = "your_database_password";
$database = "your_database_name";
 
 // Connect to the database
 include("../connection.php");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 
// Get the current month and year
$currentMonth = date('m');
$currentYear = date('Y');
 
 
$startDate = $_GET['start'];
$endDate = $_GET['end'];
 
// Query to retrieve monthly data
$sql = "SELECT C.barcodeId, C.productName, C.productGroup, D.name AS unit_name, D.short_name as unit_short_name, SUM(B.quantity) AS qty
        FROM product_transactions as A
        RIGHT JOIN orders as B
        ON A.order_id = B.id
        LEFT JOIN product as C
        ON B.product_id = C.id
        LEFT JOIN units as D
        ON C.unit_id = D.id
        WHERE A.created_at BETWEEN '$startDate' AND '$endDate'
        GROUP BY C.barcodeId, C.productName, C.productGroup, D.name, D.short_name";
 
$result = $conn->query($sql);
 
// Create PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
 
 
// Check if there are results
if ($result->num_rows > 0) {
    // Output table header
    $pdf->SetFont('Arial', 'B',  10);
    $pdf->Cell(40, 10, 'Barcode ID', 1, 0, 'C'); // Center alignment
    $pdf->Cell(40, 10, 'Product Name', 1, 0, 'C'); // Center alignment
    $pdf->Cell(40, 10, 'Product Group', 1, 0, 'C'); // Center alignment
    $pdf->Cell(35, 10, 'Total Qty', 1, 0, 'C'); // Center alignment
    $pdf->Cell(35, 10, 'Unit', 1, 1, 'C'); // Center alignment and move to the next line

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(40, 10, $row["barcodeId"], 1, 0, 'C'); // Center alignment
        $pdf->Cell(40, 10, $row["productName"], 1, 0, 'C'); // Center alignment
        $pdf->Cell(40, 10, $row["productGroup"], 1, 0, 'C'); // Center alignment
        $pdf->Cell(35, 10, $row["qty"], 1, 0, 'C'); // Center alignment
        $pdf->Cell(35, 10, $row["unit_name"]." (".$row["unit_short_name"] .")", 1, 1, 'C'); // Center alignment and move to the next line
    }
} else {
    $pdf->SetFont('Arial', 'I', 12);
    $pdf->Cell(0, 10, 'No results found', 0, 1, 'C');
}
// Output PDF
$pdf->Output();
 
// Close the database connection
$conn->close();
?>