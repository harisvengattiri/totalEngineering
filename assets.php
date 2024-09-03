<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Assets</h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/asset" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/assets" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/assets?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
<?php 
} ?>
</span>
    </div><br/>
    <div class="box-body">
	<span style="float: left;"></span>
    <span style="float: right;">Search: <input id="filter" type="text" class="form-control form-control-sm input-sm w-auto inline m-r"/></span>
    </div>
    <div>
		<?php
		if (isset($_GET['view']))
		{
		$list_count = 100;
                }
                else
		{
		$list_count = 10;
                }
                ?>
      <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <tr>
              <th data-toggle="true">
                  Code
              </th>
              <th>
                  Particulars
              </th>
              <th>
                  Category
              </th>
              <th data-hide="all">
                  SubCategory
              </th>
              <th>
                  Date
              </th>
	      <th>
                  Value
              </th>
              <th data-hide="all">
                  Notes
              </th>
              <th>
                  Actions
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		if (isset($_GET['cat']))
		{
		$cat=$_GET['cat'];
		$sql = "SELECT * FROM assets where category='$cat' ORDER BY id DESC";
                }
		if (isset($_GET['view']))
		{
		$sql = "SELECT * FROM assets ORDER BY id DESC";
                }
                else
		{
		$sql = "SELECT * FROM assets ORDER BY id DESC LIMIT 0,100";
                }

        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
              <td>AST<?php echo sprintf("%04d", $row["id"]);?></td>
              <td><?php echo $row["name"];?></td>
              <td><?php
                                $category=$row["category"];
                                $subsql2 = "SELECT tag FROM expense_categories where id=$category";
				$subresult2 = mysqli_query($conn, $subsql2);
				if (mysqli_num_rows($subresult2) > 0) 
				{
				while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				{
				echo $subrow2["tag"];
				}} 
              ?></td>
              <td><?php
                                $subcategory=$row["subcategory"];
                                $subsql2 = "SELECT category FROM expense_subcategories where id=$category";
				$subresult2 = mysqli_query($conn, $subsql2);
				if (mysqli_num_rows($subresult2) > 0) 
				{
				while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				{
				echo $subrow2["category"];
				}} 
              ?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $row["amount"];?> Dhs</td>
              <td><?php echo $row["notes"];?></td>
              <td><a href=""><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a> 
              <a href=""><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a> 
              <?php if($_SESSION['role'] == 'admin') { ?>
              <a href="<?php echo $baseurl; ?>/delete/asset/?ast=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a> 
              <?php } ?>
              </td>
          </tr>
		<?php
		}
		}
		?>
        </tbody>
        <tfoot class="hide-if-no-paging">
          <tr>
              <td colspan="5" class="text-center">
                  <ul class="pagination"></ul>
              </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->

<?php include "includes/footer.php";?>
