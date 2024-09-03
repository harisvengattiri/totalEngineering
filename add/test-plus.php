<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$customer=$_POST["customer"];
$site=$_POST["site"];
$salesrep=$_POST["salesrep"];
$date=$_POST["date"];
$lpo=$_POST["lpo"];
$terms=$_POST["terms"];
$attention=$_POST["attention"];
$lpo_date=$_POST["lpo_date"];
$order_refrence=$_POST["order_refrence"];

$sub=$_POST["invoice_subtotal"];
$grand=$_POST["invoice_total"];
$sql = "INSERT INTO `sample_quotation` (`customer`, `site`, `salesrep`, `date`,`attention`,`terms`,`subtotal`,`grand`) 
VALUES ('$customer', '$site', '$salesrep', '$date','$attention','$terms','$sub','$grand')";
if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
    $item=$_POST["item"];
    $quantity=$_POST["invoice_product_qty"];
    $unit=$_POST["invoice_product_price"];
    $total=$_POST["invoice_product_sub"];
    
    $count=sizeof($item);
    $sum=0;
    for($i=0;$i<$count;$i++)
    {
    $item[$i]= mysqli_real_escape_string($conn,$item[$i]);
    $total[$i]=$quantity[$i]*$unit[$i];
    $sql1 = "INSERT INTO `sample_quotation_item` (`quotation_id`,`item`, `quantity`, `price`, `total`) 
VALUES ('$last_id','$item[$i]', '$quantity[$i]', '$unit[$i]', '$total[$i]')";
     $conn->query($sql1);
     $sum=$sum+$total[$i];
    }
    
    $sql2="UPDATE sample_quotation SET subtotal='$sum' where id='$last_id'";
    $conn->query($sql2);
    
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
}
header("Location: goto_quote.php?id=$last_id&or=$order_referance");
}
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
          <h2>Add New Quotation</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/sample-quotation" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-4">
                <select name="customer" id="customer" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                     <!--<select name="items" id="staff" placeholder="Staff" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">-->
                  <?php 
				$sql = "SELECT name FROM customers ";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				?><option value=""> </option><?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["name"]; ?>"><?php echo $row["name"]?></option>
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
				?>
				<option value="<?php echo $row["id"];?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
               
              <label for="date" align="right"  class="col-sm-2 form-control-label">Current Date</label>
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
              <label for="type" class="col-sm-2 form-control-label">Attention</label>
              <div class="col-sm-10">
                   <input class="form-control" type="text" name="attention">
                   <!--<select class="form-control" name="site" id="site"></select>-->
              </div>
              </div>
               
               <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Project</label>
              <div class="col-sm-4">
                   <input class="form-control" type="text" name="site" id="site">
                   <!--<select class="form-control" name="site" id="site"></select>-->
              </div>
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

	<!-- CSS -->
	<!--<link rel="stylesheet" href="bootstrap.min.css">-->

</head>

