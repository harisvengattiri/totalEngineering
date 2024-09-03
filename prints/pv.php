<?php include"../config.php";?>
<?php include"../functions/functions.php";?>
<?php 
$pv=$_GET["pv"]; 
?>
<title>Payment Voucher #<?php echo $pv;?></title>
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
<td align="center" style="width: 30%; border: 0px"><h2>PAYMENT VOUCHER</h2>
<h4>TRN 100061540900003</h4>
</td>
<td style="width: 35%; border: 0px"></td>
</tr>
</table>
<br/>

     <?php 
             $sql="SELECT * FROM `pv` where id = '$pv'";
             
             $query=mysqli_query($conn,$sql);
             while($fetch=mysqli_fetch_array($query))
             {
                $cat=$fetch['category'];
                    $sql_cat = "SELECT tag FROM `expense_categories` WHERE id='$cat'";
                    $query_cat = mysqli_query($conn,$sql_cat);
                    $result_cat = mysqli_fetch_array($query_cat);
                    $cat_name = $result_cat['tag'];
                $sub=$fetch['subcategory'];
                    $sql_sub_cat = "SELECT category FROM `expense_subcategories` WHERE id='$sub'";
                    $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
                    $result_sub_cat = mysqli_fetch_array($query_sub_cat);
                    $sub_cat_name = $result_sub_cat['category'];
                $voucher=$fetch['voucher'];
                $year = $fetch['year'];
                $grand = $fetch['grand'];
                $pmethod = $fetch['pmethod'];
                $date = $fetch['date'];
                $duedate = $fetch['duedate'];
                $checkno = $fetch['checkno'];
                $inward = $fetch['inward'];
                  $sql2="SELECT * FROM `expense_subcategories` where id='$inward'";
                  $query2=mysqli_query($conn,$sql2);
                  while($fetch2=mysqli_fetch_array($query2))
                  {
                      $bank = $fetch2['category'];
                  }
                  
             }
        ?>



<table style="width: 100%;" cellspacing="0" cellpadding="0">
<tr>
<td style="width: 15%;">Voucher No:</td>
<td><b>PV|<?php echo $year;?>|<?php echo sprintf('%04d',$voucher);?></b></td>
<td style="width: 15%;">Year:</td>
<td><b>20<?php echo $year;?></b></td>
</tr>
<tr>
<td style="width: 15%;">Supplier:</td>
<td><b><?php echo $sub_cat_name;?></b></td>
<td style="width: 15%;">Account:</td>
<td><b><?php echo $cat_name;?></b></td>
</tr>
<tr>
<td style="width: 15%;">Amount:</td>
<td><b><?php echo $grand;?></b></td>
<td style="width: 15%;">Payment Method:</td>
<td><b><?php echo $pmethod;?></b></td>
</tr>
<tr>
<td style="width: 15%;">Payment Date:</td>
<td><b><?php echo $date;?></b></td>
<td style="width: 15%;">Cheque Date:</td>
<td><b><?php echo $duedate;?></b></td>
</tr>
<tr>
<td style="width: 15%;">Bank:</td>
<td><b><?php echo $bank;?></b></td>
<td style="width: 15%;">Cheque No:</td>
<td><b><?php echo $checkno;?></b></td>
</tr>

</table>
<br/>
<br/>

<?php
$brv=18;
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
