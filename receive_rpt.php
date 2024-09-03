<?php include "config.php";?>
<?php include "includes/menu.php";?>
<?php error_reporting(0); ?>

<script>
function target_popup(form) {
    window.open('', 'formpopup', 'width=1350,height=650,resizeable,scrollbars');
    form.target = 'formpopup';
}
</script>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">  
 <div style="" id="batch" class="row">
    <div class="col-md-6">
      <div class="box">
        <div class="box-header">
          <h2>Generate Receivable Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="" method="post" onsubmit="target_popup(this)">
            <div class="form-group row">
              <label for="customer" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-6">
		<select name="customer" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
                       $sql="SELECT * FROM customers WHERE type='Company' ORDER BY name";
                       $result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0)
				{
                                ?><option value="">ALL</option><?php     
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
				<?php 
				}}
                    ?>
                 </select>		
              </div>
            </div>
            <div class="form-group row">  
                 <div class="col-sm-3"></div>
                 <label align="" class="col-sm-4 form-control-label" style="color:red;">View all</label>
                 <div class="col-sm-2" style="padding-top: 10px;">
                 <input type="checkbox" style="width:16px; height:16px;" name="view" value="yes">   
                 </div>
                 <div class="col-sm-3"></div>
            </div> 
            
               <div class="form-group row m-t-md">
               <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <!--<button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>-->
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
                <button type="submit" formaction="<?php echo $cdn_url;?>/reports/receive_rpt" class="btn btn-sm btn-outline rounded b-success text-success">Print</button>
              </div>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>     
     

     <?php if(isset($_POST['submit'])) 
     {

          $view=$_POST['view'];
          $customer=$_POST['customer'];
          $sql2="SELECT name,op_bal FROM customers where id='$customer'";
                  $query2=mysqli_query($conn,$sql2);
                  $fetch2=mysqli_fetch_array($query2);
                  $cust=$fetch2['name'];
                  $opening=$fetch2['op_bal'];
                  $opening = ($opening != NULL) ? $opening : 0;
                  
     ?>

     <?php
       if($customer=='') {
     ?>
     
     <div class="col-md-12">
      <div class="box">
        <div class="box-header">
             <h2>Date : <?php echo $today=date("d/m/Y");?></h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          
        <table class="table m-b-none" data-filter="#filter" data-page-size="<?php // echo $list_count;?>">
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
                   
                   UNION ALL 
                   (SELECT `customer` as customer, '' as customername, `amount` as debit, 0 as credit, 0 as unclear
                   FROM `refund`)
                   
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
  
          <?php $bl = $row["debit"] + $row["credit"]; if($bl != 0) { ?>
          
          <?php if($view != 'yes') {
             $bl1 = $row["debit"] - $row["credit"]; 
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
        </div>
      </div>
    </div>
       <?php } else { ?> 
     <!--testing-->
     
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
             <h2>Date: <?php echo $today = date("d/m/Y");?></h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          
        <table class="table m-b-none" data-filter="#filter" data-page-size="<?php // echo $list_count;?>">
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
                              
                              UNION ALL
                              (SELECT STR_TO_DATE(`date`,'%d/%m/%Y') as date, `amount` as debit, 0 as credit, 0 as unclear
                              FROM `refund` WHERE customer = '$customer')
                              
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
             
             <td style="text-align: right;">
             <?php
                    $debit1 = $row["debit"];
                    $debit1 = ($debit1 != NULL) ? $debit1 : 0;
               echo $debit = $debit1 + $opening;
             ?></td>
             
             <td style="text-align: right;">
             <?php
               echo $credit = $row["credit"];
             ?></td>
             <td style="text-align: right;">
             <?php
               $balance = $debit - $credit;
               echo $balance = number_format($balance, 2, '.', '');
             ?></td>
             <td style="text-align: right;">
             <?php
               $unclear = $row["unclear"];
               echo $unclear = number_format($unclear, 2, '.', '');
             ?></td>
          </tr>
        <?php $sl++; } } ?>	
          
        </tbody>
      </table>
             
             
        </div>
      </div>
    </div> 
       <?php } } ?> 
</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->
  
<?php include "includes/footer.php";?>