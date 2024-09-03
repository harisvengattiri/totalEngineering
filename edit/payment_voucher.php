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
    
$customer=$_POST["customer"];
$voucher=$_POST["voucher"];
$tamount=$_POST["tamount"];

$subcategory=$_POST["subcategory"];
$category=$_POST["category"];

$date=$_POST["pdate"];
$yr = substr($date, -2);

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

$invoice=$_POST["invoice"];
$amount=$_POST["amount"];
$discount=$_POST["discount"];
$discountsum= array_sum($discount);
$discountsum = number_format($discountsum, 2, '.', '');

$grand = $discountsum+$tamount;
$grand = number_format($grand, 2, '.', '');

$invoice=array_filter($invoice);
if(count(array_unique($invoice))<count($invoice)) { $status='failed1'; } else {    
$amountsum= array_sum($amount);

 $amt123 = custom_money_format('%!i', $amountsum);
 $amt124 = custom_money_format('%!i', $tamount);

if($amt123 != $amt124) {  $status='failed2'; }  else {

  date_default_timezone_set('Asia/Dubai');
  $time = date('Y-m-d H:i:s', time());

$sql = "UPDATE `payment_voucher` SET `name` = '$customer', `date` = '$date', `year` = '$yr', `amount` = '$tamount', `discount` = '$discountsum', `grand` = '$grand',
        `category` = '$category', `subcategory` = '$subcategory', `voucher` = '$voucher', `pmethod` = '$pmethod', `clearance_date` = '$cdate',
        `status` = '$status1', `duedate` = '$duedate', `checkno` = '$checkno', `inward` = '$inward', `current_timestamp` = '$time' WHERE id=$id";
if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
               $date1=date("d/m/Y h:i:s a");
               $username=$_SESSION['username'];
               $code="PV".$id;
               $query=mysqli_real_escape_string($conn, $sql);
               $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'edit', '$code', '$username', '$query')";
               $result = mysqli_query($conn, $sql);
    
    $sql2 = "DELETE FROM payment_voucher_invoice WHERE payment=$id";
    $query2 = mysqli_query($conn,$sql2);
    
    $count=sizeof($invoice);
    for($i=0;$i<$count;$i++)
    {
        $sql_cat = "SELECT category FROM expenses WHERE inv='$invoice[$i]'";
        $query_cat = mysqli_query($conn,$sql_cat);
        $fetch_cat = mysqli_fetch_array($query_cat);
        $cat = $fetch_cat['category'];
    $total[$i] = $amount[$i] + $discount[$i];
    $sql1 = "INSERT INTO `payment_voucher_invoice` (`payment`, `date`, `inv`, `cat`, `amt`, `discount`, `total`) 
VALUES ('$id', '$date', '$invoice[$i]', '$cat', '$amount[$i]', '$discount[$i]', '$total[$i]')";
     $conn->query($sql1);
    }
    
    
} else {
    $status="failed";
}}
}}}


$id = $_GET['id'];
$sql = "SELECT * FROM payment_voucher WHERE id=$id";
$query = mysqli_query($conn,$sql);
$fetch = mysqli_fetch_array($query);
$customer=$fetch['name'];
$bank=$fetch['inward'];

$cat = $fetch['category'];
    $sql_cat = "SELECT * FROM `expense_categories` WHERE id='$cat'";
    $query_cat = mysqli_query($conn,$sql_cat);
    $result_cat = mysqli_fetch_array($query_cat);
    $cat_name = $result_cat['tag'];
$sub = $fetch['subcategory'];
    $sql_sub_cat = "SELECT * FROM `expense_subcategories` WHERE id='$sub'";
    $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
    $result_sub_cat = mysqli_fetch_array($query_sub_cat);
    $sub_cat_name = $result_sub_cat['category'];

