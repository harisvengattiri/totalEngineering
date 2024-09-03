<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<?php
$submit_status="NULL";
if(isset($_POST['submit']))
{
$project=$_POST["project"];
$for = substr($project, 3);
$code = substr($project, 0, 3);
if($code=="PRJ")
{
$wtype="project";
}
elseif($code=="MNT")
{
$wtype="maintenance";
}
$invoice=$_POST["invoice"];
$status=$_POST["status"];
$collector=$_POST["collector"];
$duedate=$_POST["duedate"];
$issuedate=$_POST["issuedate"];
$paid=$_POST["paid"];
$due=$_POST["due"];
$method=$_POST["method"];
$notes=$_POST["notes"];
$division=$_POST["division"];
$wri=$_POST["wri"];
$sql = "UPDATE `work_invoices` SET `wtype` =  '$wtype', `work` =  '$for', `division` = '$division', `status` = '$status', `invoice` = '$invoice', `collector` = '$collector', `duedate` =  '$duedate', `issuedate` =  '$issuedate', `method` = '$method', `paid` =  '$paid', `due` =  '$due', `notes` =  '$notes' WHERE  `id` =$wri";
if ($conn->query($sql) === TRUE) {
    $submit_status="success";
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="wri".$wri;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $submit_status="failed";
}}
if ($_GET) 
{
$wri=$_GET["wri"];
}

	$sql = "SELECT * FROM work_invoices where id=$wri";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {

              $wtype=$row["wtype"];
              $work=$row["work"];
              $division=$row["division"];
              $issuedate=$row["issuedate"];
              $duedate=$row["duedate"];
              $invoice=$row["invoice"];
              $collector=$row["collector"];
              $paid=$row["paid"];
              $due=$row["due"];
              $method=$row["method"];
              $status=$row["status"];
              $notes=$row["notes"];
        }}


if ($wtype=='project')
{ 
				$sql = "SELECT id,name FROM projects where id=$work";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
                                $type="PRJ";
                                $name=$row["name"];
				}}
}
elseif ($wtype=='maintenance') 
{ 
				$sql = "SELECT id,name FROM maintenances where id=$work";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
                                $type="MNT";
                                $name=$row["name"];
				}}
}


  $curr_wtype[] = array("id" => $type.$work, "val" => $type.sprintf("%04d", $work)." [".$name."]");

  $query = "SELECT id,name FROM projects";
  $result = $db->query($query);

  while($row = $result->fetch_assoc()){
    $projects[] = array("id" => PRJ.$row['id'], "val" => "PRJ".sprintf("%04d", $row["id"])." [". $row["name"]."]");
  }

  $query = "SELECT id,name FROM maintenances";
  $result = $db->query($query);

  while($row = $result->fetch_assoc()){
    $maintenances[] = array("id" => MNT.$row['id'], "val" => "MNT".sprintf("%04d", $row["id"])." [". $row["name"]."]");
  }

  $query = "SELECT parent,category,id FROM categories";
  $result = $db->query($query);

  while($row = $result->fetch_assoc()){
    $subcats[$row['parent']][] = array("id" => $row['id'], "val" => $row['category']);
  }
  $mixcat = array_merge($curr_wtype, $projects, $maintenances);
  $jsonCats = json_encode($mixcat);
  $jsonSubCats = json_encode($subcats);


?>
<script type='text/javascript'>
      <?php
        echo "var categories = $jsonCats; \n";
        echo "var subcats = $jsonSubCats; \n";
      ?>
      function loadCategories(){
        var select = document.getElementById("categoriesSelect");
        select.onchange = updateSubCats;
        for(var i = 0; i < categories.length; i++){
          select.options[i] = new Option(categories[i].val,categories[i].id);          
        }
      }
      function updateSubCats(){
        var catSelect = this;
        var catid = this.value;
        var subcatSelect = document.getElementById("subcatsSelect");
        subcatSelect.options.length = 0; //delete all options if any present
        for(var i = 0; i < subcats[catid].length; i++){
          subcatSelect.options[i] = new Option(subcats[catid][i].val,subcats[catid][i].id);
        }
      }
    </script>
