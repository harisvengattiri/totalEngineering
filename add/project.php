<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
$name=$_POST["name"];
$date=$_POST["date"];
$batch=$_POST["batch"];
$unit=$_POST["unit"];
$balance=$_POST["balance"];
$notes=$_POST["notes"];
$tag=$_POST["tags"];
$tags=json_encode($tag);
$sql = "INSERT INTO `projects` (`name`, `date`, `batch`, `unit`, `tags`, `notes`, `balance`) 
VALUES ('$name', '$date', '$batch', '$unit', '$tags', '$notes', '$balance')";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="prj".$last_id;
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
          <h2>Add New Product</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/project" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Item</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="name" id="name" placeholder="Item">
              </div>


              <label for="date" align="right" class="col-sm-2 form-control-label">Date</label>
              <div class="col-sm-4">
                <input type="text" name="date" id="date" placeholder="Production Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <label for="Quantity" class="col-sm-2 form-control-label">Batch No</label>
              <div class="col-sm-2">
                <input type="number" class="form-control" name="batch" id="value" placeholder="Batch No">
              </div>
              <label for="estimate" align="right" class="col-sm-1 form-control-label">Unit</label>
              <div class="col-sm-2">
                <input type="number" class="form-control" name="unit" id="estimate" placeholder="Unit">
              </div>
<label for="type" align="right" class="col-sm-1 form-control-label">Quantity</label>
              <div class="col-sm-4">
                <select name="tags[]" multiple id="multiple" class="form-control select2-multiple" multiple data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}"">
                  <?php 
				$sql = "SELECT tag FROM project_tags";
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
            <div class="form-group row">
                 <label for="Quantity" class="col-sm-2 form-control-label">Balance</label>
              <div class="col-sm-3">
                <input type="number" class="form-control" name="balance" id="value" placeholder="Balance">
              </div>
              <label for="notes" align="right" class="col-sm-2 form-control-label">Notes</label>
              <div class="col-sm-5">
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