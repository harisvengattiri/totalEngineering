<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $id=$_GET['id'];
   $or=$_GET['or'];

   $sql = "DELETE FROM delivery_note where id='$id'";
   $sql1 = "DELETE FROM delivery_item where delivery_id='$id'";
if ($conn->query($sql) === TRUE)
{
	$conn->query($sql1);
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="DN".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/delivery_note?status=deleted"); 
} 
else 
{    
       header("Location: $baseurl/delivery_note?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>