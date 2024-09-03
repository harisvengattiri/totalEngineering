<?php include "../config.php";?>
<?php include "../functions/functions.php";?>
<?php error_reporting(0);?>
<?php 
$qno = $_GET["qno"];
?>
<title>Mancon Quotation #<?php echo $qno;?></title>
<style>


table
{
    page-break-inside:auto;
    border-collapse: collapse;
    width: 100%;
    border: 1px solid black;
    padding: 2px;
    font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
}

tr, th, td {
    page-break-inside:avoid; page-break-after:auto ;
    height: 20px;
    border: 1px solid black;
    padding: 2px;
    font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
}

p,li {
     word-spacing: 2px;
     line-height: 140%;
     font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
     font-size: 12px;
}
body, h1 {
     font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
     font-size: 12px;
}
    .wrapper{position:relative; font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;}
    .right,.left{width:50%; position:absolute;}
    .right{right:0;}
    .left{left:0;}
</style>
<body>
<table style="width: 100%; border:0px">
<tr style="border:0px" >
<td style="width: 40%; border: 0px"></td>
<td style="width: 20%; border: 0px"><h2>QUOTATION</h2></td>
<td style="width: 40%; border: 0px"></td>
</tr>
</table>
<br/>
<?php 
             $sql = "SELECT * FROM quotation where id='$qno'";            
             $query = mysqli_query($conn,$sql);
             while($fetch = mysqli_fetch_array($query))
                  {
                       $id = $fetch['id'];
                       $date = $fetch['date'];
                       $customer1 = $fetch['customer'];
                       $site = $fetch['site'];
                       $salesrep = $fetch['salesrep'];
                       $terms = $fetch['terms'];
                       $attention = $fetch['attention'];
                       $trans = $fetch['trans'];
                       if($trans == NULL) {$trans = 0;}
                       $status = $fetch["status"];
                          if($status == 'Sales Order' && $site != NULL){
                            $sql_site = "SELECT p_name FROM customer_site WHERE id=$site";
                            $query_site = mysqli_query($conn,$sql_site);
                            $fetch_site = mysqli_fetch_array($query_site);
                            $site_name = $fetch_site['p_name'];
                            } else {
                                $site_name = $site;
                          }
                  }    
             $sql1 = "SELECT * FROM customers where id='$customer1'";
             $query1 = mysqli_query($conn,$sql1);
             while($fetch1 = mysqli_fetch_array($query1))
                  {
                       $id1=$fetch1['id'];
                       $customer=$fetch1['name'];
                       $address=$fetch1['address'];
                       $phone=$fetch1['phone'];
                       $fax=$fetch1['fax'];
                       $tin=$fetch1['tin'];
                       
                        $cust_type=$fetch1['cust_type'];
                        $period=$fetch1['period'];
                        $p_mode=$fetch1['p_mode'];
                  }
             $sql2 = "SELECT * FROM customers where id='$salesrep'";
             $query2 = mysqli_query($conn,$sql2);
             while($fetch2 = mysqli_fetch_array($query2))
                  {
                       $sr_name = $fetch2['name'];
                       $sr_des = $fetch2['person'];
                       $sr_phone = $fetch2['mobile'];
                  }
                  
             $sql_po = "SELECT order_referance,date FROM sales_order where qtn='$qno'";
             $query_po = mysqli_query($conn,$sql_po);
             while($fetch_po = mysqli_fetch_array($query_po))
                  {
                       $po = $fetch_po['order_referance'];
                       $po_date = $fetch_po['date'];
                  }
                  
?>
<table style="width: 100%;" cellspacing="0" cellpadding="0">
<tr>
<td style="width: 18%;">Customer No:</td>
<td style="width: 52%;"><b><?php echo sprintf("%03d",$id1);?></b></td>
<td style="width: 10%;">Qtn No:</td>
<td style="width: 20%;"><b><?php echo "QTN|".sprintf('%05d', $id);;?></b></td>
</tr>
<tr>
<td style="width: 18%;">Customer Name:</td>
<td style="width: 52%;"><b><?php echo $customer;?></b></td>
<td style="width: 10%;">Qtn Date:</td>
<td style="width: 20%;"><b><?php echo $date;?></b></td>
</tr>
<tr>
<td style="width: 18%;">Address</td>
<td colspan="3"><b><?php echo $address;?></b></td>
</tr>

<tr>
<td style="width: 18%;">Phone</td>
<td><b><?php echo $phone;?></b></td>
<td style="width: 15%;">Fax</td>
<td><b><?php echo $fax;?></b></td>
</tr>

<tr>
    <td style="width: 18%;">TRN</td>
    <td colspan="1"><b><?php echo $tin;?></b></td>
    <td style="width: 15%;">Sales Order</td>
    <td><b><?php echo $po; ?></b></td>
</tr>

<tr>
<td style="width: 18%;">Attn:</td>
<td><b><?php echo $attention;?></b></td>
<td style="width: 15%;">Date </td>
<td><b><?php echo $po_date; ?></b></td>
</tr>

<tr>
<td style="width: 18%;">Project</td>
<td colspan="3"><b><?php echo $site_name;?></b></td>
</tr>

<!--<tr>-->
<!--<td style="width: 18%;">Payment Terms</td>-->
<!--<td ><b><?php // echo $cust_type.' '.$period.' Days';?></b></td>-->
<!--<td style="width: 15%;">Payment Mode</td>-->
<!--<td><b><?php // echo $p_mode; ?></b></td>-->
<!--</tr>-->

