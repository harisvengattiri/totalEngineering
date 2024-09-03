<?php
if(isset($_POST['i_id'])) {
    
    $item = $_POST['i_id'];
    $qnt = $_POST['q_id'];
    
    if ($item != '') 
    {
    include('../config.php');
    $sql="SELECT `bundle`,`weight` FROM `items` WHERE `id`='$item'";
    $result=$conn->query($sql);
    $row=$result->fetch_assoc();
    $bundle=$row["bundle"];
    $weight=$row["weight"];
    $bundle = ($bundle != NULL) ? $bundle : 1;
    
    $bdl=$qnt/$bundle;
    $bndl=round($bdl,2);
        $options = array();
        $options['bundle'] = $bndl;
        $options['item_weight'] = $weight;
    
    echo json_encode($options);
    }
}
?>