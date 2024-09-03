<?php
if(isset($_POST['vehicle'])) {
    $vehicle = $_POST['vehicle'];

    if ($vehicle != '') {
    include('../config.php');

        $sql="SELECT * FROM `vehicle` WHERE `id`='$vehicle'";
        $result=$conn->query($sql);
        if($result->num_rows > 0) {
        $options = array();
        $row = $result->fetch_assoc();
            $options['vehicleWeight'] = $row['vehicleWeight'];
            $options['allowedWeight'] = $row['allowedWeight'];
        }

    echo json_encode($options);
    }
    else
    { echo 'Not Found';}
}
?>