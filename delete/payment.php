<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $pym=$_GET['pym'];
   $sql = "DELETE FROM payments where id='$pym'";
if ($conn->query($sql) === TRUE)
{
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="pym".$pym;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/payments?status=deleted"); 
} 
else 
{    
       header("Location: $baseurl/payments?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>