<?php
 //$sitemap = $_GET['sitemap']; 
if(isset($_POST))
{
$fdate=$_POST["fdate"];
$tdate=$_POST["tdate"];
$rep=$_POST["rep"];
$item2=$_POST["item"];


              if(!empty($rep))
              {
              $rep_sql = "AND salesrep='$rep'"; 
              }
              else {   $rep_sql = ''; }
             
              if(!empty($item2))
              {
              $item_sql = "AND order_item.item='$item2'";
              }
              else {   $item_sql = ''; }
              
$customer=$_POST["company"];
$site=$_POST["site"];
if(!empty($customer))
              {
              $customer_sql = "AND customer ='".$customer."' AND site ='".$site."'"; 
              }
$po=$_POST["po"];
if(!empty($po))
              {
              $po_sql = "AND lpo ='".$po."' ";
              }

}
 ob_start();
    include('report_order-pdf.php');
    $html = ob_get_contents();
 ob_end_clean();
include("MPDF57/mpdf.php");
ob_start();
$mpdf=new mPDF('utf-8', 'A4', 0, '', 0, 0, 0, 0, 0, 0);

$mpdf->SetTitle('Order Report');
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
$stylesheet = file_get_contents('report.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html,2);
ob_clean();
$mpdf->Output('Report.pdf', 'I');
exit;
?>


