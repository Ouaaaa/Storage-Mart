<?php
// ✅ Correct vendor path
require '../vendor/autoload.php'; 

use PhpOffice\PhpWord\TemplateProcessor;

// ✅ Load Word template
$templatePath = __DIR__ . '/template_accountability.docx';
if (!file_exists($templatePath)) {
    die('❌ Template file not found: ' . $templatePath);
}

$template = new TemplateProcessor($templatePath);

// ✅ Fill dynamic values
$template->setValue('name', 'Dionne Mye T. Corpuz');
$template->setValue('department', 'IT Department');
$template->setValue('position', 'Technical Support');
$template->setValue('employee_id', 'EMP-1025');
$template->setValue('date_issued', date('F d, Y')); // optional

// ✅ Save generated file
$outputFile = __DIR__ . '/filled_accountability_form.docx';
$template->saveAs($outputFile);

// ✅ Download to browser
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=" . basename($outputFile));
header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
header("Content-Length: " . filesize($outputFile));
readfile($outputFile);

// (Optional) delete file after download
unlink($outputFile);
exit;
?>
