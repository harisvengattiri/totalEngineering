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
$licence_no=$_POST["licence_no"];
$exp_date=$_POST["exp_date"];
$type_licence=$_POST["type_licence"];
$nature_bus=$_POST["nature_bus"];
$est_year=$_POST["est_year"];
     
$customer=$_POST["company"];
$rep=$_POST["rep"];
$d_name1=$_POST["d_name1"];
$d_name2=$_POST["d_name2"];
$d_name3=$_POST["d_name3"];
$d_natn1=$_POST["d_natn1"];
$d_natn2=$_POST["d_natn2"];
$d_natn3=$_POST["d_natn3"];
$d_pass1=$_POST["d_pass1"];
$d_pass2=$_POST["d_pass2"];
$d_pass3=$_POST["d_pass3"];

$bank=$_POST["bank"];
$branch=$_POST["branch"];
$account=$_POST["account"];
$account_name=$_POST["account_name"];
$name_authorized1=$_POST["name_authorized1"];
$name_authorized2=$_POST["name_authorized2"];
$name_specimen1=$_POST["name_specimen1"];
$name_specimen2=$_POST["name_specimen2"];
$credit=$_POST["credit"];
$credit1=$_POST["credit1"];
$period=$_POST["period"];
$mode=$_POST["mode"];

$g_chq_no=$_POST["g_chq_no"];
$g_amt=$_POST["g_amt"];
$g_bank=$_POST["g_bank"];
$g_iban=$_POST["g_iban"];
$g_limit=$_POST["g_limit"];
    $image = $_FILES["image"]["name"];
    if($image!=NULL)
    {
    $ext = strtolower(pathinfo($image,PATHINFO_EXTENSION));
        $image1 = 'crdt-'.uniqid().'.'.$ext;
        $target_dir1 = "../uploads/credit/";
        $target_file1 = $target_dir1 . $image1;
        $imageFileType1 = strtolower(pathinfo($target_file1,PATHINFO_EXTENSION));
        $allowlist1 = array("jpg","jpeg","png","pdf");
         if (in_array($imageFileType1, $allowlist1))
         {
           if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file1)) 
           {
           } else { $image1=NULL;}
         }
         $crdt_image=$image1;
    }

$com_name1=$_POST["com_name1"];
$com_name2=$_POST["com_name2"];
$rc_name11=$_POST["rc_name11"];
$rc_name12=$_POST["rc_name12"];
$rc_name21=$_POST["rc_name21"];
$rc_name22=$_POST["rc_name22"];
$rc_no1=$_POST["rc_no1"];
$rc_no2=$_POST["rc_no2"];

$c_name1=$_POST["c_name1"];
$c_name2=$_POST["c_name2"];
$c_designation1=$_POST["c_designation1"];
$c_designation2=$_POST["c_designation2"];
$c_no1=$_POST["c_no1"];
$c_no2=$_POST["c_no2"];

$name_authorized3=$_POST["name_authorized3"];
$name_authorized4=$_POST["name_authorized4"];
$des_authorized3=$_POST["des_authorized3"];
$des_authorized4=$_POST["des_authorized4"];

$sql = "INSERT INTO `credit_application` (`company`,`rep`,`licence_no`,`exp_date`,`type_licence`,`nature_bus`,`est_year`, `d_name1`, `d_name2`, `d_name3`, `d_natn1`, `d_natn2`, `d_natn3`, `d_pass1`, `d_pass2`, `d_pass3`,
     `bank`,`branch`, `account`, `account_name`, `name_authorized1`, `name_authorized2`,`name_specimen1`,`name_specimen2`, `credit`, `credit1`, `period`, `mode`, `g_chq_no`, `g_amt`, `g_bank`, `g_iban`, `g_limit`, `g_doc`,
     `com_name1`, `com_name2`,`rc_name11`, `rc_name12`, `rc_name21`, `rc_name22`, `rc_no1`, `rc_no2`, `c_name1`, `c_name2`,
     `c_designation1`,`c_designation2`, `c_no1`, `c_no2`, `name_authorized3`, `name_authorized4`, `des_authorized3`, `des_authorized4`)
     
