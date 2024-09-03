<?php include "../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $id=$_GET['id'];
   
   $sql = "DELETE FROM reciept where id='$id'";
   $sql1 = "DELETE FROM reciept_invoice where reciept_id='$id'";
   if ($conn->query($sql) === TRUE) {
    
        $sql_invo = "SELECT invoice FROM reciept_invoice WHERE reciept_id='$id'";
        $query_invo = mysqli_query($conn,$sql_invo);
        while($fetch_invo = mysqli_fetch_array($query_invo)) {
           $inv = $fetch_invo['invoice'];
           
           $sql_status = "UPDATE `invoice` SET `status`='' WHERE `id`='$inv'";
           $query_status = mysqli_query($conn,$sql_status);
        }
        
      $conn->query($sql1);
    
    // SECTION FOR DELETEING FROM ADDITIONAL TABLES
       $sql_del_additionalRcp = "DELETE FROM `additionalRcp` WHERE `section`='RCP' AND `entry_id`='$id'";
       $query_del_additionalRcp = mysqli_query($conn,$sql_del_additionalRcp);
       
       $sql_del_additionalAcc = "DELETE FROM `additionalAcc` WHERE `section`='DIS' AND `entry_id`='$id'";
       $query_del_additionalAcc = mysqli_query($conn,$sql_del_additionalAcc);
       
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="RPT".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/advance?status=deleted"); 
}
else 
{
       header("Location: $baseurl/advance?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>