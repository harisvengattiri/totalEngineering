<?php include "../config.php"; ?>
<?php include "../includes/menu.php"; ?>
<?php // error_reporting(0); ?>
<div class="app-body">
    <?php
    $status = "NULL";
    if (isset($_POST['submit'])) {
        if (isset($_SESSION['userid'])) {

            $inv = $_POST["inv"];
            $date = $_POST["date"];
            $actualdn = $_POST["actualdn"];

            $k = sizeof($actualdn);
            $transportation = 0;
            for ($i = 0; $i < $k; $i++) {
                $mydn = $actualdn[$i];
                $sqltrans = "SELECT transport from delivery_note where id='$mydn'";
                $querytrans = mysqli_query($conn, $sqltrans);
                $fetchtrans = mysqli_fetch_array($querytrans);
                $trans = $fetchtrans['transport'];
                $trans = ($trans != NULL) ? $trans : 0;
                $transportation = $transportation + $trans;
                $transportation = number_format($transportation, 2, '.', '');
            }

            $subtotal = $_POST["sub"];

            $vat = ($subtotal + $transportation) * 0.05;
            $grand = $subtotal + $vat + $transportation;
            $grand = number_format($grand, 2, '.', '');

            $bank_details = $_POST["bank_details"];
            $token = $_POST["token"];

            date_default_timezone_set('Asia/Dubai');
            $time = date('Y-m-d H:i:s', time());

            $sql = "UPDATE `invoice` SET `token`='$token', `total`='$subtotal', `vat`='$vat', `grand`='$grand', `transport`='$transportation',
                    `bank_details`='$bank_details' WHERE id=$inv ";

            if ($conn->query($sql) === TRUE) {
                $status = "success";

                $sql1 = "SELECT dn FROM invoice_item where invoice_id='$inv'";
                $query1 = mysqli_query($conn,$sql1);
                while($fetch1 = mysqli_fetch_array($query1))
                {
                   $dn = $fetch1['dn'];
                   $sql2 = "UPDATE delivery_note SET invoiced='' where id='$dn'";
                   $conn->query($sql2);    
                }

                // SECTION FOR DELETING FROM INVOICE ITEM TABLE
                $sql3 = "DELETE FROM `invoice_item` WHERE `invoice_id`=$inv";
                $conn->query($sql3); 

                // SECTION FOR DELETING FROM ADDITIONAL TABLES
                $sql5 = "DELETE FROM `additionalAcc` WHERE `entry_id`='$inv' AND `section`='INV'";
                $query5 = mysqli_query($conn,$sql5);
         
                $sql7 = "DELETE FROM `additionalAcc` WHERE `entry_id`='$inv' AND `section`='TRP'";
                $query7 = mysqli_query($conn,$sql7);
                
                $sql6 = "DELETE FROM `additionalRcp` WHERE `entry_id`='$inv' AND `section`='INV'";
                $query6 = mysqli_query($conn,$sql6);


                // SECTION FOR ADDING TO ADDITIONAL TABLES
                $sql_adtnl_inv = "INSERT INTO `additionalRcp`(`id`, `section`, `entry_id`, `invoice`, `invoice_date`, `date`, `cat`, `sub`, `amount`)
                              VALUES ('','INV','$inv','$inv','$date','$date','65','','$grand')";
                $conn->query($sql_adtnl_inv);

                $sql_adtnl_vat = "INSERT INTO `additionalAcc`(`id`, `section`, `entry_id`, `date`, `cat`, `sub`, `amount`)
                              VALUES ('','INV','$inv','$date','27','184','$vat')";
                $conn->query($sql_adtnl_vat);

                if ($transportation != 0) {
                    $sql_adtnl_transport = "INSERT INTO `additionalAcc`(`id`, `section`, `entry_id`, `date`, `cat`, `sub`, `amount`)
                                  VALUES ('','TRP','$inv','$date','4','','$transportation')";
                    $conn->query($sql_adtnl_transport);
                }

                $dn = $_POST["dn"];
                $item = $_POST["item"];
                $reqquantity = $_POST["reqquantity"];
                $pdate = $_POST["pdate"];
                $unit = $_POST["unit"];
                $total = $_POST["total"];
                $n = sizeof($item);
                for ($i = 0; $i < $n; $i++) {
                    $sql8 = "INSERT INTO `invoice_item` (`invoice_id`, `dn`, `item`, `pdate`, `quantity`, `unit`,`total`) 
                    VALUES ('$inv', '$dn[$i]', '$item[$i]', '$pdate[$i]', '$reqquantity[$i]', '$unit[$i]', '$total[$i]')";

                    // var_dump($sql8);
                    // exit();

                    $conn->query($sql8);

                    $sql9 = "UPDATE delivery_note SET invoiced = 'yes' WHERE id='$dn[$i]'";
                    $conn->query($sql9);
                }

                $date1 = date("d/m/Y h:i:s a");
                $username = $_SESSION['username'];
                $code = "INV" . $inv;
                $query = mysqli_real_escape_string($conn, $sql);
                $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                        values ('$date1', 'edit', '$code', '$username', '$query')";
                $result = mysqli_query($conn, $sql);
            } else {
                $status = "failed";
            }
        }
    }

