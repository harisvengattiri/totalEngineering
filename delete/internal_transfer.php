<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $itf=$_GET['itf'];
   $sql = "DELETE FROM internal_transfers where id='$itf'";
if ($conn->query($sql) === TRUE)
{
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="itf".$itf;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/internal_transfers?status=deleted"); 
} 
else 
{    
       header("Location: $baseurl/internal_transfers?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>