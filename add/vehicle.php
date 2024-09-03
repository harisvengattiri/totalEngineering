<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
if(isset($_SESSION['userid']))
{
$vehicle=$_POST["vehicle"];
$driver=$_POST["driver"];


$sql = "INSERT INTO `vehicle` (`vehicle`,`driver`) 
VALUES ('$vehicle','$driver')";
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
          <form role="form" action="<?php echo $baseurl;?>/add/vehicle" method="post">
            
            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Vehicle</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="vehicle" id="value" placeholder="vehicle">
              </div>
              </div>
<!--               <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Driver</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="driver" id="value" placeholder="Driver">
              </div>
            </div>-->
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
<script type="text/template" id="temSpecification">
     
     <div class="form-group row" style="padding-top:10px;">
      <label for="name" class="col-sm-1 form-control-label">Item</label>
              <div class="col-sm-3">
                <select name="item[]" class="form-control" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT items FROM items ORDER BY id";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["items"]; ?>"><?php echo $row["items"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
              <label for="type" align="right" class="col-sm-1 form-control-label">Quantity</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="quantity[]" id="estimate" placeholder="Quantity">
              </div>
	      <label for="Quantity" class="col-sm-1 form-control-label">Batch No</label>
              <div class="col-sm-2">
                <input type="number" class="form-control" name="batch[]" id="value" placeholder="Batch No">
              </div>        
             
		
     <div class="box-tools">
     <a href="javascript:void(0);"  class="btn btn-danger btn-sm btnRemoveMoreSpecification" data-original-title="Add More">
     <i class="fa fa-times"></i>
     </a>
     </div>
     </div>
</script>

<script>
$(document).ready(function (event) {
$('#btnAddMoreSpecification').click(function () {
          $('#divSpecificatiion').append($('#temSpecification').html());
     });
     $(document).on('click', '.btnRemoveMoreSpecification', function () {
          $(this).parent('div').parent('div').remove();
     });
});
</script>
<?php include "../includes/footer.php";?>