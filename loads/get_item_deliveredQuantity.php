<?php
require_once "../config.php";

if (isset($_POST['itemDetails'])) {

    $delivery = $_POST['dn'];
    $itemDetails = $_POST['itemDetails'];

    list($item, $JwNumber) = explode(',', $itemDetails);

    $sql = "SELECT * FROM `delivery_item` WHERE `delivery_id`='$delivery' AND `jw`='$JwNumber' AND `item`='$item'";
    $query = mysqli_query($conn,$sql);
    $result = mysqli_fetch_array($query);
    $delivered_quantity = $result['quantity'];

    $responseData = array(
        'JwNumber' => $JwNumber,
        'deliveredQuantity' => $delivered_quantity,
    );
    echo json_encode($responseData);
    exit();
} else {
    echo '<option>Delivery Not Found</option>';
}
