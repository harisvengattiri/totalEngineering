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


$dateTime = DateTime::createFromFormat('d/m/Y', $tdate);
$year = $dateTime->format('Y');

// $start_date = '01/01/2018';
// $fdate = '01/01/2023';
// $tdate = '30/09/2023';




// finding RECEIVABLE OR LIABILITY
            $sql_check_receivable = "
                    SELECT id,amount FROM (
                        SELECT CONCAT('INO', id) AS id, sum(op_bal) AS amount FROM `customers` WHERE `type`='Company' AND STR_TO_DATE(`op_date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                        UNION ALL
                        SELECT CONCAT('INV', id) AS id, sum(grand) AS amount FROM `invoice` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                        UNION ALL
                        SELECT CONCAT('RCP', id) AS id, sum(grand) AS amount FROM `reciept` WHERE STR_TO_DATE(`pdate`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND `pdate`!=''
                        UNION ALL
                        SELECT CONCAT('CNT', id) AS id, sum(total) AS amount FROM `credit_note` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                        UNION ALL
                        SELECT CONCAT('RFD', id) AS id, sum(amount) AS amount FROM `refund` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                    ) result";
            $query_check_receivable = mysqli_query($conn,$sql_check_receivable);
            $cr = $dr = 0;
            while($result_check_receivable = mysqli_fetch_array($query_check_receivable)) {
                
                $id = $result_check_receivable['id'];
                $receivable = $result_check_receivable['amount'];
                
                if (substr($id, 0, 3) === 'INO') {
                    $cr = $cr + $receivable;
                } else if(substr($id, 0, 3) === 'INV') {
                    $cr = $cr + $receivable;
                } else if (substr($id, 0, 3) === 'RCP') {
                    $dr = $dr + $receivable;
                } else if (substr($id, 0, 3) === 'CNT') {
                    $dr = $dr + $receivable;
                } else if (substr($id, 0, 3) === 'RFD') {
                    $cr = $cr + $receivable;
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
<h2 style="text-align:center;margin-bottom:1px;"><?php // echo strtoupper($status);?>TRIAL BALANCE</h2>
<table align="center" style="width:90%;">
     <tr>
          <!--<td width="50%">-->
          <!--     <span style="font-size:15px;">Customer: <?php // echo $cust;?><br>Salesman: <?php // echo $rep;?></span>-->
          <!--</td>-->
          <td width="50%" style="text-align:right"><span style="font-size:15px;"> Date: <?php echo $fdate;?> - <?php echo $tdate;?></span></td>
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
            $sql_main_cat = "SELECT * FROM `expense_main_categories`";
            $query_main_cat = mysqli_query($conn,$sql_main_cat);
            $cnt = $tot_main = 0;
            while($result_main_cat = mysqli_fetch_array($query_main_cat)) {
            $cnt++;
            $main_cat = $result_main_cat['id'];
            $mainUid = $result_main_cat['uid'];
            $main_cat_name = $result_main_cat['tag'];
            if ($main_cat == 1 || $main_cat == 2 || $main_cat == 7 || $main_cat == 8) {
                $entry_type = "Dr";
            } elseif ($main_cat == 3 || $main_cat == 4 || $main_cat == 5 || $main_cat == 6) {
                $entry_type = "Cr";
            }
            
                $sql_calc = "SELECT id as cat FROM `expense_categories` WHERE `type` = '$main_cat'";
                $query_calc = mysqli_query($conn,$sql_calc);
                while($result_calc = mysqli_fetch_array($query_calc)) {
                    $categ = $result_calc['cat'];
                    $sql_amt_main = "SELECT sum(db_amt)-sum(cd_amt) AS amount FROM (
                                    (SELECT sum(amount) as cd_amt, '' as db_amt FROM `jv_items` WHERE `type` = 'credit' AND `cat` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    UNION ALL
                                    (SELECT '' as cd_amt, sum(amount) as db_amt FROM `jv_items` WHERE `type` = 'debit' AND `cat` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    UNION ALL
                                    (SELECT '' as cd_amt, sum(grand) as db_amt FROM `pv` WHERE `category` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT sum(grand) as cd_amt, '' as db_amt FROM `pv` WHERE `bank` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT sum(amount) as cd_amt, '' as db_amt FROM `additionalAcc` WHERE `cat` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT '' as cd_amt, sum(amount) as db_amt FROM `reciept` WHERE `cat` = '$categ' AND STR_TO_DATE(clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                
                                    UNION ALL
                                    (SELECT sum(total) as cd_amt, '' as db_amt FROM `invoice` WHERE `cat` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT sum(amount) as cd_amt, '' as db_amt FROM `refund` WHERE `cat` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT sum(-amount) as cd_amt, '' as db_amt FROM `credit_note` WHERE `cat` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    ";
                
                    // if($year == '2021') {
                    // $sql_amt_main .= "UNION ALL (SELECT '' as cd_amt, sum(op_2023) as db_amt FROM `expense_categories` WHERE `id` = '$categ')";
                    // $sql_amt_main .= "UNION ALL (SELECT '' as cd_amt,  sum(op_bal) as db_amt FROM `expense_subcategories` WHERE `parent` = '$categ')";
                    // }
                
                    $sql_amt_main .= ") result";
                    
                                if($year == '2021') {
                                    $sql_opnCat = "SELECT sum(op_bal) as opnCat FROM `expense_subcategories` WHERE `parent` = '$categ'";
                                    $queryopnCat = mysqli_query($conn,$sql_opnCat);
                                    $resultopnCat = mysqli_fetch_array($queryopnCat);
                                    $opnCat = $resultopnCat['opnCat'];
                                }
                                $opnCat = ($opnCat != NULL) ? $opnCat : 0;

                        $query_amt_main = mysqli_query($conn,$sql_amt_main);
                        $result_amt_main = mysqli_fetch_array($query_amt_main);
                        $main_amt = $result_amt_main['amount'];
                            if($entry_type == 'Cr') {$main_amt = -($main_amt);}
                            if($categ == '43' || $categ == '39') {$main_amt = -($main_amt);}
                            
                            $main_amt = $main_amt+$opnCat;
                            
                        $main_amt = ($main_amt != NULL) ? $main_amt : 0;
                        
                        if($entry_type == 'Dr') {
                            if($categ == '65') {
                                $main_amt = $main_amt+$receivables;
                            } else if($categ == '66') {
                                $main_amt = $main_amt+$pdcInHand;
                            }
                        } else if($entry_type == 'Cr') {
                            if($categ == '72') {
                                $main_amt = $main_amt+$liability;
                            }
                        }
                        
                        
                $tot_main = $tot_main + $main_amt;
                }
        ?>
            <tr>
              <td><b><?php echo $cnt;?></b></td>
              <td><b><?php echo $mainUid;?></b></td>
              <td style="font-size:18px;"><b><?php echo $main_cat_name;?></b></td>
              <?php if($entry_type == 'Dr') { ?>
                <td><b><?php echo custom_money_format('%!i', $tot_main);$main_amt=$tot_main=$opnCat=0;?></b></td>
                <td></td>
              <?php } else if($entry_type == 'Cr') { ?>
                <td></td>
                <td><b><?php echo custom_money_format('%!i', $tot_main);$main_amt=$tot_main=$opnCat=0;?></b></td>
              <?php } ?>
              
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
                                    (SELECT '' as cd_amt, sum(amount) as db_amt FROM `pv` WHERE `category` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                
                                    UNION ALL
                                    (SELECT sum(amount) as cd_amt, '' as db_amt FROM `pv` WHERE `bank` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT sum(amount) as cd_amt, '' as db_amt FROM `additionalAcc` WHERE `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT '' as cd_amt, sum(amount) as db_amt FROM `reciept` WHERE `cat` = '$cat' AND STR_TO_DATE(clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT sum(total) as cd_amt, '' as db_amt FROM `invoice` WHERE `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT sum(amount) as cd_amt, '' as db_amt FROM `refund` WHERE `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT sum(-amount) as cd_amt, '' as db_amt FROM `credit_note` WHERE `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    ";
                        
                        // if($year == '2021') {
                        // $sql_amt_cat .= "UNION ALL (SELECT '' as cd_amt, sum(op_2023) as db_amt FROM `expense_categories` WHERE `id` = '$cat')";
                        // $sql_amt_cat .= "UNION ALL (SELECT '' as cd_amt, sum(op_bal) as db_amt FROM `expense_subcategories` WHERE `parent` = '$cat')";
                        // }     
                                
                        $sql_amt_cat .= ") result";
                        
                                if($year == '2021') {
                                    $sql_opnCat = "SELECT sum(op_bal) as opnCat FROM `expense_subcategories` WHERE `parent` = '$cat'";
                                    $queryopnCat = mysqli_query($conn,$sql_opnCat);
                                    $resultopnCat = mysqli_fetch_array($queryopnCat);
                                    $opnCat = $resultopnCat['opnCat'];
                                }
                                $opnCat = ($opnCat != NULL) ? $opnCat : 0;

                        $query_amt_cat = mysqli_query($conn,$sql_amt_cat);
                        $result_amt_cat = mysqli_fetch_array($query_amt_cat);
                        $cat_amt = $result_amt_cat['amount'];
                            if($entry_type == 'Cr') {$cat_amt = -($cat_amt);}
                            if($cat == '43' || $cat == '39') {$cat_amt = -($cat_amt);}
                            
                            $cat_amt = $cat_amt+$opnCat;
                            
                        $cat_amt = ($cat_amt != NULL) ? $cat_amt : 0;
                        
                        if($entry_type == 'Dr') {
                            if($cat == '65') {
                                $cat_amt = $cat_amt+$receivables;
                            } else if($cat == '66') {
                                $cat_amt = $cat_amt+$pdcInHand;
                            }
                            $tot_debit[] = $cat_amt;
                        } else if($entry_type == 'Cr') {
                            if($cat == '72') {
                                $cat_amt = $cat_amt+$liability;
                            }
                            $tot_credit[] = $cat_amt;
                        }
            ?>
            
                <tr>
                  <td><b></b></td>
                  <td><b><?php echo $catUid;?></b></td>
                  <td style="font-size:18px;"><?php echo $cat_name;?></td>
                  <?php if($entry_type == 'Dr') { ?>
                      <td style="font-size:18px;"><?php echo custom_money_format('%!i', $cat_amt);?></td>
                      <td></td>
                  <?php } else if($entry_type == 'Cr') { ?>
                      <td></td>
                      <td style="font-size:18px;"><?php echo custom_money_format('%!i', $cat_amt);?></td>
                  <?php } ?>
                </tr>
                
                
                
<!--SUB SECTION STARTS HERE -->                
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
                                        (SELECT sum(total) as cd_amt, '' as db_amt FROM `invoice` WHERE `sub` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        
                                        UNION ALL
                                        (SELECT sum(amount) as cd_amt, '' as db_amt FROM `refund` WHERE `bank` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        
                                        UNION ALL
                                        (SELECT sum(-amount) as cd_amt, '' as db_amt FROM `credit_note` WHERE `sub` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        
                                        ";
                        
                            // if($year == '2021') {                         
                            // $sql_amt_sub .= "UNION ALL (SELECT '' as cd_amt, sum(op_bal) as db_amt FROM `expense_subcategories` WHERE `id` = '$sub_cat')";
                            // }
                        
                            $sql_amt_sub .= ") result";
                            
                                if($year == '2021') {
                                    $sql_opnSub = "SELECT sum(op_bal) as opnSub FROM `expense_subcategories` WHERE `id` = '$sub_cat'";
                                    $queryopnSub = mysqli_query($conn,$sql_opnSub);
                                    $resultopnSub = mysqli_fetch_array($queryopnSub);
                                    $opnSub = $resultopnSub['opnSub'];
                                }
                                $opnSub = ($opnSub != NULL) ? $opnSub : 0;
                            

                            $query_amt_sub = mysqli_query($conn,$sql_amt_sub);
                            $result_amt_sub = mysqli_fetch_array($query_amt_sub);
                            $sub_amt = $result_amt_sub['amount'];
                                if($entry_type == 'Cr') {$sub_amt = -($sub_amt);}
                                if($sub_cat == '176') {$sub_amt = -($sub_amt);}
                            $sub_amt = $sub_amt+$opnSub;
                            $sub_amt = ($sub_amt != NULL) ? $sub_amt : 0;
                            
                            
                        if($entry_type == 'Dr') {
                            if($sub_cat == '177') {
                                $sub_amt = $sub_amt+$receivables;
                            } else if($sub_cat == '178') {
                                $sub_amt = $sub_amt+$pdcInHand;
                            }
                        } else if($entry_type == 'Cr') {
                            if($sub_cat == '182') {
                                $sub_amt = $sub_amt+$liability;
                            }
                        }
                ?>
                
                    <tr>
                      <td><b></b></td>
                      <td><b><?php echo $subcatUid;?></b></td>
                      <td style="font-size:18px;color: #bd0000;">&emsp;-> <?php echo $sub_cat_name;?></td>
                      <?php if($entry_type == 'Dr') { ?>
                      <td style="font-size:18px;"><?php echo custom_money_format('%!i', $sub_amt);?></td>
                      <td></td>
                      <?php } else if($entry_type == 'Cr') { ?>
                      <td></td>
                      <td style="font-size:18px;"><?php echo custom_money_format('%!i', $sub_amt);?></td>
                      <?php } ?>
                    </tr>
                    
                
                
                <?php } ?>
                
<!--SUB SECTION ENDS HERE -->
                
                
                
                
                
                
                
            <?php } ?>
            
        <?php } ?>
        
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