<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-12">
	<?php if($submit_status=="success") {?>
	<p><a class="list-group-item b-l-success">
          <span class="pull-right text-success"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label success pos-rlt m-r-xs">
		  <b class="arrow right b-success pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-success">Your Submission was Successfull!</span>
    </a></p>
	<?php } else if($submit_status=="failed") { ?>
	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed!</span>
    </a></p>
	<?php }?>
      <div class="box">
        <div class="box-header">
          <h2>Edit Work Invoice</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/edit/work_invoice" method="post">

<div class="form-group row">
<body onload='loadCategories()'>
<label for="name" class="col-sm-2 form-control-label">Work</label>
<div class="col-sm-4">
    <select name="project" id="categoriesSelect" placeholder="Project" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
<option><?php print_r($mixcat[0][val]);?></option>
    </select>
</div>
<label for="name" align="right" class="col-sm-2 form-control-label">Divison</label>
<div class="col-sm-4">
    <select name="division" id="subcatsSelect" placeholder="Division" class="form-control select2">
				<?php
				$sql = "SELECT id,category FROM categories where id=$division";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{ ?>
				<option selected value="<?php echo $row["id"]; ?>"><?php echo $row["category"]; ?></option>
		          <?php }} ?>
    </select>
</div>
</body>
</div>


<div class="form-group row">
              <label for="invoice" class="col-sm-2 form-control-label">Invoice No</label>
              <div class="col-sm-4">
                <input type="text" value="<?php echo $invoice;?>" class="form-control" name="invoice" id="invoice"  placeholder="Invoice No">
              </div>

              <label align="right" for="status" class="col-sm-2 form-control-label" >Status</label>
              <div class="col-sm-4">
				<select name="status" id="status" placeholder="Status" class="form-control">
				<option value="<?php echo $status;?>"><?php echo $status;?></option>
				<option value="Invoiced">Invoiced</option>
                                <option value="Pending">Pending</option>
				<option value="Partial">Partial</option>
				<option value="Paid">Paid</option>
				<option value="Unpaid">Unpaid</option>
				</select>
              </div>

</div>
            

            <div class="form-group row">
             <label for="amount" class="col-sm-2 form-control-label">Total Due</label>
              <div class="col-sm-4">
               <input type="number"  value="<?php echo $due;?>"  step="0.01" class="form-control" name="due" id="due" placeholder="Total Due Amount">
              </div>

              <label for="date" align="right" class="col-sm-2 form-control-label">Submit Date</label>
              <div class="col-sm-4">
                <input type="text" value="<?php echo $issuedate;?>"  name="issuedate" id="issuedate" placeholder="Issue Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
          
               <input name="wri"  type="text" value="<?php echo $wri;?>" hidden="hidden">
              <label for="collector" class="col-sm-2 form-control-label">Collector</label>
              <div class="col-sm-4">
               <select name="collector" id="collector" placeholder="To" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
				$sql = "SELECT id,name FROM customers where id=$collector";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				$sql = "SELECT id,name FROM customers where type='Staff' or type='Bank' ORDER BY name ASC";
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

              <label for="method" align="right" class="col-sm-2 form-control-label">Method</label>
              <div class="col-sm-4">
                
				<select name="method" id="method" placeholder="Method" class="form-control">
				<option value="<?php echo $method; ?>"><?php echo $method; ?></option>
				<option value="None">None</option>
                                <option value="Cash">Cash</option>
				<option value="Card">Card</option>
				<option value="Cheque">Cheque</option>
				<option value="eTransfer">eTransfer</option>
				</select>
              </div>
              
            </div>
            

            <div class="form-group row">
             <label for="paid" class="col-sm-2 form-control-label">Paid Amount</label>
              <div class="col-sm-4">
               <input type="number"  value="<?php echo $paid;?>" step="0.01" class="form-control" name="paid" id="paid" placeholder="Paid Amount">
              </div>

              <label for="date" align="right" class="col-sm-2 form-control-label">Payment Date</label>
              <div class="col-sm-4">
                <input type="text" value="<?php echo $duedate;?>"  name="duedate" id="duedate" placeholder="Payment Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <label for="notes" class="col-sm-2 form-control-label">Notes</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" value="<?php echo $notes;?>" name="notes" id="notes" placeholder="Notes">
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