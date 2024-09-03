<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $wri=$_GET['wri'];
   $sql = "DELETE FROM work_invoices where id='$wri'";
if ($conn->query($sql) === TRUE)
{
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="wri".$wri;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/work_invoices?status=deleted"); 
} 
else 
{    
       header("Location: $baseurl/work_invoices?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>