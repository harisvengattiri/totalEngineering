<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
$rfd=$_POST["id"];
    
$customer=$_POST["customer"];
$rcp=$_POST["rcp"];
$date=$_POST["date"];
$amount=$_POST["amount"];
$bank=$_POST["bank"];
$pmethod=$_POST["pmethod"];
$remarks=$_POST["remarks"];

$sql = "UPDATE `refund` SET `customer`='$customer', `rcp`='$rcp', `amount`='$amount', `date`='$date', `cat`='36', `bank`='$bank',
        `pmethod`='$pmethod', `remarks`='$remarks' WHERE id='$rfd'";

if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="RFD".$rfd;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}
}}


if($_GET) {
    
    $rfd = $_GET['id'];
    $sql_rfd = "SELECT * FROM `refund` WHERE `id`='$rfd'";
    $query_rfd = mysqli_query($conn,$sql_rfd);
    $result_rfd = mysqli_fetch_array($query_rfd);
        $customer = $result_rfd['customer'];
            $sql_cust = "SELECT name FROM `customers` WHERE `id`='$customer'";
            $query_cust = mysqli_query($conn,$sql_cust);
            $result_cust = mysqli_fetch_array($query_cust);
            $cust = $result_cust['name'];
        $rcp = $result_rfd['rcp'];
        $amount = $result_rfd['amount'];
        $date = $result_rfd['date'];
        $bank = $result_rfd['bank'];
            $sql_bank = "SELECT category FROM `expense_subcategories` WHERE `id`='$bank'";
            $query_bank = mysqli_query($conn,$sql_bank);
            $result_bank = mysqli_fetch_array($query_bank);
            $bank_name = $result_bank['category'];
        $pmethod = $result_rfd['pmethod'];
        $remarks = $result_rfd['remarks'];
}
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
          <h2>Edit Refund</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/edit/refund?id=<?php echo $rfd;?>" method="post">
            <input type="hidden" name="id" value="<?php echo $rfd;?>">
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-4">
                <select name="customer" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}" readonly>
                    <option value="<?php echo $customer;?>"><?php echo $cust;?></option>
                </select>
              </div>
            </div>
            
            <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Advance Receipt</label>
               <div class="col-sm-4">
                <select required name="rcp" class="form-control" data-ui-options="{theme: 'bootstrap'}" readonly>
                    <option value="<?php echo $rcp;?>"><?php echo $rcp;?></option>
                </select>
              </div>
            </div>
            
            
            <div class="form-group row"> 
              <label for="date" align="left"  class="col-sm-2 form-control-label">Date</label>
              <div class="col-sm-4">
            <input type="text" name="date" value="<?php echo $date;?>" id="date" placeholder="Production Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                   <input class="form-control" type="number" step="any" name="amount" value="<?php echo $amount;?>">
              </div>
            </div>
              
               
            <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Bank</label>
               <div class="col-sm-4">
                 <select required name="bank" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                <option value="<?php echo $bank;?>"><?php echo $bank_name;?></option>
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
                <option value="<?php echo $pmethod;?>"><?php echo $pmethod;?></option>
                <option value="Cheque">Cheque</option>
                <option value="Cash">Cash</option>
                </select>
              </div>
            </div>
            
            <div class="form-group row">
              <label for="type" class="col-sm-2 form-control-label">Remarks</label>
              <div class="col-sm-8">
                   <input class="form-control" type="text" name="remarks" value="<?php echo $remarks;?>">
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