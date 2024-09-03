<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Vehicles</h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/vehicle" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button></span>
    </div><br/>
    <div class="box-body">
	<span style="float: left;"></span>
    <span style="float: right;">Search: <input id="filter" type="text" class="form-control form-control-sm input-sm w-auto inline m-r"/></span>
    </div>
    <div>
      <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="10">
        <thead>
          <tr>
              <th>
                  Code
              </th>
              <th>
                  Number
              </th>
              <th>
                  Model
              </th>
              <th>
                  Total Expenses
              </th>
              <th>
                  Mulkiya Expiry
              </th>
              <th data-hide="all">
                  Petrol Expenses
              </th>
              <th data-hide="all">
                  Workshop Expenses
              </th>
              <th data-hide="all">
                  Accessories Expenses
              </th>
              <th data-hide="all">
                  Fine Expenses
              </th>
              <th data-hide="all">
                  Other Expenses
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
		$sql = "SELECT * FROM vehicles";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
              <td>VHL<?php echo sprintf("%04d", $row["id"]);?></td>
              <td><?php echo $row["number"];?></td>
              <td><?php echo $row["model"];?></td>
              <td><?php
              $id= $row["id"];
	      $sql5 = "SELECT SUM(amount) as sumall FROM vehicle_expenses where vehicle=$id";
              $result5 = mysqli_query($conn, $sql5);
              if (mysqli_num_rows($result5) > 0) 
		{
              while($row5 = mysqli_fetch_assoc($result5)) {
              echo 0+$row5["sumall"];
                }}
              ?> Dhs</td>
              <td><?php echo $row["mulkaexp"];?></td>
              <td><?php
              $id= $row["id"];
	      $sql5 = "SELECT SUM(amount) as sumall FROM vehicle_expenses where vehicle=$id and purpose='Petrol'";
              $result5 = mysqli_query($conn, $sql5);
              if (mysqli_num_rows($result5) > 0) 
		{
              while($row5 = mysqli_fetch_assoc($result5)) {
              echo 0+$row5["sumall"];
                }}
              ?> Dhs</td>
              <td><?php
              $id= $row["id"];
	      $sql5 = "SELECT SUM(amount) as sumall FROM vehicle_expenses where vehicle=$id and purpose='Workshop'";
              $result5 = mysqli_query($conn, $sql5);
              if (mysqli_num_rows($result5) > 0) 
		{
              while($row5 = mysqli_fetch_assoc($result5)) {
              echo 0+$row5["sumall"];
                }}
              ?> Dhs</td>
              <td><?php
              $id= $row["id"];
	      $sql5 = "SELECT SUM(amount) as sumall FROM vehicle_expenses where vehicle=$id and purpose='Accessories'";
              $result5 = mysqli_query($conn, $sql5);
              if (mysqli_num_rows($result5) > 0) 
		{
              while($row5 = mysqli_fetch_assoc($result5)) {
              echo 0+$row5["sumall"];
                }}
              ?> Dhs</td>
              <td><?php
              $id= $row["id"];
	      $sql5 = "SELECT SUM(amount) as sumall FROM vehicle_expenses where vehicle=$id and purpose='Fine'";
              $result5 = mysqli_query($conn, $sql5);
              if (mysqli_num_rows($result5) > 0) 
		{
              while($row5 = mysqli_fetch_assoc($result5)) {
              echo 0+$row5["sumall"];
                }}
              ?> Dhs</td>
              <td><?php
              $id= $row["id"];
	      $sql5 = "SELECT SUM(amount) as sumall FROM vehicle_expenses where vehicle=$id and purpose='Others'";
              $result5 = mysqli_query($conn, $sql5);
              if (mysqli_num_rows($result5) > 0) 
		{
              while($row5 = mysqli_fetch_assoc($result5)) {
              echo 0+$row5["sumall"];
                }}
              ?> Dhs</td>
              <td><?php echo $row["notes"];?></td>
              <td><a href="<?php echo $baseurl; ?>/vehicle_expenses?vhl=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a> 
              <a href="<?php echo $baseurl; ?>/edit/vehicle?vhl=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a> 
              <a  href="<?php echo $baseurl; ?>/delete/vehicle?vhl=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a> 
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
