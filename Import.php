<?php
// importQuestions.php
require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$file = $_FILES['file']['tmp_name'];
$spreadsheet = IOFactory::load($file);
$datas = $spreadsheet->getActiveSheet()->toArray();

$conn = new mysqli("localhost", "root", "ABC123!", "onlinesystem");
for ($i = 1; $i < count($datas); $i++) {
  [$id, $type, $testpaper, $alternatives, $answer] = $datas[$i];
  $stmt = $conn->prepare("INSERT INTO questions (type, testpaper, alternatives, correct_answer) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $type, $testpaper, $alternatives, $answer);
  $stmt->execute();
}
echo "Imported successfully!";

?>