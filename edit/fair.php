<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
$location=$_POST["location"];
$fair=$_POST["fair"];
$fair1=$_POST["fair1"];
$fair2=$_POST["fair2"];
$fair3=$_POST["fair3"];

$prj=$_POST["prj"];
$sql = "UPDATE `fair` SET `location` =  '$location', `fair` =  '$fair', `fair1` =  '$fair1', `fair2` =  '$fair2', `fair3` =  '$fair3' WHERE  `id` = $prj";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="LCN".$prj;
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
	$sql = "SELECT * FROM fair where id=$prj";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {

              $location=$row["location"];
              $fair=$row["fair"];
              $fair1=$row["fair1"];
              $fair2=$row["fair2"];
              $fair3=$row["fair3"];
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
          <h2>Edit Location/Fair</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/edit/fair?id=<?php echo $prj;?>" method="post">
                <input name="prj"  type="text" value="<?php echo $prj;?>" hidden="hidden">
            
            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Location</label>
              <div class="col-sm-4">
                   <input type="text" class="form-control" name="location" value="<?php echo $location;?>" id="value" placeholder="Location">
              </div>
            </div>
            <div class="form-group row">  
              <label for="Quantity" class="col-sm-2 form-control-label">fair [Tipper]</label>
              <div class="col-sm-4">
                   <input type="number" min="0" step="0.01" class="form-control" name="fair" value="<?php echo $fair;?>" id="value" placeholder="Fair [Tipper]">
              </div>
            </div>
            <div class="form-group row">  
              <label for="Quantity" class="col-sm-2 form-control-label">fair [6 Wheel]</label>
              <div class="col-sm-4">
                   <input type="number" min="0" step="0.01" class="form-control" name="fair1" value="<?php echo $fair1;?>" id="value" placeholder="Fair [6 Wheel]">
              </div>
            </div>
            <div class="form-group row">  
              <label for="Quantity" class="col-sm-2 form-control-label">fair [2XL Trailor]</label>
              <div class="col-sm-4">
                   <input type="number" min="0" step="0.01" class="form-control" name="fair2" value="<?php echo $fair2;?>" id="value" placeholder="Fair [2XL Trailor]">
              </div>
            </div>
            <div class="form-group row">  
              <label for="Quantity" class="col-sm-2 form-control-label">fair [3XL Trailor]</label>
              <div class="col-sm-4">
                   <input type="number" min="0" step="0.01" class="form-control" name="fair3" value="<?php echo $fair3;?>" id="value" placeholder="Fair [3XL Trailor]">
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