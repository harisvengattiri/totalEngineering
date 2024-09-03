<?php
if(isset($_POST['c_id'])) {
    $country = $_POST['c_id'];
    if ($country != '') {
    include('../config.php');
    $sql1="SELECT id FROM quotation WHERE `customer`='".$country."' AND `flag` != 1 AND `status`='Sales Order'";
    //echo $sql1;
    $result=$conn->query($sql1);   
    if($result->num_rows > 0){
    
       while($row=$result->fetch_assoc()) {
        	
          echo "<option value='" . $row['id'] . "'>" . $row['id'] . "</option>";
        
	} }
    }
    else
    {
        echo  'fdhg';
    }
}
?>