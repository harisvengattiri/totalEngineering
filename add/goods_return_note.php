<?php include "../includes/menu.php"; ?>
<?php require_once "../database.php"; ?>

<div class="app-body">
  <div class="padding">
    <div class="row">
      <div class="col-md-10">
      <?php 
            $status = getStatusFromUrl();
            if($status) {
                displaySubmissionStatus($status);
            }
        ?>
        <div class="box">
          <div class="box-header">
            <h2>Add New Goods Return Note</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
<style>
  .form-group {
    margin-bottom: 8px;
  }
</style>
          <?php if (!isset($_POST['submit_orders'])) { ?>
            <form role="form" action="<?php echo BASEURL; ?>/add/goods_return_note" method="post">
                <div class="form-group row">
                  <label for="name" class="col-sm-2 form-control-label">Customer</label>
                  <div class="col-sm-5">
                    <select name="customer" id="customer4retuns" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                      <?php
                      $sql = "SELECT name,id FROM customers where type='Company' order by name";
                      $result = mysqli_query($conn, $sql);
                      if (mysqli_num_rows($result) > 0) {
                      ?><option value=""> </option><?php
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                          <option value="<?php echo $row["id"]; ?>">[CST-<?php echo $row["id"]?>] <?php echo $row["name"] ?></option>
                      <?php } } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                    <label for="type" align="" class="col-sm-2 form-control-label">Delivery Notes</label>
                    <div class="col-sm-5">
                        <select name="dn" id="goodReturns" class="form-control" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}"></select>
                    </div>
                </div>
                  <div class="form-group row m-t-md">
                    <div align="center" class="col-sm-offset-2 col-sm-12">
                      <button name="submit_orders" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Check for Returns</button>
                    </div>
                  </div>
            </form>
            <?php
            } if (isset($_POST['submit_orders'])) {
              $customer = $_POST['customer'];
              $customer_name = getContactNameFromId($customer);
              $dn = $_POST['dn'];
                $delivery_details = getDeliveryDetails($dn);
            ?>
            <form role="form" action="<?php echo BASEURL; ?>/controller" method="post">
                <input type="hidden" name="controller" value="returnNotes">
                <input type="hidden" name="token" value="<?php echo rand(1000, 9999) . date('Ymdhisa'); ?>">

                <div class="form-group row">
                  <label for="name" class="col-sm-2 form-control-label">Customer</label>
                  <div class="col-sm-5">
                    <input type="hidden" name="customer" value="<?php echo $customer;?>">
                    <input class="form-control" type="text" value="<?php echo $customer_name;?>" readonly>
                  </div>
                </div>
                <div class="form-group row">
                    <label for="type" align="" class="col-sm-2 form-control-label">Delivery Note</label>
                    <div class="col-sm-5">
                      <input class="form-control" type="text" name="delivery" value="<?php echo $dn;?>" readonly>
                    </div>
                </div>

                    <div class="form-group row">
                      <label for="date" align="left" class="col-sm-2 form-control-label">Date</label>
                      <div class="col-sm-5">
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
                      <label align="left" for="type" class="col-sm-2 form-control-label">Note</label>
                        <div class="col-sm-5">
                            <input class="form-control" type="text" name="attention">
                        </div>
                    </div>

                    <div class="form-group row">
                      <label for="type" class="col-sm-2 form-control-label">Transportation</label>
                      <div class="col-sm-5">
                        <input class="form-control" type="number" name="transportation" step="any" value="<?php echo $delivery_details['transportation'];?>">
                      </div>
                    </div>

                    <div class="form-group row">
                      <label align="center" class="col-sm-2 form-control-label"><b>Item</b></label>
                      <label align="center" class="col-sm-2 form-control-label"><b>JW Numbar</b></label>
                      <label align="center" class="col-sm-2 form-control-label"><b>Delivered Quantity</b></label>
                      <label align="center" class="col-sm-2 form-control-label"><b>Status</b></label>
                      <label align="center" class="col-sm-2 form-control-label"><b>Quantity</b></label>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-2">
                            <select class="form-control" name="item[]" id="delItem_0">
                            <?php                                
                                $sqlItem1="SELECT di.item AS itemId,di.jw AS jwNumber,itm.name AS itemName FROM delivery_item di INNER JOIN items itm ON di.item=itm.id
                                        WHERE di.delivery_id='$dn'";
                                $resultItem1=$conn->query($sqlItem1);
                                ?> <option value="">Select Item</option> <?php
                                if($resultItem1->num_rows > 0) {
                                while($rowItem1=$resultItem1->fetch_assoc()) {
                                ?>
                                <option value="<?php echo $rowItem1['itemId'];?>,<?php echo $rowItem1['jwNumber'];?>"> <?php echo $rowItem1['itemName'];?> [<?php echo $rowItem1['jwNumber'];?>]</option>
                            <?php  } } ?>                      
                            </select>
                        </div>
                        <div class="col-sm-2">
                          <input type="text" class="form-control" id="ItemJW_0" readonly>
                        </div>
                        <div class="col-sm-2">
                          <input type="text" class="form-control delivered-quantity" name="delivered_quantity[]" id="deliveredQuantity_0" readonly>
                        </div>
                        <div class="col-sm-2">
                            <select name="delivery_item_status[]" class="form-control">
                              <option value="1">ROUGH CAST</option>
                              <option value="2">10-20-30 OK</option>
                              <option value="3">10-20-30 CD</option>
                              <option value="4">REWORK</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                          <input type="text" min="1" step="any" class="form-control quantity-input" name="quantity[]" value="0">
                        </div>

                        <div class="box-tools">
                            <a href="javascript:void(0);" 
                                class="btn btn-info btn-sm" id="btnAddMore" data-original-title="Add More">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    <div id="divAttach"></div>
                    

                    <div class="form-group row m-t-md">
                      <div align="center" class="col-sm-offset-2 col-sm-12">
                        <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                        <button name="submit_add_returnNote" type="submit" class="btn btn-sm btn-outline rounded b-success text-success"
                        onclick="return validateQuantities()">Save</button>
                      </div>
                    </div>
              </form>
              <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
</div>

<script>
    function validateQuantities() {
        var quantities = document.querySelectorAll('.quantity-input');
        var balances = document.querySelectorAll('.delivered-quantity');
        
        for (var i = 0; i < quantities.length; i++) {
            var quantity = parseFloat(quantities[i].value);
            var balance = parseFloat(balances[i].value);
            
            if (quantity > balance) {
                alert('Entered quantity for delivery item' + (i+1) + ' is greater than the available order.');
                return false;
            }
        }
        return true;
    }
</script>

<script type="text/javascript">
 $(document).ready(function() {
  $("#customer4retuns").change(function() {
    var cust_id = $(this).val();
    $("#goodReturns").val(null).trigger('change');
    if(cust_id != "") {
      $.ajax({
        url: "<?php echo BASEURL;?>/loads/get_unreturned_delivery",
        data:{c_id:cust_id},
        type:'POST',
        success:function(response) {
          var resp = $.trim(response);
          $("#goodReturns").html(resp);
        }
      });
    }
  });
});
</script>

<script>
$(document).ready(function() {
  $('#delItem_0').on('change', function () {
    var goodsItemRow = this.id.split('_')[1];
    var itemDetails = $(this).val();
    var dn = <?php echo $dn;?>;
    getDeliveredQuantity(goodsItemRow, itemDetails, dn);
  });
});

function getDeliveredQuantity(goodsItemRow, itemDetails, dn) {
  if (itemDetails != "") {
    $.ajax({
    url: '<?php echo BASEURL;?>/loads/get_item_deliveredQuantity',
    data: {itemDetails: itemDetails,dn: dn},
    type: 'POST',
    dataType: 'json',
    success: function(response) {
      var JwNumber = response.JwNumber;
      var deliveredQuantity = response.deliveredQuantity;
      $("#ItemJW_" + goodsItemRow).val(JwNumber);
      $("#deliveredQuantity_" + goodsItemRow).val(deliveredQuantity);
    },
    error: function(xhr, status, error) {
      console.error("Error: " + error);
    }
    });
  }
}

$(document).ready(function() {
    let goodsItemRow = 1;
    const MAX_GOODS_RETURN_ROWS = 5;

    $('#btnAddMore').click(function () {
        const goodsRow = document.createElement('div');
        goodsRow.setAttribute('class', 'form-group row');

        var goodsReturnInnerDiv = `
        <div class="col-sm-2">
            <select class="form-control" name="item[]" id="delItem_${goodsItemRow}">
            <?php                                
                $sqlItem1="SELECT di.item AS itemId,di.jw AS jwNumber,itm.name AS itemName FROM delivery_item di INNER JOIN items itm ON di.item=itm.id
                           WHERE di.delivery_id='$dn'";
                $resultItem1=$conn->query($sqlItem1);
                ?> <option value="">Select Item</option> <?php
                if($resultItem1->num_rows > 0) {
                while($rowItem1=$resultItem1->fetch_assoc()) {
                ?>
                <option value="<?php echo $rowItem1['itemId'];?>,<?php echo $rowItem1['jwNumber'];?>"> <?php echo $rowItem1['itemName'];?> [<?php echo $rowItem1['jwNumber'];?>]</option>
            <?php  } } ?>                      
            </select>
        </div>
        <div class="col-sm-2">
            <input type="text" class="form-control" id="ItemJW_${goodsItemRow}" readonly>
        </div>
        <div class="col-sm-2">
            <input type="text" class="form-control delivered-quantity" name="delivered_quantity[]" id="deliveredQuantity_${goodsItemRow}" readonly>
        </div>
        <div class="col-sm-2">
            <select name="delivery_item_status[]" class="form-control">
                <option value="1">ROUGH CAST</option>
                <option value="2">10-20-30 OK</option>
                <option value="3">10-20-30 CD</option>
                <option value="4">REWORK</option>
            </select>
        </div>
        <div class="col-sm-2">
            <input type="text" min="1" step="any" class="form-control quantity-input" name="quantity[]" value="0">
        </div>
        <div class="box-tools">
            <a href="javascript:void(0);" class="btn btn-danger btn-sm btnRemoveDebits" data-original-title="Remove"><i class="fa fa-times"></i></a>
        </div>
    `;
        if(goodsItemRow <= MAX_GOODS_RETURN_ROWS) {

            $(goodsRow).append(goodsReturnInnerDiv);
            $('#divAttach').append(goodsRow);

            $(goodsRow).on('change', '[id^="delItem_"]', function() {
              var goodsItemRow = this.id.split('_')[1];
              var itemDetails = $(this).val();
              var dn = <?php echo $dn;?>;
                getDeliveredQuantity(goodsItemRow, itemDetails, dn);
            }); 

            goodsItemRow++;
            $(goodsRow).on('click', '.btnRemoveDebits', function () {
              $(goodsRow).remove();
            });
        }

    });
});
</script>

<?php include "../includes/footer.php"; ?>
