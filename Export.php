<?php
// exportQuestions.php
require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conn = new mysqli("localhost", "root", "ABC123!", "onlinesystem");
$res = $conn->query("SELECT * FROM questions");

$sheet = new Spreadsheet();
$sheet->getActiveSheet()->fromArray(['ID', 'Type', 'testpaper', 'Alternatives', 'Answer'], NULL, 'A1');
$row = 2;
while ($q = $res->fetch_assoc()) {
  $sheet->getActiveSheet()->fromArray([
    $q['id'], $q['type'], $q['testpaper'], $q['alternatives'], $q['correct_answer']
  ], NULL, "A$row");
  $row++;
}

$writer = new Xlsx($sheet);
header("Content-Disposition: attachment; filename=questions.xlsx");
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
$writer->save("php://output");

?>