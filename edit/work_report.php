<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$wno=$_GET["wno"];
$status="NULL";
if(isset($_POST['submit']))
{
$wno=$_POST["wno"];
$customer=$_POST["customer"];
$contact=$_POST["contact"];
$phone=$_POST["phone"];
$phone=preg_replace('/\D+/', '', $phone);
$phone = preg_replace("/^971/", "0", "$phone");
$mobile=$_POST["mobile"];
$mobile=preg_replace('/\D+/', '', $mobile);
$mobile = preg_replace("/^971/", "0", "$mobile");
$whatsapp=$_POST["whatsapp"];
$whatsapp=preg_replace('/\D+/', '', $whatsapp);
$whatsapp = preg_replace("/^971/", "0", "$whatsapp");
$salesman=$_POST["salesman"];
$staff=$_POST["staff"];
$map = mysqli_real_escape_string($conn, $_POST['map']);
$site=$_POST["site"];
$date=$_POST["date"];
$type=$_POST["type"];
$materials=$_POST["materials"];
$materials=json_encode($materials);
$description=$_POST["description"];
$status=$_POST["status"];
$efrom=$_POST["efrom"];
$notes=$_POST["notes"];
$sql = "UPDATE `work_reports` SET `customer` = '$customer', `contact` = '$contact', `phone` = '$phone', `site` = '$site',
`date` = '$date', `type` = '$type', `materials` = '$materials', `description` = '$description', `status` = '$status',
`staff` = '$staff', `salesman` = '$salesman', `mobile` = '$mobile', `whatsapp` = '$whatsapp', `map` = '$map', `efrom` = '$efrom',
`notes` = '$notes' WHERE `work_no` = $wno";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="wrt".$wno;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}
?>
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-12">
	<?php if($status=="success") 
	{
	?>
	<meta http-equiv="refresh" content="0;url=<?php echo $baseurl; ?>/work_reports?status=success">
	<p><a class="list-group-item b-l-success">
          <span class="pull-right text-success"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label success pos-rlt m-r-xs">
		  <b class="arrow right b-success pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-success">Your Submission was Successfull!</span>
    </a></p>
    	<?php 
    	} 
    	else if($status=="failed") 
    	{
    	?>
	<meta http-equiv="refresh" content="0;url=<?php echo $baseurl; ?>/work_reports?status=failed">
	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed!</span>
    </a></p>
    	<?php 
    	}
    	?>
	      <div class="box">
        <div class="box-header">
          <h2>Edit Work Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">

				<?php 
				$sql = "SELECT * FROM work_reports where work_no=$wno";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				$customer= $row["customer"];
				$contact= $row["contact"];
				$phone= $row["phone"];
				$site= $row["site"];
				$date= $row["date"];
				$type= $row["type"];
				$map= $row["map"];
				$mobile= $row["mobile"];
				$whatsapp= $row["whatsapp"];
				$staff= $row["staff"];
				$salesman= $row["salesman"];
				$materials= $row["materials"];
				$description= $row["description"];
				$status= $row["status"];
				$efrom= $row["efrom"];
				$notes= $row["notes"];
				}} 
				?>          


          <form role="form" action="<?php echo $baseurl;?>/edit/work_report" method="post">
            <div class="form-group row">
              <label for="number" class="col-sm-2 form-control-label" >Work Report No</label>
              <div class="col-sm-2">
	      <input type="text" class="form-control" name="wno" value="<?php echo $wno;?>" readonly>
              </div>
              <label for="customer" align="right" class="col-sm-1 form-control-label">Customer</label>
              <div class="col-sm-3">
              <select name="customer" id="customer" placeholder="Customer" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
				$sql = "SELECT id,name FROM customers where id=$customer";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				$sql = "SELECT id,name FROM customers ORDER BY name ASC";
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
              <label for="salesman" align="right" class="col-sm-1 form-control-label">Salesman</label>
              <div class="col-sm-3">
              <select name="salesman" id="salesman" placeholder="Salesman" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
				$sql = "SELECT id,name FROM customers where id=$salesman";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				else
				{
				?>
				<option value="">Others</option>
				<?php 
				}
				$sql = "SELECT id,name FROM customers where type='staff' ORDER BY name ASC";
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
              <label for="efrom" class="col-sm-2 form-control-label">Enquiry From</label>
              <div class="col-sm-2">
                <select name="efrom" id="efrom" placeholder="Enquiry From" class="form-control">
                                    <option value='<?php echo $efrom;?>'><?php echo $efrom;?></option>  
                                    <option value='Phone'>Phone</option>
                                    <option value='Email'>Email</option>
                                    <option value='Sales'>Sales</option>
                                    <option value='Direct'>Direct</option>
                                    <option value='Others'>Others</option>       
		</select>
              </div>
              <label for="type" align="right" class="col-sm-1 form-control-label">Work Type</label>
              <div class="col-sm-2">
                <select name="type" id="method" placeholder="Work Type" class="form-control">
                                    <option value='<?php echo $type;?>'><?php echo $type;?></option>  
                                    <option value='Supply Only'>Supply Only</option>
                                    <option value='Supply & Installation'>Supply & Installation</option>                                       		                          
                                    <option value='Maintanance'>Maintanance</option>
                                    <option value='AMC'>AMC</option>
                                    <option value='Under Warranty'>Under Warranty</option> 
		</select> 
		</select>
              </div>
              <label for="staff" align="right" class="col-sm-1 form-control-label">Staff</label>
              <div class="col-sm-4">
              <select name="staff" id="staff" placeholder="Staff" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
				$sql = "SELECT id,name FROM customers where id=$staff";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				else
				{
				?>
				<option value="">Others</option>
				<?php 
				}
				$sql = "SELECT id,name FROM customers where type='staff' ORDER BY name ASC";
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
              <label for="contact" class="col-sm-2 form-control-label">Site Person</label>
              <div class="col-sm-4">
               <input type="text" class="form-control" name="contact" value="<?php echo "$contact";?>" id="contact"  placeholder="Site Person">
              </div>
              <label for="phone" align="right" class="col-sm-2 form-control-label">Site Phone</label>
              <div class="col-sm-4">
               <input type="text" class="form-control" name="phone" id="phone" value="<?php echo "$phone";?>" placeholder="Site Phone">
              </div>
