<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$particulars=$_POST['particulars'];
$amount=$_POST['amount'];
$date=$_POST['date'];
$category=$_POST['category'];
$SubCategory=$_POST['subcategory'];
$note=$_POST['note'];

$shop=$_POST['shop'];
$trn=$_POST['trn'];
$vat=$_POST['vat'];
$total=$_POST['total'];

/*$sql = "INSERT INTO `cmp_account` (`particulars`,`amount`,`date`,`cat`,`sub`,`note`) 
VALUES ('$particulars','$amount','$date','$category','$SubCategory','$note')";*/

$sql = "INSERT INTO `cmp_account` (`particulars`,`amount`,`date`,`cat`,`sub`,`note`,`shop`,`trn`,`vat`,`total`) 
VALUES ('$particulars','$amount','$date','$category','$SubCategory','$note','$shop','$trn','$vat','$total')";

if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="AC".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}
?>
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-10">
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
          <h2>Add New Account</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/cmp_account" method="post">
            
           <div class="form-group row">
              <label for="particulars" class="col-sm-2 form-control-label">Particulars</label>
              <div class="col-sm-10">
                <input type="particulars" class="form-control" name="particulars" id="particulars" placeholder="Particulars Name">
              </div>
              </div>



            <div class="form-group row">
              <label for="amount" class="col-sm-2 form-control-label">Date</label>
              <div class="col-sm-4">
                <?php $today = date("d/m/Y") ?>
                   <input type="text" name="date" value="<?php echo $today; ?>" id="date" placeholder="Established Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
               <label for="date" align="right" class="col-sm-2 form-control-label">Shop</label>
              <div class="col-sm-4">
            <input type="text" step="0.01" class="form-control" name="shop" id="shop"  placeholder="Shop">
              </div>
            </div>
            

            <div class="form-group row">
              <label for="amount" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-4">
                <input type="number" step="0.01" class="form-control" name="amount" id="amount"  placeholder="Valued Amount">
              </div>
              <label for="date" align="right" class="col-sm-2 form-control-label">VAT</label>
              <div class="col-sm-4">
            <input type="number" step="0.01" class="form-control" name="vat" id="vat"  placeholder="VAT">
              </div>
            </div>
            
            
              <div class="form-group row">
              <label for="amount" class="col-sm-2 form-control-label">TRN</label>
              <div class="col-sm-4">
                <input type="number" step="0.01" class="form-control" name="trn" id="trn"  placeholder="TRN">
              </div>
              <label for="date" align="right" class="col-sm-2 form-control-label">Total</label>
              <div class="col-sm-4">
            <input type="number" step="0.01" class="form-control" name="total" id="total"  placeholder="Total Amount">
              </div>
            </div>



<div class="form-group row">
<body onload='loadCategories()'>
<label for="category" class="col-sm-2 form-control-label">Category</label>
<div class="col-sm-4">

                                <select name="category" class="form-control" id="catSelect">
				<?php 
				$sql = "SELECT * FROM account_categories";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                ?> <option value="">SELECT</option><?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["tag"]?></option>
				<?php 
				}} 
				?>
				</select>
</div>
<label for="name" align="right" class="col-sm-2 form-control-label">Sub Category</label>
<div class="col-sm-4">
    <select name="subcategory" id="subcatsSelect" class="form-control">
    </select>
</div>
</body>
</div>
            <div class="form-group row">
              <label for="notes" class="col-sm-2 form-control-label">Notes</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="note" id="notes" placeholder="Notes">
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