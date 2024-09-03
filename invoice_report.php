<?php include "config.php";?>
<?php include "includes/menu.php";?>

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
          <h2>Report of Delivery Notes to be Invoiced</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="" method="post">
            <div class="form-group row">
             <label align="" class="col-sm-2 form-control-label">Customer</label>    
             <div class="col-sm-8">
               <!--<select name="lot" class="form-control">-->
               <select name="customer" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
				$sql = "SELECT id,name FROM customers where type='company'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
				</select>
              </div>    
                 
                 
<!--              <label for="date" align="right" class="col-sm-1 form-control-label">From</label>
              <div class="col-sm-3">
                <input type="text" name="fdate" id="fdate" placeholder="Start Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
            format: 'DD/MM/YYYY',
            icons: {
              time: 'fa fa-clock-o',
              date: 'fa fa-calendar',
              up: 'fa fa-chevron-up',
              down: 'fa fa-chevron-down',
              previous: 'fa fa-chevron-left',
              next: 'fa fa-chevron-right',
              today: 'fa fa-screenshot',
              clear: 'fa fa-trash',
              close: 'fa fa-remove'
            }
          }">
              </div>-->

<!--              <label for="date" align="right" class="col-sm-1 form-control-label">To</label>
              <div class="col-sm-2">
                <input type="text" name="tdate" id="tdate" placeholder="End Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
            format: 'DD/MM/YYYY',
            icons: {
              time: 'fa fa-clock-o',
              date: 'fa fa-calendar',
              up: 'fa fa-chevron-up',
              down: 'fa fa-chevron-down',
              previous: 'fa fa-chevron-left',
              next: 'fa fa-chevron-right',
              today: 'fa fa-screenshot',
              clear: 'fa fa-trash',
              close: 'fa fa-remove'
            }
          }">
              </div>-->
            </div>
               <div class="form-group row m-t-md">
               <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <!--<button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>-->
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
                <!--<button type="submit" formaction="<?php echo $baseurl;?>/mpdf/convert_report" class="btn btn-sm btn-outline rounded b-success text-success">Print</button>-->
              </div>
              </div>
          </form>
        </div>
      </div>
    </div>
</div>
<div style="" id="batch" class="row">
    <div class="col-md-8">
      <div class="box">
        <div class="box-header">
          <h2>Report of Delivery Notes to be Invoiced [Period]</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/do_noninv_period" method="post" onsubmit="target_popup(this)">
            <div class="form-group row">
                <div class="col-sm-4">
                    <select name="cust_type" class="form-control">
                        <option value="">All Customers</option>
                        <option value="Cash">Cash Customer</option>
                        <option value="Credit">Credit Customer</option>
                    </select>
                </div>
                
                <label for="date" align="right" class="col-sm-1 form-control-label">From</label>
                  <div class="col-sm-3">
                    <input type="text" name="fdate" id="fdate" required placeholder="Start Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
                    format: 'DD/MM/YYYY',
                    icons: {
                      time: 'fa fa-clock-o',
                      date: 'fa fa-calendar',
                      up: 'fa fa-chevron-up',
                      down: 'fa fa-chevron-down',
                      previous: 'fa fa-chevron-left',
                      next: 'fa fa-chevron-right',
                      today: 'fa fa-screenshot',
                      clear: 'fa fa-trash',
                      close: 'fa fa-remove'
                    }
                    }">
                  </div>

              <label for="date" align="right" class="col-sm-1 form-control-label">To</label>
                <div class="col-sm-3">
                    <input type="text" name="tdate" id="tdate" required placeholder="End Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
                    format: 'DD/MM/YYYY',
                    icons: {
                      time: 'fa fa-clock-o',
                      date: 'fa fa-calendar',
                      up: 'fa fa-chevron-up',
                      down: 'fa fa-chevron-down',
                      previous: 'fa fa-chevron-left',
                      next: 'fa fa-chevron-right',
                      today: 'fa fa-screenshot',
                      clear: 'fa fa-trash',
                      close: 'fa fa-remove'
                    }
                  }">
                </div>
            </div>
               <div class="form-group row m-t-md">
               <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="submit_period" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
              </div>
              </div>
          </form>
        </div>
      </div>
    </div>
</div>
  
  
  
     
     
 <div style="" id="batch" class="row">
    <div class="col-md-6">
      <div class="box">
        <div class="box-header">
          <h2>Invoice Summary Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/invoiced" method="post" onsubmit="target_popup(this)">
            <div class="form-group row">
              <label for="date" align="right" class="col-sm-1 form-control-label">From</label>
              <div class="col-sm-5">
                <input type="text" name="fdate" id="fdate" placeholder="Start Date" Required="Required" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
            format: 'DD/MM/YYYY',
            icons: {
              time: 'fa fa-clock-o',
              date: 'fa fa-calendar',
              up: 'fa fa-chevron-up',
              down: 'fa fa-chevron-down',
              previous: 'fa fa-chevron-left',
              next: 'fa fa-chevron-right',
              today: 'fa fa-screenshot',
              clear: 'fa fa-trash',
              close: 'fa fa-remove'
            }
          }">
              </div>

              <label for="date" align="right" class="col-sm-1 form-control-label">To</label>
              <div class="col-sm-5">
                <input type="text" name="tdate" id="tdate" placeholder="End Date" Required="Required" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
            format: 'DD/MM/YYYY',
            icons: {
              time: 'fa fa-clock-o',
              date: 'fa fa-calendar',
              up: 'fa fa-chevron-up',
              down: 'fa fa-chevron-down',
              previous: 'fa fa-chevron-left',
              next: 'fa fa-chevron-right',
              today: 'fa fa-screenshot',
              clear: 'fa fa-trash',
              close: 'fa fa-remove'
            }
          }">
              </div>
            </div>
               <div class="form-group row m-t-md">
               <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <!--<button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>-->
                <button name="submit_invoice" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
                <!--<button type="submit" formaction="<?php echo $baseurl;?>/mpdf/convert_report" class="btn btn-sm btn-outline rounded b-success text-success">Print</button>-->
              </div>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>     
     

