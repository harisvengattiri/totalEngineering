<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$or=$_POST["or"];
$dn=$_POST["dn"];
$actualdn=$_POST["actualdn"];
print_r($actualdn);
             $k=sizeof($actualdn);
             $transportation=0;
             for($i=0;$i<$k;$i++)
             {
             $mydn=$actualdn[$i];    
                    $sqltrans="SELECT transport from delivery_note where id='$mydn'";
                    $querytrans=mysqli_query($conn,$sqltrans);
                    $fetchtrans=mysqli_fetch_array($querytrans);
                    $trans=$fetchtrans['transport'];
                    $transportation=$transportation+$trans;
             }
             echo $transportation;


$lpo=$_POST["lpo"];

$date=$_POST["date"];
$customer=$_POST["customer"];
$site=$_POST["site"];

$subtotal=$_POST["sub"];
$vat=$_POST["vat"];
$grand=$_POST["grand"];

$inv=$_POST["inv"];

//$sql = "INSERT INTO `invoice` (`id`, `customer`, `site`,`lpo`,`o_r`, `date`, `total`, `vat`, `grand`) 
//VALUES ('$inv', '$customer', '$site', '$lpo', '$or', '$date', '$subtotal', '$vat', '$grand')";
//if ($conn->query($sql) === TRUE) {
//    $status="success";
//       $last_id = $conn->insert_id;
//       $item=$_POST["item"];
//       $reqquantity=$_POST["reqquantity"];
//       $pdate=$_POST["pdate"];
//       $unit=$_POST["unit"];
//       $total=$_POST["total"];
//       $n=sizeof($item);
//       for($i=0;$i<$n;$i++)
//       {
//       $sql1 = "INSERT INTO `invoice_item` (`invoice_id`, `dn`, `item`, `pdate`, `quantity`, `unit`,`total`) 
//VALUES ('$inv', '$dn[$i]', '$item[$i]', '$pdate[$i]', '$reqquantity[$i]', '$unit[$i]', '$total[$i]')";
//$conn->query($sql1);
//
//$sql2="UPDATE delivery_note SET invoiced = 'yes' WHERE id='$dn[$i]'";
//$conn->query($sql2);
//               
//       }
//       
//       $date1=date("d/m/Y h:i:s a");
//       $username=$_SESSION['username'];
//       $code="INV".$last_id;
//       $query=mysqli_real_escape_string($conn, $sql);
//       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
//                  values ('$date1', 'add', '$code', '$username', '$query')";
//       $result = mysqli_query($conn, $sql);
//} else {
//    $status="failed";
//}

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
	<?php }?>
      <div class="box">
        <div class="box-header">
          <h2>Add New Invoice</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/old_invoice2" method="post">
             
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
                    
                    
				$sql="SELECT customer AS c FROM delivery_note JOIN customers ON delivery_note.customer = customers.id
                                             WHERE delivery_note.invoiced='' GROUP BY delivery_note.customer ORDER BY customers.name ";
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
				<option value="<?php echo $cst;?>"><?php echo $cust;?></option>
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
              $date1 = date('d/m/y');
              if($_POST['date'])
              {
              $date=$_POST['date'];
              $readonly='readonly';
              }
              ?>
              <label for="date" class="col-sm-3 form-control-label">Current Date</label>
              <div class="col-sm-6">
                   <input type="text" name="date" value="<?php echo $date;?>" id="date" placeholder="Date" <?php echo $readonly;?> class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                <button name="submitso" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search Orders</button>
              </div>
            </div>
          </form>  
         
             <?php if(isset($_POST['submitso']))
             {
                  $customer=$_POST['customer'];
                  $site=$_POST['site'];
                  $date=$_POST['date'];
             ?>
            
             <form role="form" action="<?php echo $baseurl;?>/add/old_invoice2" method="post">
                    <input type="text" name="customer" value="<?php echo $customer;?>" hidden="hidden">
                    <input type="text" name="site" value="<?php echo $site;?>" hidden="hidden">
                    <input type="text" name="date" value="<?php echo $date;?>" hidden="hidden">
                    <div class="form-group row">
                    <label class="col-sm-3 form-control-label">Sales Order</label>
                    <div class="col-sm-6">
                    <!--<select name="dn" class="form-control select2">-->
                    <select name="so" class="form-control select2" placeholder="Item" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">     
                         <?php  
                           
//                           $sql = "SELECT order_referance FROM sales_order where customer='$customer' AND site='$site'";
				$sql = "SELECT order_referance FROM delivery_note where customer='$customer' AND customersite='$site' AND invoiced='' GROUP BY order_referance";
                                $result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                while($row = mysqli_fetch_assoc($result)) 
				{
                                  $so=$row["order_referance"]; 
                                  ?><option value="<?php echo $so;?>">PO|<?php echo $so;?></option><?php
                                }}
                        ?>
                    </select>
                    </div></div>
             
                  <div class="form-group row m-t-md">
                    <div align="" class="col-sm-offset-2 col-sm-12">
                      <button name="submit1" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search Delivery</button>
                    </div>
                  </div>  
                    
             </form>
             <?php } ?>
             
             
             <?php if(isset($_POST['submit1']))
             {
                  $customer=$_POST['customer'];
                  $site=$_POST['site'];
                  $date=$_POST['date'];
                  $so=$_POST['so'];
             ?>
             
             <form role="form" action="<?php echo $baseurl;?>/add/old_invoice2" method="post">
                  
                    <div class="form-group row">
                    <label class="col-sm-3 form-control-label">Sales Order</label>
                    <div class="col-sm-6">
                         <input class="form-control" type="text" name="so" value="<?php echo $so;?>" readonly>     
                    </div></div>
                  
                  
                    <input type="text" name="customer" value="<?php echo $customer;?>" hidden="hidden">
                    <input type="text" name="site" value="<?php echo $site;?>" hidden="hidden">
                    <input type="text" name="date" value="<?php echo $date;?>" hidden="hidden">
                    <div class="form-group row">
                    <label class="col-sm-3 form-control-label">Select Delivery</label>
                    <div class="col-sm-6">
                    <!--<select name="dn" class="form-control select2">-->
                    <select name="dn[]" multiple id="multiple" class="form-control select2-multiple" multiple data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}"">     
                         <?php  $sql = "SELECT id FROM delivery_note where order_referance='$so' AND invoiced=''";
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
                    
                    <div class="form-group row">
                    <label class="col-sm-3 form-control-label">Invoice No</label>
                    <div class="col-sm-6">
                         <input type="text" name="inv" class="form-control" required>
                    </div></div>
             
                  <div class="form-group row m-t-md">
                    <div align="" class="col-sm-offset-2 col-sm-12">
                      <button name="submit2" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Invoice</button>
                    </div>
                  </div>  
                    
             </form>
             <?php } ?>
             
             <?php if(isset($_POST['submit2']))
             {
                  $customer=$_POST['customer'];
                  $site=$_POST['site'];
                  $date=$_POST['date'];
                  $so=$_POST['so'];
                  $inv=$_POST['inv'];
                  $dn1=$_POST['dn'];
                  $cnt=count($dn1);
                   
             ?>
             
               <form role="form" action="<?php echo $baseurl;?>/add/old_invoice2" method="post">
                    <input type="text" name="customer" value="<?php echo $customer;?>" hidden="hidden">
                    <input type="text" name="site" value="<?php echo $site;?>" hidden="hidden">
                    <input type="text" name="date" value="<?php echo $date;?>" hidden="hidden">
                    
                                        <?php
                                             $sql2="SELECT lpo FROM delivery_note where order_referance='$so'";
                                             $query2=mysqli_query($conn,$sql2);
                                             $fetch2=mysqli_fetch_array($query2);
                                             $lpo=$fetch2['lpo']; 
                                        ?>
                    
                                        <div class="form-group row" style="text-align:center;margin-bottom:0px;">
                                        <label for="endd" class="col-sm-3 form-control-label"><b style="color:red;">Invoice No:</b></label>
                                        <div class="col-sm-3">
                                        <input type="text" class="form-control" name="inv" value="<?php echo $inv; ?>" placeholder="" readonly>
                                        </div>
                                        </div>
                    
                                        <div class="form-group row" style="text-align:center;margin-bottom:0px;">
                                        <label for="endd" class="col-sm-3 form-control-label"><b style="color:red;">Sales Order Referance</b></label>
                                        <div class="col-sm-3">
                                        <input type="text" class="form-control" name="or" value="<?php echo $so; ?>" placeholder="" readonly>
                                        <input type="hidden" name="lpo" value="<?php echo $lpo; ?>">
                                        </div>
                                        </div>
                    
                                <div class="form-group row" style="text-align:center;margin-bottom:0px;">
                                <label for="endd" align="" class="col-sm-2 form-control-label"><b>Delivery</b></label>
                                <label for="endd" align="" class="col-sm-3 form-control-label"><b>Item</b></label>
                                <label for="endd" align="" class="col-sm-2 form-control-label"><b>Date</b></label>   
                                <label for="endd" align="" class="col-sm-2 form-control-label"><b>Quantity</b></label>  
                                <label for="endd" align="" class="col-sm-1 form-control-label"><b>Price</b></label>
                                <label for="endd" align="" class="col-sm-2 form-control-label"><b>Total</b></label>
                                </div>
                    
                    <hr>
                    
                              <?php
                               for($i=0;$i<$cnt;$i++) 
                               {
                               $dn=$dn1[$i];
                              ?>
                              <input type="text" name="actualdn[]" value="<?php echo $dn;?>" hidden="hidden">  
                              <?php
                              $sql1="SELECT * from delivery_item where delivery_id='$dn' and thisquantity!=0";
                              $result1 = mysqli_query($conn, $sql1);
                              if (mysqli_num_rows($result1) > 0) 
				{
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
                                     $ddate=$row1['date'];
                                     $total=$quantity*$unit;
                                     $subtotal=$subtotal+$total;
                                     
                                    ?>
                                    
              <div class="form-group row">
              <div class="col-sm-2">
                <input type="text" class="form-control" name="dn[]" value="<?php echo $dn; ?>" placeholder="" readonly>
              </div>
              <div class="col-sm-3">
                   <input type="text" class="form-control" value="<?php echo $item;?>" placeholder="Item" readonly>
                   <input name="item[]" value="<?php echo $item1; ?>" hidden="hidden">
              </div>
               <!--<label for="endd" align="right" class="col-sm-1 form-control-label">Date</label>-->
              <div class="col-sm-2">
                <input type="hidden" name="pdate[]" value="<?php echo $pdate;?>">
                <input type="text" class="form-control" value="<?php echo $ddate;?>" id="endd" readonly>
              </div>
<!--               <label for="endd" align="right" class="col-sm-1 form-control-label">Quantity</label>-->
              <div class="col-sm-2">
                <input type="text" class="form-control" name="reqquantity[]" value="<?php echo $quantity;?>" id="endd" readonly>
              </div>
<!--               <label for="endd" align="right" class="col-sm-1 form-control-label">Unit Price</label>-->
              <div class="col-sm-1">
                <input type="text" class="form-control" name="unit[]" value="<?php echo $unit;?>" id="endd" readonly>
              </div>
               <div class="col-sm-2">
                    <input type="text" class="form-control" name="total[]" value="<?php echo $total;?>" id="endd" readonly>
                    
              </div>
              </div>
                <?php }}}  ?>  
                    
                               <?php 
                                 $vat = (5 / 100) * $subtotal;
                                 $grandtotal=$vat+$subtotal;
                                ?> 
                                        <hr><hr>
                                        <div class="form-group row" style="text-align:center; margin-bottom:0px;">
                                        <label for="endd" class="col-sm-3 form-control-label"><b></b></label>
                                        <label for="endd" class="col-sm-3 form-control-label"><b>Sub Total</b></label>
                                        <label for="endd" class="col-sm-3 form-control-label"><b>VAT [5%]</b></label>
                                        <label for="endd" class="col-sm-3 form-control-label"><b>Grand Total</b></label>
                                        </div>  
                                   <div class="form-group row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-3">
                                        <input type="text" class="form-control" name="sub" value="<?php echo $subtotal;?>" placeholder="" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                        <input type="text" class="form-control" name="vat" value="<?php echo $vat;?>" placeholder="" readonly>
                                        </div>
                                        <div class="col-sm-3">
                                        <input type="text" class="form-control" name="grand" value="<?php echo $grandtotal;?>" placeholder="" readonly>
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