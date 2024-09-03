<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $prj=$_GET['id'];
   $dn=$_GET['dn'];
   
   $sql = "DELETE FROM invoice where id='$prj'";
   $sql3="UPDATE delivery_note SET invoiced = '' WHERE id='$dn'";  
   $conn->query($sql3);
   $sql1 = "DELETE FROM invoice_item where invoice_id='$prj'";
if ($conn->query($sql) === TRUE)
{
       $conn->query($sql1);
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="INV".$prj;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/invoice?status=deleted"); 
} 
else 
{    
       header("Location: $baseurl/invoice?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>