<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<?php error_reporting(0); ?>

<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
        if(isset($_SESSION['userid']))
{
$id=$_POST["id"];
$name=$_POST["name"];
$tin=$_POST["tin"];
$person=$_POST["person"];
$address=$_POST["address"];
$phone=$_POST["phone"];
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
$slmn=$_POST["slmn"];
$tag=$_POST["tags"];
$Emirate=$_POST["Emirate"];
$makani=$_POST["makani"];
$license=$_POST["license"];
$payment=$_POST["payment"];
$p_mode=$_POST["p_mode"];
$cust_type=$_POST["cust_type"];
$period=$_POST["period"];
$opening=$_POST["opening"];
$tags=json_encode($tag);
$sql = "UPDATE `customers` SET `name` = '$name', `person` = '$person', `tin` = '$tin', `address` =  '$address', `phone` =  '$phone', `fax` =  '$fax', `mobile` =  '$mobile',
        `email` =  '$email', `type` =  '$type', `slmn` =  '$slmn', `tags` =  '$tags', `Emirate` =  '$Emirate', `makani` =  '$makani', `licenseNo` =  '$license', `cust_type` =  '$cust_type',
        `period` =  '$period', `payment` =  '$payment', `p_mode` =  '$p_mode', `op_bal` =  '$opening' WHERE  `id` = $id";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="CID".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}}
if ($_GET) 
{
$id=$_GET["id"];
}

	$sql = "SELECT * FROM customers where id=$id";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
              $name=$row["name"];
              $tin=$row["tin"];
              $person=$row["person"];
              $address=$row["address"];
              $phone=$row["phone"];
              $fax=$row["fax"];
              $mobile=$row["mobile"];
              $email=$row["email"];
              $type=$row["type"];
              $sm=$row["slmn"];
                  if(!empty($sm)) {
                    $sql_slmn = "SELECT name FROM `customers` WHERE `id` = '$sm'";
                    $query_slmn = mysqli_query($conn,$sql_slmn);
                    $fetch_slmn = mysqli_fetch_array($query_slmn);
                    $sm_name = $fetch_slmn['name'];
                  }
              $tags=$row["tags"];
              $makani=$row["makani"];
              $license=$row["licenseNo"];
              $cust_type=$row["cust_type"];
              $period=$row["period"];
              $payment=$row["payment"];
              $p_mode=$row["p_mode"];
              $opening=$row["op_bal"];
              $Emirate=$row["Emirate"];
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
          <h2>Edit Contact</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/edit/customer?id=<?php echo $id;?>" method="post">
              
            <div class="form-group row">
            <label for="name" class="col-sm-2 form-control-label">Customer ID</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" value="<?php echo $id;?>" readonly>
              </div>
            </div>  
              
            <div class="form-group row">
               <input name="id"  type="text" value="<?php echo $id;?>" hidden="hidden">
              <label for="name" class="col-sm-2 form-control-label">Contact Name</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" value="<?php echo $name;?>" name="name" id="name" placeholder="Name of Company/Customer/Staff">
              </div>
              <label for="person" align="right" class="col-sm-2 form-control-label">Contact Person / Designation</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" value="<?php echo $person;?>" name="person" id="person" placeholder="Contact Person if Company / Designation if Individual">
              </div>
            </div>
            <div class="form-group row">
              <label for="address" class="col-sm-2 form-control-label">Address</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" value="<?php echo $address;?>" name="address" id="address" placeholder="Address">
              </div>
              <label for="tin" align="right" class="col-sm-1 form-control-label">TRN</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" value="<?php echo $tin;?>" name="tin" id="tin" placeholder="TRN">
              </div>
            </div>
            <div class="form-group row">
              <label for="phone" class="col-sm-2 form-control-label">Phone</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" value="<?php echo $phone;?>" name="phone" id="phone" placeholder="Phone No">
              </div>
              <label for="fax" align="right" class="col-sm-1 form-control-label">Fax</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" value="<?php echo $fax;?>" name="fax" id="fax" placeholder="Fax No">
              </div>
            </div>
            <div class="form-group row">
              <label for="mobile" class="col-sm-2 form-control-label">Mobile</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" value="<?php echo $mobile;?>" name="mobile" id="mobile" placeholder="Mobile No">
              </div>
              <label for="email" align="right" class="col-sm-1 form-control-label">Email</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" value="<?php echo $email;?>" name="email" id="email" placeholder="Email ID">
              </div>
            </div>
            <div class="form-group row">
              <label for="type" class="col-sm-2 form-control-label">Contact Type</label>
              <div class="col-sm-4">
                <select name="type" class="form-control c-select">
                  <option><?php echo $type;?></option>
                  <option>Company</option>
                  <option>Individual</option>
                  <option>Partner</option>
                  <option>Staff</option>
                  <option>Supplier</option>
                  <option>Bank</option>
                  <option>SalesRep</option>
                  <option>Operator</option>
                  <option>Driver</option>
                </select>
              </div>
            <label for="type" align="right" class="col-sm-1 form-control-label">SalesMan</label>
              <div class="col-sm-5">
                <select name="slmn" class="form-control c-select">
                  <option value="<?php echo $sm;?>"><?php echo $sm_name;?></option>
                  <?php
                    $sql_sm = "SELECT id,name FROM `customers` WHERE `type` = 'SalesRep'";
                    $query_sm = mysqli_query($conn,$sql_sm);
                    while($fetch_sm = mysqli_fetch_array($query_sm)) {
                  ?>
                  <option value="<?php echo $fetch_sm['id'];?>"><?php echo $fetch_sm['name'];?></option>
                  <?php
                    }
                  ?>
                </select>
              </div>
            </div>
            
            <div class="form-group row">
              <label for="mobile" class="col-sm-2 form-control-label">Makani No</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" value="<?php echo $makani;?>" name="makani" id="makani" placeholder="Makani No">
              </div>
              <label for="license" align="right" class="col-sm-1 form-control-label">License Number</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" value="<?php echo $license;?>" name="license" id="license" placeholder="License Number">
              </div>
            </div>
            
            <div class="form-group row">
              <label for="email" align="" class="col-sm-2 form-control-label">Payment Terms</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" value="<?php echo $payment;?>" name="payment" id="payment" placeholder="Payment Terms">
              </div>
            </div>
            
            <div class="form-group row">
              <label for="type" align="" class="col-sm-2 form-control-label">Mode of Payment</label>
              <div class="col-sm-4">
                  <select name="p_mode" class="form-control c-select">
                  <option><?php echo $p_mode;?></option>
                    <option>Cash on Delivery</option>
                    <option>Cash At Site.</option>
                    <option>Cash Advance</option>
                    <option>CDC along with LPO</option>
                    <option>CDC after invoice</option>
                    <option>Advance Received</option>
                    <option>PDC along with LPO</option>
                    <option>PDC After Invoice</option>
                </select>
              </div>
            <label for="type" align="right" class="col-sm-1 form-control-label">Tags</label>
              <div class="col-sm-5">
                <select name="tags[]" multiple id="multiple" class="form-control select2-multiple" multiple data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                <?php
                if($tags != 'null') {
                  $tags = json_decode($tags);
                  $arrlength=count($tags);
                  for($x=0; $x<$arrlength; $x++)
  			          {
				        ?>
				          <option value="<?php echo $tags[$x];?>" selected="selected"><?php echo $tags[$x];?></option>
                <?php 
                  } }
                  $sql = "SELECT tag FROM customer_tags";
                  $result = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($result) > 0) 
                  {
                  while($row = mysqli_fetch_assoc($result)) 
                  {
                ?>
                  <option value="<?php echo $row["tag"]; ?>"><?php echo $row["tag"]?></option>
                <?php 
                  } }
                ?>
                </select>
              </div>
            </div>
            
            <div class="form-group row">
              <label for="mobile" class="col-sm-2 form-control-label">Customer Type</label>
              <div class="col-sm-4">
                <select type="text" class="form-control" name="cust_type" id="cust_type" required>
                    <option value="<?php echo $cust_type;?>"><?php echo $cust_type;?></option>
                    <option value="Cash">Cash Customer</option>
                    <option value="Credit">Credit Customer</option>
                </select>
              </div>
              <label for="email" align="right" class="col-sm-1 form-control-label">Period</label>
              <div class="col-sm-5">
                <select type="text" class="form-control" name="period" id="period">
                    <option value="<?php echo $period;?>"><?php echo $period;?></option>
                </select>
              </div>
           </div>
               
            <div class="form-group row">
              <label class="col-sm-2 form-control-label">Opening Balance</label>
              <div class="col-sm-4">
                <input type="number" step="0.01" class="form-control" value="<?php echo $opening;?>" name="opening" placeholder="Opening Balance">
              </div>
            
              <label for="type" align="right" class="col-sm-1 form-control-label">Emirate</label>
              <div class="col-sm-5">
                <select name="Emirate" class="form-control c-select">
                  <option><?php echo $Emirate;?></option>
                  <option>Dubai</option>
                  <option>Abu Dhabi</option>
                  <option>Sharjah</option>
                  <option>Ajman</option>
                  <option>Fujairah</option>
                  <option>Ras Al Khaimah</option>
                  <option>Umm Al Quwain</option>
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
<script type="text/javascript">
 $(document).ready(function() {
  $("#cust_type").change(function() {
    var country_id = $(this).val();
    if(country_id != "") {
      $.ajax({
        url:"../add/get_period",
        data:{c_id:country_id},
        type:'POST',
        success:function(response) {
          var resp = $.trim(response);
          $("#period").html(resp);
        }
      });
    } else {
      $("#period").html("<option value=''>------- Select --------</option>");
    }
  });
});
</script>    
<?php include "../includes/footer.php";?>