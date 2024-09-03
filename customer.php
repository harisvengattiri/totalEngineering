<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$name=$_POST["name"];
$person=$_POST["person"];
$address=$_POST["address"];
$phone=$_POST["phone"];
$tin=$_POST["tin"];
$phone=preg_replace('/\D+/', '', $phone);
$phone = preg_replace("/^971/", "0", "$phone");
$mobile=$_POST["mobile"];
$mobile=preg_replace('/\D+/', '', $mobile);
$mobile = preg_replace("/^971/", "0", "$mobile");
$fax=$_POST["fax"];
$fax=preg_replace('/\D+/', '', $fax);
$fax = preg_replace("/^971/", "0", "$fax");
$email=$_POST["email"];
$type=$_POST["type"];
$tag=$_POST["tags"];
$tags=json_encode($tag);
$sql = "INSERT INTO `customers` (`name`, `person`, `address`, `tin`, `phone`,  `mobile`,  `fax`, `email`, `type`, `tags`) 
VALUES ('$name', '$person', '$address', '$tin', '$phone', '$mobile', '$fax', '$email', '$type', '$tags')";
if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="cid".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'add', '$code', '$username', '$query')";
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
          <h2>Add New Contact</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/customer" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Contact Name</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="name" id="name" placeholder="Name of Company/Customer/Staff">
              </div>
              <label for="person" align="right" class="col-sm-2 form-control-label">Contact Person</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="person" id="person" placeholder="Contact Person for Company">
              </div>
            </div>
            <div class="form-group row">
              <label for="address" class="col-sm-2 form-control-label">Address</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="address" id="address" placeholder="Address">
              </div>
              <label for="fax" align="right" class="col-sm-1 form-control-label">TIN</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="tin" id="fax" placeholder="TIN">
              </div>
            </div>
            <div class="form-group row">
              <label for="phone" class="col-sm-2 form-control-label">Phone</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone No">
              </div>
              <label for="fax" align="right" class="col-sm-1 form-control-label">Fax</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="fax" id="fax" placeholder="Fax No">
              </div>
            </div>
            <div class="form-group row">
              <label for="mobile" class="col-sm-2 form-control-label">Mobile</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="mobile" id="Mobile" placeholder="Mobile No">
              </div>
              <label for="email" align="right" class="col-sm-1 form-control-label">Email</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="email" id="email" placeholder="Email ID">
              </div>
            </div>
            <div class="form-group row">
              <label for="type" class="col-sm-2 form-control-label">Contact Type</label>
              <div class="col-sm-4">
                <select name="type" class="form-control c-select">
                  <option>Company</option>
                  <option>Individual</option>
                  <option>Partner</option>
                  <option>Staff</option>
                  <option>Shop</option>
                  <option>Bank</option>
                </select>
              </div>
              <label for="type" align="right" class="col-sm-1 form-control-label">Tags</label>
              <div class="col-sm-5">
                <select name="tags[]" multiple id="multiple" class="form-control select2-multiple" multiple data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}"">
                  <?php 
				$sql = "SELECT tag FROM customer_tags";
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