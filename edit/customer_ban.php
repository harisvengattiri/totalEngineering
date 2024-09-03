<?php include "../config.php"; ?>
<?php include "../includes/menu.php"; ?>
<div class="app-body">
  <?php
  $status = "NULL";
  if (isset($_POST['submit'])) {
    if (isset($_SESSION['userid'])) {

      $ban_date = $_POST["ban_date"];
      $ban_desc = $_POST["ban_desc"];
      $id = $_POST["id"];

      $sql = "UPDATE `customers` SET `ban_date` = '$ban_date', `ban_desc` = '$ban_desc' WHERE `id` = '$id'";
      if ($conn->query($sql) === TRUE) {
        $status = "success";
        $date1 = date("d/m/Y h:i:s a");
        $username = $_SESSION['username'];
        $code = "CID" . $id;
        $query = mysqli_real_escape_string($conn, $sql);
        $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'edit', '$code', '$username', '$query')";
        $result = mysqli_query($conn, $sql);
      } else {
        $status = "failed";
      }
    }
  }


  if ($_GET) {
    $id = $_GET["id"];
  }
  $sql = "SELECT * FROM `customers` WHERE `id` = $id";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {

      $cust_name = $row["name"];
      $ban_date = $row["ban_date"];
      $ban_desc = $row["ban_desc"];
    }
  }



  ?>
  <!-- ############ PAGE START-->
  <div class="padding">
    <div class="row">
      <div class="col-md-8">
        <?php if ($status == "success") { ?>
          <p><a class="list-group-item b-l-success">
              <span class="pull-right text-success"><i class="fa fa-circle text-xs"></i></span>
              <span class="label rounded label success pos-rlt m-r-xs">
                <b class="arrow right b-success pull-in"></b><i class="ion-checkmark"></i></span>
              <span class="text-success">Your Submission was Successfull!</span>
            </a></p>
        <?php } else if ($status == "failed") { ?>
          <p><a class="list-group-item b-l-danger">
              <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
              <span class="label rounded label danger pos-rlt m-r-xs">
                <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
              <span class="text-danger">Your Submission was Failed!</span>
            </a></p>
        <?php } ?>
        <div class="box">
          <div class="box-header">
            <h2>Edit Banned Customer</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <form role="form" action="<?php echo $baseurl; ?>/edit/customer_ban?id=<?php echo $id; ?>" method="post">
              <input name="id" type="text" value="<?php echo $id; ?>" hidden="hidden">

              <div class="form-group row">
                <label for="Quantity" class="col-sm-2 form-control-label">Customer</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" value="<?php echo $cust_name; ?>" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="Quantity" class="col-sm-2 form-control-label">Ban Date</label>
                <div class="col-sm-8">
                  <input type="text" name="ban_date" value="<?php echo $ban_date; ?>" placeholder="Ban Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
                    format: 'DD/MM/YYYY',
                    icons: {
                      time: 'fa fa-clock-o',
                      date: 'fa fa-calendar',
                      up: 'fa fa-chevron-up',
                      down: 'fa fa-chevron-down',
                      previous: 'fa fa-chevron-left',
                      next: 'fa fa-chevron-right',
                      today: 'fa fa-screenshot',
                      clear: 'fa fa-trash',
                      close: 'fa fa-remove'
                    }
                  }">
                </div>
              </div>
              <div class="form-group row">
                <label for="Quantity" class="col-sm-2 form-control-label">Description</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" name="ban_desc" value="<?php echo $ban_desc; ?>" placeholder="Description">
                </div>
              </div>

              <div class="form-group row m-t-md">
                <div align="right" class="col-sm-offset-2 col-sm-12">
                  <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                  <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>
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

<?php include "../includes/footer.php"; ?>