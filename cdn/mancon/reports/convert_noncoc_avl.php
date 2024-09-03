<?php
$html = file_get_contents('http://mancon.gulfit.in/mpdf/report_noncoc_avl-pdf');
include("../includes/mpdf/mpdf.php");
$mpdf=new mPDF('utf-8', 'A4', 0, '', 10, 10, 30, 0, 0, 0);
$mpdf->setAutoTopMargin = 'stretch';
$mpdf->SetHTMLHeader('<img src="../images/header.png">');
$mpdf->showWatermarkImage = true;
$mpdf->setAutoBottomMargin = 'stretch';
$mpdf->SetHTMLFooter('<img style="margin-bottom:-15px;" src="../images/footer01.png"/>');
$mpdf->SetDisplayMode('fullpage');
$stylesheet = file_get_contents('http://mancon.gulfit.in/mpdf/report1.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($html);
$mpdf->Output('NON COC Report.pdf', 'I');
exit;
?>