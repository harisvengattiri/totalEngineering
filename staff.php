<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
	<div class="row">
	<?php
	$sql = "SELECT * FROM customers where type='staff' order by name";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
        ?>
				<div class="col-xs-12 col-sm-12 col-md-3">
				  <div class="box text-center">
				    <div class="p-a-md">
				    	<p><span class="label rounded label-lg success pos-rlt m-r-xs"><b class="arrow bottom b-success pull-in"></b><?php echo substr($row["name"],0,1);?></span></p>
				    	<a href="#" class="text-md block"><?php echo $row["name"];?></a>
				    	<p><small><?php echo $row["phone"];?></small></p>
				    	<a href="<?php echo $baseurl;?>/works?id=<?php echo $row["id"];?>" class="btn btn-sm btn-outline rounded b-info">Work Report</a>
				    	<a href="<?php echo $baseurl;?>/staff_cash_flow?staff=<?php echo $row["id"];?>" class="btn btn-sm btn-outline rounded b-danger">Cash Flow</a>
				    </div>
				    <div class="row row-col no-gutter b-t">
					  <div class="col-xs-12 b-r">


<?php
$staff=$row["id"];
$sql2 = "SELECT sum(amount) as itfcredit FROM internal_transfers where toid='$staff' ";
$result2 = mysqli_query($conn, $sql2);
if (mysqli_num_rows($result2) > 0) 
{
while($row2 = mysqli_fetch_assoc($result2)) 
{
$itfcredit=$row2["itfcredit"];
}}


$sql2 = "SELECT sum(amount) as pymcredit FROM payments where reciever='$staff'and method='cash' ";
$result2 = mysqli_query($conn, $sql2);
if (mysqli_num_rows($result2) > 0) 
{
while($row2 = mysqli_fetch_assoc($result2)) 
{
$pymcredit=$row2["pymcredit"];
}}


$sql2 = "SELECT sum(paid) as wricredit FROM work_invoices where (collector='$staff' and method='cash' and paid>0)";
$result2 = mysqli_query($conn, $sql2);
if (mysqli_num_rows($result2) > 0) 
{
while($row2 = mysqli_fetch_assoc($result2)) 
{
$wricredit=$row2["wricredit"];
}}


$sql2 = "SELECT sum(amount) as itfdebit FROM internal_transfers where fromid='$staff' ";
$result2 = mysqli_query($conn, $sql2);
if (mysqli_num_rows($result2) > 0) 
{
while($row2 = mysqli_fetch_assoc($result2)) 
{
$itfdebit=$row2["itfdebit"];
}}


$sql2 = "SELECT sum(amount) as prcdebit FROM purchases where purchaser='$staff'and method='cash' ";
$result2 = mysqli_query($conn, $sql2);
if (mysqli_num_rows($result2) > 0) 
{
while($row2 = mysqli_fetch_assoc($result2)) 
{
$prcdebit=$row2["prcdebit"];
}}


$sql2 = "SELECT sum(amount) as oxpdebit FROM office_expenses where purchaser='$staff'and method='cash' ";
$result2 = mysqli_query($conn, $sql2);
if (mysqli_num_rows($result2) > 0) 
{
while($row2 = mysqli_fetch_assoc($result2)) 
{
$oxpdebit=$row2["oxpdebit"];
}}


$sql2 = "SELECT sum(amount) as vxpdebit FROM vehicle_expenses where purchaser='$staff'and method='cash' ";
$result2 = mysqli_query($conn, $sql2);
if (mysqli_num_rows($result2) > 0) 
{
while($row2 = mysqli_fetch_assoc($result2)) 
{
$vxpdebit=$row2["vxpdebit"];
}}



$totcredit=0+$itfcredit+$pymcredit+$wricredit;
$totdebit=0+$itfdebit+$prcdebit+$oxpdebit+$vxpdebit;
$coh=$totcredit-$totdebit;
?>

					    <a class="p-y block text-center" data-ui-toggle-class
					    	<strong class="block"><?php echo $coh;?> Dhs</strong>
					     	<span class="block">Cash on Hand</span>
					    </a>
					  </div>
				    </div>
				  </div>
				</div>
	<?php
	}}
	?>				  
	</div>
</div>


<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->


<?php include "includes/footer.php";?>
