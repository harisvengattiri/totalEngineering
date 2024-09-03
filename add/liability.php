<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
  $query = "SELECT * FROM expense_categories";
  $result = $db->query($query);

  while($row = $result->fetch_assoc()){
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
    if(isset($_SESSION['userid']))
{
$particulars=$_POST["particulars"];
$category=$_POST["category"];
$subcategory=$_POST["subcategory"];
$purchaser=$_POST["purchaser"];
$shop=$_POST["shop"];
$date=$_POST["date"];
$amount=$_POST["amount"];
$method=$_POST["method"];
$notes=$_POST["notes"];

$inv=$_POST["inv"];
$vat=$_POST["vat"];
$amt=$_POST["amt"];



$sql = "INSERT INTO `liability` (`id`, `particular`, `cat`, `sub_cat`, `date`, `amt`,`vat`, `total`, `method`) 
VALUES ('NULL', '$particulars', '$category', '$subcategory', '$date', '$amt', '$vat', '$amount', '$method')";
if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
    $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="LBT".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
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
	<?php }?>
      <div class="box">
        <div class="box-header">
          <h2>Add New Liability</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/liability" method="post">
            <div class="form-group row">
              <label for="particulars" class="col-sm-2 form-control-label">Particulars</label>
              <div class="col-sm-4">
                <input type="particulars" class="form-control" name="particulars" id="particulars" placeholder="Expense Name">
              </div>
              <label for="date" align="right" class="col-sm-2 form-control-label">Date</label>
              <div class="col-sm-4">
                <input type="text" name="date" required id="date" placeholder="Payment Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <label for="amount" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-4">
                <input type="number" step="0.01" class="form-control" name="amt" id="input1"  placeholder="Expense Amount" required >
              </div>
              <label for="amount" align="right" class="col-sm-2 form-control-label">VAT</label>
              <div class="col-sm-4">
                <input type="number" step="0.01" class="form-control" name="vat" id="input2" value="0.00" placeholder="VAT Amount">
              </div>
            </div>
               
<script>
// $('#input1,#input2').keyup(function(){
//     var n1 = parseFloat($('#input1').val());
//     var n2 = parseFloat($('#input2').val());
//     if($(n2).empty())
//     {
//          var n3 = parseFloat($('#input1').val());
//     }
//     else
//     {
//          var n3 = parseFloat($('#input1').val()) + parseFloat($('#input2').val());
//     }
//     $('#output1').val(Number(Math.round(n3+'e2')+'e-2'));
     
     
//   $('#output1').val(n3 + n2);
 });
</script>

<script>
 $('#input1,#input2').keyup(function(){
     var n1 = parseFloat($('#input1').val());
     var n2 = parseFloat($('#input2').val());
     var n3 = parseFloat($('#input1').val()) + parseFloat($('#input2').val());
     $('#output1').val(Number(Math.round(n3+'e2')+'e-2'));
 });
</script>



               
            
            <div class="form-group row">
              <label for="method" class="col-sm-2 form-control-label">Total</label>
              <div class="col-sm-4">
                   <input type="number" step="0.01" class="form-control" name="amount" id="output1" placeholder="Total Amount" readonly required>
              </div>
              <label for="method" align="right" class="col-sm-2 form-control-label">Method</label>
              <div class="col-sm-4">
                <select name="method" id="method" placeholder="Method" class="form-control">
				<option value="Cash">Cash</option>
                                <option value="Card">Card</option>
                                <option value="Cheque">Cheque</option>
                                <option value="ETransfer">eTransfer</option>
				</select>
              </div>
            </div>    
               
               



<div class="form-group row">
<body onload='loadCategories()'>
<label for="category" class="col-sm-2 form-control-label">Category</label>
<div class="col-sm-4">
    <select name="category" id="categoriesSelect" placeholder="category" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
<option selected><?php // print_r($cats[0][val]);?></option>
    </select>
</div>
<label for="name" align="right" class="col-sm-2 form-control-label">Sub Category</label>
<div class="col-sm-4">
    <select name="subcategory" id="subcatsSelect" placeholder="SubCategory" class="form-control select2">
    </select>
</div>
</body>
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