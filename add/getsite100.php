<?php
if(isset($_POST['c_id'])) {
    $country = $_POST['c_id'];
    if ($country != '') {
    include('../config.php');
    $sql1="SELECT location FROM customer_site WHERE customer='".$country."'";
    //echo $sql1;
    $result=$conn->query($sql1);   
    if($result->num_rows > 0){
    
       while($row=$result->fetch_assoc()) {
        	
          echo "<option value='" . $row['location'] . "'>" . $row['location'] . "</option>";
        
	} }
    }
    else
    {
        echo  'fdhg';
    }
}
?>