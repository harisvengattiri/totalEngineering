<?php
$po = $_GET['id'];
include "../config.php";

$sql = "UPDATE `sales_order` SET `approve`= 1 WHERE `id`='$po'";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="PO".$po;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'edit', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl?status=approved"); 
} else {
       header("Location: $baseurl?status=approve failed");
}
?>