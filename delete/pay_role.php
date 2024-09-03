<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $id=$_GET['id'];

   $sql = "DELETE FROM pay_role where id='$id'";
   $sql1 = "DELETE FROM pay_role_item where pay_role='$id'";
if ($conn->query($sql) === TRUE)
{
	$conn->query($sql1);
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="PRL".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/pay_role?status=deleted"); 
} 
else 
{    
       header("Location: $baseurl/pay_role?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>