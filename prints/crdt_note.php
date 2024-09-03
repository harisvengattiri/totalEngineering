<?php 
$cdt=$_GET["cdt"];
$open=$_GET["open"];
$html = file_get_contents('http://mancon.gulfit.xyz/prints/crdt_note_pdf?cdt='.$cdt.'&open='.$open);
include("../includes/mpdf/mpdf.php");
$mpdf=new mPDF('utf-8', 'A4', 0, '', 10, 10, 30, 0, 0, 0);
$mpdf->setAutoTopMargin = 'stretch';
 $mpdf->SetHTMLHeader('<img src="../images/header.png">');
 $mpdf->SetWatermarkImage('../images/watermark.png');
$mpdf->showWatermarkImage = true;
$mpdf->setAutoBottomMargin = 'stretch';
 $mpdf->SetHTMLFooter('<img style="margin-bottom:-15px;" src="../images/footer01.png"/>');
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($html);
$mpdf->Output('Credit Note #'.$cdt.'.pdf', 'I');
exit;
?>