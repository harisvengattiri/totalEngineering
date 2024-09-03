<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
  $status=$_POST["status"];
$customer=$_POST["customer"];
$site=$_POST["site"];
$salesrep=$_POST["salesrep"];
$date=$_POST["date"];
$terms=$_POST["terms"];
$attention=$_POST["attention"];
$lpo=$_POST["lpo"];
$lpo_date=$_POST["lpo_date"];
$order_refrence=$_POST["order_refrence"];
$prj=$_POST["prj"];

$sql = "UPDATE `quotation` SET `customer` =  '$customer', `site` =  '$site', `salesrep` =  '$salesrep', `date` =  '$date',`attention` =  '$attention', `terms` =  '$terms', `status` =  '$status' WHERE `id` = $prj";
if ($conn->query($sql) === TRUE) {
     $status="success";
    $item=$_POST["item"];
    $quantity=$_POST["quantity"];
    $unit=$_POST["unit"];
//    $total=$_POST["total"];
    $sql2="DELETE FROM quotation_item WHERE `quotation_id` = $prj";
    $conn->query($sql2);
     $count=sizeof($item);
     $sum=0;
    for($i=0;$i<$count;$i++)
    {
   $item[$i]= mysqli_real_escape_string($conn,$item[$i]);
    $total[$i]=$quantity[$i]*$unit[$i];
    $sql1 = "INSERT INTO `quotation_item` (`quotation_id`,`item`, `quantity`, `price`, `total`) 
VALUES ('$prj','$item[$i]', '$quantity[$i]', '$unit[$i]', '$total[$i]')";
     $conn->query($sql1);
     $sum=$sum+$total[$i];
    }
     $sql3="UPDATE quotation SET subtotal='$sum' where id='$prj'";
    $conn->query($sql3);
    
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="QNO".$prj;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}}


if ($_GET) 
{
$prj=$_GET["id"];
}
	$sql = "SELECT * FROM quotation where id=$prj";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {

              $customer=$row["customer"];
              $site=$row["site"];
              $salesrep=$row["salesrep"];
              $date=$row["date"];
              $lpo=$row["lpo"];
              $lpo_date=$row["lpo_date"];
              $order_referance=$row["order_referance"];
              $terms=$row["terms"];
              $attention=$row["attention"];
              $status=$row["status"];
        }}

?>
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
          <h2>Edit Quotation</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
             
             
          <form role="form" action="<?php echo $baseurl;?>/edit/quotation?id=<?php echo $prj;?>" method="post">
            <div class="form-group row">
               <input name="prj"  type="text" value="<?php echo $prj;?>" hidden="hidden">
              <label for="name" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-4">
                <select name="customer" id="customer" class="form-control">
                  <?php 
				$sql = "SELECT name,id FROM customers where type='Company' order by name";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				$selected = ($row['id'] == $customer) ? "selected='selected'" : '';
				?>
				<option <?php echo $selected;?> value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
              
            </div>
              
               
               <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Sales Representative</label>
              <div class="col-sm-4">
                 <select name="salesrep" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT id,name FROM customers where type='SalesRep'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
                                     $selected = ($row['id'] == $salesrep) ? "selected='selected'" : '';
				?>
				<option <?php echo $selected;?> value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
              </div>
              
             <div class="form-group row">
              <label for="date" align="left"  class="col-sm-2 form-control-label">Quotation Date</label>
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
              <label for="type" class="col-sm-2 form-control-label">Attention</label>
              <div class="col-sm-4">
                   <input class="form-control" type="text" value="<?php echo $attention;?>" name="attention">
                   <!--<select class="form-control" name="site" id="site"></select>-->
              </div>
              </div>
              
              <div class="form-group row">
              <label for="type" class="col-sm-2 form-control-label">Project</label>
              <div class="col-sm-4">
                   <input class="form-control" type="text" name="site" value="<?php echo $site?>"/>
<!--                   <select class="form-control" name="site" id="site">
                        <option value="<?php echo $site?>"><?php echo $site?></option>
                   </select>-->
              </div>
              </div>
               
              <div class="form-group row">
              <label for="type" class="col-sm-2 form-control-label">Status</label>
              <div class="col-sm-4">
                 <select name="status" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                 <option value="<?php echo $status;?>"><?php echo $status;?></option>
                 <option value="Tender">Tender</option>
                 <option value="Job in Hand">Job in Hand</option>
                </select>
              </div>
              </div>  
              
