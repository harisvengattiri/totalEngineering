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
    $pre_crdt_image = $_POST["pre_crdt_pdf"];
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
    else{
        $crdt_image = $pre_crdt_image;
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


     
if($_SESSION['username'] == 'suraj') 
{
    $sql = "UPDATE credit_application SET g_chq_no = '$g_chq_no',g_amt = '$g_amt',g_bank = '$g_bank',g_iban = '$g_iban',g_limit = '$g_limit',g_doc = '$crdt_image' WHERE id='$id'";
}
else
{
$sql = "UPDATE credit_application SET company = '$customer',rep = '$rep', licence_no = '$licence_no', exp_date = '$exp_date',"
        . "type_licence = '$type_licence',nature_bus = '$nature_bus',est_year = '$est_year',d_name1 = '$d_name1',d_name2 = '$d_name2',"
        . "d_name3 = '$d_name3',d_natn1 = '$d_natn1',d_natn2 = '$d_natn2',d_natn3 = '$d_natn3',d_pass1 = '$d_pass1',"
        . "d_pass2 = '$d_pass2',d_pass3 = '$d_pass3',bank = '$bank',branch = '$branch',account = '$account',"
        . "account_name = '$account_name',name_authorized1 = '$name_authorized1',name_authorized2 = '$name_authorized2',name_specimen1 = '$name_specimen1',name_specimen2 = '$name_specimen2',"
        . "credit = '$credit',credit1 = '$credit1',period = '$period',mode = '$mode',g_chq_no = '$g_chq_no',g_amt = '$g_amt',g_bank = '$g_bank',g_iban = '$g_iban',g_limit = '$g_limit', g_doc = '$crdt_image',"
        . "com_name1 = '$com_name1',com_name2 = '$com_name2',rc_name11 = '$rc_name11',rc_name12 = '$rc_name12',"
        . "rc_name21 = '$rc_name21',rc_name22 = '$rc_name22',rc_no1 = '$rc_no1',rc_no2 = '$rc_no2',c_name1 = '$c_name1',c_name2 = '$c_name2',c_designation1 = '$c_designation1',c_designation2 = '$c_designation2',"
        . "c_no1 = '$c_no1',c_no2 = '$c_no2',name_authorized3 = '$name_authorized3',name_authorized4 = '$name_authorized4',des_authorized3 = '$des_authorized3',"
        . " des_authorized4 = '$des_authorized4' WHERE id='$id'";
}        

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
          
          $g_chq_no=$result['g_chq_no'];
          $g_amt=$result['g_amt'];
          $g_bank=$result['g_bank'];
          $g_iban=$result['g_iban'];
          $g_limit=$result['g_limit'];
          $crdt_pdf=$result['g_doc'];
          
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
        <form role="form" action="<?php echo $baseurl;?>/edit/credit_application" method="post">
             <input type="text" name="id" value="<?php echo $id;?>" hidden="hidden"> 
            
        <?php
        if($_SESSION['username'] != 'suraj') {
        ?>
        
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
            <div class="form-group row">
              <label for="name" class="col-sm-4 form-control-label">Sales Rep</label>
              <div class="col-sm-8">
                   <select name="rep" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}" required>
                  <?php 
				$sql = "SELECT * FROM customers where type='SalesRep' order by name";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                ?><option value="<?php echo $rep;?>"><?php echo $slrep;?></option><?php
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
              <input type="text" class="form-control" name="licence_no" value="<?php echo $licence_no;?>" placeholder="Trade Licence No" required>
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="exp_date" value="<?php echo $exp_date;?>" placeholder="Expiry Date" required>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="type_licence" value="<?php echo $type_licence;?>" placeholder="Type of Licence" required>
              </div>   
              <div class="col-sm-4">
              <input type="text" class="form-control" name="nature_bus" value="<?php echo $nature_bus;?>" placeholder="Nature of Business">
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="est_year" value="<?php echo $est_year;?>" placeholder="Established Year">
              </div>
            </div>
               
               <h5>Directors/Partners/Proprietor</h5>
               
            <div class="form-group row">
              <div class="col-sm-4">
                <input type="text" class="form-control" name="d_name1" value="<?php echo $d_name1;?>" id="estimate" placeholder="Name" required>
              </div>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="d_natn1" value="<?php echo $d_natn1;?>" id="estimate" placeholder="Nationality" required>
              </div>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="d_pass1" value="<?php echo $d_pass1;?>" id="estimate" placeholder="Passport No">
              </div>
            </div>
               
               <div class="form-group row">
              <div class="col-sm-4">
                <input type="text" class="form-control" name="d_name2" value="<?php echo $d_name2;?>" id="estimate" placeholder="Name">
              </div>
                 <div class="col-sm-4">
                <input type="text" class="form-control" name="d_natn2" value="<?php echo $d_natn2;?>" id="estimate" placeholder="Nationality">
              </div>
                 <div class="col-sm-4">
                <input type="text" class="form-control" name="d_pass2" value="<?php echo $d_pass2;?>" id="estimate" placeholder="Passport No">
              </div>
            </div>
               
             <div class="form-group row">
              <div class="col-sm-4">
                <input type="text" class="form-control" name="d_name3" value="<?php echo $d_name3;?>" id="estimate" placeholder="Name">
              </div>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="d_natn3" value="<?php echo $d_natn3;?>" id="estimate" placeholder="Nationality">
              </div>
              <div class="col-sm-4">
                 <input type="text" class="form-control" name="d_pass3" value="<?php echo $d_pass3;?>" id="estimate" placeholder="Passport No">
              </div>
             </div>
               
            <h5>Bank Details</h5>   
               
              <div class="form-group row">
              <div class="col-sm-6">
              <input type="text" class="form-control" name="bank" value="<?php echo $bank;?>" id="value" placeholder="Name of Bank" required>
              </div>
              <div class="col-sm-6">
              <input type="text" class="form-control" name="branch" value="<?php echo $branch;?>" id="value" placeholder="Branch" required>
              </div>
              </div>
              <div class="form-group row">
              <div class="col-sm-6">
              <input type="text" class="form-control" name="account_name" value="<?php echo $account_name;?>" id="value" placeholder="Account Name" required>
              </div>
              <div class="col-sm-6">
              <input type="text" class="form-control" name="account" value="<?php echo $account;?>" id="value" placeholder="Account Number" required>
              </div>
              </div>
              <div class="form-group row">
              <div class="col-sm-6">
              <input type="text" class="form-control" name="name_authorized1" value="<?php echo $name_authorized1;?>" id="value" placeholder="Name of Authorized signatory 1" required>
              </div>
              <div class="col-sm-6">
              <input type="text" class="form-control" name="name_authorized2" value="<?php echo $name_authorized2;?>" id="value" placeholder="Name of Authorized signatory 2">
              </div>
              </div>
              <div class="form-group row">
              <div class="col-sm-6">
              <input type="text" class="form-control" name="name_specimen1" value="<?php echo $name_specimen1;?>" id="value" placeholder="Specimen signatory 1">
              </div>
              <div class="col-sm-6">
              <input type="text" class="form-control" name="name_specimen2" value="<?php echo $name_specimen2;?>" id="value" placeholder="Specimen signatory 2">
              </div>
              </div>
              <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="credit" value="<?php echo $credit;?>" id="value" placeholder="Credit Limit" required>
              </div>
              <!--<div class="col-sm-4">-->
              <!--<input type="number" class="form-control" name="period" value="ssssssssssss" id="value" placeholder="Credit Period" required>-->
              
              <!--<select class="form-control" name="period" required>-->
              <!--  <option value="<?php // echo $period;?>"><?php // echo $period;?></option>-->
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
              <input type="text" class="form-control" name="mode" value="<?php echo $mode;?>" id="value" placeholder="Mode of Payment" required>
              </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-4">
                  <input type="text" class="form-control" name="credit1" value="<?php echo $credit1;?>" placeholder="Extended Limit">
                  </div>
              </div>
              
        <?php
        }
        ?>
            
            <script>
             $(document).ready(function() {
                $("#if_gurnd").click(function(){
                  $("#ok_gurnd").toggle();
                });
             });
            </script>
            <button id="if_gurnd" type="button" class="btn btn-primary" style="margin:10px;">Guarantee cheque</button>
            <div id="ok_gurnd" <?php if ($g_chq_no == NULL){ ?> style="display:none; <?php } ?>">
            
            <!--<div id="ok_gurnd">-->
            
            <h5>Guarantee Cheque details</h5>
              <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="g_chq_no" id="value" value="<?php echo $g_chq_no;?>" placeholder="Cheque Number">
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="g_amt" id="value" value="<?php echo $g_amt;?>" placeholder="Amount">
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="g_bank" id="value" value="<?php echo $g_bank;?>" placeholder="Bank Name">
              </div>
              </div>
              <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="g_iban" id="value" value="<?php echo $g_iban;?>" placeholder="IBAN">
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="g_limit" id="value" value="<?php echo $g_limit;?>" placeholder="Approved Limit">
              </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-4">
                    <input type="file" class="form-control" name="image" accept="image/jpg,image/jpeg,application/pdf">
                    <input type="hidden" name="pre_crdt_pdf" value="<?php echo $crdt_pdf;?>">
                  </div>
                  <label class="col-sm-2 form-control-label">Guarantee Doc</label>
                  <div class="col-sm-4">
                      <?php if(!empty($crdt_pdf)) { ?>
                      <a target="_blank" style="color:green;" href="<?php echo $baseurl;?>/uploads/credit/<?php echo $crdt_pdf;?>">View</a>&nbsp;
                      <a style="color:red;" href="<?php echo $baseurl;?>/delete/credit_pdf?id=<?php echo $id;?>">Delete</a>
                      <?php } else { ?>
                        <a style="color:red;">No Document for Guarantee is Available</a>
                      <?php } ?>
                  </div>
              </div>
            
            </div>
            
            <?php
            if($_SESSION['username'] != 'suraj') {
            ?>
            
              <h5>Business References</h5>
              <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="com_name1" value="<?php echo $com_name1;?>" id="value" placeholder="Company" required>
              </div>
              <div class="col-sm-3">
              <input type="text" class="form-control" name="rc_name11" value="<?php echo $rc_name11;?>" id="value" placeholder="Contact 1" required>
              </div>
              <div class="col-sm-3">
              <input type="text" class="form-control" name="rc_name12" value="<?php echo $rc_name12;?>" id="value" placeholder="Contact 2">
              </div>
              <div class="col-sm-2">
              <input type="text" class="form-control" name="rc_no1" value="<?php echo $rc_no1;?>" id="value" placeholder="Contact No" required>
              </div>
              </div>
              <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="com_name2" value="<?php echo $com_name2;?>" id="value" placeholder="Company">
              </div>
              <div class="col-sm-3">
              <input type="text" class="form-control" name="rc_name21" value="<?php echo $rc_name21;?>" id="value" placeholder="Contact 1">
              </div>
              <div class="col-sm-3">
              <input type="text" class="form-control" name="rc_name22" value="<?php echo $rc_name22;?>" id="value" placeholder="Contact 2">
              </div>
              <div class="col-sm-2">
              <input type="text" class="form-control" name="rc_no2" value="<?php echo $rc_no2;?>" id="value" placeholder="Contact No">
              </div>
              </div>
              <h5>To be contacted for Payment</h5>
              <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="c_name1" value="<?php echo $c_name1;?>" id="value" placeholder="Name" required>
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="c_designation1" value="<?php echo $c_designation1;?>" id="value" placeholder="Designation" required>
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="c_no1" value="<?php echo $c_no1;?>" id="value" placeholder="Mobile Number" required>
              </div>
              </div>
              <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="c_name2" value="<?php echo $c_name2;?>" id="value" placeholder="Name">
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="c_designation2" value="<?php echo $c_designation2;?>" id="value" placeholder="Designation">
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="c_no2" value="<?php echo $c_no2;?>" id="value" placeholder="Mobile Number">
              </div>
              </div>
              <h5>Authorized signatories who sign purchase orders</h5>
              <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="name_authorized3" value="<?php echo $name_authorized3;?>" id="value" placeholder="Name">
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="des_authorized3" value="<?php echo $des_authorized3;?>" id="value" placeholder="Designation">
              </div>
              </div>
              <div class="form-group row">
              <div class="col-sm-4">
              <input type="text" class="form-control" name="name_authorized4" value="<?php echo $name_authorized4;?>" id="value" placeholder="Name">
              </div>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="des_authorized4" value="<?php echo $des_authorized4;?>" id="value" placeholder="Designation">
              </div>
              </div>
              
            <?php
            }
            ?>
  
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