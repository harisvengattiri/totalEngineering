<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$jv = $_POST["jv"];  
    
$voucher=$_POST["voucher"];
$date = $_POST['date'];
$yr = substr($date, -2);
$inv = $_POST['inv'];
$note = $_POST['note'];

date_default_timezone_set('Asia/Dubai');
$time = date('Y-m-d H:i:s', time());

$sql = "UPDATE `jv` SET `voucher`='$voucher',`year`='$yr',`date`='$date',`inv`='$inv',`note`='$note',`time`='$time' WHERE `id`='$jv'";
if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
    
    $sql_del = "DELETE FROM `jv_items` WHERE `jv`='$jv'";
    $conn->query($sql_del);
    
    $sql_del_additionalAcc = "DELETE FROM `additionalAcc` WHERE `entry_id`='$jv' AND `section`='JNL'";
    $conn->query($sql_del_additionalAcc);
      
    $debitor_cat = $_POST['debitor_cat'];
    $debitor_sub_cat = $_POST['debitor_sub_cat'];
    $debt_amount = $_POST['debt_amt'];
    $debt_vat = $_POST['debt_vat'];
    $debt_note = $_POST['debt_note'];
    $n=sizeof($debitor_cat);
       for($i=0;$i<$n;$i++)
       {
       $debt_vat[$i] = ($debt_vat[$i] != NULL) ? $debt_vat[$i] : 0;
       $debt_total[$i] = $debt_amount[$i]+$debt_vat[$i];
           $sql1 = "INSERT INTO `jv_items` (`jv`,`date`,`type`,`note`,`cat`,`sub`,`amount`,`vat`,`total`) VALUES 
                    ('$jv','$date','debit','$debt_note[$i]','$debitor_cat[$i]','$debitor_sub_cat[$i]','$debt_amount[$i]','$debt_vat[$i]','$debt_total[$i]')";
           $conn->query($sql1);
           
           if($debt_vat[$i] != 0) {
               $sql_adtnl_vat = "INSERT INTO `additionalAcc`(`id`, `section`, `entry_id`, `date`, `cat`, `sub`, `amount`)
                                 VALUES ('','JNL','$jv','$date','39','176','$debt_vat[$i]')";
               $conn->query($sql_adtnl_vat);
           }
       }
    
    $creditor_cat = $_POST['creditor_cat'];
    $creditor_sub_cat = $_POST['creditor_sub_cat'];
    $crdt_amount = $_POST['crdt_amt'];
    $crdt_vat = $_POST['crdt_vat'];
    $crdt_note = $_POST['crdt_note'];
    $m=sizeof($creditor_cat);
       for($j=0;$j<$m;$j++)
       {
       $crdt_vat[$j] = ($crdt_vat[$j] != NULL) ? $crdt_vat[$j] : 0;
       $crdt_total[$j] = $crdt_amount[$j]+$crdt_vat[$j];
            $sql2 = "INSERT INTO `jv_items` (`jv`,`date`,`type`,`note`,`cat`,`sub`,`amount`,`vat`,`total`) VALUES 
                    ('$jv','$date','credit','$crdt_note[$j]','$creditor_cat[$j]','$creditor_sub_cat[$j]','$crdt_amount[$j]','$crdt_vat[$j]','$crdt_total[$j]')";
           $conn->query($sql2);
           
           if($crdt_vat[$j] != 0) {
               $sql_adtnl_vat = "INSERT INTO `additionalAcc`(`id`, `section`, `entry_id`, `date`, `cat`, `sub`, `amount`)
                                 VALUES ('','JNL','$jv','$date','27','184','$crdt_vat[$j]')";
               $conn->query($sql_adtnl_vat);
           }
       }
      
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
    $jv = $_GET['jv'];
    
    $sql = "SELECT * FROM `jv` WHERE `id`='$jv'";
    $query = mysqli_query($conn,$sql);
    $result = mysqli_fetch_array($query);
        $voucher = $result['voucher'];
        $date = $result['date'];
        $inv = $result['inv'];
        $note = $result['note'];
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
        
        <form id="addJournal" role="form" action="<?php echo $baseurl;?>/edit/jv?jv=<?php echo $jv;?>" method="post">
            <input type="hidden" name="jv" value="<?php echo $jv;?>">
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
                   <input type="text" class="form-control" name="voucher" value="<?php echo $voucher;?>" readonly>
              </div>
            </div>

            <div class="row">
                <label class="col-sm-4 form-control-label" align="center"><b>Particulars</b></label>
                <label class="col-sm-4 form-control-label" align="center"><b>Debit</b></label>
                <label class="col-sm-4 form-control-label" align="center"><b>Description</b></label>
            </div>
            
            
            <?php 
               $sql1="SELECT * FROM `jv_items` WHERE `jv`='$jv' AND `type`='debit'";
               $result1=$conn->query($sql1);
               $count1=$debit_total=0;
               while($row1 = mysqli_fetch_assoc($result1)) {

                $cat = $row1['cat'];
                    $sql_cat = "SELECT tag FROM `expense_categories` WHERE id='$cat'";
                    $query_cat = mysqli_query($conn,$sql_cat);
                    $result_cat = mysqli_fetch_array($query_cat);
                    $cat_name = $result_cat['tag'];
                $sub = $row1['sub'];
                    $sql_sub_cat = "SELECT category FROM `expense_subcategories` WHERE id='$sub'";
                    $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
                    $result_sub_cat = mysqli_fetch_array($query_sub_cat);
                    $sub_cat_name = $result_sub_cat['category'];
            
                   $amount = $row1['amount'];
                   $vat = $row1['vat'];
                   $total = $row1['total'];
                   $debit_total = $debit_total+$total;
                   $debt_note = $row1['note'];
            ?>
            <div class="form-group row">
                <label for="category" class="col-sm-1 form-control-label"><b>Dr.Acc</b></label>
                <div class="col-sm-3">
                    <select name="debitor_cat[]" id="debitor_cat<?php echo $count1;?>" required placeholder="category" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                        <?php 
                            $sql = "SELECT * FROM expense_categories";
                            $query = mysqli_query($conn,$sql);
                            if(mysqli_num_rows($query) > 0) {
                             echo '<option value="'.$cat.'">'.$cat_name.'</option>';
                            while($row = $query->fetch_assoc()) {
                        ?>
                        <option value="<?php echo $row['id'];?>"><?php echo $row['tag'];?></option>
                        <?php } } ?>
                    </select>
                </div>
                <!--<label for="name" align="right" class="col-sm-2 form-control-label">Sub Category</label>-->
                <div class="col-sm-2">
                    <select name="debitor_sub_cat[]" id="debitor_sub_cat<?php echo $count1;?>" placeholder="SubCategory" class="form-control select2">
                        <option value="<?php echo $sub;?>"><?php echo $sub_cat_name;?></option>
                    </select>
                </div>
                 <script type="text/javascript">
                 $(document).ready(function() {
                  $("#debitor_cat<?php echo $count1;?>").change(function() {
                    var cat_id = $(this).val();
                    if(cat_id != "") {
                      $.ajax({
                        url: '<?php echo $baseurl;?>/loads/subcat',
                        data:{cat_id:cat_id},
                        type:'POST',
                        success:function(response) {
                          var resp = $.trim(response);
                          $("#debitor_sub_cat<?php echo $count1;?>").html(resp);
                        }
                      });
                    } else {
                      $("#debitor_sub_cat<?php echo $count1;?>").html("<option value=''>------- Select --------</option>");
                    }
                  });
                });
                </script>
                <div class="col-sm-2">
                  <input type="text" class="form-control num1" name="debt_amt[]" placeholder="Amount" value="<?php echo $amount;?>">
                </div>
                <div class="col-sm-1">
                  <input type="text" class="form-control num2" name="debt_vat[]" placeholder="VAT" value="<?php echo $vat;?>">
                 </div>
                 <div class="col-sm-2">
                  <input type="text" class="form-control" name="debt_note[]" placeholder="Description" value="<?php echo $debt_note;?>">
                 </div>
                     <?php if($count1 == 0) { ?>
                     <div class="box-tools">
                        <a href="javascript:void(0);" class="btn btn-info btn-sm" id="btnAddMore" data-original-title="Add More">
                            <i class="fa fa-plus"></i>
                        </a>
                     </div>
                     <?php } else { ?>
                       <div class="box-tools">
                       <a href="javascript:void(0);" class="btn btn-danger btn-sm btnRemoveMoreSpecification" data-original-title="Remove">
                       <i class="fa fa-times"></i>
                       </a>
                       </div>
                     <?php } ?>
            </div>
            <?php $count1++; } ?>
            <div id="divAttach">

            </div>
            <hr>
            <div class="row">
                <label class="col-sm-4 form-control-label" align="center"><b>Particulars</b></label>
                <label class="col-sm-4 form-control-label" align="center"><b>Credit</b></label>
                <label class="col-sm-4 form-control-label" align="center"><b>Description</b></label>
            </div>
            <?php 
               $sql2="SELECT * FROM `jv_items` WHERE `jv`='$jv' AND `type`='credit'";
               $result2=$conn->query($sql2);
               $count2=$credit_total=0;
               while($row2 = mysqli_fetch_assoc($result2)) {
                   
                $cat = $row2['cat'];
                    $sql_cat = "SELECT tag FROM `expense_categories` WHERE id='$cat'";
                    $query_cat = mysqli_query($conn,$sql_cat);
                    $result_cat = mysqli_fetch_array($query_cat);
                    $cat_name = $result_cat['tag'];
                $sub = $row2['sub'];
                    $sql_sub_cat = "SELECT category FROM `expense_subcategories` WHERE id='$sub'";
                    $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
                    $result_sub_cat = mysqli_fetch_array($query_sub_cat);
                    $sub_cat_name = $result_sub_cat['category'];
            
                   $amount = $row2['amount'];
                   $vat = $row2['vat'];
                   $total = $row2['total'];
                   $credit_total = $credit_total+$total;
                   $crdt_note = $row2['note'];
            ?>
            <div class="form-group row">
                <label for="category" class="col-sm-1 form-control-label"><b>Cr.Acc</b></label>
                <div class="col-sm-3">
                    <select name="creditor_cat[]" id="creditor_cat<?php echo $count2;?>" required placeholder="category" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                        <?php 
                            $sql = "SELECT * FROM expense_categories";
                            $query = mysqli_query($conn,$sql);
                            if(mysqli_num_rows($query) > 0) {
                             echo '<option value="'.$cat.'">'.$cat_name.'</option>';
                            while($row = $query->fetch_assoc()) {
                        ?>
                        <option value="<?php echo $row['id'];?>"><?php echo $row['tag'];?></option>
                        <?php } } ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <select name="creditor_sub_cat[]" id="creditor_sub_cat<?php echo $count2;?>" placeholder="SubCategory" class="form-control select2">
                        <option value="<?php echo $sub;?>"><?php echo $sub_cat_name;?></option>
                    </select>
                </div>
                 <script type="text/javascript">
                 $(document).ready(function() {
                  $("#creditor_cat<?php echo $count2;?>").change(function() {
                    var cat_id = $(this).val();
                    if(cat_id != "") {
                      $.ajax({
                        url: '<?php echo $baseurl;?>/loads/subcat',
                        data:{cat_id:cat_id},
                        type:'POST',
                        success:function(response) {
                          var resp = $.trim(response);
                          $("#creditor_sub_cat<?php echo $count2;?>").html(resp);
                        }
                      });
                    } else {
                      $("#creditor_sub_cat<?php echo $count2;?>").html("<option value=''>------- Select --------</option>");
                    }
                  });
                });
                </script>
                <div class="col-sm-2">
                  <input type="text" class="form-control num3" name="crdt_amt[]" placeholder="Amount" value="<?php echo $amount;?>">
                </div>
                <div class="col-sm-1">
                  <input type="text" class="form-control num4" name="crdt_vat[]" placeholder="VAT" value="<?php echo $vat;?>">
                 </div>
                 <div class="col-sm-2">
                  <input type="text" class="form-control" name="crdt_note[]" placeholder="Description" value="<?php echo $crdt_note;?>">
                 </div>
                    <?php if($count2 == 0) { ?>
                    <div class="box-tools">
                        <a href="javascript:void(0);" class="btn btn-info btn-sm" id="btnAddMore2" data-original-title="Add More">
                           <i class="fa fa-plus"></i>
                        </a>
                     </div>
                     <?php } else { ?>
                       <div class="box-tools">
                       <a href="javascript:void(0);" class="btn btn-danger btn-sm btnRemoveMoreSpecification" data-original-title="Remove">
                       <i class="fa fa-times"></i>
                       </a>
                       </div>
                     <?php } ?>
            </div>
            <?php $count2++; } ?>
            <div id="divAttach2">

            </div>
            <hr>
               
            <div class="form-group row">
              <label for="Quantity" class="col-sm-1 form-control-label">Invoice No</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="inv" placeholder="Invoice No" value="<?php echo $inv;?>">
              </div>
              <label align="right" for="Quantity" class="col-sm-1 form-control-label">Note</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="note" placeholder="Note" value="<?php echo $note;?>">
              </div>
            </div>
            
            <div class="row">
                <label class="col-sm-4 form-control-label" align="center"><b>Total Amount</b></label>
                <label class="col-sm-4 form-control-label" align="center"><b id="result">Total Debit: <?php echo $debit_total;?></b></label>
                <label class="col-sm-4 form-control-label" align="center"><b id="result2">Total Credit: <?php echo $credit_total;?></b></label>
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



