<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
if(isset($_POST['submit']))
{
    if(isset($_SESSION['userid']))
    {
    $id = $_POST["id"];
    $purpose = $_POST["purpose"];
    
    if($purpose == 'main_edit') {
        $main_category = $_POST["main_category"];
        $scode = 'MCAT';
        $sql = "UPDATE `expense_main_categories` SET `tag` = '$main_category' WHERE `id` = '$id'";
    } else if($purpose == 'cat_edit') {
        $uid = $_POST["uid"];
        $main = $_POST["main_category"];
        $category = $_POST["category"];
        // $op_bal_cat = $_POST["op_bal_cat"];
        $cat_type = $_POST["cat_type"];
        $scode = 'CAT';
        $sql = "UPDATE `expense_categories` SET `uid` = '$uid', `tag` = '$category', `type` = '$main', `entry` = '$cat_type' WHERE `id` = '$id'";
    } else if($purpose == 'sub_edit') {
        $uid = $_POST["uid"];
        $category = $_POST["category"];
        $sub_category = $_POST["sub_category"];
        $op_bal = $_POST["op_bal"];
        $scode = 'SCAT';
        
        // Updating all related tables having category and Subcategory
        
        // $sql10 = "UPDATE `pv` SET `category` = '$category' WHERE `subcategory`='$id'";
        // $query10 = mysqli_query($conn,$sql10);
        // $sql11 = "UPDATE `journal` SET `crdt_cat` = '$category' WHERE `crdt_sub`='$id'";
        // $query11 = mysqli_query($conn,$sql11);
        // $sql12 = "UPDATE `journal` SET `debt_cat` = '$category' WHERE `debt_sub`='$id'";
        // $query12 = mysqli_query($conn,$sql12);
        // $sql13 = "UPDATE `debitnote` SET `cat` = '$category' WHERE `sub`='$id'";
        // $query13 = mysqli_query($conn,$sql13);
        
        $sql = "UPDATE `expense_subcategories` SET `uid` = '$uid', `parent` = '$category',`category` = '$sub_category',`op_bal` = '$op_bal' WHERE `id` = '$id'";
    }

    if ($conn->query($sql) === TRUE) {
        $status="success";
          $last_id = $conn->insert_id;
          $date1=date("d/m/Y h:i:s a");
          $username=$_SESSION['username'];
          $code=$scode.$id;
          $query=mysqli_real_escape_string($conn, $sql);
          $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                      values ('$date1', 'edit', '$code', '$username', '$query')";
          $result = mysqli_query($conn, $sql);
    } else {
        $status="failed";
    }
        
    }}

$purpose = $_GET['purpose'];
$id = $id=$_GET['id'];

