<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Vehicle Expenses</h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/vehicle_expense" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/vehicle_expenses" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/vehicle_expenses?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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
              <th data-toggle="true">
                  Vehicle
              </th>
              <th data-toggle="true">
                  Category
              </th>
              <th>
                  Purchaser
              </th>
              <th>
                  Shop
              </th>
              <th>
                  Date
              </th>
              <th>
                  Amount
              </th>
              <th>
                  Method
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
		if (isset($_GET['vhl']))
		{
                $vhl=$_GET['vhl'];
		$sql = "SELECT * FROM vehicle_expenses where vehicle=$vhl";
		}
		elseif (isset($_GET['view']))
		{
		$sql = "SELECT * FROM vehicle_expenses ORDER BY id DESC";
                }
                else
		{
		$sql = "SELECT * FROM vehicle_expenses ORDER BY id DESC LIMIT 0,100";
                }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
              <td>VXP<?php echo sprintf("%04d", $row["id"]);?></td>
              <td><?php
                                $vehicle=$row["vehicle"];
                                $subsql2 = "SELECT id,number,model FROM vehicles where id=$vehicle";
				$subresult2 = mysqli_query($conn, $subsql2);
				if (mysqli_num_rows($subresult2) > 0) 
				{
				while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				{
				echo "VHL".sprintf("%04d", $subrow2["id"])." [".$subrow2["number"]."]<br/>".$subrow2["model"];
				}} 
              ?></td>
              <td><?php echo $row["purpose"];?></td>
              <td><?php
                                $purchaser=$row["purchaser"];
                                $subsql2 = "SELECT name FROM customers where id=$purchaser";
				$subresult2 = mysqli_query($conn, $subsql2);
				if (mysqli_num_rows($subresult2) > 0) 
				{
				while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				{
				echo $subrow2["name"];
				}} 
              ?></td>
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
              <td><?php echo $row["date"];?></td>
              <td><?php echo $row["amount"];?> Dhs</td>
              <td><?php echo $row["method"];?></td>
              <td><?php echo $row["notes"];?></td>
              <td><a href="<?php echo $baseurl; ?>/edit/vehicle_expense?vxp=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a> 
              <a href="<?php echo $baseurl; ?>/delete/vehicle_expense?vxp=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a> 
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
