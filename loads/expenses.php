<?php
if(isset($_POST['s_id'])) {
    $supplier = $_POST['s_id'];
    if ($supplier != '') {
    include('../config.php');
    $sql = "SELECT * FROM `expenses` WHERE `shop`='".$supplier."'";
    $result=$conn->query($sql);   
    if($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['id'] . "'>" .'EXP|'. sprintf('%04d',$row['id']) . "</option>";
	} }
    }
    else
    {
        echo  'error';
    }
}
?>