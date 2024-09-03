<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$jv = $_POST['id'];

$creditor_cat = $_POST['creditor_cat'];
$creditor_sub_cat = $_POST['creditor_sub_cat'];
$crdt_amount = $_POST['crdt_amt'];
$crdt_vat = $_POST['crdt_vat'];
$crdt_vat = ($crdt_vat != NULL) ? $crdt_vat : 0;
$crdt_total = $crdt_amount+$crdt_vat;

$debitor_cat = $_POST['debitor_cat'];
$debitor_sub_cat = $_POST['debitor_sub_cat'];
$debt_amount = $_POST['debt_amt'];
$debt_vat = $_POST['debt_vat'];
$debt_vat = ($debt_vat != NULL) ? $debt_vat : 0;
$debt_total = $debt_amount+$debt_vat;

$date = $_POST['date'];
$inv = $_POST['inv'];
$note = $_POST['note'];

$voucher=$_POST["voucher"];
$yr = substr($date, -2);

date_default_timezone_set('Asia/Dubai');
$time = date('Y-m-d H:i:s', time());

$sql = "UPDATE `journal` SET `voucher`='$voucher',`year`='$yr',`crdt_cat`='$creditor_cat',`crdt_sub`='$creditor_sub_cat',`crdt_amount`='$crdt_amount',`crdt_vat`='$crdt_vat',`crdt_total`='$crdt_total',
               `debt_cat`='$debitor_cat',`debt_sub`='$debitor_sub_cat',`debt_amount`='$debt_amount',`debt_vat`='$debt_vat',`debt_total`='$debt_total',`date`='$date',`inv`='$inv',`note`='$note',`time`='$time'
                WHERE id='$jv'";

if ($conn->query($sql) === TRUE) {
    $status="success";
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="JNL".$jv;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}

}

