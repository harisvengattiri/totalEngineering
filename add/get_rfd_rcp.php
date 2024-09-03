<?php
if(isset($_POST['c_id'])) {
    $customer = $_POST['c_id'];
    if ($customer != '') {
    include('../config.php');
    $sql="
    SELECT r.id
        FROM reciept r
        LEFT JOIN (
            SELECT ri.reciept_id, COALESCE(SUM(ri.amount), 0) as total_amount
            FROM reciept_invoice ri
            GROUP BY ri.reciept_id
        ) ri_sum ON r.id = ri_sum.reciept_id
        WHERE r.customer = '$customer' AND r.amount > COALESCE(ri_sum.total_amount, 0)
    ";
    $result=$conn->query($sql);   
    if($result->num_rows > 0){
    echo "<option value=''>Select</option>";
       while($row=$result->fetch_assoc()) {
           $id = $row['id'];
           
            echo "<option value='" . $id . "'>" ."RCP | ". $id . "</option>";
           
	} }
}
}
?>