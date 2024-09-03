<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
$id = $_POST['id'];
$ban_desc = $_POST['ban_desc'];
$status = "banned";
$today = date('d/m/Y');
$sql = "UPDATE `customers` SET `status` = '$status', `ban_date` = '$today', `ban_desc` = '$ban_desc' WHERE `id` = '$id'";
if ($conn->query($sql) === TRUE)
{    $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="CID".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'status', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/banned?status=success"); 
} 
else 
{    
      header("Location: $baseurl/banned?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>