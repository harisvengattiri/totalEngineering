<!DOCTYPE html>

<?php
 $or = $_GET['or'];
 $mnt= $_GET['mnt'];
?>
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
             $sql="SELECT * FROM quotation where id='$mnt'";
             
             $query=mysqli_query($conn,$sql);
             while($fetch=mysqli_fetch_array($query))
             {
                  $id=$fetch['id'];
                  $date=$fetch['date'];
                  $customer=$fetch['customer'];
                  $site=$fetch['site'];
                  $salesrep=$fetch['salesrep'];
                  $terms=$fetch['terms'];
                  $attention=$fetch['attention'];
             }    
                  $sql1="SELECT * FROM customers where name='$customer'";
                  $query1=mysqli_query($conn,$sql1);
                  while($fetch1=mysqli_fetch_array($query1))
                  {
                       $id1=$fetch1['id'];
                       $address=$fetch1['address'];
                       $phone=$fetch1['phone'];
                       $fax=$fetch1['fax'];
                  }
                  
        ?>
      <!-- Header -->
      
<!--      <div class="pdf-header">
      <div class="table-wrapper">
      <div id="pdf-logo"><img src="../images/manconlogo.png" alt=""></div>
      <div class="t-row6"><p style="font-size:12px;" align="left">MOHAMMED AL NASERI CONCERETE <br>PRODUCTION & BLOCK FACTORY LLC <br> DIC 4 - Jabal Ali<br>
Phone: 04-242-36-46<br>
Fax: 04-242-36-47<br>
Email: sales@manconblock.com</p></div>
          <div class="t-row5"><h3>Quotation</h3></div>
          
          <div id="order-id">Order Referance No:<span><?php echo $or; ?></span></div>
     
      </div>
      </div>-->
      
      <!-- Header ends here -->
      <!-- Table Wrapper -->
     
       
      <div style="text-align: center; margin-top: -55px;"><h2>Quotation</h2></div>
      
      <div class="table-wrapper">
           <div class="t-row1">
              <div class="t-cell t-cell-slno1">Customer No#:<b>CST <?php echo $id1;?></b>
              <br>
              Customer Name: <b><?php echo $customer;?></b>
              <br>
              Adderss:<b><?php echo $address;?></b>
              <br>
              Phone:<b><?php echo $phone;?></b>
              <br>
              Fax:<b><?php echo $fax;?></b>
              <br>
               Attention:<b><?php echo $attention;?></b>
              <br>
              </div>
           </div>
        
             <div class="t-row2">
                  
              <div class="t-cell t-cell-slno1">Qtn No#:<b>QTN|<?php echo sprintf("%06d", $id);?></b>
              <br><br>
              Qtn Date:<b><?php echo $date;?></b>
              <br>
              <br>
              <br>
              Project:<b><?php echo $site;?></b>
              
              </div>
             </div>
      </div>
      
      <div class="table-wrapper">
        <div class="table">
          <div class="t-head">
            <div style="align:right;" class="t-cell t-cell-slno">#</div>
            <div style="align:right;" class="t-cell t-cell-prname">Product No</div>
            <div style="align:right;" class="t-cell t-cell-brwno">Description</div>
            <div style="align:right;" class="t-cell t-cell-tofit">Qty</div>
            <div style="align:right;" class="t-cell t-cell-qty">Unit</div>
            <div style="align:right;" class="t-cell t-cell-price">Unit Price</div>
            <div style="align:right;" class="t-cell t-cell-bundle">Total</div>
          </div>
          <div class="t-body">
             <?php
             $sql="SELECT * FROM quotation_item where quotation_id='$mnt'";
             $query=mysqli_query($conn,$sql);
             $sl=1;
             $qn1=0;
             while($fetch=mysqli_fetch_array($query))
             {
                  $item = mysqli_real_escape_string($conn, $fetch['item']);
                  $item1=$fetch['item'];
                  $batch=$fetch['batch'];
                  $pdate=$fetch['pdate'];
                  $coc=$fetch['coc'];
                  $thisquantity=$fetch['thisquantity'];
                  $quantity=$fetch['quantity'];
                  $price1=$fetch['price'];
                  $price = number_format($price1, 2);
                  $total1=$fetch['total'];
                  $total = number_format($total1, 2);
                  
                  
                 $sql1="SELECT dimension,description,unit FROM items where items='$item'"; 
                 $query1=mysqli_query($conn,$sql1);
                 while($fetch1=mysqli_fetch_array($query1))
                 {
                    $size=$fetch1['dimension'];
                    $prdno=$fetch1['description'];
                    $unit=$fetch1['unit'];
                 }
             
             ?>


            <!-- 1 -->



            <div class="t-row">
              <div style="align:right;" class="t-cell t-cell-slno"><?php echo $sl; ?></div>
              <?php $sl=$sl+1; ?>
              <div style="align:right;" class="t-cell t-cell-prname"><?php echo $prdno; ?></div>
              <div style="align:right;" class="t-cell t-cell-brwno"><?php echo $item1; ?></div>
              <div style="align:right;" class="t-cell t-cell-tofit"><?php echo $quantity; ?></div>
              <div style="align:right;" class="t-cell t-cell-qty"><?php echo $unit; ?></div>
              <div style="align:right;" class="t-cell t-cell-price"><?php echo $price; ?></div>
              <div style="align:right; text-align:right;" class="t-cell t-cell-bundle"><?php echo $total; ?></div>
            </div>
            <?php
              $qn1=$qn1+$total1;
             }
             $qn = number_format($qn1, 2);
             $vat1=$qn1*0.05;
              $vat = number_format($vat1, 2);
              $grand1=$qn1+$vat1;
              $grand2=round($grand1);
              $grand = number_format($grand2, 2);
             ?>
            <hr>
