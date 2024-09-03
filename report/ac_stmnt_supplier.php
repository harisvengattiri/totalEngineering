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
$fdate= '';
$tdate=date('d/m/Y');
$mindate = '01/01/2015';
$customer=$_GET["company"];

               $sqlcust="SELECT name,op_bal from customers where id='$customer'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               $opening=$fetchcust['op_bal'];
               $opening = ($opening != NULL) ? $opening : 0;
               $previous=$opening;
}
if(!empty($_POST))
{
$fdate=$_POST["fdate"];
$tdate=$_POST["tdate"];
$mindate = '01/01/2015';
$customer=$_POST["company"];

               $sqlcust="SELECT name,op_bal from customers where id='$customer'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               $opening=$fetchcust['op_bal'];
               $opening = ($opening != NULL) ? $opening : 0;
               
               $sqlinvbal="SELECT sum(amount) AS predebit FROM expenses WHERE shop='$customer' AND (STR_TO_DATE(expenses.date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(expenses.date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y')) ";
               $resultinv = mysqli_query($conn, $sqlinvbal);
               $rowinv = mysqli_fetch_assoc($resultinv);
               $predebit=$rowinv['predebit'];
               $predebit = ($predebit != NULL) ? $predebit : 0;
               
               $sqlcrdbal="SELECT sum(grand) AS precredit FROM voucher WHERE name='$customer' AND (STR_TO_DATE(voucher.clearance_date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(voucher.clearance_date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y')) AND status= 'Cleared'";
               $resultcrd = mysqli_query($conn, $sqlcrdbal);
               $rowcrd = mysqli_fetch_assoc($resultcrd);
               $precredit=$rowcrd['precredit'];
               $precredit = ($precredit != NULL) ? $precredit : 0;
               
                $sqlmis="SELECT sum(total) AS misamount FROM miscellaneous WHERE customer='$customer' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y')) ";
                $resultmis= mysqli_query($conn, $sqlmis);
                $rowmis = mysqli_fetch_assoc($resultmis);
                $miscamount=$rowmis['misamount'];
                $miscamount = ($miscamount != NULL) ? $miscamount : 0;
               
               $previous = ($predebit+$opening) - ($precredit+$miscamount);
            
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
<h2 style="text-align:center;margin-bottom:1px;"><?php // echo strtoupper($status);?>SUPPLIER STATEMENT</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <span style="font-size:15px;">Supplier: <?php echo $cust;?></span>
          </td>
          <td width="50%" style="text-align:right"><span style="font-size:15px;"> Date: <?php if ($fdate=='') {echo "Since Inception";} else{echo $fdate;}?> - <?php echo $tdate;?></span></td>
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
                  Description
              </th>
              <th style="text-align: center;">
                  Expense
              </th>
              <th style="text-align: center;">
                  Voucher
              </th>
              <th style="text-align: center;">
                  Balance
              </th>
              
<!--              <th>
                  Sales Person
              </th>-->
                      
          </tr>
        </thead>
        <tbody style="font-size:11px;">
		<?php
 
         $sql = "SELECT DATE_FORMAT(`date`,'%d/%m/%Y') as date, `time` as time, `id` as id, `inv` as inv, ROUND(`debit`,2) as debit,  ROUND(`credit`,2) as credit FROM(
                              (SELECT CONCAT('EXP', id) AS id, inv as inv, STR_TO_DATE(`date`,'%d/%m/%Y') as date, `current_timestamp` as time, `amount` as debit, 0 as credit FROM `expenses` WHERE shop = '$customer' AND STR_TO_DATE(expenses.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                              UNION ALL
                              (SELECT CONCAT('PVR', id) AS id, NULL as inv, STR_TO_DATE(`clearance_date`,'%d/%m/%Y') as date, `current_timestamp` as time, 0 as debit, `amount` as credit FROM `voucher` WHERE name ='$customer' AND STR_TO_DATE(voucher.clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND status= 'Cleared')    
                              UNION ALL
                              (SELECT CONCAT('MSC', id) AS id, NULL as inv, STR_TO_DATE(`date`,'%d/%m/%Y') as date, `time` as time, 0 as debit, `total` as credit FROM `miscellaneous` WHERE customer ='$customer' AND STR_TO_DATE(miscellaneous.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                              ) results ORDER BY DATE_FORMAT(`date`, '%Y/%m/%d'),time ASC";
         
                      
        $result = mysqli_query($conn, $sql);
        $totaldebit = $totalcredit = 0;
        if (mysqli_num_rows($result) > 0)
		    {
        $sl=1;
        $bal = 0;
        while($row = mysqli_fetch_assoc($result)) {
               $idet=$row['id'];
               $id=substr($idet, 3);
               $id = sprintf("%04d", $id);
               $credit=$row['credit'];
               $credit = ($credit != NULL) ? $credit : 0;
               $debit=$row['debit'];
               $debit = ($debit != NULL) ? $debit : 0;
               
               $date=$row['date'];
               $time=$row['time'];
               $idet=substr($idet, 0, 3).'|'.sprintf("%04d", substr($idet, 3));
               if (substr($idet, 0, 3) === 'EXP')
               {
                    $idet='INV|'.$row['inv'];
               }
          ?>
             
           <?php if($sl==1) { ?>
             <tr>
              <td colspan="1"><?php ?></td>
              <td colspan="1"><?php ?></td>
              <td colspan="1"><b>Previous Balance</b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $previous); ?></b></td>
              <td colspan="1" style="text-align: right;"><b>0.00</b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $previous); ?></b></td>
             </tr> 
           <?php $bal = $bal + $previous; } ?>    
          <tr>
               <td id="bsl"><?php echo $sl;?></td>
               <td><?php echo $date;?></td>
               <td><?php echo $idet;?></td>
               <td style="text-align: right;"><?php
                 echo $debit;
                 ?></td> 
               <td style="text-align: right;"><?php
                 echo $credit;
                 ?></td>
               <td style="text-align: right;"><?php
                  $bal = $bal+$debit-$credit;
                  echo custom_money_format('%!i', $bal);
                ?></td>     
          </tr>
		<?php
                $sl=$sl+1;  
                $totaldebit=$totaldebit+$debit;
                $totalcredit=$totalcredit+$credit;
		}
                } else { ?>
              <tr>
              <td colspan="1">1</td>
              <td colspan="1"><?php ?></td>
              <td colspan="1"><b>Previous Balance</b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $previous);?></b></td>
              <td colspan="1" style="text-align: right;"><b>0.00</b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $previous);?></b></td>
             </tr> 
             <?php } ?>
             
         <?php if(mysqli_num_rows($result) > 0) { ?>    
          <tr>
              <td colspan="2"></td>
              <td style="text-align: right;"><b>Total</b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i',$totaldebit+$previous);?></b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i',$totalcredit);?></b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i',$bal);?></b></td>
         </tr>
         <?php } ?>
          
        </tbody>
      </table>
      
