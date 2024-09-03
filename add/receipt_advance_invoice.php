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
$tamount=$_POST["tamount"];
$tamount=($tamount != NULL) ? $tamount : 0;
$pmethod=$_POST["pmethod"];
$pmethod1=$_POST["pmethod1"];
$clearancedate=$_POST["clearancedate"];
$duedate=$_POST["duedate"];
$pdate=$_POST["pdate"];
$status1=$_POST["status1"];
$inward=$_POST["inward"];
$ref=$_POST["ref"];
$cheque_no=$_POST["cheque_no"];
        
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

if($pmethod != $pmethod1)
     {
     if($pmethod == 'cheque'){ $clearancedate=''; $status1='Uncleared';}
     }



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
$amountsum = number_format($amountsum, 2, '.', '');

$sql_rfd = "SELECT sum(`amount`) as rfd FROM `refund` WHERE rcp = $id";
$query_rfd=mysqli_query($conn,$sql_rfd);
$fetch_rfd=mysqli_fetch_array($query_rfd);
$rfd = $fetch_rfd['rfd'];
$rfd = ($rfd != NULL) ? $rfd : 0;

$tot_amount = $tamount-$rfd;

if($amountsum > $tot_amount) {  $status='failed2'; }  else {
 
    $sqldelete = "DELETE FROM reciept_invoice WHERE reciept_id=$id";
    $querydelete = mysqli_query($conn,$sqldelete);
    
   // SECTION FOR DELETEING FROM ADDITIONAL TABLES
   $sql_del_additionalRcp = "DELETE FROM `additionalRcp` WHERE `section`='RCP' AND `entry_id`='$id'";
   $query_del_additionalRcp = mysqli_query($conn,$sql_del_additionalRcp);
   
   $sql_del_additionalAcc = "DELETE FROM `additionalAcc` WHERE `section`='DIS' AND `entry_id`='$id'";
   $query_del_additionalAcc = mysqli_query($conn,$sql_del_additionalAcc);
     
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
             VALUES ('$id','$pdate','$invoice[$i]', '$invoice_date', '$amount[$i]', '$discount[$i]', '$total[$i]')";
     $conn->query($sql1);
     
        // SECTION FOR ADDING TO ADDITIONAL TABLE
        
            $sql_adtnl_inv = "INSERT INTO `additionalRcp`(`id`, `section`, `entry_id`, `invoice`, `invoice_date`, `date`, `cat`, `sub`, `amount`)
                              VALUES ('','RCP','$id','$invoice[$i]','$invoice_date','$clearancedate','65','','-$total[$i]')";
            $conn->query($sql_adtnl_inv);
            if($discount[$i] != 0) {
                $sql_adtnl_dis = "INSERT INTO `additionalAcc`(`id`, `section`, `entry_id`, `invoice`, `date`, `cat`, `sub`, `amount`)
                                  VALUES ('','DIS','$id','$invoice[$i]','$pdate','43','','$discount[$i]')";
                $conn->query($sql_adtnl_dis);
            }
     

        // FINDING STATUS OF EACH INVOICE

        $sql_status_inv = "SELECT `grand` FROM `invoice` WHERE `id`='$invoice[$i]'";
        $result_status_inv = $conn->query($sql_status_inv);
        $row_status_inv = $result_status_inv->fetch_assoc();
        $grand_inv = $row_status_inv["grand"];
        $grand_inv = ($grand_inv != NULL) ? $grand_inv : 0;
        
        $sql_status_rcp = "SELECT sum(total) AS total FROM `reciept_invoice` WHERE `invoice`='$invoice[$i]'";
        $result_status_rcp = $conn->query($sql_status_rcp);
        $row_status_rcp = $result_status_rcp->fetch_assoc();
        $total_rcp = $row_status_rcp["total"];
        $total_rcp = ($total_rcp != NULL) ? $total_rcp : 0;
            
        $sql_status_cdt = "SELECT sum(total) AS tl FROM `credit_note` WHERE `invoice`='$invoice[$i]'";
        $result_status_cdt = mysqli_query($conn, $sql_status_cdt);
        $row_status_cdt = mysqli_fetch_assoc($result_status_cdt);
        $credited_amt = $row_status_cdt["tl"];
        $credited_amt = ($credited_amt != NULL) ? $credited_amt : 0;

        $current_balance = ($grand_inv)-($total_rcp+$credited_amt);

        if($current_balance <= 0)
        {
            $sql22 = "UPDATE `invoice` SET `status`='Paid' WHERE `id`='$invoice[$i]'";
            $conn->query($sql22);
        }
        else
        {
            $sql22 = "UPDATE `invoice` SET `status`='Partially' WHERE `id`='$invoice[$i]'";
            $conn->query($sql22);
        }
     
    } }
    $sql2 = "UPDATE reciept SET amount='$tamount',pmethod='$pmethod',discount='$discountsum',grand='$grand',clearance_date = '$clearancedate',duedate = '$duedate',ref = '$ref',
            cheque_no = '$cheque_no',cat = '36',inward = '$inward',status = '$status1' WHERE id=$id";
    $conn->query($sql2);

       $status="success";
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="RPT".$id;
       $query=mysqli_real_escape_string($conn, $sql2);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
    

}}}}

