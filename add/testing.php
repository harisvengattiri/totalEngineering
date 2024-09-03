<?php
include "../config.php";

$sql="SELECT sum(tbl.total) - sum(tbl.received) as diff FROM (
SELECT invoice.id as id, invoice.customer as customer, invoice.date as date, invoice.grand as total, sum(reciept_invoice.total) as received
FROM invoice
LEFT JOIN reciept_invoice ON invoice.id = reciept_invoice.invoice GROUP BY reciept_invoice.invoice
    ) as tbl";
    
$query = mysqli_query($conn,$sql);
$fetch = mysqli_fetch_array($query);
$sum = $fetch['diff'];

echo $sum;
?>