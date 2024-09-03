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
$site=$_POST["site"];
$salesrep=$_POST["salesrep"];
$date1=$_POST["date"];
$lpo=$_POST["lpo"];
$lpo_date1=$_POST["lpo_date"];

$date = date("d/m/Y", strtotime($date1));
$lpo_date = date("d/m/Y", strtotime($lpo_date1));

if($date=='01/01/1970')
{
     $date='';
}
if($lpo_date=='01/01/1970')
{
     $lpo_date='';
}

$order_refrence1=$_POST["order_refrence"];
$order_refrence=sprintf("%06d",$order_refrence1);


$sub=$_POST["invoice_subtotal"];
$vat=$_POST["invoice_vat"];
$grand=$_POST["invoice_total"];
$transport=$_POST["transport"];


$item=$_POST["item"];
if(count(array_unique($item))<count($item))
{
  $status='failed2';
}
else
{

$sql = "INSERT INTO `sales_order` (`customer`, `site`, `salesrep`, `date`, `lpo`, `lpo_date`, `order_referance`, `sub_total`, `vat`, `grand_total`, `transport`) 
VALUES ('$customer', '$site', '$salesrep', '$date', '$lpo', '$lpo_date', '$order_refrence', '$sub', '$vat', '$grand', '$transport')";
if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
    
    $item=$_POST["item"];
    
    $quantity=$_POST["invoice_product_qty"];
    $unit=$_POST["invoice_product_price"];
    $total=$_POST["invoice_product_sub"];
    
    
     $count=sizeof($item);
    for($i=0;$i<$count;$i++)
    {
    $sql1 = "INSERT INTO `order_item` (`item_id`,`o_r`,`item`, `quantity`, `unit`, `total`) 
VALUES ('$last_id','$order_refrence','$item[$i]', '$quantity[$i]', '$unit[$i]', '$total[$i]')";
     $conn->query($sql1);
    }
    
    
       $last_id1 = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="PO".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}}}
?>   

<script>
$(document).on("wheel", "input[type=number]", function (e) {
    $(this).blur();
});
</script>

<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-8">
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
        <?php } else if($status=="failed2") {?>
    	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed! Cannot order twice for same item</span>
    </a></p>
        <?php } ?>
      <div class="box">
        <div class="box-header">
          <h2>Add Old Sales Order</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/old_sales_order" method="post">
               <div class="form-group row">
               <label class="col-sm-2 form-control-label">Sales Order Ref</label>
               <div class="col-sm-6">
                   <input type="text" class="form-control" name="order_refrence" placeholder="Sales Order Ref" required>
               </div>     
               </div>
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-6">
                <select name="customer" id="customer" class="form-control" required>
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
              <label for="type" align="" class="col-sm-2 form-control-label">Customer Site</label>
              <div class="col-sm-6">
                   <select class="form-control" name="site" id="site"></select>
              </div>
              </div>
              
               
               <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Sales Representative</label>
               <div class="col-sm-6">
                 <select name="salesrep" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT salesrep FROM customer_site GROUP BY salesrep";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
                                     $name1=$row["salesrep"];
                                        $sqlcust="SELECT name from customers where id='$name1'";
                                        $querycust=mysqli_query($conn,$sqlcust);
                                        $fetchcust=mysqli_fetch_array($querycust);
                                        $cust=$fetchcust['name'];
				?>
				<option value="<?php echo $row["salesrep"];?>"><?php echo $cust;?></option>
				<?php 
				}} 
				?>
                </select>
              </div></div>
              <div class="form-group row"> 
              <label for="date" align=""  class="col-sm-2 form-control-label">Order Date</label>
              <div class="col-sm-6">
             <?php
              $today = date('d/m/Y');
              ?>
                <input type="date" name="date" value="<?php echo $today;?>" id="date" placeholder="Production Date" class="form-control has-value">
            </div>
              </div> 
               
               
              
            <div class="form-group row">
              <label for="type" class="col-sm-2 form-control-label">LPO No</label>
              <div class="col-sm-6">
                   <input type="text" class="form-control" name="lpo" id="value" placeholder="LPO No">
              </div>
            </div> 
              <div class="form-group row">
              <label for="date"  class="col-sm-2 form-control-label">LPO Date</label>
              <div class="col-sm-6">
             <?php
              $today = date('d/m/Y');
              ?> 
                <input type="date" name="lpo_date" placeholder="LPO Date" value="<?php echo $today;?>" class="form-control has-value">
            </div>
