<?php
include "../../../config.php";
$cdt=$_GET["cdt"];
$open=$_GET["open"];

// $html = file_get_contents('https://mancon.gulfit.in/prints/crdt_note_pdf?cdt='.$cdt.'&open='.$open);
// include("../includes/mpdf/mpdf.php");
// $mpdf=new mPDF('utf-8', 'A4', 0, '', 10, 10, 30, 0, 0, 0);

$html = file_get_contents($baseurl.'/prints/crdt_note_new_pdf?cdt='.$cdt.'&open='.$open);
require_once '../vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8','format' => 'A4','margin_left' => 10,'margin_right' => 10,'margin_top' => 30,'margin_bottom' => 10,'margin_header' => 0,'margin_footer' => 0,]);

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