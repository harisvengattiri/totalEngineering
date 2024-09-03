<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$order_referance=$_POST["order_referance"];
$date=$_POST["date"];
$customer=$_POST["customer"];
$customersite=$_POST["site"];
$vehicle=$_POST["vehicle"];
$driver=$_POST["driver"];
$note=$_POST["note"];
$sql = "INSERT INTO `delivery_note` (`order_referance`, `customer`, `customersite`, `date`, `vehicle`,`driver`,`note`) 
VALUES ('$order_referance', '$customer', '$customersite', '$date', '$vehicle', '$driver', '$note')";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $item=$_POST["item"];
       $reqquantity=$_POST["reqquantity"];
       $thisquantity=$_POST["thisquantity"];
       $bundle=$_POST["bundle"];
       $pdate=$_POST["pdate"];
       $coc=$_POST["coc"];
       $batch=$_POST["batch"];
       $n=sizeof($item);
       for($i=0;$i<$n;$i++)
       {
       $item[$i] = mysqli_real_escape_string($conn,$item[$i]);
       $sql1 = "INSERT INTO `delivery_item` (`delivery_id`,`order_referance`, `item`, `reqquantity`, `thisquantity`, `bundle`,`pdate`,`coc`,`batch`) 
VALUES ('$last_id','$order_referance', '$item[$i]', '$reqquantity[$i]', '$thisquantity[$i]', '$bundle[$i]', '$pdate[$i]', '$coc[$i]', '$batch[$i]')";
$conn->query($sql1);
     $sql2 = "UPDATE batches_lots SET quantity = quantity-'$thisquantity[$i]' WHERE batch='$batch[$i]'";
$conn->query($sql2);
       }
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="mnt".$last_id;
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
          <h2>Add New Delivery Note</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/delivery_note" method="post">
             <div class="form-group row">
              <label for="endd" class="col-sm-2 form-control-label">Delivery Note Referance</label>
              <div class="col-sm-4">
                <select name="order_referance" id="getall" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                <!--<select name="order_referance" id="cust" class="form-control">-->
                  <?php 
				$sql = "SELECT order_referance FROM sales_order ";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                              ?><option value="<?php echo ''; ?>"><?php echo '0';?></option><?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["order_referance"]; ?>">SO <?php echo $row["order_referance"];?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
              <?php
              $date = date('d/m/y');
              ?>
              <label for="date" align="right" class="col-sm-2 form-control-label">Current Date</label>
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
            <label for="date" class="col-sm-2 form-control-label">Note</label>
            <div class="col-sm-4">
                 <textarea type="text" name="note" class="form-control has-value"></textarea>     
            </div>     
            </div>
               <div id="all"></div>
            
               
<!--               <div class="form-group row">
               <label for="endd" class="col-sm-2 form-control-label">Sales Representative</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="salesrepresentative" id="endd" placeholder="Sales Representative">
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
  
<?php include "../includes/footer.php";?>