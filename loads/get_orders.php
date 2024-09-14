<?php
require_once "../config.php";

if ($_POST['c_id']) {

    $customer = $_POST['c_id'];
    $sql = "SELECT * FROM `sales_order` WHERE `customer`='$customer' AND `flag`=0";
    $query = mysqli_query($conn,$sql);
    while($result = mysqli_fetch_array($query)) {
        echo "<option value='{$result['id']}'>{$result['id']}</option>";
    }
} else {
    echo '<option>Order Not Found</option>';
}