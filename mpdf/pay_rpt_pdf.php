<?php
  include "../config.php";
  error_reporting(0);

$customer=$_GET["customer"];
$view=$_GET['view'];
?>
  <?php
            if(!empty($customer)) {
               $sqlcust="SELECT name,op_bal from customers where id='$customer'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               $opening=$fetchcust['op_bal'];
               $opening=($opening != NULL) ? $opening : 0;
            }
  ?>
  
<!--<div style="margin-top: -55px;" id="head" align="center"><img src="../images/header.png"></div>-->
<h2 style="text-align:center;margin-bottom:1px;"><?php // echo strtoupper($status);?>Payable Report</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <?php if($customer !=NULL){ ?>
               <span style="font-size:15px;">Supplier: <?php echo $cust;?></span>
               <?php } ?>
          </td>
          <td width="50%" style="text-align:right"><span style="font-size:15px;">Date : <?php echo $today=date("d/m/Y");?></span></td>
     </tr>     
</table>

     <?php
       if($customer=='') {
     ?>

<table id="tbl1" align="center" style="width:90%;">
        <thead>
          <tr>
              <th>
                   Sl
              </th>
              <th>
                  Supplier
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
          </tr>
        </thead>
        <tbody>    
	<?php
          $sql = "SELECT `customer` as customer, `customername` as customername, ROUND(sum(`debit`),2) as debit,  ROUND(sum(`credit`),2) as credit FROM(
                   (SELECT `id` as customer, `name` as customername, `op_bal` as debit, 0 as credit
                   FROM `customers` WHERE type='Supplier')
                   UNION ALL 
                   (SELECT `shop` as customer, '' as customername, `amount` as debit, 0 as credit
                   FROM `expenses`)
                   UNION ALL 
                   (SELECT `name` as customer, '' as customername, 0 as debit, `grand` as credit
                   FROM `voucher` WHERE status='Cleared')
                   )results
                   GROUP BY `customer`  
                   ORDER BY `customername` ASC";
        
        
        $result = mysqli_query($conn, $sql);
        $sl=1;
        $tdebit = 0;
        $tcredit = 0;
        $tbalance = 0;
        if (mysqli_num_rows($result) > 0) {
        $total=0;
        while($row = mysqli_fetch_assoc($result)) {
        ?>  
          <?php $bl = $row["debit"] + $row["credit"]; if($bl != 0) { ?>
          <tr>
             <td><?php echo $sl;?></td>
             <td><a target="_blank"  href="<?php echo $baseurl;?>/report/ac_stmnt_supplier?company=<?php echo $row["customer"];?>"><?php
                  $customer = $row["customer"];
                  $sql2="SELECT name FROM customers where id='$customer'";
                  $query2=mysqli_query($conn,$sql2);
                  $fetch2=mysqli_fetch_array($query2);
                  echo $cust=$fetch2['name'];
             ?></a></td>
             
             <td style="text-align: right;"><?php
                $debit = $row["debit"];
                $debit=($debit != NULL) ? $debit : 0;
                echo $debit;
                $tdebit = $tdebit + $debit;
             ?></td>
             
             <td style="text-align: right;"><?php
               $credit = $row["credit"];
               $credit=($credit != NULL) ? $credit : 0;
               echo $credit;
               $tcredit = $tcredit + $credit;
             ?></td>
             <td style="text-align: right;"><?php
               $balance = $debit - $credit;
               echo $balance = number_format($balance, 2, '.', '');
               $tbalance = $tbalance + $balance;
             ?></td>
          </tr>
            <?php $sl++; } } } ?>
          <tr>
              <td colspan="1"></td>
              <td colspan="1" style="text-align: right;"><b>Total</b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $tdebit);?></b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $tcredit);?></b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $tbalance);?></b></td>   
          </tr>
        </tbody>
      </table>
       <?php } else { ?>

       <table id="tbl1" align="center" style="width:90%;">
        <thead>
          <tr>
              <th>
                   Sl
              </th>
              <th>
                  Supplier
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
          </tr>
        </thead>
        <tbody>
        <?php
         
        $sql = "SELECT ROUND(sum(`debit`),2) as debit,  ROUND(sum(`credit`),2) as credit FROM(
                              (SELECT `amount` as debit, 0 as credit FROM `expenses` WHERE shop = '$customer')
                              UNION ALL 
                              (SELECT 0 as debit, `grand` as credit FROM `voucher` WHERE name ='$customer' AND status='Cleared')    
                              )results ";  
        
        $result = mysqli_query($conn, $sql);
        $sl=1;
        if (mysqli_num_rows($result) > 0) {
        $total=0;
        while($row = mysqli_fetch_assoc($result)) {
        ?>     
             
          <tr>
             <td><?php echo $sl;?></td>
             <td><?php
               echo $cust; 
             ?></td>
             
             <td style="text-align: right;"><?php
                 echo $debit = $row["debit"]+$opening;
             ?></td>
             
             <td style="text-align: right;"><?php
               echo $credit = $row["credit"];
             ?></td>
             <td style="text-align: right;"><?php
               $balance = $debit - $credit;
               echo $balance = number_format($balance, 2, '.', '');
             ?></td>
          </tr>
        <?php $sl++; } } ?>	
          
        </tbody>
      </table>
       <?php } ?>

<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>