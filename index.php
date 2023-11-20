<?php
require 'AlphaPDF.php';
////######## CONFIG #########/////
$fileName = 'input.pdf';
$watermarkImage = 'logo.jpg';
$borderImage = 'border.png';
$outputFileName = 'output.pdf';
////######## SCRIPT #########/////
$pdf = new AlphaPDF();
if (file_exists("./" . $fileName)) {
    try {
        $pageCount = $pdf->setSourceFile($fileName);
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
    $pdf->SetAlpha(0.2);
    $xFinal = ($size['width'] / 2 - 45);
    $yFinal = ($size['height'] / 2 - 40);
    $pdf->Image($watermarkImage, $xFinal, $yFinal, 0, 0, 'jpg');
    $pdf->SetAlpha(1);
    $pdf->Image($borderImage, 0, 0, $size['width'], $size['height'], 'png');
}
$pdf->Output('F', $outputFileName);