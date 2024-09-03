<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $id=$_GET['id'];

   $sql = "DELETE FROM credit_note where id='$id'";
   $sql1 = "DELETE FROM credit_note_items where cr_id='$id'";
if ($conn->query($sql) === TRUE)
{
	$conn->query($sql1);
	
	// SECTION FOR DELETEING FROM ADDITIONAL TABLES
	$sql_del_additionalAcc = "DELETE FROM `additionalAcc` WHERE `entry_id`='$id' AND `section`='CNT'";
    $query_del_additionalAcc = mysqli_query($conn,$sql_del_additionalAcc);
    
    $sql_del_additionalRcp = "DELETE FROM `additionalRcp` WHERE `entry_id`='$id' AND `section`='CNT'";
    $query_del_additionalRcp = mysqli_query($conn,$sql_del_additionalRcp);
	
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="CNT".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/crdt_note?status=deleted");
} 
else 
{    
       header("Location: $baseurl/crdt_note?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>