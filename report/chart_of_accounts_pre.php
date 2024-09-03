<?php
  include "../config.php";
  error_reporting(0);
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
$tdate = $_POST['tdate'];
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
<h2 style="text-align:center;margin-bottom:1px;"><?php echo strtoupper($status);?>CHART OF ACCOUNTS</h2>
<table align="center" style="width:90%;">
     <tr>
          <!--<td width="50%">-->
          <!--     <span style="font-size:15px;">Customer: <?php echo $cust;?><br>Salesman: <?php echo $rep;?></span>-->
          <!--</td>-->
          <td width="50%" style="text-align:right"><span style="font-size:15px;"> Date: <?php if ($fdate==''){echo "Since Inception";} else{echo $fdate;}?> - <?php echo $tdate;?></span></td>
     </tr>     
</table>
 
 
 
<table id="tbl1" align="center" style="width:90%;">
        <thead>
          <tr>
              <th>
                  Sl No
              </th>
              <th>
                  Particular
              </th>
              <th>
                  Amount
              </th>
          </tr>
        </thead>
        <tbody style="font-size:18px;">
        
        <?php
            $sql_receipt_amt = "SELECT sum(amount) as receipt_amt FROM `reciept` WHERE status = 'Cleared' AND STR_TO_DATE(pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
            $query_receipt_amt = mysqli_query($conn,$sql_receipt_amt);
            $result_receipt_amt = mysqli_fetch_array($query_receipt_amt);
            $receipt_amt_cleared = $result_receipt_amt['receipt_amt'];
            
            $sql_receipt_amt = "SELECT sum(amount) as receipt_amt FROM `reciept` WHERE status = 'Uncleared' AND STR_TO_DATE(pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
            $query_receipt_amt = mysqli_query($conn,$sql_receipt_amt);
            $result_receipt_amt = mysqli_fetch_array($query_receipt_amt);
            $receipt_amt_uncleared = $result_receipt_amt['receipt_amt'];
            
            
            $sql_invoice = "SELECT sum(grand) AS grand,sum(transport) AS transport FROM invoice WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
            $query_invoice = mysqli_query($conn,$sql_invoice);
            $result_invoice = mysqli_fetch_array($query_invoice);
            $invoice_amt = $result_invoice['grand'];
            $invoice_trans = $result_invoice['transport'];
            $invoice_total = $invoice_amt-$invoice_trans;
            
            $sql_msc = "SELECT sum(total) as msc_amt FROM miscellaneous WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')"; 
            $query_msc = mysqli_query($conn,$sql_msc);
            $result_msc = mysqli_fetch_array($query_msc);
            $msc_amt = $result_msc['msc_amt'];
        ?>
            
            <tr>
              <td><b>1</b></td>
              <td style="font-size:18px;"><b>Current assets</b></td>
              <td></td>
            </tr>
                <tr>
                  <td></td>
                  <td>Cash and cash equivalents</td>
                  <td><?php echo custom_money_format('%!i', $receipt_amt_cleared);?></td>
                </tr>
                <tr>
                  <td></td>
                  <td>Accounts receivable</td>
                  <td><?php echo custom_money_format('%!i', $receipt_amt_uncleared);?></td>
                </tr>
            <tr>
              <td><b>2</b></td>
              <td style="font-size:18px;"><b>Long term assets</b></td>
              <td>0.00</td>
            </tr>
            <tr>
              <td><b>3</b></td>
              <td style="font-size:18px;"><b>Current liabilities</b></td>
              <td>0.00</td>
            </tr>
            <tr>
              <td><b>4</b></td>
              <td style="font-size:18px;"><b>Long term liabilities</b></td>
              <td>0.00</td>
            </tr>
            <tr>
              <td><b>5</b></td>
              <td style="font-size:18px;"><b>Equity</b></td>
              <td>0.00</td>
            </tr>
            <tr>
              <td><b>6</b></td>
              <td style="font-size:18px;"><b>Income</b></td>
              <td></td>
            </tr>
                <!--<tr>-->
                <!--  <td></td>-->
                <!--  <td>Cash Income</td>-->
                <!--  <td>0.00</td>-->
                <!--</tr>-->
                <!--<tr>-->
                <!--  <td></td>-->
                <!--  <td>Income through cheque</td>-->
                <!--  <td>0.00</td>-->
                <!--</tr>-->
                <tr>
                    <td></td>
                    <td>Income from Revenue</td>
                    <td><?php echo custom_money_format('%!i', $invoice_total);?></td>
                </tr>
                <tr>
                  <td></td>
                  <td>Transportation Income</td>
                  <td><?php echo custom_money_format('%!i', $invoice_trans);?></td>
                </tr>
                <tr>
                  <td></td>
                  <td>Miscellaneous Income</td>
                  <td><?php echo custom_money_format('%!i', $msc_amt);?></td>
                </tr>
            <tr>
              <td><b>7</b></td>
              <td style="font-size:18px;"><b>Cost of sales</b></td>
              <td>0.00</td>
            </tr>
            <tr>
              <td><b>8</b></td>
              <td style="font-size:18px;"><b>Expenses</b></td>
              <td>0.00</td>
            </tr>
            
        </tbody>
      </table>
 
 
 
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>