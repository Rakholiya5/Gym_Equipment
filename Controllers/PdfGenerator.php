<?php
require_once 'fpdf/fpdf.php';
class PdfGenerator extends FPDF
{
    public function __construct()
    {
        parent::__construct();
    }

    public function generatePDF($total, $name, $phone, $email, $address, $cartItemsForPdf)
    {
        // Add a new page to the PDF
        $this->AddPage();

        $this->SetFont('Arial', 'B', 18);

        // Set some content for the PDF (You can customize this based on your needs)
        $this->Cell(0, 10, 'Invoice', 0, 1, 'C');
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Order Details:', 0, 1, 'L');
        $this->SetFont('Arial', '', 12);
        // Display user information in the PDF
        $this->Cell(0, 10, 'User Name: ' . $name, 0, 1);
        $this->Cell(0, 10, 'Mobile no.: ' . $phone, 0, 1);
        $this->Cell(0, 10, 'Email: ' . $email, 0, 1);
        $this->Cell(0, 10, 'Address: ' . $address, 0, 1);
        $this->SetFont('Arial', 'B', 12);
        // Display cart items in the PDF
        $this->Cell(0, 10, 'Order Items:', 0, 1);
        $this->SetFont('Arial', '', 12);
        $tableWidth = 120; // Adjust the width of the table as needed
        $tableX = ($this->GetPageWidth() - $tableWidth) / 2;
        // Table header
        $this->SetFont('Arial', 'B', 12);
        $this->SetX($tableX);

        $this->Cell(60, 10, 'Item Name', 1, 0, 'C');
        $this->Cell(20, 10, 'Quantity', 1, 0, 'C');
        $this->Cell(40, 10, 'Price', 1, 0, 'C');
        $this->Ln();

        // Table content
        $this->SetFont('Arial', '', 12);
        foreach ($cartItemsForPdf as $item) {
            $this->SetX($tableX);
            $this->Cell(60, 10, $item['name'], 1, 0, 'C');
            $this->Cell(20, 10, $item['quantity'], 1, 0, 'C');
            $this->Cell(40, 10, '$' . $item['price'], 1, 0, 'C');
            $this->Ln();
        }

        $this->SetX($tableX);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(60, 10, '', 1, 0, '');
        $this->Cell(20, 10, 'Total:', 1, 0, 'R');
        $this->SetFont('Arial', '', 12);
        $this->Cell(40, 10, '$' . $total, 1, 0, 'C');
        $this->Ln();


        // Output the PDF as a file
        $this->Output('invoice.pdf', 'I');
    }
}
?>
