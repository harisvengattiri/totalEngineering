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
          <h2>Generate Payable Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="" method="post" onsubmit="target_popup(this)">
            <div class="form-group row">
              <label for="customer" class="col-sm-2 form-control-label">Supplier</label>
              <div class="col-sm-6">
		<select name="customer" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
                       $sql="SELECT * FROM customers WHERE type='Supplier' ORDER BY name";
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
<!--            <div class="form-group row">  
                 <div class="col-sm-3"></div>
                 <label align="" class="col-sm-4 form-control-label" style="color:red;">View all</label>
                 <div class="col-sm-2" style="padding-top: 10px;">
                 <input type="checkbox" style="width:16px; height:16px;" name="view" value="yes">   
                 </div>
                 <div class="col-sm-3"></div>
            </div> -->
            
               <div class="form-group row m-t-md">
               <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <!--<button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>-->
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
                <button type="submit" formaction="<?php echo $cdn_url;?>/reports/pay_rpt" class="btn btn-sm btn-outline rounded b-success text-success">Print</button>
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
          if(!empty($customer)) {
          $sql2="SELECT name,op_bal FROM customers where id='$customer'";
                  $query2=mysqli_query($conn,$sql2);
                  $fetch2=mysqli_fetch_array($query2);
                  $cust=$fetch2['name'];
                  $opening=$fetch2['op_bal'];
                  $opening=($opening != NULL) ? $opening : 0;
          }
                  
     ?>

     <?php
       if($customer == '') {
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
          $sql = "SELECT `customer` as customer, `customername` as customername, ROUND(sum(`debit`),2) as debit, ROUND(sum(`credit`),2) as credit FROM(
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
            $debit = $row["debit"];
            $debit=($debit != NULL) ? $debit : 0;
            $credit = $row["credit"];
            $credit=($credit != NULL) ? $credit : 0;
            $bl = $debit-$credit;
             if($bl != 0) {
        ?>         
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
//        $sql = "SELECT rep FROM delivery_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$prevmonth1', '%d/%m/%Y') AND STR_TO_DATE('$today', '%d/%m/%Y') GROUP BY rep ORDER BY rep";
        
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
             
             <td style="text-align: right;">
             <?php
                echo $debit = $row["debit"]+$opening;
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