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
$consider_opening = $_POST['consider_opening'];
$consider_opening = ($consider_opening != NULL) ? $consider_opening : 0;


// finding receivables OLD

    // $sqlReceivables = "SELECT `invoice`,`amount` FROM `additionalRcp` WHERE `section` = 'INV'
    //                 AND STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
    // $queryReceivables = mysqli_query($conn,$sqlReceivables);
    // $receivables=0;
    // while($resultReceivables = mysqli_fetch_array($queryReceivables)) {
        
    //     $invoice = $resultReceivables['invoice'];
    //     $invoicedAmount = $resultReceivables['amount'];
    //     $invoicedAmount = ($invoicedAmount != NULL) ? $invoicedAmount : 0;
        
    //         $sqlReceipt = "SELECT sum(amount) AS receiptedAmount FROM `additionalRcp` WHERE `section` != 'INV' AND `invoice` = '$invoice'
    //                       AND STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
    //         $queryReceipt = mysqli_query($conn,$sqlReceipt);
    //         $resultReceipt = mysqli_fetch_array($queryReceipt);
    //         $receiptedAmount = $resultReceipt['receiptedAmount'];
    //         $receiptedAmount = ($receiptedAmount != NULL) ? $receiptedAmount : 0;
            
    //     $receivables = ($receivables+$invoicedAmount+$receiptedAmount);
    // }

// finding receivables NEW

    // $sqlReceivables = "
    // SELECT sum(invoicedAmount)+sum(receiptedAmount) AS Receivables FROM
    // (SELECT
    //     a1.`invoice`,
    //     SUM(CASE WHEN a2.`section` = 'INV' THEN a2.`amount` ELSE 0 END) AS invoicedAmount,
    //     SUM(CASE WHEN a2.`section` != 'INV' THEN a2.`amount` ELSE 0 END) AS receiptedAmount
    // FROM
    //     `additionalRcp` a1
    // LEFT JOIN
    //     `additionalRcp` a2 ON a1.`invoice` = a2.`invoice`
    // WHERE
    //     a1.`section` = 'INV'
    //     AND STR_TO_DATE(a1.`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
    //  	AND STR_TO_DATE(a2.`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
    // GROUP BY
    //     a1.`invoice`
    //  ) result
    //  ";

