<?php include "../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
$id=$_GET['id'];
$status=$_GET['status'];

$fdate = isset($_GET['fd']) ? $_GET['fd'] : null;
$tdate = isset($_GET['td']) ? $_GET['td'] : null;
$customer = isset($_GET['cst']) ? $_GET['cst'] : null;
$deli_status = isset($_GET['deli']) ? $_GET['deli'] : null;

$searchEntries = '';

if ($fdate !== null && $fdate !== '') {
      $searchEntries .= '&fdate=' . urlencode($fdate);
}
if ($tdate !== null && $tdate !== '') {
      $searchEntries .= '&tdate=' . urlencode($tdate);
}
if ($customer !== null && $customer !== '') {
      $searchEntries .= '&customer=' . urlencode($customer);
}
if ($deli_status !== null && $deli_status !== '') {
      $searchEntries .= '&deli_status=' . urlencode($deli_status);
}

if($status == 'Active')
{
 $status='';
}
if($status == 'Inactive')
{
 $status='1';
}
$sql = "UPDATE `sales_order` SET `status` = '$status' WHERE `id` = '$id'";
if ($conn->query($sql) === TRUE)
{    $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="PO".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'status', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/sales_order_new?status=success$searchEntries"); 
} 
else 
{    
      header("Location: $baseurl/sales_order_new?status=failed$searchEntries"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>
