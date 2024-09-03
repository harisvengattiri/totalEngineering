<?php
$html = file_get_contents('invoice-pdf.php');
include("MPDF57/mpdf.php");
ob_start();
$mpdf=new mPDF('utf-8', 'A4', 0, '', 0, 0, 0, 0, 0, 0);
$mpdf->SetDisplayMode('fullpage');
//LOAD a stylesheet
$stylesheet = file_get_contents('pdf.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html,2);
ob_clean();
$mpdf->Output();
exit;
?>
