<?php include "../config.php"; ?>
<?php include "../includes/menu.php"; ?>
<?php error_reporting(0); ?>

<div class="app-body">
  <?php
  $status = "NULL";
  if (isset($_POST['submit'])) {
    if (isset($_SESSION['userid'])) {
      $username = $_SESSION['username'];

      $customer = $_POST["customer"];
      $site = $_POST["site"];
      $qtn = $_POST["qtn"];
      $salesrep = $_POST["salesrep"];
      $date = $_POST["date"];
      $lpo = $_POST["lpo"];
      $lpo_date = $_POST["lpo_date"];

      $order_refrence1 = $_POST["order_refrence"];
      $order_refrence = sprintf("%06d", $order_refrence1);

      $transport = $_POST["transport"];
      $sub = $_POST["invoice_subtotal"];
      $vat = $_POST["invoice_vat"];
      $grand = $_POST["invoice_total"];


      $item = $_POST["item"];
      if (count(array_unique($item)) < count($item)) {
        $status = 'failed2';
      } else {

        $image = $_FILES["image"]["name"];
        if ($image != NULL) {
          $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
          $image1 = 'lpo-' . uniqid() . '.' . $ext;
          $target_dir1 = "../uploads/lpo/";
          $target_file1 = $target_dir1 . $image1;
          $imageFileType1 = strtolower(pathinfo($target_file1, PATHINFO_EXTENSION));
          $allowlist1 = array("jpg", "jpeg", "png", "pdf");
          if (in_array($imageFileType1, $allowlist1)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file1)) {
            } else {
              $image1 = NULL;
            }
          }
          $lpo_image = $image1;
        }

        $token = $_POST["token"];

        $sql = "INSERT INTO `sales_order` (`token`, `customer`, `site`, `salesrep`, `qtn`, `date`, `lpo`, `lpo_date`, `lpo_pdf`, `order_referance`, `sub_total`, `vat`, `grand_total`, `transport`, `prep`) 
VALUES ('$token', '$customer', '$site', '$salesrep', '$qtn', '$date', '$lpo', '$lpo_date', '$lpo_image', '$order_refrence', '$sub', '$vat', '$grand', '$transport', '$username')";
        if ($conn->query($sql) === TRUE) {
          $status = "success";
          $last_id = $conn->insert_id;

          $item = $_POST["item"];
          $comment = $_POST["comment"];

          $quantity = $_POST["invoice_product_qty"];
          $unit = $_POST["invoice_product_price"];
          $total = $_POST["invoice_product_sub"];

          $count = sizeof($item);
          for ($i = 0; $i < $count; $i++) {
            $sql1 = "INSERT INTO `order_item` (`item_id`,`o_r`,`item`,`comment`,`quantity`, `unit`, `total`) 
VALUES ('$last_id','$order_refrence','$item[$i]','$comment[$i]', '$quantity[$i]', '$unit[$i]', '$total[$i]')";
            $conn->query($sql1);
          }

          $sql2 = "UPDATE `quotation` SET `flag`='1' WHERE id='$qtn'";
          $conn->query($sql2);

          $last_id1 = $conn->insert_id;
          $date1 = date("d/m/Y h:i:s a");
          $username = $_SESSION['username'];
          $code = "PO" . $last_id;
          $query = mysqli_real_escape_string($conn, $sql);
          $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
          $result = mysqli_query($conn, $sql);

          echo "<script type=\"text/javascript\">" .
            "window.location='" . $baseurl . "/sales_order_new';" .
            "</script>";
        } else {
          $status = "failed";
        }
      }
    }
  }
  ?>

  <script>
    $(document).on("wheel", "input[type=number]", function(e) {
      $(this).blur();
    });
  </script>

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
        <?php } else if ($status == "failed2") { ?>
          <p><a class="list-group-item b-l-danger">
              <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
              <span class="label rounded label danger pos-rlt m-r-xs">
                <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
              <span class="text-danger">Your Submission was Failed! Cannot order twice for same item</span>
            </a></p>
        <?php } ?>
        <div class="box">
          <div class="box-header">
            <h2>Add New Sales Order</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">


            <form role="form" action="<?php echo $baseurl; ?>/add/new_sales_order_qtn" method="post" enctype="multipart/form-data">
              <div class="form-group row">
                <input type="hidden" name="token" value="<?php echo rand(1000, 9999) . date('Ymdhisa'); ?>">
                <label for="name" class="col-sm-2 form-control-label">Customer</label>
                <div class="col-sm-6">
                  <select name="customer" id="customer" class="form-control select2" Required="Required" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                    <?php

                    $customer = $_POST['customer'];
                    $site = $_POST['site'];
                    $qtn = $_POST['qtn'];

                    if ($_GET) {
                      $qtn = $_GET['qtn'];
                      $customer = $_GET['cst'];
                      $site = $_GET['st'];
                    }

                    $sqlcust = "SELECT name from customers where id='$customer'";
                    $querycust = mysqli_query($conn, $sqlcust);
                    $fetchcust = mysqli_fetch_array($querycust);
                    $cust = $fetchcust['name'];

                    $sqlsite = "SELECT p_name from customer_site where id='$site'";
                    $querysite = mysqli_query($conn, $sqlsite);
                    $fetchsite = mysqli_fetch_array($querysite);
                    $site_name = $fetchsite['p_name'];


                    $sql = "SELECT name,id FROM customers where type='Company' AND status != 'banned' order by name";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                      if ($customer == NULL) {
                    ?> <option value="">Select Customer</option> <?php
                                                                } else {
                                                                  ?> <option value="<?php echo $customer; ?>">[CST-<?php echo $customer;?>]<?php echo $cust; ?></option> <?php }
                                                                                                                                  while ($row = mysqli_fetch_assoc($result)) {
                                                                                                                                    ?>
                        <option value="<?php echo $row["id"]; ?>">[CST-<?php echo $row["id"];?>]<?php echo $row["name"] ?></option>
                    <?php
                                                                                                                                  }
                                                                                                                                }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="type" align="" class="col-sm-2 form-control-label">Customer Site</label>
                <div class="col-sm-6">
                  <select class="form-control" name="site" id="site">

                    <?php if ($qtn != NULL && $site == NULL) { ?>
                      <option value="">Select Site</option>
                      <?php
                      $sql_st = "SELECT * FROM customer_site where customer='$customer'";
                      $query_st = mysqli_query($conn, $sql_st);
                      while ($fetch_st = mysqli_fetch_array($query_st)) {
                      ?>
                        <option value="<?php echo $fetch_st['id']; ?>"><?php echo $fetch_st['p_name']; ?></option>
                    <?php }
                    } ?>



                    <?php if ($site != NULL) { ?>
                      <option value="<?php echo $site; ?>">[SITE-<?php echo $site;?>] <?php echo $site_name; ?></option>
                    <?php } ?>

                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="type" align="" class="col-sm-2 form-control-label">Quotation</label>
                <div class="col-sm-6">
                  <select class="form-control" name="qtn" id="qtn">
                    <?php if ($qtn != NULL) { ?>
                      <option value="<?php echo $qtn; ?>"><?php echo $qtn; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group row m-t-md">
                <div align="left" class="col-sm-offset-2 col-sm-12">
                  <a href="<?php echo $baseurl; ?>/sales_order_new" class="btn btn-sm btn-outline rounded b-danger text-danger">Cancel</a>
                  <button name="submit_first" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Generate</button>
                </div>
              </div>
            </form>

            <?php
            if (isset($_POST['submit_first'])) {
              $customer = $_POST['customer'];
              $site = $_POST['site'];
              $qtn = $_POST['qtn'];
              $token = $_POST['token'];
            ?>

              <form role="form" action="<?php echo $baseurl; ?>/add/new_sales_order_qtn" method="post" enctype="multipart/form-data">
                <input type="hidden" name="customer" value="<?php echo $customer; ?>">
                <input type="hidden" name="site" value="<?php echo $site; ?>">
                <input type="hidden" name="qtn" value="<?php echo $qtn; ?>">
                <input type="hidden" name="token" value="<?php echo $token; ?>">

                <div class="form-group row">
                  <label for="type" class="col-sm-2 form-control-label">Sales Representative</label>
                  <div class="col-sm-6">

                    <!--<select name="salesrep" class="form-control select2-multiple" Required="Required" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">-->
                    <?php
                    // $sql = "SELECT name,id FROM customers where type='Salesrep' order by name";
                    // $result = mysqli_query($conn, $sql);
                    // if (mysqli_num_rows($result) > 0) 
                    // {
                    ?>
                    <!--<option value=""></option>-->
                    <?php
                    // while($row = mysqli_fetch_assoc($result)) 
                    // {
                    ?>
                    <!--<option value="<?php // echo $row["id"]; 
                                        ?>"><?php // echo $row["name"]
                                            ?></option>-->
                    <?php
                    // }} 
                    ?>
                    <!--</select>-->

                    <?php
                    $sql_rep = "SELECT slmn FROM customers where id='$customer'";
                    $result_rep = mysqli_query($conn, $sql_rep);
                    $fetch_rep = mysqli_fetch_array($result_rep);
                    $rep_id = $fetch_rep['slmn'];

                    $sql_rep_name = "SELECT name FROM customers where id='$rep_id'";
                    $result_rep_name = mysqli_query($conn, $sql_rep_name);
                    $fetch_rep_name = mysqli_fetch_array($result_rep_name);
                    $rep_name = $fetch_rep_name['name'];
                    ?>

                    <input type="text" class="form-control" value="<?php echo $rep_name; ?>" required readonly>
                    <input type="hidden" name="salesrep" value="<?php echo $rep_id; ?>">



                  </div>
                </div>
                <div class="form-group row">
                  <label for="date" align="" class="col-sm-2 form-control-label">Order Date</label>
                  <div class="col-sm-6">
                    <?php
                    $today = date('d/m/Y');
                    ?>
                    <input type="text" name="date" value="<?php echo $today; ?>" id="date" placeholder="Production Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                  <label for="type" class="col-sm-2 form-control-label">LPO No</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="lpo" id="value" placeholder="LPO No">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="date" class="col-sm-2 form-control-label">LPO Date</label>
                  <div class="col-sm-6">
                    <?php
                    $today = date('d/m/Y');
                    ?>
                    <input type="text" name="lpo_date" placeholder="LPO Date" value="<?php echo $today; ?>" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                  <label for="pterms" class="col-sm-2 form-control-label">Choose LPO PDF</label>
                  <div class="col-sm-4">
                    <input type="file" class="form-control" name="image" accept="image/jpg,image/jpeg,application/pdf">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-2 form-control-label">Sales Order Ref</label>
                  <div class="col-sm-6">
                    <?php
                    $sql = "SELECT order_referance FROM sales_order ORDER BY order_referance DESC LIMIT 1";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                      $value = 0;
                      while ($row = mysqli_fetch_assoc($result)) {
                        $value = $row["order_referance"] + 1;
                    ?>
                        <input type="" class="form-control" name="order_refrence" value="<?php echo sprintf("%06d", $value); ?>" readonly>
                      <?php
                      }
                    } else {
                      $value = '000001';
                      ?> <input type="" class="form-control" name="order_refrence" value="<?php echo $value; ?>" readonly><?php
                                                                                                                        }
                                                                                                                          ?>
                  </div>
                </div>
                <table class="table table-bordered" id="invoice_table">
                  <thead>
                    <tr>
                      <th width="300">
                        <h6 style="text-align:center;"><b> Item </b></h6>
                      </th>
                      <th>
                        <h6 style="text-align:center;"><b> Comment </b></h6>
                      </th>
                      <th>
                        <h6 style="text-align:center;"><b> Quantity </b></h6>
                        <!--<h4>Qty</h4>-->
                      </th>
                      <th>
                        <h6 style="text-align:center;"><b> Price </b></h6>

                      </th>
                      <th>
                        <h6 style="text-align:center;"><b> Bundle </b></h6>

                      </th>
                      <th>
                        <h6 style="text-align:center;"><b> Sub Total </b></h6>

                      </th>
                    </tr>
                  </thead>
                    <style>
                      .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td,
                      .table > tfoot > tr > th {
                        padding-left: 2px;
                        padding-right: 2px;
                      }
                      .table td, .table th {
                        padding: 1px;
                      }
                      .product-list .form-group {
                          margin-bottom: 1px;
                      }
                    </style>
                  <tbody>
                    <?php
                    $sql_qtn = "SELECT trans FROM `quotation` WHERE id='$qtn'";
                    $query_qtn = mysqli_query($conn, $sql_qtn);
                    $fetch_qtn = mysqli_fetch_array($query_qtn);
                    $trans = $fetch_qtn['trans'];
                    $trans = ($trans != NULL) ? $trans : 0;

                    $sql = "SELECT * FROM quotation_item where quotation_id=$qtn";
                    $query = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($query) > 0) {
                    ?>


                      <?php
                      $count = 0;
                      $subtotal = 0;
                      while ($fetch = mysqli_fetch_array($query)) {
                        $count++;
                        $item = $fetch['item'];
                        // $comment=$fetch['comment'];

                        $sqlcust = "SELECT items,bundle from items where id='$item'";
                        $querycust = mysqli_query($conn, $sqlcust);
                        $fetchcust = mysqli_fetch_array($querycust);
                        $item1 = $fetchcust['items'];
                        $bundle = $fetchcust['bundle'];
                        $bundle = ($bundle != NULL) ? $bundle : 1;

                        $quantity = $fetch['quantity'];
                        $quantity = ($quantity != NULL) ? $quantity : 0;
                        $unit = $fetch['price'];
                        $unit = ($unit != NULL) ? $unit : 0;
                        $total = $fetch['total'];
                        $total = ($total != NULL) ? $total : 0;

                        $bndle = $quantity / $bundle;
                        $bndl = round($bndle, 2);
                      ?>

                        <tr class="product-list">

                          <td style="width:30%;">
                            <div class="form-group form-group-sm  no-margin-bottom">
                              <select type="text" class="form-control form-group-sm item-input invoice_product" id="item" name="item[]">
                                <option value="<?php echo $item; ?>"><?php echo $item1; ?></option>
                                <?php
                                $sql = "SELECT items,id FROM items";
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                  while ($row = mysqli_fetch_assoc($result)) {
                                    $items = $row['items'];
                                    $id = $row['id'];
                                ?>
                                    <option value="<?php echo $id; ?>"><?php echo $items; ?></option>

                                <?php }
                                } ?>
                              </select>
                            </div>
                          </td>

                          <td style="width:15%;">
                            <div class="form-group form-group-sm  no-margin-bottom">
                              <input type="text" class="form-control" name="comment[]" value="<?php // echo $comment;?>">
                            </div>
                          </td>

                          <td style="width:15%;" class="text-right">
                            <div class="form-group form-group-sm no-margin-bottom">
                              <input readonly type="number" min="1" step="any" class="form-control" name="invoice_product_qty[]" value="<?php echo $quantity; ?>">
                            </div>
                          </td>

                          <td style="width:15%;" class="text-right">
                            <div class="form-group form-group-sm no-margin-bottom">
                              <input readonly type="number" min="1" step="any" class="form-control" name="invoice_product_price[]" value="<?php echo $unit; ?>">
                            </div>
                          </td>

                          <td style="width:10%;" class="text-right">
                            <div class="form-group form-group-sm no-margin-bottom">
                              <input readonly type="number" min="1" step="any" class="form-control" name="bundle[]" value="<?php echo $bndl; ?>">
                            </div>
                          </td>

                          <td style="width:15%;" class="text-right">
                            <div class="form-group form-group-sm">
                              <input type="text" class="form-control calculate-sub" name="invoice_product_sub[]" id="invoice_product_sub" value="<?php echo $total; ?>" readonly>
                            </div>
                          </td>

                        </tr>


                    <?php $subtotal=$subtotal+$total; } } ?>
                  </tbody>
                </table>


                <div id="invoice_totals" class="padding-right row text-right">
                  <div class="col-xs-4 no-padding-right">
                  </div>
                </div>

                <?php
                  $vat = ($subtotal+$trans)*0.05;
                  $grand = $subtotal+$vat+$trans;
                ?>


                <div class="row">
                  <div style="float:right;">
                    <div class="row" style="padding: 5px 0;">
                      <label class="col-sm-4 form-control-label"><b> Sub Total :</b></label>
                      <div class="col-sm-8">
                        <input style="width:95%;" class="form-control" type="text" value="<?php echo $subtotal;?>" name="invoice_subtotal" id="invoice_subtotal" readonly>
                      </div>
                    </div>


                    <div class="row">
                      <label class="col-sm-4 form-control-label"><b> Transport :</b></label>
                      <div class="col-sm-8">
                        <input style="width:95%;" class="form-control" type="number" step=".01" name="transport" id="transport" value="<?php echo $trans; ?>" readonly>
                      </div>
                    </div>

                    <div class="row" style="padding: 5px 0;">
                      <label class="col-sm-4 form-control-label"><strong>TAX/VAT :</strong></label>
                      <div class="col-sm-8">
                        <input style="width:95%;" type="text" class="form-control" name="invoice_vat" id="invoice_vat" value="<?php echo $vat;?>" onkeyup="calculateSum()">
                      </div>
                    </div>

                    <div class="row">
                      <label class="col-sm-4 form-control-label"><b> Total:</b></label>
                      <div class="col-sm-8">
                        <input style="width:95%;" class="form-control" type="text" name="invoice_total" id="invoice_total" value="<?php echo $grand;?>" readonly>
                      </div>
                    </div>

                  </div>
                </div>

                <div class="form-group row m-t-md">
                  <div align="right" class="col-sm-offset-2 col-sm-12">
                    <a href="<?php echo $baseurl; ?>/sales_order_new" class="btn btn-sm btn-outline rounded b-danger text-danger">Cancel</a>
                    <button type="reset" class="btn btn-sm btn-outline rounded b-warning text-warning">Clear</button>
                    <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>
                  </div>
                </div>
              </form>
            <?php } ?>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ############ PAGE END-->

</div>
</div>
<!-- / -->
<script>
  function calculateSum() {
      var field1Value = parseFloat(document.getElementById('invoice_subtotal').value) || 0;
      var field2Value = parseFloat(document.getElementById('invoice_vat').value) || 0;
      var field3Value = parseFloat(document.getElementById('transport').value) || 0;
      var sum = field1Value + field2Value + field3Value;
      var formattedSum = sum.toFixed(2);
      document.getElementById('invoice_total').value = formattedSum;
  }
</script>
<?php include "../includes/footer.php"; ?>