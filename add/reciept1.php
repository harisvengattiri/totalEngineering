<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$customer=$_POST["customer"];
$site=$_POST["site"];
$tamount=$_POST["tamount"];
$pmethod=$_POST["pmethod"];
$pdate=$_POST["pdate"];
$gldate=$_POST["gldate"];
$duedate=$_POST["duedate"];
$clearancedate=$_POST["clearancedate"];
$postdated=$_POST["postdated"];
$ref=$_POST["ref"];
$checkno=$_POST["checkno"];
$bank=$_POST["bank"];
$ball=$_POST["ball"];
print_r($ball);
$sql = "INSERT INTO `reciept` (`customer`, `site`, `amount`, `pmethod`, `pdate`, `gldate`, `duedate`, `clearance_date`, `post_dated`, `ref`, `cheque_no`, `bank`) 
VALUES ('$customer', '$site', '$tamount', '$pmethod', '$pdate', '$gldate', '$duedate', '$clearancedate', '$postdated', '$ref', '$checkno', '$bank')";

if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
    $invoice=$_POST["invoice"];
    $amount=$_POST["amount"];
    $adjust=$_POST["adjust"];
    
    $count=sizeof($invoice);
    for($i=0;$i<$count;$i++)
    {
    $sql1 = "INSERT INTO `reciept_invoice` (`reciept_id`,`invoice`, `amount`, `adjust`) 
VALUES ('$last_id','$invoice[$i]', '$amount[$i]', '$adjust[$i]')";
     $conn->query($sql1);
     
     
    }
    
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="RPT".$last_id;
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
    <div class="col-md-7">
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
          <h2>Generate New Reciept</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/reciept" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-6">
                <select name="customer" id="customer" class="form-control">
                  <?php 
                              $customer=$_POST['customer'];
                              $site=$_POST['site'];
                              
                              $sqlcust="SELECT name from customers where id='$customer'";
                              $querycust=mysqli_query($conn,$sqlcust);
                              $fetchcust=mysqli_fetch_array($querycust);
                              $cust=$fetchcust['name'];
                              
                              $sqlsite="SELECT p_name from customer_site where id='$site'";
                              $querysite=mysqli_query($conn,$sqlsite);
                              $fetchsite=mysqli_fetch_array($querysite);
                              $site1=$fetchsite['p_name'];
                    
                                $sql="SELECT customer AS c FROM invoice JOIN customers ON invoice.customer = customers.id
                                             GROUP BY invoice.customer ORDER BY customers.name ";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				?> <option value="<?php echo $customer;?>"><?php echo $cust;?></option> <?php
				while($row = mysqli_fetch_assoc($result)) 
				{
                                   $cst=$row["c"];
                                        $sqlcust="SELECT name from customers where id='$cst'";
                                        $querycust=mysqli_query($conn,$sqlcust);
                                        $fetchcust=mysqli_fetch_array($querycust);
                                        $cust=$fetchcust['name'];
				?>
				<option value="<?php echo $cst; ?>"><?php echo $cust;?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="type" align="left" class="col-sm-2 form-control-label">Customer Site</label>
              <div class="col-sm-6">
                   <select class="form-control" name="site" id="site">
                        <option value="<?php echo $site;?>"><?php echo $site1;?></option>
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
               $site=$_POST['site'];
            ?>
            <form role="form" action="<?php echo $baseurl;?>/add/reciept" method="post">
                 <input type="hidden" name="customer" value="<?php echo $customer;?>">
                 <input type="hidden" name="site"  value="<?php echo $site;?>">
            <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-4">
                  <input type="text" class="form-control" name="tamount" id="value" placeholder="Amount">
              </div>
               <label for="type" class="col-sm-2 form-control-label">Payment Method</label>
              <div class="col-sm-4">
                  <select name="pmethod" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                    <option value="">Payment Method</option>
                    <option value="cash">Cash</option>
                    <option value="cheque">Cheque</option>
                </select>
              </div>
            </div>   
            
            <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Payment Date</label>
               <?php
               $today = date('d/m/Y');
               ?>
              <div class="col-sm-4">
                  <input type="text" name="pdate" value="<?php echo $today;?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <label for="type" class="col-sm-2 form-control-label">Post Dated </label>
               
               <div class="col-sm-1">
               <input style="margin-top: 10px;" type="checkbox" class="form-control" name="postdated" value="1"></span>
               </div>
               
              <label for="type" align="right" class="col-sm-2 form-control-label">Ref No:</label>
              <div class="col-sm-2">
              <input type="text" class="form-control" name="ref">    
              </div>
              <label for="type" align="right" class="col-sm-2 form-control-label">Check No:</label>
              <div class="col-sm-3">
              <input type="text" class="form-control" name="checkno">    
              </div>
              </div>
               
              <div class="form-group row">
              <label for="type" align="left" class="col-sm-2 form-control-label">Bank</label>
              <div class="col-sm-6">
                   <select class="form-control" name="bank">
                        <option>Bank 1</option>
                        <option>Bank 2</option>
                        <option>Bank 2</option>
                   </select>
              </div>
              </div>
               
            <div class="form-group row">
              <label for="name" class="col-sm-1 form-control-label">Invoice</label>
              <div class="col-sm-3">
                <select name="invoice[]" class="form-control select2" id="inv100" placeholder="Item" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT * FROM invoice WHERE customer='$customer' AND site='$site' AND flag=''";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                ?><option></option><?php
				while($row = mysqli_fetch_assoc($result)) 
				{
                                $inv=$row["id"];
                                $grand=$row["grand"];
                                $sqlsum="SELECT sum(amount) AS amt,sum(adjust) AS adt FROM reciept_invoice WHERE invoice='$inv'";
                                $resultsum = mysqli_query($conn, $sqlsum);
                                $rowsum = mysqli_fetch_assoc($resultsum);
                                $amt=$rowsum["amt"];
                                $adt=$rowsum["adt"];
                                $amnt=$amt+$adt;
                                $bal=$grand-$amnt;
                                if($bal>0){
				?>
				<option value="<?php echo $row["id"]; ?>">INV|<?php echo sprintf('%06d',$row["id"]);?> || BAL=<?php echo custom_money_format('%!i', $bal);?></option>
				<?php 
                                }}} 
				?>
                </select>
               
              </div>
              <div class="col-sm-2">
                   <input type="number" step=".01" class="form-control" name="balance[]" id="bal100" placeholder="Balance">
              </div>
              <div class="col-sm-3">
                   <input type="number" step=".01" class="form-control" name="amount[]" id="amt100" placeholder="Amount">
              </div>
              <div class="col-sm-2">
                   <input type="number" step=".01" class="form-control" name="adjust[]" id="adt100" placeholder="Adjust">
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
                <button name="submit"type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>
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
<div class="col-sm-3">\n\
<select class="form-control" id="inv'+uid+'">\n\
\n\<?php
   $sql = "SELECT * FROM invoice WHERE customer=$customer AND site=$site";
   $result = mysqli_query($conn, $sql);
   if (mysqli_num_rows($result) > 0) 
   {
   ?><option></option><?php
   while($row = mysqli_fetch_assoc($result)) 
   {
   $inv=$row["id"];
   $grand=$row["grand"];
   $sqlsum="SELECT sum(amount) AS amt,sum(adjust) AS adt FROM reciept_invoice WHERE invoice=$inv";
   $resultsum = mysqli_query($conn, $sqlsum);
   $rowsum = mysqli_fetch_assoc($resultsum);
   $amt=$rowsum["amt"];
   $adt=$rowsum["adt"];
   $amnt=$amt+$adt;
   $bal=$grand-$amnt;
   if($bal>0){
   ?><option value="<?php echo $row["id"];?>">INV|<?php echo sprintf("%06d",$row["id"]);?> || BAL=<?php echo custom_money_format("%!i", $bal);?></option><?php     
   }
   }     
   }
