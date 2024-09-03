<?php
if(isset($_POST['i_id'])) {
    
    $item = $_POST['i_id'];
    $qnt = $_POST['q_id'];
    $qnt = ($qnt != NULL) ? $qnt : 0;
    
    if ($item != '') 
    {
    include('../config.php');
    $sql="SELECT bundle FROM `items` WHERE id=$item";
    $result=$conn->query($sql);
    $row=$result->fetch_assoc();
    $bundle=$row["bundle"];
    $bundle = ($bundle != NULL) ? $bundle : 0;
    
    $bdl=$qnt/$bundle;
    $bndl=round($bdl,2);
    
    echo $bndl;

    // echo number_format($bal, 2, '.', '');
    }
}
?>