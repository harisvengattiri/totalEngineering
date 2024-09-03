<?php
include('../config.php');
if(isset($_POST['c_id'])) {
    $country =$_POST['c_id'];
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
         if($row['quantity'] != NULL) {
          $quantity=$quantity+$row['quantity'];
         }
       }
       while($row1=$result1->fetch_assoc())
       {
         if($row1['quantity'] != NULL) {
          $s_quantity=$s_quantity+$row1['quantity'];
         }
       }
         // "<option value='" . $row['quantity'] . "'>" . $row['quantity'] . "</option>";
       
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