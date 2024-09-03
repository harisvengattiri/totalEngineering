<?php include "../config.php"; ?>
<?php
session_start();
if(isset($_SESSION['userid']))
{	
   $po=$_GET['id'];
    
    $sql1 = "SELECT `qtn`,`order_referance` FROM `sales_order` WHERE id='$po'";
    
    if ($query1 = $conn->query($sql1))
    {
    // $query1 = mysqli_query($conn,$sql1);
    
        $result1 = mysqli_fetch_array($query1);
        $qtn = $result1['qtn'];
        $or = $result1['order_referance'];
        
        if($qtn != NULL)
        {
         $sql2 = "DELETE FROM `order_item` WHERE `item_id` = '$po'";
         $conn->query($sql2);
        
         $sql3 = "SELECT * FROM `quotation_item` WHERE `quotation_id` = '$qtn'";
         $query3 = mysqli_query($conn,$sql3);
         while($fetch3 = mysqli_fetch_array($query3)){
            $item = $fetch3['item'];
            $quantity = $fetch3['quantity'];
            $price = $fetch3['price'];
            $total = $fetch3['total'];
            
            $sql4 = "INSERT INTO `order_item`(`item_id`, `o_r`, `item`, `comment`, `quantity`, `unit`, `total`)
                     VALUES ('$po','$or','$item','','$quantity','$price','$total')";
            $conn->query($sql4);
            }
            
            $sql5 = "SELECT * FROM `quotation` WHERE `id` = '$qtn'";
            $query5 = mysqli_query($conn,$sql5);
            $fetch5 = mysqli_fetch_array($query5);
            $qtn_sub_total = $fetch5['subtotal'];
            $qtn_sub_total = ($qtn_sub_total != NULL) ? $qtn_sub_total : 0;
            $qtn_trans = $fetch5['trans'];
            $qtn_trans = ($qtn_trans != NULL) ? $qtn_trans : 0;
            $qtn_vat = $qtn_sub_total*0.05;
            $qtn_grand = $qtn_sub_total*1.05;
            
            $sql6 = "UPDATE `sales_order` SET `transport`='$qtn_trans',`sub_total`='$qtn_sub_total',`vat`='$qtn_vat',`grand_total`='$qtn_grand' WHERE id='$po'";
            $conn->query($sql6);
        
            // update salesorder table flag
            $sqlcheckorder = "SELECT sum(quantity) as purchase FROM order_item WHERE o_r='$or'";
            $querycheckorder = mysqli_query($conn,$sqlcheckorder);
            $resultorder = mysqli_fetch_array($querycheckorder);
            $purchase = $resultorder['purchase'];
            $purchase = ($purchase != NULL) ? $purchase : 0;

            $sqlchecksale = "SELECT sum(thisquantity) as sale FROM delivery_item WHERE order_referance='$or'";
            $querychecksale = mysqli_query($conn,$sqlchecksale);
            $resultsale = mysqli_fetch_array($querychecksale);
            $sale = $resultsale['sale'];
            $sale = ($sale != NULL) ? $sale : 0;

            if($sale >= $purchase) { $flag=1; } else { $flag=0; }
            $sql_flag = "UPDATE `sales_order` SET `flag` =  '$flag' WHERE order_referance='$or'";
            $conn->query($sql_flag);
        
        
          $date1=date("d/m/Y h:i:s a");
          $username=$_SESSION['username'];
          $code="PO".$po;
          $query=mysqli_real_escape_string($conn, $sql4);
          $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'edit', '$code', '$username', '$query')";
          $result = mysqli_query($conn, $sql);
          header("Location: $baseurl/sales_order_new?status=updated");
        }
        else 
        {
            header("Location: $baseurl/sales_order_new?status=failed1"); 
        }
}
else 
{   
    header("Location: $baseurl/sales_order_new?status=failed1"); 
}}
else
{
   header("Location:$baseurl/login/");
}
?>