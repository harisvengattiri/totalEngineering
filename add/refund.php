<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
$customer=$_POST["customer"];
$rcp=$_POST["rcp"];
$date=$_POST["date"];
$amount=$_POST["amount"];
$bank=$_POST["bank"];
$pmethod=$_POST["pmethod"];
$remarks=$_POST["remarks"];


$sql = "INSERT INTO `refund` (`customer`, `rcp`, `amount`, `date`, `cat`, `bank`, `pmethod`, `remarks`) 
VALUES ('$customer', '$rcp', '$amount', '$date', '36', '$bank', '$pmethod', '$remarks')";
if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="RFD".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}
}}
?>
<script>
$(document).on("wheel", "input[type=number]", function (e) {
    $(this).blur();
});
</script>
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-10">
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
          <h2>Add New Refund</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/refund" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-4">
                <select name="customer" id="custrefund" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT name,id FROM customers where type='Company' order by name";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				?><option value=""> </option><?php
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
               <label for="type" class="col-sm-2 form-control-label">Advance Receipt</label>
               <div class="col-sm-4">
                <select required name="rcp" id="rcprfd" class="form-control" data-ui-options="{theme: 'bootstrap'}">
                </select>
              </div>
            </div>
            
            
            
            
            <div class="form-group row"> 
              <label for="date" align="left"  class="col-sm-2 form-control-label">Date</label>
              <div class="col-sm-4">
             <?php
              $today = date('d/m/Y');
              ?>
                <input type="text" name="date" value="<?php echo $today;?>" id="date" placeholder="Production Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <label for="type" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-4">
                   <input class="form-control" type="number" step="any" name="amount">
              </div>
            </div>
              
               
            <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Bank</label>
               <div class="col-sm-4">
                 <select required name="bank" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                <option value="">SELECT BANK</option>
                  <?php 
				$sql = "SELECT id,category FROM `expense_subcategories` WHERE `parent` = '36'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["category"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
            </div>
            
            <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Payment Method</label>
               <div class="col-sm-4">
                 <select required name="pmethod" class="form-control">
                <option value="">SELECT METHOD</option>
                <option value="Cheque">Cheque</option>
                <option value="Cash">Cash</option>
                </select>
              </div>
            </div>
            
            <div class="form-group row">
              <label for="type" class="col-sm-2 form-control-label">Remarks</label>
              <div class="col-sm-8">
                   <input class="form-control" type="text" name="remarks">
              </div>
            </div>
               

 
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