<?php include "../config.php";?>
<?php include "../functions/functions.php";?>
<?php 
$rv=$_GET["rv"]; 
?>
<title>Receipt Voucher #<?php echo $rv;?></title>
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
<td align="center" style="width: 30%; border: 0px"><h2>RECEIPT VOUCHER</h2>
<h4>TRN 100061540900003</h4>
</td>
<td style="width: 35%; border: 0px"></td>
</tr>
</table>
<br/>

     <?php 
             $sql="SELECT * FROM `reciept` WHERE `id`='$rv'";
             $query=mysqli_query($conn,$sql);
             while($fetch=mysqli_fetch_array($query))
             {
                  $id=$fetch['id'];
                  $date=$fetch['pdate'];
                  $customer=$fetch['customer'];
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
                  $duedate=$fetch['duedate'];
                  $pmethod=$fetch['pmethod'];
                  $ref=$fetch['ref'];
                  
                  $sub_total=$fetch['amount'];
                  $sub_total = ($sub_total != NULL) ? $sub_total : 0;
                  $discount=$fetch['discount'];
                  $discount = ($discount != NULL) ? $discount : 0;
                  $grand_total=$fetch['grand'];
                  $grand_total = ($grand_total != NULL) ? $grand_total : 0;
             }
        ?>



<table style="width: 100%;" cellspacing="0" cellpadding="0">
<tr>
<td style="width: 20%;">Customer No:</td>
<td style="width: 35%;"><b>CST <?php echo sprintf('%04d',$id1);?></b></td>
<td style="width: 20%;">Receipt No:</td>
<td style="width: 25%;"><b>AR|<?php echo sprintf('%06d',$id);?></b></td>
</tr>
<tr>
<td>Customer Name:</td>
<td><b><?php echo $cust;?></b></td>
<td>Payment Date:</td>
<td><b><?php echo $date;?></b></td>
</tr>
<tr>
<td>Address:</td>
<td><b><?php echo $address;?></b></td>
<td>Due Date:</td>
<td><b><?php echo $duedate;?></b></td>
</tr>
<tr>
<td>Phone:</td>
<td><b><?php echo $phone;?></b></td>
<td>Payment Method:</td>
<td><b><?php echo $pmethod;?></b></td>
</tr>
<tr>
<td>FAX:</td>
<td><b><?php echo $fax;?></b></td>
<td>Reference No#</td>
<td><b><?php echo $ref;?></b></td>
</tr>
<tr>
<td>TRN:</td>
<td><b><?php echo $trn;?></b></td>
<td></td>
<td><b></b></td>
</tr>
</table>

<br/>

<table style="width: 100%;" border="0" cellspacing="0" cellpadding="2">
          <!--<tr>-->
          <!--  <td colspan="5" align="right"><b>Total Amount&nbsp;</b></td>-->
          <!--  <td colspan="1" align="right"><b><?php // echo custom_money_format('%!i', $sub_total);?></b></td>-->
          <!--</tr>-->
          <!--<tr>-->
          <!--  <td colspan="5" align="right"><b>Total Discount&nbsp;</b></td>-->
          <!--  <td colspan="1" align="right"><b><?php // echo custom_money_format('%!i', $discount);?></b></td>-->
          <!--</tr>-->
          <tr>
            <td colspan="2" align="center"><b>Grand total</b></td>
            <td colspan="3" align="center"><b>
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
            <td colspan="1" align="right"><b><?php echo custom_money_format('%!i', $grand_total);?></b></td>
          </tr>
    </table>
    <br/>
    <h3>Invoice Details</h3>
    <br/>
    <table style="width: 100%;" border="0" cellspacing="0" cellpadding="2">
    
<tr>
<th>#</th>
<th>Invoice No.</th>
<th>Date</th>
<th>Amount</th>
<th>Discount</th>
<th>Total AED</th>
</tr>

<?php
            $sql="SELECT * FROM `reciept_invoice` WHERE `reciept_id`='$rv'";
            $query=mysqli_query($conn,$sql);
            $sl=1;
            $subtotal=0;
            while($fetch=mysqli_fetch_array($query))
            {
                $invoice = $fetch['invoice'];
                $date = $fetch['date'];
                $amt = $fetch['amount'];
                $adj = $fetch['adjust'];
                $total = $fetch['total'];
             
 ?>

          <tr>
            <td align="center"><?php echo $sl;?></td>
            <?php $sl=$sl+1; ?>
            <td align="center">INV|<?php echo sprintf('%06d',$invoice);?></td>
            <td align="center"><?php echo $date;?></td>
            <td align="center"><?php echo $amt;?></td>
            <td align="center"><?php echo $adj;?></td>
            <td align="right"><?php echo custom_money_format('%!i', $total);?></td>
          </tr>
<?php
}
?>
          
<!--          <tr>
            <td colspan="7" align="right"><b>Price after VAT&nbsp;</b></td>
            <td colspan="1" align="right"><b><?php echo $grand_total;?></b></td>
          </tr>-->
</table>
<?php
$brv=10-$sl;
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
