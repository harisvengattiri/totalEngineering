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
    $date2 = '31/12/2023';
         
    $date_help = str_replace('/','-',$date2);
    $year = date('Y', strtotime($date_help));
    $date1='01/01/'.$year;
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
 
 
 
<table id="tbl1" align="center" style="width:90%;">
        <thead>
          <tr>
              <th>
                  Sl No
              </th>
              <th>
                  Account ID
              </th>
              <th>
                  Particular
              </th>
              <th>
                  Debit
              </th>
              <th>
                  Credit
              </th>
          </tr>
        </thead>
        <tbody style="font-size:18px;">


        <?php
             $sqlinvbal="SELECT sum(grand) AS openings_grand FROM invoice WHERE (STR_TO_DATE(invoice.date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(invoice.date,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
             $queryinv = mysqli_query($conn, $sqlinvbal);
             $resultinv = mysqli_fetch_array($queryinv);
             $openinginv = $resultinv['openings_grand'];
             $sql_opening1 = "SELECT sum(op_bal) AS opening_revenue0 FROM customers WHERE (STR_TO_DATE(op_date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(op_date,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
             $query_opening1 = mysqli_query($conn,$sql_opening1);
             $result_opening1 = mysqli_fetch_array($query_opening1);
             $opening_revenue0 = $result_opening1['opening_revenue0'];
             
             $real_opening = $openinginv+$opening_revenue0;
             
             $sqlrcpbal = "SELECT sum(grand) as receipt_grand1 FROM reciept WHERE status='Cleared' AND (STR_TO_DATE(reciept.pdate,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(reciept.pdate,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
             $queryrcp = mysqli_query($conn, $sqlrcpbal);
             $resultrcp = mysqli_fetch_array($queryrcp);
             $openingrcp1 = $resultrcp['receipt_grand1'];
             $sqlrcpbal = "SELECT sum(grand) as receipt_grand2 FROM reciept WHERE status='Cleared' AND (STR_TO_DATE(reciept.clearance_date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(reciept.pdate,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
             $queryrcp = mysqli_query($conn, $sqlrcpbal);
             $resultrcp = mysqli_fetch_array($queryrcp);
             $openingrcp2 = $resultrcp['receipt_grand2'];
             $cleared_balance = $openingrcp1-$openingrcp2; 
             
             $sqlrcpbal = "SELECT sum(grand) as receipt_grand3 FROM reciept WHERE status!='Cleared' AND (STR_TO_DATE(reciept.pdate,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(reciept.pdate,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
             $queryrcp = mysqli_query($conn, $sqlrcpbal);
             $resultrcp = mysqli_fetch_array($queryrcp);
             $openingrcp3 = $resultrcp['receipt_grand3'];
             $sqlrcpbal1 = "SELECT sum(grand) as receipt_grand4 FROM reciept WHERE status='Bounce' AND (STR_TO_DATE(reciept.pdate,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(reciept.pdate,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
             $queryrcp1 = mysqli_query($conn, $sqlrcpbal1);
             $resultrcp1 = mysqli_fetch_array($queryrcp1);
             $openingrcp4 = $resultrcp1['receipt_grand4'];
             $real_opening_pending1 = $openingrcp3-$openingrcp4;
             $real_opening_pending = $real_opening_pending1+$cleared_balance;
            
            $sql = "SELECT sum(grand) as total_advance FROM reciept WHERE type='2' AND (STR_TO_DATE(reciept.pdate,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(reciept.pdate,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $total_advance1 = $result['total_advance'];
            
            $sql = "SELECT sum(tbl.total) AS in_advance FROM (
                    SELECT reciept.id as id,reciept.grand as grand,sum(reciept_invoice.total) as total FROM reciept
                    INNER JOIN
                    reciept_invoice ON reciept.id = reciept_invoice.reciept_id WHERE reciept.type='2' AND
                    (STR_TO_DATE(reciept.pdate,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(reciept.pdate,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y')) AND
                    (STR_TO_DATE(reciept_invoice.date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(reciept_invoice.date,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))
                    GROUP BY reciept_invoice.reciept_id
                    ) as tbl";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $in_advance1 = $result['in_advance'];
            $adv_bal1 = $total_advance1-$in_advance1;
             
            $sql = "SELECT sum(total) as credit_note1 FROM credit_note WHERE (STR_TO_DATE(credit_note.date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(credit_note.date,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $credit_note1 = $result['credit_note1'];
            
            $total_receipted_opening = $openingrcp1+$openingrcp3;
            $opening_not_rcp = ($real_opening+$adv_bal1)-($total_receipted_opening+$credit_note1);
                   
            $actual_opening = $opening_not_rcp+$real_opening_pending;
               
            $revenues[] = $actual_opening;
            $received[] = $actual_borrow;
        ?>
        
        
        <!--OPENINGS SECTION ENDS-->
             <tr>
              <td><b>OPENING</b></td>
              <td align="right"><b><?php echo custom_money_format('%!i', $actual_borrow);?></b></td>
              <td align="right"><b><?php echo custom_money_format('%!i', $actual_opening);?></b></td>
             </tr>
<!-- row starts here--> 
          <?php
            $sql_opening = "SELECT sum(op_bal) AS opening_revenue FROM customers WHERE STR_TO_DATE(op_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query_opening = mysqli_query($conn,$sql_opening);
            $result_opening = mysqli_fetch_array($query_opening);
            $opening_revenue1 = $result_opening['opening_revenue'];

            $sql = "SELECT sum(grand) as op_rcp FROM reciept WHERE type='1' AND STR_TO_DATE(pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $fetch = mysqli_fetch_array($query);
            $opening_receipt1 = $fetch['op_rcp'];
            
            $pending_opening1 = $opening_revenue1-$opening_receipt1;
          ?>
          <?php
            $sql_invoice = "SELECT sum(grand) AS grand,sum(vat) AS vat,sum(transport) AS transport FROM invoice WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query_invoice = mysqli_query($conn,$sql_invoice);
            $result_invoice = mysqli_fetch_array($query_invoice);
            $invoice_amount = $result_invoice['grand'];
            $invoice_vat = $result_invoice['vat'];
            $invoice_trp = $result_invoice['transport'];
            $invo_amt = ($invoice_amount+$opening_revenue1)-($invoice_vat+$invoice_trp);
            $revenues[] = $invoice_amount+$opening_revenue1;
          ?>
          <?php
            $sql_msc = "SELECT sum(total) as msc_amt FROM miscellaneous WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')"; 
            $query_msc = mysqli_query($conn,$sql_msc);
            $result_msc = mysqli_fetch_array($query_msc);
            $msc_amount = $result_msc['msc_amt'];
            $msc_amount = ($msc_amount != NULL) ? $msc_amount : 0;
            $revenues[] = $msc_amount;
          ?>
          <?php
            $sql = "SELECT * FROM (
                    (SELECT CONCAT('RPT', id) AS id, sum(amount) AS Cleared_rcp_enbd FROM reciept WHERE inward='9' AND status='Cleared' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    )results";
            $query = mysqli_query($conn,$sql);
            $enbd_cr1 = $enbd_dr1 = 0;
            while($row = mysqli_fetch_array($query))
            { 
            $idet=$row["id"];
            // $result = substr($string ?? '', 0, 5);
              if(substr($idet ?? '', 0, 3) === 'RPT')
              {
              $enbd_cr1=$row["Cleared_rcp_enbd"];
              }
            }
            $enbd1 = $enbd_cr1-$enbd_dr1;
          ?>
          <?php
            $sql = "SELECT * FROM (
                    (SELECT CONCAT('RPT', id) AS id, sum(amount) AS Cleared_rcp_cbd FROM reciept WHERE inward='10' AND status='Cleared' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    )results";
            $query = mysqli_query($conn,$sql);
            $cbd_cr1 = $cbd_dr1 = 0;
            while($row = mysqli_fetch_array($query))
            { 
              $idet=$row["id"];
              if (substr($idet ?? '', 0, 3) === 'RPT')
              {
              $cbd_cr1=$row["Cleared_rcp_cbd"];
              }
            }
            $cbd1 = $cbd_cr1-$cbd_dr1;
          ?>
          <?php
            $sql = "SELECT * FROM (
                    (SELECT CONCAT('RPT', id) AS id, sum(amount) AS Cleared_rcp_credit FROM reciept WHERE inward='47' AND status='Cleared' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    )results";
            $query = mysqli_query($conn,$sql);
            $credit_cr1 = $credit_dr1 = 0;
            while($row = mysqli_fetch_array($query))
            {  
              $idet=$row["id"];
              if (substr($idet ?? '', 0, 3) === 'RPT')
              {
              $credit_cr1=$row["Cleared_rcp_credit"];
              }
            }
            $bank_credit1 = $credit_cr1-$credit_dr1;
          ?>
          <?php
            $sql = "SELECT * FROM (
                    (SELECT CONCAT('RPT', id) AS id, sum(amount) AS Cleared_rcp_enbd FROM reciept WHERE inward='9' AND status='Cleared' AND STR_TO_DATE(reciept.clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    UNION ALL
                    (SELECT CONCAT('MSC', id) AS id, sum(total) as Cleared_rcp_enbd FROM miscellaneous WHERE bank='9' AND STR_TO_DATE(miscellaneous.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    )results";
            $query = mysqli_query($conn,$sql);
            $enbd_cr = $enbd_cr_msc = $enbd_dr = $enbd_dr_petty = 0;
            while($row = mysqli_fetch_array($query))
            {  
              $idet=$row["id"];
              if (substr($idet ?? '', 0, 3) === 'RPT')
              {
              $enbd_cr=$row["Cleared_rcp_enbd"];
              }
              elseif (substr($idet ?? '', 0, 3) === 'MSC')
              {
              $enbd_cr_msc=$row["Cleared_rcp_enbd"];
              }
            }
            $enbd = ($enbd_cr+$enbd_cr_msc)-($enbd_dr+$enbd_dr_petty);
            $received[] = $enbd;
          ?>
          <?php
            $sql = "SELECT * FROM (
                    (SELECT CONCAT('RPT', id) AS id, sum(amount) AS Cleared_rcp_cbd FROM reciept WHERE inward='10' AND status='Cleared' AND STR_TO_DATE(reciept.clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    UNION ALL
                    (SELECT CONCAT('MSC', id) AS id, sum(total) as Cleared_rcp_cbd FROM miscellaneous WHERE bank='10' AND STR_TO_DATE(miscellaneous.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    )results";
            $query = mysqli_query($conn,$sql);
            $cbd_cr = $cbd_cr_msc = $cbd_dr = $cbd_dr_petty = 0;
            while($row = mysqli_fetch_array($query))
            {  
              $idet=$row["id"];
              if (substr($idet ?? '', 0, 3) === 'RPT')
              {
              $cbd_cr=$row["Cleared_rcp_cbd"];
              }
              elseif (substr($idet ?? '', 0, 3) === 'MSC')
              {
              $cbd_cr_msc=$row["Cleared_rcp_cbd"];
              }
            }
            $cbd = ($cbd_cr+$cbd_cr_msc)-($cbd_dr+$cbd_dr_petty);
            $received[] = $cbd;
          ?>
          <?php
            $sql = "SELECT * FROM (
                    (SELECT CONCAT('RPT', id) AS id, sum(amount) AS Cleared_rcp_credit FROM reciept WHERE inward='47' AND status='Cleared' AND STR_TO_DATE(reciept.clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    UNION ALL
                    (SELECT CONCAT('MSC', id) AS id, sum(total) as Cleared_rcp_credit FROM miscellaneous WHERE bank='47' AND STR_TO_DATE(miscellaneous.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    )results";
            $query = mysqli_query($conn,$sql);
            $credit_cr = $credit_cr_msc = $credit_dr = $credit_dr_petty = 0;
            while($row = mysqli_fetch_array($query))
            {  
              $idet=$row["id"];
              if (substr($idet ?? '', 0, 3) === 'RPT')
              {
              $credit_cr=$row["Cleared_rcp_credit"];
              }
              elseif (substr($idet ?? '', 0, 3) === 'MSC')
              {
              $credit_cr_msc=$row["Cleared_rcp_credit"];
              }
            }
            $bank_credit = ($credit_cr+$credit_cr_msc)-($credit_dr+$credit_dr_petty);
            $received[] = $bank_credit;
          ?>
          <?php
            $clearence_receipt_enbd = $enbd_cr1-$enbd_cr;
            $clearence_receipt_cbd = $cbd_cr1-$cbd_cr;
            $clearence_receipt_credit = $credit_cr1-$credit_cr;
            
            $clearence_receipt = $clearence_receipt_enbd+$clearence_receipt_cbd+$clearence_receipt_credit;
            
            $clearence_voucher_enbd = $enbd_dr1-$enbd_dr;
            $clearence_voucher_cbd = $cbd_dr1-$cbd_dr;
            $clearence_voucher_credit = $credit_dr1-$credit_dr;
            $clearence_voucher = $clearence_voucher_enbd+$clearence_voucher_cbd+$clearence_voucher_credit;
          ?>
          <?php
            $sql = "SELECT sum(amount) AS Uncleared_rcp FROM reciept WHERE status='Uncleared' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $Uncleared_rcp = $result['Uncleared_rcp'];
            $Uncleared_rcp = $Uncleared_rcp+$clearence_receipt;
            $received[] = $Uncleared_rcp;
          ?>
          <?php
            $sql = "SELECT sum(amount) AS Bounce_rcp FROM reciept WHERE status='Bounce' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $Bounce_rcp = $result['Bounce_rcp'];
            $Bounce_rcp = ($Bounce_rcp != NULL) ? $Bounce_rcp : 0;
            $received[] = $Bounce_rcp;
          ?>
          <?php
            $sql = "SELECT sum(amount) AS Hold_rcp FROM reciept WHERE status='Hold' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $Hold_rcp = $result['Hold_rcp'];
            $Hold_rcp = ($Hold_rcp != NULL) ? $Hold_rcp : 0;
            $received[] = $Hold_rcp;
          ?>
          <?php
            $sql = "SELECT SUM(grand) as total_receipt,sum(discount) AS receipt_discount FROM reciept WHERE STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $receipt_discount = $result['receipt_discount'];
            $receipt_total = $result['total_receipt'];
            $received[] = $receipt_discount;
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
            $receipt_pending = ($opening_revenue1+$actual_opening+$invoice_amount+$adv_bal)-($receipt_total+$credit_note);
            $received[] = $receipt_pending;
          ?>
<!-- row ends here-->
            
             <tr>
              <td><a href="ledger_trial_balance_details?type=rvn&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  Revenues
                  </a></td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $invo_amt);?></td>
             </tr>
            
            
             <tr>
              <td><a href="ledger_trial_balance_details?type=trp&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  Transport Service
                  </a></td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $invoice_trp);?></td>
             </tr>
             <tr>
              <td><a href="ledger_trial_balance_details?type=mis&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  Other Income -Scrab
              </a></td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $msc_amount);?></td>
             </tr>
             <tr>
              <td><a href="ledger_trial_balance_details?type=dis_rpt&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  Discount on Revenue
                  </a></td>
              <td align="right"><?php echo custom_money_format('%!i', $receipt_discount);?></td>
              <td align="right"></td>
             </tr>     

    

             <tr>
              <td><a href="ledger_trial_balance_details?type=adv&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  Advance
                  </a></td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $adv_bal);?></td>
             </tr>
            
             
             <tr>
              <td><a href="bank_cash_flow_search?id=815&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  BANK - E.N.B.D
                  </a></td>
              <td align="right"><?php echo custom_money_format('%!i', $enbd);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td><a href="bank_cash_flow_search?id=816&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  BANK - C.B.D
                  </a></td>
              <td align="right"><?php echo custom_money_format('%!i', $cbd);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td><a href="bank_cash_flow_search?id=1291&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  BANK - CREDIT
                  </a></td>
              <td align="right"><?php echo custom_money_format('%!i', $bank_credit);?></td>
              <td align="right"></td>
             </tr>
           
             <tr>
              <td><a href="ledger_trial_balance_details?type=uncl_rcp&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  Bill Receivabales
                  </a></td>
              <td align="right"><?php echo custom_money_format('%!i', $Uncleared_rcp);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td><a href="ledger_trial_balance_details?type=rcp_bnc&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  Bill Receivables - Bounce
                  </a></td>
              <td align="right"><?php echo custom_money_format('%!i', $Bounce_rcp);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td><a href="ledger_trial_balance_details?type=rcp_hld&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  Bill Receivables - Hold
                  </a></td>
              <td align="right"><?php echo custom_money_format('%!i', $Hold_rcp);?></td>
              <td align="right"></td>
             </tr>
             <tr>
             <td><a href="ledger_trial_balance_details?type=crdt_not&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                 Credit Note
                 </a></td>
             <td align="right"><?php echo custom_money_format('%!i', $credit_note);?></td>
             <td align="right"></td>
             </tr>
             <tr>
             <td>
                 <a href="ledger_trial_balance_details?type=csts&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                 Customers
                 </a>
                 </td>
             <td align="right"><?php echo custom_money_format('%!i', $receipt_pending);?></td>
             <td align="right"></td>
             </tr>


             
             <tr>
              <td><a href="ledger_trial_balance_details?type=rvn&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  Tax Payable
                  </a></td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $invoice_vat);?></td>
             </tr>
<!-- row ends here-->


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
            
        
        <tr>
            <td><b></b></td>
            <td></td>
            <td></td>
            <td><b><?php echo custom_money_format('%!i', array_sum($tot_debit));?></b></td>
            <td><b><?php echo custom_money_format('%!i', array_sum($tot_credit));?></b></td>
        </tr>



            
        </tbody>
      </table>

<?php
if(isset($_POST['print'])) { ?>
    <body onload="window.print()">
<?php } ?>