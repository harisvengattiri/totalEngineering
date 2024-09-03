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
$site = "";
$tamount=$_POST["tamount"];
$tamount = ($tamount != NULL) ? $tamount : 0;
$pmethod=$_POST["pmethod"];
$clearancedate=$_POST["clearancedate"];
$duedate=$_POST["duedate"];

if($pmethod == 'cash' || $pmethod == 'card')
{
    $status1='Cleared';
    if($clearancedate == '')
    {
    $clearancedate=date("d/m/Y");
    }
    if($duedate == '')
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

$invoice=$_POST["invoice"];
$balance=$_POST["balance"];
$amount=$_POST["amount"];
$discount=$_POST["discount"];
$discountsum= array_sum($discount);
$discountsum = number_format($discountsum, 2, '.', '');

$grand = $discountsum+$tamount;
$grand = number_format($grand, 2, '.', '');

if($invoice != null) {
  $invoice=array_filter($invoice);
  if(count(array_unique($invoice)) < count($invoice)) {
    $status='failed1';
  } else {
    $status='success';
  }
}

if($status != 'failed1') {
    
 $amountsum= array_sum($amount);
 
 $amt123 = floatval($amountsum);
 $amt123 = number_format($amt123, 2, '.', '');
 $amt124 = floatval($tamount);
 $amt124 = number_format($amt124, 2, '.', '');

if($amt123 > $amt124) {  $status='failed2'; }  else {

date_default_timezone_set('Asia/Dubai');
$time = date('Y-m-d H:i:s', time());
$token=$_POST["token"];

$sql = "INSERT INTO `reciept` (`token`, `customer`, `site`, `amount`, `discount`, `grand`, `pmethod`, `pdate`, `gldate`, `duedate`, `clearance_date`, `post_dated`, `ref`, `cheque_no`, `bank`, `cat`, `inward`,`rep`,`status`,`time`) 
VALUES ('$token', '$customer', '$site', '$tamount', '$discountsum', '$grand', '$pmethod', '$pdate', '$gldate', '$duedate', '$clearancedate', '$postdated', '$ref', '$checkno', '$bank', '36', '$inward', '$rep', '$status1', '$time')";

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
    
    if($invoice != null) {
    $count=sizeof($invoice);
    for($i=0;$i<$count;$i++)
    {
      $amount[$i] = ($amount[$i] != NULL) ? $amount[$i] : 0;
      $discount[$i] = ($discount[$i] != NULL) ? $discount[$i] : 0;
      $total[$i] = $amount[$i]+$discount[$i];
      
        $sql_inv_date = "SELECT `date` FROM `invoice` WHERE `id`='$invoice[$i]'";
        $query_inv_date = mysqli_query($conn,$sql_inv_date);
        $result_inv_date = mysqli_fetch_array($query_inv_date);
        $invoice_date = $result_inv_date['date'];
      
        $sql1 = "INSERT INTO `reciept_invoice` (`reciept_id`,`date`,`invoice`, `invoice_date`, `amount`, `adjust`, `total`) 
                 VALUES ('$last_id','$pdate','$invoice[$i]','$invoice_date','$amount[$i]','$discount[$i]','$total[$i]')";
        $conn->query($sql1);
        
        // SECTION FOR ADDING TO ADDITIONAL TABLE
        
            $sql_adtnl_inv = "INSERT INTO `additionalRcp`(`id`, `section`, `entry_id`, `invoice`, `invoice_date`, `date`, `cat`, `sub`, `amount`)
                              VALUES ('','RCP','$last_id','$invoice[$i]','$invoice_date','$clearancedate','65','','-$total[$i]')";
            $conn->query($sql_adtnl_inv);
            if($discount[$i] != 0) {
                $sql_adtnl_dis = "INSERT INTO `additionalAcc`(`id`, `section`, `entry_id`, `invoice`, `date`, `cat`, `sub`, `amount`)
                                  VALUES ('','DIS','$last_id','$invoice[$i]','$pdate','43','','$discount[$i]')";
                $conn->query($sql_adtnl_dis);
            }
            
        if($amount[$i]+$discount[$i] >= $balance[$i])
        {
            $sql2 = "UPDATE `invoice` SET `status`='Paid' WHERE `id`='$invoice[$i]'";
            $conn->query($sql2);
        }
        else
        {
            $sql2 = "UPDATE `invoice` SET `status`='Partially' WHERE `id`='$invoice[$i]'";
            $conn->query($sql2);
        }
     
    } }
 
} 
else 
    {
    $status="failed";
    }

} } } }
?>   
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-9">
	<?php if($status=="success") { ?>
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
      <?php } else if($status=="failed1") { ?>
          <p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed! Cannot take same invoice twice in a receipt</span>
           </a></p>
        <?php } else if($status=="failed2") { ?>
          <p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed! Payed amount is insufficient</span>
           </a></p>
        <?php } ?>
    
    
      <div class="box">
        <div class="box-header">
          <h2>Generate New Receipt</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/receipt" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-6">
                <select name="customer" id="customer" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
                              $customer=$_POST['customer'];
                              
                              $sqlcust="SELECT name from customers where id='$customer'";
                              $querycust=mysqli_query($conn,$sqlcust);
                              $fetchcust=mysqli_fetch_array($querycust);
                              $cust=$fetchcust['name'];
                    
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
               
              <div class="form-group row m-t-md">
              <div align="" class="col-sm-offset-2 col-sm-12">
                <button name="submit1" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search Invoices</button>
              </div>
              </div>
         </form>
            
             
            <?php if(isset($_POST['submit1']))
               {
               $customer=$_POST['customer'];
            ?>
            <form role="form" action="<?php echo $baseurl;?>/add/receipt" method="post">
                 <input type="hidden" name="customer" value="<?php echo $customer;?>">
                 <input type="hidden" name="token" value="<?php echo rand(1000,9999).date('Ymdhisa');?>">
            <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-4">
                  <input type="number" step="0.01" class="form-control" name="tamount" id="value" placeholder="Amount">
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
                  <div class="col-sm-2" style="text-align: right;">
                      <!--btn btn-sm btn-outline rounded b-warning text-warning-->
                      <!--<button style="margin:7px 0 0 25px;" class="btn btn-sm rounded btn-warning" id="find_sum">Find Sum</button>-->
                      <a style="margin:7px 0 0 25px;" class="btn btn-sm rounded btn-warning" id="find_sum">Find Sum</a>
                  </div>
                  <div class="col-sm-4">
                  <input id="har_sum" class="form-control" readonly>
                  </div>
              </div>
                 
                 
               <script>
                      $(document).ready(function (event) {
                      $('#inv0').change(function() {
                         var country_id = $(this).val();
                         if(country_id != "") {
                           $.ajax({
                             url:"getbalance_invoice",
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
                 
                   <?php
//                     for($i=0;$i<=49;$i++){ echo
//                         "<script type=\"text/javascript\">".
//                           "$(document).ready(function(){".
//                            "$('#sub').click(function(){".
//                            "var a=$('#amt'+$i+'').val();".
//                            "var b=$('#bal'+$i+'').val();".
//                            "var c=$('#adt'+$i+'').val();".
//                            "var d= parseFloat(a) + parseFloat(c);".
//                                "if (parseFloat(d) > parseFloat(b) ) {".
//                                "alert('Amount is more than Balance, to be paid').(d);".
//                                "return false;".
//                                "}".
//                               "});".
//                           "});".
//                           "</script>";
//                      }
                      ?>
                 
                 
            <div class="form-group row">
              <label for="name" class="col-sm-1 form-control-label">Invoice</label>
              <div class="col-sm-2">
                <select name="invoice[]" class="form-control select2" id="inv0" placeholder="Item" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT * FROM invoice WHERE `customer`='$customer' AND `status` != 'Paid'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                ?><option></option><?php
				while($row = mysqli_fetch_assoc($result)) 
				{
                                $inv=$row["id"];
                                $grand=$row["grand"];
                                $grand=($grand != NULL) ? $grand : 0;
                                $total=$grand;
                                $sqlsum="SELECT sum(amount) AS amt,sum(adjust) AS adt FROM reciept_invoice WHERE invoice='$inv'";
                                $resultsum = mysqli_query($conn, $sqlsum);
                                $rowsum = mysqli_fetch_assoc($resultsum);
                                $amt=$rowsum["amt"];
                                $amt=($amt != NULL) ? $amt : 0;
                                $adt=$rowsum["adt"];
                                $adt=($adt != NULL) ? $adt : 0;
                                
                                $sqlcdt="SELECT sum(total) AS tl FROM credit_note WHERE invoice='$inv'";
                                $resultcdt = mysqli_query($conn, $sqlcdt);
                                $rowcdt = mysqli_fetch_assoc($resultcdt);
                                $credit=$rowcdt["tl"];
                                $credit=($credit != NULL) ? $credit : 0;
                                
                                $amnt = $amt+$adt+$credit;
                                
                                $bal = $total-$amnt;
                                    $bal = ($bal != NULL) ? $bal : 0;
                                if($bal != 0) {
				?>
				<option value="<?php echo $row["id"]; ?>">INV|<?php echo sprintf('%06d',$row["id"]);?></option>
				<?php 
                                }}} 
				?>
                </select>
               
              </div>
              <div class="col-sm-3">
                   <input type="number" class="form-control" name="balance[]" id="bal0" placeholder="Balance" readonly>
              </div>
              <div class="col-sm-3">
                   <input type="number" step=".01" min="0" class="form-control bin_sum" name="amount[]" id="amt0" placeholder="Amount">
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
<select class="form-control" required name="invoice[]" id="inv'+uid+'">\n\
\n\
<?php
   $sql = "SELECT * FROM invoice WHERE `customer`='$customer' AND `status` != 'Paid'";
   $result = mysqli_query($conn, $sql);
   if (mysqli_num_rows($result) > 0)
   {
   ?><option></option><?php
   while($row = mysqli_fetch_assoc($result)) 
   {
          $inv=$row["id"];
          $grand=$row["grand"];
          $grand=($grand != NULL) ? $grand : 0;
          $total=$grand;
          $sqlsum="SELECT sum(amount) AS amt,sum(adjust) AS adt FROM reciept_invoice WHERE invoice='$inv'";
          $resultsum = mysqli_query($conn, $sqlsum);
          $rowsum = mysqli_fetch_assoc($resultsum);
          $amt=$rowsum["amt"];
          $amt=($amt != NULL) ? $amt : 0;
          $adt=$rowsum["adt"];
          $adt=($adt != NULL) ? $adt : 0;
          $sqlcdt="SELECT sum(total) AS tl FROM credit_note WHERE invoice='$inv'";
          $resultcdt = mysqli_query($conn, $sqlcdt);
          $rowcdt = mysqli_fetch_assoc($resultcdt);
          $credit=$rowcdt["tl"];
          $credit=($credit != NULL) ? $credit : 0;
          $amnt=$amt+$adt+$credit;
          $bal=$total-$amnt;
          if($bal != 0) {
   ?><option value="<?php echo $row["id"];?>">INV|<?php echo sprintf("%06d",$row["id"]);?></option><?php     
   }
   }   
   }
?></select></div>\n\
<div class="col-sm-3"><input type="number" class="form-control" name="balance[]" id="bal'+uid+'" placeholder="Balance" readonly></div>\n\
<div class="col-sm-3"><input type="number" step=".01" min="0" class="form-control bin_sum" name="amount[]" id="amt'+uid+'" placeholder="Amount"></div>\n\
<div class="col-sm-2"><input type="number" step=".01" min="0" class="form-control" name="discount[]" value="0" id="adt'+uid+'" placeholder="Discount"></div>\n\
     <div class="box-tools">\n\
     <a href="javascript:void(0);"  class="btn btn-danger btn-sm btnRemoveMoreSpecification" data-original-title="Add More">\n\
     <i class="fa fa-times"></i>\n\
     </a>\n\
     </div>\n\
</div>';
     
  
          
     if(uid<=99)
     {
     $('#divSpecificatiion').append(row);
     }
     
     <?php for($i=1;$i<=99;$i++){ echo       
                 "$(document).ready(function (event) {".
                     " $('#inv'+$i+'').change(function() {".
                         "var country_id = $(this).val();".
                         "if(country_id != '') {".
                           "$.ajax({".
                             "url:'getbalance_invoice',".
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
<script>
    // $( ".bin_sum").keyup(function() {
    $( "#find_sum").click(function() {
        
        var sum=0;
        
        $('.bin_sum').each(function(){
        sum+=Number($(this).val());
        });
        
        $('#har_sum').val(sum);
        
    });
</script>

<?php include "../includes/footer.php";?>