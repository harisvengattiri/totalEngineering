<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$customer=$_POST["customer"];
$site=$_POST["site"];
$salesrep=$_POST["salesrep"];
$date=$_POST["date"];
$lpo=$_POST["lpo"];
$lpo_date=$_POST["lpo_date"];
$order_refrence=$_POST["order_refrence"];
$sql = "INSERT INTO `sales_order` (`customer`, `site`, `salesrep`, `date`, `lpo`, `lpo_date`, `order_referance`) 
VALUES ('$customer', '$site', '$salesrep', '$date', '$lpo', '$lpo_date', '$order_refrence')";
if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
    $item=$_POST["item"];
    $quantity=$_POST["quantity"];
    $unit=$_POST["unit"];
    $total=$_POST["total"];
    
     $count=sizeof($item);
    for($i=0;$i<$count;$i++)
    {
    $item[$i]= mysqli_real_escape_string($conn,$item[$i]);
    $total[$i]=$quantity[$i]*$unit[$i];
    $sql1 = "INSERT INTO `order_item` (`item_id`,`item`, `quantity`, `unit`, `total`) 
VALUES ('$last_id','$item[$i]', '$quantity[$i]', '$unit[$i]', '$total[$i]')";
     $conn->query($sql1);
    }
    
    
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
}}
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
          <h2>Add New Sales Order</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/sales_order" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-4">
                <select name="customer" id="customer" class="form-control">
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
              <label for="type" align="right" class="col-sm-2 form-control-label">Customer Site</label>
              <div class="col-sm-4">
                   <select class="form-control" name="site" id="site"></select>
              </div>
            </div>
              
               
               <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Sales Representative</label>
              <div class="col-sm-4">
                 <select name="salesrep" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT salesrep FROM customer_site GROUP BY salesrep";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["salesrep"]; ?>"><?php echo $row["salesrep"]?></option>
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
              <label for="type" class="col-sm-1 form-control-label">LPO No</label>
              <div class="col-sm-2">
                   <input type="text" class="form-control" name="lpo" id="value" placeholder="LPO No">
              </div>
              <label for="date"  class="col-sm-1 form-control-label">LPO Date</label>
              <div class="col-sm-2">
             <?php
              $today = date('d/m/Y');
              ?>
                <input type="text" name="lpo_date" id="date" placeholder="LPO Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <label for="type" align="right" class="col-sm-2 form-control-label">Order Refrence</label>
              <div class="col-sm-4">
                  <?php 
                   $sql = "SELECT * FROM sales_order ORDER BY id DESC LIMIT 1";
//				$sql = "SELECT batch FROM batches_lots where id='$last_id'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                     $value=0;
				while($row = mysqli_fetch_assoc($result)) 
				{ 
                                     $value=$row["order_referance"]+1;
				?>
                   <input type="" class="form-control" name="order_refrence" value="<?php echo sprintf("%05d",$value);?>" readonly>
				<?php 
				}}
                               else {
                                    $value='00001';
                                       ?> <input type="" class="form-control" name="order_refrence" value="<?php echo $value;?>" readonly><?php
                                    }
		  ?>
              </div>
             </div> 
             
            <div class="form-group row">
            
            
            
            
            
            <div id="myTable">
     
     
       <?php $field_name = 1;?>
       <div id="pdresult">
      
       <input type="text" name="text" id="<?php echo "txt".$field_name."";?>" value="<?php echo $field_name;?>" />
       
        &nbsp;<input type="text" class="pname_list form-control input-sm" name="p_name[]" value="<?php echo $field_name;?>" id="<?php echo "pname".$field_name."";?>" class="typeahead" required/>
     
         
        
      </div>
      </div>
       
       <!--<tr id="addedRows"></tr>-->
      
     <div class="col-sm-4">
    <a href="javascript:void(0);" id="new_pd">Add new Product</a>
    </div>
    
    <script>
$(document).ready(function(){
  var field_name = 1;
$(document).on('click','#new_pd',function(){
	field_name++;
	$.ajax({
				url:'purchase_serv.php',
				data: {fname: field_name},
				type:'POST',
				success:function(data){
					$("#pdresult").append(data);
					var pname='#pname'+field_name;
					$(pname).focus();
				}
			});
	});
	
	$("#myTable").on('click','.remCF',function(){
	
	var r=confirm("Are you sure want to delete this Product?");
		if (r==true)
  		{
  		$(this).parent().remove();
  		}
		else
  		{
  		return false;
  		}
        
    });
});
</script>
            
            
            
            
              
           
             
              
              
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

    
 
<script>






/*
$(document).ready(function(event) {
  uid = 1;
  uvd = 2;
  $('#btnAddMoreSpecification').click(function() {
uid++;
    var templateHtml = $('#temSpecification').html();//save the html in a variable
    var temp = $(templateHtml).find('input[type="text"]').val(uid).end();//find the input and append the new uid
    $('#divSpecificatiion').append(temp);//append the new html to the div
  });

  $(document).on('click', '.btnRemoveMoreSpecification', function() {
    $(this).parent('div').parent('div').remove();
  });
});



*/
</script>

<script>
/*
 $('#input1,#input2').keyup(function(){
     var textValue1 =$('#input1').val();
     var textValue2 = $('#input2').val();

    $('#output1').val(textValue1 * textValue2); 
 });*/
</script>

<?php include "../includes/footer.php";?>