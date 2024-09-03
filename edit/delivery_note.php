<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<?php error_reporting(0); ?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_GET['status']))
{
    $status = $_GET['status'];
}
if(isset($_POST['submit']))
{
  if(isset($_SESSION['userid']))
  {
  $mnt=$_GET['id'];
  $id=$_POST['prj'];
  $order_referance=$_POST["order_referance"];
  $date=$_POST["date"];
  $customer=$_POST["customer"];
  $customersite=$_POST["site"];
  $vehicle=$_POST["vehicle"];
  $driver=$_POST["driver"];
  $note=$_POST["note"];
  $transport=$_POST["transport"];
  $transport = ($transport != NULL) ? $transport : 0;

       $item=$_POST["item"];
       $quantity=$_POST["quan"];
       $reqquantity=$_POST["reqquantity"];
       $thisquantity=$_POST["thisquantity"];
       $prevquantity=$_POST["prevquantity"];
       
       $price=$_POST["price"];
       $batch=$_POST["batch"];
       
       
       
                  $sqlcust1="SELECT op_bal FROM customers where id='$customer'";
                  $querycust1=mysqli_query($conn,$sqlcust1);
                  $fetchcust1=mysqli_fetch_array($querycust1);
                  $opening=$fetchcust1['op_bal'];
                  $opening=($opening != NULL) ? $opening : 0;
                  
                  $sqllimit="SELECT credit,credit1,status from credit_application where company=$customer";
                  $querylimit=mysqli_query($conn,$sqllimit);
                  $fetchlimit=mysqli_fetch_array($querylimit);
                  $limit1=$fetchlimit['credit'];
                  $limit1=($limit1 != NULL) ? $limit1 : 0;
                  $limit2=$fetchlimit['credit1'];
                  $limit2=($limit2 != NULL) ? $limit2 : 0;
                  $limit=$limit1+$limit2;
                  // if($limit == 0){ $limit = NULL ;}
                  
                  $sqlinvo="SELECT ROUND(SUM(total), 2) AS grand FROM delivery_note WHERE customer = $customer";
                  $queryinvo=mysqli_query($conn,$sqlinvo);
                  $fetchinvo=mysqli_fetch_array($queryinvo);
                  $invoamt=$fetchinvo['grand'];
                  $invoamt=($invoamt != NULL) ? $invoamt : 0;
                  $invoamt=$invoamt*1.05;
                  $invoamt = $invoamt + $opening;

                  $sqlrpt="SELECT sum(grand) AS amount from reciept  where customer=$customer AND status='Cleared'" ;
                  $queryrpt=mysqli_query($conn,$sqlrpt);
                  $fetchrpt=mysqli_fetch_array($queryrpt);
                  $amountrpt=0+$fetchrpt['amount'];
                  $amountrpt=($amountrpt != NULL) ? $amountrpt : 0;
                  
                  $sqlcdt="SELECT sum(`total`) AS cdt FROM `credit_note` WHERE `customer`=$customer" ;
                  $querycdt=mysqli_query($conn,$sqlcdt);
                  $fetchcdt=mysqli_fetch_array($querycdt);
                  $amountcdt=0+$fetchcdt['cdt'];
                  $amountcdt=($amountcdt != NULL) ? $amountcdt : 0;
                  
                  $current_bal = $invoamt - ($amountrpt+$amountcdt);
       
       
       $sitem=sizeof($item);
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
       
    $curr_bal = $current_bal+$amttotal123;
    
    if($curr_bal > $limit & $limit != 0)
    {
    echo "<script type=\"text/javascript\">".
         "window.location='$baseurl/edit/delivery_note?status=failed4';".
         "</script>";
    }
    else {
       
       
       
       $n=sizeof($item);
       for($i=0;$i<$n;$i++)
       {
             $reqquantity[$i]=$reqquantity[$i]+$prevquantity[$i];
          if($reqquantity[$i] >= $thisquantity[$i])
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
           $quantity[$i]=$quantity[$i]+$prevquantity[$i];
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
$sql = "UPDATE delivery_note SET order_referance = '$order_referance', date = '$date', customer = '$customer', customersite = '$customersite', vehicle = '$vehicle', driver = '$driver', note = '$note', transport = '$transport' WHERE id='$id'";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $item=$_POST["item"];
       $reqquantity=$_POST["reqquantity"];
       $thisquantity=$_POST["thisquantity"];
       $bundle=$_POST["bundle"];
       $pdate=$_POST["pdate"];
       $coc=$_POST["coc"];
       $iid=$_POST["iid"];
       $quan=$_POST["quan"];
       $prevquantity=$_POST["prevquantity"];
       $n=sizeof($item);
       $sum=0;
       for($i=0;$i<$n;$i++)
       {
       $amt[$i]=$thisquantity[$i]*$price[$i];
       $amt[$i] = number_format($amt[$i], 2, '.', '');
       $amttotal = $amttotal+$amt[$i];
            
       $sql1 = "UPDATE delivery_item SET date='$date', order_referance = '$order_referance', item = '$item[$i]', reqquantity = '$reqquantity[$i]', thisquantity = '$thisquantity[$i]', coc = '$coc[$i]', amt = '$amt[$i]' WHERE batch='$batch[$i]' AND delivery_id='$id' and id='$iid[$i]'";
$conn->query($sql1);
       }
       $amttotal = $amttotal+$transport;
       $amttotal = number_format($amttotal, 2, '.', '');
       $sql2 = "UPDATE delivery_note SET total = $amttotal WHERE id = $id";
       $result2 = mysqli_query($conn, $sql2);
       
     $ordertotal=0;
     $sqlorder="SELECT quantity FROM order_item WHERE o_r='$order_referance'";
     $queryorder=mysqli_query($conn,$sqlorder);
     while($fetchorder=mysqli_fetch_array($queryorder))
     {
     $order=$fetchorder['quantity'];
     $order=($order != NULL) ? $order : 0;
     $ordertotal=$ordertotal+$order;
     }
     $sqldel="SELECT thisquantity FROM delivery_item WHERE order_referance='$order_referance'";
     $querydel=mysqli_query($conn,$sqldel);
     $deltotal=0;
     while($fetchdel=mysqli_fetch_array($querydel))
     {
          $del=$fetchdel['thisquantity'];
          $del=($del != NULL) ? $del : 0;
          $deltotal=$deltotal+$del;
     }
     if($ordertotal==$deltotal)
     {
     $sqlup="UPDATE sales_order SET flag=1 WHERE order_referance='$order_referance'";
     }
     else { 
     $sqlup="UPDATE sales_order SET flag=0 WHERE order_referance='$order_referance'";
     }
     $queryup=mysqli_query($conn,$sqlup); 
       
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="DN".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
      }
}
}                   
}
}}

