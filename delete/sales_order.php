<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $prj=$_GET['id'];
   $or=$_GET['or'];
   
   $sqldelv = "SELECT order_referance FROM delivery_note WHERE order_referance='$or'";
   $querydelv = mysqli_query($conn,$sqldelv);
   if(mysqli_num_rows($querydelv) == 0)
   {
$sql = "DELETE FROM sales_order where id='$prj'";
if ($conn->query($sql) === TRUE)
{
       $sql1 = "DELETE FROM order_item where item_id='$prj'";
       $conn->query($sql1);

       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="SO".$prj;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
         header("Location: $baseurl/sales_order_new?status=deleted"); 
}
   }
else 
{    
       header("Location: $baseurl/sales_order_new?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>