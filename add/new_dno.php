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

if($_GET)
{
    $status = $_GET['status'];
}

if(isset($_POST['formSubmit']))
{
    $quantity_array=$_POST["thisquantity"];
    $quantity_sum = array_sum($quantity_array);
    
    if(isset($_SESSION['userid']) && $quantity_sum > 0)
    {
    $dno = $_POST["dno"];
        
    $order_referance=$_POST["order_referance"];
    $sqlrep="SELECT salesrep FROM sales_order WHERE order_referance='$order_referance'";
    $queryrep=mysqli_query($conn,$sqlrep);
    $fetchrep=mysqli_fetch_array($queryrep);
    $rep=$fetchrep['salesrep'];

    $date=$_POST["date"];
    $customer=$_POST["customer"];
    $customersite=$_POST["site"];
    $vehicle=$_POST["vehicle"];
    $driver=$_POST["driver"];
    
    $vehicleWeight = $_POST["vehicleWeight"];
    $allowedWeight = $_POST["allowedWeight"];
    $currentWeight = $_POST["currentWeight"];

    $note=$_POST["note"];
    $lpo=$_POST["lpo"];
    $transport=$_POST["transport"];
    $transport=($transport != NULL) ? $transport : 0;

    $curr_req = $_POST["curr_req"];

    $batch1=$_POST["batch"];
    $batch1=array_filter($batch1);
    if(count(array_unique($batch1)) < count($batch1))
    {
      $status='failed2';
    }
    else
    {

    $crdt_bal = $_POST["crdt_bal"];
    $crdt_bal = ($crdt_bal != NULL) ? $crdt_bal : 0;
    $lmt = $_POST["limit"];
      $items=$_POST["item"];
      $quantity=$_POST["quantity"];
      $thisquantity=$_POST["thisquantity"];
      $reqquantity=$_POST["reqquantity"];


      function compareDeliveryAndOrder($items, $reqquantity, $thisquantity, $baseurl) {
        $itemAssoc = [];
        $delivery_flag = 0;
      
        for($i = 0; $i < sizeof($items); $i++) {
          $item = $items[$i];
          $order = $reqquantity[$i];
          $delivery = $thisquantity[$i];
      
          if (isset($itemAssoc[$item])) {
            $itemAssoc[$item]['delivery'] += $delivery;
              if($itemAssoc[$item]['delivery'] > $itemAssoc[$item]['order']) {
                $delivery_flag++;
              }
          } else {
            $itemAssoc[$item] = ['order' => $order, 'delivery' => $delivery];
              if($itemAssoc[$item]['delivery'] > $itemAssoc[$item]['order']) {
                $delivery_flag++;
              }
          } 
        }
        if($delivery_flag > 0) {
          echo '<script>window.location.href = "' .$baseurl. '/add/new_dno?status=failed3"</script>';
          exit();
        }
      }

    compareDeliveryAndOrder($items, $reqquantity, $thisquantity, $baseurl);


    $batch=$_POST["batch"];
    $price=$_POST["price"];
      $sitem=sizeof($items);
      for($i=0;$i<$sitem;$i++)
      {
        if($batch[$i] != NULL)
        {
          if($thisquantity[$i] != NULL && $price[$i] != NULL) {
          $amt123[$i]=$thisquantity[$i]*$price[$i];
          $amt123[$i] = number_format($amt123[$i], 2, '.', '');
          $amttotal123 = $amttotal123+$amt123[$i];
          }
        }
      }
      $curr_bal = $crdt_bal + $amttotal123;
    

    if($curr_bal > $lmt & $lmt != 0)
    {
    echo "<script type=\"text/javascript\">".
         "window.location='$baseurl/add/new_dno?status=failed4';".
         "</script>";
    }
    else {
      $required = $curr_req;
      $taken=array_sum($thisquantity);

      if($taken > $required) {
        $check = 'notproceed';
        $status="failed3";
      } else {
        $check = 'proceed';
      }

if($check == 'proceed')
{
		  
$token=$_POST["token"];
    
$sql = "INSERT INTO `delivery_note` (`id`,`token`,`order_referance`,`rep`,`customer`, `customersite`, `lpo`, `date`, `vehicle`,`driver`,`vehicleWeight`,`allowedWeight`,`currentWeight`,`note`,`transport`) 
VALUES ('$dno','$token','$order_referance','$rep','$customer', '$customersite', '$lpo', '$date', '$vehicle', '$driver', '$vehicleWeight', '$allowedWeight', '$currentWeight', '$note', '$transport')";
if ($conn->query($sql) === TRUE) {
    $status="success";
    
       $last_id = $conn->insert_id;
       $item=$_POST["item"];
       $comment=$_POST["comment"];
       $reqquantity=$_POST["reqquantity"];
       $price=$_POST["price"];
       $thisquantity=$_POST["thisquantity"];
       $bundle=$_POST["bundle"];
       
          if($taken==$required)
		      {
		      $sqlup="UPDATE sales_order SET flag=1 WHERE order_referance='$order_referance'";
		      $queryup=mysqli_query($conn,$sqlup);
		      }
       
       $coc=$_POST["coc"];
       $batch=$_POST["batch"];
       $n=count($item);
       for($i=0;$i<$n;$i++)
       {
            if($batch[$i] != NULL)
            {
              $sqlpdate = "SELECT pdate FROM batches_lots WHERE batch='$batch[$i]'";
              $querypdate=mysqli_query($conn,$sqlpdate);
              $fetchpdate=mysqli_fetch_array($querypdate);
              $pdate=$fetchpdate['pdate'];
              
              if($thisquantity[$i] != NULL && $price[$i] != NULL) {
              $amt[$i]=$thisquantity[$i]*$price[$i];
              $amt[$i] = number_format($amt[$i], 2, '.', '');
              $amttotal = $amttotal+$amt[$i];
              $sql1 = "INSERT INTO `delivery_item` (`delivery_id`,`order_referance`, `item`, `comment`, `date`, `reqquantity`, `thisquantity`, `price`,`amt`, `bundle`,`pdate`,`coc`,`batch`) 
              VALUES ('$last_id','$order_referance', '$item[$i]','$comment[$i]', '$date', '$reqquantity[$i]', '$thisquantity[$i]', '$price[$i]', '$amt[$i]', '$bundle[$i]', '$pdate', '$coc[$i]', '$batch[$i]')";
              $conn->query($sql1);
              }
            }
       }
       $amttotal = $amttotal+$transport;
       $amttotal = number_format($amttotal, 2, '.', '');
       $sql2 = "UPDATE delivery_note SET total = $amttotal WHERE id = $last_id";
       $result2 = mysqli_query($conn, $sql2);
       
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="DN".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       
      header("Location: goto?id=$last_id&or=$order_referance");
} else {
    $status="failed";
}
}

}

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
         <?php } else if($status=="failed4") {?>
    	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed! Credit Excess</span>
    </a></p>
        <?php } ?>
    
      <div class="box">
        <div class="box-header">
          <h2>Add New Delivery Note</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/new_dno" method="post">

              <div class="form-group row">
              <label for="customer" class="col-sm-4 form-control-label">Customer</label>
              <div class="col-sm-8">
		        <select name="customer" id="customer" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
                    $customer=$_POST['customer'];
                    $site=$_POST['site'];
                    
                    $sqlcust="SELECT name from customers where `id`='$customer'";
                    $querycust=mysqli_query($conn,$sqlcust);
                    $fetchcust=mysqli_fetch_array($querycust);
                    $cust=$fetchcust['name'];
                    
                    $sqlsite="SELECT p_name from customer_site where `id`='$site'";
                    $querysite=mysqli_query($conn,$sqlsite);
                    $fetchsite=mysqli_fetch_array($querysite);
                    $site1=$fetchsite['p_name'];
                    
                    
				$sql = "SELECT name,id FROM customers where type='Company' AND status != 'banned' order by name";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
        ?> <option value="<?php echo $customer;?>"><?php echo $cust;?></option> <?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>">[CST-<?php echo $row["id"]?>] <?php echo $row["name"]?></option>
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
                  
                    $sqlcust1="SELECT op_bal FROM customers where id='$customer'";
                    $querycust1=mysqli_query($conn,$sqlcust1);
                    $fetchcust1=mysqli_fetch_array($querycust1);
                    $opening = $fetchcust1['op_bal'];
                    $opening = ($opening != null) ? $opening : 0;
                  
                  $sqllimit="SELECT credit,credit1,status from credit_application where company=$customer";
                  $querylimit=mysqli_query($conn,$sqllimit);
                  $fetchlimit=mysqli_fetch_array($querylimit);
                  $limit1=$fetchlimit['credit'];
                  $limit1 = ($limit1 != NULL) ? $limit1 : 0;
                  $limit2=$fetchlimit['credit1'];
                  $limit2 = ($limit2 != NULL) ? $limit2 : 0;
                  $limit=$limit1+$limit2;
                  // if($limit == 0){ $limit = NULL ;}
                  $status=$fetchlimit['status'];
                  
                  $sqlinvo="SELECT ROUND(SUM(total), 2) AS grand,sum(transport) as total_transport FROM delivery_note WHERE customer = $customer";
                  $queryinvo=mysqli_query($conn,$sqlinvo);
                  $fetchinvo=mysqli_fetch_array($queryinvo);
                  $invoamt=$fetchinvo['grand'];
                  $invoamt=($invoamt != NULL) ? $invoamt : 0;
                  $invoamt=$invoamt*1.05;
                  $invoamt = $invoamt + $opening;

                  $sqlrpt="SELECT sum(grand) AS amount from reciept where customer = $customer AND status = 'Cleared'";
                  $queryrpt=mysqli_query($conn,$sqlrpt);
                  $fetchrpt=mysqli_fetch_array($queryrpt);
                  $amountrpt=0+$fetchrpt['amount'];
                  $amountrpt=($amountrpt != NULL) ? $amountrpt : 0;
                  
                  $sqlcdt="SELECT sum(`total`) AS cdt FROM `credit_note` WHERE `customer`=$customer" ;
                  $querycdt=mysqli_query($conn,$sqlcdt);
                  $fetchcdt=mysqli_fetch_array($querycdt);
                  $amountcdt=0+$fetchcdt['cdt'];
                  $amountcdt=($amountcdt != NULL) ? $amountcdt : 0;
                  
                  $sqlrfd="SELECT sum(`amount`) AS rfd FROM `refund` WHERE `customer`=$customer" ;
                  $queryrfd=mysqli_query($conn,$sqlrfd);
                  $fetchrfd=mysqli_fetch_array($queryrfd);
                  $amountrfd=0+$fetchrfd['rfd'];
                  $amountrfd=($amountrfd != NULL) ? $amountrfd : 0;
                  
                  $current_bal = ($invoamt+$amountrfd)-($amountrpt+$amountcdt);
                  
             ?>
              <form role="form" action="<?php echo $baseurl;?>/add/new_dno" method="post">
                   
              <div class="form-group row">
                   <input type="hidden" name="customer" value="<?php echo $customer;?>">
                   <input type="hidden" name="site" value="<?php echo $site;?>">
                   <input type="hidden" name="status" value="<?php echo $status;?>">
                   <input type="hidden" name="lmt" value="<?php echo $limit;?>">
                   
                   <?php
                      $availableLimit = ($limit-$current_bal);
                      $availableLimit = ($availableLimit != NULL) ? $availableLimit : 0;
                      $availableLimit = number_format($availableLimit, 2, '.', '');
                   ?>
                   
                   <div class="col-sm-4">
                      <label for="endd" class="col-sm-12 form-control-label"><b>Credit Limit</b></label>
                      <input type="text" class="form-control" name="limit" value="<?php echo $limit;?>" placeholder="<?php if($limit == 0){ echo '';} ?>" readonly>
                   </div>
                   <div class="col-sm-4">
                        <label for="endd" class="col-sm-12 form-control-label"><b>Total Outstanding</b></label>
                        <input type="text" class="form-control" value="<?php echo custom_money_format('%!i', $current_bal);?>" readonly> 
                        <input type="hidden" class="form-control" name="bal" value="<?php echo $current_bal;?>" readonly>
                   </div>
                   <div class="col-sm-4">
                      <label class="col-sm-12 form-control-label"><b>Available Limit</b></label>
                      <input type="text" class="form-control" name="av_limit" value="<?php echo $availableLimit;?>" readonly>
                   </div>
              </div>     
                   
              <div class="form-group row">
              <label for="endd" class="col-sm-4 form-control-label">Sales Order No</label>
              <div class="col-sm-8">
                <select name="order_referance" id="get" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                <!--<select name="order_referance" id="cust" class="form-control">-->
                  <?php 
				$sql = "SELECT * FROM sales_order where customer='$customer' AND site='$site' AND status != 1 AND flag=0";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                ?><option value="<?php echo ''; ?>"><?php echo '';?></option><?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["order_referance"]; ?>">PO <?php echo $row["order_referance"];?>/<?php echo $row["lpo"];?></option>
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
                   <input type="text" name="date" value="<?php echo $date;?>" id="date" required placeholder="Production Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
    
    <?php
    if(isset($_POST['submit2'])) {
       
         $limit = $_POST['limit'];
         $bal = $_POST['bal'];
         $availableLimit = $_POST['av_limit'];
         $status = $_POST['status'];
         
         if($status == 'Rejected')
              {
              header("Location: $baseurl/add/new_dno?status=failed_due_Rejection_from_ADMIN");
              }
         
         if($bal >= $limit & $limit != 0)
              {
              header("Location: $baseurl/add/new_dno?status=failed_due_to_credit_excess");
              }
              
         
         
    $or = $_POST['order_referance'];
    $date = $_POST['date'];
    $sql1="SELECT * FROM sales_order WHERE order_referance='$or'";
    
    //echo $sql1;
    $result=$conn->query($sql1);   
    if($result->num_rows > 0){
    
       while($row=$result->fetch_assoc()) {
            
            $saleId=$row['id'];
            $qtn=$row['qtn'];
            $site=$row['site'];
            $salesrep=$row['salesrep'];
            $customer=$row['customer'];
            $lpo=$row['lpo'];
            $transport=$row['transport'];
            $transport=($transport != NULL) ? $transport : 0;

              $sqlOrd = "SELECT sum(quantity) AS ord_qnt FROM `order_item` WHERE `o_r`='$or'";
              $resultOrd = $conn->query($sqlOrd);
              $rowOrd = $resultOrd->fetch_assoc();
              $ord_qnt = $rowOrd['ord_qnt'];
              $ord_qnt = ($ord_qnt != NULL) ? $ord_qnt : 0;
              $sqlDel = "SELECT sum(thisquantity) AS sold_qnt FROM `delivery_item` WHERE `order_referance`='$or'";
              $resultDel = $conn->query($sqlDel);
              $rowDel = $resultDel->fetch_assoc();
              $sold_qnt = $rowDel['sold_qnt'];
              $sold_qnt = ($sold_qnt != NULL) ? $sold_qnt : 0;
              $sqlret = "SELECT sum(returnqnt) AS ret_qnt FROM `stock_return` WHERE `o_r`='$or'";
              $resultret = mysqli_query($conn, $sqlret);
              $rowret = mysqli_fetch_assoc($resultret);
              $ret_qnt = $rowret['ret_qnt'];
              $ret_qnt = ($ret_qnt != NULL) ? $ret_qnt : 0;

              $curr_req = ($ord_qnt+$ret_qnt)-$sold_qnt;
          ?>
                <form id="deliveryForm" role="form" action="<?php echo $baseurl;?>/add/new_dno" method="post">
                    <input type="text" name="customer" value="<?php echo $customer;?>" hidden="hidden">
                    <input type="text" name="site" value="<?php echo $site;?>" hidden="hidden">
                    <input type="text" name="date" value="<?php echo $date;?>" hidden="hidden">
                    <input type="hidden" name="token" value="<?php echo rand(1000,9999).date('Ymdhisa');?>">
                    
                    <input type="text" name="crdt_bal" value="<?php echo $bal;?>" hidden="hidden">
                    <input type="hidden" name="limit" value="<?php echo $limit;?>">

                    <input type="hidden" name="curr_req" value="<?php echo $curr_req;?>">
              

                  <div class="form-group row"> 
                   <div class="col-sm-4">
                      <label class="col-sm-12 form-control-label"><b>Credit Limit</b></label>
                      <input type="text" class="form-control" value="<?php echo $limit;?>" placeholder="<?php if($limit == 0){ echo '';} ?>" readonly>
                   </div>
                   <div class="col-sm-4">
                        <label class="col-sm-12 form-control-label"><b>Total Outstanding</b></label>
                        <input type="text" class="form-control" value="<?php echo custom_money_format('%!i', $bal);?>" readonly> 
                   </div>
                   <div class="col-sm-4">
                      <label class="col-sm-12 form-control-label"><b>Available Limit</b></label>
                      <input type="text" class="form-control" value="<?php echo $availableLimit;?>" readonly>
                   </div>
                  </div> 



                <div class="form-group row">
                  <label for="endd" class="col-sm-4 form-control-label">Sales Order No</label>
                  <div class="col-sm-8">
                  <input class="form-control" type="text" name="order_referance" value="<?php echo $or;?>" readonly> 
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
                   <input type="text" name="date" value="<?php echo $date;?>" id="date" placeholder=" Date" readonly class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                   
                   <select name="vehicle" id="vehicleSelect" required class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
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
            <label for="date" align="" class="col-sm-3 form-control-label">Note</label>
            <div class="col-sm-9">
                 <textarea type="text" name="note" class="form-control has-value"></textarea>     
            </div>     
            </div>
             
            <!-- <div class="form-group row">  
                 <div class="col-sm-2"></div>
                 <label align="" class="col-sm-4 form-control-label" style="color:red;">Transportation [If Needed]</label>
                 <div class="col-sm-2" style="padding-top: 10px;">
                 <input type="checkbox" style="width:16px; height:16px;" name="transport" <?php // if ($transport > 0) { ?>checked required<?php // } ?> value="<?php // echo $transport;?>">   
                 </div>
                 <div class="col-sm-2"></div>
            </div> -->

            <div class="form-group row">
              <label align="" class="col-sm-3 form-control-label">Transportation</label>
              <div class="col-sm-6">
                <select name="transport" class="form-control">
                <?php 
                  $sql = "SELECT * FROM `quotation_transportation` WHERE `quotation_id`='$qtn'";
                  $result = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($result) > 0) { ?>
                    <option value="">SELECT</option>
                  <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <option value="<?php echo $row["transportation"]; ?>"><?php echo $row["transportation"];?></option>
                <?php } } ?>
                </select>
              </div>
            </div>
            
            <div class="form-group row">
                <label align="" class="col-sm-3 form-control-label">Payment Terms</label>
                <div class="col-sm-6">
                    <?php
                        $sql_terms = "SELECT * FROM customers WHERE id='$customer'";
                        $query_terms = mysqli_query($conn,$sql_terms);
                        $result_terms = mysqli_fetch_array($query_terms);
                        $cust_type = $result_terms['cust_type'];
                        $period = $result_terms['period'];
                    ?>
                    <input type="text" class="form-control" value="<?php echo $cust_type;?> <?php echo $period;?> Days" readonly>
                </div>
            </div>
                    
            <div class="form-group row">
               <div class="col-sm-8">
                <?php 
                  $sql = "SELECT id FROM delivery_note ORDER BY id DESC LIMIT 1";
                  $result = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($result) > 0) 
                  {
                  while($row = mysqli_fetch_assoc($result)) 
                  { 
                    $dno=$row["id"]+1;
                  ?>
                    <input type="hidden" class="form-control" name="dno" value="<?php echo $dno;?>">
                  <?php 
                  }}
              ?>
              </div>
            </div>


          <div class="item-order-details">

             <div class="form-group row" style="text-align:center;">
                  <label for="endd" class="col-sm-2 form-control-label"><b>Item</b></label>
              <label for="endd" align="" class="col-sm-1 form-control-label"><b>Order</b></label>
              <label for="endd" align="" class="col-sm-1 form-control-label"><b>Balance</b></label>
              <label for="endd" align="" class="col-sm-2 form-control-label"><b>Batch No</b></label>
              <label style="width: 135px;" for="endd" class="col-sm-1 form-control-label"><b>LOT Stock Available</b></label>
              <label for="endd" class="col-sm-1 form-control-label"><b>Price</b></label>
              <label for="endd" class="col-sm-1 form-control-label"><b>Quantity</b></label>
              <label for="endd" class="col-sm-2 form-control-label"><b>COC:No</b></label>
             </div>

             <?php }  ?>
             <hr><hr>



            <div class="form-group row">
              <div class="col-sm-2">
                <input name="lpo" value="<?php echo $lpo;?> "hidden="hidden">
                <input type="hidden" id="itemWeight_0">
                <select class="form-control" name="item[]" id="delItem_0">
                    <?php                                
                          $sqlItem="SELECT oi.item AS itemId,itm.items AS itemName FROM `order_item` oi INNER JOIN `items` itm ON oi.item=itm.id
                                  WHERE oi.item_id=$saleId";

                          $resultItem=$conn->query($sqlItem);
                          ?> <option>Select Item</option> <?php
                          if($resultItem->num_rows > 0) {
                          while($rowItem=$resultItem->fetch_assoc()) 
                          {
                          ?>
                            <option value="<?php echo $rowItem['itemId'];?>"> <?php echo $rowItem['itemName'];?></option>
                          <?php  } } ?>                    
                </select>
              </div>
                
              <div class="col-sm-1">
                  <input type="text" class="form-control" name="order[]" id="orderQuantity_0" readonly>
              </div>
                
              <div class="col-sm-1">
                  <input type="text" class="form-control" name="reqquantity[]" id="quantityReq_0" readonly>
                  <input type="hidden" name="comment[]" id="itemComment_0">
              </div>
              <div class="col-sm-2">
                  <select class="form-control" name="batch[]" id="batchAvailable_0" required></select>
              </div>

              <div class="col-sm-1" style="width: 135px;">
                  <input type="text" class="form-control" name="quantity[]" id="lotStock_0" readonly>
              </div>
              <div class="col-sm-1">
                  <input type="text" class="form-control" name="price[]" id="orderPrice_0" readonly>
              </div>
              <div class="col-sm-1">
                  <input type="number" min="1" step="any" class="form-control" name="thisquantity[]" id="this_0" required>
              </div>
                  
              <div class="col-sm-2">
                  <input type="text" class="form-control" name="coc[]" id="coc_0" readonly>
              </div>

              <div class="box-tools">
                  <a href="javascript:void(0);" 
                    class="btn btn-info btn-sm" id="btnAddMore" data-original-title="Add More">
                    <i class="fa fa-plus"></i>
                  </a>
              </div>
            </div>
            <div id="divAttach"></div>
          </div>
            <hr><hr>
          
          <!-- <label class="col-sm-4 form-control-label" align="center"><b id="vehicleWeight"></b></label> -->
            <div class="row">
              <label class="col-sm-4 form-control-label" align="center"><b>Vehicle Weight</b></label>
              <label class="col-sm-4 form-control-label" align="center"><b>Allowed Goods Weight</b></label>
              <label class="col-sm-4 form-control-label" align="center"><b>Current Goods Weight</b></label>
            </div>

            <div class="row">
              <div class="col-sm-4">
                <input style="text-align: center;" type="text" class="col-sm-4 form-control" name="vehicleWeight" id="vehicleWeight" readonly>
              </div>
              <div class="col-sm-4">
                <input style="text-align: center;" type="text" class="col-sm-4 form-control" name="allowedWeight" id="allowedWeight" readonly>
              </div>
              <div class="col-sm-4">
                <input style="text-align: center;" type="text" class="col-sm-4 form-control" name="currentWeight" id="currentWeight" readonly>
              </div>
          </div>                                       

               <div class="form-group row m-t-md">
                <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <input type="hidden" name="formSubmit" value="1">
                <button type="button" id="sub" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>
               </div>
               </div>
            <?php } } ?>
              
          </form>
             
        </div>
      </div>
    </div>
  </div>
