<?php
include "../config.php";

if(isset($_POST['submitApproval']))
{
    $deliveryNotes = $_POST['deliveryNotes'];

    foreach($deliveryNotes as $dn) {
        $sql = "UPDATE `delivery_note` SET `confirmation`= 1 WHERE `id` = '$dn'";
        $conn->query($sql);
    }
    header("location:$baseurl/delivery_note");
} else if(isset($_POST['submitConfirmInvoice']))
{
    $invoices = $_POST['invoices'];

    foreach($invoices as $invo) {
        $sql = "UPDATE `invoice` SET `confirmation`= 1 WHERE `id` = '$invo'";
        $conn->query($sql);
    }
    header("location:$baseurl/invoice");
} else {
    header("location:$baseurl");
}