?></select></div>\n\
<div class="col-sm-2"><input type="text" class="form-control" name="balance[]" id="bal'+uid+'" placeholder="Balance"></div>\n\
<div class="col-sm-3"><input type="number" step=".01" class="form-control" name="amount[]" id="amt'+uid+'" placeholder="Amount"></div>\n\
<div class="col-sm-2"><input type="number" step=".01" class="form-control" name="adjust[]" id="adt'+uid+'" placeholder="Adjust"></div>\n\
     <div class="box-tools">\n\
     <a href="javascript:void(0);"  class="btn btn-danger btn-sm btnRemoveMoreSpecification" data-original-title="Add More">\n\
     <i class="fa fa-times"></i>\n\
     </a>\n\
     </div>\n\
</div>';
             
          
     if(uid<=10){
     $('#divSpecificatiion').append(row);
     
     for(i=0;i<=10;i++)
         {
               $('#inv'+i+'').change(function() {
                var country_id = $(this).val();
                if(country_id != "") {
                  $.ajax({
                    url:"getbalance_invoive",
                    data:{c_id:country_id},
                    type:'POST',
                    success:function(response) {
                      var resp = $.trim(response);
                      var inv='#inv'+i+'';
                      var bal='#bal'+i+'';
                      alert(inv);
                      alert(bal);
                    }
                  });
                }
              });
         }
     }
       
      
     uid++;uvd++;
     });
     $(document).on('click', '.btnRemoveMoreSpecification', function () {
          $(this).parent('div').parent('div').remove();
     });
     
           
     
});
</script>

<?php include "../includes/footer.php";?>