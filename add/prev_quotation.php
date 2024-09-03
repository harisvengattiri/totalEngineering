<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$customer=$_POST["customer"];
$site=$_POST["site"];
$salesrep=$_POST["salesrep"];
$date=$_POST["date"];
$lpo=$_POST["lpo"];
$terms=$_POST["terms"];
$attention=$_POST["attention"];
$lpo_date=$_POST["lpo_date"];
$order_refrence=$_POST["order_refrence"];
$sql = "INSERT INTO `quotation` (`customer`, `site`, `salesrep`, `date`,`attention`,`terms`) 
VALUES ('$customer', '$site', '$salesrep', '$date','$attention','$terms')";
if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
    $item=$_POST["item"];
    $quantity=$_POST["quantity"];
    $unit=$_POST["unit"];
    $total=$_POST["total"];
    
    $count=sizeof($item);
    $sum=0;
    for($i=0;$i<$count;$i++)
    {
    $item[$i]= mysqli_real_escape_string($conn,$item[$i]);
    $total[$i]=$quantity[$i]*$unit[$i];
    $sql1 = "INSERT INTO `quotation_item` (`quotation_id`,`item`, `quantity`, `price`, `total`) 
VALUES ('$last_id','$item[$i]', '$quantity[$i]', '$unit[$i]', '$total[$i]')";
     $conn->query($sql1);
     $sum=$sum+$total[$i];
    }
    
    $sql2="UPDATE quotation SET subtotal='$sum' where id='$last_id'";
    $conn->query($sql2);
    
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="prj".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}
header("Location: goto_quote.php?id=$last_id&or=$order_referance");
}
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
	<?php }?>
      <div class="box">
        <div class="box-header">
          <h2>Add New Quotation</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/quotation" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-4">
                <select name="customer" id="customer" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                     <!--<select name="items" id="staff" placeholder="Staff" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">-->
                  <?php 
				$sql = "SELECT name FROM customers ";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				?><option value=""> </option><?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["name"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
              
            </div>
              
               
               <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Sales Representative</label>
              <div class="col-sm-4">
                 <select name="salesrep" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT id,name FROM customers where type='SalesRep'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
               
              <label for="date" align="right"  class="col-sm-2 form-control-label">Current Date</label>
              <div class="col-sm-4">
             <?php
              $today = date('d/m/Y');
              ?>
                <input type="text" name="date" value="<?php echo $today;?>" id="date" placeholder="Production Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
               
               <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Project</label>
              <div class="col-sm-4">
                   <input class="form-control" type="text" name="site" id="site">
                   <!--<select class="form-control" name="site" id="site"></select>-->
              </div>
               </div> 
      
            <div class="form-group row">
             
              <label for="name" class="col-sm-1 form-control-label">Item</label>
              <div class="col-sm-3">
                <select name="item[]" class="form-control select2" placeholder="Item" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT items FROM items ORDER BY id ";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["items"]; ?>"><?php echo $row["items"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
              <div class="col-sm-2">
                   <input type="text" class="form-control" name="quantity[]" id="input1" placeholder="Quantity">
              </div>
              <div class="col-sm-2">
                <input type="text" step="0.01" class="form-control" name="unit[]" id="input2" placeholder="Unit Price">
              </div>
<!--              <div class="col-sm-2">
                <input type="text" class="form-control" name="total[]" id="output1" placeholder="Total Price">
              </div>-->
			<div class="box-tools">
                              <a href="javascript:void(0);" 
                                 class="btn btn-info btn-sm" id="btnAddMoreSpecification" data-original-title="Add More">
                                   <i class="fa fa-plus"></i>
                              </a>
                         </div>	  
            </div>
            <div id="divSpecificatiion">

            </div> 
               
            <div class="form-group row">
              <label for="pterms" class="col-sm-2 form-control-label">Terms & Conditions</label>
              <div class="col-sm-10">
              <textarea name="terms" data-ui-jp="summernote">
Terms &amp; Conditions&nbsp;
<ol>
	<li>Price Validity : 5 Days&nbsp;</li>
	<li>Payment Terms : CDC / PDC Subject to Approval&nbsp;</li>
	<li>Delivery Terms : Delivery at Site</li>
	<li>All blocks are DCL APPROVED&nbsp;</li>
	<li>All blocks are 4 hour fire rated &amp; acoustically tested.</li>
	<li>The Units price quoted are does not included the VAT or any other types of taxes &nbsp;imposed by UAE government.</li>
</ol>

              </textarea>
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
     <div class="form-group row" style="padding-top:10px;">
      <label for="name" class="col-sm-1 form-control-label">Item</label>
              <div class="col-sm-3">
                <select name="item[]" class="form-control select2" placeholder="Item" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT items FROM items ORDER BY id";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["items"]; ?>"><?php echo $row["items"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="quantity[]" id="input1" placeholder="Quantity">
              </div>
              <div class="col-sm-2">
                <input type="text" step="0.01" class="form-control" name="unit[]" id="input2" placeholder="Unit Price">
              </div>        
<!--             <div class="col-sm-2">
                <input type="text" class="form-control" name="total[]" id="output1" placeholder="Total Price">
              </div>-->
		
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
<script>
 $('#input1,#input2').keyup(function(){
     var textValue1 =$('#input1').val();
     var textValue2 = $('#input2').val();

    $('#output1').val(textValue1 * textValue2); 
 });
</script>

<?php include "../includes/footer.php";?>