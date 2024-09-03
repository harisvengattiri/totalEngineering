<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<?php error_reporting(0); ?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$customer=$_POST["customer"];
$site = "";
$tamount=$_POST["tamount"];
$pmethod=$_POST["pmethod"];
$pdate=$_POST["pdate"];
$gldate=$_POST["gldate"];
$duedate=$_POST["duedate"];
$clearancedate=$_POST["clearancedate"];
$postdated=$_POST["postdated"];
$ref=$_POST["ref"];
$checkno=$_POST["checkno"];
$bank=$_POST["bank"];


$invoice=$_POST["invoice"];
$amount=$_POST["amount"];

$invoice=array_filter($invoice);
if(count(array_unique($invoice))<count($invoice)) { $status='failed1'; } else {  
$amountsum= array_sum($amount);
if($amountsum != $tamount){ $status='failed2';} else {
     
$sql = "INSERT INTO `reciept` (`customer`, `site`, `amount`, `pmethod`, `pdate`, `gldate`, `duedate`, `clearance_date`, `post_dated`, `ref`, `cheque_no`, `bank`) 
VALUES ('$customer', '$site', '$tamount', '$pmethod', '$pdate', '$gldate', '$duedate', '$clearancedate', '$postdated', '$ref', '$checkno', '$bank')";

if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
    
    
    $count=sizeof($invoice);
    for($i=0;$i<$count;$i++)
    {
    $sql1 = "INSERT INTO `reciept_invoice` (`reciept_id`,`invoice`, `amount`) 
VALUES ('$last_id','$invoice[$i]', '$amount[$i]')";
     $conn->query($sql1);
     
     
    }
    
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="RPT".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} 
else 
    {
    $status="failed";
    }

}}}

