<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $id=$_GET['id'];

   $sql = "DELETE FROM petty_cash where id='$id'";
   $sql1 = "DELETE FROM petty_item where petty='$id'";
if ($conn->query($sql) === TRUE)
{
	$conn->query($sql1);
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="PTC".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/petty_cash?status=deleted"); 
} 
else 
{    
       header("Location: $baseurl/petty_cash?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>