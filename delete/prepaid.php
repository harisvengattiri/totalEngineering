<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $id=$_GET['id'];
   $sql = "DELETE FROM prepaid where id='$id'";
if ($conn->query($sql) === TRUE)
{
       $sql1 = "DELETE FROM prepaid_expense where prepaid='$id'";
       $conn->query($sql1);
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="PRE".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/prepaid?status=deleted"); 
} 
else 
{    
       header("Location: $baseurl/prepaid?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>