// finding receivables NEW NEW 
    // $sqlReceivables = "SELECT sum(amount) AS Receivables FROM `additionalRcp`
    //     WHERE STR_TO_DATE(`invoice_date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
    //     AND STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')"; 
        
    // $queryReceivables = mysqli_query($conn,$sqlReceivables);
    // $resultReceivables = mysqli_fetch_array($queryReceivables);
    // $receivables = $resultReceivables['Receivables']; 
     
     
            $sql_check_receivable = "
                    SELECT id,amount FROM (
                        SELECT CONCAT('INO', id) AS id, sum(op_bal) AS amount FROM `customers` WHERE `type`='Company' AND STR_TO_DATE(`op_date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                        UNION ALL
                        SELECT CONCAT('INV', id) AS id, sum(grand) AS amount FROM `invoice` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                        UNION ALL
                        SELECT CONCAT('RCP', id) AS id, sum(grand) AS amount FROM `reciept` WHERE STR_TO_DATE(`pdate`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND `pdate`!=''
                        UNION ALL
                        SELECT CONCAT('CNT', id) AS id, sum(total) AS amount FROM `credit_note` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
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
                }
                $balance = $cr-$dr;
                if($balance > 0) {
                    $receivables = $balance;
                    $liability = 0;
                } else {
                    $liability = ABS($balance);
                    $receivables = 0;
                }
            }

// finding PDC in Hand

    $sqlPdc = "SELECT sum(amount) AS pdcAmount FROM `reciept` WHERE
              STR_TO_DATE(`pdate`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
              AND STR_TO_DATE(`clearance_date`, '%d/%m/%Y') NOT BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
    $queryPdc = mysqli_query($conn,$sqlPdc);
    $resultPdc = mysqli_fetch_array($queryPdc);
    $pdcInHand = $resultPdc['pdcAmount'];
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
                  Amount
              </th>
          </tr>
        </thead>
        <tbody style="font-size:18px;">
            
        <?php
            $sql_main_cat = "SELECT * FROM `expense_main_categories`";
            $query_main_cat = mysqli_query($conn,$sql_main_cat);
            $cnt = $tot_main = 0;
            while($result_main_cat = mysqli_fetch_array($query_main_cat)) {
            $cnt++;
            $main_cat = $result_main_cat['id'];
            $mainUid = $result_main_cat['uid'];
            $main_cat_name = $result_main_cat['tag'];
            
                // $sql_calc = "SELECT id as cat FROM `expense_categories` WHERE `type` = '$main_cat'";
                // $query_calc = mysqli_query($conn,$sql_calc);
                // while($result_calc = mysqli_fetch_array($query_calc)) {
                //     $categ = $result_calc['cat'];
                //     $sql_amt_main = "SELECT sum(db_amt)-sum(cd_amt) AS amount FROM (
                //                     (SELECT sum(amount) as cd_amt, '' as db_amt FROM `jv_items` WHERE `type` = 'credit' AND `cat` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                //                     UNION ALL
                //                     (SELECT '' as cd_amt, sum(amount) as db_amt FROM `jv_items` WHERE `type` = 'debit' AND `cat` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                //                     UNION ALL
                //                     (SELECT '' as cd_amt, sum(grand) as db_amt FROM `pv` WHERE `category` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                //                     UNION ALL
                //                     (SELECT '' as cd_amt, sum(amount) as db_amt FROM `additionalAcc` WHERE `cat` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                //                     UNION ALL
                //                     (SELECT '' as cd_amt, sum(amount) as db_amt FROM `reciept` WHERE `cat` = '$categ' AND STR_TO_DATE(pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                    
                //                     UNION ALL
                //                     (SELECT '' as cd_amt, sum(total) as db_amt FROM `invoice` WHERE `cat` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))";
                
                // if($consider_opening == 1) {
                //     $sql_amt_main .= "UNION ALL (SELECT sum(op_bal) as cd_amt, '' as db_amt FROM `expense_subcategories` WHERE `parent` = '$categ')";
                // }
                //     $sql_amt_main .= ") result";

                //         $query_amt_main = mysqli_query($conn,$sql_amt_main);
                //         $result_amt_main = mysqli_fetch_array($query_amt_main);
                //         $main_amt = $result_amt_main['amount'];
                //         $main_amt = ($main_amt != NULL) ? $main_amt : 0;
                // $tot_main = $tot_main + $main_amt;
                // }
        ?>
            <tr>
              <td><b><?php echo $cnt;?></b></td>
              <td><b><?php echo $mainUid;?></b></td>
              <td style="font-size:18px;"><b><?php echo $main_cat_name;?></b></td>
              <td><b><?php // echo custom_money_format('%!i', $tot_main);$tot_main=0;?></b></td>
            </tr>
            
            <?php 
                $sql_cat = "SELECT * FROM `expense_categories` WHERE `type`='$main_cat' ORDER BY `uid`";
                $query_cat = mysqli_query($conn,$sql_cat);
                while($result_cat = mysqli_fetch_array($query_cat)) {
                    $cat = $result_cat['id'];
                    $catUid = $result_cat['uid'];
                    $cat_name = $result_cat['tag'];
                    $entry_type = $result_cat['entry'];
                    
                        $sql_amt_cat = "SELECT sum(db_amt)-sum(cd_amt) AS amount FROM (
                                    (SELECT sum(amount) as cd_amt, '' as db_amt FROM `jv_items` WHERE `type` = 'credit' AND `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    UNION ALL
                                    (SELECT '' as cd_amt, sum(amount) as db_amt FROM `jv_items` WHERE `type` = 'debit' AND `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    UNION ALL
                                    (SELECT '' as cd_amt, sum(grand) as db_amt FROM `pv` WHERE `category` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT sum(grand) as cd_amt, '' as db_amt FROM `pv` WHERE `bank` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT sum(amount) as cd_amt, '' as db_amt FROM `additionalAcc` WHERE `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    UNION ALL
                                    (SELECT '' as cd_amt, sum(amount) as db_amt FROM `reciept` WHERE `cat` = '$cat' AND STR_TO_DATE(clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT sum(total) as cd_amt, '' as db_amt FROM `invoice` WHERE `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    ";
                    
                    
                    if($consider_opening == 1) {
                        $sql_amt_cat .= "UNION ALL (SELECT '' as cd_amt, sum(op_bal) as db_amt FROM `expense_subcategories` WHERE `parent` = '$cat')";
                    }
                        $sql_amt_cat .= ") result";

                        $query_amt_cat = mysqli_query($conn,$sql_amt_cat);
                        $result_amt_cat = mysqli_fetch_array($query_amt_cat);
                        $cat_amt = $result_amt_cat['amount'];
                            if($entry_type == 'Cr') {$cat_amt = -($cat_amt);}
                            if($cat == '43') {$cat_amt = -($cat_amt);}
                        $cat_amt = ($cat_amt != NULL) ? $cat_amt : 0;
                        if($cat == '65') {
                            $cat_amt = $cat_amt+$receivables;
                        } else if($cat == '66') {
                            $cat_amt = $cat_amt+$pdcInHand;
                        } else if($cat == '72') {
                            $cat_amt = $cat_amt+$liability;
                        }
            ?>
            
                <tr>
                  <td><b></b></td>
                  <td><b><?php echo $catUid;?></b></td>
                  <td style="font-size:18px;"><?php echo $cat_name;?></td>
                  <td style="font-size:18px;"><?php echo custom_money_format('%!i', $cat_amt);?></td>
                </tr>
                
                
                <?php 
                    $sql_sub_cat = "SELECT * FROM `expense_subcategories` WHERE `parent`='$cat' ORDER BY `uid`";
                    $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
                    while($result_sub_cat = mysqli_fetch_array($query_sub_cat)) {
                        $sub_cat = $result_sub_cat['id'];
                        $subcatUid = $result_sub_cat['uid'];
                        $sub_cat_name = $result_sub_cat['category'];
                        
                            $sql_amt_sub = "SELECT sum(db_amt)-sum(cd_amt) AS amount FROM (
                                        (SELECT sum(amount) as cd_amt, '' as db_amt FROM `jv_items` WHERE `type` = 'credit' AND `sub` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        UNION ALL
                                        (SELECT '' as cd_amt, sum(amount) as db_amt FROM `jv_items` WHERE `type` = 'debit' AND `sub` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        UNION ALL
                                        (SELECT '' as cd_amt, sum(grand) as db_amt FROM `pv` WHERE `subcategory` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        
                                        UNION ALL
                                        (SELECT sum(grand) as cd_amt, '' as db_amt FROM `pv` WHERE `inward` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        
                                        UNION ALL
                                        (SELECT sum(amount) as cd_amt, '' as db_amt FROM `additionalAcc` WHERE `sub` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        UNION ALL
                                        (SELECT '' as cd_amt, sum(amount) as db_amt FROM `reciept` WHERE `inward` = '$sub_cat' AND STR_TO_DATE(clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                                        UNION ALL
                                        (SELECT sum(total) as cd_amt, '' as db_amt FROM `invoice` WHERE `sub` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))";
                        
                        if($consider_opening == 1) {
                            $sql_amt_sub .= "UNION ALL (SELECT '' as cd_amt, sum(op_bal) as db_amt FROM `expense_subcategories` WHERE `id` = '$sub_cat')";
                        }
                            $sql_amt_sub .= ") result";

                            $query_amt_sub = mysqli_query($conn,$sql_amt_sub);
                            $result_amt_sub = mysqli_fetch_array($query_amt_sub);
                            $sub_amt = $result_amt_sub['amount'];
                                if($entry_type == 'Cr') {$sub_amt = -($sub_amt);}
                            $sub_amt = ($sub_amt != NULL) ? $sub_amt : 0;
                ?>
                
                    <tr>
                      <td><b></b></td>
                      <td><b><?php echo $subcatUid;?></b></td>
                      <td style="font-size:18px;color: #bd0000;">&emsp;-> <?php echo $sub_cat_name;?></td>
                      <td style="font-size:18px;"><?php echo custom_money_format('%!i', $sub_amt);?></td>
                    </tr>
                    
                
                
                <?php } ?>
                
            <?php } ?>
            
        <?php } ?>

            
        </tbody>
      </table>

<?php
if(isset($_POST['print'])) { ?>
    <body onload="window.print()">
<?php } ?>