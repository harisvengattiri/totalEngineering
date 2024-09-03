<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
$item=mysqli_real_escape_string($conn, $_POST['item']);
$batch=$_POST["batch"];
$date=$_POST["date"];
$pdate=$_POST["pdate"];
$COCNo=$_POST["COCNo"];
$quantity=$_POST["quantity"];
$batch_type=$_POST["batch_type"];
$count=$_POST["count"];
$available=$_POST["avl"];

if($quantity>$available)
{
    $status="failed1";
}
else
{

$sql = "INSERT INTO `batches_lots` (`item`, `batch`, `date`, `pdate`, `COC_No`, `quantity`, `batch_type`, `count`) 
VALUES ('$item','$batch', '$date', '$pdate', '$COCNo', '$quantity', '$batch_type', '$count')";


if ($conn->query($sql) === TRUE) {
	$last_id = $conn->insert_id;
	$sql1 = "INSERT INTO `prod_items` (`item`,`sale_id`,`s_quantity`) 
VALUES ('$item','$last_id','$quantity')";
	$conn->query($sql1);
    	$status="success";
       
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="prj".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}}}
?>
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-6">
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
       <?php } else if($status=="failed1") {?>
    	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed! Due to Less Availability of Stock</span>
    </a></p>
      <?php } ?>
      <div class="box">
        <div class="box-header">
          <h2>Add New Batches/Lots</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/lot_creation" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-4 form-control-label">Item</label>
              <div class="col-sm-8">
                <select class="form-control" class="" name="item" id="country">
                  <?php 
				$sql = "SELECT item FROM prod_items GROUP BY item";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                               ?><option value="<?php echo ''; ?>"><?php echo '';?></option><?php
				while($row = mysqli_fetch_assoc($result)) 
				{ 
				?>
				<option value="<?php echo $row["item"]; ?>"><?php echo $row["item"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
              </div>
              <div class="form-group row">
              <label for="type" align="left" class="col-sm-4 form-control-label">Available Quantity</label>
              <div class="col-sm-8" id="state">
                   <input type="" class="form-control" name="avl" readonly>
              </div>
             
            </div>
               
               <div class="form-group row">
                   <label for="date" class="col-sm-4 form-control-label">Date of Lot Creation</label>
                   <?php $date1 = date('d/m/Y');?>
              <div class="col-sm-8">
                   <input type="text" name="date" value="<?php echo $date1;?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                    <label for="date" class="col-sm-4 form-control-label">Production Date</label>
              <div class="col-sm-8">
                <input type="text" name="pdate" id="date" placeholder="Production Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                <label for="Quantity" class="col-sm-4 form-control-label">Quantity for Lot</label>
              <div class="col-sm-8">
              <input type="number" class="form-control" name="quantity">
              </div>     
              </div> 
               
               
            <div class="form-group row">
             <label for="Quantity" class="col-sm-4 form-control-label">Batch No</label>
               <div class="col-sm-8">
                   <?php 
                   $sql = "SELECT batch FROM batches_lots ORDER BY batch DESC LIMIT 1";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                     $value=0;
				while($row = mysqli_fetch_assoc($result)) 
				{ 
                                     $value=$row["batch"]+1;
				?>
                   <input type="" class="form-control" name="batch" value="<?php echo $value;?>" readonly>
				<?php 
				}}
                                else{
                                     $value=18850000;
                                    ?> <input type="" class="form-control" name="batch" value="<?php echo $value;?>" readonly><?php
                                    }
		  ?>
                   
              </div>
              </div>
              
              <div class="form-group row">
              <label for="Quantity" align="left" class="col-sm-2 form-control-label">Batch Type</label>
              <div class="col-sm-3">
              <select  class="form-control" name="batch_type">
                   <option value="COC">COC</option>
                   <option value="Non COC">Non COC</option>
              </select>
              </div>
              <label for="Quantity" align="right" class="col-sm-2 form-control-label">COC No</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="COCNo" id="value" placeholder="COC No">
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