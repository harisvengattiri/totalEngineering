<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Purchases</h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/purchase" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/purchases" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/purchases?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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
                  Purchased for
              </th>
              <th data-toggle="true">
                  Purchaser
              </th>
              <th>
                  Shop
              </th>
              <th>
                  Date
              </th>
              <th data-hide="all">
                  Invoice
              </th>
              <th data-hide="phone,tablet">
                  Status
              </th>
			  <th data-hide="phone,tablet">
                  Method
              </th>
			  <th data-hide="all">
                  Price
              </th>
			  <th data-hide="all">
                  Tax
              </th>
			  <th data-hide="phone,tablet">
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
		if (isset($_GET['view']))
		{
		$sql = "SELECT * FROM purchases ORDER BY id DESC";
                }
                else
		{
		$sql = "SELECT * FROM purchases ORDER BY id DESC LIMIT 0,100";
                }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
              <td>PRC<?php echo sprintf("%04d", $row["id"]);?></td>
	      <td><?php
              if($row["work"]=="maintenance")
              {
              echo "MNT".sprintf("%04d", $row["forid"]);
              }
              elseif($row["work"]=="project")
              {
              echo "PRJ".sprintf("%04d", $row["forid"]);
              }
				$id=$row["forid"];
                                $work=$row["work"];
                                $work=$work."s";
				$subsql = "SELECT name FROM $work where id=$id";
				$subresult = mysqli_query($conn, $subsql);
				if (mysqli_num_rows($subresult) > 0) 
				{
				while($subrow = mysqli_fetch_assoc($subresult)) 
				{
				echo "<br/>[".$subrow["name"]."]";
				}} 
				?></td>
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
              <td><?php echo substr($row["date"],0,10);?><br/><?php echo substr($row["date"],11,8);?></td>
              <td><?php echo $row["invno"];?></td>
              <td><?php echo ucfirst($row["status"]);?></td>
              <td><?php echo ucfirst($row["method"]);?></td>
              <td><?php echo $row["price"];?> Dhs</td>
              <td><?php echo $row["taxa"];?> Dhs (@ <?php echo $row["taxp"];?>%)</td>
              <td><?php echo $row["amount"];?> Dhs</td>
              <td><?php echo $row["notes"];?></td> 
              <td><a href="<?php echo $baseurl; ?>/edit/purchase?prc=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a> 
              <a href="<?php echo $baseurl; ?>/delete/purchase?prc=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a> 
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
