<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$name=$_POST["name"];
$customer=$_POST["customer"];
$value=$_POST["value"];
$estimate=$_POST["estimate"];
$notes=$_POST["notes"];
$tag=$_POST["tags"];
$tags=json_encode($tag);
$sql = "INSERT INTO `projects` (`name`, `customer`, `value`, `estimate`, `tags`, `notes`) 
VALUES ('$name', '$customer', '$value', '$estimate', '$tags', '$notes')";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="prj".$last_id;
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
          <h2>Add New Product</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/project" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Product Name</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="name" id="name" placeholder="Product Name">
              </div>
              <label for="customer" align="right" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-4">
				<select name="customer" id="customer" placeholder="Customer" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
				$sql = "SELECT id,name,person FROM customers where type='company' or type='individual'or type='partner' ORDER BY name ASC";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?> [<?php echo $row["person"];?>]</option>
				<?php 
				}} 
				?>
				</select>
              </div>
        
            </div>
            <div class="form-group row">
              <label for="estimate" class="col-sm-2 form-control-label">Value</label>
              <div class="col-sm-2">
                <input type="number" class="form-control" name="value" id="value" placeholder="Value">
              </div>
              <label for="estimate" align="right" class="col-sm-1 form-control-label">Estimate</label>
              <div class="col-sm-2">
                <input type="number" class="form-control" name="estimate" id="estimate" placeholder="Estimate">
              </div>
<label for="type" align="right" class="col-sm-1 form-control-label">Tags</label>
              <div class="col-sm-4">
                <select name="tags[]" multiple id="multiple" class="form-control select2-multiple" multiple data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}"">
                  <?php 
				$sql = "SELECT tag FROM project_tags";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["tag"]; ?>"><?php echo $row["tag"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
            </div>
            <div class="form-group row">

              <label for="notes" class="col-sm-2 form-control-label">Notes</label>
              <div class="col-sm-10">
                <textarea class="form-control" rows="2" name="notes" id="notes" placeholder="Notes"></textarea>
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