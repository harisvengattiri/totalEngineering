<?php include"../config.php";?>
<?php 
$qno=$_GET["mnt"]; 
?>
<title>Oasis Quotation #
	<?php echo $qno;?>
</title>
<style>

@page {
  margin: 12px 40px 12px 40px;
  width: 21cm;
  height: 29.7cm; 
}

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
	<img width="100%" src="header.jpg">
		<table style="width: 100%; border:0px">
			<tr style="border:0px" >
				<td style="width: 40%; border: 0px"></td>
				<td style="width: 20%; border: 0px">
					<h2>
						<u>QUOTATION</u>
					</h2>
				</td>
				<td style="width: 40%; border: 0px"></td>
			</tr>
		</table>
		<br/>
		<?php
$sql = "SELECT * FROM quotations where qno=$qno";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) 
{
while($row = mysqli_fetch_assoc($result)) {
$qno=$row["qno"];
$wrno=$row["wrno"];
$customer=$row["customer"];
$date=$row["date"];
$dated = str_replace('/', '-', $date);
$date1 = date("d", strtotime($dated));
$date2 = date("S", strtotime($dated));
$date3 = date("F Y", strtotime($dated));
$proname=$row["proname"];
$attn=$row["attn"];
$from=$row["from"];
$email=$row["email"];
$phone=$row["phone"];
$contact=$row["contact"];
$subject=$row["subject"];
$pterms=$row["pterms"];
}
}
?>
		<table style="width: 100%; border: 2px solid black;" cellspacing="0" cellpadding="0">
			<tr>
				<td style="width: 10%; border: 2px solid black;">To:</td>
				<td style="width: 50%; border: 2px solid black;">
					<?php echo $customer;?>
				</td>
				<td style="width: 10%; border: 2px solid black;">From:</td>
				<td style="width: 30%; border: 2px solid black;">
					<?php echo $from;?>
				</td>
			</tr>
			<tr>
				<td style="width: 10%; border: 2px solid black;">Attn:</td>
				<td style="width: 50%; border: 2px solid black;">
					<?php echo $attn;?>
				</td>
				<td style="width: 10%; border: 2px solid black;">Ref.#</td>
				<td style="width: 30%; border: 2px solid black;">OASS/
					<?php echo $qno;?>/Wr. 
					<?php echo $wrno;?>
				</td>
			</tr>
			<tr>
				<td style="width: 10%; border: 2px solid black;">Tel/Email</td>
				<td style="width: 50%; border: 2px solid black;">
					<?php echo $phone;?> / 
					<?php echo $email;?>
				</td>
				<td style="width: 10%; border: 2px solid black;">Date:</td>
				<td style="width: 30%; border: 2px solid black;">
					<?php echo $date1;?>
					<sup>
						<?php echo $date2;?>
					</sup>
					<?php echo $date3;?>
				</td>
			</tr>
			<tr>
				<td style="width: 10%; border: 2px solid black;">Project</td>
				<td style="width: 50%; border: 2px solid black;"></td>
				<td style="width: 10%; border: 2px solid black;">TRN No.</td>
				<td style="width: 50%; border: 2px solid black;">100367611900003</td>
			</tr>
			<tr>
				<td style="width: 10%; border: 2px solid black;">Subject</td>
				<td colspan="3" style="width: 90%; border: 2px solid black;">
					<?php echo $subject;?>
				</td>
			</tr>
		</table>
		<p align="justify">
			<br/>Dear Sir,
			<br/>
			<br/>
This is with reference to your inquiry of the above captioned subject; we are pleased to submit our best
offer as follows. We hope you will find our proposal competitive in terms of quality and pricing.
		</p>
		<br/>
		<table style="width: 100%;" border="0" cellspacing="0" cellpadding="2">
			<tr>
				<th style="width: 8%;">Item</th>
				<th style="width: 60%;">Description</th>
				<th style="width: 10%;">Qty.</th>
				<th style="width: 10%;">U. Price</th>
				<th style="width: 12%;">Total AED</th>
			</tr>
			<?php
$qno=$_GET["mnt"];
$sql = "SELECT * FROM quotation_items where qno=$qno";
$result = mysqli_query($conn, $sql);
$slno=0;
$total=0;
if (mysqli_num_rows($result) > 0) 
{
while($row = mysqli_fetch_assoc($result)) {
$id=$row["id"];
$material=$row["material"];
$description=$row["description"];
$price=$row["price"];
$qty=$row["qty"];
$subtotal=$price*$qty;
$total=$total+$subtotal;
$vat=$total*0.05;
$payable=$total*1.05;
$slno=$slno+1;
?>
			<tr>
				<td align="center">
					<?php echo $slno;?>
				</td>
				<td>
					<?php echo $material.$description;?>
				</td>
				<td align="right">
					<?php echo $qty;?> Nos.
				</td>
				<td align="right">
					<?php echo custom_money_format("%!i", $price);?>
				</td>
				<td align="right">
					<?php echo custom_money_format("%!i", $subtotal);?>
				</td>
			</tr>
			<?php
}}
            include "../functions/to_words.php";
?>
			<tr>
				<td colspan="3" align="center"></td>
				<td colspan="1" align="center">
					<b>Total</b>
				</td>
				<td colspan="1" align="right">
					<b>
						<?php echo custom_money_format("%!i", $total);?>
					</b>
				</td>
			</tr>
			<tr>
				<td colspan="3" align="center"></td>
				<td colspan="1" align="center">
					<b>VAT (5%)</b>
				</td>
				<td colspan="1" align="right">
					<b>
						<?php echo custom_money_format("%!i", $vat);?>
					</b>
				</td>
			</tr>
			<tr>
				<td colspan="1" align="center">
					<b>Payable</b>
				</td>
				<td colspan="3" align="center">
					<b>
						<?php 
            $ttlwrds=convert_number_to_words($payable); 
            echo ucfirst($ttlwrds)." AED Only";
            ?>
					</b>
				</td>
				<td colspan="1" align="right">
					<b>
						<?php echo custom_money_format("%!i", $payable);?>
					</b>
				</td>
			</tr>
		</table>
		<p align="justify">
			<?php echo $pterms;?>
We hope that the information contained herein meet with your requirement and we can assure you of quality
workmanship and materials and trust we may be favored with your valued order.

			<br/>
			<br/>Thanking you
			<br/>
For 
			<b>OASIS AUTOMATIC DOOR & SECURITY SYSTEM</b>
		</p>
		<br/>
		<table style="border:0px" >
			<tr style="border:0px" >
				<td style="border:0px" >
					<table style="width: 60%;" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<th style="width: 30%;">Prepared by</th>
							<th style="width: 30%;">Sales Manager</th>
						</tr>
						<tr>
							<td align="center">Current User</td>
							<td align="center">Mr.Sidhik Hamza</td>
						</tr>
						<tr>
							<td align="center">Current User Contact</td>
							<td align="center">+971-50 584 5301</td>
						</tr>
					</table>
				</td>
				<td style="border:0px" >
					<img width="25%" src="seal.jpg">
					</td>
				</tr>
			</table>
		</body>