if($_GET['id'])
     {
     $id=$_GET['id'];
     $sql="SELECT * FROM reciept where id='$id'";
     $result=$conn->query($sql);
     while($row = mysqli_fetch_assoc($result)) 
          {
               $customer=$row["customer"];
              //  $site=$row["site"];
               $amount=$row["amount"];
               $amount=($amount != NULL) ? $amount : 0;
               $pmethod=$row["pmethod"];
               $pdate=$row["pdate"];
               $gldate=$row["gldate"];
               $duedate=$row["duedate"];
               $clearance_date=$row["clearance_date"];
               $post_dated=$row["post_dated"];
               $ref=$row["ref"];
               $cheque_no=$row["cheque_no"];
               $bank=$row["bank"];
                         $sqlbank = "SELECT name FROM banks where id='$bank'";
			                   $resultbank = mysqli_query($conn, $sqlbank);
                         if(mysqli_num_rows($resultbank) > 0) {
                         $rowbank = mysqli_fetch_assoc($resultbank);
                         $bank1=$rowbank['name'];
                         }
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
      <?php } else if($status=="failed1") {?>
          <p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed! Cannot take same invoice twice in a receipt</span>
           </a></p>
        <?php } else if($status=="failed2") {?>
          <p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed! Payed Amount and invoiced amount doesn't match</span>
           </a></p>
        <?php } ?>
    
    
      <div class="box">
        <div class="box-header">
          <h2>View Receipt</h2>
        </div>
           <?php
                              $sqlcust="SELECT name from customers where id='$customer'";
                              $querycust=mysqli_query($conn,$sqlcust);
                              $fetchcust=mysqli_fetch_array($querycust);
                              $cust=$fetchcust['name'];
                              
                              // $sqlsite="SELECT p_name from customer_site where id='$site'";
                              // $querysite=mysqli_query($conn,$sqlsite);
                              // $fetchsite=mysqli_fetch_array($querysite);
                              // $site1=$fetchsite['p_name'];
           ?>
           
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/receipt" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-6">
                   <input type="text" class="form-control" value="<?php echo $cust;?>">  
              </div>
            </div>
<!--            <div class="form-group row">
              <label for="type" align="left" class="col-sm-2 form-control-label">Customer Site</label>
              <div class="col-sm-6">
                   <input type="text" class="form-control" value="<?php // echo $site1;?>">
              </div>
            </div>-->
               
            <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-4">
                   <input type="text" class="form-control" value="<?php echo $amount;?>">
              </div>
               <label for="type" align="right" class="col-sm-2 form-control-label">Payment Method</label>
              <div class="col-sm-4">
                  <select name="pmethod" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                    <option value=""><?php echo $pmethod;?></option>
                </select>
              </div>
            </div>   
            
            <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Payment Date</label>
               <?php
               $today = date('d/m/Y');
               ?>
              <div class="col-sm-4">
                  <input type="text" name="pdate" value="<?php echo $pdate;?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
               <label for="type" align="right" class="col-sm-2 form-control-label">GL Date</label>
              <div class="col-sm-4">
                  <input type="text" name="gldate" value="<?php echo $gldate;?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
               <label for="type" class="col-sm-2 form-control-label">Due Date</label>
               
              <div class="col-sm-4">
                  <input type="text" name="duedate" value="<?php echo $duedate;?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
               <label for="type" align="right" class="col-sm-2 form-control-label">Clearance Date</label>
              <div class="col-sm-4">
                  <input type="text" name="clearancedate" value="<?php echo $clearance_date;?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <label for="type" class="col-sm-2 form-control-label">Post Dated</label>
               
               <div class="col-sm-1">
               <input style="margin-top: 10px;" type="checkbox" <?php if($post_dated==1){?> checked <?php } ?> class="form-control" name="postdated"></span>
               </div>
               
              <label for="type" align="right" class="col-sm-2 form-control-label">Ref No:</label>
              <div class="col-sm-2">
              <input type="text" class="form-control" name="ref" value="<?php echo $ref;?>">    
              </div>
              <label for="type" align="right" class="col-sm-2 form-control-label">Cheque No:</label>
              <div class="col-sm-3">
              <input type="text" class="form-control" name="checkno" value="<?php echo $cheque_no;?>">    
              </div>
              </div>
               
              <div class="form-group row">
              <label for="type" align="left" class="col-sm-2 form-control-label">Bank</label>
              <div class="col-sm-6">
                   <select class="form-control" name="bank">
                   <?php     $sql = "SELECT * FROM banks";
                              $result = mysqli_query($conn, $sql);
                              if (mysqli_num_rows($result) > 0) 
                              {
                              ?><option value=""><?php echo $bank1;?></option><?php     
                                while($row = mysqli_fetch_assoc($result)) 
				                        {
                              ?><option value="<?php echo $row["id"]; ?>"><?php echo $row["name"];?></option><?php
                                }
                                }
                   ?>             
                   </select>
              </div>
              </div>
               
            <?php 
               $sql1="SELECT * FROM reciept_invoice where reciept_id='$id'";
               $result1=$conn->query($sql1);
               while($row1 = mysqli_fetch_assoc($result1)) 
                    {
                         $invoice=$row1["invoice"];
                         $amount=$row1["amount"];
                         $adjust=$row1["adjust"];
            ?>
            <div class="form-group row">
              <label for="name" class="col-sm-1 form-control-label">Invoice</label>
              <div class="col-sm-3">
                   <input type="text" class="form-control" value="<?php echo $invoice;?>" readonly>
              </div>
              <div class="col-sm-3">
                   <?php 
				$sql = "SELECT * FROM invoice WHERE id='$invoice'";
				$result = mysqli_query($conn, $sql);
				while($row = mysqli_fetch_assoc($result)) 
				{
                                $grand=$row["grand"];
                                $grand = ($grand != NULL) ? $grand : 0;
                                $sqlsum="SELECT sum(amount) AS amt,sum(adjust) AS adt FROM reciept_invoice WHERE invoice='$invoice'";
                                $resultsum = mysqli_query($conn, $sqlsum);
                                $rowsum = mysqli_fetch_assoc($resultsum);
                                $amt=$rowsum["amt"];
                                $amt = ($amt != NULL) ? $amt : 0;
                                $adt=$rowsum["adt"];
                                $adt = ($adt != NULL) ? $adt : 0;
                                $amnt=$amt+$adt;
                                $bal1=$grand-$amnt;
                                $bal = number_format($bal1, 2, '.', '');
                                // if($bal>1) {$bal=$bal;} else { $bal=0; }
                                }
		   ?>
                   <input type="text" class="form-control" value="<?php echo $bal;?>" readonly>
              </div>
              <div class="col-sm-3">
                   <input type="number" step=".01" class="form-control" value="<?php echo $amount;?>" readonly>
              </div>
              <div class="col-sm-2">
                   <input type="number" step=".01" class="form-control" value="<?php echo $adjust;?>" readonly>
              </div>
           </div>
                    <?php } ?> 
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