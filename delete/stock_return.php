<?php include "../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $dno = $_GET['id'];
   $sql = "DELETE FROM stock_return where dn='$dno'";
if ($conn->query($sql) === TRUE)
{
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="SRN".$dno;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/stock_return?status=deleted"); 
} 
else 
{    
       header("Location: $baseurl/stock_return?status=failed"); 
}
}
else
{
   header("Location:$baseurl/login/");
}
?>