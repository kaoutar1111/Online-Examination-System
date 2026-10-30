<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conn = new mysqli("localhost", "root", "ABC123!", "onlinesystem");
if ($conn->connect_error) {
    die("Error connecting");
}

$sql = "SELECT r.student_id, e.title AS exam, r.mark, r.reviewed_in
        FROM results r
        JOIN exams e ON r.test_id = e.test_id
        ORDER BY r.reviewed_in DESC";

$res = $conn->query($sql);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle('Results');

$sheet->fromArray(
  ['student ID', 'Exam', 'mark', 'reviewed in'],
  NULL, 'A1'
);

$row = 2;
while ($data $res->fetch_assoc()) {
  $sheet->setCellValue("A$row", $data['student_id']);
  $sheet->setCellValue("B$row", $data['exam']);
  $sheet->setCellValue("C$row", $data['mark']);
  $sheet->setCellValue("D$row", $data['reviewed_in']);
  $row++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="results_exams.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
?>
