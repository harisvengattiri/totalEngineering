<?php
 //$sitemap = $_GET['sitemap']; 
 $or = $_GET['or'];
 $mnt= $_GET['id'];
 ob_start();
    include('delivery-pdf.php');
    $html = ob_get_contents();
 ob_end_clean();
include("MPDF57/mpdf.php");
ob_start();
$mpdf=new mPDF('utf-8', 'A4', 0, '', 0, 0, 0, 0, 0, 0);
$mpdf->SetTitle('DeliveryNote');
$mpdf->SetDisplayMode('fullpage');

//LOAD a stylesheet
$stylesheet = file_get_contents('delivery.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html,2);
ob_clean();
$mpdf->Output('DeliveryNote.pdf', 'I');
exit;
?>