<?php
if(isset($_POST['batch'])) {
    $batch = $_POST['batch'];
    if ($batch != '') {
    include('../config.php');
    $sql="SELECT COC_No FROM batches_lots WHERE batch='".$batch."' and quantity!=''";
    $result=$conn->query($sql);   
    if($result->num_rows > 0)
    {
        $row=$result->fetch_assoc();
        $coc=$row['COC_No'];
    }
// echo $coc;
        $sql0="SELECT item FROM batches_lots WHERE batch='".$batch."'";
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
            }
        }
        
        $sql1="SELECT sum(quantity) AS quantity FROM batches_lots WHERE `batch`='".$batch."'";
        $sql2="SELECT sum(thisquantity) AS thisquantity FROM delivery_item WHERE `batch`='".$batch."'";
        $sql3="SELECT sum(returnqnt) AS returnqnt FROM stock_return WHERE `batch`='".$batch."'";
        //echo $sql1;
        $quantity=0;
        $s_quantity=0;
        $r_quantity=0;
        $result1=$conn->query($sql1);   
        if($result1->num_rows > 0)
        {
            $row1=$result1->fetch_assoc();
            $quantity=$row1['quantity'];    
            $quantity=$quantity/$sqm;
        }
    
        $result2=$conn->query($sql2);   
        if($result2->num_rows > 0)
        {
            $row2=$result2->fetch_assoc();
            if($row2['thisquantity'] != NULL) {
                $s_quantity=$row2['thisquantity'];
            }
        }
        
        $result3=$conn->query($sql3);   
        if($result3->num_rows > 0)
        {
            $row3=$result3->fetch_assoc();
            $r_quantity=$row3['returnqnt'];
        }
    
        $finalquantity=$quantity+$r_quantity-$s_quantity;

          if(fmod($finalquantity, 1) !== 0.00)
          {
            $lotStockAvailable = number_format($finalquantity, 2, '.', '');
          } else { 
            $lotStockAvailable = $finalquantity; 
          }

// echo $lotStockAvailable;

    $responseData = array(
        'cocNo' => $coc,
        'lotStock' => $lotStockAvailable,
    );
    echo json_encode($responseData);

    }
    else { echo 'Not Found';}
}
?>