</div>
</div>


<?php if(isset($_POST['submit2'])) { ?>
  <script>
        $(document).ready(function() {

          $('#sub').prop('disabled', true);

          $('#this_0').on('keyup', function () {
            updateTotalWeight();
          });

          $('#sub').click(function(e) {
            e.preventDefault();

           var allowedGoodsWeight = parseFloat($("#allowedWeight").val());
           var currentGoodsWeight = parseFloat($("#currentWeight").val());
            
            if (currentGoodsWeight > allowedGoodsWeight) {
                alert('Vehicle cant hold this quantity as per Govt Order, Proceed anyway');
            //   alert('Cannot Deliver this order since the vehicle cant hold this quantity as per Govt Order');
            //   return false;
            }

            let valid = true;
            $('[id^="delItem_"]').each(function() {
              const deliveryItemRow = this.id.split("_")[1];
              if (!validateDeliveryInput(deliveryItemRow)) {
                valid = false;
              }
            });
            if (valid) {
              $("#deliveryForm").submit();
            }

        });

        $('#vehicleSelect').on('change', function () {
            var selectedVehicle = $(this).val();
            $.ajax({
                type: 'POST',
                url: '<?php echo $baseurl;?>/loads/getVehicleDetails',
                data: {vehicle: selectedVehicle},
                dataType: 'json',
                success: function (response) {
                  var vehicleWeight = response.vehicleWeight;
                  var allowedWeight = response.allowedWeight;
                  var goodsWeight = allowedWeight-vehicleWeight;
                    $('#vehicleWeight').val(vehicleWeight);
                    $('#allowedWeight').val(goodsWeight);
                }
            });
        });


        function updateTotalWeight() {
          totalWeightSum = 0;
          $('[id^="this_"]').each(function () {
            var rowId = this.id.split('_')[1];
            var itemWeight = parseFloat($("#itemWeight_" + rowId).val());
            var thisQuantity = parseFloat($("#this_" + rowId).val());
          if (!isNaN(itemWeight) && !isNaN(thisQuantity)) {
            var product = itemWeight * thisQuantity;
            totalWeightSum += product;
          }
          });
            $('#currentWeight').val(totalWeightSum);
          }

                    $("#delItem_0").change(function() {
                        var item_id = $(this).val();
                        var deliveryItemRow = this.id.split('_')[1];
                        var or = "<?php echo $or; ?>";
                        if (item_id != "") {
                           $.ajax({
                           url: '<?php echo $baseurl;?>/loads/getBatch',
                           data: {item_id: item_id,or: or},
                           type: 'POST',
                           dataType: 'json',
                           success: function(response) {
                              var batchOptions = response.batchOptions;
                              var order_quantity = response.order_quantity;
                              var req_quantity = response.req_quantity;
                              var order_price = response.order_price;
                              var item_comment = response.item_comment;
                              var item_weight = response.item_weight;
                              var optionsHTML = "";
                              for (var i = 0; i < batchOptions.length; i++) {
                                  var optionValue = (batchOptions[i] !== 'Select') ? batchOptions[i] : '';
                                  optionsHTML += "<option value='" + optionValue + "'>" + batchOptions[i] + "</option>";
                              }
                              $("#batchAvailable_" + deliveryItemRow).html(optionsHTML);
                              $("#orderQuantity_" + deliveryItemRow).val(order_quantity);
                              $("#quantityReq_" + deliveryItemRow).val(req_quantity);
                              $("#orderPrice_" + deliveryItemRow).val(order_price);
                              $("#itemComment_" + deliveryItemRow).val(item_comment);
                              $("#itemWeight_" + deliveryItemRow).val(item_weight);

                                  $("#batchAvailable_" + deliveryItemRow).on('change', function() {
                                      var selectedBatch = $(this).val();
                                      $.ajax({
                                          url: '<?php echo $baseurl;?>/loads/getBatchDetails',
                                          data: { batch: selectedBatch },
                                          type: 'POST',
                                          dataType: 'json',
                                          success: function(data) {
                                            var coc = data.cocNo;
                                            var lotStock = data.lotStock;
                                            $("#coc_" + deliveryItemRow).val(coc);
                                            $("#lotStock_" + deliveryItemRow).val(lotStock);
                                          }
                                      });
                                  });
                              $('#sub').prop('disabled', false);
                              updateTotalWeight();
                           }
                           });
                        } else {
                           $("#batchAvailable_" + deliveryItemRow).html("<option value=''>---- Select ----</option>");
                        }
                      });

  let totalWeightSum = 0;
  let deliveryItemRow = 1;

  function validateDeliveryInput(deliveryItemRow) {
    var thisQuantity = parseFloat($("#this_" + deliveryItemRow).val());
    var quantityReq = parseFloat($("#quantityReq_" + deliveryItemRow).val());
    var lotStock = parseFloat($("#lotStock_" + deliveryItemRow).val());
    var batchSelected = parseFloat($("#batchAvailable_" + deliveryItemRow).val());
                                  
    if (isNaN(batchSelected)) {
        alert('Please select a valid batch for row ' + (parseInt(deliveryItemRow) + 1));
        return false;
    }

    var batchSelected = $("#batchAvailable_" + deliveryItemRow).val();
    if (!batchSelected) {
        alert('Please wait for batch data to load for row ' + (parseInt(deliveryItemRow) + 1));
        return false;
    }

    if (isNaN(thisQuantity) || thisQuantity <= 0) {
        alert('Please enter a valid quantity for row ' + (parseInt(deliveryItemRow) + 1));
        return false;
    }

    if (thisQuantity > quantityReq || thisQuantity > lotStock) {
        alert('Delivery is more than available stock in the batch or required order of row ' + (parseInt(deliveryItemRow) +1));
        return false;
    }
    return true;
  }

    const MAX_DELIVERY_ROWS = 50;

    $('#btnAddMore').click(function () {

    const deliveryRow = document.createElement('div');
    deliveryRow.setAttribute('class', 'form-group row');
     
    var deliveryInnerDiv = `

          <div class="col-sm-2">
            <select class="form-control" name="item[]" id="delItem_${deliveryItemRow}">
                <?php                                
                      $sqlItem1="SELECT oi.item AS itemId,itm.items AS itemName FROM order_item oi INNER JOIN items itm ON oi.item=itm.id
                              WHERE oi.item_id='$saleId'";

                      $resultItem1=$conn->query($sqlItem1);
                      ?> <option value="">Select Item</option> <?php
                      if($resultItem1->num_rows > 0) {
                        while($rowItem1=$resultItem1->fetch_assoc()) 
                        {
                        ?>
                          <option value="<?php echo $rowItem1['itemId'];?>"> <?php echo $rowItem1['itemName'];?></option>
                        <?php  } } ?>                      
            </select>
            <?php // echo $sqlItem1;?>
          </div>
                
                    <div class="col-sm-1">
                     <input type="hidden" id="itemWeight_${deliveryItemRow}">
                     <input type="text" class="form-control" name="order[]" id="orderQuantity_${deliveryItemRow}" readonly>
                   </div>
                
                   <div class="col-sm-1">
                     <input type="text" class="form-control" name="reqquantity[]" id="quantityReq_${deliveryItemRow}" readonly>
                     <input type="hidden" name="comment[]" id="itemComment_${deliveryItemRow}">
                   </div>
                   <div class="col-sm-2">
                        <select class="form-control" name="batch[]" id="batchAvailable_${deliveryItemRow}"></select>
                   </div>
               
               <div class="col-sm-1" style="width: 135px;">
               <input type="text" class="form-control" id="lotStock_${deliveryItemRow}" name="quantity[]" readonly>
               </div>
               <div class="col-sm-1">
                    <input type="text" name="price[]" class="form-control" id="orderPrice_${deliveryItemRow}" readonly>
              </div>
               <div class="col-sm-1">
                    <input type="number" min="1" step="any" class="form-control" name="thisquantity[]" id="this_${deliveryItemRow}" required>
              </div>
               
              <div class="col-sm-2">
                <input type="text" class="form-control" id="coc_${deliveryItemRow}" name="coc[]" readonly>
              </div>

                <div class="box-tools">
                  <a href="javascript:void(0);" class="btn btn-danger btn-sm btnRemoveDebits" data-original-title="Remove"><i class="fa fa-times"></i></a>
                </div>`;
            if(deliveryItemRow <= MAX_DELIVERY_ROWS) {
            $(deliveryRow).append(deliveryInnerDiv);
            $('#divAttach').append(deliveryRow);

                      $(deliveryRow).on('keyup', '[id^="this_"]', function () {
                        updateTotalWeight();
                      });

                      $(deliveryRow).on('change', '[id^="delItem_"]', function() {
                        var deliveryItemRow = this.id.split('_')[1];

                        var item_id = $(this).val();
                        var or = "<?php echo $or; ?>";
                        if (item_id != "") {
                           $.ajax({
                           url: '<?php echo $baseurl;?>/loads/getBatch',
                           data: {item_id: item_id,or: or},
                           type: 'POST',
                           dataType: 'json',
                           success: function(response) {
                              var batchOptions = response.batchOptions;
                              var order_quantity = response.order_quantity;
                              var req_quantity = response.req_quantity;
                              var order_price = response.order_price;
                              var item_comment = response.item_comment;
                              var item_weight = response.item_weight;
                              var optionsHTML = "";
                              for (var i = 0; i < batchOptions.length; i++) {
                                  var optionValue = (batchOptions[i] !== 'Select') ? batchOptions[i] : '';
                                  optionsHTML += "<option value='" + optionValue + "'>" + batchOptions[i] + "</option>";
                              }
                              $("#batchAvailable_" + deliveryItemRow).html(optionsHTML);
                              $("#orderQuantity_" + deliveryItemRow).val(order_quantity);
                              $("#quantityReq_" + deliveryItemRow).val(req_quantity);
                              $("#orderPrice_" + deliveryItemRow).val(order_price);
                              $("#itemComment_" + deliveryItemRow).val(item_comment);
                              $("#itemWeight_" + deliveryItemRow).val(item_weight);

                                  $("#batchAvailable_" + deliveryItemRow).on('change', function() {
                                      var selectedBatch = $(this).val();
                                      $.ajax({
                                          url: '<?php echo $baseurl;?>/loads/getBatchDetails',
                                          data: { batch: selectedBatch },
                                          type: 'POST',
                                          dataType: 'json',
                                          success: function(data) {
                                            var coc = data.cocNo;
                                            var lotStock = data.lotStock;
                                            $("#coc_" + deliveryItemRow).val(coc);
                                            $("#lotStock_" + deliveryItemRow).val(lotStock);
                                          }
                                      });
                                  });
                              updateTotalWeight();
                           }
                           });
                        
                        } else {
                           $("#batchAvailable_" + deliveryItemRow).html("<option value=''>---- Select ----</option>");
                        }
                     });

          deliveryItemRow++;

         $(deliveryRow).on('click', '.btnRemoveDebits', function () {
            $(deliveryRow).remove();
         });
      }
     });
});
</script>
<?php } ?>

<style>
  .item-order-details {
    padding: 1em;
    width: 82vw;
    background: white;
    margin-top: 6%;
    margin-left: -16px;
    border-top: 23px solid #f6fafb;
    border-bottom: 23px solid #f6fafb;
  }
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

<?php include "../includes/footer.php";?>