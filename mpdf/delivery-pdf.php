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
        
        
             $sql="SELECT * FROM delivery_note where order_referance='$or' AND id='$mnt'";
             
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
                  
                  $vehi=$fetch['vehicle'];
                    $sqlveh="SELECT vehicle from vehicle where id='$vehi'";
                    $queryveh=mysqli_query($conn,$sqlveh);
                    $fetchveh=mysqli_fetch_array($queryveh);
                    $vehicle=$fetchveh['vehicle'];
                  
                  $dri=$fetch['driver'];
                    $sqldri="SELECT name from customers where id='$dri'";
                    $querydri=mysqli_query($conn,$sqldri);
                    $fetchdri=mysqli_fetch_array($querydri);
                    $driver=$fetchdri['name'];
                  
                  $print=$fetch['prints'];
                  
                  $sql1="SELECT * FROM customer_site where customer='$customer' AND id='$site'";
                  $query1=mysqli_query($conn,$sql1);
                  while($fetch1=mysqli_fetch_array($query1))
                  {
                       $siteno=$fetch1['site'];
                       $per=$fetch1['contact_per'];
                       $no=$fetch1['contact_no'];
                       $permit=$fetch1['permit'];
                       $contact=$fetch1['contact_no'];
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
                  $sql3="SELECT * FROM sales_order where order_referance='$or'";
                  $query3=mysqli_query($conn,$sql3);
                  while($fetch3=mysqli_fetch_array($query3))
                  {
                       $lpo=$fetch3['lpo'];
                       $date1=$fetch3['date'];
                  }
             }
             $print=$print+1;
             $sql0="UPDATE delivery_note SET prints=$print where id='$mnt'";
             $query0=mysqli_query($conn,$sql0);
        ?>
      <!-- Header -->
      <div class="pdf-header">
           <br><br><br><br>
<!--          <div id="pdf-logo"><img src="http://mancon.gulfit.xyz/images/logo_full.png" alt=""></div>
          <div id="order-id">Order Referance No:<span><?php echo $or; ?></span></div>-->
      </div>
      <!-- Header ends here -->
	<br>
      <!-- Table Wrapper -->
      <div class="table-wrapper">
        <div class="table1">
              <div class="t-row1">
              <div class="t-cell t-cell-slno1">Customer No#:<b>CST<?php echo $id1;?></b>
              <br>
              Customer Name: <b><?php echo $cust;?></b>
              <br>
              Adderss:<b><?php echo $address;?></b>
              <br>
              Phone:<b><?php echo $phone;?></b>
              <br>
              Fax:<b><?php echo $fax;?></b>
              </div>
             </div>
        </div>   
           
      </div>
      <div class="table-wrapper">
           
        <div class="table2">
             <div class="t-row1">
              <div class="t-cell t-cell-slno1">Delivery No#:<b>DN<?php echo sprintf("%06d",$id);?></b>
              <br>
              Delivery Date:<b><?php echo $date;?></b>
              <br>
              Order No#:<b>OR<?php echo $or;?></b>
              <br>
              Order Date:<b><?php echo $date1;?></b>
              <br>
              LPO No#:<b><?php echo $lpo;?></b>
              </div>
             </div>
        </div>
           <div class="table3">
             <div class="t-row1">
              <div class="t-cell t-cell-slno1">Building Permit:<b><?php echo $permit;?></b>
              <br>
              Site/Job No#:<b><?php echo $siteno;?></b>
              <br>
              Contact Name:<b><?php echo $per;?></b>
              <br>
              Contact Phone:<b><?php echo $contact;?></b>
              <br>
              Location:<b><?php echo $site1;?></b>
              </div>
             </div>
        </div>
      </div>
      
      
      
      <h2>Delivery Details</h2>
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
            <div class="t-cell t-cell-bundle1">Qty</div>
          </div>
          <div class="t-body">
             <?php
             $sql="SELECT * FROM delivery_item where order_referance='$or' AND delivery_id='$mnt' AND batch != 0";
             $query=mysqli_query($conn,$sql);
             $sl=1;
             $qn=0;
             while($fetch=mysqli_fetch_array($query))
             {
                  $item=$fetch['item'];
                    $sqlitem="SELECT items from items where id='$item'";
                    $queryitem=mysqli_query($conn,$sqlitem);
                    $fetchitem=mysqli_fetch_array($queryitem);
                    $item1=$fetchitem['items'];
                  $batch=$fetch['batch'];
                  $pdate=$fetch['pdate'];
                  $coc=$fetch['coc'];
                  $quantity=$fetch['thisquantity'];
                  
                 $sql1="SELECT dimension,bundle FROM items where id='$item'"; 
                 $query1=mysqli_query($conn,$sql1);
                 while($fetch1=mysqli_fetch_array($query1))
                 {
                    $size=$fetch1['dimension'];
                    $bundle=$fetch1['bundle'];  
                 }
                 $bdl=$quantity/$bundle;
                 $bndl=round($bdl,2);
             ?>
            <!-- 1 -->
            <div class="t-row">
              <div class="t-cell t-cell-slno"><?php echo $sl; ?></div>
              <?php $sl=$sl+1; ?>
              <div class="t-cell t-cell-prname"><?php echo $item1; ?></div>
              <div class="t-cell t-cell-brwno"><?php echo $size; ?></div>
              <div class="t-cell t-cell-tofit"><?php echo $batch; ?></div>
              <div class="t-cell t-cell-qty"><?php echo $pdate; ?></div>
              <div class="t-cell t-cell-price"><?php echo $coc; ?></div>
              <div class="t-cell t-cell-bundle"><?php echo $bndl; ?></div>
              <div class="t-cell t-cell-bundle1"><?php echo $quantity; ?></div>
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
      <div class="table-wrapper">
           
        <div class="table4">
             <div class="t-row1">
              <div class="t-cell t-cell-slno1">Recieved By:
              </div></div>
             <div class="t-row1">
              <div class="t-cell t-cell-slno1">Signature:
             </div></div>
        </div>
           <div class="table5">
             <div class="t-row1">
                  <div class="t-cell t-cell-slno1">Vehicle No: <b><?php echo $vehicle;?></b>
              </div></div>
             <div class="t-row1">
                  <div class="t-cell t-cell-slno1">Driver: <b><?php echo $driver;?></b>
             </div></div>
        </div>
           <div class="table6">
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
