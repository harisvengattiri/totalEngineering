<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
    
// $voucher=$_POST["voucher"];
$id=$_POST["id"];

$date=$_POST["date"];
$staff=$_POST["staff"];
$des=$_POST["des"];
$sub=$_POST["sub"];
$amount=$_POST["amount"];
$vat=$_POST["vat"];

$amount1 = array_sum($amount);
$vat1 = array_sum($vat);
$total1 = $amount1+$vat1;

// $sql = "INSERT INTO `petty_cash`(`date`,`staff`,`amount`,`vat`,`total`) 
// VALUES ('$date', '$staff', '$amount1', '$vat1', '$total1')";

$sql = "UPDATE `petty_cash` SET `date`='$date',`staff`='$staff',`amount`='$amount1',`vat`='$vat1',`total`='$total1' WHERE id='$id'";


if ($conn->query($sql) === TRUE) {
      $status="success";
      $last_id = $conn->insert_id;
    
    $sql_dlt = "DELETE FROM petty_item WHERE petty=$id";
    $query_dlt = mysqli_query($conn,$sql_dlt);
      
    $count=sizeof($sub);
    for($i=0;$i<$count;$i++)
    {
         $sql2 = "SELECT parent FROM expense_subcategories WHERE id='$sub[$i]'";
         $query2 = mysqli_query($conn,$sql2);
         $result2 = mysqli_fetch_array($query2);
         $exp[$i] = $result2['parent'];
         
         $des[$i] = mysqli_real_escape_string($conn,$des[$i]);
     
     $total[$i] = $amount[$i] + $vat[$i];
     $sql1 = "INSERT INTO `petty_item` (`petty`,`date`,`staff`,`type`,`sub`,`description`,`amount`,`vat`,`total`) 
     VALUES ('$id', '$date', '$staff', '$exp[$i]', '$sub[$i]', '$des[$i]', '$amount[$i]', '$vat[$i]', '$total[$i]')";
     $conn->query($sql1); 
    }

      $date1=date("d/m/Y h:i:s a");
      $username=$_SESSION['username'];
      $code="PTC".$id;
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
     $sql="SELECT * FROM petty_cash where id = '$id'";
     $result = $conn->query($sql);
     while ($row = mysqli_fetch_array($result)) 
     {
        $date=$row["date"];
        $staff=$row["staff"];
                  $sqlrep= "SELECT name FROM customers where id='$staff'";
			            $resultrep = mysqli_query($conn, $sqlrep);
                  $rowrep = mysqli_fetch_assoc($resultrep);
                  $staff_name=$rowrep['name'];
                         
   $sql2 = "SELECT sum(amount) AS amount FROM petty_voucher WHERE staff='$staff' AND status='Cleared'";
   $query2 = mysqli_query($conn,$sql2);
   $result2 = mysqli_fetch_array($query2);
   $amount = $result2['amount'];

   $sql1 = "SELECT sum(total) AS total FROM petty_cash WHERE staff='$staff'";
   $query1 = mysqli_query($conn,$sql1);
   $result1 = mysqli_fetch_array($query1);
   $total = $result1['total'];
   
   $balance = $amount - $total;
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
	<?php }?>
      <div class="box">
        <div class="box-header">
          <h2>Edit Petty Cash</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/edit/petty_cash?id=<?php echo $id;?>" method="post">
              <div class="form-group row">
                    <script>
                      $(document).ready(function (event) {
                      $('#staff').change(function() {
                         var country_id = $(this).val();
                         if(country_id != "") {
                           $.ajax({
                             url:"../add/my_scripts/getbalance_voucher",
                             data:{c_id:country_id},
                             type:'POST',
                             success:function(response) {
                               var resp = $.trim(response);
                               $('#balance').val(resp);
                             }
                           });
                         }
                       });
                       }); 
                    </script>
              
              <div class="col-sm-4">
              <input type="hidden" name="id" value="<?php echo $id;?>">
                   <select class="form-control" name="staff" id="staff" required>
                        <option value="<?php echo $staff;?>"><?php echo $staff_name;?></option>                        
                        <?php
                          $sql = "SELECT staff,name FROM petty_voucher INNER JOIN customers ON petty_voucher.staff=customers.id GROUP BY petty_voucher.staff";
                          $query = mysqli_query($conn,$sql);
                          while($fetch = mysqli_fetch_array($query))
                          {
                        ?>
                        <option value="<?php echo $fetch['staff'];?>"><?php echo $fetch['name'];?></option>
                        <?php } ?>
                   </select>
              </div>
              <div class="col-sm-2">
                   <input type="number" id="balance" min="0.01" step="any" class="form-control" value="<?php echo $balance;?>" name="balance" placeholder="Amount" readonly>
              </div>
              <div class="col-sm-4">
             <?php
              $today = date('d/m/Y');
              ?>
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

                <div class="form-group row col-sm-12">
                    <div class="col-sm-7"></div>
                    <div class="col-sm-2" style="cursor:pointer;">
                    <div id="calculate" style="background-color: #5bc0de;text-align: center;border-radius: 5px;">Calculate</div>
                    </div>
                    <div class="col-sm-3"><p>Total Amount: AED :<span id="total_cost"></span></p></div>
                </div>
              <div class="form-group row">
                         <label for="endd" align="center" class="col-sm-4 form-control-label"><b>Type</b></label>
                         <label for="endd" align="center" class="col-sm-4 form-control-label"><b>Description</b></label>
                         <label for="endd" align="center" class="col-sm-2 form-control-label"><b>Amount</b></label>
                         <label for="endd" align="center" class="col-sm-2 form-control-label"><b>VAT</b></label>
               </div>
            
            <?php 
               $sql1="SELECT * FROM petty_item where petty='$id'";
               $result1=$conn->query($sql1);
               $count=0;
               while($row1 = mysqli_fetch_assoc($result1)) 
                    {
                        $des=$row1["description"];
                        $amount=$row1["amount"];
                        $vat=$row1["vat"];
                        $total=$row1["total"];
                        $sub=$row1["sub"];
                            $sql3 = "SELECT category FROM expense_subcategories WHERE id='$sub'";
				            $result3 = mysqli_query($conn, $sql3);
				            $row3 = mysqli_fetch_assoc($result3);
				            $sub_name = $row3['category'];
            ?>
            
               
            <div class="form-group row">
              <div class="col-sm-4">
                <select name="sub[]" id="customer" required class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                     <!--<select name="items" id="staff" placeholder="Staff" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">-->
                  <?php 
				$sql = "SELECT * FROM expense_subcategories";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				?><option value="<?php echo $sub;?>"><?php echo $sub_name;?></option><?php
				while($row = mysqli_fetch_assoc($result)) 
				{
                        $parent=$row['parent'];
                        $sql2 = "SELECT tag FROM expense_categories where id=$parent";
        				$result2 = mysqli_query($conn, $sql2);
        				$row2 = mysqli_fetch_assoc($result2); 
        				$parent_name=$row2['tag'];
				?>
				<option value="<?php echo $row["id"];?>"><?php echo $row["category"]." [#".$parent_name."]";?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
              <div class="col-sm-4">
                   <input type="text" class="form-control" name="des[]" value="<?php echo $des;?>" placeholder="Description">
              </div>
              <div class="col-sm-2">
                   <input type="number" min="0" step="any" class="form-control amount" name="amount[]" value="<?php echo $amount;?>" placeholder="Amount">
              </div>
              <div class="col-sm-1">
                   <input type="number" min="0" step="any" class="form-control amount" name="vat[]" value="<?php echo $vat;?>" placeholder="VAT">
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
            <?php } ?>
           </div>
           <?php $count++; } ?> 

                    <div class="col-sm-12">
                         <div id="divSpecificatiion">
                         </div>
                    </div>
    
              
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>
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
// $('.amount').keyup(function () {
//     var sum = 0;
//     $('.amount').each(function() {
//         sum += Number($(this).val());
//     });
//     $('#total_cost').html(sum);
// });
</script>
<script>    
function doCalc() {
    var sum = 0;
    $('.amount').each(function() {
        sum += parseInt($(this).val());
    });
    $('#total_cost').html(sum);
}
$('#calculate').click(doCalc);
</script>
 <script type="text/template" id="temSpecification">
     <div class="form-group row" style="margin-top: -10px;">
              <div class="col-sm-4">
                <select name="sub[]" id="customer" required class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                     <!--<select name="items" id="staff" placeholder="Staff" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">-->
                  <?php 
				$sql = "SELECT * FROM expense_subcategories";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				?><option value="">SELECT</option><?php
				while($row = mysqli_fetch_assoc($result)) 
				{
                        $parent=$row['parent'];
                        $sql2 = "SELECT tag FROM expense_categories where id=$parent";
        				$result2 = mysqli_query($conn, $sql2);
        				$row2 = mysqli_fetch_assoc($result2); 
        				$parent_name=$row2['tag'];
				?>
				<option value="<?php echo $row["id"];?>"><?php echo $row["category"]." [#".$parent_name."]";?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
              <div class="col-sm-4">
                   <input type="text" class="form-control" name="des[]" placeholder="Description">
              </div>
              <div class="col-sm-2">
                <input type="number" min="0" step="any" class="form-control amount" value="0" name="amount[]" placeholder="Amount">
              </div>
              <div class="col-sm-1">
                <input type="number" min="0" step="any" class="form-control amount" value="0" name="vat[]" placeholder="VAT">
              </div>
		
     <div class="box-tools">
     <a href="javascript:void(0);"  class="btn btn-danger btn-sm btnRemoveMoreSpecification" data-original-title="Add More">
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

<?php include "../includes/footer.php";?>