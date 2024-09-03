<?php include"../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $id=$_GET['id'];
   
   $sql1 = "SELECT lpo_pdf FROM sales_order where id='$id'";
   $result1 = mysqli_query($conn, $sql1);
   $row1 = mysqli_fetch_assoc($result1);
    $Path = '../uploads/lpo/'.$row1['lpo_pdf'];
    if (file_exists($Path)){ unlink($Path);}

    $sql = "UPDATE `sales_order` SET `lpo_pdf` = '' where id='$id'";
   
if ($conn->query($sql) === TRUE)
{
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="PO".$id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
       header("Location: $baseurl/edit/sales_order?id=$id");
       
       
        
  } 
else 
{    
        header("Location: $baseurl/edit/sales_order?id=$id");
}
    
}
else
{
   header("Location:$baseurl/login/");
}

?>