<!--            <div class="form-group row">
              <label for="type" class="col-sm-1 form-control-label">LPO No</label>
              <div class="col-sm-2">
                   <input type="text" class="form-control" name="lpo" value="<?php echo $lpo;?>" id="value" placeholder="LPO No">
              </div>
              <label for="date"  class="col-sm-1 form-control-label">LPO Date</label>
              <div class="col-sm-2">
             <?php
              $today = date('d/m/Y');
              ?>
                <input type="text" name="lpo_date" value="<?php echo $lpo_date;?>" id="date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <label for="type" align="right" class="col-sm-2 form-control-label">Order Refrence</label>
              <div class="col-sm-4">
                   <input type="text" class="form-control" name="order_refrence" value="<?php echo $order_referance;?>" id="value" placeholder="Order Refrence">
              </div>
             </div> -->
             
               <?php 
               $sql="SELECT * FROM quotation_item where quotation_id=$prj ORDER BY id";
               $query=mysqli_query($conn,$sql);
               if (mysqli_num_rows($query) > 0)
               {
                    $count=0;
               while($fetch=mysqli_fetch_array($query))
               {
                    $count++;
                    $item=$fetch['item'];
                    $quantity=$fetch['quantity'];
                    $unit=$fetch['price'];
                    $total=$fetch['total'];
               ?>
               <div class="form-group row" style="padding-top:10px;">
              <label for="name" class="col-sm-1 form-control-label">Item</label>
              <div class="col-sm-3">
                <select name="item[]" class="form-control select2" placeholder="Item" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT items,id FROM items ORDER BY id";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
                                     $selected = ($row['id'] == $item) ? "selected='selected'" : '';
				?>
				<option <?php echo $selected;?> value="<?php echo $row["id"]; ?>"><?php echo $row["items"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
              <div class="col-sm-2">
                <input type="number" min="1" step="any" class="form-control" value="<?php echo $quantity;?>" name="quantity[]" id="estimate" placeholder="Quantity">
              </div>
              <div class="col-sm-2">
                <input type="number" min="1" step="any" class="form-control" value="<?php echo $unit;?>" name="unit[]" id="value" placeholder="Unit Price">
              </div>        
<!--             <div class="col-sm-2">
                <input type="text" class="form-control" value="<?php echo $total;?>" name="total[]" id="value" placeholder="Total Price">
              </div>-->
	<?php 
          if($count==1)
          {  
          ?>
               <div class="box-tools">
                              <a href="javascript:void(0);" 
                                 class="btn btn-info btn-sm" id="btnAddMoreSpecification" data-original-title="Add More">
                                   <i class="fa fa-plus"></i>
                              </a>
                         </div>
          <?php }
          else
               {
          ?>
     <div class="box-tools">
     <a href="javascript:void(0);"  class="btn btn-danger btn-sm btnRemoveMoreSpecification" data-original-title="Add More">
     <i class="fa fa-times"></i>
     </a>
     </div>
              <?php } ?>
     </div>
               <?php }} ?>     
            <div id="divSpecificatiion">

            </div> 

            <div class="form-group row">
              <label for="pterms" class="col-sm-2 form-control-label">Terms & Conditions</label>
              <div class="col-sm-10">
              <textarea name="terms" data-ui-jp="summernote">
              <?php echo $terms;?>
              </textarea>
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
 <script type="text/template" id="temSpecification">
     
     <div class="form-group row" style="padding-top:10px;">
      <label for="name" class="col-sm-1 form-control-label">Item</label>
              <div class="col-sm-3">
                <select name='item[]' class='form-control select2' placeholder='Item' data-ui-jp='select2' data-ui-options='{theme: 'bootstrap'}'>
                  <?php 
				$sql = "SELECT items,id FROM items ORDER BY id";
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
                <input type="number" min="1" step="any" class="form-control" name="quantity[]" id="estimate" placeholder="Quantity">
              </div>
              <div class="col-sm-2">
                <input type="number" min="1" step="any" class="form-control" name="unit[]" id="value" placeholder="Unit Price">
              </div>        
<!--             <div class="col-sm-2">
                <input type="text" class="form-control" name="total[]" id="value" placeholder="Total Price">
              </div>-->
		
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