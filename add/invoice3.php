<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$or=$_POST["or"];
$dn=$_POST["dn"];
$lpo=$_POST["lpo"];

$date=$_POST["date"];
$customer=$_POST["customer"];
$site=$_POST["site"];

$subtotal=$_POST["sub"];
$vat=$_POST["vat"];
$grand=$_POST["grand"];
$sql = "INSERT INTO `invoice` (`customer`, `site`,`dn`, `lpo`,`o_r`, `date`, `total`, `vat`, `grand`) 
VALUES ('$customer', '$site', '$dn', '$lpo', '$or', '$date', '$subtotal', '$vat', '$grand')";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $item=$_POST["item"];
       $reqquantity=$_POST["reqquantity"];
       $pdate=$_POST["pdate"];
       $unit=$_POST["unit"];
       $total=$_POST["total"];
       $n=sizeof($item);
       for($i=0;$i<$n;$i++)
       {
       $sql1 = "INSERT INTO `invoice_item` (`invoice_id`, `dn`, `or`, `lpo`, `item`, `pdate`, `quantity`, `unit`,`total`) 
VALUES ('$last_id', '$dn', '$or', '$lpo', '$item[$i]', '$pdate[$i]', '$reqquantity[$i]', '$unit[$i]', '$total[$i]')";
$conn->query($sql1);

$sql2="UPDATE delivery_note SET invoiced = 'yes' WHERE id='$dn'"; 
$conn->query($sql2);
               
       }
       
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="mnt".$last_id;
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
    <div class="col-md-8">
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
          <form role="form" action="<?php echo $baseurl;?>/add/invoice" method="post">
             
               <div class="form-group row">
              <label for="customer" class="col-sm-3 form-control-label">Customer</label>
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
                    
                    
				$sql = "SELECT name,id FROM customers where type='Company' order by name";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                ?> <option value="<?php echo $customer;?>"><?php echo $cust;?></option> <?php
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
              <label for="startd" align="left" class="col-sm-3 form-control-label">Customer Site</label>
              <div class="col-sm-6">
                   <select class="form-control" name="site" id="site">
                        <option value="<?php echo $site;?>"><?php echo $site1;?></option>
                   </select>
              </div>
            </div>
              
                <div class="form-group row">
               <?php
              $date = date('d/m/y');
              ?>
              <label for="date" class="col-sm-3 form-control-label">Current Date</label>
              <div class="col-sm-6">
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
            </div>  
            
            <div class="form-group row m-t-md">
              <div align="" class="col-sm-offset-2 col-sm-12">
                <button name="submit1" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search</button>
              </div>
            </div>
          </form>  
               
             <?php if(isset($_POST['submit1']))
             {
                  $customer=$_POST['customer'];
                  $site=$_POST['site'];
                  $date=$_POST['date'];
             ?>
             
             
             <form role="form" action="<?php echo $baseurl;?>/add/invoice" method="post">
                    <input type="text" name="customer" value="<?php echo $customer;?>" hidden="hidden">
                    <input type="text" name="site" value="<?php echo $site;?>" hidden="hidden">
                    <input type="text" name="date" value="<?php echo $date;?>" hidden="hidden">
                    <div class="form-group row">
                    <label class="col-sm-3 form-control-label">Delivery No</label>
                    <div class="col-sm-6">
                    <select name="dn" class="form-control select2">
                         <?php  $sql = "SELECT id FROM delivery_note where customer='$customer' AND customersite='$site' AND invoiced=''";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                while($row = mysqli_fetch_assoc($result)) 
				{
                                  $id=$row["id"]; 
                                  ?><option value="<?php echo $id;?>"><?php echo $id;?></option><?php
                                }}
                        ?>
                    </select>
                    </div></div>
             
                  <div class="form-group row m-t-md">
                    <div align="" class="col-sm-offset-2 col-sm-12">
                      <button name="submit2" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search</button>
                    </div>
                  </div>  
                    
             </form>
             <?php } ?>
             
             <?php if(isset($_POST['submit2']))
             {
                  $customer=$_POST['customer'];
                  $site=$_POST['site'];
                  $date=$_POST['date'];
                  $dn=$_POST['dn'];
             ?>
             
               <form role="form" action="<?php echo $baseurl;?>/add/invoice" method="post">
                    <input type="text" name="customer" value="<?php echo $customer;?>" hidden="hidden">
                    <input type="text" name="site" value="<?php echo $site;?>" hidden="hidden">
                    <input type="text" name="date" value="<?php echo $date;?>" hidden="hidden">
                    
                    <div class="form-group row">
                    <label class="col-sm-3 form-control-label">Delivery No</label>
                    <div class="col-sm-6">
                    <select name="dn" class="form-control select2">
                         <option value="<?php echo $dn;?>"><?php echo $dn;?></option>
                         <?php  $sql = "SELECT id FROM delivery_note where customer='$customer' AND customersite='$site' AND invoiced=''";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                while($row = mysqli_fetch_assoc($result)) 
				{
                                  $id=$row["id"]; 
                                  ?><option value="<?php echo $id;?>"><?php echo $id;?></option><?php
                                }}
                        ?>
                    </select>
                    </div></div>   
                    
                              <?php
                              $sql1="SELECT * from delivery_item where delivery_id='$dn' and thisquantity!=0";
                              $result1 = mysqli_query($conn, $sql1);
                              if (mysqli_num_rows($result1) > 0) 
				{
                                    $sql2="SELECT * FROM delivery_note where id='$dn'";
                                    $query2=mysqli_query($conn,$sql2);
                                    $fetch2=mysqli_fetch_array($query2);
                                    $lpo=$fetch2['lpo'];
                                    $or=$fetch2['order_referance'];
                                 ?> 
                                        <div class="form-group row" style="text-align:center;margin-bottom:0px;">
                                        <label for="endd" class="col-sm-3 form-control-label"><b></b></label>
                                        <label for="endd" class="col-sm-3 form-control-label"><b>Delivery Note Referance</b></label>
                                        <label for="endd" class="col-sm-3 form-control-label"><b>Sales Order Referance</b></label>
                                        <label for="endd" class="col-sm-3 form-control-label"><b>LPO</b></label>
                                        </div>     
                    
                    
                                        <div class="form-group row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-3">
                                        <input type="text" class="form-control" name="dn" value="<?php echo $dn; ?>" placeholder="" readonly>
                                        </div>
                                        
                                        <div class="col-sm-3">
                                        <input type="text" class="form-control" name="or" value="<?php echo $or; ?>" placeholder="" readonly>
                                        </div>
                                        
                                        <div class="col-sm-3">
                                             <input type="text" class="form-control" name="lpo" value="<?php echo $lpo; ?>" placeholder="" readonly>
                                        </div>
                                        </div> 
                                  
                                <div class="form-group row" style="text-align:center;margin-bottom:0px;">
                                <label for="endd" align="" class="col-sm-3 form-control-label"><b>Item</b></label>
                                <label for="endd" align="" class="col-sm-2 form-control-label"><b>Date</b></label>   
                                <label for="endd" align="" class="col-sm-2 form-control-label"><b>Quantity</b></label>  
                                <label for="endd" align="" class="col-sm-2 form-control-label"><b>Unit Price</b></label>
                                <label for="endd" align="" class="col-sm-3 form-control-label"><b>Total</b></label>
                                </div>
                               <?php 
				while($row1 = mysqli_fetch_assoc($result1)) 
				{
                                     
                                     $item1=$row1['item'];
                                     
                                        $sqlitem="SELECT items from items where id='$item1'";
                                        $queryitem=mysqli_query($conn,$sqlitem);
                                        $fetchitem=mysqli_fetch_array($queryitem);
                                        $item=$fetchitem['items'];
                                     $quantity=$row1['thisquantity'];
                                     $unit=$row1['price'];
                                     $pdate=$row1['pdate'];
                                     $total=$quantity*$unit;
                                     $subtotal=$subtotal+$total;
                                     
                                    ?>
                                    
              <div class="form-group row">
              
              <div class="col-sm-3">
                   <input type="text" class="form-control" value="<?php echo $item;?>" placeholder="Item" readonly>
                   <input name="item[]" value="<?php echo $item1; ?>" hidden="hidden">
              </div>
               <!--<label for="endd" align="right" class="col-sm-1 form-control-label">Date</label>-->
              <div class="col-sm-2">
                <input type="text" class="form-control" name="pdate[]" value="<?php echo $pdate;?>" id="endd" readonly>
              </div>
<!--               <label for="endd" align="right" class="col-sm-1 form-control-label">Quantity</label>-->
              <div class="col-sm-2">
                <input type="text" class="form-control" name="reqquantity[]" value="<?php echo $quantity;?>" id="endd" readonly>
              </div>
<!--               <label for="endd" align="right" class="col-sm-1 form-control-label">Unit Price</label>-->
              <div class="col-sm-2">
                <input type="text" class="form-control" name="unit[]" value="<?php echo $unit;?>" id="endd" readonly>
              </div>
               <div class="col-sm-3">
                    <input type="text" class="form-control" name="total[]" value="<?php echo $total;?>" id="endd" readonly>
                    
              </div>
              </div>
                <?php }}  ?>  
                    
                               <?php 
                                 $vat = (5 / 100) * $subtotal;
                                 $grandtotal=$vat+$subtotal;
                                ?> 
                    
                                        <div class="form-group row" style="text-align:center; margin-bottom:0px;">
                                        <label for="endd" class="col-sm-3 form-control-label"><b></b></label>
                                        <label for="endd" class="col-sm-3 form-control-label"><b>Sub Total</b></label>
                                        <label for="endd" class="col-sm-3 form-control-label"><b>VAT [5%]</b></label>
                                        <label for="endd" class="col-sm-3 form-control-label"><b>Grand Total</b></label>
                                        </div>  
                                   <div class="form-group row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-3">
                                        <input type="text" class="form-control" name="sub" value="<?php echo $subtotal; ?>" placeholder="" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                        <input type="text" class="form-control" name="vat" value="<?php echo $vat; ?>" placeholder="" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                        <input type="text" class="form-control" name="grand" value="<?php echo $grandtotal; ?>" placeholder="" readonly>
                                        </div>
                                   </div>
                                   
            
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>
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
  <?php include "../includes/footer.php";?>