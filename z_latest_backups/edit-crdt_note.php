<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<?php // error_reporting(0); ?>

<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
    {

    $crdt=$_POST["id"];
    $type=$_POST["type"];
    $date=$_POST["date"];
    $invoice=$_POST["invoice"];
    
    $summery = $_POST["summery"];
    $description = $_POST["description"];

    date_default_timezone_set('Asia/Dubai');
    $time = date('Y-m-d H:i:s', time());

if($type == 3) {
    
    $amt = $_POST["amt"];
    $amount = number_format($amt, 2, '.', '');
    $vat = $amt*0.05;
    $vat = number_format($vat, 2, '.', '');
    $total = $amt+$vat;
    
    $sql = "UPDATE `credit_note` SET `date`='$date', `amount`='$amount', `vat`='$vat', `total`='$total', `summery`='$summery', `description`='$description', `time`='$time' WHERE `id` = '$crdt'";
}

if($type == 1 || $type == 2) {
    $amt=$_POST["amt"];
    $amount= array_sum($amt);
    $amount = number_format($amount, 2, '.', '');
    $vat=$amount*.05;
    $vat = number_format($vat, 2, '.', '');
    $total=$amount + $vat;
    $total = ($total != NULL) ? $total : 0;
    $total = number_format($total, 2, '.', '');
    
    $sql = "UPDATE `credit_note` SET `date`='$date', `amount`='$amount', `vat`='$vat', `total`='$total', `time`='$time' WHERE `id` = '$crdt'";
}


if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
    
    // SECTION FOR DELETEING FROM ADDITIONAL TABLES
    $sql_del_adtnl = "DELETE FROM `additionalAcc` WHERE `entry_id`='$crdt' AND `section`='CNT'";
    $conn->query($sql_del_adtnl);
    
    $sql_adtnl_vat = "INSERT INTO `additionalAcc`(`id`, `section`, `entry_id`, `date`, `cat`, `sub`, `amount`)
                      VALUES ('','CNT','$crdt','$date','39','176','$vat')";
    $conn->query($sql_adtnl_vat);
    
    $sql_update_additionalRcp = "UPDATE `additionalRcp` SET `date`='$date',`amount`='-$total' WHERE `entry_id`='$crdt' AND `section`='CNT'";
    $conn->query($sql_update_additionalRcp);
    
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="CNT".$crdt;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       
       
            $sql10 = "SELECT grand FROM `invoice` WHERE `id` = '$invoice'";
            $query10 = mysqli_query($conn,$sql10);
            $result10 = mysqli_fetch_array($query10);
            $grand = $result10['grand'];
            $grand = ($grand != NULL) ? $grand : 0;
       
            $sql11 = "SELECT sum(total) as total FROM `reciept_invoice` WHERE `invoice`='$invoice'";
            $query11 = mysqli_query($conn,$sql11);
            $result11 = mysqli_fetch_array($query11);
            $total_rcp = $result11['total'];
            $total_rcp = ($total_rcp != NULL) ? $total_rcp : 0;

            $total_amt = $total_rcp + $total;
            
            if($total_amt >= $grand){$pay_status='Paid';}
            if($total_amt < $grand){$pay_status='Partially';}
            if($total_amt == NULL){$pay_status='';}
            
            $sql12 = "UPDATE `invoice` SET `status` = '$pay_status' WHERE `id`='$invoice'";
            $query12 = mysqli_query($conn,$sql12);
 
        if($type == 1 || $type == 2) {
            
            $sql_del_crdt_items = "DELETE FROM `credit_note_items` WHERE `cr_id`='$crdt'";
            $conn->query($sql_del_crdt_items);
            
            $item=$_POST["item"];
            $price=$_POST["price"];
            if($type == 1) {
                $quantity=$_POST["return"];
                $adt=$price;
            } else if($type == 2) {
                $quantity=$_POST["quantity"];
                $adt=$_POST["adt"];
            }
            $amt=$_POST["amt"];
               
            $count=sizeof($item);
            for($i=0;$i<$count;$i++)
            {
            if($amt[$i] != NULL) {
                $amt[$i] = number_format($amt[$i], 2, '.', '');     
                $sql1 = "INSERT INTO `credit_note_items` (`cr_id`,`item`, `quantity`, `price`, `adjust`, `amt`) 
                VALUES ('$crdt','$item[$i]', '$quantity[$i]', '$price[$i]', '$adt[$i]', '$amt[$i]')";
                $conn->query($sql1);
                }
            }
        }
 
}
else 
    {
    $status="failed";
    }

}}


