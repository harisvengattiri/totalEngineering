<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $id=$_GET['id'];
   $sql="select * from transactions where id='$id'";
   $query=mysqli_query($conn,$sql);
   $fetch=mysqli_fetch_array($query);
   $type=$fetch['type'];
   $amount=$fetch['amount'];
   $account=$fetch['account'];
   
   $sql1 = "DELETE FROM transactions where id='$id'";
if ($conn->query($sql1) === TRUE)
{
//     if($type==income)
//         {
//         $sql3="update account set init_balance=init_balance-$amount where account=$account";
//         $conn->query($sql3);
//         }else
//         {
//         $sql4="update account set init_balance=init_balance+$amount where account=$account";
//         $conn->query($sql4);   
//         }
         
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="TRN".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/transactions?status=deleted"); 
} 
else 
{    
       header("Location: $baseurl/transactions?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>