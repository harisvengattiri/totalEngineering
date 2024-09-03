<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $prj=$_GET['prj'];
   $sql = "DELETE FROM batches_lots where id='$prj'";
if ($conn->query($sql) === TRUE)
{
     $sql1 = "DELETE FROM prod_items where sale_id='$prj'";
     $conn->query($sql1);
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="prj".$prj;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/batches?status=deleted"); 
} 
else 
{    
       header("Location: $baseurl/batches?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>