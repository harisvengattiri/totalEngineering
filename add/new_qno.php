<?php include "../config.php"; ?>
<?php include "../includes/menu.php"; ?>
<?php error_reporting(0); ?>

<div class="app-body">
  <?php
  $status = "NULL";
  if (isset($_POST['submit'])) {
    if (isset($_SESSION['userid'])) {
      $username = $_SESSION['username'];

      $status_qtn = $_POST["status"];
      $customer = $_POST["customer"];
      $site = $_POST["site"];
      $salesrep = $_POST["salesrep"];
      $date = $_POST["date"];
      $terms = $_POST["terms"];
      $attention = $_POST["attention"];

      $sql = "INSERT INTO `quotation` (`customer`, `site`, `salesrep`, `date`,`attention`,`terms`,`status`,`prep`) 
      VALUES ('$customer', '$site', '$salesrep', '$date','$attention','$terms','$status_qtn','$username')";
      if ($conn->query($sql) === TRUE) {
        $status = "success";
        $last_id = $conn->insert_id;
        $item = $_POST["item"];
        $quantity = $_POST["quantity"];
        $unit = $_POST["unit"];

        $trans = $_POST["trans"];
        $trans_count = $_POST["trans_count"];
        $transSize = sizeof($trans);
        $totalTransportation = 0;
        for ($t = 0; $t < $transSize; $t++) {
          $transAmt[$t] = $trans[$t] * $trans_count[$t];
          $sql_trans = "INSERT INTO `quotation_transportation` (`quotation_id`,`transportation`,`qnt`,`total`) VALUES ('$last_id','$trans[$t]','$trans_count[$t]','$transAmt[$t]')";
          $conn->query($sql_trans);
          $totalTransportation = $totalTransportation+$transAmt[$t];
        }

        $count = sizeof($item);
        $sum = 0;
        for ($i = 0; $i < $count; $i++) {
          $quantity[$i] = ($quantity[$i] != NULL) ? $quantity[$i] : 0;
          $unit[$i] = ($unit[$i] != NULL) ? $unit[$i] : 0;
          $total[$i] = $quantity[$i] * $unit[$i];
          $sql1 = "INSERT INTO `quotation_item` (`quotation_id`,`item`, `quantity`, `price`, `total`) 
                   VALUES ('$last_id','$item[$i]', '$quantity[$i]', '$unit[$i]', '$total[$i]')";
          $conn->query($sql1);
          $sum = $sum + $total[$i];
        }

        $sql2 = "UPDATE `quotation` SET `subtotal`='$sum',`trans`='$totalTransportation' WHERE id='$last_id'";
        $conn->query($sql2);

        $last_id1 = $conn->insert_id;
        $date1 = date("d/m/Y h:i:s a");
        $username = $_SESSION['username'];
        $code = "QNO" . $last_id;
        $query = mysqli_real_escape_string($conn, $sql);
        $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
        $result = mysqli_query($conn, $sql);
      } else {
        $status = "failed";
      }
      // header("Location: goto_quote?id=$last_id");
    }
  }
  ?>
  <script>
    $(document).on("wheel", "input[type=number]", function(e) {
      $(this).blur();
    });
  </script>
  <!-- ############ PAGE START-->
  <style>
    .form-group {
      margin-bottom: 8px;
    }
  </style>

  <div class="padding">
    <div class="row">
      <div class="col-md-10">
        <?php if ($status == "success") { ?>
          <p><a class="list-group-item b-l-success">
              <span class="pull-right text-success"><i class="fa fa-circle text-xs"></i></span>
              <span class="label rounded label success pos-rlt m-r-xs">
                <b class="arrow right b-success pull-in"></b><i class="ion-checkmark"></i></span>
              <span class="text-success">Your Submission was Successfull!</span>
            </a></p>

          <?php
          // if($_SESSION['role'] == 'admin') {
          if ($status_qtn == 'Sales Order') {
          ?>
            <p><a>
                <span style="float: left;margin:20px;">
                  <a href="<?php echo $baseurl; ?>/add/new_sales_order_qtn?qtn=<?php echo $last_id; ?>&cst=<?php echo $customer; ?>&st=<?php echo $site; ?>"><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Generate Sales Order</a></button>&nbsp;
                  <a href="<?php echo $baseurl; ?>/quotation"><button class="btn btn-outline btn-sm rounded b-primary text-danger"><i class="fa fa-times"></i> Cancel</a></button>
                </span>
              </a></p>
          <?php } ?>

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
            <h2>Add New Quotation</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">

            <form role="form" action="<?php echo $baseurl; ?>/add/new_qno" method="post">

              <?php
              if (isset($_POST['submit1'])) {
                $stat = $_POST['status'];
                $cust = $_POST['customer'];
                $sql_cust = "SELECT name FROM customers WHERE id='$cust'";
                $query_cust = mysqli_query($conn, $sql_cust);
                $fetch_cust = mysqli_fetch_array($query_cust);
                $cust_name = $fetch_cust['name'];
              ?>
                <div class="form-group row">
                  <label for="type" class="col-sm-2 form-control-label">Status</label>
                  <div class="col-sm-4">
                    <input class="form-control" type="text" value="<?php echo $stat; ?>" readonly>
                  </div>
                  <!--</div>-->
                  <!--<div class="form-group row">-->
                  <label align="right" for="type" class="col-sm-2 form-control-label">Customer</label>
                  <div class="col-sm-4">
                    <input class="form-control" type="text" value="<?php echo $cust_name; ?>" readonly>
                  </div>
                </div>
              <?php } else { ?>
                <div class="form-group row">
                  <label for="type" class="col-sm-2 form-control-label">Status</label>
                  <div class="col-sm-4">
                    <select name="status" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}" required>
                      <option value="">Select Status</option>
                      <option value="Tender">Tender</option>
                      <option value="Job in Hand">Job in Hand</option>
                      <option value="Sales Order">Sales Order</option>
                    </select>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="name" class="col-sm-2 form-control-label">Customer</label>
                  <div class="col-sm-4">
                    <select name="customer" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                      <?php
                      $sql = "SELECT name,id FROM customers where type='Company' order by name";
                      $result = mysqli_query($conn, $sql);
                      if (mysqli_num_rows($result) > 0) {
                      ?><option value=""> </option><?php
                                      while ($row = mysqli_fetch_assoc($result)) {
                                      ?>
                          <option value="<?php echo $row["id"]; ?>">[CST-<?php echo $row["id"]?>] <?php echo $row["name"] ?></option>
                      <?php
                                      }
                                    }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="form-group row m-t-md">
                  <div align="left" class="col-sm-offset-2 col-sm-12">
                    <button name="submit1" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Generate</button>
                  </div>
                </div>

              <?php } ?>

            </form>

            <?php
            if (isset($_POST['submit1'])) {
              $stat = $_POST['status'];
              $cust = $_POST['customer'];
              $sql_cust_type = "SELECT `cust_type`,`period` FROM `customers` WHERE `id`='$cust'";
              $result_cust_type = $conn->query($sql_cust_type);
              $row_cust_type = $result_cust_type->fetch_assoc();
              $cust_type = $row_cust_type['cust_type'];
              $period = $row_cust_type['period'];
            ?>

              <form role="form" action="<?php echo $baseurl; ?>/add/new_qno" method="post">
                <input type="hidden" name="status" value="<?php echo $stat; ?>">
                <input type="hidden" name="customer" value="<?php echo $cust; ?>">

                <?php if ($stat != 'Sales Order') { ?>
                  <div class="form-group row">
                    <label for="type" class="col-sm-2 form-control-label">Project</label>
                    <div class="col-sm-4">
                      <input class="form-control" type="text" name="site">
                    </div>
                    <!--</div>-->
                  <?php } else { ?>
                    <div class="form-group row">
                      <label for="type" class="col-sm-2 form-control-label">Project</label>
                      <div class="col-sm-4">
                        <select class="form-control" name="site">
                          <?php
                          $sql_site = "SELECT p_name,id FROM customer_site WHERE customer='$cust'";
                          $result_site = $conn->query($sql_site);
                          if ($result_site->num_rows > 0) {
                            while ($row_site = $result_site->fetch_assoc()) {
                          ?>
                              <option value="<?php echo $row_site['id']; ?>"><?php echo $row_site['p_name']; ?></option>
                          <?php }
                          } ?>
                        </select>
                      </div>
                      <!--</div>-->
                    <?php } ?>
                    <!--<div class="form-group row">-->

                    <label align="right" for="type" class="col-sm-2 form-control-label">Sales Representative</label>
                    <div class="col-sm-4">
                      <!--<select name="salesrep" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">-->
                      <?php
                      // $sql = "SELECT id,name FROM customers where type='SalesRep'";
                      // $result = mysqli_query($conn, $sql);
                      // if (mysqli_num_rows($result) > 0) 
                      // {
                      // while($row = mysqli_fetch_assoc($result)) 
                      // {
                      ?>
                      <!--<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"] ?></option>-->
                      <?php
                      // }} 
                      ?>
                      <!--</select>-->

                      <?php
                      $sql_rep = "SELECT slmn FROM customers where id='$cust'";
                      $result_rep = mysqli_query($conn, $sql_rep);
                      $fetch_rep = mysqli_fetch_array($result_rep);
                      $rep_id = $fetch_rep['slmn'];
                      if (!empty($rep_id)) {
                        $sql_rep_name = "SELECT name FROM customers where id='$rep_id'";
                        $result_rep_name = mysqli_query($conn, $sql_rep_name);
                        $fetch_rep_name = mysqli_fetch_array($result_rep_name);
                        $rep_name = $fetch_rep_name['name'];
                      }
                      ?>

                      <input type="text" class="form-control" value="<?php echo $rep_name; ?>" required readonly>
                      <input type="hidden" name="salesrep" value="<?php echo $rep_id; ?>">

                    </div>
                    </div>

                    <div class="form-group row">
                      <label for="date" align="left" class="col-sm-2 form-control-label">Current Date</label>
                      <div class="col-sm-4">
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

                      <!--</div> -->
                      <!--<div class="form-group row">-->

                      <label align="right" for="type" class="col-sm-2 form-control-label">Attention</label>
                      <div class="col-sm-4">
                        <input class="form-control" type="text" name="attention">
                        <!--<select class="form-control" name="site" id="site"></select>-->
                      </div>
                    </div>

                    <div class="form-group row">
                      <label for="type" class="col-sm-2 form-control-label">Transportation</label>
                      <div class="col-sm-3">
                        <input class="form-control" type="number" name="trans[]" step="any" value="0">
                      </div>
                      <div class="col-sm-2">
                        <input class="form-control" type="number" name="trans_count[]" step="1" min="1" value="1">
                      </div>
                      <div class="box-tools">
                        <a href="javascript:void(0);" class="btn btn-info btn-sm" id="btnAddMoreTransportation" data-original-title="Add Transportation">
                          <i class="fa fa-plus"></i>
                        </a>
                      </div>
                    </div>
                    <div id="extraTransportation">

                    </div>

                    <div class="form-group row">
                      <label for="type" class="col-sm-2 form-control-label">Payment Terms</label>
                      <div class="col-sm-4">
                        <input class="form-control" type="text" value="<?php echo $cust_type . ' ' . $period . ' Days'; ?>" readonly>
                      </div>
                    </div>

                    <div class="form-group row col-md-6" style="margin-bottom:3px;">
                      <input type="hidden" id="itemWeight_0">
                      <label for="name" align="right" class="col-sm-1 form-control-label">Item</label>
                      <div class="col-sm-3">
                        <!--<select name="item[]" id="item0" class="form-control select2" placeholder="Item" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}" required>-->
                        <select name="item[]" id="item_0" class="form-control" placeholder="Item" required>
                          <option value="">Select Item</option>
                          <?php
                          $sql = "SELECT items,id FROM items ORDER BY items";
                          $result = mysqli_query($conn, $sql);
                          if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                          ?>
                              <option value="<?php echo $row["id"]; ?>"><?php echo $row["items"] ?></option>
                          <?php
                            }
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-sm-2">
                        <input type="number" min="1" step="any" class="form-control" name="quantity[]" id="qnt_0" placeholder="Quantity">
                      </div>
                      <div class="col-sm-2" style="margin-left: 8px;">
                        <input type="number" min="1" step="any" class="form-control" name="unit[]" placeholder="Unit Price">
                      </div>
                      <div class="col-sm-2">
                        <input type="number" min="1" step="any" class="form-control" name="bundle[]" id="bndl_0" placeholder="Bundle" readonly>
                      </div>
                      <!--              <div class="col-sm-2">
                <input type="text" class="form-control" name="total[]" id="output1" placeholder="Total Price">
              </div>-->
                      <div class="box-tools">
                        <a href="javascript:void(0);" class="btn btn-info btn-sm" id="btnAddMoreSpecification" data-original-title="Add More">
                          <i class="fa fa-plus"></i>
                        </a>
                      </div>
                    </div>

                    <!--<div id="divSpecificatiion">-->

                    <!--</div>-->

                    <div class="col-md-12">
                      <div id="divSpecificatiion1" class="form-group row col-md-6">

                      </div>
                      <div id="divSpecificatiion2" class="form-group row col-md-6" style="margin-top:-45px;">

                      </div>
                    </div>

                    <!-- Developer Hari -->
                    <div class="row">
                      <div class="col-md-12">
                        <hr>
                        <label for="pterms" class="col-sm-4 form-control-label">Current Goods Weight Will Be </label>
                        <div class="col-sm-2">
                          <input style="text-align: center;" type="text" class="col-sm-4 form-control" id="currentWeight" readonly>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <style>
                      hr {
                        display: block;
                        margin-top: 0.5em;
                        margin-bottom: 0.5em;
                        margin-left: auto;
                        margin-right: auto;
                        border-style: inset;
                        border-width: 1px;
                        border-top: 1px solid rgba(14, 4, 4, 0.74);
                      }
                    </style>
                    <!-- Developer Hari -->


                    <div class="form-group row col-md-12">
                      <label for="pterms" class="col-sm-2 form-control-label">Terms & Conditions</label>
                      <div class="col-sm-10">
                        <textarea name="terms" data-ui-jp="summernote">
                Terms &amp; Conditions&nbsp;
                <ol>
                  <li>Price Validity : 5 Days&nbsp;</li>
                  <li>Payment Terms : CDC / PDC Subject to Approval&nbsp;</li>
                  <li>Delivery Terms : Delivery at Site</li>
                  <li>All blocks are DCL APPROVED&nbsp;</li>
                  <li>All blocks are 4 hour fire rated &amp; acoustically tested.</li>
                  <li>The Units price quoted are does not included the VAT or any other types of taxes <br/>imposed by UAE government.</li>
                </ol>
              </textarea>
                      </div>
                    </div>


                    <div class="form-group row m-t-md">
                      <div align="right" class="col-sm-offset-2 col-sm-12">
                        <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
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
  $(document).ready(function(event) {

    let totalWeightSum = 0;

    function updateTotalWeight() {
      totalWeightSum = 0;
      $('[id^="item_"]').each(function() {
        var rowId = this.id.split('_')[1];
        var itemWeight = parseFloat($("#itemWeight_" + rowId).val());
        var quantity = parseFloat($("#qnt_" + rowId).val());
        if (!isNaN(itemWeight) && !isNaN(quantity)) {
          var product = itemWeight * quantity;
          totalWeightSum += product;
        }
      });
      $('#currentWeight').val(totalWeightSum);
    }

    $('#item_0, #qnt_0').on('change keyup', function() {
      var quotationItemRow = this.id.split('_')[1];
      var item_id = $('#item_' + quotationItemRow).val();
      var qnty_id = $('#qnt_' + quotationItemRow).val();

      if (item_id != "") {
        $.ajax({
          url: '<?php echo $baseurl; ?>/add/getbundle4item',
          data: {
            i_id: item_id,
            q_id: qnty_id
          },
          type: 'POST',
          dataType: 'json',
          success: function(response) {
            var bundle = response.bundle;
            var item_weight = response.item_weight;
            $("#bndl_" + quotationItemRow).val(bundle);
            $("#itemWeight_" + quotationItemRow).val(item_weight);
            updateTotalWeight();
          }
        });
      } else {
        $("#bndl_" + quotationItemRow).val('0');
      }

    });

    let quotationItemRow = 1;

    $('#btnAddMoreSpecification').click(function() {

      const itemRow = document.createElement('div');
      itemRow.setAttribute('class', 'form-group row');

      var qnoInnerDiv = `<label for="name" align="right" class="col-sm-1 form-control-label">Item</label>
                <div class="col-sm-3">
                <select name="item[]" id="item_${quotationItemRow}" class="form-control" placeholder="Item" required>
                <option value="">Select Item</option>
                <?php
                $sql = "SELECT items,id FROM items ORDER BY items";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                ?><option value="<?php echo $row["id"]; ?>"><?php echo $row["items"] ?></option>
                <?php }
                } ?></select>
                </div>
                <div class="col-sm-2"><input type="number" min="1" step="any" class="form-control" name="quantity[]" id="qnt_${quotationItemRow}" placeholder="Quantity"><input type="hidden" id="itemWeight_${quotationItemRow}"></div>
                <div class="col-sm-2"><input type="number" min="1" step="any" class="form-control" name="unit[]" placeholder="Unit Price"></div>
                <div class="col-sm-2"><input type="number" min="1" step="any" class="form-control" name="bundle[]" id="bndl_${quotationItemRow}" placeholder="Bundle" readonly></div>
		            <div class="box-tools">
                  <a href="javascript:void(0);"  class="btn btn-danger btn-sm btnRemoveItems" data-original-title="Remove One">
                  <i class="fa fa-times"></i></a>
                </div>`;

      if (quotationItemRow <= 9) {
        $(itemRow).append(qnoInnerDiv);
        $('#divSpecificatiion1').append(itemRow);
      }
      if (quotationItemRow > 9 && quotationItemRow <= 19) {
        $(itemRow).append(qnoInnerDiv);
        $('#divSpecificatiion2').append(itemRow);
      }

      $(itemRow).on('change keyup', '[id^="item_"], [id^="qnt_"]', function() {
        var quotationItemRow = this.id.split('_')[1];
        var item_id = $('#item_' + quotationItemRow).val();
        var qnty_id = $('#qnt_' + quotationItemRow).val();

        if (item_id != "") {
          $.ajax({
            url: '<?php echo $baseurl; ?>/add/getbundle4item',
            data: {
              i_id: item_id,
              q_id: qnty_id
            },
            type: 'POST',
            dataType: 'json',
            success: function(response) {
              var bundle = response.bundle;
              var item_weight = response.item_weight;
              $("#bndl_" + quotationItemRow).val(bundle);
              $("#itemWeight_" + quotationItemRow).val(item_weight);
              updateTotalWeight();
            }
          });
        } else {
          $("#bndl_" + quotationItemRow).val('0');
        }

      });

      quotationItemRow++;
      $(itemRow).on('click', '.btnRemoveItems', function() {
        $(itemRow).remove();
      });
    });

    let transportationRow = 1;
    $('#btnAddMoreTransportation').click(function () {
          var transportationDiv = 
                    `<div class="form-group row">
                      <label for="type" class="col-sm-2 form-control-label">Transportation</label>
                      <div class="col-sm-3">
                        <input class="form-control" type="number" name="trans[]" step="any">
                      </div>
                      <div class="col-sm-2">
                        <input class="form-control" type="number" name="trans_count[]" step="1" min="1" value="1">
                      </div>
                      <div class="box-tools">
                        <a href="javascript:void(0);" class="btn btn-danger btn-sm" id="btnRemoveTransportation" data-original-title="Remove Transportation">
                          <i class="fa fa-times"></i>
                        </a>
                      </div>
                    </div>`;
          if(transportationRow < 3) {
            $('#extraTransportation').append(transportationDiv);
          }

          $(document).on('click', '#btnRemoveTransportation', function () {
            $(this).parent('div').parent('div').remove();
          });

        transportationRow++;
      });

  });
</script>

<?php include "../includes/footer.php"; ?>