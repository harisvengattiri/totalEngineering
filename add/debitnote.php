<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{

$exp = $_POST['expense'];
$supplier = $_POST['supplier'];
$exp_amt = $_POST['exp_tot'];
$dbt_amt = $_POST['dbt_amt'];
$date = $_POST['date'];
$note = $_POST['note'];


$sql = "INSERT INTO `debit_note` (`exp`,`shop`,`exp_amt`,`dbt_amt`,`date`,`note`) 
VALUES ('$exp','$supplier','$exp_amt','$dbt_amt','$date','$note')";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="DBT".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}

}
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
          <h2>Add New Debit Note</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">

        <form role="form" action="<?php echo $baseurl;?>/add/debitnote" method="post">
            
            
            
            
            
            
            <div class="form-group row">
                <label for="category" class="col-sm-1 form-control-label">Supplier</label>
                <div class="col-sm-4">
                    <select name="supplier" id="supplier" required class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                        <?php 
                            $sql = "SELECT * FROM `customers` WHERE `type`='Supplier' ORDER BY `name`";
                            $query = mysqli_query($conn,$sql);
                            if(mysqli_num_rows($query) > 0) {
                             echo '<option value="">Select Supplier</option>';
                            while($row = $query->fetch_assoc()) {
                        ?>
                        <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
                        <?php } } ?>
                    </select>
                </div>
                <label for="name" align="right" class="col-sm-1 form-control-label">Expense</label>
                <div class="col-sm-4">
                    <select name="expense" id="expense" class="form-control select2"></select>
                </div>
                 <script type="text/javascript">
                 $(document).ready(function() {
                  $("#supplier").change(function() {
                    var supplier_id = $(this).val();
                    if(supplier_id != "") {
                      $.ajax({
                        url: '<?php echo $baseurl;?>/loads/expenses',
                        data:{s_id:supplier_id},
                        type:'POST',
                        success:function(response) {
                          var resp = $.trim(response);
                          $("#expense").html(resp);
                        }
                      });
                    } else {
                      $("#expense").html("<option value=''>------- Select --------</option>");
                    }
                  });
                });
                </script>
            </div>
            
            <div class="form-group row m-t-md">
              <div allign="right" class="col-sm-offset-2 col-sm-12">
                <button name="submit1" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Generate</button>
              </div>
            </div>
        </form>

        <?php if(isset($_POST['submit1'])) {
            $exp = $_POST['expense'];
            $sql_exp = "SELECT * FROM `expenses` WHERE id='$exp'";
            $query_exp = mysqli_query($conn,$sql_exp);
            $result_exp = mysqli_fetch_array($query_exp);
            $particulars = $result_exp['particulars'];
            $shop = $result_exp['shop'];
                $sql_shop = "SELECT * FROM `customers` WHERE id='$shop'";
                $query_shop = mysqli_query($conn,$sql_shop);
                $result_shop = mysqli_fetch_array($query_shop);
                $supplier = $result_shop['name'];
            $exp_amt = $result_exp['amt'];
            $exp_vat = $result_exp['vat'];
            $exp_total = $result_exp['amount'];
        ?>
        <form role="form" action="<?php echo $baseurl;?>/add/debitnote" method="post">
            
            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Expense</label>
              <div class="col-sm-8">
              <input type="text" class="form-control" value="EXP|<?php echo sprintf('%04d',$exp);?>" readonly>
              <input type="hidden" value="<?php echo $exp;?>" name="expense">
              </div>
            </div>

            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Particulars</label>
              <div class="col-sm-8">
              <input type="text" class="form-control" name="particulars" value="<?php echo $particulars;?>" readonly>
              </div>
            </div>

            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Supplier</label>
              <div class="col-sm-8">
              <input type="text" class="form-control" value="<?php echo $supplier;?>" readonly>
              <input type="hidden" name="supplier" value="<?php echo $shop;?>">
              </div>
            </div>

            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Expense Amount</label>
              <div class="col-sm-3">
              <input type="text" class="form-control" name="exp_amt" value="<?php echo $exp_amt;?>" readonly>
              </div>
              <div class="col-sm-2">
              <input type="text" class="form-control" name="exp_vat" value="<?php echo $exp_vat;?>" readonly>
              </div>
              <div class="col-sm-3">
              <input type="text" class="form-control" name="exp_tot" value="<?php echo $exp_total;?>" readonly>
              </div>
            </div>

            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Debit Amount</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="dbt_amt">
              </div>
              <label align="right" class="col-sm-1 form-control-label">Date</label>
                <div class="col-sm-4">
                    <input type="text" name="date" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options = "{
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
              <label for="Quantity" class="col-sm-2 form-control-label">Note</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="note" placeholder="Note">
              </div>
            </div>
              
            <div class="form-group row m-t-md">
              <div allign="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="submit"type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>
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