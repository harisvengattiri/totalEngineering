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
    $start_date = '01/01/2015';
    $inception = 'Since Inception';
    $show_fdate = ($fdate != NULL) ? $fdate : $inception;
    $fdate = ($fdate != NULL) ? $fdate : $start_date;
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
            $sql_calc1 = "SELECT sum(debt_total) AS amt1 FROM `journal` WHERE (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc2 = "SELECT sum(grand) AS amt2 FROM `pv` WHERE (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc3 = "SELECT sum(dbt_amt) AS amt3 FROM `debitnote` WHERE (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc4 = "SELECT sum(crdt_total) AS amt4 FROM `journal` WHERE (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc5 = "SELECT sum(op_bal) AS op_bal FROM `expense_subcategories`";
            
            $query_calc1 = mysqli_query($conn,$sql_calc1);
            $result_calc1 = mysqli_fetch_array($query_calc1);
                $amt1 = $result_calc1['amt1'];
                $amt1 = ($amt1 != NULL) ? $amt1 : 0;
            $query_calc2 = mysqli_query($conn,$sql_calc2);
            $result_calc2 = mysqli_fetch_array($query_calc2);
                $amt2 = $result_calc2['amt2'];
                $amt2 = ($amt2 != NULL) ? $amt2 : 0;
            $query_calc3 = mysqli_query($conn,$sql_calc3);
            $result_calc3 = mysqli_fetch_array($query_calc3);
                $amt3 = $result_calc3['amt3'];
                $amt3 = ($amt3 != NULL) ? $amt3 : 0;
            $query_calc4 = mysqli_query($conn,$sql_calc4);
            $result_calc4 = mysqli_fetch_array($query_calc4);
                $amt4 = $result_calc4['amt4'];
                $amt4 = ($amt4 != NULL) ? $amt4 : 0;
            $query_calc5 = mysqli_query($conn,$sql_calc5);
            $result_calc5 = mysqli_fetch_array($query_calc5);
                $op_bal = $result_calc5['op_bal'];
                $op_bal = ($op_bal != NULL) ? $op_bal : 0;
                
            $openingBalance = ($amt1+$amt2+$amt3) - ($amt4+$op_bal);
            $openingBalance = ($openingBalance != NULL) ? $openingBalance : 0;
        ?>
        <tr>
            <td><b></b></td>
            <td><b></b></td>
            <td style="font-size:18px;"><b>Opening Balance</b></td>
            <td><b><?php echo custom_money_format('%!i', $openingBalance);?></b></td>
        </tr>    
            
            
        <?php
            $sql_main_cat = "SELECT * FROM `expense_main_categories`";
            $query_main_cat = mysqli_query($conn,$sql_main_cat);
            $cnt = $tot_main = 0;
            while($result_main_cat = mysqli_fetch_array($query_main_cat)) {
            $cnt++;
            $main_cat = $result_main_cat['id'];
            $mainUid = $result_main_cat['uid'];
            $main_cat_name = $result_main_cat['tag'];
            
                $sql_calc = "SELECT id as cat FROM `expense_categories` WHERE `type` = '$main_cat'";
                $query_calc = mysqli_query($conn,$sql_calc);
                while($result_calc = mysqli_fetch_array($query_calc)) {
                    $categ = $result_calc['cat'];
                    $sql_amt_main = "SELECT sum(db_amt)-sum(cd_amt) AS amount FROM (
                                    (SELECT sum(crdt_total) as cd_amt, '' as db_amt FROM `journal` WHERE `crdt_cat` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    UNION ALL
                                    (SELECT '' as cd_amt, sum(debt_total) as db_amt FROM `journal` WHERE `debt_cat` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    UNION ALL
                                    (SELECT '' as cd_amt, sum(grand) as db_amt FROM `pv` WHERE `category` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    ) result
                                    ";
                        $query_amt_main = mysqli_query($conn,$sql_amt_main);
                        $result_amt_main = mysqli_fetch_array($query_amt_main);
                        $main_amt = $result_amt_main['amount'];
                        $main_amt = ($main_amt != NULL) ? $main_amt : 0;
                $tot_main = $tot_main + $main_amt;
                }
        ?>
            <tr>
              <td><b><?php echo $cnt;?></b></td>
              <td><b><?php echo $mainUid;?></b></td>
              <td style="font-size:18px;"><b><?php echo $main_cat_name;?></b></td>
              <td><b><?php echo custom_money_format('%!i', $tot_main);$tot_main=0;?></b></td>
            </tr>
            
            <?php 
                $sql_cat = "SELECT * FROM `expense_categories` WHERE `type`='$main_cat'";
                $query_cat = mysqli_query($conn,$sql_cat);
                while($result_cat = mysqli_fetch_array($query_cat)) {
                    $cat = $result_cat['id'];
                    $catUid = $result_cat['uid'];
                    $cat_name = $result_cat['tag'];
                    
                        $sql_amt_cat = "SELECT sum(db_amt)-sum(cd_amt) AS amount FROM (
                                    (SELECT sum(crdt_total) as cd_amt, '' as db_amt FROM `journal` WHERE `crdt_cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    UNION ALL
                                    (SELECT '' as cd_amt, sum(debt_total) as db_amt FROM `journal` WHERE `debt_cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    UNION ALL
                                    (SELECT '' as cd_amt, sum(grand) as db_amt FROM `pv` WHERE `category` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    ) result";
                        $query_amt_cat = mysqli_query($conn,$sql_amt_cat);
                        $result_amt_cat = mysqli_fetch_array($query_amt_cat);
                        $cat_amt = $result_amt_cat['amount'];
                        $cat_amt = ($cat_amt != NULL) ? $cat_amt : 0;
            ?>
            
                <tr>
                  <td><b></b></td>
                  <td><b><?php echo $catUid;?></b></td>
                  <td style="font-size:18px;"><?php echo $cat_name;?></td>
                  <td style="font-size:18px;"><?php echo custom_money_format('%!i', $cat_amt);?></td>
                </tr>
                
                
                <?php 
                    $sql_sub_cat = "SELECT * FROM `expense_subcategories` WHERE `parent`='$cat'";
                    $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
                    while($result_sub_cat = mysqli_fetch_array($query_sub_cat)) {
                        $sub_cat = $result_sub_cat['id'];
                        $subcatUid = $result_sub_cat['uid'];
                        $sub_cat_name = $result_sub_cat['category'];
                        
                            $sql_amt_sub = "SELECT sum(db_amt)-sum(cd_amt) AS amount FROM (
                                        (SELECT sum(crdt_total) as cd_amt, '' as db_amt FROM `journal` WHERE `crdt_sub` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        UNION ALL
                                        (SELECT '' as cd_amt, sum(debt_total) as db_amt FROM `journal` WHERE `debt_sub` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        UNION ALL
                                        (SELECT '' as cd_amt, sum(grand) as db_amt FROM `pv` WHERE `subcategory` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        ) result
                                        ";
                            $query_amt_sub = mysqli_query($conn,$sql_amt_sub);
                            $result_amt_sub = mysqli_fetch_array($query_amt_sub);
                            $sub_amt = $result_amt_sub['amount'];
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