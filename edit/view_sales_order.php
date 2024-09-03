<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<?php error_reporting(0); ?>
<?php $pageissue='issue';?>
<div class="app-body">
<?php
$status="NULL";

if ($_GET) 
{
$prj=$_GET["id"];
}
	$sql = "SELECT * FROM sales_order where id=$prj";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {

            $customer=$row["customer"];
              
            $sql_cust = "SELECT name FROM customers where id='$customer'";
			$result_cust = mysqli_query($conn, $sql_cust);
			$row_cust = mysqli_fetch_assoc($result_cust);
			$customer_name = $row_cust['name'];
			
              $site=$row["site"];
               $sqlsite="SELECT p_name from customer_site where id='$site'";
               $querysite=mysqli_query($conn,$sqlsite);
               $fetchsite=mysqli_fetch_array($querysite);
               $site1=$fetchsite['p_name'];
              
              $salesrep=$row["salesrep"];
              $date=$row["date"];
              $lpo=$row["lpo"];
              $lpo_date=$row["lpo_date"];
              $order_referance=$row["order_referance"];
              
              $transport=$row["transport"];
              $transport = ($transport != NULL) ? $transport : 0;
              $lpo_pdf = $row["lpo_pdf"];
        }}

?>
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-10">
        
      <div class="box">
        <div class="box-header">
          <h2>View Sales Order</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
             
             
          <form role="form" action="<?php echo $baseurl;?>/edit/po_approval" method="post" enctype="multipart/form-data">
            <div class="form-group row">
               <input name="prj"  type="text" value="<?php echo $prj;?>" hidden="hidden">
              <label for="name" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" value="<?php echo $customer_name?>" readonly>
                <input type="hidden" name="customer" value="<?php echo $customer?>">
              </div>
              </div>
            <div class="form-group row">
              <label for="type" align="left" class="col-sm-2 form-control-label">Customer Site</label>
              <div class="col-sm-6">
                  <input type="text" class="form-control" value="<?php echo $site1?>" readonly>
                  <input type="hidden" name="site" value="<?php echo $site?>">
              </div>
            </div>
              
               
               <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Sales Representative</label>
              <div class="col-sm-6">
                 <select name="salesrep" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT name,id FROM customers where type='SalesRep'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				if($salesrep == '') { ?><option value = ""></option> <?php }
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
              <label for="date" align="left"  class="col-sm-2 form-control-label">Current Date</label>
              <div class="col-sm-4">
                <input type="date" name="date" id="date" placeholder="Production Date" class="form-control has-value">
            </div>
              <div class="col-sm-2"><?php echo $date;?></div>
              </div> 
               
               
              
            <div class="form-group row">
              <label for="type" class="col-sm-2 form-control-label">LPO No</label>
              <div class="col-sm-6">
                   <input type="text" class="form-control" name="lpo" value="<?php echo $lpo;?>" id="value" placeholder="LPO No">
              </div>
            </div>
            <div class="form-group row">
              <label for="date"  class="col-sm-2 form-control-label">LPO Date</label>
             <div class="col-sm-4">
                <input type="date" name="lpo_date" placeholder="LPO Date" class="form-control has-value">
             </div>
              <div class="col-sm-2"><?php echo $lpo_date;?></div>
              <input type="hidden" name="prev_date" value="<?php echo $date;?>">
              <input type="hidden" name="prev_lpo_date" value="<?php echo $lpo_date;?>">
            </div>
            
            <div class="form-group row">
              <label for="pterms" class="col-sm-2 form-control-label">Choose LPO PDF</label>
              <div class="col-sm-4">
              <input type="file" class="form-control" name="image" accept="image/jpg,image/jpeg,application/pdf">
              <input type="hidden" name="pre_lpo_pdf" value="<?php echo $lpo_pdf;?>">
              </div>
              <label class="col-sm-2 form-control-label">LPO PDF</label>
              <div class="col-sm-4">
                  <?php if(!empty($lpo_pdf)) { ?>
                  <a target="_blank" style="color:green;" href="<?php echo $baseurl;?>/uploads/lpo/<?php echo $lpo_pdf;?>">View</a>&nbsp;
                  <a style="color:red;" href="<?php echo $baseurl;?>/delete/lpo_pdf?id=<?php echo $prj;?>">Delete</a>
                  <?php } else { ?>
                    <a style="color:red;">No PDF for LPO is Available</a>
                  <?php } ?>
              </div>
            </div>
            
            <div class="form-group row">   
              <label for="type" align="left" class="col-sm-2 form-control-label">Order Refrence</label>
              <div class="col-sm-6">
                   <input type="text" class="form-control" name="order_refrence" value="<?php echo $order_referance;?>" id="value" placeholder="Order Refrence" readonly required>
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
                                                <h5><b> Item </b></h5>
						</th>
                                                <th>
                                                <h5><b> Comment </b></h5>
						</th>
						<th>
                                                     <h5 align="center"><b> Qty </b></h5>
							<!--<h4>Qty</h4>-->
						</th>
						<th>
                                                     <h5 align="center"><b> Price </b></h5>
							
						</th>
						<th>
                                                     <h5 align="center"><b> Sub Total </b></h5>
							
						</th>
						
                        <!--<th>-->
                        <!--<a href="#" class="btn btn-success btn-xs add-row"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>-->
                        <!--</th>-->
                        
                    </tr>
				 </thead>
                <tbody>                
               <?php 
               $sql="SELECT * FROM order_item where item_id=$prj";
               $query=mysqli_query($conn,$sql);
               if (mysqli_num_rows($query) > 0)
               {
               ?>
                 
                    
               <?php     
               $count=0;
               while($fetch=mysqli_fetch_array($query))
               {
                    $count++;
                    $item=$fetch['item'];
                    $comment=$fetch['comment'];
                    
                    $sqlcust="SELECT items from items where id='$item'";
                    $querycust=mysqli_query($conn,$sqlcust);
                    $fetchcust=mysqli_fetch_array($querycust);
                    $item1=$fetchcust['items'];
                    
                    $quantity=$fetch['quantity'];
                    $quantity = ($quantity != NULL) ? $quantity : 0;
                    $unit=$fetch['unit'];
                    $unit = ($unit != NULL) ? $unit : 0;
                    $total=$fetch['total'];
                    $total = ($total != NULL) ? $total : 0;
               ?>
                       
					<tr>
						<td style="width:30%;">
							<div class="form-group form-group-sm  no-margin-bottom">
								<!--<a href="#" class="btn btn-danger btn-xs delete-row"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>-->
								<!--<input type="text" class="form-control form-group-sm item-input invoice_product" name="invoice_product[]" placeholder="Enter item title and / or description">-->
								
                                                                <select type="text"  class="form-control form-group-sm item-input invoice_product" id="item" name="item[]">
                                                                <option value="<?php echo $item;?>"><?php echo $item1;?></option>   
                                                                       <?php 
                                                                       $sql = "SELECT items,id FROM items";
                                                                           $result = mysqli_query($conn, $sql);
                                                                           if (mysqli_num_rows($result) > 0) 
                                                                           {
                                                                           while($row = mysqli_fetch_assoc($result)) 
                                                                           {
                                                                                $items=$row['items'];
                                                                                $id=$row['id'];
                                                                       ?>
                                                                     <option value="<?php echo $id;?>"><?php echo $items;?></option>
                                                                     
                                                                      <?php } } ?>   
                                                                </select>
                                                             
                                                                <!--<p class="item-select">or <a href="#">select an item</a></p>-->
							</div>
						</td>
                                                <td style="width:20%;">
							<div class="form-group form-group-sm  no-margin-bottom">
                                                             <input type="text" class="form-control invoice_product_qty calculate" name="comment[]" value="<?php echo $comment;?>" readonly>
							</div>
						</td>
                                                
						<td style="width:15%;" class="text-right">
							<div class="form-group form-group-sm no-margin-bottom">
								<input type="number" min="1" step="any" class="form-control invoice_product_qty calculate" name="invoice_product_qty[]" value="<?php echo $quantity;?>" readonly>
							</div>
						</td>
						<td style="width:15%;" class="text-right">
							<div class="input-group input-group-sm  no-margin-bottom">
								<!--<span class="input-group-addon"><?php echo CURRENCY ?></span>-->
                                                             <input type="number" min="1" step="any" class="form-control calculate invoice_product_price required" name="invoice_product_price[]" value="<?php echo $unit;?>" aria-describedby="sizing-addon1" placeholder="0.00" readonly>
							</div>
						</td>
<!--						<td class="text-right">
							<div class="form-group form-group-sm  no-margin-bottom">
								<input type="hidden" class="form-control calculate" name="invoice_product_discount[]" placeholder="Enter % or value (ex: 10% or 10.50)">
							</div>
						</td>-->
                                                <input type="hidden" class="form-control calculate" name="invoice_product_discount[]" placeholder="Enter % or value (ex: 10% or 10.50)">
						<td style="width:15%;" class="text-right">
							<div class="input-group input-group-sm">
								<!--<span class="input-group-addon"><?php echo CURRENCY ?></span>-->
                                                             <input type="text" class="form-control calculate-sub" name="invoice_product_sub[]" id="invoice_product_sub" value="<?php echo $total;?>" aria-describedby="sizing-addon1" readonly>
							</div>
						</td>
                                                
      <!--                  <td class="text-right">-->
						<!--	<div class="input-group input-group-sm">-->
      <!--                          <a href="#" class="btn btn-danger btn-xs delete-row"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>  -->
						<!--	</div>-->
						<!--</td>-->
                                                
					</tr>
			

               <?php } } ?>
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
                         <input style="width:95%;" class="form-control" type="number" step=".01" value="<?php echo $transport;?>" name="transport" readonly>
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
                <a href="<?php echo $baseurl;?>/un_po" class="btn btn-sm btn-outline rounded b-danger text-danger">Cancel</a>
                <button name="submit_appr" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Approve</button>
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