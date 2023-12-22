<?php
//include connection file 
include "dbconnect.php";
include_once('fpdf184/fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Customers Who Purchased Specific Book', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function GenerateCustomerList($bookID, $bookTitle, $data) {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Book ID: ' . $bookID, 0, 1, 'C');
        $this->Cell(0, 10, 'Book Title: ' . $bookTitle, 0, 1, 'C');
        $this->Ln(5);
        // Center the table
        $this->SetLeftMargin(($this->GetPageWidth() - 90) / 2); 
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(50, 10, 'Customer Name', 1, 0, 'C');
        $this->Cell(40, 10, 'Quantity', 1, 0, 'C');
        $this->Ln();

        foreach ($data as $row) {
            $this->SetFont('Arial', '', 12);
            $this->Cell(50, 10, $row['customer_name'], 1, 0, 'C');
            $this->Cell(40, 10, $row['quantity'], 1, 0, 'C');
            $this->Ln();
        }
    }
}

// Create a new PDF instance
$pdf = new PDF();
$pdf->AddPage();

// Call the stored procedure to fetch customer data for a specific book
$bookID = 9; // Replace with the desired book ID
$query = "CALL SpecificBookCustomers($bookID)";
$result = $conn->query($query);

// Check if there are rows returned
if ($result->num_rows > 0) {
    // Fetch data into an associative array
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $bookTitle = $data[0]['book_title'];

    // Generate customer list using the fetched data
    $pdf->GenerateCustomerList($bookID, $bookTitle, $data);
} else {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'No customer data found for the specific book.', 0, 1, 'C');
}

// Close the database connection
$conn->close();

// Output the PDF
$pdf->Output();