if($_GET) {
    $jv = $_GET['id'];
    
        $sql = "SELECT * FROM `journal` WHERE id='$jv'";
        $query = mysqli_query($conn,$sql);
        $result = mysqli_fetch_array($query);
        
        $debt_amount = $result['debt_amount'];
        $debt_vat = $result['debt_vat'];
        
        $crdt_amount = $result['crdt_amount'];
        $crdt_vat = $result['crdt_vat'];
        
        $vou = $result['voucher'];
        $date = $result['date'];
        $inv = $result['inv'];
        $note = $result['note'];
            
            $debt_cat = $result['debt_cat'];
                $sql_pre_cat = "SELECT * FROM `expense_categories` WHERE id='$debt_cat'";
                $query_pre_cat = mysqli_query($conn,$sql_pre_cat);
                $result_pre_cat = mysqli_fetch_array($query_pre_cat);
                $debt_cat_name = $result_pre_cat['tag'];
            $debt_sub = $result['debt_sub'];
                $sql_pre_sub_cat = "SELECT * FROM `expense_subcategories` WHERE id='$debt_sub'";
                $query_pre_sub_cat = mysqli_query($conn,$sql_pre_sub_cat);
                $result_pre_sub_cat = mysqli_fetch_array($query_pre_sub_cat);
                $debt_sub_name = $result_pre_sub_cat['category'];
                
            $crdt_cat = $result['crdt_cat'];
                $sql_cat = "SELECT * FROM `expense_categories` WHERE id='$crdt_cat'";
                $query_cat = mysqli_query($conn,$sql_cat);
                $result_cat = mysqli_fetch_array($query_cat);
                $crdt_cat_name = $result_cat['tag'];
            $crdt_sub = $result['crdt_sub'];
                $sql_sub_cat = "SELECT * FROM `expense_subcategories` WHERE id='$crdt_sub'";
                $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
                $result_sub_cat = mysqli_fetch_array($query_sub_cat);
                $crdt_sub_name = $result_sub_cat['category'];
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
	<?php }?>
      <div class="box">
        <div class="box-header">
          <h2>Edit Journal</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
        
        <form id="addJournal" role="form" action="<?php echo $baseurl;?>/edit/journal?id=<?php echo $jv;?>" method="post">
            <input type="hidden" name="id" value="<?php echo $jv;?>">
            <div class="form-group row">
              <label class="col-sm-1 form-control-label">Date</label>
                <div class="col-sm-3">
                    <input type="text" name="date" id="date" value="<?php echo $date;?>" placeholder="Date" required class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options = "{
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
              <label for="type" align="" class="col-sm-2 form-control-label">Voucher No</label>
              <div class="col-sm-4">
                   <input type="text" class="form-control" name="voucher" value="<?php echo $vou;?>" readonly>
              </div>
            </div>

            <div class="row">
                <label class="col-sm-4 form-control-label" align="center"><b>Particulars</b></label>
                <label class="col-sm-4 form-control-label" align="center"><b>Debit</b></label>
                <label class="col-sm-4 form-control-label" align="center"><b>Credit</b></label>
            </div>
            <div class="form-group row">
                <label for="category" class="col-sm-1 form-control-label"><b>Dr.Acc</b></label>
                <div class="col-sm-3">
                    <select name="debitor_cat" id="debitor_cat" required placeholder="category" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                        <option value="<?php echo $debt_cat;?>"><?php echo $debt_cat_name;?></option>
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
                <!--<label for="name" align="right" class="col-sm-2 form-control-label">Sub Category</label>-->
                <div class="col-sm-2">
                    <select name="debitor_sub_cat" id="debitor_sub_cat" placeholder="SubCategory" class="form-control select2">
                        <option value="<?php echo $debt_sub;?>"><?php echo $debt_sub_name;?></option>
                    </select>
                </div>
                 <script type="text/javascript">
                 $(document).ready(function() {
                  $("#debitor_cat").change(function() {
                    var cat_id = $(this).val();
                    if(cat_id != "") {
                      $.ajax({
                        url: '<?php echo $baseurl;?>/loads/subcat',
                        data:{cat_id:cat_id},
                        type:'POST',
                        success:function(response) {
                          var resp = $.trim(response);
                          $("#debitor_sub_cat").html(resp);
                        }
                      });
                    } else {
                      $("#debitor_sub_cat").html("<option value=''>------- Select --------</option>");
                    }
                  });
                });
                </script>
                <div class="col-sm-2">
                  <input type="text" class="form-control" name="debt_amt" id="num1" value="<?php echo $debt_amount;?>" placeholder="Amount">
                </div>
                <div class="col-sm-1">
                  <input type="text" class="form-control" name="debt_vat" id="num2" value="<?php echo $debt_vat;?>" placeholder="VAT">
                 </div>
            </div>
            
            <div class="form-group row">
                <label for="category" class="col-sm-1 form-control-label"><b>Cr.Acc</b></label>
                <div class="col-sm-3">
                    <select name="creditor_cat" id="creditor_cat" required placeholder="category" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                        <option value="<?php echo $crdt_cat;?>"><?php echo $crdt_cat_name;?></option>
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
                <!--<label for="name" align="right" class="col-sm-2 form-control-label">Sub Category</label>-->
                <div class="col-sm-2">
                    <select name="creditor_sub_cat" id="creditor_sub_cat" placeholder="SubCategory" class="form-control select2">
                        <option value="<?php echo $crdt_sub;?>"><?php echo $crdt_sub_name;?></option>
                    </select>
                </div>
                 <script type="text/javascript">
                 $(document).ready(function() {
                  $("#creditor_cat").change(function() {
                    var cat_id = $(this).val();
                    if(cat_id != "") {
                      $.ajax({
                        url: '<?php echo $baseurl;?>/loads/subcat',
                        data:{cat_id:cat_id},
                        type:'POST',
                        success:function(response) {
                          var resp = $.trim(response);
                          $("#creditor_sub_cat").html(resp);
                        }
                      });
                    } else {
                      $("#creditor_sub_cat").html("<option value=''>------- Select --------</option>");
                    }
                  });
                });
                </script>
                <div class="col-sm-3"></div>
                <div class="col-sm-2">
                  <input type="text" class="form-control" name="crdt_amt" id="num3" value="<?php echo $crdt_amount;?>" placeholder="Amount">
                </div>
                <div class="col-sm-1">
                  <input type="text" class="form-control" name="crdt_vat" id="num4" value="<?php echo $crdt_vat;?>" placeholder="VAT">
                 </div>
            </div>
            
               
            <div class="form-group row">
              <label for="Quantity" class="col-sm-1 form-control-label">Invoice No</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="inv" value="<?php echo $inv;?>" placeholder="Invoice No">
              </div>
              <label align="right" for="Quantity" class="col-sm-1 form-control-label">Note</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="note" value="<?php echo $note;?>" placeholder="Note">
              </div>
            </div>
            
            <div class="row">
                <label class="col-sm-4 form-control-label" align="center"><b>Total Amount</b></label>
                <label class="col-sm-4 form-control-label" align="center"><b id="result">Total Debit: <?php echo $debt_amount+$debt_vat;?></b></label>
                <label class="col-sm-4 form-control-label" align="center"><b id="result2">Total Credit: <?php echo $crdt_amount+$crdt_vat;?></b></label>
            </div>
            
              
            <div class="form-group row m-t-md">
              <div allign="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>
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
  
    
    <script>
        function calculateSum() {
            var num1 = parseFloat(document.getElementById("num1").value);
            var num2 = parseFloat(document.getElementById("num2").value);
            if (isNaN(num2)) { num2 = 0;}
            var sum = num1 + num2;
            var sum = sum.toFixed(2);
            document.getElementById("result").innerHTML = "Total Debit: " + sum;
            return sum;
        }
        
        window.addEventListener("load", function() {
            document.getElementById("num1").addEventListener("keyup", calculateSum);
            document.getElementById("num2").addEventListener("keyup", calculateSum);
        });
        
        function calculateSum2() {
            var num3 = parseFloat(document.getElementById("num3").value);
            var num4 = parseFloat(document.getElementById("num4").value);
            if (isNaN(num4)) { num4 = 0;}
            var sum2 = num3 + num4;
            var sum2 = sum2.toFixed(2);
            document.getElementById("result2").innerHTML = "Total Credit: " + sum2;
            return sum2;
        }
        window.addEventListener("load", function() {
            document.getElementById("num3").addEventListener("keyup", calculateSum2);
            document.getElementById("num4").addEventListener("keyup", calculateSum2);
        });

        document.getElementById("addJournal").addEventListener("submit", function(event) {
            var sum = calculateSum();
            var sum2 = calculateSum2();
              if(sum != sum2) {
                event.preventDefault();
                alert("Total Debit: " + sum + "\nTotal Credit: " + sum2);
              }
        });
    </script>
  <!-- / -->
<?php include "../includes/footer.php";?>