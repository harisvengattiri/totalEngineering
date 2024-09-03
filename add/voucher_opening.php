<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<?php
$status="NULL";
if(isset($_POST['submit']))
{
if(isset($_SESSION['userid']))
{
$customer=$_POST["customer"];
$voucher=$_POST["voucher"];
$tamount=$_POST["tamount"];
$tamount = number_format($tamount, 2, '.', '');

$subcategory=$_POST["subcategory"];
$category=$_POST["category"];

$date=$_POST["pdate"];
$cdate=$_POST["cdate"];
$pmethod=$_POST["pmethod"];

        if($pmethod == 'cash' || $pmethod == 'card')
        {
            $status1='Cleared';
            if($cdate == '')
            {
            $cdate=date("d/m/Y");
            }
        }
        if($pmethod == 'cheque'){ $cdate=''; $status1='Uncleared';}

$duedate=$_POST["duedate"];
$checkno=$_POST["checkno"];
$inward=$_POST["inward"];
$discountsum = 0;
$grand = $tamount;


  date_default_timezone_set('Asia/Dubai');
  $time = date('Y-m-d H:i:s', time());
     
$sql = "INSERT INTO `voucher` (`id`, `name`, `category`, `subcategory`, `date`, `amount`, `discount`, `grand`, `voucher`, `pmethod`, `clearance_date`, `status`, `duedate`, `checkno`, `inward`, `type`, `current_timestamp`) 
VALUES ('NULL', '$customer', '$category', '$subcategory', '$date', '$tamount', '$discountsum', '$grand', '$voucher' , '$pmethod' , '$cdate' , '$status1' , '$duedate' , '$checkno' , '$inward', '1', '$time')";

if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
    
    $sql1 = "UPDATE customers SET rcp=rcp+$tamount WHERE id=$customer";
    $query = mysqli_query($conn,$sql1);
    
               $date1=date("d/m/Y h:i:s a");
               $username=$_SESSION['username'];
               $code="PVR".$last_id;
               $query=mysqli_real_escape_string($conn, $sql);
               $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'add', '$code', '$username', '$query')";
               $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}}
?>
<!-- ############ PAGE START-->
<div class="app-body">
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
	<?php } ?>
    
    
    
      <div class="box">
        <div class="box-header">
          <h2>Add New Payment Voucher for Opening</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
        
        <form role="form" action="<?php echo $baseurl;?>/add/voucher_opening" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Supplier</label>
              <div class="col-sm-6">
                <select name="customer" id="customer" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
                              $customer=$_POST['customer'];
                              
                              $sqlcust="SELECT name from customers where id='$customer'";
                              $querycust=mysqli_query($conn,$sqlcust);
                              $fetchcust=mysqli_fetch_array($querycust);
                              $cust=$fetchcust['name'];
                              
                                $sql="SELECT * FROM customers WHERE type='Supplier' OR type='Company'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				?> <option value="<?php echo $customer;?>"><?php echo $cust;?></option> <?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row['id']; ?>"><?php echo $row['name'];?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
            </div>
               
              <div class="form-group row m-t-md">
              <div align="" class="col-sm-offset-2 col-sm-12">
                <button name="submit1" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Check Opening Balance</button>
              </div>
              </div>
         </form>     
             
          <?php if(isset($_POST['submit1']))
               {
               $customer=$_POST['customer'];
               $sql_cust = "SELECT op_bal,rcp FROM customers WHERE id='$customer'";
               $query_cust = mysqli_query($conn,$sql_cust);
               $fetch_cust = mysqli_fetch_array($query_cust);
               $supplier_opening = $fetch_cust['op_bal'];
               $rcp = $fetch_cust['rcp'];
               $opening_balance = $supplier_opening-$rcp;
            ?>   
          
          <form role="form" action="<?php echo $baseurl;?>/add/voucher_opening" method="post">
                 <input type="hidden" name="customer" value="<?php echo $customer;?>">
            <div class="form-group row">
               <label class="col-sm-2 form-control-label">Opening Amount</label>
               <div class="col-sm-4">
                  <input type="text" class="form-control" name="opening" value="<?php echo $opening_balance;?>" placeholder="Opening" readonly>
              </div>
            </div>
            <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-4">
                  <input type="text" class="form-control" name="tamount" id="value" placeholder="Amount" required>
              </div>
               
              <label for="type" align="right" class="col-sm-2 form-control-label">Voucher No</label>
              <div class="col-sm-4">
                   <?php
                     $sqlvou="SELECT voucher from voucher ORDER BY id DESC LIMIT 1";
                              $querycust=mysqli_query($conn,$sqlvou);
                              $fetchcust=mysqli_fetch_array($querycust);
                              $voucher=$fetchcust['voucher'];
                              $vou=$voucher+1;
                   ?>
                   <input type="text" class="form-control" name="voucher" value="<?php echo $vou;?>" readonly>
              </div> 
            </div>
            <div class="form-group row">
              <label for="type" align="" class="col-sm-2 form-control-label">Payment Method</label>
              <div class="col-sm-4">
                  <select name="pmethod" required class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                    <option value="">Payment Method</option>
                    <option value="cash">Cash</option>
                    <option value="cheque">Cheque</option>
                    <option value="card">Card</option>
                </select>
              </div>
                 
               <label for="type" align="right" class="col-sm-2 form-control-label">Payment Date</label>
               <?php
               $today = date('d/m/Y');
               ?>
              <div class="col-sm-4">
                  <input type="text" required name="pdate" value="<?php echo $today;?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                
              <label for="type" align="" class="col-sm-2 form-control-label">Cheque No:</label>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="checkno">    
              </div>
                
              <label for="type" align="right" class="col-sm-2 form-control-label">Cheque Date</label>
               
              <div class="col-sm-4">
                  <input type="text" name="duedate" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <label for="type" align="" class="col-sm-2 form-control-label">Bank</label>
              <div class="col-sm-4">
                   <select class="form-control" name="inward" required>
                   <?php     $sql = "SELECT * FROM customers WHERE type='Bank'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                ?><option value="">SELECT BANK</option><?php     
                                while($row = mysqli_fetch_assoc($result)) 
				{
                              ?><option value="<?php echo $row["id"]; ?>"><?php echo $row["name"];?></option><?php
                                }
                                }
                   ?>             
                   </select>
              </div>
              <label for="type" align="right" class="col-sm-2 form-control-label">Clearance Date</label>
              <div class="col-sm-4">
                  <input type="text" name="cdate" id="date" placeholder="Clearance Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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