if($_REQUEST) {
    $inv = $_REQUEST['inv'];

    $sql_invoice_details = "SELECT * FROM invoice WHERE id=$inv";
    $query_invoice_details = mysqli_query($conn, $sql_invoice_details);
    $fetch_invoice_details = mysqli_fetch_array($query_invoice_details);

    $customer = $fetch_invoice_details['customer'];
        $sqlcust="SELECT name from customers where id='$customer'";
        $querycust=mysqli_query($conn,$sqlcust);
        $fetchcust=mysqli_fetch_array($querycust);
        $cust=$fetchcust['name'];
    
    $site = $fetch_invoice_details['site'];
        $sqlsite="SELECT p_name from customer_site where id='$site'";
        $querysite=mysqli_query($conn,$sqlsite);
        $fetchsite=mysqli_fetch_array($querysite);
        $site1=$fetchsite['p_name'];

    $date = $fetch_invoice_details['date'];
    $so = $fetch_invoice_details['o_r'];
    
    if (isset($_POST['submit2'])) {
        $deliveries = $_POST['dn'];
        foreach($deliveries as $dn) {
            $deliveryNotes[] = $dn;
        }
    } else {
        $sql_invoice_item_details = "SELECT DISTINCT dn FROM invoice_item WHERE invoice_id=$inv";
        $query_invoice_item_details = mysqli_query($conn, $sql_invoice_item_details);
        while($fetch_invoice_item_details = mysqli_fetch_array($query_invoice_item_details)) {
            $deliveryNotes[] = $fetch_invoice_item_details['dn'];
        }
    }
}

    ?>
    <!-- ############ PAGE START-->
    <div class="padding">
        <div class="row">
            <div class="col-md-9">
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
                        <h2>Edit Invoice</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                        <div class="box-body">
                            <form role="form" action="<?php echo $baseurl; ?>/edit/new_invoice?inv=<?php echo $inv;?>" method="post">
                                <div class="form-group row">
                                    <label for="customer" class="col-sm-3 form-control-label">Customer</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" value="<?php echo $cust; ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="startd" align="left" class="col-sm-3 form-control-label">Customer Site</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" value="<?php echo $site1; ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="date" class="col-sm-3 form-control-label">Current Date</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" name="date" value="<?php echo $date; ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">Sales Order</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" value="<?php echo $so; ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">Select Delivery</label>
                                    <div class="col-sm-6">
                                        <!--<select name="dn[]" multiple id="multiple" class="form-control select2-multiple" multiple data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">-->
                                        <?php
                                            // $sql = "SELECT id FROM delivery_note where order_referance='$so' AND invoiced != 'yes' AND `date` != 0 AND total > 0";
                                            // $result = mysqli_query($conn, $sql);
                                            // if (mysqli_num_rows($result) > 0) {
                                            //     while ($row = mysqli_fetch_assoc($result)) {
                                            //         $id = $row["id"];
                                            //         $selected = in_array($id, $deliveryNotes) ? ' selected' : '';
                                        ?>
                                                <!--<option value="<?php // echo $id;?>"<?php // echo $selected;?>><?php // echo $id; ?></option>-->
                                        <?php // } } ?>
                                        <!--</select>-->
                                        
                                        <select name="dn[]" multiple id="multiple" class="form-control select2-multiple" multiple data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                                            <?php
                                            $sql = "SELECT id FROM delivery_note WHERE order_referance='$so' AND invoiced != 'yes' AND `date` != 0 AND total > 0 AND confirmation=1";
                                            $result = mysqli_query($conn, $sql);
                                            $databaseIds = [];
                                            
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $databaseIds[] = $row["id"];
                                                }
                                            }

                                            $mergedArray = array_unique(array_merge($databaseIds, $deliveryNotes));
                                            
                                            foreach ($mergedArray as $id) {
                                                $selected = in_array($id, $deliveryNotes) ? ' selected' : '';
                                                ?>
                                                <option value="<?php echo $id; ?>"<?php echo $selected; ?>><?php echo $id; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        
                                    </div>
                                </div>

                                <?php
                                if (!isset($_POST['submit2'])) {
                                ?>
                                <div class="form-group row" style="text-align:center;margin-bottom:0px;">
                                    <label for="endd" align="" class="col-sm-2 form-control-label"><b>Delivery</b></label>
                                    <label for="endd" align="" class="col-sm-3 form-control-label"><b>Item</b></label>
                                    <label for="endd" align="" class="col-sm-2 form-control-label"><b>Date</b></label>
                                    <label for="endd" align="" class="col-sm-2 form-control-label"><b>Quantity</b></label>
                                    <label for="endd" align="" class="col-sm-1 form-control-label"><b>Price</b></label>
                                    <label for="endd" align="" class="col-sm-2 form-control-label"><b>Total</b></label>
                                </div>

                                <hr>

                                <?php
                                $noOfDelivery = count($deliveryNotes);
                                $subtotal = 0;
                                for ($i = 0; $i < $noOfDelivery; $i++) {
                                    $dn = $deliveryNotes[$i];
                                ?>
                                <?php
                                    $sql1 = "SELECT * from delivery_item where delivery_id = '$dn' and thisquantity !=0 ";
                                    $result1 = mysqli_query($conn, $sql1);
                                    if (mysqli_num_rows($result1) > 0) {
                                        while ($row1 = mysqli_fetch_assoc($result1)) {

                                            $item1 = $row1['item'];

                                            $sqlitem = "SELECT items from items where id='$item1'";
                                            $queryitem = mysqli_query($conn, $sqlitem);
                                            $fetchitem = mysqli_fetch_array($queryitem);
                                            $item = $fetchitem['items'];

                                            $quantity = $row1['thisquantity'];
                                            $quantity = ($quantity != NULL) ? $quantity : 0;
                                            $unit = $row1['price'];
                                            $unit = ($unit != NULL) ? $unit : 0;
                                            $pdate = $row1['pdate'];
                                            $ddate = $row1['date'];
                                            $total = $quantity * $unit;
                                            $subtotal = $subtotal + $total;
                                    ?>

                                            <div class="form-group row">
                                                <div class="col-sm-2">
                                                    <input type="text" class="form-control" value="<?php echo $dn; ?>" placeholder="" readonly>
                                                </div>
                                                <div class="col-sm-3">
                                                    <input type="text" class="form-control" value="<?php echo $item; ?>" placeholder="Item" readonly>
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="text" class="form-control" value="<?php echo $ddate; ?>" readonly>
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="text" class="form-control" value="<?php echo $quantity; ?>" readonly>
                                                </div>
                                                <div class="col-sm-1">
                                                    <input type="text" class="form-control" value="<?php echo $unit; ?>" readonly>
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="text" class="form-control" value="<?php echo number_format($total, 2, '.', ''); ?>" readonly>
                                                </div>
                                            </div>
                                <?php } } } ?>
                                <?php
                                }
                                ?>

                                <div class="form-group row m-t-md">
                                    <div align="" class="col-sm-offset-2 col-sm-12">
                                        <button name="submit2" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Invoice</button>
                                    </div>
                                </div>

                            </form>

                        <?php if (isset($_POST['submit2'])) {

                            $date = $_POST['date'];

                            $dn1 = $_POST['dn'];
                            $cnt = count($dn1);
                        ?>

                            <form role="form" action="<?php echo $baseurl; ?>/edit/new_invoice?inv=<?php echo $inv;?>" method="post">
                                <input type="hidden" name="token" value="<?php echo rand(1000, 9999) . date('Ymdhisa');?>">
                                <input type="hidden" name="inv" value="<?php echo $inv;?>">
                                <input type="hidden" name="date" value="<?php echo $date;?>">

                                <div class="form-group row" style="text-align:center;margin-bottom:0px;">
                                    <label for="endd" align="" class="col-sm-2 form-control-label"><b>Delivery</b></label>
                                    <label for="endd" align="" class="col-sm-3 form-control-label"><b>Item</b></label>
                                    <label for="endd" align="" class="col-sm-2 form-control-label"><b>Date</b></label>
                                    <label for="endd" align="" class="col-sm-2 form-control-label"><b>Quantity</b></label>
                                    <label for="endd" align="" class="col-sm-1 form-control-label"><b>Price</b></label>
                                    <label for="endd" align="" class="col-sm-2 form-control-label"><b>Total</b></label>
                                </div>

                                <hr>

                                <?php
                                $subtotal = 0;
                                for ($i = 0; $i < $cnt; $i++) {
                                    $dn = $dn1[$i];
                                ?>
                                    <input type="text" name="actualdn[]" value="<?php echo $dn; ?>" hidden="hidden">
                                    <?php
                                    $sql1 = "SELECT * from delivery_item where delivery_id = '$dn' and thisquantity !=0 ";
                                    $result1 = mysqli_query($conn, $sql1);
                                    if (mysqli_num_rows($result1) > 0) {
                                        while ($row1 = mysqli_fetch_assoc($result1)) {

                                            $item1 = $row1['item'];

                                            $sqlitem = "SELECT items from items where id='$item1'";
                                            $queryitem = mysqli_query($conn, $sqlitem);
                                            $fetchitem = mysqli_fetch_array($queryitem);
                                            $item = $fetchitem['items'];

                                            $quantity = $row1['thisquantity'];
                                            $quantity = ($quantity != NULL) ? $quantity : 0;
                                            $unit = $row1['price'];
                                            $unit = ($unit != NULL) ? $unit : 0;
                                            $pdate = $row1['pdate'];
                                            $ddate = $row1['date'];
                                            $total = $quantity * $unit;
                                            $subtotal = $subtotal + $total;
                                    ?>

                                            <div class="form-group row">
                                                <div class="col-sm-2">
                                                    <input type="text" class="form-control" name="dn[]" value="<?php echo $dn; ?>" placeholder="" readonly>
                                                </div>
                                                <div class="col-sm-3">
                                                    <input type="text" class="form-control" value="<?php echo $item; ?>" placeholder="Item" readonly>
                                                    <input name="item[]" value="<?php echo $item1; ?>" hidden="hidden">
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="hidden" name="pdate[]" value="<?php echo $pdate; ?>">
                                                    <input type="text" class="form-control" value="<?php echo $ddate; ?>" id="endd" readonly>
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="text" class="form-control" name="reqquantity[]" value="<?php echo $quantity; ?>" id="endd" readonly>
                                                </div>
                                                <div class="col-sm-1">
                                                    <input type="text" class="form-control" name="unit[]" value="<?php echo $unit; ?>" id="endd" readonly>
                                                </div>
                                                <div class="col-sm-2">
                                                    <input type="text" class="form-control" name="total[]" value="<?php echo number_format($total, 2, '.', ''); ?>" id="endd" readonly>
                                                </div>
                                            </div>
                                <?php } } } ?>

                                <?php
                                $vat = (5 / 100) * $subtotal;
                                $grandtotal = $vat + $subtotal;
                                ?>
                                <hr>
                                <hr>
                                <div class="form-group row" style="text-align:center; margin-bottom:0px;">
                                    <label for="endd" class="col-sm-3 form-control-label"><b></b></label>
                                    <label for="endd" class="col-sm-3 form-control-label"><b>Sub Total</b></label>
                                    <label for="endd" class="col-sm-3 form-control-label"><b>VAT [5%]</b></label>
                                    <label for="endd" class="col-sm-3 form-control-label"><b>Grand Total</b></label>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="sub" value="<?php echo number_format($subtotal, 2, '.', ''); ?>" placeholder="" readonly>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="vat" value="<?php echo number_format($vat, 2, '.', ''); ?>" placeholder="" readonly>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="grand" value="<?php echo number_format($grandtotal, 2, '.', ''); ?>" placeholder="" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="pterms" class="col-sm-2 form-control-label">Bank Details</label>
                                    <div class="col-sm-10">
                                    <textarea name="bank_details" data-ui-jp="summernote">
                                    Bank Details
                                    <ol>
                                        <li>Account Name : Mohammed Al Naseri Concrete Production and Block Factory LLC</li>
                                        <li>Account Number:  1015380029901</li>
                                        <li>IBAN: AE230260001015380029901</li>
                                        <li>Bank Name: ENBD</li>
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
<?php include "../includes/footer.php"; ?>