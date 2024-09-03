<?php
// error_reporting(0);
if(isset($_POST['c_id'])) {
    $country = $_POST['c_id'];
    if ($country != '') {
    include('../config.php');
    
    $sql0="SELECT item FROM batches_lots WHERE batch='".$country."'";
    $result0=$conn->query($sql0);   
    if($result0->num_rows > 0)
    {
       while($row0=$result0->fetch_assoc())
        {
            $item=$row0['item'];
            $sql="SELECT sqm FROM items WHERE id='".$item."'";
    	    $result=$conn->query($sql);
    	    $row=$result->fetch_assoc();
    	    $sqm=$row['sqm'];
            // $sqm = ($sqm != NULL) ? $sqm : 1;
	    }
    }
    
    
    
    $sql1="SELECT * FROM batches_lots WHERE batch='".$country."'";
    $sql2="SELECT * FROM delivery_item WHERE batch='".$country."'";
    $sql3="SELECT * FROM stock_return WHERE batch='".$country."'";
    //echo $sql1;
    $quantity=0;
    $s_quantity=0;
    $r_quantity=0;
    $result1=$conn->query($sql1);   
    if($result1->num_rows > 0)
    {
       while($row1=$result1->fetch_assoc())
        {
            $quantity=$quantity+$row1['quantity'];
	    }
	$quantity=$quantity/$sqm;
    }

    $result2=$conn->query($sql2);   
    if($result2->num_rows > 0)
    {
       while($row2=$result2->fetch_assoc())
        {
            if($row2['thisquantity'] != NULL) {
            $s_quantity=$s_quantity+$row2['thisquantity'];
            }
	    }
    }
    
    $result3=$conn->query($sql3);   
    if($result3->num_rows > 0)
    {
       while($row3=$result3->fetch_assoc())
        {
            $r_quantity=$r_quantity+$row3['returnqnt'];
	    }
    }

    $finalquantity=$quantity+$r_quantity-$s_quantity;
      if(fmod($finalquantity, 1) !== 0.00)
      {
      echo $finalquantity = number_format($finalquantity, 2, '.', '');
      } else { echo $finalquantity; }
      
    }
    else
    {
        echo  'fdhg';
    }
}
?>