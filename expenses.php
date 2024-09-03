<?php include "config.php";?>
<?php include "includes/menu.php";?>
<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Expenses</h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/expense" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/expenses" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/expenses?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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
                  Date
              </th>
              <th>
                  Particulars
              </th>
              <th data-hide="all">
                  Category
              </th>
              <th data-hide="all">
                  SubCategory
              </th>
             <!-- <th>
                  Purchaser
              </th>-->
              <th>
                  Supplier
              </th>
            
	        <th data-hide="all">
                  Method
              </th>
	        <th style="text-align: right;">
                  Amount
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
		$sql = "SELECT * FROM expenses where category='$cat' ORDER BY id DESC";
        }
		elseif (isset($_GET['view']))
		{
		$sql = "SELECT * FROM expenses ORDER BY id DESC";
        }
        else
		{
		$sql = "SELECT * FROM expenses ORDER BY id DESC LIMIT 0,100";
        }

        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
            
        $amount = $row["amount"];
            $amount = ($amount != NULL) ? $amount : 0;
        ?>
          <tr>
              <td>EXP<?php echo sprintf("%04d", $row["id"]);?></td>
               <td><?php echo$row["date"];?></td>
              <td><?php echo $row["particulars"];?></td>
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
             <!-- <td><?php
                // $purchaser=$row["purchaser"];
                // $subsql2 = "SELECT name FROM customers where id=$purchaser";
				// $subresult2 = mysqli_query($conn, $subsql2);
				// if (mysqli_num_rows($subresult2) > 0) 
				// {
				// while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				// {
				// echo $subrow2["name"];
				// }} 
              ?></td>-->
              <td><?php
                    $shop=$row["shop"];
                    $subsql3 = "SELECT name FROM customers where id=$shop";
				$subresult3 = mysqli_query($conn, $subsql3);
				if (mysqli_num_rows($subresult3) > 0) 
				{
				while($subrow3 = mysqli_fetch_assoc($subresult3)) 
				{
				echo $subrow3["name"];
				}} 
              ?></td>
             
              <td><?php echo ucfirst($row["method"]);?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $amount);?>Dhs</td>
              <td><?php echo $row["notes"];?></td>
              <td><a href="<?php echo $baseurl;?>/view/expense?exp=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-search-plus"></i></button></a> 
              <a href="<?php echo $baseurl;?>/edit/expense?exp=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a> 
              <?php if($_SESSION['role'] == 'admin') { ?>
              <a href="<?php echo $baseurl;?>/delete/expense?exp=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a> 
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
