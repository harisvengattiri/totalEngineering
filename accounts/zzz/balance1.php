<?php
  include "../config.php";
  // error_reporting(0);
  ?>
<?php
session_start();
if(!isset($_SESSION['userid']))
{
   header("Location:$baseurl/login/");
}
?>
<?php
if(!empty($_GET))
{

}
// $fdate = $_POST['fdate'];
//     $start_date = '01/01/2015';
//     $inception = 'Since Inception';
//     $show_fdate = ($fdate != NULL) ? $fdate : $inception;
//     $fdate = ($fdate != NULL) ? $fdate : $start_date;
// $tdate = $_POST['tdate'];

// $start_date = '01/01/2015';
// $fdate = '01/01/2020';
// $tdate = '31/12/2020';


    $start_date='01/01/2018';
    // $date2=$_POST['date2'];
    $date2='31/12/2023';
    $date_help = str_replace('/','-',$date2);
    $year = date('Y', strtotime($date_help));
         
    $date1='01/01/'.$year; 
    $date1=$_POST['date1'];
?>
<style type = "text/css">
      @media screen {
         /*p.bodyText {font-family:verdana, arial, sans-serif;}*/
      }
      @media print {
           @page {margin: 0mm;}
           #hlpo,#blpo{width:100px;word-break: break-all;}
           #hsite,#bsite{width:200px;word-break: break-all;}
           #hitem,#bitem{width:200px;word-break: break-all;}
         /*p.bodyText {font-family:georgia, times, serif;}*/
      }
      @media screen, print {
         /*p.bodyText {font-size:10pt}*/
      }
</style>