</table><p align="justify"><br/>Dear Sir,<br/>
This is with reference to your inquiry from the above mentioned details; we are pleased to submit our best
offer as follows. We hope you will find our proposal competitive in terms of quality and pricing.</p>

<table style="width: 100%;" border="0" cellspacing="0" cellpadding="2">
<tr>
<th style="width: 4%;">#</th>
<th style="width: 14%;">Product No</th>
<th style="width: 26%;">Description</th>
<th style="width: 8%;">Bndl.</th>
<th style="width: 17%;">Dimensions</th>
<th>Unit</th>
<th>Qty</th>
<th>Price</th>
<th>Total</th>
</tr>

<?php
             $sql = "SELECT * FROM quotation_item where quotation_id='$qno' ORDER BY id";
             $query = mysqli_query($conn,$sql);
             $qn1 = 0;
             $subtotal = $totalqty = 0;
             while($fetch = mysqli_fetch_array($query))
             {
                  $sl++;
                  $item=$fetch['item'];
                  $batch=$fetch['batch'];
                  $pdate=$fetch['pdate'];
                  $coc=$fetch['coc'];
                  // $thisquantity=$fetch['thisquantity'];
                  $quantity=$fetch['quantity'];
                  $quantity=($quantity != NULL) ? $quantity : 0;
                  $price=$fetch['price'];
                  $price=($price != NULL) ? $price : 0;
                  $total=$fetch['total'];
                  $total=($total != NULL) ? $total : 0;
                  
                 $sql1 = "SELECT items,dimension,description,unit,bundle FROM items where id='$item'"; 
                 $query1 = mysqli_query($conn,$sql1);
                 while($fetch1 = mysqli_fetch_array($query1))
                 {
                    $item1=$fetch1['items'];
                    $size=$fetch1['dimension'];
                    $prdno=$fetch1['description'];
                    $unit=$fetch1['unit'];
                    $bundle=$fetch1['bundle'];
                    $bundle=($bundle != NULL) ? $bundle : 1;
                 }
                 $bdl=$quantity/$bundle;
                 $bndl=round($bdl,2);
             ?>

          <tr>
            <td align="center"><?php echo $sl;?></td>
            <td align="center"><?php echo $prdno;?></td>
            <td align="center"><?php echo $item1;?></td>
            <td align="center"><?php echo $bndl;?></td>
            <td align="center"><?php echo $size;?></td>
            <td align="center"><?php echo $unit;?></td>
            <td align="right"><?php echo $quantity;?></td>
            <td align="right"><?php echo custom_money_format("%!i", $price);?></td>
            <td align="right"><?php echo custom_money_format("%!i", $total);?></td>
          </tr>
<?php
$subtotal=$subtotal+$total;
$totalqty=$totalqty+$quantity;
}
?>
          <tr>
            <td colspan="6" align="right"><b>Total&nbsp;</b></td>
            <td colspan="1" align="right"><b><?php echo $totalqty;?></b></td>
            <td colspan="1" align="center"></td>
            <td colspan="1" align="right"><b><?php echo custom_money_format("%!i", $subtotal);?></b></td>
          </tr>
          <tr>
            <td colspan="5" align="center"></td>
            <td colspan="3" align="right"><b>Transportation&nbsp;</b></td>
            <td colspan="1" align="right"><b><?php echo custom_money_format("%!i", $trans);?></b></td>
          </tr>
          <?php $subtotal = $subtotal+$trans;?>
          <tr>
            <td colspan="6" align="center"></td>
            <td colspan="2" align="right"><b>VAT (5%)&nbsp;</b></td>
            <td colspan="1" align="right"><b><?php echo custom_money_format("%!i", $subtotal*0.05);?></b></td>
          </tr>



          <tr>
            <td colspan="2" align="center"><b>Payable</b></td>
            <td colspan="6" align="center"><b>
            <?php
            $payable1 = ($subtotal*1.05);
            $payable = number_format($payable1, 2, '.', '');
            $inwords=ucwords(convert_number_to_words($payable));
               if(fmod($payable,1)==0)
               {
               echo $inwords." Dirhams Only";
               }
               else
               {
               echo str_replace("Point","Dirhams and",$inwords)." Fils Only";
               }
            ?>
            </b></td>
            <td colspan="1" align="right"><b><?php echo custom_money_format("%!i", $payable);?><br/></b></td>
          </tr>


</table>
<?php
$brv=17-$sl;
for($i=0;$i<$brv;$i++)
{
echo "<br/>";
}
?>
<p align="justify"><?php echo $terms;?></p>
<table style="border:0px;">
<tr style="border:0px;">
<td style="border:0px; width: 75%;" align="left">
<p style="font-size:13px;" font>We hope that the information contained herein meet with your requirement.</p>
<!--and we can assure you of quality
workmanship and materials and trust we may be favored with your valued order.-->
<br/>
Thanking you for, <b>MANCON BLOCK FACTORY LLC.</b></td>
<td style="border:0px; vertical-align: bottom; width: 25%;" align="center"><b><?php echo $sr_name;?><br>
               <?php echo $sr_des;?><br>
               <?php echo $sr_phone;?></b></td>
</tr>
</table>

</body>
