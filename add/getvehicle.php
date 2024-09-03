<?php
if(isset($_POST['c_id'])) {
    $country = $_POST['c_id'];
    if ($country != '') {
    include('../config.php');
    $sql1="SELECT salesrep FROM sales_order WHERE order_referance='".$country."'";
    //echo $sql1;
    $result=$conn->query($sql1);   
    if($result->num_rows > 0){
    
       while($row=$result->fetch_assoc()) {
        	
//          echo "<option value='" . $row['salesrep'] . "'>" . $row['salesrep'] . "</option>";
          ?> <input type="text" class="form-control" value="<?php echo $row['salesrep'];?>" name="vehicle"><?php
	} }
    }
    else
    {
        echo  'fdhg';
    }
}
?>