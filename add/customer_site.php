<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
$customer=$_POST["customer"];
  $sqlFindUID = "SELECT MAX(uid) AS maxUid FROM `customer_site` WHERE `customer`='$customer'";
  $queryFindUID = mysqli_query($conn,$sqlFindUID);
  $resultFindUID = mysqli_fetch_array($queryFindUID);
  $newUID = $resultFindUID['maxUid']+1;
  
$site=$_POST["site"];
$p_name=$_POST["p_name"];
$p_no=$_POST["p_no"];
$salesrep=$_POST["salesrep"];
$Contact_no=$_POST["Contact_no"];
$Contact_no1=$_POST["Contact_no1"];
$location=$_POST["location"];
$Contact_per=$_POST["Contact_per"];
$permit=$_POST["permit"];
$sql = "INSERT INTO `customer_site` (`customer`, `uid`, `site`, `p_name`, `p_no`, `salesrep`, `Contact_no`, `Contact_no1`, `location`, `Contact_per`, `permit`) 
VALUES ('$customer', '$newUID', '$site', '$p_name', '$p_no', '$salesrep', '$Contact_no', '$Contact_no1', '$location', '$Contact_per', '$permit')";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="CST".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}}
?>
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-6">
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
          <h2>Add New Customer Site</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/customer_site" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-4 form-control-label">Customer</label>
              <div class="col-sm-8">
                <select name="customer" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT * FROM customers where type='Company' order by name";
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
            </div>
               <div class="form-group row">
              <label for="type" class="col-sm-4 form-control-label">Site/Job Reference</label>
              <div class="col-sm-8">
                   <?php 
                   $sql = "SELECT site FROM customer_site ORDER BY id DESC LIMIT 1";
//				$sql = "SELECT batch FROM batches_lots where id='$last_id'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                     $value=0;
				while($row = mysqli_fetch_assoc($result)) 
				{ 
                                     $value=$row["site"]+1;
				?>
                   <input type="" class="form-control" name="site" value="<?php echo $value;?>" readonly>
				<?php 
				}}
                               else { ?>
                                     <input type="" class="form-control" name="site" value="1" readonly>
                              <?php }
		  ?>
                   
              </div>
              
<!--              <div class="col-sm-4">
                   <select name="site" class="form-control">
                     <option value="Site">Site</option>
                     <option value="Job Reference">Job Reference</option>
                   </select>
              </div>-->
            </div>
               
            <div class="form-group row">
              <label for="type" class="col-sm-4 form-control-label">Project Name</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="p_name" id="estimate" placeholder="Project Name" required>
              </div>
            </div>
               <div class="form-group row">
              <label for="Quantity" class="col-sm-4 form-control-label">Project Number</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="p_no" id="value" placeholder="Project Number">
              </div>
             </div>   
               
              <div class="form-group row">
              <label for="type" class="col-sm-4 form-control-label">Sales Representative</label>
              <div class="col-sm-8">
                 <select name="salesrep" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
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
              </div>
               <div class="form-group row">
                <label for="type" class="col-sm-4 form-control-label">Location</label>
              <div class="col-sm-8">
                <select name="location" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}" required>
                  <?php 
				$sql = "SELECT * FROM fair";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["location"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
             </div>  
               
              <div class="form-group row">
              <label for="type" class="col-sm-4 form-control-label">Plot Number</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="permit" id="estimate" placeholder="Plot Number">
              </div>
              </div>
               <div class="form-group row">
              <label for="Quantity" class="col-sm-4 form-control-label">Contact Person</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="Contact_per" id="value" placeholder="Contact Person" required>
              </div>
             </div> 
               
               <div class="form-group row">
               <label for="Quantity" class="col-sm-4 form-control-label">Contact No1</label>
              <div class="col-sm-8">
                <input type="number" class="form-control" name="Contact_no" id="value" placeholder="Contact No1">
              </div>
               </div>
               <div class="form-group row">
               <label for="Quantity" class="col-sm-4 form-control-label">Contact No2</label>
              <div class="col-sm-8">
                <input type="number" class="form-control" name="Contact_no1" id="value" placeholder="Contact No2">
              </div>
              </div>
               
               
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