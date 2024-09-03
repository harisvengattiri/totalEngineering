<?php include "config.php";?>
<?php include "includes/menu.php";?>

<script>
function target_popup(form) {
    window.open('', 'formpopup', 'width=1350,height=650,resizeable,scrollbars');
    form.target = 'formpopup';
}
</script>
<script>
$(document).ready(function(){
    $("#gocomplete").click(function(){
        $("#complete").toggle();
        $("#cust").hide();
        $("#sitepo").hide();
    });
});
$(document).ready(function(){
    $("#gocust").click(function(){
        $("#cust").toggle();
        $("#complete").hide();
        $("#sitepo").hide();
    });
});
$(document).ready(function(){
    $("#gosite").click(function(){
        $("#sitepo").toggle();
        $("#complete").hide();
        $("#cust").hide();
    });
});
</script>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
     
    <div class="row">
    <div class="col-md-8">
      <div class="box">
        <div class="box-header">
          <h2>Select Your Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
     <div class="box-body">
     
     <button id="gocomplete" type="button" class="btn btn-success">Complete Order Report</button>        
     <!--<button id="gocust" type="button" class="btn btn-success">Customer Order Report</button>-->
     <button id="gosite" type="button" class="btn btn-success">Purchase Order Report</button>
     
     </div></div></div></div>     
     
  
    <div style="display: none;" id="sitepo" class="row">
    <div class="col-md-6">
      <div class="box">
        <div class="box-header">
             <h2>Customer-Site Purchase Order Report 
                  <!--<span style="color:red;">[From & To Dates are mandatory]</span>-->
             </h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/order_site_bal" method="post" onsubmit="target_popup(this)">
            
<!--            <div class="form-group row">
              <label for="date" align="" class="col-sm-1 form-control-label">From</label>
              <div class="col-sm-5">
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
              </div>

              <label for="date" align="right" class="col-sm-1 form-control-label">To</label>
              <div class="col-sm-5">
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
              </div>
            </div>-->
               
              <div class="form-group row">
              <div class="col-sm-6">
               <select name="company" id="customer" class="form-control">
				<?php 
				$sql = "SELECT name,id FROM customers where type='Company' order by name";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                ?> <option value="">CUSTOMER</option> <?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
				</select>
              </div>
              
              <div class="col-sm-6">
                   <select name="site" id="site" class="form-control">
                        <option value="">SITE</option>
                   </select>
              </div>
            </div>
            
            <div class="form-group row">   
            <div class="col-sm-6">
                   <select name="po" id="po" class="form-control">
                        <option value="">LPO</option>
                   </select>
              </div>
            </div>
               
               
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <!--<button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>-->
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
                <!--<button type="submit" formaction="<?php echo $baseurl;?>/mpdf/convert_report_delivery" class="btn btn-sm btn-outline rounded b-success text-success">Print</button>-->
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>   
     
     
     
     
  <div style="display: none;" id="complete" class="row">
    <div class="col-md-8">
      <div class="box">
        <div class="box-header">
          <h2>Order Report 
               <!--<span style="color:red;">[Select Sales Rep and Item in need of Custom Report]</span>-->
          </h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/sales_order" method="post" onsubmit="target_popup(this)">
            
            <div class="form-group row">

                 <label for="date" class="col-sm-2 form-control-label">From<span style="color:red;">*</span></label>
              <div class="col-sm-4">
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
              </div>

                 <label for="date" align="right" class="col-sm-2 form-control-label">To<span style="color:red;">*</span></label>
              <div class="col-sm-4">
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
              </div>
          </div>   
          <div class="form-group row">    
              <label class="col-sm-2 form-control-label">Sales Rep</label>
              <div class="col-sm-4">
               <select name="rep" class="form-control">
				<?php 
				$sql = "SELECT name,id FROM customers where type='SalesRep'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				?>
				<option></option>
				<?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
				</select>
              </div>
              <label align="right" class="col-sm-2 form-control-label">Item</label>
              <div class="col-sm-4">
               <select name="item" class="form-control">
				<?php 
				$sql = "SELECT items,id FROM items";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				?>
				<option></option>
				<?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["items"]?></option>
				<?php 
				}} 
				?>
				</select>
              </div>
            </div>
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <!--<button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>-->
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
                <button type="submit" formaction="<?php echo $baseurl;?>/mpdf/convert_report_order" class="btn btn-sm btn-outline rounded b-success text-success">Print</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
     
     
    <div style="display: none;" id="cust" class="row">
    <div class="col-md-10">
      <div class="box">
        <div class="box-header">
          <h2>Customer Order Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/cust_order" method="post" onsubmit="target_popup(this)">
            
            <div class="form-group row">
                 
             <label class="col-sm-1 form-control-label">Customer</label>
              <div class="col-sm-3">
               <select name="cust" class="form-control">
				<?php 
				$sql = "SELECT name,id FROM customers where type='Company' ORDER BY name";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				?>
				<option></option>
				<?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
		</select>
              </div>
                 
              <label for="date" align="right" class="col-sm-1 form-control-label">From</label>
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
              </div>

              <label for="date" align="right" class="col-sm-1 form-control-label">To</label>
              <div class="col-sm-3">
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
              </div>
            </div>
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <!--<button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>-->
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
                <!--<button type="submit" formaction="<?php echo $baseurl;?>/mpdf/convert_report_delivery" class="btn btn-sm btn-outline rounded b-success text-success">Print</button>-->
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>   
            
</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->
  
<?php include "includes/footer.php";?>