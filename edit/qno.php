<?php include "../config.php"; ?>
<?php include "../includes/menu.php"; ?>
<?php error_reporting(0); ?>
<div class="app-body">
    <?php
    $status = "NULL";
    if (isset($_POST['submit'])) {
        if (isset($_SESSION['userid'])) {
            $status = $_POST["status"];
            $customer = $_POST["customer"];
            $site = $_POST["site"];
            $salesrep = $_POST["salesrep"];
            $date = $_POST["date"];
            $terms = $_POST["terms"];
            $attention = $_POST["attention"];
            $prj = $_POST["prj"];

            $sql = "UPDATE `quotation` SET `customer` =  '$customer', `site` =  '$site', `salesrep` =  '$salesrep', `date` =  '$date', `attention` =  '$attention', `terms` =  '$terms', `status` =  '$status' WHERE `id` = $prj";
            if ($conn->query($sql) === TRUE) {
                $status = "success";
                $item = $_POST["item"];
                $quantity = $_POST["quantity"];
                $unit = $_POST["unit"];

                $sqlDelTrans = "DELETE FROM `quotation_transportation` WHERE `quotation_id` = '$prj'";
                $conn->query($sqlDelTrans);

                $trans = $_POST["trans"];
                $trans_count = $_POST["trans_count"];
                $transSize = sizeof($trans);
                $totalTransportation = 0;
                for ($t = 0; $t < $transSize; $t++) {
                  $transAmt[$t] = $trans[$t] * $trans_count[$t];
                  $sql_trans = "INSERT INTO `quotation_transportation` (`quotation_id`,`transportation`,`qnt`,`total`) VALUES ('$prj','$trans[$t]','$trans_count[$t]','$transAmt[$t]')";
                  $conn->query($sql_trans);
                  $totalTransportation = $totalTransportation+$transAmt[$t];
                }

                $sql2 = "DELETE FROM quotation_item WHERE `quotation_id` = $prj";
                $conn->query($sql2);
                $count = sizeof($item);
                $sum = 0;
                for ($i = 0; $i < $count; $i++) {
                    $item[$i] = mysqli_real_escape_string($conn, $item[$i]);
                    $quantity[$i] = ($quantity[$i] != NULL) ? $quantity[$i] : 0;
                    $unit[$i] = ($unit[$i] != NULL) ? $unit[$i] : 0;
                    $total[$i] = $quantity[$i] * $unit[$i];
                    $sql1 = "INSERT INTO `quotation_item`(`quotation_id`,`item`, `quantity`, `price`, `total`) 
                    VALUES ('$prj','$item[$i]', '$quantity[$i]', '$unit[$i]', '$total[$i]')";
                    $conn->query($sql1);
                    $sum = $sum + $total[$i];
                }
                $sql3 = "UPDATE `quotation` SET `subtotal`='$sum',`trans`='$totalTransportation' WHERE id='$prj'";
                $conn->query($sql3);

                $date1 = date("d/m/Y h:i:s a");
                $username = $_SESSION['username'];
                $code = "QNO" . $prj;
                $query = mysqli_real_escape_string($conn, $sql);
                $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'edit', '$code', '$username', '$query')";
                $result = mysqli_query($conn, $sql);
            } else {
                $status = "failed";
            }
        }
    }


    if ($_GET) {
        $prj = $_GET["id"];
    }
    $sql = "SELECT * FROM quotation where id=$prj";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {

            $customer = $row["customer"];
            $site = $row["site"];
            $salesrep = $row["salesrep"];
            $date = $row["date"];
            // $lpo=$row["lpo"];
            // $lpo_date=$row["lpo_date"];
            // $order_referance=$row["order_referance"];
            $terms = $row["terms"];
            $attention = $row["attention"];
            $trans = $row["trans"];
            $status = $row["status"];
        }
    }

    if ($status != 'Sales Order') {
        $site_name = $site;
    } else {
        if ($site != NULL) {
            $sql_site = "SELECT p_name FROM customer_site WHERE id=$site";
            $query_site = mysqli_query($conn, $sql_site);
            $fetch_site = mysqli_fetch_array($query_site);
            $site_name = $fetch_site['p_name'];
        } else {
            $site_name = 'No Site';
        }
    }

    ?>
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
                        <h2>Edit Quotation: QTN|<?php echo $prj; ?></h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">


                        <form role="form" action="<?php echo $baseurl; ?>/edit/qno?id=<?php echo $prj; ?>" method="post">

                            <!--<div class="form-group row">-->
                            <!--  <label for="type" class="col-sm-2 form-control-label">Status</label>-->
                            <!--  <div class="col-sm-4">-->
                            <!--     <select name="status" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">-->
                            <!--     <option value="<?php // echo $status;
                                                    ?>"><?php // echo $status;
                                                                                ?></option>-->
                            <!--     <option value="Tender">Tender</option>-->
                            <!--     <option value="Job in Hand">Job in Hand</option>-->
                            <!--     <option value="Sales Order">Sales Order</option>-->
                            <!--    </select>-->
                            <!--  </div>-->
                            <!--</div>-->

                            <div class="form-group row">
                                <label for="type" class="col-sm-2 form-control-label">Status</label>
                                <div class="col-sm-4">
                                    <input class="form-control" name="status" value="<?php echo $status; ?>" readonly>
                                </div>

                                <!--</div>-->
                                <!--<div class="form-group row">-->

                                <input name="prj" type="text" value="<?php echo $prj; ?>" hidden="hidden">
                                <label align="right" for="name" class="col-sm-2 form-control-label">Customer</label>
                                <div class="col-sm-4">
                                    <select name="customer" id="customer" class="form-control">
                                        <?php
                                        $sql = "SELECT name,id FROM customers where type='Company' order by name";
                                        $result = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $selected = ($row['id'] == $customer) ? "selected='selected'" : '';
                                        ?>
                                                <option <?php echo $selected; ?> value="<?php echo $row["id"]; ?>">[CST-<?php echo $row["id"];?>] <?php echo $row["name"];?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <?php if ($status != 'Sales Order') { ?>
                                <div class="form-group row">
                                    <label for="type" class="col-sm-2 form-control-label">Project</label>
                                    <div class="col-sm-4">
                                        <input class="form-control" type="text" name="site" value="<?php echo $site_name; ?>" />
                                    </div>
                                    <!--</div>-->
                                <?php } else { ?>
                                    <div class="form-group row">
                                        <label for="type" class="col-sm-2 form-control-label">Project</label>
                                        <div class="col-sm-4">
                                            <select class="form-control" name="site" id="site">
                                                <option value="<?php echo $site; ?>"><?php echo $site_name; ?></option>
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
                                        // $selected = ($row['id'] == $salesrep) ? "selected='selected'" : '';
                                        ?>
                                        <!--<option <?php // echo $selected;
                                                    ?> value="<?php // echo $row["id"]; 
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
                                        <label for="date" align="left" class="col-sm-2 form-control-label">Quotation Date</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="date" value="<?php echo $date; ?>" id="date" placeholder="Production Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                                            <input class="form-control" type="text" value="<?php echo $attention; ?>" name="attention">
                                            <!--<select class="form-control" name="site" id="site"></select>-->
                                        </div>
                                    </div>

                                    <?php
                                        $sql_trans = "SELECT * FROM `quotation_transportation` WHERE `quotation_id`='$prj'";
                                        $query_trans = mysqli_query($conn, $sql_trans);
                                        $count_trans = 0;
                                        if (mysqli_num_rows($query_trans) > 0) {
                                        while ($fetch_trans = mysqli_fetch_array($query_trans)) {
                                        $count_trans++;
                                    ?>
                                    <div class="form-group row">
                                        <label for="type" class="col-sm-2 form-control-label">Transportation</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" type="number" name="trans[]" value="<?php echo $fetch_trans['transportation'];?>" step="any">
                                        </div>
                                        <div class="col-sm-2">
                                            <input class="form-control" type="number" name="trans_count[]" step="1" min="1" value="<?php echo $fetch_trans['qnt'];?>">
                                        </div>
                                        <?php if($count_trans == 1) { ?>
                                        <div class="box-tools">
                                            <a href="javascript:void(0);" class="btn btn-info btn-sm" id="btnAddMoreTransportation" data-original-title="Add Transportation">
                                            <i class="fa fa-plus"></i>
                                            </a>
                                        </div>
                                        <?php } else { ?>
                                        <div class="box-tools">
                                            <a href="javascript:void(0);" class="btn btn-danger btn-sm" id="btnRemoveTransportation" data-original-title="Remove Transportation">
                                            <i class="fa fa-times"></i>
                                            </a>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <?php } } else { $count_trans++; ?>
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
                                    <?php } ?>
                                    
                                    
                                    <div id="extraTransportation">

                                    </div>


                                    <!--            <div class="form-group row">
              <label for="type" class="col-sm-1 form-control-label">LPO No</label>
              <div class="col-sm-2">
                   <input type="text" class="form-control" name="lpo" value="<?php // echo $lpo;
                                                                                ?>" id="value" placeholder="LPO No">
              </div>
              <label for="date"  class="col-sm-1 form-control-label">LPO Date</label>
              <div class="col-sm-2">
             <?php
                $today = date('d/m/Y');
                ?>
                <input type="text" name="lpo_date" value="<?php // echo $lpo_date;
                                                            ?>" id="date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <label for="type" align="right" class="col-sm-2 form-control-label">Order Refrence</label>
              <div class="col-sm-4">
                   <input type="text" class="form-control" name="order_refrence" value="<?php // echo $order_referance;
                                                                                        ?>" id="value" placeholder="Order Refrence">
              </div>
             </div> -->

                                    <?php
                                    $sql = "SELECT * FROM quotation_item where quotation_id=$prj ORDER BY id";
                                    $query = mysqli_query($conn, $sql);
                                    if (mysqli_num_rows($query) > 0) {
                                        $count = 0;
                                        while ($fetch = mysqli_fetch_array($query)) {
                                            $count++;
                                            $item = $fetch['item'];
                                            $quantity = $fetch['quantity'];
                                            $quantity = ($quantity != NULL) ? $quantity : 0;
                                            $unit = $fetch['price'];
                                            $total = $fetch['total'];

                                            $sqlbndl = "SELECT `bundle`,`weight` FROM items WHERE id='$item'";
                                            $resultbndl = mysqli_query($conn, $sqlbndl);
                                            $rowbndl = mysqli_fetch_assoc($resultbndl);
                                            $bundle = $rowbndl['bundle'];
                                            $bundle = ($bundle != NULL) ? $bundle : 1;
                                            $bdl = $quantity / $bundle;
                                            $bndl = round($bdl, 2);
                                            $itemWeight = $rowbndl['weight'];
                                    ?>

                                            <?php if ($count == 1) { ?>
                                                <div class="form-group row col-md-12" style="margin-bottom:3px;margin-left:-16px;">
                                                    <div class="col-sm-5"></div>

                                                    <div class="box-tools">
                                                        <a href="javascript:void(0);" class="btn btn-info btn-sm" id="btnAddMoreSpecification" data-original-title="Add More">
                                                            <i class="fa fa-plus"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <div class="form-group row col-md-6" style="margin-bottom:3px;">
                                                <label for="name" class="col-sm-1 form-control-label">Item</label>
                                                <div class="col-sm-3">
                                                    <!--<select name="item[]" id="itm<?php echo $count; ?>" class="form-control select2" placeholder="Item" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">-->
                                                    <select name="item[]" id="item_<?php echo $count;?>" class="form-control" placeholder="Item">
                                                        <?php
                                                        $sql = "SELECT items,id FROM items ORDER BY id";
                                                        $result = mysqli_query($conn, $sql);
                                                        if (mysqli_num_rows($result) > 0) {
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                $selected = ($row['id'] == $item) ? "selected='selected'" : '';
                                                        ?>
                                                            <option <?php echo $selected; ?> value="<?php echo $row["id"]; ?>"><?php echo $row["items"] ?></option>
                                                        <?php } } ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="hidden" value="<?php echo $itemWeight;?>" id="itemWeight_<?php echo $count;?>">
                                                    <input type="number" min="1" step="any" class="form-control" value="<?php echo $quantity; ?>" name="quantity[]" id="qnt_<?php echo $count;?>" placeholder="Quantity">
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="number" min="1" step="any" class="form-control" value="<?php echo $unit; ?>" name="unit[]" placeholder="Unit Price">
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="number" min="1" step="any" class="form-control" value="<?php echo $bndl; ?>" name="bundle[]" id="bndl_<?php echo $count;?>" placeholder="Bundle" readonly>
                                                </div>
                                                <!--             <div class="col-sm-2">
                <input type="text" class="form-control" value="<?php echo $total; ?>" name="total[]" id="value" placeholder="Total Price">
              </div>-->
                                                <?php
                                                if ($count == 1) {
                                                ?>
                                                    <!--<div class="box-tools">-->
                                                    <!--     <a href="javascript:void(0);" class="btn btn-info btn-sm" id="btnAddMoreSpecification" data-original-title="Add More">-->
                                                    <!--         <i class="fa fa-plus"></i>-->
                                                    <!--     </a>-->
                                                    <!-- </div>-->

                                                    <div class="box-tools">
                                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm btnRemoveMoreSpecification" data-original-title="Add More">
                                                            <i class="fa fa-times"></i>
                                                        </a>
                                                    </div>
                                                <?php } else {
                                                ?>
                                                    <div class="box-tools">
                                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm btnRemoveMoreSpecification" data-original-title="Add More">
                                                            <i class="fa fa-times"></i>
                                                        </a>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                    <?php }
                                    } ?>

                                    <div id="divSpecificatiion">

                                    </div>

                                    <!-- Developer Hari -->
                                    <div class="row">
                                    <div class="col-md-12">
                                    <hr>
                                        <label for="pterms" class="col-sm-4 form-control-label"><b>Current Goods Weight Will Be</b> </label>
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
                                                <?php echo $terms; ?>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ############ PAGE END-->

</div>
</div>
<!-- / -->

<!--Script to remove row while coming directly from database-->
<script>
    $(document).on('click', '.btnRemoveMoreSpecification', function () {
        $(this).parent('div').parent('div').remove();
    });
</script>

<script>
    $(document).ready(function(event) {

        var currentRow = <?php echo $count;?>

        let quotationItemRow = currentRow+1;
        let totalWeightSum = 0;

        function updateTotalWeight() {
            totalWeightSum = 0;
            $('[id^="item_"]').each(function () {
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
        
        $('[id^="item_"], [id^="qnt_"]').on('change keyup', function() {
        var quotationItemRow = this.id.split('_')[1];
        var item_id = $('#item_'+quotationItemRow).val();
        var qnty_id = $('#qnt_'+quotationItemRow).val();

        if (item_id != "") {
            $.ajax({
                url: '<?php echo $baseurl;?>/add/getbundle4item',
                data: {i_id: item_id,q_id: qnty_id},
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

        $('#btnAddMoreSpecification').click(function() {

            const itemRow = document.createElement('div');
            itemRow.setAttribute('class', 'form-group row col-md-6');
            itemRow.style.marginBottom = '3px';

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

            if (quotationItemRow <= 25) {
                $(itemRow).append(qnoInnerDiv);
                $('#divSpecificatiion').append(itemRow);
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

        var transRow = <?php echo $count_trans;?>;
        let transportationRow = transRow;
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
        transportationRow++;
        });
        $(document).on('click', '#btnRemoveTransportation', function () {
            $(this).parent('div').parent('div').remove();
        });

    });
</script>
<?php include "../includes/footer.php"; ?>