<?php
include "../../../config.php";
if(isset($_POST))
{
$fdate=$_POST["fdate"];
$tdate=$_POST["tdate"];
$driver=$_POST["driver"];
}
// $html = file_get_contents('http://mancon.gulfit.in/mpdf/report_driver-pdf?fd='.$fdate.'&td='.$tdate.'&driver='.$driver);
// include("../includes/mpdf/mpdf.php");
// $mpdf=new mPDF('utf-8', 'A4', 0, '', 10, 10, 30, 0, 0, 0);
$html = file_get_contents($baseurl.'/mpdf/report_driver-pdf?fd='.$fdate.'&td='.$tdate.'&driver='.$driver);
require_once '../vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8','format' => 'A4','margin_left' => 10,'margin_right' => 10,
'margin_top' => 30,'margin_bottom' => 10,'margin_header' => 0,'margin_footer' => 0,]);
$mpdf->setAutoTopMargin = 'stretch';
$mpdf->SetHTMLHeader('<img src="../images/header.png">');
$mpdf->showWatermarkImage = true;
$mpdf->setAutoBottomMargin = 'stretch';
$mpdf->SetHTMLFooter('<img style="margin-bottom:-15px;" src="../images/footer01.png"/>');
$mpdf->SetDisplayMode('fullpage');
$stylesheet = file_get_contents($baseurl.'/mpdf/report1.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($html);
$mpdf->Output('Driver Report.pdf', 'I');
exit;
?>