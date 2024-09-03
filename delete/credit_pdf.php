<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $id=$_GET['id'];
   
   $sql1 = "SELECT g_doc FROM credit_application where id='$id'";
   $result1 = mysqli_query($conn, $sql1);
   $row1 = mysqli_fetch_assoc($result1);
    $Path = '../uploads/credit/'.$row1['g_doc'];
    if (file_exists($Path)){ unlink($Path);}

    $sql = "UPDATE `credit_application` SET `g_doc` = '' where id='$id'";
if ($conn->query($sql) === TRUE)
{
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="CDT".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/edit/credit_application?id=$id");
  } 
else 
{   
        header("Location: $baseurl/edit/credit_application?id=$id");
}}
else
{ header("Location:$baseurl/login/"); }
?>