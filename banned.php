<?php include "config.php"; ?>
<?php include "includes/menu.php"; ?>

<div class="app-body">
  <!-- ############ PAGE START-->
  <div class="padding">
    <form role="form" action="<?php echo $baseurl; ?>/status/ban_customer" method="POST">
      <div class="form-group row">
        <label for="customer" align="center" class="col-sm-1 form-control-label">Customer</label>
        <div class="col-sm-5">
          <select name="id" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}" required>
              <option value="">Select Customer</option>
            <?php
            $sql = "SELECT id,name FROM customers WHERE `status` != 'banned' ORDER BY name ASC";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <option value="<?php echo $row["id"]; ?>"><?php echo $row["name"] ?></option>
            <?php
              }
            }
            ?>
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label for="Quantity" align="center" class="col-sm-1 form-control-label">Description</label>
        <div class="col-sm-5">
          <input type="text" class="form-control" name="ban_desc" placeholder="Description" required>
        </div>
        <div class="col-sm-2">
          <button name="submit" type="submit" class="btn btn-fw danger">Ban User</button>
        </div>
      </div>
    </form>


    <div class="box">
      <div class="box-header">
        <span style="float: left;">
          <h2>Banned Customer</h2>
        </span>
        <span style="float: right;">&nbsp;
          <?php
          if (isset($_GET['view'])) {
          ?>
            <a href="<?php echo $baseurl; ?>/banned"><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
          <?php
          } else {
          ?>
            <a href="<?php echo $baseurl; ?>/banned?view=all"><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
          <?php
          } ?>
        </span>
      </div><br />
      <div class="box-body">

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
                ID
              </th>

              <th>
                Name
              </th>
              <th>
                Status
              </th>
              <th>
                Ban Date
              </th>
              <th>
                Description
              </th>
              <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'gm') { ?>
                <th>
                  Actions
                </th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($_GET['view'])) {
              $sql = "SELECT id,name,ban_date,ban_desc FROM customers where status='banned' ORDER BY id DESC";
            } else {
              $sql = "SELECT id,name,ban_date,ban_desc FROM customers where status='banned' ORDER BY id DESC LIMIT 0,100";
            }
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                  <td>CID<?php echo sprintf("%04d", $row["id"]); ?></td>
                  <td><?php echo $row["name"]; ?></td>
                  <td>Banned</td>
                  <td><?php echo $row["ban_date"]; ?></td>
                  <td><?php echo $row["ban_desc"]; ?></td>

                  <td>
                    <a href="<?php echo $baseurl; ?>/edit/customer_ban?id=<?php echo $row["id"];?>" title="Edit"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>
                    <?php if ($_SESSION['role'] == 'admin') { ?>
                     <a href="<?php echo $baseurl; ?>/status/banlift_customer?id=<?php echo $row["id"]; ?>" onclick="return confirm('Are you sure?')"><span style="font-size:15px;" class="label success pos-rlt m-r-xs">Lift Ban</span></a>
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

<?php include "includes/footer.php"; ?>