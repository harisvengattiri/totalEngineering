<?php

include "../../config.php";
require_once 'vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8','format' => 'A4','margin_left' => 10,'margin_right' => 10,'margin_top' => 30,'margin_bottom' => 10,'margin_header' => 0,'margin_footer' => 0,]);
$html = file_get_contents(BASEURL.'/prints/quotation_pdf?qno=7000');
$mpdf->SetHTMLHeader("<img src=\"images/header.png\">");
$mpdf->SetWatermarkImage('images/watermark.png');
$mpdf->showWatermarkImage = true;
$mpdf->SetHTMLFooter('<img style="margin-bottom:-15px;" src="images/footer01.png"/>');
$mpdf->WriteHTML($html);
$mpdf->Output();



// error_reporting(0);
// $qno=$_GET["qno"];
// $html = file_get_contents(BASEURL.'/prints/quotation_pdf?qno='.$qno);
// require_once('vendor/autoload.php');
// $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8','format' => 'A4','margin_left' => 10,'margin_right' => 10,'margin_top' => 10,'margin_bottom' => 10,'margin_header' => 0,'margin_footer' => 0,]);
// $mpdf->setFont('Arial','',12);
// $mpdf->setAutoTopMargin = 'stretch';
// $mpdf->SetHTMLHeader("<img src=\"{BASEURL}/images/header.png\">");
// $mpdf->SetWatermarkImage('images/watermark.png');
// $mpdf->showWatermarkImage = true;
// // $mpdf->showImageErrors = true;
// $mpdf->setAutoBottomMargin = 'stretch';
// $mpdf->SetHTMLFooter('<img style="margin-bottom:-15px;" src="images/footer01.png"/>');
// $mpdf->SetDisplayMode('fullpage');
// $mpdf->WriteHTML($html);
// $mpdf->Output('Quotation #'.$qno.'.pdf', 'I');
// exit;