?>
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
          <h2>Edit Payment Voucher</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
        
        <form role="form" action="<?php echo $baseurl;?>/edit/payment_voucher?id=<?php echo $id;?>" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Supplier</label>
              <input type="hidden" name="id" value="<?php echo $id;?>" required>
              <div class="col-sm-6">
                    <?php
                        $sqlcust="SELECT name from customers where id='$customer'";
                        $querycust=mysqli_query($conn,$sqlcust);
                        $fetchcust=mysqli_fetch_array($querycust);
                        $cust=$fetchcust['name'];
                    ?>
                <input type="hidden" name="customer" value="<?php echo $customer;?>">
                <input type="text" class="form-control" value="<?php echo $cust;?>" readonly>
              </div>
            </div>

            <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-4">
                  <input type="text" class="form-control" name="tamount" value="<?php echo $fetch['amount'];?>" required>
              </div>
               
              <label for="type" align="right" class="col-sm-2 form-control-label">Voucher No</label>
              <div class="col-sm-4">
                   <input type="text" class="form-control" name="voucher" value="<?php echo $fetch['voucher'];?>" readonly>
              </div> 
            </div>
            
            
            <div class="form-group row">
                <label for="category" class="col-sm-2 form-control-label">Category</label>
                <div class="col-sm-4">
                    <select name="category" id="category" required placeholder="category" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                        <option value="<?php echo $cat;?>"><?php echo $cat_name;?></option>
                        <?php 
                            $sql = "SELECT * FROM expense_categories";
                            $query = mysqli_query($conn,$sql);
                            if(mysqli_num_rows($query) > 0) {
                            while($row = $query->fetch_assoc()) {
                        ?>
                        <option value="<?php echo $row['id'];?>"><?php echo $row['tag'];?></option>
                        <?php } } ?>
                    </select>
                </div>
                <label for="name" align="right" class="col-sm-2 form-control-label">Sub Category</label>
                <div class="col-sm-4">
                    <select name="subcategory" id="subcategory" placeholder="subcategory" class="form-control select2">
                        <option value="<?php echo $sub;?>"><?php echo $sub_cat_name;?></option>
                    </select>
                </div>
                 <script type="text/javascript">
                 $(document).ready(function() {
                  $("#category").change(function() {
                    var cat_id = $(this).val();
                    if(cat_id != "") {
                      $.ajax({
                        url: '<?php echo $baseurl;?>/loads/subcat',
                        data:{cat_id:cat_id},
                        type:'POST',
                        success:function(response) {
                          var resp = $.trim(response);
                          $("#subcategory").html(resp);
                        }
                      });
                    } else {
                      $("#subcategory").html("<option value=''>------- Select --------</option>");
                    }
                  });
                });
                </script>
            </div>
                 
            <div class="form-group row">
              <label for="type" align="" class="col-sm-2 form-control-label">Payment Method</label>
              <div class="col-sm-4">
                  <select name="pmethod" required class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                    <option value="<?php echo $fetch['pmethod'];?>"><?php echo $fetch['pmethod'];?></option>
                    <option value="cash">Cash</option>
                    <option value="cheque">Cheque</option>
                    <option value="card">Card</option>
                </select>
              </div>
                 
               <label for="type" align="right" class="col-sm-2 form-control-label">Payment Date</label>
              <div class="col-sm-4">
                  <input type="text" required name="pdate" value="<?php echo $fetch['date'];?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <input type="text" class="form-control" name="checkno" value="<?php echo $fetch['checkno'];?>">    
              </div>
                
              <label for="type" align="right" class="col-sm-2 form-control-label">Cheque Date</label>
               
              <div class="col-sm-4">
                  <input type="text" name="duedate" value="<?php echo $fetch['duedate'];?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                <?php $sql = "SELECT name FROM customers WHERE id=$bank";
				$result = mysqli_query($conn, $sql);
				$rowbnk = mysqli_fetch_assoc($result);
                ?>
                   <select class="form-control" name="inward" required>
                   <?php $sql = "SELECT * FROM customers WHERE type='Bank'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                ?><option value="<?php echo $bank;?>"><?php echo $rowbnk['name'];?></option><?php     
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
                  <input type="text" name="cdate" value="<?php echo $fetch['clearance_date'];?>" id="date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
            <?php 
               $sql1="SELECT * FROM payment_voucher_invoice where payment='$id'";
               $result1=$conn->query($sql1);
               $count=0;
               while($row1 = mysqli_fetch_assoc($result1)) 
                    {
                         $invoice=$row1["inv"];
                         $amount=$row1["amt"];
                         $discount=$row1["discount"];
            ?>

            <div class="form-group row">
              <label for="name" class="col-sm-1 form-control-label">Invoice</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="invoice[]" value="<?php echo $invoice;?>" readonly>
              </div>
              <div class="col-sm-2">
                <?php 
				$sql = "SELECT * FROM expenses WHERE inv='$invoice'";
				$result = mysqli_query($conn, $sql);
				while($row = mysqli_fetch_assoc($result)) 
				{
                    $grand=$row["amount"];
                    $sqlsum="SELECT sum(total) AS total FROM payment_voucher_invoice WHERE inv='$invoice'";
                    $resultsum = mysqli_query($conn, $sqlsum);
                    $rowsum = mysqli_fetch_assoc($resultsum);
                    $total=$rowsum["total"];
                    $bal=$grand-$total;
                    $bal = number_format($bal, 2, '.', '');
                    // if($bal>1) {$bal=$bal;} else { $bal=0; }
                    }
		        ?>
                   <input type="number" class="form-control" name="balance[]" value="<?php echo $bal;?>" readonly>
              </div>
              <div class="col-sm-2">
                   <input type="number" step=".01" min="1" class="form-control" name="amount[]" value="<?php echo $amount;?>">
              </div>
              <div class="col-sm-2">
                   <input type="number" step=".01" min="0" class="form-control" name="discount[]" value="<?php echo $discount;?>">
              </div>
			   <?php if($count=='0') { ?>
                    <div class="box-tools">
                              <a href="javascript:void(0);" 
                                 class="btn btn-info btn-sm" id="btnAddMoreSpecification" data-original-title="Add More">
                                   <i class="fa fa-plus"></i>
                              </a>
                    </div>
                <?php } else { ?>         
                   <div class="box-tools">
                   <a href="javascript:void(0);"  class="btn btn-danger btn-sm btnRemoveMoreSpecification" data-original-title="Add More">
                   <i class="fa fa-times"></i>
                   </a>
                   </div>
                <?php } $count++; ?>	  
            </div>
            <?php } ?>    
            <div id="divSpecificatiion">

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
 