if($_GET) {
    
    $crdt = $_GET['id'];
    
    $sql = "SELECT * FROM `credit_note` WHERE `id`='$crdt'";
    $query = mysqli_query($conn,$sql);
    while($result = mysqli_fetch_array($query)) {
        
        $customer = $result['customer'];
        
        $sqlcust="SELECT name from customers where id='$customer'";
                    $querycust=mysqli_query($conn,$sqlcust);
                    $fetchcust=mysqli_fetch_array($querycust);
                    $cust=$fetchcust['name'];
        $invoice = $result['invoice'];
        $date = $result['date'];
        $type = $result['type'];
        
        $amount = $result['amount'];
        
        $summery = $result['summery'];
        $description = $result['description'];
    }
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
          <h2>Edit Credit Note</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
    
    
            <form role="form" action="<?php echo $baseurl;?>/edit/crdt_note?id=<?php echo $crdt;?>" method="post">
                <input type="hidden" name="id" value="<?php echo $crdt;?>">
                <input type="hidden" name="type" value="<?php echo $type;?>">
              
              <div class="form-group row">
                  <label for="date" class="col-sm-2 form-control-label">Customer</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" value="<?php echo $cust;?>" readonly>
                  </div>
              </div>
              
              <div class="form-group row">
                  <label for="date" class="col-sm-2 form-control-label">Invoice</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="invoice" value="<?php echo $invoice;?>" readonly>
                  </div>
              </div>
              
              <div class="form-group row">
              <label for="date" class="col-sm-2 form-control-label">Date</label>
              <div class="col-sm-6">
                   <input type="text" name="date" value="<?php echo $date;?>" id="date" class="form-control has-value" required data-ui-jp="datetimepicker" data-ui-options="{
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
           
        <?php
           if($type == 3) {
        ?>
            <div class="form-group row">
               <label class="col-sm-2 form-control-label">Amount</label>
               <div class="col-sm-6">
                    <input type="number" step=".01" min="0" class="form-control" name="amt" value="<?php echo $amount;?>" placeholder="Amount">
               </div>
            </div>
           
            <div class="form-group row">
               <label class="col-sm-2 form-control-label">Summery</label>
               <div class="col-sm-6">
                    <input type="text" class="form-control" name="summery" value="<?php echo $summery;?>" placeholder="Summery">
               </div>
           </div>
           <div class="form-group row">
               <label class="col-sm-2 form-control-label">Description</label>
               <div class="col-sm-6">
                    <!--<input type="text" class="form-control" name="description" value="<?php // echo $description;?>" placeholder="Description">-->
                    <textarea type="text" class="form-control" name="description" placeholder="Description" rows="4"><?php echo $description;?></textarea>
               </div>
           </div>
        <?php } ?>
           
           <?php
           if($type == 1 || $type == 2) {
               
                $sql1="SELECT * FROM `credit_note_items` WHERE `cr_id`='$crdt' AND `amt` > 0";
				$result1 = mysqli_query($conn, $sql1);
				if (mysqli_num_rows($result1) > 0) 
				{
                while($row1 = mysqli_fetch_assoc($result1)) {
                    
                    $id=$row1['id'];
                    $item1=$row1['item'];
                    
                    $sqlitem="SELECT items from items where id='$item1'";
                    $queryitem=mysqli_query($conn,$sqlitem);
                    $fetchitem=mysqli_fetch_array($queryitem);
                    $item=$fetchitem['items'];
                    
                    $sql2="SELECT sum(quantity) AS sq FROM `invoice_item` WHERE `invoice_id`='$invoice' AND `item`='$item1'";
                    $result2 = mysqli_query($conn, $sql2);
                    $row2 = mysqli_fetch_assoc($result2);
                    $total_quantity = $row2['sq'];
                    
                    $quantity=$row1['quantity'];
                    $price=$row1['price'];
                    $adjust=$row1['adjust'];
                    $amt=$row1['amt'];
                    
            if($type == 1) {
            ?>
            <div class="form-group row">
               <label align="center" class="col-sm-3 form-control-label"><b>Item</b></label>
               <label align="center" class="col-sm-3 form-control-label"><b>Total Quantity</b></label>
               <label align="center" class="col-sm-2 form-control-label"><b>Return Quantity</b></label>
               <label align="center" class="col-sm-2 form-control-label"><b>Price</b></label>
               <label align="center" class="col-sm-2 form-control-label"><b>Amount</b></label>
            </div>
            <div class="form-group row">
              
              <div class="col-sm-3">
                   <input type="text" class="form-control" value="<?php echo $item;?>" placeholder="Item" readonly>
                   <input type="hidden" name="item[]" value="<?php echo $item1;?>">
              </div>
              <div class="col-sm-3">
                   <input type="text" class="form-control" name="quantity[]" value="<?php echo $total_quantity;?>" placeholder="Quantity" readonly>
              </div>
              <div class="col-sm-2">
                   <input type="text" class="form-control" name="return[]" value="<?php echo $quantity;?>" id="box1<?php echo $id;?>" oninput="calculate<?php echo $id;?>()" placeholder="Return">
              </div>
              <div class="col-sm-2">
                   <input type="text" class="form-control" name="price[]" value="<?php echo $adjust;?>" id="box2<?php echo $id;?>" placeholder="Price" readonly>
              </div>
              <div class="col-sm-2">
                   <input type="number" class="form-control" name="amt[]" value="<?php echo $amt;?>" id="result<?php echo $id;?>" placeholder="Amount" readonly>
              </div>
              
           </div>
           
            <?php    
            } else if($type == 2) {
            ?>
            <div class="form-group row">
               <label align="center" class="col-sm-3 form-control-label"><b>Item</b></label>
               <label align="center" class="col-sm-3 form-control-label"><b>Quantity</b></label>
               <label align="center" class="col-sm-2 form-control-label"><b>Price</b></label>
               <label align="center" class="col-sm-2 form-control-label"><b>Adjust</b></label>
               <label align="center" class="col-sm-2 form-control-label"><b>Amount</b></label>
            </div>      
           <div class="form-group row">
              
              <div class="col-sm-3">
                   <input type="text" class="form-control" value="<?php echo $item;?>" placeholder="Item" readonly>
                   <input type="hidden" name="item[]" value="<?php echo $item1;?>">
              </div>
              <div class="col-sm-3">
                   <input type="text" class="form-control" name="quantity[]" value="<?php echo $quantity;?>" id="box1<?php echo $id;?>" value="<?php echo $quantity;?>" placeholder="Quantity" readonly>
              </div>
              <div class="col-sm-2">
                   <input type="text" class="form-control" name="price[]" value="<?php echo $price;?>" value="<?php echo $unit;?>" placeholder="Price" readonly>
              </div>
              <div class="col-sm-2">
                   <input type="number" step=".01" min="0" class="form-control" name="adt[]" value="<?php echo $adjust;?>" id="box2<?php echo $id;?>" oninput="calculate<?php echo $id;?>()" value="" placeholder="Adjust">
              </div>
              <div class="col-sm-2">
                   <input type="number" class="form-control" name="amt[]" value="<?php echo $amt;?>" id="result<?php echo $id;?>" placeholder="Amount" readonly>
              </div>
              
           </div>
                 
             <?php } ?>
             
                <script>
                    function calculate<?php echo $id;?>() {
                            var myBox1<?php echo $id;?> = document.getElementById('box1<?php echo $id;?>').value;	
                            var myBox2<?php echo $id;?> = document.getElementById('box2<?php echo $id;?>').value;
                            var result<?php echo $id;?> = document.getElementById('result<?php echo $id;?>');	
                            var myResult<?php echo $id;?> = myBox1<?php echo $id;?> * myBox2<?php echo $id;?>;
                            result<?php echo $id;?>.value = myResult<?php echo $id;?>;	
                    }
                </script>  
           <?php } } } ?>      
          
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="submit"type="submit" id="sub" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>
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