<!--              <label for="type" align="right" class="col-sm-2 form-control-label">Order Refrence</label>
              <div class="col-sm-4">
                  <?php 
//                   $sql = "SELECT * FROM sales_order ORDER BY id DESC LIMIT 1";
//				$sql = "SELECT batch FROM batches_lots where id='$last_id'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                     $value=0;
				while($row = mysqli_fetch_assoc($result)) 
				{ 
   //                                  $value=$row["order_referance"]+1;
				?>
                   <input type="" class="form-control" name="order_refrence" value="<?php echo sprintf("%06d",$value);?>" readonly>
				<?php 
				}}
                               else {
                                    $value='000001';
                                       ?> <input type="" class="form-control" name="order_refrence" value="<?php// echo $value;?>" readonly><?php
                                    }
		  ?>
              </div>-->
             </div> 
             
<head>
  	<!-- JS -->
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="js/moment.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.js"></script>
	<script src="//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
	<script src="js/bootstrap.datetime.js"></script>
	<script src="js/bootstrap.password.js"></script>
	<script src="js/scripts.js"></script>
        
<!--OTHER SETTINFS-->
<?php        
define('INVOICE_PREFIX', 'MD'); // Prefix at start of invoice - leave empty '' for no prefix
define('INVOICE_INITIAL_VALUE', '1'); // Initial invoice order number (start of increment)
define('INVOICE_THEME', '#222222'); // Theme colour, this sets a colour theme for the PDF generate invoice
define('TIMEZONE', 'Asia/Kolkata'); // Timezone - See for list of Timezone's http://php.net/manual/en/function.date-default-timezone-set.php
define('DATE_FORMAT', 'DD/MM/YYYY'); // DD/MM/YYYY or MM/DD/YYYY
define('CURRENCY', 'AED : '); // Currency symbol
define('ENABLE_VAT', true); // Enable TAX/VAT
define('VAT_INCLUDED', false); // Is VAT included or excluded?
define('VAT_RATE', '5'); // This is the percentage value

?>

	<!-- CSS -->
	<!--<link rel="stylesheet" href="bootstrap.min.css">-->
</head>

                         <table class="table table-bordered" id="invoice_table">
				<thead>
					<tr>
						<th width="300">
                                                <h6 style="text-align:center;"><b> Item </b></h6>
						</th>
						<th>
                                                     <h6 style="text-align:center;"><b> Quantity </b></h6>
							<!--<h4>Qty</h4>-->
						</th>
						<th>
                                                     <h6 style="text-align:center;"><b> Price </b></h6>
							
						</th>
						<th>
                                                     <h6 style="text-align:center;"><b> Sub Total </b></h6>
							
						</th>
                                                <th>
                                                <a href="#" class="btn btn-success btn-xs add-row"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
                                                </th>
                                        </tr>
				</thead>
				<tbody>
					<tr>
						<td style="width:40%;">
							<div class="form-group form-group-sm  no-margin-bottom">
								<!--<a href="#" class="btn btn-danger btn-xs delete-row"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>-->
								<!--<input type="text" class="form-control form-group-sm item-input invoice_product" name="invoice_product[]" placeholder="Enter item title and / or description">-->
								
                                                                <select type="text"  class="form-control form-group-sm item-input invoice_product" id="item" name="item[]">
                                                                     <?php
                                                                       $sql="select * from items";
                                                                       $query=mysqli_query($conn,$sql);
                                                                       while($fetch=mysqli_fetch_array($query))
                                                                       {
                                                                       $item=$fetch['items'];
                                                                       $id=$fetch['id'];
                                                                       ?>
                                                                     <option value="<?php echo $id;?>"><?php echo $item;?></option>
                                                                     
                                                                       <?php } ?>
                                                                </select>
                                                                <!--<p class="item-select">or <a href="#">select an item</a></p>-->
							</div>
						</td>
						<td style="width:20%;" class="text-right">
							<div class="form-group form-group-sm no-margin-bottom">
								<input type="number" min="1" step="any" class="form-control invoice_product_qty calculate" name="invoice_product_qty[]" value="1">
							</div>
						</td>
						<td style="width:20%;" class="text-right">
							<div class="input-group input-group-sm  no-margin-bottom">
								<!--<span class="input-group-addon"><?php echo CURRENCY ?></span>-->
								<input type="number" min="1" step="any" class="form-control calculate invoice_product_price required" name="invoice_product_price[]" aria-describedby="sizing-addon1" placeholder="0.00">
							</div>
						</td>
