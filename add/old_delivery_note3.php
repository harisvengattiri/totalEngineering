<?php ob_start();?>
<?php error_reporting(E_ERROR | E_PARSE);?>
<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
  $or=$_POST['order_referance'];
  $customer=$_POST['customer'];
  $site=$_POST['site'];
  
  
$status="NULL";
if(isset($_POST['submit']))
{
$id1=$_POST["id1"];    
$order_referance=$_POST["order_referance"];
$date=$_POST["date"];
$customer=$_POST["customer"];
$customersite=$_POST["site"];
$vehicle=$_POST["vehicle"];
$driver=$_POST["driver"];
$note=$_POST["note"];
$lpo=$_POST["lpo"];



$batch1=$_POST["batch"];
$batch1=array_filter($batch1);
if(count(array_unique($batch1))<count($batch1))
{
  $status='failed2';
}
else
{

       $item=$_POST["item"];
       $quantity=$_POST["quantity"];
       $thisquantity=$_POST["thisquantity"];
       $reqquantity=$_POST["reqquantity"];
       $n=sizeof($item);
       for($i=0;$i<$n;$i++)
       {
          if($reqquantity[$i]>=$thisquantity[$i])
                {
                $qwer[$i]=1;
                }
            else{
                $qwer[$i]=0; 
                }  
       }
            if (in_array("0", $qwer))
            {
            $check1='match';
            $status="failed3";
            }
          else
            {
            $check1='notmatch';
            }
               if($check1=='notmatch')
               {
       
       
       for($i=0;$i<$n;$i++)
       {
           if($quantity[$i]>=$thisquantity[$i])
                {
                $qwert[$i]=1;
                }
            else{
                $qwert[$i]=0; 
                } 
       }

          if (in_array("0", $qwert))
            {
            $check='match';
            $status="failed1";
            }
          else
            {
            $check='notmatch';
            }
if($check=='notmatch')
{

$sql = "INSERT INTO `delivery_note` (`id`,`order_referance`, `customer`, `customersite`, `lpo`, `date`, `vehicle`,`driver`,`note`) 
VALUES ('$id1','$order_referance', '$customer', '$customersite', '$lpo', '$date', '$vehicle', '$driver', '$note')";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $item=$_POST["item"];
       $reqquantity=$_POST["reqquantity"];
       $price=$_POST["price"];
       $thisquantity=$_POST["thisquantity"];
       $bundle=$_POST["bundle"];
       
       $coc=$_POST["coc"];
       $batch=$_POST["batch"];
       $n=sizeof($item);
       for($i=0;$i<$n;$i++)
       {
//       $sql3="UPDATE order_item SET balance=balance-'$thisquantity[$i]' where o_r='$order_referance' and item='$item[$i]'";
//       $conn->query($sql3);
       $sqlpdate = "SELECT pdate FROM batches_lots WHERE batch='$batch[$i]'";
       $querypdate=mysqli_query($conn,$sqlpdate);
       $fetchpdate=mysqli_fetch_array($querypdate);
       $pdate=$fetchpdate['pdate'];  
       
       $sql1 = "INSERT INTO `delivery_item` (`delivery_id`,`order_referance`, `item`, `date`, `reqquantity`, `thisquantity`, `price`, `bundle`,`pdate`,`coc`,`batch`) 
VALUES ('$id1','$order_referance', '$item[$i]', '$date', '$reqquantity[$i]', '$thisquantity[$i]', '$price[$i]', '$bundle[$i]', '$pdate', '$coc[$i]', '$batch[$i]')";
$conn->query($sql1);
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
}

}}
}
}
?>
    
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-6">
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
	<?php } else if($status=="failed1") {?>
    	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed! Due to Less Availability of Stock</span>
    </a></p>
       	<?php } else if($status=="failed2") {?>
    	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed! Cannot take twice from same batch</span>
    </a></p>
        <?php } else if($status=="failed3") {?>
    	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed! Cannot deliver more than order placed</span>
    </a></p>
        <?php } ?>
    
    
      <div class="box">
        <div class="box-header">
          <h2>Add Old Delivery Note</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/old_delivery_note" method="post">
               
               
               
              <div class="form-group row">
              <label for="customer" class="col-sm-4 form-control-label">Customer</label>
              <div class="col-sm-8">
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
              <label for="startd" align="" class="col-sm-4 form-control-label">Customer Site</label>
              <div class="col-sm-8">
                   <select class="form-control" name="site" id="site">
                        <option value="<?php echo $site;?>"><?php echo $site1;?></option>
                   </select>
              </div>
              </div>
            
            <div class="form-group row m-t-md">
              <div align="left" class="col-sm-offset-2 col-sm-12">
                <button name="submit1" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search</button>
              </div>
            </div>
          </form>  
               
             <?php if(isset($_POST['submit1']))
             {
                  $customer=$_POST['customer'];
                  $site=$_POST['site'];
                  $id1=$_POST['id1'];
             ?>
              <form role="form" action="<?php echo $baseurl;?>/add/old_delivery_note" method="post">
              <div class="form-group row">
                     <input type="hidden" name="customer" value="<?php echo $customer;?>">
                     <input type="hidden" name="site" value="<?php echo $site;?>">
                     <input type="hidden" name="id1" value="<?php echo $id1;?>">
              <label for="endd" class="col-sm-4 form-control-label">Sales Order No</label>
              <div class="col-sm-8">
                <select name="order_referance" id="get" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                <!--<select name="order_referance" id="cust" class="form-control">-->
                  <?php 
				$sql = "SELECT * FROM sales_order where customer='$customer' AND site='$site' ";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                              ?><option value="<?php echo ''; ?>"><?php echo '';?></option><?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["order_referance"]; ?>">SO <?php echo $row["order_referance"];?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
              </div>
              <div class="form-group row">
              <?php
              $date = date('d/m/y');
              ?>
              <label for="date" align="" class="col-sm-4 form-control-label">Current Date</label>
              <div class="col-sm-8">
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
              <div align="left" class="col-sm-offset-2 col-sm-12">
                <button name="submit2" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search</button>
              </div>
            </div>
          </form> 
          <?php } ?> 
             
             
            <?php if(isset($_POST['submit2']))
             {
                  $or=$_POST['order_referance'];
                  $customer=$_POST['customer'];
                  $site=$_POST['site'];
             ?>
               <form role="form" action="<?php echo $baseurl;?>/add/old_delivery_note" method="post">
                <div class="form-group row">
                <label for="endd" class="col-sm-4 form-control-label">Sales Order No</label>
                <div class="col-sm-8">
                <select name="order_referance" id="get" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                <!--<select name="order_referance" id="cust" class="form-control">-->
                  <?php 
                                ?><option value="<?php echo $or;?>"><?php echo $or;?></option><?php
				$sql = "SELECT order_referance FROM sales_order where customer='$customer' AND site='$site' ";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["order_referance"]; ?>">SO <?php echo $row["order_referance"];?></option>
				<?php 
				}} 
				?>
                </select>
                </div>
                </div>
                
