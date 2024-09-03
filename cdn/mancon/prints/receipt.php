<?php
include "../../../config.php";
$rv=$_GET["rv"];
$open=$_GET["open"];

$html = file_get_contents($baseurl.'/prints/rv?rv='.$rv.'&open='.$open);
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
$mpdf->Output('Receipt Voucher #'.$rv.'.pdf', 'I');
exit;
?>