VALUES ('$customer','$rep','$licence_no','$exp_date','$type_licence','$nature_bus','$est_year', '$d_name1', '$d_name2', '$d_name3', '$d_natn1', '$d_natn2', '$d_natn3', '$d_pass1', '$d_pass2', '$d_pass3',"
        . " '$bank', '$branch','$account', '$account_name', '$name_authorized1', '$name_authorized2','$name_specimen1','$name_specimen2', '$credit', '$credit1', '$period', '$mode', '$g_chq_no', '$g_amt', '$g_bank', '$g_iban', '$g_limit', '$crdt_image',"
        . "'$com_name1', '$com_name2','$rc_name11', '$rc_name12', '$rc_name21', '$rc_name22', '$rc_no1', '$rc_no2', '$c_name1', '$c_name2',"
        . "'$c_designation1', '$c_designation2','$c_no1', '$c_no2', '$name_authorized3', '$name_authorized4', '$des_authorized3', '$des_authorized4')";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="CDT".$last_id;
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
    <div class="col-md-9">
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
          <h2>Add New Credit Application</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/credit_application" method="post" enctype="multipart/form-data">
            <div class="form-group row">
              <label for="name" class="col-sm-4 form-control-label">Customer</label>
              <div class="col-sm-8">
                   <select name="company" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}" required>
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
              <label for="name" class="col-sm-4 form-control-label">Sales Rep</label>
              <div class="col-sm-8">
                   <select name="rep" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}" required>
                  <?php 
				$sql = "SELECT * FROM customers where type='SalesRep' order by name";
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
              <div class="col-sm-4">
              
              </div>   
              <div class="col-sm-4">
              <input type="text" class="form-control" name="licence_no" placeholder="Trade Licence No" required>
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="exp_date" placeholder="Expiry Date" required>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="type_licence" placeholder="Type of Licence" required>
              </div>   
              <div class="col-sm-4">
              <input type="text" class="form-control" name="nature_bus" placeholder="Nature of Business">
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="est_year" placeholder="Established Year">
              </div>
            </div>
               
               <h5>Directors/Partners/Proprietor</h5>
               
            <div class="form-group row">
              <div class="col-sm-4">
                <input type="text" class="form-control" name="d_name1" id="estimate" placeholder="Name" required>
              </div>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="d_natn1" id="estimate" placeholder="Nationality" required>
              </div>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="d_pass1" id="estimate" placeholder="Passport No">
              </div>
            </div>
               
               <div class="form-group row">
              <div class="col-sm-4">
                <input type="text" class="form-control" name="d_name2" id="estimate" placeholder="Name">
              </div>
                 <div class="col-sm-4">
                <input type="text" class="form-control" name="d_natn2" id="estimate" placeholder="Nationality">
              </div>
                 <div class="col-sm-4">
                <input type="text" class="form-control" name="d_pass2" id="estimate" placeholder="Passport No">
              </div>
            </div>
               
             <div class="form-group row">
              <div class="col-sm-4">
                <input type="text" class="form-control" name="d_name3" id="estimate" placeholder="Name">
              </div>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="d_natn3" id="estimate" placeholder="Nationality">
              </div>
              <div class="col-sm-4">
                 <input type="text" class="form-control" name="d_pass3" id="estimate" placeholder="Passport No">
              </div>
             </div>
               
            <h5>Bank Details</h5>   
               
              <div class="form-group row">
              <div class="col-sm-6">
              <input type="text" class="form-control" name="bank" id="value" placeholder="Name of Bank" required>
              </div>
              <div class="col-sm-6">
              <input type="text" class="form-control" name="branch" id="value" placeholder="Branch" required>
              </div>
              </div>
              <div class="form-group row">
              <div class="col-sm-6">
              <input type="text" class="form-control" name="account_name" id="value" placeholder="Account Name" required>
              </div>
              <div class="col-sm-6">
              <input type="text" class="form-control" name="account" id="value" placeholder="Account Number" required>
              </div>
              </div>
              <div class="form-group row">
              <div class="col-sm-6">
              <input type="text" class="form-control" name="name_authorized1" id="value" placeholder="Name of Authorized signatory 1" required>
              </div>
              <div class="col-sm-6">
              <input type="text" class="form-control" name="name_authorized2" id="value" placeholder="Name of Authorized signatory 2">
              </div>
              </div>
              <div class="form-group row">
              <div class="col-sm-6">
              <input type="text" class="form-control" name="name_specimen1" id="value" placeholder="Specimen signatory 1">
              </div>
              <div class="col-sm-6">
              <input type="text" class="form-control" name="name_specimen2" id="value" placeholder="Specimen signatory 2">
              </div>
              </div>
              <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="credit" id="value" placeholder="Credit Limit" required>
              </div>
              <!--<div class="col-sm-4">-->
              <!--<input type="number" class="form-control" name="period" id="value" placeholder="Credit Period" required>-->
              
              <!--<select class="form-control" name="period" required>-->
              <!--  <option value="">Select</option>-->
              <!--  <option value="0">0</option>-->
              <!--  <option value="7">7</option>-->
              <!--  <option value="14">14</option>-->
              <!--  <option value="29">29</option>-->
              <!--  <option value="30">30</option>-->
              <!--  <option value="45">45</option>-->
              <!--  <option value="60">60</option>-->
              <!--  <option value="75">75</option>-->
              <!--  <option value="90">90</option>-->
              <!--  <option value="120">120</option>-->
              <!--  <option value="180">180</option>-->
              <!--</select>-->
              
              <!--</div>-->
              <div class="col-sm-4">
              <input type="text" class="form-control" name="mode" id="value" placeholder="Mode of Payment" required>
              </div>
              </div>
