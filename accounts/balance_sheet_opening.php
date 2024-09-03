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
    $fdate = $_GET['fdate'];
    $tdate = $_GET['tdate'];
}
$start_date = '01/01/2018';
$dateTime = DateTime::createFromFormat('d/m/Y', $fdate);
$year = $dateTime->format('Y');
$nextYear = $year+1;

$sql_delOpn = "DELETE FROM `account_openings` WHERE `year` = '$nextYear'";
$query_delOpn = mysqli_query($conn,$sql_delOpn);

$sql_delPrft = "DELETE FROM `account_profit` WHERE `year` = '$year'";
$query_delPrft = mysqli_query($conn,$sql_delPrft);

// finding RECEIVABLE OR LIABILITY
            $sql_check_receivable = "
                    SELECT id,amount FROM (
                        SELECT CONCAT('INO', id) AS id, ROUND(SUM(op_bal), 2) AS amount FROM `customers` WHERE `type`='Company' AND STR_TO_DATE(`op_date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                        UNION ALL
                        SELECT CONCAT('INV', id) AS id, ROUND(SUM(grand), 2) AS amount FROM `invoice` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                        UNION ALL
                        SELECT CONCAT('RCP', id) AS id, ROUND(SUM(grand), 2) AS amount FROM `reciept` WHERE STR_TO_DATE(`pdate`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND `pdate`!=''
                        UNION ALL
                        SELECT CONCAT('CNT', id) AS id, ROUND(SUM(total), 2) AS amount FROM `credit_note` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                        UNION ALL
                        SELECT CONCAT('RFD', id) AS id, ROUND(SUM(amount), 2) AS amount FROM `refund` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
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
                
                $receivables = $cr-$dr;
            }

// finding PDC in Hand

    $sqlPdc = "SELECT ROUND(SUM(amount), 2) AS pdcAmount FROM `reciept` WHERE
              STR_TO_DATE(`pdate`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
              AND STR_TO_DATE(`clearance_date`, '%d/%m/%Y') NOT BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND `pdate`!=''";
    $queryPdc = mysqli_query($conn,$sqlPdc);
    $resultPdc = mysqli_fetch_array($queryPdc);
    $pdcInHand = $resultPdc['pdcAmount'];
    
    // changed by developer hari
    $tot_debit[]=$receivables+$pdcInHand;
    $receivables=$pdcInHand=0;
?>

        <?php
            $sql_main_cat = "SELECT * FROM `expense_main_categories` WHERE `id` < 6";
            $query_main_cat = mysqli_query($conn,$sql_main_cat);
            $cnt = $tot_main = 0;
            while($result_main_cat = mysqli_fetch_array($query_main_cat)) {
            $cnt++;
            $main_cat = $result_main_cat['id'];
        ?>
            
            <?php 
                $sql_cat = "SELECT * FROM `expense_categories` WHERE `type`='$main_cat' ORDER BY `uid`";
                $query_cat = mysqli_query($conn,$sql_cat);
                while($result_cat = mysqli_fetch_array($query_cat)) {
                    $cat = $result_cat['id'];
                    $catUid = $result_cat['uid'];
                    $cat_name = $result_cat['tag'];
                    $entry_type = $result_cat['entry'];
                    
                        $sql_amt_cat = "SELECT ROUND(SUM(db_amt), 2) - ROUND(SUM(cd_amt), 2) AS amount FROM (
                                    (SELECT ROUND(SUM(amount), 2) as cd_amt, '' as db_amt FROM `jv_items` WHERE `type` = 'credit' AND `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    UNION ALL
                                    (SELECT '' as cd_amt, ROUND(SUM(amount), 2) as db_amt FROM `jv_items` WHERE `type` = 'debit' AND `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                
                                    UNION ALL
                                    (SELECT '' as cd_amt, ROUND(SUM(amount), 2) as db_amt FROM `pv` WHERE `category` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                
                                    UNION ALL
                                    (SELECT ROUND(SUM(amount), 2) as cd_amt, '' as db_amt FROM `pv` WHERE `bank` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT '' as cd_amt, ROUND(SUM(amount), 2) as db_amt FROM `reciept` WHERE `cat` = '$cat' AND STR_TO_DATE(clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT ROUND(SUM(total), 2) as cd_amt, '' as db_amt FROM `invoice` WHERE `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT ROUND(SUM(amount), 2) as cd_amt, '' as db_amt FROM `refund` WHERE `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT ROUND(SUM(-amount), 2) as cd_amt, '' as db_amt FROM `credit_note` WHERE `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    ";
                                    
                                    if($cat == '39' || $cat == '43') {
                                        $sql_amt_cat .= "UNION ALL
                                        (SELECT '' as cd_amt, ROUND(SUM(amount), 2) as db_amt FROM `additionalAcc` WHERE `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))";
                                    } else {
                                        $sql_amt_cat .="UNION ALL
                                        (SELECT ROUND(SUM(amount), 2) as cd_amt, '' as db_amt FROM `additionalAcc` WHERE `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))";
                                    }
                                
                            $sql_amt_cat .= ") result";
                        
                                
                                // Specially Designed by Developer
                                    $sql_opnCat = "SELECT ROUND(SUM(amount), 2) as opnCat FROM `account_openings` WHERE `cat` = '$cat' AND `year`='$year'";
                                    $queryopnCat = mysqli_query($conn,$sql_opnCat);
                                    $resultopnCat = mysqli_fetch_array($queryopnCat);
                                    $opnCat = $resultopnCat['opnCat'];
                                    
                                $opnCat = ($opnCat != NULL) ? $opnCat : 0;

                        $query_amt_cat = mysqli_query($conn,$sql_amt_cat);
                        $result_amt_cat = mysqli_fetch_array($query_amt_cat);
                        $cat_amt = $result_amt_cat['amount'];
                            if($entry_type == 'Cr') {$cat_amt = -($cat_amt);}
                            // if($cat == '43' || $cat == '39') {$cat_amt = -($cat_amt);}
                            
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
                            $tot_credit[] = $cat_amt;
                        }
            ?>
                
<!--SUB SECTION STARTS HERE -->                
                <?php 
                    $sql_sub_cat = "SELECT * FROM `expense_subcategories` WHERE `parent`='$cat' ORDER BY `uid`";
                    $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
                    while($result_sub_cat = mysqli_fetch_array($query_sub_cat)) {
                        $sub_cat = $result_sub_cat['id'];
                        $subcatUid = $result_sub_cat['uid'];
                        $sub_cat_name = $result_sub_cat['category'];
                        
                            $sql_amt_sub = "SELECT ROUND(SUM(db_amt), 2)-ROUND(SUM(cd_amt), 2) AS amount FROM (
                                        (SELECT ROUND(SUM(amount), 2) as cd_amt, '' as db_amt FROM `jv_items` WHERE `type` = 'credit' AND `sub` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        UNION ALL
                                        (SELECT '' as cd_amt, ROUND(SUM(amount), 2) as db_amt FROM `jv_items` WHERE `type` = 'debit' AND `sub` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        
                                        UNION ALL
                                        (SELECT '' as cd_amt, ROUND(SUM(grand), 2) as db_amt FROM `pv` WHERE `subcategory` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        
                                        UNION ALL
                                        (SELECT ROUND(SUM(grand), 2) as cd_amt, '' as db_amt FROM `pv` WHERE `inward` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        
                                        UNION ALL
                                        (SELECT '' as cd_amt, ROUND(SUM(amount), 2) as db_amt FROM `reciept` WHERE `inward` = '$sub_cat' AND STR_TO_DATE(clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                                        UNION ALL
                                        (SELECT ROUND(SUM(total), 2) as cd_amt, '' as db_amt FROM `invoice` WHERE `sub` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        
                                        UNION ALL
                                        (SELECT ROUND(SUM(amount), 2) as cd_amt, '' as db_amt FROM `refund` WHERE `bank` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        
                                        UNION ALL
                                        (SELECT ROUND(SUM(-amount), 2) as cd_amt, '' as db_amt FROM `credit_note` WHERE `sub` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        
                                        ";
                                        
                                        if($sub_cat == '176') {
                                            $sql_amt_sub .= "UNION ALL
                                            (SELECT '' as cd_amt, ROUND(SUM(amount), 2) as db_amt FROM `additionalAcc` WHERE `sub` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))";
                                        } else {
                                            $sql_amt_sub .="UNION ALL
                                            (SELECT ROUND(SUM(amount), 2) as cd_amt, '' as db_amt FROM `additionalAcc` WHERE `sub` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))";
                                        }
                                        
                            $sql_amt_sub .= ") result";

                                
                                // Specially Designed by Developer
                                    $sql_opnSub = "SELECT ROUND(SUM(amount), 2) as opnSub FROM `account_openings` WHERE `sub` = '$sub_cat' AND `year`='$year'";
                                    $queryopnSub = mysqli_query($conn,$sql_opnSub);
                                    $resultopnSub = mysqli_fetch_array($queryopnSub);
                                    $opnSub = $resultopnSub['opnSub'];
                                
                                $opnSub = ($opnSub != NULL) ? $opnSub : 0;
                            

                            $query_amt_sub = mysqli_query($conn,$sql_amt_sub);
                            $result_amt_sub = mysqli_fetch_array($query_amt_sub);
                            $sub_amt = $result_amt_sub['amount'];
                                if($entry_type == 'Cr') {$sub_amt = -($sub_amt);}
                                // if($sub_cat == '176') {$sub_amt = -($sub_amt);}
                            $sub_amt = $sub_amt+$opnSub;
                            $sub_amt = ($sub_amt != NULL) ? $sub_amt : 0;
                            
                            if($sub_cat == '177') {
                                $sub_amt = $sub_amt+$receivables;
                            } else if($sub_cat == '178') {
                                $sub_amt = $sub_amt+$pdcInHand;
                            } else if($sub_cat == '64') {
                                $drawings = $sub_amt;
                                $sub_amt = 0;
                            }

                            if($sub_amt != 0) {
                                $sql_addOpn = "INSERT INTO `account_openings`(`id`, `year`, `cat`, `sub`, `amount`) VALUES ('','$nextYear','$cat','$sub_cat','$sub_amt')";
                                $query_addOpn = mysqli_query($conn,$sql_addOpn);
                            }
        
                    } ?>
                
<!--SUB SECTION ENDS HERE -->
                
            <?php } ?>
            
        <?php } ?>
        
            <?php
                $net_income = array_sum($tot_debit) - array_sum($tot_credit);
                $net_income = $net_income+$drawings;
                
                $sql_check = "SELECT * FROM `account_openings` WHERE `cat`='41' AND `sub`='63' AND `year`='$nextYear'";
                $query_check = mysqli_query($conn,$sql_check);
                if(mysqli_num_rows($query_check) == 0) {
                    $sql_cap = "INSERT INTO `account_openings`(`id`, `year`, `cat`, `sub`, `amount`) VALUES ('','$nextYear','41','63','0')";
                    $query_cap = mysqli_query($conn,$sql_cap);
                }
                
                $sql_capital = "SELECT `amount` AS capital FROM `account_openings` WHERE `cat`='41' AND `sub`='63' AND `year`='$nextYear'";
                $query_capital = mysqli_query($conn,$sql_capital);
                $result_capital = mysqli_fetch_array($query_capital);
                $capital = $result_capital['capital'];
                $capital = $capital+$net_income;
                $capital = number_format((float)$capital, 2, '.', '');
                
                
                $sql_addOpn1 = "UPDATE `account_openings` SET `amount`='$capital' WHERE `cat`='41' AND `sub`='63' AND `year`='$nextYear'";
                $query_addOpn1 = mysqli_query($conn,$sql_addOpn1);
                
                $sql_addPrft = "INSERT INTO `account_profit`(`id`, `year`, `date`, `cat`, `sub`, `amount`) VALUES ('','$year','31/12/$year','41','63','$net_income')";
                $query_addPrft = mysqli_query($conn,$sql_addPrft);
            ?>
            
            
            
        <h2 style="text-align:center;margin-bottom:1px;">Loading Completed</h2>