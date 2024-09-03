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
<h2 style="text-align:center;margin-bottom:1px;">Receivable Report</h2>
<table align = "center" style="width:90%;">
     <tr>
          <td width="50%">
               <?php if($customer !=NULL) { ?>
               <span style="font-size:15px;">Customer: <?php echo $cust;?></span>
               <?php } ?>
          </td>
          <td width="50%" style="text-align:right"><span style="font-size:15px;">Date : <?php echo $today=date("d/m/Y");?></span></td>
     </tr>     
</table>

     <?php
       if($customer == '') {
     ?>

<table id="tbl1" align="center" style="width:90%;">
        <thead>
          <tr>
              <th>
                   Sl
              </th>
              <th>
                  Customer
              </th>
              <th style="text-align: center;">
                   Debit
              </th>
              <th style="text-align: center;">
                  Credit
              </th>
              <th style="text-align: center;">
                  Balance
              </th>
              <th style="text-align: center;">
                  Uncleared Cheque
              </th>
          </tr>
        </thead>
        <tbody>    
	<?php
          $sql = "SELECT `customer` as customer, `customername` as customername, ROUND(sum(`debit`),2) as debit,  ROUND(sum(`credit`),2) as credit,  ROUND(sum(`unclear`),2) as unclear FROM(
                   (SELECT `id` as customer, `name` as customername, `op_bal` as debit, 0 as credit, 0 as unclear
                   FROM `customers` WHERE type='Company')
                   UNION ALL 
                   (SELECT `customer` as customer, '' as customername, `grand` as debit, 0 as credit, 0 as unclear
                   FROM `invoice`)
                   UNION ALL 
                   (SELECT `customer` as customer, '' as customername, 0 as debit, `grand` as credit, 0 as unclear
                   FROM `reciept` WHERE status='Cleared')
                   UNION ALL 
                   (SELECT `customer` as customer, '' as customername, 0 as debit, `total` as credit, 0 as unclear
                   FROM `credit_note`)
                   UNION ALL 
                   (SELECT `customer` as customer, '' as customername, 0 as debit, 0 as credit, `grand` as unclear
                   FROM `reciept` WHERE status!='Cleared')
                   )results
                   GROUP BY `customer`  
                   ORDER BY `customername` ASC";
        
        
        $result = mysqli_query($conn, $sql);
        $sl=1;
        $tdebit = 0;
        $tcredit = 0;
        $tbalance = 0;
        $tunclear = 0;
        if (mysqli_num_rows($result) > 0) {
        $total=0;
        while($row = mysqli_fetch_assoc($result)) {
        ?>  
          <?php
          $debit = $row["debit"];
          $credit = $row["credit"];
           $bl = $debit + $credit;
            if($bl != 0) {
          ?>
          
          <?php if($view != 'yes') { 
             $bl1 = $debit - $credit;
             if($bl1 > 0) {   
          ?>
          <tr>
             <td><?php echo $sl;?></td>
             <td><a target="_blank"  href="<?php echo $baseurl;?>/report/ac_stmnt?company=<?php echo $row["customer"];?>"><?php
                  $customer = $row["customer"];
                  $sql2="SELECT name FROM customers where id='$customer'";
                  $query2=mysqli_query($conn,$sql2);
                  $fetch2=mysqli_fetch_array($query2);
                  echo $cust=$fetch2['name'];
             ?></a></td>
             
             <td style="text-align: right;"><?php
                echo $debit;
                $tdebit = $tdebit + $debit;
             ?></td>
             
             <td style="text-align: right;"><?php
               echo $credit;
               $tcredit = $tcredit + $credit;
             ?></td>
             <td style="text-align: right;"><?php
               $balance = $debit - $credit;
               echo $balance = number_format($balance, 2, '.', '');
               $tbalance = $tbalance + $balance;
             ?></td>
             <td style="text-align: right;"><?php
               $unclear = $row["unclear"];
               echo $unclear = number_format($unclear, 2, '.', '');
               $tunclear = $tunclear + $unclear;
             ?></td>
          </tr>
            <?php $sl++; } } else { ?>
          <tr>
             <td><?php echo $sl;?></td>
             <td><a target="_blank"  href="<?php echo $baseurl;?>/report/ac_stmnt?company=<?php echo $row["customer"];?>"><?php
                  $customer = $row["customer"];
                  $sql2="SELECT name FROM customers where id='$customer'";
                  $query2=mysqli_query($conn,$sql2);
                  $fetch2=mysqli_fetch_array($query2);
                  echo $cust=$fetch2['name'];
             ?></a></td>
             
             <td style="text-align: right;"><?php
                echo $debit = $row["debit"];
                $tdebit = $tdebit + $debit;
             ?></td>
             
             <td style="text-align: right;"><?php
               echo $credit = $row["credit"];
               $tcredit = $tcredit + $credit;
             ?></td>
             <td style="text-align: right;"><?php
               $balance = $debit - $credit;
               echo $balance = number_format($balance, 2, '.', '');
               $tbalance = $tbalance + $balance;
             ?></td>
             <td style="text-align: right;"><?php
               $unclear = $row["unclear"];
               echo $unclear = number_format($unclear, 2, '.', '');
               $tunclear = $tunclear + $unclear;
             ?></td>
          </tr>
            <?php $sl++; } } } } ?>
          <tr>
              <td colspan="1"></td>
              <td colspan="1" style="text-align: right;"><b>Total</b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $tdebit);?></b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $tcredit);?></b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $tbalance);?></b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $tunclear);?></b></td>
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
                  Customer
              </th>
              <th style="text-align: center;">
                   Debit
              </th>
              <th style="text-align: center;">
                  Credit
              </th>
              <th style="text-align: center;">
                  Balance
              </th>
              <th style="text-align: center;">
                  Uncleared Cheque
              </th>
          </tr>
        </thead>
        <tbody>
        <?php
