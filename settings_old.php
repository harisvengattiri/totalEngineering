<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">

<?php
$status="NULL";
if(isset($_GET['purpose']))
{
$purpose=$_GET["purpose"];
$id=$_GET["id"];
if($purpose=="customer_tag_delete")
{
$sql = "DELETE FROM `customer_tags` WHERE `id` = $id";
}

if($purpose=="account_category_delete")
{
$sql = "DELETE FROM `account_categories` WHERE `id` = $id";
}

if($purpose=="account_subcategory_delete")
{
$sql = "DELETE FROM `account_subcategories` WHERE `id` = $id";
}

if ($conn->query($sql) === TRUE) 
{ $status="deleted";
} else {
    $status="failed";
}
}

if(isset($_POST['add_customer_tag']))
{
$customer_tag=$_POST["customer_tag"];
$sql = "INSERT INTO `customer_tags` (`id`, `tag`) 
VALUES ('NULL', '$customer_tag')";
if ($conn->query($sql) === TRUE) {
    $status="success";
} else {
    $status="failed";
}}


elseif(isset($_POST['add_account_category']))
{
$account_category=$_POST["account_category"];
$sql = "INSERT INTO `account_categories` (`id`, `tag`) 
VALUES ('NULL', '$account_category')";
if ($conn->query($sql) === TRUE) {
    $status="success";
} else {
    $status="failed";
}}

elseif(isset($_POST['add_account_subcategory']))
{
$parent=$_POST["parent"];
$category=$_POST["category"];
$sql = "INSERT INTO `account_subcategories` (`id`, `parent`, `category`) 
VALUES ('NULL', '$parent', '$category')";
if ($conn->query($sql) === TRUE) {
    $status="success";
} else {
    $status="failed";
}}
?>



<!-- ############ PAGE START-->
<div class="row-col">
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
	<?php } else if($status=="deleted") { ?>
	<p><a class="list-group-item b-l-warning">
          <span class="pull-right text-warning"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label warning pos-rlt m-r-xs">
		  <b class="arrow right b-warning pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-warning">Your Deletion was Successfull!</span>
    </a></p>
	<?php }?>
  <div class="col-sm-3 col-lg-2 b-r">
    <div class="p-y">
      <div class="nav-active-border left b-primary">
        <ul class="nav nav-sm">
          <li class="nav-item">
            <a class="nav-link block active" href="#" data-toggle="tab" data-target="#profile">Profile</a>
          </li>
          <li class="nav-item">
            <a class="nav-link block" href="#" data-toggle="tab" data-target="#expense-categories">Accounts MainCat</a>
          </li>
          <li class="nav-item">
            <a class="nav-link block" href="#" data-toggle="tab" data-target="#expense-subcategories">Accounts SubCat.</a>
          </li>
          <li class="nav-item">
            <a class="nav-link block" href="#" data-toggle="tab" data-target="#customer-tags">Customer Tags</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="col-sm-9 col-lg-10 light bg">
    <div class="tab-content pos-rlt">

      





      








      <div class="tab-pane" id="expense-subcategories">
        <div class="p-a-md b-b _600">Accounts Sub Categories</div>
        <div class="p-a-md col-md-12"  style="display:inline-block; line-height:45px; vertical-align: top" >
        <?php
                $sql = "SELECT * from account_subcategories";
                $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
                
		while($row = mysqli_fetch_assoc($result)) 
		{
                $category=$row['category'];
                $parent=$row['parent'];
                $id=$row['id'];
                                $sql2 = "SELECT tag FROM account_categories where id=$parent";
				$result2 = mysqli_query($conn, $sql2);
				if (mysqli_num_rows($result2) > 0) 
				{
				while($row2 = mysqli_fetch_assoc($result2)) 
				{
                                $parent_name=$row2['tag'];
				}}  
        ?>
              <button class="btn btn-outline b-danger text-danger"><?php echo $category." [#".$parent_name."]"; ?> 
              <a href="<?php echo $baseurl; ?>/settings?purpose=account_subcategory_delete&id=<?php echo $id; ?>" onclick="return confirm('Are you sure?')">
              <i class="fa fa-close"></i></a></button>&nbsp;
        <?php
                }
		}
	?>
        </div>
        <form role="form" action="<?php echo $baseurl;?>/settings" method="post">
        <div class="col-sm-4"> 
