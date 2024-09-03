<?php
 //$sitemap = $_GET['sitemap']; 
 $or = $_GET['or'];
 $mnt= $_GET['mnt'];
 ob_start();
    include('quotation-pdf.php');
    $html = ob_get_contents();
 ob_end_clean();
include("MPDF57/mpdf.php");
ob_start();
$mpdf=new mPDF('utf-8', 'A4', 0, '', 0, 0, 0, 0, 0, 0);

$mpdf->SetTitle('Quotation');
$mpdf->setAutoTopMargin = 'stretch';
$mpdf->SetHTMLHeader('
<table width="100%">
    <tr>
        <td width="100%">
        <img src="../images/heder.png" alt="">
        </td>
    </tr>
   
</table>');
$mpdf->SetWatermarkImage('../images/watermark.png');
$mpdf->showWatermarkImage = true;
$mpdf->setAutoBottomMargin = 'stretch';
$mpdf->SetHTMLFooter('
<table width="100%">
    <tr>
        <td width="100%"><img src="../images/footer.png"/></td>
    </tr>
</table>');
$mpdf->SetDisplayMode('fullpage');

//LOAD a stylesheet
$stylesheet = file_get_contents('quotation.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html,2);
ob_clean();
$mpdf->Output('Quotation.pdf', 'I');
exit;
?>


