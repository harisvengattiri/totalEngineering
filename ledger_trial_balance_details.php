<?php include "config.php";?>
<?php include "includes/menu.php";?>
<?php
    $date1 = $_GET['dt1'];
    $date2 = $_GET['dt2'];
?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
 
<!--REVENUE SECTION STARTS-->
<?php
if($_GET['type']=='rvn'){
?>
    <div class="box-header">
	<span style="float: left;"><h2>Revenues <b>[<?php echo $date1." - ".$date2?>]</b></h2></span>
    </div><br/>
    <div>
      <table class="table m-b-none">
        <thead>
          <tr>
              <th>
                 Invoice No
              </th>
              <th>
                  Date
              </th>
	          <th>
                  Customer
              </th>
              <th>
                  Total
              </th>
              <th>
                  VAT
              </th>
              <th>
                  Grand
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		$sql = "SELECT * FROM invoice WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
              $name1=$row["customer"];
              $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               $total = $row["total"];
               $sum_total[] = $total;
               $vat = $row["vat"];
               $sum_vat[] = $vat;
               $grand = $row["grand"];
               $sum_grand[] = $grand;
        ?>
          <tr>
              <td>INV|<?php echo sprintf("%06d",$row["id"]);?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $cust;?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $total);?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $vat);?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $grand);?></td>
          </tr>
		<?php
		}
		?>
		<tr>
              <td colspan='3'></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_total));?></b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_vat));?></b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_grand));?></b></td>
          </tr>
		<?php
		}
		?>
        </tbody>
      </table>
    </div>
 <?php } ?>
 <!--REVENUE SECTION ENDS -->
 
 <!-- TRANSPORTATION SECTION STARTS -->
