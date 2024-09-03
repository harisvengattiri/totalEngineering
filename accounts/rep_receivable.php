<?php
  include "../config.php";
//   error_reporting(0);
  session_start();
if(!isset($_SESSION['userid']))
{
   header("Location:$baseurl/login/");
}
?>
<?php
if(!empty($_POST))
{
$fdate=$_POST["fdate"];
$tdate=$_POST["tdate"];
$mindate = '01/01/2015';
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
<h2 style="text-align:center;margin-bottom:1px;">RECEIVABLE STATEMENT</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <!--<span style="font-size:15px;">Account: <?php // echo $cat_name;?><br>Sub Account: <?php // echo $sub_cat_name;?></span>-->
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
                  Serial
              </th>
              <th>
                  Customer
              </th>
              <th>
                  Invoice / Receipt
              </th>
              <th>
                  Date
              </th>
              <th style="text-align: right;">
                  Invoiced Amount
              </th>
              <th style="text-align: right;">
                  Received Amount
              </th>
              <th style="text-align: right;">
                  Balance
              </th>
          </tr>
        </thead>
        <tbody style="font-size:11px;">
		<?php
		    $sl=1;$tot_bal=0;
		    
		    
		    $sql_check_receivable = "
                    SELECT id,cust,amount,date,time FROM (
                        SELECT CONCAT('INO', id) AS id,id AS cust,op_bal AS amount,op_date AS date,'1' AS time FROM `customers` WHERE `type`='Company' AND `op_bal`!= '' AND STR_TO_DATE(`op_date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                        UNION ALL
                        SELECT CONCAT('INV', id) AS id,customer AS cust,grand AS amount,date AS date,time FROM `invoice` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                        UNION ALL
                        SELECT CONCAT('RCP', id) AS id,customer AS cust,grand AS amount,pdate AS date,time FROM `reciept` WHERE STR_TO_DATE(`pdate`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND `pdate`!=''
                        UNION ALL
                        SELECT CONCAT('CNT', id) AS id,customer AS cust,total AS amount,date AS date,time FROM `credit_note` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                        UNION ALL
                        SELECT CONCAT('RFD', id) AS id,customer AS cust,amount AS amount,date AS date,time FROM `refund` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                    ) result ORDER BY STR_TO_DATE(date, '%d/%m/%Y'),time ASC";
            $query_check_receivable = mysqli_query($conn,$sql_check_receivable);
            $cr = $dr = $bal = 0;
            while($result_check_receivable = mysqli_fetch_array($query_check_receivable)) {
                
                $id = $result_check_receivable['id'];
                $idet=substr($id, 0, 3).'|'.sprintf("%06d", substr($id, 3));
                $date = $result_check_receivable['date'];
                $receivable = $result_check_receivable['amount'];
                
                $cust = $result_check_receivable['cust'];
                    $sql_cust = "SELECT name FROM `customers` WHERE id='$cust'";
                    $query_cust = mysqli_query($conn,$sql_cust);
                    $result_cust = mysqli_fetch_array($query_cust);
                    $customer = $result_cust['name'];
                
                if (substr($id, 0, 3) === 'INO') {
                    $cr = $receivable;
                    $cr = ($cr != NULL) ? $cr : 0;
                    $dr = 0;
                    $des = "Opening added for Customer";
                } else if(substr($id, 0, 3) === 'INV') {
                    $cr = $receivable;
                    $cr = ($cr != NULL) ? $cr : 0;
                    $dr = 0;
                    $des = "Invoice";
                } else if (substr($id, 0, 3) === 'RCP') {
                    $dr = $receivable;
                    $dr = ($dr != NULL) ? $dr : 0;
                    $cr = 0;
                    $des = "Receipt";
                } else if (substr($id, 0, 3) === 'CNT') {
                    $dr = $receivable;
                    $dr = ($dr != NULL) ? $dr : 0;
                    $cr = 0;
                    $des = "Credit Note";
                } else if (substr($id, 0, 3) === 'RFD') {
                    $cr = $receivable;
                    $cr = ($cr != NULL) ? $cr : 0;
                    $dr = 0;
                    $des = "Refund";
                }
                $bal = ($bal+$cr)-($dr);
        ?>
        
            <tr>
               <td id="bsl"><?php echo $sl;?></td>
               <td><?php echo $idet;?></td>
               <td><?php echo $customer;?></td>
               <td><?php echo $des;?></td>
               <td id="bso"><?php echo $date;?></td>
               <td style="text-align: right;"><?php echo custom_money_format('%!i', $cr);?></td>
               <td style="text-align: right;"><?php echo custom_money_format('%!i', $dr);?></td>
               <td style="text-align: right;"><?php echo custom_money_format('%!i', $bal);?></td>
          </tr>
        
        <?php $sl++; } ?>
        
        <tr>
            <td colspan="7"></td>
            <td id="bdate" style="text-align: right;"><b><?php echo custom_money_format('%!i', $bal);?></b></td>
        </tr>
        <?php
            if($bal > 0) {
                $receivables = $bal;
                $liability = 0;
            } else {
                $liability = ABS($bal);
                $receivables = 0;
            }
        ?>
        <tr>
            <td colspan="4"></td>
            <td style="text-align: right;"><b>Receivables</b></td>
            <td style="text-align: right;"><b><?php echo custom_money_format('%!i', $receivables);?></b></td>
            <td style="text-align: right;"><b>Liability</b></td>
            <td style="text-align: right;"><b><?php echo custom_money_format('%!i', $liability);?></b></td>
        </tr>
        
    </tbody>
</table>
 
 
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>