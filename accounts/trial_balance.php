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
$fdate = $_POST['fdate'];
    $start_date = '01/01/2015';
    $inception = 'Since Inception';
    $show_fdate = ($fdate != NULL) ? $fdate : $inception;
    $fdate = ($fdate != NULL) ? $fdate : $start_date;
$tdate = $_POST['tdate'];





$fdate = '01/01/2023';
$tdate = '20/09/2023';
// $tdate = '31/12/2021';

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
<h2 style="text-align:center;margin-bottom:1px;">TRIAL BALANCE</h2>
<table align="center" style="width:90%;">
     <tr>
          <!--<td width="50%">-->
          <!--     <span style="font-size:15px;">Customer: <?php // echo $cust;?><br>Salesman: <?php // echo $rep;?></span>-->
          <!--</td>-->
          <td width="50%" style="text-align:right"><span style="font-size:15px;"> Date: <?php echo $show_fdate;?> - <?php echo $tdate;?></span></td>
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
            // $sql_calc1 = "SELECT sum(amount) AS amt1 FROM `jv_items` WHERE (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            // $sql_calc2 = "SELECT sum(grand) AS amt2 FROM `pv` WHERE (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            // $sql_calc3 = "SELECT sum(dbt_amt) AS amt3 FROM `debitnote` WHERE (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            // $sql_calc4 = "SELECT sum(amount) AS amt4 FROM `jv_items` WHERE (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            // $sql_calc5 = "SELECT sum(op_bal) AS op_bal FROM `expense_subcategories`";
            // $sql_calc6 = "SELECT sum(amount) AS amt6 FROM `additionalAcc` WHERE (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            // $sql_calc7 = "SELECT sum(amount) AS amt7 FROM `reciept` WHERE (STR_TO_DATE(pdate,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(pdate,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            
            // $query_calc1 = mysqli_query($conn,$sql_calc1);
            // $result_calc1 = mysqli_fetch_array($query_calc1);
            //     $amt1 = $result_calc1['amt1'];
            //     $amt1 = ($amt1 != NULL) ? $amt1 : 0;
            // $query_calc2 = mysqli_query($conn,$sql_calc2);
            // $result_calc2 = mysqli_fetch_array($query_calc2);
            //     $amt2 = $result_calc2['amt2'];
            //     $amt2 = ($amt2 != NULL) ? $amt2 : 0;
            // $query_calc3 = mysqli_query($conn,$sql_calc3);
            // $result_calc3 = mysqli_fetch_array($query_calc3);
            //     $amt3 = $result_calc3['amt3'];
            //     $amt3 = ($amt3 != NULL) ? $amt3 : 0;
            // $query_calc4 = mysqli_query($conn,$sql_calc4);
            // $result_calc4 = mysqli_fetch_array($query_calc4);
            //     $amt4 = $result_calc4['amt4'];
            //     $amt4 = ($amt4 != NULL) ? $amt4 : 0;
            // $query_calc5 = mysqli_query($conn,$sql_calc5);
            // $result_calc5 = mysqli_fetch_array($query_calc5);
            //     $op_bal = $result_calc5['op_bal'];
            //     $op_bal = ($op_bal != NULL) ? $op_bal : 0;

            // $query_calc6 = mysqli_query($conn,$sql_calc6);
            // $result_calc6 = mysqli_fetch_array($query_calc6);
            //     $amt6 = $result_calc6['amt6'];
            //     $amt6 = ($amt6 != NULL) ? $amt6 : 0;
            // $query_calc7 = mysqli_query($conn,$sql_calc7);
            // $result_calc7 = mysqli_fetch_array($query_calc7);
            //     $amt7 = $result_calc7['amt7'];
            //     $amt7 = ($amt7 != NULL) ? $amt7 : 0;
                
            // $openingBalance = ($amt1+$amt2+$amt3+$amt6+$amt7) - ($amt4+$op_bal);
            // $openingBalance = ($openingBalance != NULL) ? $openingBalance : 0;
        ?>
        <tr>
            <!-- <td><b></b></td>
            <td><b></b></td>
            <td style="font-size:18px;"><b>Opening Balance</b></td>
            <td><b><?php // echo custom_money_format('%!i', $openingBalance);?></b></td> -->
        </tr>    
        
        
        
        
        
        

    <?php
    // BANK TYPE 1 MEANS OPENING CLEARED 
        // ACTUAL OPENING INVOICE
            $sql_real_opening_invoice = "SELECT sum(op_bal) AS opening_revenue FROM customers WHERE `type`='company'";
            $query_real_opening_invoice = mysqli_query($conn,$sql_real_opening_invoice);
            $result_real_opening_invoice = mysqli_fetch_array($query_real_opening_invoice);
            $real_opening_invoice = $result_real_opening_invoice['opening_revenue'];
            $real_opening_invoice = ($real_opening_invoice != NULL) ? $real_opening_invoice : 0;
        // PDC FOR OPENING RECEIPT
            $sql_pdc_opening = "SELECT sum(amount) AS opening_receipt_pdc FROM reciept WHERE type=1 AND pdate != '' AND STR_TO_DATE(pdate,'%d/%m/%Y') <= STR_TO_DATE('$tdate','%d/%m/%Y') AND STR_TO_DATE(clearance_date,'%d/%m/%Y') NOT BETWEEN STR_TO_DATE('$start_date','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y')";
            $query_pdc_opening = mysqli_query($conn,$sql_pdc_opening);
            $result_pdc_opening = mysqli_fetch_array($query_pdc_opening);
            $pdc_opening = $result_pdc_opening['opening_receipt_pdc'];
            $pdc_opening = ($pdc_opening != NULL) ? $pdc_opening : 0;
        // RECEIPT FOR OPENING BEFORE
            $sql_real_opening_receipt = "SELECT sum(amount) AS opening_receipt FROM reciept WHERE type=1 AND clearance_date != '' AND STR_TO_DATE(clearance_date,'%d/%m/%Y') < STR_TO_DATE('$fdate','%d/%m/%Y')";
            $query_real_opening_receipt = mysqli_query($conn,$sql_real_opening_receipt);
            $result_real_opening_receipt = mysqli_fetch_array($query_real_opening_receipt);
            $real_opening_receipt_before = $result_real_opening_receipt['opening_receipt'];
            $real_opening_receipt_before = ($real_opening_receipt_before != NULL) ? $real_opening_receipt_before : 0;
        // RECEIPT FOR OPENING NOW <------------BANK----------------->  
            $sql_real_opening_receipt_current = "SELECT sum(amount) AS opening_receipt_current FROM reciept WHERE type=1 AND clearance_date != '' AND STR_TO_DATE(clearance_date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y')";
            $query_real_opening_receipt_current = mysqli_query($conn,$sql_real_opening_receipt_current);
            $result_real_opening_receipt_current = mysqli_fetch_array($query_real_opening_receipt_current);
            $real_opening_receipt_current = $result_real_opening_receipt_current['opening_receipt_current'];
            $real_opening_receipt_current = ($real_opening_receipt_current != NULL) ? $real_opening_receipt_current : 0;
        // FINDING THE PENDING FOR REAL OPENING FOR CUSTOMERS    
            $real_pending_opening_current = ($real_opening_invoice)-($real_opening_receipt_before+$real_opening_receipt_current+$pdc_opening);
            $revenues[] = $real_opening_invoice;
            $received[] = $pdc_opening;
            $received[] = $real_opening_receipt_before;
            $received[] = $real_opening_receipt_current;
            $received[] = $real_pending_opening_current;
    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------   
    // BANK TYPE 2 MEANS ADVANCE
    
        // PDC FOR ADVANCE RECEIPT BEFORE
            $sql_pdc_advance_before = "SELECT sum(amount) AS advance_receipt_pdc_before FROM reciept WHERE type=2 AND pdate != '' AND STR_TO_DATE(pdate,'%d/%m/%Y') < STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE(clearance_date,'%d/%m/%Y') NOT BETWEEN STR_TO_DATE('$start_date','%d/%m/%Y') AND STR_TO_DATE('$fdate','%d/%m/%Y')";
            $query_pdc_advance_before = mysqli_query($conn,$sql_pdc_advance_before);
            $result_pdc_advance_before = mysqli_fetch_array($query_pdc_advance_before);
            $pdc_advance_before = $result_pdc_advance_before['advance_receipt_pdc_before'];
            $pdc_advance_before = ($pdc_advance_before != NULL) ? $pdc_advance_before : 0;
        // PDC FOR ADVANCE RECEIPT CURRENT
            $sql_pdc_advance_current = "SELECT sum(amount) AS advance_receipt_pdc_current FROM reciept WHERE type=2 AND pdate != '' AND STR_TO_DATE(pdate,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y') AND STR_TO_DATE(clearance_date,'%d/%m/%Y') NOT BETWEEN STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y')";
            $query_pdc_advance_current = mysqli_query($conn,$sql_pdc_advance_current);
            $result_pdc_advance_current = mysqli_fetch_array($query_pdc_advance_current);
            $pdc_advance_current = $result_pdc_advance_current['advance_receipt_pdc_current'];
            $pdc_advance_current = ($pdc_advance_current != NULL) ? $pdc_advance_current : 0;
        // RECEIPT FOR ADVANCE BEFORE
            $sql_advance_before = "SELECT sum(amount) AS advanceReceiptBefore FROM reciept WHERE type=2 AND clearance_date != '' AND STR_TO_DATE(clearance_date,'%d/%m/%Y') < STR_TO_DATE('$fdate','%d/%m/%Y')";
            $query_advance_before = mysqli_query($conn,$sql_advance_before);
            $result_advance_before = mysqli_fetch_array($query_advance_before);
            $advance_before = $result_advance_before['advanceReceiptBefore'];
            $advance_before = ($advance_before != NULL) ? $advance_before : 0;
        // RECEIPT FOR ADVANCE NOW <------------BANK----------------->  
            $sql_advance_current = "SELECT sum(amount) AS advanceReceiptCurrent FROM reciept WHERE type=2 AND clearance_date != '' AND STR_TO_DATE(clearance_date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y')";
            $query_advance_current = mysqli_query($conn,$sql_advance_current);
            $result_advance_current = mysqli_fetch_array($query_advance_current);
            $advance_current = $result_advance_current['advanceReceiptCurrent'];
            $advance_current = ($advance_current != NULL) ? $advance_current : 0;
        
        // FIND BALANCE IN ADVANCE RECEIPT BEFORE
            $sql_advance_balance_before = "SELECT sum(tbl.rcpAdvanceBefore)-sum(tbl.invAdvanceBefore) AS advBalanceBefore FROM (
                    SELECT reciept.amount as rcpAdvanceBefore,sum(reciept_invoice.amount) as invAdvanceBefore FROM reciept
                    LEFT JOIN
                    reciept_invoice ON reciept.id = reciept_invoice.reciept_id WHERE reciept.type='2' AND pdate != '' AND 
                    (STR_TO_DATE(reciept.pdate,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))
                    GROUP BY reciept_invoice.reciept_id
                    ) as tbl";
            $query_advance_balance_before = mysqli_query($conn,$sql_advance_balance_before);
            $result_advance_balance_before = mysqli_fetch_array($query_advance_balance_before);
            $advance_balance_before = $result_advance_balance_before['advBalanceBefore'];
            $advance_balance_before = ($advance_balance_before != NULL) ? $advance_balance_before : 0;
        // FIND BALANCE IN ADVANCE RECEIPT CURRENT
            $sql_advance_balance_current = "SELECT sum(tbl.rcpAdvanceCurrent)-sum(tbl.invAdvanceCurrent) AS advBalanceCurrent FROM (
                    SELECT reciept.amount as rcpAdvanceCurrent,sum(reciept_invoice.amount) as invAdvanceCurrent FROM reciept
                    LEFT JOIN
                    reciept_invoice ON reciept.id = reciept_invoice.reciept_id WHERE reciept.type='2' AND pdate != '' AND 
                    (STR_TO_DATE(reciept.pdate,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                    GROUP BY reciept_invoice.reciept_id
                    ) as tbl";
            $query_advance_balance_current = mysqli_query($conn,$sql_advance_balance_current);
            $result_advance_balance_current = mysqli_fetch_array($query_advance_balance_current);
            $advance_balance_current = $result_advance_balance_current['advBalanceCurrent'];
            $advance_balance_current = ($advance_balance_current != NULL) ? $advance_balance_current : 0;
            
    // BANK TYPE 'NULL' MEANS NORMAL RECEIPT
        // PDC FOR RECEIPT BEFORE
            $sql_pdc_before = "SELECT sum(amount) AS receipt_pdc_before FROM reciept WHERE type='' AND pdate != '' AND STR_TO_DATE(pdate,'%d/%m/%Y') < STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE(clearance_date,'%d/%m/%Y') NOT BETWEEN STR_TO_DATE('$start_date','%d/%m/%Y') AND STR_TO_DATE('$fdate','%d/%m/%Y')";
            $query_pdc_before = mysqli_query($conn,$sql_pdc_before);
            $result_pdc_before = mysqli_fetch_array($query_pdc_before);
            $pdc_before = $result_pdc_before['receipt_pdc_before'];
            $pdc_before = ($pdc_before != NULL) ? $pdc_before : 0;
        // PDC FOR RECEIPT CURRENT
            $sql_pdc_current = "SELECT sum(amount) AS receipt_pdc_current FROM reciept WHERE type='' AND pdate != '' AND STR_TO_DATE(pdate,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y') AND STR_TO_DATE(clearance_date,'%d/%m/%Y') NOT BETWEEN STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y')";
            $query_pdc_current = mysqli_query($conn,$sql_pdc_current);
            $result_pdc_current = mysqli_fetch_array($query_pdc_current);
            $pdc_current = $result_pdc_current['receipt_pdc_current'];
            $pdc_current = ($pdc_current != NULL) ? $pdc_current : 0;
        // RECEIPTED BEFORE
            $sql_receipt_before = "SELECT sum(amount) AS ReceiptBefore FROM reciept WHERE type='' AND clearance_date != '' AND STR_TO_DATE(clearance_date,'%d/%m/%Y') < STR_TO_DATE('$fdate','%d/%m/%Y')";
            $query_receipt_before = mysqli_query($conn,$sql_receipt_before);
            $result_receipt_before = mysqli_fetch_array($query_receipt_before);
            $receipt_before = $result_receipt_before['ReceiptBefore'];
            $receipt_before = ($receipt_before != NULL) ? $receipt_before : 0;
        // RECEIPT NOW <------------BANK----------------->  
            $sql_receipt_current = "SELECT sum(amount) AS ReceiptCurrent FROM reciept WHERE type='' AND clearance_date != '' AND STR_TO_DATE(clearance_date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y')";
            $query_receipt_current = mysqli_query($conn,$sql_receipt_current);
            $result_receipt_current = mysqli_fetch_array($query_receipt_current);
            $receipt_current = $result_receipt_current['ReceiptCurrent'];
            $receipt_current = ($receipt_current != NULL) ? $receipt_current : 0;
            
        // TO BE RECEIPTED BEFORE
            $sqlReceivablesBefore = "
                SELECT sum(amount) FROM `additionalRcp` WHERE STR_TO_DATE(`invoice_date`, '%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y') AND
                    STR_TO_DATE(`date`, '%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y')
             ";
            $queryReceivablesBefore = mysqli_query($conn,$sqlReceivablesBefore);
            $resultReceivablesBefore = mysqli_fetch_array($queryReceivablesBefore);
            $receivablesBefore = $resultReceivablesBefore['ReceivablesBefore'];
        
        // TO BE RECEIPTED CURRENT
            $sqlReceivablesCurrent = "
                SELECT sum(amount) FROM `additionalRcp` WHERE STR_TO_DATE(`invoice_date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND
                    STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
             ";
            $queryReceivablesCurrent = mysqli_query($conn,$sqlReceivablesCurrent);
            $resultReceivablesCurrent = mysqli_fetch_array($queryReceivablesCurrent);
            $receivablesCurrent = $resultReceivablesCurrent['ReceivablesCurrent'];
        
    // INVOICE SECTION
        // SALES DONE BEFORE
            $sql_sales_before = "SELECT sum(total) AS salesBefore FROM invoice WHERE date != '' AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate','%d/%m/%Y')";
            $query_sales_before = mysqli_query($conn,$sql_sales_before);
            $result_sales_before = mysqli_fetch_array($query_sales_before);
            $sales_before = $result_sales_before['salesBefore'];
            $sales_before = ($sales_before != NULL) ? $sales_before : 0;
        // SALES DONE CURRENT
            $sql_sales_current = "SELECT sum(total) AS salesCurrent FROM invoice WHERE date != '' AND STR_TO_DATE(date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y')";
            $query_sales_current = mysqli_query($conn,$sql_sales_current);
            $result_sales_current = mysqli_fetch_array($query_sales_current);
            $sales_current = $result_sales_current['salesCurrent'];
            $sales_current = ($sales_current != NULL) ? $sales_current : 0;
        
        

        
        
        
        
        
        
        
        
        
        
        
        
          
            
        $current_borrow = $real_opening_receipt_before;
        $current_opening = $real_opening_invoice; 
        ?>
        <?php ?>
        
        <!--<tr>-->
        <!--    <td></td>-->
        <!--    <td></td>-->
        <!--    <td><b>OPENING</b></td>-->
        <!--    <td align="right"><b><?php echo custom_money_format('%!i', $current_borrow);?></b></td>-->
        <!--    <td align="right"><b><?php echo custom_money_format('%!i', $current_opening);?></b></td>-->
        <!--</tr>-->

        <!--<tr>-->
        <!--    <td>1</td>-->
        <!--    <td>1001</td>-->
        <!--    <td><b>RECEIPTED FOR OPENING</b></td>-->
        <!--    <td align="right"><b><?php echo custom_money_format('%!i', $real_opening_receipt_current);?></b></td>-->
        <!--    <td align="right"><b></b></td>-->
        <!--</tr>-->
        <!--<tr>-->
        <!--    <td>2</td>-->
        <!--    <td>1002</td>-->
        <!--    <td><b>PDC IN OPENING</b></td>-->
        <!--    <td align="right"><b><?php echo custom_money_format('%!i', $pdc_opening);?></b></td>-->
        <!--    <td align="right"><b></b></td>-->
        <!--</tr>-->
        <!--<tr>-->
        <!--    <td>2</td>-->
        <!--    <td>1002</td>-->
        <!--    <td><b>PENDING IN OPENING</b></td>-->
        <!--    <td align="right"><b><?php echo custom_money_format('%!i', $real_pending_opening_current);?></b></td>-->
        <!--    <td align="right"><b></b></td>-->
        <!--</tr>-->
        
        
     
     
     
     <!--FOR SAMPLE-->
     
     
        <tr>
            <td>2</td>
            <td>1002</td>
            <td><b>OPENING INVOICE</b></td>
            <td align="right"><b></b></td>
            <td align="right"><b><?php echo custom_money_format('%!i', $real_opening_invoice);?></b></td>
        </tr>
        <tr>
            <td>2</td>
            <td>1002</td>
            <td><b>SALES BEFORE</b></td>
            <td align="right"><b></b></td>
            <td align="right"><b><?php echo custom_money_format('%!i', $sales_before);?></b></td>
        </tr>
        <tr>
            <td>2</td>
            <td>1002</td>
            <td><b>SALES NOW</b></td>
            <td align="right"><b></b></td>
            <td align="right"><b><?php echo custom_money_format('%!i', $sales_current);?></b></td>
        </tr>
        
     
     
     
     
     
        
        
        
        
        
        
        <!--<tr>-->
        <!--    <td><b></b></td>-->
        <!--    <td></td>-->
        <!--    <td></td>-->
        <!--    <td><b><?php echo custom_money_format('%!i', array_sum($received));?></b></td>-->
        <!--    <td><b><?php echo custom_money_format('%!i', array_sum($revenues));?></b></td>-->
        <!--</tr>-->



            
        </tbody>
      </table>

<?php
if(isset($_POST['print'])) { ?>
    <body onload="window.print()">
<?php } ?>