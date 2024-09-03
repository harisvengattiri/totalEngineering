<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$item=$_POST["item"];
$batch=$_POST["batch"];
$Remaining=$_POST["Remaining"];
$date=$_POST["date"];
$COCNo=$_POST["COCNo"];
$customer=$_POST["customer"];
$value=$_POST["value"];
$estimate=$_POST["estimate"];
$balance=$_POST["balance"];
$notes=$_POST["notes"];
$quantity=$_POST["quantity"];
$sql = "INSERT INTO `batches_lots` (`item`, `batch`, `remaining`, `date`, `COC_No`, `quantity`) 
VALUES ('$item','$batch', '$Remaining', '$date', '$COCNo', '$quantity')";
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
          <h2>Add New Batches/Lots</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/batches_lots" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Item</label>
              <div class="col-sm-4">
                <select name="item" multiple id="multiple" class="form-control select2-multiple" multiple data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}"">
                  <?php 
				$sql = "SELECT name FROM products where quantity >= 1000 GROUP BY name";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["name"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
                </select>
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
              <label for="Quantity" class="col-sm-2 form-control-label">Batch\Lot no</label>
              <div class="col-sm-2">
                <input type="number" class="form-control" name="batch" id="value" placeholder="Batch\Lot no">
              </div>
              <label for="estimate" align="right" class="col-sm-1 form-control-label">Remaining</label>
              <div class="col-sm-2">
                <input type="number" class="form-control" name="Remaining" id="estimate" placeholder="Remaining">
              </div>
<label for="type" align="right" class="col-sm-1 form-control-label">Quantity</label>
              <div class="col-sm-4">
                <input type="number" class="form-control" name="quantity" id="estimate" placeholder="Quantity">
              </div>
            </div>
            <div class="form-group row">
                 <label for="Quantity" class="col-sm-2 form-control-label">COC No</label>
              <div class="col-sm-3">
                <input type="number" class="form-control" name="COCNo" id="value" placeholder="COC No">
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