<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
$items= mysqli_real_escape_string($conn, $_POST['items']);
$description=$_POST['description'];
$dimension=$_POST['dimension'];
$bundle=$_POST['bundle'];
$unit=$_POST['unit'];
$price=$_POST['price'];
$sql = "INSERT INTO `items` (`items`,`price`,`description`,`dimension`,`bundle`,`unit`) 
VALUES ('$items','$price','$description','$dimension','$bundle','$unit')";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="prj".$last_id;
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
          <h2>Add New Product</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/add/items" method="post">
            
            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Item</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="items" id="value" placeholder="Item">
              </div>
              </div>
              <div class="form-group row">
              <label for="Quantity" align="right" class="col-sm-1 form-control-label">Price</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="price" id="value" placeholder="Price">
              </div>
            </div>
               <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Dimension</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="dimension" id="value" placeholder="Dimension">
              </div>
              <label for="Quantity" align="right" class="col-sm-2 form-control-label">Description</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="description" id="value" placeholder="Description">
              </div>
            </div>
              <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Bundle Size</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="bundle" id="value" placeholder="Bundle Size">
              </div>
              <label for="Quantity" align="right" class="col-sm-1 form-control-label">Unit</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="unit" id="value" placeholder="Unit">
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