<table class="table table-bordered" id="invoice_table">
				<thead>
					<tr>
						<th width="300">
                                                <h5><b> Item </b></h5>
						</th>
						<th>
                                                     <h5 align="center"><b> Qty </b></h5>
							<!--<h4>Qty</h4>-->
						</th>
						<th>
                                                     <h5 align="center"><b> Price </b></h5>
							
						</th>
						<th width="200">
                                                     <h5 align="center"><b> Discount </b></h5>
							
						</th>
						<th>
                                                     <h5 align="center"><b> Sub Total </b></h5>
							
						</th>
                                                <th>
                                                <a href="#" class="btn btn-success btn-xs add-row"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
                                                </th>
                                        </tr>
				</thead>
				<tbody>
					<tr>
						<td>
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
                                                                       ?>
                                                                     <option value="124"><?php echo $item;?></option>
                                                                     
                                                                       <?php } ?>
                                                                </select>
                                                                <!--<p class="item-select">or <a href="#">select an item</a></p>-->
							</div>
						</td>
						<td class="text-right">
							<div class="form-group form-group-sm no-margin-bottom">
								<input type="text" class="form-control invoice_product_qty calculate" name="invoice_product_qty[]" value="1">
							</div>
						</td>
						<td class="text-right">
							<div class="input-group input-group-sm  no-margin-bottom">
								<!--<span class="input-group-addon"><?php echo CURRENCY ?></span>-->
								<input type="text" class="form-control calculate invoice_product_price required" name="invoice_product_price[]" aria-describedby="sizing-addon1" placeholder="0.00">
							</div>
						</td>
						<td class="text-right">
							<div class="form-group form-group-sm  no-margin-bottom">
								<input type="text" class="form-control calculate" name="invoice_product_discount[]" placeholder="Enter % or value (ex: 10% or 10.50)">
							</div>
						</td>
						<td class="text-right">
							<div class="input-group input-group-sm">
								<!--<span class="input-group-addon"><?php echo CURRENCY ?></span>-->
								<input type="text" class="form-control calculate-sub" name="invoice_product_sub[]" id="invoice_product_sub" value="0.00" aria-describedby="sizing-addon1" disabled>
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
<!--				<div class="col-xs-6">
					<div class="input-group form-group-sm textarea no-margin-bottom">
						<textarea class-"form-control" name="invoice_notes" placeholder="Please enter any order notes here."></textarea>
					</div>
				</div>-->
                                        <div class="col-xs-4 no-padding-right">
					
                                            
					</div>
					</div>



                                             <div class="row">
                                             <div style="float:right;">
                                             
                                             <div class="row">
                                                  <label class="col-sm-4 form-control-label"><b> Sub Total:</b></label>
						<!--<div class="col-xs-3"><?php echo CURRENCY ?><span class="invoice-sub-total">0.00</span>-->
							<div class="col-sm-8">
                                                        <input class="form-control" type="text" name="invoice_subtotal" id="invoice_subtotal">
                                                        </div>
                                             </div>


                                             <div  class="row">
                                                  <label class="col-sm-4 form-control-label"><b> Discount:</b></label>
						<!--<div class="col-xs-3"><?php echo CURRENCY ?><span class="invoice-discount">0.00</span>-->
                                                  <div class="col-sm-8">
                                                  <input class="form-control" type="text" name="invoice_discount" id="invoice_discount">
                                                  </div>
                                            </div>
                                            <div class="row">
                                                 <label class="col-sm-4 form-control-label"><b> Additional:</b></label>
						<div class="col-sm-8">
							<div class="input-group input-group-sm">
								<!--<span class="input-group-addon"><?php echo CURRENCY ?></span>-->
								<input type="text" class="form-control calculate shipping" name="invoice_shipping" aria-describedby="sizing-addon1" placeholder="0.00">
							</div>
						</div>
                                            </div>
                                                  
                                                  <div class="row">
                                                  <label class="col-sm-10 form-control-label"><b>Add/Remove :</b> <input type="checkbox" class="remove_vat"></label>
                                                  </div>   
                                                  
                                                  
                                                  
                                                <div class="row">
						<label class="col-sm-4 form-control-label"><b> Total:</b></label>
						<!--<div class="col-xs-3"><?php echo CURRENCY ?><span class="invoice-total">0.00</span>-->
                                                <div class="col-sm-8">	
                                                <input class="form-control" type="text" name="invoice_total" id="invoice_total">
						</div>
                                                </div>
                                  </div>            
                                  </div>

<?php popProductsList();?>

                                             <div class="row">   
                                             <div class="form-group row">
                                                        <label for="pterms" class="col-sm-2 form-control-label">Terms & Conditions</label>
                                                        <div class="col-sm-10">
                                                        <textarea name="terms" data-ui-jp="summernote">
                                                        Terms &amp; Conditions&nbsp;
                                                        <ol>
                                                                <li>Price Validity : 5 Days&nbsp;</li>
                                                                <li>Payment Terms : CDC / PDC Subject to Approval&nbsp;</li>
                                                                <li>Delivery Terms : Delivery at Site</li>
                                                                <li>All blocks are DCL APPROVED&nbsp;</li>
                                                                <li>All blocks are 4 hour fire rated &amp; acoustically tested.</li>
                                                                <li>The Units price quoted are does not included the VAT or any other types of taxes &nbsp;imposed by UAE government.</li>
                                                        </ol>

                                                        </textarea>
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


<?php include "../includes/footer.php";?>