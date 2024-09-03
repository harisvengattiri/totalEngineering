<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
if(isset($_SESSION['userid']))
{
$customer=$_POST["customer"];
$voucher=$_POST["voucher"];
$tamount=$_POST["tamount"];

$yr=$_POST["year"];

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
     
$sql = "INSERT INTO `payment_voucher` (`id`, `name`, `category`, `subcategory`, `date`, `year`, `amount`, `discount`, `grand`, `voucher`, `pmethod`, `clearance_date`, `status`, `duedate`, `checkno`, `inward`, `current_timestamp`) 
VALUES ('NULL', '$customer', '$category', '$subcategory', '$date', '$yr', '$tamount', '$discountsum', '$grand', '$voucher' , '$pmethod' , '$cdate' , '$status1' , '$duedate' , '$checkno' , '$inward', '$time')";

if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
               $date1=date("d/m/Y h:i:s a");
               $username=$_SESSION['username'];
               $code="PV".$last_id;
               $query=mysqli_real_escape_string($conn, $sql);
               $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'add', '$code', '$username', '$query')";
               $result = mysqli_query($conn, $sql);
    $count=sizeof($invoice);
    for($i=0;$i<$count;$i++)
    {
        $sql_cat = "SELECT category FROM expenses WHERE inv='$invoice[$i]'";
        $query_cat = mysqli_query($conn,$sql_cat);
        $fetch_cat = mysqli_fetch_array($query_cat);
        $cat = $fetch_cat['category'];
    $total[$i] = $amount[$i] + $discount[$i];
    $sql1 = "INSERT INTO `payment_voucher_invoice` (`payment`, `date`, `inv`, `cat`, `amt`, `discount`, `total`) 
VALUES ('$last_id', '$date', '$invoice[$i]', '$cat', '$amount[$i]', '$discount[$i]', '$total[$i]')";
     $conn->query($sql1);
    }
    
    
} else {
    $status="failed";
}}
}}}
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
          <h2>Add New Payment Voucher</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
        
        <form role="form" action="<?php echo $baseurl;?>/add/payment_voucher" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Supplier</label>
              <div class="col-sm-4">
                <select name="customer" id="customer" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
                              $customer=$_POST['customer'];
                              $pdate=$_POST['pdate'];
                              
                              $sqlcust="SELECT name from customers where id='$customer'";
                              $querycust=mysqli_query($conn,$sqlcust);
                              $fetchcust=mysqli_fetch_array($querycust);
                              $cust=$fetchcust['name'];
                              
                                // $sql="SELECT * FROM customers WHERE type='Supplier' OR type='Company'";
                                $sql="SELECT * FROM customers WHERE type='Supplier'";
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
              <label for="type" align="right" class="col-sm-2 form-control-label">Payment Date</label>
               <?php
               if($pdate == NULL) {
               $today = date('d/m/Y'); $date = $today;
               } else { $date = $pdate; }
               ?>
              <div class="col-sm-4">
                  <input type="text" required name="pdate" value="<?php echo $date;?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <div align="" class="col-sm-offset-2 col-sm-12">
                <button name="submit1" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search Invoices</button>
              </div>
              </div>
         </form>     
             
          <?php if(isset($_POST['submit1']))
               {
               $customer=$_POST['customer'];
               $pdate=$_POST['pdate'];
               $yr = substr($pdate, -2);
            ?>   
          
          <form role="form" action="<?php echo $baseurl;?>/add/payment_voucher" method="post">
                 <input type="hidden" name="customer" value="<?php echo $customer;?>">
                 <input type="hidden" name="pdate" value="<?php echo $pdate;?>">
                 <input type="hidden" name="year" value="<?php echo $yr;?>">
            <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-4">
                  <input type="text" class="form-control" name="tamount" id="value" placeholder="Amount" required>
              </div>
               
              <label for="type" align="right" class="col-sm-2 form-control-label">Voucher No</label>
              <div class="col-sm-4">
                   <?php
                     $sqlvou="SELECT voucher from payment_voucher WHERE year='$yr' ORDER BY voucher DESC LIMIT 1";
                              $querycust=mysqli_query($conn,$sqlvou);
                              $fetchcust=mysqli_fetch_array($querycust);
                              $voucher=$fetchcust['voucher'];
                              $vou=$voucher+1;
                   ?>
                   <input type="text" class="form-control" name="voucher" value="<?php echo $vou;?>" readonly>
              </div> 
            </div>
            
            
            
            <div class="form-group row">
                <label for="category" class="col-sm-2 form-control-label">Category</label>
                <div class="col-sm-4">
                    <select name="category" id="category" required placeholder="category" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                        <?php 
                            $sql = "SELECT * FROM expense_categories";
                            $query = mysqli_query($conn,$sql);
                            if(mysqli_num_rows($query) > 0) {
                             echo '<option value="">Select Category</option>';
                            while($row = $query->fetch_assoc()) {
                        ?>
                        <option value="<?php echo $row['id'];?>"><?php echo $row['tag'];?></option>
                        <?php } } ?>
                    </select>
                </div>
                <label for="name" align="right" class="col-sm-2 form-control-label">Sub Category</label>
                <div class="col-sm-4">
                    <select name="subcategory" id="subcategory" placeholder="subcategory" class="form-control select2"></select>
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
                    <option value="">Payment Method</option>
                    <option value="cash">Cash</option>
                    <option value="cheque">Cheque</option>
                    <option value="card">Card</option>
                </select>
              </div>
                 
              <label for="type" align="right" class="col-sm-2 form-control-label">Bank</label>
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
            </div>   
               
           <div class="form-group row">
               
               
            <label for="type" align="" class="col-sm-2 form-control-label">Clearance Date</label>
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
              
            <label for="type" align="" class="col-sm-2 form-control-label">Cheque No:</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" name="checkno">    
              </div>
            </div>
                <script>
                      $(document).ready(function (event) {
                      $('#inv0').change(function() {
                         var country_id = $(this).val();
                         if(country_id != "") {
                           $.ajax({
                             url:"<?php echo $baseurl;?>/loads/getbalance_invoice_exp",
                             data:{c_id:country_id},
                             type:'POST',
                             success:function(response) {
                               var resp = $.trim(response);
                               $('#bal0').val(resp);
                             }
                           });
                         }
                       });
                       }); 
                 </script>  

            <div class="form-group row">
              <label for="name" class="col-sm-1 form-control-label">Invoice</label>
              <div class="col-sm-2">
                <select name="invoice[]" class="form-control select2" id="inv0" placeholder="Invoice" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
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
				?>
				<!--<option value="<?php echo $row["id"]; ?>">INV|<?php echo sprintf('%06d',$row["id"]);?></option>-->
                                <option value="<?php echo $row["inv"]; ?>">INV|<?php echo $row["inv"];?></option>
				<?php 
                                }}}
				?>
                </select>
               
              </div>
              <div class="col-sm-2">
                   <input type="number" class="form-control" name="balance[]" id="bal0" placeholder="Balance" readonly>
              </div>
              <div class="col-sm-2">
                   <input type="number" step=".01" min="1" class="form-control" name="amount[]" id="amt0" placeholder="Amount">
              </div>
              <div class="col-sm-2">
                   <input type="number" step=".01" min="0" class="form-control" name="discount[]" value="0" id="adt0" placeholder="Discount">
              </div>
              
			<div class="box-tools">
                              <a href="javascript:void(0);" 
                                 class="btn btn-info btn-sm" id="btnAddMoreSpecification" data-original-title="Add More">
                                   <i class="fa fa-plus"></i>
                              </a>
                         </div>	  
            </div>
                 
            <div id="divSpecificatiion">

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