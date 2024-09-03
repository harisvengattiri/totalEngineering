<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
$vehicle=$_POST["vehicle"];
$model=$_POST["model"];
$vehicleWeight=$_POST["vehicleWeight"];
$allowedWeight=$_POST["allowedWeight"];


$prj=$_POST["prj"];
$sql = "UPDATE `vehicle` SET `vehicle` =  '$vehicle', `model` =  '$model', `vehicleWeight` =  '$vehicleWeight', `allowedWeight` =  '$allowedWeight' WHERE  `id` = $prj";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="VHL".$vehicle;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}}


if ($_GET) 
{
$prj=$_GET["id"];
}
	$sql = "SELECT * FROM vehicle where id=$prj";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {

              $vehicle=$row["vehicle"];
              $model=$row["model"];
              $vehicleWeight=$row["vehicleWeight"];
              $allowedWeight=$row["allowedWeight"];
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
          <h2>Edit Vehicle</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/edit/vehicle" method="post">
                <input name="prj"  type="text" value="<?php echo $prj;?>" hidden="hidden">
            
            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Model</label>
              <div class="col-sm-4">
                   <input type="text" class="form-control" name="model" value="<?php echo $model;?>" id="value" placeholder="Model">
              </div>
            </div>
            <div class="form-group row">  
              <label for="Quantity" class="col-sm-2 form-control-label">Reg No</label>
              <div class="col-sm-4">
                   <input type="text" class="form-control" name="vehicle" value="<?php echo $vehicle;?>" id="value" placeholder="Reg No">
              </div>
            </div>
              
            <div class="form-group row">  
              <label for="Quantity" class="col-sm-2 form-control-label">Govt Allowed Weight</label>
              <div class="col-sm-4">
                   <input type="number" step="any" class="form-control" name="allowedWeight" value="<?php echo $allowedWeight;?>" placeholder="Allowed Weight">
              </div>
            </div>  
              
            <div class="form-group row">  
              <label for="Quantity" class="col-sm-2 form-control-label">Vehicle Weight</label>
              <div class="col-sm-4">
                   <input type="number" step="any" class="form-control" name="vehicleWeight" value="<?php echo $vehicleWeight;?>" placeholder="Vehicle Weight">
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