<select name="parent" id="parent" placeholder="Parent" class="form-control select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
				$sql = "SELECT * FROM account_categories";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["tag"]?></option>
				<?php 
				}} 
				?>
				</select>
        </div>
        <div class="col-sm-4">
        <input type="text" class="form-control" name="category" id="category" placeholder="Enter New Account Sub Category">
        </div>
        <div class="col-sm-2">
                <button name="add_account_subcategory" type="submit" class="btn btn-outline rounded b-success text-success">Add</button>
        </div> 
        </form>
      </div>



      <div class="tab-pane" id="customer-tags">
        <div class="p-a-md b-b _600">Customer Tags</div>
        <div class="p-a-md col-md-12"  style="display:inline-block; line-height:45px; vertical-align: top" >
        <?php
                $sql = "SELECT * from customer_tags";
                $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
                
		while($row = mysqli_fetch_assoc($result)) 
		{
        ?>
              <button class="btn btn-outline b-danger text-danger"><?php echo $row['tag']; ?> 
              <a href="<?php echo $baseurl; ?>/settings?purpose=customer_tag_delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">
              <i class="fa fa-close"></i></a></button>&nbsp;
        <?php
                }
		}
	?>
        </div>
        <form role="form" action="<?php echo $baseurl;?>/settings" method="post">
        <div class="col-sm-4">
        <input type="text" class="form-control" name="customer_tag" id="customer_tag" placeholder="Enter New Customer Tag">
        </div>
        <div class="col-sm-3">
                <button name="add_customer_tag" type="submit" class="btn btn-outline rounded b-success text-success">Add</button>
        </div>
        </form>
      </div>

<div class="tab-pane" id="expense-categories">
        <div class="p-a-md b-b _600">Account Categories</div>
        <div class="p-a-md col-md-12"  style="display:inline-block; line-height:45px; vertical-align: top" >
        <?php
                $sql = "SELECT * from account_categories";
                $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
                
		while($row = mysqli_fetch_assoc($result)) 
		{
        ?>
              <button class="btn btn-outline b-danger text-danger"><?php echo $row['tag']; ?> 
              <a href="<?php echo $baseurl; ?>/settings?purpose=account_category_delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">
              <i class="fa fa-close"></i></a></button>&nbsp;
        <?php
                }
		}
	?>
        </div>
        <form role="form" action="<?php echo $baseurl;?>/settings" method="post">
        <div class="col-sm-4">
        <input type="text" class="form-control" name="account_category" id="expense_category" placeholder="Enter New Account Category">
        </div>
        <div class="col-sm-3">
                <button name="add_account_category" type="submit" class="btn btn-outline rounded b-success text-success">Add</button>
        </div>
        </form>
      </div>





      <div class="tab-pane active" id="profile">
        <div class="p-a-md b-b _600">Public profile</div>
        <form role="form" class="p-a-md col-md-6">
          <div class="form-group">
            <label>Profile picture</label>
            <div class="form-file">
              <input type="file">
              <button class="btn white">Upload new picture</button>
            </div>
          </div>
          <div class="form-group">
            <label>First Name</label>
            <input type="text" class="form-control">
          </div>
          <div class="form-group">
            <label>Last Name</label>
            <input type="text" class="form-control">
          </div>
          <div class="form-group">
            <label>URL</label>
            <input type="text" class="form-control">
          </div>
          <div class="form-group">
            <label>Company</label>
            <input type="text" class="form-control">
          </div>
          <div class="form-group">
            <label>Location</label>
            <input type="text" class="form-control">
          </div>
          <div class="checkbox">
            <label class="ui-check">
              <input type="checkbox"><i class="dark-white"></i> Available for hire
            </label>
          </div>
          <button type="submit" class="btn btn-info m-t">Update</button>
        </form>
      </div>



    </div>
  </div>
</div>


<!-- .modal -->
<div id="modal" class="modal fade animate black-overlay" data-backdrop="false">
  <div class="row-col h-v">
    <div class="row-cell v-m">
      <div class="modal-dialog modal-sm">
        <div class="modal-content flip-y">
          <div class="modal-body text-center">          
            <p class="p-y m-t"><i class="fa fa-remove text-warning fa-3x"></i></p>
            <p>Are you sure to delete your account?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn white p-x-md" data-dismiss="modal">No</button>
            <button type="button" class="btn btn-danger p-x-md" data-dismiss="modal">Yes</button>
          </div>
        </div><!-- /.modal-content -->
      </div>
    </div>
  </div>
</div>
<!-- / .modal -->

<!-- ############ PAGE END-->


    </div>
  </div>
  <!-- / -->

<?php include "includes/footer.php";?>
