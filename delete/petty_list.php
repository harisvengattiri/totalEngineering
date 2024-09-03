<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $id=$_GET['id'];
   
   $sql = "SELECT * FROM petty_item where id='$id'";
   $query = mysqli_query($conn,$sql);
   $result = mysqli_fetch_array($query);
   $petty = $result['petty'];
   $amount = $result['amount'];
   $vat = $result['vat'];
   $total = $result['total'];
   
   $sql1 = "UPDATE petty_cash SET `amount`=`amount`-'$amount',`vat`=`vat`-'$vat',`total`=`total`-'$total' WHERE id='$petty'";
   $conn->query($sql1);
  
   $sql2 = "DELETE FROM petty_item where id='$id'";
if ($conn->query($sql2) === TRUE)
{
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="PIT".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/view/petty_list?id=$petty&status=deleted"); 
} 
else 
{    
       header("Location: $baseurl/view/petty_list?id=$petty&status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>