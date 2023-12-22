<?php
//include connection file 
include "dbconnect.php";
include_once('fpdf184/fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Customer Invoice', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function GenerateInvoice($data) {
        $this->SetFont('Arial', '', 12);
        $this->Cell(40, 10, 'Customer ID:',0);
        $this->Cell(0, 10, $data[0]['customer_id'], 0);
        $this->Ln(8);
        $this->Cell(40, 10, 'Customer Name:', 0);
        $this->Cell(0, 10, $data[0]['customer_name'], 0);
        $this->Ln(8);
        $this->Cell(40, 10, 'Email:', 0);
        $this->Cell(0, 10, $data[0]['email'], 0,);
        $this->Ln(8);
        $this->Cell(40, 10, 'Phone:', 0);
        $this->Cell(0, 10, $data[0]['phone'], 0);
        $this->Ln(8);
        $this->Cell(40, 10, 'Address:', 0);
        $this->MultiCell(0, 10, $data[0]['address'], 0);
        $this->Ln(10);

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(20, 10, 'Order ID', 1, 0, 'C');
        $this->Cell(40, 10, 'Order Date', 1, 0, 'C');
        $this->Cell(80, 10, 'Book Title', 1, 0, 'C');
        $this->Cell(25, 10, 'Price', 1, 0, 'C');
        $this->Cell(20, 10, 'Quantity', 1, 0, 'C');
        $this->Cell(30, 10, 'Total Amount', 1, 0, 'C');
        $this->Ln();

        $totalAmount = 0;
        foreach ($data as $row) {
            $this->SetFont('Arial', '', 12);
            $this->Cell(20, 10, $row['order_id'], 1, 0, 'C');
            $this->Cell(40, 10, $row['order_date'], 1, 0, 'C');
            $this->Cell(80, 10, $row['book_title'], 1, 0, 'C');
            $this->Cell(25, 10, $row['price'], 1, 0, 'C');
            $this->Cell(20, 10, $row['quantity'], 1, 0, 'C');
            $this->Cell(30, 10, $row['total_amount'], 1, 0, 'C');
            $this->Ln();

            $totalAmount += $row['total_amount'];
        }

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(185, 10, 'Total:', 1, 0, 'R');
        $this->Cell(30, 10, $totalAmount, 1, 0, 'C');
    }
}

// Create a new PDF instance
$pdf = new PDF('P','mm','A3');
$pdf->AddPage();

// Call the stored procedure to fetch invoice data
$customerID = 1; // Replace with the desired customer ID
$query = "CALL GenerateCustomerReceipt($customerID)";
$result = $conn->query($query);

// Check if there are rows returned
if ($result->num_rows > 0) {
    // Fetch data into an associative array
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Generate invoice using the fetched data
    $pdf->GenerateInvoice($data);
} else {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'No invoice data found.', 0, 1, 'C');
}

// Close the database connection
$conn->close();

// Output the PDF
$pdf->Output();