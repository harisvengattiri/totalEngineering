<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
$id=$_POST["id"];
$or=$_POST["or"];
$lpo=$_POST["lpo"];
$date=$_POST["date"];
$customer=$_POST["customer"];
$site=$_POST["site"];

$subtotal=$_POST["subtotal"];
$vat=$_POST["vat"];
$grand=$_POST["grand"];

$sql = "UPDATE invoice SET order_referance = '$or', customer = '$customer', site = '$site', date = '$date', total = '$subtotal', vat = '$vat', grand = '$grand' where id='$id'";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $item=$_POST["item"];
       $dn=$_POST["dn"];
       $reqquantity=$_POST["reqquantity"];
       $pdate=$_POST["pdate"];
       $unit=$_POST["unit"];
       $total=$_POST["total"];
       $sql2="DELETE FROM invoice_item WHERE invoice_id ='$id'";
       $conn->query($sql2);
       $n=sizeof($item);
       for($i=0;$i<$n;$i++)
       {
       $item[$i] = mysqli_real_escape_string($conn,$item[$i]);
       $sql1 = "INSERT INTO `invoice_item` (`invoice_id`, `dn`, `or`, `lpo`, `item`, `pdate`, `quantity`, `unit`,`total`) 
VALUES ('$id', '$dn[$i]', '$or[$i]', '$lpo[$i]', '$item[$i]', '$pdate[$i]', '$reqquantity[$i]', '$unit[$i]', '$total[$i]')";
$conn->query($sql1);
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
}}}

if($_GET['id'])
{
     $id=$_GET['id'];
}

$sql="SELECT * FROM invoice where id='$id'";
$query=mysqli_query($conn,$sql);
$fetch=mysqli_fetch_array($query);
//$id=$fetch['id'];
$customer=$fetch['customer'];
$site=$fetch['site'];
$or=$fetch['order_referance'];
$date=$fetch['date'];
$subtotal=$fetch['total'];
$vat=$fetch['vat'];
$grand=$fetch['grand'];

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
          <h2>Add New Invoice</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/edit/invoice" method="post">
               <input name="id" value="<?php echo $id;?>" hidden="hidden">
               <div class="form-group row">
              <label for="customer" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-4">
		<select name="customer" id="customer" class="form-control">
                  <?php 
				$sql = "SELECT name FROM customers where type='Company' order by name";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                ?> <option value="<?php echo $customer;?>"><?php echo $customer;?></option> <?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["name"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
                </select>		
              </div>
              <label for="startd" align="right" class="col-sm-2 form-control-label">Customer Site</label>
              <div class="col-sm-4">
                   <select class="form-control" name="site" id="site">
                        <option value="<?php echo $site;?>"><?php echo $site;?></option> 
                   </select>
              </div>
            </div>
            
               
              <div class="form-group row">
                   
                   <label for="date" class="col-sm-2 form-control-label">Current Date</label>
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
              <br> 
               
              
              <?php
                 $sql="SELECT * from invoice_item where invoice_id='$id'";
                 $result = mysqli_query($conn, $sql);
                 if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
                                     $item1=$row['item'];
                                     $item = mysqli_real_escape_string($conn,$row['item']);
                                     $quantityreq=$row['quantity'];
                                     $dn=$row['dn'];
                                     $or=$row['or'];
                                     $lpo=$row['lpo'];
                                     $unit=$row['unit'];
                                     $pdate=$row['pdate'];
                                     $total=$row['total'];
                                    
                                    ?>
              <div class="form-group row">
                   
              <label for="endd" class="col-sm-2 form-control-label">Delivery Note Referance</label>
              <div class="col-sm-2">
                   <input type="text" class="form-control" name="dn[]" value="<?php echo $dn;?>" id="endd" readonly>
              </div>
              <label for="endd" class="col-sm-2 form-control-label">Sales Order Referance</label>
              <div class="col-sm-2">
                   <input type="text" class="form-control" name="or[]" value="<?php echo $or;?>" id="endd" readonly>
              </div>
              <label for="endd" class="col-sm-1 form-control-label">LPO</label>
              <div class="col-sm-2">
                   <input type="text" class="form-control" name="lpo[]" value="<?php echo $lpo;?>" id="endd" readonly>
              </div>
              
              </div> 
        
               <div class="form-group row">
              
              <div class="col-sm-2">
                   <input type="text" class="form-control" name="item[]" value="<?php echo $item1;?>" id="endd" placeholder="Item">
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">Date</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="pdate[]" value="<?php echo $pdate;?>" id="endd">
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">Quantity</label>
              <div class="col-sm-1">
                <input type="text" class="form-control" name="reqquantity[]" value="<?php echo $quantityreq;?>" id="endd" placeholder="Quantity" readonly>
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">Unit Price</label>
              <div class="col-sm-1">
                <input type="text" class="form-control" name="unit[]" value="<?php echo $unit;?>" id="endd" placeholder="Quantity" readonly>
              </div>
               <div class="col-sm-2">
                    <input type="text" class="form-control" name="total[]" value="<?php echo $total;?>" id="endd" placeholder="Quantity" readonly>
              </div>
                
               </div>
             
                                    <?php
                                } }
                                    ?>
<!--                                <br>
                                
                                <div class="form-group row">
                                 <label for="endd" align="right" class="col-sm-8 form-control-label">Sub total</label>
                                   <div class="col-sm-3">
                                    <input type="text" class="form-control" name="subtotal" value="<?php echo $subtotal;?>" id="endd" placeholder="Quantity" readonly>
                                    </div>           
                                </div>
                                <div class="form-group row">
                                 <label for="endd" align="right" class="col-sm-8 form-control-label">VAT Tax [5%]</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="vat" value="<?php echo $vat;?>" id="endd" placeholder="Quantity" readonly>
              </div>           
                                </div>
                                <div class="form-group row">
                                 <label for="endd" align="right" class="col-sm-8 form-control-label">Grand total</label>
              <div class="col-sm-3">
                   <input type="text" class="form-control" name="grand" value="<?php echo $grand;?>" id="endd" placeholder="Quantity" readonly>
              </div>           
                                </div>-->
            
               
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