<!--            <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="credit1" placeholder="Extended Limit">
              </div>
            </div>-->
            
            <script>
             $(document).ready(function() {
                $("#if_gurnd").click(function(){
                  $("#ok_gurnd").toggle();
                });
             });
            </script>
            <button id="if_gurnd" type="button" class="btn btn-primary" style="margin:10px;">Guarantee cheque</button>
            <div id="ok_gurnd" style="display:none;">
            
            <!--<div id="ok_gurnd">-->
                
            <h5>Guarantee Cheque details</h5>
              <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="g_chq_no" id="value" placeholder="Cheque Number">
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="g_amt" id="value" placeholder="Amount">
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="g_bank" id="value" placeholder="Bank Name">
              </div>
              </div>
              <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="g_iban" id="value" placeholder="IBAN">
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="g_limit" id="value" placeholder="Approved Limit">
              </div>
              <div class="col-sm-4">
                  <input type="file" class="form-control" name="image" accept="image/jpg,image/jpeg,application/pdf">
              </div>
              </div>
              
              </div>
            
            
              <h5>Business References</h5>
              <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="com_name1" id="value" placeholder="Company" required>
              </div>
              <div class="col-sm-3">
              <input type="text" class="form-control" name="rc_name11" id="value" placeholder="Contact 1" required>
              </div>
              <div class="col-sm-3">
              <input type="text" class="form-control" name="rc_name12" id="value" placeholder="Contact 2">
              </div>
              <div class="col-sm-2">
              <input type="text" class="form-control" name="rc_no1" id="value" placeholder="Contact No" required>
              </div>
              </div>
              <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="com_name2" id="value" placeholder="Company">
              </div>
              <div class="col-sm-3">
              <input type="text" class="form-control" name="rc_name21" id="value" placeholder="Contact 1">
              </div>
              <div class="col-sm-3">
              <input type="text" class="form-control" name="rc_name22" id="value" placeholder="Contact 2">
              </div>
              <div class="col-sm-2">
              <input type="text" class="form-control" name="rc_no2" id="value" placeholder="Contact No">
              </div>
              </div>
              <h5>To be contacted for Payment</h5>
              <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="c_name1" id="value" placeholder="Name" required>
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="c_designation1" id="value" placeholder="Designation" required>
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="c_no1" id="value" placeholder="Mobile Number" required>
              </div>
              </div>
              <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="c_name2" id="value" placeholder="Name">
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="c_designation2" id="value" placeholder="Designation">
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="c_no2" id="value" placeholder="Mobile Number">
              </div>
              </div>
              <h5>Authorized signatories who sign purchase orders</h5>
              <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="name_authorized3" id="value" placeholder="Name">
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="des_authorized3" id="value" placeholder="Designation">
              </div>
              </div>
              <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="name_authorized4" id="value" placeholder="Name">
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="des_authorized4" id="value" placeholder="Designation">
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