if($purpose == 'main_edit') {
     $sql="SELECT `tag` FROM `expense_main_categories` WHERE id='$id'";
     $query=mysqli_query($conn,$sql);
     if(mysqli_num_rows($query) > 0)
     {
     while($result=mysqli_fetch_array($query))
     {
          $main_cat = $result['tag'];
     }}
} else if($purpose == 'cat_edit') {
     $sql="SELECT `uid`,`tag`,`type`,`entry` FROM `expense_categories` WHERE id='$id'";
     $query=mysqli_query($conn,$sql);
     if(mysqli_num_rows($query) > 0)
     {
     $result=mysqli_fetch_array($query);
        $uid = $result['uid'];
        $cat = $result['tag'];
        $type = $result['type'];
        $cat_type = $result['entry'];
             $sql_main="SELECT `tag` FROM `expense_main_categories` WHERE id='$type'";
             $query_main=mysqli_query($conn,$sql_main);
             $result_main=mysqli_fetch_array($query_main);
             $main_cat = $result_main['tag'];
     }
} else if($purpose == 'sub_edit') {
     $sql="SELECT `uid`,`category`,`parent`,`op_bal` FROM `expense_subcategories` WHERE id='$id'";
     $query=mysqli_query($conn,$sql);
     if(mysqli_num_rows($query) > 0)
     {
     $result=mysqli_fetch_array($query);
        $uid = $result['uid'];
        $sub_cat = $result['category'];
        $parent = $result['parent'];
        $op_bal = $result['op_bal'];
             $sql_cat="SELECT `tag`,`type` FROM `expense_categories` WHERE id='$parent'";
             $query_cat=mysqli_query($conn,$sql_cat);
             $result_cat=mysqli_fetch_array($query_cat);
             $cat = $result_cat['tag'];
             $type = $result_cat['type'];
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
        <?php if($purpose == 'main_edit') { ?>
            <h2>Edit Main Category</h2>
        <?php } else if ($purpose == 'cat_edit') { ?>
            <h2>Edit Category</h2>
        <?php } else if($purpose == 'sub_edit') { ?>
            <h2>Edit Sub Category</h2>
        <?php } ?>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/edit/settings?purpose=<?php echo $purpose;?>&id=<?php echo $id;?>" method="post">
          <input name="id" value="<?php echo $id;?>" hidden="hidden">
          <input name="purpose" value="<?php echo $purpose;?>" hidden="hidden">
            
            <?php if($purpose == 'main_edit') { ?>
            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Main Category</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="main_category" value="<?php echo $main_cat; ?>" >
              </div>
            </div>
            <?php } else if ($purpose == 'cat_edit') { ?>
            <div class="form-group row">
                <label for="Quantity" class="col-sm-2 form-control-label">Unique Id</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="uid" value="<?php echo $uid; ?>" >
                </div>
            </div>
            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Main Category</label>
              <div class="col-sm-4">
                    <select class="form-control" name="main_category">
                          <option value="<?php echo $type; ?>"><?php echo $main_cat;?></option>
                          <?php
                            $sql1 = "SELECT * FROM `expense_main_categories`";
                            $query1 = mysqli_query($conn,$sql1);
                            while($result1 = mysqli_fetch_assoc($query1)) {
                          ?>
                            <option value="<?php echo $result1['id'];?>"><?php echo $result1['tag'];?></option>
                          <?php } ?>
                      </select>
              </div>
            </div> 
            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Category</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="category" value="<?php echo $cat; ?>" >
              </div>
            </div>
            <!--<div class="form-group row">  -->
            <!--  <label for="Quantity" class="col-sm-2 form-control-label">Opening Balance</label>-->
            <!--  <div class="col-sm-4">-->
            <!--    <input type="text" class="form-control" name="op_bal_cat" value="<?php // echo $op_bal_cat; ?>" placeholder="Opening Balance for 2021">-->
            <!--  </div>-->
            <!--</div>-->
            <div class="form-group row">  
              <label for="Quantity" class="col-sm-2 form-control-label">Category Type</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="cat_type" value="<?php echo $cat_type; ?>" placeholder="Category Type">
              </div>
            </div>
            
            <?php } else if($purpose == 'sub_edit') { ?>
            <div class="form-group row">
                <label for="Quantity" class="col-sm-2 form-control-label">Unique Id</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="uid" value="<?php echo $uid; ?>" >
                </div>
            </div>
            <div class="form-group row">
                <label for="Quantity" class="col-sm-2 form-control-label">Category</label>
                  <div class="col-sm-4">
                      <select class="form-control" name="category">
                          <option value="<?php echo $parent; ?>"><?php echo $cat;?></option>
                          <?php
                            // $sql1 = "SELECT * FROM `expense_categories` WHERE `type`='$type'";
                            $sql1 = "SELECT * FROM `expense_categories`";
                            $query1 = mysqli_query($conn,$sql1);
                            while($result1 = mysqli_fetch_assoc($query1)) {
                          ?>
                            <!--<option value="<?php // echo $result1['id'];?>"><?php // echo $result1['tag'];?></option>-->
                          <?php } ?>
                      </select>
                  </div>
            </div>
            <div class="form-group row">  
              <label for="Quantity" class="col-sm-2 form-control-label">Sub Category</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="sub_category" value="<?php echo $sub_cat; ?>" >
              </div>
            </div>
            <div class="form-group row">  
              <label for="Quantity" class="col-sm-2 form-control-label">Opening Balance</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="op_bal" value="<?php echo $op_bal; ?>" >
              </div>
            </div>
            <?php } ?>
               
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