<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
{
$customer=$_POST["customer"];
$site=$_POST["site"];
$salesrep=$_POST["salesrep"];
$date1=$_POST["date"];
$lpo=$_POST["lpo"];
$lpo_date1=$_POST["lpo_date"];

$date = date("d/m/Y", strtotime($date1));
$lpo_date = date("d/m/Y", strtotime($lpo_date1));

if($date=='01/01/1970')
{
     $date='';
}
if($lpo_date=='01/01/1970')
{
     $lpo_date='';
}

$order_refrence1=$_POST["order_refrence"];
$order_refrence=sprintf("%06d",$order_refrence1);

$transport=$_POST["transport"];
$sub=$_POST["invoice_subtotal"];
$vat=$_POST["invoice_vat"];
$grand=$_POST["invoice_total"];


$item=$_POST["item"];
if(count(array_unique($item))<count($item))
{
  $status='failed2';
}
else
{
   
    $image = $_FILES["image"]["name"];
    if($image!=NULL)
    {
    $ext = strtolower(pathinfo($image,PATHINFO_EXTENSION));
        $image1 = 'lpo-'.uniqid().'.'.$ext;
        $target_dir1 = "../uploads/lpo/";
        $target_file1 = $target_dir1 . $image1;
        $imageFileType1 = strtolower(pathinfo($target_file1,PATHINFO_EXTENSION));
        $allowlist1 = array("jpg","jpeg","png","pdf");
         if (in_array($imageFileType1, $allowlist1))
         {
           if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file1)) 
           {
           } else { $image1=NULL;}
         }
         $lpo_image=$image1;
    }
$token=$_POST["token"];    

$sql = "INSERT INTO `test_po` (`token`, `customer`, `site`, `salesrep`, `date`, `lpo`, `lpo_date`, `lpo_pdf`, `order_referance`, `sub_total`, `vat`, `grand_total`, `transport`) 
VALUES ('$token', '$customer', '$site', '$salesrep', '$date', '$lpo', '$lpo_date', '$lpo_image', '$order_refrence', '$sub', '$vat', '$grand', '$transport')";
if ($conn->query($sql) === TRUE) {
    $status="success";
    $last_id = $conn->insert_id;
    
    $item=$_POST["item"];
    $comment=$_POST["comment"];
    
    $quantity=$_POST["invoice_product_qty"];
    $unit=$_POST["invoice_product_price"];
    $total=$_POST["invoice_product_sub"];
    
    
     $count=sizeof($item);
    for($i=0;$i<$count;$i++)
    {
    $sql1 = "INSERT INTO `test_po_item` (`item_id`,`o_r`,`item`,`comment`,`quantity`, `unit`, `total`) 
VALUES ('$last_id','$order_refrence','$item[$i]','$comment[$i]', '$quantity[$i]', '$unit[$i]', '$total[$i]')";
     $conn->query($sql1);
    }
       $last_id1 = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="PO".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       
       echo "<script type=\"text/javascript\">".
             "window.location='".$baseurl."/customer_po';". 
             "</script>";
       
} else {
    $status="failed";
}}}}
?> 
<script>
$(document).on("wheel", "input[type=number]", function (e) {
    $(this).blur();
});
</script>
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
          <h2>Generate New SalesOrder</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
            
          <form role="form" action="<?php echo $baseurl;?>/add/customer_po" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-4">
                <select name="customer" id="customer" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                <!--<select name="items" id="staff" placeholder="Staff" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">-->
                <?php 
				$sql = "SELECT name,id FROM customers where type='Company' order by name";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				?><option value=""> </option><?php
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
            <div class="form-group row m-t-md">
              <div align="" class="col-sm-offset-2 col-sm-12">
                <button name="submit1" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search Quotations</button>
              </div>
            </div>
          </form>
        <?php
         if(isset($_POST['submit1'])){
              $customer = $_POST['customer'];
        ?>
          <form role="form" action="<?php echo $baseurl;?>/add/customer_po" method="post">
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Quotations</label>
              <div class="col-sm-4">
                <select name="quotation" id="customer" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                     <!--<select name="items" id="staff" placeholder="Staff" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">-->
                  <?php 
				$sql = "SELECT id FROM qtn_test where customer='$customer'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				?><option value=""> </option><?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>">QTN|<?php echo $row["id"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
            </div>
            <div class="form-group row m-t-md">
              <div align="" class="col-sm-offset-2 col-sm-12">
                <button name="submit2" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Generate</button>
              </div>
            </div>
          </form>
          <?php } ?>
          
          
        <?php
         if(isset($_POST['submit2'])){
              $customer = $_POST['customer'];
        ?>  
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-4">
                <input class="form-control" type="text" name="customer" value="">
              </div>
            </div>
            <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Sales Representative</label>
              <div class="col-sm-4">
                <input class="form-control" type="text" name="rep" value="">
              </div>
            </div>
            <div class="form-group row m-t-md">
              <div align="" class="col-sm-offset-2 col-sm-12">
                <button name="submit2" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>
              </div>
            </div>
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