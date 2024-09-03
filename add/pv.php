<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
if(isset($_SESSION['userid']))
{
$voucher=$_POST["voucher"];
$yr=$_POST["year"];
$subcategory=$_POST["subcategory"];
$category=$_POST["category"];
$date=$_POST["pdate"];
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
     
$sql = "INSERT INTO `pv` (`id`, `category`, `subcategory`, `date`, `year`, `amount`, `discount`, `grand`, `voucher`, `pmethod`, `clearance_date`, `status`, `duedate`, `checkno`, `bank`, `inward`, `time`) 
VALUES ('NULL', '$category', '$subcategory', '$date', '$yr', '$tamount', '$discount', '$grand', '$voucher', '$pmethod', '$cdate', '$status1', '$duedate', '$checkno', '36', '$inward', '$time')";

if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
               $date1=date("d/m/Y h:i:s a");
               $username=$_SESSION['username'];
               $code="PV".$last_id;
               $query=mysqli_real_escape_string($conn, $sql);
               $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'add', '$code', '$username', '$query')";
               $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}}
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
          <h2>Add New Payment Voucher</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
        
        <form role="form" action="<?php echo $baseurl;?>/add/pv" method="post">
            
            
            <div class="form-group row">
                <label for="category" class="col-sm-2 form-control-label">Category</label>
                <div class="col-sm-4">
                    <select name="category" id="category" required placeholder="category" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                        <?php 
                        $category=$_POST['category']; 
                        $subcategory=$_POST['subcategory'];
                        $pdate=$_POST['pdate'];
                            $sql_cat = "SELECT tag FROM `expense_categories` WHERE id='$category'";
                            $query_cat = mysqli_query($conn,$sql_cat);
                            $result_cat = mysqli_fetch_array($query_cat);
                            $cat_name = $result_cat['tag'];
                            $sql_sub_cat = "SELECT category FROM `expense_subcategories` WHERE id='$subcategory'";
                            $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
                            $result_sub_cat = mysqli_fetch_array($query_sub_cat);
                            $sub_cat_name = $result_sub_cat['category'];
                        
                            $sql = "SELECT * FROM expense_categories";
                            $query = mysqli_query($conn,$sql);
                            if(mysqli_num_rows($query) > 0) {
                             echo '<option value="'.$category.'">'.$cat_name.'</option>';
                            while($row = $query->fetch_assoc()) {
                        ?>
                        <option value="<?php echo $row['id'];?>"><?php echo $row['tag'];?></option>
                        <?php } } ?>
                    </select>
                </div>
                <label for="name" align="right" class="col-sm-2 form-control-label">Sub Category</label>
                <div class="col-sm-4">
                    <select name="subcategory" id="subcategory" placeholder="subcategory" class="form-control select2">
                        <option value="<?php echo $subcategory;?>"><?php echo $sub_cat_name;?></option>
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
               <?php
               if($pdate == NULL) {
               $today = date('d/m/Y'); $date = $today;
               } else { $date = $pdate; }
               ?>
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
            </div>
               
              <div class="form-group row m-t-md">
              <div align="" class="col-sm-offset-2 col-sm-12">
                <button name="submit1" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search</button>
              </div>
              </div>
         </form>     
             
          <?php if(isset($_POST['submit1']))
               {
               $category=$_POST['category']; 
               $subcategory=$_POST['subcategory'];
               
               $pdate=$_POST['pdate'];
               $yr = substr($pdate, -2);
            ?>   
          
          <form role="form" action="<?php echo $baseurl;?>/add/pv" method="post">
                 <input type="hidden" name="category" value="<?php echo $category;?>">
                 <input type="hidden" name="subcategory" value="<?php echo $subcategory;?>">
                 <input type="hidden" name="pdate" value="<?php echo $pdate;?>">
                 <input type="hidden" name="year" value="<?php echo $yr;?>">
                 
                 <?php
                    if($subcategory == NULL) {
                        $sql_calc1 = "SELECT sum(total) AS amt1 FROM `jv_items` WHERE `type`='debit' AND `cat`='$category'";
                        $sql_calc2 = "SELECT sum(grand) AS amt2 FROM `pv` WHERE `category`='$category'";
                        $sql_calc3 = "SELECT sum(dbt_amt) AS amt3 FROM `debitnote` WHERE `cat`='$category'";
                        $sql_calc4 = "SELECT sum(total) AS amt4 FROM `jv_items` WHERE `type`='credit' AND `cat`='$category'";
                        $sql_calc5 = "SELECT sum(op_bal) AS amt5 FROM `expense_subcategories` WHERE `parent`='$category'";
                    } else {
                        $sql_calc1 = "SELECT sum(total) AS amt1 FROM `jv_items` WHERE `type`='debit' AND `cat`='$category' AND `sub`='$subcategory'";
                        $sql_calc2 = "SELECT sum(grand) AS amt2 FROM `pv` WHERE `category`='$category' AND `subcategory`='$subcategory'";
                        $sql_calc3 = "SELECT sum(dbt_amt) AS amt3 FROM `debitnote` WHERE `cat`='$category' AND `sub`='$subcategory'";
                        $sql_calc4 = "SELECT sum(total) AS amt4 FROM `jv_items` WHERE `type`='credit' AND `cat`='$category' AND `sub`='$subcategory'";
                        $sql_calc5 = "SELECT sum(op_bal) AS amt5 FROM `expense_subcategories` WHERE `parent`='$category' AND `id`='$subcategory'";
                    }
                    $query_calc1 = mysqli_query($conn,$sql_calc1);
                    $result_calc1 = mysqli_fetch_array($query_calc1);
                    $amt1 = $result_calc1['amt1'];
                    $amt1 = ($amt1 != NULL) ? $amt1 : 0;
                    $query_calc2 = mysqli_query($conn,$sql_calc2);
                    $result_calc2 = mysqli_fetch_array($query_calc2);
                    $amt2 = $result_calc2['amt2'];
                    $amt2 = ($amt2 != NULL) ? $amt2 : 0;
                    $query_calc3 = mysqli_query($conn,$sql_calc3);
                    $result_calc3 = mysqli_fetch_array($query_calc3);
                    $amt3 = $result_calc3['amt3'];
                    $amt3 = ($amt3 != NULL) ? $amt3 : 0;
                    $query_calc4 = mysqli_query($conn,$sql_calc4);
                    $result_calc4 = mysqli_fetch_array($query_calc4);
                    $amt4 = $result_calc4['amt4'];
                    $amt4 = ($amt4 != NULL) ? $amt4 : 0;
                    $query_calc5 = mysqli_query($conn,$sql_calc5);
                    $result_calc5 = mysqli_fetch_array($query_calc5);
                    $amt5 = $result_calc5['amt5'];
                    $amt5 = ($amt5 != NULL) ? $amt5 : 0;
                    
                    $bal_amt = ($amt1+$amt2+$amt3) - ($amt4+$amt5);
                 ?>
                 
            <div class="form-group row">
              <label for="type" align="" class="col-sm-2 form-control-label">Voucher No</label>
              <div class="col-sm-4">
                   <?php
                     $sqlvou="SELECT voucher from pv WHERE year='$yr' ORDER BY voucher DESC LIMIT 1";
                              $querycust=mysqli_query($conn,$sqlvou);
                              $fetchcust=mysqli_fetch_array($querycust);
                              $voucher=$fetchcust['voucher'];
                              $vou=$voucher+1;
                   ?>
                   <input type="text" class="form-control" name="voucher" value="<?php echo $vou;?>" readonly>
              </div>
              <label for="type" align="right" class="col-sm-2 form-control-label">Balance Amount</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" value="<?php echo $bal_amt;?>" readonly>
              </div>
            </div>
            
            
            

                 
            <div class="form-group row">
              <label for="type" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-4">
                  <input type="text" class="form-control" name="tamount" id="value" placeholder="Amount" required>
              </div>
                
              <label for="type" align="right" class="col-sm-2 form-control-label">Payment Method</label>
              <div class="col-sm-4">
                  <select name="pmethod" required class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                    <option value="">Payment Method</option>
                    <option value="cash">Cash</option>
                    <option value="cheque">Cheque</option>
                    <option value="card">Card</option>
                </select>
              </div>
     
            </div>   
               
           <div class="form-group row">
               
               
            <label for="type" align="" class="col-sm-2 form-control-label">Clearance Date</label>
            <div class="col-sm-4">
                  <input type="text" name="cdate" id="date" placeholder="Clearance Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                  <input type="text" name="duedate" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <input type="text" class="form-control" name="checkno">    
              </div>
              <label for="type" align="right" class="col-sm-2 form-control-label">Bank</label>
              <div class="col-sm-4">
                   <select class="form-control" name="inward" required>
                   <?php     
                    $sql = "SELECT * FROM `expense_subcategories` WHERE `parent`='36'";
    				$result = mysqli_query($conn, $sql);
    				if (mysqli_num_rows($result) > 0) 
    				{
                    ?><option value="">SELECT BANK</option><?php     
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