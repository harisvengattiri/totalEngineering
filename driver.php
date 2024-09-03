<?php include "config.php";?>
<?php include "includes/menu.php";?>
<?php
if(isset($_POST['submit']))
{
$driver=$_POST["driver"];
$contact=$_POST["contact"];

$sql = "INSERT INTO `driver` (`driver`,`contact`) 
VALUES ('$driver','$contact')";
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

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Vehicle</h2></span> 
    <span style="float: right;">&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/driver" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/driver?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
<?php 
} ?>
</span>
    </div><br/>
    <div class="box-body">
       <form role="form" action="<?php echo $baseurl;?>/driver" method="post">
            <div class="form-group row">
              <label for="Quantity" class="col-sm-1 form-control-label">Driver</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="driver" id="value" placeholder="Driver">
              </div>
              <label for="Quantity" class="col-sm-1 form-control-label">Contact</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="contact" id="value" placeholder="Contact">
              </div>
              <div class="col-sm-2">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="submit"type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>
              </div>
            </div>
            
              
            
          </form>    
         
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
<!--              <th data-toggle="true">
                  Code
              </th>-->
<!--              <th data-toggle="true">
                  Date
              </th>
              <th data-hide="all">
                  Start Date
              </th>
              <th data-hide="all">
                  End Date
              </th>
              <th data-hide="all">
                  Customer
              </th>
              <th data-hide="all">
                  Tags
              </th>-->
              <th>
                 Id
              </th>
              
	      <th>
                  Driver
              </th>
	      <th>
                  Contact
              </th>
<!--	      <th>
                  Vehicle
              </th>-->
<!--              <th data-hide="all">
                  Notes
              </th>-->
              <th>
                  Actions
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		if (isset($_GET['view']))
		{
		$sql = "SELECT * FROM driver ORDER BY id DESC";
                }
                else
		{
		$sql = "SELECT * FROM driver ORDER BY id DESC LIMIT 0,100";
                }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
              <!--<td>MNT<?php echo sprintf("%04d", $row["id"]);?></td>-->
              <td><?php echo $row["id"];?></td>
              <!--<td><?php echo $row["vehicle"];?></td>-->
              <td><?php echo $row["driver"];?></td>
	      <td><?php echo $row["contact"];?></td>
              
              <!--<a href="<?php echo $baseurl; ?>/maintenance_cash_flow?mnt=<?php echo $id; ?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>-->
             <td> <a href="<?php echo $baseurl; ?>/edit/driver?mnt=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>
              <a href="<?php echo $baseurl; ?>/delete/driver?mnt=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
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
