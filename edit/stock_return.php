<?php include "../config.php"; ?>
<?php include "../includes/menu.php"; ?>
<div class="app-body">
  <?php
  $status = "NULL";
  if (isset($_POST['submit'])) {
    if (isset($_SESSION['userid'])) {

      $customer = $_POST["customer"];
      $dno = $_POST["dno"];
      $or = $_POST["or"];

      $item = $_POST["item"];
      $quantity = $_POST["quantity"];
      $return = $_POST["return"];
      $price = $_POST["price"];
      $batch = $_POST["batch"];

      $today = date("d/m/Y");

      // DELETE OLD ENTRIES FROM STOCK RETURN
      $sql_del = "DELETE FROM `stock_return` WHERE `dn` = '$dno'";
      $conn->query($sql_del);

      $n = sizeof($return);
      for ($i = 0; $i < $n; $i++) {
        if ($return[$i] != NULL) {
          $sql = "INSERT INTO stock_return(customer,dn,o_r,date,item,quantity,returnqnt,price,batch)
          VALUES('$customer', '$dno','$or','$today','$item[$i]', '$quantity[$i]', '$return[$i]', '$price[$i]', '$batch[$i]')";
          if ($conn->query($sql) === TRUE) {
            $status = "success";
          } else {
            $status = "failed";
          }
        }
      }

      $date1 = date("d/m/Y h:i:s a");
      $username = $_SESSION['username'];
      $code = "SRN" . $dno;
      $query = mysqli_real_escape_string($conn, $sql);
      $sql = "INSERT INTO activity_log (time, process, code, user, query) 
            values ('$date1', 'edit', '$code', '$username', '$query')";
      $result = mysqli_query($conn, $sql);

    }
  }


  if ($_GET) {
    $dno = $_GET['id'];

    $sql_srn = "SELECT * FROM stock_return WHERE dn=$dno";
    $query_srn = mysqli_query($conn, $sql_srn);
    $result_srn = mysqli_fetch_array($query_srn);

    $o_r = $result_srn['o_r'];
    $customer = $result_srn['customer'];

    $sqlcust = "SELECT name from customers where id='$customer'";
    $querycust = mysqli_query($conn, $sqlcust);
    $fetchcust = mysqli_fetch_array($querycust);
    $cust = $fetchcust['name'];
  }

  ?>
  <!-- ############ PAGE START-->
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
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
            <h2>Edit Stock Return</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <form role="form" action="<?php echo $baseurl; ?>/edit/stock_return?id=<?php echo $dno; ?>" method="post">
              <div class="form-group row">
                <input type="hidden" name="customer" value="<?php echo $customer; ?>">
                <input type="hidden" name="or" value="<?php echo $o_r; ?>">
                <label for="customer" class="col-sm-2 form-control-label">Customer</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" value="<?php echo $cust; ?>" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="startd" align="left" class="col-sm-2 form-control-label">Delivery Number</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="dno" value="<?php echo $dno; ?>" readonly>
                </div>
              </div>

              <?php
              $sql = "SELECT * FROM stock_return WHERE dn = '$dno'";
              $query = mysqli_query($conn, $sql);
              if ($query->num_rows > 0) {
              ?>
                <div class="form-group row" style="text-align:center;">
                  <label class="col-sm-2 form-control-label"><b>Item</b></label>
                  <label align="" class="col-sm-2 form-control-label"><b>Quantity</b></label>
                  <label align="" class="col-sm-2 form-control-label"><b>Return</b></label>
                  <label align="" class="col-sm-2 form-control-label"><b>Price</b></label>
                  <label align="" class="col-sm-2 form-control-label"><b>Batch No</b></label>
                </div>
                <?php
                while ($fetch = mysqli_fetch_array($query)) {
                  $item = $fetch['item'];

                  $sqlitem = "SELECT items from items where id='$item'";
                  $queryitem = mysqli_query($conn, $sqlitem);
                  $fetchitem = mysqli_fetch_array($queryitem);
                  $item1 = $fetchitem['items'];

                  $quantity = $fetch['quantity'];
                  $returnqnt = $fetch['returnqnt'];
                  $price = $fetch['price'];
                  $batch = $fetch['batch'];
                ?>
                  <div class="form-group row">
                    <div class="col-sm-2">
                      <input type="hidden" name="item[]" value="<?php echo $item; ?>">
                      <input type="text" class="form-control" value="<?php echo $item1; ?>" placeholder="Item" readonly>
                    </div>
                    <div class="col-sm-2">
                      <input type="text" class="form-control" name="quantity[]" value="<?php echo $quantity; ?>" placeholder="Quantity" readonly>
                    </div>
                    <div class="col-sm-2">
                      <input type="number" min="1" class="form-control" name="return[]" value="<?php echo $returnqnt; ?>" placeholder="Quantity Returned">
                    </div>
                    <div class="col-sm-2">
                      <input type="number" min="0" step="0.01" class="form-control" name="price[]" value="<?php echo $price; ?>" placeholder="Price">
                    </div>
                    <div class="col-sm-2">
                      <input type="text" class="form-control" name="batch[]" value="<?php echo $batch; ?>" readonly>
                    </div>

                  </div>
              <?php }
              } ?>

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