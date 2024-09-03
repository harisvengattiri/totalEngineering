<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
$customer=$_POST["customer"];
$dn=$_POST["dn"];
$item=$_POST["item"];
$quantity=$_POST["quantity"];
$return=$_POST["return"];
$batch=$_POST["batch"];

$n=sizeof($return);
for($i=0;$i<$n;$i++)
{
     if($return[$i]!=NULL)
     {
$item[$i] = mysqli_real_escape_string($conn,$item[$i]);
$sql = "INSERT INTO stock_return(customer,dn,item,quantity,returnqnt,batch) 
VALUES('$customer', '$dn', '$item[$i]', '$quantity[$i]', '$return[$i]', '$batch[$i]')";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="prj".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else 
{
    $status="failed";
}
     }
}

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
          <h2>Add New Invoice</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/stock_return" method="post">
            <div class="form-group row">
              <label for="customer" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-4">
                   <select name="customer" id="cust" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                     <!--<select name="items" id="staff" placeholder="Staff" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">-->
                  <?php 
                                $customer=$_POST['customer'];
                                
                                $sqlcust="SELECT name from customers where id='$customer'";
                                $querycust=mysqli_query($conn,$sqlcust);
                                $fetchcust=mysqli_fetch_array($querycust);
                                $cust=$fetchcust['name'];
                                $dn=$_POST['dn'];
                    
                    
				$sql = "SELECT name,id FROM customers where type='Company' order by name";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				?><option value="<?php echo $customer;?>"><?php echo $cust;?> </option><?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
            </div>
               <div class="form-group row">
              <label for="startd" align="left" class="col-sm-2 form-control-label">Delivery Number</label>
              <div class="col-sm-4">
                   <select class="form-control" name="dn" id="dn">
                   <option><?php echo $dn;?></option>     
                   </select>
              </div>
              </div>
               
              <div class="form-group row m-t-md">
              <div align="left" class="col-sm-offset-2 col-sm-12">
                <!--<button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>-->
                <button name="submit1" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search</button>
              </div>
            </div>
           </form>
             
             <?php 
             if(isset($_POST['submit1']))  
             {
                  $customer=$_POST['customer'];
                  $dn=$_POST['dn'];
                  
                  $sql="SELECT * FROM delivery_item where delivery_id='$dn' AND thisquantity!=0";
                  $query=mysqli_query($conn,$sql);
                  if($query->num_rows > 0)
                  {
                       while($fetch=mysqli_fetch_array($query))
                       {
                            $item=$fetch['item'];
                            
                              $sqlitem="SELECT items from items where id='$item'";
                              $queryitem=mysqli_query($conn,$sqlitem);
                              $fetchitem=mysqli_fetch_array($queryitem);
                              $item1=$fetchitem['items'];
                            $quantity=$fetch['thisquantity'];
                            $batch=$fetch['batch'];
             ?>
               <form role="form" action="<?php echo $baseurl;?>/add/stock_return" method="post">    
          <div class="form-group row">
               <label for="endd" class="col-sm-1 form-control-label">Item</label>
              <div class="col-sm-2">
                   <input type="hidden"  name="customer" value="<?php echo $customer;?>">
                   <input type="hidden"  name="dn" value="<?php echo $dn;?>">
                   <input type="hidden"  name="item[]" value="<?php echo $item;?>">
                   <input type="text" class="form-control" value="<?php echo $item1;?>" id="endd" placeholder="Item" readonly>
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">Quantity</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="quantity[]" value="<?php echo $quantity;?>" id="endd" placeholder="Quantity" readonly>
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">Return</label>
               <div class="col-sm-2">
                    <input type="number" min="0" class="form-control" name="return[]" placeholder="Quantity Returned" id="endd">
               </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">Batch</label>
               <div class="col-sm-2">
                    <input type="text" class="form-control" name="batch[]" value="<?php echo $batch;?>"  id="endd" readonly>
              </div>
               
         </div>
             <?php } } } ?>
               
<!--              <div class="form-group row">
              <label for="type" class="col-sm-2 form-control-label">Building Permit</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="permit" id="estimate" placeholder="Building Permit">
              </div>
              <label for="Quantity" class="col-sm-2 form-control-label">Contact Person</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="Contact_per" id="value" placeholder="Contact Person">
              </div>
             </div> -->
             
               
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
 
<?php include "../includes/footer.php";?>