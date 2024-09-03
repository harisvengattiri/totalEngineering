<?php
  include "../config.php";
  error_reporting(0);
  session_start();
if(!isset($_SESSION['userid']))
{
   header("Location:$baseurl/login/");
}
?>
<?php
// if(!empty($_GET))
// {
// $fdate= '';
// $tdate=date('d/m/Y');
// $mindate = '01/01/2015';
// $customer=$_GET["company"];

//               $sqlcust="SELECT name,op_bal,slmn from customers where id='$customer'";
//               $querycust=mysqli_query($conn,$sqlcust);
//               $fetchcust=mysqli_fetch_array($querycust);
//               $cust=$fetchcust['name'];
//               $opening=$fetchcust['op_bal'];
//               $opening = ($opening != NULL) ? $opening : 0;
//               $rep_id = $fetchcust['slmn'];
// }
if(!empty($_POST))
{
$fdate=$_POST["fdate"];
    // $dateTime = DateTime::createFromFormat('d/m/Y', $fdate);
    // $year = $dateTime->format('Y');
$tdate=$_POST["tdate"];
$mindate = '01/01/2018';
$cat=$_POST["category"];
$sub=$_POST["subcategory"];
            if(!empty($cat)) {
                $sql_cat = "SELECT `tag`,`entry`,`type` FROM `expense_categories` WHERE id='$cat'";
                $query_cat = mysqli_query($conn,$sql_cat);
                $result_cat = mysqli_fetch_array($query_cat);
                $cat_name = $result_cat['tag'];
                $cat_entry= $result_cat['entry'];
                $mainCat= $result_cat['type'];
            }
            if(!empty($sub)) {
                $sql_sub_cat = "SELECT category,op_bal FROM `expense_subcategories` WHERE id='$sub'";
                $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
                $result_sub_cat = mysqli_fetch_array($query_sub_cat);
                $sub_cat_name = $result_sub_cat['category'];
                
                // $sql_opn = "SELECT `amount` AS op_bal FROM `account_openings` WHERE `sub`='$sub' AND `year`='$year'";
                
                $op_bal = $result_sub_cat['op_bal'];
                $op_bal = ($op_bal != NULL) ? $op_bal : 0;
            }
}
?>
<!--<title> <?php //echo $title;?></title>-->
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
<h2 style="text-align:center;margin-bottom:1px;">ACCOUNT STATEMENT</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <span style="font-size:15px;">Account: <?php echo $cat_name;?><br>Sub Account: <?php echo $sub_cat_name;?></span>
          </td>
          <td width="50%" style="text-align:right"><span style="font-size:15px;"> Date: <?php if ($fdate==''){echo "Since Inception";} else{echo $fdate;}?> - <?php echo $tdate;?></span></td>
     </tr>     
</table>
 
 
 
<table id="tbl1" align="center" style="width:90%;">
        <thead>
          <tr>
              <th id="hsl">
                  Sl No
              </th>
              <th>
                  Date
              </th>
              <th>
                  Due Date
              </th>
              <th>
                  Serial No.
              </th>
              <th>
                  Customer
              </th>
              <th>
                  Inv No. / Chq No.
              </th>
              <th>
                  Description
              </th>
              <th>
                  Note
              </th>
              <th>
                  Debit
              </th>
              <th>
                  Credit
              </th>
              <th>
                  Balance
              </th>
          </tr>
        </thead>
        <tbody style="font-size:11px;">
		<?php
