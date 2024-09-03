<?php
if(isset($_POST['c_id'])) {
    $country = $_POST['c_id'];
    if ($country != '') {
    include('../config.php');
    $sql1="SELECT site FROM sales_order WHERE order_referance='".$country."'";
    //echo $sql1;
    $result=$conn->query($sql1);   
    if($result->num_rows > 0){
    
       while($row=$result->fetch_assoc()) {
        	
//          echo "<option value='" . $row['site'] . "'>" . $row['site'] . "</option>";
          ?> <input type="text" class="form-control" name="driver" value="<?php echo $row[site];?>"> <?php 
        
	} }
    }
    else
    {
        echo  'fdhg';
    }
}
?>