<!--Script to remove row while coming directly from database-->
<script>
    $(document).on('click', '.btnRemoveMoreSpecification', function () {
        $(this).parent('div').parent('div').remove();
    });
</script>


<!--Debit Section starts here-->
<script>
    function calculateSum() {
        var num1Elements = document.getElementsByClassName("num1");
        var num2Elements = document.getElementsByClassName("num2");
        var sum = 0;

        for (var i = 0; i < num1Elements.length; i++) {
            var num1 = parseFloat(num1Elements[i].value);
            var num2 = parseFloat(num2Elements[i].value);
            if (isNaN(num2)) { num2 = 0; }
            sum += num1 + num2;
        }

        // sum = sum.toFixed(2);
        sum = parseFloat(sum.toFixed(2));

        document.getElementById("result").innerHTML = "Total Debit: " + sum.toFixed(2);
        return sum;
    }

   function addSumCalculationEventListeners(element) {
      const num1Elements = element.getElementsByClassName("num1");
      const num2Elements = element.getElementsByClassName("num2");

      for (let i = 0; i < num1Elements.length; i++) {
          num1Elements[i].addEventListener("keyup", calculateSum);
          num2Elements[i].addEventListener("keyup", calculateSum);
      }
   }
</script>

<script>
$(document).ready(function (event) {
   addSumCalculationEventListeners(document);
   let journalDebitRow = 1;
   const MAX_DEBIT_ROWS = 39;
   $('#btnAddMore').click(function () {

     const debitRow = document.createElement('div');
     debitRow.setAttribute('class', 'form-group row');
     
     var debitInnerDiv = `<label for="category" class="col-sm-1 form-control-label"><b>Dr.Acc</b></label>
                <div class="col-sm-3">
                    <select name="debitor_cat[]" id="debitor_cat_${journalDebitRow}" required placeholder="category" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
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
                <div class="col-sm-2">
                    <select name="debitor_sub_cat[]" id="debitor_sub_cat_${journalDebitRow}" placeholder="SubCategory" class="form-control select2"></select>
                </div>
                <div class="col-sm-2">
                  <input type="text" class="form-control num1" name="debt_amt[]" placeholder="Amount">
                </div>
                <div class="col-sm-1">
                  <input type="text" class="form-control num2" name="debt_vat[]" placeholder="VAT">
                 </div>
                 <div class="col-sm-2">
                  <input type="text" class="form-control" name="debt_note[]" placeholder="Description">
                 </div>
                    <div class="box-tools">
                        <a href="javascript:void(0);" class="btn btn-danger btn-sm btnRemoveDebits" data-original-title="Remove"><i class="fa fa-times"></i></a>
                    </div>`;
            if(journalDebitRow <= MAX_DEBIT_ROWS) {
            $(debitRow).append(debitInnerDiv);
            $('#divAttach').append(debitRow);

                     $(debitRow).on('change', '[id^="debitor_cat_"]', function() {
                        var cat_id = $(this).val();
                        var journalDebitRow = this.id.split('_')[2];
                        if (cat_id != "") {
                           $.ajax({
                           url: '<?php echo $baseurl;?>/loads/subcat',
                           data: { cat_id: cat_id },
                           type: 'POST',
                           success: function(response) {
                              var resp = $.trim(response);
                              $("#debitor_sub_cat_" + journalDebitRow).html(resp);
                           }
                           });
                        } else {
                           $("#debitor_sub_cat_" + journalDebitRow).html("<option value=''>------- Select --------</option>");
                        }
                     });

         addSumCalculationEventListeners(debitRow);
         journalDebitRow++;

         $(debitRow).on('click', '.btnRemoveDebits', function () {
            $(debitRow).remove();
         });
      }
     });
});
</script>
<!--Debit Section ends here-->


