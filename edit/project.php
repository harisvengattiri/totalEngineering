<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$name=$_POST["name"];
$customer=$_POST["customer"];
$date=$_POST["date"];
$batch=$_POST["batch"];
$value=$_POST["value"];
$unit=$_POST["unit"];
$balance=$_POST["balance"];
$notes=$_POST["notes"];
$tag=$_POST["tags"];
$tags=json_encode($tag);
$prj=$_POST["prj"];
$sql = "UPDATE `projects` SET `name` =  '$name', `customer` =  '$customer', `date` =  '$date', `batch` =  '$batch', `unit` =  '$unit', `balance` =  '$balance', `value` = '$value', `tags` =  '$tags', `notes` =  '$notes' WHERE  `id` = $prj";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="prj".$prj;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}


if ($_GET) 
{
$prj=$_GET["prj"];
}
	$sql = "SELECT * FROM projects where id=$prj";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {

              $name=$row["name"];
              $date=$row["date"];
              $batch=$row["batch"];
              $value=$row["value"];
              $unit=$row["unit"];
              $balance=$row["balance"];
              $notes=$row["notes"];
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
          <h2>Edit Product</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/edit/project" method="post">
            <div class="form-group row">
               <input name="prj"  type="text" value="<?php echo $prj;?>" hidden="hidden">
              <label for="name" class="col-sm-2 form-control-label">Product Name</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="name" value="<?php echo $name;?>" id="name" placeholder="Project Name">
              </div>
              <label for="date" align="right" class="col-sm-2 form-control-label">Date</label>
              <div class="col-sm-4">
                <input type="text" name="date" id="date" placeholder="Production Date" value="<?php echo $date;?>" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <label for="estimate" class="col-sm-2 form-control-label">Batch No</label>
              <div class="col-sm-2">
                <input type="number" class="form-control" value="<?php echo $batch;?>" name="batch" id="value" placeholder="Batch No">
              </div>
              <label for="estimate" align="right" class="col-sm-1 form-control-label">Unit</label>
              <div class="col-sm-2">
                <input type="number" class="form-control" name="unit"  value="<?php echo $unit;?>" id="estimate" placeholder="Unit">
              </div>
<label for="type" align="right" class="col-sm-1 form-control-label">Tags</label>
              <div class="col-sm-4">
                <select name="tags[]" multiple id="multiple" class="form-control select2-multiple" multiple data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}"">

                  <?php 
			        $tags = json_decode($tags);
			        $arrlength=count($tags);
			        for($x=0;$x<$arrlength;$x++)
  			        {
				?>
				<option value="<?php echo $tags[$x];?>" selected="selected"><?php echo $tags[$x];?></option>
				<?php 
  			        }
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
                 <label for="estimate" class="col-sm-2 form-control-label">Balance</label>
              <div class="col-sm-3">
                <input type="number" class="form-control" value="<?php echo $balance;?>" name="balance" id="value" placeholder="Balance">
              </div>
              <label for="notes" align="right" class="col-sm-2 form-control-label">Notes</label>
              
              <div class="col-sm-5">
                <textarea class="form-control" rows="2" name="notes" value="<?php echo $notes;?>" id="notes" placeholder="Notes"><?php echo $notes;?></textarea>
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