<!--           <div class="form-group row m-t-md">
              <div align="" class="col-sm-offset-2 col-sm-12">
                <button name="submit2" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search</button>
              </div>
            </div>-->
            </form> 
          <?php } ?> 
             
             
    
    <?php
    if(isset($_POST['submit2'])) {
    $or = $_POST['order_referance'];
    $date = $_POST['date'];
    $sql1="SELECT * FROM sales_order WHERE order_referance='$or'";
    
    //echo $sql1;
    $result=$conn->query($sql1);   
    if($result->num_rows > 0){
    
       while($row=$result->fetch_assoc()) {
            
            $id=$row['id'];
            $site=$row['site'];
            $salesrep=$row['salesrep'];
            $customer=$row['customer'];
            $lpo=$row['lpo'];
        	
//       echo "<option value='" . $row['site'] . "'>" . $row['site'] . "</option>";
          ?>
    <form role="form" action="<?php echo $baseurl;?>/add/old_delivery_note" method="post">
                    <input type="text" name="customer" value="<?php echo $customer;?>" hidden="hidden">
                    <input type="text" name="site" value="<?php echo $site;?>" hidden="hidden">
                    <input type="text" name="order_referance" value="<?php echo $or;?>" hidden="hidden">
                    <input type="text" name="date" value="<?php echo $date;?>" hidden="hidden">
                    <input type="text" name="id1" value="<?php echo $id1;?>" hidden="hidden">
              
                    
              <div class="form-group row">
                    <?php 
                    $id1=$_POST['id1'];
                    ?>
               <label for="startd" class="col-sm-4 form-control-label">Delivery No</label>
              <div class="col-sm-8" id="veh">
                   <input type="text" class="form-control" value="<?php echo $id1;?>" name="id1" required>
              <!--<select class="form-control" id="veh"></select>-->
              </div>
               </div> 
                    
              <div class="form-group row">
              <label for="startd" class="col-sm-2 form-control-label">LPO</label>
              <div class="col-sm-4" id="veh">
                   <input type="text" class="form-control" value="<?php echo $lpo;?>" name="lpo" readonly>
              <!--<select class="form-control" id="veh"></select>-->
              </div>
              
              <label for="date" align="right" class="col-sm-2 form-control-label">Date</label>
              <div class="col-sm-4">
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
                    
                    
              <div class="form-group row">
              <label align="" for="startd" class="col-sm-2 form-control-label">Vehicle</label>
              <div class="col-sm-4" id="veh">
                   
                   <select name="vehicle" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                <!--<select name="order_referance" id="cust" class="form-control">-->
                  <?php 
				$sql = "SELECT vehicle,id FROM vehicle";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                              ?><option value="<?php echo ''; ?>"><?php echo '';?></option><?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["vehicle"];?></option>
				<?php 
				}} 
				?>
                 </select>
                   
              <!--<input type="text" class="form-control" name="vehicle">-->
              <!--<select class="form-control" id="veh"></select>-->
              </div>
           
             <label for="endd" align="right" class="col-sm-2 form-control-label">Driver</label>
              <div class="col-sm-4" id="nam">
              <select name="driver" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                <!--<select name="order_referance" id="cust" class="form-control">-->
                  <?php 
				$sql = "SELECT name,id FROM customers where type='Driver'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                              ?><option value="<?php echo ''; ?>"><?php echo '';?></option><?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"];?></option>
				<?php 
				}} 
				?>
                </select>
              </div> 
           </div>
            <div class="form-group row">
            <label for="date" align="" class="col-sm-2 form-control-label">Note</label>
            <div class="col-sm-10">
                 <textarea type="text" name="note" class="form-control has-value"></textarea>     
            </div>     
            </div>
                    
                    
 </div></div></div></div></div></div>
