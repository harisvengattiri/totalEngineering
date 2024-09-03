<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$inventry=$_POST["Inventry"];
$customer=$_POST["customer"];
$customersite=$_POST["customersite"];
$salesorder=$_POST["salesorder"];
$date=$_POST["date"];
$vehicle=$_POST["vehicle"];
$driver=$_POST["driver"];
$commission=$_POST["commission"];
$salesrepresentative=$_POST["salesrepresentative"];
$tags=json_encode($tag);
$sql = "INSERT INTO `delivery_note` (`inventry`, `customer`, `customersite`, `salesorder`, `date`, `vehicle`,`driver`,`commission`,`salesrepresentative`) 
VALUES ('$inventry', '$customer', '$customersite', '$salesorder', '$date', '$vehicle', '$driver', '$commission', '$salesrepresentative')";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="mnt".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}
?>
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-12">
	<?php if($status=="success") {?>
	<p><a class="list-group-item b-l-success">
          <span class="pull-right text-success"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label success pos-rlt m-r-xs">
		  <b class="arrow right b-success pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-success">Your Submission was Successfull!</span>
    </a></p>
	<?php } else if($status=="failed") { ?>
	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed!</span>
    </a></p>
	<?php }?>
      <div class="box">
        <div class="box-header">
          <h2>Add New Delivery Note</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/delivery_note" method="post">

            <div class="form-group row">
              <label for="customer" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-4">
		<select name="customer" class="form-control">
                  <?php 
				$sql = "SELECT name FROM customers ";
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
              <label for="startd" align="right" class="col-sm-2 form-control-label">Customer Site</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="customersite" id="startd" placeholder="Customer Site ">
              </div>
            </div>
            <div class="form-group row">
              
              <label for="endd" class="col-sm-2 form-control-label">Delivery Note Referance</label>
              <div class="col-sm-4">
                <select name="order_referance" id="cust" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                <!--<select name="order_referance" id="cust" class="form-control">-->
                  <?php 
				$sql = "SELECT order_referance FROM sales_order ";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["order_referance"]; ?>"><?php echo $row["order_referance"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
              <label for="date" align="right" class="col-sm-2 form-control-label">Date</label>
              <div class="col-sm-4">
                <input type="text" name="date" id="date" placeholder="Production Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <label for="startd" class="col-sm-2 form-control-label">Vehicle</label>
              <div class="col-sm-4" id="veh">
                   <input type="text" class="form-control" name="vehicle" placeholder="Vehicle">
              <!--<select class="form-control" id="veh"></select>-->
              </div>
              <label for="endd" align="right" class="col-sm-2 form-control-label">Driver</label>
              <div class="col-sm-4" id="nam">
              <input type="text" class="form-control" name="driver" placeholder="Driver">
              </div>
              </div>
             
               <div class="form-group row">
               <label for="endd" class="col-sm-1 form-control-label">Item</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="Item" id="endd" placeholder="Item">
              </div>
               <label for="endd" class="col-sm-1 form-control-label">Quantity</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="Quantity" id="endd" placeholder="Quantity">
              </div>
               <label for="endd" class="col-sm-1 form-control-label">Bundle</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="Bundle" id="endd" placeholder="Bundle">
              </div>
               </div>
               
               <div class="form-group row">
               <label for="endd" class="col-sm-1 form-control-label">COC No</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="COC No" id="endd" placeholder="COC No">
              </div>
               <label for="endd" class="col-sm-1 form-control-label">Date</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="Production Date" id="endd" placeholder="Production Date">
              </div>
               <label for="endd" class="col-sm-1 form-control-label">Batch No</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="Batch No" id="endd" placeholder="Batch No">
              </div>
               </div>
               
<!--               <div class="form-group row">
               <label for="endd" class="col-sm-2 form-control-label">Sales Representative</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="salesrepresentative" id="endd" placeholder="Sales Representative">
              </div>
               </div>-->
              
            
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="submit"type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>
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
  
<?php include "../includes/footer.php";?>