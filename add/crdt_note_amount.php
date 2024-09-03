<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<?php // error_reporting(0); ?>

<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
$customer=$_POST["customer"];
$invoice=$_POST["invoice"];
$date=$_POST["date"];
$amt=$_POST["amt"];

$summery = $_POST["summery"];
$description = $_POST["description"];

$vat=$amt*.05;
$vat = number_format($vat, 2, '.', '');
$total=$amt + $vat;
$total = ($total != NULL) ? $total : 0;
$total = number_format($total, 2, '.', '');


date_default_timezone_set('Asia/Dubai');
$time = date('Y-m-d H:i:s', time());
  
  
$sql = "INSERT INTO `credit_note` (`customer`, `date`, `invoice`, `type`, `cat`, `sub`, `amount`, `vat`, `total`, `summery`, `description`, `time`) 
VALUES ('$customer', '$date', '$invoice', '3', '40', '175', '$amt', '$vat', '$total', '$summery', '$description', '$time')";

if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
    
    $sql_adtnl_vat = "INSERT INTO `additionalAcc`(`id`, `section`, `entry_id`, `date`, `cat`, `sub`, `amount`)
                      VALUES ('','CNT','$last_id','$date','39','176','$vat')";
    $conn->query($sql_adtnl_vat);
    
        $sql_inv_date = "SELECT `date` FROM `invoice` WHERE `id`='$invoice'";
        $query_inv_date = mysqli_query($conn,$sql_inv_date);
        $result_inv_date = mysqli_fetch_array($query_inv_date);
        $invoice_date = $result_inv_date['date'];
    
    $sql_adtnl_inv = "INSERT INTO `additionalRcp`(`id`, `section`, `entry_id`, `invoice`, `invoice_date`, `date`, `cat`, `sub`, `amount`)
                      VALUES ('','CNT','$last_id','$invoice','$invoice_date','$date','65','','-$total')";
    $conn->query($sql_adtnl_inv);
    
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="CNT".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       
       
            $sql10 = "SELECT grand FROM `invoice` WHERE `id` = '$invoice'";
            $query10 = mysqli_query($conn,$sql10);
            $result10 = mysqli_fetch_array($query10);
            $grand = $result10['grand'];
            $grand = ($grand != NULL) ? $grand : 0;
       
            $sql11 = "SELECT sum(total) as total FROM `reciept_invoice` WHERE `invoice`='$invoice'";
            $query11 = mysqli_query($conn,$sql11);
            $result11 = mysqli_fetch_array($query11);
            $total_rcp = $result11['total'];
            $total_rcp = ($total_rcp != NULL) ? $total_rcp : 0;

            $total_amt = $total_rcp + $total;
            
            if($total_amt >= $grand){$pay_status='Paid';}
            if($total_amt < $grand){$pay_status='Partially';}
            if($total_amt == NULL){$pay_status='';}
            
            $sql12 = "UPDATE `invoice` SET `status` = '$pay_status' WHERE `id`='$invoice'";
            $query12 = mysqli_query($conn,$sql12);
}
else 
    {
    $status="failed";
    }

}}
?>   
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-9">
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
      <?php } ?>
    
      <div class="box">
        <div class="box-header">
          <h2>Generate Credit Note</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/crdt_note_amount" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-6">
                <select name="customer" id="icustomer" required class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
                              $customer=$_POST['customer'];
                              $invoice=$_POST['invoice'];
                              
                              $sqlcust="SELECT name from customers where id='$customer'";
                              $querycust=mysqli_query($conn,$sqlcust);
                              $fetchcust=mysqli_fetch_array($querycust);
                              $cust=$fetchcust['name'];
                              
                                $sql="SELECT customer AS c,name FROM invoice JOIN customers ON invoice.customer = customers.id
                                             GROUP BY invoice.customer ORDER BY customers.name ";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				    if($customer == NULL){
				?> <option value="">Select Customer</option> <?php        
				} else {
				?> <option value="<?php echo $customer;?>"><?php echo $cust;?></option> <?php }
				while($row = mysqli_fetch_assoc($result)) 
				{
                $cst=$row["c"];
                $cust=$row["name"];
				?>
				<option value="<?php echo $cst; ?>"><?php echo $cust;?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
            </div>
               
            <div class="form-group row">
              <label for="type" align="left" class="col-sm-2 form-control-label">Invoice</label>
              <div class="col-sm-6">
                   <select name="invoice" id="invoice" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                       <?php if($invoice == NULL){ ?>
                        <option value="">Proceed with out invoice</option>
                        <?php } else { ?>
                        <option value="<?php  echo $invoice;?>">INV|<?php echo $invoice;?></option>
                        <?php } ?>
                   </select>
              </div>
            </div>
             
              <div class="form-group row m-t-md">
              <div align="" class="col-sm-offset-2 col-sm-12">
                <button name="submit1" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search Items</button>
              </div>
              </div>
         </form>
            
             
            <?php if(isset($_POST['submit1']))
               {
               $customer=$_POST['customer'];
               $invoice=$_POST['invoice'];
              //  $date=$_POST['date'];
            ?>
            <form role="form" action="<?php echo $baseurl;?>/add/crdt_note_amount" method="post">
                 <input type="hidden" name="customer" value="<?php echo $customer;?>">
                 <input type="hidden" name="invoice"  value="<?php echo $invoice;?>">
              
            <div class="form-group row">
               <label for="date" class="col-sm-2 form-control-label">Date</label>
               <div class="col-sm-6">
                   <input type="text" name="date" value="" id="date" class="form-control has-value" required data-ui-jp="datetimepicker" data-ui-options="{
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
               <label class="col-sm-2 form-control-label">Amount</label>
               <div class="col-sm-6">
                    <input type="number" step=".01" min="0" class="form-control" name="amt" placeholder="Amount">
               </div>
           </div>
           
           <div class="form-group row">
               <label class="col-sm-2 form-control-label">Summery</label>
               <div class="col-sm-6">
                    <input type="text" class="form-control" name="summery" placeholder="Summery">
               </div>
           </div>
           <div class="form-group row">
               <label class="col-sm-2 form-control-label">Description</label>
               <div class="col-sm-6">
                    <!--<input type="text" class="form-control" name="description" placeholder="Description">-->
                    <textarea type="text" class="form-control" name="description" placeholder="Description" rows="4"></textarea>
               </div>
           </div>
                 

                 
     
          
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="submit"type="submit" id="sub" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>
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