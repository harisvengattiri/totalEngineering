<?php include"../config.php";?>
<?php include"../functions/functions.php";?>
<?php 
$jv=$_GET["jv"]; 
?>
<title>Journal Voucher #<?php echo $jv;?></title>
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
<td align="center" style="width: 30%; border: 0px"><h2>JOURNAL VOUCHER</h2>
<h4>TRN 100061540900003</h4>
</td>
<td style="width: 35%; border: 0px"></td>
</tr>
</table>
<br/>

     <?php 
             $sql="SELECT * FROM `jv` where id = '$jv'";
             
             $query=mysqli_query($conn,$sql);
             while($fetch=mysqli_fetch_array($query))
             {
                $cat=$fetch['crdt_cat'];
                    $sql_cat = "SELECT tag FROM `expense_categories` WHERE id='$cat'";
                    $query_cat = mysqli_query($conn,$sql_cat);
                    $result_cat = mysqli_fetch_array($query_cat);
                    $cat_name = $result_cat['tag'];
                $sub=$fetch['crdt_sub'];
                    $sql_sub_cat = "SELECT category FROM `expense_subcategories` WHERE id='$sub'";
                    $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
                    $result_sub_cat = mysqli_fetch_array($query_sub_cat);
                    $sub_cat_name = $result_sub_cat['category'];
                    
                $cat1=$fetch['debt_cat'];
                    $sql_cat1 = "SELECT tag FROM `expense_categories` WHERE id='$cat1'";
                    $query_cat1 = mysqli_query($conn,$sql_cat1);
                    $result_cat1 = mysqli_fetch_array($query_cat1);
                    $cat_name1 = $result_cat1['tag'];
                $sub1=$fetch['debt_sub'];
                    $sql_sub_cat1 = "SELECT category FROM `expense_subcategories` WHERE id='$sub1'";
                    $query_sub_cat1 = mysqli_query($conn,$sql_sub_cat1);
                    $result_sub_cat1 = mysqli_fetch_array($query_sub_cat1);
                    $sub_cat_name1 = $result_sub_cat1['category'];    
                 
                $inv = $fetch['inv'];  
                $voucher = $fetch['voucher'];
                $year = $fetch['year'];
                $note = $fetch['note'];
                $date = $fetch['date'];
                $crdt_amt = $fetch['crdt_amount'];
                $crdt_vat = $fetch['crdt_vat'];
                $crdt_total = $fetch['crdt_total'];
                $debt_amt = $fetch['debt_amount'];
                $debt_vat = $fetch['debt_vat'];
                $debt_total = $fetch['debt_total'];
             }
        ?>



<table style="width: 100%;" cellspacing="0" cellpadding="0">
<tr>
<td style="width: 15%;">Voucher No:</td>
<td style="width: 35%;"><b>JV|<?php echo $year;?>|<?php echo sprintf('%04d',$voucher);?></b></td>
<td style="width: 15%;">Year:</td>
<td><b>20<?php echo $year;?></b></td>
</tr>
<tr>
<td style="width: 15%;">Invoice No:</td>
<td style="width: 35%;"><b><?php echo $inv;?></b></td>
<td style="width: 15%;">Due Date:</td>
<td><b><b><?php echo $date;?></b></b></td>
</tr>
<tr>
<td style="width: 15%;">Note:</td>
<td colspan="3"><b><?php echo $note;?></b></td>
</tr>
</table>
<br>

<table style="width: 100%;" cellspacing="0" cellpadding="0">
<tr>
<td style="width: 15%;">Credit Account:</td>
<td style="width: 35%;"><b><?php echo $cat_name;?></b></td>
<td style="width: 15%;">Sub Account:</td>
<td><b><?php echo $sub_cat_name;?></b></td>
</tr>
<tr>
<td style="width: 15%;">Credit Amount:</td>
<td><b><?php echo $crdt_amt;?></b></td>
<td style="width: 15%;">VAT:</td>
<td><b><?php echo $crdt_vat;?></b></td>
</tr>
<tr>
<td style="width: 15%;">Credit Total:</td>
<td><b><?php echo $crdt_total;?></b></td>
</tr>
</table>
<br>

<table style="width: 100%;" cellspacing="0" cellpadding="0">
<tr>
<td style="width: 15%;">Debit Account:</td>
<td style="width: 35%;"><b><?php echo $cat_name1;?></b></td>
<td style="width: 15%;">Sub Account:</td>
<td><b><?php echo $sub_cat_name1;?></b></td>
</tr>
<tr>
<td style="width: 15%;">Debit Amount:</td>
<td><b><?php echo $debt_amt;?></b></td>
<td style="width: 15%;">VAT:</td>
<td><b><?php echo $debt_vat;?></b></td>
</tr>
<tr>
<td style="width: 15%;">Debit Total:</td>
<td><b><?php echo $debt_total;?></b></td>
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
