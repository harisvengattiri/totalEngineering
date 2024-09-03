<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
$employee=$_POST["employee"];
$fdate=$_POST["fdate"];
$tdate=$_POST["tdate"];
$pdate=$_POST["pdate"];
$attention=$_POST["attention"];
$frequency=$_POST["frequency"];

$component=$_POST["component"];
$component1=$_POST["component1"];

$amount=$_POST["amount"];
$amount1=$_POST["amount1"];

$sql = "INSERT INTO `pay_role` (`employee`, `fdate`, `tdate`, `pdate`,`attention`,`frequency`) 
VALUES ('$employee', '$fdate', '$tdate', '$pdate','$attention','$frequency')";
if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
    $count=sizeof($amount);
    $amount_sum=0;
    for($i=0;$i<$count;$i++)
    {
    if($amount[$i] != 0){
     $amount_sum = $amount_sum + $amount[$i];   
     $sql1 = "INSERT INTO `pay_role_item` (`pay_role`, `employee`, `pdate`, `component`, `amount`, `type`) 
VALUES ('$last_id', '$employee', '$pdate', '$component[$i]', '$amount[$i]', '0')";
     $conn->query($sql1);
    }}
    $count1=sizeof($amount1);
    $amount_sum1=0;
    for($i=0;$i<$count1;$i++)
    {
    if($amount1[$i] != 0){
     $amount_sum1 = $amount_sum1 + $amount1[$i];   
     $sql1 = "INSERT INTO `pay_role_item` (`pay_role`, `employee`, `pdate`, `component`, `amount`, `type`) 
VALUES ('$last_id', '$employee', '$pdate', '$component1[$i]', '$amount1[$i]', '1')";
     $conn->query($sql1);
    }}
    $total = $amount_sum - $amount_sum1;
    $sql_total = "UPDATE pay_role SET earning=$amount_sum,deduction=$amount_sum1,total=$total WHERE id=$last_id";
    $conn->query($sql_total);
    
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="PRL".$last_id;
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
          <h2>Add New Pay Role</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/pay_role" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Staff</label>
              <div class="col-sm-4">
                <select name="employee" id="customer" required class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                     <!--<select name="items" id="staff" placeholder="Staff" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">-->
                  <?php 
				$sql = "SELECT name,id,type FROM customers where type !='Company' AND type !='Supplier' AND type !='Bank' order by name";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				?><option value=""> </option><?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
              
             
              <label for="date" align="right"  class="col-sm-2 form-control-label">Payment Date</label>
              <div class="col-sm-4">
             <?php
              $today = date('d/m/Y');
              ?>
                   <input type="text" name="pdate" value="<?php echo $today;?>" required id="date" placeholder="Payment Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <label for="type" class="col-sm-2 form-control-label">Attention</label>
              <div class="col-sm-10">
                   <input class="form-control" type="text" name="attention">
                   <!--<select class="form-control" name="site" id="site"></select>-->
              </div>
              </div>
               
               
