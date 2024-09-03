<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<?php $pageissue = 'issue';?>
<?php error_reporting(0); ?>

<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
    $username=$_SESSION['username'];
    
$customer=$_POST["customer"];
$site=$_POST["site"];
$qtn=$_POST["qtn"];
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

$transport=$_POST["transport"];
$sub=$_POST["invoice_subtotal"];
$vat=$_POST["invoice_vat"];
$grand=$_POST["invoice_total"];


$item=$_POST["item"];
if(count(array_unique($item)) < count($item))
{
  $status='failed2';
}
else
{
   
    $image = $_FILES["image"]["name"];
    if($image!=NULL)
    {
    $ext = strtolower(pathinfo($image,PATHINFO_EXTENSION));
        $image1 = 'lpo-'.uniqid().'.'.$ext;
        $target_dir1 = "../uploads/lpo/";
        $target_file1 = $target_dir1 . $image1;
        $imageFileType1 = strtolower(pathinfo($target_file1,PATHINFO_EXTENSION));
        $allowlist1 = array("jpg","jpeg","png","pdf");
         if (in_array($imageFileType1, $allowlist1))
         {
          if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file1)) 
          {
          } else { $image1=NULL;}
         }
         $lpo_image=$image1;
    }
    
$token = $_POST["token"];    

