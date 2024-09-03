<?php include "../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
$id=$_GET['id'];
$status=$_GET['status'];
if($status == 'Cleared')
{
$cdate = date("d/m/Y");
}
$sql = "UPDATE `reciept` SET `status` = '$status',`clearance_date` = '$cdate' WHERE `id` = '$id'";
if ($conn->query($sql) === TRUE) {
    
    // SECTION FOR ADDING TO ADDITIONAL TABLE
        $sql_adtnl_inv = "UPDATE `additionalRcp` SET `date`='$cdate' WHERE `entry_id`='$id' AND `section`='RCP'";
        $conn->query($sql_adtnl_inv);
    
    $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="RCP".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'status', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/receipt_cheque?status=success"); 
} 
else 
{    
      header("Location: $baseurl/receipt_cheque?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>