<?php
if($_GET['type']=='trp'){
?>
    <div class="box-header">
	<span style="float: left;"><h2>Transportation <b>[<?php echo $date1." - ".$date2?>]</b></h2></span>
    </div><br/>
    <div>
      <table class="table m-b-none">
        <thead>
          <tr>
              <th>
                 Invoice No
              </th>
              <th>
                  Date
              </th>
	          <th>
                  Customer
              </th>
              <th>
                  Trasportation Charge
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		$sql = "SELECT * FROM invoice WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') AND transport > 0 ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
              $name1=$row["customer"];
              $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               $transport = $row["transport"];
               $sum_transport[] = $transport;
        ?>
          <tr>
              <td>INV|<?php echo sprintf("%06d",$row["id"]);?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $cust;?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $transport);?></td>
          </tr>
		<?php
		}
		?>
		<tr>
              <td colspan='3'></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_transport));?></b></td>
          </tr>
		<?php
		}
		?>
        </tbody>
      </table>
    </div>
 <?php } ?>    
  <!-- TRANSPORTATION SECTION ENDS -->
  
  <!-- MISCELANIOUS SECTION STARTS -->
    <?php
    if($_GET['type']=='mis'){
    ?>
    <div class="box-header">
	<span style="float: left;"><h2>Miscellaneous <b>[<?php echo $date1." - ".$date2?>]</b></h2></span>
    </div><br/>
    <div>
      <table class="table m-b-none">
        <thead>
          <tr>
              <th>
                 Income No
              </th>
              <th>
                  Date
              </th>
	          <th>
                  Customer
              </th>
              <th>
                  Particular
              </th>
              <th>
                  Amount
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		$sql = "SELECT * FROM miscellaneous WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
              $name1=$row["customer"];
              $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               $miscelanious = $row["total"];
               $sum_miscelanious[] = $miscelanious;
        ?>
          <tr>
              <td>MIS|<?php echo sprintf("%06d",$row["id"]);?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $cust;?></td>
              <td><?php echo $row["particulars"];?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $miscelanious);?></td>
          </tr>
		<?php
		}
		?>
		<tr>
              <td colspan='4'></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_miscelanious));?></b></td>
          </tr>
		<?php
		}
		?>
        </tbody>
      </table>
    </div>
 <?php } ?> 
  <!-- MISCELANIOUS SECTION ENDS -->
  
  <!-- DISCOUNT ON RECEIPT SECTION STARTS -->
    <?php
    if($_GET['type']=='dis_rpt'){
    ?>
    <div class="box-header">
	<span style="float: left;"><h2>Discount on Revenue <b>[<?php echo $date1." - ".$date2?>]</b></h2></span>
    </div><br/>
    <div>
      <table class="table m-b-none">
        <thead>
          <tr>
              <th>
                 Receipt No
              </th>
              <th>
                  Date
              </th>
	          <th>
                  Customer
              </th>
              <th>
                  Amount
              </th>
              <th>
                  Discount
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		$sql = "SELECT * FROM reciept WHERE STR_TO_DATE(pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') AND discount > 0 ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
              $name1=$row["customer"];
              $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               $receipt_amt = $row["amount"];
               $receipt_discount = $row["discount"];
               $sum_receipt_amt[] = $receipt_amt;
               $sum_receipt_discount[] = $receipt_discount;
        ?>
          <tr>
              <td>RPT|<?php echo sprintf("%06d",$row["id"]);?></td>
              <td><?php echo $row["clearance_date"];?></td>
              <td><?php echo $cust;?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $receipt_amt);?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $receipt_discount);?></td>
          </tr>
		<?php
		}
		?>
		<tr>
              <td colspan='3'></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_receipt_amt));?></b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_receipt_discount));?></b></td>
          </tr>
		<?php
		}
		?>
        </tbody>
      </table>
    </div>
 <?php } ?> 
  <!-- DISCOUNT ON RECEIPT SECTION ENDS -->
  
  <!-- EXPENSE SECTION STARTS -->
    <?php
    if($_GET['type']=='exp'){
       $cat = $_GET['cat'];
    ?>
    <div class="box-header">
	<span style="float: left;"><h2>Expenses <b>[<?php echo $date1." - ".$date2?>]</b></h2></span>
    </div><br/>
    <div>
      <table class="table m-b-none">
        <thead>
          <tr>
              <th>
                 Expense
              </th>
              <th>
                  Date
              </th>
	          <th>
                  Customer
              </th>
              <th>
                  particular
              </th>
              <th>
                  Amount
              </th>
              <th>
                  VAT
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		$sql = "SELECT * FROM expenses WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') AND category=$cat ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
              $name1=$row["shop"];
              $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               $expense_amt = $row["amt"];
               $expense_vat = $row["vat"];
               $sum_expense_amt[] = $expense_amt;
               $sum_expense_vat[] = $expense_vat;
        ?>
          <tr>
              <td>EXP|<?php echo sprintf("%06d",$row["id"]);?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $cust;?></td>
              <td><?php echo $row["particulars"];?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $expense_amt);?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $expense_vat);?></td>
          </tr>
		<?php
		}
		?>
		<tr>
              <td colspan='4'></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_expense_amt));?></b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_expense_vat));?></b></td>
          </tr>
		<?php
		}
		?>
        </tbody>
      </table>
    </div>
    
    <!--PETTY CASH SECTION-->
    <div class="box-header">
	<span style="float: left;"><h2>Petty Cash <b>[<?php echo $date1." - ".$date2?>]</b></h2></span>
    </div><br/>
      <div>
      <table class="table m-b-none">
        <thead>
          <tr>
              <th>
                 Petty
              </th>
              <th>
                  Date
              </th>
	          <th>
                  Staff
              </th>
              <th>
                  Description
              </th>
              <th>
                  Amount
              </th>
              <th>
                  VAT
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		$sql = "SELECT * FROM petty_item WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') AND type=$cat ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
              $name1=$row["staff"];
              $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               $petty_amt = $row["amount"];
               $petty_vat = $row["vat"];
               $sum_petty_amt[] = $petty_amt;
               $sum_petty_vat[] = $petty_vat;
        ?>
          <tr>
              <td>PTC|<?php echo sprintf("%06d",$row["id"]);?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $cust;?></td>
              <td><?php echo $row["description"];?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $petty_amt);?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $petty_vat);?></td>
          </tr>
		<?php
		}
		?>
		<tr>
              <td colspan='4'></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_petty_amt));?></b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_petty_vat));?></b></td>
          </tr>
		<?php
		}
		?>
        </tbody>
      </table>
    </div>
 <?php } ?> 
  <!-- EXPENSE SECTION ENDS -->
  
  <!-- EXPENSE VAT SECTION STARTS -->
    <?php
    if($_GET['type']=='exp_vat'){
    ?>
    <div class="box-header">
	<span style="float: left;"><h2>Tax Receivables <b>[<?php echo $date1." - ".$date2?>]</b></h2></span>
    </div><br/>
    <div>
      <table class="table m-b-none">
        <thead>
          <tr>
              <th>
                 Expense
              </th>
              <th>
                  Date
              </th>
	          <th>
                  Customer
              </th>
              <th>
                  particular
              </th>
              <th>
                  Amount
              </th>
              <th>
                  VAT
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		$sql = "SELECT * FROM expenses WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') AND vat > 0 ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
              $name1=$row["shop"];
              $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               $expense_amt = $row["amt"];
               $expense_vat = $row["vat"];
               $sum_expense_amt[] = $expense_amt;
               $sum_expense_vat[] = $expense_vat;
        ?>
          <tr>
              <td>RPT|<?php echo sprintf("%06d",$row["id"]);?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $cust;?></td>
              <td><?php echo $row["particulars"];?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $expense_amt);?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $expense_vat);?></td>
          </tr>
		<?php
		}
		?>
		<tr>
              <td colspan='4'></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_expense_amt));?></b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_expense_vat));?></b></td>
          </tr>
		<?php
		}
		?>
        </tbody>
      </table>
    </div>
 <?php } ?> 
  <!-- EXPENSE VAT SECTION ENDS -->
  
  <!-- ADVANCE SECTION STARTS -->
    <?php
    if($_GET['type']=='adv'){
    ?>
    <div class="box-header">
	<span style="float: left;"><h2>Advance Balance <b>[<?php echo $date1." - ".$date2?>]</b></h2></span>
    </div><br/>
    <div>
      <table class="table m-b-none">
        <thead>
          <tr>
              <th>
                 Sl No
              </th>
	          <th>
                  Customer
              </th>
              <th>
                  Advance Amount
              </th>
              <th>
                  Invoiced Advance
              </th>
              <th>
                  Advance Balance
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
        $sql = "SELECT sum(grand) as total_advance,customer FROM reciept WHERE type='2' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') GROUP BY customer";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{$sl=1;
        while($row = mysqli_fetch_assoc($result)) {
              $name1=$row["customer"];
              $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               $total_advance = $row["total_advance"];
               $sum_advance_amt[] = $total_advance;
               
               $sql1 = "SELECT sum(reciept_invoice.total) as in_advance_cust FROM reciept
                         INNER JOIN
                         reciept_invoice ON reciept.id = reciept_invoice.reciept_id WHERE reciept.type='2' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') AND STR_TO_DATE(reciept_invoice.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') AND reciept.customer=$name1";
                $result1 = mysqli_query($conn, $sql1);
                $row1 = mysqli_fetch_assoc($result1);
                $in_advance_cust=$row1["in_advance_cust"];
                $sum_advance_cust[] = $in_advance_cust;
                
                $advance_balance = $total_advance-$in_advance_cust;
                $sum_advance_balance[] = $advance_balance;
        ?>
          <tr>
              <td><?php echo $sl++;?></td>
              <td><?php echo $cust;?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $total_advance);?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $in_advance_cust);?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $advance_balance);?></td>
          </tr>
		<?php
		}
		?>
		<tr>
              <td colspan='2'></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_advance_amt));?></b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_advance_cust));?></b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_advance_balance));?></b></td>
          </tr>
		<?php
		}
		?>
        </tbody>
      </table>
    </div>
 <?php } ?>
  <!-- ADVANCE SECTION ENDS -->
  
    <!-- PETTY SECTION ENDS -->
    <?php
    if($_GET['type']=='petty'){
    ?>
    <div class="box-header">
	<span style="float: left;"><h2>Petty Cash <b>[<?php echo $date1." - ".$date2?>]</b></h2></span>
    </div><br/>
    <div>
      <table class="table m-b-none">
        <thead>
          <tr>
              <th>
                 Petty
              </th>
              <th>
                  Date
              </th>
	          <th>
                  Staff
              </th>
              <th>
                  particular
              </th>
              <th>
                  Amount
              </th>
              <th>
                  VAT
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		$sql = "SELECT * FROM petty_item WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
              $name1=$row["staff"];
              $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               $petty_amt = $row["amount"];
               $petty_vat = $row["vat"];
               $sum_petty_amt[] = $petty_amt;
               $sum_petty_vat[] = $petty_vat;
        ?>
          <tr>
              <td>PTC|<?php echo sprintf("%06d",$row["id"]);?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $cust;?></td>
              <td><?php echo $row["description"];?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $petty_amt);?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $petty_vat);?></td>
          </tr>
		<?php
		}
		       $sum_petty_amt = array_sum($sum_petty_amt);
               $sum_petty_vat = array_sum($sum_petty_vat);
		?>
		 <tr>
              <td colspan='4'></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', $sum_petty_amt);?></b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', $sum_petty_vat);?></b></td>
          </tr>
          <?php
          $sql1="SELECT sum(amount) as petty_voucher FROM petty_voucher WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
          $result1 = mysqli_query($conn, $sql1);
          $row1 = mysqli_fetch_assoc($result1);
          $petty_voucher=$row1["petty_voucher"];
          $total_petty = $sum_petty_amt+$sum_petty_vat;
          $petty_balance = $petty_voucher-$total_petty;
          ?>
          <tr>
              <td colspan='3'></td>
              <td style="text-align: right;"><b>Total Voucher And Petty Cash</b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', $petty_voucher);?></b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', $total_petty);?></b></td>
          </tr>
          <tr>
              <td colspan='4'></td>
              <td><b>Balance</b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', $petty_balance);?></b></td>
          </tr>
		<?php
		}
		?>
        </tbody>
      </table>
    </div>
 <?php } ?> 
    <!-- PETTY SECTION ENDS -->
    <!-- UNCLEARED RECEIPT SECTION STARTS -->
    <?php
    if($_GET['type']=='uncl_rcp'){
    ?>
    <div class="box-header">
	<span style="float: left;"><h2>Uncleared Receipts <b>[<?php echo $date1." - ".$date2?>]</b></h2></span>
    </div><br/>
    <div>
      <table class="table m-b-none">
        <thead>
          <tr>
              <th>
                 Receipt
              </th>
              <th>
                  Date
              </th>
              <th>
                  Method
              </th>
	          <th>
                  Customer
              </th>
              <th>
                  Amount
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		$sql = "SELECT * FROM reciept WHERE status='Uncleared' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')
		        UNION ALL
		        SELECT * FROM reciept WHERE status='Cleared' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') AND STR_TO_DATE(reciept.clearance_date, '%d/%m/%Y') NOT BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')
		        ";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0)
		{
        while($row = mysqli_fetch_assoc($result)) {
              $name1=$row["customer"];
              $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               $uncleared_amt = $row["amount"];
               $sum_uncleared_amt[] = $uncleared_amt;
        ?>
          <tr>
              <td>RPT|<?php echo sprintf("%06d",$row["id"]);?></td>
              <td><?php echo $row["pdate"];?></td>
              <td><?php echo $row["pmethod"];?></td>
              <td><?php echo $cust;?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $uncleared_amt);?></td>
          </tr>
		<?php
		}
		?>
		<tr>
              <td colspan='4'></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_uncleared_amt));?></b></td>
          </tr>
		<?php
		}
		?>
        </tbody>
      </table>
    </div>
 <?php } ?>   
 <!-- UNCLEARED RECEIPT SECTION ENDS -->
 
  <!-- BOUNCE RECEIPT SECTION STARTS -->
    <?php
    if($_GET['type']=='rcp_bnc'){
    ?>
    <div class="box-header">
	<span style="float: left;"><h2>Bounce Receipts <b>[<?php echo $date1." - ".$date2?>]</b></h2></span>
    </div><br/>
    <div>
      <table class="table m-b-none">
        <thead>
          <tr>
              <th>
                 Receipt
              </th>
              <th>
                  Date
              </th>
              <th>
                  Method
              </th>
	          <th>
                  Customer
              </th>
              <th>
                  Amount
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		$sql = "SELECT * FROM reciept WHERE status='Bounce' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0)
		{
        while($row = mysqli_fetch_assoc($result)) {
              $name1=$row["customer"];
              $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               $uncleared_amt = $row["amount"];
               $sum_uncleared_amt[] = $uncleared_amt;
        ?>
          <tr>
              <td>RPT|<?php echo sprintf("%06d",$row["id"]);?></td>
              <td><?php echo $row["pdate"];?></td>
              <td><?php echo $row["pmethod"];?></td>
              <td><?php echo $cust;?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $uncleared_amt);?></td>
          </tr>
		<?php
		}
		?>
		<tr>
              <td colspan='4'></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_uncleared_amt));?></b></td>
          </tr>
		<?php
		}
		?>
        </tbody>
      </table>
    </div>
 <?php } ?> 
  <!-- BOUNCE RECEIPT SECTION ENDS -->
  
  <!-- HOLD RECEIPT SECTION STARTS -->
    <?php
    if($_GET['type']=='rcp_hld'){
    ?>
    <div class="box-header">
	<span style="float: left;"><h2>Hold Receipts <b>[<?php echo $date1." - ".$date2?>]</b></h2></span>
    </div><br/>
    <div>
      <table class="table m-b-none">
        <thead>
          <tr>
              <th>
                 Receipt
              </th>
              <th>
                  Date
              </th>
              <th>
                  Method
              </th>
	          <th>
                  Customer
              </th>
              <th>
                  Amount
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		$sql = "SELECT * FROM reciept WHERE status='Hold' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0)
		{
        while($row = mysqli_fetch_assoc($result)) {
              $name1=$row["customer"];
              $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               $uncleared_amt = $row["amount"];
               $sum_uncleared_amt[] = $uncleared_amt;
        ?>
          <tr>
              <td>RPT|<?php echo sprintf("%06d",$row["id"]);?></td>
              <td><?php echo $row["pdate"];?></td>
              <td><?php echo $row["pmethod"];?></td>
              <td><?php echo $cust;?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $uncleared_amt);?></td>
          </tr>
		<?php
		}
		?>
		<tr>
              <td colspan='4'></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_uncleared_amt));?></b></td>
          </tr>
		<?php
		}
		?>
        </tbody>
      </table>
    </div>
 <?php } ?> 
  <!-- HOLD RECEIPT SECTION ENDS -->
  
    <!-- CREDIT NOTE SECTION STARTS -->
    <?php
    if($_GET['type']=='crdt_not'){
    ?>
    <div class="box-header">
	<span style="float: left;"><h2>Credit Notes <b>[<?php echo $date1." - ".$date2?>]</b></h2></span>
    </div><br/>
    <div>
      <table class="table m-b-none">
        <thead>
          <tr>
              <th>
                 Crdt No
              </th>
              <th>
                  Date
              </th>
              <th>
                  Invoice
              </th>
	          <th>
                  Customer
              </th>
              <th>
                  Amount
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		$sql = "SELECT * FROM credit_note WHERE STR_TO_DATE(credit_note.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0)
		{
        while($row = mysqli_fetch_assoc($result)) {
              $name1=$row["customer"];
              $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               $crdt_amt = $row["total"];
               $sum_crdt_amt[] = $crdt_amt;
        ?>
          <tr>
              <td>RPT|<?php echo sprintf("%06d",$row["id"]);?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $row["invoice"];?></td>
              <td><?php echo $cust;?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $crdt_amt);?></td>
          </tr>
		<?php
		}
		?>
		<tr>
              <td colspan='4'></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_crdt_amt));?></b></td>
          </tr>
		<?php
		}
		?>
        </tbody>
      </table>
    </div>
 <?php } ?>     
    <!-- CREDIT NOTE SECTION ENDS -->
    
     <!-- PENDING RECEIPT SECTION STARTS -->
    <?php
    if($_GET['type']=='csts'){
    ?>
    <div class="box-header">
	<span style="float: left;"><h2>Customers <b>[<?php echo $date1." - ".$date2?>]</b></h2></span>
    </div><br/>
    <div>
      <table class="table m-b-none">
        <thead>
          <tr>
              <th>
                 Customer
              </th>
              <th>
                  Amount
              </th>
              <th>
                  Balance
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		$sql = "SELECT id,name,op_bal FROM customers WHERE type='Company'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0)
		{
        while($row = mysqli_fetch_assoc($result)) 
        { $bal=$cr=$dr=$dr1=0;
           $opng = $row["op_bal"];
           $customer = $row["id"];
           $sql1 = "SELECT id,amount FROM (
                    (SELECT CONCAT('INV', id) AS id, sum(grand) AS amount FROM invoice WHERE customer='$customer' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('01/01/2018', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    UNION ALL
                    (SELECT CONCAT('RCP', id) AS id, sum(grand) as amount FROM reciept WHERE customer='$customer' AND STR_TO_DATE(pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('01/01/2018', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    UNION ALL
                    (SELECT CONCAT('CRT', id) AS id, sum(total) as amount FROM credit_note WHERE customer='$customer' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('01/01/2018', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    )results ";
            $query1 = mysqli_query($conn,$sql1);
            while($row1 = mysqli_fetch_array($query1))
            {  
              $idet=$row1["id"];
              if (substr($idet, 0, 3) === 'INV')
              {
              $cr=$row1["amount"];
              }
              else if (substr($idet, 0, 3) === 'RCP')
              {
              $dr=$row1["amount"];
              }
              else if (substr($idet, 0, 3) === 'CRT')
              {
              $dr1=$row1["amount"];
              }
            } 
            $bal = ($opng+$cr)-($dr+$dr1);
            $sum_bal_amt[]=$bal;
        ?>
          <tr>
              <td><?php echo $row["name"];?></td>
              <td><?php echo $row["op_bal"];?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $bal);?></td>
          </tr>
		<?php
		}
		?>
		<tr>
              <td colspan='2'></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_bal_amt));?></b></td>
          </tr>
		<?php
		}
		?>
        </tbody>
      </table>
    </div>
 <?php } ?>   
     <!-- PENDING RECEIPT SECTION ENDS -->
     
     
     <!-- DISCOUNT VOUCHER SECTION STARTS -->
    <?php
    if($_GET['type']=='dis_vch'){
    ?>
    <div class="box-header">
	<span style="float: left;"><h2>Discount on Voucher <b>[<?php echo $date1." - ".$date2?>]</b></h2></span>
    </div><br/>
    <div>
      <table class="table m-b-none">
        <thead>
          <tr>
              <th>
                 Id
              </th>
	          <th>
                  Date
              </th>
              <th>
                  Name
              </th>
              <th>
                  Discount
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		$sql = "SELECT discount as voucher_discount FROM voucher WHERE STR_TO_DATE(voucher.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0)
		{
        while($row = mysqli_fetch_assoc($result)) {
              $name1=$row["name"];
              $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               $discount_vch = $row["discount"];
               $sum_discount_vch[] = $discount_vch;
        ?>
          <tr>
              <td>RPT|<?php echo sprintf("%06d",$row["id"]);?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $cust;?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $discount_vch);?></td>
          </tr>
		<?php
		}
		?>
		<tr>
              <td colspan='3'></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i', array_sum($sum_discount_vch));?></b></td>
          </tr>
		<?php
		}
		?>
        </tbody>
      </table>
    </div>
 <?php } ?>  
     <!-- DISCOUNT VOUCHER SECTION ENDS -->
    
    
  </div>
</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->

<?php include "includes/footer.php";?>
