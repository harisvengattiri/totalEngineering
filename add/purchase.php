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
$submit_status="NULL";
if(isset($_POST['submit']))
{
$project=$_POST["project"];
$for = substr($project, 3);
$code = substr($project, 0, 3);
if($code=="PRJ")
{
$work="project";
}
elseif($code=="MNT")
{
$work="maintenance";
}
$purchaser=$_POST["purchaser"];
$shop=$_POST["shop"];
$invno=$_POST["invno"];
$price=$_POST["price"];
$taxp=$_POST["taxp"];
$taxa=$_POST["taxa"];
$amount=$_POST["amount"];
$status=$_POST["status"];
$method=$_POST["method"];
$date=$_POST["date"];
$notes=$_POST["notes"];
$division=$_POST["division"];
$sql = "INSERT INTO `purchases` (`work`, `forid`, `invno`, `division`, `purchaser`, `shop`, `date`, `status`, `method`, `price`, `taxp`, `taxa`, `amount`,  `notes`) 
VALUES ('$work', '$for', '$invno', '$division', '$purchaser', '$shop', '$date', '$status', '$method', '$price', '$taxp', '$taxa', '$amount', '$notes')";
if ($conn->query($sql) === TRUE) {
    $submit_status="success";
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="prc".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $submit_status="failed";
}}
?>
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
          <h2>Add New Purchase</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">

          <form oninput="x.value=parseFloat(a.value)/100*parseFloat(b.value), y.value=parseFloat(a.value)+parseFloat(x.value)" name="addpurchase" role="form" action="<?php echo $baseurl;?>/add/purchase" method="post">
<div class="form-group row">
<body onload='loadCategories()'>
<label for="name" class="col-sm-2 form-control-label">Purchased For</label>
<div class="col-sm-4">
    <select name="project" id="categoriesSelect" placeholder="Project" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
<option selected><?php print_r($mixcat[0][val]);?></option>
    </select>
</div>
<label for="name" align="right" class="col-sm-2 form-control-label">Divison</label>
<div class="col-sm-4">
    <select name="division" id="subcatsSelect" placeholder="Division" class="form-control select2">
    <option selected value=""> Select the Project First </option>
    </select>
</div>
</body>
</div>

            <div class="form-group row">
              <label for="customer" class="col-sm-2 form-control-label">Purchaser</label>
              <div class="col-sm-2">
				<select name="purchaser" id="purchaser" placeholder="Purchaser" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
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
              <label for="shop" align="right" class="col-sm-1 form-control-label">Shop</label>
              <div class="col-sm-3">
                
				<select name="shop" id="shop" placeholder="Shop" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
				$sql = "SELECT id,name FROM customers where type='Shop' ORDER BY name ASC";
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
              </div><label for="amount" align="right" class="col-sm-1 form-control-label">Date</label>
              <div class="col-sm-3"><input type="text" name="date" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
             <label for="amount" class="col-sm-2 form-control-label">Price</label>
              <div class="col-sm-2">
               <input type="number" step="0.01" class="form-control" id="a" name="price" placeholder="Price">
              </div>
             <label for="amount" class="col-sm-1 form-control-label">Tax %</label>
              <div class="col-sm-1">
               <input type="number" step="0.01" class="form-control" id="b" value="5.00" name="taxp" placeholder="Tax %">
              </div>
             <label for="amount" class="col-sm-1 form-control-label">Tax Amount</label>
              <div class="col-sm-2">
               <input type="text" class="form-control" id="x" name="taxa" placeholder="Tax Amount">
              </div>
             <label for="amount" class="col-sm-1 form-control-label">Amount</label>
              <div class="col-sm-2">
               <input type="text" class="form-control" id="y" name="amount" placeholder="Amount">
              </div>
            </div>

            <div class="form-group row">
              <label for="invno" class="col-sm-2 form-control-label">Invoice No</label>
              <div class="col-sm-2">
               <input class="form-control"  name="invno" type="text" placeholder="Invoice No">
			  </div>
              <label for="status" align="right" class="col-sm-1 form-control-label">Status</label>
              <div class="col-sm-3">
                
				<select name="status" id="status" placeholder="Status" class="form-control">
				<option value="credit">Credit</option>
				<option value="debit">Debit</option>
				</select>
              </div>
              <label for="method" align="right" class="col-sm-1 form-control-label">Method</label>
              <div class="col-sm-3">
                
				<select name="method" id="method" placeholder="Method" class="form-control">
				<option value="none">None</option>
                                <option value="cash">Cash</option>
				<option value="card">Card</option>
				<option value="cheque">Cheque</option>
				<option value="etransfer">eTransfer</option>
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