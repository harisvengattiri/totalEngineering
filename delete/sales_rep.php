<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $prj=$_GET['mnt'];
   $sql = "DELETE FROM sales_rep where id='$prj'";
if ($conn->query($sql) === TRUE)
{
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="prj".$prj;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/sales_rep?status=deleted"); 
} 
else 
{    
       header("Location: $baseurl/sales_rep?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>