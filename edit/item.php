<?php include "../includes/menu.php";?>
<?php require_once("../database.php");?>

<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
  if(isset($_SESSION['userid']))
  {
    try {
      editItems($_POST);
      $status = "success";
    } catch (Exception $e) {
      $status = "failed";
    }
  }
}


if(isset($_GET['id']))
{
    $prj=$_GET['id'];

      $sql="SELECT * FROM items where id='$prj'";
      $query=mysqli_query($conn,$sql);
      if(mysqli_num_rows($query) > 0)
      {
          while($result=mysqli_fetch_array($query))
          {
                $item=$result['items'];
                $description=$result['description'];
                $dimension=$result['dimension'];
                $bundle=$result['bundle'];
                $unit=$result['unit'];
                $price=$result['price'];
                $weight=$result['weight'];
                $sqm=$result['sqm'];
          }
      }
}



?>
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-6">
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
          <h2>Edit Product</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo BASE_URL;?>/edit/items?id=<?php echo $prj;?>" method="post">
            <input type="text" name="prj" value="<?php echo $prj;?>" hidden="hidden"> 
            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Item</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="items" value="<?php echo $item;?>" id="value" placeholder="Item">
              </div>
            </div>
            <div class="form-group row">
              <label for="Quantity" align="left" class="col-sm-2 form-control-label">Price</label>
              <div class="col-sm-8">
                <input type="number" min="1" class="form-control" name="price" value="<?php echo $price;?>" id="value" placeholder="Price">
              </div>
            </div>
               <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Dimension</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="dimension" value="<?php echo $dimension;?>" id="value" placeholder="Dimension">
              </div>
               </div>
            <div class="form-group row">
              <label for="Quantity" align="left" class="col-sm-2 form-control-label">Description</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="description" value="<?php echo $description;?>" id="value" placeholder="Description">
              </div>
            </div>
            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Bundle Size</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="bundle" value="<?php echo $bundle;?>" id="value" placeholder="Bundle Size">
              </div>
              <label for="Quantity" align="left" class="col-sm-1 form-control-label">Unit</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="unit" value="<?php echo $unit;?>" id="value" placeholder="Unit">
              </div>   
            </div>
            
            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Weight [Kg]</label>
              <div class="col-sm-4">
                <input type="number" step="any" class="form-control" name="weight" value="<?php echo $weight;?>" placeholder="Weight">
              </div>
            </div>
            
            <div class="form-group row">
              <div class="col-sm-4">
                <input type="hidden" class="form-control" name="sqm" value="<?php echo $sqm;?>" id="value" placeholder="SQM Value">
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