$sql = "INSERT INTO `sales_order` (`token`, `customer`, `site`, `salesrep`, `qtn`, `date`, `lpo`, `lpo_date`, `lpo_pdf`, `order_referance`, `sub_total`, `vat`, `grand_total`, `transport`, `prep`) 
VALUES ('$token', '$customer', '$site', '$salesrep', '$qtn', '$date', '$lpo', '$lpo_date', '$lpo_image', '$order_refrence', '$sub', '$vat', '$grand', '$transport', '$username')";
if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
    
    $item=$_POST["item"];
    $comment=$_POST["comment"];
    
    $quantity=$_POST["invoice_product_qty"];
    $unit=$_POST["invoice_product_price"];
    $total=$_POST["invoice_product_sub"];
    
    $count=sizeof($item);
    for($i=0;$i<$count;$i++)
    {
    $sql1 = "INSERT INTO `order_item` (`item_id`,`o_r`,`item`,`comment`,`quantity`, `unit`, `total`) 
VALUES ('$last_id','$order_refrence','$item[$i]','$comment[$i]', '$quantity[$i]', '$unit[$i]', '$total[$i]')";
     $conn->query($sql1);
    }
    
    $sql2 = "UPDATE `quotation` SET `flag`='1' WHERE id='$qtn'";
    $conn->query($sql2);
    
      $last_id1 = $conn->insert_id;
      $date1=date("d/m/Y h:i:s a");
      $username=$_SESSION['username'];
      $code="PO".$last_id;
      $query=mysqli_real_escape_string($conn, $sql);
      $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
      $result = mysqli_query($conn, $sql);
       
      echo "<script type=\"text/javascript\">".
             "window.location='".$baseurl."/sales_order_new';". 
             "</script>";
       
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
          <h2>Add New Sales Order</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
            
            
          <form role="form" action="<?php echo $baseurl;?>/add/new_sales_order_qtn" method="post" enctype="multipart/form-data">
            <div class="form-group row">
            <input type="hidden" name="token" value="<?php echo rand(1000,9999).date('Ymdhisa');?>">
              <label for="name" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-6">
                <select name="customer" id="customer" class="form-control select2" Required="Required" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                <?php 
                
                        $customer=$_POST['customer'];
                        $site=$_POST['site'];
                        $qtn=$_POST['qtn'];
                        
                        if($_GET){
                        $qtn = $_GET['qtn'];
                        $customer = $_GET['cst'];
                        $site = $_GET['st'];
                        }
                        
                        $sqlcust="SELECT name from customers where id='$customer'";
                        $querycust=mysqli_query($conn,$sqlcust);
                        $fetchcust=mysqli_fetch_array($querycust);
                        $cust=$fetchcust['name'];
                        
                        $sqlsite="SELECT p_name from customer_site where id='$site'";
                        $querysite=mysqli_query($conn,$sqlsite);
                        $fetchsite=mysqli_fetch_array($querysite);
                        $site_name=$fetchsite['p_name'];
                
                
				$sql = "SELECT name,id FROM customers where type='Company' AND status != 'banned' order by name";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				    if($customer == NULL){
				    ?> <option value="">Select Customer</option> <?php        
				    } else {
				    ?> <option value="<?php echo $customer;?>"><?php echo $cust;?></option> <?php }
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
                   <select class="form-control" name="site" id="site">
                       
                       <?php if($qtn != NULL && $site == NULL) { ?>
                            <option value="">Select Site</option>
                       <?php
                            $sql_st = "SELECT * FROM customer_site where customer='$customer'";
                            $query_st = mysqli_query($conn,$sql_st);
                            while($fetch_st = mysqli_fetch_array($query_st)){
                       ?>
                            <option value="<?php echo $fetch_st['id'];?>"><?php echo $fetch_st['p_name'];?></option>
                       <?php } } ?>
                       
                       
                       
                       <?php if($site != NULL) { ?>
                        <option value="<?php echo $site;?>"><?php echo $site_name;?></option>
                       <?php } ?>
                       
                   </select>
              </div>
              </div>
              
              <div class="form-group row">
              <label for="type" align="" class="col-sm-2 form-control-label">Quotation</label>
              <div class="col-sm-6">
                   <select class="form-control" name="qtn" id="qtn">
                       <?php if($qtn != NULL) { ?>
                        <option value="<?php echo $qtn;?>"><?php echo $qtn;?></option>
                       <?php } ?>
                   </select>
              </div>
              </div>
              
                <div class="form-group row m-t-md">
                  <div align="left" class="col-sm-offset-2 col-sm-12">
                    <a href="<?php echo $baseurl;?>/sales_order_new" class="btn btn-sm btn-outline rounded b-danger text-danger">Cancel</a>
                    <button name="submit_first" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Generate</button>
                  </div>
                </div>
            </form>
            
            <?php
                if(isset($_POST['submit_first']))
                {
                $customer=$_POST['customer'];
                $site=$_POST['site'];
                $qtn=$_POST['qtn'];
                $token=$_POST['token'];
            ?>
              
            <form role="form" action="<?php echo $baseurl;?>/add/new_sales_order_qtn" method="post" enctype="multipart/form-data">
                <input type="hidden" name="customer" value="<?php echo $customer;?>">
                <input type="hidden" name="site" value="<?php echo $site;?>">
                <input type="hidden" name="qtn" value="<?php echo $qtn;?>">
                <input type="hidden" name="token" value="<?php echo $token;?>">
                 
               <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label" >Sales Representative</label>
               <div class="col-sm-6">
                   
                <!--<select name="salesrep" class="form-control select2-multiple" Required="Required" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">-->
                <?php
                // $sql = "SELECT name,id FROM customers where type='Salesrep' order by name";
				// $result = mysqli_query($conn, $sql);
				// if (mysqli_num_rows($result) > 0) 
				// {
				?>
				<!--<option value=""></option>-->
				<?php
				// while($row = mysqli_fetch_assoc($result)) 
				// {
				?>
				<!--<option value="<?php // echo $row["id"]; ?>"><?php // echo $row["name"]?></option>-->
				<?php 
				// }} 
				?>
                <!--</select>-->
                
                <?php
                    $sql_rep = "SELECT slmn FROM customers where id='$customer'";
				    $result_rep = mysqli_query($conn, $sql_rep);
				    $fetch_rep = mysqli_fetch_array($result_rep);
				    $rep_id = $fetch_rep['slmn'];
				    
				    $sql_rep_name = "SELECT name FROM customers where id='$rep_id'";
				    $result_rep_name = mysqli_query($conn, $sql_rep_name);
				    $fetch_rep_name = mysqli_fetch_array($result_rep_name);
				    $rep_name = $fetch_rep_name['name'];
                ?>
                
                <input type="text" class="form-control" value="<?php echo $rep_name;?>" required readonly>
                <input type="hidden" name="salesrep" value="<?php echo $rep_id;?>">
                
                
                
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
                                      
             </div>
             
            <div class="form-group row">
              <label for="pterms" class="col-sm-2 form-control-label">Choose LPO PDF</label>
              <div class="col-sm-4">
              <input type="file" class="form-control" name="image" accept="image/jpg,image/jpeg,application/pdf">
              </div>
            </div>
               
               <div class="form-group row">
               <label class="col-sm-2 form-control-label">Sales Order Ref</label>
               <div class="col-sm-6">
                   <?php 
                                $sql = "SELECT order_referance FROM sales_order ORDER BY order_referance DESC LIMIT 1";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                $value=0;
				while($row = mysqli_fetch_assoc($result)) 
				{ 
                                $value=$row["order_referance"]+1;
				?>
                   <input type="" class="form-control" name="order_refrence" value="<?php echo sprintf("%06d",$value);?>" readonly>
				<?php 
				}}
                               else {
                                    $value='000001';
                                       ?> <input type="" class="form-control" name="order_refrence" value="<?php echo $value;?>" readonly><?php
                                    }
		  ?>
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
                                                <h6 style="text-align:center;"><b> Item </b></h6>
						</th>
                                                <th>
                                                <h6 style="text-align:center;"><b> Comment </b></h6>
						</th>
						<th>
                                                     <h6 style="text-align:center;"><b> Quantity </b></h6>
							<!--<h4>Qty</h4>-->
						</th>
						<th>
                                                     <h6 style="text-align:center;"><b> Price </b></h6>
							
						</th>
						<th>
                                                     <h6 style="text-align:center;"><b> Bundle </b></h6>
							
						</th>
						<th>
                                                     <h6 style="text-align:center;"><b> Sub Total </b></h6>
							
						</th>
						<!--CHANGES BY DEVELOPER HARI STARTS-->
                        <!--<th>-->
                        <!--<a href="#" class="btn btn-success btn-xs add-row"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>-->
                        <!--</th>-->
                        <!--CHANGES BY DEVELOPER HARI ENDS-->
                        </tr>
				</thead>
				
			<tbody>                
               <?php
               $sql_qtn = "SELECT trans FROM `quotation` WHERE id='$qtn'";
               $query_qtn = mysqli_query($conn,$sql_qtn);
               $fetch_qtn = mysqli_fetch_array($query_qtn);
               $trans = $fetch_qtn['trans'];
               $trans = ($trans != NULL) ? $trans : 0;
               
               $sql="SELECT * FROM quotation_item where quotation_id=$qtn";
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
                    // $comment=$fetch['comment'];
                    
                    $sqlcust="SELECT items,bundle from items where id='$item'";
                    $querycust=mysqli_query($conn,$sqlcust);
                    $fetchcust=mysqli_fetch_array($querycust);
                    $item1=$fetchcust['items'];
                    $bundle=$fetchcust['bundle'];
                    $bundle = ($bundle != NULL) ? $bundle : 1;
                    
                    $quantity=$fetch['quantity'];
                    $quantity = ($quantity != NULL) ? $quantity : 0;
                    $unit=$fetch['price'];
                    $unit = ($unit != NULL) ? $unit : 0;
                    $total=$fetch['total'];
                    $total = ($total != NULL) ? $total : 0;
                    
                        $bndle = $quantity/$bundle;
                        $bndl=round($bndle,2);
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
                        <td style="width:15%;">
							<div class="form-group form-group-sm  no-margin-bottom">
              <input type="text" class="form-control invoice_product_qty calculate" name="comment[]" value="<?php // echo $comment;?>">
							</div>
						</td>
                                                
						<td style="width:15%;" class="text-right">
							<div class="form-group form-group-sm no-margin-bottom">
								<input readonly type="number" min="1" step="any" class="form-control invoice_product_qty calculate" name="invoice_product_qty[]" value="<?php echo $quantity;?>">
							</div>
						</td>
						<td style="width:15%;" class="text-right">
							<div class="input-group input-group-sm  no-margin-bottom">
								<!--<span class="input-group-addon"><?php echo CURRENCY ?></span>-->
                                <input readonly type="number" min="1" step="any" class="form-control calculate invoice_product_price required" name="invoice_product_price[]" value="<?php echo $unit;?>" aria-describedby="sizing-addon1" placeholder="0.00">
							</div>
						</td>
						
						<td style="width:15%;" class="text-right">
							<div class="input-group input-group-sm  no-margin-bottom">
                                <input readonly type="number" min="1" step="any" class="form-control" name="bundle[]" value="<?php echo $bndl;?>" aria-describedby="sizing-addon1" placeholder="0.00">
							</div>
						</td>
						
