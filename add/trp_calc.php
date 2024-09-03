<?php include "../config.php";?>
<?php
$invoice = $_GET['inv'];
$sql = "SELECT dn FROM invoice_item WHERE invoice_id='$invoice'";
$query = mysqli_query($conn,$sql);
while($result=mysqli_fetch_array($query))
{
    $dn[] = $result['dn'];
}

$delivery_notes = array_unique($dn);

foreach($delivery_notes as $delivery_note) {
    $sql1 = "SELECT transport FROM delivery_note WHERE id='$delivery_note'";
    $query1 = mysqli_query($conn,$sql1);
    $result1 = mysqli_fetch_array($query1);
    if($result1['transport'] != NULL) {
        $trp[] = $result1['transport'];
    }
}
$trp_charge = array_sum($trp);
$trp_charge = number_format($trp_charge, 2, '.', '');

$sql2 = "SELECT `transport`,`total`,`date` FROM invoice WHERE id='$invoice'";
$query2 = mysqli_query($conn,$sql2);
$result2 = mysqli_fetch_array($query2);
$transport = $result2['transport'];
$transport = ($transport != NULL) ? $transport : 0;
$sub_total = $result2['total'];
$sub_total = ($sub_total != NULL) ? $sub_total : 0;

$date = $result2['date'];

if($transport != $trp_charge) {
    
    $vat = ($sub_total+$trp_charge)*0.05;
    $grand = $sub_total+$vat+$trp_charge;
    $grand = number_format($grand, 2, '.', '');
    
    $sql3 = "UPDATE invoice SET `transport`='$trp_charge',`vat`='$vat',`grand`='$grand' WHERE id='$invoice'";
    $query3 = mysqli_query($conn,$sql3);
    
    $sql4 = "UPDATE `additionalAcc` SET `amount` = '$vat' WHERE `entry_id`='$invoice' AND `section`='INV'";
    $query4 = mysqli_query($conn,$sql4);
    
    $sql_adtnl_inv = "UPDATE `additionalRcp` SET `amount` = '$grand' WHERE `section`='INV' AND `entry_id`='$invoice'";
    $conn->query($sql_adtnl_inv);

    if($transport > 0) {
        $sql5 = "UPDATE `additionalAcc` SET `amount` = '$trp_charge' WHERE `entry_id`='$invoice' AND `section`='TRP'";
        $query5 = mysqli_query($conn,$sql5);
    } else {
        $sql_adtnl_transport = "INSERT INTO `additionalAcc`(`id`, `section`, `entry_id`, `date`, `cat`, `sub`, `amount`)
                                VALUES ('','TRP','$invoice','$date','4','','$trp_charge')";
        $conn->query($sql_adtnl_transport);
    }
    
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="INV".$invoice;
       $query=mysqli_real_escape_string($conn, $sql3);
       $sql6 = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'edit', '$code', '$username', '$query')";
       $result6 = mysqli_query($conn, $sql6);
}

header("Location:$baseurl/invoice");
?>