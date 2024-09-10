<?php include "../config.php";?>

<?php 
$order = $_GET["id"]; 
?>
<title>Delivery Order #<?php echo $order;?></title>

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
    padding: 5px;
    font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
}

tr, th, td {
    page-break-inside:avoid; page-break-after:auto ;
    height: 20px;
    border: 1px solid black;
    padding: 5px;
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
<td style="width: 35%; border: 0px"></td>
<td align="center" style="width: 30%; border: 0px"><h2>DELIVERY ORDER</h2>
<h4>TRN 100061540900003</h4>
</td>
<td style="width: 35%; border: 0px"></td>
</tr>
</table>
<br/>

     <?php 
             $sql="SELECT * FROM sales_order where id = '$order'";
             
             $query=mysqli_query($conn,$sql);
             while($fetch=mysqli_fetch_array($query))
             {
                  $id=$fetch['id'];
                  $date=$fetch['date'];
                  $customer=$fetch['customer'];
                  
                  $sub_total=$fetch['total'];
                  $sub_total = ($sub_total != NULL) ? $sub_total : 0;

                  $vat=$fetch['vat'];
                  $vat = ($vat != NULL) ? $vat : 0;

                  $grand_total=$fetch['grand'];
                  $grand_total = ($grand_total != NULL) ? $grand_total : 0;

                  $transportation=$fetch['transport'];
                  $transportation = ($transportation != NULL) ? $transportation : 0;
             }
        ?>



<table style="width: 100%;" cellspacing="0" cellpadding="0">
<tr>
<td style="width: 15%;">Customer No:</td>
<td><b>CST <?php echo sprintf('%04d',$id1);?></b></td>
<td style="width: 15%;">Invoice No:</td>
<td><b>AR|<?php echo sprintf('%06d',$inv);?></b></td>
</tr>
<tr>
<td style="width: 15%;">Customer Name:</td>
<td><b><?php echo $cust;?></b></td>
<td style="width: 15%;">Invoice Date:</td>
<td><b><?php echo $date;?></b></td>
</tr>
<tr>
<td style="width: 15%;">Address:</td>
<td><b><?php echo $address;?></b></td>
<td style="width: 15%;">LPO No:</td>
<td><b><?php echo $lpo;?></b></td>
</tr>
<tr>
<td style="width: 15%;">Phone:</td>
<td><b><?php echo $phone;?></b></td>
<td style="width: 15%;">Site:</td>
<td><b><?php echo $site1;?></b></td>
</tr>
<tr>
<td style="width: 15%;">FAX:</td>
<td><b><?php echo $fax;?></b></td>
<td style="width: 16%;">Purchase Order#</td>
<td><b><?php echo $or;?></b></td>
</tr>
<tr>
<td style="width: 15%;">TRN:</td>
<td><b><?php echo $trn;?></b></td>
<td style="width: 15%;"></td>
<td><b></b></td>
</tr>
</table>
<br/>
<h3>Invoice Details</h3>
<br/>
<table style="width: 100%;" border="0" cellspacing="0" cellpadding="2">
<tr>
<th style="width: 3%;">#</th>
<th style="width: 14%;">Delivery No.</th>
<th style="width: 14%;">Item</th>
<th style="width: 9%;">Qty</th>
<th style="width: 8%;">Price</th>
<th style="width: 10%;">Total AED</th>
</tr>

        <?php
             $sql="SELECT * FROM order_item where order_id = '$order'";
             $query=mysqli_query($conn,$sql);
             $sl=1;
             $subtotal=0;
             while($fetch=mysqli_fetch_array($query))
             {
                  $item = $fetch['item'];
                  $quantity = $fetch['quantity'];
                  $price = $fetch['price'];
                  $total = $fetch['total'];  
        ?>
          <tr>
            <td align="center"><?php echo $sl;?></td>
            <td align="center">10012</td>
            <td align="center"><?php echo $item;?></td>
            <td align="center"><?php echo $quantity;?></td>
            <td align="center"><?php echo $price;?></td>
            <td align="right"><?php echo custom_money_format('%!i', $total);?></td>
          </tr>
        <?php $sl++;} ?>
          <tr>
            <td colspan="8" align="right"><b>Price before VAT&nbsp;</b></td>
            <td colspan="1" align="right"><b><?php echo custom_money_format('%!i', $total);?></b></td>
          </tr>
          <tr>
            <td colspan="8" align="right"><b>VAT&nbsp;5%&nbsp;</b></td>
            <td colspan="1" align="right"><b><?php echo custom_money_format('%!i', $vat);?></b></td>
          </tr>
         
          <tr>
            <td colspan="8" align="right"><b>Transportation&nbsp;</b></td>
            <td colspan="1" align="right"><b><?php echo custom_money_format('%!i', $transportation);?></b></td>
          </tr>
          
          <tr>
            <td colspan="2" align="center"><b>Grand total</b></td>
            <td colspan="6" align="center"><b>
            <?php
            $inwords=ucwords(convert_number_to_words($grand_total));

               if(fmod($grand_total,1)==0)
               {
                 echo 'AED '. $inwords." Only";
               }
               else
               {
                $dirham = preg_replace('/ Point.*/', '', $inwords);
                $filsmod1 = fmod($grand_total,1);
                $filsmod = number_format((float)$filsmod1, 2, '.', '');
                $fils = str_replace('0.', '', $filsmod);
                
                echo 'AED '. $dirham.' And '.$fils.'/100';
               }
            ?>
            </b></td>
            <td colspan="1" align="right"><b><?php echo custom_money_format('%!i', $grand_total);?></b></td>
          </tr>
</table>
<?php
$brv=13-$sl;
for($i=0;$i<$brv;$i++)
{
echo "<br/>";
}
?>
<p align="justify"><?php echo $bank_details;?></p>
<table style="width: 100%; border:0px" cellspacing="0" cellpadding="0">

<tr style="border:0px" >    
<td style="width: 13%; border:0px"><br/><br/>Received By:<br/><br/></td>
<td style="border:0px" ><br/><br/><b>....................</b><br/><br/></td>         
<td style="width: 40%; border:0px"><br/><br/><br/><br/></td>
<td style="width: 13%; border:0px"><br/><br/>Prepared By:<br/><br/></td>
<td style="border:0px" ><br/><br/><b><?php echo $_GET['open'];?></b><br/><br/></td>
</tr>    

<tr style="border:0px" > 
<td style="width: 13%; border:0px"><br/><br/>Signature:<br/><br/></td>
<td style="border:0px" ><br/><br/>........................<br/><br/></td>    
    
<td style="width: 40%; border:0px"><br/><br/><br/><br/></td>

<td style="width: 13%; border:0px"><br/><br/>Signature:<br/><br/></td>
<td style="border:0px" ><br/><br/>....................<br/><br/></td>
</tr>
</table>
</body>