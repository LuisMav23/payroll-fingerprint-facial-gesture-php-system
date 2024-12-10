<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

// Load the existing Excel template file
$inputFileName = 'Employee Form.xls';
$spreadsheet = IOFactory::load($inputFileName);

// Get the active sheet (or specify a sheet by name or index)
$sheet = $spreadsheet->getActiveSheet();

// Define the starting row (row 9, considering zero-based index, it's actually row 10)
$startRow = 9;
$highestRow = $sheet->getHighestRow();

// Extract GET parameters for values
$values = isset($_GET['values']) ? urldecode($_GET['values']) : '';

// Decode JSON-encoded parameter
$values = json_decode($values, true);

// Validate that values is an array
if (is_array($values)) {
    $rowIndex = $startRow;

    // Update or append rows with provided values
    foreach ($values as $valueSet) {
        if (is_array($valueSet)) {
            $colIndex = 'A';
            foreach ($valueSet as $value) { // corrected variable name from $value to $valueSet
                $sheet->setCellValue($colIndex . $rowIndex, $value);
                $colIndex++;
            }
            $rowIndex++;
        }
    }

    // Save the updated Excel file to a temporary file with a unique name
    $tempFileName = tempnam(sys_get_temp_dir(), 'temp_employee_form_') . '.xls';
    $writer = IOFactory::createWriter($spreadsheet, 'Xls');
    $writer->save($tempFileName);

    // Serve the file for download with the original template name
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . basename($inputFileName) . '"');
    header('Cache-Control: max-age=0');
    readfile($tempFileName);

    // Delete the temporary file after serving it
    unlink($tempFileName);

    exit;
} else {
    echo "Please provide valid JSON-encoded values.";
}
?>