<?php if(isset($_POST['submit'])) 
    {
        $customer=$_POST['customer'];
        $sqlcust="SELECT name from customers where id='$customer'";
        $querycust=mysqli_query($conn,$sqlcust);
        $fetchcust=mysqli_fetch_array($querycust);
        $cust=$fetchcust['name'];
?>
     <div class="col-md-12">
      <div class="box">
        <div class="box-header">
             <h2>Delivery Notes to be Invoiced of: <?php echo $cust;?></h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          
        <table class="table m-b-none" data-filter="#filter" data-page-size="<?php // echo $list_count;?>">
        <thead>
          <tr>
              <th>
                   Sl No
              </th>
              <th>
                  Date
              </th>
              <th>
                  Customer Site
              </th>
              <th>
                  Sales Order
              </th>
              <th>
                  Delivery Note
              </th>
              <th>
                  Amount
              </th>
              
          </tr>
        </thead>
        <tbody>
             
	<?php
	$sql = "SELECT * FROM delivery_note where customer='$customer' AND invoiced='' AND total > 0";
        $result = mysqli_query($conn, $sql);
        $sl=1;
        if (mysqli_num_rows($result) > 0) {
            $total = 0;
        while($row = mysqli_fetch_assoc($result)) {
            $delivery_amount = $row['total'];
            $delivery_amount_with_VAT = $delivery_amount*1.05;
        ?>  
          <tr>
             <td><?php echo $sl;?></td>
             <td><?php echo $row["date"];?></td>
             <?php
             $site=$row['customersite']; 
             $sqlsite="SELECT p_name from customer_site where id='$site'";
               $querysite=mysqli_query($conn,$sqlsite);
               $fetchsite=mysqli_fetch_array($querysite);
               $site1=$fetchsite['p_name'];
             ?>
             <td><?php echo $site1;?></td>
             <td><?php echo $row['order_referance'];?></td>
             <td><?php echo sprintf('%06d',$row['id']);?></td>
             <td style="text-align:right;padding-right: 4%;"><?php echo custom_money_format('%!i', $delivery_amount_with_VAT);?></td>
          </tr>
		<?php
		$total = $total + $delivery_amount_with_VAT;
            $sl++;  
		}
		}
		?>
        </tbody>
        <tfooter>
            <tr>
             <td colspan="5">
             <td style="text-align:right;padding-right: 4%;"><b><?php echo custom_money_format('%!i', $total);?></b></td>
            </tr>
        </tfooter>
      </table>
             
             
        </div>
      </div>
    </div>    
<?php } ?> 
     
     
<?php if(isset($_POST['submit_invoice'])) 
    {
    $fdate=$_POST['fdate'];
    $tdate=$_POST['tdate'];
?>
     <div class="col-md-12">
      <div class="box">
        <div class="box-header">
             <h2>Invoiced Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          
        <table class="table m-b-none" data-filter="#filter" data-page-size="<?php // echo $list_count;?>">
        <thead>
          <tr>
              <th>
                   Sl No
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
                  Customer Site
              </th>
              <th>
                  Purchase Order
              </th>
              <th>
                  Quantity
              </th>
               <th>
                  Invoice Amount
              </th>
              
              
          </tr>
        </thead>
        <tbody>
             
	<?php
        $sql = "SELECT * FROM invoice WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
        $result = mysqli_query($conn, $sql);
        $sl=1;
        if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
        ?>  
          <tr>
             <td><?php echo $sl;?></td>
             <td><?php echo $row["date"];?></td>
             <td><?php echo sprintf('%06d',$row['id']);?></td>
             <td><?php
                  $customer=$row['customer'];
                  $sqlcust="SELECT name from customers where id='$customer'";
                  $querycust=mysqli_query($conn,$sqlcust);
                  $fetchcust=mysqli_fetch_array($querycust);
                  echo $cust=$fetchcust['name'];
             ?></td>
             <td><?php
             $site=$row['site']; 
             $sqlsite="SELECT p_name from customer_site where id='$site'";
               $querysite=mysqli_query($conn,$sqlsite);
               $fetchsite=mysqli_fetch_array($querysite);
               echo $site1=$fetchsite['p_name'];?></td>
             
             <td>PO|<?php echo $or=$row['o_r'];?></td>
             <td><?php
                    $sqlqnt="SELECT sum(quantity) AS qnt FROM order_item where o_r='$or'";
                    $queryqnt=mysqli_query($conn,$sqlqnt);
                    $fetchqnt=mysqli_fetch_array($queryqnt);
                    echo $qnt=$fetchqnt['qnt'];
             ?></td>
             <td><?php echo $row['grand'];?></td>
<!--             <td><?php // echo $quantity;?></td>
             <td><?php // echo $delquan;?></td>-->
             <!--<td><?php echo $lotquan;?></td>-->
          </tr>
		<?php
                $sl++;  
		}
		}
		?>
        </tbody>
      </table>
             
             
        </div>
      </div>
    </div>    
    <?php } ?> 
     
     

</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->
  
<?php include "includes/footer.php";?>