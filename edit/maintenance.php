<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$name=$_POST["name"];
$customer=$_POST["customer"];
$startd=$_POST["startd"];
$endd=$_POST["endd"];
$value=$_POST["value"];
$estimate=$_POST["estimate"];
$notes=$_POST["notes"];
$tag=$_POST["tags"];
$tags=json_encode($tag);
$mnt=$_POST["mnt"];
$sql = "UPDATE `maintenances` SET `name` =  '$name', `startd` =  '$startd', `endd` =  '$endd', `customer` =  '$customer', `value` = '$value', `tags` =  '$tags', `notes` =  '$notes' WHERE  `id` = $mnt";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="mnt".$mnt;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}


if ($_GET) 
{
$mnt=$_GET["mnt"];
}
	$sql = "SELECT * FROM maintenances where id=$mnt";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {

              $name=$row["name"];
              $customer=$row["customer"];
              $startd=$row["startd"];
              $endd=$row["endd"];
              $value=$row["value"];
              $tags=$row["tags"];
              $notes=$row["notes"];
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
          <h2>Edit Maintenance</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/edit/maintenance" method="post">
            <div class="form-group row">
               <input name="mnt"  type="text" value="<?php echo $mnt;?>" hidden="hidden">
              <label for="name" class="col-sm-2 form-control-label">maintenance Name</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="name" value="<?php echo $name;?>" id="name" placeholder="maintenance Name">
              </div>
              <label for="customer" align="right" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-4">
				<select name="customer" id="customer" placeholder="Customer" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
				$sql = "SELECT id,name,person FROM customers where id='$customer'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?> [<?php echo $row["person"];?>]</option>
				<?php 
				}} 
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
              <label for="startd" class="col-sm-2 form-control-label">Start Date</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="startd" value="<?php echo $startd;?>" id="startd" placeholder="Start Date">
              </div>
              <label for="endd" align="right" class="col-sm-2 form-control-label">End Date</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="endd" value="<?php echo $endd;?>" id="endd" placeholder="End Date">
              </div>
            </div>
            <div class="form-group row">
              <label for="estimate" class="col-sm-2 form-control-label">Value</label>
              <div class="col-sm-4">
                <input type="number" class="form-control" value="<?php echo $value;?>" name="value" id="value" placeholder="Value">
              </div>
<label for="type" align="right" class="col-sm-2 form-control-label">Tags</label>
              <div class="col-sm-4">
                <select name="tags[]" multiple id="multiple" class="form-control select2-multiple" multiple data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}"">

                  <?php 
			        $tags = json_decode($tags);
			        $arrlength=count($tags);
			        for($x=0;$x<$arrlength;$x++)
  			        {
				?>
				<option value="<?php echo $tags[$x];?>" selected="selected"><?php echo $tags[$x];?></option>
				<?php 
  			        }
				$sql = "SELECT tag FROM maintenance_tags";
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
                <textarea class="form-control" rows="2" name="notes" value="<?php echo $notes;?>" id="notes" placeholder="Notes"></textarea>
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