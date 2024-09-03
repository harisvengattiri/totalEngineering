<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$Account_No=$_POST['Account_No'];
$Intial_Balance=$_POST['Intial_Balance'];
$Name=$_POST['Name'];
$Note=$_POST['Note'];

$sql = "INSERT INTO `account` (`account`,`name`,`init_balance`,`note`) 
VALUES ('$Account_No','$Name','$Intial_Balance','$Note')";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="AC".$last_id;
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
          <h2>Add New Account</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/account" method="post">
            
            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Account No</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="Account_No" placeholder="Account No">
              </div>
            </div>
             
            <div class="form-group row">   
             <label for="Quantity" class="col-sm-2 form-control-label">Name</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="Name" id="value" placeholder="Name">
              </div>
            </div>
               
            <div class="form-group row">   
             <label for="Quantity" class="col-sm-2 form-control-label">Intial Balance</label>
              <div class="col-sm-8">
                <input type="number" class="form-control" name="Intial_Balance" id="value" placeholder="Intial Balance">
              </div>
            </div>
               
                <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Note</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="Note" placeholder="Note">
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