//        $sql = "SELECT rep FROM delivery_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$prevmonth1', '%d/%m/%Y') AND STR_TO_DATE('$today', '%d/%m/%Y') GROUP BY rep ORDER BY rep";
        
               $sql = "SELECT ROUND(sum(`debit`),2) as debit, ROUND(sum(`credit`),2) as credit, ROUND(sum(`unclear`),2) as unclear FROM(
                              (SELECT STR_TO_DATE(`date`,'%d/%m/%Y') as date, `grand` as debit, 0 as credit, 0 as unclear
                              FROM `invoice` WHERE customer = '$customer')
                              UNION ALL 
                              (SELECT STR_TO_DATE(`clearance_date`,'%d/%m/%Y') as date, 0 as debit, `grand` as credit, 0 as unclear
                              FROM `reciept` WHERE customer ='$customer' AND status='Cleared')
                              UNION ALL 
                              (SELECT `date` as date, 0 as debit, `total` as credit, 0 as unclear FROM `credit_note` WHERE customer ='$customer')
                              UNION ALL
                              (SELECT STR_TO_DATE(`clearance_date`,'%d/%m/%Y') as date, 0 as debit, 0 as credit, `grand` as unclear
                              FROM `reciept` WHERE customer ='$customer' AND status!='Cleared')
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
                    $debit1 = $row["debit"];
                    $debit1=($debit1 != NULL) ? $debit1 : 0;
               echo $debit = $debit1 + $opening;
             ?></td>
             
             <td style="text-align: right;"><?php
                  $credit = $row["credit"];
                  $credit=($credit != NULL) ? $credit : 0;
               echo $credit;
             ?></td>
             <td style="text-align: right;"><?php
               $balance = $debit - $credit;
               echo $balance = number_format($balance, 2, '.', '');
             ?></td>
             <td style="text-align: right;">
             <?php
               $unclear = $row["unclear"];
               $unclear=($unclear != NULL) ? $unclear : 0;
               echo $unclear = number_format($unclear, 2, '.', '');
             ?></td>
          </tr>
        <?php $sl++; } } ?>	
          
        </tbody>
      </table>
       <?php } ?>

<?php
if(isset($_POST['print'])) { ?>
<body onload="window.print()">
<?php } ?>