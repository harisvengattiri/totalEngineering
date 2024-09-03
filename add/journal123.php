<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
  $query = "SELECT * FROM expense_categories";
  $result = $db->query($query);
  $cats[] = array("id" => "", "val" => "Select Category");
  while($row = $result->fetch_assoc()) {
    $cats[] = array("id" => $row['id'], "val" => $row["tag"]);
  }

  $query = "SELECT * FROM expense_subcategories";
  $result = $db->query($query);

  while($row = $result->fetch_assoc()){
    $subcats[$row['parent']][] = array("id" => $row['id'], "val" => $row['category']);
  }
  $jsonCats = json_encode($cats);
  $jsonSubCats = json_encode($subcats);
?>
<script type='text/javascript'>
      <?php
        echo "var categories = $jsonCats; \n";
        echo "var subcats = $jsonSubCats; \n";
      ?>
      function loadCategories(){
        var select = document.getElementById("categoriesSelect");
        select.onchange = updateSubCats;
        for(var i = 0; i < categories.length; i++){
          select.options[i] = new Option(categories[i].val,categories[i].id);          
        }
      }
      function updateSubCats(){
        var catSelect = this;
        var catid = this.value;
        var subcatSelect = document.getElementById("subcatsSelect");
        subcatSelect.options.length = 0; //delete all options if any present
        for(var i = 0; i < subcats[catid].length; i++){
          subcatSelect.options[i] = new Option(subcats[catid][i].val,subcats[catid][i].id);
        }
      }
</script>
<?php
$status="NULL";
if(isset($_POST['submit']))
{

$exp = $_POST['expense'];
$supplier = $_POST['supplier'];
$exp_amt = $_POST['exp_tot'];
$exp_prev_cat = $_POST['exp_prev_cat'];
$exp_new_cat = $_POST['exp_new_cat'];
$exp_prev_sub_cat = $_POST['exp_prev_sub_cat'];
$exp_new_sub_cat = $_POST['exp_new_sub_cat'];
$date = $_POST['date'];
$note = $_POST['note'];


$sql = "INSERT INTO `journal` (`exp`,`shop`,`exp_amt`,`pre_cat`,`cat`,`pre_sub_cat`,`sub_cat`,`date`,`note`) 
VALUES ('$exp','$supplier','$exp_amt','$exp_prev_cat','$exp_new_cat','$exp_prev_sub_cat','$exp_new_sub_cat','$date','$note')";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       
        $sql_expense = "UPDATE `expenses` SET `category`='$exp_new_cat',`subcategory`='$exp_new_sub_cat' WHERE `id`='$exp'";
        $conn->query($sql_expense);
       
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
          <h2>Add New Journal</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">

        <form role="form" action="<?php echo $baseurl;?>/add/journal" method="post">
            
            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Expense</label>
              <div class="col-sm-8">
                <select name="expense" id="get" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}" required>
                    <?php 
                        $sql = "SELECT * FROM expenses";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) 
                        {
                        ?><option value="">Select Expense</option><?php
                        while($row = mysqli_fetch_assoc($result)) 
                        {
                        ?>
                        <option value="<?php echo $row["id"]; ?>">EXP|<?php echo sprintf('%04d',$row["id"]);?></option>
                        <?php } } ?>
                </select>
              </div>
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
            
            $exp_cat = $result_exp['category'];
                $sql_cat = "SELECT tag FROM `expense_categories` WHERE id='$exp_cat'";
                $query_cat = mysqli_query($conn,$sql_cat);
                $result_cat = mysqli_fetch_array($query_cat);
                $cat_name = $result_cat['tag'];
            $exp_sub_cat = $result_exp['subcategory'];
                $sql_sub_cat = "SELECT category FROM `expense_subcategories` WHERE id='$exp_sub_cat'";
                $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
                $result_sub_cat = mysqli_fetch_array($query_sub_cat);
                $sub_cat_name = $result_sub_cat['category'];
        ?>
        <form role="form" action="<?php echo $baseurl;?>/add/journal" method="post">
            
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
                <label for="category" class="col-sm-2 form-control-label">Category</label>
                <div class="col-sm-4">
                <input type="text" class="form-control" value="<?php echo $cat_name;?>" readonly>
                <input type="hidden" name="exp_prev_cat" value="<?php echo $exp_cat;?>">
                </div>
                <label align="right" class="col-sm-2 form-control-label">Sub Category</label>
                <div class="col-sm-4">
                <input type="text" class="form-control" value="<?php echo $sub_cat_name;?>" readonly>
                <input type="hidden" name="exp_prev_sub_cat" value="<?php echo $exp_sub_cat;?>">
                </div>
            </div>
            
            <div class="form-group row">
                <body onload='loadCategories()'>
                    <label for="category" class="col-sm-2 form-control-label">Category</label>
                    <div class="col-sm-4">
                        <select name="exp_new_cat" id="categoriesSelect" placeholder="category" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                            <option selected><?php // print_r($cats[0][val]);?></option>
                        </select>
                    </div>
                    <label for="name" align="right" class="col-sm-2 form-control-label">Sub Category</label>
                    <div class="col-sm-4">
                        <select name="exp_new_sub_cat" id="subcatsSelect" placeholder="SubCategory" class="form-control select2">
                        </select>
                    </div>
                </body>
            </div>

            <div class="form-group row">
              <label class="col-sm-2 form-control-label">Date</label>
                <div class="col-sm-8">
                    <input type="text" name="date" id="date" placeholder="Date" required class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options = "{
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