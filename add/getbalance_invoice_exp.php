<?php
if(isset($_POST['c_id'])) {
    $country = $_POST['c_id'];
    if ($country != '') 
    {
    include('../config.php');
    $sql="SELECT sum(amount) as amount FROM expenses WHERE inv='$country'";
    $result=$conn->query($sql);
    $row=$result->fetch_assoc();
    $amount=$row["amount"];
    
    $sql1="SELECT sum(total) AS total FROM voucher_invoice WHERE inv='$country'";
    //echo $sql1;
    $result1=$conn->query($sql1);
    
       $row1=$result1->fetch_assoc();
       $total=$row1["total"];

    $bal=$amount-$total;
    echo number_format($bal, 2, '.', '');
    }
}
?>