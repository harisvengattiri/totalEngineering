<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$qtn=$_GET["qtn"];
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
    $item=$_POST["item"];
    $quantity=$_POST["quantity"];
    $unit=$_POST["unit"];
    $total=$quantity*$unit;

$sql = "INSERT INTO `qtn_item_test` (`quotation_id`,`item`, `quantity`, `price`, `total`) 
VALUES ('$qtn','$item', '$quantity', '$unit', '$total')";
if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
    
    $sql2="UPDATE qtn_test SET `subtotal`=`subtotal`+'$total' where id='$qtn'";
    $conn->query($sql2);
    
       $last_id1 = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="QNO".$qtn;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}
//header("Location: goto_quote.php?id=$last_id&or=$order_referance");
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
          <h2>Add Item to Quotation #<?php echo $qtn;?></h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/quotation_po_item?qtn=<?php echo $qtn;?>" method="post">
            
            <div class="form-group row">
              <label for="name" class="col-sm-1 form-control-label">Item</label>
              <div class="col-sm-3">
                <select name="item" class="form-control select2" placeholder="Item" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT items,id FROM items ORDER BY items";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["items"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
              <div class="col-sm-2">
                   <input type="number" min="1" step="any" class="form-control" name="quantity" id="input1" placeholder="Quantity">
              </div>
              <div class="col-sm-2">
                <input type="number" min="1" step="any" class="form-control" name="unit" id="input2" placeholder="Unit Price">
              </div>
              
            </div>
            
              
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <a href="<?php echo $baseurl;?>/view_quotation_po?qtn=<?php echo $qtn;?>" class="btn btn-sm btn-outline rounded b-danger text-danger">Cancel</a>
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