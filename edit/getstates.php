<?php
if(isset($_POST['c_id'])) {
	include('../config.php');
    $country = mysqli_real_escape_string($conn, $_POST['c_id']);
    if ($country != '') {
    
    $sql1="SELECT quantity FROM prod_items WHERE item='".$country."'";
    $sql2="SELECT quantity FROM batches_lots WHERE item='".$country."'";
    //echo $sql1;
    $result=$conn->query($sql1);
    $result1=$conn->query($sql2);    
    if($result->num_rows > 0){
    
       $quantity=0;
       $s_quantity=0;
       while($row=$result->fetch_assoc()) 
       {
            $quantity=$quantity+$row['quantity'];
         // "<option value='" . $row['quantity'] . "'>" . $row['quantity'] . "</option>";
       }
       while($row1=$result1->fetch_assoc()) 
       {
            $s_quantity=$s_quantity+$row1['quantity'];
       }
	
        $finalquantity=$quantity-$s_quantity;
	?><input type="number" class="form-control" value="<?php echo $finalquantity;?>" name="avl" readonly><?php
       }
    }
    else
    {
        echo  'fdhg';
    }
}
?>