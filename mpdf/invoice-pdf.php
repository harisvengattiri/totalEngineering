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
<?php 
        include "../config.php";
             $sql="SELECT * FROM invoice where id='$mnt'";
             
             $query=mysqli_query($conn,$sql);
             while($fetch=mysqli_fetch_array($query))
             {
                  $id=$fetch['id'];
                  $date=$fetch['date'];
                  $customer=$fetch['customer'];
                  $site=$fetch['customersite'];
                    $sqlsite="SELECT p_name from customer_site where id='$site'";
                    $querysite=mysqli_query($conn,$sqlsite);
                    $fetchsite=mysqli_fetch_array($querysite);
                    $site1=$fetchsite['p_name'];
                  $lpo=$fetch['lpo'];
                  
                  $sql1="SELECT * FROM customer_site where customer='$customer' AND id='$site'";
                  $query1=mysqli_query($conn,$sql1);
                  while($fetch1=mysqli_fetch_array($query1))
                  {
                       $siteno=$fetch1['site'];
                       $per=$fetch1['contact_per'];
                       $no=$fetch1['contact_no'];
                       $permit=$fetch1['permit'];
                  }
                  $sql2="SELECT * FROM customers where id='$customer'";
                  $query2=mysqli_query($conn,$sql2);
                  while($fetch2=mysqli_fetch_array($query2))
                  {
                       $id1=$fetch2['id'];
                       $cust=$fetch2['name'];
                       $address=$fetch2['address'];
                       $phone=$fetch2['phone'];
                       $fax=$fetch2['fax'];
                  }
                  
             }
        ?>
      <!-- Header -->
      <div class="pdf-header">
          <div id="pdf-logo"><img src="http://mancon.gulfit.xyz/images/logo_full.png" alt=""></div>
          <div id="order-id">Invoice No:<span>INV <?php echo $mnt; ?></span></div>
      </div>
      <!-- Header ends here -->
	<br>
      <!-- Table Wrapper -->
<!--      <div class="table-wrapper">
        <div class="table1">
              <div class="t-row1">
              <div class="t-cell t-cell-slno1">Customer No#:<b>CST<?php echo $id1;?></b>
              <br>
              Customer Name: <b><?php echo $customer;?></b>
              <br>
              Adderss:<b><?php echo $address;?></b>
              <br>
              Phone:<b><?php echo $phone;?></b>
              <br>
              Fax:<b><?php echo $fax;?></b>
              </div>
             </div>
        </div> 
      </div>-->
      
      <div class="table-wrapper">
           
        <div class="table1">
             <div class="t-row1">
              <div class="t-cell t-cell-slno1">Customer No#:<b>CST <?php echo sprintf('%04d',$id1);?></b>
              <br>
              Customer Name: <b><?php echo $cust;?></b>
              <br>
              Adderss:<b><?php echo $address;?></b>
              <br>
              Phone:<b><?php echo $phone;?></b>
              <br>
              Fax:<b><?php echo $fax;?></b>
              <br>
              TRN:<b><?php echo $trn;?></b>
              </div>
             </div>
        </div>
           
           <div class="table3">
             <div class="t-row1">
              <div class="t-cell t-cell-slno1">Invoice No#:<b><?php echo $mnt;?></b>
              <br>
              Invoice Date: <b><?php echo $date;?></b>
              <br>
              TRN: <b><?php echo $trn;?></b>
              <br>
              LPO No: <b><?php echo $lpo;?></b>
              </div>
             </div>
        </div>
      </div>
      
      
      
      <h2>Invoice Details</h2>
      <div class="table-wrapper">
           
        <div class="table">
          <div class="t-head">
            <div class="t-cell t-cell-slno">#</div>
            <div class="t-cell t-cell-delno">Delivery No.</div>
            <div class="t-cell t-cell-date">Date</div>
            <div class="t-cell t-cell-description">Description</div>
            <div class="t-cell t-cell-size">Size</div>
            <div class="t-cell t-cell-quantity">Qty</div>
            <div class="t-cell t-cell-price">Price</div>
            <div class="t-cell t-cell-total">Total</div>
          </div>
          <div class="t-body">
             <?php
             $sql="SELECT * FROM invoice_item where invoice_id='$mnt'";
             $query=mysqli_query($conn,$sql);
             $sl=1;
             $subtotal=0;
             while($fetch=mysqli_fetch_array($query))
             {
                  $item = mysqli_real_escape_string($conn, $fetch['item']);
                  $item1=$fetch['item'];
                  $dn=$fetch['dn'];
                  $qty=$fetch['quantity'];
                  $price=$fetch['unit'];
                  $total=$fetch['total'];
                  $date=$fetch['pdate'];
                  $coc=$fetch['coc'];
                  $quantity=$fetch['thisquantity'];
                  
                 $sql1="SELECT dimension FROM items where items='$item'";
                 $query1=mysqli_query($conn,$sql1);
                 while($fetch1=mysqli_fetch_array($query1))
                 {
                    $size1=$fetch1['dimension']; 
                 }
             
             ?>
            <!-- 1 -->
            <div class="t-row">
              <div class="t-cell t-cell-slno"><?php echo $sl; ?></div>
              <?php $sl=$sl+1; ?>
              <div class="t-cell t-cell-delno"><?php echo sprintf('%05d',$dn); ?></div>
              <div class="t-cell t-cell-date"><?php echo $date; ?></div>
              <div class="t-cell t-cell-description"><?php echo $item1; ?></div>
              <?php
              $size1 = str_replace(' ', '', $size1);
               ?>
              <div class="t-cell t-cell-size"><?php echo $size1; ?></div>
              <div class="t-cell t-cell-quantity"><?php echo $qty; ?></div>
              <div class="t-cell t-cell-price"><?php echo $price; ?></div>
              <div class="t-cell t-cell-total"><?php echo $total; ?></div>
            </div>
            <?php
              $subtotal=$subtotal+$total;
            ?>
             <?php } ?>

            <div class="t-footer">
              <div class="total-wrapper">
               <?php 
              $vat = (5 / 100) * $subtotal;
              $grandtotal=$vat+$subtotal; 
              ?>
                   <div style="float:right;margin-right:10px;">Sub Total : <b><?php echo $subtotal; ?></b></div>
              </div>
            </div>
            
            <div class="t-footer">
              <div class="total-wrapper">  
                   <div style="float:right;margin-right:10px;">VAT Tax (5%) : <b><?php echo $vat; ?></b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Grand Total : <b><?php echo $grandtotal; ?></b></div>
              </div>
            </div>

          </div>
        </div>

      </div>
      <!-- Table Wrapper ends here -->

      <!-- Remarks -->
      <div class="table-wrapper">
           
        <div class="table2">
             <div class="t-row1">
              <div class="t-cell t-cell-slno1">Recieved By:
              </div></div>
             <div class="t-row1">
              <div class="t-cell t-cell-slno1">Signature:
             </div></div>
        </div>
           
           <div class="table3">
             <div class="t-row1">
              <div class="t-cell t-cell-slno1">Prepared By:
              </div></div>
             <div class="t-row1">
              <div class="t-cell t-cell-slno1">Signature:
             </div></div>
        
      </div>
      <!-- Remarks ends here -->

    </div>
  </div>

</body>
</html>