<div class="app-body" style="padding-bottom: 0px;padding-top:0px;margin-top:-20px;margin-bottom:45px;">
<!-- ############ PAGE START-->
<div class="padding" style="padding: 0rem 1.5rem;">
  <div class="row" style="margin-top:-95px;">
    <div class="col-md-12">
      <div class="box">
        <div class="box-divider m-a-0"></div>
        <div class="box-body">  
             
             
  </div></div></div></div></div></div>
<div class="app-body" style="padding-bottom: 0px;padding-top:0px;margin-top:-20px;margin-bottom:45px;">
<!-- ############ PAGE START-->
<div class="padding" style="padding: 0rem 1.5rem;">
  <div class="row" style="margin-top:-95px;">
    <div class="col-md-12">
      <div class="box">
        <div class="box-divider m-a-0"></div>
        <div class="box-body">            
             <div class="form-group row" style="text-align:center;">
                  <label for="endd" class="col-sm-2 form-control-label"><b>Item</b></label>
              <label for="endd" align="" class="col-sm-2 form-control-label"><b>Order Quantity</b></label>
              <label for="endd" align="" class="col-sm-2 form-control-label"><b>Batch No</b></label>
              <label for="endd" class="col-sm-2 form-control-label"><b>LOT Stock Available</b></label>
              <label for="endd"  class="col-sm-2 form-control-label"><b>Quantity</b></label>
              <label for="endd"  class="col-sm-2 form-control-label"><b>COC:No</b></label>
             </div>          
                    
    
