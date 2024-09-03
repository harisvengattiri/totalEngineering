<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$payer=$_POST['payer'];
$account=$_POST['account'];
$sql3="select name from account where account=$account";
$query=mysqli_query($conn,$sql3);
$fetch=mysqli_fetch_array($query);
$ac_name=$fetch['name'];
$date=$_POST['date'];
$amount=$_POST['amount'];
$type=$_POST['type'];
$catagory=$_POST['catagory'];
$method=$_POST['method'];
$note=$_POST['note'];

$sql = "INSERT INTO `transactions` (`payer`,`account`,`ac_name`,`date`,`amount`,`type`,`catagory`,`method`,`note`) 
VALUES ('$payer','$account','$ac_name','$date','$amount','$type','$catagory','$method','$note')";
if ($conn->query($sql) === TRUE) {
    $status="success";
    
//    if($type==income)
//         {
//         $sql1="update account set init_balance=init_balance+$amount where account=$account";
//         $conn->query($sql1);
//         }else
//         {
//         $sql2="update account set init_balance=init_balance-$amount where account=$account";
//         $conn->query($sql2);   
//         }
    
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="TRN".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}
?>
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-6">
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
          <h2>Add New Transaction</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/transaction" method="post">
            
            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Payer</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="payer" placeholder="Payer">
              </div>
            </div>
             
            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Account</label>
              <div class="col-sm-8">
                   <select name="account" class="form-control">
                        <?php 
                          $sql="select * from account";
                          $query=mysqli_query($conn,$sql);
                          while($fetch=mysqli_fetch_array($query))
                          {
                               $account=$fetch['account'];
                               $name=$fetch['name'];
                          ?>
                        <option value="<?php echo $account;?>"><?php echo $account?> - <?php echo $name?></option>
                          <?php } ?>
                   </select>   
                <!--<input type="text" class="form-control" name="account" placeholder="Account">-->
              </div>
            </div>
               
           <div class="form-group row">
              <label for="date"  class="col-sm-2 form-control-label">Date</label>
              <div class="col-sm-8">
                <?php $today=date("d/m/Y"); ?>
                <input type="text" name="date" value="<?php echo $today;?>" id="date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <label for="Quantity" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-8">
                <input type="number" class="form-control" name="amount" placeholder="Amount">
              </div>
            </div>
               
            <div class="form-group row">
              <label class="col-sm-2 form-control-label">Type</label>
              <div class="col-sm-8">
                   <select class="form-control" name="type">
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                   </select>
                   
                <!--<input type="text" class="form-control" name="type" placeholder="Type">-->
              </div>
            </div>
               
            <div class="form-group row">
              <label class="col-sm-2 form-control-label">Catagory</label>
              <div class="col-sm-8">
                   <select class="form-control" name="catagory">
                        <option value="sale">Sales</option>
                        <option value="purchase">Purchase</option>
                        <option value="expense">Expense</option>
                        <option value="salary">Salary</option>
                   </select>
                <!--<input type="text" class="form-control" name="catagory" placeholder="Catagory">-->
              </div>
            </div>
               
               <div class="form-group row">
              <label class="col-sm-2 form-control-label">Method</label>
              <div class="col-sm-8">
                   <select class="form-control" name="method">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="cheque">Cheque</option>
                   </select>
                <!--<input type="text" class="form-control" name="method" placeholder="Method">-->
              </div>
            </div>
               
               <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Note</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="note" placeholder="Note">
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