<?php
     $sql="SELECT * FROM voucher WHERE name='$customer' AND status !='Cleared'";
        $result = mysqli_query($conn, $sql);
        $totalamount = 0;
        if (mysqli_num_rows($result) > 0) 
	      { $sl=1;       
?>


 <table id="tbl1" align="center" style="width:90%;margin-top: 25px;">
        <thead>
          <tr>
              <th>
                  Sl
              </th>
              <th>
                   Date
              </th>
              <th>
                   Cheque No
              </th>
              <th>
                   Due Date
              </th>
              <th>
                   Bank
              </th>
              <th>
                   Status
              </th>
              <th style="width:20%;">
                   Invoice No
              </th>
              <th>
                   Amount
              </th>
          </tr>
        </thead>
        <tbody style="font-size:11px;">
        
             
        <?php  
        while($row = mysqli_fetch_assoc($result)) { 
            $id = $row['id'];

            $amount = $row['amount'];
            $amount = ($amount != NULL) ? $amount : 0;
        ?>     
            
             <tr>
             <td><?php echo $sl;?></td>
             <td><?php echo $row['date'];?></td>
             <td><?php echo $row['checkno'];?></td>
             <td><?php echo $row['duedate'];?></td>
             <td>
                  <?php
                    $bnk=$row['inward'];
                    $sqlbnk="SELECT name FROM customers where id='$bnk'"; 
                    $resultbnk = mysqli_query($conn, $sqlbnk);
                    $rowbnk = mysqli_fetch_assoc($resultbnk);
                  ?>
                  <?php echo $rowbnk['name'];?>
             </td>
             <td><?php echo $row['status'];?></td>
             <td>
                  <?php
                    $sql3="SELECT inv FROM voucher_invoice where payment='$id'"; 
                    $result3 = mysqli_query($conn, $sql3);
                    if (mysqli_num_rows($result3) > 0) 
                    {
                    $sl1=0;
                    while($row3 = mysqli_fetch_assoc($result3)) 
                    {
                    if($sl1 %4 == 0) {echo '<br>';}     
                  ?>
                  INV|<?php echo $row3['inv'];?>
                  <?php $sl1++; } } ?>
             </td>
             <td style="text-align: right;"><?php echo custom_money_format('%!i', $amount);?></td>
             </tr>
             
                <?php 
                $sl=$sl+1; 
                $totalamount=$totalamount+$amount;
                }
                ?>
              <tr>
              <td colspan="6"></td>
              <td><b>Total</b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i',$totalamount);?></b></td>
              </tr>  
              
       </tbody>
 </table>
 <?php } ?>
      

<?php if(isset($_POST['print'])) { ?>
<body onload="window.print()">
<?php } ?>