<?php include "../config.php";?>
<?php include "../functions/functions.php";?>
<?php error_reporting(0); ?>
<?php 
$sono=$_GET["sono"]; 
?>
<title>Mancon Purchase Order #<?php echo $sono;?></title>
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
<td style="width: 25%; border: 0px"></td>
<td align="center" style="width: 50%; border: 0px"><h2>CUSTOMER PURCHASE ORDER</h2></td>
<td style="width: 25%; border: 0px"></td>
</tr>
</table>
<br/>
<?php 
        
        
             $sql="SELECT * FROM sales_order where id='$sono'";
             
             $query=mysqli_query($conn,$sql);
             while($fetch=mysqli_fetch_array($query))
             {
                    $id=$fetch['id'];
                    $date=$fetch['date'];
                    $lpo=$fetch['lpo'];
                    $or=$fetch['order_referance'];
                    $customer=$fetch['customer'];
                    $site=$fetch['site'];
                    $rep=$fetch['salesrep'];
                    $prep=$fetch['prep'];
                    $qtn=$fetch['qtn'];
                    
                    $sqlrep="SELECT name FROM customers where id='$rep'";
                    $queryrep=mysqli_query($conn,$sqlrep);
                    $fetchrep=mysqli_fetch_array($queryrep);
                    $salesman=$fetchrep['name'];
                    
                    $sub_total=$fetch['sub_total'];
                    $sub_total = ($sub_total != NULL) ? $sub_total : 0;
                    $vat=$fetch['vat'];
                    $vat = ($vat != NULL) ? $vat : 0;
                    $transport=$fetch['transport'];
                    if($transport == NULL) {$transport = 0;}
                    $grand_total = $fetch['grand_total'];
                    $grand_total = ($grand_total != NULL) ? $grand_total : 0;
                  
            $sql1="SELECT * FROM customer_site where customer='$customer' AND id='$site'";
            $query1=mysqli_query($conn,$sql1);
            while($fetch1=mysqli_fetch_array($query1))
                  {
                    $siteno=$fetch1['site'];
                    $site1=$fetch1['p_name'];
                    $pno=$fetch1['p_no'];
                    $loc1=$fetch1['location'];
                    $sqlloc="SELECT * FROM fair where id='$loc1'";
                    $queryloc=mysqli_query($conn,$sqlloc);
                    while($fetchloc=mysqli_fetch_array($queryloc))
                    {
                     $loc=$fetchloc['location'];
                    }
                    
                    $per=$fetch1['contact_per'];
                    $no=$fetch1['contact_no'];
                    $permit=$fetch1['permit'];
                    $no2=$fetch1['contact_no1'];
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
                    $tin=$fetch2['tin'];
                    $opening=$fetch2['op_bal'];
                    
                    $cust_type=$fetch2['cust_type'];
                    $period=$fetch2['period'];
                    $p_mode=$fetch2['p_mode'];
                  }
            
             }
        ?>

<table style="width: 100%;" cellspacing="0" cellpadding="0">
<tr>
<td style="width: 20%;">Customer No:</td>
<td><b><?php echo sprintf("%03d",$id1);?></b></td>
</tr>
<tr>
<td style="width: 20%;">Customer Name:</td>
<td><b><?php echo $cust;?></b></td>
</tr>
<tr>
<td style="width: 20%;">Address</td>
<td><b><?php echo $address;?></b></td>
</tr>
<tr>
<td style="width: 20%;">Phone:</td>
<td><b><?php echo $phone;?></b></td>
</tr>
<tr>
<td style="width: 20%;">Fax:</td>
<td><b><?php echo $fax;?></b></td>
</tr>
<tr>
<td style="width: 20%;">VAT:</td>
<td><b><?php echo $tin;?></b></td>
</tr>
</table><br/>

<table style="width: 100%;" cellspacing="0" cellpadding="0">
<tr>
<td style="width: 15%;">Purchase Order: <b><?php echo $or;?></b></td>
<td style="width: 15%;">Site No: <b><?php echo $siteno;?></b></td>
<?php
  $today=date("d/m/Y");
?>
<td style="width: 15%;">Date: <b><?php echo $today;?></b></td>
</tr>
</table><br/>


<table style="width: 100%;" cellspacing="0" cellpadding="0">
<tr>
<td style="width: 15%;">Project No:</td>
<td><b><?php echo $pno;?></b></td>
<td style="width: 15%;">Order Date:</td>
<td><b><?php echo $date;?></b></td>
</tr>
<tr>
<td style="width: 15%;">Project Name:</td>
<td><b><?php echo $site1;?></b></td>
<td style="width: 15%;">LPO No:</td>
<td><b><?php echo $lpo;?></b></td>
</tr>
<tr>
<td style="width: 15%;">Plot Number:</td>
<td><b><?php echo $permit;?></b></td>   
<td style="width: 15%;">Sales Man:</td>
<td><b><?php echo $salesman;?></b></td>
</tr>
<tr>
<td style="width: 15%;">Location:</td>
<td><b><?php echo $loc;?></b></td>
<td style="width: 15%;">Payment Terms</td>
<td><b><?php echo $cust_type.' '.$period.' Days';?></b></td>
</tr>
<tr>
<td style="width: 15%;">Contact Name:</td>
<td><b><?php echo $per;?></b></td>
<td style="width: 15%;">Payment Mode</td>
<td><b><?php echo $p_mode;?></b></td>
</tr>
<tr>
<td style="width: 15%;">Contact Phone:</td>
<td><b><?php echo $no; if($no2 != NULL) { echo ','. $no2;}?></b></td>
<td style="width: 15%;">Qtn No:</td>
<td><b>QTN|<?php echo $qtn;?></b></td>
</tr>
</table>
<br/>
<h3>Delivery Details</h3>
<br/>
<table style="width: 100%;" border="0" cellspacing="0" cellpadding="2">
<tr>
<th style="width: 2%;">#</th>
<th style="width: 20%;">Product No.</th>
<th style="width: 28%;">Description</th>
<th style="width: 4%;">Bndl.</th>
<th style="width: 10%;">Qty</th>
<th style="width: 10%;">UOM</th>
<th style="width: 13%;">Unit Price</th>
<th style="width: 13%;">Total</th>
</tr>

