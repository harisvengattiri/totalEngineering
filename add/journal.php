<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$particulars = '';
$supplier = '';
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
$yr=$_POST["year"];

date_default_timezone_set('Asia/Dubai');
$time = date('Y-m-d H:i:s', time());

$sql = "INSERT INTO `journal` (`particulars`,`shop`,`voucher`,`year`,`crdt_cat`,`crdt_sub`,`crdt_amount`,`crdt_vat`,`crdt_total`,`debt_cat`,`debt_sub`,`debt_amount`,`debt_vat`,`debt_total`,`date`,`inv`,`note`,`time`) 
VALUES ('$particulars','$supplier','$voucher','$yr','$creditor_cat','$creditor_sub_cat','$crdt_amount','$crdt_vat','$crdt_total','$debitor_cat','$debitor_sub_cat','$debt_amount','$debt_vat','$debt_total','$date','$inv','$note','$time')";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="JNL".$last_id;
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
          <h2>Add New Journal</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">


        <?php
            // $exp = $_POST['expense'];
            // $sql_exp = "SELECT * FROM `expenses` WHERE id='$exp'";
            // $query_exp = mysqli_query($conn,$sql_exp);
            // $result_exp = mysqli_fetch_array($query_exp);
            // $particulars = $result_exp['particulars'];
            // $shop = $result_exp['shop'];
            //     $sql_shop = "SELECT * FROM `customers` WHERE id='$shop'";
            //     $query_shop = mysqli_query($conn,$sql_shop);
            //     $result_shop = mysqli_fetch_array($query_shop);
            //     $supplier = $result_shop['name'];
            // $exp_amt = $result_exp['amt'];
            // $exp_vat = $result_exp['vat'];
            // $exp_total = $result_exp['amount'];
            
            // $exp_cat = $result_exp['category'];
            //     $sql_cat = "SELECT tag FROM `expense_categories` WHERE id='$exp_cat'";
            //     $query_cat = mysqli_query($conn,$sql_cat);
            //     $result_cat = mysqli_fetch_array($query_cat);
            //     $cat_name = $result_cat['tag'];
            // $exp_sub_cat = $result_exp['subcategory'];
            //     $sql_sub_cat = "SELECT category FROM `expense_subcategories` WHERE id='$exp_sub_cat'";
            //     $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
            //     $result_sub_cat = mysqli_fetch_array($query_sub_cat);
            //     $sub_cat_name = $result_sub_cat['category'];
        ?>
        
        <form role="form" action="<?php echo $baseurl;?>/add/journal" method="post">
            <?php
                $date=$_POST['date'];
            ?>
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
            
            <div class="form-group row m-t-md">
              <div align="" class="col-sm-offset-2 col-sm-12">
                <button name="submit1" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search</button>
              </div>
            </div>
        </form>
        <?php if(isset($_POST['submit1']))
               {
               $date=$_POST['date'];
               $yr = substr($date, -2);
            ?>
        <form id="addJournal" role="form" action="<?php echo $baseurl;?>/add/journal" method="post">
            <input type="hidden" name="date" value="<?php echo $date;?>">
            <input type="hidden" name="year" value="<?php echo $yr;?>">
            
            <div class="form-group row">
              <label for="type" align="" class="col-sm-2 form-control-label">Voucher No</label>
              <div class="col-sm-4">
                   <?php
                     $sqlvou="SELECT voucher from journal WHERE year='$yr' ORDER BY voucher DESC LIMIT 1";
                              $querycust=mysqli_query($conn,$sqlvou);
                              $fetchcust=mysqli_fetch_array($querycust);
                              $voucher=$fetchcust['voucher'];
                              $vou=$voucher+1;
                   ?>
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
                        <?php 
                            $sql = "SELECT * FROM expense_categories";
                            $query = mysqli_query($conn,$sql);
                            if(mysqli_num_rows($query) > 0) {
                             echo '<option value="">Select Category</option>';
                            while($row = $query->fetch_assoc()) {
                        ?>
                        <option value="<?php echo $row['id'];?>"><?php echo $row['tag'];?></option>
                        <?php } } ?>
                    </select>
                </div>
                <!--<label for="name" align="right" class="col-sm-2 form-control-label">Sub Category</label>-->
                <div class="col-sm-2">
                    <select name="debitor_sub_cat" id="debitor_sub_cat" placeholder="SubCategory" class="form-control select2"></select>
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
                  <input type="text" class="form-control" name="debt_amt" id="num1" placeholder="Amount">
                </div>
                <div class="col-sm-1">
                  <input type="text" class="form-control" name="debt_vat" id="num2" placeholder="VAT">
                 </div>
            </div>
            
            <div class="form-group row">
                <label for="category" class="col-sm-1 form-control-label"><b>Cr.Acc</b></label>
                <div class="col-sm-3">
                    <select name="creditor_cat" id="creditor_cat" required placeholder="category" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                        <?php 
                            $sql = "SELECT * FROM expense_categories";
                            $query = mysqli_query($conn,$sql);
                            if(mysqli_num_rows($query) > 0) {
                             echo '<option value="">Select Category</option>';
                            while($row = $query->fetch_assoc()) {
                        ?>
                        <option value="<?php echo $row['id'];?>"><?php echo $row['tag'];?></option>
                        <?php } } ?>
                    </select>
                </div>
                <!--<label for="name" align="right" class="col-sm-2 form-control-label">Sub Category</label>-->
                <div class="col-sm-2">
                    <select name="creditor_sub_cat" id="creditor_sub_cat" placeholder="SubCategory" class="form-control select2"></select>
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
                  <input type="text" class="form-control" name="crdt_amt" id="num3" placeholder="Amount">
                </div>
                <div class="col-sm-1">
                  <input type="text" class="form-control" name="crdt_vat" id="num4" placeholder="VAT">
                 </div>
            </div>
            
            <!--<div class="form-group row">-->
            <!--    <body onload='loadCategories()'>-->
            <!--        <label for="category" class="col-sm-1 form-control-label"><b>Cr.Acc</b></label>-->
            <!--        <div class="col-sm-3">-->
            <!--            <select name="creditor_cat" id="categoriesSelect" required placeholder="category" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">-->
            <!--                <option selected></option>-->
            <!--            </select>-->
            <!--        </div>-->
            <!--        <div class="col-sm-2">-->
            <!--            <select name="creditor_sub_cat" id="subcatsSelect" placeholder="SubCategory" class="form-control select2">-->
            <!--            </select>-->
            <!--        </div>-->
            <!--        <div class="col-sm-3"></div>-->
            <!--        <div class="col-sm-2">-->
            <!--          <input type="text" class="form-control" name="crdt_amt" id="num3" placeholder="Amount">-->
            <!--        </div>-->
            <!--        <div class="col-sm-1">-->
            <!--          <input type="text" class="form-control" name="crdt_vat" id="num4" placeholder="VAT">-->
            <!--        </div>-->
            <!--    </body>-->
            <!--</div>-->
               
            <div class="form-group row">
              <label for="Quantity" class="col-sm-1 form-control-label">Invoice No</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="inv" placeholder="Invoice No">
              </div>
              <label align="right" for="Quantity" class="col-sm-1 form-control-label">Note</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="note" placeholder="Note">
              </div>
            </div>
            
            <div class="row">
                <label class="col-sm-4 form-control-label" align="center"><b>Total Amount</b></label>
                <label class="col-sm-4 form-control-label" align="center"><b id="result"></b></label>
                <label class="col-sm-4 form-control-label" align="center"><b id="result2"></b></label>
            </div>
            
              
            <div class="form-group row m-t-md">
              <div allign="right" class="col-sm-offset-2 col-sm-12">
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