<?php
require_once __DIR__ . '/vendor/autoload.php'; // Include the mPDF library

$mpdf = new \Mpdf\Mpdf();

// Set a title for the PDF
$mpdf->SetTitle('Employee Information');

// Create the content
$html = '
    <h1>Employee Information</h1>
    <p><strong>Date:</strong> August 2024</p>
    <p><strong>Employee Code:</strong> WY01</p>
    <p>This is a sample PDF generated using mPDF with dummy data.</p>
';

// Write the HTML content to the PDF
$mpdf->WriteHTML($html);

// Define the file path where you want to save the PDF
$filePath = __DIR__ . '/employee_information.pdf';

// Save the PDF to the specified path
$mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);

echo "PDF has been saved to $filePath";
?>