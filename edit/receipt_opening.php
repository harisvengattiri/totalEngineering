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
   $id=$_POST["id"];  
     
$customer=$_POST["customer"];
$site = "";
$tamount=$_POST["tamount"];
$tamount = ($tamount != NULL) ? $tamount : 0;
$tamount = number_format($tamount, 2, '.', '');

$old_tamount=$_POST["old_tamount"];
$old_tamount = ($old_tamount != NULL) ? $old_tamount : 0;
$old_tamount = number_format($old_tamount, 2, '.', '');
$updation = $tamount-$old_tamount;

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

$sql = "UPDATE `reciept` SET `customer` = '$customer',`site` = '$site',`amount` = '$tamount',`discount` = '0.00',`grand` = '$tamount',"
        . "`pmethod` = '$pmethod',`type` = '1',`pdate` = '$pdate',`gldate` = '$gldate',`duedate` = '$duedate',`clearance_date` = '$clearancedate',"
        . "`post_dated` = '$postdated',`ref` = '$ref',`cheque_no` = '$checkno',`bank` = '$bank',`inward` = '$inward',"
        . "`rep` = '$rep',`status` = '$status1',`time` = '$time' WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
    
    $sql1 = "UPDATE customers SET rcp=rcp+$updation WHERE id=$customer";
    $query = mysqli_query($conn,$sql1);
    
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="RPT".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} 
else 
    {
    $status="failed";
    }

}}
?> 
     
<?php
$id = $_GET['id'];

$sql="SELECT * FROM reciept WHERE id='$id'";
$query = mysqli_query($conn,$sql);
$fetch = mysqli_fetch_array($query);

$customer = $fetch['customer'];
// $site = $fetch['site'];

 $sqlcust="SELECT name from customers where id='$customer'";
 $querycust=mysqli_query($conn,$sqlcust);
 $fetchcust=mysqli_fetch_array($querycust);
 $cust=$fetchcust['name'];

                $sqlopen="SELECT op_bal,rcp FROM customers WHERE id=$customer";
		            $resultopen = mysqli_query($conn, $sqlopen);
                $rowopen = mysqli_fetch_assoc($resultopen);
                $opening = $rowopen['op_bal'];
                $opening = ($opening != NULL) ? $opening : 0;
                $rcp = $rowopen['rcp'];
                $rcp = ($rcp != NULL) ? $rcp : 0;
                $bal = $opening-$rcp;
                              
//  $sqlsite="SELECT p_name from customer_site where id='$site'";
//  $querysite=mysqli_query($conn,$sqlsite);
//  $fetchsite=mysqli_fetch_array($querysite);
//  $site1=$fetchsite['p_name'];
 
 $bank1 = $fetch['bank'];
 $sqlbank = "SELECT name FROM banks WHERE id='$bank1'";
 $querybank = mysqli_query($conn,$sqlbank);
 $fetchbank = mysqli_fetch_array($querybank);
 $bank = $fetchbank['name'];
 
 $inward1 = $fetch['inward'];
 $sqlinward = "SELECT `category` FROM `expense_subcategories` WHERE id='$inward1'";
 $queryinward = mysqli_query($conn,$sqlinward);
 $fetchinward = mysqli_fetch_array($queryinward);
 $inward = $fetchinward['category'];
 
 $rep1 = $fetch['rep'];
 $sqlrep = "SELECT name FROM customers WHERE id='$rep1'";
 $queryrep = mysqli_query($conn,$sqlrep);
 $fetchrep = mysqli_fetch_array($queryrep);
 $rep = $fetchrep['name'];
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
          <h2>Edit Opening Receipt</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/edit/receipt_opening?id=<?php echo $id;?>" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Customer</label>
              <input type="hidden" name="id" value="<?php echo $id;?>">
              <div class="col-sm-6">
                <select name="customer" id="customer" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php
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
              <label for="type" class="col-sm-2 form-control-label">Opening Balance</label>
              <div class="col-sm-4">
                   <input type="text" class="form-control" value="<?php echo $bal;?>" placeholder="Opening Balance" readonly>
              </div>  
               <label for="type" align="right" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-4">
                   <input type="text" class="form-control" name="tamount" value="<?php echo $fetch['amount'];?>" placeholder="Amount" required>
                   <input type="hidden" name="old_tamount" value="<?php echo $fetch['amount'];?>">
              </div>
            </div>   
            
            <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Payment Date</label>
              <div class="col-sm-4">
                  <input type="text" required name="pdate" value="<?php echo $fetch['pdate'];?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                  <input type="text" name="gldate" value="<?php echo $fetch['gldate'];?>" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                  <input type="text" name="duedate" value="<?php echo $fetch['duedate'];?>" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                  <input type="text" name="clearancedate" value="<?php echo $fetch['clearance_date'];?>" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
               <input style="margin-top: 10px;" type="checkbox" <?php if($fetch['post_dated'] == 1) {?> checked <?php } ?> class="form-control" name="postdated" value="1"></span>
               </div>
               <label for="type" align="right" class="col-sm-2 form-control-label">Ref No:</label>
               <div class="col-sm-2">
               <input type="text" class="form-control" name="ref" value="<?php echo $fetch['ref'];?>">    
               </div>
               <label for="type" align="right" class="col-sm-2 form-control-label">Cheque No:</label>
               <div class="col-sm-3">
               <input type="text" class="form-control" name="checkno" value="<?php echo $fetch['cheque_no'];?>">    
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
                                ?><option value="<?php echo $bank1;?>"><?php echo $bank;?></option><?php     
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
                    ?>
                    <option value="<?php echo $inward1;?>"><?php echo $inward;?></option><?php      
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
                                ?><option value="<?php echo $rep1;?>"><?php echo $rep;?></option><?php     
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
                    <option value="<?php echo $fetch['pmethod'];?>"><?php echo $fetch['pmethod'];?></option>
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