<!--Credit Section starts here-->
<script>
    function calculateSum2() {
        var num3Elements = document.getElementsByClassName("num3");
        var num4Elements = document.getElementsByClassName("num4");
        var sum2 = 0;

        for (var i = 0; i < num3Elements.length; i++) {
            var num3 = parseFloat(num3Elements[i].value);
            var num4 = parseFloat(num4Elements[i].value);
            if (isNaN(num4)) { num4 = 0; }
            sum2 += num3 + num4;
        }

        // sum2 = sum2.toFixed(2);
        sum2 = parseFloat(sum2.toFixed(2));

        document.getElementById("result2").innerHTML = "Total Credit: " + sum2.toFixed(2);
        return sum2;
    }

   function addSumCalculationEventListeners2(element) {
      const num3Elements = element.getElementsByClassName("num3");
      const num4Elements = element.getElementsByClassName("num4");

      for (let i = 0; i < num3Elements.length; i++) {
          num3Elements[i].addEventListener("keyup", calculateSum2);
          num4Elements[i].addEventListener("keyup", calculateSum2);
      }
   }
</script>

<script>
$(document).ready(function (event) {
   addSumCalculationEventListeners2(document);
   let journalCreditRow = 1;
   const MAX_CREDIT_ROWS = 9;
   $('#btnAddMore2').click(function () {

     const creditRow = document.createElement('div');
     creditRow.setAttribute('class', 'form-group row');
     
     var creditInnerDiv = `<label for="category" class="col-sm-1 form-control-label"><b>Cr.Acc</b></label>
                <div class="col-sm-3">
                    <select name="creditor_cat[]" id="creditor_cat_${journalCreditRow}" required placeholder="category" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
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
                <div class="col-sm-2">
                    <select name="creditor_sub_cat[]" id="creditor_sub_cat_${journalCreditRow}" placeholder="SubCategory" class="form-control select2"></select>
                </div>
                <div class="col-sm-2">
                  <input type="text" class="form-control num3" name="crdt_amt[]" placeholder="Amount">
                </div>
                <div class="col-sm-1">
                  <input type="text" class="form-control num4" name="crdt_vat[]" placeholder="VAT">
                 </div>
                 <div class="col-sm-2">
                  <input type="text" class="form-control" name="crdt_note[]" placeholder="Description">
                 </div>
                    <div class="box-tools">
                        <a href="javascript:void(0);" class="btn btn-danger btn-sm btnRemoveCredits" data-original-title="Remove"><i class="fa fa-times"></i></a>
                    </div>`;
            if(journalCreditRow <= MAX_CREDIT_ROWS) {
            $(creditRow).append(creditInnerDiv);
            $('#divAttach2').append(creditRow);

                     $(creditRow).on('change', '[id^="creditor_cat_"]', function() {
                        var cat_id = $(this).val();
                        var journalCreditRow = this.id.split('_')[2];
                        if (cat_id != "") {
                           $.ajax({
                           url: '<?php echo $baseurl;?>/loads/subcat',
                           data: { cat_id: cat_id },
                           type: 'POST',
                           success: function(response) {
                              var resp = $.trim(response);
                              $("#creditor_sub_cat_" + journalCreditRow).html(resp);
                           }
                           });
                        } else {
                           $("#creditor_sub_cat_" + journalCreditRow).html("<option value=''>------- Select --------</option>");
                        }
                     });

         addSumCalculationEventListeners2(creditRow);
         journalCreditRow++;

         $(creditRow).on('click', '.btnRemoveCredits', function () {
            $(creditRow).remove();
         });
      }
     });
});
</script>
<script>
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