<?php
if(isset($_POST['c_id'])) {
    $country = $_POST['c_id'];
    if ($country != '') 
    {
    include('../config.php');
    $sql="SELECT grand FROM invoice WHERE id=$country";
    $result=$conn->query($sql);
    $row=$result->fetch_assoc();
    $grand=$row["grand"];
    $grand=($grand != NULL) ? $grand : 0;
    
    $sql1="SELECT sum(total) AS total FROM reciept_invoice WHERE invoice=$country";
    //echo $sql1;
    $result1=$conn->query($sql1);
    $row1=$result1->fetch_assoc();
    $total=$row1["total"];
    $total=($total != NULL) ? $total : 0;
        
        $sqlcdt="SELECT sum(total) AS tl FROM credit_note WHERE invoice='$country'";
        $resultcdt = mysqli_query($conn, $sqlcdt);
        $rowcdt = mysqli_fetch_assoc($resultcdt);
        $credit=$rowcdt["tl"];
        $credit=($credit != NULL) ? $credit : 0;
        
	$amnt = $total + $credit;
    
    $bal = $grand - $amnt;
    echo number_format($bal, 2, '.', '');
    }
}
?>