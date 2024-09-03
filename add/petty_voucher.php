<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
$date=$_POST["date"];
$staff=$_POST["staff"];
$checkno=$_POST["checkno"];
$pmethod=$_POST["pmethod"];
$amount=$_POST["amount"];
$bank=$_POST["bank"];

$sql = "INSERT INTO `petty_voucher` (`date`,`staff`,`amount`,`pmethod`,`status`,`bank`,`cheque_no`,`clearance_date`)
VALUES ('$date', '$staff', '$amount', '$pmethod', 'Cleared', '$bank', '$checkno', '$date')";

if ($conn->query($sql) === TRUE) {
      $status="success";
      $last_id = $conn->insert_id;
      $date1=date("d/m/Y h:i:s a");
      $username=$_SESSION['username'];
      $code="PTV".$last_id;
      $query=mysqli_real_escape_string($conn, $sql);
      $sql = "INSERT INTO activity_log (time, process, code, user, query) 
              values ('$date1', 'add', '$code', '$username', '$query')";
      $result = mysqli_query($conn, $sql);
          } else {
              $status="failed";
          }
}}
?>
<script>
$(document).on("wheel", "input[type=number]", function (e) {
    $(this).blur();
});
</script>
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-10">
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
          <h2>Add New Petty Cash Voucher</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
             
         <form role="form" action="<?php echo $baseurl;?>/add/petty_voucher" method="post">
              <div class="form-group row">
              <div class="col-sm-4">
                   <select class="form-control" name="staff" required>
                        <option value="">Select Staff</option>                        
                        <?php
                          $sql = "SELECT name,id FROM customers where type='SalesRep' OR type='Staff'";
                          $query = mysqli_query($conn,$sql);
                          while($fetch = mysqli_fetch_array($query))
                          {
                        ?>
                              <option value="<?php echo $fetch['id'];?>"><?php echo $fetch['name'];?></option>
                        <?php } ?>
                   </select>
              </div>
              </div>

              <div class="form-group row">
             <div class="col-sm-4">
             <?php
              $today = date('d/m/Y');
              ?>
                   <input type="text" name="date" value="<?php echo $today;?>" required id="date" placeholder="Payment Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <div class="col-sm-4">
                   <select name="pmethod" required class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                    <option value="">Payment Method</option>
                    <option value="cheque">Cheque</option>
                    <option value="cash">Cash</option>
                </select>
              </div>
              <div class="col-sm-4">
                   <select name="bank" required class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                    <option value="">Select Bank</option>
                    <?php
                    $sql = "SELECT id,category FROM `expense_subcategories` WHERE `parent` ='36'";
                    $query = mysqli_query($conn,$sql);
                    while($result = mysqli_fetch_array($query)){
                    ?>
                    <option value="<?php echo $result['id'];?>"><?php echo $result['category'];?></option>
                    <?php } ?>
                </select>
              </div>
          </div>
               
          <div class="form-group row">
              <div class="col-sm-4">
                   <input type="text" id="amt" step="any" class="form-control amount" name="amount" placeholder="Amount">
              </div>
              <div class="col-sm-4">
                   <input type="text" class="form-control" name="checkno" placeholder="Cheque No">    
              </div>
          <!--    <div class="col-sm-4">-->
          <!--         <input type="text" name="cdate" id="date" placeholder="Clearance Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{-->
          <!--  format: 'DD/MM/YYYY',-->
          <!--  icons: {-->
          <!--    time: 'fa fa-clock-o',-->
          <!--    date: 'fa fa-calendar',-->
          <!--    up: 'fa fa-chevron-up',-->
          <!--    down: 'fa fa-chevron-down',-->
          <!--    previous: 'fa fa-chevron-left',-->
          <!--    next: 'fa fa-chevron-right',-->
          <!--    today: 'fa fa-screenshot',-->
          <!--    clear: 'fa fa-trash',-->
          <!--    close: 'fa fa-remove'-->
          <!--  }-->
          <!--}">-->
          <!--  </div>-->
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