<!--						<td class="text-right">
							<div class="form-group form-group-sm  no-margin-bottom">
								<input type="hidden" class="form-control calculate" name="invoice_product_discount[]" placeholder="Enter % or value (ex: 10% or 10.50)">
							</div>
						</td>-->
                                                <input type="hidden" class="form-control calculate" name="invoice_product_discount[]" placeholder="Enter % or value (ex: 10% or 10.50)">
						<td style="width:20%;" class="text-right">
							<div class="input-group input-group-sm">
								<!--<span class="input-group-addon"><?php echo CURRENCY ?></span>-->
                                                             <input type="text" class="form-control calculate-sub" name="invoice_product_sub[]" id="invoice_product_sub" value="0.00" aria-describedby="sizing-addon1" readonly>
							</div>
						</td>
                                                
                                                <td class="text-right">
							<div class="input-group input-group-sm">
                                                           <a href="#" class="btn btn-danger btn-xs delete-row"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>  
							</div>
						</td>
                                                
					</tr>
				</tbody>
			</table>


                           <div id="invoice_totals" class="padding-right row text-right">
                           <div class="col-xs-4 no-padding-right">
                           </div>
			   </div>			
               

               <div class="row">
               <div style="float:right;">
                    <div class="row">
		     <div class="col-sm-4">
			<strong>Sub Total:</strong>
		     </div>
		     <div class="col-sm-6">
			<?php echo CURRENCY ?><span class="invoice-sub-total">0.00</span>
			<input type="hidden" name="invoice_subtotal" id="invoice_subtotal">
		     </div>
		   </div>
                   
                   <div class="row">
                    <label class="col-sm-4 form-control-label"><b> Transport:</b></label>
                    <div class="col-sm-8">
                         <input style="width:95%;" class="form-control" type="number" name="transport">
                    </div>
                    </div> 
                    
                    
                    <?php if (ENABLE_VAT == true) { ?>
                    <div class="row">
		    <div class="col-sm-4">
		    <strong>TAX/VAT:</strong>
		    </div>
		    <div class="col-sm-6">
		    <?php echo CURRENCY ?><span class="invoice-vat" data-enable-vat="<?php echo ENABLE_VAT ?>" data-vat-rate="<?php echo VAT_RATE ?>" data-vat-method="<?php echo VAT_INCLUDED ?>">0.00</span>
		    <input type="hidden" name="invoice_vat" id="invoice_vat">
		    </div>
		    </div>
                    <div class="row">
                         <div class="col-sm-4">
                          Remove     
                         </div>
                         <div class="col-sm-2">
                           <input type="checkbox" class="remove_vat">   
                         </div>
                    </div>
		    <?php } ?>
                    
                    <div class="row">
                    <label class="col-sm-4 form-control-label"><b> Total:</b></label>
                    <div class="col-sm-8">
                         <input style="width:95%;" class="form-control" type="text" name="invoice_total" id="invoice_total" readonly>
		    </div>
                    </div>
                    
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
<?php $pageissue='issue';?>
<?php include "../includes/footer.php";?>
