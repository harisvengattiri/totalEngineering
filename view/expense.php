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
if($_GET['exp'])
{
   $id = $_GET['exp'];
   $sql = "SELECT * FROM expenses WHERE id='$id'";
   $query = mysqli_query($conn,$sql);
   while($fetch=mysqli_fetch_array($query))
   {
       $particulars = $fetch['particulars'];
       $category1 = $fetch['category'];
               $query1 = "SELECT * FROM expense_categories WHERE id='$category1'";
               $result1 = $db->query($query1);
               while($row1 = $result1->fetch_assoc()){
                 $category = $row1['tag'];
               }
       $subcategory1 = $fetch['subcategory'];
               $query1 = "SELECT * FROM expense_subcategories WHERE id='$subcategory1'";
               $result1 = $db->query($query1);
               while($row1 = $result1->fetch_assoc()){
                 $subcategory = $row1['category'];
               }
       $purchaser = $fetch['purchaser'];
       $shop1 = $fetch['shop'];
               $query1 = "SELECT * FROM customers WHERE id='$shop1'";
               $result1 = $db->query($query1);
               while($row1 = $result1->fetch_assoc()){
                 $shop = $row1['name'];
               }
       $inv = $fetch['inv'];
       $amt = $fetch['amt'];
       $vat = $fetch['vat'];
       $amount = $fetch['amount'];
       $method = $fetch['method'];
       $notes = $fetch['notes'];
       $date = $fetch['date'];
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
          <h2>Expense View</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/view/expense" method="post">

          <div class="form-group row">
              <label for="particulars" class="col-sm-2 form-control-label">Invoice No</label>
              <div class="col-sm-4">
                   <input class="form-control" name="inv" value="<?php echo $inv;?>" placeholder="Invoice No">
              </div>
          </div>
               
               
<div class="form-group row">
              <label for="particulars" class="col-sm-2 form-control-label">Particulars</label>
              <div class="col-sm-4">
                <input type="particulars" class="form-control" name="particulars" value="<?php echo $particulars;?>" id="particulars" placeholder="Expense Name">
              </div>
              <label for="date" align="right" class="col-sm-2 form-control-label">Date</label>
              <div class="col-sm-4">
                   <input type="text" name="date" id="date" value="<?php echo $date;?>" placeholder="Payment Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                <input type="number" step="0.01" class="form-control" name="amt" id="input1" value="<?php echo $amt;?>"  placeholder="Expense Amount">
              </div>
              <label for="amount" align="right" class="col-sm-2 form-control-label">VAT</label>
              <div class="col-sm-4">
                <input type="number" step="0.01" class="form-control" name="vat" id="input2" value="<?php echo $vat;?>" value="0.00" placeholder="VAT Amount">
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
                   <input type="number" step="0.01" class="form-control" name="amount" value="<?php echo $amount;?>" id="output1" placeholder="Total Amount" readonly required>
              </div>
              <label for="method" align="right" class="col-sm-2 form-control-label">Method</label>
              <div class="col-sm-4">
                <select name="method" id="method" placeholder="Method" class="form-control">
                                <option value="<?php echo $method;?>"><?php echo $method;?></option>
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
         <option selected><?php echo $category;?></option>
         <option><?php // print_r($cats[0][val]);?></option>
    </select>
</div>
<label for="name" align="right" class="col-sm-2 form-control-label">Sub Category</label>
<div class="col-sm-4">
    <select name="subcategory" id="subcatsSelect" placeholder="SubCategory" class="form-control select2">
         <option><?php echo $subcategory;?></option>
    </select>
</div>
</body>
</div>
            <div class="form-group row">
    <!--          <label for="purchaser" class="col-sm-2 form-control-label" >Purchaser</label>-->
    <!--          <div class="col-sm-4">-->
    <!--           <select name="purchaser" id="purchaser" placeholder="Purchaser" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">-->
				<!--<option value="<?php // echo $purchaser;?>"><?php // echo $purchaser;?></option>-->
    <!--                <option value="">Others</option>-->
				<?php 
				// $sql = "SELECT id,name FROM customers where type='Staff' ORDER BY name ASC";
				// $result = mysqli_query($conn, $sql);
				// if (mysqli_num_rows($result) > 0) {
				// while($row = mysqli_fetch_assoc($result)) {
			    ?>
				    <!--<option value="<?php // echo $row["id"]; ?>"><?php // echo $row["name"]?></option>-->
				<?php // }} ?>
				<!--</select>-->
                <!-- </div>-->
              <label for="shop" align="left" class="col-sm-2 form-control-label">Supplier</label>
              <div class="col-sm-4"> 
                <select name="shop" id="shop" placeholder="supplier" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<option value="<?php echo $shop;?>"><?php echo $shop;?></option>
				<?php 
				$sql = "SELECT id,name FROM customers where type='Supplier' ORDER BY name ASC";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
				</select>
              </div>
            </div>

            <div class="form-group row">
              <label for="notes" class="col-sm-2 form-control-label">Notes</label>
              <div class="col-sm-10">
                   <input type="text" class="form-control" name="notes" value="<?php echo $notes;?>" id="notes" placeholder="Notes">
              </div>
            </div>
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                   <a href="<?php echo $baseurl;?>/expenses"><button type="button" class="btn btn-sm btn-outline rounded b-danger text-danger">Close</button></a>
                <!--<button name="submit"type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>-->
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