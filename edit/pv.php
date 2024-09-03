<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
if(isset($_SESSION['userid']))
{
$pv=$_POST["id"];

$voucher=$_POST["voucher"];
$subcategory=$_POST["subcategory"];
$category=$_POST["category"];
$date=$_POST["pdate"];
$yr = substr($date, -2);
$cdate=$_POST["cdate"];
$pmethod=$_POST["pmethod"];
        if($pmethod == 'cash' || $pmethod == 'card')
        {
            $status1='Cleared';
            if($cdate == '')
            {
            $cdate=date("d/m/Y");
            }
        }
        if($pmethod == 'cheque'){ $cdate=''; $status1='Uncleared';}

$duedate=$_POST["duedate"];
$checkno=$_POST["checkno"];
$inward=$_POST["inward"];
$tamount=$_POST["tamount"];
$discount = 0;
$grand = $discount+$tamount;
$grand = number_format($grand, 2, '.', '');

  date_default_timezone_set('Asia/Dubai');
  $time = date('Y-m-d H:i:s', time());

$sql = "UPDATE `pv` SET `category`='$category', `subcategory`='$subcategory', `date`='$date', `year`='$yr', `amount`='$tamount', `discount`='$discount', `grand`='$grand',
       `voucher`='$voucher', `pmethod`='$pmethod', `clearance_date`='$cdate', `status`='$status1', `duedate`='$duedate', `checkno`='$checkno', `inward`='$inward', `time`='$time'
        WHERE `id`='$pv'";

if ($conn->query($sql) === TRUE) {
    $status="success";
               $date1=date("d/m/Y h:i:s a");
               $username=$_SESSION['username'];
               $code="PV".$pv;
               $query=mysqli_real_escape_string($conn, $sql);
               $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'edit', '$code', '$username', '$query')";
               $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}
}}

