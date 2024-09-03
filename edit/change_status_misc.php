<?php include"../config.php"; ?>
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
$sql = "UPDATE `miscellaneous` SET `status` = '$status',`clearance_date` = '$cdate' WHERE `id` = '$id'";
if ($conn->query($sql) === TRUE)
{    $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="MSC".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'status', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/miscellaneous?status=success"); 
} 
else 
{    
      header("Location: $baseurl/miscellaneous?status=failed");
}}
else
{
   header("Location:$baseurl/login/");
}
?>