<!--              <br>
              <h5>Salary Slip Based on Timesheet</h5> 
              <div class="form-group row"> 
              <label for="date" align=""  class="col-sm-2 form-control-label">Start Date</label>
              <div class="col-sm-4">
                <input type="text" name="fdate" id="date" placeholder="Start Date" required class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              
              <label for="date" align="right"  class="col-sm-2 form-control-label">End Date</label>
              <div class="col-sm-4">
             <?php
              $today = date('d/m/Y');
              ?>
                <input type="text" name="tdate" value="<?php echo $today;?>" id="date" placeholder="End Date" required class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
            </div>-->
               
            <!--<div class="form-group row">-->
            <!--  <label for="name" class="col-sm-2 form-control-label">Payroll Frequency</label>-->
            <!--  <div class="col-sm-4">-->
            <!--    <select name="frequency" id="customer" required class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">-->
            <!--         <option>Monthly</option>-->
            <!--         <option>Daily</option>-->
            <!--    </select>-->
            <!--  </div> -->
            <!--</div>-->
               
               
               
               <br>
               <h5>EARNING & DEDUCTION</h5>              
               <div class="form-group row">
                    <div class="col-sm-6" align="center"><b>EARNING</b></div>
                    <div class="col-sm-6" align="center"><b>DEDUCTION</b></div>
               </div>
            <div class="form-group row">
             
              <label for="name" class="col-sm-1 form-control-label">Component</label>
              <div class="col-sm-2">
                <select name="component[]" class="form-control" placeholder="Component">
                     <option value="1">Salary</option>
                     <option value="2">Loan</option>
                     <option value="3">Commission</option>
                     <option value="4">PF</option>
                </select>
              </div>
              <div class="col-sm-2">
                   <input type="number" min="0" step="any" class="form-control amount" value="0" name="amount[]" placeholder="Amount">
              </div>
                        <div class="col-sm-1">
			<div class="box-tools">
                              <a href="javascript:void(0);" 
                                 class="btn btn-info btn-sm" id="btnAddMoreSpecification" data-original-title="Add More">
                                   <i class="fa fa-plus"></i>
                              </a>
                         </div>
                        </div>

              <label for="name" align="right" class="col-sm-1 form-control-label">Component</label>
              <div class="col-sm-2">
                <select name="component1[]" class="form-control" placeholder="Component">
                     <option value="11">Leave</option>
                     <option value="12">Loan</option>
                </select>
              </div>
              <div class="col-sm-2">
                   <input type="number" min="0" step="any" class="form-control amount1" value="0" name="amount1[]" placeholder="Amount">
              </div>
                        <div class="col-sm-1">
			<div class="box-tools">
                              <a href="javascript:void(0);" 
                                 class="btn btn-info btn-sm" id="btnAddMoreSpecification1" data-original-title="Add More">
                                   <i class="fa fa-plus"></i>
                              </a>
                         </div>	
                        </div>
       </div>
            
               <div class="col-sm-12">
                    <div class="col-sm-6">
                         <div id="divSpecificatiion">
                         </div>
                    </div>
                    <div class="col-sm-6">
                         <div id="divSpecificatiion1">
                         </div>
                    </div>
               </div> 
               
               <div class="form-group row col-sm-12">
                    <div class="col-sm-5"></div>
                    <div class="col-sm-2" style="cursor:pointer;">
               <div id="add" style="background-color: #5bc0de;text-align: center;border-radius: 5px;">Calculate</div>
                    </div>
                    <div class="col-sm-5"></div>
               </div>
               
               <div class="form-group row">
                    <div class="col-sm-3" align="right"><b>Total Earnings</b></div>
                    <div class="col-sm-2">
                         <input class="form-control total_amount" readonly>
                    </div>
                    
                    <div class="col-sm-4" align="right"><b>Total Deductions</b></div>
                    <div class="col-sm-2">
                         <input class="form-control total_amount1" readonly>
                    </div>
                    <div class="col-sm-1"></div>
               </div>
               
               
               <div class="form-group row">
                    <div class="col-sm-2" align="right"><b>Net Total Amount</b></div>
                    <div class="col-sm-3">
                         <input class="form-control result" readonly="readonly" required="required">
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
  
 
 <script type="text/template" id="temSpecification">
     <div class="form-group row" style="margin-top: -10px;">
      <label for="name" class="col-sm-2 form-control-label">Component</label>
              <div class="col-sm-4">
                <select name="component[]" class="form-control select2" placeholder="Component" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                     <option value="1">Salary</option>
                     <option value="2">Loan</option>
                     <option value="3">Commission</option>
                     <option value="4">PF</option>
                </select>
              </div>
              <div class="col-sm-4">
                <input type="number" min="1" step="any" class="form-control amount" value="0" name="amount[]" placeholder="Amount">
              </div>
		
     <div class="box-tools">
     <a href="javascript:void(0);"  class="btn btn-danger btn-sm btnRemoveMoreSpecification" data-original-title="Add More">
     <i class="fa fa-times"></i>
     </a>
    
     </div>
      
     </div>
 </script>
   
 
  <script type="text/template" id="temSpecification1">
     <div class="form-group row" style="margin-top: -10px;">
      <label for="name" align="right" class="col-sm-2 form-control-label">Component</label>
              <div class="col-sm-4">
                <select name="component1[]" class="form-control select2" placeholder="Component" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                    <option value="11">Leave</option>
                    <option value="12">Loan</option>
                </select>
              </div>
              <div class="col-sm-4">
                <input type="number" min="1" step="any" class="form-control amount1" value="0" name="amount1[]" placeholder="Amount">
              </div>
      
     <div class="box-tools">
     <a href="javascript:void(0);"  class="btn btn-danger btn-sm btnRemoveMoreSpecification1" data-original-title="Add More">
     <i class="fa fa-times"></i>
     </a>
    
     </div>
      
     </div>
 </script> 
 
<script>
$(document).ready(function (event) {
uid=1;uvd=2;
$('#btnAddMoreSpecification').click(function () {
          $('#divSpecificatiion').append($('#temSpecification').html());
 uid++;uvd++; });
     $(document).on('click', '.btnRemoveMoreSpecification', function () {
          $(this).parent('div').parent('div').remove();
     });
});
</script>

<script>
$(document).ready(function (event) {
uid=1;uvd=2;
$('#btnAddMoreSpecification1').click(function () {
          $('#divSpecificatiion1').append($('#temSpecification1').html());
 uid++;uvd++; });
     $(document).on('click', '.btnRemoveMoreSpecification1', function () {
          $(this).parent('div').parent('div').remove();
     });
});
</script>

<script>    
function doCalc() {
    var total = 0;
    var total1 = 0;
    $('.amount').each(function() {
        total += parseInt($(this).val());
    });
    $('.amount1').each(function() {
        total1 += parseInt($(this).val());
    });
    
    $('.total_amount').val(total);
    $('.total_amount1').val(total1);
    
    var result = parseInt(total - total1);
    $('.result').val(result);
}
$('#add').click(doCalc);
</script>

<script>
 $('.amount').keyup(function(){
     doCalc(); 
});
 $('.amount1').keyup(function(){
     doCalc(); 
});
</script>

<!--<script>
 $('#input1,#input2').keyup(function(){
     var textValue1 =$('#input1').val();
     var textValue2 = $('#input2').val();

    $('#output1').val(textValue1 * textValue2); 
 });
</script>-->

<?php include "../includes/footer.php";?>