<style>
table {
    border-collapse: collapse;
    width: 100%;
}
th, td {
    text-align: left;
    padding: 8px;
}
th {
    background-color: #4CAF50;
    color: white;
}
h1, h2 {
    font-family: Arial, Helvetica, sans-serif;
}
th,td {
    font-family: verdana;
}
tr:nth-child(even){background-color: #f2f2f2}
</style>

<div style="margin-top: -55px;" id="head" align="center"><img src="../images/header.png"></div>
<h2 style="text-align:center;margin-bottom:1px;"><?php // echo strtoupper($status);?>CHART OF ACCOUNTS</h2>
<table align="center" style="width:90%;">
     <tr>
          <!--<td width="50%">-->
          <!--     <span style="font-size:15px;">Customer: <?php // echo $cust;?><br>Salesman: <?php // echo $rep;?></span>-->
          <!--</td>-->
          <td width="50%" style="text-align:right"><span style="font-size:15px;"> Date: <?php echo $date1;?> - <?php echo $date2;?></span></td>
     </tr>     
</table>
 
 
        <table class="table m-b-none" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
             <tr>
              <th style="text-align:center;">
                 Particular
              </th>
              <th style="text-align:center;">
                 Debit
              </th>
              <th style="text-align:center;">
                 Credit
              </th>
             </tr>
        </thead>
        <tbody>   
             
          <!--CODING SECTION-->                
<!-- row starts here--> 

          <?php
             $sqlinvbal="SELECT sum(grand) AS openings_grand FROM invoice WHERE (STR_TO_DATE(invoice.date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(invoice.date,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
             $queryinv = mysqli_query($conn, $sqlinvbal);
             $resultinv = mysqli_fetch_array($queryinv);
             $openinginv = $resultinv['openings_grand'];
             
             $sql_opening1 = "SELECT sum(op_bal) AS opening_revenue1 FROM customers WHERE (STR_TO_DATE(op_date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(op_date,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
             $query_opening1 = mysqli_query($conn,$sql_opening1);
             $result_opening1 = mysqli_fetch_array($query_opening1);
             $opening_revenue1 = $result_opening1['opening_revenue1'];
             
             $sqlrcpbal = "SELECT sum(grand) as receipt_grand FROM reciept WHERE status='Cleared' AND (STR_TO_DATE(reciept.pdate,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(reciept.pdate,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
             $queryrcp = mysqli_query($conn, $sqlrcpbal);
             $resultrcp = mysqli_fetch_array($queryrcp);
             $openingrcp = $resultrcp['receipt_grand'];
             
             $sqlrcpbal1 = "SELECT sum(grand) as receipt_grand1 FROM reciept WHERE status!='Cleared' AND (STR_TO_DATE(reciept.pdate,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(reciept.pdate,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
             $queryrcp1 = mysqli_query($conn, $sqlrcpbal1);
             $resultrcp1 = mysqli_fetch_array($queryrcp1);
             $openingrcp1 = $resultrcp1['receipt_grand1'];
             
            $sql = "SELECT sum(total) as credit_note1 FROM credit_note WHERE (STR_TO_DATE(credit_note.date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(credit_note.date,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $credit_note1 = $result['credit_note1'];
           ?>

             <tr>
              <td>Opening Revenue</td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $openinginv);?></td>
             </tr>
             <tr>
              <td>Opening1 Revenue</td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $opening_revenue1);?></td>
             </tr>
             <tr>
              <td>Receipt Cleared</td>
              <td align="right"><?php echo custom_money_format('%!i', $openingrcp);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td>Receipt Uncleared</td>
              <td align="right"><?php echo custom_money_format('%!i', $openingrcp1);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td>Opening credit Note</td>
              <td align="right"><?php echo custom_money_format('%!i', $credit_note1);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td>Opening Advance</td>
              <td align="right"><?php echo custom_money_format('%!i', 555);?></td>
              <td align="right"></td>
             </tr>

          <?php
            $sql_opening = "SELECT sum(op_bal) AS opening_revenue FROM customers WHERE STR_TO_DATE(op_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query_opening = mysqli_query($conn,$sql_opening);
            $result_opening = mysqli_fetch_array($query_opening);
            $opening_revenue = $result_opening['opening_revenue'];

            $sql = "SELECT sum(grand) as op_rcp FROM reciept WHERE type='1' AND STR_TO_DATE(pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $fetch = mysqli_fetch_array($query);
            $opening_receipt = $fetch['op_rcp'];
            
            $pending_opening = $opening_revenue-$opening_receipt;
            $revenues[] = $opening_revenue;
            $received[] = $pending_opening;
          ?>
          <?php
            $sql_invoice = "SELECT sum(grand) AS grand,sum(vat) AS vat,sum(transport) AS transport FROM invoice WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query_invoice = mysqli_query($conn,$sql_invoice);
            $result_invoice = mysqli_fetch_array($query_invoice);
            $invoice_amount = $result_invoice['grand'];
            $invoice_vat = $result_invoice['vat'];
            $invoice_trp = $result_invoice['transport'];
            $invo_amt = $invoice_amount-($invoice_vat+$invoice_trp);
            $revenues[] = $invoice_amount;
          ?> 
          <?php
            $sql = "SELECT SUM(grand) as total_receipt FROM reciept WHERE STR_TO_DATE(pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $fetch = mysqli_fetch_array($query);
            $receipt_total = $fetch['total_receipt'];
            $received[] = $receipt_total;
          ?>
          <?php
            $sql = "SELECT sum(total) as credit_note FROM credit_note WHERE STR_TO_DATE(credit_note.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $credit_note = $result['credit_note'];
            $received[] = $credit_note;
          ?>

          <?php
            $sql = "SELECT sum(grand) as total_advance FROM reciept WHERE type='2' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $total_advance = $result['total_advance'];
            
            $sql = "SELECT sum(tbl.total) AS in_advance FROM (
                    SELECT reciept.id as id,reciept.grand as grand,sum(reciept_invoice.total) as total FROM reciept
                    INNER JOIN
                    reciept_invoice ON reciept.id = reciept_invoice.reciept_id WHERE reciept.type='2' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') AND STR_TO_DATE(reciept_invoice.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') GROUP BY reciept_invoice.reciept_id
                    ) as tbl";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $in_advance = $result['in_advance'];
            $adv_bal = $total_advance-$in_advance;
            $revenues[] = $adv_bal;
          ?>
          <?php
            $receipt_pending = ($opening_revenue+$invoice_amount+$adv_bal)-($receipt_total+$credit_note+$pending_opening);
            $received[] = $receipt_pending;
          ?>

             <tr>
              <td>Opening Revenue</td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $opening_revenue);?></td>
             </tr>

             <tr>
              <td>Opening Pending</td>
              <td align="right"><?php echo custom_money_format('%!i', $pending_opening);?></td>
              <td align="right"></td>
             </tr>

             <tr>
              <td>Revenues</td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $invo_amt);?></td>
             </tr>
             <tr>
              <td>Invoice Vat</td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $invoice_vat);?></td>
             </tr>
             <tr>
              <td>Transport Service</td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $invoice_trp);?></td>
             </tr>
             <tr>
              <td>Receipt</td>
              <td align="right"><?php echo custom_money_format('%!i', $receipt_total);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td>Credit Note</td>
              <td align="right"><?php echo custom_money_format('%!i', $credit_note);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td>Receipt pending</td>
              <td align="right"><?php echo custom_money_format('%!i', $receipt_pending);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td>Advance Balance</td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $adv_bal);?></td>
             </tr>
             
             
             
 <!--TOTAL CALCULATION-->
<!-- row starts here--> 
             <?php
               $revenue_total = array_sum($revenues);
               $received_total = array_sum($received);
             ?>
             <tr>
              <td><b>TOTAL</b></td>
              <td align="right"><?php echo custom_money_format('%!i', $received_total);?></td>
              <td align="right"><?php echo custom_money_format('%!i', $revenue_total);?></td>
             </tr>
<!-- row ends here-->
             
        </tbody>
      </table> 


<?php
if(isset($_POST['print'])) { ?>
    <body onload="window.print()">
<?php } ?>