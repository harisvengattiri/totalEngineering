<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
$item = $_POST['item'];
$batch=$_POST["batch"];
$date=$_POST["date"];
$pdate=$_POST["pdate"];
$COCNo=$_POST["COCNo"];
$quantity=$_POST["quantity"];
$oldquantity=$_POST["oldquantity"];
$batch_type=$_POST["batch_type"];
// $count=$_POST["count"];
$prj=$_POST["prj"];

$available=$_POST["avl"];
$newavailable=$available+$oldquantity;
if($quantity>$newavailable)
{
    $status="failed1";
}
else
{
$sql = "UPDATE `batches_lots` SET `item` =  '$item', `date` =  '$date', `pdate` =  '$pdate', `batch` =  '$batch', `COC_No` =  '$COCNo', `batch_type` =  '$batch_type', `count` =  '1', `quantity` =  '$quantity' WHERE  `id` = $prj";
if ($conn->query($sql) === TRUE) {
     $status="success";
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="LOT".$batch;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}}}


if ($_GET) 
{
$prj=$_GET["id"];
}
	$sql = "SELECT * FROM batches_lots where id=$prj";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		    {
        while($row = mysqli_fetch_assoc($result)) {
              $item =$row["item"];
              $date=$row["date"];
              $pdate=$row["pdate"];
              $quantity=$row["quantity"];
              $quantity=($quantity != NULL) ? $quantity : 0;
              $COC_No=$row["COC_No"];
              // $count=$row["count"];
              $batch_type=$row["batch_type"];
              $batch=$row["batch"];
              
              
                    $sqlquan="SELECT quantity FROM prod_items WHERE item='$item'";
                    $sqlsquan="SELECT quantity FROM batches_lots WHERE item='$item'";
                    //echo $sql1;
                    $resultquan=$conn->query($sqlquan);
                    $resultsquan=$conn->query($sqlsquan);    
                    if($resultquan->num_rows > 0){
                       $quan=0;
                       $s_quan=0;
                        while($row=$resultquan->fetch_assoc()) {
                          if($row['quantity'] != NULL) {
                          $quan=$quan+$row['quantity'];
                          }
                        }
                        while($row1=$resultsquan->fetch_assoc()) {
                          if($row1['quantity'] != NULL) {
                          $s_quan=$s_quan+$row1['quantity'];
                          }
                        }

                         $finalquan = $quan - $s_quan;
                    }
              
              
            }  }



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
        <?php } else if($status=="failed1") { ?>
    	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed! Due to Less Availability of Stock</span>
    </a></p>
        <?php } ?>
      <div class="box">
        <div class="box-header">
          <h2>Edit Lot</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
             
             
          <form role="form" action="<?php echo $baseurl;?>/edit/lot_creation?id=<?php echo $prj;?>" method="post">
            <div class="form-group row">
               <input name="prj"  type="text" value="<?php echo $prj;?>" hidden="hidden">
               <label align="" class="col-sm-2 form-control-label"></label>
               <label for="name" align="" class="col-sm-1 form-control-label"><b>Item</b></label>
              <div class="col-sm-6">
                <select class="form-control" class="" name="item" id="country">
                  <?php 
				$sql = "SELECT item FROM prod_items GROUP BY item";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result))
				{ 
                                     $item1=$row['item'];
                                     
                                     $selected = ($row['item'] == $item) ? "selected='selected'" : '';
                                     $sqlitem="SELECT items from items where id='$item1'";
                                     $queryitem=mysqli_query($conn,$sqlitem);
                                     $fetchitem=mysqli_fetch_array($queryitem);
				?>
				<option <?php echo $selected;?> value="<?php echo $row["item"]; ?>"><?php echo $fetchitem['items'];?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
            </div>
               
               <?php
                 $sqllot="SELECT thisquantity FROM delivery_item WHERE batch='$batch'";
                 $resultlot=$conn->query($sqllot);
                 $delquan=0;
                 while($rowlot=$resultlot->fetch_assoc())
                 {
                  if($rowlot['thisquantity'] != NULL) {
                    $delquan=$delquan+$rowlot['thisquantity'];
                  }
                 }
                 $sqlrtn="SELECT returnqnt FROM stock_return WHERE batch='$batch'";
                 $resultrtn=$conn->query($sqlrtn);
                 $rtnquan=0;
                 while($rowrtn=$resultrtn->fetch_assoc())
                 {
                  if($rowrtn['returnqnt'] != NULL) {
                    $rtnquan=$rtnquan+$rowrtn['returnqnt'];
                  }
                 }
                 $lotquan=$quantity+$rtnquan-$delquan;
               ?>
                
               <div class="form-group row" style="margin-bottom: 0px;">
                 <label for="type" align="center" class="col-sm-6 form-control-label"><b>Available Stock in Production</b></label>
                 <label for="type" align="center" class="col-sm-6 form-control-label"><b>Available Stock in Batch</b></label>
            </div>
            <div class="form-group row">
              <div class="col-sm-6" id="state">
                   <input type="" class="form-control" value="<?php echo $finalquan;?>" name="avl" readonly>
              </div>
                 <div class="col-sm-6" id="state123">
                   <input type="" class="form-control" value="<?php echo $lotquan;?>" name="avl123" readonly>
              </div>
            </div>   
               
               
               <div class="form-group row">
                  <label for="date" class="col-sm-4 form-control-label">Date of Lot Creation</label>
              <div class="col-sm-8">
                   <input type="text" name="date" value="<?php echo $date;?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                   <input type="text" name="pdate" value="<?php echo $pdate;?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
               <label for="Quantity" class="col-sm-4 form-control-label">Quantity For Lot</label>
              <div class="col-sm-8">
                   <input type="number" min="1" step="0.01" class="form-control" value="<?php echo $quantity;?>" name="quantity">
                   <input type="hidden" class="form-control" value="<?php echo $quantity;?>" name="oldquantity">
              </div>
              </div>
               
               
            <div class="form-group row">
               <label for="Quantity" class="col-sm-4 form-control-label">Batch No</label>
              <div class="col-sm-8">
                   <input type="" class="form-control" name="batch" value="<?php echo $batch;?>" readonly>
              </div> 
            </div>
            <div class="form-group row">
              <label for="Quantity" align="left" class="col-sm-2 form-control-label">Batch Type</label>
              <div class="col-sm-3">
              <select  class="form-control" name="batch_type">
                   <option value="<?php echo $batch_type;?>"><?php echo $batch_type;?></option>
                   <option value="COC">COC</option>
                   <option value="Non COC">Non COC</option>
              </select>
              </div>
              <label for="Quantity" align="right" class="col-sm-2 form-control-label">COC No</label>
              <div class="col-sm-5">
                   <input type="text" class="form-control" value="<?php echo $COC_No;?>" name="COCNo" id="value" placeholder="COC No">
              </div>
<!--              <label for="Quantity" class="col-sm-1 form-control-label">Count</label>
              <div class="col-sm-3">
                   <input type="number" class="form-control" name="count" value="<?php // echo $count;?>" id="value" placeholder="Count for Lot">
              </div>-->
              
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