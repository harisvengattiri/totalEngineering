<?php include"../config.php";?>
<?php include"../functions/functions.php";?>
<?php 
$inv=$_GET["inv"]; 
?>
<title>Invoice #<?php echo $inv;?></title>
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
<td align="center" style="width: 30%; border: 0px"><h2>TAX INVOICE</h2>
<h4>TRN 100061540900003</h4>
</td>
<td style="width: 35%; border: 0px"></td>
</tr>
</table>
<br/>

     <?php 
             $sql="SELECT * FROM invoice where id='$inv'";
             
             $query=mysqli_query($conn,$sql);
             while($fetch=mysqli_fetch_array($query))
             {
                  $id=$fetch['id'];
                  $date=$fetch['date'];
                  $customer=$fetch['customer'];
                  $site=$fetch['site'];
                    $sqlsite="SELECT p_name from customer_site where id='$site'";
                    $querysite=mysqli_query($conn,$sqlsite);
                    $fetchsite=mysqli_fetch_array($querysite);
                    $site1=$fetchsite['p_name'];
                  $lpo=$fetch['lpo'];
                  $or=$fetch['o_r'];
                  
                  $sub_total=$fetch['total'];
                  $vat=$fetch['vat'];
                  $grand_total=$fetch['grand'];
                  $transportation=$fetch['transport'];
                  
                  $bank_details=$fetch['bank_details'];
                  
                  $print=$fetch['prints2'];
                  
                  
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
                       $trn=$fetch2['tin'];
                  }
                  
             }
            $print=$print+1;
            $sql0="UPDATE invoice SET prints2=$print where id='$inv'";
            $query0=mysqli_query($conn,$sql0);
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
<td style="width: 15%;">Currency:</td>
<td><b>Dirhams-AED</b></td>
</tr>
</table>
<br/>
<h3>Invoice Details</h3>
<br/>
<table style="width: 100%;" border="0" cellspacing="0" cellpadding="2">
<tr>
<th style="width: 3%;">#</th>
<th style="width: 14%;">Delivery No.</th>
<th style="width: 14%;">Date</th>
<th style="width: 26%;">Description</th>
<th style="width: 14%;">Size</th>
<th style="width: 10%;">Qty</th>
<th style="width: 9%;">Unit Price</th>
<th style="width: 10%;">Price</th>
<th style="width: 10%;">VAT 5%</th>
<th style="width: 10%;">Total AED</th>
</tr>

<?php
             $sql="SELECT *,sum(quantity) AS sq FROM invoice_item where invoice_id='$inv' GROUP BY item,dn ORDER BY dn";
             $query=mysqli_query($conn,$sql);
             $sl=1;
             $subtotal=0;
             while($fetch=mysqli_fetch_array($query))
             {
                  $item=$fetch['item'];
                  $dn=$fetch['dn'];
                  $sqlddate="SELECT date FROM delivery_note where id='$dn'";
                  $queryddate=mysqli_query($conn,$sqlddate);
                  $fetchddate=mysqli_fetch_array($queryddate);
                  $date=$fetchddate['date'];
                
                  $qty=$fetch['sq'];
                  $price=$fetch['unit'];
                  $total1=$fetch['total'];
                  $total=$qty*$price;
                  $pdate=$fetch['pdate'];
                  $coc=$fetch['coc'];
                  $quantity=$fetch['thisquantity'];
                  
                 $sql1="SELECT * FROM items where id='$item'";
                 $query1=mysqli_query($conn,$sql1);
                 while($fetch1=mysqli_fetch_array($query1))
                 {
                    $item1=$fetch1['items'];
                    $size1=$fetch1['dimension'];
                    $description=$fetch1['description'];
                 }
             
 ?>

          <tr>
            <td align="center"><?php echo $sl;?></td>
            <?php $sl=$sl+1; ?>
            <td align="center">DN|<?php echo sprintf('%06d',$dn);?></td>
            <td align="center"><?php echo $date;?></td>
            <td align="center"><?php echo $item1;?></td>
            <?php $size1 = str_replace(' ', '', $size1);?>
            <td align="center"><?php echo $size1;?></td>
            <td align="center"><?php echo $qty;?></td>
            <td align="center"><?php echo $price;?></td>
            <td align="right"><?php echo custom_money_format('%!i', $total);?></td>
            <td align="right"><?php echo custom_money_format('%!i', $total*0.05);?></td>
            <td align="right"><?php echo custom_money_format('%!i', $total*1.05);?></td>
          </tr>
<?php
}
?>
          
          <tr>
            <td colspan="7" align="right"><b>Transportation&nbsp;</b></td>
            <td colspan="1" align="center"><b>
            <td colspan="1" align="center"><b>
            <td colspan="1" align="right"><b><?php echo custom_money_format('%!i', $transportation);?></b></td>
          </tr>
          
          <tr>
            <td colspan="2" align="center"><b>Grand total</b></td>
            <td colspan="5" align="center"><b>
            <?php
            $inwords=ucwords(convert_number_to_words($grand_total));

               if(fmod($grand_total,1)==0)
               {
                //   echo $inwords." Dirhams Only";
                echo 'AED '. $inwords." Only";
               }
               else
               {
                //   echo str_replace("Point","Dirhams and",$inwords)." Fils Only";
            
                $dirham = preg_replace('/ Point.*/', '', $inwords);
                $filsmod1 = fmod($grand_total,1);
                $filsmod = number_format((float)$filsmod1, 2, '.', '');
                $fils = str_replace('0.', '', $filsmod);
                
                echo 'AED '. $dirham.' And '.$fils.'/100';
               }
            ?>
            </b></td>
            
            <td colspan="1" align="right"><b><?php echo custom_money_format('%!i', $sub_total);?></b></td>
            <td colspan="1" align="right"><b><?php echo custom_money_format('%!i', $vat);?></b></td>
            <td colspan="1" align="right"><b><?php echo custom_money_format('%!i', $grand_total);?></b></td>
          </tr>
          
<!--          <tr>
            <td colspan="7" align="right"><b>Price after VAT&nbsp;</b></td>
            <td colspan="1" align="right"><b><?php echo $grand_total;?></b></td>
          </tr>-->
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
