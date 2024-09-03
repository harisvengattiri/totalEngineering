<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  
</head>
<body>    
  <div class="main-wrapper">
    <div class="main-inner">

      <!-- Header -->
      <div class="pdf-header">
          <div id="pdf-logo"><img src="http://mancon.gulfit.xyz/images/logo_full.png" alt=""></div>
          <div id="order-id">Order Referance No:<span><?php echo $or; ?></span></div>
      </div>
      <!-- Header ends here -->

      <!-- Address -->
      <div class="address-wrapper">
        <h2>ADDRESS</h2>
        <p>
          <?php echo 'haris'; ?>, <br />
          <?php echo 'Gulf IT'; ?>,<br />
          <?php echo 'vengattiri H'; ?>,<br />
          <?php echo 'India'; ?>,<br />
          PH : <?php echo '9544292357'; ?><br />          
        </p>
      </div>
      <!-- Address ends here -->

      <!-- Table Wrapper -->
      <div class="table-wrapper">
           
        <div class="table">
          <div class="t-head">
            <div class="t-cell t-cell-slno">SNo</div>
            <div class="t-cell t-cell-prname">Item Description</div>
            <div class="t-cell t-cell-brwno">Size</div>
            <div class="t-cell t-cell-tofit">Batch No.</div>
            <div class="t-cell t-cell-qty">Prod Date</div>
            <div class="t-cell t-cell-price">COC No.</div>
            <div class="t-cell t-cell-bundle">Bundles</div>
            <div class="t-cell t-cell-bundle">Qty</div>
          </div>
          <div class="t-body">
             <?php
             include "../config.php";
             $sql="SELECT * FROM delivery_item where order_referance='$or' AND batch != 0";
             $query=mysqli_query($conn,$sql);
             $sl=1;
             $qn=0;
             while($fetch=mysqli_fetch_array($query))
             {
                  
                  $item=$fetch['item'];
                  $batch=$fetch['batch'];
                  $pdate=$fetch['pdate'];
                  $coc=$fetch['coc'];
                  $quantity=$fetch['thisquantity'];
                  
                 $sql1="SELECT size FROM items where items='$item'"; 
                 $query1=mysqli_query($conn,$sql1);
                 while($fetch1=mysqli_fetch_array($query1))
                 {
                    $size=$fetch1['size'];  
                 }
             
             ?>
            <!-- 1 -->
            <div class="t-row">
              <div class="t-cell t-cell-slno"><?php echo $sl; ?></div>
              <?php $sl=$sl+1; ?>
              <div class="t-cell t-cell-prname"><?php echo $item; ?></div>
              <div class="t-cell t-cell-brwno"><?php echo $size; ?></div>
              <div class="t-cell t-cell-tofit"><?php echo $batch; ?></div>
              <div class="t-cell t-cell-qty"><?php echo $pdate; ?></div>
              <div class="t-cell t-cell-price"><?php echo $coc; ?></div>
              <div class="t-cell t-cell-bundle"><?php echo '5'; ?></div>
              <div class="t-cell t-cell-bundle"><?php echo $quantity; ?></div>
            </div>
            <?php $qn=$qn+$quantity; ?>
             <?php } ?>

            <div class="t-footer">
              <div class="total-wrapper">
                <div style="float:right;margin-right:10px;">Grand Total(Quantity): <?php echo $qn; ?></div>
              </div>
            </div>

          </div>
        </div>

      </div>
      <!-- Table Wrapper ends here -->

      <!-- Remarks -->
      <div class="remarks-wrapper">
        <h2>Remarks</h2>
        <p><?php echo 'good'; ?></p>
        <p>Declaration <br>
        The goods sold are intended for end user consumption and not for resale.</p>
        <br>
        <p>This is a computer generated invoice.</p>
      </div>
      <!-- Remarks ends here -->

    </div>
  </div>

</body>
</html>
