<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$id=$_POST['id'];
$account=$_POST['Account_No'];
$balance=$_POST['Intial_Balance'];
$note=$_POST['Note'];
$name=$_POST['Name'];

$sql = "UPDATE account SET account = '$account',name = '$name', init_balance = '$balance', note = '$note' WHERE id='$id'";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="AC".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}


if($_GET['id'])
{
    $id=$_GET['id']; 
}
$sql="SELECT * FROM account where id='$id'";
$query=mysqli_query($conn,$sql);
if(mysqli_num_rows($query) > 0)
{
     while($result=mysqli_fetch_array($query))
     {
          $account=$result['account'];
          $name=$result['name'];
          $balance=$result['init_balance'];
          $note=$result['note'];
     }
}


?>
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-8">
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
          <h2>Edit Account</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          
         <form role="form" action="<?php echo $baseurl;?>/edit/account" method="post">
         <input type="text" name="id" value="<?php echo $id;?>" hidden="hidden">   
            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Account No</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" value="<?php echo $account;?>" name="Account_No" placeholder="Account No">
              </div>
            </div>
             
            <div class="form-group row">   
             <label for="Quantity" class="col-sm-2 form-control-label">Name</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" value="<?php echo $name;?>" name="Name" id="value" placeholder="Name">
              </div>
            </div>
               
            <div class="form-group row">   
             <label for="Quantity" class="col-sm-2 form-control-label">Current Balance</label>
              <div class="col-sm-6">
                <input type="number" class="form-control" value="<?php echo $balance;?>" name="Intial_Balance" id="value" placeholder="Intial Balance" readonly>
              </div>
            </div>
               
                <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Note</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" value="<?php echo $note;?>" name="Note" placeholder="Note">
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