if($_GET['id'])
{
     $receipt = $_GET['id'];
     
     $sqlrcp = "SELECT * FROM reciept where id=$receipt";
     $queryrcp = mysqli_query($conn,$sqlrcp);
     $fetchrcp = mysqli_fetch_assoc($queryrcp);
     
     $customer = $fetchrcp['customer'];
             $sqlcust="SELECT name FROM customers where id='$customer'";
             $querycust=mysqli_query($conn,$sqlcust);
             $fetchcust=mysqli_fetch_array($querycust);
             $cust=$fetchcust['name'];
     
     $amount = $fetchrcp['amount'];
     $amount = ($amount != NULL) ? $amount : 0;
     
     $pmethod = $fetchrcp['pmethod'];
     $inward = $fetchrcp['inward'];
        $sqlbank="SELECT `category` FROM `expense_subcategories` where id='$inward'";
        $querybank=mysqli_query($conn,$sqlbank);
        $fetchbank=mysqli_fetch_array($querybank);
        $inward_bank=$fetchbank['category'];
        
     $duedate=$fetchrcp["duedate"];
     $pdate=$fetchrcp["pdate"];
     $clearance_date=$fetchrcp["clearance_date"];
     $status1=$fetchrcp["status"];
     $ref=$fetchrcp["ref"];
     $cheque_no=$fetchrcp["cheque_no"];
     
     
     $sqlinv = "SELECT sum(total) AS total FROM reciept_invoice WHERE reciept_id=$receipt";
     $queryinv = mysqli_query($conn,$sqlinv);
     $fetchinv = mysqli_fetch_assoc($queryinv);
     $total = $fetchinv['total'];
     $total = ($total != NULL) ? $total : 0;
}


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
		  <span class="text-danger">Your Submission was Failed! Payed amount is insufficient</span>
           </a></p>
        <?php } ?>
    
    
      <div class="box">
        <div class="box-header">
          <h2>Assign Invoices to Receipt</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/receipt_advance_invoice?id=<?php echo $receipt;?>" method="post">
               <input type="hidden" name="id" value="<?php echo $receipt;?>">
          
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-6">
                <select name="customer" id="customer" class="form-control">
                    <option value="<?php echo $customer; ?>"><?php echo $cust;?></option>	
                </select>
              </div>
            </div>
<!--            <div class="form-group row">
              <label for="type" align="left" class="col-sm-2 form-control-label">Customer Site</label>
              <div class="col-sm-6">
                   <select class="form-control" name="site" id="site">
                        <option value="<?php // echo $site;?>"><?php // echo $site1;?></option>
                   </select>
              </div>
            </div>-->
               

            <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-4">
                   <input type="text" class="form-control" name="tamount" value="<?php echo $amount;?>" placeholder="Amount">
              </div>
              <label align="right" class="col-sm-2 form-control-label">Cheque No:</label>
              <div class="col-sm-4">
                  <input type="text" class="form-control" name="cheque_no" value="<?php echo $cheque_no;?>">    
              </div>
            </div>
            <div class="form-group row">
                        <label for="type" align="" class="col-sm-2 form-control-label">Payment Method</label>
                        <div class="col-sm-4">
                            <select name="pmethod" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                              <option value="<?php echo $pmethod;?>"><?php echo $pmethod;?></option>
                              <option value="cash">Cash</option>
                              <option value="cheque">Cheque</option>
                              <option value="card">Card</option>
                            </select>
                        </div>
                        <input type="hidden" name="pmethod1" value="<?php echo $pmethod;?>">
                        <input type="hidden" name="status1" value="<?php echo $status1;?>">
                        
                        <label for="type" align="right" class="col-sm-2 form-control-label">Inwarded To</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="inward">
                            <option value="<?php echo $inward;?>"><?php echo $inward_bank;?></option>
                            <?php     
                            $sql = "SELECT id,category FROM `expense_subcategories` WHERE parent='36'";
            				$result = mysqli_query($conn, $sql);
            				    if (mysqli_num_rows($result) > 0) 
            				    {
                                    while($row = mysqli_fetch_assoc($result)) 
            				        {
                                    ?><option value="<?php echo $row["id"]; ?>"><?php echo $row["category"];?></option><?php
                                    }
                                }
                               ?>             
                            </select>
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
            
            <div class="form-group row">
                <label for="type" align="" class="col-sm-2 form-control-label">Ref No:</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="ref" value="<?php echo $ref;?>">    
                </div>
                <label for="type" align="right" class="col-sm-2 form-control-label">Due Date</label>
               <input type="hidden" name="pdate" value="<?php echo $pdate;?>"> 
              <div class="col-sm-4">
                  <input type="text" name="duedate" value="<?php echo $duedate;?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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

               <label for="type" align="" class="col-sm-2 form-control-label">Clearance Date</label>
              <div class="col-sm-4">
                  <input type="text" name="clearancedate" value="<?php echo $clearance_date;?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              
                <div class="col-sm-2" style="text-align: right;">
                    <a style="margin:7px 0 0 25px;" class="btn btn-sm rounded btn-warning" id="find_sum">Find Sum</a>
                </div>
                <div class="col-sm-4">
                    <input id="har_sum" class="form-control" readonly>
                </div>
              
            </div> 
                 
                 
                   <?php
