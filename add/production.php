<?php ob_start();?>
<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
$item = $_POST['item'];
$date=$_POST["date"];
$seriel=$_POST["seriel"];
$shift=$_POST["shift"];
$operator=$_POST["operator"];

//if($shift=='Day')
//{
//   $sql2="SELECT shift FROM products WHERE date='$date' AND shift='Day'";
//   $query2=mysqli_query($conn,$sql2);
//     if(mysqli_num_rows($query2)>0)
//          {
//              header('Location:?failed=1');
//              exit();
//          }
//}
//if($shift=='Night')
//{
//   $sql2="SELECT shift FROM products WHERE date='$date' AND shift='Night'";
//   $query2=mysqli_query($conn,$sql2);
//     if(mysqli_num_rows($query2)>0)
//          {
//              header('Location:?failed=1');
//              exit();
//          }  
//}

$sql = "INSERT INTO `products` (`date`, `seriel`) 
VALUES ('$date', '$seriel')";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $quantity=$_POST["quantity"];
       $n=sizeof($item);
       for($i=0;$i<$n;$i++)
       {
//            if($shift[$i]=='Day')
//               {
//   $sql2="SELECT shift FROM prod_items WHERE date='$date' AND shift='Day'";
//   $query2=mysqli_query($conn,$sql2);
//     if(mysqli_num_rows($query2)>0)
//          {
//              header('Location:?failed=1');
//              exit();
//          }
//               }
//if($shift[$i]=='Night')
//{
//   $sql2="SELECT shift FROM prod_items WHERE date='$date' AND shift='Night'";
//   $query2=mysqli_query($conn,$sql2);
//     if(mysqli_num_rows($query2)>0)
//          {
//              header('Location:?failed=1');
//              exit();
//          }  
//}

       $sql1 = "INSERT INTO `prod_items` (`prod_id`, `item`, `quantity` , `shift` , `operator` , `date` , `seriel`) 
VALUES ('$last_id', '$item[$i]', '$quantity[$i]', '$shift[$i]', '$operator[$i]', '$date', '$seriel')";
       $conn->query($sql1);
       }
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="PRD".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}}
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
	<?php }
  if($_GET) { $status1 = $_GET['failed'];} else { $status1 = '5';}
  if($status1 == '1') { ?>
    <p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Failed Since the entry to this shift already exists!</span>
    </a></p> <?php } ?>
      <div class="box">
        <div class="box-header">
          <h2>Add New Product</h2>
          <!-- <h2><?php // echo $msg;?></h2> -->
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/production" method="post">
               
               
<!--               <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Machine Operator</label>
              <div class="col-sm-3">
                 <select name="operator" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT * FROM operators";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["operators"]; ?>"><?php echo $row["operators"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
               <label for="type" align="right" class="col-sm-1 form-control-label">Shift</label>
              <div class="col-sm-3">
                   <select name="shift" class="form-control">
                     <option value="Day">Day</option>
                     <option value="Night">Night</option>
                   </select>
              </div>
              </div> -->
               
             
              
            <div class="form-group row">
              <label for="date"  class="col-sm-1 form-control-label">Date</label>
              <div class="col-sm-3">
                   <?php $today=date("d/m/Y"); ?>
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
              <label for="Quantity" class="col-sm-1 form-control-label">Seriel No</label>
              <div class="col-sm-2">
                   <?php 
                   $sql = "SELECT seriel FROM products ORDER BY id DESC LIMIT 1";
//				$sql = "SELECT batch FROM batches_lots where id='$last_id'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                     $value=0;
				while($row = mysqli_fetch_assoc($result)) 
				{ 
                                     $value=$row["seriel"]+1;
                                     
				?>
                   <input type="" class="form-control" name="seriel" value="<?php echo $value;?>" readonly>
				<?php 
				}} 
                                else{
                                     $value=1;
                                     ?><input type="" class="form-control" name="seriel" value="<?php echo $value;?>" readonly><?php
                                    }
		  ?>
                   
              </div>
              
             </div>   
               
            <div class="form-group row">
             
              <div class="col-sm-3">
                <select name="item[]" class="form-control select2" placeholder="Item" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT id,items FROM items ORDER BY id";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"];?>"><?php echo $row["items"];?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
              <div class="col-sm-2">
                   <input type="number" min="1" step="any" class="form-control" name="quantity[]" id="input1" placeholder="Quantity">
              </div>
              
              <label for="type" class="col-sm-1 form-control-label">Operator</label>
              <div class="col-sm-2">
                 <select name="operator[]" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                        
                                <?php 
				$sql = "SELECT name,id FROM customers where type='Operator'";
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
               <label for="type" align="right" class="col-sm-1 form-control-label">Shift</label>
              <div class="col-sm-2">
                   <select name="shift[]" class="form-control">
                     <option value="Day">Day</option>
                     <option value="Night">Night</option>
                   </select>
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
      <div class="col-sm-3">
                <select name="item[]" class="form-control select2" placeholder="Item" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT items,id FROM items ORDER BY id";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"];?>"><?php echo $row["items"];?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
              <div class="col-sm-2">
                   <input type="number" min="1" step="any" class="form-control" name="quantity[]" id="input1" placeholder="Quantity">
              </div>
              
              <label for="type" class="col-sm-1 form-control-label">Operator</label>
              <div class="col-sm-2">
                 <select name="operator[]" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                        
                                <?php 
				$sql = "SELECT name,id FROM customers where type='Operator'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"];?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
                                
                </select>
              </div>
               <label for="type" align="right" class="col-sm-1 form-control-label">Shift</label>
              <div class="col-sm-2">
                   <select name="shift[]" class="form-control">
                     <option value="Day">Day</option>
                     <option value="Night">Night</option>
                   </select>
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