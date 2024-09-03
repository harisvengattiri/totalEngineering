<?php
if(isset($_POST['item_id'])) {
    $item = $_POST['item_id'];
    $or = $_POST['or'];
    if ($item != '') {
    include('../config.php');
    // here is the code for taking batch list
        $sql="SELECT batch FROM batches_lots
        WHERE batch NOT IN (SELECT batch FROM delivery_item) AND item='".$item."'
        UNION
        SELECT DISTINCT item.batch from 
        (SELECT batch, item, quantity from batches_lots where item = '".$item."') AS item 
        INNER JOIN
        (SELECT item, batch, SUM(thisquantity) as thisquantity FROM delivery_item where item = '".$item."' GROUP BY batch) AS dq 
        ON item.batch = dq.batch WHERE item.quantity > dq.thisquantity";

        $result=$conn->query($sql);
        if($result->num_rows > 0) {
        $options = array();
        $options['batchOptions'][] = 'Select';
        while($row = $result->fetch_assoc()) {
            $options['batchOptions'][] = $row['batch'];
        } }

    // here is the code for taking order details
        $sql1 = "SELECT quantity,unit,comment FROM `order_item` WHERE `o_r`='$or' AND `item`='$item'";
        $result1 = $conn->query($sql1);
        $row1 = $result1->fetch_assoc();
        $order_quantity = $row1['quantity'];
        $order_quantity = ($order_quantity != NULL) ? $order_quantity : 0;
        $price = $row1['unit'];
        $comment = $row1['comment'];
            $options['order_quantity'] = $order_quantity;
            $options['order_price'] = $price;
            $options['item_comment'] = $comment;
    // here is the code for taking balance of item to be delivered
        $sql2 = "SELECT sum(thisquantity) AS sold FROM `delivery_item` WHERE `order_referance`='$or' AND `item`='$item'";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();
        $sold_quantity = $row2['sold'];
        $sold_quantity = ($sold_quantity != NULL) ? $sold_quantity : 0;
        
        $sqlret = "SELECT sum(returnqnt) AS returnQnt FROM `stock_return` WHERE `o_r`='$or' AND `item`='$item'";
        $resultret = mysqli_query($conn, $sqlret);
        $rowret = mysqli_fetch_assoc($resultret);
        $return_quantity = $rowret['returnQnt'];
        $return_quantity = ($return_quantity != NULL) ? $return_quantity : 0;

        $req_quantity = ($order_quantity+$return_quantity)-$sold_quantity;

            $options['req_quantity'] = $req_quantity;

    // here is the code for taking weight of Item
        $sqlWeight = "SELECT `weight` FROM `items` WHERE `id`='$item'";
        $resultWeight = mysqli_query($conn, $sqlWeight);
        $rowWeight = mysqli_fetch_assoc($resultWeight);
        $item_weight = $rowWeight['weight'];

            $options['item_weight'] = $item_weight;

    echo json_encode($options);
    }
    else
    { echo 'Not Found';}
}
?>