<script>
$(document).ready(function (event) {  
uid=1;uvd=2;
 

$('#btnAddMoreSpecification').click(function () { 
    
var row='<div class="form-group row" style="padding-top:10px;">\n\
<label for="name" class="col-sm-1 form-control-label">Invoice</label>\n\
<div class="col-sm-2">\n\
<select class="form-control" name="invoice[]" id="inv'+uid+'">\n\
\n\<?php
   $sql = "SELECT *,sum(amount) as amount FROM expenses WHERE shop='$customer' GROUP BY `inv`";
   $result = mysqli_query($conn, $sql);
   if (mysqli_num_rows($result) > 0) 
   {
   ?><option></option><?php
   while($row = mysqli_fetch_assoc($result)) 
   {
        $inv=$row["inv"];
        $grand=$row["amount"];
        $sqlsum="SELECT sum(total) AS total FROM payment_voucher_invoice WHERE inv='$inv'";
        $resultsum = mysqli_query($conn, $sqlsum);
        $rowsum = mysqli_fetch_assoc($resultsum);
        $total=$rowsum["total"];
        $bal=$grand-$total;
        if($bal != 0){
   ?><option value="<?php echo $row["inv"];?>">INV|<?php echo $row["inv"];?></option><?php
   }
   }     
   }
?></select></div>\n\
<div class="col-sm-2"><input type="number" class="form-control" name="balance[]" id="bal'+uid+'" placeholder="Balance" readonly></div>\n\
<div class="col-sm-2"><input type="number" step=".01" min="1" class="form-control" name="amount[]" id="amt'+uid+'" placeholder="Amount"></div>\n\
<div class="col-sm-2"><input type="number" step=".01" min="0" class="form-control" name="discount[]" value="0" id="adt'+uid+'" placeholder="Discount"></div>\n\
     <div class="box-tools">\n\
     <a href="javascript:void(0);"  class="btn btn-danger btn-sm btnRemoveMoreSpecification" data-original-title="Add More">\n\
     <i class="fa fa-times"></i>\n\
     </a>\n\
     </div>\n\
</div>';
     
  
          
     if(uid<=29)
     {
     $('#divSpecificatiion').append(row);
     }
     
     <?php for($i=1;$i<=29;$i++){ echo       
                 "$(document).ready(function (event) {".
                     " $('#inv'+$i+'').change(function() {".
                         "var country_id = $(this).val();".
                         "if(country_id != '') {".
                           "$.ajax({".
                             "url:'$baseurl/loads/getbalance_invoice_exp',".
                             "data:{c_id:country_id},".
                             "type:'POST',".
                             "success:function(response) {".
                               "var resp = $.trim(response);".
                               "$('#bal'+$i+'').val(resp);".
                             "}".
                           "});".
                         "}".
                       "});".
                       "});";
                }?>
    
     uid++;uvd++;
     });
     $(document).on('click', '.btnRemoveMoreSpecification', function () {
          $(this).parent('div').parent('div').remove();
     });
});
</script>
  
  
<?php include "../includes/footer.php";?>