<!--            <div class="t-footer">
              <div class="total-wrapper">
                <div style="align:right;">Sub Total : <?php echo $qn;?></div><br>
                <div style="align:right;">VAT [5%]: <?php echo $vat;?></div><br>
                <div style="align:right;">Grand Total : <?php echo $grand;?></div>
                <?php
               $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
	       echo $f->format($grand).' '.'AED Only';
                ?>
              </div>
            </div>-->



<table align="right" style="width:50%;">
  
  <tr>
    <td align="right">Sub Total :</td>
    <td align="right"><?php echo $qn; ?></td>
    
  </tr>
  <tr>
    <td align="right">VAT [5%]: </td>
    <td align="right"><?php echo $vat;?></td>
    
  </tr>
  <tr>
    <td align="right"><b>Grand Total :</b></td>
    <td align="right"><b><?php echo $grand;?></b></td>
  </tr>
</table>
<hr>
<table align="left">
  <tr>
       <td><b>Amount in Words :</b></td>
    <td>     <?php $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
	       echo ucwords($f->format($grand2)).' '.'AED Only'; ?>
    </td>
    
  </tr>     
</table>



            
<!--            <div class="t-footer">
              <div class="total-wrapper">
                <div style="align:right;">Grand Total : <?php echo $grand;?></div>
             <?php
               $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
	       echo '$f->format($grand)'.' '.'AED Only';
             ?>
              </div>
            </div>-->
          </div>
        </div>

      </div>
      <!-- Table Wrapper ends here -->
  <!--<div>   <img src="../images/footertext.png"/></div>-->     
     <!-- Remarks -->
<?php
/*$limit=25-$sl;
$allowed=12;
if($limit<=$allowed)
{
for($k=0;$k<$limit+1;$k++)
{
echo "<br/>";
}
}*/
?>
     <div class="table-wrapper page-break-before">
       <div class="t-row">
       <div class="t-row3">
       <br>
<p style="font-size:12px;"><?php echo $terms;?></p>
<!--<p>
1) Due to Toll Gate Price increase from April onwards, Quotation price will be revised from
April-2018.<br>
2) Payment Terms : 90 Days PDC<br>
3) Delivery Terms : Delivery at Site<br>
4) The above price does not include taxes, VAT or any other type of taxes
imposed by UAE Government shall be added to the invoices in the future.
</p>-->
       </div>
      <div class="t-row4">
            <p style="font-size:12px;"><b>Nowshad Adimu<br>
               SALES MANAGER<br>
               050-5449452</b></p>
       </div>
       
     </div>
 <!--          <div class="t-row">
             <div>
            <p style="font-size:12px;"><b>Nowshad Adimu<br>
               SALES MANAGER<br>
               050-5449452</b></p>
       </div>  -->
          </div>
     </div>
     

</div>
</body>
</html>
