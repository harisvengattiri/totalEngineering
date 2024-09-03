<?php include "../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $id=$_GET['id'];
   $customer=$_GET['cst'];
   $tamount=$_GET['amt'];
   $tamount = ($tamount != NULL) ? $tamount : 0;
   $sql = "UPDATE reciept SET amount='0', discount='0', grand='0' where id='$id'";
if ($conn->query($sql) === TRUE)
{
       $sql1 = "UPDATE customers SET rcp=rcp-$tamount WHERE id=$customer";
       $query1 = mysqli_query($conn,$sql1);
    
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="RCP".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/openings?status=deleted"); 
} 
else 
{    
       header("Location: $baseurl/openings?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>