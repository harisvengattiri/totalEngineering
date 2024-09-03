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

  <div class="row">
    <div class="col-md-8">
      <div class="box">
        <div class="box-header">
          <h2>Sales Order Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/sales_order" method="post" onsubmit="target_popup(this)">
            
            <div class="form-group row">

              <label for="date" class="col-sm-2 form-control-label">Start Date</label>
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

              <label for="date" align="right" class="col-sm-2 form-control-label">End Date</label>
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
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

<!--<div class="col-md-6">
      <div class="box">
        <div class="box-header">
          <h2>Bank Cash Flow</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/bank_cash_flow" method="post" onsubmit="target_popup(this)">
            
            <div class="form-group row">

              <label for="date" class="col-sm-2 form-control-label">Start Date</label>
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

              <label for="date" align="right" class="col-sm-2 form-control-label">End Date</label>
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
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>-->
  </div>



  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <h2>Item Sales Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/items" method="post" onsubmit="target_popup(this)">
            
            <div class="form-group row">

              <label for="staff" class="col-sm-1 form-control-label" >Items</label>
              <div class="col-sm-3">
               <select name="items" id="staff" placeholder="Staff" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
				$sql = "SELECT * FROM items";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["items"]; ?>"><?php echo $row["items"]?></option>
				<?php 
				}} 
				?>
				</select>
              </div>
              <label for="date" align="right" class="col-sm-2 form-control-label">Start Date</label>
              <div class="col-sm-2">
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

              <label for="date" align="right" class="col-sm-1 form-control-label">End Date</label>
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
              </div>
            </div>
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


   <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <h2>Driver Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/driver" method="post" onsubmit="target_popup(this)">
            
            <div class="form-group row">

              <label for="staff" class="col-sm-1 form-control-label" >Driver</label>
              <div class="col-sm-3">
               <select name="driver" id="staff" placeholder="Staff" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
				$sql = "SELECT name FROM customers where type='Driver'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["name"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
				</select>
              </div>
              <label for="date" align="right" class="col-sm-2 form-control-label">Start Date</label>
              <div class="col-sm-2">
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

              <label for="date" align="right" class="col-sm-1 form-control-label">End Date</label>
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
              </div>
            </div>
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>    
   
   <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <h2>Vehicles Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/vehicle" method="post" onsubmit="target_popup(this)">
            
            <div class="form-group row">

              <label for="staff" class="col-sm-1 form-control-label" >Vehicle</label>
              <div class="col-sm-3">
               <select name="vehicle" id="staff" placeholder="Staff" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
				$sql = "SELECT vehicle FROM vehicle";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["vehicle"]; ?>"><?php echo $row["vehicle"]?></option>
				<?php 
				}} 
				?>
				</select>
              </div>
              <label for="date" align="right" class="col-sm-2 form-control-label">Start Date</label>
              <div class="col-sm-2">
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

              <label for="date" align="right" class="col-sm-1 form-control-label">End Date</label>
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
              </div>
            </div>
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>   
     
   <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <h2>Company Project wise Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/company" method="post" onsubmit="target_popup(this)">
            
            <div class="form-group row">

              <label for="staff" class="col-sm-2 form-control-label" >Company</label>
              <div class="col-sm-4">
               <select name="company" id="customer" class="form-control">
				<?php 
				$sql = "SELECT name FROM customers where type='Company'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["name"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
				</select>
              </div>
              <label for="staff" align="right" class="col-sm-2 form-control-label" >Project</label>
              <div class="col-sm-4">
              <select name="site" id="site" class="form-control"></select>
              </div>
              </div>
              <div class="form-group row">
              <label for="date" class="col-sm-2 form-control-label">Start Date</label>
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

              <label for="date" align="right" class="col-sm-2 form-control-label">End Date</label>
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
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>    
     
   <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <h2>Sales Representative Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/sales_rep" method="post" onsubmit="target_popup(this)">
            
            <div class="form-group row">

              <label for="staff" class="col-sm-1 form-control-label" >Sales Rep</label>
              <div class="col-sm-5">
               <select name="sales_rep" id="staff" placeholder="Staff" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
				$sql = "SELECT name FROM customers where type='SalesRep'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["name"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
				</select>
              </div>
              <label for="date" class="col-sm-1 form-control-label">Start Date</label>
              <div class="col-sm-2">
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

              <label for="date" align="right" class="col-sm-1 form-control-label">End Date</label>
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
              </div>
            </div>
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
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