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
$date=$_POST["date"];
$lpo_date=$_POST["lpo_date"];
$transport=$_POST["transport"];
$lpo=$_POST["lpo"];
$order_refrence=$_POST["order_refrence"];
$prj=$_POST["prj"];
    
    $image = $_FILES["image"]["name"];
    $pre_lpo_image = $_POST["pre_lpo_pdf"];
    if($image!=NULL)
    {
    $ext = strtolower(pathinfo($image,PATHINFO_EXTENSION));
    $image1 = 'lpo-'.uniqid().'.'.$ext;
    $target_dir1 = "../uploads/lpo/";
    $target_file1 = $target_dir1 . $image1;
    $imageFileType1 = strtolower(pathinfo($target_file1,PATHINFO_EXTENSION));
    $allowlist1 = array("jpg","jpeg","png","pdf");
     if (in_array($imageFileType1, $allowlist1)) {
     $target_file1=$target_file1;
       if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file1)) {
          
        } else {
             $image1=NULL;
        }
         }
      $lpo_image=$image1;
    }
    else{
        $lpo_image = $pre_lpo_image;
    }

$sql = "UPDATE `sales_order` SET `customer` =  '$customer', `site` =  '$site', `salesrep` =  '$salesrep', `date` =  '$date', `lpo` =  '$lpo', `lpo_date` =  '$lpo_date', `lpo_pdf` =  '$lpo_image', `order_referance` =  '$order_refrence', `sub_total` =  '$sub', `vat` =  '$vat', `grand_total` =  '$grand', `transport` =  '$transport' WHERE `id` = $prj";
if ($conn->query($sql) === TRUE) {
    $status="success";
    
    // update delivery note table lpo
    $sqldeli = "UPDATE `delivery_note` SET `lpo` =  '$lpo' WHERE order_referance='$order_refrence'";
    $conn->query($sqldeli);

       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="PO".$prj;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       
        echo "<script type=\"text/javascript\">".
             "window.location='".$baseurl."/customer_po';". 
             "</script>";
} else {
      echo  "<script type=\"text/javascript\">".
            "window.location='customer_po?id=$prj&status=failed';". 
            "</script>";
}

echo "<script type=\"text/javascript\">".
"window.location='customer_po?id=$prj';". 
"</script>";
}}


if ($_GET) 
{
$prj=$_GET["id"];
}
	$sql = "SELECT * FROM sales_order where id=$prj";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {

            $customer=$row["customer"];
              
            $sql_cust = "SELECT name FROM customers where id='$customer'";
			$result_cust = mysqli_query($conn, $sql_cust);
			$row_cust = mysqli_fetch_assoc($result_cust);
			$customer_name = $row_cust['name'];
			
              $site=$row["site"];
               $sqlsite="SELECT p_name from customer_site where id='$site'";
               $querysite=mysqli_query($conn,$sqlsite);
               $fetchsite=mysqli_fetch_array($querysite);
               $site1=$fetchsite['p_name'];
              
              $salesrep=$row["salesrep"];
              $date=$row["date"];
              $lpo=$row["lpo"];
              $lpo_date=$row["lpo_date"];
              $order_referance=$row["order_referance"];
              
              $transport=$row["transport"];
              $lpo_pdf = $row["lpo_pdf"];
        }}

?>
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-10">
         
         <?php $status=$_GET['status'];?>
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
        <?php } else if($status=="failed2") {?>
    	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed! Cannot order twice for same item</span>
    </a></p>
        <?php } ?>
      <div class="box">
        <div class="box-header">
          <h2>Edit Sales Order</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
             
             
          <form role="form" action="<?php echo $baseurl;?>/edit/customer_po?id=<?php echo $prj;?>" method="post" enctype="multipart/form-data">
            <div class="form-group row">
               <input name="prj"  type="text" value="<?php echo $prj;?>" hidden="hidden">
              <label for="name" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" value="<?php echo $customer_name?>" readonly>
                <input type="hidden" name="customer" value="<?php echo $customer?>">
              </div>
              </div>
            <div class="form-group row">
              <label for="type" align="left" class="col-sm-2 form-control-label">Customer Site</label>
              <div class="col-sm-6">
                  <input type="text" class="form-control" value="<?php echo $site1?>" readonly>
                  <input type="hidden" name="site" value="<?php echo $site?>">
              </div>
            </div>
              
               
               <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Sales Representative</label>
              <div class="col-sm-6">
                 <select name="salesrep" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT name,id FROM customers where type='SalesRep'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				if($salesrep==''){?><option value=""></option> <?php}
				while($row = mysqli_fetch_assoc($result)) 
				{
                                     $selected = ($row['id'] == $salesrep) ? "selected='selected'" : '';
				?>
				<option <?php echo $selected;?> value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
                </select>
              </div>
              </div> 
              
             <div class="form-group row">  
              <label for="date" align="left"  class="col-sm-2 form-control-label">Current Date</label>
                <div class="col-sm-4">
                   <input type="text" name="date" value="<?php echo $date;?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <label for="type" class="col-sm-2 form-control-label">LPO No</label>
              <div class="col-sm-6">
                   <input type="text" class="form-control" name="lpo" value="<?php echo $lpo;?>" id="value" placeholder="LPO No">
              </div>
            </div>
            
            <div class="form-group row">
              <label for="date"  class="col-sm-2 form-control-label">LPO Date</label>
              <div class="col-sm-4">
                   <input type="text" name="lpo_date" value="<?php echo $lpo_date;?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <label for="pterms" class="col-sm-2 form-control-label">Choose LPO PDF</label>
              <div class="col-sm-4">
              <input type="file" class="form-control" name="image" accept="image/jpg,image/jpeg,application/pdf">
              <input type="hidden" name="pre_lpo_pdf" value="<?php echo $lpo_pdf;?>">
              </div>
              <label class="col-sm-2 form-control-label">LPO PDF</label>
              <div class="col-sm-4">
                  <?php if(!empty($lpo_pdf)) { ?>
                  <a target="_blank" style="color:green;" href="<?php echo $baseurl;?>/uploads/lpo/<?php echo $lpo_pdf;?>">View</a>&nbsp;
                  <a style="color:red;" href="<?php echo $baseurl;?>/delete/lpo_pdf?id=<?php echo $prj;?>">Delete</a>
                  <?php } else { ?>
                    <a style="color:red;">No PDF for LPO is Available</a>
                  <?php } ?>
              </div>
            </div>
            
            <div class="form-group row">   
              <label for="type" align="left" class="col-sm-2 form-control-label">Order Refrence</label>
              <div class="col-sm-6">
                   <input type="text" class="form-control" name="order_refrence" value="<?php echo $order_referance;?>" id="value" placeholder="Order Refrence" readonly required>
              </div>
             </div>
               
               
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <a href="<?php echo $baseurl;?>/customer_po" class="btn btn-sm btn-outline rounded b-danger text-danger">Cancel</a>
                <button type="reset" class="btn btn-sm btn-outline rounded b-warning text-warning">Clear</button>
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
<?php // $pageissue='issue';?>
<?php include "../includes/footer.php";?>