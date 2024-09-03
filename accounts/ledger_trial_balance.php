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
// $fdate = '01/01/2023';
// $tdate = '31/12/2023';

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
<h2 style="text-align:center;margin-bottom:1px;"><?php // echo strtoupper($status);?>LEDGER TRIAL BALANCE</h2>
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
            $sql_opening_sale = "SELECT sum(op_bal) AS total_openingInvoice FROM `customers` WHERE `type`='Company' AND
                                 STR_TO_DATE(`op_date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query_opening_sale = mysqli_query($conn,$sql_opening_sale);
            $result_opening_sale = mysqli_fetch_array($query_opening_sale);
            $openingSale = $result_opening_sale['total_openingInvoice'];
            $tot_credit[] = $openingSale;
            $tot_debit[] = 0;
        
            $sql_current_sale = "SELECT sum(total) AS currentSale,sum(vat) AS currentVat,sum(transport) AS currentTrans FROM `invoice` WHERE
                                STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')
                                AND `date` != ''";
            $query_current_sale = mysqli_query($conn,$sql_current_sale);
            $result_current_sale = mysqli_fetch_array($query_current_sale);
            $currentSale = $result_current_sale['currentSale'];
                $currentVat = $result_current_sale['currentVat'];
                $currentTrans = $result_current_sale['currentTrans'];
            $tot_credit[] = $currentSale;
                $tot_credit[] = $currentVat;
                $tot_credit[] = $currentTrans;
                
            $tot_debit[] = 0;
        ?>
        
        <?php
            $sql_in_bank = "SELECT sum(currentInBank) AS currentInBank FROM (
                            (SELECT sum(amount) AS currentInBank FROM `reciept` WHERE
                            STR_TO_DATE(`clearance_date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') AND
                            `pdate`!='' AND `clearance_date` != '')
                            )result";
            $query_in_bank = mysqli_query($conn,$sql_in_bank);
            $result_in_bank = mysqli_fetch_array($query_in_bank);
            $currentInBank = $result_in_bank['currentInBank'];
            $tot_credit[] = 0;
            $tot_debit[] = $currentInBank;
            
            $sqlPdc = "SELECT sum(amount) AS pdcAmount FROM `reciept` WHERE
                        STR_TO_DATE(`pdate`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') AND
                        STR_TO_DATE(`clearance_date`, '%d/%m/%Y') NOT BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') AND
                        `pdate`!=''";
            $queryPdc = mysqli_query($conn,$sqlPdc);
            $resultPdc = mysqli_fetch_array($queryPdc);
            $pdcInHand = $resultPdc['pdcAmount'];
            $tot_credit[] = 0;
            $tot_debit[] = $pdcInHand;
            
            $sqlDis = "SELECT sum(discount) AS disAmount FROM `reciept` WHERE
                        STR_TO_DATE(`pdate`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') AND
                        `pdate`!=''";
            $queryDis = mysqli_query($conn,$sqlDis);
            $resultDis = mysqli_fetch_array($queryDis);
            $discountGiven = $resultDis['disAmount'];
            $tot_credit[] = 0;
            $tot_debit[] = $discountGiven;
            
            $sql_in_credit = "SELECT sum(total) AS currentInCredit FROM `credit_note` WHERE
                            STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') AND
                            `date` != ''";
            $query_in_credit = mysqli_query($conn,$sql_in_credit);
            $result_in_credit = mysqli_fetch_array($query_in_credit);
            $currentInCredit = $result_in_credit['currentInCredit'];
            $tot_credit[] = 0;
            $tot_debit[] = $currentInCredit;
        ?>
        
        <!--CHECK WHETHER COMPANY HAVE LIABILITY OR RECEIVABLES-->
        <?php
        
            $sql_check_receivable = "
                    SELECT id,amount FROM (
                        SELECT CONCAT('INO', id) AS id, sum(op_bal) AS amount FROM `customers` WHERE `type`='Company' AND STR_TO_DATE(`op_date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')
                        UNION ALL
                        SELECT CONCAT('INV', id) AS id, sum(grand) AS amount FROM `invoice` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')
                        UNION ALL
                        SELECT CONCAT('RCP', id) AS id, sum(grand) AS amount FROM `reciept` WHERE STR_TO_DATE(`pdate`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') AND `pdate`!=''
                        UNION ALL
                        SELECT CONCAT('CNT', id) AS id, sum(total) AS amount FROM `credit_note` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')
                        UNION ALL
                        SELECT CONCAT('RFD', id) AS id, ROUND(SUM(amount), 2) AS amount FROM `refund` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')
                    ) result";
            $query_check_receivable = mysqli_query($conn,$sql_check_receivable);
            $cr = $dr = 0;
            while($result_check_receivable = mysqli_fetch_array($query_check_receivable)) {
                
                $id = $result_check_receivable['id'];
                $receivable = $result_check_receivable['amount'];
                
                if (substr($id, 0, 3) === 'INO')
                {
                    $cr = $cr + $receivable;
                } else if(substr($id, 0, 3) === 'INV') {
                    $cr = $cr + $receivable;
                } else if (substr($id, 0, 3) === 'RCP') {
                    $dr = $dr + $receivable;
                } else if (substr($id, 0, 3) === 'CNT') {
                    $dr = $dr + $receivable;
                } else if (substr($id, 0, 3) === 'RFD') {
                    // here is confusion for developer hari
                    // $cr = $cr + $receivable;
                }
                $balance = $cr-$dr;
                if($balance > 0) {
                    $receivables = $balance;
                    $liability = 0;
                } else {
                    $liability = $balance;
                    $receivables = 0;
                }
            }
            
            $tot_credit[] = $liability;
            $tot_debit[] = $receivables;
            
        
        ?>
        
                <tr>
                  <td><b></b></td>
                  <td><b></td>
                  <td style="font-size:18px;">SALES</td>
                      <td></td>
                      <td style="font-size:18px;"><?php echo custom_money_format('%!i', $openingSale);?></td>
                </tr>
                <tr>
                  <td><b></b></td>
                  <td><b></td>
                  <td style="font-size:18px;">SALES</td>
                      <td></td>
                      <td style="font-size:18px;"><?php echo custom_money_format('%!i', $currentSale);?></td>
                </tr>
                <tr>
                  <td><b></b></td>
                  <td><b></td>
                  <td style="font-size:18px;">VAT</td>
                      <td></td>
                      <td style="font-size:18px;"><?php echo custom_money_format('%!i', $currentVat);?></td>
                </tr>
                <tr>
                  <td><b></b></td>
                  <td><b></td>
                  <td style="font-size:18px;">TRANSPORT</td>
                      <td></td>
                      <td style="font-size:18px;"><?php echo custom_money_format('%!i', $currentTrans);?></td>
                </tr>
                
                <tr>
                  <td><b></b></td>
                  <td><b></td>
                  <td style="font-size:18px;">BANK</td>
                    <td style="font-size:18px;"><?php echo custom_money_format('%!i', $currentInBank);?></td>
                    <td></td>
                </tr>
                
                <tr>
                  <td><b></b></td>
                  <td><b></td>
                  <td style="font-size:18px;">PDC</td>
                      <td style="font-size:18px;"><?php echo custom_money_format('%!i', $pdcInHand);?></td>
                      <td></td>
                </tr>
                <tr>
                  <td><b></b></td>
                  <td><b></td>
                  <td style="font-size:18px;">DISCOUNT</td>
                      <td style="font-size:18px;"><?php echo custom_money_format('%!i', $discountGiven);?></td>
                      <td></td>
                </tr>
                
                
                <tr>
                  <td><b></b></td>
                  <td><b></td>
                  <td style="font-size:18px;">CREDITED</td>
                      <td style="font-size:18px;"><?php echo custom_money_format('%!i', $currentInCredit);?></td>
                      <td></td>
                </tr>
                
                <tr>
                  <td><b></b></td>
                  <td><b></td>
                  <td style="font-size:18px;">RECEIVABLES</td>
                    <td style="font-size:18px;"><?php echo custom_money_format('%!i', $receivables);?></td>
                    <td></td>
                </tr>
                
                <tr>
                  <td><b></b></td>
                  <td><b></td>
                  <td style="font-size:18px;">LIABILITY</td>
                    <td></td>
                    <td style="font-size:18px;"><?php echo custom_money_format('%!i', $liability);?></td>
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