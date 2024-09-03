<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$id=$_POST["id"];
$tamount=$_POST["tamount"];

$invoice=$_POST["invoice"];
$amount=$_POST["amount"];
$discount=$_POST["discount"];
$discountsum= array_sum($discount);
$discountsum = number_format($discountsum, 2, '.', '');

$invoice=array_filter($invoice);
if(count(array_unique($invoice))<count($invoice)) { $status='failed1'; } else {    
$amountsum= array_sum($amount);


if($amountsum > $tamount) {  $status='failed2'; }  else {
 
    $count=sizeof($invoice);
    for($i=0;$i<$count;$i++)
    {
    $total[$i] = $amount[$i]+$discount[$i];
    $sql1 = "INSERT INTO `reciept_invoice` (`reciept_id`,`invoice`, `amount`, `adjust`, `total`) 
VALUES ('$id','$invoice[$i]', '$amount[$i]', '$discount[$i]', '$total[$i]')";
     $conn->query($sql1); 
    }
    $sql2 = "UPDATE reciept SET discount =discount+'$discountsum',grand =grand+'$discountsum' WHERE id=$id";
    $conn->query($sql2);

    $status="success";
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="RPT".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
    

}}}

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
     $sqlinv = "SELECT sum(total) AS total FROM reciept_invoice WHERE reciept_id=$receipt";
     $queryinv = mysqli_query($conn,$sqlinv);
     $fetchinv = mysqli_fetch_assoc($queryinv);
     $total1 = $fetchinv['total'];
     
     $bal1 = $amount - $total1;
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
		  <span class="text-danger">Your Submission was Failed! Payed Amount and invoiced amount doesn't match</span>
           </a></p>
        <?php } ?>
    
    
      <div class="box">
        <div class="box-header">
          <h2>Generate New Receipt</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/receipt_advance_invoice" method="post">
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
                   <input type="text" class="form-control" name="tamount" value="<?php echo $bal1; ?>" readonly placeholder="Amount">
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
                 
                 
            <div class="form-group row">
              <label for="name" class="col-sm-1 form-control-label">Invoice</label>
              <div class="col-sm-2">
                <select name="invoice[]" class="form-control select2" id="inv0" placeholder="Item" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT * FROM invoice WHERE customer='$customer'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                ?><option></option><?php
				while($row = mysqli_fetch_assoc($result)) 
				{
                                $inv=$row["id"];
                                $grand=$row["grand"];
                                $total=$grand;
                                $sqlsum="SELECT sum(amount) AS amt,sum(adjust) AS adt FROM reciept_invoice WHERE invoice='$inv'";
                                $resultsum = mysqli_query($conn, $sqlsum);
                                $rowsum = mysqli_fetch_assoc($resultsum);
                                $amt=$rowsum["amt"];
                                $adt=$rowsum["adt"];
                                
                                $sqlcdt="SELECT sum(total) AS tl FROM credit_note WHERE invoice='$inv'";
                                $resultcdt = mysqli_query($conn, $sqlcdt);
                                $rowcdt = mysqli_fetch_assoc($resultcdt);
                                $credit=$rowcdt["tl"];
                                
                                $amnt=$amt+$adt+$credit;
                                
                                $bal=$total-$amnt;
                                if($bal>1){
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
   $sql = "SELECT * FROM invoice WHERE customer=$customer";
   $result = mysqli_query($conn, $sql);
   if (mysqli_num_rows($result) > 0) 
   {
   ?><option></option><?php
   while($row = mysqli_fetch_assoc($result)) 
   {
          $inv=$row["id"];
          $grand=$row["grand"];
          $total=$grand;
          $sqlsum="SELECT sum(amount) AS amt,sum(adjust) AS adt FROM reciept_invoice WHERE invoice='$inv'";
          $resultsum = mysqli_query($conn, $sqlsum);
          $rowsum = mysqli_fetch_assoc($resultsum);
          $amt=$rowsum["amt"];
          $adt=$rowsum["adt"];
          $sqlcdt="SELECT sum(total) AS tl FROM credit_note WHERE invoice='$inv'";
          $resultcdt = mysqli_query($conn, $sqlcdt);
          $rowcdt = mysqli_fetch_assoc($resultcdt);
          $credit=$rowcdt["tl"];
          $amnt=$amt+$adt+$credit;
          $bal=$total-$amnt;
          if($bal>1){
   ?><option value="<?php echo $row["id"];?>">INV|<?php echo sprintf("%06d",$row["id"]);?></option><?php     
   }
   }     
   }
?></select></div>\n\
<div class="col-sm-3"><input type="number" class="form-control" name="balance[]" id="bal'+uid+'" placeholder="Balance" readonly></div>\n\
<div class="col-sm-3"><input type="number" step=".01" min="1" class="form-control" name="amount[]" id="amt'+uid+'" placeholder="Amount"></div>\n\
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

<?php include "../includes/footer.php";?>