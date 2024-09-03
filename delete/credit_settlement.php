<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $cst=$_GET['cst'];
   $sql = "DELETE FROM credit_settlements where id='$cst'";
if ($conn->query($sql) === TRUE)
{
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="cst".$cst;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/credit_settlements?status=deleted"); 
} 
else 
{    
       header("Location: $baseurl/credit_settlements?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>