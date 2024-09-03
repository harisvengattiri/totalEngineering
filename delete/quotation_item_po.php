<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $prj=$_GET['id'];
   $qtn=$_GET['qtn'];
   $amt=$_GET['amt'];
   
    $sql = "DELETE FROM qtn_item_test where id='$prj'";
    if ($conn->query($sql) === TRUE)
    {
        $sql2="UPDATE qtn_test SET `subtotal`=`subtotal`-'$amt' where id='$qtn'";
        $conn->query($sql2); 
        
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="QNO".$qtn;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/view_quotation_po?qtn=$qtn&status=deleted"); 
} 
else 
{    
       header("Location: $baseurl/view_quotation_po?qtn=$qtn&status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>