<?php
    }  
       ?> 
               <hr><hr>

               <?php
                 $sql="SELECT * from order_item where item_id=$id";
                 $result = mysqli_query($conn, $sql);
                 if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
                                     $id=$row['id'];
                                     $item=$row['item'];
                                     $quantityreq=$row['quantity'];
                                     
                                     $sqlbal="SELECT thisquantity from delivery_item where order_referance='$or' and item=$item";
                                     $resultbal = mysqli_query($conn, $sqlbal);
                                     $sell=0;
                                     while($rowbal = mysqli_fetch_assoc($resultbal)) 
                                     {
                                          $sell=$sell+$rowbal['thisquantity'];
                                     }
                                     $sqlret="SELECT returnqnt from stock_return where o_r='$or' and item=$item";
                                     $resultret = mysqli_query($conn, $sqlret);
                                     $ret=0;
                                     while($rowret = mysqli_fetch_assoc($resultret)) 
                                     {
                                          $ret=$ret+$rowret['returnqnt'];
                                     }
                                     
                                     $balance=$quantityreq+$ret-$sell;
                                     $price=$row['unit'];
                                     
                                     
                                        $sqlitem="SELECT items from items where id='$item'";
                                        $queryitem=mysqli_query($conn,$sqlitem);
                                        $fetchitem=mysqli_fetch_array($queryitem);
                                        $item1=$fetchitem['items'];
                                     
                                     
                                   $sql1="SELECT batch FROM batches_lots WHERE item='".$item."' and quantity !=0";
                                   $result1=$conn->query($sql1);
                                   if($result1->num_rows > 0)
                                   {
                                   
               ?>
             
           <div class="form-group row">
                    <div class="col-sm-2">
                        <input name="price[]" value="<?php echo $price;?> "hidden="hidden">
                        <input name="lpo" value="<?php echo $lpo;?> "hidden="hidden">
                        <input type="text" class="form-control" value="<?php echo $item1;?>" id="endd" placeholder="Item" readonly>
                        <input name="item[]" value="<?php echo $item;?>"hidden="hidden">
                    </div>
                   <div class="col-sm-2">
                     <input type="text" class="form-control" name="reqquantity[]" value="<?php echo $balance;?>" id="req<?php echo $id;?>" placeholder="Quantity" readonly>
                   </div>
                   <div class="col-sm-2">
                        <select class="form-control" name="batch[]" id="batch<?php echo $id;?>">
                              <?php 
                             $sql1="SELECT * FROM batches_lots WHERE item='".$item."' and quantity !=0";
                                        $result1=$conn->query($sql1);
                                        if($result1->num_rows > 0){
                                             ?> <option></option> <?php
                                             while($row1=$result1->fetch_assoc()) 
                                                     {
                                                        $batch=$row1['batch'];
                             ?>
                             <option> <?php echo $batch;?> </option>
                                        <?php } } ?>
                        </select>
                   </div>
               
               <!--<div id="quantity<?php echo $id;?>"></div>-->
       
               
               <div class="col-sm-2">
               <input type="text" class="form-control" name="quantity[]" id="quantity<?php echo $id;?>" value="<?php echo $quantity;?>" readonly>
               </div>
               <div class="col-sm-2">
                    <input type="number" min="1" step="any" class="form-control" name="thisquantity[]" placeholder="Quantity From this Batch" id="this<?php echo $id;?>">
              </div>
               
              <div class="col-sm-2">
                <input type="text" class="form-control" name="coc[]" id="coc<?php echo $id;?>" value="<?php echo $coc;?>" id="endd" readonly>
              </div>
      </div>
               
               
         <div class="form-group row">
               <div class="col-sm-2">
                   <input name="price[]" value="<?php echo $price;?> "hidden="hidden">
                   <input name="lpo" value="<?php echo $lpo;?> "hidden="hidden">
                   <input type="text" class="form-control" value="<?php echo $item1;?>" id="endd" placeholder="Item" readonly>
                   <input name="item[]" value="<?php echo $item;?>"hidden="hidden">
               </div>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="reqquantity[]" value="<?php echo $balance;?>" id="req1<?php echo $id;?>" placeholder="Quantity" readonly>
              </div>
              <div class="col-sm-2">
                   <select class="form-control" name="batch[]" id="batch<?php echo $id+10;?>">
                        <?php 
                        $sql1="SELECT * FROM batches_lots WHERE item='".$item."' and quantity !=0";
                                   $result1=$conn->query($sql1);
                                   if($result1->num_rows > 0){
                                        ?> <option></option> <?php
                                        while($row1=$result1->fetch_assoc()) 
                                                {
                                                   $batch=$row1['batch'];
                        ?>
                        <option> <?php echo $batch;?> </option>
                                   <?php } } ?>
                   </select>
              </div>
               <!--<div id="quantity<?php echo $id+10;?>"></div>-->
               
                       <div class="col-sm-2">
                       <input type="text" class="form-control" name="quantity[]" id="quantity<?php echo $id+10;?>" value="<?php echo $quantity;?>" readonly>
                       </div>
                      
                       <div class="col-sm-2">
                            <input type="number" min="1" step="any" class="form-control" name="thisquantity[]" placeholder="Quantity From this Batch" id="this1<?php echo $id;?>">
                      </div>
                      
                      <div class="col-sm-2">
                        <input type="text" class="form-control" name="coc[]" id="coc<?php echo $id+10;?>" value="<?php echo $coc;?>" id="endd" readonly>
                      </div>
      </div> 
           <style>
               hr { 
                display: block;
                margin-top: 0.5em;
                margin-bottom: 0.5em;
                margin-left: auto;
                margin-right: auto;
                border-style: inset;
                border-width: 1px;
                border-top: 1px solid rgba(14, 4, 4, 0.74);
                } 
          </style>
          <hr>
 <script type="text/javascript">
 $(document).ready(function() {
  $("#batch<?php echo $id;?>").change(function() {
    var country_id = $(this).val();
    if(country_id != "") {
      $.ajax({
        url:"getquantity",
        data:{c_id:country_id},
        type:'POST',
        success:function(response) {
          var resp = $.trim(response);
          $("#quantity<?php echo $id;?>").val(resp);
        }
      });
    } else {
      $("#quantity<?php echo $id;?>").html("<option value=''>------- Select --------</option>");
    }
  });
});
</script>
 <script type="text/javascript">
 $(document).ready(function() {
  $("#batch<?php echo $id+10;?>").change(function() {
    var country_id = $(this).val();
    if(country_id != "") {
      $.ajax({
        url:"getquantity",
        data:{c_id:country_id},
        type:'POST',
        success:function(response) {
          var resp = $.trim(response);
          $("#quantity<?php echo $id+10;?>").val(resp);
        }
      });
    } else {
      $("#quantity<?php echo $id+10;?>").html("<option value=''>------- Select --------</option>");
    }
  });
});
</script>

 <script type="text/javascript">
 $(document).ready(function() {
  $("#batch<?php echo $id;?>").change(function() {
    var country_id = $(this).val();
    if(country_id != "") {
      $.ajax({
        url:"get_coc",
        data:{c_id:country_id},
        type:'POST',
        success:function(response) {
          var resp = $.trim(response);
          $("#coc<?php echo $id;?>").val(resp);
        }
      });
    } else {
      $("#coc<?php echo $id;?>").html("<option value=''>------- Select --------</option>");
    }
  });
});
</script>
 <script type="text/javascript">
 $(document).ready(function() {
  $("#batch<?php echo $id+10;?>").change(function() {
    var country_id = $(this).val();
    if(country_id != "") {
      $.ajax({
        url:"get_coc",
        data:{c_id:country_id},
        type:'POST',
        success:function(response) {
          var resp = $.trim(response);
          $("#coc<?php echo $id+10;?>").val(resp);
        }
      });
    } else {
      $("#coc<?php echo $id+10;?>").html("<option value=''>------- Select --------</option>");
    }
  });
});
</script>

   <script>
        $(document).ready(function(){
           $('#sub').click(function(){
           var a=$('#this<?php echo $id;?>').val();
           var b=$('#req<?php echo $id;?>').val();
           var c=$('#quantity<?php echo $id;?>').val();
             if (parseInt(a) > parseInt(b) || (parseInt(a) > parseInt(c))) {
             alert('Delivery is more than available stock in the batch or required order');
             return false;
             }
            });
        });
  </script>
  
  <script>
        $(document).ready(function(){
           $('#sub').click(function(){
           var d=$('#this1<?php echo $id;?>').val();
           var e=$('#req1<?php echo $id;?>').val();
           var f=$('#quantity<?php echo $id+10;?>').val();
             if (parseInt(d) > parseInt(e) || parseInt(d) > parseInt(f)){
             alert('Delivery is more than available stock in the batch or required order');
             return false;
             }
            });
        });
  </script>

                                <hr>
                               
                                <?php } } } ?>

               <div class="form-group row m-t-md">
                <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="submit" type="submit" id="sub" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>
               </div>
            </div>
            <?php } } ?>   
            
               <!--<div id="all"></div>-->
            
               
<!--               <div class="form-group row">
               <label for="endd" class="col-sm-2 form-control-label">Sales Representative</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="salesrepresentative" id="endd" placeholder="Sales Representative">
              </div>
               </div>-->
              
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