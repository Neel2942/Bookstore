<?php
//include connection file 
include "dbconnect.php";
include_once('fpdf184/fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(190, 10, 'Book Store Inventory', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function GenerateBookList($data) {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(20, 10, 'Book ID', 1, 0, 'C');
        $this->Cell(80, 10, 'Title', 1, 0, 'C');
        $this->Cell(40, 10, 'Author', 1, 0, 'C');
        $this->Cell(20, 10, 'Edition', 1, 0, 'C');
        $this->Cell(35, 10, 'ISBN', 1, 0, 'C');
        $this->Cell(20, 10, 'Price', 1, 0, 'C');
        $this->Cell(30, 10, 'Category', 1, 0, 'C');
        $this->Ln();

        foreach ($data as $row) {
            $this->SetFont('Arial', '', 12);
            $this->Cell(20, 10, $row['book_id'], 1, 0, 'C');
            $this->Cell(80, 10, $row['book_title'], 1, 0, 'C');
            $this->Cell(40, 10, $row['author_name'], 1, 0, 'C');
            $this->Cell(20, 10, $row['edition'], 1, 0, 'C');
            $this->Cell(35, 10, $row['isbn'], 1, 0, 'C');
            $this->Cell(20, 10, $row['price'], 1, 0, 'C');
            $this->Cell(30, 10, $row['category_name'], 1, 0, 'C');
            $this->Ln();
        }
    }
}

// Create a new PDF instance
$pdf = new PDF('L', 'mm', 'A3');
$pdf->AddPage();

// Call the stored procedure to fetch book data
$query = "CALL ListBooksInStore()";
$result = $conn->query($query);

// Check if there are rows returned
if ($result->num_rows > 0) {
    // Fetch data into an associative array
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    
    // Calculate the left margin to center the table
    $pageWidth = $pdf->GetPageWidth();
    $tableWidth = 30 + 80 + 40 + 30 + 50 + 30 + 40;
    $leftMargin = ($pageWidth - $tableWidth) / 2;
    $pdf->SetLeftMargin($leftMargin);

    // Generate book list using the fetched data
    $pdf->GenerateBookList($data);
} else {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'No books found.', 0, 1, 'C');
}

// Close the database connection
$conn->close();

// Output the PDF
$pdf->Output();