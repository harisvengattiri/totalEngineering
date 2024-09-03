<?php include "../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{
   $id=$_GET['id'];

   $sqlinv = "SELECT invoice FROM reciept_invoice WHERE invoice='$id' AND total > 0";
   $queryinv = mysqli_query($conn,$sqlinv);
   if(mysqli_num_rows($queryinv) == 0)
   {
   $sql = "UPDATE invoice SET total='0',vat='0',grand='0' where id='$id'";
   $sql1 = "UPDATE invoice_item SET quantity='0',unit='0',total='0' where invoice_id='$id'";
    if ($conn->query($sql) === TRUE)
    {
       $conn->query($sql1);
       $sql2 = "SELECT dn FROM invoice_item where invoice_id='$id'";
       $query2 = mysqli_query($conn,$sql2);
       while($fetch2 = mysqli_fetch_array($query2))
       {
          $dn = $fetch2['dn'];
          $sql3 = "UPDATE delivery_note SET invoiced='' where id='$dn'";
          $conn->query($sql3);     
       }
       
       $sql5 = "DELETE FROM `additionalAcc` WHERE `entry_id`='$id' AND `section`='INV'";
       $query5 = mysqli_query($conn,$sql5);
       
       $sql6 = "DELETE FROM `additionalAcc` WHERE `entry_id`='$id' AND `section`='TRP'";
       $query6 = mysqli_query($conn,$sql6);
       
       $sql7 = "DELETE FROM `additionalRcp` WHERE `entry_id`='$id' AND `section`='INV'";
       $query7 = mysqli_query($conn,$sql7);
       
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="INV".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/invoice?status=Updated"); 
    } 
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