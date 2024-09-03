<?php include "config.php"; ?>
<?php include "includes/menu.php"; ?>
<?php
$status = '';
if($_GET) {
  $status = $_GET['status'];
}

if (isset($_POST['submit'])) {

  $location = $_POST["location"];
  $fair = $_POST["fair"];

  $sql = "INSERT INTO `fair` (`location`,`fair`) 
  VALUES ('$location','$fair')";
  if ($conn->query($sql) === TRUE) {
    $status = "success";
    $last_id = $conn->insert_id;
    $date1 = date("d/m/Y h:i:s a");
    $username = $_SESSION['username'];
    $code = "LOC" . $last_id;
    $query = mysqli_real_escape_string($conn, $sql);
    $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
    $result = mysqli_query($conn, $sql);
  } else {
    $status = "failed";
  }
}
?>

<div class="app-body">
  <!-- ############ PAGE START-->
  <div class="padding">
    <div class="box">
      <div class="box-header">
        <span style="float: left;">
          <h2>Location and Charge</h2>
        </span>
        <span style="float: right;">&nbsp;
          <?php
          if (isset($_GET['view'])) {
          ?>
            <a href="<?php echo $baseurl; ?>/fair"><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
          <?php
          } else {
          ?>
            <a href="<?php echo $baseurl; ?>/fair?view=all"><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
          <?php
          } ?>
        </span>
      </div><br />
      <div class="box-body">
        <form role="form" action="<?php echo $baseurl; ?>/fair" method="post">
          <div class="form-group row">
            <label for="Quantity" class="col-sm-1 form-control-label">Location</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" name="location" id="value" placeholder="Location">
            </div>
            <label for="Quantity" class="col-sm-1 form-control-label">Fair [Tipper]</label>
            <div class="col-sm-3">
              <input type="number" min="1" class="form-control" name="fair" id="value" placeholder="Fair">
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

      <div class="box-body">
        <div class="row">
          <?php if($status=="deleted") { ?>
          <p><a class="list-group-item b-l-success">
                  <span class="pull-right text-success"><i class="fa fa-circle text-xs"></i></span>
                  <span class="label rounded label success pos-rlt m-r-xs">
              <b class="arrow right b-success pull-in"></b><i class="ion-checkmark"></i></span>
              <span class="text-success">Your Delete was Successfull!</span>
            </a></p>
          <?php } else if($status=="failed") { ?>
          <p><a class="list-group-item b-l-danger">
                  <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
                  <span class="label rounded label danger pos-rlt m-r-xs">
              <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
              <span class="text-danger">Your Delete was Failed!</span>
            </a></p>
          <?php } ?>
        </div>
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
                Id
              </th>
              <th>
                Location
              </th>
              <th>
                fair [Tipper]
              </th>
              <th>
                fair [6 Wheel]
              </th>
              <th>
                fair [2XL Trailor]
              </th>
              <th>
                fair [3XL Trailor]
              </th>
              <th>
                Actions
              </th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($_GET['view'])) {
              $sql = "SELECT * FROM fair ORDER BY id DESC";
            } else {
              $sql = "SELECT * FROM fair ORDER BY id DESC LIMIT 0,100";
            }
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
              $sl = 1;
              while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                  <td><?php echo $sl; ?></td>
                  <td><?php echo $row["location"]; ?></td>
                  <td><?php echo $row["fair"]; ?></td>
                  <td><?php echo $row["fair1"]; ?></td>
                  <td><?php echo $row["fair2"]; ?></td>
                  <td><?php echo $row["fair3"]; ?></td>
                  <td>
                    <a href="<?php echo $baseurl; ?>/edit/fair?id=<?php echo $row["id"]; ?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>
                    <?php // if ($_SESSION['role'] == 'admin') { 
                    ?>
                    <a href="<?php echo $baseurl; ?>/delete/fair?id=<?php echo $row["id"]; ?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
                    <?php // } 
                    ?>
                  </td>
                </tr>
            <?php
                $sl++;
              }
            } ?>
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