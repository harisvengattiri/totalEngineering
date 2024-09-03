<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $prj=$_GET['id'];
   
    $sql = "DELETE FROM quotation where id='$prj'";
    $sql1 = "DELETE FROM quotation_item where quotation_id='$prj'";
    $sql2 = "DELETE FROM quotation_transportation where quotation_id='$prj'";
    
    if ($conn->query($sql) === TRUE)
    {
       $conn->query($sql1);
       $conn->query($sql2);
        
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="QNO".$prj;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/quotation?status=deleted"); 
} 
else 
{    
       header("Location: $baseurl/quotation?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>