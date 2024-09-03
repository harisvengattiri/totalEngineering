<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<?php error_reporting(0); ?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
$customer=$_POST["customer"];
$site="";
$tamount=$_POST["tamount"];
$tamount=($tamount != NULL) ? $tamount : 0;
$tamount = number_format($tamount, 2, '.', '');
$pmethod=$_POST["pmethod"];
$clearancedate=$_POST["clearancedate"];
$duedate=$_POST["duedate"];

if($pmethod == 'cash' || $pmethod == 'card')
{
    $status1='Cleared';
    if($clearancedate=="")
    {
    $clearancedate=date("d/m/Y");
    }
    if($duedate=="")
    {
    $duedate=date("d/m/Y");
    }
}

if($pmethod == 'cheque'){ $clearancedate=''; $status1='Uncleared';}

$pdate=$_POST["pdate"];
$gldate=$_POST["gldate"];
$postdated=$_POST["postdated"];
$ref=$_POST["ref"];
$checkno=$_POST["checkno"];
$bank=$_POST["bank"];
$inward=$_POST["inward"];
$rep=$_POST["rep"];

date_default_timezone_set('Asia/Dubai');
$time = date('Y-m-d H:i:s', time());
$token=$_POST["token"];
$sql = "INSERT INTO `reciept` (`token`, `customer`, `site`, `amount`, `discount`, `grand`, `pmethod`, `type`, `pdate`, `gldate`, `duedate`, `clearance_date`, `post_dated`, `ref`, `cheque_no`, `bank`, `cat`, `inward`,`rep`,`status`,`time`) 
VALUES ('$token', '$customer', '$site', '$tamount', '0.00', '$tamount', '$pmethod', '2',  '$pdate', '$gldate', '$duedate', '$clearancedate', '$postdated', '$ref', '$checkno', '$bank', '36', '$inward', '$rep', '$status1', '$time')";

if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
    
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="RPT".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
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
      <?php } else if($status=="failed1") {?>
          <p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed! Cannot take same invoice twice in a receipt</span>
           </a></p>
        <?php } else if($status=="failed2") {?>
          <p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed! Payed Amount and invoiced amount doesn't match</span>
           </a></p>
        <?php } ?>
    
    
      <div class="box">
        <div class="box-header">
          <h2>Generate New Receipt</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/receipt_advance" method="post">
            <div class="form-group row">
              <input type="hidden" name="token" value="<?php echo rand(1000,9999).date('Ymdhisa');?>">
              <label for="name" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-6">
                <select name="customer" id="customer" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
                              $customer=$_POST['customer'];
                              // $site=$_POST['site'];
                              
                              $sqlcust="SELECT name from customers where id='$customer'";
                              $querycust=mysqli_query($conn,$sqlcust);
                              $fetchcust=mysqli_fetch_array($querycust);
                              $cust=$fetchcust['name'];
                              
                              // $sqlsite="SELECT p_name from customer_site where id='$site'";
                              // $querysite=mysqli_query($conn,$sqlsite);
                              // $fetchsite=mysqli_fetch_array($querysite);
                              // $site1=$fetchsite['p_name'];
                    
                                $sql="SELECT * FROM customers WHERE type='Company' ORDER BY name";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				?> <option value="<?php echo $customer;?>"><?php echo $cust;?></option> <?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"];?>"><?php echo $row["name"];?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
            </div>
         
            <div class="form-group row">
               <label for="type" align="" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-4">
                  <input type="text" class="form-control" name="tamount" id="value" placeholder="Amount">
              </div>
            </div>   
            
            <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Payment Date</label>
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
               <label for="type" align="right" class="col-sm-2 form-control-label">GL Date</label>
              <div class="col-sm-4">
                  <input type="text" name="gldate" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
               <label for="type" class="col-sm-2 form-control-label">Due Date</label>
               
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
               <label for="type" align="right" class="col-sm-2 form-control-label">Clearance Date</label>
              <div class="col-sm-4">
                  <input type="text" name="clearancedate" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
               <label for="type" class="col-sm-2 form-control-label">Post Dated</label>
               <div class="col-sm-1">
               <input style="margin-top: 10px;" type="checkbox" class="form-control" name="postdated" value="1"></span>
               </div>
               <label for="type" align="right" class="col-sm-2 form-control-label">Ref No:</label>
               <div class="col-sm-2">
               <input type="text" class="form-control" name="ref">    
               </div>
               <label for="type" align="right" class="col-sm-2 form-control-label">Cheque No:</label>
               <div class="col-sm-3">
               <input type="text" class="form-control" name="checkno">    
               </div>
              </div>
               
              <div class="form-group row">
              <label for="type" align="left" class="col-sm-2 form-control-label">Customer Bank</label>
              <div class="col-sm-4">
                   <select class="form-control" name="bank">
                   <?php     $sql = "SELECT * FROM banks";
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
              <label for="type" align="right" class="col-sm-2 form-control-label">Inwarded To</label>
              <div class="col-sm-4">
                   <select class="form-control" name="inward">
                   <?php
                    $sql = "SELECT id,category FROM `expense_subcategories` WHERE `parent`='36'";
    				$result = mysqli_query($conn, $sql);
    				if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                    ?><option value="<?php echo $row["id"]; ?>"><?php echo $row["category"];?></option><?php
                     } }
                   ?>             
                   </select>
              </div>
              </div>
              
              <div class="form-group row">
              <label for="type" align="left" class="col-sm-2 form-control-label">Sales Rep</label>
              <div class="col-sm-4">
                   <select class="form-control" name="rep">
                   <?php     $sql = "SELECT * FROM customers WHERE type='SalesRep'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                ?><option value="">SELECT SALES REP</option><?php     
                                while($row = mysqli_fetch_assoc($result)) 
				{
                              ?><option value="<?php echo $row["id"]; ?>"><?php echo $row["name"];?></option><?php
                                }
                                }
                   ?>             
                   </select>
              </div>
              <label for="type" align="right" class="col-sm-2 form-control-label">Payment Method</label>
              <div class="col-sm-4">
                  <select name="pmethod" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                    <!--<option value="">Payment Method</option>-->
                    <option value="cash">Cash</option>
                    <option value="cheque">Cheque</option>
                    <option value="card">Card</option>
                </select>
              </div>
              </div>

              
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="submit"type="submit" id="sub" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>
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