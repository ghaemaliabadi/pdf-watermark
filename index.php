<?php
require 'AlphaPDF.php';
////######## CONFIG #########/////
$inputFileName = 'input.pdf';
$watermarkImage = 'logo.jpg';
$borderImage = 'border.png'; // Optional, if you don't want to use border for your PDF, comment this line
$outputFileName = 'output.pdf';
$watermarkOpacity = 20; // Range: 0-100
////######## SCRIPT #########/////
$pdf = new AlphaPDF();
if (file_exists("./" . $inputFileName)) {
    try {
        $pageCount = $pdf->setSourceFile($inputFileName);
    } catch (Exception $e) {
        die('Error occurred during file loading. Exception:' . $e->getMessage());
    }
} else {
    die('Source PDF not found!');
}
// ITERATE THROUGH ALL PAGES
for ($i = 1; $i <= $pageCount; $i++) {
    try {
        $tpl = $pdf->importPage($i);
    } catch (Exception $e) {
        die('Error occurred during file import. Exception:' . $e->getMessage());
    }
    $size = $pdf->getTemplateSize($tpl);
    $pdf->addPage();
    $pdf->useTemplate($tpl, 1, 1, $size['width'], $size['height'], TRUE);
    $pdf->SetAlpha($watermarkOpacity / 100);
    $xFinal = ($size['width'] / 2 - 45); // Change this value to move the watermark to right or left
    $yFinal = ($size['height'] / 2 - 40); // Change this value to move the watermark to top or bottom
    $pdf->Image($watermarkImage, $xFinal, $yFinal, 0, 0, 'jpg');
    /** @noinspection PhpConditionAlreadyCheckedInspection */
    if (isset($borderImage)) {
        $pdf->SetAlpha(1);
        $pdf->Image($borderImage, 0, 0, $size['width'], $size['height'], 'png');
    }
}
$pdf->Output('F', $outputFileName);