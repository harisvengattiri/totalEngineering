<?php
if(isset($_POST['c_id'])) {
    $country = $_POST['c_id'];
    if ($country != '') {
    include('../config.php');
    $sql1="SELECT * FROM batches_lots WHERE batch='".$country."'";
    //echo $sql1;
    $result=$conn->query($sql1);   
    if($result->num_rows > 0)
    {
    
       while($row=$result->fetch_assoc())
        {
            
            $id=$row['id'];
            $quantity=$row['quantity'];
	}
    }
       echo $quantity;
    }
    else
    {
        echo  'fdhg';
    }
}
?>