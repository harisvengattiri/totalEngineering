<?php include "../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $prj=$_GET['id'];
   $or=$_GET['or'];

    $sql = "UPDATE delivery_note SET total='' where id='$prj' AND invoiced=''";
if ($conn->query($sql) === TRUE)
{
       $sql1 = "UPDATE delivery_item SET thisquantity='',amt='' where order_referance='$or' AND delivery_id='$prj'";
       $conn->query($sql1);
       $sql2 = "UPDATE sales_order SET flag='0' where order_referance='$or'";
       $conn->query($sql2);
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="DN".$prj;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/delivery_note?status=Updated"); 
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