<?php
             $sql="SELECT * FROM order_item where item_id='$sono'";
             $query=mysqli_query($conn,$sql);
             $total_price=0;
             $sl = 0;
             while($fetch=mysqli_fetch_array($query))
             {
                  $sl++;
                  $item=$fetch['item'];
                  $quantity=$fetch['quantity'];
                  $quantity=($quantity != NULL) ? $quantity : 0;
                  $unit=$fetch['unit'];
                  $unit=($unit != NULL) ? $unit : 0;
                  $total=$fetch['total'];
                  $total=($total != NULL) ? $total : 0;
                  
                 $sql1="SELECT items,dimension,description,unit,bundle FROM items where id='$item'"; 
                 $query1=mysqli_query($conn,$sql1);
                 while($fetch1=mysqli_fetch_array($query1))
                 {
                    $item1=$fetch1['items'];
                    $size=$fetch1['dimension'];
                    $prdno=$fetch1['description'];
                    $unit1=$fetch1['unit'];
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
            <td align="center"><?php echo $quantity;?></td>
            <td align="center"><?php echo $unit1;?></td>
            <td align="center"><?php echo $unit;?></td>
            <td align="right"><?php echo $total;?></td>
          </tr>
<?php
}
?>
          <tr>
            <td colspan="7" align="right"><b>Price before VAT&nbsp;</b></td>
            <td colspan="1" align="right"><b><?php echo $sub_total;?></b></td>
          </tr>
          <tr>
            <td colspan="7" align="right"><b>Transportation&nbsp;</b></td>
            <td colspan="1" align="right"><b><?php echo $transport;?></b></td>
          </tr>
          <tr>
            <td colspan="7" align="right"><b>VAT&nbsp;</b></td>
            <td colspan="1" align="right"><b><?php echo $vat;?></b></td>
          </tr>
          <tr>
            <td colspan="7" align="right"><b>Total including VAT&nbsp;</b></td>
            <td colspan="1" align="right"><b><?php echo $grand_total;?></b></td>
          </tr>
</table>


        <?php
            $sql_credit = "SELECT * FROM credit_application WHERE company='$customer'";
            $query_credit=mysqli_query($conn,$sql_credit);
            $fetch_credit=mysqli_fetch_array($query_credit);

            // if(isset($fetch_credit['credit']) != NULL) { $crdt1 = $fetch_credit['credit']; } else { $crdt1 = 0; }
            // if(isset($fetch_credit['credit1']) != NULL) { $crdt2 = $fetch_credit['credit1']; } else { $crdt2 = 0; }
            // if(isset($fetch_credit['period'])) { $crdt_period = $fetch_credit['period']; } else { $crdt_period = 'NILL'; }

            $crdt1 = $fetch_credit['credit'];
            $crdt1 = ($crdt1 != NULL) ? $crdt1 : 0;
            $crdt2 = $fetch_credit['credit1'];
            $crdt2 = ($crdt2 != NULL) ? $crdt2 : 0;
            $crdt_period = $fetch_credit['period'];
            $crdt_period = ($crdt_period != NULL) ? $crdt_period : 0;

            $crdt = $crdt1 + $crdt2;
            
            $sqlrpt="SELECT grand,duedate from reciept where customer=$customer AND status!='Cleared'";
            $queryrpt=mysqli_query($conn,$sqlrpt);
            if (mysqli_num_rows($queryrpt) > 0) 
		    {
		    $sl=$sl+3;
		    ?>
		    <br>
            <table style="width: 100%;" border="0" cellspacing="0" cellpadding="2">
            <tr>
            <th style="width: 20%;">Credit Period</th>
            <th style="width: 20%;">Credit Limit</th>
            <th style="width: 20%;">Due Date</th>
            <th style="width: 20%;">Unclear Cheque</th>
            </tr>
		    <?php    
		        while($fetchrpt=mysqli_fetch_array($queryrpt))
		        {
		        $sl++;
		        ?>
		        <tr>
                    <td align="center"><?php echo $crdt_period;?></td>
                    <td align="right"><?php echo $crdt;?></td>
                    <td align="center"><?php echo $fetchrpt['duedate'];?></td>
                    <td align="right"><?php echo $fetchrpt['grand'];?></td>
                </tr>
		        <?php } ?>
             </table>   
		   <?php } ?> 



<?php
$lines=10;
$brv=$lines-$sl;
for($i=0;$i<$brv;$i++)
{
echo "<br/>";
}
?>

<table style="width: 100%; border:0px" cellspacing="0" cellpadding="0">
<tr style="border:0px" >
<td style="width: 60%; border:0px"><br/><br/><br/><br/></td>
<td style="width: 13%; border:0px"><br/><br/>Prepared By:<br/><br/></td>
<td style="border:0px" ><br/><br/><b><?php if($prep != NULL) { echo $prep; } else { echo $_GET['open']; } ?></b><br/><br/></td>
</tr>
<tr style="border:0px" >
<td style="width: 60%; border:0px"><br/><br/><br/><br/></td>
<td style="width: 13%; border:0px"><br/><br/>Signature:<br/><br/></td>
<td style="border:0px" ><br/><br/>....................................<br/><br/></td>
</tr>
</table>
</body>
