<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $id=$_GET['id'];

   $sql = "DELETE FROM petty_voucher where id='$id'";
if ($conn->query($sql) === TRUE)
{
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="PTV".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/petty_voucher?status=deleted"); 
} 
else 
{    
       header("Location: $baseurl/petty_voucher?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>