if ($_GET) 
{
$or=$_GET["or"];
$mnt=$_GET["id"];

$sql_check_invoiced = "SELECT sum(total) AS invo_total FROM `invoice_item` WHERE dn='$mnt'";
$query_check_invoiced = mysqli_query($conn,$sql_check_invoiced);
$result_check_invoiced = mysqli_fetch_array($query_check_invoiced);
  if($result_check_invoiced['invo_total'] > 0) {
    echo "<script type=\"text/javascript\">".
        "window.location='$baseurl/delivery_note?cant__edit';".
        "</script>";
    }
}
	$sql = "SELECT * FROM delivery_note where order_referance=$or AND id=$mnt";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
              $customer=$row["customer"];
                    $sqlcust="SELECT name from customers where id='$customer'";
                    $querycust=mysqli_query($conn,$sqlcust);
                    $fetchcust=mysqli_fetch_array($querycust);
                    $cust=$fetchcust['name'];
                    
              $customersite=$row["customersite"];
                    $sqlsite="SELECT p_name from customer_site where id='$customersite'";
                    $querysite=mysqli_query($conn,$sqlsite);
                    $fetchsite=mysqli_fetch_array($querysite);
                    $custsite=$fetchsite['p_name'];
              $vehicle=$row["vehicle"];
                  if(!empty($vehicle)) {
                    $sqlveh="SELECT vehicle from vehicle where id='$vehicle'";
                    $queryveh=mysqli_query($conn,$sqlveh);
                    $fetchveh=mysqli_fetch_array($queryveh);
                    $veh=$fetchveh['vehicle'];
                  }
              $driver=$row["driver"];
              if(!empty($driver)) {
                    $sqldri="SELECT name from customers where id='$driver'";
                    $querydri=mysqli_query($conn,$sqldri);
                    $fetchdri=mysqli_fetch_array($querydri);
                    $dri=$fetchdri['name'];
                  }
              $note=$row["note"];
              $transport=$row["transport"];
              $transport=($transport != NULL) ? $transport : 0;
             
                   $sqltsp="SELECT transport FROM sales_order where order_referance='$or'";
                   $resulttsp = mysqli_query($conn, $sqltsp);
                   $rowtsp = mysqli_fetch_assoc($resulttsp);
                   $transport1=$rowtsp["transport"];
                   $transport1=($transport1 != NULL) ? $transport1 : 0;
              
              $lpo=$row["lpo"];     
              $date=$row["date"];
              $prj=$row["id"];
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
	<?php } else if($status=="failed1") {?>
    	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed! Due to Less Availability of Stock</span>
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
          <h2>Edit Delivery Note</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="" method="post">
          <input name="prj"  type="text" value="<?php echo $prj;?>" hidden="hidden">
             <div class="form-group row">
              <label for="endd" class="col-sm-2 form-control-label">Delivery Note Referance</label>
              <div class="col-sm-4">
                <!--<select name="order_referance" id="getall" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">-->
                <select name="order_referance" class="form-control" readonly>
                <!--<select name="order_referance" id="cust" class="form-control">-->
                  <?php 
				// $sql = "SELECT order_referance,lpo FROM sales_order";
				// $result = mysqli_query($conn, $sql);
				// if (mysqli_num_rows($result) > 0) 
				// {
                ?>
                    <option value="<?php echo $or;?>"><?php echo $or;?>/<?php echo $lpo;?></option> 
                <?php
				// while($row = mysqli_fetch_assoc($result)) 
				// {
				?>
				<!--<option value="<?php // echo $row["order_referance"]; ?>"><?php // echo $row["order_referance"];?>/<?php // echo $row["lpo"];?></option>-->
				<?php 
				// }} 
				?>
                </select>
              </div>
             </div>
          <div class="form-group row">
              <?php
             // $date = date('d/m/y');
              ?>
              <label for="date" align="left" class="col-sm-2 form-control-label">Delivery Date</label>
              <div class="col-sm-4">
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
          
          <div class="form-group row"> 
            <label for="date" class="col-sm-2 form-control-label">Note</label>
            <div class="col-sm-4">
                 <textarea type="text" name="note" class="form-control has-value"><?php echo $note;?></textarea>     
            </div>     
            </div>
          
               <div class="form-group row">
              <label for="customer" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-4">
		<select name="customer" class="form-control" id="customer" readonly>
                  <?php 
    				// $sql = "SELECT name,id FROM customers where type='Company' AND status != 'banned' order by name";
    				// $result = mysqli_query($conn, $sql);
    				// if (mysqli_num_rows($result) > 0) 
    				// {
                    ?>
                <option value="<?php echo $customer;?>"><?php echo $cust;?></option>
                    <?php
    				// while($row = mysqli_fetch_assoc($result)) 
    				// {
    				?>              
    				<!--<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>-->
    				<?php 
    				// }} 
    				?>
                </select>		
              </div>
              </div>
          
          <div class="form-group row"> 
              <label for="startd" align="left" class="col-sm-2 form-control-label">Customer Site</label>
              <div class="col-sm-4">
                   <select class="form-control" name="site" id="site" readonly>
                        <option value="<?php echo $customersite;?>"><?php echo $custsite;?></option>
                   </select>
              </div>
          </div>
          
          <div class="form-group row">
              <label for="startd" class="col-sm-2 form-control-label">Vehicle</label>
              <div class="col-sm-4" id="veh">
                 <select name="vehicle" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                <!--<select name="order_referance" id="cust" class="form-control">-->
                  <?php 
				$sql = "SELECT vehicle,id FROM vehicle";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                              ?><option value="<?php echo $vehicle; ?>"><?php echo $veh;?></option><?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["vehicle"];?></option>
				<?php 
				}} 
				?>
                 </select>
              <!--<select class="form-control" id="veh"></select>-->
              </div>
          </div>
          <div class="form-group row">
              <label for="endd" align="left" class="col-sm-2 form-control-label">Driver</label>
              <div class="col-sm-4" id="nam">
              <select name="driver" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                <!--<select name="order_referance" id="cust" class="form-control">-->
                  <?php 
				$sql = "SELECT name,id FROM customers where type='Driver'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                              ?><option value="<?php echo $driver; ?>"><?php echo $dri;?></option><?php
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
                 <div class="col-sm-1"></div>
                 <label align="right" class="col-sm-3 form-control-label" style="color:red;">Transportation [If Needed] :</label>
                 <div class="col-sm-2" style="padding-top: 10px;">
                 <input type="checkbox" <?php if($transport != 0) { ?> checked="checked" <?php } ?> style="width:16px; height:16px;" name="transport" value="<?php echo $transport1;?>">   
                 </div>
           </div>
          
             <br>
               <hr><hr>

               <?php
                 $sql="SELECT * from delivery_item where order_referance=$or AND delivery_id=$mnt";
                 
                 $result = mysqli_query($conn, $sql);
                 if (mysqli_num_rows($result) > 0) 
				{
                ?>
               
               <div class="form-group row" style="text-align:center;">
                <label for="endd" class="col-sm-2 form-control-label"><b>Item</b></label>
                <label for="endd" align="" class="col-sm-1 form-control-label"><b>Order</b></label>
                <label for="endd" align="" class="col-sm-1 form-control-label"><b>Balance</b></label>
                <label for="endd" align="" class="col-sm-2 form-control-label"><b>From this Batch</b></label>
                <label for="endd" align="" class="col-sm-2 form-control-label"><b>Quantity Available</b></label>
                <label for="endd" align="" class="col-sm-2 form-control-label"><b>COC No</b></label>
                <label for="endd" align="" class="col-sm-2 form-control-label"><b>Batch No</b></label>
               </div>           
                      
                      
                      
                     <?php          
				while($row = mysqli_fetch_assoc($result)) 
				{
				   $item=$row["item"];
                                   $iid=$row["id"];
                                   $price=$row["price"];
                                   
                                   $sqlitem="SELECT items,sqm from items where id='$item'";
                                   $queryitem=mysqli_query($conn,$sqlitem);
                                   $fetchitem=mysqli_fetch_array($queryitem);
                                   $item1=$fetchitem['items'];
                                   $sqm=$fetchitem['sqm'];
                                   $sqm = ($sqm != NULL) ? $sqm : 1;
                                   
                                   $reqquantity=$row["reqquantity"];
                                   $reqquantity = ($reqquantity != NULL) ? $reqquantity : 0;
                                   $thisquantity=$row["thisquantity"];
                                   $thisquantity = ($thisquantity != NULL) ? $thisquantity : 0;

                                   $pdate=$row["pdate"];   
                                   $coc=$row["coc"]; 
                                   $batch=$row["batch"]; 
                                   
                                   if(!empty($batch)){
                                    $sql1="SELECT quantity from batches_lots where batch='$batch' and quantity !=0";
                                    $result1 = mysqli_query($conn, $sql1);
                                    $quan=0;
                                    while($row1 = mysqli_fetch_assoc($result1))
                                    {
                                      if($row1['quantity'] != NULL) {
                                      $quan=$quan+$row1['quantity'];
                                      $quan=$quan/$sqm;
                                      }
                                    }
                                    $sqlquan="SELECT thisquantity from delivery_item where batch='$batch'";
                                    $resultquan = mysqli_query($conn, $sqlquan);
                                    $sell=0;
                                    while($rowquan = mysqli_fetch_assoc($resultquan))
                                    {
                                      if($rowquan['thisquantity'] != NULL) {
                                      $sell=$sell+$rowquan['thisquantity'];
                                      }
                                    }
                                    $sqlret="SELECT returnqnt FROM stock_return WHERE batch='$batch'";
                                    $resultret=$conn->query($sqlret);
                                    $r_quantity=0;
                                    while($rowret=$resultret->fetch_assoc())
                                    {
                                    if($rowret['returnqnt'] != NULL) {
                                    $r_quantity=$r_quantity+$rowret['returnqnt'];
                                    }
                                    }
                                    $available=$quan+$r_quantity-$sell;
                                    if(fmod($available, 1) !== 0.00)
                                    {
                                    $available=number_format($available, 2, '.', '');
                                    }
                                    
                                    
                                     $sqlbal="SELECT thisquantity from delivery_item where order_referance='$or' and item=$item";
                                     $resultbal = mysqli_query($conn, $sqlbal);
                                     $bal=0;
                                     while($rowbal = mysqli_fetch_assoc($resultbal)) 
                                     {
                                        if($rowbal['thisquantity'] != NULL) {
                                          $bal=$bal+$rowbal['thisquantity'];
                                        }
                                     }
                                     $sqlreturn="SELECT returnqnt from stock_return where o_r='$or' and item=$item";
                                     $resultreturn = mysqli_query($conn, $sqlreturn);
                                     $return=0;
                                     while($rowreturn = mysqli_fetch_assoc($resultreturn)) 
                                     {
                                      if($rowreturn['returnqnt'] != NULL) {
                                          $return=$return+$rowreturn['returnqnt'];
                                      }
                                     }
                                     $sqlreq="SELECT quantity from order_item where o_r='$or' and item=$item";
                                     $resultreq = mysqli_query($conn, $sqlreq);
                                     $req=0;
                                     while($rowreq = mysqli_fetch_assoc($resultreq)) 
                                     {
                                        if($rowreq['quantity'] != NULL) {
                                          $req=$req+$rowreq['quantity'];
                                        }
                                     }
                                     
                                     $balance = $req + $return - $bal;
                                    
                                ?>
             
              <div class="form-group row">
               
              <div class="col-sm-2">
                   <input type="text" class="form-control" value="<?php echo $item1;?>" id="endd" placeholder="Item" readonly>
                   <input type="hidden" class="form-control" name="item[]" value="<?php echo $item;?>" id="endd" placeholder="Item">
                   <input type="text" class="form-control" name="iid[]" value="<?php echo $iid;?>" hidden="hidden">
                   <input type="text" class="form-control" name="price[]" value="<?php echo $price;?>" hidden="hidden">
              </div>
              
              <div class="col-sm-1">
                <input type="text" class="form-control" name="order[]" value="<?php echo $req;?>" readonly>
              </div>     
                   
              <div class="col-sm-1">
                <input type="text" class="form-control" name="reqquantity[]" value="<?php echo $balance;?>" id="endd" placeholder="Quantity" readonly>
              </div>
               
               <div class="col-sm-2">
                    <input type="number" min="0" step="any" class="form-control" name="thisquantity[]" value="<?php echo $thisquantity;?>" placeholder="Quantity From this Batch" id="endd">
                    <input type="text" class="form-control" name="prevquantity[]" value="<?php echo $thisquantity;?>" hidden="hidden">
               </div>
               
               <div class="col-sm-2">
                    <input type="text" class="form-control" name="quan[]" value="<?php echo $available;?>"  id="endd" readonly>
              </div>
               
<!--               <label for="endd" class="col-sm-1 form-control-label">Bundle</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="bundle[]" id="endd" placeholder="Bundle">
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">Date</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="pdate[]" value="<?php echo $pdate;?>" id="endd">
              </div>-->
               
              <div class="col-sm-2">
                <input type="text" class="form-control" name="coc[]" value="<?php echo $coc;?>" id="endd" readonly>
              </div>
               
              <div class="col-sm-2">
                <input type="text" class="form-control" name="batch[]" value="<?php echo $batch;?>" id="endd" readonly>
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
                    
               
                                    <?php }}} ?>
            
               
<!--               <div class="form-group row">
               <label for="endd" class="col-sm-2 form-control-label">Sales Representative</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="salesrepresentative" id="endd" placeholder="Sales Representative">
              </div>
               </div>-->
              
            
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