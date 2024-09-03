<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<?php
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
  $mixcat = array_merge($projects, $maintenances);
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
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$work=$_POST["work"];
$code = substr($work, 0, 3);
$work = substr($work, 3);
if($code=="PRJ")
{
$wtype="project";
}
elseif($code=="MNT")
{
$wtype="maintenance";
}
$receiver=$_POST["receiver"];
$amount=$_POST["amount"];
$method=$_POST["method"];
$date=$_POST["date"];
$notes=$_POST["notes"];
$division=$_POST["division"];
$sql = "INSERT INTO `payments` (`work`, `wtype`, `division`, `reciever`, `date`, `method`, `amount`, `notes`) 
VALUES ('$work', '$wtype', '$division', '$receiver',  '$date', '$method', '$amount', '$notes')";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="pym".$last_id;
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
          <h2>Add New Payment</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">

          <form name="addpayment" role="form" action="<?php echo $baseurl;?>/add/payment" method="post">
<div class="form-group row">
<body onload='loadCategories()'>
<label for="work" class="col-sm-2 form-control-label">Work</label>
<div class="col-sm-4">
    <select name="work" id="categoriesSelect" placeholder="work" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
<option selected><?php print_r($mixcat[0][val]);?></option>
    </select>
</div>
<label for="name" align="right" class="col-sm-2 form-control-label">Divison</label>
<div class="col-sm-4">
    <select name="division" id="subcatsSelect" placeholder="Division" class="form-control select2">
    <option selected value=""> Select the Work First </option>
    </select>
</div>
</body>
</div>

            <div class="form-group row">
              <label for="receiver" class="col-sm-2 form-control-label">Receiver</label>
              <div class="col-sm-4">
				<select name="receiver" id="receiver" placeholder="receiver" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
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
              <label for="amount" align="right" class="col-sm-2 form-control-label">Date</label>
              <div class="col-sm-4"><input type="text" name="date" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
             <label for="amount" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-4">
               <input type="number" step="0.01" class="form-control" name="amount" id="amount" placeholder="Amount">
              </div>
              <label for="method" align="right" class="col-sm-2 form-control-label">Method</label>
              <div class="col-sm-4">
                
				<select name="method" id="method" placeholder="Method" class="form-control">
				<option value="None">None</option>
                                <option value="Cash">Cash</option>
				<option value="Card">Card</option>
				<option value="Cheque">Cheque</option>
				<option value="eTransfer">eTransfer</option>
				</select>
              </div>

            </div>

            <div class="form-group row">
              <label for="notes" class="col-sm-2 form-control-label">Notes</label>
              <div class="col-sm-10">
                <textarea class="form-control" rows="2" name="notes" id="notes" placeholder="Notes"></textarea>
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