//                     for($i=0;$i<=29;$i++){ echo
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
                 
             
           <?php 
               $sql1="SELECT * FROM reciept_invoice where reciept_id='$receipt'";
               $result1=$conn->query($sql1);
               $count = 0;
               if(mysqli_num_rows($result1) == 0) { ?>
                   
              <div class="form-group row">
              <label for="name" class="col-sm-1 form-control-label">Invoice</label>
              <div class="col-sm-2">
                <select name="invoice[]" class="form-control select2" id="inv0" placeholder="Item" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT * FROM invoice WHERE customer='$customer' AND `status` != 'Paid'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
        ?><option></option><?php
				while($row = mysqli_fetch_assoc($result)) 
				{
                                $inv=$row["id"];
                                $grand=$row["grand"];
                                $grand=($grand != NULL) ? $grand : 0;
                                $fair=$row["transport"];
                                $fair=($fair != NULL) ? $fair : 0;

                                $total = $grand + $fair;

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
                                
                                $amnt = $amt + $adt + $credit;
                                $bal = $total-$amnt;
                                if($bal != 0) {
				?>
				<option value="<?php echo $row["id"]; ?>">INV|<?php echo sprintf('%06d',$row["id"]);?></option>
				<?php 
          } } }
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
                    
                    
               <?php } else {
               while($row1 = mysqli_fetch_assoc($result1)) {
                         $invoice=$row1["invoice"];
                         $amount=$row1["amount"];
                         $amount=($amount != NULL) ? $amount : 0;
                         $adjust=$row1["adjust"];
                         $adjust=($adjust != NULL) ? $adjust : 0;
            ?>      
                 
                 
            <div class="form-group row">
              <label for="name" class="col-sm-1 form-control-label">Invoice</label>
              <div class="col-sm-2">
                <input type="text" name="invoice[]" class="form-control" value="<?php echo $invoice;?>" readonly>
              </div>
              
              <div class="col-sm-3">
                   <?php 
				$sql = "SELECT * FROM invoice WHERE id='$invoice'";
				$result = mysqli_query($conn, $sql);
				while($row = mysqli_fetch_assoc($result)) 
				{
                                $grand=$row["grand"];
                                $grand=($grand != NULL) ? $grand : 0;
                                $sqlsum="SELECT sum(total) AS total FROM reciept_invoice WHERE invoice='$invoice'";
                                $resultsum = mysqli_query($conn, $sqlsum);
                                $rowsum = mysqli_fetch_assoc($resultsum);
                                $total=$rowsum["total"];
                                $total=($total != NULL) ? $total : 0;
                                $sqlcdt="SELECT sum(total) AS tl FROM credit_note WHERE invoice='$invoice'";
                                $resultcdt = mysqli_query($conn, $sqlcdt);
                                $rowcdt = mysqli_fetch_assoc($resultcdt);
                                $credit=$rowcdt["tl"];
                                $credit=($credit != NULL) ? $credit : 0;

                                $amnt = $total+$credit;
                                $bal = $grand-$amnt;
                                $bal = number_format($bal, 2, '.', '');
                                }
		   ?>
                   <input type="text" name="balance[]" class="form-control" value="<?php echo $bal;?>" readonly>
              </div>
              <div class="col-sm-3">
                   <input type="number" name="amount[]" step=".01" class="form-control bin_sum" value="<?php echo $amount;?>">
              </div>
              <div class="col-sm-2">
                   <input type="number" name="discount[]" step=".01" class="form-control" value="<?php echo $adjust;?>">
              </div>
              
	   <?php if($count=='0') { ?>
                    <div class="box-tools">
                              <a href="javascript:void(0);" 
                                 class="btn btn-info btn-sm" id="btnAddMoreSpecification" data-original-title="Add More">
                                   <i class="fa fa-plus"></i>
                              </a>
                    </div>
           <?php } else { ?>         
                    <!-- <div class="box-tools">
                              <a href="javascript:void(0);"  class="btn btn-danger btn-sm btnRemoveMoreSpecification" data-original-title="Add More">
                              <i class="fa fa-times"></i>
                              </a>
                    </div> -->
          <?php } ?>	  
            </div>
               <?php $count++; } } ?>     
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

<!-- ############ PAGE END -->

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
\n\
<?php
   $sql = "SELECT * FROM invoice WHERE `customer`=$customer AND `status` != 'Paid'";
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
    $( "#find_sum").click(function() {
        
        var sum=0;
        
        $('.bin_sum').each(function(){
        sum+=Number($(this).val());
        });
        
        $('#har_sum').val(sum);
        
    });
</script>
<?php include "../includes/footer.php";?>