</div>

<div class="form-group row">
              <label for="mobile" class="col-sm-2 form-control-label">Site Mobile</label>
              <div class="col-sm-4">
               <input type="text" class="form-control" name="mobile" id="mobile" value="<?php echo "$mobile";?>" placeholder="Site Mobile">
              </div>
              <label for="mobile" align="right" class="col-sm-2 form-control-label">Site Whatsapp</label>
              <div class="col-sm-4">
               <input type="whatsapp" class="form-control" name="whatsapp" value="<?php echo "$whatsapp";?>" id="whatsapp" placeholder="Site Whatsapp">
              </div>
</div>

<div class="form-group row">
              <label for="site" class="col-sm-2 form-control-label">Work Site</label>
              <div class="col-sm-4">
               <input type="text" class="form-control" name="site" id="site" value="<?php echo $site;?>" placeholder="Work Site">
              </div>
              <label for="date" align="right" class="col-sm-2 form-control-label">Date</label>
              <div class="col-sm-4">
               <input type="text" name="date" id="date" placeholder="Date" value="<?php echo $date;?>" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
            format: 'DD/MM/YYYY',
            icons: {
              time: 'fa fa-clock-o',
              date: 'fa fa-calendar',
              up: 'fa fa-chevron-up',
              down: 'fa fa-chevron-down',
              previous: 'fa fa-chevron-left',
              next: 'fa fa-chevron-right',
              today: 'fa fa-screenshot',
              clear: 'fa fa-trash',
              close: 'fa fa-remove'
            }
          }">
              </div>
</div>


            <div class="form-group row">
              <label for="description" class="col-sm-2 form-control-label">Work Description</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" value="<?php echo $description;?>" name="description" id="description" placeholder="Work Description">
              </div>
              </div>


            <div class="form-group row">
              <label for="map" class="col-sm-2 form-control-label">Location Link</label>
              <div class="col-sm-10">
                <input type="map" class="form-control" value="<?php echo $map;?>" name="map" id="map" placeholder="Location Link">
              </div>
              </div>


<div class="form-group row">
              <label for="materials" class="col-sm-2 form-control-label">Materials</label>
              <div class="col-sm-7">
               <select name="materials[]" multiple id="multiple" class="form-control select2-multiple" multiple data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}"">
                  <?php
				
			        $materials = json_decode($materials);
			        $arrlength=count($materials);
			        for($x=0;$x<$arrlength;$x++)
  			        {	
				$sql = "SELECT id,name FROM materials where id=$materials[$x]";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>" selected="selected"><?php echo $row["name"]?></option>
				<?php 
				}}
  			        }			
				$sql = "SELECT id,name FROM materials";
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
              <label for="status" align="right" class="col-sm-1 form-control-label">Status</label>
              <div class="col-sm-2">

                <select name="status" id="status" placeholder="Status" class="form-control">
                                    <option value='<?php echo $status;?>'><?php echo $status;?></option>  
                                    <option value='Attended'>Attended</option>
                                    <option value='Inspection'>Inspection</option>  
                                    <option value='Quotation Sent'>Quotation Sent</option>   
                                    <option value='Approved'>Approved</option>    
                                    <option value='Assigned for Job'>Assigned for Job</option>    
                                    <option value='Invoiced'>Invoiced</option>  
		</select>
              </div>
</div>

            <div class="form-group row">
              <label for="notes" class="col-sm-2 form-control-label">Notes</label>
              <div class="col-sm-10">
              <textarea name="notes" data-ui-jp="summernote"><?php echo $notes;?></textarea>
              </div>
            </div>
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <a href="<?php echo $baseurl;?>/work_reports" class="btn btn-sm btn-outline rounded b-primary text-primary">Back</a>
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