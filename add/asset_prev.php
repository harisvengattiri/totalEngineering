<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
  $query = "SELECT * FROM asset_categories";
  $result = $db->query($query);

  while($row = $result->fetch_assoc()){
    $cats[] = array("id" => $row['id'], "val" => $row["tag"]);
  }

  $query = "SELECT * FROM asset_subcategories";
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
$name=$_POST["particulars"];
$category=$_POST["category"];
$subcategory=$_POST["subcategory"];
$date=$_POST["date"];
$amount=$_POST["amount"];
$notes=$_POST["notes"];
$sql = "INSERT INTO `assets` (`id`, `name`, `category`, `subcategory`, `date`, `amount`, `notes`) 
VALUES ('NULL', '$name', '$category', '$subcategory', '$date', '$amount', '$notes')";
if ($conn->query($sql) === TRUE) {
    $status="success";
} else {
    $status="failed";
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
	<?php }?>
      <div class="box">
        <div class="box-header">
          <h2>Add New Asset</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/asset" method="post">

<div class="form-group row">
              <label for="particulars" class="col-sm-2 form-control-label">Particulars</label>
              <div class="col-sm-10">
                <input type="particulars" class="form-control" name="particulars" id="particulars" placeholder="Asset Name">
              </div>
              </div>


            <div class="form-group row">
              <label for="amount" class="col-sm-2 form-control-label">Value</label>
              <div class="col-sm-4">
                <input type="number" step="0.01" class="form-control" name="amount" id="amount"  placeholder="Valued Amount">
              </div>
              <label for="date" align="right" class="col-sm-2 form-control-label">Date</label>
              <div class="col-sm-4">
                <input type="text" name="date" id="date" placeholder="Established Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
<body onload='loadCategories()'>
<label for="category" class="col-sm-2 form-control-label">Category</label>
<div class="col-sm-4">
    <select name="category" id="categoriesSelect" placeholder="category" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
<option selected><?php print_r($cats[0][val]);?></option>
    </select>
</div>
<label for="name" align="right" class="col-sm-2 form-control-label">Sub Category</label>
<div class="col-sm-4">
    <select name="subcategory" id="subcatsSelect" placeholder="SubCategory" class="form-control select2">
    </select>
</div>
</body>
</div>
            <div class="form-group row">
              <label for="notes" class="col-sm-2 form-control-label">Notes</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="notes" id="notes" placeholder="Notes">
              </div>
            </div>
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