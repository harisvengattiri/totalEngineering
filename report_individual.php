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
    $("#goslrep").click(function(){
        $("#slrep").toggle();
        $("#rep").hide();
        $("#allrep").hide();
        $("#allsale").hide();
        $("#cust").hide();
    });
});
$(document).ready(function(){
    $("#gorep").click(function(){
        $("#rep").toggle();
        $("#slrep").hide();
        $("#allrep").hide();
        $("#allsale").hide();
        $("#cust").hide();
    });
});
$(document).ready(function(){
    $("#goallrep").click(function(){
        $("#allrep").toggle();
        $("#slrep").hide();
        $("#rep").hide();
        $("#allsale").hide();
        $("#cust").hide();
    });
});
$(document).ready(function(){
    $("#goallsale").click(function(){
        $("#allsale").toggle();
        $("#allrep").hide();
        $("#slrep").hide();
        $("#rep").hide();
        $("#cust").hide();
    });
});
$(document).ready(function(){
    $("#gocust").click(function(){
        $("#cust").toggle();
        $("#rep").hide();
        $("#slrep").hide();
        $("#allrep").hide();
        $("#allsale").hide();
    });
});
</script>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">

    <div class="row">
    <div class="col-md-10">
      <div class="box">
        <div class="box-header">
          <h2>Select Your Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
     
     <button id="goslrep" type="button" class="btn btn-success">Order Report</button>
     <button id="gorep" type="button" class="btn btn-success">Item Order Report</button>
     <button id="goallrep" type="button" class="btn btn-success">Sales Report</button>
     <button id="goallsale" type="button" class="btn btn-success">Item Sales Report</button>
     <button id="gocust" type="button" class="btn btn-success">Item Customer Report</button>
     
        </div></div></div></div>

    
     
     
     
     
   <div style="display:none;" id="allsale" class="row">
    <div class="col-md-10">
      <div class="box">
        <div class="box-header">
          
          <h2>Item Sale Report [Item - Quantity]</h2>
         
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/item_sale" method="post" onsubmit="target_popup(this)">
          
          <div class="form-group row">

              <label for="staff" class="col-sm-1 form-control-label" >Sales Rep</label>
              <div class="col-sm-3">
               <select name="sales_rep" class="form-control">
				<?php 
				$sql = "SELECT name,id FROM customers where type='SalesRep'";
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
               
            <div class="form-group row">
              <label for="staff" class="col-sm-1 form-control-label" >Items</label>
              <div class="col-sm-3">
               <select name="item" class="form-control">
				<?php 
				$sql = "SELECT items,id FROM items";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0)
                                ?><option value="">ALL</option><?php     
				{
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
<!--                <button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>-->
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
                <button type="submit" formaction="<?php echo $cdn_url;?>/reports/convert_report_item_sale" class="btn btn-sm btn-outline rounded b-success text-success">Print</button>
              </div>
            </div>     
               
            
            
          </form>
        </div>
      </div>
    </div>
  </div>  
     
     
     
     
     
     
   <div style="display: none;" id="allrep" class="row">
    <div class="col-md-10">
      <div class="box">
        <div class="box-header">
          <h2>SalesMan Delivery Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/sales_rep_delivery" method="post" onsubmit="target_popup(this)">
            
            <div class="form-group row">

              <label for="staff" class="col-sm-1 form-control-label" >Sales Rep</label>
              <div class="col-sm-3">
               <select name="sales_rep" class="form-control">
                   <option value="">ALL</option>
				<?php 
				$sql = "SELECT name,id FROM customers where type='SalesRep'";
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
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
                <!--<button type="submit" formaction="<?php echo $baseurl;?>/mpdf/convert_report_del_rep" class="btn btn-sm btn-outline rounded b-success text-success">Print</button>-->
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>  
     
     
     
   <div style="display: none;" id="slrep" class="row">
    <div class="col-md-10">
      <div class="box">
        <div class="box-header">
          <h2>Order Report [Project Value]</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/sales_rep" method="post" onsubmit="target_popup(this)">
            
            <div class="form-group row">

              <label for="staff" class="col-sm-1 form-control-label" >Sales Rep</label>
              <div class="col-sm-3">
               <select name="sales_rep" class="form-control">
				<?php 
				$sql = "SELECT name,id FROM customers where type='SalesRep'";
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
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
                <button type="submit" formaction="<?php echo $cdn_url;?>/reports/convert_report_svalue" class="btn btn-sm btn-outline rounded b-success text-success">Print</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>   
       
     
     <div style="display: none;" id="rep" class="row">
    <div class="col-md-10">
      <div class="box">
        <div class="box-header">
          <h2>Item Order Report [Item - Quantity]</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/salesman" method="post" onsubmit="target_popup(this)">
            
            <div class="form-group row">

              <label for="staff" class="col-sm-1 form-control-label" >Sales Rep</label>
              <div class="col-sm-3">
               <select name="sales_rep" class="form-control">
				<?php 
				$sql = "SELECT name,id FROM customers where type='SalesRep'";
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
               
            <div class="form-group row">
              <label for="staff" class="col-sm-1 form-control-label" >Items</label>
              <div class="col-sm-3">
               <select name="item" class="form-control">
				<?php 
				$sql = "SELECT items,id FROM items";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0)
                                ?><option value="">ALL</option><?php     
				{
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
<!--                <button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>-->
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
                <button type="submit" formaction="<?php echo $cdn_url;?>/reports/convert_report_sitem" class="btn btn-sm btn-outline rounded b-success text-success">Print</button>
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
          <h2>Item - Customer Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/item_customer" method="post" onsubmit="target_popup(this)">
            
            <div class="form-group row">

              <label for="staff" class="col-sm-1 form-control-label" >Item</label>
              <div class="col-sm-3">
               <select name="item" class="form-control">
				<?php 
				$sql = "SELECT items,id FROM items ORDER BY items";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["items"]?></option>
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
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
                <!--<button type="submit" formaction="<?php echo $baseurl;?>/mpdf/item_customer" class="btn btn-sm btn-outline rounded b-success text-success">Print</button>-->
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