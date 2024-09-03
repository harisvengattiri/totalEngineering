<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $mnt=$_GET['mnt'];
   $sql = "DELETE FROM maintenances where id='$mnt'";
if ($conn->query($sql) === TRUE)
{
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="mnt".$mnt;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/maintenances?status=deleted"); 
} 
else 
{    
       header("Location: $baseurl/maintenances?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>