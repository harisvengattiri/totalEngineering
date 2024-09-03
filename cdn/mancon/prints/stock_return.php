<?php
include "../../../config.php";
$id=$_GET["id"];
$open=$_GET["open"];
// $html = file_get_contents('https://mancon.gulfit.in/prints/stock_return_pdf?id='.$id.'&open='.$open);
// include("../includes/mpdf/mpdf.php");
// $mpdf=new mPDF('utf-8', 'A4', 0, '', 10, 10, 30, 0, 0, 0);
$html = file_get_contents($baseurl.'/prints/stock_return_pdf?id='.$id.'&open='.$open);
require_once '../vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8','format' => 'A4','margin_left' => 10,'margin_right' => 10,'margin_top' => 30,'margin_bottom' => 10,'margin_header' => 0,'margin_footer' => 0,]);
$mpdf->setAutoTopMargin = 'stretch';
$mpdf->showWatermarkImage = true;
$mpdf->setAutoBottomMargin = 'stretch';
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($html);
$mpdf->Output('Stock Return #'.$dno.'.pdf', 'I');
exit;
?>