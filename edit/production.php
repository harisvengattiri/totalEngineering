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
$operator=$_POST["operator"];
$shift=$_POST["shift"];
$prj=$_POST["prj"];

$sql = "UPDATE `products` SET `date` =  '$date' WHERE  `seriel` = $prj";
if ($conn->query($sql) === TRUE) {
     
     $sql2="SELECT id FROM products where seriel='$prj'";
     $query2=mysqli_query($conn,$sql2);
     $result2=mysqli_fetch_array($query2);
     $newprod=$result2["id"];
             
    $status="success";
       $quantity=$_POST["quantity"];
       $sql3="DELETE FROM prod_items WHERE seriel='$prj'";
       $conn->query($sql3);
       $n=sizeof($item);
       for($i=0;$i<$n;$i++)
       {
       $item[$i] = mysqli_real_escape_string($conn,$item[$i]);
       $sql1 = "INSERT INTO `prod_items` (`prod_id`, `item`, `quantity` , `shift` , `operator` , `date` , `seriel`) 
VALUES ('$newprod', '$item[$i]', '$quantity[$i]', '$shift[$i]', '$operator[$i]', '$date', '$seriel')";
       $conn->query($sql1);
       }     
            
            
            
//       $item[$i] = mysqli_real_escape_string($conn,$item[$i]);
//       $sql1 = "INSERT INTO `prod_items` (`prod_id`, `item`, `quantity`) 
//VALUES ('$prj', '$item[$i]', '$quantity[$i]')";
//       $conn->query($sql1);
//       }
       
       
       
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="PRD".$prj;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}}


if ($_GET) 
{
$prj=$_GET["srl"];
}
	$sql = "SELECT date FROM products where seriel=$prj";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        $row = mysqli_fetch_assoc($result); 
         $date=$row["date"];
              
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
	<?php } ?>
    
      <div class="box">
        <div class="box-header">
          <h2>Edit Product</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
             
             
          <form role="form" action="<?php echo $baseurl;?>/edit/production" method="post">
               <input name="prj"  type="text" value="<?php echo $prj;?>" hidden="hidden">
                
            <div class="form-group row">
              <label for="date"  class="col-sm-2 form-control-label">Date</label>
              <div class="col-sm-3">
                <input type="text" name="date" value="<?php echo $date;?>" id="date" placeholder="Production Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                   <input type="number" value="<?php echo $prj;?>" class="form-control" name="seriel" id="value" placeholder="Seriel No" readonly>
              </div>
              </div>
               
               <?php
                    $sql3="SELECT * FROM prod_items where seriel='$prj'";
                    $query3=mysqli_query($conn,$sql3);
                    if(mysqli_num_rows($query3) > 0)
                    {
                         $count=0;
                    while($fetch3=mysqli_fetch_array($query3))
                    {
                        $item=$fetch3['item'];
                        $quantity=$fetch3['quantity'];
                        $operator=$fetch3['operator'];
                        $shift=$fetch3['shift'];
                      
                 ?>
              <div class="form-group row">
              <div class="col-sm-3">
                <select name="item[]" class="form-control select2" placeholder="Item" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT items,id FROM items ORDER BY id";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
                                $selected = ($row['id'] == $item) ? "selected='selected'" : '';
				?>
				<option <?php echo $selected; ?> value="<?php echo $row["id"];?>"><?php echo $row["items"];?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
              <div class="col-sm-2">
                   <input type="number" min="1" step="any" class="form-control" name="quantity[]" id="input1" value="<?php echo $quantity; ?>" placeholder="Quantity">
              </div>
              <label for="type" class="col-sm-1 form-control-label">Operator</label>
              <div class="col-sm-2">
                 <select name="operator[]" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                                <?php 
				$sql = "SELECT name FROM customers where type='Operator'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
                                $selected = ($row['name'] == $operator) ? "selected='selected'" : '';
				?>
				<option <?php echo $selected;?> value="<?php echo $row["name"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
               <label for="type" align="right" class="col-sm-1 form-control-label">Shift</label>
              <div class="col-sm-2">
                   <select name="shift[]" class="form-control">
                        <?php
                          if($shift=='Day')
                          {
                               $selected1='Day';
                          }
                          else
                               {
                               $selected1='Night';
                               }
                          
                          ?>
                     <option value="<?php echo $selected1;?>"><?php echo $selected1;?></option>   
                     <option value="Day">Day</option>
                     <option value="Night">Night</option>
                   </select>
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
              <?php } $count++; ?>
            </div>
               <?php }} ?>
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
				$sql = "SELECT name FROM customers where type='Operator'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
                                
				?>
				<option value="<?php echo $row["name"];?>"><?php echo $row["name"];?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
               <label for="type" align="right" class="col-sm-1 form-control-label">Shift</label>
              <div class="col-sm-2">
                   <select name="shift[]" class="form-control">
                        <?php
                          if($shift=='Day')
                          {
                               $selected1='Day';
                          }
                          else
                               {
                               $selected1='Night';
                               }
                          
                          ?>
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