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

$start_date = '01/01/2015';
$fdate = '01/01/2018';
$tdate = '31/12/2018';
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
            $sql_find_opening = "SELECT sum(amount) AS pendingInPrevious FROM `additionalRcp` WHERE
                                 STR_TO_DATE(`invoice_date`, '%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(`invoice_date`, '%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y') AND
                                 STR_TO_DATE(`date`, '%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(date, '%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y')
                                 AND `invoice_date` != '' AND `date` != ''
                                 ";
            $query_find_opening = mysqli_query($conn,$sql_find_opening);
            $result_find_opening = mysqli_fetch_array($query_find_opening);
            $opening = $result_find_opening['pendingInPrevious'];
            $tot_credit[] = $opening;
            $tot_debit[] = 0;
        ?>
        
        <?php
            $sql_current_sale = "SELECT sum(amount) AS currentSale FROM `additionalRcp` WHERE
                                `section`='INV' AND
                                STR_TO_DATE(`invoice_date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                                AND `invoice_date` != ''
                                ";
            $query_current_sale = mysqli_query($conn,$sql_current_sale);
            $result_current_sale = mysqli_fetch_array($query_current_sale);
            $currentSale = $result_current_sale['currentSale'];
            $tot_credit[] = $currentSale;
            $tot_debit[] = 0;
        ?>
        
        <?php
            $sql_in_bank = "SELECT sum(amount) AS currentInBank FROM `additionalRcp` WHERE
                            `section`='RCP' AND
                            STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                            AND `invoice_date` != '' AND `date` != ''
                           ";
            $query_in_bank = mysqli_query($conn,$sql_in_bank);
            $result_in_bank = mysqli_fetch_array($query_in_bank);
            $currentInBank = $result_in_bank['currentInBank'];
            $currentInBank = -($currentInBank);
            $tot_credit[] = 0;
            $tot_debit[] = $currentInBank;
        ?>
        
        <?php
            $sql_sundry_debtors = "SELECT sum(oldReceivables) AS sundryDebtors FROM (
                            (SELECT sum(amount) AS oldReceivables FROM `additionalRcp` WHERE
                            STR_TO_DATE(`invoice_date`, '%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(`invoice_date`, '%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y')
                            AND `invoice_date`!='')
                            UNION ALL
                            (SELECT sum(-amount) AS oldReceivables FROM `additionalRcp` WHERE
                            STR_TO_DATE(`invoice_date`, '%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(`invoice_date`, '%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y')
                            AND `invoice_date`!='' AND `date`='')
                            )result
                          ";
            $query_sundry_debtors = mysqli_query($conn,$sql_sundry_debtors);
            $result_sundry_debtors = mysqli_fetch_array($query_sundry_debtors);
            $sundryDebtors = $result_sundry_debtors['sundryDebtors'];
            $tot_credit[] = 0;
            $tot_debit[] = $sundryDebtors;
        ?>
        
        <?php
            $sqlReceivables = "SELECT sum(amount) AS Receivables FROM `additionalRcp`
                                WHERE STR_TO_DATE(`invoice_date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                                AND STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                                AND `invoice_date`!='' AND `date`!=''
                                ";
            $queryReceivables = mysqli_query($conn,$sqlReceivables);
            $resultReceivables = mysqli_fetch_array($queryReceivables);
            $Receivables = $resultReceivables['Receivables'];
            $tot_credit[] = 0;
            $tot_debit[] = $Receivables;
            
            $sqlPdc = "SELECT sum(-amount) AS pdcAmount FROM `additionalRcp` WHERE
                        `section`='RCP' AND
                        STR_TO_DATE(`invoice_date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND 
                        `invoice_date`!='' AND `date`=''";
            $queryPdc = mysqli_query($conn,$sqlPdc);
            $resultPdc = mysqli_fetch_array($queryPdc);
            $pdcInHand = $resultPdc['pdcAmount'];
            $tot_credit[] = 0;
            $tot_debit[] = $pdcInHand;
            
            //------------------------
            $sql_advance = "SELECT sum(grand) AS total FROM `reciept` WHERE `type` != '1' AND STR_TO_DATE(`clearance_date`, '%d/%m/%Y')
                            BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
            $queryAdvance = mysqli_query($conn,$sql_advance);
            $resultAdvance = mysqli_fetch_array($queryAdvance);
            $totalRcp = $resultAdvance['total'];                
                            
            $sql_additional = "SELECT sum(-amount) AS total FROM `additionalRcp` WHERE section = 'RCP' AND STR_TO_DATE(`date`, '%d/%m/%Y')
                                BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
            $query_additional = mysqli_query($conn,$sql_additional);
            $result_additional = mysqli_fetch_array($query_additional);
            $totalAddRcp = $result_additional['total'];
            
            $advanceBalance = $totalRcp-$totalAddRcp;
            
            $tot_credit[] = $advanceBalance;
            $tot_debit[] = 0;
            //-------------------------------
            
            // $sql = "SELECT sum(tbl.total) AS in_advance FROM (
            //         SELECT reciept.grand as grand,sum(reciept_invoice.total) as total
            //         FROM reciept INNER JOIN reciept_invoice ON reciept.id = reciept_invoice.reciept_id
            //         WHERE reciept.type='2' AND
            //         STR_TO_DATE(reciept.clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')
            //         GROUP BY reciept_invoice.reciept_id
            //         ) as tbl";
        ?>
        
                <tr>
                  <td><b></b></td>
                  <td><b></td>
                  <td style="font-size:18px;">OPENING</td>
                      <td></td>
                      <td style="font-size:18px;"><?php echo custom_money_format('%!i', $opening);?></td>
                </tr>
                
                <tr>
                  <td><b></b></td>
                  <td><b></td>
                  <td style="font-size:18px;">CURRENT SALE</td>
                      <td></td>
                      <td style="font-size:18px;"><?php echo custom_money_format('%!i', $currentSale);?></td>
                </tr>
                
                <tr>
                  <td><b></b></td>
                  <td><b></td>
                  <td style="font-size:18px;">ADVANCE BALANCE</td>
                      <td></td>
                      <td style="font-size:18px;"><?php echo custom_money_format('%!i', $advanceBalance);?></td>
                </tr>
                
                <tr>
                  <td><b></b></td>
                  <td><b></td>
                  <td style="font-size:18px;">IN BANK</td>
                    <td style="font-size:18px;"><?php echo custom_money_format('%!i', $currentInBank);?></td>
                    <td></td>
                </tr>
                
                <tr>
                  <td><b></b></td>
                  <td><b></td>
                  <td style="font-size:18px;">SUNDRY DEBTORS</td>
                    <td style="font-size:18px;"><?php echo custom_money_format('%!i', $sundryDebtors);?></td>
                    <td></td>
                </tr>
                
                <tr>
                  <td><b></b></td>
                  <td><b></td>
                  <td style="font-size:18px;">RECEIVABLES</td>
                    <td style="font-size:18px;"><?php echo custom_money_format('%!i', $Receivables);?></td>
                    <td></td>
                </tr>
                
                <tr>
                  <td><b></b></td>
                  <td><b></td>
                  <td style="font-size:18px;">PDC IN HAND</td>
                    <td style="font-size:18px;"><?php echo custom_money_format('%!i', $pdcInHand);?></td>
                    <td></td>
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