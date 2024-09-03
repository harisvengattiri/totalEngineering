<?php include "../config.php";?>
<?php
// $bal = 1000.987512;
// number_format($bal, 2, '.', '');


// $sql = "SELECT * FROM liability_invoice11 WHERE cat=''";
// $query = mysqli_query($conn,$sql);
// while($fetch = mysqli_fetch_array($query)){
// $id = $fetch['id'];
// $inv = $fetch['inv'];

// $sql1 = "SELECT category FROM expenses11 WHERE inv='$inv'";
// $query1 = mysqli_query($conn,$sql1);
// $fetch1 = mysqli_fetch_array($query1);
// $cat = $fetch1['category'];

// $sql2 = "UPDATE liability_invoice11 SET cat='$cat' WHERE id='$id'";
// $query2 = mysqli_query($conn,$sql2);
// }

// $invoice = 65022222222222222222222;
// $sql = "SELECT dn FROM invoice_item WHERE invoice_id='$invoice'";
// $query = mysqli_query($conn,$sql);
// while($result=mysqli_fetch_array($query))
// {
//     $dn[] = $result['dn'];
// }

// $delivery_notes = array_unique($dn);

// foreach($delivery_notes as $delivery_note){
//     $sql1 = "SELECT transport FROM delivery_note WHERE id='$delivery_note'";
//     $query1 = mysqli_query($conn,$sql1);
//     $result1 = mysqli_fetch_array($query1);
//     $trp[] = $result1['transport'];
// }
// $trp_charge = array_sum($trp);
// $trp_charge = number_format($trp_charge, 2, '.', '');

// $sql2 = "SELECT transport FROM invoice WHERE id='$invoice'";
// $query2 = mysqli_query($conn,$sql2);
// $result2 = mysqli_fetch_array($query2);
// $transport = $result2['transport'];

// if($transport != $trp_charge)
// {
//     $sql3 = "UPDATE invoice22 SET `transport`='0',`grand`=`grand`-'$transport' WHERE id='$invoice'";
//     $query3 = mysqli_query($conn,$sql3);
    
//     $sql4 = "UPDATE invoice22 SET `transport`='$trp_charge',`grand`=`grand`+ '$trp_charge' WHERE id='$invoice'";
//     $query4 = mysqli_query($conn,$sql4);
// }

// $date = "31/12/2019";
// $date = str_replace('/', '-', $date);

// for($i=1;$i<=6;$i++)
// {
// $newDate = date("10-m-Y", strtotime($date));
// echo $p_dates =  Date("01/m/Y", strtotime("$newDate"."+$i month")).'<br>';
// }
    
?>

<?php 
    // $orgDate = "20/12/2019";
    // $orgDate = str_replace('/', '-', $orgDate);
    // $newDate = date("Y-m-d", strtotime($orgDate));
?>


<?php
$sql = "SELECT id FROM quotation WHERE status='Sales Order'";
$query = mysqli_query($conn,$sql);
while($fetch = mysqli_fetch_array($query)){
    
    $qtn = $fetch['id'];
    
    $sql1 = "SELECT id,qtn FROM sales_order WHERE qtn='$qtn'";
    $query1 = mysqli_query($conn,$sql1);
    $fetch1 = mysqli_fetch_array($query1);
    $po = $fetch1['id'];
    
    if($po == NULL){echo $qtn.'<br>';}
    
}


// $sql = "SELECT id FROM quotation WHERE status='Sales Order'";
// $query = mysqli_query($conn,$sql);
// while($fetch = mysqli_fetch_array($query)){
    
//     $qtn = $fetch['id'];
    
//     $sql1 = "SELECT id,qtn,site FROM sales_order WHERE qtn='$qtn'";
//     $query1 = mysqli_query($conn,$sql1);
//     $fetch1 = mysqli_fetch_array($query1);
    
//     $po = $fetch1['id'];
//     $site = $fetch1['site'];
    
//     if($po != NULL)
//     {
//       $sql2 = "UPDATE quotation SET site=$site WHERE id=$qtn";
//       $query2 = mysqli_query($conn,$sql2);
//     }
// }
// echo 'FINISH';

?>






















