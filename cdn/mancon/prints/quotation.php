<?php
include "../../../config.php";
// error_reporting(0);
$qno=$_GET["qno"];
// $html = file_get_contents('https://mancon.gulfit.in/prints/quotation_pdf?qno='.$qno);
$html = file_get_contents($baseurl.'/prints/quotation_pdf?qno='.$qno);
// include("../includes/mpdf/mpdf.php");
require_once('../vendor/autoload.php');
// $mpdf=new mPDF('utf-8', 'A4', 0, '', 10, 10, 0, 0, 0, 0);
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8','format' => 'A4','margin_left' => 10,'margin_right' => 10,'margin_top' => 30,'margin_bottom' => 10,'margin_header' => 0,'margin_footer' => 0,]);
$mpdf->setFont('Arial','',12);
$mpdf->setAutoTopMargin = 'stretch';
$mpdf->SetHTMLHeader("<img src=\"{$baseurl}/images/header.jpg\">");
$mpdf->SetWatermarkImage('../images/watermark.png');
$mpdf->showWatermarkImage = true;
// $mpdf->showImageErrors = true;
$mpdf->setAutoBottomMargin = 'stretch';
$mpdf->SetHTMLFooter('<img style="margin-bottom:-15px;" src="../images/footer01.png"/>');
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($html);
$mpdf->Output('Quotation #'.$qno.'.pdf', 'I');
exit;
?>