// 		Opening Balance Taking While There is no Subcategory Selected
        if($sub == NULL) {
		    $sql_calc1 = "SELECT sum(amount) AS amt1 FROM `jv_items` WHERE `type`='debit' AND `cat`='$cat' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc2 = "SELECT sum(grand) AS amt2 FROM `pv` WHERE `category`='$cat' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc3 = "SELECT sum(dbt_amt) AS amt3 FROM `debitnote` WHERE `cat`='$cat' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc4 = "SELECT sum(amount) AS amt4 FROM `jv_items` WHERE `type`='credit' AND `cat`='$cat' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc5 = "SELECT sum(amount) AS amt5 FROM `additionalAcc` WHERE `cat`='$cat' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc6 = "SELECT sum(amount) AS amt6 FROM `reciept` WHERE `cat`='$cat' AND (STR_TO_DATE(clearance_date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(clearance_date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc7 = "SELECT sum(total) AS amt7 FROM `invoice` WHERE `cat`='$cat' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc8 = "SELECT sum(grand) AS amt8 FROM `pv` WHERE `bank`='$cat' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc9 = "SELECT sum(amount) AS amt9 FROM `refund` WHERE `cat`='$cat' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc10 = "SELECT sum(amount) AS amt10 FROM `credit_note` WHERE `cat`='$cat' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc11 = "SELECT sum(amount) AS amt11 FROM `account_profit` WHERE `cat`='$cat' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
        } else {
            $sql_calc1 = "SELECT sum(amount) AS amt1 FROM `jv_items` WHERE `type`='debit' AND `cat`='$cat' AND `sub`='$sub' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc2 = "SELECT sum(grand) AS amt2 FROM `pv` WHERE `category`='$cat' AND `subcategory`='$sub' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc3 = "SELECT sum(dbt_amt) AS amt3 FROM `debitnote` WHERE `cat`='$cat' AND `sub`='$sub' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc4 = "SELECT sum(amount) AS amt4 FROM `jv_items` WHERE `type`='credit' AND `cat`='$cat' AND `sub`='$sub' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc5 = "SELECT sum(amount) AS amt5 FROM `additionalAcc` WHERE `cat`='$cat' AND `sub`='$sub' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc6 = "SELECT sum(amount) AS amt6 FROM `reciept` WHERE `cat`='$cat' AND `inward`='$sub' AND (STR_TO_DATE(clearance_date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(clearance_date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc7 = "SELECT sum(total) AS amt7 FROM `invoice` WHERE `cat`='$cat' AND `sub`='$sub' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc8 = "SELECT sum(grand) AS amt8 FROM `pv` WHERE `bank`='$cat' AND `inward`='$sub' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc9 = "SELECT sum(amount) AS amt9 FROM `refund` WHERE `cat`='$cat' AND `bank`='$sub' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc10 = "SELECT sum(amount) AS amt10 FROM `credit_note` WHERE `cat`='$cat' AND `sub`='$sub' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
            $sql_calc11 = "SELECT sum(amount) AS amt11 FROM `account_profit` WHERE `cat`='$cat' AND `sub`='$sub' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y'))";
        }
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
            $amt5 = $result_calc5['amt5'];
            $amt5 = ($amt5 != NULL) ? $amt5 : 0;
            
            $query_calc6 = mysqli_query($conn,$sql_calc6);
            $result_calc6 = mysqli_fetch_array($query_calc6);
            $amt6 = $result_calc6['amt6'];
            $amt6 = ($amt6 != NULL) ? $amt6 : 0;
            $query_calc7 = mysqli_query($conn,$sql_calc7);
            $result_calc7 = mysqli_fetch_array($query_calc7);
            $amt7 = $result_calc7['amt7'];
            $amt7 = ($amt7 != NULL) ? $amt7 : 0;
            $query_calc8 = mysqli_query($conn,$sql_calc8);
            $result_calc8 = mysqli_fetch_array($query_calc8);
            $amt8 = $result_calc8['amt8'];
            $amt8 = ($amt8 != NULL) ? $amt8 : 0;
            $query_calc9 = mysqli_query($conn,$sql_calc9);
            $result_calc9 = mysqli_fetch_array($query_calc9);
            $amt9 = $result_calc9['amt9'];
            $amt9 = ($amt9 != NULL) ? $amt9 : 0;
            $query_calc10 = mysqli_query($conn,$sql_calc10);
            $result_calc10 = mysqli_fetch_array($query_calc10);
            $amt10 = $result_calc10['amt10'];
            $amt10 = ($amt10 != NULL) ? $amt10 : 0;
            $query_calc11 = mysqli_query($conn,$sql_calc11);
            $result_calc11 = mysqli_fetch_array($query_calc11);
            $amt11 = $result_calc11['amt11'];
            $amt11 = ($amt11 != NULL) ? $amt11 : 0;
            
            if($cat_entry == 'Dr') {
            $openingBalance = ($op_bal+$amt1+$amt2+$amt3+$amt5+$amt6+$amt7+$amt10+$amt11) - ($amt4+$amt8+$amt9);
            } else if($cat_entry == 'Cr') {
            $openingBalance = ($op_bal+$amt4+$amt3+$amt5+$amt6+$amt7+$amt10+$amt11) - ($amt1+$amt2+$amt8+$amt9);
            }
            // Developer Hari removed opening of revenue and expenses and also drawings here
                if($mainCat >= 6 || $sub == 64) {$openingBalance = 0;}
            
            $openingBalance = ($openingBalance != NULL) ? $openingBalance : 0;
            
            // if($sub == NULL) {
            //      $sql ="SELECT * FROM (
            //             (SELECT CONCAT('JV', id) AS id, date, year, time, voucher, note AS description, inv AS inv, crdt_total AS credit FROM journal WHERE crdt_cat='$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
            //             UNION ALL
            //             (SELECT CONCAT('JD', id) AS id, date, year, time, voucher, note AS description, inv AS inv, debt_total AS credit FROM journal WHERE debt_cat='$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
            //             UNION ALL
            //             (SELECT CONCAT('PV', id) AS id, date, year, time, voucher, NULL AS description, checkno AS inv, grand AS credit FROM pv WHERE category='$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
            //             ) results ORDER BY STR_TO_DATE(date, '%d/%m/%Y'),time ASC";
            // } else {
            //     $sql ="SELECT * FROM (
            //             (SELECT CONCAT('JV', id) AS id, date, year, time, voucher, note AS description, inv AS inv, crdt_total AS credit FROM journal WHERE crdt_cat='$cat' AND crdt_sub='$sub' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
            //             UNION ALL
            //             (SELECT CONCAT('JD', id) AS id, date, year, time, voucher, note AS description, inv AS inv, debt_total AS credit FROM journal WHERE debt_cat='$cat' AND debt_sub='$sub' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
            //             UNION ALL
            //             (SELECT CONCAT('PV', id) AS id, date, year, time, voucher, NULL AS description, checkno AS inv, grand AS credit FROM pv WHERE category='$cat' AND subcategory ='$sub' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
            //             ) results ORDER BY STR_TO_DATE(date, '%d/%m/%Y'),time ASC";
            // }
            
            // if($sub == NULL) {   
            //      $sql ="SELECT * FROM (
            //             (SELECT CONCAT('JV', jv) AS id, date, '' AS year, '' AS time, '' AS voucher, '' AS description, '' AS inv, amount AS credit FROM `jv_items` WHERE `type`='credit' AND `cat`='$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
            //             UNION ALL
            //             (SELECT CONCAT('JD', jv) AS id, date, '' AS year, '' AS time, '' AS voucher, '' AS description, '' AS inv, amount AS credit FROM `jv_items` WHERE `type`='debit' AND cat='$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
            //             UNION ALL
            //             (SELECT CONCAT('PV', id) AS id, date, year, time, voucher, NULL AS description, checkno AS inv, grand AS credit FROM `pv` WHERE category='$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
            //             ) results ORDER BY STR_TO_DATE(date, '%d/%m/%Y'),time ASC";
            // } else {
            //     $sql ="SELECT * FROM (
            //             (SELECT CONCAT('JV', jv) AS id, date, '' AS year, '' AS time, '' AS voucher, '' AS description, '' AS inv, amount AS credit FROM `jv_items` WHERE `type`='credit' AND cat='$cat' AND sub='$sub' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
            //             UNION ALL
            //             (SELECT CONCAT('JD', id) AS id, date, '' AS year, '' AS time, '' AS voucher, '' AS description, '' AS inv, amount AS credit FROM `jv_items` WHERE `type`='debit' AND cat='$cat' AND sub='$sub' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
            //             UNION ALL
            //             (SELECT CONCAT('PV', id) AS id, date, year, time, voucher, NULL AS description, checkno AS inv, grand AS credit FROM `pv` WHERE category='$cat' AND subcategory ='$sub' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
            //             ) results ORDER BY STR_TO_DATE(date, '%d/%m/%Y'),time ASC";
            // }
        
            // if($sub == NULL) {
            //      $sql ="SELECT * FROM (
            //             (SELECT CONCAT('JV', jv) AS id, jvi.date, jv.year AS year, jv.time AS time, jv.voucher AS voucher, jv.note AS description, jv.inv AS inv, jvi.amount AS credit
            //                 FROM `jv_items` jvi INNER JOIN `jv` jv ON jvi.jv = jv.id
            //                 WHERE type='credit' AND jvi.cat='$cat' AND STR_TO_DATE(jvi.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
            //             UNION ALL
            //             (SELECT CONCAT('JD', jv) AS id, jvi.date, jv.year AS year, jv.time AS time, jv.voucher AS voucher, jv.note AS description, jv.inv AS inv, jvi.amount AS credit
            //                 FROM `jv_items` jvi INNER JOIN `jv` jv ON jvi.jv = jv.id
            //                 WHERE type='debit' AND jvi.cat='$cat' AND STR_TO_DATE(jvi.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
            //             UNION ALL
            //             (SELECT CONCAT('PV', id) AS id, date, year, time, voucher, NULL AS description, checkno AS inv, grand AS credit FROM `pv` WHERE category='$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
            //             ) results ORDER BY STR_TO_DATE(date, '%d/%m/%Y'),time ASC";
            // } else {
            //     $sql ="SELECT * FROM (
            //             (SELECT CONCAT('JV', jv) AS id, jvi.date, jv.year AS year, jv.time AS time, jv.voucher AS voucher, jv.note AS description, jv.inv AS inv, jvi.amount AS credit
            //                 FROM `jv_items` jvi INNER JOIN `jv` jv ON jvi.jv = jv.id
            //                 WHERE type='credit' AND jvi.cat='$cat' AND jvi.sub='$sub' AND STR_TO_DATE(jvi.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
            //             UNION ALL
            //             (SELECT CONCAT('JD', jv) AS id, jvi.date, jv.year AS year, jv.time AS time, jv.voucher AS voucher, jv.note AS description, jv.inv AS inv, jvi.amount AS credit
            //                 FROM `jv_items` jvi INNER JOIN `jv` jv ON jvi.jv = jv.id
            //                 WHERE type='debit' AND jvi.cat='$cat' AND jvi.sub='$sub' AND STR_TO_DATE(jvi.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
            //             UNION ALL
            //             (SELECT CONCAT('PV', id) AS id, date, year, time, voucher, NULL AS description, checkno AS inv, grand AS credit FROM `pv` WHERE category='$cat' AND subcategory ='$sub' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
            //             ) results ORDER BY STR_TO_DATE(date, '%d/%m/%Y'),time ASC";
            // }
            
            
            if($sub == NULL) {
                 $sql ="SELECT * FROM (
                        (SELECT CONCAT('JV', jv) AS id, jvi.type AS type, jvi.date,'' AS duedate, '' AS cust,jv.year AS year, jv.time AS time, jv.voucher AS voucher, jv.note AS description, jv.inv AS inv, jvi.amount AS credit, jvi.note AS note
                            FROM `jv_items` jvi INNER JOIN `jv` jv ON jvi.jv = jv.id
                            WHERE jvi.cat='$cat' AND STR_TO_DATE(jvi.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        UNION ALL
                        (SELECT CONCAT('PV', id) AS id, '' AS type, date,'' AS duedate,'' AS cust, year, time, voucher, NULL AS description, checkno AS inv, grand AS credit, NULL AS note FROM `pv` WHERE category='$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                        UNION ALL
                        (SELECT CONCAT('PB', id) AS id, '' AS type, date,'' AS duedate,'' AS cust, year, time, voucher, NULL AS description, checkno AS inv, grand AS credit, NULL AS note FROM `pv` WHERE bank='$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                        UNION ALL
                        (SELECT CONCAT('AT', entry_id) AS id, section AS type, date,'' AS duedate,'' AS cust, '' AS year, '1' AS time, '' AS voucher, NULL AS description, '' AS inv, amount AS credit, NULL AS note FROM `additionalAcc` WHERE cat='$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                        UNION ALL
                        (SELECT CONCAT('DT', id) AS id, '' AS type, date,'' AS duedate,'' AS cust, '' AS year, time, '' AS voucher, note AS description, '' AS inv, dbt_amt AS credit, NULL AS note FROM `debitnote` WHERE cat='$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                        UNION ALL
                        (SELECT CONCAT('RP', id) AS id, '' AS type, clearance_date AS date,duedate AS duedate,customer AS cust, '' AS year, time, '' AS voucher, NULL AS description, cheque_no AS inv, amount AS credit, NULL AS note FROM `reciept` WHERE cat='$cat' AND STR_TO_DATE(clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                        UNION ALL
                        (SELECT CONCAT('IN', id) AS id, '' AS type, date,'' AS duedate,customer AS cust, '' AS year, time, '' AS voucher, NULL AS description, '' AS inv, total AS credit, NULL AS note FROM `invoice` WHERE cat='$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                        UNION ALL
                        (SELECT CONCAT('RF', id) AS id, '' AS type, date,'' AS duedate,customer AS cust, '' AS year, time, '' AS voucher, remarks AS description, '' AS inv, amount AS credit, NULL AS note FROM `refund` WHERE cat='$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                        UNION ALL
                        (SELECT CONCAT('CN', id) AS id, '' AS type, date,'' AS duedate,customer AS cust, '' AS year, time, '' AS voucher, NULL AS description, '' AS inv, -amount AS credit, NULL AS note FROM `credit_note` WHERE cat='$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                        UNION ALL
                        (SELECT CONCAT('PR', id) AS id, '' AS type, date,'' AS duedate,'' AS cust, year AS year, '1' AS time, '' AS voucher, NULL AS description, '' AS inv, amount AS credit, NULL AS note FROM `account_profit` WHERE cat='$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                        ) results ORDER BY STR_TO_DATE(date, '%d/%m/%Y'),time ASC";
            } else {
                $sql ="SELECT * FROM (
                        (SELECT CONCAT('JV', jv) AS id, jvi.type AS type, jvi.date,'' AS duedate,'' AS cust, jv.year AS year, jv.time AS time, jv.voucher AS voucher, jv.note AS description, jv.inv AS inv, jvi.amount AS credit, jvi.note AS note
                            FROM `jv_items` jvi INNER JOIN `jv` jv ON jvi.jv = jv.id
                            WHERE jvi.cat='$cat' AND jvi.sub='$sub' AND STR_TO_DATE(jvi.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        UNION ALL
                        (SELECT CONCAT('PV', id) AS id, '' AS type, date,'' AS duedate,'' AS cust, year, time, voucher, NULL AS description, checkno AS inv, grand AS credit, NULL AS note FROM `pv` WHERE category='$cat' AND subcategory ='$sub' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                        UNION ALL
                        (SELECT CONCAT('PB', id) AS id, '' AS type, date,'' AS duedate,'' AS cust, year, time, voucher, NULL AS description, checkno AS inv, grand AS credit, NULL AS note FROM `pv` WHERE bank='$cat' AND inward ='$sub' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                        UNION ALL
                        (SELECT CONCAT('AT', entry_id) AS id, section AS type, date,'' AS duedate,'' AS cust, '' AS year, '1' AS time, '' AS voucher, NULL AS description, '' AS inv, amount AS credit, NULL AS note FROM `additionalAcc` WHERE cat='$cat' AND sub ='$sub' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                        UNION ALL
                        (SELECT CONCAT('DT', id) AS id, '' AS type, date,'' AS duedate,'' AS cust, '' AS year, time, '' AS voucher, note AS description, '' AS inv, dbt_amt AS credit, NULL AS note FROM `debitnote` WHERE cat='$cat' AND sub ='$sub' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                        UNION ALL
                        (SELECT CONCAT('RP', id) AS id, '' AS type, clearance_date AS date,duedate AS duedate,customer AS cust, '' AS year, time, '' AS voucher, NULL AS description, cheque_no AS inv, amount AS credit, NULL AS note FROM `reciept` WHERE cat='$cat' AND inward ='$sub' AND STR_TO_DATE(clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                        UNION ALL
                        (SELECT CONCAT('IN', id) AS id, '' AS type, date,'' AS duedate,customer AS cust, '' AS year, time, '' AS voucher, NULL AS description, '' AS inv, total AS credit, NULL AS note FROM `invoice` WHERE cat='$cat' AND sub ='$sub' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                        UNION ALL
                        (SELECT CONCAT('RF', id) AS id, '' AS type, date,'' AS duedate,customer AS cust, '' AS year, time, '' AS voucher, remarks AS description, '' AS inv, amount AS credit, NULL AS note FROM `refund` WHERE cat='$cat' AND bank ='$sub' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                        UNION ALL
                        (SELECT CONCAT('CN', id) AS id, '' AS type, date,'' AS duedate,customer AS cust, '' AS year, time, '' AS voucher, NULL AS description, '' AS inv, -amount AS credit, NULL AS note FROM `credit_note` WHERE cat='$cat' AND sub ='$sub' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                        UNION ALL
                        (SELECT CONCAT('PR', id) AS id, '' AS type, date,'' AS duedate,'' AS cust, '' AS year, '1' AS time, '' AS voucher, NULL AS description, '' AS inv, amount AS credit, NULL AS note FROM `account_profit` WHERE cat='$cat' AND sub ='$sub' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                        ) results ORDER BY STR_TO_DATE(date, '%d/%m/%Y'),time ASC";
            }
        // echo $sql;    
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0)
		{
        $sl=1;
        while($row = mysqli_fetch_assoc($result)) {
              $idet=$row["id"];
              $type=$row["type"];
              $date=$row['date'];
              $duedate=$row['duedate'];
              $yr=$row['year'];
              $vou=$row["voucher"];
              $description=$row["description"];
              $note=$row["note"];
              $inv=$row["inv"];
              $cust=$row["cust"];
                if($cust != NULL) {
                    $sql_cust = "SELECT name FROM `customers` WHERE id='$cust'";
                    $query_cust = mysqli_query($conn,$sql_cust);
                    $result_cust = mysqli_fetch_array($query_cust);
                    $customer = $result_cust['name'];
                } else {$customer = '';}
             
              if (substr($idet, 0, 2) === 'JV')
              {
                  if($type == 'credit') {
                      $dr=0;
                      $cr=$row["credit"];
                      $cr=($cr != NULL) ? $cr : 0;
                  } else if($type == 'debit') {
                      $cr=0;
                      $dr=$row["credit"];
                      $dr=($dr != NULL) ? $dr : 0;
                  }
                $idet=substr($idet, 0, 2).'|'.$yr.'|'.sprintf("%06d",$vou);
              }
              
              else if (substr($idet, 0, 2) === 'PV')
              {
                  $cr=0;
                  $dr=$row["credit"];
                  $dr=($dr != NULL) ? $dr : 0;
                $idet=substr($idet, 0, 2).'|'.$yr.'|'.sprintf("%06d",$vou);
              }
              
              else if (substr($idet, 0, 2) === 'PB')
              {
                  $dr=0;
                  $cr=$row["credit"];
                  $cr=($cr != NULL) ? $cr : 0;
                $idet='PV|'.$yr.'|'.sprintf("%06d",$vou);
              }
              
              else if (substr($idet, 0, 2) === 'AT')
              {

                if($cat_entry == 'Dr') {
                  $cr=0;
                  $dr=$row["credit"];
                  $dr=($dr != NULL) ? $dr : 0;
                } else if($cat_entry == 'Cr') {
                  $dr=0;
                  $cr=$row["credit"];
                  $cr=($cr != NULL) ? $cr : 0;
                }
                //$idet = substr_replace($idet, 'JV', 0, 2);
                
                    if($type == 'JNL') {
                        $srl = 'JV';
                    } else if($type == 'TRP') {
                        $srl = 'TP';
                    } else if($type == 'CNT') {
                        $srl = 'CN';
                    } else {
                        $srl = $type;
                    }
                
                $idet = substr($srl, 0, 2).'|'.sprintf("%06d", substr($idet, 2)); 
              }
              
            else if (substr($idet, 0, 2) === 'DT')
            {
                $cr=0;
                $dr=$row["credit"];
                $dr=($dr != NULL) ? $dr : 0;
                $idet=substr($idet, 0, 2).'|'.sprintf("%06d",substr($idet, 2));
            }
            
            else if (substr($idet, 0, 2) === 'IN')
            {
                $dr=0;
                $cr=$row["credit"];
                $cr=($cr != NULL) ? $cr : 0;
                $idet=substr($idet, 0, 2).'|'.sprintf("%06d",substr($idet, 2));
            }
            
            else if (substr($idet, 0, 2) === 'RP')
            {
                $cr=0;
                $dr=$row["credit"];
                $dr=($dr != NULL) ? $dr : 0;
                $idet=substr($idet, 0, 2).'|'.sprintf("%06d",substr($idet, 2));
            }
            else if (substr($idet, 0, 2) === 'RF')
            {
                $dr=0;
                $cr=$row["credit"];
                $cr=($cr != NULL) ? $cr : 0;
                $idet=substr($idet, 0, 2).'|'.sprintf("%06d",substr($idet, 2));
            }
            else if (substr($idet, 0, 2) === 'CN')
            {
                $dr=0;
                $cr=$row["credit"];
                $cr=($cr != NULL) ? $cr : 0;
                $idet=substr($idet, 0, 2).'|'.sprintf("%06d",substr($idet, 2));
            }
            else if (substr($idet, 0, 2) === 'PR')
            {
                $dr=0;
                $cr=$row["credit"];
                $cr=($cr != NULL) ? $cr : 0;
                $idet=substr($idet, 0, 2).'|'.sprintf("%06d",substr($idet, 2));
            }
          ?>
          
          <?php if($sl==1) { ?>
             <tr>
              <td colspan="1"><?php ?></td>
              <td colspan="1"><?php echo $fdate;?></td>
              <td colspan="5"></td>
              <td colspan="1"><b>Opening Balance</b></td>
              <?php if($cat_entry == 'Dr') { ?>
                <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $openingBalance);?></b></td>
                <td colspan="1" style="text-align: right;"><b>0.00</b></td>
              <?php } else if($cat_entry == 'Cr') { ?>
                <td colspan="1" style="text-align: right;"><b>0.00</b></td>
                <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $openingBalance);?></b></td>
              <?php } ?>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $openingBalance);?></b></td>
             </tr>
             
           <?php $showBalance = $openingBalance; } ?>  
          <tr>
               <td id="bsl"><?php echo $sl;?></td>
               <td id="bso"><?php echo $date;?></td>
               <td id="bso"><?php echo $duedate;?></td>
               <td><?php echo $idet;?></td>
               <td><?php echo $customer;?></td>
               <td><?php echo $inv;?></td>
               <td><?php 
                    if (substr($idet, 0, 2) === 'JV') {
                        if($description != NULL) { echo $description; } else { echo 'Journal Voucher'; }
                    } else if(substr($idet, 0, 2) === 'PV') {
                        echo 'Payment Voucher';
                    } else if(substr($idet, 0, 2) === 'RF') {
                        echo 'Refund';
                    } else if(substr($idet, 0, 2) === 'CN') {
                        echo 'Credit Note';
                    } else if(substr($idet, 0, 2) === 'PR') {
                        echo 'Profit Accured';
                    }
               ?></td>
               <td><?php echo $note;?></td>
               <td id="bso" style="text-align: right;"><?php echo custom_money_format('%!i', $dr);?></td>
               <td id="bdate" style="text-align: right;"><?php echo custom_money_format('%!i', $cr);?></td>
               
               <td id="bso" style="text-align: right;"><?php
               if($cat_entry == 'Cr') {
                   $showBalance = ($showBalance+$cr)-$dr;
               } else if($cat_entry == 'Dr') {
                   $showBalance = ($showBalance+$dr)-$cr;
               }
                  $showBalance = number_format($showBalance, 2, '.', '');
                  echo custom_money_format('%!i', $showBalance);
               ?></td>     
          </tr>
          
		<?php
            $sl=$sl+1;  
            $totaldebit=$totaldebit+$dr;
            $totalcredit=$totalcredit+$cr;
		    } } else { ?>
             <tr>
                <td colspan="1"><?php ?></td>
                <td colspan="1"><?php echo $fdate;?></td>
                <td colspan="5"></td>
                <td colspan="1"><b>Opening Balance</b></td>
                <?php if($cat_entry == 'Dr') { ?>
                <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $openingBalance);?></b></td>
                <td colspan="1" style="text-align: right;"><b>0.00</b></td>
                <?php } else if($cat_entry == 'Cr') { ?>
                <td colspan="1" style="text-align: right;"><b>0.00</b></td>
                <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $openingBalance);?></b></td>
                <?php } ?>
                <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $openingBalance);?></b></td>
             </tr>
            <?php }
            if($cat_entry == 'Dr') {
                $totaldebit=$totaldebit+$openingBalance;
            } else if($cat_entry == 'Cr') {
                $totalcredit=$totalcredit+$openingBalance;
            }
    		?>
            <?php
            if(mysqli_num_rows($result) > 0)
    		{
            ?>
                  <tr>
                  <td colspan="7"></td>
                  <td><b>Total</b></td>
                  <td style="text-align: right;"><b><?php echo custom_money_format('%!i',$totaldebit);?></b></td>
                  <td style="text-align: right;"><b><?php echo custom_money_format('%!i',$totalcredit);?></b></td>
                  <td style="text-align: right;"><b><?php echo custom_money_format('%!i',$showBalance);?></b></td>
                  </tr>
            <?php } ?>
    </tbody>
</table>
 
 
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>