<!--						<td class="text-right">
							<div class="form-group form-group-sm  no-margin-bottom">
								<input type="hidden" class="form-control calculate" name="invoice_product_discount[]" placeholder="Enter % or value (ex: 10% or 10.50)">
							</div>
						</td>-->
                        <input type="hidden" class="form-control calculate" name="invoice_product_discount[]" placeholder="Enter % or value (ex: 10% or 10.50)">
						<td style="width:10%;" class="text-right">
							<div class="input-group input-group-sm">
								<!--<span class="input-group-addon"><?php echo CURRENCY ?></span>-->
                                <input type="text" class="form-control calculate-sub" name="invoice_product_sub[]" id="invoice_product_sub" value="<?php echo $total;?>" aria-describedby="sizing-addon1" readonly>
							</div>
						</td>
                            
                        <!--CHANGES BY DEVELOPER HARI STARTS-->                        
                        <!-- <td class="text-right">-->
						<!--<div class="input-group input-group-sm">-->
                        <!--  <a href="#" class="btn btn-danger btn-xs delete-row"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>  -->
						<!--</div>-->
						<!--</td>-->
                        <!--CHANGES BY DEVELOPER HARI ENDS--> 
                                                
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
              <input style="width:95%;" class="form-control" type="number" step=".01" name="transport" value="<?php echo $trans;?>">
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
                <a href="<?php echo $baseurl;?>/sales_order_new" class="btn btn-sm btn-outline rounded b-danger text-danger">Cancel</a>
                <button type="reset" class="btn btn-sm btn-outline rounded b-warning text-warning">Clear</button>
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>
              </div>
            </div>
          </form>
          <?php } ?>
          
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