if($_GET) {

    $pv=$_GET['id'];

    $sql = "SELECT * FROM pv where id='$pv'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) 
    {
    while($row = mysqli_fetch_assoc($result)) 
    {
        $name=$row['name'];
        $amount=$row['amount'];
        $date=$row['date'];
        $voucher=$row['voucher'];
        $pmethod=$row['pmethod'];
        $duedate=$row['duedate'];
        $cdate=$row['clearance_date'];
        $checkno=$row['checkno'];
        $inward=$row['inward'];
        
        $cat = $row['category'];
            $sql_cat = "SELECT * FROM `expense_categories` WHERE id='$cat'";
            $query_cat = mysqli_query($conn,$sql_cat);
            $result_cat = mysqli_fetch_array($query_cat);
            $cat_name = $result_cat['tag'];
        $sub = $row['subcategory'];
            $sql_sub_cat = "SELECT * FROM `expense_subcategories` WHERE id='$sub'";
            $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
            $result_sub_cat = mysqli_fetch_array($query_sub_cat);
            $sub_cat_name = $result_sub_cat['category'];

    } }
}
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
	<?php } ?>
    
      <div class="box">
        <div class="box-header">
          <h2>Edit Payment Voucher</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
        
        <form role="form" action="<?php echo $baseurl;?>/edit/pv?id=<?php echo $pv;?>" method="post">
        <input type="hidden" name="id" value="<?php echo $pv;?>">   
            
            <div class="form-group row">
                <label for="category" class="col-sm-2 form-control-label">Category</label>
                <div class="col-sm-4">
                    <select name="category" id="category" required placeholder="category" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                        <option value="<?php echo $cat;?>"><?php echo $cat_name;?></option>
                        <?php 
                            $sql = "SELECT * FROM expense_categories";
                            $query = mysqli_query($conn,$sql);
                            if(mysqli_num_rows($query) > 0) {
                            while($row = $query->fetch_assoc()) {
                        ?>
                        <option value="<?php echo $row['id'];?>"><?php echo $row['tag'];?></option>
                        <?php } } ?>
                    </select>
                </div>
                <label for="name" align="right" class="col-sm-2 form-control-label">Sub Category</label>
                <div class="col-sm-4">
                    <select name="subcategory" id="subcategory" placeholder="subcategory" class="form-control select2">
                        <option value="<?php echo $sub;?>"><?php echo $sub_cat_name;?></option>
                    </select>
                </div>
                 <script type="text/javascript">
                 $(document).ready(function() {
                  $("#category").change(function() {
                    var cat_id = $(this).val();
                    if(cat_id != "") {
                      $.ajax({
                        url: '<?php echo $baseurl;?>/loads/subcat',
                        data:{cat_id:cat_id},
                        type:'POST',
                        success:function(response) {
                          var resp = $.trim(response);
                          $("#subcategory").html(resp);
                        }
                      });
                    } else {
                      $("#subcategory").html("<option value=''>------- Select --------</option>");
                    }
                  });
                });
                </script>
            </div>
            
            
            <div class="form-group row">
              <label for="type" align="" class="col-sm-2 form-control-label">Payment Date</label>
              <div class="col-sm-4">
                  <input type="text" required name="pdate" value="<?php echo $date;?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              
              <label for="type" align="" class="col-sm-2 form-control-label"></label>
              <div class="col-sm-4">
                  <span id="calculate" style="padding: 10px;color: white;background-color: #155520;cursor:pointer;">Click here to get the updated Account Balance</span>
              </div>
            
            </div>

                 
            <div class="form-group row">
              <label for="type" align="" class="col-sm-2 form-control-label">Voucher No</label>
              <div class="col-sm-4">
                   <input type="text" class="form-control" name="voucher" value="<?php echo $voucher;?>" readonly>
              </div>
              <label for="type" align="right" class="col-sm-2 form-control-label">Balance Amount</label>

              <div class="col-sm-4">
                  <input type="text" id="actualBalance" class="form-control" value="Cannot Show Now" readonly>
              </div>
              
              
            </div>
            
            
            

                 
            <div class="form-group row">
              <label for="type" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-4">
                  <input type="text" class="form-control" name="tamount" value="<?php echo $amount;?>" id="value" placeholder="Amount" required>
              </div>
                
              <label for="type" align="right" class="col-sm-2 form-control-label">Payment Method</label>
              <div class="col-sm-4">
                  <select name="pmethod" required class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                    <option value="<?php echo $pmethod;?>"><?php echo $pmethod;?></option>
                    <option value="cash">Cash</option>
                    <option value="cheque">Cheque</option>
                    <option value="card">Card</option>
                </select>
              </div>
     
            </div>   
               
           <div class="form-group row">
               
               
            <label for="type" align="" class="col-sm-2 form-control-label">Clearance Date</label>
            <div class="col-sm-4">
                  <input type="text" name="cdate" value="<?php echo $cdate;?>" id="date" placeholder="Clearance Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                
              <label for="type" align="right" class="col-sm-2 form-control-label">Cheque Date</label>
               
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
               
            </div>    

               
            <div class="form-group row">
              
            <label for="type" align="" class="col-sm-2 form-control-label">Cheque No:</label>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="checkno" value="<?php echo $checkno;?>">  
              </div>
              <label for="type" align="right" class="col-sm-2 form-control-label">Bank</label>
              <div class="col-sm-4">
                <?php
                    $sql_bank = "SELECT `category` FROM `expense_subcategories` WHERE `id`='$inward'";
                    $query_bank = mysqli_query($conn,$sql_bank);
                    $result_bank = mysqli_fetch_array($query_bank);
                    $bank_name = $result_bank['category'];
                ?>
                   <select class="form-control" name="inward" required>
                    <option value="<?php echo $inward;?>"><?php echo $bank_name;?></option>
                   <?php     
                    $sql = "SELECT * FROM `expense_subcategories` WHERE `parent`='36'";
    				$result = mysqli_query($conn, $sql);
    				if (mysqli_num_rows($result) > 0) 
    				{   
                    while($row = mysqli_fetch_assoc($result)) 
    				{
                    ?><option value="<?php echo $row["id"]; ?>"><?php echo $row["category"];?></option><?php
                    } }
                   ?>             
                   </select>
              </div>
              
            </div>
              
              
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
  
   <script type="text/javascript">
    $(document).ready(function() {
      $("#calculate").click(function() {
            var cat = $("#category").val();
            var sub = $("#subcategory").val();
            $.ajax({
              url: '<?php echo $baseurl;?>/loads/getAccountBalance',
              data: {category:cat,subcategory:sub},
              type: "POST",
              success: function(response) {
              var resp = $.trim(response);
                $("#actualBalance").val(resp);
              }
            });
        });
    });
   </script>
  
  <!-- / -->
<?php include "../includes/footer.php";?>