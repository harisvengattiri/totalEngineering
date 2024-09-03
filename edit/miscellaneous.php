<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
$id=$_POST["id"];
    
$date=$_POST["date"];
$cdate=$_POST["cdate"];
$particulars=$_POST["particulars"];
$amt=$_POST["amt"];
$vat=$_POST["vat"];
$amount=$_POST["amount"];
$pmethod=$_POST["method"];
$customer=$_POST["customer"];
$bank=$_POST["bank"];

    if($pmethod == 'Cash' || $pmethod == 'Card')
    {
        $status1='Cleared';
        if($cdate == '')
        {
        $cdate=date("d/m/Y");
        }
    }
    if($pmethod == 'Cheque'){ $cdate=''; $status1='Uncleared';}

// $sql = "INSERT INTO `miscellaneous111` (`date`, `clearance_date`, `status`, `particulars`, `method`, `customer`, `bank`, `amount`, `vat`,`total`) 
// VALUES ('$date','$cdate','$status1','$particulars','$pmethod','$customer','$bank','$amt','$vat','$amount')";

$sql = "UPDATE `miscellaneous` SET `date`='$date',`clearance_date`='$cdate',`status`='$status1',`particulars`='$particulars',`method`='$pmethod',`customer`='$customer',
        `bank`='$bank',`amount`='$amt',`vat`='$vat',`total`='$amount' WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="MSC".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}
}}
?>
<?php
if($_GET['id'])
{
    $id=$_GET['id']; 
}
$sql="SELECT * FROM miscellaneous where id='$id'";
$query=mysqli_query($conn,$sql);
if(mysqli_num_rows($query) > 0)
{
     while($result=mysqli_fetch_array($query))
     {
          $date=$result['date'];
          $cdate=$result['clearance_date'];
          $particulars=$result['particulars'];
          $method=$result['method'];
          $customer=$result['customer'];
          $bank=$result['bank'];
          $amount=$result['amount'];
          $vat=$result['vat'];
          $total=$result['total'];
     }
}
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
          <h2>Edit Miscellaneous Income</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/edit/miscellaneous?id=<?php echo $id;?>" method="post">
             
            <div class="form-group row">
              <label for="particulars" class="col-sm-2 form-control-label">Particulars</label>
              <div class="col-sm-4">
                <input type="hidden" name="id" value="<?php echo $id;?>">
                <input type="particulars" class="form-control" name="particulars" value="<?php echo $particulars;?>" id="particulars" placeholder="Particulars">
              </div>
              <label for="date" align="right" class="col-sm-2 form-control-label">Date</label>
              <div class="col-sm-4">
                <input type="text" name="date" value="<?php echo $date;?>" required id="date" placeholder="Payment Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <label for="amount" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-4">
                <input type="number" step="0.01" value="<?php echo $amount;?>" class="form-control" name="amt" id="input1"  placeholder="Amount" required >
              </div>
              <label for="amount" align="right" class="col-sm-2 form-control-label">VAT</label>
              <div class="col-sm-4">
                <input type="number" step="0.01" value="<?php echo $vat;?>" class="form-control" name="vat" id="input2" value="0.00" placeholder="VAT Amount">
              </div>
            </div>
               
              <script>
               $('#input1,#input2').keyup(function(){
                   var n1 = parseFloat($('#input1').val());
                   var n2 = parseFloat($('#input2').val());
                   var n3 = parseFloat($('#input1').val()) + parseFloat($('#input2').val());
                   $('#output1').val(Number(Math.round(n3+'e2')+'e-2'));
               });
              </script>
              
             <div class="form-group row">
              <label for="method" class="col-sm-2 form-control-label">Total</label>
              <div class="col-sm-4">
                   <input type="number" step="0.01" value="<?php echo $total;?>" class="form-control" name="amount" id="output1" placeholder="Total Amount" readonly required>
              </div>
              <label for="method" align="right" class="col-sm-2 form-control-label">Method</label>
              <div class="col-sm-4">
                <select name="method" id="method" placeholder="Method" class="form-control">
                    <option value="<?php echo $method;?>"><?php echo $method;?></option>
				                <option value="Cash">Cash</option>
                                <option value="Card">Card</option>
                                <option value="Cheque">Cheque</option>
                                <!--<option value="ETransfer">eTransfer</option>-->
		</select>
              </div>
              </div> 
              
             <div class="form-group row">
            <label for="method" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-4">
                   <select name="customer" required class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                    <?php
                        $sql = "SELECT name FROM customers WHERE id='$customer'";
                        $query = mysqli_query($conn,$sql);
                        $result = mysqli_fetch_array($query);
                        $cust = $result['name'];
                       ?>
                    <option value="<?php echo $customer;?>"><?php echo $cust;?></option>
                    <?php
                    $sql = "SELECT id,name FROM customers WHERE type='Company' OR type='Supplier'";
                    $query = mysqli_query($conn,$sql);
                    while($result = mysqli_fetch_array($query)){
                    ?>
                    <option value="<?php echo $result['id'];?>"><?php echo $result['name'];?></option>
                    <?php } ?>
                </select>
              </div>
              
              <label for="date" align="right" class="col-sm-2 form-control-label">Clearance Date</label>
              <div class="col-sm-4">
                <input type="text" name="cdate" value="<?php echo $cdate;?>" id="date" placeholder="Clearance Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
               <label for="method" align="" class="col-sm-2 form-control-label">Bank</label>
              <div class="col-sm-4">
                   <select name="bank" required class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                       <?php
                        $sql = "SELECT category FROM `expense_subcategories` WHERE id='$bank'";
                        $query = mysqli_query($conn,$sql);
                        $result = mysqli_fetch_array($query);
                        $bank_name = $result['category'];
                       ?>
                    <option value="<?php echo $bank;?>"><?php echo $bank_name;?></option>
                    <?php
                    $sql = "SELECT id,category FROM `expense_subcategories` WHERE `parent`='36'";
                    $query = mysqli_query($conn,$sql);
                    while($result = mysqli_fetch_array($query)){
                    ?>
                    <option value="<?php echo $result['id'];?>"><?php echo $result['category'];?></option>
                    <?php } ?>
                </select>
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