<?php include "config.php"; ?>
<?php include "includes/menu.php"; ?>
<?php
if (isset($_POST['submit'])) {
$driver = $_POST["driver"];
$vehicleType = $_POST["vehicleType"];

try {
  $sql = "INSERT INTO `driverType` (`driver`,`vehicleType`) 
  VALUES ('$driver','$vehicleType')";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  if ($stmt->affected_rows > 0) {
      $status = "success";

      $last_id = $conn->insert_id;
      $date1 = date("d/m/Y h:i:s a");
      $username = $_SESSION['username'];
      $code = "DRT" . $last_id;
      $query = mysqli_real_escape_string($conn, $sql);
      $sql = "INSERT INTO activity_log (time, process, code, user, query) 
              values ('$date1', 'add', '$code', '$username', '$query')";
      $result = mysqli_query($conn, $sql);

  } else {
      $status = "failed";
  }
  $stmt->close();
} catch (Exception $e) {
  $status = "failed";
}
}
?>

<div class="app-body">
  <!-- ############ PAGE START-->
  <div class="padding">

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
        <span style="float: left;">
          <h2>Driver Assigned Vehicle Types</h2>
        </span>
        <span style="float: right;">&nbsp;
          <?php
          if (isset($_GET['view'])) {
          ?>
            <a href="<?php echo $baseurl; ?>/driver_type"><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
          <?php
          } else {
          ?>
            <a href="<?php echo $baseurl; ?>/driver_type?view=all"><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
          <?php
          } ?>
        </span>
      </div><br />
      <div class="box-body">
        <form role="form" action="<?php echo $baseurl; ?>/driver_type" method="post">
          <div class="form-group row">
            <label for="Quantity" class="col-sm-1 form-control-label">Driver</label>
            <div class="col-sm-2">
              <select class="form-control" name="driver" required>
                <option value="">Select Driver</option>
                <?php
                  $sql_driver = "SELECT * FROM `customers` WHERE `type`='Driver'";
                  $query_driver = mysqli_query($conn,$sql_driver);
                  while($result_driver = mysqli_fetch_array($query_driver)) {
                ?>
                  <option value="<?php echo $result_driver['id'];?>"><?php echo $result_driver['name'];?></option>
                <?php } ?>
              </select>
            </div>
            <label align="right" for="Quantity" class="col-sm-1 form-control-label">Vehicle Type</label>
            <div class="col-sm-2">
              <select class="form-control" name="vehicleType">
                <option>Tipper</option>
                <option>6 Wheel</option>
                <option>2XL Trailor</option>
                <option>3XL Trailor</option>
              </select>
            </div>
            
            <div class="col-sm-2">
              <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
              <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>
            </div>
          </div>
        </form>

        <span style="float: left;"></span>
        <span style="float: right;">Search: <input id="filter" type="text" class="form-control form-control-sm input-sm w-auto inline m-r" /></span>
      </div>
      <div>
        <?php
        if (isset($_GET['view'])) {
          $list_count = 100;
        } else {
          $list_count = 10;
        }
        ?>
        <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count; ?>">
          <thead>
            <tr>
              <th>
                Sl No
              </th>
              <th>
                Driver
              </th>
              <th>
                Vehicle Type
              </th>
              <th>
                Actions
              </th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($_GET['view'])) {
              $sql = "SELECT * FROM driverType ORDER BY id DESC";
            } else {
              $sql = "SELECT * FROM driverType ORDER BY id DESC LIMIT 0,100";
            }
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
              $sl = 1;
              while ($row = mysqli_fetch_assoc($result)) {

                $driver = $row["driver"];

                $sql_driver = "SELECT `name` FROM `customers` WHERE `id`='$driver'";
                $query_driver = mysqli_query($conn,$sql_driver);
                $result_driver = mysqli_fetch_array($query_driver);
                $driverName = $result_driver['name'];
            ?>
                <tr>
                  <td><?php echo $sl; ?></td>
                  <td><?php echo $driverName; ?></td>
                  <td><?php echo $row["vehicleType"]; ?></td>
                  <td>
                      <a href="<?php echo $baseurl; ?>/delete/driver_type?id=<?php echo $row["id"]; ?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
                  </td>
                </tr>
            <?php $sl++; } } ?>
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

<?php include "includes/footer.php"; ?>