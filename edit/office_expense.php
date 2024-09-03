<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$particulars=$_POST["particulars"];
$category=$_POST["category"];
$purchaser=$_POST["purchaser"];
$shop=$_POST["shop"];
$date=$_POST["date"];
$amount=$_POST["amount"];
$method=$_POST["method"];
$notes=$_POST["notes"];
$oxp=$_POST["oxp"];
$sql = "UPDATE `office_expenses` SET 
`particulars` =  '$particulars', `category` =  '$category', `purchaser` = '$purchaser', `shop` = '$shop',
`date` =  '$date', `method` = '$method', `amount` =  '$amount', `notes` =  '$notes' WHERE  `id` = $oxp";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="oxp".$oxp;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}


if ($_GET) 
{
$oxp=$_GET["oxp"];
}

	$sql = "SELECT * FROM office_expenses where id=$oxp";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {

              $particulars=$row["particulars"];
              $category=$row["category"];
              $purchaser=$row["purchaser"];
              $shop=$row["shop"];
              $date=$row["date"];
              $amount=$row["amount"];
              $method=$row["method"];
              $notes=$row["notes"];
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
          <h2>Edit Office Expense</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/edit/office_expense" method="post">

<div class="form-group row">
               <input name="oxp"  type="text" value="<?php echo $oxp;?>" hidden="hidden">
              <label for="particulars" class="col-sm-2 form-control-label">Particulars</label>
              <div class="col-sm-4">
                <input type="particulars" class="form-control" name="particulars" value="<?php echo $particulars;?>" id="particulars" placeholder="Expense Name">
              </div>
              <label for="Category" align="right" class="col-sm-2 form-control-label">Category</label>
              <div class="col-sm-4">
               <select name="category" id="category" placeholder="Category" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">

				<option value="<?php echo $category;?>"><?php echo ucfirst($category);?></option>
				<?php 
				$sql = "SELECT tag FROM expense_categories ORDER BY tag ASC";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["tag"]; ?>"><?php echo $row["tag"]?></option>
				<?php 
				}} 
				?>
				</select>
				
              </div>
</div>
            <div class="form-group row">
              <label for="purchaser" class="col-sm-2 form-control-label" >Purchaser</label>
              <div class="col-sm-4">
               <select name="purchaser" id="purchaser" placeholder="Purchaser" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
				$sql = "SELECT id,name FROM customers where id=$purchaser";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				$sql = "SELECT id,name FROM customers where type='Staff' or type='Bank' ORDER BY name ASC";
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
              <label for="shop" align="right" class="col-sm-2 form-control-label">Shop</label>
              <div class="col-sm-4"> 
                                <select name="shop" id="shop" placeholder="Shop" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
				$sql = "SELECT id,name FROM customers where id=$shop";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}}
				$sql = "SELECT id,name FROM customers where type='Shop' ORDER BY name ASC";
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
              <label for="amount" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-2">
                <input type="number" step="0.01" class="form-control" value="<?php echo $amount;?>" name="amount" id="amount"  placeholder="Expense Amount">
              </div>
              <label for="method" class="col-sm-1 form-control-label">Method</label>
              <div class="col-sm-2">
                <select name="method" id="method" placeholder="Method" class="form-control">
				<option value="<?php echo $method;?>"><?php echo ucfirst($method);?></option>
				<option value="Cash">Cash</option>
                                <option value="Card">Card</option>
                                <option value="Cheque">Cheque</option>
                                <option value="ETransfer">eTransfer</option>
				</select>
              </div>

              <label for="date" align="right" class="col-sm-1 form-control-label">Date</label>
              <div class="col-sm-4">
                <input type="text" name="date" id="date" placeholder="Payment Date"  value="<?php echo $date;?>" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <label for="notes" class="col-sm-2 form-control-label">Notes</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" value="<?php echo $notes;?>" name="notes" id="notes" placeholder="Notes">
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