<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $vxp=$_GET['vxp'];
   $sql = "DELETE FROM vehicle_expenses where id='$vxp'";
if ($conn->query($sql) === TRUE)
{
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="vxp".$vxp;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/vehicle_expenses?status=deleted"); 
} 
else 
{    
       header("Location: $baseurl/vehicle_expenses?status=failed"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>