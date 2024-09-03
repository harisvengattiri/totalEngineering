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
$customer=$_POST["company"];

$credit=$_POST["credit"];
$credit1=$_POST["credit1"];
$period=$_POST["period"];
$mode=$_POST["mode"];   

$sql = "UPDATE credit_application SET company = '$customer', credit = '$credit', credit1 = '$credit1', period = '$period', mode = '$mode' WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="CDT".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}}


if(isset($_GET['id']))
{
    $id=$_GET['id']; 
}
$sql="SELECT * FROM credit_application where id='$id'";
$query=mysqli_query($conn,$sql);
if(mysqli_num_rows($query) > 0)
{
     while($result=mysqli_fetch_array($query))
     {
          $company=$result['company'];
          $sqlcust = "SELECT name FROM customers where id=$company";
	  $resultcust = mysqli_query($conn, $sqlcust);
          $rowcust = mysqli_fetch_assoc($resultcust);
          $cust=$rowcust['name'];
          
          $rep=$result['rep'];
          $sqlrep = "SELECT name FROM customers where id=$rep";
	  $resultrep = mysqli_query($conn, $sqlrep);
          $rowrep = mysqli_fetch_assoc($resultrep);
          $slrep=$rowrep['name'];
          
          $licence_no=$result['licence_no'];
          $exp_date=$result['exp_date'];
          $type_licence=$result['type_licence'];
          $nature_bus=$result['nature_bus'];
          
          $est_year=$result['est_year'];
          $d_name1=$result['d_name1'];
          $d_name2=$result['d_name2'];
          $d_name3=$result['d_name3'];
          $d_natn1=$result['d_natn1'];
          $d_natn2=$result['d_natn2'];
          $d_natn3=$result['d_natn3'];
          
          $d_pass1=$result['d_pass1'];
          $d_pass2=$result['d_pass2'];
          $d_pass3=$result['d_pass3'];
          $bank=$result['bank'];
          $branch=$result['branch'];
          $account=$result['account'];
          $account_name=$result['account_name'];
          
          $name_authorized1=$result['name_authorized1'];
          $name_authorized2=$result['name_authorized2'];
          $name_specimen1=$result['name_specimen1'];
          $name_specimen2=$result['name_specimen2'];
          $credit=$result['credit'];
          $credit1=$result['credit1'];
          $period=$result['period'];
          $mode=$result['mode'];
          
          $com_name1=$result['com_name1'];
          $com_name2=$result['com_name2'];
          $rc_name11=$result['rc_name11'];
          $rc_name12=$result['rc_name12'];
          $rc_name21=$result['rc_name21'];
          $rc_name22=$result['rc_name22'];
          $rc_no1=$result['rc_no1'];
          $rc_no2=$result['rc_no2'];
          $c_name1=$result['c_name1'];
          $c_name2=$result['c_name2'];
          
          $c_designation1=$result['c_designation1'];
          $c_designation2=$result['c_designation2'];
          $c_no1=$result['c_no1'];
          $c_no2=$result['c_no2'];
          $name_authorized3=$result['name_authorized3'];
          $name_authorized4=$result['name_authorized4'];
          $des_authorized3=$result['des_authorized3'];
          $des_authorized4=$result['des_authorized4'];
     }
}


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
          <h2>Edit Credit Application</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
        <form role="form" action="<?php echo $baseurl;?>/edit/credit_application_ext" method="post">
             <input type="text" name="id" value="<?php echo $id;?>" hidden="hidden"> 
            <div class="form-group row">
              <label for="name" class="col-sm-4 form-control-label">Customer</label>
              <div class="col-sm-8">
                   <select name="company" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}" required>
                  <?php 
				$sql = "SELECT * FROM customers where type='Company' order by name";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                ?><option value="<?php echo $company;?>"><?php echo $cust;?></option><?php
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

           
              <h5>Bank Details</h5>
              <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="credit" value="<?php echo $credit;?>" id="value" placeholder="Credit Limit" required>
              </div>
              <!--<div class="col-sm-4">-->
              <!--<input type="text" class="form-control" name="period" value="<?php echo $period;?>" id="value" placeholder="Credit Period" required>-->
              <!--</div>-->
              <div class="col-sm-4">
              <input type="text" class="form-control" name="mode" value="<?php echo $mode;?>" id="value" placeholder="Mode of Payment" required>
              </div>
              </div>
            <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="credit1" value="<?php echo $credit1;?>" placeholder="Extended Limit">
              </div>
            </div>
               
  
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>
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