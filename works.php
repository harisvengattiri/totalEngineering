<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Daily Works</h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/work" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button></span>
    </div><br/>
    <div class="box-body">
	<span style="float: left;"></span>
    <span style="float: right;">Search: <input id="filter" type="text" class="form-control form-control-sm input-sm w-auto inline m-r"/></span>
    </div>
    <div>
      <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="10">
        <thead>
          <tr>
              <th data-toggle="true">
                  Code
              </th>
              <th data-toggle="true">
                  Name 
              </th>
              <th data-toggle="true">
                  Work 
              </th>
              <th data-toggle="true">
                  Subdivision 
              </th>
              <th>
                  Main Staff
              </th>
              <th>
                  Date
              </th>
	      <th data-hide="phone,tablet">
                  Status
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
		if (isset($_GET['id']))
		{
                $staff=$_GET['id'];
		$sql = "SELECT * FROM works where staff=$staff";
		}
		else
		{
		$sql = "SELECT * FROM works order by id DESC";
                }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
              <td>DWR<?php echo sprintf("%04d", $row["id"]);?></td>
              <td><?php echo $row["wname"];?></td>
	      <td><?php
              if($row["wtype"]=="maintenance")
              {
              echo "MNT".sprintf("%04d", $row["work"]);
              }
              elseif($row["wtype"]=="project")
              {
              echo "PRJ".sprintf("%04d", $row["work"]);
              }
				$id=$row["work"];
                                $work=$row["wtype"];
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
              <td><?php echo $row["division"];?></td>
              <td><?php
                                $staff=$row["staff"];
                                $subsql2 = "SELECT name FROM customers where id=$staff";
				$subresult2 = mysqli_query($conn, $subsql2);
				if (mysqli_num_rows($subresult2) > 0) 
				{
				while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				{
				echo $subrow2["name"];
				}} 
              ?></td>
              <td><?php echo $row["date"];?></td>
              <td>No Status</td>
              <td><?php echo $row["notes"];?></td>
              <td><a href=""><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a> 
              <a href=""><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a> 
              <a href=""><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a> 
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
