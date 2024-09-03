<?php include"../config.php";?>
<?php include"../functions/functions.php";?>
<?php 
$cdt=$_GET["cdt"]; 
?>
<title>Credit Note #<?php echo $cdt;?></title>
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
<td align="center" style="width: 30%; border: 0px"><h2>TAX CREDIT NOTE</h2>
<h4>TRN 100061540900003</h4>
</td>
<td style="width: 35%; border: 0px"></td>
</tr>
</table>
<br/>

     <?php 
             $sql="SELECT * FROM credit_note where id = '$cdt'";
             
             $query=mysqli_query($conn,$sql);
             while($fetch=mysqli_fetch_array($query))
             {
                  $id=$fetch['id'];
                  $date=$fetch['date'];
                  $customer=$fetch['customer'];
                  $invoice=$fetch['invoice'];
                    $sqllpo="SELECT lpo FROM invoice where id='$invoice'";
                    $querylpo=mysqli_query($conn,$sqllpo);
                    $fetchlpo=mysqli_fetch_array($querylpo);
                    $lpo=$fetchlpo['lpo'];
                    
                  $summery=$fetch['summery'];
                  $description=$fetch['description'];
                  
                  $sub_total=$fetch['amount'];
                  $sub_total=($sub_total != NULL) ? $sub_total : 0;
                  $vat=$fetch['vat'];
                  $vat=($vat != NULL) ? $vat : 0;
                  $grand_total=$fetch['total'];
                  $grand_total=($grand_total != NULL) ? $grand_total : 0;
                  
                  $sql2="SELECT * FROM customers where id='$customer'";
                  $query2=mysqli_query($conn,$sql2);
                  while($fetch2=mysqli_fetch_array($query2))
                  {
                       $id1=$fetch2['id'];
                       $cust=$fetch2['name'];
                       $address=$fetch2['address'];
                       $phone=$fetch2['phone'];
                       $fax=$fetch2['fax'];
                       $trn=$fetch2['tin'];
                  }
                  
             }
        ?>



<table style="width: 100%;" cellspacing="0" cellpadding="0">
<tr>
<td style="width: 15%;">Customer No:</td>
<td><b>CST <?php echo sprintf('%04d',$id1);?></b></td>
<td style="width: 15%;">Credit Note No:</td>
<td><b>CN|<?php echo sprintf('%06d',$cdt);?></b></td>
</tr>
<tr>
<td style="width: 15%;">Customer Name:</td>
<td><b><?php echo $cust;?></b></td>
<td style="width: 15%;">Date:</td>
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
<td style="width: 15%;">Invoice:</td>
<td><b>INV|<?php echo $invoice;?></b></td>
</tr>
<tr>
<td style="width: 15%;">FAX:</td>
<td><b><?php echo $fax;?></b></td>
<td style="width: 16%;"></td>
<td><b></b></td>
</tr>
<tr>
<td style="width: 15%;">TRN:</td>
<td><b><?php echo $trn;?></b></td>
<td style="width: 15%;"></td>
<td><b></b></td>
</tr>
</table>
<br/>
<h3>Credit Note Details</h3>
<br/>
<table style="width: 100%;" border="0" cellspacing="0" cellpadding="2">
<tr>
<th style="width: 3%;">#</th>
<th style="width: 23%;">Description</th>
<th style="width: 14%;">Size</th>
<th style="width: 10%;">Qty</th>
<th style="width: 10%;">Price</th>
<th style="width: 10%;">Total</th>
</tr>

<?php
//             $sql="SELECT *,sum(quantity) AS sq FROM invoice_item where invoice_id='$inv' GROUP BY item,dn ORDER BY dn";
             $sql="SELECT * FROM credit_note_items where cr_id='$cdt' AND adjust != ''";
             $query=mysqli_query($conn,$sql);
             $sl=1;
             $subtotal=0;
             while($fetch=mysqli_fetch_array($query))
             {
                  $item=$fetch['item'];
                  $quantity=$fetch['quantity'];
                  $quantity=($quantity != NULL) ? $quantity : 0;
                  $adjust=$fetch['adjust'];
                  $adjust=($adjust != NULL) ? $adjust : 0;
                  $total=$fetch['amt'];
                  $total=($total != NULL) ? $total : 0;
                  
                  
                 $sql1="SELECT * FROM items where id='$item'";
                 $query1=mysqli_query($conn,$sql1);
                 while($fetch1=mysqli_fetch_array($query1))
                 {
                    $item1=$fetch1['items'];
                    $size1=$fetch1['dimension'];
                    $description=$fetch1['description'];
                 }
            if($total > 0) { 
 ?>

          <tr>
            <td align="center"><?php echo $sl;?></td>
            <?php $sl=$sl+1; ?>
            <td align="center"><?php echo $item1;?></td>
            <?php $size1 = str_replace(' ', '', $size1);?>
            <td align="center"><?php echo $size1;?></td>
            <td align="center"><?php echo $quantity;?></td>
            <td align="center"><?php echo $adjust;?></td>
            <td align="right"><?php echo custom_money_format('%!i', $total);?></td>
          </tr>
<?php
} }
?>
          <tr>
            <td colspan="5" align="right"><b>Price before VAT&nbsp;</b></td>
            <td colspan="1" align="right"><b><?php echo custom_money_format('%!i', $sub_total);?></b></td>
          </tr>
          <tr>
            <td colspan="5" align="right"><b>VAT&nbsp;</b></td>
            <td colspan="1" align="right"><b><?php echo custom_money_format('%!i', $vat);?></b></td>
          </tr>
         
          <tr>
            <td colspan="2" align="center"><b>Grand total</b></td>
            <td colspan="3" align="center"><b>
            <?php
            $inwords=ucwords(convert_number_to_words($grand_total));

               if(fmod($grand_total,1)==0)
               {
               echo $inwords." Dirhams Only";
               }
               else
               {
               echo str_replace("Point","Dirhams and",$inwords)." Fils Only";
               }
            ?>
            </b></td>
            <td colspan="1" align="right"><b><?php echo custom_money_format('%!i', $grand_total);?></b></td>
          </tr>
          
<!--          <tr>
            <td colspan="7" align="right"><b>Price after VAT&nbsp;</b></td>
            <td colspan="1" align="right"><b><?php // echo $grand_total;?></b></td>
          </tr>-->
</table>
<?php
$brv=18-$sl;
for($i=0;$i<$brv;$i++)
{
echo "<br/>";
}
?>

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
