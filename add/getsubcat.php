<?php
if(isset($_POST['c_id'])) {
    $country = $_POST['c_id'];
    if ($country != '') {
    include('../config.php');
    $sql1="SELECT * FROM account_subcategories WHERE parent='".$country."'";
    //echo $sql1;
    $result=$conn->query($sql1);   
    if($result->num_rows > 0){
    
       while($row=$result->fetch_assoc()) {
        	
          echo "<option value='" . $row['id'] . "'>" . $row['category'] . "</option>";
        
	} }
    }
    else
    {
        echo  'failed';
    }
}
?>