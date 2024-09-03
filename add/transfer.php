<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$from=$_POST['from'];
$to=$_POST['to'];
$date=$_POST['date'];
$amount=$_POST['amount'];

$sql = "INSERT INTO `transfers` (`from`,`to`,`amount`,`date`) 
VALUES ('$from','$to','$amount','$date')";
if ($conn->query($sql) === TRUE) {
    $status="success";
    
    
//    $sql1="update account set init_balance=init_balance-$amount where account=$from";
//    $conn->query($sql1);
//    
//    $sql2="update account set init_balance=init_balance+$amount where account=$to";
//    $conn->query($sql2);
    
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="TRS".$last_id;
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
          <h2>Account to Account Transfer</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/transfer" method="post">
            
            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">From Account</label>
              <div class="col-sm-8">
                   <select name="from" class="form-control">
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
              <label for="Quantity" class="col-sm-2 form-control-label">To Account</label>
              <div class="col-sm-8">
                   <select name="to" class="form-control">
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
              <label for="Quantity" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-8">
                <input type="number" class="form-control" name="amount" placeholder="Amount">
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